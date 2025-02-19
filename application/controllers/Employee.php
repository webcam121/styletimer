<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends Frontend_Controller {

	function __construct() {
		parent::__construct();
	
		$this->load->model('Booking_model','booking');
		if(empty($this->session->userdata('st_userid'))){
			redirect(base_url('auth/login'));
		 }

		 $usid=$this->session->userdata('st_userid');
			if(!empty($usid)){
			  $status=getstatus_row($usid);
			  if($status != 'active'){
			  	redirect(base_url('auth/logouts/').$status);
			   }
			}
	}

	//*** Change password view ***//
	public function change_password()
	{
		$this->load->view('frontend/employee/change_password');
	}

	//*** Employee dashboard ***//
	public function dashboard()
	{
		 $this->data['title']='styletimer - Buchungsverwaltung';
		 $detail=$this->user->select_row('st_users','merchant_id',array('id' =>$this->session->userdata('st_userid')));
		 if(!empty($detail)){	 
		 $selectMinMaxtime="SELECT MIN(starttime) as mintime,(SELECT MAX(endtime) FROM st_availability WHERE type='open' AND user_id=".$detail->merchant_id.") as maxtime FROM st_availability WHERE type='open' AND user_id=".$detail->merchant_id;
         	$this->data['minmaxtime']= $this->user->custome_query($selectMinMaxtime,'row');
         }

      
		$returndata =array();

		$id = $this->session->userdata('st_userid');
		$permissions = getEmpPermissionForDeletCancel($id);

		if ($permissions->allow_emp_to_delete_cancel_booking == 1) {
			$empdetail = $this->user->custome_query('select * from st_users where id='.$id,'row');
			$mid = $empdetail->merchant_id;
			$this->data['minmaxtime']->mintime = getPreExtraHrs($mid);
        	$this->data['minmaxtime']->maxtime = getAfterExtraHrs($mid);

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
			$data = $this->user->select('st_availability','*',array('user_id'=>$id));
			if(!empty($data))
			{
			
				foreach($data as $row)
				{ 
					if($row->type=='open')
					{
					$returndata[] = ['start'=>$row->starttime,
									'end'=>$row->endtime,
									'dow'=>[date('w', strtotime($row->days))]

									]; 
						if(!empty($row->starttime_two) && !empty($row->endtime_two)){
						$returndata[] = ['start'=>$row->starttime_two,
									'end'=>$row->endtime_two,
									'dow'=>[date('w', strtotime($row->days))]

									];
						}            
					}else{
						$returndata[] = ['start'=>'00:00:00',
									'end'=>'00:00:00',
									'dow'=>[date('w', strtotime($row->days))]
									];
					}
					
				}

			}
		}		
		$this->data['businesshours'] = $returndata;
		$this->data['employee_id'] = $id;
		//echo '<pre>'; print_r($this->data); die;

        $this->load->view('frontend/employee/dashboard',$this->data);	
	}

	public function blockdelete($id = '')
    {
        if (!empty($id)) {
            $this->user->delete('st_booking', ['blocked' => $id]);
        }
        $this->session->set_flashdata(
            'success',
            'Blockierung erfolgreich gelöscht'
        );
        redirect(base_url('employee/dashboard'));
    }

	public function get_blocktime_details()
    {
        // echo '<pre>'; print_r($_POST); die;
        $field =
            'count(id) as totalCount,employee_id,blocked_type,block_for,blocked';
        $info = $this->user->select_row('st_booking', $field, [
            'blocked' => $_POST['block_id'],
        ]);
        $eid = $this->session->userdata('st_userid');
		$userDetail = $this->user->select_row(
			'st_users',
			'*',
			[
				'id' => $this->session->userdata('st_userid'),
			]
		);
		$mid = $userDetail->merchant_id;
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

	function employee_unavailablity()
    {
        //sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssprint_r($_POST); die;
        if (
            empty($this->session->userdata('st_userid')) &&
            $this->session->userdata('access') != 'employee'
        ) {
            echo json_encode(['success' => 0, 'url' => base_url('auth/login')]);
            die();
        } else {
            $dayName = date('l', strtotime($_POST['date']));
            $dayName = strtolower($dayName);
            $_POST['date'] = date('Y-m-d', strtotime($_POST['date']));

			$uid = [$this->session->userdata('st_userid')];
			$userDetail = $this->user->select_row(
				'st_users',
				'*',
				[
					'id' => $this->session->userdata('st_userid'),
				]
			);

            $merchantTime = $this->user->select_row(
                'st_availability',
                'days,type,starttime,endtime',
                [
                    'user_id' => $userDetail->merchant_id,
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
                    $ii = 0;
                    foreach ($uid as $eid) {
                        $update['employee_id'] = $eid;
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

						$insertArr = [];
						$insertArr['employee_id'] = $this->session->userdata('st_userid');
						$insertArr[
							'merchant_id'
						] = $userDetail->merchant_id;

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

	public function booking_old(){

		$usid=$this->session->userdata('st_userid');

		$bookings = $this->user->select('st_booking','id,merchant_id,booking_time,booking_endtime',['employee_id'=>$usid]);

            //echo $this->db->last_query();die;
			$data = [];
			if($bookings)
				foreach($bookings as $booking){
					$name=get_servicename($booking->id);
					if(empty($name))
						$name="";
					else
						$name=$name;

				$data[] = [
						'title' => $name,
						'start'=> $booking->booking_time,
						'end'=> $booking->booking_endtime,
						];
					}
				echo json_encode($data); die;
			 
	}

	//*** Booking ***//
	public function booking(){

		$usid=$this->session->userdata('st_userid');
		
		$sdate = date('Y-m-d 00:00:00',strtotime($_POST['start']));
		$edate = date('Y-m-d 00:00:00',strtotime($_POST['end']));
		/*$bookings=$this->user->join_two_without_limit('st_booking','st_users','employee_id','id',array('st_booking.status!='=>'deleted','st_booking.status!='=>'cancelled','employee_id'=>$usid,'booking_type!='=>'self'),'st_booking.id,st_booking.merchant_id,st_booking.user_id,st_booking.total_price,st_booking.booking_type,st_booking.notes,st_booking.fullname,booking_time,employee_id,booking_endtime,booking_type,st_booking.status,st_users.first_name,st_users.last_name,st_users.calender_color as color,(SELECT first_name FROM st_users WHERE id=st_booking.user_id) as ufirst_name,(SELECT last_name FROM st_users WHERE id=st_booking.user_id) as ulast_name,(SELECT profile_pic FROM st_users WHERE id=st_booking.user_id) as uprofile_pic,(SELECT notes FROM st_usernotes WHERE user_id=st_booking.user_id AND created_by=st_booking.merchant_id) as unotes','','st_booking.id');*/

		$bookings = $this->user->join_two_without_limit_calender('st_booking','st_users','employee_id','id',array('st_booking.status!='=>'deleted','st_booking.status!='=>'cancelled','employee_id'=>$usid,'booking_time >='=>$sdate,'booking_time <'=>$edate),'st_booking.id,st_booking.merchant_id,st_booking.user_id,st_booking.total_price,st_booking.booking_type,st_booking.block_for,st_booking.blocked,st_booking.notes,st_booking.fullname,booking_time,employee_id,booking_endtime,booking_type,st_booking.status,st_users.first_name,st_users.profile_pic,st_users.last_name,st_users.calender_color as color,(SELECT first_name FROM st_users WHERE id=st_booking.user_id) as ufirst_name,(SELECT last_name FROM st_users WHERE id=st_booking.user_id) as ulast_name,(SELECT profile_pic FROM st_users WHERE id=st_booking.user_id) as uprofile_pic,(SELECT notes FROM st_usernotes WHERE user_id=st_booking.user_id AND created_by='.$usid.') as unotes','','st_booking.id');
			
		//echo $this->db->last_query();die;
		$data = [];
		$abcheck = '';
		if($bookings)
			$permissions = getEmpPermissionForDeletCancel($usid);
			foreach($bookings as $booking){

				// booking service name with sub category
				$book_details=$this->user->select('st_booking_detail','id,booking_id,service_id,service_name,service_type,setuptime_start,setuptime_end,finishtime_start,finishtime_end',array('booking_id'=>$booking->id,'show_calender !='=>1),'','id','ASC');
				
				$sevices='';
				$abcheck = '';
				if(!empty($book_details)){
					
					foreach($book_details as $serv){
						
					if ($serv->price_start_option == 'ab') {
						if (empty($abcheck)) {
							$abcheck = 'ab';
						}
					}
					$sub_name=get_subservicename($serv->service_id);  
					
					if($sub_name == $serv->service_name)
						$sevices.=$serv->service_name.',';
					else
						$sevices.=$sub_name.' - '.$serv->service_name.',';
					}
				}
				$name = rtrim($sevices, ',');
				//~ //$name=get_servicename($booking->id);
				if(empty($name))
					$name="";
				else
					$name=$name;
						
				$difference = strtotime($booking->booking_endtime) - strtotime($booking->booking_time);

				$difference_in_minutes = $difference / 60;	
            		
						
						
			 	$image=base_url('assets/frontend/images/user-icon-gret.svg');
				// $detail_url=base_url("booking/detail/").url_encode($booking->id);			
				if(!empty($booking->booking_type) && $booking->booking_type=="self"){
					if(!empty($booking->profile_pic)){
						if(!empty($booking->totalblockcheck) && $booking->totalblockcheck<=1 || empty($booking->totalblockcheck))
							$image=base_url('assets/uploads/employee/'.$booking->employee_id.'/icon_'.$booking->profile_pic);
						}
						if ($booking->blocked_type == 'close') {
							$data[] = [
								'id'=>$booking->id,
								'resourceId'=>$booking->employee_id,
								'title' =>'',
								'serviceName'=>"",
								'status'=>'',
								'notes'=>$booking->notes,
								'blocked'=>$booking->blocked,
								'abcheck' => '',
								'start'=> $booking->booking_time,
								'end'=> $booking->booking_endtime,
								'totaltime'=>$difference_in_minutes,
								'color'=>"",
								'abcheck' => $abcheck,
								'blocked_type' => $booking->blocked_type,
								'image_url'=>'',
								'detail_url'=> url_encode($booking->id),
								'add_class' => '',
							];
						}
						else {
							$editable = false;
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
	
								if (count($emparray) == 1) {
									if ($permissions->allow_emp_to_delete_cancel_booking == 1) {
										$editable = true;
									}
								}
								//echo '<pre>'; print_r($emparray); die;
								$empname = implode($emparray, ',');
							} else {
								$empname = $this->lang->line('All_staff');
							}

							$data[] = [
								'id'=>$booking->id,
								'resourceId'=>$booking->employee_id,
								'title' =>'',
								'serviceName'=>$this->lang->line('blocked_time'),
								'status'=>'',
								'notes'=>$booking->notes,
								'blocked'=>$booking->blocked,
								'start'=> $booking->booking_time,
								'end'=> $booking->booking_endtime,
								'abcheck' => $abcheck,
								'totaltime'=>$difference_in_minutes,
								'color'=>"#4D4D4D",
								'blocked_type' => $booking->blocked_type,
								'image_url'=>$image,
								'detail_url'=> url_encode($booking->id),
								'userName' => $empname,
								'editable' => $editable,
								'add_class' => 'block-time-bg',
							];
						}
					
					}
				else{	
					if($booking->status=='completed')
					{
						
						if($booking->booking_type=='guest'){
							$userName=$booking->fullname;
							
						}
						else{
							$userName=$booking->ufirst_name." ".$booking->ulast_name;
								if(!empty($booking->uprofile_pic)){
									$image=base_url('assets/uploads/users/'.$booking->user_id.'/icon_'.$booking->uprofile_pic);
									}
						}
					
						$unotes="";		  
						if(!empty($booking->unotes)) $unotes=$booking->unotes; 		  	  
						$tstarttime = $book_details[0]->setuptime_start;
						$tendtime = $book_details[0]->setuptime_end;
						foreach($book_details as $details){		
							if($details->service_type==1){
								$tendtime = $details->setuptime_end;
								$data[] = [
								'id'=>$booking->id,
								'resourceId'=>$booking->employee_id,
								'title' =>$booking->first_name,
								'serviceName'=>$name,
								'status'=>'completed',
								'notes'=>$booking->notes,
								'blocked'=>$booking->blocked,
								'unotes'=>$unotes,
								'abcheck' => '',
								'start'=> $details->finishtime_start,
								'end'=>  $details->finishtime_end,
								'totaltime'=>$difference_in_minutes,
								'color'=>"#C1BEBE60",
								'totalprice'=>$booking->total_price,
								'userName'=>$userName,
								'image_url'=>$image,
								'detail_url'=> url_encode($booking->id)
								]; 
								$tstarttime = $details->finishtime_start;
								$tendtime = $details->finishtime_end;
							} else {
								$tendtime = $details->setuptime_end;
							}
						}
						$data[] = [
							'id'=>$booking->id,
							'resourceId'=>$booking->employee_id,
							'title' =>$booking->first_name,
							'serviceName'=>$name,
							'status'=>'completed',
							'notes'=>$booking->notes,
							'blocked'=>$booking->blocked,
							'unotes'=>$unotes,
							'abcheck' => '',
							'start'=> $tstarttime,
							'end'=>  $tendtime,
							'totaltime'=>$difference_in_minutes,
							'color'=>"#C1BEBE60",
							'totalprice'=>price_formate(
								$booking->total_price
							),
							'blocked_type' => $booking->blocked_type,
							'userName' => $userName,
							'image_url' => $image,
							'detail_url' => url_encode($booking->id),
							'add_class' => ''
						]; 
					} else if ($booking->status == 'no show') {
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
					} else if ($booking->status == 'confirmed') {
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
					}
					else
					{
						if(!empty($booking->color)){ 
						
							$color=$booking->color; 
						}
						else $color="#FF9944";	 	
					
					
						if($booking->booking_type=='guest'){
							$userName=$booking->fullname;
							
						}
						else
						{
						$userName=$booking->ufirst_name." ".$booking->ulast_name;
								if(!empty($booking->uprofile_pic)){
									$image=base_url('assets/uploads/users/'.$booking->user_id.'/icon_'.$booking->uprofile_pic);
									}
						}	
						$tcolor = getTextColorFromBgColor($color);
						$unotes="";		  
						if(!empty($booking->unotes)) $unotes=$booking->unotes;	    
						if(!empty($book_details)){
							$tstarttime = $book_details[0]->setuptime_start;
							$tendtime = $book_details[0]->setuptime_end;
							foreach($book_details as $details){	
								$tendtime = $details->setuptime_end;	  
								if($details->service_type==1){
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
			}
				
		
		$empdetail = $this->user->custome_query('select * from st_users where id='.$usid,'row');
		$data1 = $this->user->select('st_availability', '*', [
            'user_id' => $empdetail->merchant_id,
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
		echo json_encode($data); die;
			 
	}

	//*** Employee booking list  ***///
	public function all_bookings()
	{
		if(empty($this->session->userdata('st_userid'))){
			redirect(base_url('auth/login'));
		 }

		 $where=array('employee_id'=>$this->session->userdata('st_userid'),'booking_type !='=>'self');
		 $totalcount = $this->user->getbookinglist($where,0,0,'user_id');
		 if(!empty($totalcount))
		 	$total=count($totalcount);
		 else
		 	$total=0;

		 $limit = isset($_GET['limit'])?$_GET['limit']:PER_PAGE10;	//PER_PAGE10
		 $url = 'employee/all_bookings';
		 $segment = 3;    
		 $page = mypaging($url,$total,$segment,$limit);
		 $this->data['booking']=$this->user->getbookinglist($where,$page["per_page"],$page["offset"],'user_id');
		 $this->load->view('frontend/employee/booking_listing',$this->data);
	}

	//**** Booking detail  ***//
	public function booking_detail($id=""){
		if(empty($this->session->userdata('st_userid'))){
			redirect(base_url('auth/login'));
		 }
		if($id!=""){
			//~ $bid=url_decode($id);
			//~ $this->data['main'] = $this->booking->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$bid),'st_booking.id,st_booking.book_id,st_booking.invoice_id,st_booking.merchant_id,business_name,st_users.address,st_users.city,st_users.zip,st_booking.booking_time,st_booking.email,st_booking.total_price,st_booking.updated_on,st_booking.total_minutes,st_booking.book_by,st_booking.user_id as userid,st_booking.notes as booknotes,st_booking.created_on,st_booking.updated_on,st_booking.created_by,st_booking.employee_id,st_booking.status,st_booking.booking_type,st_booking.fullname,st_booking.gender,st_booking.notes,st_booking.email as guestemail,st_booking.reason,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name');
		
		//~ if(!empty($this->data['main']))
			//~ {
			//~ $field = "st_users.id,st_booking_detail.user_id,first_name,last_name,profile_pic,service_name,duration,price,buffer_time,discount_price,email,gender, address,city,country,zip,service_id,(select notes from st_usernotes WHERE user_id=st_users.id AND created_by = ".$this->data['main'][0]->merchant_id.") as notes";  
			
			//~ $whr   = array('booking_id'=>$bid);
    		//~ $this->data['booking_detail'] = $this->booking->join_two('st_booking_detail','st_users','user_id','id',$whr,$field); 

			//~ //created_by
	    	//~ $this->data['review']    =$this->booking->select_row('st_review','id,rate,review,anonymous,merchant_id,created_on',array('booking_id'=>$bid));
	    	//~ $sql2="SELECT `subcategory_id` FROM `st_merchant_category` `r` JOIN `st_category` `u1` ON `r`.`category_id`=`u1`.`id` JOIN `st_category` `u2` ON `r`.`subcategory_id`=`u2`.`id` WHERE `r`.`status` = 'active' AND `r`.`created_by`=".$this->data['main'][0]->merchant_id." GROUP BY `r`.`subcategory_id` LIMIT 4";
	    	//~ $this->data['all_service']=$this->user->custome_query($sql2,'result');
			//~ $this->load->view('frontend/user/booking_confirm',$this->data);
	    	//~ }
			 //~ else
	    		 //~ redirect(base_url());
			$bid=url_decode($id);
			$this->data['main']= $this->booking->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>$bid),'business_name,st_booking.booking_time,st_booking.created_on,employee_id,st_booking.status,st_booking.booking_type,st_booking.fullname,st_booking.email as guestemail,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name');
			if(!empty($this->data['main'])){

			$field="st_users.id,first_name,last_name,profile_pic,service_name,duration,price,buffer_time,discount_price,service_id";  
			$whr=array('booking_id'=>$bid);
	    	$this->data['booking_detail']= $this->booking->join_two('st_booking_detail','st_users','user_id','id',$whr,$field);  //created_by
	    	$this->data['review']=$this->booking->select_row('st_review','rate,review',array('booking_id'=>$bid));
	    		$this->load->view('frontend/employee/booking_detail',$this->data);
	    	}
	    	else
	    		redirect(base_url());
		}
		else
			redirect(base_url());
			
			
		
	}

 //**** get opening hour of salon ****//
function get_opning_hour(){
	if(!empty($_POST['date']) && !empty($this->session->userdata('st_userid')))
	  {
		
		$dayName = date("l", strtotime($_POST['date']));
		$dateslect = date("Y-m-d", strtotime($_POST['date']));
		$dayName = strtolower($dayName);		
		$id      = url_decode($_POST['eid']);
     	$bookid  = url_decode($_POST['bk_id']);	
     				 				 
		$info    = $this->user->select_row('st_booking','id,total_minutes,merchant_id,total_buffer,booking_time',array('id'=>$bookid,'status'=>'confirmed'));	
		
		$emptyhtml = '<h6 class="mt-3">Leider sind am '.date('d',strtotime($dateslect)).'. '.get_month_de_translation(date('F',strtotime($dateslect))).' keine Termine verfügbar.</h6>
							<h6>Bitte wähle einen anderen Tag</h6>';
		if(!empty($info))
		 {
		$mrntid  = $info->merchant_id;
		
     	$availablity1 = $this->user->select_row('st_availability','id,type',array('user_id'=>$mrntid,'days'=>$dayName,'type'=>'open'));     	
     	//~ $availablity = $this->user->select_row('st_availability','days,type,starttime,endtime,starttime_two,endtime_two',array('user_id'=>$mrntid,'days'=>$dayName));     	
     	 if(!empty($availablity1)){
			 
			
			 $availablity = $this->user->select_row('st_availability','days,type,starttime,endtime,starttime_two,endtime_two',array('user_id'=>$id,'days'=>$dayName,'type'=>'open'));
			  $html = "";
			  			 
			  if(!empty($availablity)){
				  
				$totaldurationTim = $info->total_minutes+$info->total_buffer;
				
				 $detailSelectQry = "";
				 
				 $bookdteialQuery = "SELECT bd.id,service_type,bd.duration,bd.buffer_time,bd.setuptime,bd.processtime,bd.finishtime,oa.starttime,oa.endtime,mc.price,mc.discount_price FROM st_booking_detail as bd JOIN st_merchant_category as mc ON mc.id=bd.service_id  LEFT JOIN st_offer_availability as oa ON oa.service_id=bd.service_id AND oa.days='".$dayName."' AND oa.type='open' WHERE booking_id=".$bookid;            
				
			     $serviceDetails   = $this->user->custome_query($bookdteialQuery,'result'); 
			     
			    // echo '<pre>'; print_r($serviceDetails); die;
			     
			//$serviceDetails   = $this->user->select('st_booking_detail','id,service_type,duration,buffer_time,setuptime,processtime,finishtime',array('booking_id'=>$bookid)); 
				    
				
				   //$html=$html.'<option value="">Select time</option>';
									$start = $availablity->starttime; //you can write here 00:00:00 but not need to it
									$end   = $availablity->endtime;

									$tStart = strtotime($start);
									$tEnd   = strtotime(date('H:i:s',strtotime($end. "- ".$totaldurationTim." minutes")));
									
									$currtime = date('H:i:s');
									$crntdate = date('Y-m-d');
									$tNow     = $tStart;
									$k        = 1;
									$checkedTime = "";
									if(date('Y-m-d',strtotime($info->booking_time))==$_POST['date']){
										$checkedTime = date('H:i:s',strtotime($info->booking_time));
										}
									
									
									while($tNow <= $tEnd){ 
										
                                       $nowTime = date("H:i:s",$tNow);
                                       //~ echo $nowTime."==".$currtime."==".$date."==".$crntdate."</br>";									
									 if($dateslect==$crntdate && $currtime<=$nowTime || $dateslect!=$crntdate){
										 
											$timeArray        = array();                           
											$ikj              = 0;
											$strtodatyetime   = $dateslect." ".date('H:i:s',$tNow);
											
											$dis              = 0;
                                            $total            = 0;
											
											 foreach($serviceDetails as $row){
												 
												     if(!empty($row->starttime) && !empty($row->endtime) && $row->starttime<=$nowTime && $row->endtime>=$nowTime)
													  {	  
														if(!empty($row->discount_price)){ 
															$dis=($row->price-$row->discount_price)+$dis;
															$total=$row->price+$total;
														  }
														else{
														   $total=$row->price+$total;
														 } 
													  }
												   else{
													   $total=$row->price+$total;
													   }
												 
												 
																									
														$timeArray[$ikj]        = new stdClass;
														
														$bkstartTime            = $strtodatyetime;
														$timeArray[$ikj]->start = $bkstartTime; 
														
													   if($row->service_type==1){
																						   
															$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
															$timeArray[$ikj]->end   = $bkEndTime;							    	
															$ikj++;	
															
															$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
															$timeArray[$ikj]        = new stdClass;
															$timeArray[$ikj]->start = $finishStart;
															
															$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
															$timeArray[$ikj]->end   = $finishEnd;
															$ikj++;
															
															$strtodatyetime=$finishEnd;
																						
													   }else{
															$totalMin               = $row->duration+$row->buffer_time;   
															
															$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
															$timeArray[$ikj]->end   = $bkEndTime;							    	
															$ikj++;	
															
															$strtodatyetime=$bkEndTime;							   
													   } 
													 }
										 
									    $resultCheckSlot = checkTimeSlots($timeArray,$id,$mrntid,$totaldurationTim,$bookid);
									    
									    if($resultCheckSlot==true)
									      {
											  $pdisc = "";
											  
											  if(!empty($dis)) $pdisc = $total." €";
											  
											  $ptotal = $total-$dis;
											  
											  $checkClass="";
											  $selctedClass="";
											  if($nowTime==$checkedTime){
												 $checkClass = "checked"; 
												 $selctedClass = " selected_time"; 
												}
											  
										    $html=$html.'<li class="select-time-price lineheight40 '.$selctedClass.'">
															<input type="radio" id="id_time-1price'.$k.'" name="chg_time" class="slectTime" value="'.date("H:i:s",$tNow).'" '.$checkClass.'>
															<label for="id_time-1price'.$k.'">
															  <span class="text-left pl-10">'.date("H:i",$tNow).'</span>
															  <span>													
																 <span class="new-price float-right">'.$ptotal.' €</span>													 
																<span class="old-price "><del>'.$pdisc.' </del></span>
															  </span>
															</label>
														  </li>';
											$k++;
									      }

										 }
							       $tNow = strtotime('+15 minutes',$tNow); 
				                }
				                
				             if(!empty($availablity->starttime_two) && !empty($availablity->endtime_two)){
								  
								    $start = $availablity->starttime_two; //you can write here 00:00:00 but not need to it
									$end =$availablity->endtime_two;

									$tStart   = strtotime($start);
									$tEnd     = strtotime(date('H:i:s',strtotime($end. "- ".$totaldurationTim." minutes")));
									
									$currtime = date('H:i:s');
									$crntdate = date('Y-m-d');
									//echo $tStart."==".$tEnd;
									$k      = 1;
									$tNow = $tStart;
									while($tNow <= $tEnd){ 
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
									
                                       $nowTime=date("H:i:s",$tNow);
                                       //~ echo $nowTime."==".$currtime."==".$date."==".$crntdate."</br>";									
									 if(($dateslect==$crntdate && $currtime<=$nowTime || $dateslect!=$crntdate)){
										 
										   	$timeArray        = array();                           
											$ikj              = 0;
											$strtodatyetime   = $dateslect." ".date('H:i:s',$tNow);
											$dis              = 0;
                                            $total            = 0;
											
											 foreach($serviceDetails as $row){
												 
													   if(!empty($row->starttime) && !empty($row->endtime) && $row->starttime<=$nowTime && $row->endtime>=$nowTime)
														  {	  
															if(!empty($row->discount_price)){ 
																$dis=($row->price-$row->discount_price)+$dis;
																$total=$row->price+$total;
															  }
															else{
															   $total=$row->price+$total;
															 } 
														  }
													   else{
														   $total=$row->price+$total;
														   }
																									
														$timeArray[$ikj]        = new stdClass;
														
														$bkstartTime            = $strtodatyetime;
														$timeArray[$ikj]->start = $bkstartTime; 
														
													   if($row->stype==1){
																						   
															$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
															$timeArray[$ikj]->end   = $bkEndTime;							    	
															$ikj++;	
															
															$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
															$timeArray[$ikj]        = new stdClass;
															$timeArray[$ikj]->start = $finishStart;
															
															$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
															$timeArray[$ikj]->end   = $finishEnd;
															$ikj++;
															
															$strtodatyetime=$finishEnd;
																						
													   }else{
															$totalMin               = $row->duration+$row->buffer_time;   
															
															$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
															$timeArray[$ikj]->end   = $bkEndTime;							    	
															$ikj++;	
															
															$strtodatyetime=$bkEndTime;							   
													   } 
													 }
										 
									       $resultCheckSlot = checkTimeSlots($timeArray,$id,$mrntid,$totaldurationTim);
									       
									       if($resultCheckSlot==true){
														  $pdisc = "";
														  
														  if(!empty($dis)) $pdisc = $total." €";
														  
														  $ptotal = $total-$dis;
														  
													   $checkClass="";
													   $selctedClass="";
														
													   if($nowTime==$checkedTime){
														  $checkClass = "checked";  
														  $selctedClass = " selected_time"; 
														}
														
										              $html=$html.'<li class="select-time-price lineheight40">
																	<input type="radio" id="id_time-2price'.$k.'" name="chg_time" class="slectTime '.$selctedClass.'" value="'.date("H:i:s",$tNow).'" '.$checkClass.'>
																		<label for="id_time-2price'.$k.'">
																		  <span class="text-left pl-10">'.date("H:i",$tNow).'</span>
																		  <span>													
																			<span class="new-price float-right">'.$ptotal.' €</span>													 
																			<span class="old-price "><del>'.$pdisc.'</del></span>
																		  </span>
																		</label>
																	  </li>';
									          $k++;
									          }
										   
										 }
							       $tNow = strtotime('+15 minutes',$tNow); 
				                }
								 
								 }  
				                
				  if(empty($html)) $html = $emptyhtml;
				  
				   echo json_encode(array('success'=>1,'message'=>'success','html'=>$html));	
			    }
			  else echo json_encode(array('success'=>1,'message'=>'employee not work for selected day','html'=>$emptyhtml));		 
			 }
		 else echo json_encode(array('success'=>1,'message'=>'Salon not update the time','html'=>$emptyhtml));	
	     }	     
	    else echo json_encode(array('success'=>1,'message'=>'You can not reschedule this booking','html'=>$emptyhtml));	 	 
     	
        }
      else echo json_encode(array('success'=>1,'message'=>'Please select a valid date.','html'=>$emptyhtml));	
	
	}
	
	
public function booking_reshedule()
	{
	
		$originalDate = $_POST['chg_date'];
		$newDate      = date("Y-m-d", strtotime($originalDate));
		$field        = 'id,merchant_id,booking_time,total_minutes,booking_type,email,fullname,total_buffer,employee_id,(SELECT notification_time FROM st_users WHERE st_users.id=st_booking.merchant_id) as notification_time,(SELECT additional_notification_time FROM st_users WHERE st_users.id=st_booking.merchant_id) as additional_notification_time';
		
		$info         = $this->user->select_row('st_booking',$field,array('id'=> url_decode($_POST['reSchedule_id'])));
		$oldDate = '';

		if(!empty($info)){
			$oldDate = $info->booking_time;
			$toDate = date('Y-m-d');
			
			if($newDate < $toDate)
				echo json_encode(array('success'=>0, 'msg' =>'Please select valid date'));
			else{

					 $date          = $newDate;
                	 $nameOfDay     = date('l', strtotime($date));
               		 $totalMinutes  = 0; //$info->total_minutes+$info->total_buffer;
                	 $times         = strtotime($_POST['chg_time']);
					 $newTime       = date("H:i", strtotime('+ '.$totalMinutes.' minutes', $times));
					 $dayName       = strtolower($nameOfDay);

             
               	 //~ $select123="SELECT * FROM st_availability WHERE days='".$dayName."' AND type='open' AND ((`starttime`<='".$_POST['chg_time']."' AND endtime>='".$newTime."') OR (`starttime_two`<='".$_POST['chg_time']."' AND endtime_two>='".$newTime."')) AND user_id=".$info->employee_id."";
															
				//~ $check= $this->user->custome_query($select123,'row');

               	//~ if(!empty($check)){

				  $bk_time      = $newDate.' '.$_POST['chg_time'];
				 // $newtimestamp = strtotime(''.$bk_time.' + '.$totalMinutes.' minute');
				  //$book_end     = date('Y-m-d H:i:s', $newtimestamp);
				  
					
				  //~ $whereAS      = '((booking_time>="'.$bk_time.'" AND booking_time<="'.$book_end.'") OR (booking_endtime>="'.$bk_time.'" AND booking_endtime<="'.$book_end.'") OR (booking_time<="'.$bk_time.'" AND booking_endtime>="'.$book_end.'") OR (booking_time>="'.$bk_time.'" AND booking_endtime<="'.$book_end.'")) AND status!="cancelled"';
				        
				        
				               //~ $this->db->where($whereAS);
				      //~ $check = $this->user->select_row('st_booking','id',array('employee_id'=>$info->employee_id,'id !='=>url_decode($_POST['reSchedule_id'])));

				  $sqlForservice = "SELECT `st_booking_detail`.`id`,`st_booking_detail`.`user_id`,`st_booking_detail`.`buffer_time`,`st_booking_detail`.`has_buffer`,`st_category`.`category_name`, `name`,`st_booking_detail`.`service_id`,st_booking_detail.duration,st_booking_detail.service_type as stype,st_booking_detail.setuptime,st_booking_detail.processtime,st_booking_detail.finishtime,`st_merchant_category`.`price`, `st_merchant_category`.`discount_price`,`days`,`st_offer_availability`.`type`,`starttime`,`endtime`,`parent_service_id` FROM `st_booking_detail` LEFT JOIN `st_merchant_category` ON `st_booking_detail`.`service_id`=`st_merchant_category`.`id` LEFT JOIN `st_offer_availability` ON (`st_booking_detail`.`service_id`=`st_offer_availability`.`service_id` AND `st_offer_availability`.`days`='".$dayName."') LEFT JOIN `st_category` ON `st_merchant_category`.`subcategory_id`=`st_category`.`id` WHERE `booking_id` = ".url_decode($_POST['reSchedule_id'])." ORDER BY st_booking_detail.id";
		
	    		  $booking_detail  = $this->user->custome_query($sqlForservice,'result');

	    		  $total_price=$total_buffer=$total_min=$total_dis=0;
				               
			      if(!empty($booking_detail)){
					  
					     $timeArray        = array();                           
						 $ikj              = 0;
						 $strtodatyetime   = $bk_time;
                       
			      		 foreach($booking_detail as $row)
										 {
											 
										$timeArray[$ikj]        = new stdClass;						
										$bkstartTime            = $strtodatyetime;
										$timeArray[$ikj]->start = $bkstartTime; 

										if($row->stype==1){
											$total_min=$row->duration+$total_min;
																		   
											$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$row->setuptime.' minute'));
											$timeArray[$ikj]->end   = $bkEndTime;							    	
											$ikj++;	
											
											$finishStart            = date('Y-m-d H:i:s',strtotime(''.$bkEndTime.' + '.$row->processtime.' minute'));									
											$timeArray[$ikj]        = new stdClass;
											$timeArray[$ikj]->start = $finishStart;
											
											$finishEnd              = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
											$timeArray[$ikj]->end   = $finishEnd;
											$ikj++;
											
											$strtodatyetime=$finishEnd;
																		
										}else{
											$total_buffer           = $row->buffer_time+$total_buffer;
											$totalMin               = $row->duration+$row->buffer_time;  
											 
											$total_min              = $row->duration+$total_min;
											
											$bkEndTime              = date('Y-m-d H:i:s',strtotime(''.$bkstartTime.' + '.$totalMin.' minute'));
											$timeArray[$ikj]->end   = $bkEndTime;							    	
											$ikj++;	
											
											$strtodatyetime=$bkEndTime;							   
										} 	
											
											
											
										if(!empty($row->type) && $row->type=='open')
										   { 
											if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
											  {	  
											   if(!empty($row->discount_price)){	  
											       $total_dis    = ($row->price-$row->discount_price)+$total_dis;
											       $total_price  = $row->discount_price+$total_price;  
											    }
											   else{
												   $total_price  = $row->price+$total_price;  
												   } 
											  }
											 else $total_price   = $row->price+$total_price;  
										   }else $total_price    = $row->price+$total_price; 
											  
											
										 }
										 
								   $totalMinutes   = $total_buffer+$total_min;	   
									
                                   $resultCheckSlot = checkTimeSlots($timeArray,$info->employee_id,$info->merchant_id,$totalMinutes,$info->id);
                                   
                                   if($resultCheckSlot==true){

									 $min           =  $total_buffer+$total_min;
									 $newtimestamp  = strtotime(''.$bk_time.' + '.$min.' minute');
									 $book_end      = date('Y-m-d H:i:s', $newtimestamp);
	 								 //notification set time
	 								 $notif_time    = $info->notification_time;
	 								 $ad_notif_time = $info->additional_notification_time;

									 $timestamp     = strtotime($bk_time);
									 $time          = $timestamp - ($notif_time * 60 * 60);
									 $ad_time          = $timestamp - ($ad_notif_time * 60 * 60);
									// Date and time after subtraction
									 $notif_date    = date("Y-m-d H:i:s", $time);
									 if($ad_notif_time != '0'){
									 	$ad_notif_date    = date("Y-m-d H:i:s", $ad_time);
									 	$book_Arr['additional_notification_date'] = $ad_notif_date;
									 }

									 $book_Arr['booking_time']      = $bk_time;
									 $book_Arr['booking_endtime']   = $book_end;
									 $book_Arr['total_minutes']     = $total_min;
									 $book_Arr['total_buffer']      = $total_buffer;
									 $book_Arr['total_price']       = $total_price;
									 $book_Arr['total_discount']    = $total_dis;
									 $book_Arr['pay_status']        = 'cash';
									 $book_Arr['status']            = 'confirmed';
									 $book_Arr['notification_date'] = $notif_date;
									 $book_Arr['updated_on']        = date('Y-m-d H:i:s');
									 $book_Arr['updated_by']        = $this->session->userdata('st_userid');
									


					if($this->user->update('st_booking',$book_Arr,array('id'=>url_decode($_POST['reSchedule_id']))))
					   {
						//$this->user->delete('st_booking_detail',array('booking_id' => url_decode($_POST['reSchedule_id'])));
                         $boojkstartTime  = $bk_time;
                         
						foreach($booking_detail as $row){
							$detail_Arr = [];
							//$detail_Arr['booking_id']       = url_decode($_POST['reSchedule_id']);							
							$detail_Arr['mer_id']           = $info->merchant_id;
							$detail_Arr['emp_id']           = $info->employee_id;
							$detail_Arr['service_type']     = $row->stype;
							$detail_Arr['has_buffer']       = $row->has_buffer;
						   if($row->stype==1){							
								$detail_Arr['setuptime']        = $row->setuptime;
								$detail_Arr['processtime']      = $row->processtime;
								$detail_Arr['finishtime']       = $row->finishtime;
								$detail_Arr['setuptime_start']  = $boojkstartTime;										 
																			
								$setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$row->setuptime.' minute'));
								$finishStart                    = date('Y-m-d H:i:s',strtotime(''.$setuEnd.' + '.$row->processtime.' minute'));
								$finishEnd                      = date('Y-m-d H:i:s',strtotime(''.$finishStart.' + '.$row->finishtime.' minute'));
								
								$detail_Arr['setuptime_end']    = $setuEnd;	
								$detail_Arr['finishtime_start'] = $finishStart;	
								$detail_Arr['finishtime_end']   = $finishEnd;
																				
								$boojkstartTime                 = $finishEnd;
							
						 }else{
							    $totalMin                       = $row->duration+$row->buffer_time;							
							    $setuEnd                        = date('Y-m-d H:i:s',strtotime(''.$boojkstartTime.' + '.$totalMin.' minute'));												
							    $detail_Arr['setuptime_start']  = $boojkstartTime;	
							    $detail_Arr['setuptime_end']    = $setuEnd;	
							    
							    $boojkstartTime                 = $setuEnd;
						  }
							
							
							$detail_Arr['service_id']    = $row->service_id;
							if(!empty($row->name))         
							$detail_Arr['service_name']  = $row->name;
						   else                            
							$detail_Arr['service_name']  = $row->category_name;
							
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

							$detail_Arr['price']           = 0;
							$detail_Arr['discount_price']  = 0;

							if(!empty($row->type) && $row->type=='open'){ 
								if($row->starttime<=date('H:i:s',strtotime($bk_time)) && $row->endtime>=date('H:i:s',strtotime($bk_time)))
						          {		  
									 if(!empty($row->discount_price)){
										$detail_Arr['price']           = $row->discount_price;
										$detail_Arr['discount_price']  = $row->price-$row->discount_price;
										}  
								      else $detail_Arr['price']        = $row->price;
								  }
								  else $detail_Arr['price']            = $row->price;
							   }
							 
							else $detail_Arr['price']                  = $row->price;
						         

							$detail_Arr['duration']                    = $row->duration+$row->buffer_time;
							$detail_Arr['buffer_time']                 = $row->buffer_time;
							$detail_Arr['updated_on']                  = date('Y-m-d H:i:s');
							$detail_Arr['user_id']                     = $row->user_id;
							$detail_Arr['updated_by']                  = $this->session->userdata('st_userid');
							$this->user->update('st_booking_detail',$detail_Arr,array('id'=>$row->id));
							

						}

						

			            $this->data['main'] = "";
						///mail section
						$this->data['main'] = $this->user->join_two('st_booking','st_users','merchant_id','id',array('st_booking.id' =>url_decode($_POST['reSchedule_id'])),'st_booking.id,business_name,booking_time,employee_id,book_id,st_booking.merchant_id,st_booking.user_id,st_booking.created_on,(select first_name from st_users where id=st_booking.employee_id) as first_name,(select last_name from st_users where id=st_booking.employee_id) as last_name,(select email from st_users where id=st_booking.user_id) as usemail,(select notification_status from st_users where id=st_booking.user_id) as user_notify,email_text');
						
						if(!empty($this->data['main'])){

							$field  = "st_users.id,first_name,last_name,service_id,profile_pic,service_name,duration,price,buffer_time,discount_price";  
							$whr    = array('booking_id'=>url_decode($_POST['reSchedule_id']));
							
							$this->data['booking_detail'] = $this->user->join_two('st_booking_detail','st_users','user_id','id',$whr,$field);
							$this->data['booking_detail'][0]->service_name = get_servicename($this->data['main'][0]->id);
							
							$body_msg=str_replace('*salonname*',$this->data['main'][0]->business_name,$this->lang->line("booking_reshedule_body"));
							$MsgTitle=$this->lang->line("booking_reshedule_title");
							
							if($info->booking_type=='guest'){
								$email                                         = $info->email;
								$this->data['booking_detail'][0]->first_name   = $info->fullname;
							}
							else{
								if($this->data['main'][0]->user_notify != 0){
									sendPushNotification($this->data['main'][0]->user_id,array('body'=>$body_msg,'title'=> $MsgTitle,'salon_id'=>$this->data['main'][0]->merchant_id ,'book_id'=> url_decode($_POST['reSchedule_id']), 'booking_status' => 'reschedule' ,'click_action' => 'BOOKINGDETAIL'));
									}
								$email = $this->data['main'][0]->usemail;
							}	

							$message = $this->load->view('email/reshedule_booking_new',$this->data, true);
						    $mail    = emailsend($email,$this->lang->line("styletimer_reschedule_booking"),$message,'styletimer');

							$empDat = is_mail_enable_for_user_action($this->data['main'][0]->employee_id);
							if ($empDat) {
								$tmp = $this->data;
								$tmp['main'][0]->salon_name = $empDat->first_name;
								$tmp['old_date'] = $oldDate;
								$message2 = $this->load->view('email/reshedule_booking_salon',$tmp, true);
								emailsend($empDat->email,$this->lang->line('styletimer_reschedule_booking'),$message2,'styletimer');
							}
						}
						$yrdata= strtotime($_POST['chg_date']);
						//$ddd = date('d F Y', $yrdata);
						$ddd = date('d.m.Y', $yrdata);
						$yrda = strtotime($_POST['chg_time']);
						$ttt = date('H:i', $yrda);
						
						echo json_encode(array('success'=>1,
						'msg' => 'Die Buchung wurde erfolgreich auf den '.$ddd.' um '.$ttt. ' Uhr verlegt.'));
					}
					else
						echo json_encode(array('success'=>0,'msg'=>'Sorry unable to process'));	
				  }
				else echo json_encode(array('success'=>0,'msg'=>'Der zugewiesene Mitarbeiter ist in diesem Zeitraum nicht verfügbar'));		
			   }	
			    else echo json_encode(array('success'=>0,'msg'=>'Der zugewiesene Mitarbeiter ist in diesem Zeitraum nicht verfügbar'));
			

			}

			//date("h:i");
		}
	else echo json_encode(array('success'=>0,'msg'=>'You can not reschedule this booking.'));	

	}


}
