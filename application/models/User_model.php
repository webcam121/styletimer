<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
====================================================================================================
 *
 * @description: This is product model for admin and all user type
 * @create date: 14-march-2018
 *
 *
====================================================================================================*/

class User_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * For Backend
     * */

    /**
     * For Forntend
     * */
    public function checkUser($data = array())
    {
        $this->tableName = 'st_users';
        $this->primaryKey = 'id,access';
        $this->db->select($this->primaryKey);
        $this->db->from($this->tableName);
        $this->db->where(array('email' => $data['email'], 'status !=' => 'deleted'));
        $prevQuery = $this->db->get();
        $prevCheck = $prevQuery->row();

        if (!empty($prevCheck)) {
            if ($prevCheck->access == 'user') {
                //$prevResult = $prevQuery->row_array();
                $data['updated_on'] = date("Y-m-d H:i:s");
                $update = $this->db->update($this->tableName, $data, array('id' => $prevCheck->id));
                $userID = $prevCheck->id;
            } else {
                $userID = $prevCheck->id;
            }
        } else {
            $data['access'] = "user";
            $data['created_on'] = date("Y-m-d H:i:s");
            $data['updated_on'] = date("Y-m-d H:i:s");
            $insert = $this->db->insert($this->tableName, $data);
            $userID = $this->db->insert_id();
        }
        return $userID ? $userID : false;
    }

    public function getservicelist($where, $whereIn = [], $limit = 0, $offset = 0)
    {
        //$where= array('');
        // $this->db->order_by('r.id', 'desc');
        $this->db->select('r.id as sid,r.price_start_option,subcategory_id,name,duration,price,buffer_time,discount_price,u1.category_name as m_category, u2.category_name as s_category, u3.category_name as filtercat_name');
        $this->db->from('st_merchant_category r');
        $this->db->join('st_category u1', 'r.category_id' . '=' . 'u1.id');
        $this->db->join('st_category u2', 'r.subcategory_id' . '=' . 'u2.id');
        $this->db->join('st_filter_category u3', 'r.filtercat_id' . '=' . 'u3.id');

        $this->db->where($where);
        if (count($whereIn)) {
            $this->db->where_in('r.id', $whereIn);
        }
        $this->db->order_by('m_category', 'asc');
        $this->db->order_by('filtercat_name', 'asc');
        $this->db->order_by('s_category', 'asc');

        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $getdata = $this->db->get();
        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();
            $usermodel = $this->user;
            $arr = array_map(function ($item) use ($usermodel){
                return array_merge(
                    (array)$item,
                    [
                        'subService' => $usermodel->select(
                            'st_merchant_category',
                            '*',
                            ['parent_service_id' => $item->sid, 'status' => 'active'],
                            '',
                            'id',
                            'ASC'
                        )
                    ]
                );
            }, $arr);
            return $arr;
        } else {
            return false;
        }

    }

    public function get_all_service_of_merchant($merchant_id, $search = '')
    {
        if ($search == '') {
            $sql = "SELECT st_merchant_category.id,st_merchant_category.subcategory_id,st_merchant_category.name,st_merchant_category.tax_id,st_merchant_category.duration,st_merchant_category.price,st_merchant_category.discount_price,st_category.category_name FROM `st_merchant_category` JOIN st_category on st_category.id = st_merchant_category.subcategory_id where st_merchant_category.created_by = {$merchant_id} and st_merchant_category.status!='deleted'";
        } else {
            $sql = "SELECT st_merchant_category.id,st_merchant_category.subcategory_id,st_merchant_category.name,st_merchant_category.tax_id,st_merchant_category.duration,st_merchant_category.price,st_merchant_category.discount_price,st_category.category_name FROM `st_merchant_category` JOIN st_category on st_category.id = st_merchant_category.subcategory_id where st_merchant_category.created_by = {$merchant_id} and st_merchant_category.status!='deleted' and (st_merchant_category.name LIKE '%{$search}%' OR st_category.category_name LIKE '%{$search}%')";
            //AND st_merchant_category.name=''
        }
        $query = $this->db->query($sql);

        $services = $query->result();

        if ($services) {

            $services_by_category = [];

            foreach ($services as $service) {
                $services_by_category[$service->category_name][] = $service;
            }

            return $services_by_category;

        } else {
            return false;
        }

    }

    public function select_custome_orderBy($table = '', $field = '*', $whereField = '', $whereValue = '', $orderBy = 'id.asc', $limit = 0, $offset = 0, $resultInArray = false, $like = '', $like1 = '', $where_in = false)
    {

        $table = $table;
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

        if (is_array($whereField)) {
            $this->db->where($whereField);
        } elseif (!empty($whereField) && $whereValue != '') {
            $this->db->where($whereField, $whereValue);
        }

        if ($like != '') {
            $lk = explode('_', $like);
            $this->db->like($lk[0], $lk[1]);
        }

        if ($like1 != '') {
            $lk1 = explode('_', $like1);
            $this->db->like($lk1[0], $lk1[1]);
        }

        if ($where_in != false) { //echo "<pre>"; print_r($where_in); die;
            $this->db->where_in($where_in[0], $where_in[1]);
        }

        if (!empty($orderBy)) {
            $aa = explode(' ', $orderBy);
            for($i = 0; $i < count($aa); $i = $i + 2) {
                $this->db->order_by($aa[$i], $aa[$i + 1]);
            }
        }
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();

        //echo $this->db->last_query(); die;
        if ($resultInArray) {
            $result = $query->result_array();
        } else {
            $result = $query->result();
        }

        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    public function join_two_orderby($tbl1, $tbl2, $field1, $field2, $where, $select, $orderby = "id", $groupby = "", $limit = 0, $offset = 0, $search = "", $short = "asc", $jointype = "")
    {
        $this->db->group_by($groupby);
        $this->db->select($select);
        $this->db->from($tbl1);
        if (!empty($jointype)) {
            $this->db->join($tbl2, $tbl1 . '.' . $field1 . '=' . $tbl2 . '.' . $field2, $jointype);
        } else {
            $this->db->join($tbl2, $tbl1 . '.' . $field1 . '=' . $tbl2 . '.' . $field2);
        }
        $this->db->order_by($orderby, $short);
        //$this->db->order_by($orderby, "desc");
        $this->db->where($where);
        if ($search != "") {
            $whr = "(" . $tbl2 . ".first_name LIKE '%" . $search . "%' OR " . $tbl2 . ".last_name LIKE '%" . $search . "%' OR " . $tbl2 . ".email LIKE '%" . $search . "%')";
            $this->db->where($whr);
            //$this->db->or_like($tbl2.'.last_name',$search);
            //$this->db->or_like($tbl2.'.email',$search);
        }
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        $getdata = $this->db->get();
        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();

            return $arr;
        } else {
            return false;
        }

    }

    public function join_two_orderbyMy($tbl1, $tbl2, $field1, $field2, $where, $currentData, $select, $orderby = "id", $groupby = "", $limit = 0, $offset = 0, $search = "", $short = "asc", $jointype = "")
    {
        $this->db->group_by($groupby);
        $this->db->select($select);
        $this->db->from($tbl1);
        if (!empty($jointype)) {
            $this->db->join($tbl2, $tbl1 . '.' . $field1 . '=' . $tbl2 . '.' . $field2, $jointype);
        } else {
            $this->db->join($tbl2, $tbl1 . '.' . $field1 . '=' . $tbl2 . '.' . $field2);
        }
        $this->db->order_by($orderby, $short);
        //$this->db->order_by($orderby, "desc");
        $this->db->where($where);
        $this->db->having('endTrial  > ', $currentData, false);
        $this->db->or_having('online_booking = "1" AND allow_online_booking = "true"');
        //   $this->db->where($triEndData .'>='.','.$currentData);
        if ($search != "") {
            $whr = "(" . $tbl2 . ".first_name LIKE '%" . $search . "%' OR " . $tbl2 . ".last_name LIKE '%" . $search . "%' OR " . $tbl2 . ".email LIKE '%" . $search . "%')";
            $this->db->where($whr);
            //$this->db->or_like($tbl2.'.last_name',$search);
            //$this->db->or_like($tbl2.'.email',$search);
        }
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        $getdata = $this->db->get();
        // echo $this->db->last_query(); die;
        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();

            return $arr;
        } else {
            return false;
        }
    }

    public function getTopServiceData($select, $where)
    {
        $this->db->select($select);
        $this->db->from('st_merchant_category');
        $this->db->join('st_booking_detail', 'st_merchant_category.id' . '=' . 'st_booking_detail.service_id');
        $this->db->join('st_booking', 'st_booking_detail.booking_id' . '=' . 'st_booking.id');
        $this->db->group_by('st_booking_detail.service_id');
        $this->db->order_by('count', "desc");
        $this->db->where($where);
        $getdata = $this->db->get();
        //  echo $this->db->last_query();
        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();
            return $arr;
        } else {
            return false;
        }

    }

    public function join_three_orderby($tbl1, $tbl2, $tbl3, $field1, $field2, $field3, $field4, $where, $select, $orderby = "id", $groupby = "", $limit = 0, $offset = 0)
    {
        $this->db->group_by($groupby);
        $this->db->select($select);
        $this->db->from($tbl1);
        $this->db->join($tbl2, $tbl1 . '.' . $field1 . '=' . $tbl2 . '.' . $field2);
        $this->db->join($tbl3, $tbl2 . '.' . $field3 . '=' . $tbl3 . '.' . $field4);
        $this->db->order_by($orderby, "desc");
        $this->db->where($where);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        $getdata = $this->db->get();
        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();

            return $arr;
        } else {
            return false;
        }
    }

    public function getbookinglist($where, $limit = 0, $offset = 0, $acc = 'employee_id', $order = '')
    {
        $this->db->select('us2.first_name,us2.last_name,us2.profile_pic,
	   us.address,us.mobile,us.cancel_booking_allow,us.hr_before_cancel,us.zip,
	   us.city,us.business_name, booking_time,total_minutes,total_price,st_booking.status,st_booking.invoice_id,st_booking.updated_on,st_booking.id,st_booking.merchant_id,st_booking.created_by,st_booking.book_by,st_booking.book_id,st_booking.user_id,st_booking.employee_id,st_booking.reshedule_count_byuser,us.end_date,st_booking.booking_type,st_booking.email as guestemail,st_booking.fullname');
        if ($order != "") {
            $this->db->order_by($order);
        }
        //$this->db->order_by($order,'desc');
        else {
            $this->db->order_by('id', 'desc');
        }

        $this->db->from('st_booking');
        $this->db->join('st_users as us', 'us.id' . '=' . 'st_booking.merchant_id', 'left');
        $this->db->join('st_users as us2', 'us2.id' . '=' . 'st_booking.' . $acc, 'left');
        $this->db->where($where);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $getdata = $this->db->get();

        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();
            return $arr;
        } else {
            return false;
        }

    }

    public function getbookinglistmodifiy($where, $limit = 0, $offset = 0, $acc = 'employee_id', $order = '')
    {
        $this->db->select('us2.first_name,us2.last_name,us2.profile_pic,us.address,
	   us.mobile,us.cancel_booking_allow,us.hr_before_cancel,us.zip,us.city,
	   us.business_name,booking_time,total_minutes,total_time,total_buffer,total_price,
	   st_booking.status,st_booking.invoice_id,
	   st_booking.updated_on,st_booking.id,st_booking.merchant_id,
	   st_booking.book_by,st_booking.book_id,st_booking.user_id,
	   st_booking.employee_id,st_booking.reshedule_count_byuser,
	   us.end_date,st_booking.booking_type,st_booking.email as guestemail,
	   st_booking.fullname,(select price_start_option from st_merchant_category WHERE id=st_booking_detail.service_id) as price_start_option,
	   (SELECT count(id) FROM st_review WHERE st_review.booking_id = st_booking.id) as tot_review');
        if ($order != "") {
            $this->db->order_by($order);
        }
        //$this->db->order_by($order,'desc');
        else {
            $this->db->order_by('id', 'desc');
        }

        $this->db->group_by('booking_id');
        
        $this->db->from('st_booking');
        $this->db->join('st_users as us', 'us.id' . '=' . 'st_booking.merchant_id', 'left');
        $this->db->join('st_users as us2', 'us2.id' . '=' . 'st_booking.' . $acc, 'left');
        $this->db->join('st_booking_detail', 'st_booking_detail.booking_id=st_booking.id');
        $this->db->where($where);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $getdata = $this->db->get();
        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();
            return $arr;
        } else {
            return false;
        }

    }

    public function getAllbookinglist($where, $limit = 0, $offset = 0, $order = 'st_booking.id', $short = 'DESC', $search = "")
    {
        $this->db->select('(select price_start_option from st_merchant_category WHERE id=st_booking_detail.service_id) as price_start_option,us2.first_name,us2.last_name,us2.profile_pic,us2.status,us.first_name as emp_fn,us.last_name as emp_ln,us.profile_pic as emp_pic,st_booking.book_id,st_booking.updated_on,total_buffer,st_booking.merchant_id,booking_time,total_minutes,total_price,st_booking.status,st_booking.id,st_booking.merchant_id,st_booking.user_id,st_booking.employee_id,st_booking.booking_type,st_booking.email as guestemail,st_booking.fullname');
        $this->db->order_by($order, $short);
        $this->db->from('st_booking');
        $this->db->join('st_users as us', 'us.id' . '=' . 'st_booking.employee_id', 'left');
        $this->db->join('st_users as us2', 'us2.id' . '=' . 'st_booking.user_id', 'left');
        $this->db->join('st_booking_detail', 'st_booking.id=st_booking_detail.booking_id');
        $this->db->where($where);
        $this->db->group_by("st_booking_detail.booking_id");
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        if ($search != "") {
            $whr = "(us2.first_name LIKE '%" . $search . "%' OR us2.last_name LIKE '%" . $search . "%' OR us2.email LIKE '%" . $search . "%' OR st_booking.fullname LIKE '%" . $search . "%')";
            $this->db->where($whr);
        }
        $getdata = $this->db->get();
        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();
            return $arr;
        } else {
            return false;
        }

    }

    public function getbookinglistadmin($where = "")
    {
        $this->db->select('us2.first_name,us2.last_name,us2.profile_pic,us.business_name,st_booking.fullname,st_booking.booking_type,booking_time,total_minutes,total_price,st_booking.status,st_booking.id,st_booking.merchant_id,st_booking.user_id,st_booking.employee_id,st_booking.created_on');
        $this->db->order_by('booking_time', 'DESC');
        $this->db->from('st_booking');
        $this->db->join('st_users as us', 'us.id' . '=' . 'st_booking.merchant_id', 'left');
        $this->db->join('st_users as us2', 'us2.id' . '=' . 'st_booking.user_id', 'left');
        if ($where != "") {
            $this->db->where($where);
        }

        $getdata = $this->db->get();
        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();
            return $arr;
        } else {
            return false;
        }

    }

    public function getpaymentlistadmin($start, $end)
    {
        $this->db->select('us.first_name,us.last_name,us.profile_pic,us.business_name,transuction_id,amount,st_payments.created_on');
        $this->db->order_by('st_payments.id', 'ASC');
        $this->db->from('st_payments');
        $this->db->join('st_users as us', 'us.id' . '=' . 'st_payments.user_id', 'left');
        if ($start != '') {
            $this->db->where('date(st_payments.created_on) >=', $start);
        }

        if ($end != '') {
            $this->db->where('date(st_payments.created_on) <=', $end);
        }

        $getdata = $this->db->get();
        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();
            return $arr;
        } else {
            return false;
        }

    }

    public function getWhereLike($table, $where, $select = "*", $limit = "", $offset = "", $short = "id", $order = "desc", $search = "", $colum = "")
    {
        $this->db->select($select);
        $this->db->order_by($short, $order);
        $this->db->limit($limit, $offset);
        $this->db->where($where);
        if ($search != "") {
            $this->db->like($colum, $search);
        }
        $getdata = $this->db->get($table);
        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();
            foreach ($arr as $rows) {
                $data[] = $rows;
            }
            $getdata->free_result();

            return $data;
        } else {
            return false;
        }
    }

    public function join_two_without_limit($tbl1, $tbl2, $field1, $field2, $where, $select, $orderby = "id", $groupby = "")
    {
        $this->db->group_by($groupby);
        $this->db->select($select);
        $this->db->from($tbl1);
        $this->db->join($tbl2, $tbl1 . '.' . $field1 . '=' . $tbl2 . '.' . $field2);
        $this->db->order_by($orderby, "desc");
        $this->db->where($where);
        $getdata = $this->db->get();
        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();

            return $arr;
        } else {
            return false;
        }
    }

    public function join_two_without_limit_calender($tbl1, $tbl2, $field1, $field2, $where, $select, $orderby = "id", $groupby = "")
    {
        $this->db->group_by($groupby);
        $this->db->select($select);
        $this->db->from($tbl1);
        $this->db->join($tbl2, $tbl1 . '.' . $field1 . '=' . $tbl2 . '.' . $field2);
        $this->db->order_by($orderby, "asc");
        $this->db->where($where);
        $getdata = $this->db->get();
        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();

            return $arr;
        } else {
            return false;
        }
    }

    public function dateDifference($date_1, $date_2, $differenceFormat = '%i')
    {echo $date_1 . "==" . $date_2;
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);

    }

    public function join_two_orderby_report($tbl1, $tbl2, $field1, $field2, $where, $select, $orderby = "id", $groupby = "", $limit = 0, $offset = 0, $search = "", $jointype = "", $type="result")
    {
        $this->db->group_by($groupby);
        $this->db->select($select);
        $this->db->from($tbl1);
        if (!empty($jointype)) {
            $this->db->join($tbl2, $tbl1 . '.' . $field1 . '=' . $tbl2 . '.' . $field2, $jointype);
        } else {
            $this->db->join($tbl2, $tbl1 . '.' . $field1 . '=' . $tbl2 . '.' . $field2);
        }
        $this->db->order_by($orderby);
		$this->db->where($where);
		
        if ($search != "") {
            $whr = "(" . $tbl1 . ".first_name LIKE '%" . $search . "%' OR " . $tbl1 . ".last_name LIKE '%" . $search . "%' OR " . $tbl1 . ".email LIKE '%" . $search . "%')";
            $this->db->where($whr);
        }

		if ($type == "total") {
			$total = $this->db->count_all_results();
			return $total;
		}

        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        $getdata = $this->db->get();
        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();

            return $arr;
        } else {
            return false;
        }
    }

    public function getbookinglistadmin_export($where = "", $search = "")
    {
        $this->db->select('st_booking.id,us2.first_name,us2.last_name,st_booking.fullname,us2.profile_pic,us.business_name,booking_time,total_minutes,total_price,st_booking.status,st_booking.id,booking_type,st_booking.merchant_id,st_booking.user_id,st_booking.employee_id,st_booking.created_on');
        $this->db->order_by('booking_time', 'DESC');
        $this->db->from('st_booking');
        $this->db->join('st_users as us', 'us.id' . '=' . 'st_booking.merchant_id', 'left');
        $this->db->join('st_users as us2', 'us2.id' . '=' . 'st_booking.user_id', 'left');
        $this->db->where($where);
        if ($search != "") {
            $whr = "(us.business_name LIKE '%" . $search . "%' OR total_price LIKE '%" . $search . "%' OR us2.first_name LIKE '%" . $search . "%' OR us2.last_name LIKE '%" . $search . "%')";
            $this->db->where($whr);
        }

        $getdata = $this->db->get();
        $num = $getdata->num_rows();
        if ($num > 0) {
            $arr = $getdata->result();
            return $arr;
        } else {
            return false;
        }

    }
    /*public function getbookinglistadmin_export($search="")
{
$this->db->select('us2.first_name,us2.last_name,us2.profile_pic,us.first_name as emp_fn,us.last_name as emp_ln,us.profile_pic as emp_pic,st_booking.updated_on,total_buffer,st_booking.merchant_id,booking_time,total_minutes,total_price,st_booking.status,st_booking.id,st_booking.merchant_id,st_booking.user_id,st_booking.employee_id,st_booking.booking_type,st_booking.email as guestemail,st_booking.fullname');
//$this->db->order_by($order, $short);
$this->db->from('st_booking');
$this->db->join('st_users as us', 'us.id'.'='.'st_booking.employee_id','left');
$this->db->join('st_users as us2', 'us2.id'.'='.'st_booking.user_id','left');
//$this->db->where($where);
if($search!=""){
$whr="(us.business_name LIKE '%".$search."%' OR total_price LIKE '%".$search."%' OR us2.first_name LIKE '%".$search."%' OR us2.last_name LIKE '%".$search."%')";
$this->db->where($whr);
}

$getdata  = $this->db->get();
$num = $getdata->num_rows();
if($num> 0) {
$arr=$getdata->result();
return $arr;
} else
return false;
}*/

}
