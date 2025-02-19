<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_model extends CI_Model{ 
	
	public function __construct()
	{
			// Call the CI_Model constructor
			parent::__construct();
	}
	
	
	function select($table='', $field='*',  $whereField='', $whereValue='', $orderBy='id', $order='DESC', $limit=0, $offset=0, $resultInArray=false,$like='',$like1='',$where_in=false){
		
		$table=$table;
		 $this->db->select($field);
		 $this->db->from($table);
		
		//~ if(is_array($whereField)){
		   //~ foreach($whereField as $k=>$v){
			  //~ if(is_array($v)){
				  //~ $this->db->where_in($k, $v);
				  //~ unset($whereField[$k]);
			  //~ }   
		   //~ }	
		//~ }
		
		
		if(is_array($whereField)){
			$this->db->where($whereField);
		}elseif(!empty($whereField) && $whereValue != ''){
			$this->db->where($whereField, $whereValue);
		}
		
		if($like != ''){
			$lk = explode('_',$like);
			$this->db->like($lk[0],$lk[1]);
		}
       
        if($like1 != ''){
			$lk1 = explode('_',$like1);
			$this->db->like($lk1[0],$lk1[1]);
		} 
		
		if($where_in!=false){ //echo "<pre>"; print_r($where_in); die;
			$this->db->where_in($where_in[0],$where_in[1]);
		}

		if(!empty($orderBy)){  
			$this->db->order_by($orderBy, $order);
		}
		if($limit > 0){
			$this->db->limit($limit,$offset);
		}
		
		
		$query = $this->db->get();
		
		//echo $this->db->last_query(); die;
		if($resultInArray){
			$result = $query->result_array();
		}else{
			$result = $query->result();
		}
		
		if(!empty($result)){
			return 	$result;
		}
		else{
			return FALSE;
		}
	}

	 public function access_token_check($uid = NULL, $device_type, $device_id, $access_token = NULL){
    	$this->db->select('*');
    	$this->db->from('st_devices');
    	if(!empty($uid)){
    		$this->db->where('uid', $uid);
    	}
    	$this->db->where('device_type', $device_type);	
    	$this->db->where('device_id', $device_id);	
    	if(!empty($access_token)){
    		$this->db->where('access_token', $access_token);	
    	}
		
    	$query = $this->db->get();

    	$result = $query->row();
		return $result;

    }
    
    function select_row($table='',$field='',$where=''){
		
		$result = false;
		if($table != '' && $field !=''){
			$this->db->select($field);
			if(is_array($where) && count($where) > 0){
				$this->db->where($where);
			}
			$query = $this->db->get($table);
			$result=$query->row();
		}
		return $result;
	}
    // delete access token entry
    public function access_token_delete($uid = NULL, $device_id){
    	if(!empty($uid)){
    		$this->db->where('uid', $uid);	
    	}
    	$this->db->where('device_id', $device_id);	
		// $this->db->where('access_token', $access_token);	
    	$effected_rows = $this->db->delete('st_devices');	
    	
    	return $effected_rows;
    }
    // Save access token of the devices
    public function saveAccessToken($device_id, $device_type, $access_token){
    	$current_time = time();  
    	$data = array(
    		'device_id' => $device_id,
    		'device_type' => $device_type,
    		'access_token' => $access_token,
    		'created' => $current_time,
    		'last_access' => $current_time
    	);

    	$r = $this->db->insert('st_devices', $data);	
    	return $r;
    }

    // Update token of the devices
	 public function update_uid_AccessToken($uid, $device_id, $device_type, $access_token){

    	// echo 'UID: ' . $uid . '<br/>';
    	// echo 'device_id: ' .  $device_id . '<br/>';
    	// echo 'device_type: ' . $device_type . '<br/>';
    	// echo 'access_token: ' .  $access_token . '<br/>';

    	$current_time = time();
    	$update = array(
    		'uid' => $uid
    	);

    	$where = array(
    		'device_id' => $device_id,
    		'device_type' => $device_type,
    		'access_token' => $access_token
    	);

    	$this->db->where($where);

    	$r = $this->db->update('st_devices', $update);	
    	return $r;
    }
    // for login 
	public function login($identity)
	{
        //$where=array('email' => $identity, 'status !=' => 'deleted', 'access' => 'user');
        $where=array('email' => $identity,'status !='=>'deleted');
		$this->db->select('id, first_name, last_name, email,access ,password,gender, country, city, profile_pic, mobile, dob , zip, address, latitude, longitude, status,login_status');
		$this->db->from('st_users');
		$this->db->where($where);	
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}

    // update 
    public function access_token_update_time($uid, $device_id, $access_token){
        $this->db->where('uid', $uid);  
        $this->db->where('device_id', $device_id);
        $this->db->where('access_token', $access_token);
        $this->db->update('st_devices', array('last_access' => time()));
    }


    // ------------------------------- COMMON FUNCTIONS -----------------------
   
   
    function insert($table, $data){
		$this->db->insert($table,$data);
		$num = $this->db->insert_id();
		if($num)
			return $num;
		else
			return FALSE;
	}

    function update($table,$where,$data){
        $this->db->where($where);
        $update = $this->db->update($table,$data);      
        if($update) 
            return TRUE;
        else 
            return FALSE;
    }
    
    function updateCI($table='', $data=array(), $field='', $ID=0){
		$table=$table;
		if(empty($table) || !count($data)){
			return false;
		}
		else{
			if(is_array($field)){
				
				$this->db->where($field);
			}else{
				$this->db->where($field , $ID);
			}
			return $REs = $this->db->update($table , $data);
			//echo $this->db->last_query(); die;
		}
	}
    
    function delete($table,$where)
    {
        $table=$table;
        $this->db->delete($table, $where);
        return $this->db->affected_rows();
    }

    function getWhereRowSelect($table,$where,$select='*'){ 
        $this->db->select($select);
        $this->db->where($where);
        $getdata = $this->db->get($table);      
        $result = $getdata->row();
        return $result;
    }

    function join_two($tbl1,$tbl2 ,$field1,$field2,$where,$select,$group_by="",$jtype='left')
    {   
          $this->db->group_by($group_by);
          $this->db->select($select);
          $this->db->from($tbl1);
          $this->db->join($tbl2, $tbl1.'.'.$field1.'='.$tbl2.'.'.$field2,$jtype);
          $this->db->where($where);     
          $getdata  = $this->db->get();
          //echo $this->db->last_query(); die;
          $num = $getdata->num_rows();
          if($num> 0) { 
            $arr=$getdata->result();
            return $arr;
          } else 
           return false;
    }   

        function join_twomyChange($tbl1,$tbl2 ,$field1,$field2,$where,$select,$group_by="",$jtype='left')
    {   
          $this->db->group_by($group_by);
          $this->db->select($select);
          $this->db->from($tbl1);
          $this->db->join($tbl2, $tbl1.'.'.$field1.'='.$tbl2.'.'.$field2,$jtype);
          $this->db->where($where);  
          $getdata  = $this->db->get();
         
          $num = $getdata->num_rows();
          if($num> 0) { 
            $arr=$getdata->result();
            return $arr;
          } else 
           return false;
    }   
   
    function custome_query($sql,$type='result')
    {
        $query = $this->db->query($sql);
        
        if($type=='result'){
            $res = $query->result();    
        }else{
            $res = $query->row();       
        }
        
        if($res==null)return false;  
        else return $res; 
         
    }

    function getWhereAllselect($table,$where,$select='*')
    {   
        $this->db->select($select);
        if($where !='')
            $this->db->where($where);
        
        $getdata = $this->db->get($table);  
        $num = $getdata->num_rows();
        if($num> 0){ 
                $arr=$getdata->result();
                foreach ($arr as $rows)
                {
                    $data[] = $rows;
                }
                $getdata->free_result();
                
                return $data;
        }else 
            return false;
    }

    function getWhere($table,$where,$select="*",$limit="",$offset="",$short="id",$order="desc",$search=""){ 
        $this->db->select($select);
        $this->db->order_by($short,$order);
        $this->db->limit($limit,$offset); 
        $this->db->where($where);
        
        $getdata = $this->db->get($table);  
        $num = $getdata->num_rows();
        if($num> 0){ 
                $arr=$getdata->result();
                foreach ($arr as $rows)
                {
                    $data[] = $rows;
                }
                $getdata->free_result();
                
                return $data;
        }else{ 
            return false;
        }       
    }

    function get_datacount($table,$where)
    {
        $this->db->where($where);
        $num_rows = $this->db->count_all_results($table);
        return $num_rows;
    }

       public function getbookinglistmyChange($fild,$where,$limit=0, $offset=0,$where_or='',$shortby="booking_time",$order="DESC")
   {
      
    $this->db->select($fild);
    $this->db->order_by($shortby,$order);
        $this->db->from('st_booking');
        //$this->db->join('st_booking_detail as sbd', 'sbd.booking_id'.'='.'st_booking.id','left');
        $this->db->join('st_users as us', 'us.id'.'='.'st_booking.merchant_id','left');
        //$this->db->join('st_users as us2', 'us2.id'.'='.'st_booking.'.$acc,'left');
        $this->db->where($where);   
        if($where_or != ''){
            $this->db->or_where($where_or);
        }

        if($limit > 0){
            $this->db->limit($limit,$offset);
        }
        
        $getdata  = $this->db->get();
       // echo $this->db->last_query();die;
        $num = $getdata->num_rows();
        if($num> 0) { 
            $arr=$getdata->result();
          //  print_r($arr);die;
            return $arr;
        } else 
       return false;
   }

    public function getbookinglist($where,$limit=0, $offset=0,$where_or='',$shortby="booking_time",$order="DESC")
   {
    $this->db->select('st_booking.id,st_booking.book_id,us.business_name,us.mobile ,us.cancel_booking_allow,us.hr_before_cancel,reshedule_count_byuser,st_booking.employee_id,us.address,city,zip,booking_time,total_minutes,total_price,st_booking.id,st_booking.merchant_id,st_booking.status,us.end_date,us.online_booking');
    $this->db->order_by($shortby,$order);
        $this->db->from('st_booking');
        //$this->db->join('st_booking_detail as sbd', 'sbd.booking_id'.'='.'st_booking.id','left');
        $this->db->join('st_users as us', 'us.id'.'='.'st_booking.merchant_id','left');
        //$this->db->join('st_users as us2', 'us2.id'.'='.'st_booking.'.$acc,'left');
        $this->db->where($where);   
        if($where_or != ''){
            $this->db->or_where($where_or);
        }

        if($limit > 0){
            $this->db->limit($limit,$offset);
        }
        
        $getdata  = $this->db->get();
        $num = $getdata->num_rows();
        if($num> 0) { 
            $arr=$getdata->result();
          //  print_r($arr);die;
            return $arr;
        } else 
       return false;
   }

   public function socialLogin($unique_id,$email,$type)
    {
        $this->db->select('id, first_name, last_name, email,access ,password,gender, country, city, profile_pic, mobile, dob, zip, address, latitude, longitude, status,login_status, access');
        $this->db->from('st_users');
        $this->db->where('unique_id', $unique_id); 
        
        if($type!='apple') 
          $this->db->or_where('email', $email);   
        //~ $this->db->or_where('mobile_no', $phone);   
        $this->db->where('status !=', 'deleted');   
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function getWhereLike($table,$where,$select="*",$limit="",$offset="",$short="id",$order="desc",$search="",$colum=""){   
        $this->db->select($select);
        $this->db->order_by($short,$order);
        $this->db->limit($limit,$offset); 
        $this->db->where($where);
        if($search!=""){
          $this->db->like($colum,$search);
          } 
        $getdata = $this->db->get($table);  
        $num = $getdata->num_rows();
        if($num> 0){ 
                $arr=$getdata->result();
                foreach ($arr as $rows)
                {
                    $data[] = $rows;
                }
                $getdata->free_result();
                
                return $data;
        }else{ 
            return false;
        }       
    }

    function join_three_row($tbl1,$tbl2,$tbl3,$field1,$field2,$field3,$field4,$where,$select){  
          $this->db->select($select);
          $this->db->from($tbl1);
          $this->db->join($tbl2, $tbl1.'.'.$field1.'='.$tbl2.'.'.$field2);
          $this->db->join($tbl3, $tbl2.'.'.$field3.'='.$tbl3.'.'.$field4);
          $this->db->where($where); 
          $getdata  = $this->db->get();
          $num = $getdata->num_rows();
          if($num> 0){ 
            $arr=$getdata->row();
            
            return $arr;
          } else{ 
           return false;
          }
    }

}

?>
