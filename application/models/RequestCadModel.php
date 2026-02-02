<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RequestCadModel extends CI_Model
{
    private $mysqldatetime;
    public function __construct()
    {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo   = fnGetUserLoggedInfo('1');
        $this->companyid     = $ArrUserLoggedInfo['companyid'];
        date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
        $this->mysqldatetime = date('d/m/Y h:i A');
        $this->mysqltime = date('h:m:s');
        $this->userid        = $ArrUserLoggedInfo['id'];
        $this->subscriberid     = $ArrUserLoggedInfo['subscriber_id'];
         
         //$subid=$ArrUpdateData["subscriberid"];
    }
    
    function getSizeChart($enqId = '')
    {
        $this->db->select('size_ids');
        $ArrRes = $this->db->get_where('tbl_pc_size_chart', array('enquiry_id' => $enqId));
        return $ArrRes->row()->size_ids;
    }

    function getSizeMaster($size_ids = '')
    {
        $userInfoQry = "SELECT size_name FROM tbl_size_master sm WHERE sm.id IN (" . $size_ids . ")";
        $data        = $this->db->query($userInfoQry)->result_array();
        return $data;
    }

    function getSizeMasterDropdown($size_ids = '')
    {
        $userInfoQry = "SELECT id as id, size_name as name FROM tbl_size_master sm WHERE sm.id IN (" . $size_ids . ")";
        $data = $this->db->query($userInfoQry)->result_array();
        $allVar = [ 'id'=> '0', 'name' => 'All' ];
        $allVar1 = [ 'id'=> '00', 'name' => 'Running Size'];
        array_push($data, $allVar);
        array_push($data, $allVar1);
        return $data;
    }

    function getRequestDataa($enqId, $reqId)
    {
        $sql = "SELECT a.*, b.contactname as auth_name, a.ref_queue_no as sam_queue_no from tbl_request a
                INNER JOIN ".KN_USERS." as b on a.auth_by=b.id 
                where enquiry_id='$enqId' and request_id='$reqId' ";
                //print_r($sql);
        $data = $this->db->query($sql)->result_array();

        return $data;
    }
    
    // ********** CAD REQUEST STARTS HERE *********** /

    public function getCadRequestDetailss($id) {
        $sql = "SELECT * FROM tbl_cad_requirement as a WHERE a.enquiry_id = " . $id . "  and a.req_sent_status = 0";
        $data = $this->db->query($sql)->result_array();

        // $ref_sql = "SELECT b.ref_queue_no as id, b.ref_queue_no as name, a.po_enq_ref_id as po_enq_id, a.combo_id, a.component_id, a.spec_code_id as size_id, a.cad_requirement as req_id 
        //             FROM tbl_cad_requirement as a 
        //             INNER JOIN tbl_request_cad as b ON a.cad_requirement_id=b.cad_id
        //             WHERE a.enquiry_id = " . $id . " AND a.flag=1 and a.req_sent_status = 1 and b.ref_queue_no != '' ";
        // $ref_data = $this->db->query($ref_sql)->result_array();
        
        $ref_sql = "SELECT b.ref_queue_no as id, b.ref_queue_no as name, a.po_enq_ref_id as po_enq_id, a.combo_id, a.component_id, a.spec_code_id as size_id, a.cad_requirement as req_id 
                    FROM tbl_cad_requirement as a 
                    INNER JOIN tbl_request_cad as b ON a.cad_requirement_id=b.cad_id
                    WHERE a.enquiry_id = " . $id . "  and a.req_sent_status = 1 and b.ref_queue_no != '' ";
        $ref_data = $this->db->query($ref_sql)->result_array();

        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['cad_requirement_id'], $value['req_sent_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['cad_requirement'], "", "", "", $value['req_size']];
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $output['data'] = $result;
        $output['sizeData'] = $sizeMaster;
        $output['cadRefNo'] = $ref_data;
        return $output;
    }
    // ********** CAD REQUEST ENDS HERE *********** /

    // ********** CREATE CAD REQUEST STARTS HERE *********** /

    public function createCadRequestt($req_data, $id, $req_type, $cutoff_date, $merchant_note) {
        // print_r($req_data);
        $userInfo = fnGetUserLoggedInfo(1);
        $subscriberId =  $userInfo['subscriber_id'];
        $requestValue['enquiry_id'] = $id;
        $requestValue['companyid'] = $this->companyid;
        $requestValue['type'] = 1;
        $requestValue['req_type'] = $req_type;
        $requestValue['req_date'] = $this->mysqldatetime;
        $requestValue['req_by'] = $this->userid;
        $requestValue['cutoff_date'] = $cutoff_date;
        $requestValue['merchant_note'] = $merchant_note;
        $requestValue['subscriberid'] = $subscriberId;
        $requestValue['log'] = LOGTIME;
        //$requestValue['auth_status'] = 2;

        $this->db->insert('tbl_request', $requestValue);
        $primaryId = $this->db->insert_id();

        if($primaryId) {
            foreach($req_data as $key => $value) {
                $cadValue["request_id"] = $primaryId;
                $cadValue["cad_id"] = $value[0];
                $cadValue["purpose"] = $value[1];
                $cadValue["category"] = $value[2];
                $cadValue["if_revised"] = $value[3];
                $cadValue["req_size"] = $value[4];
                $cadValue["grad_measure_chart"] = $value[6];
                $cadValue["artwork"] = $value[7];
                $cadValue["measure_details"] = $value[8];
                $cadValue["buyer_sample"] = $value[9];
                $cadValue["buyer_comment"] = $value[10];
                // $cadValue['merchantid'] = $userid;
                // $cadValue['subscriberid'] = $subscriberId;
                $cadValue['log'] = LOGTIME;

                $this->db->insert('tbl_request_cad', $cadValue);
                // update request sent status 
                $this->db->where('cad_requirement_id', $value[0]);
                $this->db->update('tbl_cad_requirement', array('req_sent_status' => 1, 'request_id'=> $primaryId,'log' => LOGTIME));
            }
            $result['status'] = "success";
            $result['requestId'] = $primaryId;
            return $result;
        }
    }

    public function editCadRequestt($req_data, $reqId, $req_type, $cutoff_date, $merchant_note) {
        
        $requestValue['mgmt_approval'] = 3;
        $requestValue['auth_status'] = 3;
        $requestValue['req_type'] = $req_type;
        $requestValue['cutoff_date'] = $cutoff_date;
        $requestValue['merchant_note'] = $merchant_note;
        $requestValue['req_date'] = $this->mysqldatetime;
        $requestValue['auth_by'] = '';
        $requestValue['auth_date'] = '';
        $requestValue['auth_type'] = '';
        $requestValue['log'] = LOGTIME;

        $this->db->where('request_id', $reqId);
        $this->db->update('tbl_request', $requestValue);

        foreach($req_data as $key => $value) {
            $cadValue["purpose"] = $value[1];
            $cadValue["category"] = $value[2];
            $cadValue["if_revised"] = $value[3];
            $cadValue["req_size"] = $value[4];
            $cadValue["grad_measure_chart"] = $value[6];
            $cadValue["artwork"] = $value[7];
            $cadValue["measure_details"] = $value[8];
            $cadValue["buyer_sample"] = $value[9];
            $cadValue["buyer_comment"] = $value[10];
            $cadValue['log'] = LOGTIME;
            $this->db->where('request_cad_id', $value[0]);
            $this->db->update('tbl_request_cad', $cadValue);
        }

        $result['status'] = "success";
        return $result;
    }
      public function getuserdetail($user_type,$sub_id)
    {
        
        $sql = "SELECT * FROM " . KN_USERS . " as a
                WHERE a.usertype = ".$user_type."  and subscriber_id = " . $sub_id." ";
        $result = $this->db->query($sql)->result_array();
        
        
        return $result;
    }

    public function getsubscribercompanydetail($sub_id)
    {
        
           $sql = "SELECT * FROM " . KN_PROFORMAINVOICE . " as a
                WHERE subscriber_id = " . $sub_id." ";
        $result = $this->db->query($sql)->result_array();
        
        
        return $result;
    }

    // ********** CREATE CAD REQUEST ENDS HERE *********** /

    public function updateMgmtAuthorizationn($id, $request_id, $auth_status, $auth_type, $mgmt_remark) {
        $requestValue['auth_status'] = $auth_status;
        $requestValue['mgmt_approval'] = $auth_status;
        $requestValue['auth_by'] = $this->userid;
        $requestValue['auth_date'] = $this->mysqldatetime;
        $requestValue['auth_type'] = $auth_type;
        $requestValue['mgmt_remark'] = $mgmt_remark;
        $requestValue['log'] = LOGTIME;

        $this->db->where('request_id', $request_id);
        $this->db->update('tbl_request', $requestValue);
    }

    // *************************************************************************************** //
    // CAD DEPARTMENT STARTS HERE 
    // ************************************************************************************** //

    

    public function updateCadDepartStatuss($id, $req_id, $deprt_approval, $dep_remarks, $data) {

    // Check if the department approval is either '1' or 1
    if ($deprt_approval == '1' || $deprt_approval == 1) {
        
        // Fetch the maximum queue number and increment it for the new queue number
        $req_sql = "SELECT MAX(queue_no) + 1 as last_queue_no FROM tbl_request";
        $req_data = $this->db->query($req_sql)->result_array();
        
        // Get order enquiry details
        $ord_sql = "SELECT reqforisrior, isriorcode FROM kn_order_enquiry WHERE id = ?";
        $ord_data = $this->db->query($ord_sql, [$id])->result_array();
        
        // Extract necessary data from the order enquiry result
        $reqforisrior = $ord_data[0]['reqforisrior'];
        $ArrIsrIor = unserialize(ARRISRIOR);
        $reqisriorcode = $ord_data[0]['isriorcode'];
        $parts = explode('/', $reqisriorcode);
        $companyname = isset($parts[2]) ? $parts[2] : null;
        
        // Get subscriber ID
        $subid = $this->subscriberid;
        
        // Fetch the count of requests for the given subscriber
        $wipcount = $this->commonmodel->getcadrequestCountcq($subid);
        $count = ($wipcount > 0) ? $wipcount+1 : 1; // Default to 1 if no records exist
        
        $queue_no = $count; // Set the queue number for the main request
        
        // Generate the reference queue number for the main request
        $ref_queue_no = $ArrIsrIor[$reqforisrior] . "/" . date('my') . "/" . $companyname . "/CQ-" . $count;
        
        // Loop through the provided data for each request in tbl_request_cad
        foreach ($data as $key => $value) {
            
            // Fetch the count of requests for the given subscriber in tbl_request_cad
            $wipcounts = $this->commonmodel->getcadrequestCountcr($subid);
            $counts = ($wipcounts > 0) ? $wipcounts+1 : 1; // Default to 1 if no records exist
            
            // Generate the reference queue number for the cad request
            $ref_cad_queue_no = $ArrIsrIor[$reqforisrior] . "/" . date('my') . "/" . $companyname . "/CR-" . $counts;
            
            // Prepare the update data for tbl_request_cad
            $cadUpdateData = [
                'queue_no' => $counts,
                'ref_queue_no' => $ref_cad_queue_no,
                'log' => LOGTIME
            ];
            
            // Update the tbl_request_cad table with the new queue number
            $this->db->where('request_cad_id', $value[0]);
            $this->db->update('tbl_request_cad', $cadUpdateData);
        }
        
        // Prepare the update data for tbl_request
        $requestValue = [
            'queue_no' => $queue_no,
            'ref_queue_no' => $ref_queue_no,
            'req_status' => $deprt_approval,
            'deprt_approval' => $deprt_approval,
            'dep_remarks' => $dep_remarks,
            'que_assign_date' => $this->mysqldatetime,
            'log' => LOGTIME
        ];
        
        // Update the tbl_request table with the new queue number and status
        $this->db->where('enquiry_id', $id);
        $this->db->where('request_id', $req_id);
        $this->db->update('tbl_request', $requestValue);
    }

    // Return success status
    return ['status' => 'success'];
}
    
    public function UpdateCadQueueRemarkk_old($id, $req_id, $dep_remarks, $req_data) {
        //echo "<pre>"; print_r($req_data); exit;
        $requestValue['dep_remarks'] = $dep_remarks;
        $requestValue['log'] = LOGTIME;
        
        // update request sent status 
        $this->db->where('enquiry_id', $id);
        $this->db->where('request_id', $req_id);
        $this->db->update('tbl_request', $requestValue);    

        foreach($req_data as $key => $value) {
            if($value[3] == true) {
                
           
            if($value[11] == 0 && $value[10] != "" ) {
                $jobStatus['job_status'] = 1;
                $jobStatus['job_schd_dt'] = $value[10];
                $jobStatus['job_sta_upd'] = 0;
            }
            
            else if(($value[11] == 1 || $value[11] == 2) && $value[10] != "") {
                $jobStatus['job_status'] = 2;
                $jobStatus['job_schd_dt'] = $value[10];
                $jobStatus['job_re_sta_upd'] = 0;
                $jobStatus['editCount'] = $value[13]+1;
            }
           

             else if(($value[11] == 3 || $value[11] == 4 || $value[11] == 5|| $value[11] == 6 || $value[11] == 7||  $value[11] == 10) && $value[10] != "") {
                $jobStatus['job_status'] = $value[11];
                $jobStatus['job_schd_dt'] = $value[10];
                $jobStatus['job_re_sta_upd'] = 0;
                $jobStatus['editCount'] = $value[13]+1;
            }
           
            $jobStatus['job_status_upd_dt'] = $this->mysqldatetime;
            $jobStatus['job_update_status'] = 1;
            $jobStatus['log'] = LOGTIME;
            

            $this->db->where('cad_requirement_id', $value[0]);
            $this->db->update('tbl_cad_requirement', $jobStatus);
        }
        }
        if($this->db->affected_rows() > 0)
        {
            $result['status'] = "success";
        }
        else {
            $result['status'] = "failure";
        }

        return $result;
        
    }

    
   public function UpdateCadQueueRemarkk($id, $req_id, $dep_remarks, $req_data) {
    $requestValue['dep_remarks'] = $dep_remarks;
    $requestValue['log'] = LOGTIME;

    // Update the main request
    $this->db->where('enquiry_id', $id);
    $this->db->where('request_id', $req_id);
    $this->db->update('tbl_request', $requestValue);

    $update_count = 0;
    $result['status'] = 'job_failure'; // Default to job_failure
   $is_satisfied=false;

    $data=[];


   //print_r($req_data);
   //die;
     $id_arr = array();
    foreach ($req_data as $key => $value) {
    if ($value[3] == true && $value[11] == 4) {
       $id_arr[] = $value[0];

    }

    }
 $is_request_satisfied=true;
    foreach ($req_data as $key => $value) {
    if ($value[3] == true ) {
        $id_arr2[] = $value[11];
    }
}

// Check if 4 is in the array, and return false if it is
if (in_array(3, $id_arr2) || in_array(5, $id_arr2)) {
    $is_request_satisfied = false; // Return false if 3 or 5 is found
}


     $is_satisfied=false;
    $id_string = implode(',', $id_arr);

    $jobstatus_count=0;

    if(empty($id_string)) {
          $is_satisfied=true;
    }else{
 $sql = "SELECT * FROM tbl_cad_requirement as a 
                        WHERE a.cad_requirement_id IN ({$id_string}) 
                        AND a.enquiry_id = {$id} 
                        AND request_id = {$req_id} 
                        AND (a.qa_status = 5 OR a.qa_status = 6) 
                        AND (a.job_status = 3 OR a.job_status = 5)";
                $data = $this->db->query($sql)->result_array();
                $jobstatus_count=count($data);
              

                if(count($data) == count($id_arr) ) {
                     $is_satisfied=true;
                }

    }

   
             
                
if($is_request_satisfied) {
    
if($is_satisfied == true) {
     foreach ($req_data as $key => $value) {
        $jobStatus = [];
        //echo $value[11];
        //die;

        if ($value[3] == true) {
            if ($value[11] == 0 && $value[10] != "") {
                $jobStatus = [
                    'job_status' => 1,
                    'job_schd_dt' => $value[10],
                    'job_sta_upd' => 0
                ];
            } else if (($value[11] == 1 || $value[11] == 2) && $value[10] != "") {
                $jobStatus = [
                    'job_status' => 2,
                    'job_schd_dt' => $value[10],
                    'job_re_sta_upd' => 0,
                    'editCount' => $value[13] + 1
                ];
            } else if (($value[11] == 8)) {
                if($value[10] != ""){
                    $jobStatus = [
                    'job_status' => $value[11],
                    'job_schd_dt' => $value[10],
                    'job_re_sta_upd' => 0,
                    'editCount' => $value[13] + 1
                ]; 
                }else{
                      $jobStatus = [
                    'job_status' => $value[11],
                    'job_schd_dt' =>date('Y-m-d H:i:s'),
                    'job_re_sta_upd' => 0,
                    'editCount' => $value[13] + 1
                ]; 
                    
                }
               
            } else if (in_array($value[11], [3, 5, 6, 7]) && $value[10] != "") {
                $jobStatus = [
                    'job_status' => $value[11],
                    'job_schd_dt' => $value[10],
                    'job_re_sta_upd' => 0,
                    'editCount' => $value[13] + 1
                ];
            } else if (($value[11] == 4 || $value[11] === '4') && $value[10] != "") {
                
                if ($jobstatus_count > 0) {
                    $jobStatus = [
                        'job_status' => 4,
                        'job_schd_dt' => $value[10],
                        'job_re_sta_upd' => 0,
                        'editCount' => $value[13] + 1
                    ];
                } else {
                    // Fails the condition, return job_failure immediately
                    $result['status'] = 'job_failure';
                    return $result;
                }
            }

            // Apply update if jobStatus is set
            if (!empty($jobStatus)) {
                $jobStatus['job_status_upd_dt'] = $this->mysqldatetime;
                $jobStatus['job_update_status'] = 1;
                $jobStatus['log'] = LOGTIME;

                $this->db->where('cad_requirement_id', $value[0]);
                $this->db->update('tbl_cad_requirement', $jobStatus);

                if ($this->db->affected_rows() > 0) {
                    $update_count++;
                }
                 $result['status'] = 'success';
            }
        }
    }
}else{
    $result['status'] = 'job_failure';
}
}else{
    $result['status'] = 'req_failure';
}

   

    

    // Final result
    // if ($update_count > 0) {
    //     $result['status'] = 'success';
    // } else if ($result['status'] !== 'job_failure') {
    //     $result['status'] = 'failure';
    // }

    return $result;
}

    
    
    public function updateJobCompletedd($id, $req_id, $req_data) {
        
        $requestValue['log'] = LOGTIME;

        $this->db->where('enquiry_id', $id);
        $this->db->where('request_id', $req_id);
        $this->db->update('tbl_request', $requestValue);  
        foreach($req_data as $key => $value) {
            if($value[3] == true && $value[14] == 'Yes' ) {
                $jobStatus['job_status'] = 4;                
                $jobStatus['job_status_upd_dt'] = $this->mysqldatetime;
                $jobStatus['log'] = LOGTIME;

                $this->db->where('cad_requirement_id', $value[0]);
                $this->db->update('tbl_cad_requirement', $jobStatus);
            }
        }
        
        if($this->db->affected_rows() > 0)
        {
            $result['status'] = "success";
        }
        else {
            $result['status'] = "failure";
        }

        return $result;
        
        
    }

    // public function UpdateCadQueueRemarkk($id, $req_id, $dep_remarks, $req_data) {
    //     echo "<pre>"; print_r($req_data); exit;
    //     $requestValue['dep_remarks'] = $dep_remarks;
    //     $requestValue['log'] = LOGTIME;

    //     // update request sent status 
    //     $this->db->where('enquiry_id', $id);
    //     $this->db->where('request_id', $req_id);
    //     $this->db->update('tbl_request', $requestValue);    

    //     foreach($req_data as $key => $value) {
    //         $jobStatus['job_status'] = $value[10];
    //         if($value[10] == 1) {
    //             $jobStatus['job_schd_dt'] = $value[9];
    //             $jobStatus['job_sta_upd'] = 0;
    //         }
    //         else if($value[10] == 2) {
    //             $jobStatus['job_schd_dt'] = $value[9];
    //             $jobStatus['job_re_sta_upd'] = 0;
    //         }
    //         else if($value[10] == 8) {
    //             $jobStatus["qa_req_sent_status"] = 0;
    //             $jobStatus["qa_approval"] = 0;
    //             $jobStatus["qa_status"] = 0;
    //             $jobStatus["qa_schd_date"] = '';
    //             $jobStatus["qa_sta_upd_dt"] = '';
    //             $jobStatus["qa_sta_upd"] = 1;
    //             $jobStatus["qa_re_sta_upd"] = 1;
    //         }
    //         $jobStatus['job_status_upd_dt'] = $this->mysqldatetime;
    //         $jobStatus['job_update_status'] = 1;
    //         $jobStatus['log'] = LOGTIME;
            
    //         $this->db->where('cad_requirement_id', $value[0]);
    //         $this->db->update('tbl_cad_requirement', $jobStatus);
    //     }
    //     if($this->db->affected_rows() > 0)
    //     {
    //         $result['status'] = "success";
    //     }
    //     else {
    //         $result['status'] = "failure";
    //     }

    //     return $result;
        
    // }

    public function getrequestDetailss($id, $reqId ) {
        $req_sql = "SELECT a.* FROM tbl_request as a WHERE a.enquiry_id='$id' and a.request_id='$reqId' ";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $readStatus = true;
        if(sizeof($req_data) > 0) {
            $type = $req_data[0]['type'];
            $data = [];
            if($type == 1) {
                $sql = "SELECT *, a.ref_queue_no as cad_ref_no,a.log as logs FROM tbl_request_cad as a inner join tbl_cad_requirement as b on a.cad_id = b.cad_requirement_id 
                        WHERE a.request_id='$reqId' ";
                $data = $this->db->query($sql)->result_array();
            }
        }
        else {
            $data = [];
        }

        $result = [];
        foreach ($data as $key => $value)
        {

    //         if($value['qa_approval'] == 0  || $value['qa_approval'] == "0") {
    //     // If 'qa_schd_date' is empty, set 'qa_status' to 8
    //     $value['qa_status'] = '8';///PENDING
    // }
     if($value['qa_approval'] == '0'  || $value['qa_approval'] == 0) {
        // If 'qa_schd_date' is empty, set 'qa_status' to 8
        $value['qa_status'] = '8';///PENDING
        
          }

          if($value['qa_approval'] == 1 && $value['qa_schd_date'] == null) {
            $value['qa_sta_upd_dt'] = $value['qno_assign_dt'];
            
              }

  
              if($value['job_status'] == 0 && $value['job_schd_dt'] == "" ) {
        // If 'qa_schd_date' is empty, set 'qa_status' to 8
        $value['job_status_upd_dt'] = date('d/m/Y h:i A',strtotime($value['logs']));
    }


              

         
            
    $result[$key] = [$value['request_cad_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['cad_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value["req_size"]];
            
            $referenceData[$key] = [$value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['grad_measure_chart'], $value["artwork"], $value["measure_details"], $value["buyer_sample"], $value["buyer_comment"]];
            
            $qaStatusData[$key] = [$value['request_cad_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['cad_requirement'], $value['qa_req_sent_dt'], $value['qa_schd_date'], $value['qa_status'], $value['qa_sta_upd_dt'] ];
            
            $jobStatusData[$key] = [$value['request_cad_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['cad_requirement'], $value['cad_ref_no'], $value['job_schd_dt'], $value['job_status'], $value['job_status_upd_dt'] ];
        }

    

        // *** get garment size *** //
        $sizeChart  = $this->getSizeChart($id);
        $sizeMaster = $this->getSizeMasterDropdown($sizeChart);

        // ******* GET THE COLUMN ENDS ********* //
        $output['data'] = $result;
        $output['requestData'] = $referenceData;
        $output['qaStatusData'] = $qaStatusData;
        $output['jobStatusData'] = $jobStatusData;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }

    public function getcadqarequestDetailss($id, $reqId ) {
        $req_sql = "SELECT a.* FROM tbl_request as a WHERE a.enquiry_id='$id' and a.request_id='$reqId' ";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $readStatus = true;
        if(sizeof($req_data) > 0) {
            $type = $req_data[0]['type'];
            $data = [];
            if($type == 1) {
                $sql = "SELECT *,a.ref_queue_no as cad_ref_no, a.log as logs, b.qa_approval as qa_approval FROM tbl_request_cad as a inner join tbl_cad_requirement as b on a.cad_id = b.cad_requirement_id 
                        WHERE a.request_id='$reqId' ";
                $data = $this->db->query($sql)->result_array();

                $qa_sql = "SELECT *,a.ref_queue_no as cad_ref_no FROM tbl_request_cad as a inner join tbl_cad_requirement as b on a.cad_id = b.cad_requirement_id 
                        WHERE a.request_id='$reqId' ";
                $qa_data = $this->db->query($qa_sql)->result_array();
                
            }
        }
        else {
            $data = [];
        }

        $result = [];
        $jobStatusData = [];
        $qaStatusData = [];
        foreach ($data as $key => $value)
        {
            // $status = false;
            // if($value['job_update_status'] == 1) {
            //     $status = true;
            if(($value['qa_status'] == 5 || $value['qa_status'] == 6) && ($value['job_status'] == 3 || $value['job_status'] == 5)) {
                $qaPass = 'Yes';
            } else {
                $qaPass = 'No';;
            }
            if($value['job_status'] == 4) {
                $checkVal = 0;
            } else {
                $checkVal = 0;
            }

              if($value['job_status'] == 0 && $value['job_schd_dt'] == "" ) {
        // If 'qa_schd_date' is empty, set 'qa_status' to 8
        $value['job_status_upd_dt'] = date('d/m/Y h:i A',strtotime($value['logs']));
    }



                $jobStatusData[$key] = [$value['cad_requirement_id'], $value['job_sta_upd'], $value['job_re_sta_upd'], $checkVal, $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                                $value['cad_requirement'], $value['cad_ref_no'], $value['job_schd_dt'], $value['job_status'], $value['job_status_upd_dt'],$value['editCount'], $qaPass, $value['qa_status'] ,$value['qa_approval']];
            // }
            $result[$key] = [ $value['cad_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['cad_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value["req_size"]];
            
            $referenceData[$key] = [$value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['grad_measure_chart'], $value["artwork"], $value["measure_details"], $value["buyer_sample"], $value["buyer_comment"]];
        }

        // foreach ($qa_data as $key => $value) {
        //     $qaStatusData[$key] = [$value['cad_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
        //                     $value['cad_requirement'], $value['cad_ref_no'], $value['qa_req_sent_dt'], $value['qa_schd_date'], $value['qa_status'], $value['qa_sta_upd_dt'] ];
        // }
         foreach ($qa_data as $key => $value) {
            if($value['qa_approval'] == 0) {
        // If 'qa_schd_date' is empty, set 'qa_status' to 8
        $value['qa_status'] = '8';///PENDING
    }

    if($value['qa_approval'] == 1 && $value['qa_schd_date'] == null) {
               $value['qa_sta_upd_dt'] = $value['qno_assign_dt'];
            
              }


   

            $qaStatusData[$key] = [$value['cad_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['cad_requirement'], $value['cad_ref_no'], $value['qa_req_sent_dt'], $value['qa_schd_date'], $value['qa_status'], $value['qa_sta_upd_dt'] ];
        }


        // *** get garment size *** //
        $sizeChart  = $this->getSizeChart($id);
        $sizeMaster = $this->getSizeMasterDropdown($sizeChart);

        // ******* GET THE COLUMN ENDS ********* //
        $output['data'] = $result;
        $output['qadata'] = $data;
        $output['requestData'] = $referenceData;
        $output['qaStatusData'] = $qaStatusData;
        $output['jobStatusData'] = array_values($jobStatusData);
        $output['sizeData'] = $sizeMaster;
        return $output;
    }

    public function updateCadQARequestt($id, $req_id, $qa_cutoff_date, $dep_note, $req_data) {
        
        //echo "<pre>"; print_r($req_data); exit;
        $requestValue['qa_cutoff_date'] = $qa_cutoff_date;
        $requestValue['dep_note'] = $dep_note;
        $requestValue['qa_req_date'] = $this->mysqldatetime;
        $requestValue['dept_qa_request'] = 1;
        $requestValue['cad_by'] = $this->userid;
        $requestValue['log'] = LOGTIME;

        // update request sent status
        
         $this->db->where('enquiry_id', $id);
         $this->db->where('request_id', $req_id);
         $this->db->update('tbl_request', $requestValue);
        
        $qaData['enquiry_id'] =$id;
        $qaData['request_id'] =$req_id;
        
        
        if($req_data[0][13] == 0 || $req_data[0][13] == "0") {
            $this->db->insert('qa_request',$qaData);
            $qa_req_id = $this->db->insert_id();
        } else {
            $qa_req_id = $req_data[0][13];
        }


       // print_r($req_data); exit;
        foreach ($req_data as $key => $value) {
            // if($value[1] == true)
            // {
                // if($value[13] == 0 || $value[13] == "0") {
                    
                // } else {
                //     $qa_req_id = $req_data[0][13];
                // }
                // if($value[12] == 7 || $value[12] == 4 || $value[12] == "7" || $value[12] == "4") {
                //     $job_status = 5;
                //     $qa_approval = 2;
                // } else {
                //     $job_status = 3;
                //     $qa_approval = 0;
                // }

               // print_r($req_data);die;

                if($value[12] == 7 ||  $value[12] == "7" ) {
                    $job_status = 5;
                    $qa_approval = 2;
                } else if($value[12] == 8 || $value[12] == "8") {
                    $job_status = 3;
                    $qa_approval = 0;
                }
                
                $this->db->where('cad_requirement_id', $value[0]);
                $this->db->update('tbl_cad_requirement', array('qa_req_id' => $qa_req_id, 'job_status' => $job_status, 'qa_approval' => $qa_approval, 'qa_req_sent_status'=> 1, 'job_status_upd_dt'=> $this->mysqldatetime,'qa_req_sent_dt'=> $this->mysqldatetime,'log' => LOGTIME));
           // }
        }

        if($this->db->affected_rows() > 0)
        {
            $result['status'] = "success";
        }
        else {
            $result['status'] = "failure";
        }

        return $result;

    }

    public function updateCadQAReceivedListt($id, $req_id, $qa_req_status, $qa_dept_remarks, $tbl_data) {
        
        if($qa_req_status == 1)
        {

            $cad_req_sql = "SELECT MAX(queue_no)+1 as last_queue_no FROM tbl_cad_requirement";
            $cad_req_data = $this->db->query($cad_req_sql)->result_array();

            $samp_req_sql = "SELECT MAX(queue_no)+1 as last_queue_no FROM tbl_sample_requirement";
            $sam_req_data = $this->db->query($samp_req_sql)->result_array();
    
            $ord_sql = "SELECT reqforisrior FROM kn_order_enquiry WHERE id=$id";
            $ord_data = $this->db->query($ord_sql)->result_array();
            $reqforisrior = $ord_data[0]['reqforisrior'];
            $ArrIsrIor   = unserialize(ARRISRIOR);
    
            $cad_queue_no = $cad_req_data[0]['last_queue_no'];
            $sam_queue_no = $sam_req_data[0]['last_queue_no'];

            //$queue_no = $req_data[0]['last_queue_no'];

            if($cad_queue_no == "" && $sam_queue_no == "") 
            { 
                $queue_no = 1;
            }
            else {
                if($cad_queue_no > $sam_queue_no) {
                    $queue_no = $cad_queue_no;
                }
                else {
                    $queue_no = $sam_queue_no;
                }
            }
    
            $ref_queue_no = $ArrIsrIor[$reqforisrior]."-BSG".$id."/".date('my')."/QQ".$queue_no;

        }

        $requestValue['qa_dept_remarks'] = $qa_dept_remarks;
        $requestValue['qno_assign_dt'] = $this->mysqldatetime;
        $requestValue['qa_approval'] = $qa_req_status;
        //$requestValue['qa_status'] = 3;
        $requestValue['log'] = LOGTIME;

        foreach ($tbl_data as $key => $value) {

         
            
            $qa_app = $this->db->where('cad_requirement_id',$value[0])->get('tbl_cad_requirement')->row()->qa_approval;
           
            if($qa_app == 0) {
                $requestValue['queue_no'] = $queue_no;
                $requestValue['ref_queue_no'] = $ref_queue_no;
                $requestValue['qa_status'] = 0;
            } else {
                //$requestValue['qa_status'] = 3;
                $requestValue['qa_status'] = 9;
                $requestValue['qa_schd_date'] = '';
                $requestValue['editCount'] = 0;
                
            }
            
            $this->db->where('cad_requirement_id', $value[0]);
            $this->db->update('tbl_cad_requirement', $requestValue);
        }
        
    }

    public function getqaqueueDetailss($id, $reqId, $cadId) {
        $req_sql = "SELECT a.* FROM tbl_request as a WHERE a.enquiry_id='$id' and a.request_id='$reqId' ";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $readStatus = true;
        if(sizeof($req_data) > 0) {
            $type = $req_data[0]['type'];
            $data = [];
            if($type == 1) {
                $sql = "SELECT *, a.ref_queue_no as ref_no FROM tbl_request_cad as a inner join tbl_cad_requirement as b on a.cad_id = b.cad_requirement_id 
                        WHERE a.request_id='$reqId' and b.qa_req_id= '$cadId' and b.qa_approval=1 ";
                $data = $this->db->query($sql)->result_array();
            }
        }
        else {
            $data = [];
        }

        $result = [];
        $referenceData = [];
        $qaStatusData = [];
        $qaStatus = [];
        $qaStatus1 = '';
        foreach ($data as $key => $value)
        {
            if($value['qa_status'] == "5" || $value['qa_status'] == "6" || $value['qa_status'] == "7") {
                $qaStatus[] = 1;
            } else {
                $qaStatus[] = 0;
            }

             if($value['qa_approval'] == 1 &&  $value['qa_schd_date'] == null) {
               $value['qa_sta_upd_dt'] = $value['qno_assign_dt'];
            
              }


            $result[$key] = [$value['request_cad_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['cad_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value["req_size"], $value['ref_no']];
            
            $referenceData[$key] = [$value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['grad_measure_chart'], $value["artwork"], $value["measure_details"], $value["buyer_sample"], $value["buyer_comment"]];
            
            $qaStatusData[$key] = [ $value['cad_requirement_id'], $value['qa_sta_upd'], $value['qa_re_sta_upd'], '',$value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['cad_requirement'], $value['ref_no'], $value['qa_req_sent_dt'], $value['qa_schd_date'], $value['qa_status'], $value['qa_sta_upd_dt'] ];
        }

        // *** get garment size *** //
        $sizeChart  = $this->getSizeChart($id);
        $sizeMaster = $this->getSizeMasterDropdown($sizeChart);

        // ******* GET THE COLUMN ENDS ********* //
        
        if(in_array(0,$qaStatus)) {
            $qaStatus1 = 'pending';
        } else {
            $qaStatus1 = 'completed';
        }
        $output['data'] = $result;
        $output['requestData'] = $referenceData;
        $output['qaStatusData'] = $qaStatusData;
        $output['cad_data'] = $data;
        $output['qaStatus'] = $qaStatus1;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }

    public function UpdateCadQAQueueListt($id, $req_id, $qa_dept_remarks, $req_data) {

        foreach ($req_data as $key => $value) {
            if($value[3] == true) {
           
            $updateValue['qa_sta_upd_dt'] = $this->mysqldatetime;
            if(($value[12] == 0 ) && $value[11] != "" ) {
                $updateValue['qa_status'] = 1;
                $updateValue['qa_schd_date'] = $value[11];
                $updateValue['qa_sta_upd'] = 0;
            }
             if(($value[12] == 9 ) && $value[11] != "" ) {
                $updateValue['qa_status'] = 1;
                $updateValue['qa_schd_date'] = $value[11];
                $updateValue['qa_sta_upd'] = 0;
            }
            
            else if(($value[12] == 1 || $value[12] == 2) && $value[11] != "") {
                $updateValue['qa_status'] = 2;
                $updateValue['qa_schd_date'] = $value[11];
                $updateValue['qa_re_sta_upd'] = 0;
                //$updateValue['editCount'] = $value[12]+1;
            }
            else if($value[12] == 3) {
                $updateValue['qa_status'] = $value[12];
                if($value[11] != "") {
                   $updateValue["qa_schd_date"] = $value[11];  
                }else{
                     $updateValue["qa_schd_date"] = date('Y-m-d H:i:s');
                }
               
                $updateValue['qa_re_sta_upd'] = 0;
            }


            else if(($value[12] == 4 || $value[12] == 5 || $value[12] == 6 ) && $value[11] != "") {
                $updateValue['qa_status'] = $value[12];
                $updateValue["qa_schd_date"] = $value[11];
                $updateValue['qa_re_sta_upd'] = 0;
            }else if(($value[12] == 7 ) && $value[11] != "" ) {
                $updateValue['qa_status'] = $value[12];
                $updateValue["qa_schd_date"] = $value[11];
                $updateValue['qa_re_sta_upd'] = 0;
                $updateValue['job_status'] = 6;
                $updateValue['job_status_upd_dt'] = $this->mysqldatetime;

                
            }
           

            
            $updateValue['qa_dept_remarks'] = $qa_dept_remarks;
            $updateValue['log'] = LOGTIME;
            if($value[12] == 0 ||  $value[12] == 1 || $value[12] == 2 || $value[12] == 3|| $value[12] == 4|| $value[12] == 5|| $value[12] == 6|| $value[12] == 7 || $value[12] == 9) {
                $this->db->where('cad_requirement_id', $value[0]);
                $this->db->update('tbl_cad_requirement', $updateValue);
            }
                 
            }
        }
        
        $result["status"] = "success";
        return $result;
        
    }

    // *************************************************************************************** //
    // CAD DEPARTMENT ENDS HERE 
    // ************************************************************************************** //

    // IMAGE UPLOAD

    public function uploadCADReqImagess($type, $id, $filepathName, $reqId, $deptId)
    {
        if(!isset($deptId))
        {
            $this->db->insert('tbl_wip_files', array('enquiry_id'=> $id, 'request_id'=> $reqId, 'type'=> $type, 'image_url'=>$filepathName,'log' => LOGTIME));
            $result["status"] = "success";
            return $result;
        }
        else {
            $this->db->insert('tbl_wip_files', array('enquiry_id'=> $id, 'request_id'=> $reqId, 'type'=> $type, 'image_url'=>$filepathName, 'dept_id'=> $deptId,'log' => LOGTIME));
            $result["status"] = "success";
            return $result;
        }
    }

    function getQAStatus($enqId, $reqId)
    {
        $sql = "SELECT * FROM tbl_cad_requirement as a WHERE a.enquiry_id = " . $enqId . " AND request_id = " . $reqId . "  and (a.qa_req_sent_status = 0 or a.qa_status = 7 or a.qa_status = 4)";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

     function getQAjobStatus($enqId, $reqId)
    {
        $sql = "SELECT * FROM tbl_cad_requirement as a WHERE a.enquiry_id = " . $enqId . " AND request_id = " . $reqId . "   AND (a.job_status = 3 or a.job_status = 5)";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function getreqStatus($enqId, $reqId)
    {
        $sql = "SELECT * FROM tbl_cad_requirement as a WHERE a.enquiry_id = " . $enqId . " AND request_id = " . $reqId . "   AND (a.job_status = 8 or a.job_status = 7)";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    function getQACompletedStatus($enqId, $reqId)
    {
        $sql = "SELECT * FROM tbl_cad_requirement as a WHERE a.enquiry_id = " . $enqId . " AND request_id = " . $reqId . "  and (a.qa_status = 5 or a.qa_status = 6) and (a.job_status = 3 or a.job_status = 5) ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function getMIData($miId)
    {
        $sql = "SELECT * FROM tbl_mi_details as a WHERE a.mi_id = " . $miId;
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function getCADMIData($miId, $dc)
    {
        $sql = 'SELECT a.*,b.*,c.ref_queue_no FROM tbl_mi_details as a 
                INNER JOIN tbl_mi_cad_details as b ON a.request_id=b.request_id
                INNER JOIN tbl_request as c ON a.request_id=c.request_id
                WHERE b.dc_ref_queue_no = '.$dc.' GROUP BY b.dc_ref_queue_no';
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function getCadMIDetailss($id, $reqId, $miId)
    {        
        $sql = "SELECT *
                FROM tbl_mi_details as a
                INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                WHERE a.mi_id=".$miId." AND c.dc_status = 1 ";
        $result = $this->db->query($sql)->result_array();

        $cadMIData = [];

        foreach ($result as $key => $value) {
            if($value['item_issued'] == '') {
                $item_issued = $value['req'];
                $issued_size = $value['req_size'];
            } else {
                $item_issued = $value['item_issued'];
                $issued_size = $value['issued_size'];
            }
            $cadMIData[$key] = [
                $value['mat_ind_cad_id'], $value['po_no'], $value['combo'], $value['component'], $value['spec_code'],
                $value['cad_ref'], $value['req'], $value['purpose'], $value['req_size'], $item_issued,
                $issued_size, $value['parts_issued'], $value['dc_ref_queue_no'], $value['issue_date']
            ];
        }

        // *** get garment size *** //
        $sizeChart  = $this->getSizeChart($id);
        $sizeMaster = $this->getSizeMasterDropdown($sizeChart);
        
        $cad_sql = "SELECT c.po_enq_ref_id as po_id, c.combo_id, c.component_id, c.spec_code_id as size_id, a.request_cad_id as id, a.ref_queue_no as name FROM tbl_request_cad a
                INNER JOIN tbl_request b ON a.request_id = b.request_id
                INNER JOIN tbl_cad_requirement c ON a.cad_id = c.cad_requirement_id
                WHERE b.enquiry_id = ".$id." ";
        $cad_ref_data = $this->db->query($cad_sql)->result_array();

        $data['cadMIData'] = $cadMIData;
        $data['sizeData'] = $sizeMaster;
        $data['cadRefNo'] = $cad_ref_data;
        return $data;
    }

    public function checkDCStatus($miId)
    {
        $sql = "SELECT *
            FROM tbl_mi_details as a
            INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
            WHERE a.mi_id=".$miId." AND c.dc_status = 1 ";
        $result = $this->db->query($sql)->result();
        return sizeof($result);
    }

    public function updateCadIndentDetailss($id, $reqId, $data)
    {
        $count = 0;
        foreach ($data as $key => $value) {
            $count++;
            $updateValue['item_issued'] = $value[9];
            $updateValue['issued_size'] = $value[10];
            $updateValue['parts_issued'] = $value[11];
            $updateValue['log'] = LOGTIME;
                  
            $this->db->where('mat_ind_cad_id', $value[0]);
            $this->db->update('tbl_mi_cad_details', $updateValue);
        }
        if($count > 0)
        {
            $result['status'] = "success";
        }
        else {
            $result['status'] = "failure";
        }
        return $result;
        
    }

    public function getDCListt($id, $miId)
    {
        $sql = "SELECT *
                FROM tbl_mi_details as a
                INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                WHERE a.mi_id=".$miId." AND c.dc_status = 1 ";
        $result = $this->db->query($sql)->result_array();

        $cadMIData = [];

        $mi_type = "";
        foreach ($result as $key => $value) {
            if($value['type'] != "" || $value['type'] != null)
            {
                $mi_type = $value['type'];
            }
            $cadMIData[$key] = [
                $value['mat_ind_cad_id'], false, $value['po_no'], $value['combo'], $value['component'], $value['spec_code'],
                $value['cad_ref'], $value['item_issued'], $value['issued_size'], $value['parts_issued']
            ];
        }

        // *** get garment size *** //
        $sizeChart  = $this->getSizeChart($id);
        $sizeMaster = $this->getSizeMasterDropdown($sizeChart);
        
        $cad_sql = "SELECT c.po_enq_ref_id as po_id, c.combo_id, c.component_id, c.spec_code_id as size_id, a.request_cad_id as id, a.ref_queue_no as name FROM tbl_request_cad a
                INNER JOIN tbl_request b ON a.request_id = b.request_id
                INNER JOIN tbl_cad_requirement c ON a.cad_id = c.cad_requirement_id
                WHERE b.enquiry_id = ".$id."  ";
        $cad_ref_data = $this->db->query($cad_sql)->result_array();

        $data['cadMIData'] = $cadMIData;
        $data['mi_type'] = $mi_type;
        $data['sizeData'] = $sizeMaster;
        $data['cadRefNo'] = $cad_ref_data;
        return $data;
    }

    public function updateDCListt($id, $reqId, $miId, $data, $received_by, $mi_type)
    {
        $req_sql = "SELECT ref_queue_no FROM tbl_request WHERE request_id = ".$reqId;
        $req_data = $this->db->query($req_sql)->result_array();

        $cad_mi_sql = "SELECT MAX(dc_queue_no)+1 as last_queue_no FROM tbl_mi_cad_details";
        $cad_mi_data = $this->db->query($cad_mi_sql)->result_array();

        $ord_sql = "SELECT reqforisrior FROM kn_order_enquiry WHERE id=$id";
        $ord_data = $this->db->query($ord_sql)->result_array();
        $reqforisrior = $ord_data[0]['reqforisrior'];
        $ArrIsrIor   = unserialize(ARRISRIOR);

        //$queue_no = $cad_mi_data[0]['last_queue_no'];
        //if($queue_no == "") { $queue_no = 1; }

             $subid = $this->subscriberid;
             $wipcount = $this->commonmodel->getcadrequestCountDC($subid);
             $count = ($wipcount > 0) ? $wipcount+1 : 1; // Default to 1 if no records exist
             $queue_no = $count; 
    

        $ref_queue_no = $req_data[0]['ref_queue_no']."/CDC-".$queue_no;

        $sql = "SELECT * FROM tbl_mi_cad_details WHERE request_id = " . $reqId . " AND dc_status = 1";
        $cadMIData = $this->db->query($sql)->result_array();

        foreach ($data as $key => $value) {
            if($value[1] == false) {
                unset($data[$key]);
            }
        }

        $data = array_values($data);

        if(sizeof($data) == sizeof($cadMIData))
        {
            $this->db->where('request_id', $reqId);
            $this->db->update('tbl_mi_details', array( 'dc_comp_status'=> 1 ,'log' => LOGTIME));
        }

        foreach ($data as $key => $value) {
            if($value[1] == true)
            {
                $updateValue['dc_status'] = 0;
                $updateValue['mi_issued_by'] = $this->userid;
                $updateValue['dc_queue_no'] = $queue_no;
                $updateValue['dc_ref_queue_no'] = $ref_queue_no;
                $updateValue['dc_dt'] = $this->mysqldatetime;
                $updateValue['material_received_by'] = $received_by;
                $updateValue['parts_issued'] = $value[9];
                $updateValue['mi_type'] = $mi_type;
                $updateValue['log'] = LOGTIME;
                
                $this->db->where('mat_ind_cad_id', $value[0]);
                $this->db->update('tbl_mi_cad_details', $updateValue);
            }
        }
        if($this->db->affected_rows() > 0)
        {
            $result['status'] = "success";
        }
        else {
            $result['status'] = "failure";
        }
        return $result;
    }


    public function updateMIDCListt($id, $reqId, $dc, $irs)
    {
        $updateValue['item_sta_upt_dt'] = $this->mysqldatetime;
        $updateValue['item_received_status'] = $irs;
        $updateValue['log'] = LOGTIME;
        $this->db->where('dc_ref_queue_no', $dc);
        $this->db->update('tbl_mi_cad_details', $updateValue);
       
    }

    function getDCDetails($enqId, $reqId, $miId)
    {
        $sql = "SELECT a.*, b.*, b.contactname as sam_name, a.ref_queue_no as sam_queue_no 
                from tbl_request a
                LEFT JOIN ".KN_USERS." as b on a.cad_by=b.id
                where enquiry_id='$enqId' and request_id='$reqId' ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function getCADDCListt($id, $miId, $dc)
    {
        $dc = '"'.$dc.'"';
        $sql = "SELECT * FROM tbl_mi_details as a
                INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                WHERE c.mi_id=".$miId."  AND c.dc_status = 0 ";
        $result = $this->db->query($sql)->result_array();

        $cadMIData = [];

        foreach ($result as $key => $value) {
            $cadMIData[$key] = [
                $value['mat_ind_cad_id'], $value['po_no'], $value['combo'], $value['component'], $value['spec_code'],
                $value['cad_ref'], $value['item_issued'], $value['issued_size'], $value['parts_issued']
            ];
        }

        // *** get garment size *** //
        $sizeChart  = $this->getSizeChart($id);
        $sizeMaster = $this->getSizeMasterDropdown($sizeChart);
        
        $cad_sql = "SELECT c.po_enq_ref_id as po_id, c.combo_id, c.component_id, c.spec_code_id as size_id, a.request_cad_id as id, a.ref_queue_no as name FROM tbl_request_cad a
                INNER JOIN tbl_request b ON a.request_id = b.request_id
                INNER JOIN tbl_cad_requirement c ON a.cad_id = c.cad_requirement_id
                WHERE b.enquiry_id = ".$id."  ";
        $cad_ref_data = $this->db->query($cad_sql)->result_array();

        $data['cadMIData'] = $cadMIData;
        $data['sizeData'] = $sizeMaster;
        $data['cadRefNo'] = $cad_ref_data;
        return $data;
    }
    
    public function getCADDCListt_print($id, $miId, $dc)
    {
        $result = [];
        $dc = '"'.$dc.'"';
        // $sql = "SELECT a.*,c.*, d.size_name,e.ref_queue_no FROM tbl_mi_details as a
        //         INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
        //         INNER JOIN tbl_size_master as d on c.issued_size = d.id
        //         INNER JOIN tbl_request_cad as e on cad_ref = e.request_cad_id
        //         WHERE c.mi_id=".$miId." AND c.dc_ref_queue_no = $dc AND c.dc_status = 0 AND a.flag=1";
        // $result = $this->db->query($sql)->result_array();

        // $sql = "SELECT * FROM tbl_mi_details as a
        //         INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
        //         WHERE c.mi_id=".$miId." AND c.dc_ref_queue_no = $dc AND c.dc_status = 0 AND a.flag=1";
        // $result = $this->db->query($sql)->result_array();


        $sql = "SELECT * FROM tbl_mi_details as a
                INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                WHERE c.mi_id=".$miId."  AND c.dc_status = 0 ";
        $result = $this->db->query($sql)->result_array();

       foreach ($result as $key => $row) {

    // req_size
    // if (!empty($row['req_size'])) {
    //     $sizeMaster = $this->getSizeMasterDropdown($row['req_size']);
    //     if (!empty($sizeMaster) && isset($sizeMaster[0]['name'])) {
    //         $result[$key]['req_size'] = $sizeMaster[0]['name'];
    //     }
    // }

    // issued_size
    if (!empty($row['issued_size'])) {
        $sizeMaster = $this->getSizeMasterDropdown($row['issued_size']);
        if (!empty($sizeMaster) && isset($sizeMaster[0]['name'])) {
            $result[$key]['issued_size'] = $sizeMaster[0]['name'];
        }
    }
}
        



         //print_r($sizeMaster[0]['name']);
          //print_r($result);
        // die;
        
        return $result;
    }
    
    public function getqacadqueueDetailss($id, $reqId, $cadId) {
        $req_sql = "SELECT a.* FROM tbl_request as a WHERE a.enquiry_id='$id' and a.request_id='$reqId' ";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $readStatus = true;
        if(sizeof($req_data) > 0) {
            $type = $req_data[0]['type'];
            $data = [];
            if($type == 1) {
                $sql = "SELECT *, a.ref_queue_no as ref_no FROM tbl_request_cad as a inner join tbl_cad_requirement as b on a.cad_id = b.cad_requirement_id 
                        WHERE a.request_id='$reqId' and b.qa_req_id= '$cadId' ";
                $data = $this->db->query($sql)->result_array();
            }
        }
        else {
            $data = [];
        }

        $result = [];
        $referenceData = [];
        $qaStatusData = [];
        $qaStatus = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['request_cad_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['cad_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value["req_size"], $value['ref_no']];
            
            $referenceData[$key] = [$value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['grad_measure_chart'], $value["artwork"], $value["measure_details"], $value["buyer_sample"], $value["buyer_comment"]];
                            
            if($value['qa_status'] == "5" || $value['qa_status'] == "6" || $value['qa_status'] == "7") {
                $checkVal = 1;
                $editStatus = 1;
                $qaStatusss = 1;
                $qaStatus[] = 1;
            } else {
                $checkVal = 0;
                $editStatus = 0;
                $qaStatusss = 0;
                $qaStatus[] = 0;
            }
            
            $qaStatusData[$key] = [ $value['cad_requirement_id'], $value['qa_sta_upd'], $value['qa_re_sta_upd'], $checkVal, $value['po_enq_ref_id'],  $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['cad_requirement'], $value['qa_req_sent_dt'], $value['qa_schd_date'], $value['qa_status'], $value['qa_sta_upd_dt'], $editStatus, $qaStatusss, 0 ];
        }

        // *** get garment size *** //
        $sizeChart  = $this->getSizeChart($id);
        $sizeMaster = $this->getSizeMasterDropdown($sizeChart);

        // ******* GET THE COLUMN ENDS ********* //
        $output['data'] = $result;
        $output['requestData'] = $referenceData;
        $output['qaStatusData'] = $qaStatusData;
        $output['cad_data'] = $data;
        $output['qaStatus'] = $qaStatus;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }
    
    public function UpdateCadQAQueueDataa($id, $req_id, $qa_dept_remarks, $req_data) {
        //print_r($req_data); exit;
        foreach ($req_data as $key => $value) {
            if($value[11] == '4' || $value[11] == 4 || $value[11] == '7' || $value[11] == 7) {
                $updateValue['qa_req_sent_status'] = 0;
            }
            $updateValue['qa_sta_upd_dt'] = $this->mysqldatetime;
            $updateValue['qa_status'] = $value[11];
            $updateValue['qa_dept_remarks'] = $qa_dept_remarks;
            $updateValue['log'] = LOGTIME;
            //print_r($updateValue); exit;
            if($value[14] == 0 || $value[14] == "0") {
                $this->db->where('cad_requirement_id', $value[0]);
                $this->db->update('tbl_cad_requirement', $updateValue);
            }
        }
        
        $result["status"] = "success";
        return $result;
        
    }
    
    public function getBOMDCData($enqId, $reqId)
    {
        $sql = "SELECT * from tbl_request 
                where enquiry_id='$enqId' and request_id='$reqId' ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    public function getMIReceivedData($miId, $dc)
    {
        
        $sql = "SELECT * FROM tbl_mi_details as a
                INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                WHERE c.mi_id=".$miId." AND c.dc_ref_queue_no = $dc AND c.dc_status = 0 ";
        $result = $this->db->query($sql)->result_array();
        return $result;
        
    }

    

}