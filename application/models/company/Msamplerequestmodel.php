<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msamplerequestmodel extends CI_Model {

  public function __construct() {
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->subscriberId = $ArrUserLoggedInfo['subscriber_id'];
        $this->mysqldatetime = date('d/m/Y h:i A');
    }

    public function getSamRequestData($VarRequestId, $VarCompanyId) {
        $VarSamSql = "SELECT a.id,requesttype,a.cutoffdatetime,a.merchantid,a.queueno_assigned_date,a.merchantnote,a.status,
a.datecreated,a.dateupdated,a.orderid,mgmtcurrentstatus,deptcurrentstatus,a.mgmtremarks,a.mgmtid,deptremarks,queueno,
jobschedule,approvaltype,request_type_dept,cadqueuecompletestatus,jsondatagrid,requestrefid,attachment_jxl,cadissuedto,
fabissuedto,bomissuedto,cadindentcutoffdatetime,fabindentcutoffdatetime,bomindentcutoffdatetime,cad_mat_ind_ref_no,fab_mat_ind_ref_no,
bom_mat_ind_ref_no FROM " . KN_ALLREQUEST . " AS a INNER JOIN " . KN_SAMPLE_REQUEST . " AS s ON a.id = s.requestrefid WHERE 
a.id = '$VarRequestId' AND a.companyid = '$VarCompanyId' ";
        return $this->db->query($VarSamSql)->result();
    }

    public function getSamRequestIndentsByType($VarRequestId, $VarCompanyId,$VarIndentType) {
        $VarSamSql = "SELECT a.id,requesttype,a.cutoffdatetime,a.merchantid,a.queueno_assigned_date,a.merchantnote,a.datecreated,
a.orderid,mgmtcurrentstatus,deptcurrentstatus,a.mgmtremarks,a.mgmtid,deptremarks,queueno,jobschedule,
approvaltype,request_type_dept,DATE_FORMAT(a.dateupdated,'%d-%m-%Y %H:%i:%s') as dateupdated,cadqueuecompletestatus,jsondatagrid,attachment_jxl,
cadissuedto,fabissuedto,bomissuedto,cadindentcutoffdatetime,fabindentcutoffdatetime,bomindentcutoffdatetime,
s.cad_mat_ind_ref_no,s.fab_mat_ind_ref_no,s.bom_mat_ind_ref_no FROM
 " . KN_ALLREQUEST . " AS a INNER JOIN " . KN_SAMPLE_REQUEST . " AS s ON a.id = s.requestrefid 
 WHERE a.id = '$VarRequestId' AND a.companyid = '$VarCompanyId' AND request_type_dept = '$VarIndentType' ";
        return $this->db->query($VarSamSql)->result();
    }

    function saveAuthoriseSamRequest($ArrData = array(),$VarId='') {
        $this->db->where('id', $VarId);
        $this->db->update(KN_ALLREQUEST, $ArrData);
        return $this->db->affected_rows();
    }

    public function sampleRequestMisc($jsonData,$VarId,$OrderId) {
        $this->db->from(KN_SAMPLE_REQUEST_MISC);
        $this->db->where('requestid',$VarId);
        $this->db->where('orderid',$OrderId);
        $checkExists = $this->db->count_all_results();

        if($checkExists == 1) {
            $this->db->where('requestid',$VarId);
            $this->db->where('orderid',$OrderId);
            $this->db->update(KN_SAMPLE_REQUEST_MISC, array('samRefJxl'=>$jsonData));
        }
        else {
            $this->db->insert(KN_SAMPLE_REQUEST_MISC, array('requestid'=>$VarId,'orderid'=>$OrderId,'samRefJxl'=>$jsonData));
        }
        return true;
    }

    public function prevSampleRefNo($VarOrderId) {
        $sampleReqMisc = $this->commonmodel->fnGetAllTableInfo(KN_SAMPLE_REQUEST_MISC, 'samRefJxl', array('orderid' => $VarOrderId));
        return $sampleReqMisc;
    }

    /********************************Data Tables***************************/

    var $recdListColumn_order = array('','oe.id','brandname','','requesttype','a.datecreated','a.cutoffdatetime','a.approvaltype','mgmt','merchant','',
        'a.dateupdated','a.status');
    var $recdList_column_search = array('oe.isriorcode','brandname','requesttype','a.datecreated','a.cutoffdatetime','a.approvaltype','u.contactname','mu.contactname',
        'a.dateupdated','a.status');

    public function recdlistDatatables() {
        $this->datatables_recdlist_qry();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    public function datatables_recdlist_qry() {
        $this->db->select('a.id as allid,oe.isriorcode,requesttype,approvaltype,DATE_FORMAT(a.cutoffdatetime,"%d-%m-%Y %H:%i:%s") AS formattedCutOffDt,
        DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") AS formattedDateCreated,DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") AS formattedDateUpdated,
        a.merchantid,a.dateupdated,a.status,mgmtcurrentstatus,a.datecreated,a.queueno,deptcurrentstatus,a.mgmtid,a.datecreated,jsondatagrid,brandname,
u.contactname as mgmt,mu.contactname as merchant');
        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.mgmtcurrentstatus','2');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->where('a.request_type_dept','SAMPLE');
        $this->db->where('a.queueno','0');
        $this->db->where('a.mgmtcurrentstatus','2');
        $this->db->join(KN_SAMPLE_REQUEST.' AS s','s.requestrefid = a.id');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.mgmtid = u.id');
        $this->db->join(KN_USERS.' AS mu','a.merchantid = mu.id');
        $i = 0;
        foreach ($this->recdList_column_search as $item) {
            if($_POST['search']['value']) {
                if(validateDate($_POST['search']['value'])) {
                    $_POST['search']['value'] = date('Y-m-d',strtotime($_POST['search']['value']));
                }
                if($i===0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->recdList_column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if(isset($_POST['order'])) {
            $this->db->order_by($this->recdListColumn_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->recdListColumn_order)) {
            $recdListColumn_order = $this->recdListColumn_order;
            $this->db->order_by(key($recdListColumn_order), $recdListColumn_order[key($recdListColumn_order)]);
        }
    }

    public function count_recdListFiltered() {
        $this->datatables_recdlist_qry();
        $query = $this->db->get();
        return $query->num_rows();
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
    public function count_RecdList() {
        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.mgmtcurrentstatus','2');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->where('a.request_type_dept','SAMPLE');
        $this->db->where('a.queueno','0');
        $this->db->join(KN_SAMPLE_REQUEST.' AS s','s.requestrefid = a.id');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.mgmtid = u.id');
        $this->db->join(KN_USERS.' AS mu','a.merchantid = mu.id');
        return $this->db->count_all_results();
    }

    //sample indent list


    var $column_order = array('isriorcode','','queueno','indentrefno','matissuedto','indentcutoff','si.dateupdated');
    var $column_search = array('isriorcode','queueno','indentrefno','matissuedto','indentcutoff','si.dateupdated');
    var $order = array('id' => 'asc');

    public function samIndentListDataTables() {
        $this->dataTablesSamIndentListQry();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function dataTablesSamIndentListQry() {
        $this->db->select('si.id as indentid,s.requestrefid,a.status,a.mgmtcurrentstatus,si.dateupdated,a.approvaltype,a.datecreated,queueno,a.deptcurrentstatus,
a.requestrefno,a.request_type_dept,a.merchantid,a.mgmtid,br.id,brandname,oe.isriorcode,indentrefno,indentcutoff,si.matissuedto,
si.status,u.contactname');

        $this->db->from(KN_SAMPLE_REQUEST.' AS s');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->where('indenttype','2');
        $this->db->join(KN_ALLREQUEST.' AS a','a.id = s.requestrefid');
        $this->db->join(KN_SREQ_INDENTS.' AS si','si.requestid = a.id');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.mgmtid = u.id');

        $i = 0;
        foreach ($this->column_search as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if(isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function count_filtered() {
        $this->dataTablesSamIndentListQry();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all() {
        $this->db->from(KN_SAMPLE_REQUEST.' AS s');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->where('indenttype','2');
        $this->db->join(KN_ALLREQUEST.' AS a','a.id = s.requestrefid');
        $this->db->join(KN_SREQ_INDENTS.' AS si','si.requestid = a.id');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.mgmtid = u.id');

        return $this->db->count_all_results();
    }
    /********************************Data Tables ENDS***************************/

    //SAMPLE request Queue LIST
    public function sampleQueueListDataTables() {
        $this->dataTablesSampleQueueListQry();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function dataTablesSampleQueueListQry() {
        $this->db->select('a.id,DATE_FORMAT(cutoffdatetime,"%d-%m-%Y %H:%i:%s") AS formattedCutOffDt,a.status,a.mgmtcurrentstatus,a.queuecompletestatus,
        DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") AS formattedDateCreated,DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") AS formattedDateUpdated,
        a.approvaltype,a.queueno,a.deptcurrentstatus,a.merchantid,oe.isriorcode,a.mgmtid,jsondatagrid,brandname,
        u.contactname as mgmt,mu.contactname as merchant');

        $queueListColumn_order  = array('','oe.id','brandname','a.queueno','a.datecreated','cutoffdatetime','a.approvaltype','u.contactname','mu.contactname',
            'current_status','a.dateupdated','a.status');
        $queueListColumn_search = array('oe.isriorcode','brandname','a.queueno','a.datecreated','cutoffdatetime','a.approvaltype','u.contactname','mu.contactname',
            'current_status','a.dateupdated','a.status');

        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->where('a.request_type_dept','SAMPLE');
        $this->db->where('a.queueno !=','0');
        $this->db->order_by('a.dateupdated','desc');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.mgmtid = u.id');
        $this->db->join(KN_USERS.' AS mu','a.merchantid = mu.id');
        $i = 0;
        foreach ($queueListColumn_search as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($queueListColumn_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if(isset($_POST['order'])) {
            $this->db->order_by($queueListColumn_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($queueList_order)) {
            $this->db->order_by(key($queueList_order), $order[key($queueList_order)]);
        }
    }

    public function countSampleQueueListFiltered() {
        $this->dataTablesSampleQueueListQry();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function countAllSampleQueueList() {
        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->where('a.request_type_dept','SAMPLE');
        $this->db->where('a.queueno !=','0');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.mgmtid = u.id');
        $this->db->join(KN_USERS.' AS mu','a.merchantid = mu.id');
        return $this->db->count_all_results();
    }

    public function getReqRequestListt() {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name FROM tbl_request as a 
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as d on a.auth_by=d.id
                WHERE a.flag IN(1,2) AND a.type=2 AND a.mgmt_approval=1 AND a.deprt_approval=0 AND a.qa_approval=0 and a.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY a.log DESC";
	    $result = $this->db->query($sql)->result_array();

        $mer_sql = "SELECT b.contactname as merchant_name
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE a.flag IN(1,2) AND a.type = 2 and a.mgmt_approval=1 and a.deprt_approval=0 and a.qa_approval=0 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        $sam_sql = "SELECT *
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample as e on a.sample_requirement_id=e.sample_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE b.flag IN(1,2) AND b.type = 2 and b.mgmt_approval=1 AND b.deprt_approval=0 AND b.qa_approval=0 ORDER BY b.log DESC";
        $sam_data = $this->db->query($sam_sql)->result_array();

        foreach ($result as $key => $res) {
            $arr = $s_arr = [];
            
            foreach ($sam_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['sample_requirement']);
                    $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    array_push($s_arr, $status);
                }
            }
        
            $result[$key]['sample_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['sample_status'] = implode(' <br /> ', $s_arr);
    
        }

        foreach ($result as $key => $value) {
            $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        }

	    return $result;
    }

    public function getQueueListt() {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name 
                FROM tbl_request as a 
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as d on a.auth_by=d.id
                WHERE a.flag IN(1,2) AND a.type=2 AND a.mgmt_approval=1 AND a.deprt_approval=1 AND a.qa_approval=0 and a.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY a.log DESC";
	    $result = $this->db->query($sql)->result_array();

        $mer_sql = "SELECT b.contactname as merchant_name
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE a.flag IN(1,2) AND a.type = 2 and a.mgmt_approval=1 and a.deprt_approval=1 and a.qa_approval=0 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        $sam_sql = "SELECT *, a.log as recent_update,a.job_sta_upd_dt as job_sta_upd, b.que_assign_date as que_assign_date,b.qno_assign_dt as qno_assign_dts,b.log as recent_update2 
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample as e on a.sample_requirement_id=e.sample_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE b.flag IN(1,2) AND b.type = 2 and b.mgmt_approval=1 AND b.deprt_approval=1 AND b.qa_approval=0 ";
        $sam_data = $this->db->query($sam_sql)->result_array();

         $jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT.', 'COMPLETED', 'Q.A. RR SENT', 'ALT. PEND.','ALT. IN PROG.','JOB IN PROG.' ,'GAR. ISSUED','REWORK' ];


        foreach ($result as $key => $res) {
            $arr = $s_arr = $s_log = [];
            $s_arr2 = $s_log = [];
            
            foreach ($sam_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    $recent_update = strtotime(date($value['recent_update']));
                    $recent_update2 = strtotime(date($value['recent_update2']));
                    if ($recent_update < $recent_update2) {
                        //$result[$key]['recent_update'] = $value['job_sta_upd'];
                         //$result[$key]['recent_update'] = $value['job_sta_upd'];
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
                          if($value['job_sta_upd']==''){
                           $recent_up = date('d/m/Y h:i A',strtotime($res['log']));
                             //$recent_up = $res['log'];
                        }
                    }
                    array_push($arr, $value['sample_requirement']);
                    $status = '';
                    if($value['job_status'] == 5 || $value['job_status'] == 6 ||  $value['job_status'] == 7 || $value['job_status'] == 10 ) {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    else if($value['job_status'] == 4  || $value['job_status'] == 9 ) {
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
             // $result[$key]['log2'] = implode(' <br /> ', $s_arr2);
    
        }

        foreach ($result as $key => $value) {
            $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        }

	    return $result;
    }

    //SAMPLE request Queue LIST ENDS

    public function getReqSentListt() {
        $sql = "SELECT a.*, i.qa_req_id as qa_req_ids, c.brandname, b.isriorcode, d.contactname as auth_name, g.contactname as merchant_name, h.contactname as sample_name, e.sam_qa_status, e.sample_requirement, e.flag as flags ,e.sample_requirement_id, e.qa_req_sent_dt,e.qa_req_id, e.qa_cutoff_date, e.log as recent_update
                FROM sample_qa_request as i 
                INNER JOIN tbl_sample_requirement as e on i.qa_req_id=e.qa_req_id
                INNER JOIN tbl_request_sample as f on e.sample_requirement_id=f.sample_id
                INNER JOIN tbl_request as a on a.request_id=f.request_id
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as d on a.auth_by=d.id
                inner join ".KN_USERS." as g on a.req_by=g.id 
                inner join ".KN_USERS." as h on a.cad_by=h.id 
                WHERE a.flag IN(1,2) AND a.type=2 AND e.qa_req_status=1 AND (e.qa_approval = 0 OR e.qa_approval = 2) and a.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY i.qa_req_id ORDER BY e.log DESC";
	    $result = $this->db->query($sql)->result_array();

      
        foreach($result as $key => $value) {
            $arr = $s_arr = $log_arr = [];
            $req_sql = "SELECT b.sample_requirement, c.request_id, b.log as logs, b.qa_approval FROM sample_qa_request as a 
                    INNER JOIN tbl_sample_requirement b on a.qa_req_id=b.qa_req_id
                    INNER JOIN tbl_request_sample c on b.sample_requirement_id=c.sample_id
                    INNER JOIN tbl_request d on c.request_id=d.request_id
                    WHERE a.qa_req_id=".$value['qa_req_ids']." and d.type = 2  and b.qa_req_status=1 and ( b.qa_approval = 0 or b.qa_approval = 2) ORDER BY b.log DESC";
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
            
            $result[$key]['item'] = implode(' <br /> ', $arr);
            $result[$key]['sam_status'] = implode(' <br /> ', $s_arr);
            $result[$key]['logs'] = implode(' <br /> ', $log_arr);
    }

	    return $result;
    }

    public function getGarmentIssuedListt() {
        $sql = "SELECT a.*, e.flag as flags, c.brandname, b.isriorcode, d.contactname as merchant_name, e.sam_qa_status, e.*, 
                group_concat(f.ref_queue_no SEPARATOR' <br /> ') as sam_ref_no, a.ref_queue_no as sam_queue_no,
                a.cutoff_date as r_cutoff_date, e.log as recent_update
                FROM tbl_sample_requirement as e 
                INNER JOIN tbl_request_sample as f on e.sample_requirement_id=f.sample_id
                INNER JOIN tbl_request as a on a.request_id=f.request_id
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as d on a.req_by=d.id
                WHERE a.flag IN(1,2) AND a.type=2 AND e.dc_status=0  GROUP BY e.dc_ref_queue_no ORDER BY e.log DESC";
	    $result = $this->db->query($sql)->result_array();

        // $mer_sql = "SELECT b.contactname as merchant_name
        //         FROM tbl_sample_requirement as e 
        //         INNER JOIN tbl_request_sample as f on e.sample_requirement_id=f.sample_id
        //         INNER JOIN tbl_request as a on a.request_id=f.request_id
        //         inner join kn_users as b on a.req_by=b.id 
        //         WHERE a.flag=1 AND a.type = 2 and e.dc_status=0 GROUP BY e.dc_ref_queue_no ORDER BY a.log DESC";
        // $mer_result = $this->db->query($mer_sql)->result_array();

        // foreach ($result as $key => $value) {
        //     $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        // }

	    return $result;

        //print_r($result);
    }

    public function getMIDCListt() {
        
          $sql = "SELECT a.*,e.*, a.log as logs,a.flag as flags, d.*, h.*,  d.type as utype, f.brandname, e.isriorcode, g.contactname as auth_name, a.item_received_status, b.issue_by,b.dc_ref_queue_no,b.dc_dt FROM tbl_mi_issued_details as a 
                    INNER JOIN tbl_mi_bom_details b ON a.mi_bom_details_id = b.mi_bom_details_id
                    INNER JOIN tbl_mi_bom c ON b.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_mi_details as h on c.request_id=h.request_id
                    INNER JOIN tbl_request d ON h.req_bom_id = d.request_id
                    INNER JOIN kn_order_enquiry as e on d.enquiry_id=e.id 
                    INNER JOIN ".KN_MASTER_BRANDS." as f on e.brandId=f.id 
                    INNER JOIN ".KN_USERS." as g on d.auth_by=g.id
                    WHERE a.dc_status=1 AND a.flag IN(1,2)  GROUP BY a.dc_no ";
        $result = $this->db->query($sql)->result_array();

        //print_r($result);

          $sql = "SELECT a.*,d.*, a.type as types, b.type as utype,  b.*, c.flag as flags ,e.brandname, d.isriorcode, f.contactname as auth_name, c.dc_ref_queue_no, c.dc_dt, c.item_received_status, c.mi_issued_by, c.mat_ind_cad_id,c.log as logs
                FROM tbl_mi_details as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                INNER JOIN kn_order_enquiry as d on a.enquiry_id=d.id 
                INNER JOIN ".KN_MASTER_BRANDS." as e on d.brandId=e.id 
                LEFT JOIN ".KN_USERS." as f on b.auth_by=f.id
                WHERE  a.flag IN(1,2) and b.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY c.dc_ref_queue_no ";
        $result1 = $this->db->query($sql)->result_array();

        //print_r($result1);

          $finResult = array_merge($result, $result1);
          usort($finResult, function ($a, $b) {
    return $b['logs'] <=> $a['logs'];
});

        return $finResult;
    }

    public function getMIBOMdCListt() {
        
         $sql = "SELECT a.*, a.log as logs,a.flag as flags, d.*, h.*, d.type as types, f.brandname, e.isriorcode, g.contactname as auth_name, a.item_received_status, b.mi_bom_details_id,b.issue_by,b.dc_ref_queue_no,b.dc_dt FROM tbl_mi_issued_details as a 
                    INNER JOIN tbl_mi_bom_details b ON a.mi_bom_details_id = b.mi_bom_details_id
                    INNER JOIN tbl_mi_bom c ON b.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_mi_details as h on c.request_id=h.request_id
                    INNER JOIN tbl_request d ON h.req_bom_id = d.request_id
                    INNER JOIN kn_order_enquiry as e on d.enquiry_id=e.id 
                    INNER JOIN ".KN_MASTER_BRANDS." as f on e.brandId=f.id 
                    INNER JOIN ".KN_USERS." as g on d.auth_by=g.id
                    WHERE a.dc_status=1 AND a.flag IN(1,2)  GROUP BY a.dc_no ORDER BY a.log DESC";
        $result = $this->db->query($sql)->result_array();

        //print_r($result);

        $mer_sql = "SELECT c.contactname as merchant_name
            FROM tbl_mi_details as a 
            INNER JOIN tbl_request as b on a.request_id=b.request_id
            INNER JOIN tbl_mi_bom as d on a.request_id=d.request_id
            INNER JOIN tbl_mi_bom_details as g on d.mi_bom_id=g.mi_bom_id
            INNER JOIN ".KN_USERS." as c on b.req_by=c.id
            WHERE a.dc_bom_status=1 AND a.flag=1 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        $mer_sql2 = "SELECT c.contactname as sam_name
            FROM tbl_mi_details as a 
            INNER JOIN tbl_request as b on a.request_id=b.request_id
            INNER JOIN tbl_mi_bom as d on a.request_id=d.request_id
            INNER JOIN tbl_mi_bom_details as g on d.mi_bom_id=g.mi_bom_id
            INNER JOIN ".KN_USERS." as c on b.cad_by=c.id
            WHERE a.dc_bom_status=1 AND a.flag=1 ORDER BY a.log DESC";
        $mer_result2 = $this->db->query($mer_sql2)->result_array();

        $cad_result = [];
          $cad_depts = '';
        foreach ($result as $key => $value) {
            //print_r($value['types']);
            if($value['issue_by'] != '') {
                $cad_sql = "SELECT contactname as cad_name FROM ".KN_USERS." WHERE id=".@$value['issue_by'];
                $cad_result = $this->db->query($cad_sql)->result_array();
            }
             if($value['issued_type'] == 'BOM') {
                 $cad_depts = 'BOM Store';
            } else {
                //$cad_depts = $value['cad_dept'];
            }
          

            $result[$key]['merchant_name'] = @$mer_result[$key]['merchant_name'];
            $result[$key]['sam_name'] = @$mer_result2[$key]['sam_name'];
            $result[$key]['mi_issued_by'] = @$cad_result[0]['cad_name'];
            $result[$key]['bom_depts'] = $cad_depts;
        }


        
        return $result;
    }

    public function getMIDCListt_changed() {
        
       
          $sql_bom= "SELECT a.*, a.log as logs,a.flag as flags, d.*, h.*, d.type as types, f.brandname, e.isriorcode, g.contactname as auth_name, a.item_received_status, b.issue_by,b.dc_ref_queue_no,b.dc_dt FROM tbl_mi_issued_details as a 
                    INNER JOIN tbl_mi_bom_details b ON a.mi_bom_details_id = b.mi_bom_details_id
                    INNER JOIN tbl_mi_bom c ON b.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_mi_details as h on c.request_id=h.request_id
                    INNER JOIN tbl_request d ON h.req_bom_id = d.request_id
                    INNER JOIN kn_order_enquiry as e on d.enquiry_id=e.id 
                    INNER JOIN ".KN_MASTER_BRANDS." as f on e.brandId=f.id 
                    INNER JOIN ".KN_USERS." as g on d.auth_by=g.id
                    WHERE a.dc_status=1 AND a.flag IN(1,2)  GROUP BY a.dc_no ORDER BY a.log DESC";
        $result_bom = $this->db->query($sql_bom)->result_array();


        $mer_sql = "SELECT c.contactname as merchant_name
            FROM tbl_mi_details as a 
            INNER JOIN tbl_request as b on a.request_id=b.request_id
            INNER JOIN tbl_mi_cad_details as d on a.request_id=d.request_id
            LEFT JOIN ".KN_USERS." as c on b.req_by=c.id
            WHERE a.dc_comp_status=1 AND a.flag IN(1,2)  ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        $mer_sql2 = "SELECT c.contactname as sam_name
            FROM tbl_mi_details as a 
            INNER JOIN tbl_request as b on a.request_id=b.request_id
            INNER JOIN tbl_mi_cad_details as d on a.request_id=d.request_id
            LEFT JOIN ".KN_USERS." as c on b.cad_by=c.id
            WHERE a.dc_comp_status=1 AND a.flag IN(1,2)  ORDER BY a.log DESC";
        $mer_result2 = $this->db->query($mer_sql2)->result_array();

        foreach ($result as $key => $value) {
             
            $cad_depts = '';
            
            if($value['types'] == 'EXTERNAL') {
                @$cad_depts = $this->db->where('id',$value['cad_dept'])->get('kn_master_bom_vendor')->row()->vendorname;
            } else {
                $cad_depts = $value['cad_dept'];
            }

            $cad_sql = "SELECT contactname as cad_name FROM ".KN_USERS." WHERE id=".$value['mi_issued_by'];
            $cad_result = $this->db->query($cad_sql)->result_array();

            $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
            $result[$key]['sam_name'] = $mer_result2[$key]['sam_name'];
            $result[$key]['mi_issued_by'] = $cad_result[0]['cad_name'];
            $result[$key]['cad_depts'] = $cad_depts;

             //$finResult = array_merge($result, $result_bom);
        }
        return $result_bom;
    }

     public function getMICAddCListt() {
         
         $sql = "SELECT a.*, a.type as types, b.type as utype, c.flag as flags, b.*, e.brandname, d.isriorcode, f.contactname as auth_name, c.dc_ref_queue_no, c.dc_dt, c.item_received_status, c.mi_issued_by,c.mat_ind_cad_id, c.log as logs
                FROM tbl_mi_details as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                INNER JOIN kn_order_enquiry as d on a.enquiry_id=d.id 
                INNER JOIN ".KN_MASTER_BRANDS." as e on d.brandId=e.id 
                LEFT JOIN ".KN_USERS." as f on b.auth_by=f.id
                WHERE  a.flag IN(1,2) and b.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY c.dc_ref_queue_no ORDER BY c.log DESC";
        $result = $this->db->query($sql)->result_array();

     

        $mer_sql = "SELECT c.contactname as merchant_name
            FROM tbl_mi_details as a 
            INNER JOIN tbl_request as b on a.request_id=b.request_id
            INNER JOIN tbl_mi_cad_details as d on a.request_id=d.request_id
            LEFT JOIN ".KN_USERS." as c on b.req_by=c.id
            WHERE a.dc_comp_status=1 AND a.flag IN(1,2)  ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        $mer_sql2 = "SELECT c.contactname as sam_name
            FROM tbl_mi_details as a 
            INNER JOIN tbl_request as b on a.request_id=b.request_id
            INNER JOIN tbl_mi_cad_details as d on a.request_id=d.request_id
            LEFT JOIN ".KN_USERS." as c on b.cad_by=c.id
            WHERE a.dc_comp_status=1 AND a.flag IN(1,2)  ORDER BY a.log DESC";
        $mer_result2 = $this->db->query($mer_sql2)->result_array();

        foreach ($result as $key => $value) {
               //print_r($value['issued_type']);
             //print_r($value['types']);
            $cad_depts = '';
            
            // if($value['types'] == 'EXTERNAL') {
            //     @$cad_depts = $this->db->where('id',$value['cad_dept'])->get('kn_master_bom_vendor')->row()->vendorname;
            // } else {
            //     $cad_depts = $value['cad_dept'];
            // }

            if($value['issued_type'] == 'CAD') {
                 $cad_depts = 'CAD Dept';
            } else {
                 $cad_depts = 'CAD Dept';
            }

            $cad_sql = "SELECT contactname as cad_name FROM ".KN_USERS." WHERE id=".$value['mi_issued_by'];
            $cad_result = $this->db->query($cad_sql)->result_array();

            $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
            $result[$key]['sam_name'] = $mer_result2[$key]['sam_name'];
            //$result[$key]['mi_issued_by'] = $cad_result[0]['cad_name'];
            $result[$key]['cad_depts'] = $cad_depts;
        }
        return $result;
    }





     
}