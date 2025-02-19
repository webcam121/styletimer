<?php defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH.'libraries/Antideo.php');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Auth extends CI_Controller
{
    public $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        $this->lang->load('salon_dashboard','german');
        
        //if($this->ion_auth->logged_in())redirect('/backend/dashboard', 'refresh');

    }

    /**
     * Redirect if needed, otherwise display the user list
     */
    public function index()
    {

        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
        {
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['title'] = $this->lang->line('index_heading');

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            //list the users
            $this->data['users'] = $this->ion_auth->users()->result();

            //USAGE NOTE - you can do more complicated queries like this
            //$this->data['users'] = $this->ion_auth->where('field', 'value')->users()->result();

            /*foreach ($this->data['users'] as $k => $user)
            {
            $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
            }*/
            redirect("auth/login", 'refresh');
            //$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'index', $this->data);
        }
    }

    /**
     * Log the user in
     */
    // my code rk //
    public function c_registration() {
        if ($this->input->method() == "get") {
            redirect('/?page=registration');
        }
        redirect('/?page=login');
    }

    public function login()
    {
        if ($this->input->method() == "get") {
            redirect('/?page=login');
        }

        $this->data['title'] = $this->lang->line('login_heading');
        $this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');
        if ($this->form_validation->run() === true) {
            $remember = (bool) $this->input->post('remember');
            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                $usersSql = $this->db->select('login_status,online_booking,access')->from('st_users')->where('email', $this->input->post('identity'))->where('status', 'active');

                $data = $usersSql->get()->row();

                if (!empty($data)) {
                    if ($data->access == 'marchant') {
                        if ($data->online_booking == 0) {
                            $this->session->set_userdata('online_booking', 'yes');   
                        }
                        $url = base_url('merchant/dashboard');
                        $resData = ['success' => '1', 'message' => 'login success', 'url' => $url];
                        echo json_encode($resData);die;
                    }
                    if ($data->access == 'user') {

                        //$url=base_url("profile/edit_user_profile");
                        $url = base_url();
                        $resData = ['success' => '1', 'message' => 'login success', 'url' => $url];
                        echo json_encode($resData);die;
                        //  echo "user role";
                        //    print_r($data);die;
                        // $usersSql = $this->db->select('login_status')->from('st_users')->where('email', $this->input->post('identity'))->where('status','active');
                        // $dataUser = $usersSql->get()->row();
                        // if($dataUser->login_status == 0){
                        //     $this->db->update('st_users', array('login_status' => '1'), array('email' => $this->input->post('identity'),'status'=>'active'));
                        //     if($bid !=""){
                        //         $url=base_url("user/all_bookings?bid=").$bid;
                        //         $resData = ['success'=>'1','message'=>'login success','url'=>$url];
                        //           echo json_encode($resData);    die;
                        //     }

                        //     else{
                        //         $url=base_url("profile/edit_user_profile");
                        //         $resData = ['success'=>'1','message'=>'login success','url'=>$url];
                        //           echo json_encode($resData);    die;
                        //     }

                        // }
                        // else{
                        //     $usersSql = $this->db->select('activation_code,status,(SELECT count(*) FROM st_login_attempts WHERE login="'.$this->input->post('identity').'") as login_count')->from('st_users')->where('email', $this->input->post('identity'));
                        //     $data = $usersSql->get()->row();
                        //     if(!empty($data->status)){
                        //     if($data->activation_code != "" && $data->status =="inactive"){
                        //     echo json_encode(['success'=>2,'message'=>'User Inactive','url'=>""]);die;
                        //     //echo json_encode(['success'=>'2','message'=>$this->ion_auth->errors(),'url'=>'false']); die;
                        //     }
                        //     // if($data->login_count > 2){
                        //     // echo json_encode(['success'=>3,'message'=>'login success','url'=>""]); die;
                        //     // //echo json_encode(['success'=>'3','message'=>$this->ion_auth->errors(),'url'=>'false']); die;
                        //     // }
                        //     }
                        // // $url=base_url();
                        // // $resData = ['success'=>'1','message'=>'login success','url'=>$url];
                        // //           echo json_encode($resData);    die;
                        // }
                    }
                    if ($data->access == 'employee') {
                        $url = base_url("employee/dashboard");
                        $resData = ['success' => '1', 'message' => 'login success', 'url' => $url];
                        echo json_encode($resData);die;
                    }
                } else {
                    // echo "hello ";
                    // print_r($data);
                    // $url=base_url();
                    // $resData = ['success'=>'3',
                    // 'message'=>'Bitte E-Mail und Passwort prüfen',
                    // 'url'=>$url];
                    // echo json_encode($resData);    die;
                    $this->session->set_flashdata('error', 'There is some technical error');
                    echo json_encode(array('success' => 0, 'msg' => 'There is some technical error', 'html' => ""));
                }

                // print_r($data);die;

            } else {
                //     echo "hello ";
                // print_r($data);
                $url = base_url('?page=login&status=email');
//         $url=base_url('?page=login&status=email');
                echo json_encode(['success' => 3, 'message' => 'Bitte E-Mail und Passwort prüfen', 'url' => $url]);
                // $resData = ['success'=>'3',
                // 'message'=>'',
                // ];
                // print_r($resData);
                // echo json_encode($resData);    die;
            }

        }
    }

    // old code  login
    // public function login()
    // {

    //     //print_r($_POST);die;
    //     $this->data['title'] = $this->lang->line('login_heading');

    //     // validate form input
    //     $this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
    //     $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

    //     if ($this->form_validation->run() === TRUE)
    //     {

    //         // check to see if the user is logging in
    //         // check for "remember me"
    //         $remember = (bool)$this->input->post('remember');

    //         if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
    //         {
    //             //print_r($this->session); die;
    //             $bid ='';
    //             if(!empty($_POST['red_bookid']))
    //                 $bid=$_POST['red_bookid'];

    //             if($this->session->userdata('access')=='marchant'){

    //                 //~ if($_POST['select_url'] !=""){
    //                         //~ $url=base_url($_POST['select_url']);
    //                     //~ }
    //                 //~ else{
    //                     $usersSql = $this->db->select('login_status')->from('st_users')->where('email', $this->input->post('identity'))->where('status','active');
    //                         $data = $usersSql->get()->row();

    //                         if($data->login_status == 0){
    //                             $this->db->update('st_users', array('login_status' => '1'), array('email' => $this->input->post('identity'),'status'=>'active'));
    //                             $url=base_url("merchant/dashboard?setup=profile");
    //                         }
    //                         else{
    //                             $query=$this->db->query("SELECT about_salon,online_booking,(SELECT count(id) FROM st_availability WHERE user_id=".$this->session->userdata('st_userid')." AND type='open') as workinhhrs,
    //                             (SELECT count(id) FROM st_users WHERE merchant_id=".$this->session->userdata('st_userid')." AND access='employee') as employeeCount,
    //                             (SELECT count(id) FROM st_merchant_category WHERE created_by=".$this->session->userdata('st_userid')." AND status !='deleted') as serviceCount
    //                              FROM st_users WHERE id=".$this->session->userdata('st_userid'));

    //                             $checkdata=$query->row();
    //                             //print_r($checkdata);die;
    //                             if($checkdata->online_booking==0){
    //                                 $this->session->set_userdata('online_booking','off');
    //                              }

    //                             if(empty($checkdata->about_salon))
    //                                 $url=base_url("merchant/dashboard?setup=profile");
    //                             else if(empty($checkdata->workinhhrs))
    //                                 $url=base_url("merchant/dashboard?setup=workinghour");
    //                             else if(empty($checkdata->employeeCount))
    //                                 $url=base_url("merchant/dashboard?setup=employee");
    //                             else if(empty($checkdata->serviceCount))
    //                                 $url=base_url("merchant/dashboard?setup=service");
    //                             else if($bid !="")
    //                                 $url=base_url('merchant/booking_listing?bid=').$bid;
    //                             else
    //                                 $url=base_url('merchant/dashboard');
    //                             }
    //                     //~ }

    //                 }
    //             elseif($this->session->userdata('access')=='employee')
    //                {
    //                 $url=base_url("employee/dashboard");
    //                 }
    //             else{

    //                     if($_SERVER['HTTP_REFERER'] !=""){
    //                         //$url=base_url("user/service_provider/".$_POST['select_url']);
    //                         if($bid !="")
    //                             $url=base_url("user/all_bookings?bid=").$bid;
    //                         else
    //                             $url=$_SERVER['HTTP_REFERER'];

    //                     }
    //                     else{
    //                         $usersSql = $this->db->select('login_status')->from('st_users')->where('email', $this->input->post('identity'))->where('status','active');
    //                         $data = $usersSql->get()->row();
    //                         if($data->login_status == 0){
    //                             $this->db->update('st_users', array('login_status' => '1'), array('email' => $this->input->post('identity'),'status'=>'active'));
    //                           if($bid !="")
    //                             $url=base_url("user/all_bookings?bid=").$bid;
    //                           else
    //                               $url=base_url("profile/edit_user_profile");
    //                         }
    //                         else if($bid !="")
    //                             $url=base_url("user/all_bookings?bid=").$bid;
    //                         else
    //                             $url=base_url();
    //                     }
    //                 }
    //                 if((bool)$this->input->post('rememberme')){
    //                     setcookie('ck_emailid',$this->input->post('identity'), time() + (86400 * 30), "/");
    //                       setcookie('ck_password',$this->input->post('password'), time() + (86400 * 30), "/");
    //                   }
    //                   else if(isset($_COOKIE['ck_emailid']) && $_COOKIE['ck_emailid'] == $this->input->post('identity')){
    //                     setcookie('ck_emailid','', time()
    //                       + (86400 * 30), "/");
    //                       setcookie('ck_password','', time() + (86400 * 30), "/");
    //                     }
    //         //echo $url; die;
    //         $resData = ['success'=>'1','message'=>'login success','url'=>$url];
    //         echo json_encode($resData);    die;
    //         //echo json_encode(['success'=>'1','message'=>'login success','url'=>$url]); die;
    //             //if the login is successful
    //             //redirect them back to the home page
    //             //$this->session->set_flashdata('message', $this->ion_auth->messages());
    //             //redirect('/', 'refresh');
    //         }
    //         else
    //         {
    //             // if the login was un-successful
    //             // redirect them back to the login page

    //             $usersSql = $this->db->select('activation_code,status,(SELECT count(*) FROM st_login_attempts WHERE login="'.$this->input->post('identity').'") as login_count')->from('st_users')->where('email', $this->input->post('identity'));
    //             $data = $usersSql->get()->row();
    //             if(!empty($data->status)){
    //             if($data->activation_code != "" && $data->status =="inactive"){
    //                 echo json_encode(['success'=>2,'url'=>""]);die;
    //                 //echo json_encode(['success'=>'2','message'=>$this->ion_auth->errors(),'url'=>'false']); die;
    //                 }
    //              if($data->login_count > 2){
    //                 echo json_encode(['success'=>3,'url'=>""]); die;
    //                 //echo json_encode(['success'=>'3','message'=>$this->ion_auth->errors(),'url'=>'false']); die;
    //               }
    //             }

    //             //$this->session->set_flashdata('err_message', $this->ion_auth->errors());

    //             //$this->session->set_flashdata('err_message','Benutzername oder Passwort prüfen');
    //             //echo 'DEV';die();
    //             echo json_encode(['success'=>0,'url'=>""]);
    //             //echo json_encode(['success'=>0,'message'=>'Invalid_User']);
    //              die;
    //             // echo json_encode(['err_message'=>0,'message'=>'Benutzername oder Passwort prüfen']); die;
    //             //echo json_encode(['success'=>'0','message'=>$this->ion_auth->errors(),'url'=>'false']); die;

    //             //redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
    //         }
    //     }
    //     else
    //     {
    //         if(!empty($this->session->userdata('st_userid')))
    //         {
    //             redirect(base_url());
    //         }
    //         else{
    //         // the user is not logging in so display the login page
    //         // set the flash data error message if there is one
    //         $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

    //         $this->data['identity'] = [
    //             'name' => 'identity',
    //             'id' => 'identity',
    //             'type' => 'text',
    //             'value' => $this->form_validation->set_value('identity'),
    //         ];

    //         $this->data['password'] = [
    //             'name' => 'password',
    //             'id' => 'password',
    //             'type' => 'password',
    //         ];

    //         $this->load->library('user_agent');
    //         if ($this->agent->is_browser())
    //         {
    //                 $agent = $this->agent->browser();
    //         }
    //         elseif ($this->agent->is_robot())
    //         {
    //                 $agent = $this->agent->robot();
    //         }
    //         elseif ($this->agent->is_mobile())
    //         {
    //                 $agent = $this->agent->mobile();
    //         }
    //         else
    //         {
    //                 $agent = 'Unidentified User Agent';
    //         }

    //      $borsers=array('safari','firefox','uc browser','chrome','google chrome','opera');
    //     if(in_array(strtolower($agent), $borsers))
    //       {
    //           //echo "Match found";
    //       }
    //      else $this->session->set_flashdata('error','Browser is not supported and to use chrome, firefox or safari for best experience.');
    //          redirect(base_url().'?page=login');
    //         //die;
    //         //$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'login', $this->data);
    //     }
    //   }
    // }

    /**
     * Log the user in
     */
    public function administrator()
    {
        $this->data['title'] = $this->lang->line('login_heading');

        // validate form input
        $this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

        if ($this->form_validation->run() === true) {
            //  check to see if the user is logging in
            // check for "remember me"
            $remember = (bool) $this->input->post('remember');

            if ($_SESSION['admin_count'] == 'on') {
                $captcha;
                if (isset($_POST['g-recaptcha-response'])) {
                    $captcha = $_POST['g-recaptcha-response'];
                }
    
                if (!$captcha) {
                    if ($type == 'user') {
                        echo json_encode(['success' => 0, 'message'=> 'Bitte überprüfen Sie das Captcha']);die;
                    } else {
                        echo json_encode(['success' => 0, 'message'=> 'Bitte überprüfen Sie das Captcha']);die;
                    }             
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
                    echo json_encode(['success' => 0, 'message'=> 'Bitte überprüfen Sie das Captcha']);die;
                }
            }

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember, 'admin')) {

                //if the login is successful
                //redirect them back to the home page
                //$this->session->set_flashdata('message', $this->ion_auth->messages());
                //redirect('/backend/user/listing/user', 'refresh');
                $_SESSION['admin_count'] = '';
                redirect('/backend/dashboard?short=day', 'refresh');

            } else {
                // if the login was un-successful
                // redirect them back to the login page
                //die("ddttt");
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                $usersSql = $this->db->select('(SELECT count(*) FROM st_login_attempts WHERE login="' . $this->input->post('identity') . '") as login_count')->from('st_users')->where('email', $this->input->post('identity'));
                $data = $usersSql->get()->row();
                if ($data->login_count > 2) {
                    $_SESSION['admin_count'] = 'on';
                }
                redirect('auth/administrator', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        } else {
            // the user is not logging in so display the login page
            // set the flash data error message if there is one
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['identity'] = [
                'name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
            ];

            $this->data['password'] = [
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
            ];

            $this->_render_page('backend/login', $this->data);
        }
    }

    /**
     * Log the user out
     */
    public function logout()
    {
        $this->data['title'] = "Logout";

        // log the user out
        $this->ion_auth->logout();

        // redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        //use site url when subdomin is working
        //redirect(SITEURL, 'refresh');
        redirect(base_url('backend'), 'refresh');
    }

    /**
     * Change password
     */
    public function change_password()
    {

        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        $user = $this->ion_auth->user()->row();
        if ($this->form_validation->run() === false) {
            // display the form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
            $this->data['old_password'] = [
                'name' => 'old',
                'id' => 'old',
                'type' => 'password',
            ];
            $this->data['new_password'] = [
                'name' => 'new',
                'id' => 'new',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            ];
            $this->data['new_password_confirm'] = [
                'name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            ];
            $this->data['user_id'] = [
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            ];

            // render
            $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'change_password', $this->data);
        } else {
            $identity = $this->session->userdata('identity');
            $access = $this->session->userdata('access');
            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                // print_r($change);die;
                //if the password was successfully changed
                //$this->session->set_flashdata('success', $this->ion_auth->messages());
                //redirect($access.'/change_password', 'refresh');
                echo json_encode(['success' => 1, 'message' => $this->ion_auth->messages()]);die;
            } else {

                //echo "not change data  ".$change;
                //$this->session->set_flashdata('error', $this->ion_auth->errors());
                //redirect($access.'/change_password', 'refresh');
                echo json_encode(['success' => 0, 'message' => $this->ion_auth->errors()]);die;
            }
        }
    }

    /**
     * Forgot password
     */
    public function forgot_password()
    {
        //print_r($_POST);echo "not work";die;
        //$this->data['title'] = $this->lang->line('forgot_password_heading');
        if (isset($_POST) && !empty($_POST)) {

            $identity_column = $this->config->item('identity', 'ion_auth');
            $identity = $this->ion_auth->where($identity_column, $this->input->post('email'))->users()->row();
            //var_dump($identity); die;

            //print_r($identity_column);
            if (empty($identity)) {
                echo false;
                die;
            }

            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});
            print_r($forgotten);die;
            if ($forgotten) {
                // if there were no errors
                $identity = $this->ion_auth->where($identity_column, $this->input->post('email'))->users()->row();
                //$res = $this->forgot_password_email(array('code'=>$identity->forgotten_password_code, "name"=>$identity->first_name.' '.$identity->last_name,"email"=>$identity->email));

                $message = $this->load->view('email/forgot_password',
                    array('link' => base_url("auth/reset_password/$identity->forgotten_password_code"),
                        "name" => ucwords($identity->first_name), "button" => "Passwort zurücksetzen", "msg" => "This message has been sent to you by StyleTimer. Click on the link below to reset your account password."), true);
                $mail = emailsend($identity->email, 'styletimer - Passwort zurücksetzen', $message, 'styletimer');

                //echo TRUE;
                echo json_encode(array('success' => 1, 'message' => $this->ion_auth->messages()));
                //$this->session->set_flashdata('message', $this->ion_auth->messages());
                //redirect(base_url().'auth/forgot_password', 'refresh');
            } else {
                //echo FALSE;
                echo json_encode(array('success' => 0, 'message' => $this->ion_auth->errors()));
                die;
                //$this->session->set_flashdata('message', $this->ion_auth->errors());
                //redirect("auth/forgot_password", 'refresh');
            }
        } else {if (!empty($this->session->userdata('st_userid'))) {
            redirect(base_url());
        } else {
            redirect(base_url());
        }
        }
    }

    public function emailschk()
    {
        $identity_column = $this->config->item('identity', 'ion_auth');
        $table = 'st_users';
        $email = $this->input->post('email');
        // print_r($email);die;
        // $email = $_POST['email'];
        // print_r($email);die;
        //$dataEmail['email'] = $email;
        $result = $this->ion_auth->checkemailmodifiy($email);
        //($result);
        if ($result) {
            $ids = $result->id;
            $six_digit_random_number = random_int(100000, 999999);
            $dataUpdate['forgotten_password_code'] = $six_digit_random_number;
            $forget = $this->ion_auth->forget_update1($ids, $dataUpdate);
            if ($forget) {
                $identity = $this->ion_auth->where($identity_column, $this->input->post('email'))->users()->row();
                //$res = $this->forgot_password_email(array('code'=>$identity->forgotten_password_code, "name"=>$identity->first_name.' '.$identity->last_name,"email"=>$identity->email));

                $message = $this->load->view('email/forgot_password',
                    array('link' => base_url("auth/reset_password/$identity->forgotten_password_code"),
                        "name" => ucwords($identity->first_name), "button" => "Passwort zurücksetzen", "msg" => "This message has been sent to you by StyleTimer. Click on the link below to reset your account password."), true);
                $mail = emailsend($email, 'styletimer - Passwort zurücksetzen', $message, 'styletimer');

                //echo TRUE;
                //    echo json_encode(array('success' => 1, 'message' => $this->ion_auth->messages()));
                echo json_encode(array('success' => 1, 'message' => 'E-Mail zur Wiederherstellung des Passworts gesendet'));die;
            }

        } else {
            echo json_encode(array('success' => 0, 'message' => $this->ion_auth->errors()));
            die;
        }

    }

    /**
     * Reset password - final step for forgotten password
     *
     * @param string|null $code The reset code
     */
    public function reset_password($code = null)
    {
        if (!$code) {
            show_404();
        }

        $this->data['title'] = $this->lang->line('reset_password_heading');

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {
            // if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() === false) {
                // display the form

                // set the flash data error message if there is one
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = [
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                ];
                $this->data['new_password_confirm'] = [
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                ];
                $this->data['user_id'] = [
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                ];
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;

                // render
                //$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'reset_password', $this->data);
                $this->load->view('frontend/reset_password', $this->data);
            } else {
                $identity = $user->{$this->config->item('identity', 'ion_auth')};
                // do we have a valid request?

                // finally change the password
                $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                if ($change) {

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
                    // if the password was successfully changed
                    $this->session->set_flashdata('success', $this->ion_auth->messages());

                    if ($user->access == 'marchant') {
                        $url = base_url("merchant/dashboard");
                    } elseif ($user->access == 'employee') {
                        $url = base_url("employee/dashboard");
                    } else {
                        $url = base_url();
                    }

                    redirect($url, 'refresh'); //"auth/login"
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('auth/reset_password/' . $code, 'refresh');
                }

            }
        } else {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('error', "Forgot password link has been expired.");
            redirect("auth/forgot_password", 'refresh');
        }
    }

    /**
     * Activate the user
     *
     * @param int         $id   The user ID
     * @param string|bool $code The activation code
     */
    public function activate($id, $code = false)
    {
        $activation = false;

        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } else if ($this->ion_auth->is_admin()) {
            $activation = $this->ion_auth->activate($id);
        }
        if ($activation) {
            // redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            //redirect("auth", 'refresh');
            $usersSql = $this->db->select('email,business_name,id, password, status, last_login, access, first_name, profile_pic,profile_status,login_status')->from('st_users')->where('id', $id)->where('status', 'active');
            $data = $usersSql->get()->row();

            $session_data = [
                'identity' => $data->email,
                'email' => $data->email,
                'st_userid' => $data->id, //everyone likes to overwrite id so we'll use user_id
                'old_last_login' => $data->last_login,
                'last_check' => time(),
                'access' => $data->access,
                'status' => $data->status,
                'sty_fname' => $data->first_name,
                'business_name' => $data->business_name,
                'sty_profile' => $data->profile_pic,
                'profile_status' => $data->profile_status,
            ];

            $this->session->set_userdata($session_data);
            $this->db->update('st_users', array('login_status' => '1'), array('id' => $id, 'status' => 'active'));
            if ($data->access == 'marchant') {
                $url = base_url("merchant/dashboard?setup=profile");
            } elseif ($data->access == 'employee') {
                $url = base_url("employee/dashboard");
            } else {
                $url = base_url("profile/edit_user_profile");
            }
            //echo $url;
            redirect($url);

            //redirect("home?pg='login'", 'refresh');
        } else {
            // redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/login", 'refresh');
            //redirect("home?pg='login'", 'refresh');
        }
    }

    /**
     * Deactivate the user
     *
     * @param int|string|null $id The user ID
     */
    public function deactivate($id = null)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        }

        $id = (int) $id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() === false) {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->user($id)->row();

            $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'deactivate_user', $this->data);
        } else {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes') {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === false || $id != $this->input->post('id')) {
                    show_error($this->lang->line('error_csrf'));
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                    $this->ion_auth->deactivate($id);
                }
            }

            // redirect them back to the auth page
            redirect('auth', 'refresh');
        }
    }

    /**
     * Create a new user
     */
    public function create_user()
    {
        $this->data['title'] = $this->lang->line('create_user_heading');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        if ($identity_column !== 'email') {
            $this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
        } else {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() === true) {
            $email = strtolower($this->input->post('email'));
            $identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),
            ];
        }
        if ($this->form_validation->run() === true && $this->ion_auth->register($identity, $password, $email, $additional_data)) {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth", 'refresh');
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = [
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            ];
            $this->data['last_name'] = [
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            ];
            $this->data['identity'] = [
                'name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
            ];
            $this->data['email'] = [
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            ];
            $this->data['company'] = [
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company'),
            ];
            $this->data['phone'] = [
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone'),
            ];
            $this->data['password'] = [
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password'),
            ];
            $this->data['password_confirm'] = [
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            ];

            $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'create_user', $this->data);
        }
    }
    /**
     * Redirect a user checking if is admin
     */
    public function redirectUser()
    {
        if ($this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        redirect('/', 'refresh');
    }

    /**
     * Edit a user
     *
     * @param int|string $id
     */
    public function edit_user($id)
    {
        $this->data['title'] = $this->lang->line('edit_user_heading');

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id))) {
            redirect('auth', 'refresh');
        }

        $user = $this->ion_auth->user($id)->row();
        //$groups = $this->ion_auth->groups()->result_array();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();

        //USAGE NOTE - you can do more complicated queries like this
        //$groups = $this->ion_auth->where(['field' => 'value'])->groups()->result_array();

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim|required');
        $this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'trim|required');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === false || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            // update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
            }

            if ($this->form_validation->run() === true) {
                $data = [
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'company' => $this->input->post('company'),
                    'phone' => $this->input->post('phone'),
                ];

                // update the password if it was posted
                if ($this->input->post('password')) {
                    $data['password'] = $this->input->post('password');
                }

                // Only allow updating groups if user is admin
                if ($this->ion_auth->is_admin()) {
                    // Update the groups user belongs to
                    $groupData = $this->input->post('groups');

                    if (isset($groupData) && !empty($groupData)) {

                        $this->ion_auth->remove_from_group('', $id);

                        foreach ($groupData as $grp) {
                            $this->ion_auth->add_to_group($grp, $id);
                        }

                    }
                }

                // check to see if we are updating the user
                if ($this->ion_auth->update($user->id, $data)) {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    $this->redirectUser();

                } else {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    $this->redirectUser();

                }

            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user'] = $user;
        //$this->data['groups'] = $groups;
        //$this->data['currentGroups'] = $currentGroups;

        $this->data['first_name'] = [
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        ];
        $this->data['last_name'] = [
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        ];
        $this->data['company'] = [
            'name' => 'company',
            'id' => 'company',
            'type' => 'text',
            'value' => $this->form_validation->set_value('company', $user->company),
        ];
        $this->data['phone'] = [
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone', $user->phone),
        ];
        $this->data['password'] = [
            'name' => 'password',
            'id' => 'password',
            'type' => 'password',
        ];
        $this->data['password_confirm'] = [
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'type' => 'password',
        ];

        $this->_render_page('auth/edit_user', $this->data);
    }

    /**
     * Create a new group
     */
    public function create_group()
    {
        $this->data['title'] = $this->lang->line('create_group_title');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        // validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'trim|required|alpha_dash');

        if ($this->form_validation->run() === true) {
            $new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
            if ($new_group_id) {
                // check to see if we are creating the group
                // redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth", 'refresh');
            }
        } else {
            // display the create group form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['group_name'] = [
                'name' => 'group_name',
                'id' => 'group_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('group_name'),
            ];
            $this->data['description'] = [
                'name' => 'description',
                'id' => 'description',
                'type' => 'text',
                'value' => $this->form_validation->set_value('description'),
            ];

            $this->_render_page('auth/create_group', $this->data);
        }
    }

    /**
     * Edit a group
     *
     * @param int|string $id
     */
    public function edit_group($id)
    {
        // bail if no group id given
        if (!$id || empty($id)) {
            redirect('auth', 'refresh');
        }

        $this->data['title'] = $this->lang->line('edit_group_title');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        $group = $this->ion_auth->group($id)->row();

        // validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() === true) {
                $group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

                if ($group_update) {
                    $this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("auth", 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['group'] = $group;

        $readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

        $this->data['group_name'] = [
            'name' => 'group_name',
            'id' => 'group_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('group_name', $group->name),
            $readonly => $readonly,
        ];
        $this->data['group_description'] = [
            'name' => 'group_description',
            'id' => 'group_description',
            'type' => 'text',
            'value' => $this->form_validation->set_value('group_description', $group->description),
        ];

        $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'edit_group', $this->data);
    }

    /**
     * @return array A CSRF key-value pair
     */
    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return [$key => $value];
    }

    /**
     * @return bool Whether the posted CSRF token matches
     */
    public function _valid_csrf_nonce()
    {
        $csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
        if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue')) {
            return true;
        }
        return false;
    }

    /**
     * @param string     $view
     * @param array|null $data
     * @param bool       $returnhtml
     *
     * @return mixed
     */
    public function _render_page($view, $data = null, $returnhtml = false) //I think this makes more sense

    {

        $viewdata = (empty($data)) ? $this->data : $data;

        $view_html = $this->load->view($view, $viewdata, $returnhtml);

        // This will return html on 3rd argument being true
        if ($returnhtml) {
            return $view_html;
        }
    }

    public function registration($type = "")
    {
        $antideo = new Antideo('c96d4c11c4fc2fe7e0fdec02cb8fd4e5');
        //$this->data['title'] = $this->lang->line('create_user_heading');
        //if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())

        if ($this->ion_auth->logged_in()) {
            redirect(base_url());
        }

        if (isset($_POST['email'])) {
            $email = strtolower($this->input->post('email'));
            //if($this->checkemail($email)){
            $tables = $this->config->item('tables', 'users');
            $identity_column = $this->config->item('identity', 'ion_auth');
            $this->data['identity_column'] = $identity_column;

            $salesman_code = "";

            $identity = $email;
            $password = $this->input->post('password');
            if ($type == 'user') {
                $gender = $this->input->post('gender');
                if (empty($gender)) $gender = 'male';
                $b_name = $b_type = $slug = '';
                $subscription_status = '';
                $sstart = '';
                $send = '';
                $refference = '';

                $online = 0;
                $trialTime = 0;
                $cancelallow = 'no';
                $hrsBeforeCancel = "0";
                $refference = (!empty($this->input->post('hot_toknow')) ? $this->input->post('hot_toknow') : '');
                $notification = 1;

                $cityCheck = $this->input->post('city');
                if (!empty($cityCheck)) {
                    $city = $this->input->post('city');
                } else {
                    $city = "";
                }
            } else {
                $ne = trim($this->input->post('business_name'));
                $bb_name = $ne;
                $neert = str_replace(' ', '-', $ne);

                $b_name = strtolower($neert);

                $b_type = $this->input->post('business_type');
                $slug = create_slug($ne);

                $gender = '';
                $cityCheck = $this->input->post('city');
                if (!empty($cityCheck)) {
                    $city = $this->input->post('city');
                } else {
                    $city = "";
                }

                if (!empty($this->input->post('hot_toknow'))) {
                    $refference = $this->input->post('hot_toknow');
                    if ($refference == "Referral") {
                        $salesman_code = $this->input->post('referral_code');
                    }
                } else {
                    $refference = "";
                }

                $subscription_status = 'trial';
                $sstart = date('Y-m-d H:i:s');
                $trialTime = getTrialPeriodDuration();
                $send = date('Y-m-d H:i:s', strtotime($sstart . ' + ' . $trialTime . ' months'));
                if (intval($trialTime) == 0) {
                    $send = date('Y-m-d H:i:s', strtotime($sstart . ' + ' . '1 day'));
                }
                $online = 0;
                $cancelallow = 'yes';
                $hrsBeforeCancel = "24";
                $notification = 0;
            }

            if (!empty($this->input->post('dob'))) {
                $dob = date('Y-m-d', strtotime($this->input->post('dob')));
            } else {
                $dob = "";
            }

            try {
            
                $emailAuthenticityCheck = $antideo->email($this->input->post('email'));
                
                if($emailAuthenticityCheck->disposable || isset($emailAuthenticityCheck->scam->reports) || isset($emailAuthenticityCheck->spam->reports)|| $emailAuthenticityCheck->role_based_email){
                    echo json_encode(['success' => 0, 'message' => 'Zum Schutz unserer Salons sind keine Wegwerf E-Mail Adressen gestattet']);
                    die;
                }
            } catch (\Exception $e) {

                log_message('debug', 'Email validation exception code = '.$e->getCode().' and message='. $e->getMessage());

            }

            $additional_data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'gender' => $gender,
                'mobile' => $this->input->post('telephone'),
                'zip' => (!empty($this->input->post('post_code')) ? $this->input->post('post_code') : ''),
                'address' => (!empty($this->input->post('location')) ? $this->input->post('location') : ''),
                'latitude' => (!empty($this->input->post('latitude')) ? $this->input->post('latitude') : ''),
                'longitude' => (!empty($this->input->post('longitude')) ? $this->input->post('longitude') : ''),
                'business_name' => (!empty($this->input->post('business_name')) ? $this->input->post('business_name') : ''),
                'slug' => $slug,
                'business_type' => $b_type,
                'country' => (!empty($this->input->post('country')) ? $this->input->post('country') : 'Germany'),
                'city' => $city,
                'dob' => $dob,
                'created_on' => date("Y-m-d H:i:s"),
                'access' => $type,
                'online_booking' => $online,
                'reffrel_code' => $refference,
                'salesman_code' => $salesman_code,
                'status' => 'inactive',
                'subscription_status' => $subscription_status,
                'start_date' => $sstart,
                'end_date' => $send,
                'extra_trial_month' => 1,
                'activation_code' => encryptPass($identity),
                'newsletter' => isset($_POST['newsletter']) ? 1 : 0,
                'service_email' => isset($_POST['service_mail']) ? 1 : 0,
                'cancel_booking_allow' => $cancelallow,
                'hr_before_cancel' => $hrsBeforeCancel,
                'notification_status' => $notification,
            ];
            $actv = encryptPass($identity);

            $captcha;
            if (isset($_POST['g-recaptcha-response'])) {
                $captcha = $_POST['g-recaptcha-response'];
            }

            if (!$captcha) {
                if ($type == 'user') {
                    echo json_encode(['success' => 0, 'message'=> 'Bitte überprüfen Sie das Captcha']);die;
                } else {
                    echo json_encode(['success' => 0, 'message'=> 'Bitte überprüfen Sie das Captcha']);die;
                }             
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
                echo json_encode(['success' => 0, 'message'=> 'Bitte überprüfen Sie das Captcha']);die;
            }

            $tid = $this->ion_auth->register($identity, $password, $email, $additional_data);

            if ($tid) {
                $url = base_url('salons/' . $slug);
                if ($slug === '') {
                    $url = base_url('salons/?id=' . $tid);
                }
                
                $adminArray = array('salonname' => $b_name, 'email' => $email, 'mobile' => $this->input->post('telephone'), 'street' => $this->input->post('location'), 'city' => $this->input->post('city'), 'country' => $this->input->post('country'), 'postcode' => $this->input->post('post_code'), 'url' => $url);

                $adminMessage = $this->load->view('email/notify_admin_on_new_salon_register', $adminArray, true);

                $mail1 = emailsend('info@styletimer.de', 'Styletimer -new salon registration', $adminMessage, 'styletimer');

                $days_array = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');

                // check to see if we are creating the user
                // redirect them back to the admin page

                if ($type == 'marchant') {
                    $paymentMethod = array('Bar', 'EC-Karte', 'Kreditkarte');

                    foreach ($paymentMethod as $payname) {
                        $pinsert = array();
                        $pinsert['user_id'] = $tid;
                        $pinsert['method_name'] = $payname;

                        if ($payname == 'Bar') {
                            $pinsert['defualt'] = 1;
                        }
                        $pinsert['status'] = "active";
                        $pinsert['created_on'] = date('Y-m-d H:i:s');
                        $pinsert['created_by'] = $tid;
                        $this->db->insert('st_merchant_payment_method', $pinsert);
                    }

                    foreach ($days_array as $day) {
                        $insertArr = array();
                        $insertArr['user_id'] = $tid;
                        $insertArr['days'] = $day;
                        $insertArr['type'] = 'close';
                        $insertArr['starttime'] = '';
                        $insertArr['endtime'] = '';
                        $insertArr['created_on'] = date('Y-m-d H:i:s');
                        $insertArr['created_by'] = $tid;
                        $this->db->insert('st_availability', $insertArr);
                    }
                    //*********************************************************************************************************************************************//
                    $taxinsert = array();
                    $taxinsert['merchant_id'] = $tid;
                    $taxinsert['tax_name'] = 'Mwst';
                    $taxinsert['price'] = '19';
                    $taxinsert['defualt'] = 1;
                    $taxinsert['status'] = "active";
                    $taxinsert['created_on'] = date('Y-m-d H:i:s');
                    $taxinsert['created_by'] = $tid;

                    $this->db->insert('st_taxes', $taxinsert);

                    $taxinsert2 = array();
                    $taxinsert2['merchant_id'] = $tid;
                    $taxinsert2['tax_name'] = 'Ust.';
                    $taxinsert2['price'] = '19';
                    $taxinsert2['defualt'] = 0;
                    $taxinsert2['status'] = "active";
                    $taxinsert2['created_on'] = date('Y-m-d H:i:s');
                    $taxinsert2['created_by'] = $tid;
                    $captcha;
                    if (isset($_POST['g-recaptcha-response'])) {
                        $captcha = $_POST['g-recaptcha-response'];
                    }

                    if (!$captcha) {
                        echo json_encode(['success' => 0, 'message'=> 'Bitte überprüfen Sie das Captcha']);die;
                    }

                    //  $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lel0UoeAAAAAIvhcWXEsMIgEkgcuSn2lMl4K-tb&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);

                    if ($response['success'] == false) {
                        echo json_encode(['success' => 0, 'message'=> 'Bitte überprüfen Sie das Captcha']);die;
                    }

                    $this->db->insert('st_taxes', $taxinsert2);
                    //*********************************************************************************************************************************************//

                    $message = $this->load->view('email/merchant_activtion_link', array('link' => base_url("auth/activate/$tid/$actv"), "name" => ucwords($this->input->post('first_name')), 'business_name' => $bb_name, "button" => "ACTIVATE ACCOUNT", "msg" => "This message has been sent to you by StyleTimer. Click on the link below to Activate your account."), true);
                } else {
                    $message = $this->load->view('email/user_activtion_link', array('link' => base_url("auth/activate/$tid/$actv"), "name" => ucwords($this->input->post('first_name')), "button" => "ACTIVATE ACCOUNT", "msg" => "This message has been sent to you by StyleTimer. Click on the link below to Activate your account."), true);
                }
                $mail = emailsend($email, 'styletimer - Account aktivieren', $message, 'styletimer');
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                if ($type == 'marchant') {
                    $_SESSION['regstyle_email'] = $email;
                    $newdata = array('st_regMid' => $tid);
                    $this->session->set_userdata($newdata);
                    echo json_encode(['success' => 1]);die;
                } else {
                    echo json_encode(['success' => 1]);die;
                }
            } else {
                // display the create user form
                // set the flash data error message if there is one
                if ($type == 'user') {
                    echo json_encode(['success' => 0, 'message'=> 'Anfrage kann nicht bearbeitet werden']);die;
                } else {
                    echo json_encode(['success' => 0, 'message'=> 'Anfrage kann nicht bearbeitet werden']);die;
                }
            }
        }
        if ($type == 'user') {
            redirect(base_url('user/registration'));
        } else {
            redirect(base_url('merchant/registration'));
        }

        //$this->load->view('frontend/registration');
    }

    public function employee_register()
    {
        //$this->data['title'] = $this->lang->line('create_user_heading');
        //if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())

        if ($this->ion_auth->logged_in()) {
            redirect(base_url());
        }
        if (isset($_POST['frmsubmit'])) {

            $pass = $this->ion_auth->hash_password($this->input->post('password'));
            $update_data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'mobile' => $this->input->post('telephone'),
                'created_on' => date("Y-m-d H:i:s"),
                'status' => 'active',
                'password' => $pass,
                'activation_code' => '',
            ];
            $emp_id = $_POST['employee_id'];
            $tid = $this->db->update('st_users', $update_data, array('activation_code' => $emp_id));

            if ($tid) {
                redirect(base_url('merchant/thankyou'));
            } else {
                redirect(base_url('merchant/employee_registration/' . $_POST['employee_id']));
            }

        } else {
            redirect(base_url());
        }

    }

    public function checkemail($id = "")
    {

        //$email = $id;
        $email = $_REQUEST['email'];

        $usersSql = $this->db->select('count(*) as cnt')->from('st_users')->where('email', $email)->where('status!=', 'deleted');

        $data = $usersSql->get()->row();
        if ($data->cnt > 0) {
            echo "false";
        } else {
            echo "true";
        }

        /*if($data->cnt > 0)
    return false;
    else
    return true;*/
    }
// function loginRedirect(){

//      redirect(base_url(), 'refresh');
    //     }

    // public function emailschk()
    // {
    //    $table='st_users';
    //     $email['email']=$_POST['email'];
    //    $result = $this->ion_auth->checkemail($email,$table);
    //    if($result) { echo 'false'; } else  { echo 'true'; }
    // }
    public function logouts($status = "")
    {
        $this->data['title'] = "Logout";

        // log the user out
        $this->ion_auth->logout();

        // redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        if ($status != '') {
            $this->session->set_flashdata('error', 'Your account has been ' . $status . ' by admin. Please contact to admin');
        }

        //use site url when subdomin is working
        //redirect(SITEURL, 'refresh');
        redirect(base_url(), 'refresh');
    }

    public function checksalon_name($id = "")
    {
        $name = $_POST['business_name'];
        if (!empty($this->session->userdata('st_userid')) || $this->session->userdata('access') == 'marchant') {
            $this->db->where('id !=', $this->session->userdata('st_userid'));
        }

        $usersSql = $this->db->select('count(*) as cnt')->from('st_users')->where('business_name', trim($name))->where('status!=', 'deleted');

        $data = $usersSql->get()->row();
        if ($data->cnt > 0) {
            echo "true";die(); // true
        } else {
            echo "true";die();
        }

    }

    public function checkemail_for_profile($id = "")
    {
        $name = $_POST['email'];

        $this->db->where('id !=', $this->session->userdata('st_userid'));
        $usersSql = $this->db->select('count(*) as cnt')->from('st_users')->where('email', trim($name))->where('status!=', 'deleted');

        $data = $usersSql->get()->row();
        if ($data->cnt > 0) {
            echo "false";die(); // true
        } else {
            echo "true";die();
        }

    }

    public function resend_activation_mail()
    {
        $email = $_POST['email'];
        $usersSql = $this->db->select('id,activation_code,access,first_name,business_name,status')->from('st_users')->where('email', $email);
        //->where('status','inactive')
        $data = $usersSql->get()->row();
        if (!empty($data->id)) {
            if ($data->status == "inactive") {
                $tid = $data->id;
                $actv = $data->activation_code;
                $name = $data->first_name;
                $b_name = $data->business_name;
                if ($data->access == 'marchant') {
                    $message = $this->load->view('email/merchant_activtion_link', array('link' => base_url("auth/activate/$tid/$actv"), "name" => ucwords($name), 'business_name' => $b_name, "button" => "ACTIVATE ACCOUNT", "msg" => "This message has been sent to you by StyleTimer. Click on the link below to Activate your account."), true);
                } else {
                    $message = $this->load->view('email/user_activtion_link', array('link' => base_url("auth/activate/$tid/$actv"), "name" => ucwords($name), "button" => "ACTIVATE ACCOUNT", "msg" => "This message has been sent to you by StyleTimer. Click on the link below to Activate your account."), true);
                }
                $mail = emailsend($email, 'styletimer - Account aktivieren', $message, 'styletimer');

                echo json_encode(['success' => '1', 'message' => 'Activation link send to email']);die;
            } else {
                echo json_encode(['success' => '0', 'message' => 'Link not send, your account is ' . $data->status . '']);die;
            }
        } else {
            echo json_encode(['success' => '0', 'message' => 'email address not found']);die;
        }

    }

}
