<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cadmodel extends CI_Model {

    private $mysqlDatetime;
    private $companyId;
    private $userId;

    public function __construct() {
        parent::__construct();
        $ArrUserLoggedInfo      = fnGetUserLoggedInfo('1');
        $this->companyId        = $ArrUserLoggedInfo['companyid'];
        $this->userId           = $ArrUserLoggedInfo['id'];
        $this->mysqlDatetime    = date('Y-m-d H:i:s');
    }

    public function cadRequestAttachmentDetails($VarRequestId) {
        $this->db->select('jsonAttachmentDetails');
        $VarQry    = $this->db->get_where(KN_CAD_REQUEST, array('requestrefid' => $VarRequestId));
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }

    var $queueColOrder = array('','oe.id','brandname','queueno','','formattedDateCreated','formattedCutOffDt','a.approvaltype',
        'u.contactname','mu.contactname','current_status','formattedDateUpdated','a.status');
    var $queueColSearch = array('oe.isriorcode','brandname','queueno','a.datecreated','cutoffdatetime','a.status','u.contactname','mu.contactname','current_status',
        'a.dateupdated','approvaltype');

    public function dataTablesQueueListQry() {
        $this->db->select('a.id,DATE_FORMAT(cutoffdatetime,"%d-%m-%Y %H:%i:%s") AS formattedCutOffDt,DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") AS 
        formattedDateCreated,DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") AS formattedDateUpdated,
        a.status,a.mgmtcurrentstatus,a.approvaltype,a.queuecompletestatus,a.queueno,a.deptcurrentstatus,
        a.requestrefno,a.current_status,oe.isriorcode,jsondatagrid,u.contactname as mgmt,brandname,mu.contactname as merchantName');
        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->where('a.queueno !=','0');
        $this->db->where('a.request_type_dept',"CAD");
        $this->db->order_by('a.qid','desc');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.mgmtid = u.id');
        $this->db->join(KN_USERS.' AS mu','a.merchantid = mu.id');
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

    public function getQueueListDataTables() {
        $this->dataTablesQueueListQry();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function queueCountFiltered() {
        $this->dataTablesQueueListQry();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function queueCountAll() {
        $this->db->from(KN_ALLREQUEST.' AS a');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->where('a.queueno !=','0');
        $this->db->where('a.request_type_dept',"CAD");
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.mgmtid = u.id');
        $this->db->join(KN_USERS.' AS mu','a.merchantid = mu.id');
        return $this->db->count_all_results();
    }


}