<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class indentsmodel extends CI_Model {
    public function __construct() {
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
    }

    public function bomIndentListDataTables() {
        $this->dataTablesBomIndentListQry();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function dataTablesBomIndentListQry() {
        $this->db->select('a.id,a.mgmtcurrentstatus,DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") AS formattedDateUpdated,
        DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") as formattedDateCreated,
        a.approvaltype,queueno,a.deptcurrentstatus,a.requestrefno,a.merchantid,a.mgmtid,brandname,oe.isriorcode,si.bom_mat_ind_ref_no,
DATE_FORMAT(bomindentcutoffdatetime,"%d-%m-%Y %H:%i:%s") AS indentcutoffdt,bomissuedto,bomi.status,u.contactname,appT.types as appType');
        $this->db->from(KN_ALLREQUEST . ' AS a');
        $this->db->where('a.companyid', $this->companyid);
        $this->db->where('a.queueno !=','0');
        $this->db->join(KN_SAMPLE_REQUEST. ' AS si', 'a.id = si.requestrefid');
        $this->db->join(KN_MERCHANT_SAMPLE_BOM_INDENT . ' AS bomi','bomi.requestid = a.id');
        $this->db->join(KN_ORDER_ENQUIRY . ' AS oe', 'a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_USERS . ' AS u', 'a.mgmtid = u.id');
        $this->db->join(KN_MASTER_REQUEST_TYPE.' AS appT','appT.id = a.approvaltype');
        $i = 0;
        $column_order = array('','oe.isriorcode', '', 'queueno', 'bomissuedto', 'bom_mat_ind_ref_no','a.datecreated', 'bomindentcutoffdatetime','approvaltype',
            'contactname', '', 'a.dateupdated','a.status');

        $column_search = array('oe.isriorcode','queueno','bomissuedto','bom_mat_ind_ref_no', 'bomindentcutoffdatetime','appT.types',
            'u.contactname','a.datecreated', 'a.dateupdated');

        foreach ($column_search as $item) {
            if ($this->input->post('search')['value']) {
                if(validateDate($_POST['search']['value'])) {
                    $_POST['search']['value'] = date('Y-m-d',strtotime($_POST['search']['value']));
                }
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }
                if (count($column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if (!empty($this->input->post('order'))) {
            $this->db->order_by($column_order[$this->input->post('order')['0']['column']], $this->input->post('order')['0']['dir']);
        } else if (isset($order)) {

            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function bomIndentCount_all() {
        $this->db->from(KN_ALLREQUEST . ' AS a');
        $this->db->where('a.companyid', $this->companyid);
        $this->db->join(KN_SAMPLE_REQUEST. ' AS si', 'a.id = si.requestrefid');
        $this->db->join(KN_MERCHANT_SAMPLE_BOM_INDENT . ' AS bomi','bomi.requestid = a.id');
        $this->db->join(KN_ORDER_ENQUIRY . ' AS oe', 'a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_USERS . ' AS u', 'a.mgmtid = u.id');
        $this->db->where('a.queueno !=','0');
        $this->db->join(KN_MASTER_REQUEST_TYPE.' AS appT','appT.id = a.approvaltype');
        return $this->db->count_all_results();
    }

    public function bomIndentCount_filtered() {
        $this->dataTablesBomIndentListQry();
        $query = $this->db->get();
        return $query->num_rows();
    }

    /******************************************/

    public function cadIndentListDataTables() {
        $this->dataTablesCadIndentListQry();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function dataTablesCadIndentListQry() {
        $this->db->select('a.id,a.mgmtcurrentstatus,DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") AS formattedDateUpdated,
        DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") as formattedDateCreated,
        a.approvaltype,queueno,a.deptcurrentstatus,a.requestrefno,a.merchantid,a.mgmtid,brandname,oe.isriorcode,si.cad_mat_ind_ref_no,
DATE_FORMAT(cadindentcutoffdatetime,"%d-%m-%Y %H:%i:%s") AS indentcutoffdt,cadissuedto,cadi.status,u.contactname');
        $this->db->from(KN_ALLREQUEST . ' AS a');
        $this->db->where('a.companyid', $this->companyid);
        $this->db->where('a.queueno !=','0');
        $this->db->order_by('a.dateupdated','desc');
        $this->db->join(KN_SAMPLE_REQUEST. ' AS si', 'a.id = si.requestrefid');
        $this->db->join(KN_MERCHANT_SAMPLE_CAD_INDENT . ' AS cadi','cadi.requestid = a.id');
        $this->db->join(KN_ORDER_ENQUIRY . ' AS oe', 'a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');        
        $this->db->join(KN_USERS . ' AS u', 'a.mgmtid = u.id');
        $i = 0;
        $column_order = array('','oe.isriorcode', '', 'queueno', 'cadissuedto', 'cad_mat_ind_ref_no','a.datecreated', 'cadindentcutoffdatetime','approvaltype',
            'contactname', '', 'a.dateupdated','a.status');

        $column_search = array('oe.isriorcode','queueno','cadissuedto','cad_mat_ind_ref_no', 'cadindentcutoffdatetime','approvaltype',
            'u.contactname','a.datecreated', 'a.dateupdated');

        foreach ($column_search as $item) {
            if ($this->input->post('search')['value']) {
                if(validateDate($_POST['search']['value'])) {
                    $_POST['search']['value'] = date('Y-m-d',strtotime($_POST['search']['value']));
                }
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }
                if (count($column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if (!empty($this->input->post('order'))) {
            $this->db->order_by($column_order[$this->input->post('order')['0']['column']], $this->input->post('order')['0']['dir']);
        } else if (isset($order)) {

            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function cadIndentListCountFiltered() {
        $this->dataTablesCadIndentListQry();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function cadIndentListCountAll() {
        $this->db->from(KN_ALLREQUEST . ' AS a');
        $this->db->where('a.companyid', $this->companyid);
        $this->db->join(KN_SAMPLE_REQUEST. ' AS si', 'a.id = si.requestrefid');
        $this->db->join(KN_MERCHANT_SAMPLE_CAD_INDENT . ' AS cadi','cadi.requestid = a.id');
        $this->db->join(KN_ORDER_ENQUIRY . ' AS oe', 'a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');        
        $this->db->join(KN_USERS . ' AS u', 'a.mgmtid = u.id');
        $this->db->where('a.queueno !=','0');
        return $this->db->count_all_results();
    }

    public function dataTablesFabIndentListQry() {
        $this->db->select('a.id,a.mgmtcurrentstatus,DATE_FORMAT(a.dateupdated,"%d-%m-%Y %H:%i:%s") AS formattedDateUpdated,
        DATE_FORMAT(a.datecreated,"%d-%m-%Y %H:%i:%s") as formattedDateCreated,
        a.approvaltype,queueno,a.deptcurrentstatus,a.requestrefno,a.merchantid,a.mgmtid,brandname,oe.isriorcode,si.fab_mat_ind_ref_no,
DATE_FORMAT(fabindentcutoffdatetime,"%d-%m-%Y %H:%i:%s") AS indentcutoffdt,fabissuedto,fabi.status,u.contactname,
appT.types as appType');
        $this->db->from(KN_ALLREQUEST . ' AS a');
        $this->db->where('a.companyid', $this->companyid);
        $this->db->where('a.queueno !=','0');
        $this->db->join(KN_SAMPLE_REQUEST. ' AS si', 'a.id = si.requestrefid');
        $this->db->join(KN_MERCHANT_SAMPLE_FAB_INDENT . ' AS fabi','fabi.requestid = a.id');
        $this->db->join(KN_ORDER_ENQUIRY . ' AS oe', 'a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_USERS . ' AS u', 'a.mgmtid = u.id');
        $this->db->join(KN_MASTER_REQUEST_TYPE.' AS appT','appT.id = a.approvaltype');
        $i = 0;
        $column_order = array('','oe.isriorcode', '', 'queueno', 'fabissuedto', 'fab_mat_ind_ref_no','a.datecreated', 'fabindentcutoffdatetime','approvaltype',
            'contactname', '', 'a.dateupdated','a.status');

        $column_search = array('oe.isriorcode','queueno','fabissuedto','fab_mat_ind_ref_no', 'fabindentcutoffdatetime','appT.types',
            'u.contactname','a.datecreated', 'a.dateupdated');

        foreach ($column_search as $item) {
            if ($this->input->post('search')['value']) {
                if(validateDate($_POST['search']['value'])) {
                    $_POST['search']['value'] = date('Y-m-d',strtotime($_POST['search']['value']));
                }
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }
                if (count($column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if (!empty($this->input->post('order'))) {
            $this->db->order_by($column_order[$this->input->post('order')['0']['column']], $this->input->post('order')['0']['dir']);
        } else if (isset($order)) {

            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function fabIndentListDataTables() {
        $this->dataTablesFabIndentListQry();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function fabIndentListCountFiltered() {
        $this->dataTablesFabIndentListQry();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function fabIndentListCountAll() {
        $this->db->from(KN_ALLREQUEST . ' AS a');
        $this->db->where('a.companyid', $this->companyid);
        $this->db->join(KN_SAMPLE_REQUEST. ' AS si', 'a.id = si.requestrefid');
        $this->db->join(KN_MERCHANT_SAMPLE_FAB_INDENT . ' AS fabi','fabi.requestid = a.id');
        $this->db->join(KN_ORDER_ENQUIRY . ' AS oe', 'a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');
        $this->db->join(KN_USERS . ' AS u', 'a.mgmtid = u.id');
        $this->db->where('a.queueno !=','0');
        $this->db->join(KN_MASTER_REQUEST_TYPE.' AS appT','appT.id = a.approvaltype');
        return $this->db->count_all_results();

        /*$this->db->from(KN_SAMPLEREQUESTINDENTDETAILS . ' AS si');
        $this->db->where('a.companyid', $this->companyid);
        $this->db->where('indenttype', '3');
        $this->db->join(KN_ALLREQUEST . ' AS a', 'a.id = si.requestid');
        $this->db->join(KN_ORDER_ENQUIRY . ' AS oe', 'a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = oe.brandId');       
        $this->db->join(KN_USERS . ' AS u', 'a.mgmtid = u.id');
        return $this->db->count_all_results();*/
    }
}