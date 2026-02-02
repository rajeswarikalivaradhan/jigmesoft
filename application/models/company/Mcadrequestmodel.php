<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mcadrequestmodel extends CI_Model {

    /*function getOrderEntryDataFromFifthTbl($VarReferenceId = '', $VarCompanyId = '') {
        $VarSql             = "SELECT jsondatagrid FROM " . ORDERENTRY_FIFTHTBL . " WHERE referenceid = '$VarReferenceId' AND companyid = '$VarCompanyId' ";
        $ArrFromFifthTblRes = $this->db->query($VarSql)->row();
        $ArrFromFifthTbl    = json_decode($ArrFromFifthTblRes->jsondatagrid, true);
        if (empty($ArrFromFifthTbl)) {
            $ArrFifthTbl = [];
        } else {
            foreach ($ArrFromFifthTbl as $item) {
                $ArrFifthTbl[] = array($item[0], $item[1], $item[2], $item[4]);
            }
        }
        return $ArrFifthTbl;
    }*/

     public function __construct()
    {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo   = fnGetUserLoggedInfo('1');
        $this->companyid     = $ArrUserLoggedInfo['companyid'];
        $this->subscriberid     = $ArrUserLoggedInfo['subscriber_id'];
        $this->mysqldate     = date('Y-m-d');
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->userid        = $ArrUserLoggedInfo['id'];

       
    }

    function getCadRequirements($VarRequirementId = '') {
        $this->db->select('id,requirement');
        $this->db->where('type', '1');
        if ($VarRequirementId <> '') {
            $this->db->where('id', $VarRequirementId);
        }
        $Res = $this->db->from(KN_CAD_REQUIREMENTPURPOSE);
        return $Res->get()->result_array();
    }
    function getPurpose() {
        $this->db->select('id,purpose');
        return $this->db->get_where(KN_CAD_REQUIREMENTPURPOSE, array('type' => '2'))->result();
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
    
    
    function saveCadRequestInfo($ArrAllRequest, $ArrCadReqData) {
        $VarId = $ArrAllRequest['id'];
        if ($VarId == "" && $VarId == 0) {
            unset($ArrAllRequest['id']);
            $this->db->insert(KN_ALLREQUEST, $ArrAllRequest);
            $VarId                         = $this->db->insert_id();
            $ArrResult['errcode']          = 1;
            $ArrResult['msg']              = '';
            $ArrResult['id']               = $VarId;
            $ArrResult['eid']              = urlencode(base64_encode($VarId));
            $ArrCadReqData['requestrefid'] = $VarId;
            $this->db->insert(KN_CAD_REQUEST, $ArrCadReqData);
        } else {
            if ($this->db->update(KN_ALLREQUEST, $ArrAllRequest, array('id' => $VarId))) {
                $ArrResult['errcode']          = 1;
                $ArrResult['msg']              = '';
                $ArrResult['id']               = $VarId;
                $ArrResult['eid']              = urlencode(base64_encode($VarId));
                $ArrCadReqData['requestrefid'] = $VarId;
                $this->db->update(KN_CAD_REQUEST, $ArrCadReqData, array('requestrefid' => $VarId));
            } else {
                $ArrResult['errcode'] = '-1';
                $ArrResult['msg']     = 'Invalid Data!';
            }
        }
        $ArrResult['dateTime']         = date('d-m-Y H:i:s');
        return $ArrResult;
    }
    function fnCount($VarWip = '', $VarIsrIorType = '', $VarCoffDateFrom = '', $VarCoffDateTo = '', $VarMerchant = '', $VarBrand = '',
                     $VarAfilter = '', $VarCadStatus = '', $VarApprovalType = '', $VarReqType = '', $VarAllReq = '') {
        $userInfo = fnGetUserLoggedInfo(1);
        $ArrWhere = array();
        if ($VarWip <> '') {
            $ArrWhere[] = "oe.isriorcode like '%" . $VarWip . "%'";
        }
        if ($VarIsrIorType <> '') {
            $ArrWhere[] = "oe.reqforisrior = " . $VarIsrIorType;
        }
        if ($VarCoffDateFrom <> '' && $VarCoffDateTo <> '') {
            $ArrWhere[] = "date(a.cutoffdatetime) >= '$VarCoffDateFrom' AND date(a.cutoffdatetime) <= '$VarCoffDateTo' ";
        }
        if ($VarMerchant <> '') {
            $ArrWhere[] = "merchantid = " . $VarMerchant;
        }
        if ($VarBrand <> '') {
            $ArrWhere[] = "oe.brandId LIKE '%" . $VarBrand . "%'";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "oe.isriorcode LIKE '" . $VarAfilter . "%'";
        }
        if ($VarCadStatus <> '') {
            $ArrWhere[] = "a.deptcurrentstatus = " . $VarCadStatus;
        } else {
            $ArrWhere[] = "a.deptcurrentstatus IN (0,1,2,3,4)";
        }
        $ArrWhere[] = "a.mgmtcurrentstatus IN (0,1,2,3,4)";
        /*if($VarMgmtCStatus<>'') {
            $ArrWhere[]             = "c.mgmtcurrentstatus=".$VarMgmtCStatus;
        } else {
            $ArrWhere[]             = "c.mgmtcurrentstatus IN(0,1,2,3,4)";
        }*/
        if ($VarApprovalType) {
            $ArrWhere[] = "a.approvaltype =" . $VarApprovalType;
        }
        if ($VarReqType <> '') {
            $ArrWhere[] = "a.requesttype =" . $VarReqType;
        }
        if ($VarAllReq <> '') {
            $ArrWhere[] = "a.request_type_dept = " . $VarAllReq;
        }
        $ArrWhere[] = "a.companyid = " . $userInfo['companyid'] . " AND a.queueno = '0'";;
        $VarWhere = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        //$VarSqlLab              = "SELECT count(1) as trec  FROM ".KN_CAD_REQUEST." AS c INNER JOIN ".KN_ORDER_ENQUIRY." AS oe ON c.orderid = oe.id WHERE ".$VarWhere;
        $VarSqlCount = "SELECT count(1) as trec  FROM " . KN_ALLREQUEST . " AS a INNER JOIN " . KN_ORDER_ENQUIRY . " AS oe ON a.orderid = oe.id WHERE " . $VarWhere;
        $ObjRows     = $this->db->query($VarSqlCount)->row();
        return $ObjRows->trec;
    }
    function fnList($VarWip = '', $VarIsrIorType = '', $VarCoffDateFrom = '', $VarCoffDateTo = '', $VarMerchant = '', $VarBrand = '',
                    $VarAfilter = '', $VarCadStatus = '', $VarApprovalType = '', $VarReqType = '', $VarAllReq = '', $VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $userInfo     = fnGetUserLoggedInfo(1);
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols  = array('oe.isriorcode', 'brandname', 'a.id', 'a.datecreated', 'a.cutoffdatetime', 'a.merchantid',
            'a.approvaltype', 'a.mgmtid', 'a.dateupdated', 'a.status');
        $VarSortBy    = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'a.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($VarWip <> '') {
            $ArrWhere[] = "oe.isriorcode like '%" . $VarWip . "%'";
        }
        if ($VarIsrIorType <> '') {
            $ArrWhere[] = "oe.reqforisrior = " . $VarIsrIorType;
        }
        if ($VarCoffDateFrom <> '' && $VarCoffDateTo <> '') {
            $ArrWhere[] = "date(a.cutoffdatetime) >= '$VarCoffDateFrom' AND date(a.cutoffdatetime) <= '$VarCoffDateTo' ";
        }
        if ($VarMerchant <> '') {
            $ArrWhere[] = "merchantid = " . $VarMerchant;
        }
        if ($VarBrand <> '') {
            $ArrWhere[] = "oe.brandId LIKE '%" . $VarBrand . "%'";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "oe.isriorcode LIKE '" . $VarAfilter . "%'";
        }
        if ($VarCadStatus <> '') {
            $ArrWhere[] = "a.deptcurrentstatus = " . $VarCadStatus;
        } else {
            $ArrWhere[] = "a.deptcurrentstatus IN (0,1,2,3,4)";
        }
        $ArrWhere[] = "a.mgmtcurrentstatus IN (0,1,2,3,4)";
        if ($VarApprovalType) {
            $ArrWhere[] = "a.approvaltype =" . $VarApprovalType;
        }
        if ($VarReqType <> '') {
            $ArrWhere[] = "a.requesttype =" . $VarReqType;
        }
        if ($VarAllReq <> '') {
            $ArrWhere[] = "a.request_type_dept = " . $VarAllReq;
        }
        $ArrWhere[] = "a.companyid = " . $userInfo['companyid'] . " AND a.queueno = '0'";
        $VarWhere   = '';
        if (count($ArrWhere) >= 1) {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSqlLab
                   = "SELECT a.id,a.requesttype,a.cutoffdatetime,a.merchantid,oe.isriorcode,oe.stylenamerefno,oe.brandId,a.dateupdated,a.status,
a.mgmtcurrentstatus,a.dateupdated,a.approvaltype,a.datecreated,a.queueno,a.deptcurrentstatus,a.request_type_dept,jsondatagrid FROM " . KN_ALLREQUEST . " 
                                      AS a INNER JOIN " . KN_ORDER_ENQUIRY . " AS oe ON a.orderid = oe.id WHERE " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder . " limit " . $VarLimitInfo;
        $ObjResult = $this->db->query($VarSqlLab);
        return $ObjResult;
    }
    function saveAuthoriseCadRequest($ArrData = array()) {
        $this->db->where('id', $ArrData['id']);
        $this->db->update(KN_ALLREQUEST, $ArrData);
        return $this->db->affected_rows();
    }
    function saveCadDeptAuthorise($VarId,$VarCompanyId,$ArrData = array()) {
        $this->db->where('id', $VarId);
        $this->db->update(KN_ALLREQUEST, $ArrData);
        return $this->db->affected_rows();

    }
    function fnInsertMgmtLogData($VarFromTable = '', $VarToTable = '', $VarId = '', $VarApprStatus = '', $VarComments = '') {
        //$VarQry = $this->db->get_where($VarFromTable,array('id'=>$VarId,'companyid'=>$VarCompanyId));
        $VarQry        = $this->db->get_where($VarFromTable, array('id' => $VarId));
        $ArrRes        = $VarQry->result_array();
        $ArrInsertData = $ArrRes[0];
        unset($ArrInsertData['id']);
        $ArrInsertData['cadrequestid']      = $VarId;
        $ArrInsertData['mgmtcurrentstatus'] = $VarApprStatus;
        $ArrInsertData['mgmtremarks']       = $VarComments;
        $ArrInsertData['dateupdated']       = date('Y-m-d H:i:s');
        $this->db->insert($VarToTable, $ArrInsertData);
        return $this->db->insert_id();
    }
    function fnInsertCadDeptLogData($VarFromTable = '', $VarToTable = '', $VarId = '', $VarApprStatus = '', $VarComments = '') {
        //$VarQry = $this->db->get_where($VarFromTable,array('id'=>$VarId,'companyid'=>$VarCompanyId));
        $VarQry        = $this->db->get_where($VarFromTable, array('id' => $VarId));
        $ArrRes        = $VarQry->result_array();
        $ArrInsertData = $ArrRes[0];
        unset($ArrInsertData['id']);
        $ArrInsertData['cadrequestid']         = $VarId;
        $ArrInsertData['caddeptcurrentstatus'] = $VarApprStatus;
        $ArrInsertData['caddeptremarks']       = $VarComments;
        $ArrInsertData['dateupdated']          = date('Y-m-d H:i:s');
        $this->db->insert($VarToTable, $ArrInsertData);
        return $this->db->insert_id();
    }
    function fnActiveInactiveStatus($checkboxids, $optvalue) {
        $Arrids = json_decode($checkboxids, true);
        $this->db->where_in('id', $Arrids);
        if ($this->db->update(KN_CAD_REQUEST, array('status' => $optvalue))) {
            return true;
        }
        return false;
    }
    public function fnCountCadReqLog() {
    }
    public function fnListCadReqLog() {
    }
    public function fnCadIndentCount($VarWip = '', $VarCoffDateFrom = '', $VarCoffDateTo = '', $VarIsrIorType = '', $VarIndFrom = '', $VarQueueNo = '', $VarBB = '',
                                     $VarAfilter = '', $VarCadStatus = '', $VarApprovalType = '') {
        $userInfo = fnGetUserLoggedInfo(1);
        $ArrWhere = array();
        if ($VarWip <> '') {
            $ArrWhere[] = "oe.isriorcode like '%" . $VarWip . "%'";
        }
        if ($VarIsrIorType <> '') {
            $ArrWhere[] = "oe.reqforisrior = " . $VarIsrIorType;
        }
        if ($VarIndFrom <> '') {
            $ArrWhere[] = "a.merchantid = " . $VarIndFrom;
        }
        if ($VarCoffDateFrom <> '' && $VarCoffDateTo <> '') {
            $ArrWhere[] = "date(a.cutoffdatetime) >= '$VarCoffDateFrom' AND date(a.cutoffdatetime) <= '$VarCoffDateTo' ";
        }
        if ($VarQueueNo <> '') {
            $ArrWhere[] = "a.queueno = '" . $VarQueueNo . "'";
        }
        if ($VarBB <> '') {
            $ArrWhere[] = "oe.brandId = " . $VarBB;
        }
        if ($VarCadStatus <> '') {
            $ArrWhere[] = "a.status = " . $VarCadStatus;
        } else {
            $ArrWhere[] = "a.status IN (1,2)";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "br.brandname LIKE '" . $VarAfilter . "%'";
        }
        if ($VarApprovalType) {
            $ArrWhere[] = "a.approvaltype =" . $VarApprovalType;
        }
        $ArrWhere[] = "a.companyid = '" . $userInfo['companyid'] . "' AND a.request_type_dept = 'CAD' ";
        $VarWhere   = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSqlCount = "SELECT count(1) as trec  FROM " . KN_ALLREQUEST . " AS a INNER JOIN " . KN_ORDER_ENQUIRY . " AS oe ON a.orderid = oe.id 
        INNER JOIN " . KN_MASTER_BRANDS . " AS br ON br.id = oe.brandId WHERE " . $VarWhere;
        $ObjRows     = $this->db->query($VarSqlCount)->row();
        return $ObjRows->trec;
    }
    public function fnCadIndentList($VarWip = '', $VarCoffDateFrom = '', $VarCoffDateTo = '', $VarIsrIorType = '', $VarIndFrom = '', $VarQueueNo = '', $VarBB = '',
                                    $VarAfilter = '', $VarCadStatus = '', $VarApprovalType = '', $VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $userInfo     = fnGetUserLoggedInfo(1);
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols  = array('oe.isriorcode', 'brandname', 'c.id', 'a.datecreated', 'c.cutoffdatetime', 'c.merchantid',
            'c.approvaltype', 'c.mgmtid', 'c.dateupdated', 'c.status');
        $VarSortBy    = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'a.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($VarWip <> '') {
            $ArrWhere[] = "oe.isriorcode like '%" . $VarWip . "%'";
        }
        if ($VarIsrIorType <> '') {
            $ArrWhere[] = "oe.reqforisrior = " . $VarIsrIorType;
        }
        if ($VarIndFrom <> '') {
            $ArrWhere[] = "a.merchantid = " . $VarIndFrom;
        }
        if ($VarCoffDateFrom <> '' && $VarCoffDateTo <> '') {
            $ArrWhere[] = "date(a.cutoffdatetime) >= '$VarCoffDateFrom' AND date(a.cutoffdatetime) <= '$VarCoffDateTo' ";
        }
        if ($VarQueueNo <> '') {
            $ArrWhere[] = "a.queueno = '" . $VarQueueNo . "'";
        }
        if ($VarBB <> '') {
            $ArrWhere[] = "oe.brandId = " . $VarBB;
        }
        if ($VarCadStatus <> '') {
            $ArrWhere[] = "a.status = " . $VarCadStatus;
        } else {
            $ArrWhere[] = "a.status IN (1,2)";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "br.brandname LIKE '" . $VarAfilter . "%'";
        }
        if ($VarApprovalType) {
            $ArrWhere[] = "a.approvaltype =" . $VarApprovalType;
        }
        $ArrWhere[] = "a.companyid = '" . $userInfo['companyid'] . "' ";
        $VarWhere   = '';
        if (count($ArrWhere) >= 1) {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSqlList = "SELECT a.id,a.dateupdated,a.status,a.mgmtcurrentstatus,a.dateupdated,a.approvaltype,a.datecreated,a.queueno,a.deptcurrentstatus,
a.requestrefno,a.request_type_dept,a.merchantid,a.mgmtid,brandname,oe.isriorcode,cadindentrefno,cadindentcutoffdt FROM " . KN_SAMPLE_REQUEST . " AS s INNER JOIN ".KN_ALLREQUEST." AS a 
ON a.id = s.requestrefid INNER JOIN ".CADINDENTDETAILS." AS ci ON s.requestrefid = ci.requestid INNER JOIN " . KN_ORDER_ENQUIRY . " AS oe ON a.orderid = oe.id 
INNER JOIN " . KN_MASTER_BRANDS . " AS br ON br.id = oe.brandId WHERE " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder . " limit " . $VarLimitInfo;
        $ObjResult = $this->db->query($VarSqlList);
        return $ObjResult;
    }
    public function getCadRequestData($VarCadRequestId, $VarCompanyId) {
        $VarCadSql = "SELECT a.id,c.requestrefid,a.requesttype,a.request_type_dept,a.cutoffdatetime,a.datecreated,a.dateupdated,a.queueno_assigned_date,
a.approvaltype,a.queueno,a.mgmtid,a.authdatetime,a.queuecompletestatus,
a.requestrefno,a.merchantnote,a.orderid,a.mgmtcurrentstatus,a.deptcurrentstatus,a.mgmtremarks,deptremarks,jobschedule,current_status,a.status,
c.cadqueuecompletestatus,c.jsonAttachmentDetails,jsondatagrid,oe.datecreated as wipdatecreated FROM 
 " . KN_ALLREQUEST . " AS a INNER JOIN " . KN_CAD_REQUEST . " AS c ON a.id = c.requestrefid INNER JOIN " . KN_ORDER_ENQUIRY . " AS oe
 ON a.orderid = oe.id WHERE a.id = '$VarCadRequestId' AND a.companyid = '$VarCompanyId' ";
        return $this->db->query($VarCadSql)->row();
    }

    /*******************************Data Tables START******************************/
    var $recdListColumn_order = array('','oe.isriorcode','brandname','','requesttype','a.datecreated','cutoffdatetime','approvaltype',
        'u.contactname','mu.contactname','current_status','a.dateupdated','a.status');
    var $recdList_column_search = array('oe.isriorcode','brandname','a.datecreated','cutoffdatetime',
        'u.contactname','mu.contactname','current_status','a.dateupdated','a.status','reqT.types','appT.types');

    // public function recdlistDatatables() {
    //     $this->datatables_recdlist_qry();
    //     if($_POST['length'] != -1)
    //         $this->db->limit($_POST['length'], $_POST['start']);
    //     $query = $this->db->get();
    //     return $query->result();
    // }
    // public function datatables_recdlist_qry() {
    //     $this->db->select('a.id as allid,oe.isriorcode,requesttype,a.merchantid,a.status,
    //     mgmtcurrentstatus,a.approvaltype,queueno,deptcurrentstatus,a.request_type_dept,a.mgmtid,DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") as formattedDateCreated,
    //     DATE_FORMAT(cutoffdatetime,"%d-%m-%Y %H:%i:%s") AS formattedCutOffDt,DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") AS formattedDateUpdated,
    //     jsondatagrid,br.id,brandname,u.contactname as mgmt,mu.contactname as merchant,current_status,');
    //     $this->db->from(KN_ALLREQUEST.' AS a');
    //     $this->db->where('a.companyid',$this->companyid);
    //     $this->db->where('a.queueno','0');
    //     $this->db->where('a.request_type_dept','CAD');
    //     $this->db->where('a.mgmtcurrentstatus','2');
    //     $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
    //     $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');      
    //     $this->db->join(KN_USERS.' AS u','a.mgmtid = u.id');
    //     $this->db->join(KN_USERS.' AS mu','a.merchantid = mu.id');
    //     $i = 0;
    //     foreach ($this->recdList_column_search as $item) {
    //         if($_POST['search']['value']) {
    //             if(validateDate($_POST['search']['value'])) {
    //                 $_POST['search']['value'] = date('Y-m-d',strtotime($_POST['search']['value']));
    //             }
    //             if($i===0) {
    //                 $this->db->group_start();
    //                 $this->db->like($item, $_POST['search']['value']);
    //             }
    //             else {
    //                 $this->db->or_like($item, $_POST['search']['value']);
    //             }
    //             if(count($this->recdList_column_search) - 1 == $i) {
    //                 $this->db->group_end();
    //             }
    //         }
    //         $i++;
    //     }
    //     if(isset($_POST['order'])) {
    //         $this->db->order_by($this->recdListColumn_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    //     }
    //     else if(isset($this->recdListColumn_order)) {
    //         $recdListColumn_order = $this->recdListColumn_order;
    //         $this->db->order_by(key($recdListColumn_order), $recdListColumn_order[key($recdListColumn_order)]);
    //     }
    // }

    public function count_recdListFiltered() {
        $this->datatables_recdlist_qry();
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_RecdList() {
        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->where('a.queueno','0');
        $this->db->where('a.request_type_dept','CAD');
        $this->db->where('a.mgmtcurrentstatus','2');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');        
        $this->db->join(KN_USERS.' AS u','a.mgmtid = u.id');
        $this->db->join(KN_USERS.' AS mu','a.merchantid = mu.id');
        return $this->db->count_all_results();
    }
    
    // **** New *** //
    public function getcadreceivedlistt() {

        $sql = "SELECT a.*, c.brandname, b.isriorcode, f.contactname as auth_name
                FROM tbl_request as a 
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as f on a.auth_by=f.id
                WHERE a.flag IN(1,2) AND a.type = 1 and a.mgmt_approval=1 and a.deprt_approval=0 and a.qa_approval=0 and a.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY a.log DESC";
        $result = $this->db->query($sql)->result_array();

        $cad_sql = "SELECT a.cad_requirement, a.request_id, a.log as logs, a.qa_approval
                FROM tbl_cad_requirement as a 
                INNER JOIN tbl_request as e on a.request_id=e.request_id
                WHERE a.flag IN(1,2) AND e.type = 1 and e.mgmt_approval=1 and e.deprt_approval=0 and e.qa_approval=0 ORDER BY a.log DESC";
        $cad_data = $this->db->query($cad_sql)->result_array();

        foreach ($result as $key => $res) {
            $arr = $s_arr = $log_arr = [];
            
            foreach ($cad_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['cad_requirement']);
                    $status = '';
                    if($value['qa_approval'] == '0' || $value['qa_approval'] == '')
                    {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    else if($value['qa_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                    else if($value['qa_approval'] == '3') {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING-RR</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                    }
                    array_push($s_arr, $status);
                    
                    $date = date('d-m-Y h:i A',strtotime($value['logs']));
                    array_push($log_arr, $date);
                    
                }
            }
        
            // $dis_arr = array_unique($arr);
            $result[$key]['cad_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['cad_status'] = implode(' <br /> ', $s_arr);
            $result[$key]['logs'] = implode(' <br /> ', $log_arr);
    
        }

        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE a.flag IN(1,2) AND a.type = 1 and a.mgmt_approval=1 and a.deprt_approval=0 and a.qa_approval=0";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($result as $key => $value) {
            $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        }

        return $result;
    }
    
    public function getcadQueuelistt() {

        $sql = "SELECT a.*, c.brandname, b.isriorcode, f.contactname as auth_name
                FROM tbl_request as a 
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as f on a.auth_by=f.id
                WHERE a.flag IN(1,2) AND a.type = 1 and a.mgmt_approval=1 and a.deprt_approval=1 and a.qa_approval=0 and a.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY a.log DESC";
        $result = $this->db->query($sql)->result_array();

        $cad_sql = "SELECT a.cad_requirement, a.request_id, a.job_status, e.que_assign_date as que_assign_date, a.log as recent_update,a.job_status_upd_dt as job_sta_upd, e.log as recent_update2
                FROM tbl_cad_requirement as a 
                LEFT JOIN tbl_request as e on a.request_id=e.request_id
                WHERE e.flag IN(1,2) AND e.type = 1 and e.mgmt_approval=1 and e.deprt_approval=1 and e.qa_approval=0 ";
        $cad_data = $this->db->query($cad_sql)->result_array();

        $jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT.', 'COMPLETED', 'Q.A. RR SENT', 'RE-WORK PEND.','RE-WORK IN PROG.','WORK IN PROG.' ];


        foreach ($result as $key => $res) {
            $arr = $jArr = $recent_up1 = [];
            
            foreach ($cad_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    $recent_update = strtotime(date($value['recent_update']));
                    $recent_update2 = strtotime(date($value['recent_update2']));
                    if ($recent_update < $recent_update2) {
                        //$result[$key]['recent_update'] = $value['recent_update2'];
                        if($value['job_sta_upd']==''){
                             //$recent_up = date('d/m/Y h:i A',strtotime($value['recent_update2']));
                              $recent_up = $value['que_assign_date'];
                        }else{
                       $recent_up = $value['job_sta_upd'];
                        }
                        
                    }
                    else {
                          $recent_up = $value['job_sta_upd'];
                        if($value['job_sta_upd']==''){
                           $recent_up = date('d/m/Y h:i A',strtotime($res['log']));
                             //$recent_up = $res['log'];
                        }
                        
                           //$recent_up = $value['job_sta_upd'];
                        

                    }
                    if($value['job_status'] == 4)
                    {
                        $jobStatusTag = '<span class="text-light knGreenColor bg-dark"><strong>'.$jobStatus[$value['job_status']].'</strong></span>';
                    }
                    else if($value['job_status'] == 5 || $value['job_status'] == 6 ||$value['job_status'] == 7)
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

                    //print_r($recent_up1);
                }
            }
        
            // $dis_arr = array_unique($arr);
            // $j_dis_arr = array_unique($jArr);
             $result[$key]['cad_request_for'] = 'CAD';
            $result[$key]['cad_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['job_status'] = implode(' <br /> ', $jArr);
            $result[$key]['recent_update'] = implode(' <br /> ', $recent_up1);
        }

        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE a.flag IN(1,2) AND a.type = 1 and a.mgmt_approval=1 and a.deprt_approval=1 and a.qa_approval=0";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($result as $key => $value) {
            $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        }

        return $result;
    }
    
    // public function getcadSentlistt() {

    //     $sql = "SELECT a.*, c.brandname, b.isriorcode, f.contactname as auth_name, g.cad_requirement_id, g.qa_approval as qa_status, g.log as recent_update
    //             FROM tbl_request as a 
    //             INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
    //             INNER JOIN kn_master_brands as c on b.brandId=c.id 
    //             INNER JOIN kn_users as f on a.auth_by=f.id
    //             INNER JOIN tbl_cad_requirement as g on a.request_id=g.request_id
    //             WHERE a.flag=1 AND a.type = 1 and a.mgmt_approval=1 and a.deprt_approval=1 and a.dept_qa_request=1 and g.qa_req_sent_status=1 ORDER BY a.log DESC";
    //     $result = $this->db->query($sql)->result_array();


    //     $mer_sql = "SELECT b.contactname as merchant_name 
    //             FROM tbl_request as a
    //             inner join kn_users as b on a.req_by=b.id 
    //             INNER JOIN tbl_cad_requirement as g on a.request_id=g.request_id
    //             WHERE a.flag=1 AND a.type = 1 and a.mgmt_approval=1 and a.deprt_approval=1 and a.dept_qa_request=1 and g.qa_req_sent_status=1 ORDER BY a.log DESC";
    //     $mer_result = $this->db->query($mer_sql)->result_array();

    //     foreach ($result as $key => $value) {
    //         $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
    //     }

    //     $mer_sql2 = "SELECT b.contactname as cad_name 
    //             FROM tbl_request as a
    //             inner join kn_users as b on a.cad_by=b.id 
    //             INNER JOIN tbl_cad_requirement as g on a.request_id=g.request_id
    //             WHERE a.flag=1 AND a.type = 1 and a.mgmt_approval=1 and a.deprt_approval=1 and a.dept_qa_request=1 and g.qa_req_sent_status=1 ORDER BY a.log DESC";
    //     $mer_result2 = $this->db->query($mer_sql2)->result_array();

    //     foreach ($result as $key => $value) {
    //         $result[$key]['cad_name'] = $mer_result2[$key]['cad_name'];
    //     }

    //     return $result;

    // }
    
    public function getcadSentlistt() {
        

        $sql = "SELECT a.qa_req_id as qa_req_ids,b.*, g.flag as flags, g.cad_requirement_id,d.brandname, c.isriorcode, f.contactname as auth_name, h.contactname as merchant_name,i.contactname as cad_name, g.cad_requirement_id, g.qa_approval as qa_status, g.log as recent_update FROM qa_request as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN kn_order_enquiry as c on b.enquiry_id=c.id 
                INNER JOIN ".KN_MASTER_BRANDS." as d on c.brandId=d.id 
                INNER JOIN ".KN_USERS." as f on b.auth_by=f.id
                INNER JOIN ".KN_USERS." as h on b.req_by=h.id
                INNER JOIN ".KN_USERS." as i on b.cad_by=i.id
                INNER JOIN tbl_cad_requirement as g on a.qa_req_id=g.qa_req_id
                WHERE b.flag IN(1,2) AND b.type = 1 and b.mgmt_approval=1 and b.deprt_approval=1 and b.dept_qa_request=1 and g.qa_req_sent_status=1 and (g.qa_approval=0 or g.qa_approval=2) and b.subscriberid = " . $this->db->escape($this->subscriberid)."GROUP BY a.qa_req_id ORDER BY b.log DESC";
        $result = $this->db->query($sql)->result_array();
        
        foreach($result as $key => $value) {
            $arr = $s_arr = $log_arr = [];
            $req_sql = "SELECT c.cad_requirement, c.request_id, c.log as logs, c.qa_approval FROM qa_request as a 
	                INNER JOIN tbl_request as b on a.request_id=b.request_id
	                INNER JOIN tbl_cad_requirement as c on a.qa_req_id=c.qa_req_id 
	                WHERE a.qa_req_id=".$value['qa_req_ids']." and b.flag IN(1,2) AND b.type=1 AND b.mgmt_approval=1 AND b.deprt_approval=1 AND b.dept_qa_request=1  AND c.qa_req_sent_status = 1 and (c.qa_approval=0 or c.qa_approval=2) ORDER BY b.log DESC";
	        $req_data = $this->db->query($req_sql)->result_array();    
	       
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
	        
	        $result[$key]['item'] = implode(' <br /> ', $arr);
	        $result[$key]['cad_status'] = implode(' <br /> ', $s_arr);
            $result[$key]['logs'] = implode(' <br /> ', $log_arr);
	    }

        // $mer_sql = "SELECT c.contactname as merchant_name FROM qa_request as a
        //         INNER JOIN tbl_request as b on a.request_id=b.request_id
        //         inner join kn_users as c on b.req_by=c.id 
        //         INNER JOIN tbl_cad_requirement as g on a.qa_req_id=g.qa_req_id
        //         WHERE b.flag=1 AND b.type = 1 and b.mgmt_approval=1 and b.deprt_approval=1 and b.dept_qa_request=1 and g.qa_req_sent_status=1 GROUP BY a.qa_req_id ORDER BY b.log DESC";
        // $mer_result = $this->db->query($mer_sql)->result_array();

        // foreach ($result as $key => $value) {
        //     $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        // }

        // $mer_sql2 = "SELECT c.contactname as cad_name FROM qa_request as a
        //         INNER JOIN tbl_request as b on a.request_id=b.request_id
        //         inner join kn_users as c on b.cad_by=c.id 
        //         INNER JOIN tbl_cad_requirement as g on a.qa_req_id=g.qa_req_id
        //         WHERE b.flag=1 AND b.type = 1 and b.mgmt_approval=1 and b.deprt_approval=1 and b.dept_qa_request=1 and g.qa_req_sent_status=1 GROUP BY a.qa_req_id ORDER BY b.log DESC";
        // $mer_result2 = $this->db->query($mer_sql2)->result_array();

        // foreach ($result as $key => $value) {
        //     $result[$key]['cad_name'] = $mer_result2[$key]['cad_name'];
        // }

        return $result;

    }

    public function getcadIndentlistt() {
        
        $sql = "SELECT a.*, b.*, e.brandname, d.isriorcode, f.contactname as auth_name, group_concat(g.req) as req_ids, g.log as recent_update
                FROM tbl_mi_details as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN kn_order_enquiry as d on a.enquiry_id=d.id 
                INNER JOIN ".KN_MASTER_BRANDS." as e on d.brandId=e.id 
                INNER JOIN ".KN_USERS." as f on b.auth_by=f.id
                INNER JOIN tbl_mi_cad_details as g on a.request_id=g.request_id
                WHERE a.req_sent_status=1 AND a.dc_comp_status=0 AND a.flag=1 and b.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY g.request_id  ORDER BY a.log DESC";
        $result = $this->db->query($sql)->result_array();
        
        $mer_sql = "SELECT c.contactname as merchant_name 
                FROM tbl_mi_details as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN ".KN_USERS." as c on b.req_by=c.id
                WHERE a.req_sent_status=1 AND a.dc_comp_status=0 AND a.flag=1 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($result as $key => $value) {

            $dc_sql = "SELECT COUNT(*) as count
                    FROM tbl_mi_details as a 
                    INNER JOIN tbl_request as b on a.request_id=b.request_id
                    INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                    WHERE a.req_sent_status=1 AND c.request_id = ".$value['request_id']." AND c.dc_status=1 AND a.flag=1 GROUP BY a.mi_id ORDER BY a.log DESC";
            $result[$key]['scTotalCount'] = $this->db->query($dc_sql)->result_array();

            $dc_sql2 = "SELECT COUNT(*) as count
                    FROM tbl_mi_details as a 
                    INNER JOIN tbl_request as b on a.request_id=b.request_id
                    INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                    WHERE a.req_sent_status=1 AND c.request_id = ".$value['request_id']." AND a.flag=1 GROUP BY a.request_id ORDER BY a.log DESC";
            $result[$key]['dcTotalCount'] = $this->db->query($dc_sql2)->result_array();

        }

        $requirementSource = [ 'Bit Marker', 'Pattern', 'Pattern (Size Set)', 'Lay Marker', 'Others' ];
        
        //print_r($result); exit;

        foreach ($result as $key => $value) {
            $req = explode(',', $value['req_ids']);

            $arr = [];
            foreach ($req as $key1 => $res) {
                if($res != '') {
                    array_push($arr, $requirementSource[$res]);
                }
            }
            $result[$key]['requirement'] = implode(' <br /> ', $arr);

            if($value['scTotalCount'][0]['count'] == $value['dcTotalCount'][0]['count'])
            {
                $result[$key]['status'] = 0;
            }
            else {
                $result[$key]['status'] = 1;
            }
        }

        foreach ($result as $key => $value) {
            $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        }
usort($result, function ($a, $b) {
    return strtotime($b['log']) - strtotime($a['log']);  
});
        return $result;
    }

    public function getcadDClistt() {
        $sql = "SELECT a.*, a.type as types,c.flag as flags, b.*, e.brandname, d.isriorcode, f.contactname as auth_name, c.dc_ref_queue_no, c.dc_dt, c.item_received_status, c.mi_issued_by, c.log as recent_update
                FROM tbl_mi_details as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                INNER JOIN kn_order_enquiry as d on a.enquiry_id=d.id 
                INNER JOIN ".KN_MASTER_BRANDS." as e on d.brandId=e.id 
                LEFT JOIN ".KN_USERS." as f on b.auth_by=f.id
                WHERE   a.flag IN(1,2) and b.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY c.dc_ref_queue_no ORDER BY c.log DESC";
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
           // $result[$key]['mi_issued_by'] = $cad_result[0]['cad_name'];
            $result[$key]['cad_depts'] = $cad_depts;
        }

        return $result;
    }

}