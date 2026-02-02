<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Managementmodel extends CI_Model {
    public function __construct() {
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->subscriberid = $ArrUserLoggedInfo['subscriber_id'];
        $this->subscribid     = $ArrUserLoggedInfo['subscriber_id'];
        $this->mysqldatetime = date('d/m/Y h:i A');

     


    }

    function getMgmtUsers($VarCompanyId='') {
        $VarSql = "SELECT u.id,u.contactname,u.username FROM ".KN_USERS." AS u WHERE u.usertype = '3' AND u.companyid = '$VarCompanyId' order by u.id desc limit 10 ";
        $ArrRes = $this->db->query($VarSql)->result();
        return $ArrRes;
    }

    function saveMgmtRoles($VarRoles,$VarCompanyId='',$VarMysqlDataTime,$VarEditMode='') {
            foreach ($VarRoles as $key => $item) {
                /*$this->db->select('id');
                $this->db->where('companyid',$VarCompanyId);
                $this->db->where('mgmtuserid',$key);
                $query = $this->db->get(KN_MGMT_ROLES);
                $numrows = $query->num_rows();*/
                //if($VarEditMode == 0) {
                    $this->db->update(KN_MGMT_ROLES,array('enquiryrole'=>$item[0],'cadrole'=>$item[1],'status'=>1,'dateupdated'=>$VarMysqlDataTime),array('mgmtuserid'=>$key,'companyid'=>$VarCompanyId));
                    //$this->db->insert(KN_MGMT_ROLES,array('mgmtuserid'=>$key,'enquiryrole'=>$item[0],'cadrole'=>$item[1],'status'=>1,'companyid'=>$VarCompanyId,'datecreated'=>$VarMysqlDataTime));
                //}                else {

                    //$this->db->update(KN_MGMT_ROLES,array('enquiryrole'=>$item[0],'cadrole'=>$item[1],'status'=>1,'dateupdated'=>$VarMysqlDataTime),array('mgmtuserid'=>$key,'companyid'=>$VarCompanyId));
                //}
            }
            //$this->db->insert(KN_MGMT_ROLES,$VarRoles);
            //Update
            //$this->db->update(KN_MGMT_ROLES,$VarRoles,array('companyid'=>$VarCompanyId));
            return true;
            //return $this->db->insert_id();

        //echo '<pre>'; print_r($num); die('');
    }

    function getMgmtRoles($VarCompanyId='') {
        $VarSql = "SELECT r.id,r.role,r.mgmtuserid,r.enquiryrole,r.cadrole FROM ".KN_MGMT_ROLES." AS r WHERE companyid = '$VarCompanyId'";
        $ArrRes = $this->db->query($VarSql)->result();
        return $ArrRes;
    }

    function getMgmtRolesById($VarUserId,$VarCompanyId) {
        $VarSql = "SELECT r.id,r.mgmtuserid,r.enquiryrole,r.cadrole FROM ".KN_MGMT_ROLES." AS r WHERE r.mgmtuserid = '$VarUserId' AND r.companyid = '$VarCompanyId' ";
        $ArrRes = $this->db->query($VarSql)->result();
        return $ArrRes;

    }

    var $authListColOrder = array('','oe.id', 'brandname', 'request_type_dept','', 'a.requesttype','formattedDateCreated', 'formatCutOffDt', 'a.approvaltype',
        'u.contactname','current_status','formattedDateUpdated', 'a.status');
    var $authListColSearch = array('oe.isriorcode', 'brandname', 'request_type_dept','a.datecreated', 'a.cutoffdatetime','a.approvaltype',
        'u.contactname', 'a.dateupdated', 'current_status','a.status');

    public function dataTablesAuthListQry() {
        $this->db->select('a.id,a.requesttype,DATE_FORMAT(cutoffdatetime,"%d-%m-%Y %H:%i:%s") as formatCutOffDt,
        DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") as formattedDateUpdated,a.merchantid,oe.isriorcode,oe.stylenamerefno,a.status,
        DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") as formattedDateCreated,a.mgmtcurrentstatus,a.dateupdated,a.approvaltype,a.queueno,
        a.deptcurrentstatus,jsondatagrid,brandname,buyername,u.contactname,requirementforbom,current_status,request_type_dept');
        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->order_by("a.id","desc");
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        // commented by myself  regards new brand form
       // $this->db->join(KN_MASTER_BUYER.' AS by','by.id = oe.buyerId');
        $this->db->join(KN_USERS.' AS u','a.merchantid = u.id');
        $i = 0;
        foreach ($this->authListColSearch as $item) {
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
                if(count($this->authListColSearch) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if(isset($_POST['order'])) {
            $this->db->order_by($this->authListColOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function authListDataTables() {
        $this->dataTablesAuthListQry();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    public function authListCountFiltered() {
        $this->dataTablesAuthListQry();
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function authListCountAll() {
        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
       // commented by myself  regards new brand form
       // $this->db->join(KN_MASTER_BUYER.' AS by','by.id = oe.buyerId');
        $this->db->join(KN_USERS.' AS u','a.merchantid = u.id');
        return $this->db->count_all_results();
    }

    var $wipColOrder  = array('','e.id', 'e.datecreated','brandname','stylenamerefno','orderenqrefno','poNoEnqRefNo','poQtySampleQty','pcs_set','shipmentSubDate',
        '','e.dateupdated','e.dateupdated','e.status');
    var $wipColSearch = array('e.isriorcode', 'e.datecreated','brandname','stylenamerefno','orderenqrefno','poNoEnqRefNo','poQtySampleQty','pcs_set',
        'e.dateupdated','shipmentSubDate','e.status');

    public function dataTablesWipQry() {
        $this->db->select('e.id,del.id as delId,u.contactname,DATE_FORMAT(e.datecreated,"%d-%m-%Y %H:%i:%s") AS 
        formattedDateCreated,DATE_FORMAT(e.dateupdated,"%d-%m-%Y %H:%i:%s") AS 
        formattedDateUpdated,e.isriorno,e.companyid,e.reqforisrior,e.stylenamerefno,orderenqrefno,
        e.exporderqty,e.orderstatus,pcs_set,e.isriorcode,e.merchantid,brandname,e.status,poNoEnqRefNo,
		poQtySampleQty,pcs_set,DATE_FORMAT(shipmentSubDate,"%d-%m-%Y") AS formattedShipmentSubDate');
        $this->db->from(KN_ORDER_ENQUIRY.' AS e');
        $this->db->where('e.companyid',$this->companyid);
        $this->db->where('e.status !=','3');
        $this->db->where('e.orderstatus','2');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = e.brandId');
        $this->db->join(DELIVERY_SCHEDULE_WIP_LIST.' AS del','del.referenceid = e.id');
        $this->db->join(KN_USERS.' AS u','u.id = e.merchantid');

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
        $this->db->where('e.status !=','3');
        $this->db->where('e.orderstatus','2');
        //$this->db->order_by('del.ids','asc');
        //$this->db->group_by('poNoEnqRefNo');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = e.brandId');
        $this->db->join(DELIVERY_SCHEDULE_WIP_LIST.' AS del','del.referenceid = e.id');
        return $this->db->count_all_results();
    }



    var $queueListColOrder = array('','oe.id','brandname','queueno','request_type_dept','','formatDateCreated','cutoffdatetime','approvaltype','u.contactname','current_status',
        'formatDateUpdated','a.status');
    var $queueListColSearch = array('oe.isriorcode','brandname','queueno','request_type_dept','a.datecreated','cutoffdatetime','a.status','u.contactname','a.dateupdated','a.approvaltype');

    public function queueListDataTables($VarRequestListType='') {
        $this->dataTablesQueueListQry($VarRequestListType);
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function dataTablesQueueListQry($VarRequestListType='') {
        if($VarRequestListType == "BOM") {
            $this->db->select('a.id,DATE_FORMAT(cutoffdatetime,"%d-%m-%Y %H:%i:%s") AS formattedCutOffDt,DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") AS 
        formatDateCreated,DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") AS formatDateUpdated,a.status,a.mgmtcurrentstatus,a.approvaltype,
        a.queuecompletestatus,a.queueno,a.deptcurrentstatus,a.requestrefno,a.request_type_dept,a.current_status,oe.isriorcode,brandname,
        u.contactname as merchant,requirementforbom,request_type_dept');
        }
        else {
            $this->db->select('a.id,DATE_FORMAT(cutoffdatetime,"%d-%m-%Y %H:%i:%s") AS formattedCutOffDt,DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") AS 
        formatDateCreated,DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") AS formatDateUpdated,a.status,a.mgmtcurrentstatus,a.approvaltype,
        a.queuecompletestatus,a.queueno,a.deptcurrentstatus,a.requestrefno,a.request_type_dept,a.current_status,oe.isriorcode,brandname,
        jsondatagrid,u.contactname as merchant,requirementforbom,request_type_dept');
        }
        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->where('a.status !=','3');
        $this->db->where('a.queueno !=','0');
        if(!empty($VarRequestListType)) {
            $this->db->where('a.request_type_dept',$VarRequestListType);
        }
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.merchantid = u.id');
        //$this->db->join(KN_MASTER_REQUEST_TYPE.' AS appT','appT.id = a.approvaltype');
        $i = 0;
        foreach ($this->queueListColSearch as $item) {
            if(validateDate($_POST['search']['value'])) {
                $_POST['search']['value'] = date('Y-m-d',strtotime($_POST['search']['value']));
            }
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->queueListColSearch) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if(isset($_POST['order'])) {
            $this->db->order_by($this->queueListColOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function queueListCountAll($VarRequestListType='') {
        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->where('a.status !=','3');
        $this->db->where('a.queueno !=','0');
        if(!empty($VarRequestListType)) {
            $this->db->where('a.request_type_dept',$VarRequestListType);
        }
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.merchantid = u.id');
        //$this->db->join(KN_MASTER_REQUEST_TYPE.' AS appT','appT.id = a.approvaltype');
        return $this->db->count_all_results();
    }

    public function queueListCountFiltered($VarRequestListTypeId='') {
        $this->dataTablesQueueListQry($VarRequestListTypeId);
        $query = $this->db->get();
        return $query->num_rows();
    }

    /*
     * Enquiry List
     * */
    public function getOrderEnquiryDataTables() {
        $this->getOrderEnquiryQry();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    var $orderEnquiryColOrder = array('','oe.orderenqrefno','oe.datecreated', 'brandname','pair',
        'enquirytype','stylenamerefno','confirmprice','currency','u.contactname','orderstatus','oe.dateupdated','oe.status');

    var $orderEnquiryColSearch = array('oe.orderenqrefno', 'oe.datecreated', 'et.enquirytype','brandname','pair','stylenamerefno','confirmprice',
        'u.contactname','oe.dateupdated','oe.status','order_status_value');

    public function getOrderEnquiryQry() {
        $status = '0,3';
        $this->db->select('oe.id,orderenqrefno,br.brandname,enquirytype,oe.currency,stylenamerefno,
        DATE_FORMAT(oe.datecreated,"%d-%m-%Y %H:%i:%s") as formattedDateCreated,DATE_FORMAT(oe.dateupdated,"%d-%m-%Y %H:%i:%s") as formattedDateUpdated,
        oe.status,confirmprice,oe.orderstatus,oe.isriorno,pair as isr_ior,u.contactname,order_status_value');
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY. ' AS oe');
        $this->db->where('oe.companyid',$userInfo['companyid']);
        $this->db->where('oe.orderstatus !=',2);
        //$this->db->where('oe.status !=',3);
        $this->db->where_not_in('oe.status',$status);
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY.' AS mo','mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS.' AS cons','cons.eachkey = oe.reqforisrior');
        $this->db->join(KN_USERS.' AS u','u.id = oe.merchantid');
        $i = 0;
        foreach ($this->orderEnquiryColSearch as $item) {
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
                if(count($this->orderEnquiryColSearch) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if(isset($_POST['order'])) {
            $this->db->order_by($this->orderEnquiryColOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function orderEnquiryListCountAll() {
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY. ' AS oe');
        $this->db->where('oe.companyid',$userInfo['companyid']);
        $this->db->where('oe.orderstatus !=',2);
        $this->db->where('oe.status !=',3);
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY.' AS mo','mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS.' AS cons','cons.eachkey = oe.reqforisrior');
        $this->db->join(KN_USERS.' AS u','u.id = oe.merchantid');
        return $this->db->count_all_results();
    }

    public function orderEnquiryListCountFiltered() {
        $this->getOrderEnquiryQry();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getOrderEnquiryListt()
    {
        $status = [0,3];
        $order_status=[1,3,4];
        $this->db->select('oe.id,orderenqrefno,br.brandname,enquirytype,oe.currency,stylenamerefno,
        ,oe.datecreated as formattedDateCreated, oe.totalcombo, oe.totalcomponents, oe.dateupdated as formattedDateUpdated, 
        oe.dateauthorized,oe.reqdatetime, oe.status,confirmprice,oe.orderstatus,oe.isriorno,pair as isr_ior,
        u.contactname,order_status_value');
        $userInfo = fnGetUserLoggedInfo(1);
       
        $this->db->from(KN_ORDER_ENQUIRY. ' AS oe');
        $this->db->where('oe.companyid',$userInfo['companyid']);
        $this->db->where('oe.subscriberid',$userInfo['subscriber_id']);
        $this->db->where('oe.orderstatus !=', 2);
        $this->db->where('oe.draft_status =', 2);
        $this->db->where_not_in('oe.status',$status);
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY.' AS mo','mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS.' AS cons','cons.eachkey = oe.reqforisrior');
        $this->db->join(KN_USERS.' AS u','u.id = oe.merchantid');
        $this->db->order_by('oe.id DESC');
	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
	   // echo $query= $this->db->get_compiled_select();
    }

    public function getEnquiryIORListt()
    {   // oe.datecreated as dateauthorized instead of this dateauthorized column selected from db,

        $status = [0,3];
       //order_status=[1,3,4];
        $order_status=[3];
        $this->db->select('oe.id,orderenqrefno,br.brandname,enquirytype,oe.currency,stylenamerefno,
        ,oe.datecreated as formattedDateCreated, oe.totalcombo, oe.totalcomponents, oe.dateupdated as formattedDateUpdated, 
        oe.dateauthorized,oe.reqdatetime, oe.status,confirmprice,oe.orderstatus,oe.isriorno,pair as isr_ior,
        u.contactname,order_status_value');
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY. ' AS oe');
        $this->db->where('oe.companyid',$userInfo['companyid']);
        $this->db->where('oe.subscriberid',$userInfo['subscriber_id']);

        $this->db->where('oe.orderstatus !=', 2);
        $this->db->where('oe.reqforisrior =', 2);
        $this->db->where('oe.draft_status =', 2);
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY.' AS mo','mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS.' AS cons','cons.eachkey = oe.reqforisrior');
        $this->db->join(KN_USERS.' AS u','u.id = oe.merchantid');
        $this->db->order_by('oe.id DESC');
	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
    }
    public function getEnquiryISRListt()
    {   // oe.datecreated as dateauthorized instead of this dateauthorized column selected from db,

        $status = [0,3];
       //order_status=[1,3,4];
        $order_status=[3];
        $this->db->select('oe.id,orderenqrefno,br.brandname,enquirytype,oe.currency,stylenamerefno,
        ,oe.datecreated as formattedDateCreated, oe.totalcombo, oe.totalcomponents, oe.dateupdated as formattedDateUpdated, 
        oe.dateauthorized,oe.reqdatetime, oe.status,confirmprice,oe.orderstatus,oe.isriorno,pair as isr_ior,
        u.contactname,order_status_value');
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY. ' AS oe');
        $this->db->where('oe.companyid',$userInfo['companyid']);
        $this->db->where('oe.subscriberid',$userInfo['subscriber_id']);
        $this->db->where('oe.orderstatus !=', 2);
        $this->db->where('oe.reqforisrior =', 1);
        $this->db->where('oe.draft_status =', 2);
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY.' AS mo','mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS.' AS cons','cons.eachkey = oe.reqforisrior');
        $this->db->join(KN_USERS.' AS u','u.id = oe.merchantid');
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
    public function getdraftdata()
    {
        // $result = $this->db->from('kn_order_enquiry')->where('draft_status', 1)->get()->num_rows();
        $result = $this->db->from('kn_order_enquiry')->where('draft_status', 1)->get()->row();
        return $result;
    }

    public function searchWIPListt($data)
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
                    WHERE  GROUP BY a.pono_enq_refno, a.enquiry_id) as aa where aa.tol_po_qty like ('%$data[po_sam_qty]')";
                    $po_data = $this->db->query($po_sql)->result_array();
        }else{
            $po_sql = "SELECT a.enquiry_id, a.pono_enq_refno, a.po_shipment_date, b.pcs_set, sum(a.po_qty) as tol_po_qty
                    FROM tbl_oe_po_wise as a 
                    INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id 
                    WHERE  GROUP BY a.pono_enq_refno, a.enquiry_id";
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
    
    public function searchEnquiryListt($data)
    {

        $status = [0,3];
       //order_status=[1,3,4];
        $order_status=[3];
        $this->db->select('oe.id,orderenqrefno,br.brandname,enquirytype,oe.currency,stylenamerefno,
        ,oe.datecreated as formattedDateCreated, oe.totalcombo, oe.totalcomponents, oe.dateupdated as formattedDateUpdated, 
        oe.dateauthorized,oe.reqdatetime, oe.status,confirmprice,oe.orderstatus,oe.isriorno,pair as isr_ior,
        u.contactname,order_status_value');
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY. ' AS oe');
        $this->db->where('oe.orderstatus !=', 2);
        $this->db->where('oe.status !=', 3);
        $this->db->where('oe.draft_status =', 2);
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY.' AS mo','mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS.' AS cons','cons.eachkey = oe.reqforisrior');
        $this->db->join(KN_USERS.' AS u','u.id = oe.merchantid');
        $this->db->order_by('oe.id DESC');
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
	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
    }
        public function searchEnquiryIORListt($data)
    {
        $status = [0,3];
        $order_status=[1,3,4];
        $this->db->select('oe.id,orderenqrefno,br.brandname,enquirytype,oe.currency,stylenamerefno,
        ,oe.datecreated as formattedDateCreated, oe.totalcombo, oe.totalcomponents, oe.dateupdated as formattedDateUpdated, 
        oe.dateauthorized,oe.reqdatetime, oe.status,confirmprice,oe.orderstatus,oe.isriorno,pair as isr_ior,
        u.contactname,order_status_value');
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_ORDER_ENQUIRY. ' AS oe');
        $this->db->where('oe.companyid',$userInfo['companyid']);
        $this->db->where('oe.orderstatus !=', 2);
        $this->db->where('oe.status !=', 3);
        $this->db->where('oe.reqforisrior =', 2);
        $this->db->where('oe.draft_status =', 2);
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_MASTER_MODEOFENQUIRY.' AS mo','mo.id = oe.modeofenquiry');
        $this->db->join(KN_CONSTANTS.' AS cons','cons.eachkey = oe.reqforisrior');
        $this->db->join(KN_USERS.' AS u','u.id = oe.merchantid');
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

    public function getAllRequestListt() {
        $sql = "SELECT a.*, c.brandname, b.orderenqrefno
            FROM tbl_request as a 
            inner join kn_order_enquiry as b on a.enquiry_id=b.id
            inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
            WHERE  (a.mgmt_approval=0 OR a.mgmt_approval=2)";
	    $result = $this->db->query($sql)->result();
	    return $result;
    }

    public function getAllMgmtPIApprovalListt()
    {
        // $sql = "SELECT aa.*, a.*, c.brandname, b.orderenqrefno FROM tbl_request_yarn aa
        //         INNER JOIN tbl_request as a ON a.request_id = aa.request_id
        //         INNER JOIN kn_order_enquiry as b ON a.enquiry_id=b.id
        //         INNER JOIN ".KN_MASTER_BRANDS." as c ON b.brandId=c.id
        //         WHERE a.type=3 AND aa.req_status = 1 AND a.mgmt_approval=1 AND a.deprt_approval=1 AND a.flag=1";
        // $data = $this->db->query($sql)->result_array();
        
         $sql = "SELECT a.*, a.log as recent_log,f.cutoff_date,f.auth_type, c.brandname, b.isriorcode, d.contactname as auth_name, e.req_dt, e.appr_type, e.request_type, f.type as usertype,e.log as logs, e.* FROM tbl_purchase_indent as a
                inner join tbl_request as f on a.request_id=f.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                left join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join ".KN_USERS." as d on e.appr_by=d.id
                where f.type IN (3, 4) and f.mgmt_approval=1 and a.pi_appl_status=1 and a.pi_list_status=0  and f.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY e.log DESC";
              
              
             
        $data = $this->db->query($sql)->result_array();

       
      
        return $data;
    }

    public function getFabricMgmtPIApprovalListt()
    {
        $data = [];
        return $data;
    }

    public function getStationeryMgmtPIApprovalListt()
    {
        $data = [];
        return $data;
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
    public function getAllManagementPIListt()
    {

        $results=[];
         $sql = "SELECT a.*, e.flag as flags,h.type,h.cutoff_date, c.brandname, b.isriorcode, e.*, e.log as logs, f.proforma_value, f.currency as pay_currency, f.pay_by_date, f.request_payment_id, g.vendorname FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join tbl_request_payment as f on f.type_of_mode='M' and a.purchase_indent_id=f.purchase_indent_id
                left join kn_master_bom_vendor as g on a.vendor_id=g.id
                where  h.type IN (3, 4) and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and a.bill_paid_status = 0 and e.type_of_mode='M' and h.subscriberid = " . $this->db->escape($this->subscriberid)."  ORDER BY e.log DESC";
        $result = $this->db->query($sql)->result_array();
        
        foreach ($result as $key => $res) {
         $request_for = []; $payment_requirement = []; $vendorname = []; $inv_status = []; $log = []; 
        
       
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
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING-RR</strong></span>';
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

    public function getYarnManagementPIListt()
    {
        $data = [];
        return $data;
    }

    public function getFabricManagementPIListt()
    {
        $data = [];
        return $data;
    }

    public function getStationeryManagementPIListt()
    {
        $data = [];
        return $data;
    }

    public function getCADListt() {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, f.contactname as auth_name
                FROM tbl_request as a 
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                LEFT JOIN ".KN_USERS." as f on a.req_by=f.id 
                WHERE a.type = 1 AND a.deprt_approval=0 AND a.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY a.log DESC";
        $result = $this->db->query($sql)->result_array();
       
         $cad_sql = "SELECT a.cad_requirement, a.request_id
                FROM tbl_cad_requirement as a 
                INNER JOIN tbl_request as e on a.request_id=e.request_id
                WHERE e.type = 1 AND e.deprt_approval=0 ";
        $cad_data = $this->db->query($cad_sql)->result_array();

        foreach ($result as $key => $res) {
            $arr = $s_arr = [];
            
            foreach ($cad_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['cad_requirement']);
                    $status = '';
                    if($res['mgmt_approval'] == '0' || $res['mgmt_approval'] == '')
                    {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    else if($res['mgmt_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                    else if($res['mgmt_approval'] == '3') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                    }
                    array_push($s_arr, $status);
                }
            }
        
            // $dis_arr = array_unique($arr);
            $result[$key]['cad_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['cad_status'] = implode(' <br /> ', $s_arr);
    
        }

	    return $result;
    }

    public function getrequestDetailss($id, $reqId ) {
        $req_sql = "SELECT a.*, b.contactname as auth_name FROM tbl_request as a 
            LEFT JOIN ".KN_USERS." as b ON a.auth_by=b.id WHERE a.enquiry_id='$id' and a.request_id='$reqId' ";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $readStatus = true;
        if(sizeof($req_data) > 0) {
            $type = $req_data[0]['type'];
            $data = [];
            if($type == 1) {
                $sql = "SELECT * FROM tbl_request_cad as a inner join tbl_cad_requirement as b on a.cad_id = b.cad_requirement_id 
                        WHERE a.request_id='$reqId' ";
                $data = $this->db->query($sql)->result_array();
            }
        }
        else {
            $data = [];
        }

        $result = $att_ref_data = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['request_cad_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['cad_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value["req_size"]];

            $att_ref_data[$key] = [ $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'],
                            $value['buyer_sample'], $value['buyer_comment'] ];
        }

        // *** get garment size *** //
        $sizeChart  = $this->getSizeChart($id);
        $sizeMaster = $this->getSizeMasterDropdown($sizeChart);
        $PurposeData = ['Costing', 'Fab. Bulk Cons. Calculation', 'Dev. Sample Cutting', 'Order Conf. Sample Cuttting',
                            'Shipment Sample Cuttting', 'Production Bulk Cutting', 'Others'];
        $column = [
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "P.O. No. /\n Enq. Ref. No.", 'width' => '8%', 'align' => 'left', 'type' => 'text', 'readOnly' => true],
            ['title' => "Combo / Colour", 'width' => '8%', 'aligh' => 'left', 'type' => 'text', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'type' => 'text', 'readOnly' => true],
            ['title' => "Size Spec", 'width' => '8%', 'align'=> 'left', 'type' => 'text', 'readOnly' => true],
            ['title' => "Requirement", 'width' => '8%', 'align'=> 'left', 'type' => 'text', 'readOnly' => true],
            ['title' => "Purpose", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $PurposeData, 'readOnly' => $readStatus],
            ['title' => "Category", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => ['New', 'In-line', 'Revised'], 'readOnly' => $readStatus],
            ['title' => "If Revised or In-line\nPrevious CAD Ref. No.", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => [], 'readOnly' => true],
            ['title' => "Required\nSize(s)", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $sizeMaster, 'multiple' => true, 'readOnly' => $readStatus ],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $result;
        $output['requestData'] = $att_ref_data;
        $output['req_data'] = $req_data;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }
    
    function getSizeChart($enqId = '')
    {
        $this->db->select('size_ids');
        $query = $this->db->get_where('tbl_pc_size_chart', array('enquiry_id' => $enqId));
        //return $ArrRes->row()->size_ids;
         if ($query->num_rows() > 0) {
        // Return the 'size_ids' value from the first row
        return $query->row()->size_ids;
    } else {
        // Return a default value if no result is found
        return null; // or return ''; based on your use case
    }
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

    public function updateCadAuthorizationn($data)
    {
        $requestValue['auth_date'] = $this->mysqldatetime;
        $requestValue['mgmt_approval'] = $data['auth_status'];
        $requestValue['auth_status'] = $data['auth_status'];
        $requestValue['auth_type'] = $data['auth_type'];
        $requestValue['auth_by'] = $this->userid;
        $requestValue['mgmt_remark'] = $data['mgmt_remark'];
        $requestValue['log'] = LOGTIME;

        

        $this->db->where('request_id', $data['request_id']);
        $this->db->update('tbl_request', $requestValue);
    }

    public function getSampleListt() {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name 
                FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as d ON a.req_by = d.id
                WHERE a.type = 2 AND a.deprt_approval=0 AND a.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY a.log DESC";
        $result = $this->db->query($sql)->result_array();
        
        $sam_sql = "SELECT *
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample as e on a.sample_requirement_id=e.sample_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE b.type = 2 and b.deprt_approval=0 ORDER BY b.log DESC";
        $sam_data = $this->db->query($sam_sql)->result_array();

        foreach ($result as $key => $res) {
            $arr = $s_arr = [];
            
            foreach ($sam_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['sample_requirement']);
                    $status = '';
                    if($value['mgmt_approval'] == '0' || $value['mgmt_approval'] == '')
                    {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    } 
                    else if($value['mgmt_approval'] == '3') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
                    }
                    else if($value['mgmt_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                    }
                    array_push($s_arr, $status);
                }
            }
        
            $result[$key]['sample_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['sample_status'] = implode(' <br /> ', $s_arr);
    
        }

	    return $result;
    }

    public function getBOMListt() {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name 
                FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as d ON a.req_by = d.id
                WHERE a.type = 3 AND a.deprt_approval=0 AND a.draft_status=0 AND a.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY log DESC ";
        $result = $this->db->query($sql)->result_array();
	    return $result;
    }

    public function getBOM2Listt() {
       $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name 
                FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                INNER JOIN ".KN_USERS." as d ON a.req_by = d.id
                WHERE a.type = 4 AND a.deprt_approval=0 AND a.draft_status=0 AND a.subscriberid = " . $this->db->escape($this->subscriberid)." ORDER BY log DESC ";
        $result = $this->db->query($sql)->result_array();
	    return $result;
    }


    //  public function getBOM2Listt() {
    //     $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name
    //             FROM tbl_request as a 
    //             inner join kn_order_enquiry as b on a.enquiry_id=b.id 
    //             inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
    //             LEFT JOIN ".KN_USERS." as d ON a.auth_by = d.id
    //             WHERE a.type = 4 AND a.deprt_approval=0 AND a.draft_status=0  and a.req_by = ".$this->db->escape($this->userid)." ORDER BY a.log DESC";
    //             //WHERE a.flag=1 AND a.type = 3 AND a.deprt_approval=0 AND a.draft_status=0";
    //     $result = $this->db->query($sql)->result_array();
	//     return $result;
    // }
    function getRequestDataa($enqId, $reqId)
    {
        $sql = "SELECT * from tbl_request a
                LEFT JOIN ".KN_USERS." b ON a.auth_by=b.id
                WHERE a.request_id='$reqId' ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    

    public function getWIPListt()
    {
        $this->db->select('e.id,ids,e.datecreated AS 
        formattedDateCreated,e.dateupdated AS 
        formattedDateUpdated,e.dateauthorized,e.isriorno,e.isriorcode,e.companyid,e.reqforisrior,e.stylenamerefno,orderenqrefno,
        e.exporderqty,e.orderstatus,pcs_set,e.mgmtid,brandname,e.status,poNoEnqRefNo,pcs_set,shipmentSubDate AS formattedShipmentSubDate, e.pcsorset');
        $this->db->from(KN_ORDER_ENQUIRY . ' AS e');
        //$this->db->where('e.companyid', $this->companyid);
        $this->db->where('e.subscriberid',  $this->subscriberid);
       // commented line no :748 by me regards shown approved datas for all management
       //$this->db->where('e.mgmtid', $this->userid);
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

    public function getISRListt()
    {
        $this->db->select('e.id,ids,e.datecreated AS 
        formattedDateCreated,e.dateupdated AS 
        formattedDateUpdated,e.isriorno,e.isriorcode,e.companyid,e.reqforisrior,e.stylenamerefno,orderenqrefno,
        e.exporderqty,e.orderstatus,pcs_set,e.mgmtid,brandname,e.status,poNoEnqRefNo,
		poQtySampleQty,pcs_set,shipmentSubDate AS formattedShipmentSubDate, e.pcsorset');
        $this->db->from(KN_ORDER_ENQUIRY . ' AS e');
        $this->db->where('e.companyid', $this->companyid);
        $this->db->where('e.subscriberid', $this->subscribid );
        $this->db->where('e.mgmtid', $this->userid);
        $this->db->where('e.status !=', '3');
        $this->db->where('e.orderstatus', '2');
        $this->db->where('e.reqforisrior', '1');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = e.brandId');
        $this->db->join(DELIVERY_SCHEDULE_WIP_LIST . ' AS del', 'del.referenceid = e.id');
      
	    $query = $this->db->get();
          //print_r($query);
	    $result = $query->result();
       // print_r($result);
	    return $result;
    }

    public function getIORListt()
    {
        $this->db->select('e.id,ids,e.datecreated AS 
        formattedDateCreated,e.dateupdated AS 
        formattedDateUpdated,e.isriorno,e.isriorcode,e.companyid,e.reqforisrior,e.stylenamerefno,orderenqrefno,
        e.exporderqty,e.orderstatus,pcs_set,e.mgmtid,brandname,e.status,poNoEnqRefNo,
		poQtySampleQty,pcs_set,shipmentSubDate AS formattedShipmentSubDate, e.pcsorset');
        $this->db->from(KN_ORDER_ENQUIRY . ' AS e');
        $this->db->where('e.companyid', $this->companyid);
        $this->db->where('e.subscriberid', $this->subscriber_id);
        $this->db->where('e.mgmtid', $this->userid);
        $this->db->where('e.status !=', '3');
        $this->db->where('e.orderstatus', '2');
        $this->db->where('e.reqforisrior', '2');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = e.brandId');
        $this->db->join(DELIVERY_SCHEDULE_WIP_LIST . ' AS del', 'del.referenceid = e.id');
	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
    }

    public function search2wiplistt($data)
    {
        $this->db->select('e.id,ids,e.datecreated AS 
        formattedDateCreated,e.dateupdated AS 
        formattedDateUpdated,e.dateauthorized,e.isriorno,e.isriorcode,e.companyid,e.reqforisrior,e.stylenamerefno,orderenqrefno,
        e.exporderqty,e.orderstatus,pcs_set,e.mgmtid,brandname,e.status,poNoEnqRefNo,pcs_set,shipmentSubDate AS formattedShipmentSubDate, e.pcsorset');
        $this->db->from(KN_ORDER_ENQUIRY . ' AS e');
        $this->db->where('e.companyid', $this->companyid);
       // commented line no :748 by me regards shown approved datas for all management
       //$this->db->where('e.mgmtid', $this->userid);
        $this->db->where('e.status !=', '3');
        $this->db->where('e.orderstatus', '2');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = e.brandId');
        $this->db->join(DELIVERY_SCHEDULE_WIP_LIST . ' AS del', 'del.referenceid = e.id');
        if (!empty($data['status'])) {
            $this->db->where('e.status', $data['status']);
        }
        if (!empty($data['ior'])) {
            $this->db->where('e.reqforisrior', 2);
        }
        if (!empty($data['isr'])) {
            $this->db->where('e.reqforisrior', 1);
        }
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
    

    
    function getRequestData($enqId, $reqId)
    {
        $sql = "SELECT *
                from tbl_request a
                where a.request_id='$reqId' ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }



}