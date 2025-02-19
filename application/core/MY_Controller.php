<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Backend controller
 */
class Admin_Controller extends CI_Controller 
{

    public function __construct()
    {
        ob_start(); 
        parent::__construct();
        if (!extension_loaded('mysqli')) {
			dl('php_mysqli.dll');
		}
        
        // Load models
         $this->load->model('user_model', 'user');
         //$this->load->model('eyedrop_model', 'eyedrop');
         //$this->load->model('treatment_model', 'treatment');
         //$this->load->model('quetionaire_model', 'quetionaire');
        
        // Define workspace
        //$this->session->set_userdata(['workspace' => 'backend']);
    }

    /*
    | -------------------------------------------------------------------------
    | PUBLIC FUNCTIONS
    | -------------------------------------------------------------------------
    */
    
    
    /**
     * Check if user is logged in
     * If not redirect to login page
     */
    public function is_not_logged_in_as_admin_redirect()
    {
        if( ($this->session->userdata('acccess')=='admin')?true:false )
        { 
			//die(" ddd");
			redirect(base_url('/backend'));
            exit();
        }
    }
    
    
    
     /**
     * Check if user is logged in
     * If not redirect to login page
     */
    public function is_not_logged_in_as_user_redirect()
    {   
		//   $user_session = $this->session->userdata(SUBDOMAIN);
		//   $access = $user_session['access']; 
		 
		if( ! is_loged_in() || ! ($this->session->userdata('acccess')=='user')?true:false )
        {   
            redirect(base_url('/signin'));
            exit();
        }
    }
    
    
    
         /**
     * Check if user is logged in
     * If not redirect to login page
     */
    public function check_login_and_redirect()
    {   
		
		 $BASEURL = str_replace("www.","",$_SERVER['SERVER_NAME']);
		 $subdomain = explode('.',$BASEURL);
		 $subdomain = $subdomain[0]; 
		   
		   $access = $this->session->userdata('access');
		   
		//$access = $user_session['access']; //$this->session->userdata('access');
		if($access!=NULL){
			 //var_dump($access);die;
			 if($access=='user')redirect(base_url('user/dashboard'));
			 if($access=='marchant')redirect(base_url('marchant/dashboard'));
			 if($access=='employee')redirect(base_url('employee/dashboard'));
			 exit();
         }
		
        
    }
    /******************************************************************************************************************************/
      
    
    /**
     * Check user access permission for different page modules
     * If user do not have access display an error
     * 
     * @parem string $controller
     * @param boolean $return_boolean
     */
    /*public function check_access_permissions(string $controller = NULL, bool $return_boolean = FALSE)
    {
        $can_access = $this->auth->access_rights(get_user_from_session());
        
        if($return_boolean)
        {
            return ( ! in_array($controller, $can_access)) ? FALSE : TRUE;
        }
        
        if( ! in_array($controller, $can_access))
        {
            $message = $this->load->error_view('html/error_general', ['heading' => lang('common_access_denited_heading'), 'message' => lang('common_access_denited_message')], TRUE);
            die($message);
        }
    }*/
    
    /**
     * Check if user is logged in
     * If yes redirect to profile page
     */
    public function is_logged_in_redirect()
    {
        if(is_loged_in() && is_admin())
        {
            redirect(base_url());
            exit();
        }
    }
    
    
    
    /**
     * Return JSON
     *
     * @param $array
     * @return CI_Output
     */
    public function json($array)
    {
        return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($array));
    }

    /**
     * Return failed json with additional data
     *
     * @param array $data
     * @return CI_Output
     */
    public function json_failed($data = array())
    {
        $response = [
            'success' => FALSE,
            'csrft' => $this->security->get_csrf_token_name(),
            'csrfh' => $this->security->get_csrf_hash()
        ];

        return $this->json(array_merge($response, $data));
    }
        
    /**
     * Add successful json with additional data
     *
     * @param array $data
     * @return CI_Output
     */
    public function json_success($data = array())
    {
        $response = [
            'success' => TRUE,
            'csrft' => $this->security->get_csrf_token_name(),
            'csrfh' => $this->security->get_csrf_hash()
        ];

        return $this->json(array_merge($response, $data));
    }
}

/**
 * Frontend controller
 */
class Frontend_Controller extends CI_Controller 
{
public function __construct()
    {
        ob_start(); 
        parent::__construct();
        
       
        // Load models
         $this->load->model('user_model', 'user');
         $this->lang->load('salon_dashboard','german');
         //$this->load->model('eyedrop_model', 'eyedrop');
         //$this->load->model('treatment_model', 'treatment');
         //$this->load->model('quetionaire_model', 'quetionaire');
        
        // Define workspace
        //$this->session->set_userdata(['workspace' => 'backend']);
    }

    /*
    | -------------------------------------------------------------------------
    | PUBLIC FUNCTIONS
    | -------------------------------------------------------------------------
    */
    
    
    /**
     * Check if user is logged in
     * If not redirect to login page
     */
    public function is_not_logged_in_as_admin_redirect()
    {
        if( ($this->session->userdata('acccess')=='admin')?true:false )
        { 
			//die(" ddd");
			redirect(base_url('/backend'));
            exit();
        }
    }
    
    
    
     /**
     * Check if user is logged in
     * If not redirect to login page
     */
    public function is_not_logged_in_as_user_redirect()
    {   
		//   $user_session = $this->session->userdata(SUBDOMAIN);
		//   $access = $user_session['access']; 
		 
		if( ! is_loged_in() || ! ($this->session->userdata('acccess')=='user')?true:false )
        {   
            redirect(base_url('/signin'));
            exit();
        }
    }
    
    
    
         /**
     * Check if user is logged in
     * If not redirect to login page
     */
    public function check_login_and_redirect()
    {   
		
		 $BASEURL = str_replace("www.","",$_SERVER['SERVER_NAME']);
		 $subdomain = explode('.',$BASEURL);
		 $subdomain = $subdomain[0]; 
		   
		   $access = $this->session->userdata('access');
		   
		//$access = $user_session['access']; //$this->session->userdata('access');
		if($access!=NULL){
			 //var_dump($access);die;
			 if($access=='user')redirect(base_url('user/dashboard'));
			 if($access=='marchant')redirect(base_url('marchant/dashboard'));
			 if($access=='employee')redirect(base_url('employee/dashboard'));
			 exit();
         }
		
        
    }
    /******************************************************************************************************************************/
      
    
    /**
     * Check user access permission for different page modules
     * If user do not have access display an error
     * 
     * @parem string $controller
     * @param boolean $return_boolean
     */
    /*public function check_access_permissions(string $controller = NULL, bool $return_boolean = FALSE)
    {
        $can_access = $this->auth->access_rights(get_user_from_session());
        
        if($return_boolean)
        {
            return ( ! in_array($controller, $can_access)) ? FALSE : TRUE;
        }
        
        if( ! in_array($controller, $can_access))
        {
            $message = $this->load->error_view('html/error_general', ['heading' => lang('common_access_denited_heading'), 'message' => lang('common_access_denited_message')], TRUE);
            die($message);
        }
    }*/
    
    /**
     * Check if user is logged in
     * If yes redirect to profile page
     */
    public function is_logged_in_redirect()
    {
        if(is_loged_in() && is_admin())
        {
            redirect(base_url());
            exit();
        }
    }
    
    
    
    /**
     * Return JSON
     *
     * @param $array
     * @return CI_Output
     */
    public function json($array)
    {
        return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($array));
    }

    /**
     * Return failed json with additional data
     *
     * @param array $data
     * @return CI_Output
     */
    public function json_failed($data = array())
    {
        $response = [
            'success' => FALSE,
            'csrft' => $this->security->get_csrf_token_name(),
            'csrfh' => $this->security->get_csrf_hash()
        ];

        return $this->json(array_merge($response, $data));
    }
        
    /**
     * Add successful json with additional data
     *
     * @param array $data
     * @return CI_Output
     */
    public function json_success($data = array())
    {
        $response = [
            'success' => TRUE,
            'csrft' => $this->security->get_csrf_token_name(),
            'csrfh' => $this->security->get_csrf_hash()
        ];

        return $this->json(array_merge($response, $data));
    }

}
