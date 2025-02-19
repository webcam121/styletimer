<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class MY_Model extends CI_Model{
   
    function __construct(){
        parent::__construct();  
        if (!extension_loaded('mysqli')) {
			dl('php_mysqli.dll');
		}  
         //$this->load->library('image_lib');
    }
    
    /***Select****************
	  *
	  * @description: This function is used for get all data
	  * 
	  ********/
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
	
	
	
	
	/***Select single data****************
	  *
	  * @description: This function is used for get single data
	  * 
	  ********/
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
	
	
	/***Insert****************/
    /*
	 * @description: This function is used addDataIntoTabel
	 * 
	 */
	
	function insert($table='', $data=array()){
		$table=$table;
		if($table=='' || !count($data)){
			return false;
		}
		  $uid = 1;
		  if($this->session->userdata("st_userid")!='')$uid = $this->session->userdata("st_userid");
		$data['created_by']= $uid;
		$data['created_on']= date("Y-m-d H:i:s");
		$data['updated_by']= $uid;
		$data['updated_on']= date("Y-m-d H:i:s");
		
		$inserted = $this->db->insert($table , $data);
		//$this->db->last_query();
		$ID = $this->db->insert_id();
		return $ID;
	}
    
    
    /***Update****************/
    /*
	 * @description: This function is used updateDataFromTabel
	 * 
	 */
	 
	function update($table='', $data=array(), $field='', $ID=0){
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
	
	
	/***Delete****************/
    /*
	 * @description: This function is used deleteRow
	 * 
	 */

	function delete($table,$where)
	{
		$table=$table;
		$this->db->delete($table, $where);
		//echo $sql = $this->db->last_query(); die;
		return $this->db->affected_rows();
	}
	

	function join_two($tbl1,$tbl2 ,$field1,$field2,$where,$select,$group_by="",$jtype='left')
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
	
function join_two_row($tbl1,$tbl2 ,$field1,$field2,$where,$select,$group_by="",$jtype='left')
	{   
	      $this->db->group_by($group_by);
		  $this->db->select($select);
		  $this->db->from($tbl1);
		  $this->db->join($tbl2, $tbl1.'.'.$field1.'='.$tbl2.'.'.$field2,$jtype);
		  $this->db->where($where);		
		  $getdata  = $this->db->get();
		  $num = $getdata->num_rows();
		  if($num> 0) { 
			$arr=$getdata->row();
			return $arr;
		  } else 
		   return false;
	}	
	
	
/****************************************************************************************************************************************************/	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

    
    
    
    
    

    
    
    
    /***Custome Query****************/
    /*
	 * @description: This function is fetching data by custome query
	 * 
	 */

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
    
    
    
    /****
	 * 
	 * Mail function 
	 * **/
    public function emailsend($to,$subject,$msg,$from,$file='',$filename='',$content='',$type='')
		{
			
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://smtp.googlemail.com',
				'smtp_port' => 465,
				'smtp_user' => 'harshan.shantiinfotech@gmail.com',
				'smtp_pass' => 'harshan123456',
				'mailtype' => 'html', 
				'charset' => 'iso-8859-1'
			);
			$CI =& get_instance();
			$CI->load->library('email', $config);
			$CI->email->initialize($config);
			$CI->email->set_mailtype("html");
			$CI->email->set_newline("\r\n");
			$CI->email->from(ADMIN_EMAIL_SEND, $from);
			$CI->email->to( $to );
			$CI->email->subject($subject);
			$CI->email->message($msg);
			//~ if($file!="")
				//~ $CI->email->attach( $file);
			if(!empty($file) && $type=='pdf')
			{
				for($i=0;$i<count($file);$i++)
				{
					$CI->email->attach($file[$i]);
				}
			}
			else
			{
				$CI->email->attach( $file);
			}
				
			$result = $CI->email->send();
				
			if ($result) {
				return true;
			} else {
				return false;
			}
			
		}
    
    
    /***response****************/
    function Rinsert($table='', $data=array()){
		$table=$table;
		if($table=='' || !count($data)){
			return false;
		}
		$inserted = $this->db->insert($table , $data);
		//$this->db->last_query();
		$ID = $this->db->insert_id();
		return $ID;
	}
    
    /**************************/
    
    
     /***Select****************/
    /*
	 * @description: This function is used for get all data
	 * 
	 */

    
    
    
    
    
    
    
    
    
    

   	/*
	 * @description: This function is used getSum
	 * 
	 */ 
	function getMax($table='',$field='',$where=''){
		
		$this->db->select_max($field);
		if(is_array($where) && count($where) > 0){
			$this->db->where($where);
		}
		$query = $this->db->get($table);
		$result=$query->result();
		return $result;
	}
	
	/*
	 * @description: This function is used getSum
	 * 
	 */ 
	
	function getSum($table='',$field='',$where=''){
		$table='gmc_'.$table;
		$result = false;
		if($table != '' && $field !=''){
			$this->db->select_sum($field);
			if(is_array($where) && count($where) > 0){
				$this->db->where($where);
			}
			$query = $this->db->get($table);
			$result=$query->result();
		}
		return $result;
	}
	
	/*
	 * @description: This function is used countResult
	 * 
	 */ 
	
	function countResult($table='',$field='',$value='', $limit=0){
	
		if(is_array($field)){
				$this->db->where($field);
		}
		elseif($field!='' && $value!=''){
			$this->db->where($field, $value);
		}else{
			$this->db->where($field);
			}
		$this->db->from($table);
		
		if($limit >0){
			$this->db->limit($limit);
		}
		
		 $res= $this->db->count_all_results();
		// echo $this->db->last_query();
		 return $res;
		 
	}

	
	/*
	 * @description: This function is used getDataFromTabelWhereIn
	 * 
	 */ 
	
	function getDataFromTableWhereIn($table='', $field='*',  $whereField='', $whereValue='', $orderBy='', $order='ASC', $whereNotIn=0,$array_type='array'){
		//echo $table; die;
		$table=$table;
		 $this->db->select($field);
		 $this->db->from($table);
		 
		if($whereNotIn > 0){
			$this->db->where_not_in($whereField, $whereValue);
		}else{
			$this->db->where_in($whereField, $whereValue);
		}
		
		if(is_array($orderBy) && count($orderBy)){
			/* $orderBy treat as where condition if $orderBy is array  */
			$this->db->where($orderBy);
		}
		elseif(!empty($orderBy)){  
			$this->db->order_by($orderBy, $order);
		}
		
		$query = $this->db->get();
		//echo $array_type; die;
		if($array_type=='array')$result = $query->result_array();
		if($array_type=='object')$result = $query->result();
		if(!empty($result)){
			return 	$result;
		}
		else{
			return FALSE;
		}
	}
	
	
	/*
	 * @description: This function is used getDataFromTabel
	 * 
	 */
	
	function getObjectDataFromTable($table='', $field='*',  $whereField='',$whereInField='',$whereNotIn=''){
		
		$table=$table;
		$this->db->select($field);
		$this->db->from($table);
		$this->db->where($whereField);
		if($whereInField!=''){
			$this->db->where_not_in($whereInField, $whereNotIn);
		}
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->row();
		
		return 	$result;
	}

	/*
	 * @description: This function is used getDataFromTabelWhereWhereIn
	 * 
	 */
	
	function getDataFromTableWhereWhereIn($table='', $field='*',  $where='',  $whereinField='', $whereinValue='', $orderBy='', $whereNotIn=0){
	
		$table=$table;
		 $this->db->select($field);
		 $this->db->from($table);
		 
		if(is_array($where)){
			$this->db->where($where);
		}
		
		if($whereNotIn > 0){
			$this->db->where_not_in($whereinField, $whereinValue);
		}else{
			$this->db->where_in($whereinField, $whereinValue);
		}
		
		if(!empty($orderBy)){  
			$this->db->order_by($orderBy);
		}
		
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result();
		if(!empty($result)){
			return 	$result;
		}
		else{
			return FALSE;
		}
	}
	
	
	/*
	 * @description: This function is used getDataFromTabel
	 * 
	 */
	
	function getDataFromTable($table='', $field='*',  $whereField='', $whereValue='', $orderBy='', $order='ASC', $limit=0, $offset=0, $resultInArray=false  ){
		
		$table=$table;
		 $this->db->select($field);
		 $this->db->from($table);
		
		if(is_array($whereField)){
			$this->db->where($whereField);
		}elseif(!empty($whereField) && $whereValue != ''){
			$this->db->where($whereField, $whereValue);
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
	/*
	 * @description: This function is used getLikeDataFromTabel
	 * 
	 */
	
	function getLikeDataFromTabel($table='', $field='*',  $like='', $where='', $orderBy='', $order='ASC', $limit=0 ){
		
		$table=$table;
		//echo $table.', '.$field.', '.$whereField.', '.$whereValue.', '.$orderBy;
		 $this->db->select($field);
		 $this->db->from($table);
		 
		if(is_array($like)){
			$this->db->like($like);
		}elseif(is_array($where)){
			$this->db->where($where);
		}
		if(!empty($orderBy)){  
			$this->db->order_by($orderBy, $order);
		}
		if($limit >0){
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		
		$result = $query->result();
		if(!empty($result)){
			return 	$result;
		}
		else{
			return FALSE;
		}
	}
	
	
	
	
	/*
	 * @description: This function is used updateDataFromTabelWhereIn
	 * 
	 */
	
	function updateDataFromTabelWhereIn($table='', $data=array(), $where=array(), $whereInField='', $whereIn=array(), $whereNotIn=false){
		$table=$table;
		if(empty($table) || !count($data)){
			return false;
		}
		else{
			if(is_array($where) && count($where) > 0){
				
				$this->db->where($where);
			}
			
			if(is_array($whereIn) && count($whereIn) > 0 && $whereInField != ''){
				if($whereNotIn){
					$this->db->where_not_in($whereInField,$whereIn);
				}else{
					$this->db->where_in($whereInField,$whereIn);
				}
			}
			return $this->db->update($table , $data);
		}
	}
	
	
	/*
	 * @description: This function is used deleteRowFromTabel
	 * 
	 */
	 
	function deleteRowFromTable($table='', $field='', $ID=0, $limit=0){
		$table=$table;
		$Flag=false;
		if($table!='' && $field!=''){
			if(is_array($ID) && count($ID)){
				$this->db->where_in($field ,$ID);
			}elseif(is_array($field) && count($field) > 0){
				$this->db->where($field);
			}else{
				$this->db->where($field , $ID);
			}
			if($limit >0){
				$this->db->limit($limit);
			}
			if($this->db->delete($table)){
				$Flag=true;
			}
		}
		//echo $this->db->last_query();
		return $Flag;
	}
	
	/*
	 * @description: This function is used deletelWhereWhereIn
	 * 
	 */
	 
	 
	function deletelWhereWhereIn($table='', $where='',  $whereinField='', $whereinValue='', $whereNotIn=0){
		$table=$table;
		if(is_array($where)){
			$this->db->where($where);
		}
		
		if($whereNotIn > 0){
			$this->db->where_not_in($whereinField, $whereNotIn);
		}else{
			$this->db->where_in($whereinField, $whereinValue);
		}
		
		if($this->db->delete($table)){
				return true;
		}else{
			return false;
		}
	}
	
	
	
	
	
		
	
	/* 
     * Get JSON DATA FROM DATABASE
     * @return array
     * */
	function getObjectDataFromIDAndgetJSONdataFromID($table,$field,$users_id='',$id=''){
		$this->db->select('*');
		$this->db->order_by('id', 'desc');
		
		if(!empty($id)){	
			$this->db->where('id',$id);
			$res = $this->db->get($table);
			return $res->row();
		}else{
			$res = $this->db->get($table);
			$resul= $res->result();		
			$valuearray =array();
			foreach($resul as $value){
				$array =json_decode($value->$field);
				if(in_array($users_id,$array)) {
					$valuearray[] = $value;
				}
			}
			return $valuearray;			
		}
		
    }
    
    /* 
     * Get COMMA Separated DATA FROM DATABASE
     * @return array
     * */
    function getCOMMASeparateddataFromUserIDAndID($table,$field,$user_id,$id='',$status='1',$order='desc'){		
        if(!empty($table)){			
			$this->db->select('*');
			$this->db->where(array('status'=>$status));
			if(!empty($id)){	
				$this->db->where('id',$id);
				$res = $this->db->get($table);
				return $res->row();
			}else{
				$this->db->order_by('id', $order);
				$this->db->where("$field REGEXP  '(^|,)(".$user_id.")(,|$)'");
				$res = $this->db->get($table);
				return $res->result();
			}
		}else{
			return false;
		}           
	}
	
	
	
	
    
}

