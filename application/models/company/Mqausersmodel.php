<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mqausersmodel extends CI_Model {

    private $mysqldatetime;
    public function __construct()
    {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo   = fnGetUserLoggedInfo('1');
        $this->companyid     = $ArrUserLoggedInfo['companyid'];
        $this->subscriberid  = $ArrUserLoggedInfo['subscriber_id'];
      
        $this->mysqldatetime = date('d/m/Y h:i A');
        $this->userid        = $ArrUserLoggedInfo['id'];
    }
    
    public function getRequestListt_old() {

        // $sample_sql = "SELECT a.*, e.sample_requirement_id, c.brandname, b.isriorcode, f.contactname as auth_name, e.sam_qa_status, e.qa_req_sent_dt as qa_req_date, e.qa_cutoff_date
        //         FROM tbl_sample_requirement as e
        //         INNER JOIN tbl_request_sample g on e.sample_requirement_id=g.sample_id
        //         INNER JOIN tbl_request a on g.request_id=a.request_id
        //         INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
        //         INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
        //         INNER JOIN ".KN_USERS." as f on a.auth_by=f.id
        //         WHERE a.flag=1 AND a.type = 2 and e.qa_req_status = 1 and e.sam_qa_status = 0 and a.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY e.log DESC";
	    // $sample_data = $this->db->query($sample_sql)->result_array();
         $sample_sql = "SELECT h.qa_req_id,a.*, c.brandname, b.isriorcode, f.contactname as auth_name, k.contactname as merchant_name,i.contactname as sample_name, e.sample_requirement_id, e.qa_req_sent_dt, e.qa_cutoff_date, e.sam_qa_status,  e.log as recent_update FROM sample_qa_request as h 
                INNER JOIN tbl_sample_requirement e on h.qa_req_id=e.qa_req_id
                INNER JOIN tbl_request_sample g on e.sample_requirement_id=g.sample_id
                INNER JOIN tbl_request a on g.request_id=a.request_id
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as f on a.auth_by=f.id
                INNER JOIN ".KN_USERS." as k on a.req_by=k.id
                INNER JOIN ".KN_USERS." as i on a.cad_by=i.id
                WHERE a.flag=1 AND a.type = 2 and e.qa_req_status = 1 AND  (e.qa_approval=0 or e.qa_approval=2) AND a.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY h.qa_req_id  ORDER BY e.log DESC";
	    $sample_data = $this->db->query($sample_sql)->result_array();


        // $cad_sql = "SELECT a.*, c.brandname, b.isriorcode, f.contactname as auth_name, g.cad_requirement_id, g.qa_approval as cad_qa_status
        //         FROM tbl_request as a 
        //         INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
        //         INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
        //         INNER JOIN ".KN_USERS." as f on a.auth_by=f.id 
        //         INNER JOIN tbl_cad_requirement as g on a.request_id=g.request_id 
        //         WHERE a.flag=1 AND a.type=1 AND a.mgmt_approval=1 AND a.deprt_approval=1 AND a.dept_qa_request=1 AND g.qa_approval=0 and g.qa_req_sent_status = 1";
	    // $cad_data = $this->db->query($cad_sql)->result_array();
        $cad_sql = "SELECT a.qa_req_id,b.*, d.brandname, c.isriorcode, f.contactname as auth_name, h.contactname as merchant_name,i.contactname as cad_name, g.cad_requirement_id, g.qa_approval as cad_qa_status, g.log as recent_update FROM qa_request as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN kn_order_enquiry as c on b.enquiry_id=c.id 
                INNER JOIN ".KN_MASTER_BRANDS." as d on c.brandId=d.id 
                INNER JOIN ".KN_USERS." as f on b.auth_by=f.id 
                INNER JOIN ".KN_USERS." as h on b.req_by=h.id
                INNER JOIN ".KN_USERS." as i on b.cad_by=i.id
                INNER JOIN tbl_cad_requirement as g on a.qa_req_id=g.qa_req_id 
                WHERE b.flag=1 AND b.type=1 AND b.mgmt_approval=1 AND b.deprt_approval=1 AND b.dept_qa_request=1 AND g.qa_req_sent_status = 1 AND (g.qa_approval=0 or g.qa_approval=2) and b.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY a.qa_req_id ORDER BY b.log DESC";
	    $cad_data = $this->db->query($cad_sql)->result_array();
	    

        $sam_mer_sql = "SELECT b.contactname as merchant_name 
                    FROM tbl_sample_requirement as e
                    INNER JOIN tbl_request_sample g on e.sample_requirement_id=g.sample_id
                    INNER JOIN tbl_request a on g.request_id=a.request_id
                    inner join ".KN_USERS." as b on a.req_by=b.id 
                    WHERE a.flag=1 AND a.type = 2 and e.qa_req_status = 1 and e.sam_qa_status = 0";
        $sam_mer_result = $this->db->query($sam_mer_sql)->result_array();

        $sam_mer_sql2 = "SELECT b.contactname as sample_name 
                    FROM tbl_sample_requirement as e
                    INNER JOIN tbl_request_sample g on e.sample_requirement_id=g.sample_id
                    INNER JOIN tbl_request a on g.request_id=a.request_id
                    inner join ".KN_USERS." as b on a.cad_by=b.id
                    WHERE a.flag=1 AND a.type = 2 and e.qa_req_status = 1 and e.sam_qa_status = 0";
        $sam_mer_result2 = $this->db->query($sam_mer_sql2)->result_array();

        $cad_mer_sql = "SELECT b.contactname as merchant_name 
                    FROM tbl_request as a
                    inner join ".KN_USERS." as b on a.req_by=b.id 
                    inner join tbl_cad_requirement as c on a.request_id=c.request_id 
                    WHERE a.flag=1 AND a.type = 1 and a.mgmt_approval=1 AND a.deprt_approval=1 AND a.dept_qa_request=1 AND c.qa_approval=0 and c.qa_req_sent_status = 1";
        $cad_mer_result = $this->db->query($cad_mer_sql)->result_array();

        $cad_mer_sql2 = "SELECT b.contactname as cad_name 
                    FROM tbl_request as a
                    inner join ".KN_USERS." as b on a.cad_by=b.id 
                    inner join tbl_cad_requirement as c on a.request_id=c.request_id 
                    WHERE a.flag=1 AND a.type = 1 and a.mgmt_approval=1 AND a.deprt_approval=1 AND a.dept_qa_request=1 AND c.qa_approval=0 and c.qa_req_sent_status = 1";
        $cad_mer_result2 = $this->db->query($cad_mer_sql2)->result_array();

        foreach ($sample_data as $key => $value) {
            $sample_data[$key]['merchant_name'] = $sam_mer_result[$key]['merchant_name'];
            $sample_data[$key]['sample_name'] = $sam_mer_result2[$key]['sample_name'];
        }

        foreach ($cad_data as $key => $value) {
            $cad_data[$key]['merchant_name'] = $cad_mer_result[$key]['merchant_name'];
            $cad_data[$key]['cad_name'] = $cad_mer_result2[$key]['cad_name'];
        }

        $finResult = array_merge( $cad_data , $sample_data );

	    return $finResult;
    }
    public function getRequestListt() {
 $cad_sql = "SELECT a.qa_req_id,b.*, d.brandname, c.isriorcode, g.flag as flags,f.contactname as auth_name, h.contactname as merchant_name,i.contactname as cad_name, g.cad_requirement_id, g.qa_approval as cad_qa_status, g.log as recent_update FROM qa_request as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN kn_order_enquiry as c on b.enquiry_id=c.id 
                INNER JOIN ".KN_MASTER_BRANDS." as d on c.brandId=d.id 
                INNER JOIN ".KN_USERS." as f on b.auth_by=f.id 
                INNER JOIN ".KN_USERS." as h on b.req_by=h.id
                INNER JOIN ".KN_USERS." as i on b.cad_by=i.id
                INNER JOIN tbl_cad_requirement as g on a.qa_req_id=g.qa_req_id 
                WHERE  b.type=1 AND b.mgmt_approval=1 AND b.deprt_approval=1 AND b.dept_qa_request=1 AND g.qa_req_sent_status = 1 AND (g.qa_approval=0 or g.qa_approval=2) and b.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY a.qa_req_id ORDER BY b.log DESC";
	    $cad_data = $this->db->query($cad_sql)->result_array();
	    
	    foreach($cad_data as $key => $value) {
	        $arr = $s_arr = $log_arr = [];
	        $req_sql = "SELECT c.cad_requirement, c.request_id, c.log as logs, c.qa_approval FROM qa_request as a 
	                INNER JOIN tbl_request as b on a.request_id=b.request_id
	                INNER JOIN tbl_cad_requirement as c on a.qa_req_id=c.qa_req_id 
	               WHERE a.qa_req_id=".$value['qa_req_id']."  AND b.type=1 AND b.mgmt_approval=1 AND b.deprt_approval=1 AND b.dept_qa_request=1 AND c.qa_req_sent_status = 1 AND (c.qa_approval=0 or c.qa_approval=2) ORDER BY b.log DESC";
	        $req_data = $this->db->query($req_sql)->result_array(); 
	    //print_r($req_data); exit;
	        foreach($req_data as $key1 => $value1) {
	                array_push($arr, $value1['cad_requirement']);
	                $status = '';
                    if($value1['qa_approval'] == '0' || $value1['qa_approval'] == '')
                    {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    else if($value1['qa_approval'] == '3') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                    else if($value1['qa_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                    }
                    array_push($s_arr, $status);
                    
                    $date = date('d-m-Y h:i A',strtotime($value1['logs']));
                    array_push($log_arr, $date);
	        }
	        
	        $cad_data[$key]['item'] = implode(' <br /> ', $arr);
	        $cad_data[$key]['cad_status'] = implode(' <br /> ', $s_arr);
            $cad_data[$key]['logs'] = implode(' <br /> ', $log_arr);
	}
    //End cad request 

    //started  sample request
	    

$sample_sql = "SELECT h.qa_req_id,a.*, c.brandname, b.isriorcode, e.flag as flags, f.contactname as auth_name, k.contactname as merchant_name,i.contactname as sample_name, e.sample_requirement_id, e.qa_req_sent_dt, e.qa_cutoff_date, e.sam_qa_status,  e.log as recent_update FROM sample_qa_request as h 
                INNER JOIN tbl_sample_requirement e on h.qa_req_id=e.qa_req_id
                INNER JOIN tbl_request_sample g on e.sample_requirement_id=g.sample_id
                INNER JOIN tbl_request a on g.request_id=a.request_id
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as f on a.auth_by=f.id
                INNER JOIN ".KN_USERS." as k on a.req_by=k.id
                INNER JOIN ".KN_USERS." as i on a.cad_by=i.id
                WHERE  a.type = 2 and e.qa_req_status = 1 AND  (e.qa_approval=0 or e.qa_approval=2) AND a.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY h.qa_req_id  ORDER BY e.log DESC";
	    $sample_data = $this->db->query($sample_sql)->result_array();

        
        
        foreach($sample_data as $key => $value) {
            $arr = $s_arr = $log_arr = [];
            $req_sql = "SELECT b.sample_requirement, c.request_id, b.log as logs, b.qa_approval FROM sample_qa_request as a 
                    INNER JOIN tbl_sample_requirement b on a.qa_req_id=b.qa_req_id
                    INNER JOIN tbl_request_sample c on b.sample_requirement_id=c.sample_id
                    INNER JOIN tbl_request d on c.request_id=d.request_id
                    WHERE a.qa_req_id=".$value['qa_req_id']." and d.type = 2 and b.qa_req_status = 1 and (b.qa_approval=0 or b.qa_approval=2) ORDER BY b.log DESC";
            $req_data = $this->db->query($req_sql)->result_array(); 
        //print_r($req_data); exit;
            foreach($req_data as $key1 => $value1) {
                    array_push($arr, $value1['sample_requirement']);
                    $status = '';
                    if($value1['qa_approval'] == '0' || $value1['qa_approval'] == '')
                    {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    else if($value1['qa_approval'] == '3') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                    else if($value1['qa_approval'] == '2') {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING-RR</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                    }
                    array_push($s_arr, $status);
                    
                    $date = date('d-m-Y h:i A',strtotime($value1['logs']));
                    array_push($log_arr, $date);
            }
            
            $sample_data[$key]['item'] = implode(' <br /> ', $arr);
            $sample_data[$key]['sam_status'] = implode(' <br /> ', $s_arr);
            $sample_data[$key]['logs'] = implode(' <br /> ', $log_arr);
    }

	     //$sample_data;



	     //$cad_data;

        $finResult = array_merge( $cad_data, $sample_data );\

       //print_r($finResult);

         usort($finResult, function ($a, $b) {
        return strtotime($b['logs']) - strtotime($a['logs']);
    });

	    return $finResult;
    }

    public function getProductionRequestListt() {
        return [];
    }
    
    public function getSampleRequestListt() {

        $sample_sql = "SELECT h.qa_req_id,a.*, c.brandname, b.isriorcode, e.flag as flags,f.contactname as auth_name, k.contactname as merchant_name,i.contactname as sample_name, e.sample_requirement_id, e.qa_req_sent_dt, e.qa_cutoff_date, e.sam_qa_status,  e.log as recent_update FROM sample_qa_request as h 
                INNER JOIN tbl_sample_requirement e on h.qa_req_id=e.qa_req_id
                INNER JOIN tbl_request_sample g on e.sample_requirement_id=g.sample_id
                INNER JOIN tbl_request a on g.request_id=a.request_id
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as f on a.auth_by=f.id
                INNER JOIN ".KN_USERS." as k on a.req_by=k.id
                INNER JOIN ".KN_USERS." as i on a.cad_by=i.id
                WHERE  a.type = 2 and e.qa_req_status = 1 AND  (e.qa_approval=0 or e.qa_approval=2) AND a.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY h.qa_req_id  ORDER BY e.log DESC";
	    $sample_data = $this->db->query($sample_sql)->result_array();

        
        
        foreach($sample_data as $key => $value) {
            $arr = $s_arr = $log_arr = [];
            $req_sql = "SELECT b.sample_requirement,b.sample_requirement_id, b.flag as flags,c.request_id, b.log as logs, b.qa_approval FROM sample_qa_request as a 
                    INNER JOIN tbl_sample_requirement b on a.qa_req_id=b.qa_req_id
                    INNER JOIN tbl_request_sample c on b.sample_requirement_id=c.sample_id
                    INNER JOIN tbl_request d on c.request_id=d.request_id
                    WHERE a.qa_req_id=".$value['qa_req_id']." and d.type = 2 and b.qa_req_status = 1 and (b.qa_approval=0 or b.qa_approval=2) ORDER BY b.log DESC";
            $req_data = $this->db->query($req_sql)->result_array(); 
        //print_r($req_data); exit;
            foreach($req_data as $key1 => $value1) {
                    array_push($arr, $value1['sample_requirement']);
                    $status = '';
                    if($value1['qa_approval'] == '0' || $value1['qa_approval'] == '')
                    {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    else if($value1['qa_approval'] == '3') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                    else if($value1['qa_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                    }
                    array_push($s_arr, $status);
                    
                    $date = date('d-m-Y h:i A',strtotime($value1['logs']));
                    array_push($log_arr, $date);
            }
            
            $sample_data[$key]['item'] = implode(' <br /> ', $arr);
            $sample_data[$key]['sam_status'] = implode(' <br /> ', $s_arr);
            $sample_data[$key]['logs'] = implode(' <br /> ', $log_arr);
    }

	    return $sample_data;
    }
    
    public function getCADRequestListt() {

        $cad_sql = "SELECT a.qa_req_id,b.*, d.brandname, c.isriorcode, g.flag as flags,g.cad_requirement_id, f.contactname as auth_name, h.contactname as merchant_name,i.contactname as cad_name, g.cad_requirement_id, g.qa_approval as cad_qa_status, g.log as recent_update FROM qa_request as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN kn_order_enquiry as c on b.enquiry_id=c.id 
                INNER JOIN ".KN_MASTER_BRANDS." as d on c.brandId=d.id 
                INNER JOIN ".KN_USERS." as f on b.auth_by=f.id 
                INNER JOIN ".KN_USERS." as h on b.req_by=h.id
                INNER JOIN ".KN_USERS." as i on b.cad_by=i.id
                INNER JOIN tbl_cad_requirement as g on a.qa_req_id=g.qa_req_id 
                WHERE   b.type=1 AND b.mgmt_approval=1 AND b.deprt_approval=1 AND b.dept_qa_request=1 AND g.qa_req_sent_status = 1 AND (g.qa_approval=0 or g.qa_approval=2) and b.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY a.qa_req_id ORDER BY g.log DESC";
	    $cad_data = $this->db->query($cad_sql)->result_array();
	    
	    foreach($cad_data as $key => $value) {
	        $arr = $s_arr = $log_arr = [];
	        $req_sql = "SELECT c.cad_requirement, c.request_id, c.log as logs, c.qa_approval FROM qa_request as a 
	                INNER JOIN tbl_request as b on a.request_id=b.request_id
	                INNER JOIN tbl_cad_requirement as c on a.qa_req_id=c.qa_req_id 
	               WHERE a.qa_req_id=".$value['qa_req_id']."  AND b.type=1 AND b.mgmt_approval=1 AND b.deprt_approval=1 AND b.dept_qa_request=1 AND c.qa_req_sent_status = 1 AND (c.qa_approval=0 or c.qa_approval=2) ORDER BY b.log DESC";
	        $req_data = $this->db->query($req_sql)->result_array(); 
	    //print_r($req_data); exit;
	        foreach($req_data as $key1 => $value1) {
	                array_push($arr, $value1['cad_requirement']);
	                $status = '';
                    if($value1['qa_approval'] == '0' || $value1['qa_approval'] == '')
                    {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    else if($value1['qa_approval'] == '3') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                    else if($value1['qa_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                    }
                    array_push($s_arr, $status);
                    
                    $date = date('d-m-Y h:i A',strtotime($value1['logs']));
                    array_push($log_arr, $date);
	        }
	        
	        $cad_data[$key]['item'] = implode(' <br /> ', $arr);
	        $cad_data[$key]['cad_status'] = implode(' <br /> ', $s_arr);
            $cad_data[$key]['logs'] = implode(' <br /> ', $log_arr);
	}
	    
// usort($cad_data, function ($a, $b) {
//     return $b['logs'] <=> $a['logs'];
// });

	    return $cad_data;
    }

    public function getQueueListt() {
        
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name FROM tbl_request as a 
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as d on a.auth_by=d.id
                WHERE a.type=2 AND a.mgmt_approval=1 AND a.deprt_approval=1 AND a.qa_approval=0 and a.req_by = ".$this->db->escape($this->userid)."ORDER BY a.log DESC";
	    $result = $this->db->query($sql)->result_array();

        $mer_sql = "SELECT b.contactname as merchant_name
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE a.type = 2 and a.mgmt_approval=1 and a.deprt_approval=1 and a.qa_approval=0 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        $sam_sql = "SELECT *, a.log as recent_update,b.que_assign_date as que_assign_date,a.job_sta_upd_dt as job_sta_upd,b.log as recent_update2
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample as e on a.sample_requirement_id=e.sample_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE b.type = 2 and b.mgmt_approval=1 AND b.deprt_approval=1 AND b.qa_approval=0 ";
        $sam_data = $this->db->query($sam_sql)->result_array();

       
         $jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT', 'COMPLETED', 'Q.A. RR SENT', 'ALT. PEND.','ALT. IN PROG.','JOB IN PROG.' ,'GAR. ISSUED','REWORK' ];


        foreach ($result as $key => $res) {
            $arr = $s_arr = $s_log=[];
            
            foreach ($sam_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    //$recent_update3 = strtotime(date($value['recent_update']));
                    $recent_update = strtotime(date($value['recent_update']));
                    $recent_update2 = strtotime(date($value['recent_update2']));
                    if ($recent_update < $recent_update2) {
                       // $result[$key]['recent_update'] = $value['job_sta_upd'];
                        if($value['job_sta_upd']==''){
                             //$recent_up = date('d/m/Y h:i A',strtotime($value['recent_update2']));
                              $recent_up = $value['que_assign_date'];
                        }else{
                            $recent_up = $value['job_sta_upd'];
                        }
                    }
                    else {
                        //$result[$key]['recent_update'] = $value['recent_update'];
                         $recent_up = $value['job_sta_upd'];
                    }
                    array_push($arr, $value['sample_requirement']);
                    $status = '';
                    if($value['job_status'] == 5 || $value['job_status'] == 6 || $value['job_status'] == 7 || $value['job_status'] == 10) {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    else if($value['job_status'] == 4 || $value['job_status'] == 9) {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    array_push($s_arr, $status);
                     //array_push($s_log, date('d/m/Y h:i A',strtotime($value['recent_update'])));
                      array_push($s_log, $recent_up);

                }
            }
        
            $result[$key]['sample_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['sample_status'] = implode(' <br /> ', $s_arr);
             $result[$key]['logs'] = implode(' <br /> ', $s_log);
    
        }

        foreach ($result as $key => $value) {
            $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        }

	    return $result;
    }


    public function searchsampleqaList($data) {
        
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname, e.sample_requirement as auth_name FROM tbl_request as a 
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id
                INNER JOIN tbl_sample_requirement as e on e.enquiry_id=a.enquiry_id
                INNER JOIN tbl_request_sample as f on f.request_id=a.request_id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as d on a.auth_by=d.id
                WHERE a.type=2 AND a.mgmt_approval=1 AND a.deprt_approval=1 AND a.qa_approval=0 and a.req_by = ".$this->db->escape($this->userid)."";
	   
         if (!empty($data['wip_ref_no'])) {
                $wip_ref_no = $this->db->escape_like_str($data['wip_ref_no']);
                $sql .= " AND b.isriorcode LIKE '%" . $wip_ref_no . "%'";
        }

       if (!empty($data['brandId'])) {
               $brandId = (int)$data['brandId'];
               $sql .= " AND b.brandId = " . $brandId;
       }
        if (!empty($data['requirement'])) {
               $requirement = $this->db->escape_like_str($data['requirement']);
                $sql .= " AND e.sample_requirement LIKE '%" . $requirement . "%'";
               //$sql .= " AND a.cad_requirement = " . $requirement;
       }
        if (!empty($data['Queue_no'])) {
                $Queue_no = $this->db->escape_like_str($data['Queue_no']);
               //$sql .= " AND a.ref_queue_no = " . $Queue_no;
                 $sql .= " AND a.ref_queue_no LIKE '%" . $Queue_no . "%'";
       }

       if (!empty($data['RequestFrom']) && !empty($data['RequestTo'])) {
   $startDate = $this->changeReverseDate($data['RequestFrom']) . '- 00:00:00' ;
    $endDate   = $this->changeReverseDate($data['RequestTo']) .'- 00:00:00';
     $sql .= " AND STR_TO_DATE(a.req_date, '%d/%m/%Y %h:%i %p') 
                  BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
   
}
 if (!empty($data['CutoffFrom']) && !empty($data['CutoffTo'])) {
   $startDate = $this->changeReverseDate($data['CutoffFrom']) . '- 00:00:00' ;
    $endDate   = $this->changeReverseDate($data['CutoffTo']) .'- 00:00:00';
     $sql .= " AND STR_TO_DATE(a.cutoff_date, '%d/%m/%Y %h:%i %p') 
                  BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
   
}
        
        $sql .= " ORDER BY a.log DESC";

       //echo $sql;

         $result = $this->db->query($sql)->result_array();


        $mer_sql = "SELECT b.contactname as merchant_name
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE a.type = 2 and a.mgmt_approval=1 and a.deprt_approval=1 and a.qa_approval=0 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        $sam_sql = "SELECT *, a.log as recent_update,b.que_assign_date as que_assign_date,a.job_sta_upd_dt as job_sta_upd,b.log as recent_update2
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample as e on a.sample_requirement_id=e.sample_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE b.type = 2 and b.mgmt_approval=1 AND b.deprt_approval=1 AND b.qa_approval=0 ";
     if (!empty($data['requirement'])) {
               $requirement = $this->db->escape_like_str($data['requirement']);
                $sam_sql .= " AND a.sample_requirement LIKE '%" . $requirement . "%'";
               //$sql .= " AND a.cad_requirement = " . $requirement;
       }
        $sam_data = $this->db->query($sam_sql)->result_array();

       
         $jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT', 'COMPLETED', 'Q.A. RR SENT', 'ALT. PEND.','ALT. IN PROG.','JOB IN PROG.' ,'GAR. ISSUED','REWORK' ];


        foreach ($result as $key => $res) {
            $arr = $s_arr = $s_log=[];
            
            foreach ($sam_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    //$recent_update3 = strtotime(date($value['recent_update']));
                    $recent_update = strtotime(date($value['recent_update']));
                    $recent_update2 = strtotime(date($value['recent_update2']));
                    if ($recent_update < $recent_update2) {
                       // $result[$key]['recent_update'] = $value['job_sta_upd'];
                        if($value['job_sta_upd']==''){
                             //$recent_up = date('d/m/Y h:i A',strtotime($value['recent_update2']));
                              $recent_up = $value['que_assign_date'];
                        }else{
                            $recent_up = $value['job_sta_upd'];
                        }
                    }
                    else {
                        //$result[$key]['recent_update'] = $value['recent_update'];
                         $recent_up = $value['job_sta_upd'];
                    }
                    array_push($arr, $value['sample_requirement']);
                    $status = '';
                    if($value['job_status'] == 5 || $value['job_status'] == 6 || $value['job_status'] == 7 || $value['job_status'] == 10) {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    else if($value['job_status'] == 4 || $value['job_status'] == 9) {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    array_push($s_arr, $status);
                     //array_push($s_log, date('d/m/Y h:i A',strtotime($value['recent_update'])));
                      array_push($s_log, $recent_up);

                }
            }
        
            $result[$key]['sample_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['sample_status'] = implode(' <br /> ', $s_arr);
             $result[$key]['logs'] = implode(' <br /> ', $s_log);
    
        }

        // foreach ($result as $key => $value) {
        //     $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        // }
  //print_r($result);
	    return $result;
    }

     
    

    public function getmanagmenetgetQueueListt() {
        
        $sql = "SELECT *, d.contactname as auth_name FROM tbl_request a
                INNER JOIN kn_order_enquiry as ba on a.enquiry_id=ba.id
                INNER JOIN ".KN_MASTER_BRANDS." as ca on ba.brandId=ca.id
                LEFT join ".KN_USERS." as d on a.auth_by=d.id
                WHERE a.mgmt_approval = 1 and a.deprt_approval=1 AND a.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY a.log DESC";
	    $result = $this->db->query($sql)->result_array();

        $cad_sql = "SELECT a.cad_requirement, e.que_assign_date as que_assign_date, a.request_id, a.job_status ,a.job_status_upd_dt as jobstatus_upd,a.log as recent_update,e.log as recent_update2
                FROM tbl_cad_requirement as a 
                INNER JOIN tbl_request as e on a.request_id=e.request_id
                WHERE  e.type = 1 and e.mgmt_approval=1 and e.deprt_approval=1 ORDER BY a.cad_requirement_id ASC";
        $cad_data = $this->db->query($cad_sql)->result_array();

        $sam_sql = "SELECT * ,a.log as recent_update,e.log as recent_update2
                FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample as e on a.sample_requirement_id=e.sample_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE b.type = 2 and b.mgmt_approval=1 AND b.deprt_approval=1 AND b.qa_approval=0 ORDER BY b.log DESC";
        $sam_data = $this->db->query($sam_sql)->result_array();
        
          $CADjobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT.', 'COMPLETED', 'Q.A. RR SENT', 'RE-WORK PEND.','RE-WORK IN PROG.','WORK IN PROG.' ];
            
          $SAMPLEjobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT', 'COMPLETED', 'Q.A. RR SENT', 'ALT. PEND.','ALT. IN PROG.','JOB IN PROG.' ,'GAR. ISSUED','REWORK' ];

        
         foreach ($result as $key => $res) {
            $arrc = $job_cad = $jArr = [];
            $c_log = [];
            $s_log=[];
            
            foreach ($cad_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arrc, $value['cad_requirement']);

                    if($value['jobstatus_upd'] == ''){
                            
                               //$recent_update = date('d/m/Y h:i A',strtotime($value['recent_update2']));
                                $recent_update = $value['que_assign_date'];
                        }else{
                                 //$cad_data[$key2]['recent_update'] = $value['job_status_upd_dt'];
                                //$recent_update = $value['jobstatus_upd'];
                                $recent_update = $value['jobstatus_upd'];
                                 
                        }
                    if($value['job_status'] == 4)
                    {
                        $jobStatusTag = '<span class="text-light knGreenColor bg-dark"><strong>'.$CADjobStatus[$value['job_status']].'</strong></span>';
                    }
                    else if($value['job_status'] == 5 || $value['job_status'] == 6 || $value['job_status'] == 7)
                    {
                        $jobStatusTag = '<span class="text-light knRedColor bg-dark"><strong>'.$CADjobStatus[$value['job_status']].'</strong></span>';
                    }
                    else
                    {
                        $jobStatusTag = '<span class="text-light knOrangeColor bg-dark"><strong>'.$CADjobStatus[$value['job_status']].'</strong></span>';
                    }
                    //array_push($arr, $value['cad_requirement']);
                     array_push($c_log, $recent_update);
                    array_push($jArr, $jobStatusTag);
                }
            }
            
            $arr = $job_samp = $s_arr = [];
            
            foreach ($sam_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['sample_requirement']);

                    //$sam_data[$key2]['recent_update'] = $value['job_sta_upd_dt'];
                     if($value['job_sta_upd_dt']==''){
                             // $cad_data[$key2]['recent_update'] = $value['recent_update'];
                              //$recent_update_sam = $value['recent_update2'];
                                 $recent_update_sam = date('d/m/Y h:i A',strtotime($value['recent_update2']));
                        }else{
                                 //$cad_data[$key2]['recent_update'] = $value['job_status_upd_dt'];
                                $recent_update_sam = $value['job_sta_upd_dt'];
                                 
                        }

                   
                     $status = '';
                    if($value['job_status'] == 5 || $value['job_status'] == 6 || $value['job_status'] == 7 || $value['job_status'] == 10) {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>'.$SAMPLEjobStatus[$value['job_status']].'</strong></span>';
                    }
                    else if($value['job_status'] == 4 || $value['job_status'] == 9) {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>'.$SAMPLEjobStatus[$value['job_status']].'</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>'.$SAMPLEjobStatus[$value['job_status']].'</strong></span>';
                    }
                    array_push($s_log, $recent_update_sam);
                    array_push($s_arr, $status);
                }
            }
        
            $result[$key]['sample_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['sample_status'] = implode(' <br /> ', $s_arr);
             $result[$key]['sample_job_status_dt'] = implode(' <br /> ',$s_log);

            $result[$key]['cad_requirement'] = implode(' <br /> ', $arrc);
            $result[$key]['job_status'] = implode(' <br /> ', $jArr);
             $result[$key]['cad_job_status_dt'] = implode(' <br /> ', $c_log);
        }

	    return $result;
    }
    public function getQAQueueListt_old() {
       $sample_sql = "SELECT a.*, e.sample_requirement_id, c.brandname, b.isriorcode, f.contactname as auth_name, i.contactname as sample_name, e.qa_status,
                e.qa_req_sent_dt, e.qa_cutoff_date,e.sam_qa_status, e.qa_req_sent_dt as qa_req_date, e.sample_requirement as item,e.ref_queue_no as sam_queue_no, e.log as recent_update, h.qa_req_id as qa_req_ids
                FROM sample_qa_request as h
                INNER JOIN tbl_sample_requirement e on e.qa_req_id=h.qa_req_id
                INNER JOIN tbl_request_sample g on e.sample_requirement_id=g.sample_id
                INNER JOIN tbl_request a on g.request_id=a.request_id
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as f on a.auth_by=f.id
                inner join ".KN_USERS." as i on a.cad_by=i.id
                WHERE a.flag=1 AND a.type = 2 and e.qa_req_status = 1 and e.qa_approval = 1  and a.subscriberid = " . $this->db->escape($this->subscriberid)." group by h.qa_req_id  order by e.log desc";
	    $sample_data = $this->db->query($sample_sql)->result_array();

       
        $sam_mer_sql2 = "SELECT b.contactname as sample_name 
                    FROM tbl_sample_requirement as e
                    INNER JOIN tbl_request_sample g on e.sample_requirement_id=g.sample_id
                    INNER JOIN tbl_request a on g.request_id=a.request_id
                    inner join ".KN_USERS." as b on a.cad_by=b.id
                    WHERE  a.type = 2 and e.qa_req_status = 1 and e.sam_qa_status = 1 and a.subscriberid = " . $this->db->escape($this->subscriberid)."";
        $sam_mer_result2 = $this->db->query($sam_mer_sql2)->result_array();
        
       
           $cad_sql = "SELECT a.qa_req_id,b.*, d.brandname, c.isriorcode, f.contactname as auth_name, h.contactname as cad_name, g.log as recent_update, g.qa_status,g.cad_requirement_id, g.cad_requirement as item,g.qa_approval as cad_qa_status FROM qa_request as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN kn_order_enquiry as c on b.enquiry_id=c.id 
                INNER JOIN ".KN_MASTER_BRANDS." as d on c.brandId=d.id 
                INNER JOIN ".KN_USERS." as f on b.auth_by=f.id 
                INNER JOIN ".KN_USERS." as h on b.cad_by=h.id 
                INNER JOIN tbl_cad_requirement as g on a.qa_req_id=g.qa_req_id 
                WHERE b.flag=1 AND b.type=1 AND g.qa_approval=1  and b.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY a.qa_req_id ORDER BY g.log DESC";
        $cad_data = $this->db->query($cad_sql)->result_array();
        

        $cad_mer_sql2 = "SELECT f.contactname as cad_name 
                    FROM tbl_request as a
                    INNER JOIN ".KN_USERS." as f on a.cad_by=f.id 
                    INNER JOIN tbl_cad_requirement as g on a.request_id=g.request_id 
                    WHERE a.type=1 AND g.qa_approval=1";
        $cad_mer_result2 = $this->db->query($cad_mer_sql2)->result_array();

        // foreach ($sample_data as $key => $value) {
        //     $sample_data[$key]['sample_name'] = $sam_mer_result2[$key]['sample_name'];
        // }

        // foreach ($cad_data as $key => $value) {
        //     $cad_data[$key]['cad_name'] = $cad_mer_result2[$key]['cad_name'];
        // }


        $finResult = array_merge($cad_data, $sample_data);
        

        //print_r($finResult);

	    return $finResult;
    }


     public function getQAQueueListt() {
       $cad_sql = "SELECT a.qa_req_id,b.*, d.brandname, c.isriorcode, g.flag as flags,g.cad_requirement_id,f.contactname as auth_name, h.contactname as cad_name, g.cad_requirement_id, g.qa_approval as cad_qa_status FROM qa_request as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN kn_order_enquiry as c on b.enquiry_id=c.id 
                INNER JOIN ".KN_MASTER_BRANDS." as d on c.brandId=d.id 
                INNER JOIN ".KN_USERS." as f on b.auth_by=f.id 
                INNER JOIN ".KN_USERS." as h on b.cad_by=h.id 
                INNER JOIN tbl_cad_requirement as g on a.qa_req_id=g.qa_req_id 
                WHERE  b.type=1 AND g.qa_approval=1 and b.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY a.qa_req_id ORDER BY g.log DESC";
        $cad_data = $this->db->query($cad_sql)->result_array();
    
        foreach($cad_data as $key => $value) {
            $item = $recent_update = $qa_status = [];
            $req_sql = "SELECT c.cad_requirement, c.qa_status, c.log as recent_update FROM qa_request as a 
	                INNER JOIN tbl_request as b on a.request_id=b.request_id
	                INNER JOIN tbl_cad_requirement as c on a.qa_req_id=c.qa_req_id 
	                WHERE a.qa_req_id=".$value['qa_req_id']."  AND b.type=1 AND b.mgmt_approval=1 AND b.deprt_approval=1 AND b.dept_qa_request=1 AND c.qa_req_sent_status = 1 AND c.qa_approval=1 ORDER BY b.log DESC";
	        $req_data = $this->db->query($req_sql)->result_array();    
	       // print_r($req_data); exit;
	        foreach($req_data as $key1 => $value1) {
	            
	            $QAStatus = ['IN QUEUE', 'SCHEDULED', 'RE-SCHEDULED', 'Q.A.IN PROGRESS', 'DISCREPANCY', 'PASS', 'PASS COND', 'FAIL', '-','IN-QUEUE RR'];
				
                $statusVal = $value1['qa_status'];	
				if($value1['qa_status'] == '')
					$qa_status1 = '<span class="text-light knOrangeColor bg-dark"><strong>'."$QAStatus[0]".'</strong></span>';
				else if($statusVal == 5 || $statusVal == 6 )
					$qa_status1 = '<span class="text-light knGreenColor bg-dark"><strong>'."$QAStatus[$statusVal]".'</strong></span>';
				else if($statusVal == 7 || $statusVal == 4 || $statusVal == 9)
					$qa_status1 = '<span class="text-light knRedColor bg-dark"><strong>'."$QAStatus[$statusVal]".'</strong></span>';
				else
				    $qa_status1 = '<span class="text-light knOrangeColor bg-dark"><strong>'."$QAStatus[$statusVal]".'</strong></span>';
						
	            array_push($item, $value1['cad_requirement']);
	            array_push($qa_status, $qa_status1);
	            array_push($recent_update, date('d-m-Y h:i A',strtotime($value1['recent_update'])));
	        }
	        
	        $cad_data[$key]['item'] = implode(' <br /> ', $item);
	        $cad_data[$key]['qa_status'] = implode(' <br /> ', $qa_status);
	        $cad_data[$key]['recent_update'] = implode(' <br /> ', $recent_update);
	    }

        $sample_sql = "SELECT a.*, e.sample_requirement_id, e.flag as flags ,e.sample_requirement_id, c.brandname, b.isriorcode, f.contactname as auth_name, i.contactname as sample_name, e.qa_status,
                e.qa_req_sent_dt, e.qa_cutoff_date,e.sample_requirement as item ,e.sam_qa_status, e.qa_req_sent_dt as qa_req_date,e.ref_queue_no as sam_queue_no, e.log as recent_update, h.qa_req_id as qa_req_ids
                FROM sample_qa_request as h
                INNER JOIN tbl_sample_requirement e on e.qa_req_id=h.qa_req_id
                INNER JOIN tbl_request_sample g on e.sample_requirement_id=g.sample_id
                INNER JOIN tbl_request a on g.request_id=a.request_id
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as f on a.auth_by=f.id
                inner join ".KN_USERS." as i on a.cad_by=i.id
                WHERE a.flag=1 AND a.type = 2 and e.qa_req_status = 1 and e.qa_approval = 1  and a.subscriberid = " . $this->db->escape($this->subscriberid)." group by h.qa_req_id  order by e.log desc";
	    $sample_data = $this->db->query($sample_sql)->result_array();

       foreach($sample_data as $key => $value) {
            $item = $recent_update = $qa_status = [];
            $req_sql = "SELECT b.sample_requirement, b.qa_status, b.log as recent_update FROM sample_qa_request as a 
                    INNER JOIN tbl_sample_requirement b on a.qa_req_id=b.qa_req_id
                    INNER JOIN tbl_request_sample c on b.sample_requirement_id=c.sample_id
                    INNER JOIN tbl_request d on c.request_id=d.request_id 
                    WHERE a.qa_req_id=".$value['qa_req_ids']." and b.flag=1 AND d.type=2 AND d.mgmt_approval=1 AND d.deprt_approval=1 AND  b.qa_req_status = 1 AND b.qa_approval=1 ";
            $req_data = $this->db->query($req_sql)->result_array();    
            //print_r($req_data); exit;
            foreach($req_data as $key1 => $value1) {
                 $QAStatus = ['IN QUEUE', 'SCHEDULED', 'RE-SCHEDULED', 'Q.A.IN PROGRESS', 'NEED ALTERATION', 'PASS', 'PASS COND', 'FAIL','-','IN-QUEUE RR' ];
				
				$statusVal = $value1['qa_status'];	
				if($value1['qa_status'] == '')
					$qa_status1 = '<span class="text-light knOrangeColor bg-dark"><strong>'."$QAStatus[0]".'</strong></span>';
				else if($statusVal == 5 || $statusVal == 6 )
					$qa_status1 = '<span class="text-light knGreenColor bg-dark"><strong>'."$QAStatus[$statusVal]".'</strong></span>';
				else if($statusVal == 7 || $statusVal == 4  || $statusVal == 9)
					$qa_status1 = '<span class="text-light knRedColor bg-dark"><strong>'."$QAStatus[$statusVal]".'</strong></span>';
				else
				    $qa_status1 = '<span class="text-light knOrangeColor bg-dark"><strong>'."$QAStatus[$statusVal]".'</strong></span>';
               
                array_push($item, $value1['sample_requirement']);
                array_push($qa_status, $qa_status1);
                array_push($recent_update, date('d-m-Y h:i A',strtotime($value1['recent_update'])));
            }
            
           $sample_data[$key]['item'] = implode(' <br /> ', $item);
           $sample_data[$key]['qa_status'] = implode(' <br /> ', $qa_status);
             $sample_data[$key]['recent_update'] = implode(' <br /> ', $recent_update);
            //$sample_data[$key]['recent_update'] = date('d-m-Y h:i A',strtotime($value1['recent_update']));
        }




        $finResult = array_merge($cad_data, $sample_data);

         usort($finResult, function ($a, $b) {
        return strtotime($b['recent_update']) - strtotime($a['recent_update']);
    });

        //print_r($finResult);

	    return $finResult;
    }




    public function getCADQAQueueListt() {
        
       $cad_sql = "SELECT a.qa_req_id,b.*, g.flag as flags,g.cad_requirement_id , d.brandname, c.isriorcode, f.contactname as auth_name, h.contactname as cad_name, g.cad_requirement_id, g.qa_approval as cad_qa_status FROM qa_request as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN kn_order_enquiry as c on b.enquiry_id=c.id 
                INNER JOIN ".KN_MASTER_BRANDS." as d on c.brandId=d.id 
                INNER JOIN ".KN_USERS." as f on b.auth_by=f.id 
                INNER JOIN ".KN_USERS." as h on b.cad_by=h.id 
                INNER JOIN tbl_cad_requirement as g on a.qa_req_id=g.qa_req_id 
                WHERE  b.type=1 AND g.qa_approval=1 and b.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY a.qa_req_id ORDER BY g.log DESC";
        $cad_data = $this->db->query($cad_sql)->result_array();
    
        foreach($cad_data as $key => $value) {
            $item = $recent_update = $qa_status = [];
            $req_sql = "SELECT c.cad_requirement, c.qa_status, c.log as recent_update FROM qa_request as a 
	                INNER JOIN tbl_request as b on a.request_id=b.request_id
	                INNER JOIN tbl_cad_requirement as c on a.qa_req_id=c.qa_req_id 
	                WHERE a.qa_req_id=".$value['qa_req_id']."  AND b.type=1 AND b.mgmt_approval=1 AND b.deprt_approval=1 AND b.dept_qa_request=1 AND c.qa_req_sent_status = 1 AND c.qa_approval=1 ORDER BY b.log DESC";
	        $req_data = $this->db->query($req_sql)->result_array();    
	       // print_r($req_data); exit;
	        foreach($req_data as $key1 => $value1) {
	            
	             $QAStatus = ['IN QUEUE', 'SCHEDULED', 'RE-SCHEDULED', 'Q.A.IN PROGRESS', 'DISCREPANCY', 'PASS', 'PASS COND', 'FAIL', '-','IN-QUEUE RR'];
				
                $statusVal = $value1['qa_status'];	
				if($value1['qa_status'] == '')
					$qa_status1 = '<span class="text-light knOrangeColor bg-dark"><strong>'."$QAStatus[0]".'</strong></span>';
				else if($statusVal == 5 || $statusVal == 6  )
					$qa_status1 = '<span class="text-light knGreenColor bg-dark"><strong>'."$QAStatus[$statusVal]".'</strong></span>';
				else if($statusVal == 7 || $statusVal == 4  || $statusVal == 9 )
					$qa_status1 = '<span class="text-light knRedColor bg-dark"><strong>'."$QAStatus[$statusVal]".'</strong></span>';
				else
				    $qa_status1 = '<span class="text-light knOrangeColor bg-dark"><strong>'."$QAStatus[$statusVal]".'</strong></span>';
						
	            array_push($item, $value1['cad_requirement']);
	            array_push($qa_status, $qa_status1);
	            array_push($recent_update, date('d-m-Y h:i A',strtotime($value1['recent_update'])));
	        }
	        
	        $cad_data[$key]['item'] = implode(' <br /> ', $item);
	        $cad_data[$key]['qa_status'] = implode(' <br /> ', $qa_status);
	        $cad_data[$key]['recent_update'] = implode(' <br /> ', $recent_update);
	    }

        // $cad_mer_sql2 = "SELECT f.contactname as cad_name FROM qa_request as a
        //             INNER JOIN tbl_request as b on a.request_id=b.request_id
        //             INNER JOIN kn_users as f on b.cad_by=f.id 
        //             INNER JOIN tbl_cad_requirement as g on a.qa_req_id=g.qa_req_id 
        //             WHERE b.flag=1 AND b.type=1 AND g.qa_approval=1 GROUP BY a.qa_req_id ORDER BY g.log DESC";
        // $cad_mer_result2 = $this->db->query($cad_mer_sql2)->result_array();

        // foreach ($cad_data as $key => $value) {
        //     $cad_data[$key]['cad_name'] = $cad_mer_result2[$key]['cad_name'];
        // }
        //$array = ["apple", "orange", "banana", "apple", "grape", "banana"];

       //$uniqueArray = array_unique($cad_data);

        return $cad_data;
    }
    
    public function getSampleQAQueueListt() {
        
        $sample_sql = "SELECT a.*, e.sample_requirement_id, c.brandname,e.flag as flags,e.sample_requirement_id, b.isriorcode, f.contactname as auth_name, i.contactname as sample_name, e.qa_status,
                e.qa_req_sent_dt, e.qa_cutoff_date,e.sample_requirement as item ,e.sam_qa_status, e.qa_req_sent_dt as qa_req_date,e.ref_queue_no as sam_queue_no, e.log as recent_update, h.qa_req_id as qa_req_ids
                FROM sample_qa_request as h
                INNER JOIN tbl_sample_requirement e on e.qa_req_id=h.qa_req_id
                INNER JOIN tbl_request_sample g on e.sample_requirement_id=g.sample_id
                INNER JOIN tbl_request a on g.request_id=a.request_id
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as f on a.auth_by=f.id
                inner join ".KN_USERS." as i on a.cad_by=i.id
                WHERE  a.type = 2 and e.qa_req_status = 1 and e.qa_approval = 1  and a.subscriberid = " . $this->db->escape($this->subscriberid)." group by h.qa_req_id  order by e.log desc";
	    $sample_data = $this->db->query($sample_sql)->result_array();

      
        
        foreach($sample_data as $key => $value) {
            $item = $recent_update = $qa_status = [];
            $req_sql = "SELECT b.sample_requirement, b.qa_status, b.log as recent_update FROM sample_qa_request as a 
                    INNER JOIN tbl_sample_requirement b on a.qa_req_id=b.qa_req_id
                    INNER JOIN tbl_request_sample c on b.sample_requirement_id=c.sample_id
                    INNER JOIN tbl_request d on c.request_id=d.request_id 
                    WHERE a.qa_req_id=".$value['qa_req_ids']." AND d.type=2 AND d.mgmt_approval=1 AND d.deprt_approval=1 AND  b.qa_req_status = 1 AND b.qa_approval=1 ";
            $req_data = $this->db->query($req_sql)->result_array();    
            //print_r($req_data); exit;
            foreach($req_data as $key1 => $value1) {
                 $QAStatus = ['IN QUEUE', 'SCHEDULED', 'RE-SCHEDULED', 'Q.A.IN PROGRESS', 'NEED ALTERATION', 'PASS', 'PASS COND', 'FAIL','-','IN-QUEUE RR'];
				
				$statusVal = $value1['qa_status'];	
				if($value1['qa_status'] == '')
					$qa_status1 = '<span class="text-light knOrangeColor bg-dark"><strong>'."$QAStatus[0]".'</strong></span>';
				else if($statusVal == 5 || $statusVal == 6 )
					$qa_status1 = '<span class="text-light knGreenColor bg-dark"><strong>'."$QAStatus[$statusVal]".'</strong></span>';
				else if($statusVal == 7 || $statusVal == 4 || $statusVal == 9 )
					$qa_status1 = '<span class="text-light knRedColor bg-dark"><strong>'."$QAStatus[$statusVal]".'</strong></span>';
				else
				    $qa_status1 = '<span class="text-light knOrangeColor bg-dark"><strong>'."$QAStatus[$statusVal]".'</strong></span>';
               
                array_push($item, $value1['sample_requirement']);
                array_push($qa_status, $qa_status1);
                array_push($recent_update, date('d-m-Y h:i A',strtotime($value1['recent_update'])));
            }
            
           $sample_data[$key]['item'] = implode(' <br /> ', $item);
           $sample_data[$key]['qa_status'] = implode(' <br /> ', $qa_status);
             $sample_data[$key]['recent_update'] = implode(' <br /> ', $recent_update);
            //$sample_data[$key]['recent_update'] = date('d-m-Y h:i A',strtotime($value1['recent_update']));
        }

	    return $sample_data;
    }

    public function getManagementQueueListt() {
        
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name FROM tbl_request as a 
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as d on a.auth_by=d.id
                WHERE  a.type=2 AND a.mgmt_approval=1 AND a.deprt_approval=1 AND a.qa_approval=0 and a.subscriberid = ".$this->db->escape($this->subscriberid)." ORDER BY a.log DESC";
	    $result = $this->db->query($sql)->result_array();

        $mer_sql = "SELECT b.contactname as merchant_name
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE  a.type = 2 and a.mgmt_approval=1 and a.deprt_approval=1 and a.qa_approval=0 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        $sam_sql = "SELECT *, a.log as recent_update, b.que_assign_date as que_assign_date, a.job_sta_upd_dt as job_sta_upd, b.log as recent_update2
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample as e on a.sample_requirement_id=e.sample_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE  b.type = 2 and b.mgmt_approval=1 AND b.deprt_approval=1 AND b.qa_approval=0 ";
        $sam_data = $this->db->query($sam_sql)->result_array();

       $jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT', 'COMPLETED', 'Q.A. RR SENT', 'ALT. PEND.','ALT. IN PROG.','JOB IN PROG.' ,'GAR. ISSUED','REWORK' ];
 

        foreach ($result as $key => $res) {
            $arr = $s_arr = [];
            $s_log=[];
            
            foreach ($sam_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    $recent_update = strtotime(date($value['recent_update']));
                    $recent_update2 = strtotime(date($value['recent_update2']));
                    if ($recent_update < $recent_update2) {
                        //$result[$key]['recent_update'] = $value['job_sta_upd'];
                         if($value['job_sta_upd']==''){
                            // $recent_up = date('d/m/Y h:i A',strtotime($value['recent_update2']));
                              $recent_up = $value['que_assign_date'];
                        }else{
                            $recent_up = $value['job_sta_upd'];
                        }
                    }
                    else {
                       // $result[$key]['recent_update'] = $value['recent_update2'];
                        $recent_up = $value['job_sta_upd'];
                    }
                    array_push($arr, $value['sample_requirement']);
                    $status = '';
                    if($value['job_status'] == 5 || $value['job_status'] == 6 || $value['job_status'] == 7 || $value['job_status'] == 10) {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    else if($value['job_status'] == 4 || $value['job_status'] == 9) {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    array_push($s_arr, $status);
                      //array_push($s_log, date('d/m/Y h:i A',strtotime($value['recent_update'])));
                      array_push($s_log, $recent_up);
                }
            }
        
            $result[$key]['sample_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['sample_status'] = implode(' <br /> ', $s_arr);
             $result[$key]['logs'] = implode(' <br /> ', $s_log);
    
        }

        foreach ($result as $key => $value) {
            $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        }

	    return $result;
    }
    
    public function getSampleQueueListt() {
        
        $sample_sql = "SELECT a.*, f.brandname, e.isriorcode, g.contactname as auth_name
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                INNER JOIN kn_order_enquiry as e on c.enquiry_id=e.id
                INNER JOIN ".KN_MASTER_BRANDS." as f on e.brandId=f.id 
                INNER JOIN ".KN_USERS." as g on c.auth_by=g.id 
                WHERE  a.req_sent_status = 1 and a.qa_req_status=1 and c.subscriberid = ".$this->db->escape($this->subscriberid);
	    $result = $this->db->query($sample_sql)->result_array();
	    return $result;
    }
    
    // public function getBomQueueListt() {
    //     $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.request_for, e.payment_requirement FROM tbl_request as a 
    //             inner join kn_order_enquiry as b on a.enquiry_id=b.id 
    //             inner join kn_master_brands as c on b.brandId=c.id 
    //             inner join kn_users as d on a.auth_by=d.id 
    //             inner join tbl_request_status as e on a.request_id=e.request_id
    //             where a.type=3 and a.deprt_approval=1 and a.flag=1 and e.type_of_mode ='M' GROUP BY a.request_id ORDER BY a.log DESC";
    //     $data = $this->db->query($sql)->result_array();

    //     $mer_sql = "SELECT b.contactname as merchant_name 
    //             FROM tbl_request as a
    //             inner join kn_users as b on a.req_by=b.id 
    //             WHERE a.flag=1 AND a.type = 3 and a.deprt_approval=1 ORDER BY a.log DESC";
    //     $mer_result = $this->db->query($mer_sql)->result_array();

    //     foreach ($data as $key => $value) {
    //         if($value['deprt_approval'] == 1) {
    //             $status = '<span class="text-light knOrangeColor bg-dark"><strong>IN QUEUE</strong></span>';
    //         } else {
    //             $status = '<span class="text-light knOrangeColor bg-dark"><strong>SUPPLY CLOSED</strong></span>';
    //         }
    //         //$data[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
    //         $data[$key]['bom_status'] = $status;
    //     }

    //     return $data;

    // }
    
    public function getBomQueueListt() {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.request_for, e.payment_requirement FROM 
                tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                inner join ".KN_USERS." as d on a.auth_by=d.id 
                left join tbl_request_status as e on a.request_id=e.request_id
                where a.type=3 and a.deprt_approval=1 and a.subscriberid = ".$this->db->escape($this->subscriberid)." GROUP BY a.request_id ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();

        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE a.type = 3 and a.deprt_approval=1 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($data as $key => $value) {
            if($value['deprt_approval'] == 1) {
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>IN QUEUE</strong></span>';
            } else {
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>SUPPLY CLOSED</strong></span>';
            }
            //$data[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
            $data[$key]['bom_status'] = $status;
        }

        return $data;

    }


     public function searchbom1qaList($data) {
        $types=$data['type'];
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.request_for, e.payment_requirement FROM 
                tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                inner join ".KN_USERS." as d on a.auth_by=d.id 
                left join tbl_request_status as e on a.request_id=e.request_id
                where a.type='".$types."' and a.deprt_approval=1 and a.subscriberid = ".$this->db->escape($this->subscriberid)."";
      
        if (!empty($data['wip_ref_no'])) {
                $wip_ref_no = $this->db->escape_like_str($data['wip_ref_no']);
                $sql .= " AND b.isriorcode LIKE '%" . $wip_ref_no . "%'";
        }

       if (!empty($data['brandId'])) {
               $brandId = (int)$data['brandId'];
               $sql .= " AND b.brandId = " . $brandId;
       }
        if (!empty($data['requirement'])) {
    $requirement = $this->db->escape_like_str($data['requirement']);
    $bomtype = "Bom(A1)";

   if (stripos($bomtype, $requirement) !== false) {
    $sql .= " AND a.type = '" . $types . "' ";
} else {
    $sql .= " AND b.brandId = 'BOM' ";
}

    // $sql .= " AND a.cad_requirement = '" . $requirement . "'";
}
        if (!empty($data['Queue_no'])) {
                $Queue_no = $this->db->escape_like_str($data['Queue_no']);
               //$sql .= " AND a.ref_queue_no = " . $Queue_no;
                 $sql .= " AND a.ref_queue_no LIKE '%" . $Queue_no . "%'";
       }

       if (!empty($data['RequestFrom']) && !empty($data['RequestTo'])) {
   $startDate = $this->changeReverseDate($data['RequestFrom']) . '- 00:00:00' ;
    $endDate   = $this->changeReverseDate($data['RequestTo']) .'- 00:00:00';
     $sql .= " AND STR_TO_DATE(a.req_date, '%d/%m/%Y %h:%i %p') 
                  BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
   
}
 if (!empty($data['CutoffFrom']) && !empty($data['CutoffTo'])) {
   $startDate = $this->changeReverseDate($data['CutoffFrom']) . '- 00:00:00' ;
    $endDate   = $this->changeReverseDate($data['CutoffTo']) .'- 00:00:00';
     $sql .= " AND STR_TO_DATE(a.cutoff_date, '%d/%m/%Y %h:%i %p') 
                  BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
   
}
        
        $sql .= "  GROUP BY a.request_id ORDER BY a.log DESC";

        //print_r($sql);

         $data = $this->db->query($sql)->result_array();



        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE a.type='".$types."' and a.deprt_approval=1 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($data as $key => $value) {
            if($value['deprt_approval'] == 1) {
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>IN QUEUE</strong></span>';
            } else {
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>SUPPLY CLOSED</strong></span>';
            }
            //$data[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
            $data[$key]['bom_status'] = $status;
        }

        return $data;

    }


    public function searchbom1qaList_model($data) {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.request_for, e.payment_requirement FROM 
                tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                inner join ".KN_USERS." as d on a.auth_by=d.id 
                left join tbl_request_status as e on a.request_id=e.request_id
                where a.type=3 and a.deprt_approval=1 and a.subscriberid = ".$this->db->escape($this->subscriberid)."";
      
        if (!empty($data['wip_ref_no'])) {
                $wip_ref_no = $this->db->escape_like_str($data['wip_ref_no']);
                $sql .= " AND b.isriorcode LIKE '%" . $wip_ref_no . "%'";
        }

       if (!empty($data['brandId'])) {
               $brandId = (int)$data['brandId'];
               $sql .= " AND b.brandId = " . $brandId;
       }
        if (!empty($data['requirement'])) {
    $requirement = $this->db->escape_like_str($data['requirement']);
    $bomtype = "Bom(A1)";

    if (strpos($bomtype, $requirement) !== false) {
        $sql .= " AND a.type = 3 ";
    } else {
        $sql .= " AND b.brandId = 'BOM' ";
    }

    // $sql .= " AND a.cad_requirement = '" . $requirement . "'";
}
        if (!empty($data['Queue_no'])) {
                $Queue_no = $this->db->escape_like_str($data['Queue_no']);
               //$sql .= " AND a.ref_queue_no = " . $Queue_no;
                 $sql .= " AND a.ref_queue_no LIKE '%" . $Queue_no . "%'";
       }

       if (!empty($data['RequestFrom']) && !empty($data['RequestTo'])) {
   $startDate = $this->changeReverseDate($data['RequestFrom']) . '- 00:00:00' ;
    $endDate   = $this->changeReverseDate($data['RequestTo']) .'- 00:00:00';
     $sql .= " AND STR_TO_DATE(a.req_date, '%d/%m/%Y %h:%i %p') 
                  BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
   
}
 if (!empty($data['CutoffFrom']) && !empty($data['CutoffTo'])) {
   $startDate = $this->changeReverseDate($data['CutoffFrom']) . '- 00:00:00' ;
    $endDate   = $this->changeReverseDate($data['CutoffTo']) .'- 00:00:00';
     $sql .= " AND STR_TO_DATE(a.cutoff_date, '%d/%m/%Y %h:%i %p') 
                  BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
   
}
        
        $sql .= "  GROUP BY a.request_id ORDER BY a.log DESC";

        //print_r($sql);

         $data = $this->db->query($sql)->result_array();



        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE a.type = 3 and a.deprt_approval=1 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($data as $key => $value) {
            if($value['deprt_approval'] == 1) {
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>IN QUEUE</strong></span>';
            } else {
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>SUPPLY CLOSED</strong></span>';
            }
            //$data[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
            $data[$key]['bom_status'] = $status;
        }

        return $data;

    }


    
    
    public function getFabricQueueListt() {
        $sql = "SELECT * FROM tbl_request b
                INNER JOIN kn_order_enquiry as ba on b.enquiry_id=ba.id
                INNER JOIN ".KN_MASTER_BRANDS." as ca on ba.brandId=ca.id 
                WHERE b.mgmt_approval=1 AND b.deprt_approval=1 AND b.qa_approval=0 AND b.type=5";
	    $result = $this->db->query($sql)->result();
	    return $result;
    }
    
    public function getAllQueueListt() {
        $sql = "SELECT *, d.contactname as auth_name FROM tbl_request a
                INNER JOIN kn_order_enquiry as ba on a.enquiry_id=ba.id
                INNER JOIN ".KN_MASTER_BRANDS." as ca on ba.brandId=ca.id
                LEFT join ".KN_USERS." as d on a.auth_by=d.id
                WHERE a.mgmt_approval = 1 and a.deprt_approval=1 AND a.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY a.log DESC";
	    $result = $this->db->query($sql)->result_array();

        $cad_sql = "SELECT a.cad_requirement, e.que_assign_date as que_assign_date,a.request_id, a.job_status ,a.job_status_upd_dt as jobstatus_upd,a.log as recent_update,e.log as recent_update2
                FROM tbl_cad_requirement as a 
                INNER JOIN tbl_request as e on a.request_id=e.request_id
                WHERE  e.type = 1 and e.mgmt_approval=1 and e.deprt_approval=1 ORDER BY a.cad_requirement_id ASC";
        $cad_data = $this->db->query($cad_sql)->result_array();

        $sam_sql = "SELECT * ,a.log as recent_update,b.que_assign_date as que_assign_date, b.log as recent_update2
                FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample as e on a.sample_requirement_id=e.sample_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE b.type = 2 and b.mgmt_approval=1 AND b.deprt_approval=1 AND b.qa_approval=0 ORDER BY b.log DESC";
        $sam_data = $this->db->query($sam_sql)->result_array();
        
          //$jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT', 'COMPLETED', 'Q.A. RR SENT', 'RE-WORK PEND.','RE-WORK IN PROG.','JOB IN PROG.' ,'GAR. ISSUED' ];
        $CADjobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT.', 'COMPLETED', 'Q.A. RR SENT', 'RE-WORK PEND.','RE-WORK IN PROG.','WORK IN PROG.' ];
         $SAMPLEjobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT', 'COMPLETED', 'Q.A. RR SENT', 'ALT. PEND.','ALT. IN PROG.','JOB IN PROG.' ,'GAR. ISSUED','REWORK' ];

        
         foreach ($result as $key => $res) {
            $arrc = $job_cad = $jArr = [];
            $c_log = [];
            $s_log=[];
            
            foreach ($cad_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arrc, $value['cad_requirement']);

                    //   $jobStatusUpdDt = !empty($value['job_status_upd_dt']) ? $value['job_status_upd_dt'] : 'N/A'; // Set 'N/A' if empty
                    //   array_push($job_cad, $jobStatusUpdDt);

                     
                        //$result[$key]['recent_update'] = $value['recent_update2'];
                        if($value['jobstatus_upd'] == ''){
                            
                               //$recent_update = date('d/m/Y h:i A',strtotime($value['recent_update2']));
                               $recent_update = $value['que_assign_date'];
                        }else{
                                 //$cad_data[$key2]['recent_update'] = $value['job_status_upd_dt'];
                                //$recent_update = $value['jobstatus_upd'];
                                $recent_update = $value['jobstatus_upd'];
                                 
                        }

                    if($value['job_status'] == 4)
                    {
                        $jobStatusTag = '<span class="text-light knGreenColor bg-dark"><strong>'.$CADjobStatus[$value['job_status']].'</strong></span>';
                    }
                    else if($value['job_status'] == 5 || $value['job_status'] == 6 || $value['job_status'] == 7)
                    {
                        $jobStatusTag = '<span class="text-light knRedColor bg-dark"><strong>'.$CADjobStatus[$value['job_status']].'</strong></span>';
                    }
                    else
                    {
                        $jobStatusTag = '<span class="text-light knOrangeColor bg-dark"><strong>'.$CADjobStatus[$value['job_status']].'</strong></span>';
                    }
                    //array_push($arr, $value['cad_requirement']);
                     array_push($c_log, $recent_update);

                     //print_r($c_log);
                    array_push($jArr, $jobStatusTag);
                }
            }
            
            $arr = $job_samp = $s_arr = [];
            
            foreach ($sam_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['sample_requirement']);

                    //$sam_data[$key2]['recent_update'] = $value['job_sta_upd_dt'];

                     if($value['job_sta_upd_dt']==''){
                            
                               //$recent_update_sam = date('d/m/Y h:i A',strtotime($value['recent_update2']));
                                 $recent_update_sam = $value['que_assign_date'];
                        }else{
                                 //$cad_data[$key2]['recent_update'] = $value['job_status_upd_dt'];
                                $recent_update_sam = $value['job_sta_upd_dt'];
                                 
                        }

                   
                     $status = '';
                    if($value['job_status'] == 5 || $value['job_status'] == 6 || $value['job_status'] == 7 || $value['job_status'] == 10) {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>'.$SAMPLEjobStatus[$value['job_status']].'</strong></span>';
                    }
                    else if($value['job_status'] == 4 || $value['job_status'] == 9) {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>'.$SAMPLEjobStatus[$value['job_status']].'</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>'.$SAMPLEjobStatus[$value['job_status']].'</strong></span>';
                    }
                    array_push($s_log, $recent_update_sam);
                    array_push($s_arr, $status);
                }
            }
        
            $result[$key]['sample_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['sample_status'] = implode(' <br /> ', $s_arr);
             $result[$key]['sample_job_status_dt'] = implode(' <br /> ',$s_log);

            $result[$key]['cad_requirement'] = implode(' <br /> ', $arrc);
            $result[$key]['job_status'] = implode(' <br /> ', $jArr);
             $result[$key]['cad_job_status_dt'] = implode(' <br /> ', $c_log);
        }

       
	    return $result;
    }

     public function searchAllQueueListt($data) {
        $sql = "SELECT *, d.contactname as auth_name FROM tbl_request a
                INNER JOIN kn_order_enquiry as ba on a.enquiry_id=ba.id
                INNER JOIN tbl_cad_requirement as dd on dd.request_id=a.request_id 
                INNER JOIN tbl_sample_requirement as ee on ee.request_id=a.request_id 
                INNER JOIN ".KN_MASTER_BRANDS." as ca on ba.brandId=ca.id
                LEFT join ".KN_USERS." as d on a.auth_by=d.id
                WHERE a.mgmt_approval = 1 and a.deprt_approval=1 AND a.subscriberid = " . $this->db->escape($this->subscriberid)." ";
	    //$result = $this->db->query($sql)->result_array();
         
        if (!empty($data['wip_ref_no'])) {
                $wip_ref_no = $this->db->escape_like_str($data['wip_ref_no']);
                $sql .= " AND ba.isriorcode LIKE '%" . $wip_ref_no . "%'";
        }

       if (!empty($data['brandId'])) {
               $brandId = (int)$data['brandId'];
               $sql .= " AND ba.brandId = " . $brandId;
       }
        if (!empty($data['requirement'])) {
    $requirement = $this->db->escape_like_str($data['requirement']);
    $bomtype = "Bom(A1)";

   if (stripos($bomtype, $requirement) !== false) {
    $sql .= " AND a.type = '" . $types . "' ";
} else {
    $sql .= " AND b.brandId = 'BOM' ";
}

    // $sql .= " AND a.cad_requirement = '" . $requirement . "'";
}
        if (!empty($data['Queue_no'])) {
                $Queue_no = $this->db->escape_like_str($data['Queue_no']);
               //$sql .= " AND a.ref_queue_no = " . $Queue_no;
                 $sql .= " AND a.ref_queue_no LIKE '%" . $Queue_no . "%'";
       }

       if (!empty($data['RequestFrom']) && !empty($data['RequestTo'])) {
   $startDate = $this->changeReverseDate($data['RequestFrom']) . '- 00:00:00' ;
    $endDate   = $this->changeReverseDate($data['RequestTo']) .'- 00:00:00';
     $sql .= " AND STR_TO_DATE(a.req_date, '%d/%m/%Y %h:%i %p') 
                  BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
   
}
 if (!empty($data['CutoffFrom']) && !empty($data['CutoffTo'])) {
   $startDate = $this->changeReverseDate($data['CutoffFrom']) . '- 00:00:00' ;
    $endDate   = $this->changeReverseDate($data['CutoffTo']) .'- 00:00:00';
     $sql .= " AND STR_TO_DATE(a.cutoff_date, '%d/%m/%Y %h:%i %p') 
                  BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
   
}
        
        $sql .= " ORDER BY a.log DESC";

        echo $sql;
        
        $result = $this->db->query($sql)->result_array();

        $cad_sql = "SELECT a.cad_requirement, e.que_assign_date as que_assign_date,a.request_id, a.job_status ,a.job_status_upd_dt as jobstatus_upd,a.log as recent_update,e.log as recent_update2
                FROM tbl_cad_requirement as a 
                INNER JOIN tbl_request as e on a.request_id=e.request_id
                WHERE  e.type = 1 and e.mgmt_approval=1 and e.deprt_approval=1 ORDER BY a.cad_requirement_id ASC";
        $cad_data = $this->db->query($cad_sql)->result_array();

        $sam_sql = "SELECT * ,a.log as recent_update,b.que_assign_date as que_assign_date, b.log as recent_update2
                FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample as e on a.sample_requirement_id=e.sample_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE b.type = 2 and b.mgmt_approval=1 AND b.deprt_approval=1 AND b.qa_approval=0 ORDER BY b.log DESC";
        $sam_data = $this->db->query($sam_sql)->result_array();
        
          //$jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT', 'COMPLETED', 'Q.A. RR SENT', 'RE-WORK PEND.','RE-WORK IN PROG.','JOB IN PROG.' ,'GAR. ISSUED' ];
        $CADjobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT.', 'COMPLETED', 'Q.A. RR SENT', 'RE-WORK PEND.','RE-WORK IN PROG.','WORK IN PROG.' ];
         $SAMPLEjobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT', 'COMPLETED', 'Q.A. RR SENT', 'ALT. PEND.','ALT. IN PROG.','JOB IN PROG.' ,'GAR. ISSUED','REWORK' ];

        
         foreach ($result as $key => $res) {
            $arrc = $job_cad = $jArr = [];
            $c_log = [];
            $s_log=[];
            
            foreach ($cad_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arrc, $value['cad_requirement']);

                    //   $jobStatusUpdDt = !empty($value['job_status_upd_dt']) ? $value['job_status_upd_dt'] : 'N/A'; // Set 'N/A' if empty
                    //   array_push($job_cad, $jobStatusUpdDt);

                     
                        //$result[$key]['recent_update'] = $value['recent_update2'];
                        if($value['jobstatus_upd'] == ''){
                            
                               //$recent_update = date('d/m/Y h:i A',strtotime($value['recent_update2']));
                               $recent_update = $value['que_assign_date'];
                        }else{
                                 //$cad_data[$key2]['recent_update'] = $value['job_status_upd_dt'];
                                //$recent_update = $value['jobstatus_upd'];
                                $recent_update = $value['jobstatus_upd'];
                                 
                        }

                    if($value['job_status'] == 4)
                    {
                        $jobStatusTag = '<span class="text-light knGreenColor bg-dark"><strong>'.$CADjobStatus[$value['job_status']].'</strong></span>';
                    }
                    else if($value['job_status'] == 5 || $value['job_status'] == 6 || $value['job_status'] == 7)
                    {
                        $jobStatusTag = '<span class="text-light knRedColor bg-dark"><strong>'.$CADjobStatus[$value['job_status']].'</strong></span>';
                    }
                    else
                    {
                        $jobStatusTag = '<span class="text-light knOrangeColor bg-dark"><strong>'.$CADjobStatus[$value['job_status']].'</strong></span>';
                    }
                    //array_push($arr, $value['cad_requirement']);
                     array_push($c_log, $recent_update);

                     //print_r($c_log);
                    array_push($jArr, $jobStatusTag);
                }
            }
            
            $arr = $job_samp = $s_arr = [];
            
            foreach ($sam_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['sample_requirement']);

                    //$sam_data[$key2]['recent_update'] = $value['job_sta_upd_dt'];

                     if($value['job_sta_upd_dt']==''){
                            
                               //$recent_update_sam = date('d/m/Y h:i A',strtotime($value['recent_update2']));
                                 $recent_update_sam = $value['que_assign_date'];
                        }else{
                                 //$cad_data[$key2]['recent_update'] = $value['job_status_upd_dt'];
                                $recent_update_sam = $value['job_sta_upd_dt'];
                                 
                        }

                   
                     $status = '';
                    if($value['job_status'] == 5 || $value['job_status'] == 6 || $value['job_status'] == 7 || $value['job_status'] == 10) {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>'.$SAMPLEjobStatus[$value['job_status']].'</strong></span>';
                    }
                    else if($value['job_status'] == 4 || $value['job_status'] == 9) {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>'.$SAMPLEjobStatus[$value['job_status']].'</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>'.$SAMPLEjobStatus[$value['job_status']].'</strong></span>';
                    }
                    array_push($s_log, $recent_update_sam);
                    array_push($s_arr, $status);
                }
            }
        
            $result[$key]['sample_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['sample_status'] = implode(' <br /> ', $s_arr);
             $result[$key]['sample_job_status_dt'] = implode(' <br /> ',$s_log);

            $result[$key]['cad_requirement'] = implode(' <br /> ', $arrc);
            $result[$key]['job_status'] = implode(' <br /> ', $jArr);
             $result[$key]['cad_job_status_dt'] = implode(' <br /> ', $c_log);
        }

       
	    return $result;
    }

     public function getBrandListt()
    {
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_MASTER_BRANDS);
        $this->db->where('companyid', $userInfo['companyid']);
	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
    }
    
    public function getCadQueueListt() {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, f.contactname as auth_name
                FROM tbl_request as a 
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                INNER JOIN ".KN_USERS." as f on a.auth_by=f.id 
                WHERE a.flag IN(1,2) AND a.type = 1 and a.mgmt_approval=1 and a.deprt_approval=1 and a.subscriberid = ".$this->db->escape($this->subscriberid)." ORDER BY a.log DESC";
	            
                $result = $this->db->query($sql)->result_array();

        $cad_sql = "SELECT a.cad_requirement, a.request_id, a.job_status, e.que_assign_date as que_assign_date, a.log as recent_update, a.job_status_upd_dt as job_sta_upd,e.log as recent_update2
                FROM tbl_cad_requirement as a 
                INNER JOIN tbl_request as e on a.request_id=e.request_id
                WHERE a.flag IN(1,2)  AND e.type = 1 and e.mgmt_approval=1 and e.deprt_approval=1 ORDER BY a.cad_requirement_id ASC";
        $cad_data = $this->db->query($cad_sql)->result_array();

            //$jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT', 'COMPLETED', 'Q.A. RR SENT', 'RE-WORK PEND.','RE-WORK IN PROG.','JOB IN PROG.' ];
           $jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT.', 'COMPLETED', 'Q.A. RR SENT', 'RE-WORK PEND.','RE-WORK IN PROG.','WORK IN PROG.' ];

        foreach ($result as $key => $res) {
            $arr = $jArr = $recent_up1 = [] ;
            $s_log=[];
            
            foreach ($cad_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    $recent_update = strtotime(date($value['recent_update']));
                    $recent_update2 = strtotime(date($value['recent_update2']));
                   
                    if ($recent_update < $recent_update2) {
                        if($value['job_sta_upd']==''){
                             //$recent_up = date('d/m/Y h:i A',strtotime($value['recent_update2']));
                              $recent_up = $value['que_assign_date'];
                        }else{
                              $recent_up = $value['job_sta_upd'];
                        }
                       
                        //$recent_up = date('d/m/Y h:i A',strtotime($value['job_sta_upd']));
                    }
                    else {
                        
                        $recent_up = $value['job_sta_upd'];
                    }
                    if($value['job_status'] == 4)
                    {
                        $jobStatusTag = '<span class="text-light knGreenColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    else if($value['job_status'] == 5 || $value['job_status'] == 6 || $value['job_status'] == 7)
                    {
                        $jobStatusTag = '<span class="text-light knRedColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    else
                    {
                        $jobStatusTag = '<span class="text-light knOrangeColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    array_push($arr, $value['cad_requirement']);
                    array_push($jArr, $jobStatusTag);
                    array_push($recent_up1, $recent_up);
                     array_push($s_log, $recent_up);
                }
            }
         
            // $dis_arr = array_unique($arr);
            // $j_dis_arr = array_unique($jArr);
            $result[$key]['cad_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['job_status'] = implode(' <br /> ', $jArr);
            $result[$key]['recent_update'] = implode(' <br /> ', $recent_up1);
             $result[$key]['logs'] = implode(' <br /> ', $s_log);
        }

        $mer_sql = "SELECT b.contactname as merchant_name 
                    FROM tbl_request as a
                    inner join ".KN_USERS." as b on a.req_by=b.id 
                    WHERE a.type = 1 and a.mgmt_approval=1 and a.deprt_approval=1";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($result as $key => $value) {
            $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        }

	    return $result;
    }

     public function searchcadqaList($data) {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, f.contactname as auth_name
                FROM tbl_request as a 
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN tbl_cad_requirement as d on d.request_id=a.request_id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                INNER JOIN ".KN_USERS." as f on a.auth_by=f.id 
                WHERE a.flag IN(1,2) AND a.type = 1 and a.mgmt_approval=1 and a.deprt_approval=1 and a.subscriberid = ".$this->db->escape($this->subscriberid)."";
	            
        if (!empty($data['wip_ref_no'])) {
                $wip_ref_no = $this->db->escape_like_str($data['wip_ref_no']);
                $sql .= " AND b.isriorcode LIKE '%" . $wip_ref_no . "%'";
        }

       if (!empty($data['brandId'])) {
               $brandId = (int)$data['brandId'];
               $sql .= " AND b.brandId = " . $brandId;
       }
        if (!empty($data['requirement'])) {
               $requirement = $this->db->escape_like_str($data['requirement']);
                $sql .= " AND d.cad_requirement LIKE '%" . $requirement . "%'";
               //$sql .= " AND a.cad_requirement = " . $requirement;
       }
        if (!empty($data['Queue_no'])) {
                $Queue_no = $this->db->escape_like_str($data['Queue_no']);
               //$sql .= " AND a.ref_queue_no = " . $Queue_no;
                 $sql .= " AND a.ref_queue_no LIKE '%" . $Queue_no . "%'";
       }

       if (!empty($data['RequestFrom']) && !empty($data['RequestTo'])) {
   $startDate = $this->changeReverseDate($data['RequestFrom']) . '- 00:00:00' ;
    $endDate   = $this->changeReverseDate($data['RequestTo']) .'- 00:00:00';
     $sql .= " AND STR_TO_DATE(a.req_date, '%d/%m/%Y %h:%i %p') 
                  BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
   
}
 if (!empty($data['CutoffFrom']) && !empty($data['CutoffTo'])) {
   $startDate = $this->changeReverseDate($data['CutoffFrom']) . '- 00:00:00' ;
    $endDate   = $this->changeReverseDate($data['CutoffTo']) .'- 00:00:00';
     $sql .= " AND STR_TO_DATE(a.cutoff_date, '%d/%m/%Y %h:%i %p') 
                  BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
   
}
        
        $sql .= " ORDER BY a.log DESC";

        //print_r($sql);

        $result = $this->db->query($sql)->result_array();


       
        $cad_sql = "SELECT a.cad_requirement, a.request_id, a.job_status, e.que_assign_date as que_assign_date, a.log as recent_update, a.job_status_upd_dt as job_sta_upd,e.log as recent_update2
                FROM tbl_cad_requirement as a 
                INNER JOIN tbl_request as e on a.request_id=e.request_id
                WHERE a.flag IN(1,2)  AND e.type = 1 and e.mgmt_approval=1 and e.deprt_approval=1 ORDER BY a.cad_requirement_id ASC";
   
          $cad_data = $this->db->query($cad_sql)->result_array();


 

            //$jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT', 'COMPLETED', 'Q.A. RR SENT', 'RE-WORK PEND.','RE-WORK IN PROG.','JOB IN PROG.' ];
           $jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT.', 'COMPLETED', 'Q.A. RR SENT', 'RE-WORK PEND.','RE-WORK IN PROG.','WORK IN PROG.' ];

        foreach ($result as $key => $res) {
            $arr = $jArr = $recent_up1 = [] ;
            $s_log=[];
            
            foreach ($cad_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    $recent_update = strtotime(date($value['recent_update']));
                    $recent_update2 = strtotime(date($value['recent_update2']));
                   
                    if ($recent_update < $recent_update2) {
                        if($value['job_sta_upd']==''){
                             //$recent_up = date('d/m/Y h:i A',strtotime($value['recent_update2']));
                              $recent_up = $value['que_assign_date'];
                        }else{
                              $recent_up = $value['job_sta_upd'];
                        }
                       
                        //$recent_up = date('d/m/Y h:i A',strtotime($value['job_sta_upd']));
                    }
                    else {
                        
                        $recent_up = $value['job_sta_upd'];
                    }
                    if($value['job_status'] == 4)
                    {
                        $jobStatusTag = '<span class="text-light knGreenColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    else if($value['job_status'] == 5 || $value['job_status'] == 6 || $value['job_status'] == 7)
                    {
                        $jobStatusTag = '<span class="text-light knRedColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    else
                    {
                        $jobStatusTag = '<span class="text-light knOrangeColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    array_push($arr, $value['cad_requirement']);
                    array_push($jArr, $jobStatusTag);
                    array_push($recent_up1, $recent_up);
                     array_push($s_log, $recent_up);
                }
            }
         
            // $dis_arr = array_unique($arr);
            // $j_dis_arr = array_unique($jArr);
            $result[$key]['cad_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['job_status'] = implode(' <br /> ', $jArr);
            $result[$key]['recent_update'] = implode(' <br /> ', $recent_up1);
             $result[$key]['logs'] = implode(' <br /> ', $s_log);
        }

//         $mer_sql = "SELECT b.contactname as merchant_name 
//                     FROM tbl_request as a
//                     inner join ".KN_USERS." as b on a.req_by=b.id 
//                     WHERE a.type = 1 and a.mgmt_approval=1 and a.deprt_approval=1";
//         $mer_result = $this->db->query($mer_sql)->result_array();

//         foreach ($result as $key => $value) {
//             $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
//         }
//    //print_r($result);
	    return $result;
    }
    
    public function getEstablishmentQueueListt() {
	    $result = [];
	    return $result;
    }
    
    public function getProductionQueueListt() {
	    $result = [];
	    return $result;
    }
    
    public function getVesselQueueListt() {
	    $result = [];
	    return $result;
    }
    
    public function getStationeryQueueListt() {
	    $result = [];
	    return $result;
    }
    
    public function getBom2QueueListt() {
        // $sql = "SELECT * FROM tbl_request_bom_2 b
        //         INNER JOIN tbl_request c on b.request_id=c.request_id
        //         INNER JOIN kn_order_enquiry as ba on c.enquiry_id=ba.id
        //         INNER JOIN ".KN_MASTER_BRANDS." as ca on ba.brandId=ca.id 
        //         WHERE b.flag=1 and b.qa_req_status=1 and a.req_by = ".$this->db->escape($this->userid)." ORDER BY b.log DESC";
	    // $result = $this->db->query($sql)->result();
	    // return $result;

         $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.request_for, e.payment_requirement FROM 
                tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                inner join ".KN_USERS." as d on a.auth_by=d.id 
                left join tbl_request_status as e on a.request_id=e.request_id
                where a.type=4 and a.deprt_approval=1 and a.subscriberid = ".$this->db->escape($this->subscriberid)." GROUP BY a.request_id ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();

        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE a.type = 4 and a.deprt_approval=1 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($data as $key => $value) {
            if($value['deprt_approval'] == 1) {
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>IN QUEUE</strong></span>';
            } else {
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>SUPPLY CLOSED</strong></span>';
            }
            //$data[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
            $data[$key]['bom_status'] = $status;
        }

        return $data;
    }
public function changeReverseDate($date)
    {
        $array=explode("-",$date);
        $rev=array_reverse($array);
        $date=implode("-",$rev);
        return $date;
    }
    public function getProductionQAQueueListt() {
        return [];
    }


}