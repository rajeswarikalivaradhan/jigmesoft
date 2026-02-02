<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MerchantrRequestSentModel extends CI_Model
{
    private $mysqldatetime;
    public function __construct()
    {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo   = fnGetUserLoggedInfo('1');
        $this->companyid     = $ArrUserLoggedInfo['companyid'];
        $this->mysqldatetime = date('d/m/Y h:i A');
        $this->userid        = $ArrUserLoggedInfo['id'];
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

    // ********** CAD REQUEST STARTS HERE *********** /

    public function getManageAllListt() {
        
    }

    // ********** CAD REQUEST ENDS HERE *********** /

    
    // ********** CAD REQUEST STARTS HERE *********** /

    public function getCadRequestDetailss($id) {
        $sql = "SELECT * FROM tbl_cad_requirement as a WHERE a.enquiry_id = " . $id . " AND a.flag=1 and a.req_sent_status = 0";
        $data = $this->db->query($sql)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['cad_requirement_id'], $value['req_sent_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['cad_requirement'], "", "", "", ""];
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $output['data'] = $result;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }
    // ********** CAD REQUEST ENDS HERE *********** /

    // ********** CREATE CAD REQUEST STARTS HERE *********** /

    public function createCadRequestt($req_data, $id, $req_type, $cutoff_date, $merchant_note) {

        // print_r($req_data);
        $requestValue['enquiry_id'] = $id;
        $requestValue['type'] = 1;
        $requestValue['req_type'] = $req_type;
        $requestValue['req_date'] = $this->mysqldatetime;
        $requestValue['cutoff_date'] = $cutoff_date;
        $requestValue['merchant_note'] = $merchant_note;
        $requestValue['log'] = LOGTIME;

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
                $cadValue["grad_measure_chart"] = $value[5];
                $cadValue["artwork"] = $value[6];
                $cadValue["measure_details"] = $value[7];
                $cadValue["buyer_sample"] = $value[8];
                $cadValue["buyer_comment"] = $value[9];
                $cadValue['log'] = LOGTIME;

                $this->db->insert('tbl_request_cad', $cadValue);
                // update request sent status 
                $this->db->where('cad_requirement_id', $value[0]);
                $this->db->update('tbl_cad_requirement', array('req_sent_status' => 1));
            }
        }
    }
    // ********** CREATE CAD REQUEST ENDS HERE *********** /
}
