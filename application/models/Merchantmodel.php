<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Merchantmodel extends CI_Model {


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
    /******************************************data Tables******************************************/
    var $allReqColOrder = array('', 'oe.id', 'brandname', 'request_type_dept', '', 'a.requesttype', 'a.datecreated', 'a.cutoffdatetime', 'a.approvaltype',
        'a.mgmtid', 'current_status', 'a.dateupdated', 'a.status');
    var $allReqColSearch = array('oe.isriorcode', 'brandname', 'request_type_dept', 'a.datecreated', 'requesttype', 'a.cutoffdatetime', 'a.approvaltype',
        'u.contactname', 'current_status', 'a.dateupdated', 'a.status');

    public function getAllReqListQry() {
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->select('a.id,oe.isriorcode,a.requesttype,DATE_FORMAT(a.cutoffdatetime,"%d-%m-%Y %H:%i:%s") AS formattedCutOffDt,
        DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") AS formatDateCreated,a.merchantid,DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") AS formattedDateUpdated,
        a.status,a.mgmtcurrentstatus,a.approvaltype,a.queueno,a.deptcurrentstatus,a.mgmtid,a.request_type_dept,a.current_status,jsondatagrid,brandname,
        u.contactname as mgmt,requirementforbom,request_type_dept');
        $this->db->from(KN_ALLREQUEST . ' AS a');
        $this->db->where('a.companyid', $userInfo['companyid']);
        $this->db->where('a.deptcurrentstatus != ', '2');
        $this->db->where('a.merchantid', $userInfo['id']);
        $this->db->join(KN_ORDER_ENQUIRY . ' AS oe', 'a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_USERS . ' AS u', 'a.mgmtid = u.id', 'left');
        $i = 0;
        foreach ($this->allReqColSearch as $item) {
            if ($_POST['search']['value']) {
                if (validateDate($_POST['search']['value'])) {
                    $_POST['search']['value'] = date('Y-m-d', strtotime($_POST['search']['value']));
                }
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->allReqColSearch) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->allReqColOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function getAllReqListDataTables()
    {
        $this->getAllReqListQry();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function allReqListCountFiltered()
    {
        $this->getAllReqListQry();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function allReqListCountAll()
    {
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ALLREQUEST . ' AS a');
        $this->db->where('a.companyid', $userInfo['companyid']);
        $this->db->where('a.merchantid', $userInfo['id']);
        $this->db->where('a.deptcurrentstatus != ', '2');
        $this->db->join(KN_ORDER_ENQUIRY . ' AS oe', 'a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_USERS . ' AS u', 'a.mgmtid = u.id', 'left');
        return $this->db->count_all_results();
    }

    /**/

    public function updateSampleRequestIndentDetails($ArrOtherData = array())
    {
        $this->db->insert(KN_SAMPLE_REQUEST, $ArrOtherData);
    }

    var $queueListColOrder = array('', 'oe.id', 'brandname', 'queueno', 'request_type_dept', '', 'formatDateCreated', 'formattedCutOffDt',
        'approvaltype', 'u.contactname', 'current_status',
        'formatDateUpdated', 'a.status');
    var $queueListColSearch = array('oe.isriorcode', 'brandname', 'queueno', 'request_type_dept', 'a.datecreated',
        'approvaltype', 'cutoffdatetime', 'a.status', 'a.dateupdated');

    public function queueListDataTables($VarRequestListTypeId = '')
    {
        $this->dataTablesQueueListQry($VarRequestListTypeId);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function queueListCountAll($VarRequestListTypeId = '') {
        $this->db->from(KN_ALLREQUEST . ' AS a');
        $this->db->where('a.companyid', $this->companyid);
        $this->db->where('a.status !=', '3');
        $this->db->where('a.queueno !=', '0');
        if (!empty($VarRequestListTypeId)) {
            $this->db->where('a.request_type_dept', $VarRequestListTypeId);
        }
        $this->db->join(KN_ORDER_ENQUIRY . ' AS oe', 'a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_USERS . ' AS u', 'a.mgmtid = u.id');
        $this->db->join(KN_USERS . ' AS mu', 'a.merchantid = mu.id');
        return $this->db->count_all_results();
    }

    public function queueListCountFiltered($VarRequestListTypeId = '') {
        $this->dataTablesQueueListQry($VarRequestListTypeId);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function dataTablesQueueListQry($VarRequestListTypeId = '') {
        if ($VarRequestListTypeId == "BOM") {
            $this->db->select('a.id,DATE_FORMAT(cutoffdatetime,"%d-%m-%Y %H:%i:%s") AS formattedCutOffDt,DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") AS 
        formatDateCreated,DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") AS  
        formatDateUpdated,a.status,a.mgmtcurrentstatus,a.approvaltype,a.queuecompletestatus,a.queueno,a.deptcurrentstatus,
        a.requestrefno,a.request_type_dept,a.current_status,oe.isriorcode,brandname,u.contactname as mgmt,mu.contactname as merchant,requirementforbom');
        } else {
            $this->db->select('a.id,DATE_FORMAT(cutoffdatetime,"%d-%m-%Y %H:%i:%s") AS formattedCutOffDt,DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") AS 
        formatDateCreated,DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") AS  
        formatDateUpdated,a.status,a.mgmtcurrentstatus,a.approvaltype,a.queuecompletestatus,a.queueno,a.deptcurrentstatus,
        a.requestrefno,a.request_type_dept,a.current_status,oe.isriorcode,brandname,jsondatagrid,u.contactname as mgmt,mu.contactname as merchant,
        requirementforbom');
        }
        $this->db->from(KN_ALLREQUEST . ' AS a');
        $this->db->where('a.companyid', $this->companyid);
        $this->db->where('a.status !=', '3');
        if (!empty($VarRequestListTypeId)) {
            $this->db->where('a.request_type_dept', $VarRequestListTypeId);
        }
        $this->db->where('a.queueno !=', '0');
        $this->db->join(KN_ORDER_ENQUIRY . ' AS oe', 'a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_USERS . ' AS u', 'a.mgmtid = u.id');
        $this->db->join(KN_USERS . ' AS mu', 'a.merchantid = mu.id');
        $this->db->order_by('a.qid', 'desc');
        $i = 0;
        foreach ($this->queueListColSearch as $item) {
            if (validateDate($_POST['search']['value'])) {
                $_POST['search']['value'] = date('Y-m-d', strtotime($_POST['search']['value']));
            }
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->queueListColSearch) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->queueListColOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    var $wipColOrder = array('', 'e.id', 'e.datecreated', 'brandname', 'stylenamerefno', 'orderenqrefno', 'poNoEnqRefNo', 'poQtySampleQty', 'pcs_set', 'shipmentSubDate',
        '', 'e.dateupdated', 'e.status');
    var $wipColSearch = array('e.isriorcode', 'e.datecreated', 'brandname', 'stylenamerefno', 'orderenqrefno', 'poNoEnqRefNo', 'poQtySampleQty', 'pcs_set',
        'e.dateupdated', 'shipmentSubDate', 'e.status');

    public function dataTablesWipQry() {
        $this->db->select('e.id,ids,DATE_FORMAT(e.datecreated,"%d-%m-%Y %H:%i:%s") AS 
        formattedDateCreated,DATE_FORMAT(e.dateupdated,"%d-%m-%Y %H:%i:%s") AS 
        formattedDateUpdated,e.isriorno,e.isriorcode,e.companyid,e.reqforisrior,e.stylenamerefno,orderenqrefno,
        e.exporderqty,e.orderstatus,pcs_set,e.merchantid,brandname,e.status,poNoEnqRefNo,
		poQtySampleQty,pcs_set,DATE_FORMAT(shipmentSubDate,"%d-%m-%Y") AS formattedShipmentSubDate');
        $this->db->from(KN_ORDER_ENQUIRY . ' AS e');
        $this->db->where('e.companyid', $this->companyid);
        $this->db->where('e.merchantid', $this->userid);
        $this->db->where('e.status !=', '3');
        $this->db->where('e.orderstatus', '2');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = e.brandId');
        $this->db->join(DELIVERY_SCHEDULE_WIP_LIST . ' AS del', 'del.referenceid = e.id');

        $i = 0;
        foreach ($this->wipColSearch as $item) {
            if ($_POST['search']['value']) {
                if (validateDate($_POST['search']['value'])) {
                    $_POST['search']['value'] = date('Y-m-d', strtotime($_POST['search']['value']));
                }
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->wipColSearch) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        //echo '<pre>'; print_r($_POST);
        if (isset($_POST['order'])) {
            $this->db->order_by($this->wipColOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            //echo '<pre>'; print_r($order);
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function getWipDataTables()
    {
        $this->dataTablesWipQry();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function wipCountFiltered() {
        $this->dataTablesWipQry();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function wipCountAll() {
        $this->db->from(KN_ORDER_ENQUIRY . ' AS e');
        $this->db->where('e.companyid', $this->companyid);
        $this->db->where('e.merchantid', $this->userid);
        $this->db->where('e.status !=', '3');
        $this->db->where('e.orderstatus', '2');
        //$this->db->order_by('del.poNoEnqRefNo', 'asc');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = e.brandId');
        $this->db->join(DELIVERY_SCHEDULE_WIP_LIST . ' AS del', 'del.referenceid = e.id');
        return $this->db->count_all_results();
    }

    /*    var $wipColOrder =  array('','e.id', 'e.datecreated','brandname','stylenamerefno','orderenqrefno','','','','','','e.dateupdated','e.status');
        var $wipColSearch = array('e.id', 'e.datecreated','brandname','stylenamerefno','orderenqrefno','e.dateupdated','e.status');

        public function dataTablesWipQry() {
            $this->db->select('e.id,DATE_FORMAT(e.datecreated,"%d-%m-%Y %H:%i:%s") AS
            formattedDateCreated,DATE_FORMAT(e.dateupdated,"%d-%m-%Y %H:%i:%s") AS
            formattedDateUpdated,e.isriorno,e.companyid,e.reqforisrior,e.stylenamerefno,orderenqrefno,
            e.exporderqty,e.orderstatus,e.pcsorset,e.brandbuyerid,e.isriorcode,e.merchantid,brandname,buyername,e.status');

            $this->db->from(KN_ORDER_ENQUIRY.' AS e');
            $this->db->where('e.companyid',$this->companyid);
            $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = e.brandbuyerid');
            $this->db->join(KN_MASTER_BUYER.' AS by','br.buyerid = by.id');
            $this->db->where('e.status !=','3');
            $this->db->where('e.orderstatus',2);
            $i = 0;
            foreach ($this->wipColSearch as $item) {
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
                    if(count($this->wipColSearch) - 1 == $i) {
                        $this->db->group_end();
                    }
                }
                $i++;
            }
            //echo '<pre>'; print_r($_POST);
            if(isset($_POST['order'])) {
                $this->db->order_by($this->wipColOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            }
            else if(isset($this->order)) {
                $order = $this->order;
                //echo '<pre>'; print_r($order);
                $this->db->order_by(key($order), $order[key($order)]);
            }
        }

        public function getWipDataTables() {
            $this->dataTablesWipQry();
            if($_POST['length'] != -1)
                $this->db->limit($_POST['length'], $_POST['start']);
            $query = $this->db->get();
            return $query->result();
        }

        public function wipCountFiltered() {
            $this->dataTablesWipQry();
            $query = $this->db->get();
            return $query->num_rows();
        }
        public function wipCountAll() {
            $this->db->from(KN_ORDER_ENQUIRY.' AS e');
            $this->db->where('e.companyid',$this->companyid);
            $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = e.brandbuyerid');
            $this->db->join(KN_MASTER_BUYER.' AS by','br.buyerid = by.id');
            $this->db->where('e.status !=','3');
            $this->db->where('e.orderstatus',2);
            return $this->db->count_all_results();
        }*/


    /*
     * Enquiry List
     * */
    public function getOrderEnquiryDataTables()
    {
        $this->getOrderEnquiryQry();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    var $orderEnquiryColOrder = array('', 'oe.orderenqrefno', 'oe.datecreated', 'brandname', 'pair',
        'enquirytype', 'stylenamerefno', 'confirmprice', 'currency', 'orderstatus', 'oe.dateupdated', 'oe.status');

    var $orderEnquiryColSearch = array('oe.orderenqrefno', 'oe.datecreated', 'enquirytype', 'brandname', 'order_status_value',
        'pair', 'stylenamerefno', 'confirmprice', 'order_status_value', 'oe.dateupdated', 'oe.status');

    public function getOrderEnquiryQry()
    {
        $this->db->select('oe.id,COUNT(c.id) AS total_comp,orderenqrefno,brandname,enquirytype,oe.currency,
            stylenamerefno,DATE_FORMAT(oe.datecreated,"%d-%m-%Y %H:%i:%s") as formattedDateCreated,
            DATE_FORMAT(oe.dateupdated,"%d-%m-%Y %H:%i:%s") as formattedDateUpdated,
            oe.status,confirmprice,oe.orderstatus,oe.isriorno,pair as isr_ior,order_status_value ');
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY . ' AS oe');
        $this->db->where('oe.merchantid', $userInfo['id']);
        $this->db->where('oe.companyid', $userInfo['companyid']);
        $this->db->where('oe.orderstatus !=', 2);
        $this->db->where('oe.status !=', 3);
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY . ' AS mo', 'mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS . ' AS cons', 'cons.eachkey = oe.reqforisrior');
        $this->db->join('tbl_components as c','c.enquiry_id = oe.id','LEFT');
        $this->db->group_by('oe.id');
        $i = 0;
        foreach ($this->orderEnquiryColSearch as $item) {
            if ($_POST['search']['value']) {
                if (validateDate($_POST['search']['value'])) {
                    $_POST['search']['value'] = date('Y-m-d', strtotime($_POST['search']['value']));
                }
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->orderEnquiryColSearch) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->orderEnquiryColOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function orderEnquiryListCountAll()
    {
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY . ' AS oe');
        $this->db->where('oe.companyid', $userInfo['companyid']);
        $this->db->where('oe.merchantid', $userInfo['id']);
        $this->db->where('oe.orderstatus !=', 2);
        $this->db->where('oe.status !=', 3);
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY . ' AS mo', 'mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS . ' AS cons', 'cons.eachkey = oe.reqforisrior');
        return $this->db->count_all_results();
    }

    public function orderEnquiryListCountFiltered()
    {
        $this->getOrderEnquiryQry();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getOrderEnquiryListt()
    {   // oe.datecreated as dateauthorized instead of this dateauthorized column selected from db,
		$this->db->select('oe.id,COUNT(c.id) AS total_comp,orderenqrefno,brandname,enquirytype,oe.currency,
            stylenamerefno,oe.datecreated as formattedDateCreated, oe.totalcombo, oe.totalcomponents,
            oe.dateupdated as formattedDateUpdated,oe.dateauthorized,oe.reqdatetime,
            oe.status,confirmprice,oe.orderstatus,oe.isriorno,pair as isr_ior,order_status_value');
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY . ' AS oe');
        $this->db->where('oe.merchantid', $userInfo['id']);
        //$this->db->where('oe.subscriberid', $userInfo['subscriber_id']);
        $this->db->where('oe.orderstatus !=', 2);
        $this->db->where('oe.draft_status =', 2);
        // $this->db->where('oe.status !=', 3);
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY . ' AS mo', 'mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS . ' AS cons', 'cons.eachkey = oe.reqforisrior');
        $this->db->join('tbl_components as c','c.enquiry_id = oe.id','LEFT');
        $this->db->group_by('oe.id');
        $this->db->order_by('oe.id DESC');
	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
    }
    public function getEnquiryIORListt()
    {   // oe.datecreated as dateauthorized instead of this dateauthorized column selected from db,
		$this->db->select('oe.id,COUNT(c.id) AS total_comp,orderenqrefno,brandname,enquirytype,oe.currency,
            stylenamerefno,oe.datecreated as formattedDateCreated, oe.totalcombo, oe.totalcomponents,
            oe.dateupdated as formattedDateUpdated, oe.dateauthorized,oe.reqdatetime,
            oe.status,confirmprice,oe.orderstatus,oe.isriorno,pair as isr_ior,order_status_value');
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY . ' AS oe');
        $this->db->where('oe.merchantid', $userInfo['id']);
        $this->db->where('oe.companyid', $userInfo['companyid']);
        $this->db->where('oe.orderstatus !=', 2);
        $this->db->where('oe.reqforisrior =', 2);
        $this->db->where('oe.draft_status =', 2);
        // $this->db->where('oe.status !=', 3);
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY . ' AS mo', 'mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS . ' AS cons', 'cons.eachkey = oe.reqforisrior');
        $this->db->join('tbl_components as c','c.enquiry_id = oe.id','LEFT');
        $this->db->group_by('oe.id');
        $this->db->order_by('oe.id DESC');
	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
    }
    public function getEnquiryISRListt()
    {   // oe.datecreated as dateauthorized instead of this dateauthorized column selected from db,
		$this->db->select('oe.id,COUNT(c.id) AS total_comp,orderenqrefno,brandname,enquirytype,oe.currency,
            stylenamerefno,oe.datecreated as formattedDateCreated, oe.totalcombo, oe.totalcomponents,
            oe.dateupdated as formattedDateUpdated, oe.dateauthorized,oe.reqdatetime,
            oe.status,confirmprice,oe.orderstatus,oe.isriorno,pair as isr_ior,order_status_value');
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY . ' AS oe');
        $this->db->where('oe.merchantid', $userInfo['id']);
        $this->db->where('oe.companyid', $userInfo['companyid']);
        $this->db->where('oe.orderstatus !=', 2);
        $this->db->where('oe.reqforisrior =', 1);
        $this->db->where('oe.draft_status =', 2);
        // $this->db->where('oe.status !=', 3);
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY . ' AS mo', 'mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS . ' AS cons', 'cons.eachkey = oe.reqforisrior');
        $this->db->join('tbl_components as c','c.enquiry_id = oe.id','LEFT');
        $this->db->group_by('oe.id');
        $this->db->order_by('oe.id DESC');
	    $query = $this->db->get();
	    $result = $query->result();
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
    
    public function searchEnquiryListt($data)
    {   // oe.datecreated as dateauthorized instead of this dateauthorized column selected from db,
        $this->db->select('oe.id,COUNT(c.id) AS total_comp,orderenqrefno,brandname,enquirytype,oe.currency,
            stylenamerefno,oe.datecreated as formattedDateCreated, oe.totalcombo, oe.totalcomponents,
            oe.dateupdated as formattedDateUpdated, oe.dateauthorized,oe.reqdatetime,
            oe.status,confirmprice,oe.orderstatus,oe.isriorno,pair as isr_ior,order_status_value');
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY . ' AS oe');
        $this->db->where('oe.merchantid', $userInfo['id']);
        $this->db->where('oe.companyid', $userInfo['companyid']);
        $this->db->where('oe.orderstatus !=', 2);
        $this->db->where('oe.status !=', 3);
        $this->db->where('oe.draft_status =', 2);
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY . ' AS mo', 'mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS . ' AS cons', 'cons.eachkey = oe.reqforisrior');
        $this->db->join('tbl_components as c','c.enquiry_id = oe.id','LEFT');
        $this->db->group_by('oe.id');
        $this->db->order_by('oe.id DESC');
        $this->db->like('oe.orderenqrefno', $data["order_enq_ref_no"]);
        // $this->db->like('oe.stylenamerefno', $data["stylenamerefno"]);
        $this->db->like('oe.enquirytype', $data["enquirytype"]);
        if($data['datecreatedfrom'] != '' && $data['datecreatedto'] != '')
        {
            $startDate = $this->changeReverseDate($data['datecreatedfrom']);
            $endDate = $this->changeReverseDate($data['datecreatedto']);
            $startDate = $startDate. ' ' . '00:00:00';
            $endDate = $endDate. ' ' . '23:59:59';
            //commeted by me for fetching agianst reqest date & time
            // $this->db->where('oe.datecreated >=', $startDate);
            // $this->db->where('oe.datecreated <=', $endDate);
            $this->db->where('oe.reqdatetime >=', $startDate);
            $this->db->where('oe.reqdatetime <=', $endDate);
        }
        // if($data['dateauthorizedfrom'] != '' && $data['dateauthorizedto'] != '')
        // {
        //     $sDate = $data['dateauthorizedfrom']. ' ' . '00:00:00';
        //     $eDate = $data['dateauthorizedto']. ' ' . '23:59:59';
        //     $this->db->where('oe.dateauthorized >=', $sDate);
        //     $this->db->where('oe.dateauthorized <=', $eDate);
        // }
        if($data['totalcombo'] != '')
        {
            $this->db->where('oe.totalcombo', $data['totalcombo']);
        }
        if($data['totalcomponents'] != '')
        {
            $this->db->where('oe.totalcomponents', $data['totalcomponents']);
        }
        if($data['brandId'] != '')
        {
            $this->db->where('oe.brandId', $data['brandId']);
        }
        if($data['status'] != '')
        {
            $this->db->where('oe.status', $data['status']);
        }
        if($data['ior'] != '' ){

            $this->db->where('oe.reqforisrior', 2);
        }
        if($data['isr'] != '' ){
            $this->db->where('oe.reqforisrior', 1);
        }
        // if($data['status'] != '' && $data['isr'] != '' )
        // {   
        //     $this->db->where('oe.status', $data['status']);
        //     $this->db->where('oe.reqforisrior', 1);
            
        // }elseif($data['status'] != '' && $data['ior'] != '' ){
           
        //     $this->db->where('oe.status', $data['status']);
        //     $this->db->where('oe.reqforisrior', 2);
        // }elseif($data['status'] == '' && $data['isr'] != '' ){

        //     $this->db->where('oe.reqforisrior', 1);
        // }elseif($data['status'] == '' && $data['ior'] != '' ){
           
            
        //     $this->db->where('oe.reqforisrior', 2);
        // }

	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
    }
    public function searchEnquiryIORListt($data)
    {   
        // oe.datecreated as dateauthorized instead of this dateauthorized column selected from db,
        $this->db->select('oe.id,COUNT(c.id) AS total_comp,orderenqrefno,brandname,enquirytype,oe.currency,
            stylenamerefno,oe.datecreated as formattedDateCreated, oe.totalcombo, oe.totalcomponents,
            oe.dateupdated as formattedDateUpdated, oe.dateauthorized,oe.reqdatetime,
            oe.status,confirmprice,oe.orderstatus,oe.isriorno,pair as isr_ior,order_status_value');
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY . ' AS oe');
        $this->db->where('oe.merchantid', $userInfo['id']);
        $this->db->where('oe.companyid', $userInfo['companyid']);
        $this->db->where('oe.orderstatus !=', 2);
        $this->db->where('oe.status !=', 3);
        $this->db->where('oe.reqforisrior =', 2);
        $this->db->where('oe.draft_status =', 2);
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY . ' AS mo', 'mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS . ' AS cons', 'cons.eachkey = oe.reqforisrior');
        $this->db->join('tbl_components as c','c.enquiry_id = oe.id','LEFT');
        $this->db->group_by('oe.id');
        $this->db->order_by('oe.id DESC');
        $this->db->like('oe.orderenqrefno', $data["order_enq_ref_no"]);
        // $this->db->like('oe.stylenamerefno', $data["stylenamerefno"]);
        $this->db->like('oe.enquirytype', $data["enquirytype"]);
        if($data['datecreatedfrom'] != '' && $data['datecreatedto'] != '')
        {
            $startDate = $this->changeReverseDate($data['datecreatedfrom']);
            $endDate = $this->changeReverseDate($data['datecreatedto']);
            $startDate = $startDate. ' ' . '00:00:00';
            $endDate = $endDate. ' ' . '23:59:59';
            // commeted by me for fetching agianst reqest date & time
            // $this->db->where('oe.datecreated >=', $startDate);
            // $this->db->where('oe.datecreated <=', $endDate);
            $this->db->where('oe.reqdatetime >=', $startDate);
            $this->db->where('oe.reqdatetime <=', $endDate);
        }
        // if($data['dateauthorizedfrom'] != '' && $data['dateauthorizedto'] != '')
        // {
        //     $sDate = $data['dateauthorizedfrom']. ' ' . '00:00:00';
        //     $eDate = $data['dateauthorizedto']. ' ' . '23:59:59';
        //     $this->db->where('oe.dateauthorized >=', $sDate);
        //     $this->db->where('oe.dateauthorized <=', $eDate);
        // }
        if($data['totalcombo'] != '')
        {
            $this->db->where('oe.totalcombo', $data['totalcombo']);
        }
        if($data['totalcomponents'] != '')
        {
            $this->db->where('oe.totalcomponents', $data['totalcomponents']);
        }
        if($data['brandId'] != '')
        {
            $this->db->where('oe.brandId', $data['brandId']);
        }
        if($data['status'] != '')
        {
            $this->db->where('oe.status', $data['status']);
        }

	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
    }
    public function searchEnquiryISRListt($data)
    {   
        // oe.datecreated as dateauthorized instead of this dateauthorized column selected from db,
        $this->db->select('oe.id,COUNT(c.id) AS total_comp,orderenqrefno,brandname,enquirytype,oe.currency,
            stylenamerefno,oe.datecreated as formattedDateCreated, oe.totalcombo, oe.totalcomponents,
            oe.dateupdated as formattedDateUpdated, oe.dateauthorized,oe.reqdatetime,
            oe.status,confirmprice,oe.orderstatus,oe.isriorno,pair as isr_ior,order_status_value');
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY . ' AS oe');
        $this->db->where('oe.merchantid', $userInfo['id']);
        $this->db->where('oe.companyid', $userInfo['companyid']);
        $this->db->where('oe.orderstatus !=', 2);
        $this->db->where('oe.status !=', 3);
        $this->db->where('oe.reqforisrior =', 1);
        $this->db->where('oe.draft_status =', 2);
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY . ' AS mo', 'mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS . ' AS cons', 'cons.eachkey = oe.reqforisrior');
        $this->db->join('tbl_components as c','c.enquiry_id = oe.id','LEFT');
        $this->db->group_by('oe.id');
        $this->db->order_by('oe.id DESC');
        $this->db->like('oe.orderenqrefno', $data["order_enq_ref_no"]);
        // $this->db->like('oe.stylenamerefno', $data["stylenamerefno"]);
        $this->db->like('oe.enquirytype', $data["enquirytype"]);
        if($data['datecreatedfrom'] != '' && $data['datecreatedto'] != '')
        {
            $startDate = $this->changeReverseDate($data['datecreatedfrom']);
            $endDate = $this->changeReverseDate($data['datecreatedto']);
            $startDate = $startDate. ' ' . '00:00:00';
            $endDate = $endDate. ' ' . '23:59:59';
            // commeted by me for fetching agianst reqest date & time
            // $this->db->where('oe.datecreated >=', $startDate);
            // $this->db->where('oe.datecreated <=', $endDate);
            $this->db->where('oe.reqdatetime >=', $startDate);
            $this->db->where('oe.reqdatetime <=', $endDate);
        }
        // if($data['dateauthorizedfrom'] != '' && $data['dateauthorizedto'] != '')
        // {
        //     $sDate = $data['dateauthorizedfrom']. ' ' . '00:00:00';
        //     $eDate = $data['dateauthorizedto']. ' ' . '23:59:59';
        //     $this->db->where('oe.dateauthorized >=', $sDate);
        //     $this->db->where('oe.dateauthorized <=', $eDate);
        // }
        if($data['totalcombo'] != '')
        {
            $this->db->where('oe.totalcombo', $data['totalcombo']);
        }
        if($data['totalcomponents'] != '')
        {
            $this->db->where('oe.totalcomponents', $data['totalcomponents']);
        }
        if($data['brandId'] != '')
        {
            $this->db->where('oe.brandId', $data['brandId']);
        }
        if($data['status'] != '')
        {
            $this->db->where('oe.status', $data['status']);
        }

	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
    }
    public function changeReverseDate($date)
    {
        $array=explode("-",$date);
        $rev=array_reverse($array);
        $date=implode("-",$rev);
        return $date;
    }
    
    public function submitAuthRequestt($data)
    {

        $id = $data['id'];
        $orderStatus = $data['orderStatus'];
        $status = $data['status'];
        $reqdatetime = date('Y-m-d H:i:s');

        $this->db->where('orderStatus', $orderStatus);
        $this->db->where('status', $status);
        $this->db->where('id', $id);
        $this->db->from(KN_ORDER_ENQUIRY);
        $get_query = $this->db->get();
        $detail = $get_query->result();
        if(sizeof($detail) > 0) {
            $result["status"] = "success";
            $result["statusCode"] = "203";
            $result["message"] = "Your enquiry is already sent to authorization";
        }
        else {
            $this->db->where('id', $id);
            $this->db->update(KN_ORDER_ENQUIRY,($orderStatus==4)?array('orderStatus' => $orderStatus,'mgmtid'=>'','status' => $status,'reqdatetime'=>$reqdatetime,'dateauthorized'=>''):array('orderStatus' => $orderStatus, 'status' => $status,'reqdatetime'=>$reqdatetime));        
            if ($this->db->affected_rows() == '1') {
                $result["status"] = "success";
                $result["statusCode"] = "200";
                $result["message"] = "Authorized Successfully";
            }
            else {
                $result["status"] = "fail";
                $result["statusCode"] = "400";
                $result["message"] = "Error in authorizing";
            }
        }
        return $result;
    }

    public function getSeperateOrderEnquiryListt($data)
    {   // oe.datecreated as dateauthorized instead of this dateauthorized column selected from db,
        $id = $data['id'];
		$this->db->select('oe.*,oe.id,COUNT(c.id) AS total_comp,orderenqrefno,brandname,br.buyername,br.country,enquirytype,oe.currency,oe.enquirydate,
            stylenamerefno,oe.datecreated as formattedDateCreated, oe.totalcombo, oe.totalcomponents,
            oe.dateupdated as formattedDateUpdated, oe.dateauthorized,oe.reqdatetime,oe.merchantnote,
            oe.status,oe.draft_status,confirmprice,oe.orderstatus,oe.isriorno,pair as isr_ior,order_status_value, u.contactname as mer_name,mgmusr.contactname as authorizedby');
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY . ' AS oe');
        $this->db->where('oe.id', $id);
        $this->db->where('oe.merchantid', $userInfo['id']);
        $this->db->where('oe.companyid', $userInfo['companyid']);
        $this->db->where('oe.orderstatus !=', 2);
        $this->db->where('oe.status !=', 3);
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId','LEFT');
        $this->db->join(KN_MASTER_MODEOFENQUIRY . ' AS mo', 'mo.id = oe.modeofenquiry','LEFT');
        $this->db->join(KN_CONSTANTS . ' AS cons', 'cons.eachkey = oe.reqforisrior','LEFT');
        $this->db->join(KN_USERS . ' AS u', 'u.id = oe.merchantid','LEFT');
        $this->db->join(KN_USERS . ' AS mgmusr', 'mgmusr.id = oe.mgmtid','LEFT');
        $this->db->join('tbl_components as c','c.enquiry_id = oe.id','LEFT');
        $this->db->group_by('oe.id');
        $this->db->order_by('oe.id DESC');
	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
    }

    public function getWIPListt()
    {
        $this->db->select('e.id,ids,e.datecreated AS 
        formattedDateCreated,e.dateupdated AS 
        formattedDateUpdated,e.dateauthorized,e.isriorno,e.isriorcode,e.companyid,e.reqforisrior,e.stylenamerefno,orderenqrefno,
        e.exporderqty,e.orderstatus,pcs_set,e.merchantid,brandname,e.status,poNoEnqRefNo,pcs_set,shipmentSubDate AS formattedShipmentSubDate, e.pcsorset');
        $this->db->from(KN_ORDER_ENQUIRY . ' AS e');
        $this->db->where('e.companyid', $this->companyid);
        $this->db->where('e.merchantid', $this->userid);
        $this->db->where('e.status !=', '3');
        $this->db->where('e.orderstatus', '2');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = e.brandId');
        $this->db->join(DELIVERY_SCHEDULE_WIP_LIST . ' AS del', 'del.referenceid = e.id');
        $this->db->order_by('e.datecreated DESC');
	    $query = $this->db->get();
	    $result = $query->result_array();

        $po_sql = "SELECT a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, b.pcs_set, sum(a.po_qty) as tol_po_qty
                    FROM tbl_oe_po_wise as a 
                    INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id 
                    GROUP BY a.pono_enq_refno, a.enquiry_id";
        $po_data = $this->db->query($po_sql)->result_array();
        
        foreach ($result as $key => $res) {
            $result[$key]['poQtySampleRefQty'] = '';
            $result[$key]['poQtySampleQty'] = '';
            $result[$key]['poShipmentDate'] = '';
            $result[$key]['poPcsSet'] = '';
            foreach ($po_data as $key2 => $value) {
                if($value['enquiry_id'] == $res['id'])
                {
                    $po_ship_date = date('d/m/Y g:i A', strtotime($value['po_shipment_date']));
                    
                    if(!isset($result[$key]['poQtySampleRefQty'])) {
                        $result[$key]['poQtySampleRefQty'] = $value['pono_enq_refno'];
                        $result[$key]['poQtySampleQty'] = $value['tol_po_qty'];
                        $result[$key]['poShipmentDate'] = $po_ship_date;
                        $result[$key]['poPcsSet'] = $value['pcs_set'];
                    }
                    else {
                        $result[$key]['poQtySampleRefQty'] = $result[$key]['poQtySampleRefQty'].' <br /> '.$value['pono_enq_refno'];
                        $result[$key]['poQtySampleQty'] = $result[$key]['poQtySampleQty'].' <br /> '.$value['tol_po_qty'];
                        $result[$key]['poShipmentDate'] = $result[$key]['poShipmentDate'].' <br /> '.$po_ship_date;
                        $result[$key]['poPcsSet'] = $result[$key]['poPcsSet'].' <br /> '.$value['pcs_set'];
                    }
                }
            }
        }

        return $result;
    }
    
    public function searchWIPListt111($data)
    {

        $subquery = $this->db
    ->select('a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, SUM(a.po_qty) AS tol_po_qty')
    ->from('tbl_oe_po_wise as a')
    ->join('tbl_oe_combo_color as b', 'a.combo_color_id = b.combo_color_id')
    ->group_by(['a.pono_enq_refno', 'a.enquiry_id'])
    ->get_compiled_select();

         $this->db->select('
    e.id, ids, e.datecreated AS formattedDateCreated, e.dateupdated AS formattedDateUpdated, e.dateauthorized,e.orderenqrefno,  
    e.isriorno, e.isriorcode, e.companyid, e.reqforisrior, e.stylenamerefno,
    e.exporderqty, e.orderstatus, pcs_set, e.merchantid, brandname, e.status, poNoEnqRefNo,
    pcs_set, shipmentSubDate AS formattedShipmentSubDate, e.pcsorset,
    po_data.pono_enq_refno, po_data.po_shipment_date, po_data.tol_po_qty
');

$this->db->from(KN_ORDER_ENQUIRY . ' AS e');
$this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = e.brandId');
$this->db->join(DELIVERY_SCHEDULE_WIP_LIST . ' AS del', 'del.referenceid = e.id');

// Join on subquery as po_data
$this->db->join("($subquery) AS po_data", 'po_data.enquiry_id = e.id', 'left'); 

// Where conditions
$this->db->where('e.companyid', $this->companyid);
$this->db->where('e.merchantid', $this->userid);
$this->db->where('e.status !=', '3');
$this->db->where('e.orderstatus', '2');
$this->db->like('e.isriorcode', '14'); // You can adjust like %14% or ESCAPE here if needed

// Date range filter on po_shipment_date (adjust your $startDate and $endDate accordingly)
$startDate = '2025-09-11 00:00:00';
$endDate = '2025-11-13 23:59:59';

$this->db->where('po_data.po_shipment_date >=', $startDate);
$this->db->where('po_data.po_shipment_date <=', $endDate);

$this->db->order_by('e.datecreated DESC');


        // echo $this->db->get_compiled_select();
      //echo "<pre>";
//echo $this->db->get_compiled_select();
//echo "</pre>";
//exit;
	    $query = $this->db->get();
	    $result = $query->result_array();

        
        if($data['po_sam_ref_no'] != '')
        {
            $po_sql = "SELECT a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, b.pcs_set, sum(a.po_qty) as tol_po_qty
                    FROM tbl_oe_po_wise as a 
                    INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id 
                    WHERE  a.pono_enq_refno='$data[po_sam_ref_no]' GROUP BY a.pono_enq_refno, a.enquiry_id";
            $po_data = $this->db->query($po_sql)->result_array();
        }else if($data['po_sam_qty'] != '')
        {   
             $po_sql = "SELECT * FROM (SELECT a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, b.pcs_set, sum(a.po_qty) as tol_po_qty
                    FROM tbl_oe_po_wise as a 
                    INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id 
                     GROUP BY a.pono_enq_refno, a.enquiry_id) as aa where aa.tol_po_qty like ('%$data[po_sam_qty]')";
                    $po_data = $this->db->query($po_sql)->result_array();
        }else{
            $po_sql = "SELECT a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, b.pcs_set, sum(a.po_qty) as tol_po_qty
                    FROM tbl_oe_po_wise as a 
                    INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id 
                     GROUP BY a.pono_enq_refno, a.enquiry_id";
                    $po_data = $this->db->query($po_sql)->result_array();
           
        }
        $results=[];
       if($data['po_sam_ref_no'] != ''){
          
           foreach ($po_data as $key => $res) {
                
                
                  $keys = array_search($res['enquiry_id'], $result);
                    if (($key) !== false)
                    {
                        #deleting the key found
                        unset($result[$keys]);
                    }
                
            }
            
       }
      // var_dump($result);
        //$po_data = $this->db->query($po_sql)->result_array();
       // var_dump($po_data);
        //echo $po_data = $this->db->query($po_sql)->result_array();
        
        foreach ($result as $key => $res) {
            $result[$key]['poQtySampleRefQty'] = '';
            $result[$key]['poQtySampleQty'] = '';
            $result[$key]['poShipmentDate'] = '';
            $result[$key]['poPcsSet'] = '';
            foreach ($po_data as $key2 => $value) {
                if($value['enquiry_id'] == $res['id'])
                {
                    $po_ship_date = date('d/m/Y g:i A', strtotime($value['po_shipment_date']));
                    
                    if(!isset($result[$key]['poQtySampleRefQty'])) {
                        $result[$key]['poQtySampleRefQty'] = $value['pono_enq_refno'];
                        $result[$key]['poQtySampleQty'] = $value['tol_po_qty'];
                        $result[$key]['poShipmentDate'] = $po_ship_date;
                        $result[$key]['poPcsSet'] = $value['pcs_set'];
                    }
                    else {
                        $result[$key]['poQtySampleRefQty'] = $result[$key]['poQtySampleRefQty'].' <br /> '.$value['pono_enq_refno'];
                        $result[$key]['poQtySampleQty'] = $result[$key]['poQtySampleQty'].' <br /> '.$value['tol_po_qty'];
                        $result[$key]['poShipmentDate'] = $result[$key]['poShipmentDate'].' <br /> '.$po_ship_date;
                        $result[$key]['poPcsSet'] = $result[$key]['poPcsSet'].' <br /> '.$value['pcs_set'];
                    }
                }
               
            }
            
        }
        
	    ///$query = $this->db->get();
	    // echo $this->db->get_compiled_select();
	    //$result = $query->result();
	    return $result;
    }


     public function searchWIPListt($data)
     {
          $subquery = $this->db
    ->select('a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, SUM(a.po_qty) AS tol_po_qty')
    ->from('tbl_oe_po_wise as a')
    ->join('tbl_oe_combo_color as b', 'a.combo_color_id = b.combo_color_id')
    ->group_by(['a.pono_enq_refno', 'a.enquiry_id'])
    ->get_compiled_select();

        $this->db->select('e.id,ids,e.datecreated AS 
        formattedDateCreated,e.dateupdated AS 
        formattedDateUpdated,e.isriorno,e.isriorcode,e.companyid,e.reqforisrior,e.stylenamerefno,orderenqrefno,
        e.exporderqty,e.orderstatus,pcs_set,e.merchantid,brandname,e.status,poNoEnqRefNo,pcs_set,shipmentSubDate AS formattedShipmentSubDate, e.pcsorset');
        $this->db->from(KN_ORDER_ENQUIRY . ' AS e');
        $this->db->where('e.companyid', $this->companyid);
        $this->db->where('e.merchantid', $this->userid);
        $this->db->where('e.status !=', '3');
        $this->db->where('e.orderstatus', '2');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = e.brandId');
        $this->db->join(DELIVERY_SCHEDULE_WIP_LIST . ' AS del', 'del.referenceid = e.id');
        $this->db->join("($subquery) AS po_data", 'po_data.enquiry_id = e.id', 'left'); 
        $this->db->order_by('e.datecreated DESC');
        if($data['wip_ref_no'] != '')
        {
        $this->db->like('e.isriorcode', $data["wip_ref_no"]);
        }
         if($data['style_ref_no'] != '')
        {
        $this->db->like('e.stylenamerefno', $data["style_ref_no"]);
        }
        if($data['shipmentFrom'] != '' && $data['shipmentTo'] != '')
        {
            $startDate = $this->changeReverseDate($data['shipmentFrom']);
            $endDate = $this->changeReverseDate($data['shipmentTo']);

            //$startDate = $startDate. ' ' . '00:00:00';
            //$endDate = $endDate. ' ' . '23:59:59';
            // $this->db->where('shipmentSubDate >=', $startDate);
            // $this->db->where('shipmentSubDate <=', $endDate);
            //$startDate = '2025-09-11 00:00:00';
            //$endDate = '2025-11-13 23:59:59';

$this->db->where('po_data.po_shipment_date >=', $startDate);
$this->db->where('po_data.po_shipment_date <=', $endDate);

        }
        if($data['brandId'] != '')
        {
            $this->db->where('e.brandId', $data['brandId']);
        }
        if($data['status'] != '')
        {
            $this->db->where('e.status', $data['status']);
        }
        if($data['ior'] != '' ){

            $this->db->where('e.reqforisrior', 2);
        }
        if($data['isr'] != '' ){
            $this->db->where('e.reqforisrior', 1);
        }
        // echo $this->db->get_compiled_select();
        //echo $this->db->get_compiled_select();
      //echo "<pre>";
//echo $this->db->get_compiled_select();
//echo "</pre>";
//exit;
	    $query = $this->db->get();
	    $result = $query->result_array();

        
        if($data['po_sam_ref_no'] != '')
        {
            $po_sql = "SELECT a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, b.pcs_set, sum(a.po_qty) as tol_po_qty
                    FROM tbl_oe_po_wise as a 
                    INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id 
                    WHERE  a.pono_enq_refno='$data[po_sam_ref_no]' GROUP BY a.pono_enq_refno, a.enquiry_id";
            $po_data = $this->db->query($po_sql)->result_array();
        }else if($data['po_sam_qty'] != '')
        {  
             $po_sql = "SELECT * FROM (SELECT a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, b.pcs_set, sum(a.po_qty) as tol_po_qty
                    FROM tbl_oe_po_wise as a 
                    INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id 
                  GROUP BY a.pono_enq_refno, a.enquiry_id) as aa where aa.tol_po_qty like ('%$data[po_sam_qty]')";
                    $po_data = $this->db->query($po_sql)->result_array();
        }else{
            $po_sql = "SELECT a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, b.pcs_set, sum(a.po_qty) as tol_po_qty
                    FROM tbl_oe_po_wise as a 
                    INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id 
                    GROUP BY a.pono_enq_refno, a.enquiry_id";
                    $po_data = $this->db->query($po_sql)->result_array();
           
        }
        $results=[];
       if($data['po_sam_ref_no'] != ''){
          
           foreach ($po_data as $key => $res) {
                
                
                  $keys = array_search($res['enquiry_id'], $result);
                    if (($key) !== false)
                    {
                        #deleting the key found
                        unset($result[$keys]);
                    }
                
            }
            
       }
        // var_dump($result);
        //$po_data = $this->db->query($po_sql)->result_array();
        // var_dump($po_data);
        //echo $po_data = $this->db->query($po_sql)->result_array();
        
        foreach ($result as $key => $res) {
            $result[$key]['poQtySampleRefQty'] = '';
            $result[$key]['poQtySampleQty'] = '';
            $result[$key]['poShipmentDate'] = '';
            $result[$key]['poPcsSet'] = '';
            foreach ($po_data as $key2 => $value) {
                if($value['enquiry_id'] == $res['id'])
                {
                    $po_ship_date = date('d/m/Y g:i A', strtotime($value['po_shipment_date']));
                    
                    if(!isset($result[$key]['poQtySampleRefQty'])) {
                        $result[$key]['poQtySampleRefQty'] = $value['pono_enq_refno'];
                        $result[$key]['poQtySampleQty'] = $value['tol_po_qty'];
                        $result[$key]['poShipmentDate'] = $po_ship_date;
                        $result[$key]['poPcsSet'] = $value['pcs_set'];
                    }
                    else {
                        $result[$key]['poQtySampleRefQty'] = $result[$key]['poQtySampleRefQty'].' <br /> '.$value['pono_enq_refno'];
                        $result[$key]['poQtySampleQty'] = $result[$key]['poQtySampleQty'].' <br /> '.$value['tol_po_qty'];
                        $result[$key]['poShipmentDate'] = $result[$key]['poShipmentDate'].' <br /> '.$po_ship_date;
                        $result[$key]['poPcsSet'] = $result[$key]['poPcsSet'].' <br /> '.$value['pcs_set'];
                    }
                }
               
            }
            
        }
        
	    ///$query = $this->db->get();
	    // echo $this->db->get_compiled_select();
	    //$result = $query->result();
	    return $result;
    }
    public function searchWIPListtbk($data)
    {
          $this->db->select('e.id,ids,e.datecreated AS 
        formattedDateCreated,e.dateupdated AS 
        formattedDateUpdated,e.isriorno,e.isriorcode,e.companyid,e.reqforisrior,e.stylenamerefno,orderenqrefno,
        e.exporderqty,e.orderstatus,pcs_set,e.merchantid,brandname,e.status,poNoEnqRefNo,pcs_set,shipmentSubDate AS formattedShipmentSubDate, e.pcsorset');
        $this->db->from(KN_ORDER_ENQUIRY . ' AS e');
        $this->db->where('e.companyid', $this->companyid);
        $this->db->where('e.merchantid', $this->userid);
        $this->db->where('e.status !=', '3');
        $this->db->where('e.orderstatus', '2');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = e.brandId');
        $this->db->join(DELIVERY_SCHEDULE_WIP_LIST . ' AS del', 'del.referenceid = e.id');
        $this->db->order_by('e.datecreated DESC');
        if($data['wip_ref_no'] != '')
        {
        $this->db->like('e.isriorcode', $data["wip_ref_no"]);
        }
         if($data['style_ref_no'] != '')
        {
        $this->db->like('e.stylenamerefno', $data["style_ref_no"]);
        }
        if($data['shipmentFrom'] != '' && $data['shipmentTo'] != '')
        {
            $startDate = $this->changeReverseDate($data['shipmentFrom']);
            $endDate = $this->changeReverseDate($data['shipmentTo']);
            $startDate = $startDate. ' ' . '00:00:00';
            $endDate = $endDate. ' ' . '23:59:59';
            $this->db->where('shipmentSubDate >=', $startDate);
            $this->db->where('shipmentSubDate <=', $endDate);
        }
        if($data['brandId'] != '')
        {
            $this->db->where('e.brandId', $data['brandId']);
        }
        // echo $this->db->get_compiled_select();
	    $query = $this->db->get();
	    $result = $query->result_array();

        
        if($data['po_sam_ref_no'] != '')
        {
            $po_sql = "SELECT a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, b.pcs_set, sum(a.po_qty) as tol_po_qty
                    FROM tbl_oe_po_wise as a 
                    INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id 
                    WHERE  a.pono_enq_refno='$data[po_sam_ref_no]' GROUP BY a.pono_enq_refno, a.enquiry_id";
            $po_data = $this->db->query($po_sql)->result_array();
        }else if($data['po_sam_qty'] != '')
        {   
             $po_sql = "SELECT * FROM (SELECT a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, b.pcs_set, sum(a.po_qty) as tol_po_qty
                    FROM tbl_oe_po_wise as a 
                    INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id 
                    GROUP BY a.pono_enq_refno, a.enquiry_id) as aa where aa.tol_po_qty like ('%$data[po_sam_qty]')";
                    $po_data = $this->db->query($po_sql)->result_array();
        }else{
            $po_sql = "SELECT a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, b.pcs_set, sum(a.po_qty) as tol_po_qty
                    FROM tbl_oe_po_wise as a 
                    INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id 
                     GROUP BY a.pono_enq_refno, a.enquiry_id";
                    $po_data = $this->db->query($po_sql)->result_array();
           
        }
        
        //$po_data = $this->db->query($po_sql)->result_array();
       // var_dump($po_data);
        //echo $po_data = $this->db->query($po_sql)->result_array();
        
        foreach ($result as $key => $res) {
            $result[$key]['poQtySampleRefQty'] = '';
            $result[$key]['poQtySampleQty'] = '';
            $result[$key]['poShipmentDate'] = '';
            $result[$key]['poPcsSet'] = '';
            foreach ($po_data as $key2 => $value) {
                if($value['enquiry_id'] == $res['id'])
                {
                    $po_ship_date = date('d/m/Y g:i A', strtotime($value['po_shipment_date']));
                    
                    if(!isset($result[$key]['poQtySampleRefQty'])) {
                        $result[$key]['poQtySampleRefQty'] = $value['pono_enq_refno'];
                        $result[$key]['poQtySampleQty'] = $value['tol_po_qty'];
                        $result[$key]['poShipmentDate'] = $po_ship_date;
                        $result[$key]['poPcsSet'] = $value['pcs_set'];
                    }
                    else {
                        $result[$key]['poQtySampleRefQty'] = $result[$key]['poQtySampleRefQty'].' <br /> '.$value['pono_enq_refno'];
                        $result[$key]['poQtySampleQty'] = $result[$key]['poQtySampleQty'].' <br /> '.$value['tol_po_qty'];
                        $result[$key]['poShipmentDate'] = $result[$key]['poShipmentDate'].' <br /> '.$po_ship_date;
                        $result[$key]['poPcsSet'] = $result[$key]['poPcsSet'].' <br /> '.$value['pcs_set'];
                    }
                }
               
            }
            
        }
        
	    ///$query = $this->db->get();
	    // echo $this->db->get_compiled_select();
	    //$result = $query->result();
	    return $result;
    }
    
    public function searchWIPListt_backup($data)
    {
        $this->db->select('e.id,ids,e.datecreated AS 
        formattedDateCreated,e.dateupdated AS 
        formattedDateUpdated,e.isriorno,e.isriorcode,e.companyid,e.reqforisrior,e.stylenamerefno,orderenqrefno,
        e.exporderqty,e.orderstatus,pcs_set,e.merchantid,brandname,e.status,poNoEnqRefNo,
		poQtySampleQty,pcs_set,shipmentSubDate AS formattedShipmentSubDate, e.pcsorset');
        $this->db->from(KN_ORDER_ENQUIRY . ' AS e');
        $this->db->where('e.companyid', $this->companyid);
        $this->db->where('e.merchantid', $this->userid);
        $this->db->where('e.status !=', '3');
        $this->db->where('e.orderstatus', '2');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = e.brandId');
        $this->db->join(DELIVERY_SCHEDULE_WIP_LIST . ' AS del', 'del.referenceid = e.id');

        $this->db->like('e.isriorcode', $data["wip_ref_no"]);
        $this->db->like('e.stylenamerefno', $data["style_ref_no"]);

        // if($data['shipmentDate'] != '')
        // {
        //     $startDate = $this->changeReverseDate($data['shipmentDate']);
        //     $startDate = $startDate. ' ' . '00:00:00';
        //     $this->db->where('shipmentSubDate >=', $startDate);
        // }
        if($data['shipmentFrom'] != '' && $data['shipmentTo'] != '')
        {
            $startDate = $this->changeReverseDate($data['shipmentFrom']);
            $endDate = $this->changeReverseDate($data['shipmentTo']);
            $startDate = $startDate. ' ' . '00:00:00';
            $endDate = $endDate. ' ' . '23:59:59';
            $this->db->where('shipmentSubDate >=', $startDate);
            $this->db->where('shipmentSubDate <=', $endDate);
        }
        if($data['po_sam_ref_no'] != '')
        {
            $this->db->where('e.po_sam_ref_no', $data['po_sam_ref_no']);
        }
        if($data['po_sam_qty'] != '')
        {
            $this->db->where('e.po_sam_qty', $data['po_sam_qty']);
        }
        if($data['brandId'] != '')
        {
            $this->db->where('e.brandId', $data['brandId']);
        }
	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
    }

    public function getISRListt()
    {
        
        $this->db->select('e.id,ids,e.datecreated AS 
        formattedDateCreated,e.dateupdated AS 
        formattedDateUpdated,e.isriorno,e.isriorcode,e.companyid,e.reqforisrior,e.stylenamerefno,orderenqrefno,
        e.exporderqty,e.orderstatus,pcs_set,e.merchantid,brandname,e.status,poNoEnqRefNo,
		poQtySampleQty,pcs_set,shipmentSubDate AS formattedShipmentSubDate, e.pcsorset');
        $this->db->from(KN_ORDER_ENQUIRY . ' AS e');
        $this->db->where('e.companyid', $this->companyid);
        $this->db->where('e.merchantid', $this->userid);
        $this->db->where('e.status !=', '3');
        $this->db->where('e.orderstatus', '2');
        $this->db->where('e.reqforisrior', '1');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = e.brandId');
        $this->db->join(DELIVERY_SCHEDULE_WIP_LIST . ' AS del', 'del.referenceid = e.id');
        $this->db->order_by('e.datecreated DESC');
	    $query = $this->db->get();
	    $result = $query->result_array();

        $po_sql = "SELECT a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, b.pcs_set, sum(a.po_qty) as tol_po_qty
                    FROM tbl_oe_po_wise as a 
                    INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id 
                   
                    GROUP BY a.pono_enq_refno, a.enquiry_id";
        $po_data = $this->db->query($po_sql)->result_array();
        
        foreach ($result as $key => $res) {
            $result[$key]['poQtySampleRefQty'] = '';
            $result[$key]['poQtySampleQty'] = '';
            $result[$key]['poShipmentDate'] = '';
            $result[$key]['poPcsSet'] = '';
            foreach ($po_data as $key2 => $value) {
                if($value['enquiry_id'] == $res['id'])
                {
                    $po_ship_date = date('d/m/Y g:i A', strtotime($value['po_shipment_date']));
                    
                    if(!isset($result[$key]['poQtySampleRefQty'])) {
                        $result[$key]['poQtySampleRefQty'] = $value['pono_enq_refno'];
                        $result[$key]['poQtySampleQty'] = $value['tol_po_qty'];
                        $result[$key]['poShipmentDate'] = $po_ship_date;
                        $result[$key]['poPcsSet'] = $value['pcs_set'];
                    }
                    else {
                        $result[$key]['poQtySampleRefQty'] = $result[$key]['poQtySampleRefQty'].' <br /> '.$value['pono_enq_refno'];
                        $result[$key]['poQtySampleQty'] = $result[$key]['poQtySampleQty'].' <br /> '.$value['tol_po_qty'];
                        $result[$key]['poShipmentDate'] = $result[$key]['poShipmentDate'].' <br /> '.$po_ship_date;
                        $result[$key]['poPcsSet'] = $result[$key]['poPcsSet'].' <br /> '.$value['pcs_set'];
                    }
                }
            }
        }

	    return $result;
    }

    public function getIORListt()
    {
        $this->db->select('e.id,ids,e.datecreated AS 
        formattedDateCreated,e.dateupdated AS 
        formattedDateUpdated,e.isriorno,e.isriorcode,e.companyid,e.reqforisrior,e.stylenamerefno,orderenqrefno,
        e.exporderqty,e.orderstatus,pcs_set,e.merchantid,brandname,e.status,poNoEnqRefNo,
		poQtySampleQty,pcs_set,shipmentSubDate AS formattedShipmentSubDate, e.pcsorset');
        $this->db->from(KN_ORDER_ENQUIRY . ' AS e');
        $this->db->where('e.companyid', $this->companyid);
        $this->db->where('e.merchantid', $this->userid);
        $this->db->where('e.status !=', '3');
        $this->db->where('e.orderstatus', '2');
        $this->db->where('e.reqforisrior', '2');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = e.brandId');
        $this->db->join(DELIVERY_SCHEDULE_WIP_LIST . ' AS del', 'del.referenceid = e.id');
        $this->db->order_by('e.datecreated DESC');
	    $query = $this->db->get();
	    $result = $query->result_array();

        $po_sql = "SELECT a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, b.pcs_set, sum(a.po_qty) as tol_po_qty
                    FROM tbl_oe_po_wise as a 
                    INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id 
                    GROUP BY a.pono_enq_refno, a.enquiry_id";
        $po_data = $this->db->query($po_sql)->result_array();
        
        foreach ($result as $key => $res) {
            $result[$key]['poQtySampleRefQty'] = '';
            $result[$key]['poQtySampleQty'] = '';
            $result[$key]['poShipmentDate'] = '';
            $result[$key]['poPcsSet'] = '';
            foreach ($po_data as $key2 => $value) {
                if($value['enquiry_id'] == $res['id'])
                {
                    $po_ship_date = date('d/m/Y g:i A', strtotime($value['po_shipment_date']));
                    
                    if(!isset($result[$key]['poQtySampleRefQty'])) {
                        $result[$key]['poQtySampleRefQty'] = $value['pono_enq_refno'];
                        $result[$key]['poQtySampleQty'] = $value['tol_po_qty'];
                        $result[$key]['poShipmentDate'] = $po_ship_date;
                        $result[$key]['poPcsSet'] = $value['pcs_set'];
                    }
                    else {
                        $result[$key]['poQtySampleRefQty'] = $result[$key]['poQtySampleRefQty'].' <br /> '.$value['pono_enq_refno'];
                        $result[$key]['poQtySampleQty'] = $result[$key]['poQtySampleQty'].' <br /> '.$value['tol_po_qty'];
                        $result[$key]['poShipmentDate'] = $result[$key]['poShipmentDate'].' <br /> '.$po_ship_date;
                        $result[$key]['poPcsSet'] = $result[$key]['poPcsSet'].' <br /> '.$value['pcs_set'];
                    }
                }
            }
        }

	    return $result;
    }

    public function getStationeryListt()
    {
	    $result = [];
	    return $result;
    }

    // public function getAllMIListt()
    // {
	//     $result = [];
	//     return $result;
    // }

    public function getAllMIListt_OLD()
{
    // Get CAD MI List
    $sql = "SELECT a.*, b.*, e.brandname, d.isriorcode, f.contactname as auth_name, group_concat(g.req) as req_ids, g.log as recent_update
            FROM tbl_mi_details as a 
            INNER JOIN tbl_request as b on a.request_id=b.request_id
            INNER JOIN kn_order_enquiry as d on a.enquiry_id=d.id 
            INNER JOIN ".KN_MASTER_BRANDS." as e on d.brandId=e.id 
            INNER JOIN ".KN_USERS." as f on b.auth_by=f.id
            INNER JOIN tbl_mi_cad_details as g on a.request_id=g.request_id
            WHERE a.req_sent_status=1 AND a.dc_comp_status=0  and b.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY g.request_id ORDER BY a.log DESC";
    $result = $this->db->query($sql)->result_array();

    $mer_sql = "SELECT c.contactname as merchant_name 
                FROM tbl_mi_details as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN ".KN_USERS." as c on b.req_by=c.id
                WHERE a.req_sent_status=1 AND a.dc_comp_status=0  ORDER BY a.log DESC";
    $mer_result = $this->db->query($mer_sql)->result_array();

    foreach ($result as $key => $value) {
        $dc_sql = "SELECT COUNT(*) as count
                    FROM tbl_mi_details as a 
                    INNER JOIN tbl_request as b on a.request_id=b.request_id
                    INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                    WHERE a.req_sent_status=1 AND c.request_id = ".$value['request_id']." AND c.dc_status=1  GROUP BY a.mi_id ORDER BY a.log DESC";
        $result[$key]['scTotalCount'] = $this->db->query($dc_sql)->result_array();

        $dc_sql2 = "SELECT COUNT(*) as count
                    FROM tbl_mi_details as a 
                    INNER JOIN tbl_request as b on a.request_id=b.request_id
                    INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                    WHERE a.req_sent_status=1 AND c.request_id = ".$value['request_id']."  GROUP BY a.request_id ORDER BY a.log DESC";
        $result[$key]['dcTotalCount'] = $this->db->query($dc_sql2)->result_array();
    }

    $requirementSource = ['Bit Marker', 'Pattern', 'Pattern (Size Set)', 'Lay Marker', 'Others'];
    foreach ($result as $key => $value) {
        $req = explode(',', $value['req_ids']);
        $arr = [];
        foreach ($req as $key1 => $res) {
            if($res != '') {
                array_push($arr, $requirementSource[$res]);
            }
        }
        $result[$key]['requirement'] = implode(' <br /> ', $arr);
        $result[$key]['status'] = ($value['scTotalCount'][0]['count'] == $value['dcTotalCount'][0]['count']) ? 0 : 1;
    }

    foreach ($result as $key => $value) {
        $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
    }

    // Get BOM MI List
    $bom_sql = "SELECT a.*, f.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                INNER JOIN ".KN_USERS." as d on a.auth_by=d.id
                INNER JOIN tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
                INNER JOIN tbl_mi_bom as f on e.request_id = f.request_id 
                GROUP BY a.request_id AND a.type=3 and a.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY a.log DESC";
    $bom_data = $this->db->query($bom_sql)->result_array();
    
    $bom_mi_tbl_data = [];
    $bom_ref_nos = [];
    foreach ($bom_data as $key1 => $value1) {
        $bom_sql1 = "SELECT a.*,c.bom_ref_no FROM tbl_mi_bom_details as a
                    INNER JOIN tbl_mi_bom as b on a.mi_bom_id = b.mi_bom_id
                    INNER JOIN tbl_mi_details as c on b.request_id = c.request_id
                    WHERE a.mi_bom_id = ".$value1['mi_bom_id']." ";
        $bom_details_data = $this->db->query($bom_sql1)->result_array();
        array_push($bom_mi_tbl_data, $bom_details_data);
    }

    foreach ($bom_mi_tbl_data as $key => $value) {
        foreach ($value as $nKey => $nValue) {
            $status = ($nValue['bom_status'] == 0) ? false : true;
            $tot_qty = 0;
            $dc_no = [];
            $dc_date = [];
            $uom = [];
            $bom_ref_no = "'".$nValue['bom_ref_no']."'";
            $sample_no = "'".$nValue['sample_no']."'";

            $issue_sql = "SELECT * FROM tbl_mi_issued_details as a WHERE a.mi_ref_no = ".$bom_ref_no." AND a.mi_serial_no = ".$sample_no." AND a.mi_bom_details_id = ".$nValue['mi_bom_details_id']." ";
            $issue_data = $this->db->query($issue_sql)->result_array();
            foreach ($issue_data as $key2 => $value2) {
                array_push($uom, $value2['uom']);
                $tot_qty += $value2['issued_qty'];
            }

            $uom = array_unique($uom);
            $issuse_status = ($tot_qty == 0) ? 'PENDING' : (($nValue['ind_qty'] == $tot_qty) ? 'ISSUED - FULL' : 'ISSUED - PART');
            $bom_ref_nos[$nValue['bom_ref_no']][] = $issuse_status;
        }
    }

    $mi_ref_no = $status1 = [];
    foreach ($bom_ref_nos as $key2 => $value2) {
        if (in_array('PENDING', $value2) || in_array('ISSUED - PART', $value2)) {
            $mi_ref_no[] = $key2;
            $status1[] = (in_array('PENDING', $value2)) ? 'PENDING' : 'ISSUED - PART';
        } else {
            $mi_ref_no[] = $key2;
            $status1[] = 'ISSUED - FULL';
        }
    }

    $result1 = [];
    for ($i = 0; $i < sizeof($mi_ref_no); $i++) {
        $mi_ref_no1 = "'".$mi_ref_no[$i]."'";
        $sql1 = "SELECT a.*, f.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                INNER JOIN ".KN_USERS." as d on a.auth_by=d.id
                INNER JOIN tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
                INNER JOIN tbl_mi_bom as f on e.request_id = f.request_id 
                where e.bom_ref_no = ".$mi_ref_no1." GROUP BY a.request_id ORDER BY a.log DESC";
        $result[] = $this->db->query($sql1)->result_array();
    }

    foreach ($result as $key3 => $value3) {
        $nValue1['status'] = '';
        foreach ($value3 as $nKey1 => $nValue1) {
            $nValue1['status'] = $status1[$key3];
            $result1[] = $nValue1;
        }
    }

    // Merge both results (CAD and BOM)
    $combinedResults = array_merge($result);

    return $combinedResults;
}

public function getAllMIListt()
{
 


    $sql = "SELECT a.*, b.*, e.brandname, d.isriorcode, f.contactname as auth_name, group_concat(g.req) as req_ids, g.log as recent_update
                FROM tbl_mi_details as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN kn_order_enquiry as d on a.enquiry_id=d.id 
                INNER JOIN ".KN_MASTER_BRANDS." as e on d.brandId=e.id 
                INNER JOIN ".KN_USERS." as f on b.auth_by=f.id
                INNER JOIN tbl_mi_cad_details as g on a.request_id=g.request_id
                WHERE a.req_sent_status=1 AND a.dc_comp_status=0  and b.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY g.request_id  ORDER BY a.log DESC";
        $result3 = $this->db->query($sql)->result_array();
        
        $mer_sql = "SELECT c.contactname as merchant_name 
                FROM tbl_mi_details as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN ".KN_USERS." as c on b.req_by=c.id
                WHERE a.req_sent_status=1 AND a.dc_comp_status=0  ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($result3 as $key => $value) {

            $dc_sql = "SELECT COUNT(*) as count
                    FROM tbl_mi_details as a 
                    INNER JOIN tbl_request as b on a.request_id=b.request_id
                    INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                    WHERE a.req_sent_status=1 AND c.request_id = ".$value['request_id']." AND c.dc_status=1  GROUP BY a.mi_id ORDER BY a.log DESC";
            $result3[$key]['scTotalCount'] = $this->db->query($dc_sql)->result_array();

            $dc_sql2 = "SELECT COUNT(*) as count
                    FROM tbl_mi_details as a 
                    INNER JOIN tbl_request as b on a.request_id=b.request_id
                    INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                    WHERE a.req_sent_status=1 AND c.request_id = ".$value['request_id']."  GROUP BY a.request_id ORDER BY a.log DESC";
            $result3[$key]['dcTotalCount'] = $this->db->query($dc_sql2)->result_array();

        }

        $requirementSource = [ 'Bit Marker', 'Pattern', 'Pattern (Size Set)', 'Lay Marker', 'Others' ];
        
        //print_r($result); exit;

        foreach ($result3 as $key => $value) {
            $req = explode(',', $value['req_ids']);

            $arr = [];
            foreach ($req as $key1 => $res) {
                if($res != '') {
                    array_push($arr, $requirementSource[$res]);
                }
            }
            $result3[$key]['requirement'] = implode(' <br /> ', $arr);

            if($value['scTotalCount'][0]['count'] == $value['dcTotalCount'][0]['count'])
            {
                $result3[$key]['status'] = 0;
            }
            else {
                $result3[$key]['status'] = 1;
            }
        }

        foreach ($result3 as $key => $value) {
            $result3[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        }



        //get start bom

          $sql = "SELECT a.*, f.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join ".KN_USERS." as d on a.auth_by=d.id
                inner join tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
                inner join tbl_mi_bom as f on e.request_id = f.request_id 
                  and a.subscriberid = " . $this->db->escape($this->subscriberid)."  ORDER BY a.log DESC";
        
        $bom_data = $this->db->query($sql)->result_array();

        



       
        
        $bom_mi_tbl_data = [];
        
        $bom_ref_nos = [];
        
        //for ($i=0; $i < sizeof($bom_data); $i++) { 
        foreach($bom_data as $key1 => $value1) {
            $bom_sql1 = "SELECT a.*,c.bom_ref_no FROM tbl_mi_bom_details as a
                INNER JOIN tbl_mi_bom as b on a.mi_bom_id = b.mi_bom_id
                INNER JOIN tbl_mi_details as c on b.request_id = c.request_id
                WHERE a.mi_bom_id = ".$value1['mi_bom_id']." ";
            $bom_details_data = $this->db->query($bom_sql1)->result_array();  
                
            array_push($bom_mi_tbl_data, $bom_details_data);
        }
        //print_r($bom_mi_tbl_data); exit;
        foreach ($bom_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                if($nValue['bom_status'] == 0) 
                {
                    $status = false;
                }
                else {
                    $status = true;
                }
                $tot_qty = 0;
                $dc_no = [];
                $dc_date = [];
                $uom = [];
                $bom_ref_no = "'".$nValue['bom_ref_no']."'";
                $sample_no = "'".$nValue['sample_no']."'";
                
                $issue_sql = "SELECT * FROM tbl_mi_issued_details as a WHERE a.mi_ref_no = ".$bom_ref_no." AND a.mi_serial_no = ".$sample_no." AND a.mi_bom_details_id = ".$nValue['mi_bom_details_id']." ";
                $issue_data = $this->db->query($issue_sql)->result_array();  
                
                foreach($issue_data as $key2 => $value2) {
                    
                    array_push($uom, $value2['uom']);
                    $tot_qty += $value2['issued_qty'];
                }
                $uom = array_unique($uom);
                if($tot_qty == 0) {
                    $issuse_status = 'PENDING'; 
                } else if($nValue['ind_qty'] == $tot_qty) {
                    $issuse_status = 'ISSUED - FULL';
                } else {
                    $issuse_status = 'ISSUED - PART';
                }
                
                $bom_ref_nos[$nValue['bom_ref_no']][] = $issuse_status;
            }
        }
        //print_r($bom_ref_nos); exit;
        $mi_ref_no = $status1 = [];
        foreach($bom_ref_nos as $key2 => $value2) {
            if(in_array('PENDING',$value2) || in_array('ISSUED - PART',$value2)) {
                $mi_ref_no[] = $key2;
                if(in_array('PENDING',$value2)) {
                    $status1[] = 'PENDING';
                } else {
                    $status1[] = 'ISSUED - PART';
                }
            } else {
                
                $mi_ref_no[] = $key2;
                $status1[] = 'ISSUED - FULL';
                
            }
        } 
       $result = $result1 = [];
       for($i=0;$i<sizeof($mi_ref_no);$i++) {
           $mi_ref_no1 = "'".$mi_ref_no[$i]."'";
        $sql1 = "SELECT a.*, f.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join ".KN_USERS." as d on a.auth_by=d.id
                inner join tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
                inner join tbl_mi_bom as f on e.request_id = f.request_id 
                where e.bom_ref_no = ".$mi_ref_no1." GROUP BY a.request_id  ORDER BY a.log DESC";
        $result[] = $this->db->query($sql1)->result_array();
        
       }
       
       foreach($result as $key3 => $value3) {
           $nValue1['status'] = '';
           foreach ($value3 as $nKey1 => $nValue1) {
               $nValue1['status'] = $status1[$key3];
               $result1[] = $nValue1;
                     //$Value1_type['status_type'] = $status1[$key3];
               //$result1[] = $Value1_type;
           }
       }
       
        //return $result1;

         $combinedResults = array_merge($result3,$result1 );

          usort($combinedResults, function($a, $b) {
         return strtotime($b['log']) - strtotime($a['log']);
          });

    return $combinedResults;
}

    public function getCADMIListt()
    {
	    // $result = [];
	    // return $result;

         $sql = "SELECT a.*, b.*, e.brandname, d.isriorcode, f.contactname as auth_name, group_concat(g.req) as req_ids, g.flag as flags,g.log as recent_update
                FROM tbl_mi_details as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN kn_order_enquiry as d on a.enquiry_id=d.id 
                INNER JOIN ".KN_MASTER_BRANDS." as e on d.brandId=e.id 
                INNER JOIN ".KN_USERS." as f on b.auth_by=f.id
                INNER JOIN tbl_mi_cad_details as g on a.request_id=g.request_id
                WHERE a.req_sent_status=1 AND a.dc_comp_status=0  and b.subscriberid = " . $this->db->escape($this->subscriberid)." GROUP BY g.request_id  ORDER BY recent_update  DESC";
        $result = $this->db->query($sql)->result_array();
        
        $mer_sql = "SELECT c.contactname as merchant_name 
                FROM tbl_mi_details as a 
                INNER JOIN tbl_request as b on a.request_id=b.request_id
                INNER JOIN ".KN_USERS." as c on b.req_by=c.id
                WHERE a.req_sent_status=1 AND a.dc_comp_status=0 ";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($result as $key => $value) {

            $dc_sql = "SELECT COUNT(*) as count
                    FROM tbl_mi_details as a 
                    INNER JOIN tbl_request as b on a.request_id=b.request_id
                    INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                    WHERE a.req_sent_status=1 AND c.request_id = ".$value['request_id']." AND c.dc_status=1  GROUP BY a.mi_id ORDER BY c.log DESC";
            $result[$key]['scTotalCount'] = $this->db->query($dc_sql)->result_array();

            $dc_sql2 = "SELECT COUNT(*) as count
                    FROM tbl_mi_details as a 
                    INNER JOIN tbl_request as b on a.request_id=b.request_id
                    INNER JOIN tbl_mi_cad_details as c on a.request_id=c.request_id
                    WHERE a.req_sent_status=1 AND c.request_id = ".$value['request_id']."  GROUP BY a.request_id ORDER BY a.log DESC";
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

        return $result;

    }

    public function getBOMMIListt()
    {
	


        // $sql = "SELECT a.*, f.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
        //         inner join kn_order_enquiry as b on a.enquiry_id=b.id 
        //         inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
        //         inner join ".KN_USERS." as d on a.auth_by=d.id
        //         inner join tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
        //         inner join tbl_mi_bom as f on e.request_id = f.request_id 
        //         GROUP BY a.request_id  AND a.type=3 and a.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY a.log DESC";
        
        // $bom_data = $this->db->query($sql)->result_array();

          $sql = "SELECT a.*, f.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join ".KN_USERS." as d on a.auth_by=d.id
                inner join tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
                inner join tbl_mi_bom as f on e.request_id = f.request_id 
                  and a.subscriberid = " . $this->db->escape($this->subscriberid)."  ORDER BY f.log DESC";
        
        $bom_data = $this->db->query($sql)->result_array();

        



       
        
        $bom_mi_tbl_data = [];
        
        $bom_ref_nos = [];
        
        //for ($i=0; $i < sizeof($bom_data); $i++) { 
        foreach($bom_data as $key1 => $value1) {
            $bom_sql1 = "SELECT a.*,c.bom_ref_no FROM tbl_mi_bom_details as a
                INNER JOIN tbl_mi_bom as b on a.mi_bom_id = b.mi_bom_id
                INNER JOIN tbl_mi_details as c on b.request_id = c.request_id
                WHERE a.mi_bom_id = ".$value1['mi_bom_id']." ";
            $bom_details_data = $this->db->query($bom_sql1)->result_array();  
                
            array_push($bom_mi_tbl_data, $bom_details_data);
        }
        //print_r($bom_mi_tbl_data); exit;
        foreach ($bom_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                if($nValue['bom_status'] == 0) 
                {
                    $status = false;
                }
                else {
                    $status = true;
                }
                $tot_qty = 0;
                $dc_no = [];
                $dc_date = [];
                $uom = [];
                $bom_ref_no = "'".$nValue['bom_ref_no']."'";
                $sample_no = "'".$nValue['sample_no']."'";
                
                $issue_sql = "SELECT * FROM tbl_mi_issued_details as a WHERE a.mi_ref_no = ".$bom_ref_no." AND a.mi_serial_no = ".$sample_no." AND a.mi_bom_details_id = ".$nValue['mi_bom_details_id']." ";
                $issue_data = $this->db->query($issue_sql)->result_array();  
                
                foreach($issue_data as $key2 => $value2) {
                    
                    array_push($uom, $value2['uom']);
                    $tot_qty += $value2['issued_qty'];
                }
                $uom = array_unique($uom);
                if($tot_qty == 0) {
                    $issuse_status = 'PENDING'; 
                } else if($nValue['ind_qty'] == $tot_qty) {
                    $issuse_status = 'ISSUED - FULL';
                } else {
                    $issuse_status = 'ISSUED - PART';
                }
                
                $bom_ref_nos[$nValue['bom_ref_no']][] = $issuse_status;
            }
        }
        //print_r($bom_ref_nos); exit;
        $mi_ref_no = $status1 = [];
        foreach($bom_ref_nos as $key2 => $value2) {
            if(in_array('PENDING',$value2) || in_array('ISSUED - PART',$value2)) {
                $mi_ref_no[] = $key2;
                if(in_array('PENDING',$value2)) {
                    $status1[] = 'PENDING';
                } else {
                    $status1[] = 'ISSUED - PART';
                }
            } else {
                
                $mi_ref_no[] = $key2;
                $status1[] = 'ISSUED - FULL';
                
            }
        } 
       $result = $result1 = [];
       for($i=0;$i<sizeof($mi_ref_no);$i++) {
           $mi_ref_no1 = "'".$mi_ref_no[$i]."'";
        $sql1 = "SELECT a.*, f.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join ".KN_USERS." as d on a.auth_by=d.id
                inner join tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
                inner join tbl_mi_bom as f on e.request_id = f.request_id 
                where e.bom_ref_no = ".$mi_ref_no1." GROUP BY a.request_id  ORDER BY a.log DESC";
        $result[] = $this->db->query($sql1)->result_array();
        
       }
       
       foreach($result as $key3 => $value3) {
           $nValue1['status'] = '';
           foreach ($value3 as $nKey1 => $nValue1) {
               $nValue1['status'] = $status1[$key3];
               $result1[] = $nValue1;
           }
       }
       
        return $result1;
    }

    public function getBOM2MIListt()
    {
	    $result = [];
	    return $result;
    }

    public function getFabricMIListt()
    {
	    $result = [];
	    return $result;
    }

    public function getStationeryMIListt()
    {
	    $result = [];
	    return $result;
    }

    public function getAllPIListt()
    {
	   $sql = "SELECT a.*, h.type,h.cutoff_date, c.brandname, b.isriorcode, e.*, e.log as logs,e.flag as flags , f.proforma_value, f.currency as pay_currency, f.pay_by_date, f.request_payment_id, g.vendorname FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join tbl_request_payment as f on f.type_of_mode='M' and a.purchase_indent_id=f.purchase_indent_id
                left join kn_master_bom_vendor as g on a.vendor_id=g.id
                where  h.type IN (3, 4) and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and e.type_of_mode='M'  and a.bill_paid_status = 0 and b.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY e.log DESC";
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

    public function getFabricPIListt()
    {
	    $result = [];
	    return $result;
    }

    public function getStationeryPIListt()
    {
	    $result = [];
	    return $result;
    }

    public function getGarmentIssuedListt() {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.sam_qa_status, e.*, group_concat(f.ref_queue_no SEPARATOR' <br /> ') as sam_ref_no
                FROM tbl_sample_requirement as e 
                INNER JOIN tbl_request_sample as f on e.sample_requirement_id=f.sample_id
                INNER JOIN tbl_request as a on a.request_id=f.request_id
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as d on a.auth_by=d.id
                WHERE  a.type=2 AND e.dc_status=0 GROUP BY e.dc_ref_queue_no ORDER BY a.log DESC";
	    $result = $this->db->query($sql)->result_array();

        $mer_sql = "SELECT b.contactname as merchant_name
                FROM tbl_sample_requirement as e 
                INNER JOIN tbl_request_sample as f on e.sample_requirement_id=f.sample_id
                INNER JOIN tbl_request as a on a.request_id=f.request_id
                inner join ".KN_USERS." as b on a.req_by=b.id 
                WHERE a.type = 2 and e.dc_status=0 GROUP BY e.dc_ref_queue_no ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        foreach ($result as $key => $value) {
            $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        }

	    return $result;
    }
    
    public function checkDraftorNot($id)
    {
        //$result = $this->db->from('kn_order_enquiry')->where('id', $id)->where('draft_status', 1)->get()->num_rows();
        $result = $this->db->from('kn_order_enquiry')->where('id', $id)->where('draft_status', 1)->get()->row();
        
        return $result;
    }
    public function getdraftdata()
    {
        // $result = $this->db->from('kn_order_enquiry')->where('draft_status', 1)->get()->num_rows();
        $result = $this->db->from('kn_order_enquiry')->where('draft_status', 1)->get()->row();
        return $result;
    }
    
    public function cleardraft($id)
    {   
        if($this->db->delete('kn_order_enquiry',array('id' => $id))){
        $ArrResult['success']					    = 1;
        }else{
        $ArrResult['success']					    = 0;
        }
        return $ArrResult;
    }
    
}
