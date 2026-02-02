<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class mbompurchaserequestmodel extends CI_Model {
    private $mysqlDateTime;
    private $companyId;
    private $userId;

    public function __construct() {
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyId = $ArrUserLoggedInfo['companyid'];
         $this->subscriberId     = $ArrUserLoggedInfo['subscriber_id'];
        $this->userId   = $ArrUserLoggedInfo['id'];
        $this->mysqlDateTime = date('Y-m-d H:i:s');
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

    public function saveBomPurchaseRequest($ArrUpdate, $VarPurpose, $VarArticle,$jsonBomConsolidatedShortage) {
        $VarId = $ArrUpdate['id'];
        if(empty($VarId)) {
            unset($ArrUpdate['id']);
            $this->db->insert(KN_ALLREQUEST, $ArrUpdate);
            $VarInsertId = $this->db->insert_id();
            $ArrResult['errcode'] = 1;
            $ArrResult['msg'] = '';
            $ArrResult['id'] = $VarInsertId;
            $ArrResult['eid'] = urlencode(base64_encode($VarInsertId));
            $ArrBomRequest = array('requestrefid' => $VarInsertId, 'purpose' => $VarPurpose, 'articletypeid' => $VarArticle,'bom_shortages_json'=>$jsonBomConsolidatedShortage);
            $this->db->insert(KN_BOMPURCHASEREQ, $ArrBomRequest);
        } else {
            if ($this->db->update(KN_ALLREQUEST, $ArrUpdate, array('id' => $VarId))) {
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = '-1';
                $ArrResult['msg'] = 'Invalid Data!';
            }
        }
        return $ArrResult;
    }
    
    

    public function getBomPurchaseRequest($VarId, $VarCompanyId) {
        $VarBomSql = "SELECT a.id,a.requesttype,a.cutoffdatetime,a.merchantid,a.queueno_assigned_date,a.status,
a.requestrefno,a.merchantnote,a.orderid,a.mgmtcurrentstatus,a.authdatetime,a.deptcurrentstatus,a.mgmtremarks,a.deptremarks,a.queueno,
a.dateupdated,a.jobschedule,a.approvaltype,mgmtid,alldeptid,a.request_type_dept,a.isriorcode,a.authdatetime,
bpr.articletypeid,bpr.purpose,bpr.id,bpr.bompirequestgrid_tblname FROM 
 " . KN_ALLREQUEST . " AS a INNER JOIN " . KN_BOMPURCHASEREQ . " AS bpr ON a.id = bpr.requestrefid WHERE a.id = '$VarId' 
 AND a.companyid = '$VarCompanyId'";
        return $this->db->query($VarBomSql)->row();

    }

    public function saveAuthoriseBomPurRequest($ArrData=array()) {
        $this->db->where('id', $ArrData['id']);
        $this->db->update(KN_ALLREQUEST, $ArrData);
        return $this->db->affected_rows();
    }

    var $recdListColumnOrder = array('','oe.isriorcode','brandname','','requesttype','a.datecreated','cutoffdatetime','approvaltype',
        'u.contactname','mu.contactname','current_status','a.dateupdated','a.status');

    var $recdListColumnSearch = array('oe.isriorcode','brandname','a.datecreated','cutoffdatetime',
        'u.contactname','mu.contactname','current_status','a.dateupdated','a.status','reqT.types','appT.types');
    public function recdlistDatatables() {
        $this->datatables_recdlist_qry();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    public function datatables_recdlist_qry() {
        $this->db->select('a.id as allid,DATE_FORMAT(cutoffdatetime,"%d-%m-%Y %H:%i:%s") AS formattedCutOffDt,DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") AS 
        formattedDateCreated,DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") AS formattedDateUpdated,requesttype,a.merchantid,a.status,a.isriorcode,
mgmtcurrentstatus,a.approvaltype,queueno,deptcurrentstatus,a.mgmtid,a.authdatetime,brandname,
u.contactname as mgmt,mu.contactname as merchant,bpr.articletypeid,bpr.purpose,reqT.types as reqType,appT.types as appType');
        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.companyid',$this->companyId);
        $this->db->where('a.request_type_dept','BOM');
        $this->db->where('a.mgmtcurrentstatus','2');
        $this->db->where('a.queueno','0');
        $this->db->join(KN_BOMPURCHASEREQ.' AS bpr','bpr.requestrefid = a.id');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.mgmtid = u.id');
        $this->db->join(KN_USERS.' AS mu','a.merchantid = mu.id');
        $this->db->join(KN_MASTER_REQUEST_TYPE.' AS reqT','reqT.id = a.requesttype');
        $this->db->join(KN_MASTER_REQUEST_TYPE.' AS appT','appT.id = a.approvaltype');
        $i = 0;
        foreach ($this->recdListColumnSearch as $item) {
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
                if(count($this->recdListColumnSearch) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if(isset($_POST['order'])) {
            $this->db->order_by($this->recdListColumnOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
    }

    public function count_recdListFiltered() {
        $this->datatables_recdlist_qry();
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_RecdList() {
        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.companyid',$this->companyId);
        $this->db->where('a.request_type_dept','BOM');
        $this->db->where('a.mgmtcurrentstatus','2');
        $this->db->where('a.queueno','0');
        $this->db->join(KN_BOMPURCHASEREQ.' AS bpr','bpr.requestrefid = a.id');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.mgmtid = u.id');
        $this->db->join(KN_USERS.' AS mu','a.merchantid = mu.id');
        $this->db->join(KN_MASTER_REQUEST_TYPE.' AS reqT','reqT.id = a.requesttype');
        $this->db->join(KN_MASTER_REQUEST_TYPE.' AS appT','appT.id = a.approvaltype');
        return $this->db->count_all_results();
    }

    public function saveBomQueueNo($VarId,$ArrData = array()) {
        $this->db->where('id', $VarId);
        $this->db->update(KN_ALLREQUEST, $ArrData);
        return $this->db->affected_rows();
    }

    /*public function createBomPITbl($VarTblName) {
        $VarCreateTblQry = "CREATE TABLE IF NOT EXISTS $VarTblName (
        `id` bigint NOT NULL AUTO_INCREMENT,
  `companyid` bigint NOT NULL,
  `orderid` bigint NOT NULL,
  `bompurchasrerequestid` bigint NOT NULL,
  `bompurchaseindentid` bigint NOT NULL,
  `bom_item_id` bigint NOT NULL,
  `itemdesc` varchar(100) NOT NULL,
  `garmentsize` varchar(5) NOT NULL,
  `itemcode` varchar(60) NOT NULL,
  `itemcolorcode` varchar(60) NOT NULL,
  `sizeordim` varchar(10) NOT NULL,
  `uom1` varchar(15) NOT NULL,
  `planbomqty` varchar(10) NOT NULL,
  `progbomqty` varchar(10) NOT NULL,
  `uom2` varchar(15) NOT NULL,
  `currency` varchar(4) NOT NULL,
  `unitrate` varchar(4) NOT NULL,
  `amount` varchar(10) NOT NULL,
  `sgstpercent` tinyint NOT NULL,
  `sgstvalue` varchar(4) NOT NULL,
  `cgstpercent` tinyint NOT NULL,
  `cgstvalue` varchar(4) NOT NULL,
  `igstpercent` tinyint NOT NULL,
  `igstvalue` varchar(4) NOT NULL,
  `dutypercent` tinyint NOT NULL,
  `dutyvalue` varchar(4) NOT NULL,
  `subtotal` varchar(10) NOT NULL,
  `tempselect` tinyint NOT NULL,
  `hiddenstatus` tinyint NOT NULL,
  `selectcheckbox` tinyint NOT NULL,
  `status` tinyint NOT NULL,
  PRIMARY KEY (`id`)
)";
        $this->db->query($VarCreateTblQry);
        return true;

    }*/

    public function savePreparePi($jsonData,$VarTaxTypeId,$bomPurchaseRequestId) {
        $ArrPreview = [];
        $ArrData = json_decode($jsonData,true);
        foreach ($ArrData as $data) {
            if($data[14] == 1) {
                /*Remove 6 key because plan bom qty is not needed in preview page only prog bom qty is required*/
                /*Remove 7 key because plan bom qty is not needed in preview page */
                /*Remove 8 key because plan bom qty is not needed in preview page */
                $ArrPreview[] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[7],$data[8],
                    $data[10],$data[11],$data[12],$data[14]);
            }
        }
        $previewJxl = json_encode($ArrPreview);
        $this->db->update(KN_BOMPURCHASEREQ, array('jsonPurchaseIndent' => $previewJxl,'taxtypeid'=>$VarTaxTypeId),array('requestrefid' => $bomPurchaseRequestId));
        return true;
    }

    public function getPreparePiForPreview($VarId) {
        $this->db->select('jsonPurchaseIndent,taxtypeid');
        $VarQry    = $this->db->get_where(KN_BOMPURCHASEREQ, array('requestrefid' => $VarId));
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }

    //BOM Purchase request Que list

    var $queueColOrder = array('','oe.id','brandname','queueno','request_type_dept','','a.datecreated','cutoffdatetime','approvaltype','','status','','','a.dateupdated');
    var $queueColSearch = array('oe.isriorcode','brandname','queueno','a.datecreated','approvaltype','cutoffdatetime','a.status','a.dateupdated');

    public function dataTablesQueueListQry() {

            $this->db->select('a.id,DATE_FORMAT(cutoffdatetime,"%d-%m-%Y %H:%i:%s") AS formattedCutOffDt,DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") AS 
        formattedDateCreated,DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") AS formattedDateUpdated,a.status,a.mgmtcurrentstatus,a.approvaltype,
        a.queuecompletestatus,a.queueno,a.deptcurrentstatus,
        a.requestrefno,a.request_type_dept,a.current_status,oe.isriorcode,brandname,u.contactname as mgmt,mu.contactname as merchant,requirementforbom,
        appT.types as appType');

        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->where('a.queueno !=','0');
        $this->db->where('a.request_type_dept',"BOM");
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.mgmtid = u.id');
        $this->db->join(KN_USERS.' AS mu','a.merchantid = mu.id');
        $this->db->join(KN_MASTER_REQUEST_TYPE.' AS appT','appT.id = a.approvaltype');
        $i = 0;
        foreach ($this->queueColSearch as $item) {
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
                if(count($this->queueColSearch) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if(isset($_POST['order'])) {
            $this->db->order_by($this->queueColOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function bomQueueListDataTables() {
        $this->dataTablesQueueListQry();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function bomQueueListCountFiltered() {
        $this->dataTablesQueueListQry();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function bomQueueListCountAll() {
        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->where('a.queueno !=','0');
        $this->db->where('a.request_type_dept',"BOM");
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.mgmtid = u.id');
        $this->db->join(KN_USERS.' AS mu','a.merchantid = mu.id');
        return $this->db->count_all_results();
    }
    //BOM Purchase request Que list ENDD


    // *** New *** //
    public function getPurchaseListt_old() {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name FROM 
                tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                inner join ".KN_USERS." as d on a.auth_by=d.id 
                where a.type=3 and a.mgmt_approval=1 and a.deprt_approval=0  order by a.log desc";
        $data = $this->db->query($sql)->result_array();

        
        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE  a.type = 3  and a.mgmt_approval=1 and a.deprt_approval=0";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($data as $key => $value) {
            $data[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        }

        return $data;
    }
    

    public function getPurchaseListt() {
        $sql = "SELECT a.*, a.flag as flags,c.brandname, b.isriorcode, d.contactname as auth_name FROM 
                tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                inner join ".KN_USERS." as d on a.auth_by=d.id 
                where a.type IN (3, 4) and a.mgmt_approval=1 and a.deprt_approval=0  and a.subscriberid = " . $this->db->escape($this->subscriberId)." order by a.log desc";
        $data = $this->db->query($sql)->result_array();

        
        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE a.type IN (3, 4)  and a.mgmt_approval=1 and a.deprt_approval=0";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($data as $key => $value) {
            $data[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        }

        return $data;
    }
    public function getPurchaseListtbom1() {
        $sql = "SELECT a.*, a.flag as flags, c.brandname, b.isriorcode, d.contactname as auth_name FROM 
                tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                inner join ".KN_USERS." as d on a.auth_by=d.id 
                where a.type IN (3) and a.mgmt_approval=1 and a.deprt_approval=0 and  a.subscriberid = " . $this->db->escape($this->subscriberId)." order by a.log desc";
        $data = $this->db->query($sql)->result_array();

        
        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE a.type IN (3)  and a.mgmt_approval=1 and a.deprt_approval=0";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($data as $key => $value) {
            $data[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        }

        return $data;
    }

    public function getPurchaseListtbom2() {
        $sql = "SELECT a.*, a.flag as flags, c.brandname, b.isriorcode, d.contactname as auth_name FROM 
                tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                inner join ".KN_USERS." as d on a.auth_by=d.id 
                where a.type IN (4) and a.mgmt_approval=1 and a.deprt_approval=0 and  a.subscriberid = " . $this->db->escape($this->subscriberId)." order by a.log desc";
        $data = $this->db->query($sql)->result_array();

        
        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE  a.type IN (4)  and a.mgmt_approval=1 and a.deprt_approval=0";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($data as $key => $value) {
            $data[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        }

        return $data;
    }
    public function getBomQueueListt() {
        $sql = "SELECT a.*, a.flag as flags, c.brandname, b.isriorcode, d.contactname as auth_name FROM 
                tbl_request as a left join kn_order_enquiry as b on a.enquiry_id=b.id 
                left join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                left join ".KN_USERS." as d on a.auth_by=d.id 
                where a.type IN (3, 4) and a.deprt_approval=1  and a.subscriberid = " . $this->db->escape($this->subscriberId)." ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();
    // print_r($sql); exit;
        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE   a.type IN (3, 4) and a.deprt_approval=1 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($data as $key => $value) {
            if($value['deprt_approval'] == 1) {
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>IN QUEUE</strong></span>';
            } else {
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>SUPPLY CLOSED</strong></span>';
            }
            $data[$key]['merchant_name'] = @$mer_result[$key]['merchant_name'];
            $data[$key]['bom_status'] = $status;
        }

        return $data;
    }

    public function getBom1QueueListt() {
        $sql = "SELECT a.*, a.flag as flags,c.brandname, b.isriorcode, d.contactname as auth_name FROM 
                tbl_request as a left join kn_order_enquiry as b on a.enquiry_id=b.id 
                left join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                left join ".KN_USERS." as d on a.auth_by=d.id 
                where a.type IN (3) and a.deprt_approval=1  and a.subscriberid = " . $this->db->escape($this->subscriberId)." ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();
    // print_r($sql); exit;
        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE  a.type IN (3) and a.deprt_approval=1 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($data as $key => $value) {
            if($value['deprt_approval'] == 1) {
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>IN QUEUE</strong></span>';
            } else {
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>SUPPLY CLOSED</strong></span>';
            }
            $data[$key]['merchant_name'] = @$mer_result[$key]['merchant_name'];
            $data[$key]['bom_status'] = $status;
        }

        return $data;
    }
     public function getBom2QueueListt() {
        $sql = "SELECT a.*, a.flag as flags,c.brandname, b.isriorcode, d.contactname as auth_name FROM 
                tbl_request as a left join kn_order_enquiry as b on a.enquiry_id=b.id 
                left join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                left join ".KN_USERS." as d on a.auth_by=d.id 
                where a.type IN (4) and a.deprt_approval=1  and a.subscriberid = " . $this->db->escape($this->subscriberId)." ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();
    // print_r($sql); exit;
        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE  a.type IN (4) and a.deprt_approval=1 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($data as $key => $value) {
            if($value['deprt_approval'] == 1) {
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>IN QUEUE</strong></span>';
            } else {
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>SUPPLY CLOSED</strong></span>';
            }
            $data[$key]['merchant_name'] = @$mer_result[$key]['merchant_name'];
            $data[$key]['bom_status'] = $status;
        }

        return $data;
    }
    
    public function getPurchaseIndentListt() {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name FROM 
                tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.i
                inner join ".KN_USERS." as d on a.auth_by=d.id 
                where a.type=3 and a.pi_appl_status=1 ";
        $data = $this->db->query($sql)->result_array();

        return $data;
    }

    public function getBomPurchaseSentListt() {
        // $sql = "SELECT a.purchase_indent_id,f.log,b.enquiry_id,b.request_id,b.type,b.req_type,b.req_date,b.cutoff_date,b.auth_type, d.brandname, c.isriorcode, e.contactname as auth_name, f.request_for, f.payment_requirement,f.appr_status, h.vendorname FROM 
        //         tbl_purchase_indent as a
        //         inner join tbl_request as b on a.request_id=b.request_id 
        //         inner join kn_order_enquiry as c on b.enquiry_id=c.id 
        //         inner join kn_master_brands as d on c.brandId=d.id 
        //         inner join kn_users as e on b.auth_by=e.id 
        //         inner join tbl_request_status as f on a.purchase_indent_id=f.purchase_indent_id
        //         inner join tbl_request_payment as g on a.purchase_indent_id=g.purchase_indent_id
        //         inner join kn_master_bom_vendor as h on g.vendor_id=h.id
        //         where b.type=3 and b.deprt_approval=1 and a.pi_list_status=0 and a.pi_draft_status=0 and b.flag=1 ORDER BY a.log DESC";
                $sql = "SELECT f.purchase_indent_id,f.log,b.enquiry_id,f.flag as flags,b.request_id,b.type,b.cutoff_date,f.appr_type, d.brandname, c.isriorcode, e.contactname as auth_name, f.req_dt, f.request_type, f.request_for, f.payment_requirement,f.appr_status, h.vendorname FROM 
                tbl_purchase_indent as a
                inner join tbl_request as b on a.request_id=b.request_id 
                inner join kn_order_enquiry as c on b.enquiry_id=c.id 
                inner join ".KN_MASTER_BRANDS." as d on c.brandId=d.id 
                inner join tbl_request_status as f on a.purchase_indent_id=f.purchase_indent_id
                left join ".KN_USERS." as e on f.appr_by=e.id
                left join tbl_request_payment as g on a.purchase_indent_id=g.purchase_indent_id
                left join kn_master_bom_vendor as h on g.vendor_id=h.id
                where  b.type IN (3, 4) and b.deprt_approval=1 and a.pi_list_status=0 and a.pi_draft_status=0 and b.subscriberid = " . $this->db->escape($this->subscriberId)." ORDER BY f.log DESC";
        $data = $this->db->query($sql)->result_array();
                
            // $sql = "SELECT a.* FROM 
            //     tbl_purchase_indent as a
            //     inner join tbl_request as b on a.request_id=b.request_id ";
            //      print_r($sql); exit;
        $data = $this->db->query($sql)->result_array();

        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join kn_users as b on a.req_by=b.id 
                WHERE   a.type IN (3, 4) and a.deprt_approval=1 and a.pi_appl_status=1 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($data as $key => $value) {
            // $data[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
            // $data[$key]['bom_status'] = '';
        }


        return $data;
    }
    
    public function getBom1PurchaseSentListt() {
                $sql = "SELECT f.request_status_id,f.log,f.flag as flags,b.enquiry_id,b.request_id,b.type,b.cutoff_date,f.appr_type, d.brandname, c.isriorcode, e.contactname as auth_name, f.req_dt, f.request_type, f.request_for, f.payment_requirement,f.appr_status, h.vendorname FROM 
                tbl_purchase_indent as a
                inner join tbl_request as b on a.request_id=b.request_id 
                inner join kn_order_enquiry as c on b.enquiry_id=c.id 
                inner join ".KN_MASTER_BRANDS." as d on c.brandId=d.id 
                inner join tbl_request_status as f on a.purchase_indent_id=f.purchase_indent_id
                left join ".KN_USERS." as e on f.appr_by=e.id
                left join tbl_request_payment as g on a.purchase_indent_id=g.purchase_indent_id
                left join kn_master_bom_vendor as h on g.vendor_id=h.id
                where  b.type IN (3) and b.deprt_approval=1 and a.pi_list_status=0 and a.pi_draft_status=0 and b.flag=1 and b.subscriberid = " . $this->db->escape($this->subscriberId)." ORDER BY f.log DESC";
        $data = $this->db->query($sql)->result_array();
                
            // $sql = "SELECT a.* FROM 
            //     tbl_purchase_indent as a
            //     inner join tbl_request as b on a.request_id=b.request_id ";
            //      print_r($sql); exit;
        $data = $this->db->query($sql)->result_array();

        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join kn_users as b on a.req_by=b.id 
                WHERE   a.type IN (3) and a.deprt_approval=1 and a.pi_appl_status=1 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($data as $key => $value) {
            // $data[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
            // $data[$key]['bom_status'] = '';
        }

        return $data;
    }
    public function getBom2PurchaseSentListt() {
                $sql = "SELECT f.request_status_id,f.flag as flags,f.log,b.enquiry_id,b.request_id,b.type,b.cutoff_date,f.appr_type, d.brandname, c.isriorcode, e.contactname as auth_name, f.req_dt, f.request_type, f.request_for, f.payment_requirement,f.appr_status, h.vendorname FROM 
                tbl_purchase_indent as a
                inner join tbl_request as b on a.request_id=b.request_id 
                inner join kn_order_enquiry as c on b.enquiry_id=c.id 
                inner join ".KN_MASTER_BRANDS." as d on c.brandId=d.id 
                inner join tbl_request_status as f on a.purchase_indent_id=f.purchase_indent_id
                left join ".KN_USERS." as e on f.appr_by=e.id
                left join tbl_request_payment as g on a.purchase_indent_id=g.purchase_indent_id
                left join kn_master_bom_vendor as h on g.vendor_id=h.id
                where  b.type IN (4) and b.deprt_approval=1 and a.pi_list_status=0 and a.pi_draft_status=0  and b.subscriberid = " . $this->db->escape($this->subscriberId)." ORDER BY f.log DESC";
        $data = $this->db->query($sql)->result_array();
                
            // $sql = "SELECT a.* FROM 
            //     tbl_purchase_indent as a
            //     inner join tbl_request as b on a.request_id=b.request_id ";
            //      print_r($sql); exit;
        $data = $this->db->query($sql)->result_array();

        $mer_sql = "SELECT b.contactname as merchant_name 
                FROM tbl_request as a
                inner join kn_users as b on a.req_by=b.id 
                WHERE  a.type IN (4) and a.deprt_approval=1 and a.pi_appl_status=1 ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($data as $key => $value) {
            // $data[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
            // $data[$key]['bom_status'] = '';
        }

        return $data;
    }
    
    // public function getBomPurchaseIndentListt() {
    //      $sql = "SELECT a.*,h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, e.*, e.log as logs, f.*, g.vendorname FROM tbl_purchase_indent as a 
    //             inner join tbl_request as h on a.request_id=h.request_id
    //             inner join kn_order_enquiry as b on a.enquiry_id=b.id 
    //             inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
    //             inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
    //             inner join tbl_request_payment as f on a.purchase_indent_id=f.purchase_indent_id
    //             inner join kn_master_bom_vendor as g on f.vendor_id=g.id
    //             where h.type=3 and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and h.flag=1 GROUP BY a.purchase_indent_id ORDER BY e.log DESC";
    //     $data = $this->db->query($sql)->result_array();

    //     return $data;
    // }
    
    public function getBomPurchaseIndentListt()
    {

         $results =[];
         
        $sql = "SELECT a.*, h.type,h.cutoff_date, c.brandname, b.isriorcode, e.*, e.request_status_id,e.log as logs,e.flag as flags , f.proforma_value, f.currency as pay_currency, f.pay_by_date, f.request_payment_id, g.vendorname FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join tbl_request_payment as f on f.type_of_mode='M' and a.purchase_indent_id=f.purchase_indent_id
                left join kn_master_bom_vendor as g on a.vendor_id=g.id
                where  h.type IN (3, 4) and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and e.type_of_mode='M'  and a.bill_paid_status = 0 and b.subscriberid = " . $this->db->escape($this->subscriberId)." ORDER BY e.log DESC";
        $result = $this->db->query($sql)->result_array();
        
        foreach ($result as $key => $res) {
         $request_for = []; $payment_requirement = []; $vendorname = []; $inv_status = []; $log = []; 
         $payment_requirement[] = 'BOM (A1)';
         $request_for[] = $res['request_for'];
         $vendorname[] = $res['vendorname'];
         $log[] = date('d/m/Y h:i A',strtotime($res['log']));
         if($res['payment_paid_status'] == 'PAID' || $res['payment_paid_status'] == 'PART PAID') {
             if($res['payment_paid_status'] == 'PAID') {
                $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>';    
             } else if($res['payment_paid_status'] == 'PART PAID') {
                 $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>';
             }
             
         } else {
             
            if($res['appr_status'] == 0 || $res['appr_status'] == '') {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
            } else if($res['appr_status'] == 1) {
                $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>APPROVED</strong></span>';
            } else if($res['appr_status'] == 2) {
                $inv_status[] = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
            } else if($res['appr_status'] == 3) {
                $inv_status[] = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
            } else {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
            }
         }
        //  if($res['appr_status'] == 0) {
        //      $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
        //  } else if($res['appr_status'] == 1) {
        //      $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>APPROVED</strong></span>';
        //  } else if($res['appr_status'] == 2) {
        //      $inv_status[] = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
        //  } else if($res['appr_status'] == 3) {
        //      $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING-RR</strong></span>';
        //  }
         $results[$key]['request_status_id'] = $res['request_status_id'];
         $results[$key]['isriorcode'] = $res['isriorcode'];
         $results[$key]['enquiry_id'] = $res['enquiry_id'];
         $results[$key]['request_id'] = $res['request_id'];
         $results[$key]['purchase_indent_id'] = $res['purchase_indent_id'];
         $results[$key]['brandname'] = $res['brandname'];
         $results[$key]['type'] = $res['type'];
         $results[$key]['vendorname'] = $vendorname;
         $results[$key]['request_for'] = $request_for;
         $results[$key]['payment_requirement'] = $payment_requirement;
         $results[$key]['pi_ref_queue_no'] = $res['pi_ref_queue_no'];
         $results[$key]['pi_dt'] = $res['pi_dt'];
         $results[$key]['pay_by_date'] = $res['pay_by_date'];
         $results[$key]['cutoff_date'] = $res['cutoff_date'];
         $results[$key]['exp_dod'] = $res['exp_dod'];
         $results[$key]['inv_status'] = $inv_status;
         $results[$key]['type_of_mode'] = $res['type_of_mode'];
         $results[$key]['logs'] = $log;
         $results[$key]['flags'] = $res['flags'];
         
        $res_data = $this->db->where('type_of_mode','S')->where('purchase_indent_id',$res['purchase_indent_id'])->where('payment_paid_status !=','PAID')->get('tbl_request_status')->result_array();
        
        foreach($res_data as $key2 => $value) {   
        if($value['purchase_indent_id'] == $res['purchase_indent_id']) { 
            $pId = $value['purchase_indent_id'];
            $vendor_data = $sql = "SELECT b.vendorname FROM tbl_request_payment as a 
                inner join kn_master_bom_vendor as b on a.vendor_id=b.id
                where a.purchase_indent_id = $pId ";
            $vendor_name1 = $this->db->query($vendor_data)->row()->vendorname;
            $row_id = $value['row_id'];
            
            $sql = "SELECT a.*, g.vendorname FROM tbl_request_payment as a 
                inner join kn_master_bom_vendor as g on a.vendor_id=g.id
                where a.purchase_indent_id=$pId and a.row_id = '$row_id' ";
            $pay_vendor = $this->db->query($sql)->row();
           // @$pay_vendor = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_request_payment')->row();
            if($pay_vendor) {
                $vendor_name = $pay_vendor->vendorname;
            }
            
            @$inv_vendor = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_payment_invoice')->row();
            if($inv_vendor) {
                $vendor_name = $inv_vendor->vendor_name;
            }
             
             @$others_amt = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_request_payment_others')->row();
            if($others_amt) {
                $vendor_name = $others_amt->pay_code;
            }
            
            //@$others_amt = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_request_payment_others')->row();
            
            
            if($value['payment_paid_status'] == 'PAID' || $value['payment_paid_status'] == 'PART PAID') {
                if($value['payment_paid_status'] == 'PAID') {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>');    
                } else if($value['payment_paid_status'] == 'PART PAID') {
                    array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>');
                }
            } else {
            
            if($value['appr_status'] == 0 || $value['appr_status'] == '') {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>');
            } else if($value['appr_status'] == 1) {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>APPROVED</strong></span>');
            } else if($value['appr_status'] == 2) {
                array_push($inv_status, '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>');
            } else if($value['appr_status'] == 3) {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING - RR</strong></span>');
            }  else {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>');
            }
            }
            $logg = date('d/m/Y h:i A',strtotime($value['log']));
            array_push($request_for, $value['request_for']);
            array_push($payment_requirement, $value['payment_requirement']);
            array_push($vendorname, $vendor_name);
            array_push($log, $logg);
         
            $results[$key]['request_for'] = implode(' <br /> ', $request_for);
            $results[$key]['payment_requirement'] = implode(' <br /> ', $payment_requirement);
            $results[$key]['vendorname'] = implode(' <br /> ', $vendorname);
            $results[$key]['inv_status'] = implode(' <br /> ', $inv_status);
            $results[$key]['logs'] = implode(' <br /> ', $log);
        } 
        }
        }
        return $results;
    }

     public function getBomPurchaseIndentListtbom1()
    {

         $results =[];
         
        $sql = "SELECT a.*, e.flag as flags , h.type,h.cutoff_date, c.brandname, b.isriorcode, e.*, e.log as logs,e.request_status_id, f.proforma_value, f.currency as pay_currency, f.pay_by_date, f.request_payment_id, g.vendorname FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join tbl_request_payment as f on f.type_of_mode='M' and a.purchase_indent_id=f.purchase_indent_id
                left join kn_master_bom_vendor as g on a.vendor_id=g.id
                where  h.type IN (3) and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and e.type_of_mode='M'  and a.bill_paid_status = 0 and b.subscriberid = " . $this->db->escape($this->subscriberId)." ORDER BY e.log DESC";
        $result = $this->db->query($sql)->result_array();
        
        foreach ($result as $key => $res) {
         $request_for = []; $payment_requirement = []; $vendorname = []; $inv_status = []; $log = []; 
         $payment_requirement[] = 'BOM (A1)';
         $request_for[] = $res['request_for'];
         $vendorname[] = $res['vendorname'];
         $log[] = date('d-m-Y h:i A',strtotime($res['log']));
         if($res['payment_paid_status'] == 'PAID' || $res['payment_paid_status'] == 'PART PAID') {
             if($res['payment_paid_status'] == 'PAID') {
                $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>';    
             } else if($res['payment_paid_status'] == 'PART PAID') {
                 $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>';
             }
             
         } else {
             
            if($res['appr_status'] == 0 || $res['appr_status'] == '') {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
            } else if($res['appr_status'] == 1) {
                $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>APPROVED</strong></span>';
            } else if($res['appr_status'] == 2) {
                $inv_status[] = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
            } else if($res['appr_status'] == 3) {
                $inv_status[] = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
            } else {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
            }
         }
        //  if($res['appr_status'] == 0) {
        //      $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
        //  } else if($res['appr_status'] == 1) {
        //      $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>APPROVED</strong></span>';
        //  } else if($res['appr_status'] == 2) {
        //      $inv_status[] = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
        //  } else if($res['appr_status'] == 3) {
        //      $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING-RR</strong></span>';
        //  }
        $results[$key]['request_status_id'] = $res['request_status_id'];
         $results[$key]['isriorcode'] = $res['isriorcode'];
         $results[$key]['enquiry_id'] = $res['enquiry_id'];
         $results[$key]['request_id'] = $res['request_id'];
         $results[$key]['purchase_indent_id'] = $res['purchase_indent_id'];
         $results[$key]['brandname'] = $res['brandname'];
         $results[$key]['type'] = $res['type'];
         $results[$key]['vendorname'] = $vendorname;
         $results[$key]['request_for'] = $request_for;
         $results[$key]['payment_requirement'] = $payment_requirement;
         $results[$key]['pi_ref_queue_no'] = $res['pi_ref_queue_no'];
         $results[$key]['pi_dt'] = $res['pi_dt'];
         $results[$key]['pay_by_date'] = $res['pay_by_date'];
         $results[$key]['cutoff_date'] = $res['cutoff_date'];
         $results[$key]['exp_dod'] = $res['exp_dod'];
         $results[$key]['inv_status'] = $inv_status;
         $results[$key]['type_of_mode'] = $res['type_of_mode'];
         $results[$key]['logs'] = $log;
         $results[$key]['flags'] = $res['flags'];
         
        $res_data = $this->db->where('type_of_mode','S')->where('purchase_indent_id',$res['purchase_indent_id'])->where('payment_paid_status !=','PAID')->get('tbl_request_status')->result_array();
        
        foreach($res_data as $key2 => $value) {   
        if($value['purchase_indent_id'] == $res['purchase_indent_id']) { 
            $pId = $value['purchase_indent_id'];
            $vendor_data = $sql = "SELECT b.vendorname FROM tbl_request_payment as a 
                inner join kn_master_bom_vendor as b on a.vendor_id=b.id
                where a.purchase_indent_id = $pId ";
            $vendor_name1 = $this->db->query($vendor_data)->row()->vendorname;
            $row_id = $value['row_id'];
            
            $sql = "SELECT a.*, g.vendorname FROM tbl_request_payment as a 
                inner join kn_master_bom_vendor as g on a.vendor_id=g.id
                where a.purchase_indent_id=$pId and a.row_id = '$row_id' ";
            $pay_vendor = $this->db->query($sql)->row();
           // @$pay_vendor = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_request_payment')->row();
            if($pay_vendor) {
                $vendor_name = $pay_vendor->vendorname;
            }
            
            @$inv_vendor = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_payment_invoice')->row();
            if($inv_vendor) {
                $vendor_name = $inv_vendor->vendor_name;
            }
             
             @$others_amt = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_request_payment_others')->row();
            if($others_amt) {
                $vendor_name = $others_amt->pay_code;
            }
            
            //@$others_amt = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_request_payment_others')->row();
            
            
            if($value['payment_paid_status'] == 'PAID' || $value['payment_paid_status'] == 'PART PAID') {
                if($value['payment_paid_status'] == 'PAID') {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>');    
                } else if($value['payment_paid_status'] == 'PART PAID') {
                    array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>');
                }
            } else {
            
            if($value['appr_status'] == 0 || $value['appr_status'] == '') {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>');
            } else if($value['appr_status'] == 1) {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>APPROVED</strong></span>');
            } else if($value['appr_status'] == 2) {
                array_push($inv_status, '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>');
            } else if($value['appr_status'] == 3) {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING - RR</strong></span>');
            }  else {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>');
            }
            }
            $logg = date('d-m-Y h:i A',strtotime($value['log']));
            array_push($request_for, $value['request_for']);
            array_push($payment_requirement, $value['payment_requirement']);
            array_push($vendorname, $vendor_name);
            array_push($log, $logg);
         
            $results[$key]['request_for'] = implode(' <br /> ', $request_for);
            $results[$key]['payment_requirement'] = implode(' <br /> ', $payment_requirement);
            $results[$key]['vendorname'] = implode(' <br /> ', $vendorname);
            $results[$key]['inv_status'] = implode(' <br /> ', $inv_status);
            $results[$key]['logs'] = implode(' <br /> ', $log);
        } 
        }
        }
        return $results;
    }
     public function getBomPurchaseIndentListtbom2()
    {

         $results =[];
         
        $sql = "SELECT a.*, e.flag as flags , h.type,h.cutoff_date, c.brandname, b.isriorcode, e.*, e.log as logs,e.request_status_id, f.proforma_value, f.currency as pay_currency, f.pay_by_date, f.request_payment_id, g.vendorname FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join tbl_request_payment as f on f.type_of_mode='M' and a.purchase_indent_id=f.purchase_indent_id
                left join kn_master_bom_vendor as g on a.vendor_id=g.id
                where  h.type IN (4) and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and e.type_of_mode='M'  and a.bill_paid_status = 0 and b.subscriberid = " . $this->db->escape($this->subscriberId)." ORDER BY e.log DESC";
        $result = $this->db->query($sql)->result_array();
        
        foreach ($result as $key => $res) {
         $request_for = []; $payment_requirement = []; $vendorname = []; $inv_status = []; $log = []; 
         $payment_requirement[] = 'BOM (A1)';
         $request_for[] = $res['request_for'];
         $vendorname[] = $res['vendorname'];
         $log[] = date('d-m-Y h:i A',strtotime($res['log']));
         if($res['payment_paid_status'] == 'PAID' || $res['payment_paid_status'] == 'PART PAID') {
             if($res['payment_paid_status'] == 'PAID') {
                $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>';    
             } else if($res['payment_paid_status'] == 'PART PAID') {
                 $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>';
             }
             
         } else {
             
            if($res['appr_status'] == 0 || $res['appr_status'] == '') {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
            } else if($res['appr_status'] == 1) {
                $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>APPROVED</strong></span>';
            } else if($res['appr_status'] == 2) {
                $inv_status[] = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
            } else if($res['appr_status'] == 3) {
                $inv_status[] = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
            } else {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
            }
         }
        //  if($res['appr_status'] == 0) {
        //      $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
        //  } else if($res['appr_status'] == 1) {
        //      $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>APPROVED</strong></span>';
        //  } else if($res['appr_status'] == 2) {
        //      $inv_status[] = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
        //  } else if($res['appr_status'] == 3) {
        //      $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING-RR</strong></span>';
        //  }
         $results[$key]['isriorcode'] = $res['isriorcode'];
          $results[$key]['request_status_id'] = $res['request_status_id'];
         $results[$key]['enquiry_id'] = $res['enquiry_id'];
         $results[$key]['request_id'] = $res['request_id'];
         $results[$key]['purchase_indent_id'] = $res['purchase_indent_id'];
         $results[$key]['brandname'] = $res['brandname'];
         $results[$key]['type'] = $res['type'];
         $results[$key]['vendorname'] = $vendorname;
         $results[$key]['request_for'] = $request_for;
         $results[$key]['payment_requirement'] = $payment_requirement;
         $results[$key]['pi_ref_queue_no'] = $res['pi_ref_queue_no'];
         $results[$key]['pi_dt'] = $res['pi_dt'];
         $results[$key]['pay_by_date'] = $res['pay_by_date'];
         $results[$key]['cutoff_date'] = $res['cutoff_date'];
         $results[$key]['exp_dod'] = $res['exp_dod'];
         $results[$key]['inv_status'] = $inv_status;
         $results[$key]['type_of_mode'] = $res['type_of_mode'];
         $results[$key]['logs'] = $log;
         $results[$key]['flags'] = $res['flags'];
         
        $res_data = $this->db->where('type_of_mode','S')->where('purchase_indent_id',$res['purchase_indent_id'])->where('payment_paid_status !=','PAID')->get('tbl_request_status')->result_array();
        
        foreach($res_data as $key2 => $value) {   
        if($value['purchase_indent_id'] == $res['purchase_indent_id']) { 
            $pId = $value['purchase_indent_id'];
            $vendor_data = $sql = "SELECT b.vendorname FROM tbl_request_payment as a 
                inner join kn_master_bom_vendor as b on a.vendor_id=b.id
                where a.purchase_indent_id = $pId ";
            $vendor_name1 = $this->db->query($vendor_data)->row()->vendorname;
            $row_id = $value['row_id'];
            
            $sql = "SELECT a.*, g.vendorname FROM tbl_request_payment as a 
                inner join kn_master_bom_vendor as g on a.vendor_id=g.id
                where a.purchase_indent_id=$pId and a.row_id = '$row_id' ";
            $pay_vendor = $this->db->query($sql)->row();
           // @$pay_vendor = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_request_payment')->row();
            if($pay_vendor) {
                $vendor_name = $pay_vendor->vendorname;
            }
            
            @$inv_vendor = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_payment_invoice')->row();
            if($inv_vendor) {
                $vendor_name = $inv_vendor->vendor_name;
            }
             
             @$others_amt = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_request_payment_others')->row();
            if($others_amt) {
                $vendor_name = $others_amt->pay_code;
            }
            
            //@$others_amt = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_request_payment_others')->row();
            
            
            if($value['payment_paid_status'] == 'PAID' || $value['payment_paid_status'] == 'PART PAID') {
                if($value['payment_paid_status'] == 'PAID') {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>');    
                } else if($value['payment_paid_status'] == 'PART PAID') {
                    array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>');
                }
            } else {
            
            if($value['appr_status'] == 0 || $value['appr_status'] == '') {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>');
            } else if($value['appr_status'] == 1) {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>APPROVED</strong></span>');
            } else if($value['appr_status'] == 2) {
                array_push($inv_status, '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>');
            } else if($value['appr_status'] == 3) {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING - RR</strong></span>');
            }  else {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>');
            }
            }
            $logg = date('d-m-Y h:i A',strtotime($value['log']));
            array_push($request_for, $value['request_for']);
            array_push($payment_requirement, $value['payment_requirement']);
            array_push($vendorname, $vendor_name);
            array_push($log, $logg);
         
            $results[$key]['request_for'] = implode(' <br /> ', $request_for);
            $results[$key]['payment_requirement'] = implode(' <br /> ', $payment_requirement);
            $results[$key]['vendorname'] = implode(' <br /> ', $vendorname);
            $results[$key]['inv_status'] = implode(' <br /> ', $inv_status);
            $results[$key]['logs'] = implode(' <br /> ', $log);
        } 
        }
        }
        return $results;
    }


    public function getMgmtBillPaidListt()
    {
     
        $sql = "SELECT a.*,  a.flag as flags, h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, e.*, e.log as logs, a.log as billlog, f.invoice_no, f.invoice_date, f.invoice_value, f.curency as pay_currency, f.pay_by_date, f.vendor_name FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                inner join tbl_payment_invoice as f on a.purchase_indent_id=f.purchase_indent_id
                where h.type=3 and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and a.bill_paid_status=1 and e.type_of_mode='M'  GROUP BY a.purchase_indent_id and h.subscriberid = " . $this->db->escape($this->subscriberId)." ORDER BY e.log DESC";
        $result = $this->db->query($sql)->result_array();
        
        foreach ($result as $key => $res) {
        //  $request_for = []; $payment_requirement = []; $vendorname = []; $inv_status = []; $log = [];
         $inv_no = []; $inv_date = []; $inv_value = []; $inv_status = []; $currency = []; $mop = [];
         $payment_requirement[] = $res['payment_requirement'];
         
         $log = date('d-m-Y h:i A',strtotime($res['billlog']));
         
         
         $results[$key]['isriorcode'] = $res['isriorcode'];
         $results[$key]['enquiry_id'] = $res['enquiry_id'];
         $results[$key]['request_id'] = $res['request_id'];
         $results[$key]['purchase_indent_id'] = $res['purchase_indent_id'];
         $results[$key]['brandname'] = $res['brandname'];
         $results[$key]['pi_ref_queue_no'] = $res['pi_ref_queue_no'];
         $results[$key]['vendorname'] = $res['vendor_name'];
         $results[$key]['type'] = $res['type'];
         $results[$key]['invoice_no'] = $res['invoice_no'];
         $results[$key]['invoice_date'] = date('d-m-Y',strtotime($res['invoice_date']));
         $results[$key]['invoice_value'] = $res['invoice_value'];
         $results[$key]['currency'] = $res['pay_currency'];
         $results[$key]['type_of_mode'] = $res['type_of_mode'];
         $results[$key]['logs'] = $log;
         $results[$key]['flags'] = $res['flags'];
         
        $id = $res['purchase_indent_id'];
        $pay_sql = "SELECT * FROM tbl_payment_invoice where purchase_indent_id = " . $id;
        $pay_data = $this->db->query($pay_sql)->result_array();
        
            foreach($pay_data as $key2 => $value) {   
                    $date = date('d-m-Y',strtotime($value['invoice_date']));
                    array_push($inv_no, $value['invoice_no']);
                    array_push($inv_date, $date);
                    array_push($inv_value, $value['invoice_value']);
                    array_push($currency, $value['curency']);
                    $status = '<span class="text-light knGreenColor bg-dark"><strong>BILL PAID</strong></span>';
                    array_push($inv_status, $status);
            }
            
            $results[$key]['invoice_no'] = implode(' <br /> ', $inv_no);
            $results[$key]['invoice_date'] = implode(' <br /> ', $inv_date);
            $results[$key]['invoice_value'] = implode(' <br /> ', $inv_value);
            $results[$key]['currency'] = implode(' <br /> ', $currency);
            $results[$key]['invoice_status'] = implode(' <br /> ', $inv_status);
        }
            
            
        return $results;
    }
    
    public function getstocktransferlistt() {
        $sql = "SELECT a.*,b.isriorcode, c.brandname,d.cutoff_date,e.pi_dt FROM tbl_surplus_issued_details as a 
                inner join kn_order_enquiry as b on a.enqId=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                inner join tbl_request as d on a.reqId=d.request_id 
                inner join tbl_purchase_indent as e on a.pId=e.purchase_indent_id
                WHERE a.draft_status=0 and d.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.enqId ";
        $result = $this->db->query($sql)->result_array();
        foreach ($result as $key => $res) {
            $stm_ref_no = $stm_date = $trans_category = $status = [] ;
            $enq_id = $res['enqId'];
            $sql1 = "SELECT * FROM tbl_surplus_issued_details WHERE enqId=".$enq_id." AND draft_status=0 GROUP BY stm_ref_no ";
            $result1 = $this->db->query($sql1)->result_array();
            foreach($result1 as $key1 => $val) {
                $enq_id1 = base64_encode($enq_id);
                $stm_ref_no1 = base64_encode($val['stm_ref_no']);
                array_push($stm_ref_no,'<a class="bold" href="'.base_url().'company/Mpurchaseuser/stocktransferdetails/'.$enq_id1.'/stm_ref_no/'.$stm_ref_no1.' ">'.$val['stm_ref_no'].'</a>');
                // $stm_ref_no[] = $val['stm_ref_no'];
                $stm_date[] = $val['stm_date_time'];
                $trans_category[] = $val['transfer_category'];
                $status[] = '<span class="text-light knGreenColor bg-dark"><strong>ISSUE - PART</strong></span>';
            }
            
            $data[$key]['isriorcode'] = $res['isriorcode'];
            $data[$key]['brandname'] = $res['brandname'];
            $data[$key]['requirement'] = 'BOM (Art-1)';
            $data[$key]['pi_ref_no'] = $res['pi_ref_no'];
            $data[$key]['pi_dt'] = $res['pi_dt'];
            $data[$key]['cutoff_date'] = $res['cutoff_date'];
            $data[$key]['stm_ref_no'] = implode(' <br /> ', $stm_ref_no);
            $data[$key]['stm_date_time'] = implode(' <br /> ', $stm_date);
            $data[$key]['transfer_category'] = implode(' <br /> ', $trans_category);
            $data[$key]['status'] = implode(' <br /> ', $status);;
            $data[$key]['log'] = $res['log'];
            $data[$key]['flag'] = $res['flag'];
            
            
        }
        
        
        return $data;
    }

}