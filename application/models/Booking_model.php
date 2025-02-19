<?php if (!defined('BASEPATH'))exit('No direct script access allowed');
/*
====================================================================================================
	* 
	* @description: This is product model for admin and all user type
	* @create date: 14-march-2018
	* 
	* 
====================================================================================================*/


class Booking_model extends MY_Model{
   
    public function __construct(){
        parent::__construct();    
    }


    function get_datacount($table,$where)
    {
		$this->db->where($where);
		$num_rows = $this->db->count_all_results($table);
		return $num_rows;
	}
	
	function selectServiceEmployee($tbl1,$tbl2 ,$field1,$field2,$where,$select,$orderby="id",$groupby="",$where_in=""){  
          $this->db->group_by($groupby); 
		  $this->db->select($select);
		  $this->db->from($tbl1);
		  $this->db->join($tbl2, $tbl1.'.'.$field1.'='.$tbl2.'.'.$field2);
		  $this->db->order_by($orderby, "desc");
		  $this->db->where($where);	
		  $this->db->where_in($where_in);	 	
		  $getdata  = $this->db->get();
		  $num = $getdata->num_rows();
		  if($num> 0){ 
			$arr=$getdata->result();
			
			return $arr;
		  } else{ 
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
    
  function join_three_row_from_same_table_for_two_users($tbl1,$tbl2,$tbl3,$field1,$field2,$field3,$field4,$where,$select){  
		  $this->db->select($select);
		  $this->db->from($tbl1);
		  $this->db->join($tbl2.' as u1', $tbl1.'.'.$field1.'=u1.'.$field2,'left');
		  $this->db->join($tbl3.' as u2', $tbl1.'.'.$field3.'=u2.'.$field4,'left');
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
   
 function join_three_booking($tbl1,$tbl2,$tbl3,$field1,$field2,$field3,$field4,$where,$select){  
		  $this->db->select($select);
		  $this->db->from($tbl1);
		  $this->db->join($tbl2, $tbl1.'.'.$field1.'='.$tbl2.'.'.$field2,'left');
		  $this->db->join($tbl3, $tbl1.'.'.$field3.'='.$tbl3.'.'.$field4,'left');
		  $this->db->where($where);	
		  $getdata  = $this->db->get();
		  $num = $getdata->num_rows();
		  if($num> 0){ 
			$arr=$getdata->result();
			
			return $arr;
		  } else{ 
		   return false;
		  }
    } 
}
