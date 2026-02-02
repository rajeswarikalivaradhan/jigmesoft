<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Myarnstoremodel extends CI_Model
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

    function getRequestDataa($enqId, $reqId)
    {
        $sql = "SELECT * from tbl_request a 
                -- INNER JOIN tbl_request_yarn b ON a.request_id = b.request_id
                where a.request_id='$reqId' and a.flag=1";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    
    // ********** SAMPLE REQUEST STARTS HERE *********** /

    public function getStorepiListt() {
        $sql = "SELECT a.*, c.brandname, b.orderenqrefno, d.*, e.*, g.vendorname FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                inner join tbl_request_purchase_indent as d on a.request_id=d.request_id
                inner join tbl_request_bom as e on a.request_id=e.request_id
                inner join tbl_request_payment as f on a.request_id=f.request_id
                inner join kn_master_bom_vendor as g on f.vendor_id=g.id
                where a.type=3 and a.mgmt_approval=1 and a.deprt_approval=1 and a.flag=1 and e.req_status=1";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    public function getPIRequestSendDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                INNER JOIN tbl_request_payment d on b.request_id=d.request_id
                INNER JOIN tbl_request_purchase_indent e on b.request_id=e.request_id
                INNER JOIN kn_master_bom_vendor f on d.vendor_id=f.id
                WHERE b.request_id = " . $reqId . " AND b.flag=1 AND b.qa_req_status = 1";
        $data = $this->db->query($sql)->result_array();

        $vendor_sql = "SELECT *, vendorname as name FROM kn_master_bom_vendor";
        $vendor_data = $this->db->query($vendor_sql)->result_array();

        $withinResult = $interResult = $importsResult = $paymentRequst = [];

        $vendor_id = '';

        foreach ($data as $key => $value)
        {

            $vendor_id = $value['vendor_id'];

            $bcm = $value['blend'].' / '.$value['content'].' / '.$value['material'];

            $withinResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $bcm,
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $value['unit_rate'], 
                $value['amount'], $value['gst'], $value['cgst'], $value['cgst_value'], 
                $value['sgst'], $value['sgst_value'], $value['sub_total']
            ];

            $interResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $bcm,
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $value['unit_rate'], 
                $value['amount'], $value['igst'], $value['igst_value'], $value['sub_total']
            ];

            $importsResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $bcm,
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $value['currency'], 
                $value['unit_rate'], $value['amount']
            ];

            $paymentRequst[$key] = [ $value['request_id'], $value['vendor_id'], $value['proforma_no'], $value['proforma_date'],
                $value['proforma_value'], $value['qyoted_currency'], $value['mode_of_payment'],
                $value['pay_by_date'], $value['amount_payable'], $value['currency'], $value['vendor_bank_name'],
                $value['acc_no'], $value['ifsc'], $value['shift_code']
            ];

        }

        $modeOfShipment = [ 'ON-LINE', 'CHEQUE' ];
        $currencyList = unserialize(ARRCURRENCYLIST);

        $output['withinStateDetails'] = $withinResult;
        $output['interStateDetails'] = $interResult;
        $output['importStateDetails'] = $importsResult;
        $output['paymentRequst'] = $paymentRequst;
        $output['advancepaiddetails'] = [];
        $output['vendor_data'] = $vendor_data;
        $output['vendor_id'] = $vendor_id;
        $output['fullData'] = $data;
        $output['modeOfShipment'] = $modeOfShipment;
        $output['currencyList'] = $currencyList;
        return $output;
    }
    
    public function getMerchantBomQueueDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND b.qa_req_status = 1";
        $data = $this->db->query($sql)->result_array();

        $uomData = unserialize(ARRUNITOFMEASURE);

        $inhousestatusdetails = [];
        $itemacceptstatus = [];
        $inhouseconsolidatedqtydetails = [];
        
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['itemacceptstatus'] = $itemacceptstatus;
        $output['inhouseconsolidatedqtydetails'] = $inhouseconsolidatedqtydetails;
        $output['uomData'] = $uomData;
        return $output;
    }


    public function getSampleRequestDetailss($id) {
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id 
                WHERE a.enquiry_id = ".$id." AND a.flag=1 and a.req_sent_status = 0 ORDER BY a.sample_requirement_id asc";
        $data = $this->db->query($sql)->result_array();
        
        $result = [];
        $referResult = [];
        $ref_status = 0;
        $req_id = "";

        // return $data;
        foreach ($data as $key => $value)
        {
            $ref_status += (int) $value['req_reference_status'];
            $req_id = $value['request_id'];
            if($value['req_reference_status'] == "1" || $value['req_reference_status'] == 1) 
            {
                $result[$key] = ['edit', $value['sample_requirement_id'], $value['req_reference_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['sample_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value['req_size'], $value['req_qty'] ];

                $referResult[$key] = ['edit', $value['sample_requirement_id'], $value['req_reference_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];
            }
            else {
                $result[$key] = ['', $value['sample_requirement_id'], $value['req_reference_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                        $value['spec_code_id'], $value['sample_requirement'], "", "", "", $value['req_size'], $value['req_qty'] ];
            }
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);

        $output['data'] = $result;
        $output['sizeData'] = $sizeMaster;
        $output['ref_status'] = $ref_status;
        $output['req_id'] = $req_id;
        $output['referResult'] = array_values($referResult);
        return $output;
    }

    // ********** SAMPLE REQUEST ENDS HERE *********** /

    // ********** CREATE SAMPLE REQUEST STARTS HERE *********** /

    public function createSampleRequestt($req_data, $id, $req_type, $cutoff_date, $merchant_note, $mode, $req_id) {

        // print_r($req_data);
        $requestValue['enquiry_id'] = $id;
        $requestValue['type'] = 2;
        $requestValue['req_type'] = $req_type;
        $requestValue['req_date'] = $this->mysqldatetime;
        $requestValue['cutoff_date'] = $cutoff_date;
        $requestValue['merchant_note'] = $merchant_note;
        $requestValue['log'] = LOGTIME;
        // print_r($req_data);
        // exit();
        if($mode == "add") {
            $this->db->insert('tbl_request', $requestValue);
            $primaryId = $this->db->insert_id();        
            if($primaryId) {
                foreach($req_data as $key => $value) {
                    $sampleValue["request_id"] = $primaryId;
                    $sampleValue["sample_id"] = $value[1];
                    $sampleValue["purpose"] = $value[2];
                    $sampleValue["category"] = $value[3];
                    $sampleValue["if_revised"] = $value[4];
                    $sampleValue["reqrd_size"] = $value[5];
                    $sampleValue["qtypcs"] = $value[6];
                    $sampleValue["grad_measure_chart"] = $value[7];
                    $sampleValue["artwork"] = $value[8];
                    $sampleValue["measure_details"] = $value[9];
                    $sampleValue["buyer_sample"] = $value[10];
                    $sampleValue["buyer_comment"] = $value[11];
    
                    $this->db->insert('tbl_request_sample', $sampleValue);
                    // update request sent status 
                    $this->db->where('sample_requirement_id', $value[1]);
                    $this->db->update('tbl_sample_requirement', array('req_reference_status' => 1));
                }
            }
        } else {
            if($value[0] == "edit") {

                foreach($req_data as $key => $value) {
                    // $sampleValue_update["request_id"] = $primaryId;
                    // $sampleValue_update["sample_id"] = $value[0];
                    $sampleValue_update["purpose"] = $value[2];
                    $sampleValue_update["category"] = $value[3];
                    $sampleValue_update["if_revised"] = $value[4];
                    $sampleValue_update["reqrd_size"] = $value[5];
                    $sampleValue_update["qtypcs"] = $value[6];
                    $sampleValue_update["grad_measure_chart"] = $value[7];
                    $sampleValue_update["artwork"] = $value[8];
                    $sampleValue_update["measure_details"] = $value[9];
                    $sampleValue_update["buyer_sample"] = $value[10];
                    $sampleValue_update["buyer_comment"] = $value[11];

                    $this->db->where('sample_id', $value[1]);
                    $this->db->update('tbl_request_sample', $sampleValue_update);
                }
            } else if($value[1] == "") {
                foreach($req_data as $key => $value) {
                    $sampleValue_ins["request_id"] = $req_id;
                    $sampleValue_ins["sample_id"] = $value[1];
                    $sampleValue_ins["purpose"] = $value[2];
                    $sampleValue_ins["category"] = $value[3];
                    $sampleValue_ins["if_revised"] = $value[4];
                    $sampleValue_ins["reqrd_size"] = $value[5];
                    $sampleValue_ins["qtypcs"] = $value[6];
                    $sampleValue_ins["grad_measure_chart"] = $value[7];
                    $sampleValue_ins["artwork"] = $value[8];
                    $sampleValue_ins["measure_details"] = $value[9];
                    $sampleValue_ins["buyer_sample"] = $value[10];
                    $sampleValue_ins["buyer_comment"] = $value[11];
    
                    $this->db->insert('tbl_request_sample', $sampleValue_ins);
                    // update request sent status 
                    $this->db->where('sample_requirement_id', $value[1]);
                    $this->db->update('tbl_sample_requirement', array('req_reference_status' => 1));
                }
            }

        }
        
    }
    // ********** CREATE SAMPLE REQUEST ENDS HERE *********** /

    // ********** SAMPLE REQUEST STARTS HERE *********** /

    public function getManagementSampleRequestDetailss($id) {
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE a.enquiry_id = " . $id . " AND a.flag=1 and a.req_sent_status = 1";
        $data = $this->db->query($sql)->result_array();

        $req_sql = "SELECT * FROM tbl_request as a WHERE a.enquiry_id = " . $id . " AND a.flag=1 AND type=2";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $result = [];
        $att_result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], "", $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $output['data'] = $result;
        $output['attachmentdata'] = $att_result;
        $output['req_data'] = $req_data;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }

    // ********** SAMPLE REQUEST ENDS HERE *********** /
    
    // ********** CREATE SAMPLE REQUEST STARTS HERE *********** /

    public function updateManagementSampleRequestt($id, $auth_status, $auth_type, $mgmt_remark) {
        $requestValue['auth_status'] = $auth_status;
        $requestValue['mgmt_approval'] = $auth_status;
        $requestValue['auth_by'] = $this->userid;
        $requestValue['auth_date'] = $this->mysqldatetime;
        $requestValue['auth_type'] = $auth_type;
        $requestValue['mgmt_remark'] = $mgmt_remark;

        $this->db->where('request_id', $id);
        $this->db->update('tbl_request', $requestValue);
    }

    // ********** CREATE SAMPLE REQUEST ENDS HERE *********** /

    // ********** SAMPLE DEPARTMENT REQUEST STARTS HERE *********** /

    public function getDepartmentSampleRequestDetailss($id) {
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $id . " AND a.flag=1 and a.req_sent_status = 1 and c.mgmt_approval=1";
        $data = $this->db->query($sql)->result_array();

        $req_sql = "SELECT * FROM tbl_request as a WHERE a.request_id = " . $id . " AND a.flag=1 AND type=2 and a.mgmt_approval=1";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $result = [];
        $att_result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], "", $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];
        }
        
        $output['data'] = $result;
        $output['attachmentdata'] = $att_result;
        $output['req_data'] = $req_data;
        return $output;
    }

    // ********** SAMPLE DEPARTMENT REQUEST ENDS HERE *********** /

    // ********** UPDATE SAMPLE DEPARTMENT REQUEST STARTS HERE *********** /

    public function updateDepartmentSampleRequestt($id, $req_status) {
        $requestValue['req_status'] = $req_status;
        $requestValue['deprt_approval'] = $req_status;
        // $requestValue['qa_schd_date'] = $this->mysqldatetime;

        $this->db->where('request_id', $id);
        $this->db->update('tbl_request', $requestValue);
    }
    // ********** UPDATE SAMPLE DEPARTMENT REQUEST ENDS HERE *********** /

    // ********** SAMPLE QA REQUEST STARTS HERE *********** /

    public function getQASampleRequestDetailss($id) {
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $id . " AND a.flag=1 and a.req_sent_status = 1 and c.qa_approval=0";
        $data = $this->db->query($sql)->result_array();

        $req_sql = "SELECT * FROM tbl_request as a WHERE a.request_id = " . $id . " AND a.flag=1 AND type=2 and a.qa_approval=0";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $result = $att_result = $qa_status_result = $job_status_result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], "", $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

            $qa_status_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], '', $value['qa_req_date'], $value['qa_schd_date'], $value['qa_status'], $value['qa_status_update'] ];
                            
            $job_status_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sam_ref_no'], $value['job_schd_date'], $value['job_status'], $value['job_status_update'] ];
        }
        
        $output['data'] = $result;
        $output['attachmentdata'] = $att_result;
        $output['qastatusdata'] = $qa_status_result;
        $output['jobstatusdata'] = $job_status_result;
        $output['req_data'] = $req_data;
        return $output;
    }

    // ********** SAMPLE QA REQUEST ENDS HERE *********** /

    // ********** SAMPLE QA REQUEST STARTS HERE *********** /

    public function getQARequestDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . " AND a.flag=1 and a.req_sent_status = 1 and a.qa_req_status=0 and c.qa_approval=0";
        $data = $this->db->query($sql)->result_array();
        
        $result = [];

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($enqId);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $output['data'] = $data;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }

    // ********** SAMPLE QA REQUEST ENDS HERE *********** /

    // ********** UPDATE SAMPLE DEPARTMENT REQUEST STARTS HERE *********** /

    public function updateQARequestDetailss($id, $data, $date, $note) {

        $qa_pending_sql = "SELECT * FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $id . " AND a.flag=1 and a.req_sent_status = 1 and a.qa_req_status=0";
        $qa_pending_data = $this->db->query($qa_pending_sql)->result_array();

        $qa_update_sql = "SELECT * FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $id . " AND a.flag=1 and a.req_sent_status = 1 and a.qa_req_status=1";
        $qa_update_data = $this->db->query($qa_update_sql)->result_array();

        $req_data = json_decode($data);

        foreach($req_data as $key => $value) {
            $sampleValue["qa_req_status"] = 1;
            $sampleValue["qa_req_date"] = $this->mysqldatetime;
            $sampleValue["qa_cutoff_date"] = $date;
            $sampleValue["sam_dept_note"] = $note;
            $this->db->where('sample_requirement_id', $value[0]);
            $this->db->update('tbl_sample_requirement', $sampleValue);
        }

        if(sizeof($qa_pending_data) == sizeof($qa_update_data)) {
            $this->db->where('request_id', $id);
            $this->db->update('tbl_request', array('qa_approval'=>1));
            $result['status'] = "200";
            return $result;
        }
        else {
            $result['status'] = "201";
            return $result;
        }

    }

    // ********** UPDATE SAMPLE DEPARTMENT REQUEST ENDS HERE *********** /

    // ********** SAMPLE QA QUEUE STARTS HERE *********** /

    public function getQAQueueDetailss($enqId, $samReqId) {
        $sql = "SELECT * FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE a.sample_requirement_id = " . $samReqId;
        $data = $this->db->query($sql)->result_array();
        
        $result = $att_result = $qa_status = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], '', $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

            $qa_status[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['qa_req_date'], $value['qa_schd_date'], $value['qa_status'], $value['qa_status_update'] ];
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($enqId);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $output['data'] = $result;
        $output['req_data'] = $data;
        $output['attachmentdata'] = $att_result;
        $output['qa_status_data'] = $qa_status;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }

    // ********** SAMPLE QA QUEUE ENDS HERE *********** /

    // ********** UPDATE SAMPLE DEPARTMENT QUEUE STARTS HERE *********** /

    public function updateQAQueueDetailss($id, $data, $note) {

        $req_data = json_decode($data);

        foreach ($req_data as $key => $value)
        {
            if($value[7] == '') {
                $sampleValue["qa_schd_date"] = $this->mysqldatetime;
            }
            $sampleValue["qa_status"] = $value[8];
            $sampleValue["qa_status_update"] = $this->mysqldatetime;
            $sampleValue["qa_dept_remarks"] = $note;
            $this->db->where('sample_requirement_id', $value[0]);
            $this->db->update('tbl_sample_requirement', $sampleValue);
        }

    }

    // ********** UPDATE SAMPLE DEPARTMENT QUEUE ENDS HERE *********** /   

    // ********** MERCHANT SAMPLE QUEUE STARTS HERE *********** /

    public function getMerchantSampleQueueDetailss($enqId, $samReqId) {
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE a.sample_requirement_id = " . $samReqId . " AND a.flag=1 and a.req_sent_status = 1";
        $data = $this->db->query($sql)->result_array();
        
        $result = $att_result = $qa_status_result = $job_status_result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], "", $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

            $qa_status_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], '', $value['qa_req_date'], $value['qa_schd_date'], $value['qa_status'], $value['qa_status_update'] ];
                            
            $job_status_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sam_ref_no'], $value['job_schd_date'], $value['job_status'], $value['job_status_update'] ];
        }
        
        $output['data'] = $result;
        $output['attachmentdata'] = $att_result;
        $output['qastatusdata'] = $qa_status_result;
        $output['jobstatusdata'] = $job_status_result;
        $output['req_data'] = $data;
        return $output;
    }

    // ********** MERCHANT SAMPLE QUEUE ENDS HERE *********** /

    // ********** SAMPLE QUEUE LIST DETAIL STARTS HERE *********** /

    public function getSampleQueueDetailss($enqId, $samReqId) {
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE a.sample_requirement_id = " . $samReqId . " AND a.flag=1 and a.req_sent_status = 1";
        $data = $this->db->query($sql)->result_array();
        
        $result = $att_result = $qa_status_result = $job_status_result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], "", $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($enqId);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $output['data'] = $result;
        $output['attachmentdata'] = $att_result;
        $output['req_data'] = $data;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }

    // ********** SAMPLE QUEUE LIST DETAIL ENDS HERE *********** /

    public function getOrderStockListt()
    {
        return [];
    }

    public function getSurplusStockListt()
    {
        return [];
    }

    public function getGeneralStockListt()
    {
        return [];
    }
     

}
