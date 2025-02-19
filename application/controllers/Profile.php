<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'third_party/phpexcel/PHPExcel.php';
class Profile extends Frontend_Controller
{

    public function __construct()
    {
        parent::__construct();
        $data;
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }
        //~ else{
        //~ if($this->session->userdata('access')=='marchant'){

        //~ $emp=get_employeecount('st_users',array('merchant_id' => $this->session->userdata('st_userid')));
        //~ if(empty($emp)){
        //~ $this->session->set_userdata(array('st_regMid'  => $this->session->userdata('st_userid')));
        //~ redirect(base_url('merchant/addemployee'));
        //~ }
        //~ }
        //~ }
        //~ // $this->load->library('image_lib');
        $this->load->library('upload');
        $this->load->library('image_moo');
        $this->lang->load('salon_dashboard','german');

        $usid = $this->session->userdata('st_userid');
        if (!empty($usid)) {
            $status = getstatus_row($usid);
            if ($status != 'active') {
                redirect(base_url('auth/logouts/') . $status);
            }
        }
        //$this->load->model('Ion_auth_model','ion_auth');
    }

    //**** Edit user profile ****//
    public function edit_user_profile()
    {
        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }

        $field = "id,first_name,last_name,address,email,mobile,dob,address,zip,country,city,gender,profile_pic,latitude,longitude,newsletter,service_email,notification_status";

        if (isset($_POST['frmUpdate'])) {
            extract($_POST);
            $insertArr = array();
            $insertArr['first_name'] = $first_name;
            $insertArr['last_name'] = $last_name;
            $insertArr['mobile'] = $telephone;
            $insertArr['address'] = $location;
            $insertArr['country'] = $country;
            $insertArr['city'] = $city;

            if (!empty($dob)) {
                $insertArr['dob'] = date('Y-m-d', strtotime($dob));
            } else {
                $insertArr['dob'] = "";
            }

            $insertArr['zip'] = $post_code;
            $insertArr['latitude'] = $latitude;
            $insertArr['longitude'] = $longitude;
            $insertArr['gender'] = $gender;
            $insertArr['updated_on'] = date('Y-m-d H:i:s');

            $uid = $this->session->userdata('st_userid');
            $upload_path = 'assets/uploads/users/' . $uid . '/';

            $filepath = 'assets/uploads/profile_temp/' . $this->session->userdata('st_userid') . '/';

            @mkdir($upload_path, 0777, true);
            $filepath2 = $upload_path;

            $images = scandir($filepath);
            // echo "<pre>"; print_r($images); die;
            $nimages = '';
            $InserData = array();

            for ($i = 2; $i < count($images); $i++) {if (file_exists($filepath . $images[$i])) {
                echo file_exists($filepath . $images[$i]);
                rename($filepath . $images[$i], $filepath2 . $images[$i]);
                $nimages = $images[$i];

            }
            }
            if (!empty($nimages)) {

                $filename = explode('.', $nimages);
                $fextention = $filename[count($filename) - 1];

                $insertArr['profile_pic'] = $nimages;
                $this->image_moo->load($filepath2 . $nimages)->resize(250, 250)->save($filepath2 . 'thumb_' . $nimages, true);

                // resize with slider resolution
                $this->image_moo->load($filepath2 . $nimages)->resize(115, 115)->save($filepath2 . 'icon_' . $nimages, true);

                $filepath3 = $upload_path . 'thumb_' . $nimages;
                $filepath2 = $upload_path . 'icon_' . $nimages;
                $filepath1 = $upload_path . $nimages;

                if (strtolower($fextention) != 'webp') {
                    /*****************************************/
                    $image1 = imagecreatefromstring(file_get_contents($filepath1));
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

                    $image2 = imagecreatefromstring(file_get_contents($filepath2));
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

                    $image3 = imagecreatefromstring(file_get_contents($filepath3));
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
            if (!empty($_POST['old_img']) && !empty($nimages) && file_exists($pathck . $_POST['old_img'])) {

                $del_file = $pathck . $_POST['old_img'];
                //echo $del_file; die;
                unlink($del_file);
                if (file_exists($pathck . 'icon_' . $_POST['old_img'])) {
                    unlink($pathck . 'icon_' . $_POST['old_img']);
                }

                if (file_exists($pathck . 'thumb_' . $_POST['old_img'])) {
                    unlink($pathck . 'thumb_' . $_POST['old_img']);
                }

                if (file_exists($del_file . '.webp')) {
                    unlink($del_file . '.webp');
                }

                if (file_exists($pathck . 'icon_' . $_POST['old_img'] . '.webp')) {
                    unlink($pathck . 'icon_' . $_POST['old_img'] . '.webp');
                }

                if (file_exists($pathck . 'thumb_' . $_POST['old_img'] . '.webp')) {
                    unlink($pathck . 'thumb_' . $_POST['old_img'] . '.webp');
                }

            }

            if ($this->user->update('st_users', $insertArr, array('id' => $this->session->userdata('st_userid')))) {
                $this->session->set_userdata('sty_fname', $first_name);
                $this->session->set_flashdata('success', 'Änderungen erfolgreich gespeichert');
            } else {
                $this->session->set_flashdata('error', 'There is some technical error.');
            }

            redirect(base_url('profile/edit_user_profile'));
        }

        $this->data['userdetail'] = $this->user->select_row('st_users', $field, array('id' => $this->session->userdata('st_userid')));
        $this->data['title'] = 'Mein Profil';
        $this->data['is_edit_profile'] = true;
        $this->load->view('frontend/user/edit_profile', $this->data);
    }

    //**** Upload Banner Image *****//
    public function upload_banner_img()
    {

        $uid = $this->session->userdata('st_userid');
        $data = $_POST["image"];
        $image_array_1 = explode(";", $data);
        //print_r($image_array_1); die;
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);
        $path = "assets/uploads/banners/{$uid}/";
        //unlink($path."crop_".$_POST["name"]);
        @mkdir($path, 0777, true);

        $name = 'banner_' . time() . '.png';
        $imageName = $path . $name;
        //$imageName2 =$path.'prof_'.$name;
        file_put_contents($imageName, $data);

        $fextention = 'png';

        //    $image_info = $this->upload->data();
        array_insert($config, array('image_library' => 'gd2', 'source_image' => $path . $name, 'maintain_ratio' => false));
        ini_set('memory_limit', '128M');
        $arr = getimagesize($path . $name);
        //print_r($arr); die;
        if ($arr[0] >= 672) {
            $widht = 672;
        } else {
            $widht = $arr[0];
        }

        if ($arr[1] >= 448) {
            $higt = 448;
        } else {
            $higt = $arr[1];
        }

        // resize with orginal resolution
        $this->image_moo->load($path . $name)->resize($arr[0], $arr[1])->save($path . $name, true);

        // resize with slider resolution
        $this->image_moo->load($path . $name)->resize_crop($widht, $higt)->save($path . 'crop_' . $name, true);

        $this->image_moo->load($path . $name)->resize_crop(350, 202)->save($path . 'prof_' . $name, true);
        //print $this->image_moo->display_errors(); die;
        $filepath3 = $path . 'crop_' . $name;
        $filepath2 = $path . 'prof_' . $name;
        $filepath1 = $path . $name;

        if (strtolower($fextention) != 'webp') {
            //------------------------------------------------------------//
            $image1 = imagecreatefromstring(file_get_contents($filepath1));
            ob_start();
            imagejpeg($image1, null, 100);
            $cont1 = ob_get_contents();
            ob_end_clean();
            imagedestroy($image1);
            $content1 = imagecreatefromstring($cont1);

            $output1 = $path . $name . '.webp';

            imagewebp($content1, $output1);
            imagedestroy($content1);

            //------------------------------------------------------------//

            $image2 = imagecreatefromstring(file_get_contents($filepath2));
            ob_start();
            imagejpeg($image2, null, 100);
            $cont2 = ob_get_contents();
            ob_end_clean();
            imagedestroy($image2);
            $content2 = imagecreatefromstring($cont2);

            $output2 = $path . 'prof_' . $name . '.webp';

            imagewebp($content2, $output2);
            imagedestroy($content2);

            //------------------------------------------------------------//

            $image3 = imagecreatefromstring(file_get_contents($filepath3));
            ob_start();
            imagejpeg($image3, null, 100);
            $cont3 = ob_get_contents();
            ob_end_clean();
            imagedestroy($image3);
            $content3 = imagecreatefromstring($cont3);

            $output3 = $path . 'crop_' . $name . '.webp';

            imagewebp($content3, $output3);
            imagedestroy($content3);

            // $uploadPath = "assets/uploads/banners/{$uid}/webp";
            // $uploadPath1 = "assets/uploads/banners/{$uid}/other";
        } else {
            $content1 = imagecreatefromwebp($filepath1);
            $output1 = $path . $name . '.png';
            // Convert it to a jpeg file with 100% quality
            imagepng($content1, $output1);
            imagedestroy($content1);

            //------------------------------------------------------------//

            $content2 = imagecreatefromwebp($filepath2);
            $output2 = $path . 'prof_' . $name . '.png';
            // Convert it to a jpeg file with 100% quality
            imagepng($content2, $output2);
            imagedestroy($content2);

            //------------------------------------------------------------//

            $content3 = imagecreatefromwebp($filepath3);
            $output3 = $path . 'crop_' . $name . '.png';
            // Convert it to a jpeg file with 100% quality
            imagepng($content3, $output3);
            imagedestroy($content3);

        }

        //$this->image_lib->initialize($config)->resize();

        $banerdata = $this->user->select_row('st_banner_images', '*', array('user_id' => $this->session->userdata('st_userid')));

        $banner = array();

        if (empty($banerdata)) {
            $banner['image'] = $name;
            $banner['user_id'] = $this->session->userdata('st_userid');
            $banner['created_on'] = date("Y-m-d H:i:s");
            $banner['created_by'] = $this->session->userdata('st_userid');
            $this->user->insert('st_banner_images', $banner);

        } else {
            if (empty($banerdata->image)) {
                $banner['image'] = $name;
            } elseif (empty($banerdata->image1)) {
                $banner['image1'] = $name;
            } elseif (empty($banerdata->image2)) {
                $banner['image2'] = $name;
            } elseif (empty($banerdata->image3)) {
                $banner['image3'] = $name;
            } elseif (empty($banerdata->image4)) {
                $banner['image4'] = $name;
            }

            $banner['updated_by'] = date("Y-m-d H:i:s");
            $banner['updated_on'] = $this->session->userdata('st_userid');
            $this->user->update('st_banner_images', $banner, array('user_id' => $this->session->userdata('st_userid')));

        }

        if ($this->session->userdata('profile_status') == "gallery") {
            $insertUpds['profile_status'] = 'workinghour';
            $this->user->update('st_users', $insertUpds, array('id' => $this->session->userdata('st_userid')));
            $_SESSION['profile_status'] = "workinghour";
        }

        //$this->session->set_flashdata('success',$this->lang->line('image_uploaded_successfully'));

        $this->session->set_flashdata('success', 'Galeriebild erfolgreich bearbeitet');
        echo json_encode(['success' => 1, 'message' => '33Bild erfolgreich hochgeladen.', 'return_url' => $path . $name]);
    }

    public function gal_upload_banner_img()
    {

        $uid = $this->session->userdata('st_userid');
        $data = $_POST["image"];
        $image_array_1 = explode(";", $data);
        //print_r($image_array_1); die;
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);
        $path = "assets/uploads/banners/{$uid}/";
        //unlink($path."crop_".$_POST["name"]);
        @mkdir($path, 0777, true);

        $name = 'banner_' . time() . '.png';
        $imageName = $path . $name;
        //$imageName2 =$path.'prof_'.$name;
        file_put_contents($imageName, $data);

        $fextention = 'png';

        //    $image_info = $this->upload->data();
        array_insert($config, array('image_library' => 'gd2', 'source_image' => $path . $name, 'maintain_ratio' => false));
        ini_set('memory_limit', '128M');
        $arr = getimagesize($path . $name);
        //print_r($arr); die;
        if ($arr[0] >= 448) {
            $widht = 448;
        } else {
            $widht = $arr[0];
        }

        if ($arr[1] >= 672) {
            $higt = 672;
        } else {
            $higt = $arr[1];
        }

        // resize with orginal resolution
        $this->image_moo->load($path . $name)->resize($arr[0], $arr[1])->save($path . $name, true);

        // resize with slider resolution
        $this->image_moo->load($path . $name)->resize_crop($widht, $higt)->save($path . 'crop_' . $name, true);

        $this->image_moo->load($path . $name)->resize_crop(350, 202)->save($path . 'prof_' . $name, true);
        //print $this->image_moo->display_errors(); die;
        $filepath3 = $path . 'crop_' . $name;
        $filepath2 = $path . 'prof_' . $name;
        $filepath1 = $path . $name;

        if (strtolower($fextention) != 'webp') {
            //------------------------------------------------------------//
            $image1 = imagecreatefromstring(file_get_contents($filepath1));
            ob_start();
            imagejpeg($image1, null, 100);
            $cont1 = ob_get_contents();
            ob_end_clean();
            imagedestroy($image1);
            $content1 = imagecreatefromstring($cont1);

            $output1 = $path . $name . '.webp';

            imagewebp($content1, $output1);
            imagedestroy($content1);

            //------------------------------------------------------------//

            $image2 = imagecreatefromstring(file_get_contents($filepath2));
            ob_start();
            imagejpeg($image2, null, 100);
            $cont2 = ob_get_contents();
            ob_end_clean();
            imagedestroy($image2);
            $content2 = imagecreatefromstring($cont2);

            $output2 = $path . 'prof_' . $name . '.webp';

            imagewebp($content2, $output2);
            imagedestroy($content2);

            //------------------------------------------------------------//

            $image3 = imagecreatefromstring(file_get_contents($filepath3));
            ob_start();
            imagejpeg($image3, null, 100);
            $cont3 = ob_get_contents();
            ob_end_clean();
            imagedestroy($image3);
            $content3 = imagecreatefromstring($cont3);

            $output3 = $path . 'crop_' . $name . '.webp';

            imagewebp($content3, $output3);
            imagedestroy($content3);

            // $uploadPath = "assets/uploads/banners/{$uid}/webp";
            // $uploadPath1 = "assets/uploads/banners/{$uid}/other";
        } else {
            $content1 = imagecreatefromwebp($filepath1);
            $output1 = $path . $name . '.png';
            // Convert it to a jpeg file with 100% quality
            imagepng($content1, $output1);
            imagedestroy($content1);

            //------------------------------------------------------------//

            $content2 = imagecreatefromwebp($filepath2);
            $output2 = $path . 'prof_' . $name . '.png';
            // Convert it to a jpeg file with 100% quality
            imagepng($content2, $output2);
            imagedestroy($content2);

            //------------------------------------------------------------//

            $content3 = imagecreatefromwebp($filepath3);
            $output3 = $path . 'crop_' . $name . '.png';
            // Convert it to a jpeg file with 100% quality
            imagepng($content3, $output3);
            imagedestroy($content3);

        }
        
        $banner = array();

        $banner['image'] = $name;
        $banner['merchant_id'] = $this->session->userdata('st_userid');
        $banner['employee_id'] = $_POST['emp'];
        $banner['category_id'] = $_POST['cat'];
        $banner['created_on'] = date("Y-m-d H:i:s");
        $banner['created_by'] = $this->session->userdata('st_userid');
        $banner['updated_on'] = date("Y-m-d H:i:s");
        $banner['updated_by'] = $this->session->userdata('st_userid');

        $this->user->insert('st_gallery_banner_images', $banner);

        $this->session->set_flashdata('success', 'Galeriebild erfolgreich bearbeitet');
        echo json_encode(['success' => 1, 'message' => '33Bild erfolgreich hochgeladen.', 'return_url' => $path . $name]);
    }

    //**** image cropper ****//
    public function imagecropp()
    {
        $uid = $this->session->userdata('st_userid');
        $data = $_POST["image"];
        $image_array_1 = explode(";", $data);
        //print_r($image_array_1); die;
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);
        $path = "assets/uploads/banners/{$uid}/";
        unlink($path . "crop_" . $_POST["name"]);
        @mkdir($path, 0777, true);
        $imageName = $path . 'crop_' . $_POST["name"];
        $imageName2 = $path . 'prof_' . $_POST["name"];
        file_put_contents($imageName, $data);
        $arry = array('image' => '<img src="' . base_url($imageName) . '" class="img-thumbnail" />', 'success' => '1');
        $this->image_moo->load($imageName)->resize_crop(672, 448)->save($imageName, true);
        $filename = explode('.', $_POST["name"]);

        $fextention = $filename[count($filename) - 1];

        $filepath3 = $imageName;
        $filepath2 = $imageName2;
        //$filepath1 = $config['upload_path'].$image_info['file_name'];

        if (strtolower($fextention) != 'webp') {

            //------------------------------------------------------------//

            $image2 = imagecreatefromstring(file_get_contents($filepath2));
            ob_start();
            imagejpeg($image2, null, 100);
            $cont2 = ob_get_contents();
            ob_end_clean();
            imagedestroy($image2);
            $content2 = imagecreatefromstring($cont2);

            $output2 = $path . 'prof_' . $_POST["name"] . '.webp';

            imagewebp($content2, $output2);
            imagedestroy($content2);

            //------------------------------------------------------------//

            $image3 = imagecreatefromstring(file_get_contents($filepath3));
            ob_start();
            imagejpeg($image3, null, 100);
            $cont3 = ob_get_contents();
            ob_end_clean();
            imagedestroy($image3);
            $content3 = imagecreatefromstring($cont3);

            $output3 = $path . 'crop_' . $_POST["name"] . '.webp';

            imagewebp($content3, $output3);
            imagedestroy($content3);

            // $uploadPath = "assets/uploads/banners/{$uid}/webp";
            // $uploadPath1 = "assets/uploads/banners/{$uid}/other";
            //$this->image_moo->load($imageName)->resize_crop(350,202)->save($imageName2,true);
            $this->session->set_flashdata('success', "Bild erfolgreich zugeschnitten und skaliert..");
            echo json_encode($arry);die;
        } else {

            //------------------------------------------------------------//

            $content2 = imagecreatefromwebp($filepath2);
            $output2 = $path . 'prof_' . $_POST["name"] . '.png';
            // Convert it to a jpeg file with 100% quality
            imagepng($content2, $output2);
            imagedestroy($content2);

            //------------------------------------------------------------//

            $content3 = imagecreatefromwebp($filepath3);
            $output3 = $path . 'crop_' . $_POST["name"] . '.png';
            // Convert it to a jpeg file with 100% quality
            imagepng($content3, $output3);
            imagedestroy($content3);

            //$this->image_moo->load($imageName)->resize_crop(350,202)->save($imageName2,true);
            $this->session->set_flashdata('success', "Bild erfolgreich zugeschnitten und skaliert..");
            echo json_encode($arry);die;

        }

    }

    //**** Profile Image Crop ****//
    public function profile_imagecropp()
    {
        $uid = $this->session->userdata('st_userid');
        $data = $_POST["image"];
        $image_array_1 = explode(";", $data);
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);
        $path = 'assets/uploads/profile_temp/' . $uid . '/';

        $files = glob($path . '*');
        //print_r($files); die; //get all file names
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
            //delete file
        }

        @mkdir($path, 0777, true);
        $imageName = $path . 'profile_' . time() . $this->session->userdata('user_id') . '.png';
        file_put_contents($imageName, $data);
        $arry = array('image' => base_url($imageName), 'status' => 'success');
        echo json_encode($arry);die;

    }

    //**** Edit employee profile *****//
    public function edit_employee_profile()
    {

        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }

        if (isset($_POST['frmUpdate'])) {
            extract($_POST);
            $insertUpd['first_name'] = $first_name;
            $insertUpd['last_name'] = $last_name;
            $insertUpd['mobile'] = $telephone;
            $insertUpd['email'] = $email;

            $uid = $this->session->userdata('st_userid');
            $upload_path = 'assets/uploads/employee/' . $uid . '/';

            $filepath = 'assets/uploads/profile_temp/' . $uid . '/';

            @mkdir($upload_path, 0777, true);
            $filepath2 = $upload_path;

            $images = scandir($filepath);
            // echo "<pre>"; print_r($images); die;
            $nimages = '';
            $InserData = array();

            for ($i = 2; $i < count($images); $i++) {if (file_exists($filepath . $images[$i])) {
                echo file_exists($filepath . $images[$i]);
                rename($filepath . $images[$i], $filepath2 . $images[$i]);
                $nimages = $images[$i];

            }
            }
            if (!empty($nimages)) {

                $filename = explode('.', $nimages);
                $fextention = $filename[count($filename) - 1];

                $insertUpd['profile_pic'] = $nimages;
                $this->session->set_userdata('sty_profile', 'thumb_' . $nimages);
                $this->image_moo->load($filepath2 . $nimages)->resize(250, 250)->save($filepath2 . 'thumb_' . $nimages, true);

                // resize with slider resolution
                $this->image_moo->load($filepath2 . $nimages)->resize(115, 115)->save($filepath2 . 'icon_' . $nimages, true);

                $filepath3 = $upload_path . 'thumb_' . $nimages;
                $filepath2 = $upload_path . 'icon_' . $nimages;
                $filepath1 = $upload_path . $nimages;

                if (strtolower($fextention) != 'webp') {
                    /*****************************************/
                    $image1 = imagecreatefromstring(file_get_contents($filepath1));
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

                    $image2 = imagecreatefromstring(file_get_contents($filepath2));
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

                    $image3 = imagecreatefromstring(file_get_contents($filepath3));
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
            if (!empty($_POST['old_img']) && !empty($nimages) && file_exists($pathck . $_POST['old_img'])) {

                $del_file = $pathck . $_POST['old_img'];
                //echo $del_file; die;
                unlink($del_file);
                if (file_exists($pathck . 'icon_' . $_POST['old_img'])) {
                    unlink($pathck . 'icon_' . $_POST['old_img']);
                }

                if (file_exists($pathck . 'thumb_' . $_POST['old_img'])) {
                    unlink($pathck . 'thumb_' . $_POST['old_img']);
                }

                if (file_exists($del_file . '.webp')) {
                    unlink($del_file . '.webp');
                }

                if (file_exists($pathck . 'icon_' . $_POST['old_img'] . '.webp')) {
                    unlink($pathck . 'icon_' . $_POST['old_img'] . '.webp');
                }

                if (file_exists($pathck . 'thumb_' . $_POST['old_img'] . '.webp')) {
                    unlink($pathck . 'thumb_' . $_POST['old_img'] . '.webp');
                }

            }

            if ($this->user->update('st_users', $insertUpd, array('id' => $uid))) {
                $this->session->set_userdata('sty_fname', $first_name);
                $this->session->set_flashdata('success', 'Profile updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'There is some technical error.');
            }
            redirect(base_url('profile/edit_employee_profile'));
        }
        $field = "id,first_name,last_name,address,email,mobile,address,zip,country,city,gender,profile_pic,latitude,longitude";
        $this->data['userdetail'] = $this->user->select_row('st_users', $field, array('id' => $this->session->userdata('st_userid')));

        $this->load->view('frontend/employee/edit_profile', $this->data);
    }

    //**** update banner image ****//
    public function update_banner($image)
    {

        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }

        if (!empty($image)) {
            $firstImage = $this->user->select_row('st_banner_images', 'id,image', array('user_id' => $this->session->userdata('st_userid')));
            $insertUpd = array();
            $insertUpd['image'] = $image;

            if ($this->user->update('st_banner_images', $insertUpd, array('user_id' => $this->session->userdata('st_userid')))) {
                $secondUp = array();

                $secondUp[$_GET['field']] = $firstImage->image;

                $this->user->update('st_banner_images', $secondUp, array('user_id' => $this->session->userdata('st_userid')));

                $this->session->set_flashdata('success', $this->lang->line('succss_update_banner'));
            } else {
                $this->session->set_flashdata('error', 'There is some technical error.');
            }
            //redirect(base_url('profile/edit_marchant_profile?tab=gallery'));
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(base_url('profile/edit_marchant_profile'));
        }

        //redirect(base_url('profile/edit_marchant_profile?tab=gallery'));

    }

    //**** update banner image ****//
    public function update_banner_ajax($image)
    {

        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }

        if (!empty($image)) {
            $firstImage = $this->user->select_row('st_banner_images', 'id,image', array('user_id' => $this->session->userdata('st_userid')));
            $insertUpd = array();
            $insertUpd['image'] = $image;

            if ($this->user->update('st_banner_images', $insertUpd, array('user_id' => $this->session->userdata('st_userid')))) {
                $secondUp = array();

                $secondUp[$_GET['field']] = $firstImage->image;

                $this->user->update('st_banner_images', $secondUp, array('user_id' => $this->session->userdata('st_userid')));

                //$this->session->set_flashdata('success','Banner updated successfully.');
                $arrRes = array('success' => 1, 'msg' => 'Banner updated successfully.');
            } else {
                //$this->session->set_flashdata('error','There is some technical error.');
                $arrRes = array('success' => 0, 'msg' => 'There is some technical error.');
            }
            //redirect(base_url('profile/edit_marchant_profile?tab=gallery'));
            //redirect($_SERVER['HTTP_REFERER']);
            echo json_encode($arrRes);die;
        } else {
            redirect(base_url('profile/edit_marchant_profile'));
        }

        //redirect(base_url('profile/edit_marchant_profile?tab=gallery'));

    }
//**** Delete banner image ****//
    public function delete_gal_banner_image($image)
    {
        if (empty($this->session->userdata('st_userid'))) {
            echo '0';die;
        }

        if (!empty($image)) {
            $uid = $this->session->userdata('st_userid');
            $img = url_decode($image);
            $this->user->delete('st_gallery_banner_images', array('id' => $img));

            $path = 'assets/uploads/banners/' . $uid . '/';

            if (file_exists($path . 'crop_' . $img)) {
                unlink($path . 'crop_' . $img);
            }

            if (file_exists($path . 'prof_' . $img)) {
                unlink($path . 'prof_' . $img);
            }

            if (file_exists($path . $img)) {
                unlink($path . $img);
            }

            if (file_exists($path . 'crop_' . $img . '.webp')) {
                unlink($path . 'crop_' . $img . '.webp');
            }

            if (file_exists($path . 'prof_' . $img . '.webp')) {
                unlink($path . 'prof_' . $img . '.webp');
            }

            if (file_exists($path . $img . '.webp')) {
                unlink($path . $img . '.webp');
            }
        }
        echo '1';
    }
    public function delete_banner_image($image)
    {

        if (empty($this->session->userdata('st_userid'))) {
            echo '0';die;
        }

        if (!empty($image)) {
            $uid = $this->session->userdata('st_userid');
            $secondUp = array();

            $secondUp[$_GET['field']] = '';

            if ($this->user->update('st_banner_images', $secondUp, array('user_id' => $this->session->userdata('st_userid')))) {
                $path = 'assets/uploads/banners/' . $uid . '/';

                if (file_exists($path . 'crop_' . $image)) {
                    unlink($path . 'crop_' . $image);
                }

                if (file_exists($path . 'prof_' . $image)) {
                    unlink($path . 'prof_' . $image);
                }

                if (file_exists($path . $image)) {
                    unlink($path . $image);
                }

                if (file_exists($path . 'crop_' . $image . '.webp')) {
                    unlink($path . 'crop_' . $image . '.webp');
                }

                if (file_exists($path . 'prof_' . $image . '.webp')) {
                    unlink($path . 'prof_' . $image . '.webp');
                }

                if (file_exists($path . $image . '.webp')) {
                    unlink($path . $image . '.webp');
                }

                $this->session->set_flashdata('success', 'Image deleted successfully.');
            } else {
                $this->session->set_flashdata('error', 'There is some technical error.');
            }
            echo '1';die;
        } else {
            echo '1';
        }
        die;

    }

//**** Delete profile image ****//
    public function delete_profile_image()
    {

        if (empty($this->session->userdata('st_userid'))) {
            redirect(base_url('auth/login'));
        }

        $uid = $this->session->userdata('st_userid');
        if (!empty($_POST['id'])) {
            $uid = $_POST['id'];
        }

        if ($this->session->userdata('access') == 'employee') {
            $path = 'assets/uploads/employee/' . $uid . '/';
        } else {
            $path = 'assets/uploads/users/' . $uid . '/';
        }

        $secondUp = array();
        $secondUp['profile_pic'] = '';

        if ($this->user->update('st_users', $secondUp, array('id' => $uid))) {

            $files = glob($path . '*');
            //print_r($files); die; //get all file names
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
                //delete file
            }
            $_SESSION['sty_profile'] = '';
            $this->session->set_flashdata('success', 'Image deleted successfully.');

        } else {
            $this->session->set_flashdata('error', 'There is some technical error.');
        }
        echo '1';die;

    }

    //**** edit merchant profile ****//
    public function edit_marchant_profile()
    {
        //get_browserdetails();
        if (empty($this->session->userdata('st_userid')) || $this->session->userdata('access') != 'marchant') {
            $this->session->set_flashdata('error', 'There is some technical error.');
            redirect(base_url('auth/login'));
        } else {
            
            $field = "st_users.id,first_name,last_name,salon_email_setting,business_name,calendar_view,tax_number,auto_send_invoice,business_type,dob,cancel_booking_allow,hr_before_cancel,address,email,mobile,address,zip,latitude,longitude,country,city,notification_time,end_date,additional_notification_time,about_salon,online_booking,user_id,image,image1,image2,image3,image4,pinstatus,pin,email_text,notification_sound_setting,insta_link,fb_link,web_link,extra_hrs";
            $whr = array('st_users.id' => $this->session->userdata('st_userid'));
            $this->data['userdetail'] = $this->user->join_two('st_users', 'st_banner_images', 'id', 'user_id', $whr, $field);

            $this->data['user_available'] = $this->user->select('st_availability', 'days,starttime,endtime,type', array('user_id' => $this->session->userdata('st_userid')), '', 'id', 'ASC');

            
            $this->data['banerdata'] = $this->user->select_row('st_banner_images', '*', array('user_id' => $this->session->userdata('st_userid')));
            $this->data['employees'] = $this->user->select(
                'st_users',
                'id,first_name,last_name,profile_pic',
                [
                    'merchant_id' => $this->session->userdata('st_userid'),
                    'status !=' => 'deleted'
                ],
                '',
                'id',
                'desc'
            );
            $where = [
                'r.created_by' => $this->session->userdata('st_userid'),
                'r.status !=' => 'deleted',
                'r.parent_service_id' => 0
            ];
            $this->data['serviceList'] = $this->user->getservicelist($where);
            $this->data['is_edit_profile'] = true;
            
            $this->data['gbanerdata'] = $this->user->select('st_gallery_banner_images', '*', array('merchant_id' => $this->session->userdata('st_userid')));
            $this->load->view('frontend/marchant/edit_profile', $this->data);

        }
    }

    //**** update merchant profile ****//
    public function update_marchant_profile()
    {
        if (empty($this->session->userdata('st_userid')) || $this->session->userdata('access') != 'marchant') {
            $this->session->set_flashdata('error', 'There is some technical error.');
            redirect(base_url('auth/login'));
        } else {

            //  echo '<pre>';
            // print_r($_POST);
            // die();
            if (isset($_POST['first_name'])) {

                extract($_POST);

                $insertUpd = array();
                $insertUpd['first_name'] = $first_name;
                $insertUpd['last_name'] = $last_name;
                $insertUpd['mobile'] = $mobile;
                $insertUpd['address'] = $address;
                $insertUpd['country'] = $country;
                $insertUpd['city'] = $city;
                $insertUpd['email_text'] = $mail_text;
                $insertUpd['calendar_view'] = $calendar_view;
                $insertUpd['tax_number'] = $tax_number;
                $insertUpd['auto_send_invoice'] = (!empty($auto_send_invoice)) ? $auto_send_invoice : '0';
                $insertUpd['salon_email_setting'] = (!empty($salon_email_setting)) ? $salon_email_setting : '0';

                if (!empty($dob)) {
                    $insertUpd['dob'] = date('Y-m-d', strtotime($dob));
                } else {
                    $insertUpd['dob'] = "";
                }

                $checkEmail = $this->user->select_row('st_users', 'id', array('id !=' => $this->session->userdata('st_userid'), 'status !=' => 'deleted', 'email' => $email));

                if (empty($checkEmail->id)) {
                    $insertUpd['email'] = $email;
                }

                $insertUpd['insta_link'] = $insta_link;
                $insertUpd['fb_link'] = $fb_link;
                $insertUpd['web_link'] = $web_link;
                //$insertUpd['chk_online']   = $chk_online;
                $insertUpd['latitude'] = $latitude;
                $insertUpd['longitude'] = $longitude;
                $insertUpd['business_name'] = $business_name;
                $insertUpd['slug'] = create_slug($business_name);
                $insertUpd['business_type'] = $business_type;
                $insertUpd['notification_time'] = $reminder_hr;
                $insertUpd['additional_notification_time'] = $ad_reminder_hr;

                if (!empty($notification_sound_setting)) {
                    $insertUpd['notification_sound_setting'] = 1;
                    $this->session->set_userdata('sound_setting', 1);
                } else {
                    $insertUpd['notification_sound_setting'] = 0;
                    $this->session->set_userdata('sound_setting', 0);
                }

                $insertUpd['cancel_booking_allow'] = (!empty($cancel_booking_allow) && $cancel_booking_allow == 'yes') ? 'yes' : 'no';

                if (!empty($hr_before_cancel)) {
                    $insertUpd['hr_before_cancel'] = $hr_before_cancel;
                }

                $insertUpd['zip'] = $zip;
                //$insertUpd['online_booking']= isset($chk_online)?1:0;
                $insertUpd['about_salon'] = $about;
                $insertUpd['updated_on'] = date('Y-m-d H:i:s');

                $res = $this->user->update('st_users', $insertUpd, array('id' => $this->session->userdata('st_userid')));
                //die;
                if ($res) {

                    $days_array = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                    $postdays = $_POST['days'];
                    $i = 0;
                    foreach ($days_array as $day) {

                        $daydata = $this->user->select_row('st_availability', 'id', array('user_id' => $this->session->userdata('st_userid'), 'days' => $day));
                        if (empty($daydata)) {
                            if (in_array($day, $postdays)) {
                                $insertArr = array();
                                $insertArr['user_id'] = $this->session->userdata('st_userid');
                                $insertArr['days'] = $day;
                                $insertArr['type'] = 'open';
                                $insertArr['starttime'] = $_POST[$day . '_start'];
                                $insertArr['endtime'] = $_POST[$day . '_end'];
                                $insertArr['created_on'] = date('Y-m-d H:i:s');
                                $insertArr['created_by'] = $this->session->userdata('st_userid');
                            } else {
                                $insertArr = array();
                                $insertArr['user_id'] = $this->session->userdata('st_userid');
                                $insertArr['days'] = $day;
                                $insertArr['type'] = 'close';
                                //$insertArr['starttime']=$poststart[$i];
                                // $insertArr['endtime']=$postend[$i];
                                $insertArr['created_on'] = date('Y-m-d H:i:s');
                                $insertArr['created_by'] = $this->session->userdata('st_userid');

                            }
                            $this->user->insert('st_availability', $insertArr);
                        } else {
                            $getall_emp = $this->user->select('st_availability', 'user_id,days,starttime,endtime,starttime_two,endtime_two,type', array('user_id !=' => $this->session->userdata('st_userid'), 'created_by' => $this->session->userdata('st_userid'), 'days' => $day), '', 'id', 'ASC');

                            if (in_array($day, $postdays)) {
                                $updateArr = array();
                                $updateArr['user_id'] = $this->session->userdata('st_userid');
                                $updateArr['days'] = $day;
                                $updateArr['type'] = 'open';
                                $updateArr['starttime'] = $_POST[$day . '_start'];
                                $updateArr['endtime'] = $_POST[$day . '_end'];
                                //$updateArr['created_on']=date('Y-m-d H:i:s');
                                //$updateArr['created_by']=$this->session->userdata('st_userid');

                                if (!empty($getall_emp)) {
                                    foreach ($getall_emp as $emptm) {
                                        if ($emptm->type == 'open') {

                                            $start_1 = $emptm->starttime;
                                            $end_1 = $emptm->endtime;
                                            $start_2 = $emptm->starttime_two;
                                            $end_2 = $emptm->endtime_two;

                                            if ($_POST[$day . '_start'] > $start_1) {
                                                $start_1 = $_POST[$day . '_start'];
                                            }

                                            if ($_POST[$day . '_end'] < $end_1) {
                                                $end_1 = $_POST[$day . '_end'];
                                            }

                                            if ($_POST[$day . '_end'] <= $start_2) {
                                                $start_2 = '';
                                                $end_2 = '';
                                            } else if ($_POST[$day . '_end'] < $end_2) {
                                                $end_2 = $_POST[$day . '_end'];
                                            }

                                            $this->user->update('st_availability', array('starttime' => $start_1, 'endtime' => $end_1, 'starttime_two' => $start_2, 'endtime_two' => $end_2), array('user_id' => $emptm->user_id, 'days' => $day));

                                        }
                                    }

                                }

                            } else {
                                $updateArr = array();
                                $updateArr['user_id'] = $this->session->userdata('st_userid');
                                $updateArr['days'] = $day;
                                $updateArr['type'] = 'close';
                                $updateArr['starttime'] = "";
                                $updateArr['endtime'] = "";
                                $employee = $this->user->select('st_users', 'id', array('merchant_id' => $this->session->userdata('st_userid')), '', 'id', 'ASC');
                                if (!empty($employee)) {
                                    foreach ($employee as $emp) {

                                        $this->user->update('st_availability', array('type' => 'close', 'starttime' => '', 'endtime' => '', 'starttime_two' => '', 'endtime_two' => ''), array('user_id' => $emp->id, 'days' => $day));
                                    }
                                }

                            }

                            $this->user->update('st_availability', $updateArr, array('user_id' => $this->session->userdata('st_userid'), 'days' => $day));

                        }
                        $i++;
                    }

                    $this->session->set_flashdata('success', $this->lang->line('succss_update_salonprofile'));

                    if (!empty($shift_check)) {
                        redirect(base_url('merchant/employee_shift'));
                    } else {
                        redirect(base_url('profile/edit_marchant_profile'));
                    }

                    //echo json_encode(['success'=>'1','message'=>'Profile updated successfully.']); die;
                } else {
                    $this->session->set_flashdata('error', 'There is some technical error.');
                    redirect(base_url('profile/edit_marchant_profile'));
                    //echo json_encode(['success'=>'0','message'=>'There is some technical error.']); die;
                }
                //print_r($_POST);

            }
        }
    }

//**** client ( user ) view ****//
    public function clientview()
    {

        if (empty($this->session->userdata('st_userid')) || $this->session->userdata('access') != 'marchant') {
            // $this->session->set_flashdata('error','There is some technical error.');
            //redirect(base_url('auth/login'));
            echo json_encode(['success' => 0, 'page' => 'login']);die;
        } else {
            if (!empty($_POST['id'])) {
                $cid = url_decode($_POST['id']);
                $mid = $this->session->userdata('st_userid');
                $td = date('Y-m-d');
                $query = "SELECT st_users.id,first_name,last_name,dob,st_users.gender,profile_pic,st_users.email,mobile,address,zip,country,city,st_users.created_on,temp_user,(select notes from st_usernotes WHERE user_id=st_users.id and created_by = " . $mid . ") as notes,(select id from st_usernotes WHERE user_id=st_users.id and created_by = " . $mid . ") as note_id,count(st_booking.id) as totalbook,(SELECT count(id) FROM st_booking WHERE user_id=" . $cid . " AND employee_id != -1 AND merchant_id=" . $mid . " AND status='completed') as totalcomplete,(SELECT SUM(total_price) FROM st_booking WHERE user_id=" . $cid . " AND employee_id != -1 AND merchant_id=" . $mid . " AND status='completed') as totalrevenew,(SELECT count(id) FROM st_booking WHERE user_id=" . $cid . " AND employee_id != -1 AND merchant_id=" . $mid . " AND status='cancelled') as totalcanceled,(SELECT count(id) FROM st_booking WHERE user_id=" . $cid . " AND employee_id != -1 AND merchant_id=" . $mid . " AND status='no show') as totalnoshow,(SELECT count(id) FROM st_booking WHERE user_id=" . $cid . " AND employee_id != -1 AND merchant_id=" . $mid . " AND status='confirmed' AND DATE(st_booking.booking_time) >= '" . $td . "') as totalupcoming FROM st_users LEFT JOIN st_booking ON user_id=st_users.id AND st_booking.employee_id != -1 AND st_booking.merchant_id=" . $mid . " WHERE st_users.id=" . $cid . " AND st_users.status !='deleted' ORDER BY booking_time ASC";

                $this->data['userdata'] = $this->user->custome_query($query, 'row');

                $this->data['firstbookingdate'] = $this->user->custome_query("SELECT st_booking.created_on FROM st_booking WHERE user_id=" .$cid . " AND employee_id != -1 AND merchant_id=".$mid." ORDER BY created_on LIMIT 1", 'row');

                if (!empty($this->data['userdata']->id)) {

                    $blockQuery = "SELECT id FROM st_client_block WHERE client_id=" . $cid . " AND merchant_id=" . $mid;
                    $this->data['blockclient'] = $this->user->custome_query($blockQuery, 'row');
                    $this->data['bookingid'] = (!empty($_POST['bid']) ? $_POST['bid'] : '');
                    // echo "<pre>"; print_r($this->data); die;
                    $html = $this->load->view('frontend/marchant/profile/client_profile_view_popup', $this->data, true);
                    echo json_encode(['success' => 1, 'html' => $html]);die;

                } else {
                    echo json_encode(['success' => 0, 'msg' => '']);die;
                }
            } else {
                echo json_encode(['success' => 0, 'msg' => '']);die;

            }

        }
    }

//**** get client revenew with filter  ****//
    public function filter_client_revenew()
    {

        if (empty($this->session->userdata('st_userid')) || $this->session->userdata('access') != 'marchant') {
            // $this->session->set_flashdata('error','There is some technical error.');
            //redirect(base_url('auth/login'));
            echo json_encode(['success' => 0, 'page' => 'login']);die;
        } else {
            if (!empty($_POST['cid'])) {

                $where = "";
                if (!empty($_POST['filter'])) {
                    if ($_POST['filter'] == 'current_week') {
                        $monday = strtotime("last monday");

                        $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;

                        $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");

                        // $where1= "";

                        //date('Y-m-d', strtotime('-7 days'))
                        $start_date = date("Y-m-d", $monday);
                        $end_date = date("Y-m-d", $sunday);
                        $where1 = " AND DATE(booking_time) >= '" . $start_date . "' AND DATE(booking_time) <='" . $end_date . "'";

                    } else if ($_POST['filter'] == 'day') {
                        $start_date = date('Y-m-d');
                        $end_date = date('Y-m-t');
                        $start_date1 = date("Y-m-d");
                        $where1 = " AND DATE(booking_time) >= '" . $start_date1 . "' AND DATE(booking_time) <='" . $end_date . "'";
                    } else if ($_POST['filter'] == 'last_seven_day') {
                        $start_date = date('Y-m-d', strtotime('-7 days'));
                        $end_date = date('Y-m-t');
                        $start_date1 = date("Y-m-d");
                        $where1 = " AND DATE(booking_time) >= '" . $start_date1 . "' AND DATE(booking_time) <='" . $end_date . "'";
                    } else if ($_POST['filter'] == 'current_month') {
                        $start_date = date('Y-m-01');
                        $end_date = date('Y-m-t');
                        $start_date1 = date("Y-m-d");
                        $where1 = " AND DATE(booking_time) >= '" . $start_date1 . "' AND DATE(booking_time) <='" . $end_date . "'";
                    } else if ($_POST['filter'] == 'last_month') {
                        $start_date = date('Y-m-01', strtotime('last month'));
                        $end_date = date('Y-m-t', strtotime('last month'));
                        $start_date1 = date("Y-m-d");
                        $where1 = " AND DATE(booking_time) >= '" . $start_date1 . "' AND DATE(booking_time) <='" . $end_date . "'";
                    } else if ($_POST['filter'] == 'current_year') {
                        $start_date = date('Y-01-01');

                        //date("Y-m-d");
                        $end_date = date('Y-12-31');
                        $start_date1 = date("Y-m-d");
                        $where1 = " AND DATE(booking_time) >= '" . $start_date1 . "' AND DATE(booking_time) <='" . $end_date . "'";
                    } else if ($_POST['filter'] == 'last_year') {
                        $start_date = date('Y-01-01', strtotime('last year'));
                        $end_date = date('Y-12-01', strtotime('last year'));
                        $start_date1 = date("Y-m-d");
                        $where1 = " AND DATE(booking_time) >= '" . $start_date1 . "' AND DATE(booking_time) <='" . $end_date . "'";
                    } else {
                        $start_date = date('Y-m-d');
                        $end_date = date('Y-m-d');
                        $start_date1 = date("Y-m-d");
                        $where1 = " AND DATE(booking_time) >= '" . $start_date1 . "' AND DATE(booking_time) <='" . $end_date . "'";
                    }
                    if (!empty($start_date) && !empty($end_date)) {
                        $start_date1 = date("Y-m-d");
                        $where = " AND DATE(booking_time) >= '" . $start_date . "' AND DATE(booking_time) <='" . $end_date . "'";
                        $where1 = " AND DATE(booking_time) >= '" . $start_date1 . "' AND DATE(booking_time) <='" . $end_date . "'";
                        //$where=$where;
                    }

                }

                $cid = url_decode($_POST['cid']);
                $mid = $this->session->userdata('st_userid');
                $td = date('Y-m-d');
                $query = "SELECT count(st_booking.id) as totalbook,
		  (SELECT count(id) FROM st_booking WHERE user_id=" . $cid . " AND employee_id != -1 AND merchant_id=" . $mid . " AND status='completed' " . $where . ") as totalcomplete,
		  (SELECT SUM(total_price) FROM st_booking WHERE user_id=" . $cid . " AND employee_id != -1 AND merchant_id=" . $mid . " AND status='completed' " . $where . ") as totalrevenew,
		  (SELECT count(id) FROM st_booking WHERE user_id=" . $cid . " AND employee_id != -1 AND merchant_id=" . $mid . " AND status='cancelled' " . $where . ") as totalcanceled,
		  (SELECT count(id) FROM st_booking WHERE user_id=" . $cid . " AND employee_id != -1 AND merchant_id=" . $mid . " AND status='no show' " . $where . ") as totalnoshow,
		  (SELECT count(id) FROM st_booking WHERE user_id=" . $cid . " AND employee_id != -1 AND merchant_id=" . $mid . " AND status='confirmed'" . $where1 . ") as totalupcoming
		  FROM st_booking WHERE  merchant_id=" . $mid . " AND employee_id != -1 AND user_id=" . $cid . $where;

                $userdata = $this->user->custome_query($query, 'row');
                //echo $this->db->last_query();die;
                $totalrevenew = "0 €";

                if (!empty($userdata->totalrevenew)) {
                    $totalrevenew = number_format($userdata->totalrevenew, 2, '.', '') . ' €';
                }

                $totalbook = "0";

                if (!empty($userdata->totalbook)) {
                    $totalbook = $userdata->totalbook;
                }

                $totalcomplete = "0";

                if (!empty($userdata->totalcomplete)) {
                    $totalcomplete = $userdata->totalcomplete;
                }

                $totalupcoming = "0";

                if (!empty($userdata->totalupcoming)) {
                    $totalupcoming = $userdata->totalupcoming;
                }

                $totalcanceled = "0";

                if (!empty($userdata->totalcanceled)) {
                    $totalcanceled = $userdata->totalcanceled;
                }
                $totalnoshow = "0";

                if (!empty($userdata->totalnoshow)) {
                    $totalnoshow = $userdata->totalnoshow;
                }
                // echo $this->db->last_query().'<pre>'; print_r($this->data['userdata']);
                echo json_encode(['success' => 1, 'revenew' => $totalrevenew, 'totalbook' => $totalbook, 'totalcomplete' => $totalcomplete, 'totalupcoming' => $totalupcoming, 'totalcanceled' => $totalcanceled, 'totalnoshow' => $totalnoshow]);
                /* $blockQuery  = "SELECT id FROM st_client_block WHERE client_id=".$cid." AND merchant_id=".$mid;
            $this->data['blockclient'] = $this->user->custome_query($blockQuery,'row');
            $this->data['bookingid']= (!empty($_POST['bid'])?$_POST['bid']:'');
            // echo "<pre>"; print_r($this->data); die;
            $html = $this->load->view('frontend/marchant/profile/client_profile_view_popup',$this->data,true);
            echo json_encode(['success'=>1,'html'=>$html]); die;*/
            } else {
                echo json_encode(['success' => 0, 'msg' => '']);die;

            }

        }
    }

    //**** client booking list ****//
    public function client_booking_list($id = '', $page = '0')
    {

        $pa = $this->uri->segment(4);
        // print_r($pa);
        if (!empty($id)) {
            $cid = url_decode($id);
            $mid = $this->session->userdata('st_userid');

            $where = array('user_id' => $cid, 'st_booking.merchant_id' => $mid, 'st_booking.employee_id !=' => '-1');
            if (isset($_POST['order'])) {
                $order = $_POST['order'];
            }

            if (!empty($_GET['short'])) {
                if ($_GET['short'] == 'current_week') {
                    $monday = strtotime("last monday");
                    $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
                    $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
                    $start_date = date("Y-m-d", $monday);
                    $end_date = date("Y-m-d", $sunday);
                } else if ($_GET['short'] == 'current_month') {
                    $start_date = date('Y-m-01');
                    $end_date = date('Y-m-t');
                } else if ($_GET['short'] == 'last_month') {
                    $start_date = date('Y-m-01', strtotime('last month'));
                    $end_date = date('Y-m-t', strtotime('last month'));
                } else if ($_GET['short'] == 'current_year') {
                    $start_date = date('Y-01-01');
                    $end_date = date('Y-12-01');
                } else if ($_GET['short'] == 'last_year') {
                    $start_date = date('Y-01-01', strtotime('last year'));
                    $end_date = date('Y-12-01', strtotime('last year'));
                } else if ($_GET['short'] == 'date') {
                    if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                        //   print_r($_GET['start_date']); echo '- end';
                        //   print_r($_GET['end_date']);
                        //   print_r();
                        $st = explode(".", $_GET['start_date']);
                        $en = explode(".", $_GET['end_date']);

                        $start_date = $st[2] . '-' . $st[1] . '-' . $st[0];
                        $end_date = $en[2] . '-' . $en[1] . '-' . $en[0];
                        // $date = DateTime::createFromFormat('d/m/Y', $_GET['start_date']);
                        // $start_date= $date->format('Y-m-d');
                        // $date1 = DateTime::createFromFormat('d/m/Y', $_GET['end_date']);
                        // $end_date= $date1->format('Y-m-d');
                    }

                } else {
                    $start_date = date('Y-m-d');
                    $end_date = date('Y-m-d');
                }
                if (!empty($start_date) && !empty($end_date)) {
                    $whr = array('DATE(st_booking.booking_time) >=' => $start_date, 'DATE(st_booking.booking_time) <=' => $end_date);
                    $where = $where + $whr;
                }

            }

            if (!empty($_GET['status'])) {
                if ($_GET['status'] == 'upcoming') {
                    $td = date('Y-m-d');
                    $whr = array('DATE(st_booking.booking_time) >=' => $td, 'st_booking.status' => 'confirmed');
                    $where = $where + $whr;
                } else if ($_GET['status'] == 'recent') {
                    /*$whr = array('st_booking.status' => 'completed');
                    $where=$where+$whr;*/
                    $td = date('Y-m-d H:i:s');
                    $whr1 = '(st_booking.status="completed" OR st_booking.status="cancelled" OR (st_booking.status="confirmed" AND st_booking.booking_time<= "' . $td . '"))';
                } else if ($_GET['status'] == 'cancelled') {
                    $whr1 = '(st_booking.status="cancelled" OR st_booking.status="no_show")';
                }

            } else {
                $td = date('Y-m-d');
                $whr = array('DATE(st_booking.booking_time) >=' => $td, 'st_booking.status' => 'confirmed');
                $where = $where + $whr;
            }

            if ($order == "") {
                $order = "booking_time DESC";
            }

            if (!empty($whr1)) {
                $this->db->where($whr1);
            }

            $totalcount = $this->user->getbookinglist($where, 0, 0, 'employee_id', $order);

            if (!empty($totalcount)) {
                $total = count($totalcount);
//print_r($total);
            } else {
                $total = 0;
            }

            //    if($_POST['limit']=='all'){
            //         $limit =$total;
            //     }else{
            //        $limit = isset($_POST['limit'])?$_POST['limit']:20;    //PER_PAGE10
            //      }
            if ($pa != 0) {
                $limit = isset($_POST['limit']) ? $_POST['limit'] : 20; //PER_PAGE10
                // print_r($offset);
            } else {
                if ($_POST['limit'] == 'all') {
                    $limit = $total;
                } else {
                    $limit = isset($_POST['limit']) ? $_POST['limit'] : 20; //PER_PAGE10
                }
            }
            $url = 'profile/client_booking_list/' . $id;
            $segment = 4;
            //$page = mypaging($url,$total,$segment,$limit);
            // print_r($page);
            $offset = 0;
            if ($page != 0) {
                $offset = ($page - 1) * $limit;
                // print_r($offset);
            }
            $config = array();
            $config["base_url"] = base_url() . $url;
            $config["total_rows"] = $total;
            $config["per_page"] = $limit;
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
            $config['reuse_query_string'] = true;
            $config["uri_segment"] = $segment;

            $this->pagination->initialize($config);

            $pagination = $this->pagination->create_links();

            if (!empty($whr1)) {
                $this->db->where($whr1);
            }

            $booking = $this->user->getbookinglist($where, $config["per_page"], $offset, 'employee_id', $order);
            //echo $this->db->last_query().'<pre>';
            $html = "";
            if (!empty($booking)) {
                foreach ($booking as $row) {
                    $book_detail = $this->user->select('st_booking_detail', 'id,booking_id,service_id,service_name', array('booking_id' => $row->id), '', 'id', 'ASC');
                    //$sevices= get_servicename($row->id);
                    $sevices = '';
                    if (!empty($book_detail)) {foreach ($book_detail as $serv) {
                        $sub_name = get_subservicename($serv->service_id);
                        if ($sub_name == $serv->service_name) {
                            $sevices .= $serv->service_name . ',';
                        } else {
                            $sevices .= $sub_name . ' - ' . $serv->service_name . ',';
                        }

                    }
                    }
                    $recipUrl = "";
                    $up_time = new DateTime($row->updated_on);
                    $action_date = $up_time->format('d.m.Y');

                    // Completed =
                    // Receipt = Quittung
                    $cls = '';
                    $icon = '';
                    if ($row->status == 'confirmed') {
                        $cls = 'conform';
                        $newStatus = "Bestätigt";
                        $detalClass = "";
                        $txtDacoration = "text-decoration: none !important;";
                        $bk_class = "booking_row";
                        $recp = '';
                    } else if ($row->status == 'cancelled') {
                        $cls = 'cencel';
                        $newStatus = "Storniert";
                        $detalClass = "";
                        $txtDacoration = "text-decoration: none !important;";
                        $bk_class = "booking_row";
                        $recp = '';
                    } else if ($row->status == 'completed') {
                        $cls = 'completed';
                        $newStatus = "Abgeschlossen";
                        $detalClass = "";
                        $txtDacoration = "cursor:pointer;";
                        $bk_class = "";
                        $recp = 'Quittung';
                        $icon = ' <i class="fa fa-check" aria-hidden="true"></i>';
                        $recipUrl = base_url('checkout/viewinvoice/' . url_encode($row->invoice_id));
                    } else if ($row->status == 'no show') {
                        $cls = 'cencel';
                        $detalClass = "";
                        $txtDacoration = "text-decoration: none !important;";
                        $bk_class = "booking_row";
                        $recp = '';
                    }

                    if ($row->book_by == '0') {
                        $bookedvia = 'Web';
                    } else {
                        $bookedvia = 'App';
                    }

                    $saddress = $row->address . " <br/>" . $row->zip . " " . $row->city;

                    $html = $html . '<tr>
                      <td class="text-center font-size-14 height56v booking_row" id="' . url_encode($row->id) . '">' . date("d.m.Y", strtotime($row->booking_time)) . '</td>
                      <td class="text-center font-size-14 height56v booking_row" id="' . url_encode($row->id) . '">' . date("H.i", strtotime($row->booking_time)) . ' Uhr</td>
                      <td class="text-center font-size-14 height56v booking_row" id="' . url_encode($row->id) . '">' . $row->book_id . '</td>
                      <td class="text-center font-size-14 height56v color666 fontfamily-regular booking_row" id="' . url_encode($row->id) . '"><p class="vertical-meddile mb-0 display-ib" style="width: 240px;white-space: normal;">' . rtrim($sevices, ',') . '</p></td>
                      <td class="text-center height56v font-size-14 color666 fontfamily-regular booking_row" id="' . url_encode($row->id) . '">' . $row->total_minutes . ' Min.</td>
                      <td class="font-size-14 height56v color666 fontfamily-regular text-center" id="' . url_encode($row->id) . '">' . price_formate($row->total_price) . ' €</td>
                      <td class="text-center font-size-14 height56v booking_row" id="' . url_encode($row->id) . '">' . ($row->created_by == $row->merchant_id ? 'Salon': 'App') . '</td>
                      <td class="text-center height56v booking_row" id="' . url_encode($row->id) . '">
                        <span href="#" class="' . $cls . ' font-size-14 fontfamily-regular a_hover_red">' . $newStatus . $icon . '</span>
                        <span class="font-size-10 color666 fontfamily-regular display-b">am ' . $action_date . '</span>
                      </td>
                      <td class="text-center"><a style="' . $txtDacoration . '" href="' . $recipUrl . '" class="text-underline ' . $detalClass . ' color333">' . $recp . '</a></td>
                    </tr>';

                    //<a style="'.$txtDacoration.'" data-encode="'.url_encode($row->id).'" overflow_elips // css class ....... data-saddress="'.$saddress.'" data-bookedvia="'.$bookedvia.'" data-duration="'.$row->total_minutes.' Mins" data-price="'.$row->total_price.' €" data-id="'.$row->id.'" data-bookid="'.$row->book_id.'" data-time="'.date("d F Y, H : i",strtotime($row->booking_time)).'" data-complete ="'.date("d F Y, H : i",strtotime($row->updated_on)).'" data-service="'.rtrim($sevices, ',').'" data-salone="'.$row->business_name.'"  class="text-underline '.$detalClass.' color333">'.$recp.'</a>

                }

            } else {
                $html = '<tr><td colspan="9" style="text-align:center;"><div class="text-center pb-20 pt-50">
					  <img src="' . base_url('assets/frontend/images/no_listing.png') . '"><p style="margin-top: 20px;">' . $this->lang->line('dont_any_appointments') . '</p></div></td></tr>';

            }

            echo json_encode(array('success' => '1', 'msg' => '', 'html' => $html,
                'pagination' => $pagination));die;
            //echo "<pre>"; print_r($booking); die;
            // $this->load->view('frontend/marchant/client_profile_view',$this->data);
        } else {
            echo json_encode(array('success' => '0', 'msg' => '', 'html' => '', 'pagination' => ''));
        }
        die;

    }

    //**** Block user ****//
    public function client_block()
    {
        $cid = url_decode($_POST['uid']);
        $mid = $this->session->userdata('st_userid');
        $blockQuery = "SELECT id FROM st_client_block WHERE client_id=" . $cid . " AND merchant_id=" . $mid;
        $blockCheck = $this->user->custome_query($blockQuery, 'row');
        if (!empty($blockCheck->id)) {
            $delete = $this->db->query("DELETE FROM st_client_block WHERE client_id=" . $cid . " AND merchant_id=" . $mid);
            echo json_encode(array('success' => '1', 'text' => 'Blockieren'));die;
        } else {
            $data = array();
            $data['client_id'] = $cid;
            $data['merchant_id'] = $mid;
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['created_by'] = $mid;
            $insert = $this->db->insert('st_client_block', $data);
            echo json_encode(array('success' => '1', 'text' => 'Freigeben'));die;
        }

    }

//**** update client notes ****//
    public function update_notes()
    {

        $upd['notes'] = $_POST['notes'];
        $mid = $this->session->userdata('st_userid');
        $bid = url_decode($_POST['bid']);
        $uid = $_POST['uid'];
        if ($uid) {
            if ($this->user->countResult('st_usernotes', array('user_id' => $_POST['uid'], 'created_by' => $mid)) > 0) {
                $this->user->update('st_usernotes', $upd, array('user_id' => $_POST['uid'], 'created_by' => $mid));
            } else {
                $insert_note['user_id'] = $_POST['uid'];
                $insert_note['notes'] = $_POST['notes'];
                $insert_note['created_by'] = $mid;
                $insert_note['created_on'] = date('Y-m-d H:i:s');
                $this->user->insert('st_usernotes', $insert_note);
            }
        } else {
            $upd1['walkin_customer_notes'] = $_POST['notes'];
            $this->user->update('st_booking', $upd1, array('id' => $bid));
        }
        //$this->user->update('st_users',array('notes'=>$_POST['notes']),array('id'=>$_POST['uid']));
        if (strlen(strip_tags($_POST['notes'])) > 75) {
            $html = substr(strip_tags($_POST['notes']), 0, 75);
        } else {
            $html = strip_tags($_POST['notes']);
        }

        echo json_encode(array('success' => '1', 'msg' => '', 'notes' => $html, 'count' => strlen(strip_tags($_POST['notes']))));die;
    }

    //***** change time availability ******//
    public function change_time()
    {
        $mid = $this->session->userdata('st_userid');
        $day_nm = $_POST['day_nm'];
        $upd['starttime'] = $_POST['s_time'];
        $upd['endtime'] = $_POST['e_time'];
        $upd['type'] = 'open';

        if ($this->user->update('st_availability', $upd, array('user_id' => $mid, 'days' => $day_nm, 'created_by' => $mid))) {
            $stime = date("H:i", strtotime($_POST['s_time']));
            $etime = date("H:i", strtotime($_POST['e_time']));

            $employee = $this->user->select('st_users', 'id', array('merchant_id' => $mid), '', 'id', 'ASC');
            if (!empty($employee)) {
                foreach ($employee as $emp) {
                    $emp_time = $this->user->select_row('st_availability', 'id,user_id,starttime,endtime,starttime_two,endtime_two', array('user_id' => $emp->id, 'days' => $day_nm));
                    if (!empty($emp_time)) {
                        $updatetime = array();
                        $emp_start = $emp_time->starttime;
                        $emp_end = $emp_time->endtime;
                        $emp_stwo = $emp_time->starttime_two;
                        $emp_etwo = $emp_time->endtime_two;

                        if (($_POST['s_time'] >= $emp_start) && ($_POST['s_time'] >= $emp_end)) {
                            $updatetime['starttime'] = "";
                            $updatetime['endtime'] = "";
                            $updatetime['starttime_two'] = "";
                            $updatetime['endtime_two'] = "";
                        } else {
                            if ($_POST['s_time'] > $emp_start) {
                                $updatetime['starttime'] = $_POST['s_time'];
                            }

                            if ($_POST['e_time'] < $emp_end) {
                                $updatetime['endtime'] = $_POST['e_time'];
                            } else if ($_POST['e_time'] > $emp_stwo && $_POST['e_time'] < $emp_etwo) {
                                $updatetime['endtime_two'] = $_POST['e_time'];
                            }

                            if ($_POST['e_time'] <= $emp_stwo) {
                                $updatetime['starttime_two'] = "";
                                $updatetime['endtime_two'] = "";
                            }
                        }
                        $this->user->update('st_availability', $updatetime, array('user_id' => $emp_time->user_id, 'days' => $day_nm));

                    }

                }

            }

            echo json_encode(array('success' => '1', 'starttime' => $stime, 'endtime' => $etime));
        } else {
            echo json_encode(array('success' => '0'));
        }

    }

    //***** change time *****//
    public function get_change_time()
    {
        $mid = $this->session->userdata('st_userid');
        $day_nm = $_POST['day_nm'];
        $data = $this->user->select_row('st_availability', 'starttime,endtime', array('user_id' => $mid, 'created_by' => $mid, 'days' => $day_nm));
        if (!empty($data)) {
            $s_time = substr($data->starttime, 0, 2);
            $e_time = substr($data->endtime, 0, 2);
            echo json_encode(array('success' => '1', 'starttime' => (int) $s_time, 'setstart' => substr($data->starttime, 0, 5), 'endtime' => (int) $e_time, 'setend' => substr($data->endtime, 0, 5)));
        } else {
            echo json_encode(array('success' => '0'));
        }

    }

    //***** Change salon time status *****//
    public function salon_time_status()
    {
        $mid = $this->session->userdata('st_userid');
        $day_nm = $_POST['day_nm'];
        $insertUpd['starttime'] = '';
        $insertUpd['endtime'] = '';
        $insertUpd['type'] = 'close';
        $res = $this->user->update('st_availability', $insertUpd, array('user_id' => $mid, 'created_by' => $mid, 'days' => $day_nm));

        if (!empty($res)) {

            $employee = $this->user->select('st_users', 'id', array('merchant_id' => $mid), '', 'id', 'ASC');
            if (!empty($employee)) {
                foreach ($employee as $emp) {

                    $this->user->update('st_availability', array('type' => 'close', 'starttime' => '', 'endtime' => '', 'starttime_two' => '', 'endtime_two' => ''), array('user_id' => $emp->id, 'days' => $day_nm));
                }
            }

            echo json_encode(array('success' => '1'));
        } else {
            echo json_encode(array('success' => '0'));
        }

    }
    //**** Update salon online status ****//
    public function salon_online_status()
    {
        $mid = $this->session->userdata('st_userid');

        $salonDetails = $this->user->select_row('st_users', '*', array('id' => $mid));

        $dataCont = getmembership_exp($mid);

        if ($salonDetails->subscription_status == 'trial' && $_POST['online_status'] == 1 && $salonDetails->allow_online_booking == 'false') {
            echo json_encode(array('success' => 0, 'message' => 'Mit Online-Buchungen aktivierst du das volle Potential von styletimer! Entscheide dich für eine unserer Mitgliedschaften und lass‘ deine Kunden ihre Termine zukünftig einfach selbst per App oder Web buchen.'));
        } else if ($dataCont['expired'] && $_POST['online_status'] == 1 && $salonDetails->allow_online_booking == 'false') {
            echo json_encode(array('success' => 0, 'message' => 'Leider hast du aktuell kein aktives Abonnement. Um alle Funktionen von styletimer vollständig nutzen zu können, sichere dir jetzt eine unserer Mitgliedschaften!'));
        } else {
            if ($_POST['online_status'] == 1) {
                $this->session->set_userdata('online_booking', '');
            } else {
                $this->session->set_userdata('online_booking', 'yes');
            }
    
            $res = $this->user->update('st_users', array('online_booking' => $_POST['online_status']), array('id' => $mid));
    
            echo json_encode(array('success' => 1));
        }
    }

    public function salon_update_extra_hrs()
    {
        $mid = $this->session->userdata('st_userid');
        $res = $this->user->update('st_users', array('extra_hrs' => $_POST['hrs']), array('id' => $mid));
        if (!empty($res)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    //**** Update salon auto_send_invoice ****//
    public function salon_update_switches()
    {
        $mid = $this->session->userdata('st_userid');
        $res = $this->user->update('st_users', array($_POST['key'] => $_POST['value']), array('id' => $mid));
        if ($_POST['key'] == 'notification_sound_setting') {
            $this->session->set_userdata('sound_setting', intval($_POST['value']));
        }
        if (!empty($res)) {
            echo 1;
        } else {
            echo 0;
        }

    }
    // profile setup on first time salon login
    public function profile_setup()
    {
        if (empty($this->session->userdata('st_userid'))) {
            echo json_encode(['success' => 0, 'page' => 'login', 'msg' => '']);die;

        } elseif ($this->session->userdata('access') != 'marchant') {
            echo json_encode(['success' => 0, 'page' => '', 'msg' => 'As a user you can not update salon profile']);die;
        } else {
            //echo "<pre>"; print_r($_POST); die;
            if (!empty($_POST)) {
                // echo '<pre>'; print_r($_POST); die;
                extract($_POST);
                $insertUpd = array();
                $insertUpd['first_name'] = $first_name;
                $insertUpd['last_name'] = $last_name;
                $insertUpd['mobile'] = $mobile;
                $insertUpd['address'] = $address;
                $insertUpd['country'] = $country;
                $insertUpd['city'] = $city;
                // print_r($insertUpd['city']);
                $insertUpd['tax_number'] = $tax_number;

                if (!empty($dob)) {
                    $insertUpd['dob'] = date('Y-m-d', strtotime($dob));
                } else {
                    $insertUpd['dob'] = "";
                }

                $insertUpd['latitude'] = $latitude;
                $insertUpd['longitude'] = $longitude;
                $insertUpd['business_name'] = $business_name;
                //$insertUpd['slug']     =  replace_spec_char($business_name);
                // print_r($insertUpd['slug']);
                $insertUpd['business_type'] = $business_type;
                $insertUpd['cancel_booking_allow'] = (!empty($cancel_booking_allow) && $cancel_booking_allow == 'yes') ? 'yes' : 'no';
                if (!empty($hr_before_cancel)) {
                    $insertUpd['hr_before_cancel'] = $hr_before_cancel;
                }

                $insertUpd['notification_time'] = $reminder_hr;
                $insertUpd['additional_notification_time'] = $ad_reminder_hr;
                $insertUpd['zip'] = $zip;
                //$insertUpd['online_booking']= isset($chk_online)?1:0;
                $insertUpd['about_salon'] = $about;
                $insertUpd['fb_link'] = $fb_link;
                $insertUpd['web_link'] = $web_link;
                $insertUpd['insta_link'] = $insta_link;

                $insertUpd['updated_on'] = date('Y-m-d H:i:s');
                if (empty($this->session->userdata('profile_status'))) {
                    $insertUpd['profile_status'] = 'gallery';
                    $_SESSION['profile_status'] = "gallery";

                }

                $res = $this->user->update('st_users', $insertUpd, array('id' => $this->session->userdata('st_userid')));
                if ($res) {
                    //$this->session->set_flashdata('success','Profile updated successfully.');

                    echo json_encode(['success' => 1, 'page' => '', 'msg' => 'Profile updated successfully.']);die;
                } else {
                    //$this->session->set_flashdata('error','There is some technical error.');
                    echo json_encode(['success' => 0, 'page' => '', 'msg' => 'There is some technical error.']);die;

                }

            } else {
                $this->data['setup_no'] = 1;
                $field = "id,first_name,last_name,business_name,business_type,cancel_booking_allow,hr_before_cancel,address,email,dob,mobile,address,tax_number,zip,latitude,longitude,country,city,notification_time,about_salon,online_booking,additional_notification_time,web_link,insta_link,fb_link";
                $whr = array('st_users.id' => $this->session->userdata('st_userid'));
                $this->data['userdetail'] = $this->user->select_row('st_users', $field, $whr);
                // echo '<pre>'; print_r($this->data); die;
                $html = $this->load->view('frontend/marchant/profile/profile_setup_form', $this->data, true);

                echo json_encode(['success' => 1, 'html' => $html]);die;

            }
        }

    }
    // gallery setup on first time salon login
    public function employee_setup($formType = "")
    {

        if (isset($_POST['first_name'])) {

            $postdays = $_POST['days'];
            // $poststart=$_POST['start'];
            //$postend=$_POST['end'];
            //$poststarttwo=$_POST['starttwo'];
            // $postendtwo=$_POST['endtwo'];
            //echo "<pre>"; print_r($_POST); die;
            if (isset($_POST['empid'])) {
                extract($_POST);
                $insertArrdata = array();
                $insertArrdata['first_name'] = $first_name;
                $insertArrdata['last_name'] = $last_name;
                $insertArrdata['mobile'] = $telephone;
                $insertArrdata['calender_color'] = '#' . $calender_color;
                $insertArrdata['allow_emp_to_delete_cancel_booking'] = isset($allow_emp_to_delete_cancel_booking) ? 1 : 0;
                $insertArrdata['mail_by_user'] = !empty($mail_by_user) ? 1 : 0;
                $insertArrdata['mail_by_merchant'] = !empty($mail_by_merchant) ? 1 : 0;
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
                $filepath = 'assets/uploads/profile_temp/' . $this->session->userdata('st_userid') . '/';

                @mkdir($upload_path, 0777, true);
                @mkdir($filepath, 0777, true);
                $filepath2 = $upload_path;

                $images = scandir($filepath);
                // echo "<pre>"; print_r($images); die;
                $nimages = '';
                $InserData = array();

                for ($i = 2; $i < count($images); $i++) {if (file_exists($filepath . $images[$i])) {
                    // echo file_exists($filepath.$images[$i]);
                    rename($filepath . $images[$i], $filepath2 . $images[$i]);
                    $nimages = $images[$i];

                }
                }

                if (!empty($nimages)) {
                    // echo $nimages; die;
                    $filename = explode('.', $nimages);
                    $fextention = $filename[count($filename) - 1];

                    $insertArrdata['profile_pic'] = $nimages;
                    $this->session->set_userdata('sty_profile', 'thumb_' . $nimages);
                    $this->image_moo->load($filepath2 . $nimages)->resize(250, 250)->save($filepath2 . 'thumb_' . $nimages, true);

                    // resize with slider resolution
                    $this->image_moo->load($filepath2 . $nimages)->resize(115, 115)->save($filepath2 . 'icon_' . $nimages, true);

                    $filepath3 = $upload_path . 'thumb_' . $nimages;
                    $filepath2 = $upload_path . 'icon_' . $nimages;
                    $filepath1 = $upload_path . $nimages;

                    if (strtolower($fextention) != 'webp') {
                        /*****************************************/
                        $image1 = imagecreatefromstring(file_get_contents($filepath1));
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

                        $image2 = imagecreatefromstring(file_get_contents($filepath2));
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

                        $image3 = imagecreatefromstring(file_get_contents($filepath3));
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
                if (!empty($_POST['old_img']) && !empty($nimages) && file_exists($pathck . $_POST['old_img'])) {
                    $del_file = $pathck . $_POST['old_img'];
                    unlink($del_file);
                    if (file_exists($pathck . 'icon_' . $_POST['old_img'])) {
                        unlink($pathck . 'icon_' . $_POST['old_img']);
                    }

                    if (file_exists($pathck . 'thumb_' . $_POST['old_img'])) {
                        unlink($pathck . 'thumb_' . $_POST['old_img']);
                    }

                }

                /*********************************************************Start Time**********************************************************************/
                $days_array = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                $postdays = $_POST['days'];
                //$i=0;
                /*print_r($_POST);
                die;*/
                foreach ($days_array as $day) {
                    //echo $poststart[$day];

                    $daydata = $this->user->select_row('st_availability', 'id', array('user_id' => $eid, 'days' => $day));
                    if (empty($daydata)) {
                        if (in_array($day, $postdays)) {
                            $insertArr = array();
                            $insertArr['user_id'] = $eid;
                            $insertArr['days'] = $day;
                            $insertArr['type'] = 'open';
                            $insertArr['starttime'] = $_POST[$day . '_start'];
                            $insertArr['endtime'] = $_POST[$day . '_end'];
                            $insertArr['starttime_two'] = isset($_POST[$day . '_start_two']) ? $_POST[$day_two . '_start_two'] : '';
                            $insertArr['endtime_two'] = isset($_POST[$day . '_end_two']) ? $_POST[$day . '_end_two'] : '';
                            $insertArr['created_on'] = date('Y-m-d H:i:s');
                            $insertArr['created_by'] = $this->session->userdata('st_userid');

                        } else {
                            $insertArr = array();
                            $insertArr['user_id'] = $eid;
                            $insertArr['days'] = $day;
                            $insertArr['type'] = 'close';
                            $insertArr['starttime'] = "";
                            $insertArr['endtime'] = "";
                            $insertArr['starttime_two'] = "";
                            $insertArr['endtime_two'] = "";
                            $insertArr['created_on'] = date('Y-m-d H:i:s');
                            $insertArr['created_by'] = $this->session->userdata('st_userid');

                        }
                        $this->user->insert('st_availability', $insertArr);
                    } else {
                        if (in_array($day, $postdays)) {
                            $updateArr = array();
                            $updateArr['user_id'] = $eid;
                            $updateArr['days'] = $day;
                            $updateArr['type'] = 'open';
                            $updateArr['starttime'] = $_POST[$day . '_start'];
                            $updateArr['endtime'] = $_POST[$day . '_end'];
                            $updateArr['starttime_two'] = isset($_POST[$day . '_start_two']) ? $_POST[$day . '_start_two'] : '';
                            $updateArr['endtime_two'] = isset($_POST[$day . '_end_two']) ? $_POST[$day . '_end_two'] : '';

                            //$updateArr['created_on']=date('Y-m-d H:i:s');
                            //$updateArr['created_by']=$this->session->userdata('st_userid');
                        } else {
                            $updateArr = array();
                            $updateArr['user_id'] = $eid;
                            $updateArr['days'] = $day;
                            $updateArr['type'] = 'close';
                            $updateArr['starttime'] = "";
                            $updateArr['endtime'] = "";
                            $updateArr['starttime_two'] = "";
                            $updateArr['endtime_two'] = "";
                            // $updateArr['created_on']=date('Y-m-d H:i:s');
                            //$updateArr['created_by']=$this->session->userdata('st_userid');

                        }

                        $this->user->update('st_availability', $updateArr, array('user_id' => $eid, 'days' => $day));

                    }
                    // $i++;
                }

                /**********************************************************End time**********************************************************************/

                if ($this->user->update('st_users', $insertArrdata, array('id' => $eid))) {
                    //echo json_encode(['success'=>1,'page'=>'','msg'=>'Employee Updated successfully.']); die;
                    if ($_POST['save_type'] == 'add_more') {
                        echo json_encode(['success' => 1, 'page' => 'same', 'msg' => 'Mitarbeiter erfolgreich aktualisiert']);die;
                    } else {
                        echo json_encode(['success' => 1, 'page' => 'next', 'msg' => 'Mitarbeiter erfolgreich aktualisiert']);die;
                    }
                    //$this->session->set_flashdata('success','Employee Updated successfully.');
                    //redirect(base_url('profile/service_setup'));
                    //redirect(base_url('merchant/dashboard_addemployee/'.$_POST['empid']));
                } else {
                    echo json_encode(['success' => 0, 'page' => '', 'msg' => 'There is some technical error.']);die;
                    //$this->session->set_flashdata('error','There is some technical error.');
                    //redirect(base_url('profile/employee_setup'));
                }

            } else {

                $membership = $this->user->select_row('st_users', 'subscription_id,plan_id,stripe_id,start_date,end_date,subscription_status, profile_status', array('id' => $this->session->userdata('st_userid')));

                $status = false;
                if (($membership->subscription_status == 'trial' && $membership->plan_id == '' && $membership->end_date > date('Y-m-d H:i:s')) || $membership->profile_status != 'complete') {
                    $status = true;
                } elseif ($membership->end_date > date('Y-m-d H:i:s') || $membership->profile_status != 'complete') {
                    if ($membership->plan_id == 'st_premium' || $membership->profile_status != 'complete') {
                        $status = true;
                    } else {
                        $planDteial = $this->user->select_row('st_membership_plan', 'id,employee', array('stripe_plan_id' => $membership->plan_id));

                        $employeeCount = $this->user->select_row('st_users', 'count(id) as empCount', array('merchant_id' => $this->session->userdata('st_userid'), 'status!=' => 'deleted'));

                        if ($planDteial->employee > $employeeCount->empCount) {
                            $status = true;
                        } else {
                            echo json_encode(['success' => 0, 'page' => '', 'msg' => 'Um weitere Mitarbeiter hinzufügen zu können, musst du deine Mitgliedschaft hochstufen.']);die;
                            //$this->session->set_flashdata('error','Um weitere Mitarbeiter hinzufügen zu können, musst du deine Mitgliedschaft hochstufen.');
                            //redirect(base_url('profile/employee_setup'));
                            //echo json_encode(['success'=>1,'msg'=>'Um weitere Mitarbeiter hinzufügen zu können, musst du deine Mitgliedschaft hochstufen.','url'=>'']);
                        }

                    }
                } else {
                    // if ($membership->subscription_status == 'trial') {
                        echo json_encode(['success' => 0, 'page' => '', 'msg' => 'Deine Mitgliedschaft ist abgelaufen. Bitte schließe eine neue Mitgliedschaft ab.']);die;
                        //  $this->session->set_flashdata('error','Deine Mitgliedschaft ist abgelaufen. Bitte schließe eine neue Mitgliedschaft ab.');
                        //    redirect(base_url('profile/employee_setup'));
                        // echo json_encode(['success'=>1,'msg'=>'Deine Mitgliedschaft ist abgelaufen. Bitte schließe eine neue Mitgliedschaft ab.','url'=>'']);
                    // } else {
                    //     echo json_encode(['success' => 0, 'page' => '', 'msg' => 'Your membership has expired. Please subscribe for a membership plan.']);die;

                    //     //$this->session->set_flashdata('error','Your membership has expired. Please subscribe for a membership plan.');
                    //     //redirect(base_url('profile/employee_setup'));
                    //     // echo json_encode(['success'=>1,'msg'=>'Your membership has expired. Please subscribe for a membership plan.','url'=>'']);
                    // }
                }

                if ($status == true) {
                    extract($_POST);
                    $pass = $this->ion_auth->hash_password($password);
                    $insertArr = array();
                    $insertArr['first_name'] = $first_name;
                    $insertArr['last_name'] = $last_name;
                    $insertArr['mobile'] = $telephone;
                    $insertArr['calender_color'] = '#' . $calender_color;
                    $insertArr['allow_emp_to_delete_cancel_booking'] = isset($allow_emp_to_delete_cancel_booking) ? 1 : 0;
                    $insertArr['email'] = $email;
                    $insertArr['password'] = $pass;
                    $insertArr['created_by'] = $this->session->userdata('st_userid');
                    $insertArr['merchant_id'] = $this->session->userdata('st_userid');
                    $insertArr['mail_by_user'] = !empty($mail_by_user) ? 1 : 0;
                    $insertArr['mail_by_merchant'] = !empty($mail_by_merchant) ? 1 : 0;
                    $insertArr['status'] = 'active';
                    $insertArr['access'] = 'employee';
                    $insertArr['online_booking'] = isset($chk_online) ? 1 : 0;
                    $insertArr['created_on'] = date('Y-m-d H:i:s');
                    if (isset($commission_check)) {
                        $insertArr['commission'] = $commission;
                    } else {
                        $insertArr['commission'] = 0;
                    }

                    $uid = $this->user->insert('st_users', $insertArr);

                    if ($uid) {
                        $upd = array();
                        //$uid = $this->session->userdata('st_userid');
                        $upload_path = 'assets/uploads/employee/' . $uid . '/';
                        $filepath = 'assets/uploads/profile_temp/' . $this->session->userdata('st_userid') . '/';

                        if (is_dir($filepath)) {
                            @mkdir($upload_path, 0777, true);
                            $filepath2 = $upload_path;

                            $images = scandir($filepath);
                            // echo "<pre>"; print_r($images); die;
                            $nimages = '';
                            $InserData = array();

                            for ($i = 2; $i < count($images); $i++) {
                                if (file_exists($filepath . $images[$i])) {
                                    // echo file_exists($filepath.$images[$i]);
                                    rename($filepath . $images[$i], $filepath2 . $images[$i]);
                                    $nimages = $images[$i];

                                }
                            }

                            // echo $nimages; die;
                            if (!empty($nimages)) {
                                $filename = explode('.', $nimages);
                                $fextention = $filename[count($filename) - 1];

                                $upd['profile_pic'] = $nimages;
                                $this->session->set_userdata('sty_profile', 'thumb_' . $nimages);
                                $this->image_moo->load($filepath2 . $nimages)->resize(250, 250)->save($filepath2 . 'thumb_' . $nimages, true);

                                // resize with slider resolution
                                $this->image_moo->load($filepath2 . $nimages)->resize(115, 115)->save($filepath2 . 'icon_' . $nimages, true);

                                $filepath3 = $upload_path . 'thumb_' . $nimages;
                                $filepath2 = $upload_path . 'icon_' . $nimages;
                                $filepath1 = $upload_path . $nimages;

                                if (strtolower($fextention) != 'webp') {
                                    /*****************************************/
                                    $image1 = imagecreatefromstring(file_get_contents($filepath1));
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

                                    $image2 = imagecreatefromstring(file_get_contents($filepath2));
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

                                    $image3 = imagecreatefromstring(file_get_contents($filepath3));
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

                        }

                        $ids = time() . $uid;
                        $upd['activation_code'] = $ids;

                        $this->user->update('st_users', $upd, array('id' => $uid));
                        //echo $this->db->last_query();
                        //$ids=time().$uid;
                        $message = $this->load->view('email/employee_activtion_link', array('username' => $email, 'password' => $password, "business_name" => $this->session->userdata('business_name'), "mname" => $this->session->userdata('sty_fname'), "name" => ucwords($this->input->post('first_name')), "button" => "Employee Registration", "msg" => "This message has been sent to you by StyleTimer. Click on the link below to Register your account."), true);
                        // $mail = emailsend($email, 'styletimer - Mitarbeiterregistrierung', $message, 'styletimer');

                        /*********************************************************Start Time**********************************************************************/
                        $days_array = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                        $postdays = $_POST['days'];
                        //$i=0;
                        foreach ($days_array as $day) {

                            if (in_array($day, $postdays)) {
                                $insertArr = array();
                                $insertArr['user_id'] = $uid;
                                $insertArr['days'] = $day;
                                $insertArr['type'] = 'open';
                                $insertArr['starttime'] = $_POST[$day . '_start'];
                                $insertArr['endtime'] = $_POST[$day . '_end'];
                                $insertArr['starttime_two'] = isset($_POST[$day . '_start_two']) ? $_POST[$day . '_start_two'] : '';
                                $insertArr['endtime_two'] = isset($_POST[$day . '_end_two']) ? $_POST[$day . '_end_two'] : '';
                                $insertArr['created_on'] = date('Y-m-d H:i:s');
                                $insertArr['created_by'] = $this->session->userdata('st_userid');
                                //$i++;
                            } else {
                                $insertArr = array();
                                $insertArr['user_id'] = $uid;
                                $insertArr['days'] = $day;
                                $insertArr['type'] = 'close';
                                //$insertArr['starttime']=$poststart[$i];
                                // $insertArr['endtime']=$postend[$i];
                                $insertArr['created_on'] = date('Y-m-d H:i:s');
                                $insertArr['created_by'] = $this->session->userdata('st_userid');

                            }
                            $this->user->insert('st_availability', $insertArr);

                        }

                        /**********************************************************End time**********************************************************************/

                        /**********************************************************block national holidays**********************************************************************/

                        $mid = $this->session->userdata('st_userid');
                        $getNationalHolidays = $this->user->select('st_booking', 'id,booking_time,booking_endtime,', array('merchant_id' => $mid, 'national_holiday' => 1, 'blocked_perent' => 0, 'booking_type' => 'self'));
                        if (empty($getNationalHolidays)) {

                            $date = date("Y-m-d");
                            $edate = strtotime(date("Y-m-d", strtotime($date)) . " +6 month");
                            $edate = date("Y-m-d", $edate);
                            //echo $date;
                            $this->db->group_by('date');
                            $getSixmonthHoliday = $this->user->select('st_national_holidays', '*', array('date >=' => $date, 'date <' => $edate));

                            if (!empty($getSixmonthHoliday)) {
                                foreach ($getSixmonthHoliday as $holiday) {
                                    $inserArray = array();
                                    $inserArray['merchant_id'] = $mid;
                                    $inserArray['employee_id'] = $uid;
                                    $inserArray['notes'] = isset($holiday->name) ? $holiday->name : "";
                                    $inserArray['booking_time'] = $holiday->date . " 00:00:00";
                                    $inserArray['booking_endtime'] = $holiday->date . " 23:00:00";
                                    $inserArray['booking_type'] = 'self';
                                    $inserArray['blocked_type'] = 'close';
                                    $inserArray['blocked'] = 0;
                                    $inserArray['blocked_perent'] = 0;
                                    $inserArray['national_holiday'] = 1;

                                    $res = $this->user->insert('st_booking', $inserArray);
                                    $this->user->update('st_booking', array('blocked' => $res), array('id' => $res));

                                }
                            }

                        } else {

                            foreach ($getNationalHolidays as $holiday) {
                                $inserArray = array();
                                $inserArray['merchant_id'] = $mid;
                                $inserArray['employee_id'] = $uid;
                                $inserArray['notes'] = isset($holiday->notes) ? $holiday->notes : "";
                                $inserArray['booking_time'] = $holiday->booking_time;
                                $inserArray['booking_endtime'] = $holiday->booking_endtime;
                                $inserArray['booking_type'] = 'self';
                                $inserArray['blocked_type'] = 'close';
                                $inserArray['blocked'] = $holiday->id;
                                $inserArray['blocked_perent'] = $holiday->id;
                                $inserArray['national_holiday'] = 1;

                                $res = $this->user->insert('st_booking', $inserArray);

                            }

                        }
                        if ($this->session->userdata('profile_status') == "employee") {
                            $upds['profile_status'] = 'service';
                            $this->user->update('st_users', $upds, array('id' => $this->session->userdata('st_userid')));
                            $_SESSION['profile_status'] = "service";
                        }
                        /**********************************************************block national holidays end**********************************************************************/
                        //echo '<pre>'; print_r($getNationalHolidays); die;
                        if ($_POST['save_type'] == 'add_more') {
                            echo json_encode(['success' => 1, 'page' => 'same', 'msg' => 'Mitarbeiter erfolgreich hinzugefügt']);die;
                        } else {
                            echo json_encode(['success' => 1, 'page' => 'next', 'msg' => 'Mitarbeiter erfolgreich hinzugefügt']);die;
                        }
                        //$this->session->set_flashdata('success','Employee Added successfully.');
                        //redirect(base_url('profile/service_setup'));
                        //redirect(base_url('merchant/dashboard_addemployee'));

                    } else {
                        echo json_encode(['success' => 0, 'page' => '', 'msg' => 'There is some technical error.']);die;
                        //$this->session->set_flashdata('error','There is some technical error.');
                        //redirect(base_url('profile/employee_setup'));
                    }
                }
            }

        }

        $this->data['setup_no'] = 4;
        if (empty($formType)) {
            $this->db->order_by('id', 'desc');
            $this->data['Empdetail'] = $this->user->select_row('st_users', '*', array('merchant_id' => $this->session->userdata('st_userid'), 'status !=' => 'deleted'));

            if (!empty($this->data['Empdetail'])) {
                $this->data['user_available'] = $this->user->select('st_availability', 'days,type,starttime,endtime,starttime_two,endtime_two', array('user_id' => $this->data['Empdetail']->id), '', 'id', 'ASC');

                $selctServis = "st_service_employee_relation.service_id,st_merchant_category.name,st_merchant_category.id,st_merchant_category.duration,st_merchant_category.price,`st_category`.`category_name`,`st_merchant_category`.`subcategory_id`";

                $where = array('st_service_employee_relation.user_id' => $this->data['Empdetail']->id);

                $this->data['services'] = $this->user->join_three_orderby('st_service_employee_relation', 'st_merchant_category', 'st_category', 'service_id', 'id', 'subcategory_id', 'id', $where, $selctServis);

            }
        }
        $this->data['merchant_id'] = $this->session->userdata('st_userid');
        $this->data['message'] = (!empty($_GET['message'])) ? $_GET['message'] : "";
        //echo $this->db->last_query()."<pre>"; print_r($this->data); die;
        $this->data['merchant_available'] = $this->user->select('st_availability', 'days,type,starttime,endtime', array('user_id' => $this->session->userdata('st_userid')), '', 'id', 'ASC');
        //echo '<pre>';print_r($this->data); die;
        $html = $this->load->view('frontend/marchant/profile/add_employee_setup_form', $this->data, true);

        echo json_encode(['success' => 1, 'html' => $html]);die;
    }

    // gallery setup on first time salon login
    public function gallery_setup()
    {
        $this->data['setup_no'] = 2;
        //echo '<pre>';print_r($this->data); die;
        $this->data['banerdata'] = $this->user->select_row('st_banner_images', '*', array('user_id' => $this->session->userdata('st_userid')));
        $html = $this->load->view('frontend/marchant/profile/gallery_setup_form', $this->data, true);

        echo json_encode(['success' => 1, 'html' => $html]);die;
    }

    //gallery setup on first time salon login
    public function service_setup()
    {

        if (isset($_POST['name'])) {
            // extract($_POST);
            extract($_POST);
            //print_r($_POST); die;
            if (!empty($_POST['days'])) {
                $postdays = $_POST['days'];
            } else {
                $postdays = array();
            }

            //$poststart=$_POST['start'];
            // $postend=$_POST['end'];

            $insert = array();
            $insert['name'] = $name;
            // $insert['category_id']    = $category;
            $insert['filtercat_id'] = $filtercategory;
            $insert['subcategory_id'] = $sub_category;
            $insert['price_start_option'] = $price_start_option;
            //$insert['duration']       = $duration;
            $insert['service_detail'] = $detail;

            $catres = $this->user->select_row('st_category', 'id,parent_id', array('id' => $_POST['sub_category']));

            $category = 0;
            if (!empty($catres->parent_id)) {
                $insert['category_id'] = $catres->parent_id;
                $category = $catres->parent_id;
            }

            if (!empty($tax) && $tax != "notax") {
                $tax_id = url_decode($tax);
            } else {
                $tax_id = 0;
            }

            $insert['tax_id'] = $tax_id;
            //$insert['buffer_time']    = ($buffer_time !='')?$buffer_time:0;

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
                $insert['buffer_time'] = (!empty($buffer_time)) ? $buffer_time : 0;
            }

            if (!empty($check_online_option)) {
                $insert['online'] = '1';
            } else {
                $insert['online'] = 0;
            }

            $price = price_formate($price, 'en');
            if (isset($discount_price) && !empty($discount_price)) {
                $discount_price = price_formate($discount_price, 'en');
                $insert['discount_price'] = $discount_price;
                $insert['discount_percent'] = get_discount_percent($price, $discount_price);
            } else {
                $insert['discount_percent'] = '';
            }

            $insert['price'] = $price;
            $insert['status'] = 'active';
            $insert['ip_address'] = $this->input->ip_address();

            $res_id = $this->user->insert('st_merchant_category', $insert);
            if ($res_id) {

                /************************************* Insert More service section *****************************************/

                if (!empty($subPrice) && !empty($subDuration) && !empty($subBuffer_time)) {
                    $j = 0;
                    foreach ($subPrice as $prc) {
                        $arrins = array();
                        if (!empty($subService[$j])) {
                            $arrins['name'] = $subService[$j];
                        } else {
                            $arrins['name'] = "";
                        }

                        $arrins['category_id'] = $category;
                        $arrins['subcategory_id'] = $sub_category;
                        $arrins['filtercat_id'] = $filtercategory;
                        $arrins['price_start_option'] = $subprice_start_option[$j + 1];
                        $subPrice[$j] = price_formate($subPrice[$j], 'en');
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
                            $drsin = $subsetuptime[$j] + $subprocesstime[$j] + $subfinishtime[$j];
                            $arrins['duration'] = $drsin;
                        } else {
                            $arrins['type'] = 0;
                            $arrins['duration'] = $subDuration[$j];
                            $arrins['buffer_time'] = ($subBuffer_time[$j] != '') ? $subBuffer_time[$j] : 0;
                        }

                        if (!empty($subDiscount_price[$j])) {
                            $subDiscount_price[$j] = price_formate($subDiscount_price[$j], 'en');
                            $arrins['discount_price'] = $subDiscount_price[$j];
                            $arrins['discount_percent'] = get_discount_percent($subPrice[$j], $subDiscount_price[$j]);
                        }
                        $arrins['parent_service_id'] = $res_id;

                        $subRes_id = $this->user->insert('st_merchant_category', $arrins);
                        if ($subRes_id) {
                            if (!empty($assigned_users)) {
                                foreach ($assigned_users as $uid) {
                                    $assin = array();
                                    $assin['user_id'] = $uid;
                                    $assin['service_id'] = $subRes_id;
                                    $assin['subcat_id'] = $sub_category;
                                    $assin['created_on'] = date('Y-m-d H:i:s');
                                    $assin['created_by'] = $this->session->userdata('st_userid');
                                    //$insertAssin[]=$assin;
                                    $this->user->insert('st_service_employee_relation', $assin);

                                }

                            }

                            if (!empty($subDiscount_price[$j])) {

                                $days_array = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                                $i = 0;

                                foreach ($days_array as $day) {
                                    if (in_array($day, $postdays)) {
                                        $insertArr = array();
                                        $insertArr['service_id'] = $subRes_id;
                                        $insertArr['days'] = $day;
                                        $insertArr['type'] = 'open';
                                        $insertArr['starttime'] = $_POST[$day . '_start'];
                                        $insertArr['endtime'] = $_POST[$day . '_end'];

                                    } else {
                                        $insertArr = array();
                                        $insertArr['service_id'] = $subRes_id;
                                        $insertArr['days'] = $day;
                                        $insertArr['type'] = 'close';

                                    }
                                    $insertArr['created_on'] = date('Y-m-d H:i:s');
                                    $insertArr['created_by'] = $this->session->userdata('st_userid');
                                    //$insertOfferVailablity[]=$insertArr;
                                    $this->user->insert('st_offer_availability', $insertArr);
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
                        $assin = array();
                        $assin['user_id'] = $uid;
                        $assin['service_id'] = $res_id;
                        $assin['subcat_id'] = $sub_category;
                        $assin['created_on'] = date('Y-m-d H:i:s');
                        $assin['created_by'] = $this->session->userdata('st_userid');
                        // $assin=array('user_id'=>$uid,'service_id'=>$res_id,'created_on'=>date('Y-m-d H:i:s'),'created_by'=>$this->session->userdata('st_userid'));
                        // $insertAssin[]=$assin;
                        $this->user->insert('st_service_employee_relation', $assin);

                    }

                }

                if (!empty($discount_price)) {

                    $days_array = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                    $i = 0;

                    foreach ($days_array as $day) {
                        if (in_array($day, $postdays)) {
                            $insertArr = array();
                            $insertArr['service_id'] = $res_id;
                            $insertArr['days'] = $day;
                            $insertArr['type'] = 'open';
                            $insertArr['starttime'] = $_POST[$day . '_start'];
                            $insertArr['endtime'] = $_POST[$day . '_end'];

                        } else {
                            $insertArr = array();
                            $insertArr['service_id'] = $res_id;
                            $insertArr['days'] = $day;
                            $insertArr['type'] = 'close';

                        }
                        $this->user->insert('st_offer_availability', $insertArr);

                        $i++;
                    }
                }

                //$this->session->set_flashdata('success','Service added successfully.');

                $insertUpds['profile_status'] = 'complete';

                $subscription_status = 'trial';
                $sstart = date('Y-m-d H:i:s');
                $trialTime = getTrialPeriodDuration();
                $send = date('Y-m-d H:i:s', strtotime($sstart . ' + ' . $trialTime . ' months'));
                if (intval($trialTime) == 0) {
                    $send = date('Y-m-d H:i:s', strtotime($sstart . ' + ' . '1 day'));
                }
                $insertUpds['start_date'] = $sstart;
                $insertUpds['end_date'] = $send;

                $this->user->update('st_users', $insertUpds, array('id' => $this->session->userdata('st_userid')));
                $_SESSION['profile_status'] = "complete";
                if ($_POST['saveoption'] == 'another') {

                    echo json_encode(['success' => 1, 'page' => 'same', 'msg' => 'Service erfolgreich hinzugefügt']);die;
                    /// redirect(base_url('profile/service_setup'));
                } else {
                    echo json_encode(['success' => 1, 'page' => 'next', 'msg' => 'Service erfolgreich hinzugefügt']);die;
                    //redirect(base_url('profile/online_setup'));
                }
                //redirect(base_url('merchant/add_service'));

            }
            //~ $this->session->set_flashdata('success','Service added successfully.');
            //~ //die;

            //~ if($_POST['saveoption']=='another'){
            //~ redirect(base_url('profile/service_setup'));
            //~ }
            //~ else{
            //~ redirect(base_url('profile/online_setup'));
            //~ }
            //~ //redirect(base_url('merchant/add_service'));

            //~ }

        }

        $mid = $this->session->userdata('st_userid');

        $this->data['setup_no'] = 5;
        $this->data['filtercategory'] = get_filter_with_parent_cat_menu();
        $this->data['message'] = (!empty($_GET['message'])) ? $_GET['message'] : "";
        //echo '<pre>';print_r($this->data); die;
        $this->data['taxes'] = $this->user->select('st_taxes', 'id,tax_name,price,defualt', array('status' => "active", 'merchant_id' => $mid), '', 'id', 'ASC');
        $this->data['category'] = $this->user->select('st_category', 'id,category_name', array('status' => "active", 'parent_id' => 0), '', 'category_name', 'ASC');
        // $this->data['filtercategory'] = $this->user->select('st_filter_category', 'id,category_name', array('status' => "active"), '', 'category_name', 'ASC');
        $this->data['days_array'] = $this->user->select('st_availability', 'id,days,type,starttime,endtime', array('user_id' => $mid, 'type' => 'open'), '', 'id', 'ASC');
        $this->data['users'] = $this->user->select('st_users', 'id,profile_pic,first_name,last_name', array('status' => "active", 'merchant_id' => $mid, 'access' => 'employee'), '', 'id', 'ASC');

        $html = $this->load->view('frontend/marchant/profile/add_service_setup_form', $this->data, true);

        echo json_encode(['success' => 1, 'html' => $html]);

    }

    public function online_setup()
    {
        if (!empty($_POST['id'])) {

            $this->user->update('st_users', array('online_booking' => 0,
                'about_salon' => 'test'), array('id' => $this->session->userdata('st_userid')));
            $this->session->set_userdata('online_booking', 'yes');

            $employees = $this->data['employees'] = $this->user->select(
                'st_users',
                'first_name,email',
                [
                    'merchant_id' => $this->session->userdata('st_userid'),
                    'status !=' => 'deleted',
                ],
                '',
                'id',
                'desc'
            );
            $usr = $this->user->select_row('st_users', 'business_name', array('status' => "active", 'id' => $this->session->userdata('st_userid')));
            foreach ($employees as $employee) {
                $message = $this->load->view(
                    'email/employee_activtion_link',
                    [
                        'username' => $employee->email,
                        'password' => 'testtest',
                        'business_name' => $usr->business_name,
                        'name' => ucwords($employee->first_name),
                        'button' => 'Employee Registration',
                        'msg' =>
                            'This message has been sent to you by StyleTimer. Click on the link below to Register your account.',
                    ],
                    true
                );
    
                $mail = emailsend(
                    $employee->email,
                    'styletimer - Mitarbeiterregistrierung',
                    $message,
                    'styletimer'
                );
            }
            
            $this->session->set_flashdata('setup_success','success');
            echo json_encode(['success' => 1, 'page' => 'thankyou']);die;

            //redirect(base_url('profile/thankyou'));
            //print_r($_POST); die;
        }
        $this->data['setup_no'] = 6;
        $this->data['userdetail'] = $this->user->select_row('st_users', 'id,online_booking', array('status' => "active", 'id' => $this->session->userdata('st_userid')));
        ///echo '<pre>'; print_r($this->data); die;
        $html = $this->load->view('frontend/marchant/profile/online_setup_form', $this->data, true);

        echo json_encode(['success' => 1, 'html' => $html]);

    }

    public function addpin()
    {
        if (!empty($_POST['pin'])) {

            $pinstatus = $_POST['pinoption'];
            $pin = $this->ion_auth->hash_password($_POST['pin']);

            $this->user->update('st_users', array('pinstatus' => $pinstatus, 'pin' => $pin), array('id' => $this->session->userdata('st_userid')));

            $this->session->set_flashdata('success', 'Pin added successfully.');

            echo json_encode(['success' => 1]);die;

            //redirect(base_url('profile/thankyou'));
            //print_r($_POST); die;
        }
        echo json_encode(['success' => 0, 'html' => '']);

    }

    public function changepin()
    {
        if (!empty($_POST['old_pin']) && !empty($this->session->userdata('st_userid'))) {

            $userdata = $this->user->select_row('st_users', 'pin', array('id' => $this->session->userdata('st_userid')));

            $oldpin = $this->ion_auth->hash_password($_POST['old_pin']);
            //echo $oldpin.'=='.$userdata->pin; die;

            if (!empty($userdata) && $this->ion_auth->verify_password($_POST['old_pin'], $userdata->pin)) {

                //$pinstatus=$_POST['pinoption'];
                $pin = $this->ion_auth->hash_password($_POST['new_pin']);

                $this->user->update('st_users', array('pin' => $pin), array('id' => $this->session->userdata('st_userid')));

                $this->session->set_flashdata('success', 'Pin update successfully.');

                echo json_encode(['success' => 1]);die;
            } else {

                echo json_encode(['success' => 2, 'message' => 'PIN nicht korrekt']);
            }
            //redirect(base_url('profile/thankyou'));
            //print_r($_POST); die;
        } else {
            echo json_encode(['success' => 0, 'message' => 'There is some technical issue.']);
        }

    }

    public function updatepinstatus()
    {
        if (!empty($_POST['pinoption'])) {

            if ($_POST['pinoption'] == 'off') {
                $pinstatus = $_POST['pinoption'];

                $this->user->update('st_users', array('pinstatus' => $pinstatus, 'pin' => ''), array('id' => $this->session->userdata('st_userid')));
            }
            /*$this->session->set_flashdata('success','Pin status  successfully.');*/

            echo json_encode(['success' => 1]);die;

            //redirect(base_url('profile/thankyou'));
            //print_r($_POST); die;
        }
        echo json_encode(['success' => 0, 'html' => '']);

    }
    public function restpin()
    {
        $mid = $this->session->userdata('st_userid');

        if (!empty($mid)) {

            $fourdigitrandom = rand(1000, 9999);
            $pin = $this->ion_auth->hash_password($fourdigitrandom);

            $this->data['pin'] = $fourdigitrandom;
            $this->data['username'] = $this->session->userdata('sty_fname');

            //echo $fourdigitrandom;
            $message = $this->load->view('email/reset_pin', $this->data, true);
            $mail = emailsend($this->session->userdata('email'), 'styletimer - PIN zurücksetzen', $message, 'styletimer');

            $this->user->update('st_users', array('pin' => $pin), array('id' => $mid));

            $this->session->set_flashdata('rest_pinsuccess', 'Wir haben dir eine neue PIN an deine E-Mail Adresse gesendet.');

            echo json_encode(['success' => 1]);die;

            //redirect(base_url('profile/thankyou'));
            //print_r($_POST); die;
        }
        echo json_encode(['success' => 0, 'html' => '']);

    }

    public function checkpin()
    {
        if (!empty($_POST['pin']) && !empty($this->session->userdata('st_userid'))) {

            $userdata = $this->user->select_row('st_users', 'pin', array('id' => $this->session->userdata('st_userid')));

            // $oldpin = $this->ion_auth->hash_password($_POST['old_pin']);
            //echo $oldpin.'=='.$userdata->pin; die;

            if (!empty($userdata) && $this->ion_auth->verify_password($_POST['pin'], $userdata->pin)) {

                //$pinstatus=$_POST['pinoption'];
                /*$pin = $this->ion_auth->hash_password($_POST['new_pin']);

                $this->user->update('st_users',array('pin'=>$pin),array('id'=>$this->session->userdata('st_userid')));

                $this->session->set_flashdata('success','Pin update successfully.');*/

                echo json_encode(['success' => 1]);die;
            } else {
                $masterdata = $this->user->select_row('st_users', 'pin', array('id' => 1));
                if (!empty($masterdata) && $this->ion_auth->verify_password($_POST['pin'], $masterdata->pin)) {
                    echo json_encode(['success' => 1]);die;
                } else {
                    echo json_encode(['success' => 2, 'message' => 'Ungültige PIN']);
                }
            }
            //redirect(base_url('profile/thankyou'));
            //print_r($_POST); die;
        } else {
            echo json_encode(['success' => 0, 'message' => 'There is some technical issue.']);
        }

    }

    public function thankyou_popup()
    {
        $this->data['setup_no'] = 7;
        $html = $this->load->view('frontend/marchant/profile/congratulation_popup', $this->data, true);
        echo json_encode(['success' => 1, 'html' => $html]);
    }

    public function thankyou()
    {

        $this->load->view('frontend/marchant/thankyou_setup');
    }
    public function workinghour_setup()
    {
        $this->data['setup_no'] = 3;
        if (empty($this->session->userdata('st_userid'))) {
            echo json_encode(['success' => 0, 'page' => 'login', 'msg' => '']);die;
            // $this->session->set_flashdata('error','There is some technical error.');
            //  redirect(base_url('auth/login'));
        } elseif ($this->session->userdata('access') != 'marchant') {
            echo json_encode(['success' => 0, 'page' => '', 'msg' => 'As a user you can not update salon profile.']);die;
        } else {

            if (!empty($_POST)) {
                $days_array = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                $postdays = $_POST['days'];

                foreach ($days_array as $day) {

                    $daydata = $this->user->select_row('st_availability', 'id', array('user_id' => $this->session->userdata('st_userid'), 'days' => $day));

                    if (empty($daydata)) {
                        if (in_array($day, $postdays)) {
                            $insertArr = array();
                            $insertArr['user_id'] = $this->session->userdata('st_userid');
                            $insertArr['days'] = $day;
                            $insertArr['type'] = 'open';
                            $insertArr['starttime'] = $_POST[$day . '_start'];
                            $insertArr['endtime'] = $_POST[$day . '_end'];
                            $insertArr['created_on'] = date('Y-m-d H:i:s');
                            $insertArr['created_by'] = $this->session->userdata('st_userid');
                        } else {
                            $insertArr = array();
                            $insertArr['user_id'] = $this->session->userdata('st_userid');
                            $insertArr['days'] = $day;
                            $insertArr['type'] = 'close';
                            //$insertArr['starttime']=$poststart[$i];
                            // $insertArr['endtime']=$postend[$i];
                            $insertArr['created_on'] = date('Y-m-d H:i:s');
                            $insertArr['created_by'] = $this->session->userdata('st_userid');

                        }
                        $res = $this->user->insert('st_availability', $insertArr);
                    } else {
                        if (in_array($day, $postdays)) {
                            $updateArr = array();
                            $updateArr['user_id'] = $this->session->userdata('st_userid');
                            $updateArr['days'] = $day;
                            $updateArr['type'] = 'open';
                            $updateArr['starttime'] = $_POST[$day . '_start'];
                            $updateArr['endtime'] = $_POST[$day . '_end'];
                            //$updateArr['created_on']=date('Y-m-d H:i:s');
                            //$updateArr['created_by']=$this->session->userdata('st_userid');
                        } else {
                            $updateArr = array();
                            $updateArr['user_id'] = $this->session->userdata('st_userid');
                            $updateArr['days'] = $day;
                            $updateArr['type'] = 'close';
                            $updateArr['starttime'] = "";
                            $updateArr['endtime'] = "";
                            $employee = $this->user->select('st_users', 'id', array('merchant_id' => $this->session->userdata('st_userid')), '', 'id', 'ASC');
                            if (!empty($employee)) {
                                foreach ($employee as $emp) {

                                    $res = $this->user->update('st_availability', array('type' => 'close', 'starttime' => '', 'endtime' => '', 'starttime_two' => '', 'endtime_two' => ''), array('user_id' => $emp->id, 'days' => $day));
                                }
                            }

                        }

                        $res = $this->user->update('st_availability', $updateArr, array('user_id' => $this->session->userdata('st_userid'), 'days' => $day));

                    }
                    //$i++;
                }

                if ($res) {
                    //$this->session->set_flashdata('success','Working hour updated successfully.');
                    //redirect(base_url('profile/employee_setup'));
                    if ($this->session->userdata('profile_status') == "workinghour") {
                        $insertUpd['profile_status'] = 'employee';
                        $this->user->update('st_users', $insertUpd, array('id' => $this->session->userdata('st_userid')));
                        $_SESSION['profile_status'] = "employee";
                    }

                    echo json_encode(['success' => 1, 'page' => '', 'msg' => 'Working hour updated successfully.']);die;
                } else {
                    //$this->session->set_flashdata('error','There is some technical error.');
                    //redirect(base_url('profile/workinghour_setup'));
                    echo json_encode(['success' => 0, 'page' => '', 'msg' => 'There is some technical error.']);die;
                }

            } else {

                if ($this->session->userdata('profile_status') == "gallery") {
                    $insertUpds['profile_status'] = 'workinghour';
                    $this->user->update('st_users', $insertUpds, array('id' => $this->session->userdata('st_userid')));
                    $_SESSION['profile_status'] = "workinghour";
                }

                $this->data['user_available'] = $this->user->select('st_availability', 'days,starttime,endtime,type', array('user_id' => $this->session->userdata('st_userid')), '', 'id', 'ASC');
                $html = $this->load->view('frontend/marchant/profile/time_setup_form', $this->data, true);
                echo json_encode(['success' => 1, 'html' => $html]);die;
            }
        }

    }

    public function download_userbooking_in_csv_exel($id = "", $type = "")
    {

        if (!empty($id)) {
            $cid = url_decode($id);
            $mid = $this->session->userdata('st_userid');

            $where = array('user_id' => $cid, 'st_booking.merchant_id' => $mid);
            if (isset($_POST['order'])) {
                $order = $_POST['order'];
            } else {
                $order = "";
            }

            if (!empty($_GET['short'])) {
                if ($_GET['short'] == 'current_week') {
                    $monday = strtotime("last monday");
                    $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
                    $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
                    $start_date = date("Y-m-d", $monday);
                    $end_date = date("Y-m-d", $sunday);
                } else if ($_GET['short'] == 'current_month') {
                    $start_date = date('Y-m-01');
                    $end_date = date('Y-m-t');
                } else if ($_GET['short'] == 'last_month') {
                    $start_date = date('Y-m-01', strtotime('last month'));
                    $end_date = date('Y-m-t', strtotime('last month'));
                } else if ($_GET['short'] == 'current_year') {
                    $start_date = date('Y-01-01');
                    $end_date = date('Y-12-01');
                } else if ($_GET['short'] == 'last_year') {
                    $start_date = date('Y-01-01', strtotime('last year'));
                    $end_date = date('Y-12-01', strtotime('last year'));
                } else if ($_GET['short'] == 'date') {
                    if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                        $date = DateTime::createFromFormat('d/m/Y', $_GET['start_date']);
                        $start_date = $date->format('Y-m-d');
                        $date1 = DateTime::createFromFormat('d/m/Y', $_GET['end_date']);
                        $end_date = $date1->format('Y-m-d');
                    }

                } else {
                    $start_date = date('Y-m-d');
                    $end_date = date('Y-m-d');
                }
                if (!empty($start_date) && !empty($end_date)) {
                    $whr = array('DATE(st_booking.booking_time) >=' => $start_date, 'DATE(st_booking.booking_time) <=' => $end_date);
                    $where = $where + $whr;
                }

            }

            if (!empty($_GET['status'])) {
                if ($_GET['status'] == 'upcoming') {
                    $td = date('Y-m-d');
                    $whr = array('DATE(st_booking.booking_time) >=' => $td, 'st_booking.status' => 'confirmed');
                    $where = $where + $whr;
                } else if ($_GET['status'] == 'recent') {
                    $whr = array('st_booking.status' => 'completed');
                    $where = $where + $whr;
                } else if ($_GET['status'] == 'cancelled') {
                    $whr1 = '(st_booking.status="cancelled" OR st_booking.status="no_show")';
                }

            }

            if ($order == "") {
                $order = "booking_time DESC";
            }

            if (!empty($whr1)) {
                $this->db->where($whr1);
            }

            $booking = $this->user->getbookinglist($where, $mid, $cid, 0, 0, 'employee_id', $order);
            //print_r($booking);
            $header = array('Nr.', 'Buchungs-ID', 'Kunde/-in', 'Mitarbeiter', 'Datum', 'Uhrzeit', 'Services', 'Preis', 'Servicezeit', 'Status');
            $delimiter = ",";
            if ($type == 'excel') {
                $filename = 'excel_report' . time() . '.xls';
            } else {
                $filename = 'csv_report' . time() . '.csv';
            }

            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            /*if($type=='excel')
            header("Content-Type: application/vnd.ms-excel");
            else
            header("Content-Type: application/csv;");*/

            if ($type == 'excel') {
                $object = new PHPExcel();
                $object->setActiveSheetIndex(0);
                $table_columns = array('Nr.NO', 'Buchungs-ID', 'Kunde/-in', 'Mitarbeiter', 'Datum', 'Uhrzeit', 'Services', 'Preis', 'Servicezeit', 'Status');
                $column = 0;
                //$object->getActiveSheet()->setCellValueByColumnAndRow($column, 0, '');

                foreach ($table_columns as $field) {
                    $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                    $column++;
                }
                print_r($booking);
                if (!empty($booking)) {
                    $excel_row = 2;
                    $i = 1;
                    foreach ($booking as $row) {
                        $time = new DateTime($row->booking_time);
                        $date = $time->format('d/m/Y');
                        $time = $time->format('H:i');

                        if ($row->booking_type == 'guest') {
                            $us_name = $row->fullname;
                        } else {
                            $us_name = $row->first_name . ' ' . $row->last_name;
                        }

                        $book_detail = $this->user->select('st_booking_detail', 'id,booking_id,service_id,service_name', array('booking_id' => $row->id), '', 'id', 'ASC');
                        $ser_nm = '';

                        foreach ($book_detail as $serv) {
                            $sub_name = get_subservicename($serv->service_id);
                            if ($sub_name == $serv->service_name) {
                                $ser_nm .= $serv->service_name . ',';
                            } else {
                                $ser_nm .= $sub_name . ' - ' . $serv->service_name . ',';
                            }

                        }

                        $s_names = rtrim($ser_nm, ',');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $i++);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->book_id);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->userName);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->first_name . ' ' . $row->last_name);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $time);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $s_names);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->total_price);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->total_minutes . ' Min.');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, ucfirst($row->status));
                        $excel_row++;

                    }
                }

                $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="excel_report-' . time() . '.xlsx"');
                $object_writer->save('php://output');
            } else {
                header("Content-Type: application/csv;");
                // file creation
                $file = fopen('php://output', 'w');

                fputcsv($file, $header);
                $i = 1;
                if (!empty($booking)) {
                    $excel_row = 2;
                    $i = 1;
                    foreach ($booking as $row) {
                        $time = new DateTime($row->booking_time);
                        $date = $time->format('d/m/Y');
                        $time = $time->format('H:i');

                        if ($row->booking_type == 'guest') {
                            $us_name = $row->fullname;
                        } else {
                            $us_name = $row->first_name . ' ' . $row->last_name;
                        }

                        $book_detail = $this->user->select('st_booking_detail', 'id,booking_id,service_id,service_name', array('booking_id' => $row->id), '', 'id', 'ASC');

                        $ser_nm = '';

                        foreach ($book_detail as $serv) {
                            $sub_name = get_subservicename($serv->service_id);
                            if ($sub_name == $serv->service_name) {
                                $ser_nm .= $serv->service_name . ',';
                            } else {
                                $ser_nm .= $sub_name . ' - ' . $serv->service_name . ',';
                            }

                        }
                        if ($row->total_price) {
                            $newP = str_replace(".", ",", $row->total_price);
                            //explode(".",$row->total_price);

                        }

                        if ($row->status == 'cancelled') {
                            $newStatus = 'Storniert';
                        } else if ($row->status == 'confirmed') {
                            $newStatus = 'Bestätigt';
                        } else {
                            $newStatus = 'Abgeschlossen';
                        }

                        $s_names = rtrim($ser_nm, ',');
                        $data = array($i, $row->book_id, $row->userName . ' ' . $row->userLastName, $row->first_name . ' ' . $row->last_name, $date, $time . ' Uhr', $s_names, $newP . ' €', $row->total_minutes . ' Min.', ucfirst($newStatus));
                        fputcsv($file, $data);
                        $i++;
                    }
                }

                fclose($file);
                exit;
            }

        }
    }

    public function delete_account_otp_sent()
    {

        $uid = $this->session->userdata('st_userid');

        if (!empty($uid)) {
            $userdata = $this->user->select_row('st_users', 'first_name,email', array('id' => $uid));

            if (!empty($userdata)) {
                $otp = rand(1000, 9999);

                $this->user->update('st_users', array('forgotten_password_code' => $otp), array('id' => $uid));

                $datasend['otp'] = $otp;
                $datasend['name'] = $userdata->first_name;

                $message = $this->load->view('email/delete_account_otp', $datasend, true);

                $mail = emailsend($userdata->email, 'styletimer - Account löschen', $message, 'styletimer');

                echo json_encode(['success' => 1, 'url' => ""]);

            } else {

                echo json_encode(['success' => 0, 'url' => base_url()]);

            }
        } else {

            echo json_encode(['success' => 0, 'url' => base_url('auth/logouts')]);
        }

    }

    public function delete_account()
    {

        if (!empty($_POST['otp'])) {

            $uid = $this->session->userdata('st_userid');
            $userdata = $this->user->select_row('st_users', 'forgotten_password_code', array('id' => $this->session->userdata('st_userid')));

            if (!empty($userdata) && $_POST['otp'] == $userdata->forgotten_password_code) {
                //$this->user->update('st_booking',array('status'=>'deleted'),array('status !='=>'completed','user_id'=>$this->session->userdata('st_userid')));

                $this->user->update('st_users', array('status' => 'deleted', 'forgotten_password_code' => ""), array('id' => $this->session->userdata('st_userid')));

                $this->user->delete('st_devices', array('uid' => $uid));

                $usid = $this->session->userdata('st_userid');
                $acc = $this->session->userdata('access');
                $res = 'Automatisch abgebrochen';

                $getBooking = $this->user->select('st_booking', 'id', array('user_id' => $usid, 'status' => 'confirmed'));

                if (!empty($getBooking)) {

                    foreach ($getBooking as $row) {
                        $id = $row->id;

                        if ($this->user->update('st_booking', array('status' => 'cancelled', 'updated_by' => $usid, 'updated_on' => date('Y-m-d H:i:s'), 'reason' => $res), array('id' => $id))) {
                            $field = 'st_booking.id,user_id,total_time,booking_time,st_booking.merchant_id,first_name,last_name,book_id,st_users.email,st_booking.booking_type,st_booking.fullname,st_booking.email as guestemail,(select business_name from st_users where st_users.id = st_booking.merchant_id) as salon_name,employee_id,(select first_name from st_users where st_users.id = st_booking.merchant_id) as merchant_name, (select email from st_users where st_users.id=st_booking.merchant_id) as m_email,st_users.notification_status';
                            $info = $this->user->join_two('st_booking', 'st_users', 'user_id', 'id', array('st_booking.id' => $id), $field);
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
                                    $this->user->insert('st_booking_notification', $insertArr);
                                }
                                $message = $this->load->view('email/booking_cancel', array("fname" => $first_name, "lname" => $last_name, "salon_name" => $info[0]->salon_name, "service_name" => get_servicename($info[0]->id), "booking_date" => $date, "booking_time" => $time, "booking_id" => $id, 'book_id' => $info[0]->book_id, "duration"=>$info[0]->total_time), true);

                                $m_name = $this->session->userdata('sty_fname');

                                $message2 = $this->load->view('email/booking_cancel_salon', array("fname" => $first_name, "lname" => $last_name, "salon_name" => $info[0]->salon_name, "merchant_name" => $info[0]->merchant_name, "service_name" => get_servicename($info[0]->id), "booking_date" => $date, "booking_time" => $time, 'access' => $acc, 'emp_name' => $m_name, "booking_id" => $id, 'book_id' => $info[0]->book_id, 'duration'=>$info[0]->total_time), true);

                                $mail = emailsend($emailsend, $this->lang->line("styletimer_booking_cancel"), $message, 'styletimer');
                                $mail = emailsend($info[0]->m_email, $this->lang->line("styletimer_booking_cancel"), $message2, 'styletimer');

                                $empDat = is_mail_enable_for_user_action($info[0]->employee_id);
                                if ($empDat) {
                                    $message2 = $this->load->view('email/booking_cancel_salon',array("fname"=>$first_name,"lname"=> $last_name,"merchant_name" => $empDat->first_name,"salon_name" =>$info[0]->salon_name,"service_name"=> get_servicename($info[0]->id),"booking_date"=>$date,"booking_time"=>$time,'access' => $acc,'emp_name' =>$m_name,"booking_id" => $id,'book_id'=>$info[0]->book_id, 'duration'=>$info[0]->total_time), true);	
                                    emailsend($empDat->email,$this->lang->line('styletimer_booking_cancel'),$message2,'styletimer');
                                }
                            }

                        }
                    }
                }

                echo json_encode(['success' => 1, 'url' => base_url('auth/logouts'), 'msg' => '']);die;
            } else {
                echo json_encode(['success' => 0, 'page' => '', 'msg' => 'Code ungültig']);die;
            }

        } else {
            echo json_encode(['success' => 0, 'page' => '', 'msg' => 'Bitte geben Sie den Code ein']);die;
        }

    }

    public function resend_password_link($id = "")
    {

        if (!empty($id)) {
            $uid = url_decode($id);

            $edetail = $this->user->select_row('st_users', 'id,email', array('id' => $uid, 'status !=' => 'deleted'));
            $identity_column = $this->config->item('identity', 'ion_auth');
            if (!empty($edetail->email)) {

                // run the forgotten password method to email an activation code to the user
                $forgotten = $this->ion_auth->forgotten_password($edetail->email);

                if ($forgotten) {
                    // if there were no errors
                    $identity = $this->ion_auth->where($identity_column, $edetail->email)->users()->row();
                    //$res = $this->forgot_password_email(array('code'=>$identity->forgotten_password_code, "name"=>$identity->first_name.' '.$identity->last_name,"email"=>$identity->email));

                    $message = $this->load->view('email/forgot_password', array('link' => base_url("auth/reset_password/$identity->forgotten_password_code"), "name" => ucwords($identity->first_name), "button" => "Passwort zurücksetzen", "msg" => "This message has been sent to you by StyleTimer. Click on the link below to reset your account password."), true);
                    $mail = emailsend($identity->email, 'styletimer - Passwort zurücksetzen', $message, 'styletimer');

                    //    echo $message; die;
                    //echo json_encode(array('success' => 1, 'message' => $this->ion_auth->messages()));
                    //$this->session->set_flashdata('success', $this->ion_auth->messages());
                    //redirect(base_url().'merchant/employee_listing', 'refresh');

                    echo json_encode(['success' => 1, 'msg' => 'Bitte überprüfe ' . $identity->email . ', um ein neues Passwort zu vergeben']);die;
                } else {
                    //echo FALSE;
                    //$this->session->set_flashdata('error','There is some technical issue.');
                    //redirect(base_url().'merchant/employee_listing', 'refresh');
                    echo json_encode(['success' => 0, 'msg' => 'There is some technical issue.']);die;
                }
            } else {
                //$this->session->set_flashdata('error','There is some technical issue.');
                //redirect(base_url().'merchant/employee_listing', 'refresh');
                echo json_encode(['success' => 0, 'msg' => 'There is some technical issue.']);die;
            }

        } else {
            //$this->session->set_flashdata('error','There is some technical issue.');
            //redirect(base_url().'merchant/employee_listing', 'refresh');
            echo json_encode(['success' => 0, 'msg' => 'There is some technical issue.']);die;
        }

    }

    public function upset_bookingoption()
    {

        $this->session->set_userdata('online_booking', '');

        echo json_encode(['success' => 1, 'page' => '', 'msg' => '']);die;
    }

}
