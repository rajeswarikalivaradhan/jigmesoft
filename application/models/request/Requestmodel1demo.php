<?php
error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RequestBomModel extends CI_Model
{
    private $mysqldatetime;
    public function __construct()
    {
        fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo   = fnGetUserLoggedInfo('1');
        $this->companyid     = $ArrUserLoggedInfo['companyid'];
        $this->mysqldatetime = date('d/m/Y h:i A');
        $this->userid        = $ArrUserLoggedInfo['id'];
        $this->subscriberId = $ArrUserLoggedInfo['subscriber_id'];
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
    
    // ********** SAMPLE REQUEST STARTS HERE *********** /

    public function getPurchaseRequestDetailss($id) {
        $sql = "SELECT *, SUM(`req_bom_qty`) as calc_bom_qty FROM tbl_bom_article_1_requirement as a 
                where a.enquiry_id='$id' AND a.flag=1
                GROUP BY a.item_desc, a.blend, a.content, a.material, a.garment_size, a.appr_item_code, 
                        a.appr_item_col_code, a.size_dim, a.uom ORDER BY appr_item_code";
        $data = $this->db->query($sql)->result_array();
        
        $sql1 = "SELECT * FROM tbl_bom_article_1_req_consld as a 
                LEFT JOIN tbl_request as b ON a.request_id=b.request_id
                WHERE a.enquiry_id='$id' AND a.flag=1";
        $data1 = $this->db->query($sql1)->result_array();
        //print_r($sql1); exit;

        $sql2 = "SELECT * FROM tbl_bom_1_sourcing_details as a 
                INNER JOIN kn_master_bom_vendor as b ON a.vendor_name_address=b.id
                WHERE a.enquiry_id='$id' AND a.flag=1";
        $data2 = $this->db->query($sql2)->result_array();

        $sql3 = "SELECT * FROM tbl_bom_article_1_requirement as a 
                where a.enquiry_id='$id' AND a.flag=1
                GROUP BY a.item_desc";

        $data3 = $this->db->query($sql3)->result_array();
        $req_data = '';
        $result = [];
        $sourcing_result = [];

        for($i=0; $i < sizeof($data); $i++)
        {

            if( isset($data1[$i]['draft_consl_bom_qty'])) {
                $calc_bom_qty = $data1[$i]['draft_consl_bom_qty'];
                $data[$i]['calc_bom_qty'] = $data1[$i]['draft_consl_bom_qty'];
            }
            else {
                $calc_bom_qty = "";
                $data[$i]['calc_bom_qty'] = "";
            }
            if( isset($data1[$i]['draft_excess_qty'])) {
                $excess_qty = $data1[$i]['draft_excess_qty'];
                $data[$i]['excess_qty'] = $data1[$i]['draft_excess_qty'];
            }
            else {
                $excess_qty = "";
                $data[$i]['excess_qty'] = "";
            }
            
            if( isset($data1[$i]['draft_plan_bom_qty'])) {
                $plan_bom_qty = $data1[$i]['draft_plan_bom_qty'];
                $data[$i]['plan_bom_qty'] = $data1[$i]['draft_plan_bom_qty'];
            }
            else {
                $plan_bom_qty = "";
                $data[$i]['plan_bom_qty'] = "";
            }
            
            if( isset($data1[$i]['bom_1_req_consld_id'])) {
                $bom_1_req_consld_id = $data1[$i]['bom_1_req_consld_id'];
                $data[$i]['bom_1_req_consld_id'] = $data1[$i]['bom_1_req_consld_id'];
            }
            else {
                $bom_1_req_consld_id = "";
                $data[$i]['bom_1_req_consld_id'] = "";
            }

            if( isset($data1[$i]['bom_1_req_consld_id'])) {
                $request_id[] = $data1[$i]['request_id'];
            }



            $data[$i]['req_type'] = $data1[$i]['req_type'];
            $data[$i]['req_date'] = $data1[$i]['req_date'];
            $data[$i]['cutoff_date'] = $data1[$i]['cutoff_date'];
            $data[$i]['merchant_note'] = $data1[$i]['merchant_note'];
            $data[$i]['req_sent_status'] = $data1[$i]['req_sent_status'];

            $data[$i]['bom_1_source_id'] = $data[$i]['sourcing_advice'] = $data[$i]['vendor_location'] = $data[$i]['vendor_name_address'] =
            $data[$i]['contact_email'] = $data[$i]['gst'] = $data[$i]['online_order_sys'] = $data[$i]['pass_expiry_date'] = '';

            for ($j=0; $j < sizeof($data3); $j++) { 
                if($data3[$j]['item_desc'] == $data[$i]['item_desc']) {
                    $vendor_name_address = $data2[$j]['vendorname'].' / '.$data2[$j]['address'];
                    $data[$i]['bom_1_source_id']  = $data2[$j]['bom_1_source_id'];    
                    $data[$i]['sourcing_advice'] = $data2[$j]['sourcing_advice'];    
                    $data[$i]['vendor_location'] = $data2[$j]['vendor_location'];    
                    $data[$i]['vendor_name_address'] = $vendor_name_address;    
                    $data[$i]['vendor_id'] = $data2[$j]['id'];    
                    $data[$i]['contact_email'] = $data2[$j]['contact_email'];    
                    $data[$i]['gst'] = $data2[$j]['gst'];
                    $data[$i]['online_order_sys'] = $data2[$j]['online_order_sys'];    
                    $data[$i]['pass_expiry_date'] = $data2[$j]['pass_expiry_date'];
                }
            }

            $bcm = $data[$i]['blend'] . ' / '. $data[$i]['content'] . ' / ' . $data[$i]['material'];
            $data[$i]['bcm'] = $data[$i]['blend'] . ' / '. $data[$i]['content'] . ' / ' . $data[$i]['material'];
            
            $item_code = "'".$data[$i]['appr_item_code']."'";
            $bsql = "SELECT * FROM tbl_request_bom as a 
                    INNER JOIN tbl_request as b ON a.request_id=b.request_id
                    WHERE a.enquiry_id='$id' AND a.appr_item_code=".$item_code." AND b.purchase_req_type = 'BULK' AND a.flag=1";
            $bdata = $this->db->query($bsql)->num_rows();
    
        //if($data1[$i]['req_draft_status'] == 0 && $data1[$i]['req_sent_status'] == 0)
            if($data1[$i]['req_draft_status'] == 0 )
            {
                
                $combineValue = ['', $bom_1_req_consld_id, false, $data[$i]['item_desc'], $bcm, 
                                $data[$i]['garment_size'], $data[$i]['appr_item_code'], $data[$i]['appr_item_col_code'], 
                                $data[$i]['size_dim'], $data[$i]['uom'], $calc_bom_qty, $excess_qty, 
                                $plan_bom_qty, $data[$i]['requirement_uom'],$bdata
                            ];
                array_push($result, $combineValue);
            }
            else if($data1[$i]['req_draft_status'] == 1 && $data1[$i]['req_sent_status'] == 0) {
                $combineValue = ['draft', $bom_1_req_consld_id, true, $data[$i]['item_desc'], $bcm, 
                                $data[$i]['garment_size'], $data[$i]['appr_item_code'], $data[$i]['appr_item_col_code'], 
                                $data[$i]['size_dim'], $data[$i]['uom'], $calc_bom_qty, $excess_qty, 
                                $plan_bom_qty, $data[$i]['requirement_uom'],$bdata
                            ];
                array_push($result, $combineValue);
                                
                $cValue = ['draft','', $data[$i]['vendor_id'], true, $data[$i]['item_desc'],$data[$i]['appr_item_code'], $data[$i]['appr_item_col_code'], 
                                $data[$i]['sourcing_advice'], $data[$i]['vendor_location'], $data[$i]['vendor_name_address'], 
                                $data[$i]['contact_email'], $data[$i]['gst'], $data[$i]['online_order_sys'], $data[$i]['pass_expiry_date']
                            ];
                array_push($sourcing_result, $cValue);
            }

        }
        
        if(sizeof($sourcing_result) > 0) {
            $check_array = [];
            for($k=0;$k<sizeof($result);$k++) {
            
                if($result[$k][0] == 'draft') {
                    $ids = $k;
                    array_push($check_array,$ids);
                }
            }

            for ($j=0; $j < sizeof($sourcing_result); $j++) { 
                
                 $sourcing_result[$j][1] = $check_array[$j];
            }
        }

        // for ($i=0; $i < sizeof($data1); $i++) { 
        //     if($data[$i]['req_sent_status'] == 1)
        //     {
        //         unset($data[$i]);
        //     }
        // }
        $request_ids = array_unique($request_id);
        foreach($request_ids as $r) {
            if($r != '') {
                $req_sql = "SELECT * FROM tbl_request WHERE request_id='$r'";
                $req_data = $this->db->query($req_sql)->result_array();
            }
        }
        
        $type_sql = "SELECT * FROM tbl_request as a where a.enquiry_id='$id' AND a.purchase_req_type = 'BULK' AND a.flag=1 ";
        $type_data = $this->db->query($type_sql)->num_rows();
        
        $bulk_sql = "SELECT * FROM tbl_request_bom as a 
                INNER JOIN tbl_request as b ON a.request_id=b.request_id
                WHERE a.enquiry_id='$id' AND b.purchase_req_type = 'BULK' AND a.flag=1";
        $bulk_data = $this->db->query($bulk_sql)->result_array();
        
        $output['data'] = $result;
        $output['bulk_count'] = $type_data;
        $output['bulk_data'] = $bulk_data;
        $output['totData'] = array_values($data);
        $output['sourcing_result'] = array_values($sourcing_result);
        $output['req_data'] = $req_data;
        return $output;
    }

    public function getPurchaseRequestDetailss_bulk($id) {
        $sql = "SELECT *, SUM(`req_bom_qty`) as calc_bom_qty FROM tbl_bom_article_1_requirement as a 
                where a.enquiry_id='$id' AND a.flag=1
                GROUP BY a.item_desc, a.blend, a.content, a.material, a.garment_size, a.appr_item_code, 
                        a.appr_item_col_code, a.size_dim, a.uom ORDER BY appr_item_code";
        $data = $this->db->query($sql)->result_array();
        
        $sql1 = "SELECT * FROM tbl_bom_article_1_req_consld as a 
                LEFT JOIN tbl_request as b ON a.request_id=b.request_id
                WHERE a.enquiry_id='$id' AND a.flag=1";
        $data1 = $this->db->query($sql1)->result_array();

        $sql2 = "SELECT * FROM tbl_bom_1_sourcing_details as a 
                INNER JOIN kn_master_bom_vendor as b ON a.vendor_name_address=b.id
                WHERE a.enquiry_id='$id' AND a.flag=1";
        $data2 = $this->db->query($sql2)->result_array();

        $sql3 = "SELECT * FROM tbl_bom_article_1_requirement as a 
                where a.enquiry_id='$id' AND a.flag=1
                GROUP BY a.item_desc";

        $data3 = $this->db->query($sql3)->result_array();

        $req_data = '';
        $result = [];
        $sourcing_result = [];

        for($i=0; $i < sizeof($data); $i++)
        {
            if( isset($data1[$i]['excess_qty'])) {
                $excess_qty = $data1[$i]['excess_qty'];
                $data[$i]['excess_qty'] = $data1[$i]['excess_qty'];
            }
            else {
                $excess_qty = "";
                $data[$i]['excess_qty'] = "";
            }
            
            if( isset($data1[$i]['plan_bom_qty'])) {
                $plan_bom_qty = $data1[$i]['plan_bom_qty'];
                $data[$i]['plan_bom_qty'] = $data1[$i]['plan_bom_qty'];
            }
            else {
                $plan_bom_qty = "";
                $data[$i]['plan_bom_qty'] = "";
            }
            
            if( isset($data1[$i]['bom_1_req_consld_id'])) {
                $bom_1_req_consld_id = $data1[$i]['bom_1_req_consld_id'];
                $data[$i]['bom_1_req_consld_id'] = $data1[$i]['bom_1_req_consld_id'];
            }
            else {
                $bom_1_req_consld_id = "";
                $data[$i]['bom_1_req_consld_id'] = "";
            }

            if( isset($data1[$i]['bom_1_req_consld_id'])) {
                $request_id[] = $data1[$i]['request_id'];
            }

            $data[$i]['req_type'] = $data1[$i]['req_type'];
            $data[$i]['req_date'] = $data1[$i]['req_date'];
            $data[$i]['cutoff_date'] = $data1[$i]['cutoff_date'];
            $data[$i]['merchant_note'] = $data1[$i]['merchant_note'];
            $data[$i]['req_sent_status'] = $data1[$i]['req_sent_status'];

            $data[$i]['bom_1_source_id'] = $data[$i]['sourcing_advice'] = $data[$i]['vendor_location'] = $data[$i]['vendor_name_address'] =
            $data[$i]['contact_email'] = $data[$i]['gst'] = $data[$i]['online_order_sys'] = $data[$i]['pass_expiry_date'] = '';

            for ($j=0; $j < sizeof($data3); $j++) { 
                if($data3[$j]['item_desc'] == $data[$i]['item_desc']) {
                    $vendor_name_address = $data2[$j]['vendorname'].' / '.$data2[$j]['address'];
                    $data[$i]['bom_1_source_id']  = $data2[$j]['bom_1_source_id'];    
                    $data[$i]['sourcing_advice'] = $data2[$j]['sourcing_advice'];    
                    $data[$i]['vendor_location'] = $data2[$j]['vendor_location'];    
                    $data[$i]['vendor_name_address'] = $vendor_name_address;    
                    $data[$i]['vendor_id'] = $data2[$j]['id'];    
                    $data[$i]['contact_email'] = $data2[$j]['contact_email'];    
                    $data[$i]['gst'] = $data2[$j]['gst'];
                    $data[$i]['online_order_sys'] = $data2[$j]['online_order_sys'];    
                    $data[$i]['pass_expiry_date'] = $data2[$j]['pass_expiry_date'];
                }
            }

            $bcm = $data[$i]['blend'] . ' / '. $data[$i]['content'] . ' / ' . $data[$i]['material'];
            $data[$i]['bcm'] = $data[$i]['blend'] . ' / '. $data[$i]['content'] . ' / ' . $data[$i]['material'];

            if($data1[$i]['req_draft_status'] == 0 && $data1[$i]['req_sent_status'] == 0)
            {
                $combineValue = ['', $bom_1_req_consld_id, false, $data[$i]['item_desc'], $bcm, 
                                $data[$i]['garment_size'], $data[$i]['appr_item_code'], $data[$i]['appr_item_col_code'], 
                                $data[$i]['size_dim'], $data[$i]['uom'], $data[$i]['calc_bom_qty'], $excess_qty, 
                                $plan_bom_qty, $data[$i]['requirement_uom']
                            ];
                array_push($result, $combineValue);
            }
            else if($data1[$i]['req_draft_status'] == 1 && $data1[$i]['req_sent_status'] == 0) {
                $combineValue = ['draft', $bom_1_req_consld_id, true, $data[$i]['item_desc'], $bcm, 
                                $data[$i]['garment_size'], $data[$i]['appr_item_code'], $data[$i]['appr_item_col_code'], 
                                $data[$i]['size_dim'], $data[$i]['uom'], $data[$i]['calc_bom_qty'], $excess_qty, 
                                $plan_bom_qty, $data[$i]['requirement_uom']
                            ];
                array_push($result, $combineValue);
                                
                $cValue = ['draft', '', $bom_1_req_consld_id, true, $data[$i]['item_desc'],$data[$i]['appr_item_code'], $data[$i]['appr_item_col_code'], 
                                $data[$i]['sourcing_advice'], $data[$i]['vendor_location'], $data[$i]['vendor_name_address'], 
                                $data[$i]['contact_email'], $data[$i]['gst'], $data[$i]['online_order_sys'], $data[$i]['pass_expiry_date']
                            ];
                array_push($sourcing_result, $cValue);
                
            }

        }

        if(sizeof($sourcing_result) > 0) {
            $check_array = [];
            for($k=0;$k<sizeof($result);$k++) {
            
                if($result[$k][0] == 'draft') {
                    $ids = $k;
                    array_push($check_array,$ids);
                }
            }

            for ($j=0; $j < sizeof($sourcing_result); $j++) { 
                
                 $sourcing_result[$j][1] = $check_array[$j];
            }
        }
       // print_r($data); exit;
       // echo sizeof($data); exit;
        // for ($y=0; $y < sizeof($data); $y++) { 
        //     if($data[$y]['req_sent_status'] == 1)
        //     {
        //         echo 'Yes';
        //         unset($data[$y]);
        //     } else {
        //         echo 'No';
        //     }
        // }
        $data12 = [];
        $y = 0;
        foreach($data as $key => $value) {
            if($value['req_sent_status'] == 0) {
                $data12[$y] = $value;
                $y++;
            }
        }
        //print_r($data12); exit;
        $request_ids = array_unique($request_id);
       
        foreach($request_ids as $r) {
            if($r != '') {
                $req_sql = "SELECT * FROM tbl_request WHERE request_id='$r'";
                $req_data = $this->db->query($req_sql)->result_array();
            }
        }

        $output['data'] = $result;
        $output['totData'] = array_values($data12);
        $output['sourcing_result'] = array_values($sourcing_result);
        $output['req_data'] = $req_data;

        return $output;
    }

    // ********** SAMPLE REQUEST ENDS HERE *********** /

    // ********** CREATE SAMPLE REQUEST STARTS HERE *********** /

    public function createPurchaseRequestt($req_data, $id, $req_type, $cutoff_date, $merchant_note, $purchase_req_type, $mode, $type, $req_id, $bom_data) {
        //echo "<pre>"; print_r($_POST); exit;
        $userInfo = fnGetUserLoggedInfo(1);
        $subscriberId =  $userInfo['subscriber_id'];
        $requestValue['enquiry_id'] = $id;
        $requestValue['companyid'] = $this->companyid;
        $requestValue['req_by'] = $this->userid;
        $requestValue['type'] = 3;
        $requestValue['req_type'] = $req_type;
        $requestValue['cutoff_date'] = $cutoff_date;
        $requestValue['merchant_note'] = $merchant_note;
        $requestValue['purchase_req_type'] = $purchase_req_type;
        $requestValue['subscriberid'] = $subscriberId;
        $requestValue['log'] = LOGTIME;
        if($type == 'save') {
            $requestValue['draft_status'] = 0;
            $requestValue['req_date'] = $this->mysqldatetime;
        }
        else {
            $requestValue['draft_status'] = 1;
        }

        foreach ($bom_data as $key => $value) 
        {
            if($value[2] == false)
            {
                unset($bom_data[$key]);
            }
        }

        $bom_data = array_values($bom_data);


        if($mode == "add") {
            
            $this->db->insert('tbl_request', $requestValue);
            $primaryId = $this->db->insert_id();        
            if($primaryId) {
                foreach($bom_data as $key => $value) {

                    $requestBOMValue['enquiry_id'] = $id;
                    $requestBOMValue['request_id'] = $primaryId;
                    $requestBOMValue['bom_req_consld_id'] = $value[1];
                    $requestBOMValue['item_desc'] = $value[3];
                    $requestBOMValue['bcm'] = $value[4];
                    $requestBOMValue['garment_size'] = $value[5];
                    $requestBOMValue['appr_item_code'] = $value[6];
                    $requestBOMValue['appr_item_col_code'] = $value[7];
                    $requestBOMValue['size_dim'] = $value[8];
                    $requestBOMValue['uom'] = $value[9];
                    $requestBOMValue['consl_bom_qty'] = $value[10];
                    $requestBOMValue['excess_qty'] = $value[11];
                    $requestBOMValue['plan_bom_qty'] = $value[12];
                    $requestBOMValue['requirement_uom'] = $value[13];
                    $requestBOMValue['vendor_id'] = $req_data[$key][2];
                    $requestBOMValue['sourcing_advice'] = $req_data[$key][7];
                    $requestBOMValue['vendor_location'] = $req_data[$key][8];
                    $requestBOMValue['vendor_name_address'] = $req_data[$key][9];
                    $requestBOMValue['contact_email'] = $req_data[$key][10];
                    $requestBOMValue['gst'] = $req_data[$key][11];
                    $requestBOMValue['online_order_sys'] = $req_data[$key][12];
                    $requestBOMValue['pass_expiry_date'] = $req_data[$key][13];
                    $requestBOMValue['log'] = LOGTIME;

                    if($type == 'draft') {
                        
                        $draftVal['draft_consl_bom_qty'] = $value[10];
                        $draftVal['draft_excess_qty'] = $value[11];
                        $draftVal['draft_plan_bom_qty'] = $value[12];
                        $draftVal['req_draft_status'] = 1;
                        $draftVal['request_id'] = $primaryId;
                        $this->db->where('bom_1_req_consld_id', $value[1]);
                        $this->db->update('tbl_bom_article_1_req_consld', $draftVal);
                    }
                    else if($type == 'save') {
                        $this->db->insert('tbl_request_bom', $requestBOMValue);
                        if($purchase_req_type == 'BULK') {
                            
                            $this->db->where('bom_1_req_consld_id', $value[1]);
                            $this->db->update('tbl_bom_article_1_req_consld', array('req_sent_status' => 1, 'request_id' => $primaryId));
                        } else {
                            $this->db->where('bom_1_req_consld_id', $value[1]);
                            $this->db->update('tbl_bom_article_1_req_consld', array('req_draft_status'=>0,'request_id'=>0,'draft_consl_bom_qty'=>'','draft_excess_qty'=>'','draft_plan_bom_qty'=>''));
                            
                        }
                    }
                }
                $result["status"] = "success";
                $result['request_id'] = $primaryId;
                //echo 'in'; print_r($result); exit;
                return $result;
            }
        } else {
            foreach($bom_data as $key => $value) {
            $requestBOMValue['enquiry_id'] = $id;
            $requestBOMValue['request_id'] = $req_id;
            $requestBOMValue['bom_req_consld_id'] = $value[1];
            $requestBOMValue['item_desc'] = $value[3];
            $requestBOMValue['bcm'] = $value[4];
            $requestBOMValue['garment_size'] = $value[5];
            $requestBOMValue['appr_item_code'] = $value[6];
            $requestBOMValue['appr_item_col_code'] = $value[7];
            $requestBOMValue['size_dim'] = $value[8];
            $requestBOMValue['uom'] = $value[9];
            $requestBOMValue['consl_bom_qty'] = $value[10];
            $requestBOMValue['excess_qty'] = $value[11];
            $requestBOMValue['plan_bom_qty'] = $value[12];
            $requestBOMValue['requirement_uom'] = $value[13];
            $requestBOMValue['vendor_id'] = $req_data[$key][2];
            $requestBOMValue['sourcing_advice'] = $req_data[$key][7];
            $requestBOMValue['vendor_location'] = $req_data[$key][8];
            $requestBOMValue['vendor_name_address'] = $req_data[$key][9];
            $requestBOMValue['contact_email'] = $req_data[$key][10];
            $requestBOMValue['gst'] = $req_data[$key][11];
            $requestBOMValue['online_order_sys'] = $req_data[$key][12];
            $requestBOMValue['pass_expiry_date'] = $req_data[$key][13];
            $requestBOMValue['log'] = LOGTIME;
                if($type == 'save') {
                    $this->db->insert('tbl_request_bom', $requestBOMValue);
                }
            }
                    
            $updateValue['req_type'] = $req_type;
            $updateValue['cutoff_date'] = $cutoff_date;
            $updateValue['merchant_note'] = $merchant_note;
            $updateValue['purchase_req_type'] = $purchase_req_type;
            $updateValue['log'] = LOGTIME;
            if($type == 'save') {
                $updateValue['req_date'] = $this->mysqldatetime;
                $updateValue['draft_status'] = 0;
            }
            else {
                $updateValue['draft_status'] = 1;
            }
            
            $this->db->where('request_id', $req_id);
            $this->db->update('tbl_request', $updateValue);

            $draftVal['draft_consl_bom_qty'] = '';
            $draftVal['draft_excess_qty'] = '';
            $draftVal['draft_plan_bom_qty'] = '';
            $draftVal['req_draft_status'] = 0;
            $draftVal['request_id'] = '';

            $this->db->where('request_id',$req_id)->update('tbl_bom_article_1_req_consld',$draftVal);

            foreach($bom_data as $key => $value) {
                
                if($type == 'draft') {
                    
                     if($value[2] == true) {
                        
                        $draftVal['draft_consl_bom_qty'] = $value[10];
                        $draftVal['draft_excess_qty'] = $value[11];
                        $draftVal['draft_plan_bom_qty'] = $value[12];
                        $draftVal['req_draft_status'] = 1;
                        $draftVal['request_id'] = $req_id;
                        $this->db->where('bom_1_req_consld_id', $value[1]);
                        $this->db->update('tbl_bom_article_1_req_consld', $draftVal);
                    }
                    
                    
                }

                else if($type == 'save') {
                    
                    $status = 1;
                    if($value[2] == false) {
                        $status = 0;
                        
                        $draftVal['draft_consl_bom_qty'] = $value[10];
                        $draftVal['draft_excess_qty'] = $value[11];
                        $draftVal['draft_plan_bom_qty'] = $value[12];
                        
                        $draftVal['req_draft_status'] = $status;
                        $draftVal['request_id'] = $req_id;
                        $this->db->where('bom_1_req_consld_id', $value[1]);
                        $this->db->update('tbl_bom_article_1_req_consld', $draftVal);
                    }
                    else if($value[2] == true) {
                        if($purchase_req_type == 'BULK') {
                        foreach($bom_data as $key => $value) {
                            $this->db->where('bom_1_req_consld_id', $value[1]);
                            $this->db->update('tbl_bom_article_1_req_consld', array('req_sent_status' => 1, 'request_id'=> $req_id,'req_draft_status'=>0,'draft_consl_bom_qty'=>'','draft_excess_qty'=>'','draft_plan_bom_qty'=>''));
                        }
                           
                        } else {
                            foreach($bom_data as $key => $value) {
                            $this->db->where('bom_1_req_consld_id', $value[1]);
                            $this->db->update('tbl_bom_article_1_req_consld', array('req_draft_status'=>0,'request_id'=>0,'draft_consl_bom_qty'=>'','draft_excess_qty'=>'','draft_plan_bom_qty'=>''));
                            }
                            
                        }
                    }
                }
                
            }

            $result["status"] = "success";
            $result['request_id'] = $req_id;
            return $result;

        }
        
    }

    // ********** CREATE SAMPLE REQUEST ENDS HERE *********** /

    // ********** MERCHANT SAMPLE QUEUE STARTS HERE *********** /

    public function getMerchantBomQueueDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_request_bom b
                LEFT JOIN tbl_request c on b.request_id=c.request_id
                LEFT JOIN kn_master_bom_vendor d on b.vendor_id=d.id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND c.mgmt_approval = 1 AND c.deprt_approval = 1 ";
        $data = $this->db->query($sql)->result_array();
        //print_r($data); exit;

        
        $pisql = "SELECT * FROM tbl_request_bom b
                LEFT JOIN tbl_request c on b.request_id=c.request_id
                LEFT JOIN kn_master_bom_vendor d on b.vendor_id=d.id
                LEFT JOIN tbl_purchase_indent e on b.request_id=e.request_id
                LEFT JOIN tbl_request_status f on e.purchase_indent_id = f.purchase_indent_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND c.mgmt_approval = 1 AND c.deprt_approval = 1 and f.type_of_mode='M' GROUP BY b.request_bom_id ";
        $pidata = $this->db->query($pisql)->result_array();
        // print_r($pidata); exit;
        $insql = "SELECT * FROM tbl_bom_in_house b
                LEFT JOIN tbl_request c on b.request_id=c.request_id
                LEFT JOIN tbl_purchase_indent d on b.purchase_indent_id=d.purchase_indent_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND c.mgmt_approval = 1 AND c.deprt_approval = 1 ";
        $inHousedata = $this->db->query($insql)->result_array();
        
        $result = $att_result = $pidetails = $inhousestatusdetails = $itemacceptstatus = $inhouseconsolidatedqtydetails = [];
        
        foreach ($data as $key => $value)
        {

            $vendor_name_address = $value['vendorname'] . ' / ' . $value['address'];

            $result[$key] = [ $value['request_bom_id'] , $value['item_desc'], $value['bcm'], $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                            $value['size_dim'], $value['uom'], $value['consl_bom_qty'], $value['excess_qty'], $value['plan_bom_qty'], 
                            $value['requirement_uom'] ];

            $att_result[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['appr_item_code'], $value['appr_item_col_code'],
                            $value['sourcing_advice'], $value['vendor_location'], $vendor_name_address, 
                            $value['contact_email'], $value['gst'], $value['online_order_sys'], $value['pass_expiry_date']
                        ];
                            
        }
        
        if($inHousedata) {
        
        foreach ($inHousedata as $key => $value)
        {
            
            $inhousestatusdetails[$key] = [ $value['bom_in_house_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_color_code'], $value['size_dim'], $value['uom'], $value['pi_ref_queue_no'], $value['dc_no'], $value['dc_date'], $value['dc_qty'], $value['invoice_no'], $value['invoice_date'], $value['invoice_qty'], $value['received_date'], $value['received_qty'], $value['received_uom']
                        ];
                        
            $itemacceptstatus[$key] = [ $value['bom_in_house_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_color_code'], $value['dc_no'], $value['dc_date'], $value['dc_qty'], $value['uom'], $value['merchant_item_status'], $value['merchant_appl_date_time'], 
                            $value['qa_status'], $value['qa_status_upt_dt'], $value['mgmt_ovrd_status'], $value['mgmt_status_upd_dt']
                        ];
        }
        } else {
            $inhousestatusdetails[0] = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '','', '' ];
            $itemacceptstatus[0] = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '','' ];
        }
        
        foreach ($pidata as $key => $value) {
            if($value['pi_appl_status'] == 0) {
                $status = 'PENDING';
            } else if($value['pi_appl_status'] == 1) {
                $status = 'APPROVED';
            } else if($value['pi_appl_status'] == 2) {
                $status = 'DECLINED';
            } else if($value['pi_appl_status'] == 1) {
                $status = 'PENDING - RR';
            } else {
                $status = '-';
            }
            $pidetails[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], $value['pi_dt'], $value['pi_ref_queue_no'], $value['plan_bom_qty'], $value['requirement_uom'], $status, $value['appr_dt'], $value['exp_dod']
                        ];
            $k1 = $key;
        }
        
        $pisql1 = "SELECT b.*,a.*,e.pi_dt,e.pi_ref_queue_no,e.pi_appl_status,f.appr_dt,e.exp_dod FROM tbl_surplus_issued_details b
                LEFT JOIN tbl_request_bom a on b.bom_id=a.request_bom_id
                LEFT JOIN tbl_purchase_indent e on b.pi_ref_no=e.pi_ref_queue_no
                LEFT JOIN tbl_request c on e.request_id=c.request_id
                LEFT JOIN kn_master_bom_vendor d on a.vendor_id=d.id
                LEFT JOIN tbl_request_status f on e.purchase_indent_id = f.purchase_indent_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND c.mgmt_approval = 1 AND c.deprt_approval = 1 and f.type_of_mode='M' AND e.purchase_indent_id != 0 GROUP BY b.bom_id ";
        $pidata1 = $this->db->query($pisql1)->result_array();
        
        if($pidata1) {
            foreach($pidata1 as $key1 => $value1) {
                if($k1 > 0) {
                    $k1++;
                }
                if($value1['pi_appl_status'] == 0) {
                    $status = 'PENDING';
                } else if($value1['pi_appl_status'] == 1) {
                    $status = 'APPROVED';
                } else if($value1['pi_appl_status'] == 2) {
                    $status = 'DECLINED';
                } else if($value1['pi_appl_status'] == 1) {
                    $status = 'PENDING - RR';
                } else {
                    $status = '-';
                }
                $pidetails[$k1] = [ $value1['request_bom_id'], $value1['item_desc'], $value1['garment_size'], $value1['appr_item_code'],
                            $value1['appr_item_col_code'], $value1['size_dim'], $value1['uom'], $value1['pi_dt'], $value1['pi_ref_queue_no'], $value1['plan_bom_qty'], $value1['requirement_uom'], $status, $value1['appr_dt'], $value1['exp_dod']
                        ];
                
            }
        }
        //print_r($pidetails); exit;
        $UOM = unserialize(ARRUNITOFMEASURE);

        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }
        
        $where = '((b.merchant_item_status = 1 and b.qa_status = 1 and b.mgmt_ovrd_status = 0) or (b.merchant_item_status = 1 and b.qa_status = 2 and b.mgmt_ovrd_status = 1) or (b.merchant_item_status=2 and b.qa_status = 1 and b.mgmt_ovrd_status = 1) or (b.merchant_item_status=2 and b.qa_status = 2 and b.mgmt_ovrd_status = 1) )';
        $sql2 = "SELECT c.*, b.order_stock_status,b.received_uom,b.supply_closure_status,b.supply_closure_date, SUM(b.received_qty) as received_qtys, c.plan_bom_qty FROM tbl_request_bom c
                LEFT JOIN tbl_bom_in_house b on c.request_bom_id = b.request_bom_id  AND $where
                LEFT JOIN tbl_request d on c.request_id=d.request_id
                WHERE c.request_id = " . $reqId . " AND c.flag=1 GROUP BY c.item_desc,c.garment_size,c.appr_item_code,c.appr_item_col_code,c.size_dim,c.uom ";
        @$req_data1 = $this->db->query($sql2)->result_array();
        //print_r($sql2); exit;
        $k = 0;
        if($req_data1) {
        foreach ($req_data1 as $key => $value)
        {
            
                $item_dec = $value['item_desc'];
                $garment_size = $value['garment_size'];
                $appr_item_code = $value['appr_item_code'];
                
                
                //$item[$item_dec][$garment_size][$appr_item_code][] = $value['received_qty'];
                
                if($value['merchant_item_status'] == 1 && $value['qa_status'] == 1 && $value['mgmt_ovrd_status'] == 0) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 1 && $value['qa_status'] == 2 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 2 && $value['qa_status'] == 1 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 2 && $value['qa_status'] == 2 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else {
                    $con_status = 'Failed';
                }
    
                //echo $con_status; echo '<br>';
                // if($con_status === 'Consolidated') {
                //     $plan_bom_qty = $value['plan_bom_qty'];
                //     $received_qty = $value['received_qtys'];
                //     $received_uom = $value['received_uom'];
                // } else if($value['order_stock_status'] == 1) {
                //     $plan_bom_qty = $value['plan_bom_qty'];
                //     $received_qty = $value['received_qtys'];
                //     $received_uom = $value['received_uom'];
                // } else {
                //     $plan_bom_qty = 0;
                //     $received_qty = 0;
                //     $received_uom = '';
                // }
                
                $plan_bom_qty = $value['plan_bom_qty'];
                $received_qty = $value['received_qtys'];
                $received_uom = $value['received_uom'];
                
                if($value['supply_closure_status'] == 0) {
                    $status = 'PENDING';
                } else if($value['supply_closure_status'] == 1) {
                    $status = 'DISC SUPPLY CLOSED';
                } else if($value['supply_closure_status'] == 2) {
                    $status = 'SHORT SUPPLY CLOSED';
                } else if($value['supply_closure_status'] == 3) {
                    $status = 'FULL SUPPLY CLOSED';
                } else if($value['supply_closure_status'] == 4) {
                    $status = 'P.I. CANCELLED';
                } else {
                    $status = '-';
                }
                
                // if($con_status == 'Consolidated' || $value['order_stock_status'] == 1) {
                    $diff = $received_qty - $plan_bom_qty;
                    $inhouseconsolidatedqtydetails[$key] = [ $value['bom_in_house_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], $plan_bom_qty, $received_qty, $diff, $received_uom, $status, $value['order_stock_status']
                        ];
                    $k++;
                // }
        }
        }
        
        $req_sql = "SELECT a.*, b.contactname as auth_name FROM tbl_request as a LEFT JOIN ".KN_USERS." as b ON a.auth_by=b.id WHERE a.request_id='$reqId' ";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $output['purchaserequest'] = $result;
        $output['sourcedetails'] = $att_result;
        $output['pidetails'] = $pidetails;
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['itemacceptstatus'] = $itemacceptstatus;
        $output['inhouseconsolidatedqtydetails'] = $inhouseconsolidatedqtydetails;
        $output['uomData'] = $UOMDetails;
        $output['req_data'] = $req_data;
        return $output;
    }

    public function getSupplyClosureListt($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_bom_in_house c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 ";
        $data = $this->db->query($sql)->result_array();

        $sql1 = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_bom_in_house c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 ";
        $data1 = $this->db->query($sql1)->result_array();

        $sql2 = "SELECT * FROM tbl_request_bom b
                LEFT JOIN tbl_bom_in_house c on c.request_id=b.request_id
                WHERE c.request_id = " . $reqId . " AND c.flag=1 AND c.order_stock_status=0 ";
        $data2 = $this->db->query($sql2)->result_array();
        
        $inhousestatusdetails = $itemacceptstatus = $inhouseconsolidatedqtydetails = [];

        foreach ($data as $key => $value)
        {

            $inhousestatusdetails[$key] = [ $value['bom_in_house_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],$value['appr_item_col_code'], $value['size_dim'], $value['uom'], '',$value['dc_no'],$value['dc_date'],$value['dc_qty'],$value['invoice_no'],$value['invoice_date'],$value['invoice_qty'],$value['invoice_rate'],$value['currency'],$value['foreign_exch_rate'],$value['invoice_value'],$value['received_qty'],$value['received_uom'],$value['received_date'], $value['storage_bin']];
        }

        foreach ($data1 as $key => $value)
        {

            $itemacceptstatus[$key] = [ $value['bom_in_house_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'], $value['dc_no'], $value['dc_date'], $value['dc_qty'], $value['received_uom'], $value['invoice_no'], $value['invoice_date'], $value['merchant_item_status'], $value['merchant_appl_date_time'], $value['qa_status'], $value['qa_status_upt_dt'], $value['mgmt_ovrd_status'], $value['mgmt_status_upd_dt'] ];

        }

        foreach ($data2 as $key => $value)
        {
            $qty = $value['invoice_qty'];
            $qty1 = $value['received_qty'];
            $diff = floatval($qty)-floatval($qty1);

            $inhouseconsolidatedqtydetails[$key] = [ $value['bom_in_house_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'], $value['size_dim'], $value['uom'], $value['invoice_qty'], $value['received_qty'], $diff, $value['received_uom'], $value['supply_closure_status'], ];

        }

        $UOM = unserialize(ARRUNITOFMEASURE);
        $currencyList = unserialize(ARRCURRENCYLIST);

        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }
        
        $req_sql = "SELECT * FROM tbl_request
                WHERE request_id = " . $reqId;
        $req_data = $this->db->query($req_sql)->result_array();
        
        $bomMIDetails = $this->getInHouseDetailss($enqId, $reqId);
        
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['itemacceptstatus'] = $itemacceptstatus;
        $output['inhouseconsolidatedqtydetails'] = $inhouseconsolidatedqtydetails;
        $output['bomAppendData'] = $bomMIDetails;
        $output['uomData'] = $UOMDetails;
        $output['req_data'] = $req_data;
        $output['currency'] = $currencyList;
        return $output;
    }

    // ********** MERCHANT SAMPLE QUEUE ENDS HERE *********** /
    
    // ********** UPDATE MERCHANT SAMPLE QUEUE ENDS HERE *********** /

    public function updateMerchantQueuee($data, $enqId)
    {
        foreach($data as $key => $value) {
            $this->db->where('request_bom_id', $value[0]);
            $this->db->update('tbl_request_bom', array('merchant_item_status' => $value[9], "merchant_appl_date_time"=> $this->mysqldatetime,'log'=>LOGTIME));
        }
    }

    // ********** UPDATE MERCHANT SAMPLE QUEUE ENDS HERE *********** /

    function getRequestDataa($enqId, $reqId)
    {
        $sql = "SELECT a.*,a.req_status as request_status,b.*,b.req_status as pi_req_status, c.contactname as req_name,d.contactname as auth_name 
                FROM tbl_request a 
                LEFT JOIN tbl_request_bom as b ON a.request_id=b.request_id
                LEFT JOIN ".KN_USERS." c on a.req_by=c.id
                LEFT JOIN ".KN_USERS." d on a.auth_by=d.id
                WHERE a.request_id='$reqId' and a.flag=1";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    function getRequestDataa_pi($enqId, $reqId, $pId)
    {
         $sql = "SELECT a.*,b.ref_queue_no,c.req_status as pi_req_status, d.contactname as req_name,e.contactname as auth_name, f.contactname as pi_req_name, g.appr_status 
                FROM tbl_purchase_indent a 
                INNER JOIN tbl_request as b ON a.request_id=b.request_id
                INNER JOIN tbl_request_bom as c ON a.request_id=c.request_id
                INNER JOIN ".KN_USERS." d on b.req_by=d.id
                INNER JOIN ".KN_USERS." e on b.auth_by=e.id
                INNER JOIN ".KN_USERS." f on a.pi_req_by=f.id
                INNER JOIN tbl_request_status as g ON a.purchase_indent_id=g.purchase_indent_id
                WHERE a.purchase_indent_id='$pId'";
          //      print_r($sql); exit;
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    function getRequestDataa_apprpi($enqId, $reqId, $pId)
    {
         $sql = "SELECT a.*,b.ref_queue_no,c.req_status as pi_req_status, d.contactname as req_name,e.contactname as auth_name, f.contactname as pi_req_name, g.appr_status, h.contactname as pi_appr_name 
                FROM tbl_purchase_indent a 
                INNER JOIN tbl_request as b ON a.request_id=b.request_id
                INNER JOIN tbl_request_bom as c ON a.request_id=c.request_id
                INNER JOIN ".KN_USERS." d on b.req_by=d.id
                INNER JOIN ".KN_USERS." e on b.auth_by=e.id
                INNER JOIN ".KN_USERS." f on a.pi_req_by=f.id
                INNER JOIN tbl_request_status as g ON a.purchase_indent_id=g.purchase_indent_id
                INNER JOIN ".KN_USERS." h on g.appr_by=h.id
                WHERE a.purchase_indent_id='$pId'";
               // print_r($sql); exit;
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function getsupplayclose($enqId, $reqId, $pId)
    {
        $sql = "SELECT b.*,c.plan_bom_qty FROM tbl_bom_in_house b
                LEFT JOIN tbl_request_bom c on b.request_bom_id = c.request_bom_id
                WHERE b.purchase_indent_id = " . $pId . " and (b.supply_closure_status = 0 OR b.supply_closure_status = 4)  AND b.flag=1 ";
        $req_data = $this->db->query($sql)->result_array();
       
          if (count($req_data) > 0) {
        return 1;  // Data exists
       } else {
      return 0;  // No data found
         }
        //return $req_data;
    }
    function getorderstock($enqId, $reqId, $pId)
    {
        $sql = "SELECT b.*,c.plan_bom_qty FROM tbl_bom_in_house b
                LEFT JOIN tbl_request_bom c on b.request_bom_id = c.request_bom_id
                WHERE b.purchase_indent_id = " . $pId . " and  b.order_stock_status=1 AND b.flag=1 ";
        $req_data = $this->db->query($sql)->result_array();
        $sql1 = "SELECT b.*,c.plan_bom_qty FROM tbl_bom_in_house b
                LEFT JOIN tbl_request_bom c on b.request_bom_id = c.request_bom_id
                WHERE b.purchase_indent_id = " . $pId . "  AND b.mgmt_ovrd_status NOT IN (2, 3, 4)    AND b.flag=1 ";
        $req_data1 = $this->db->query($sql1)->result_array();
         if (count($req_data1) == count($req_data)) {
        return 1;  // Data exists
       } else {
      return 0;  // No data found
         }
    }
    
    
    public function getDraftStatus($enqId, $reqId)
    {
        $pidraft = 0;
        //$pidraft = $this->db->where('request_id',$reqId)->get('tbl_request')->row()->pi_draft_status;
        $req_data = $this->db->where('request_id',$reqId)->where('pi_draft_status',1)->get('tbl_request_bom')->result_array();
        if($req_data) {
            if($req_data[0]['purchase_indent_id'] != 0) {
                $pidraft = $this->db->where('purchase_indent_id',$req_data[0]['purchase_indent_id'])->get('tbl_purchase_indent')->row()->pi_draft_status;
            }
        }
        return $pidraft;

    }
    
    public function getTotalStatus($enqId, $reqId)
    {
       
       $req_data = $this->db->where('purchase_indent_id = ',0)->where('request_id',$reqId)->where('pi_status',0)->get('tbl_request_bom')->num_rows();
        return $req_data;

    }

    function getBomRequestDataa($enqId, $reqId)
    {
        //$sql = "SELECT * from  tbl_request where request_id=".$reqId." and flag=1";
        $sql = "SELECT b.*,c.contactname as req_name,d.contactname as auth_name FROM tbl_request b
                LEFT JOIN ".KN_USERS." c on b.req_by=c.id
                LEFT JOIN ".KN_USERS." d on b.auth_by=d.id
                WHERE b.request_id = " . $reqId . " AND b.flag=1";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    function getBomPIDataa($enqId, $reqId)
    {
        $pi_data = array();
        $req_data = $this->db->where('request_id',$reqId)->where('pi_draft_status',1)->get('tbl_request_bom')->result_array();
        if($req_data) {
            if($req_data[0]['purchase_indent_id'] != 0) {
                $pi_data = $this->db->where('purchase_indent_id',$req_data[0]['purchase_indent_id'])->get('tbl_purchase_indent')->result_array();
            }
        }

        return $pi_data;
    }
    
    // ********** MANAGEMENT SAMPLE QUEUE STARTS HERE *********** /

    public function getManagementBomQueueDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND b.qa_req_status = 1";
        $data = $this->db->query($sql)->result_array();
        
        $result = $att_result = $qa_status_result = $job_status_result = [];

        foreach ($data as $key => $value)
        {
            $bcm = $value['blend'].' / '.$value['content'].' / '.$value['material'];

            $result[$key] = [ $value['request_bom_id'] , $value['item_desc'], $bcm, $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                            $value['size_dim'], $value['uom'], $value['consl_bom_qty'], $value['excess_qty'], $value['plan_bom_qty'], 
                            $value['requirement_uom'] ];

            $att_result[$key] = [ $value['request_bom_id'], $value['item_desc'], $bcm, 
                            $value['sourcing_advice'], $value['vendor_location'], $value['vendor_name_address'], 
                            $value['contact_email'], $value['gst'], $value['online_order_sys'], $value['pass_expiry_date']
                        ];

            $pidetails[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], '', '', '', '', '', '', '', '', ''
                        ];

            $inhousestatusdetails[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], '', '', '', '', '', '', '', '', '', ''
                        ];

            $itemacceptstatus[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], '', '', '', '', $value['merchant_item_status'], $value['merchant_appl_date_time'], 
                            $value['qa_status'], $value['qa_status_upt_dt'], $value['mgmt_ovrd_status'], $value['mgmt_status_upd_dt']
                        ];

            $inhouseconsolidatedqtydetails[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], '', '', '', '', '', '', '', ''
                        ];
                            
        }
        
        $output['purchaserequest'] = $result;
        $output['sourcedetails'] = $att_result;
        $output['pidetails'] = $pidetails;
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['itemacceptstatus'] = $itemacceptstatus;
        $output['inhouseconsolidatedqtydetails'] = $inhouseconsolidatedqtydetails;
        return $output;
    }

    // ********** MANAGEMENT SAMPLE QUEUE ENDS HERE *********** /
    
    // ********** UPDATE MANAGEMENT SAMPLE QUEUE ENDS HERE *********** /

    public function updateManagementQueuee($data, $enqId, $reqId)
    {
        foreach($data as $key => $value) {
            if($value[13] != 0 && $value[14] == '') {
                $this->db->where('bom_in_house_id', $value[0]);
                $this->db->update('tbl_bom_in_house', array('mgmt_ovrd_status' => $value[13], "mgmt_status_upd_dt"=> $this->mysqldatetime,'log'=>LOGTIME));
                
                $this->db->where('request_id',$reqId)->update('tbl_request',array('log'=>LOGTIME));
            }
        }
    }

    // ********** UPDATE MANAGEMENT SAMPLE QUEUE ENDS HERE *********** /

    // ********** MANAGEMENT SAMPLE QUEUE STARTS HERE *********** /

    public function getDraftPIRequestDetailss($enqId, $reqId) {
        // $sql = "SELECT b.*,d.unit_rate,d.gst,d.gst_value,d.igst,d.igst_value,d.currency,d.sub_total,d.vendor_id FROM tbl_request_bom b
        //         LEFT JOIN tbl_request c on b.request_id=c.request_id
        //         LEFT JOIN tbl_request_purchase_indent d on b.request_bom_id=d.request_bom_id AND b.plan_bom_qty = d.qty
        //         WHERE b.request_id = " . $reqId . " AND b.flag=1 AND c.mgmt_approval = 1 AND c.deprt_approval = 1";

        $sql = "SELECT b.*,d.unit_rate,d.gst,d.gst_value,d.igst,d.igst_value,d.currency,d.sub_total,d.vendor_id FROM tbl_request_bom b
                LEFT JOIN tbl_request c on b.request_id=c.request_id
                LEFT JOIN tbl_purchase_indent e on b.purchase_indent_id=e.purchase_indent_id
                LEFT JOIN tbl_request_purchase_indent d on b.request_bom_id=d.request_bom_id AND b.plan_bom_qty = d.qty
                WHERE b.request_id = " . $reqId . " AND b.flag=1 AND b.pi_status=0 AND c.mgmt_approval = 1 AND c.deprt_approval = 1";
                
        $data = $this->db->query($sql)->result_array();

        $vendor_sql = "SELECT *, vendorname as name FROM kn_master_bom_vendor";
        $vendor_data = $this->db->query($vendor_sql)->result_array();

        // $pi_sql = "SELECT * FROM tbl_request_purchase_indent
        //         WHERE request_id = " . $reqId . " AND flag=1";
        // $pi_data = $this->db->query($pi_sql)->result_array();
        
        $pi_sql = "SELECT * FROM tbl_purchase_indent
                WHERE request_id = " . $reqId . " AND pi_draft_status=1";
        $pi_data = $this->db->query($pi_sql)->result_array();
//print_r($pi_data); exit;
        @$pi_id = $this->db->where('request_id',$reqId)->where('pi_draft_status',1)->get('tbl_purchase_indent')->row()->purchase_indent_id;
        
        $pay_data = $this->db->where('request_id',$reqId)->where('purchase_indent_id',$pi_id)->get('tbl_request_payment')->row();
        $pay_req_data = $this->db->where('request_id',$reqId)->where('purchase_indent_id',$pi_id)->get('tbl_request_status')->row();

        $withinResult = $interResult = $importsResult = [];

        $vendor_id = '';

        foreach ($data as $key => $value)
        {
            if($value['pi_draft_status'] == 1) {
                $status = 'true';
                $type = 'edit';
            } else {
                $status = 'false';
                $type = 'add';
            }
            $withinResult[$key] = [ $type, $value['request_bom_id'], $status, $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $value['unit_rate'],'', $value['gst'], $value['gst_value']
            ];

            $interResult[$key] = [ $type, $value['request_bom_id'], $status, $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $value['unit_rate'], '', $value['igst'], '', $value['igst_value'] 
            ];

            $importsResult[$key] = [ $type, $value['request_bom_id'], $status, $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $value['currency'], $value['unit_rate'], $value['sub_total']
            ];

        }

        $modeOfShipment = [ 'IMPS', 'NEFT', 'RTGS', 'SWIFT', 'CHEQUE', 'CASH' ];
        $currencyList = unserialize(ARRCURRENCYLIST);

        if($pay_req_data) {
            $requestPaymentLog = [
                [ 'edit','P.I. APPROVAL', $pay_req_data->request_type, '', $pay_req_data->payment_requirement, '', '', '', '', '', '', $pay_req_data->request_status_id ],
            ];
        } else {
            $requestPaymentLog = [
                [ 'add','P.I. APPROVAL', '', '', '', '', '', '', '', '', '', '', ],
            ];
        }
        

        if($pay_data) {
            $requestPaymentData = 
                [ 'edit', $pay_data->vendor_id,$pay_data->vendor_bank_name, $pay_data->acc_no, $pay_data->ifsc, $pay_data->proforma_no, $pay_data->proforma_date, $pay_data->proforma_value, $pay_data->quoted_currency, $pay_data->mode_of_payment, $pay_data->pay_by_date, $pay_data->amount_payable, $pay_data->currency,$pay_data->request_payment_id];
            
        } else {
            $requestPaymentData = [
                [ 'add', '', '', '', '', '', '', '', '', '', '', '','' ],
            ];
        }

        $req_data = $this->db->where('request_id',$reqId)->get('tbl_request')->result_array();

        $pi_data = array();
        $req_data = $this->db->where('request_id',$reqId)->where('pi_draft_status',1)->get('tbl_request_bom')->result_array();
        if($req_data) {
            if($req_data[0]['purchase_indent_id'] != 0) {
                $pi_data = $this->db->where('purchase_indent_id',$req_data[0]['purchase_indent_id'])->get('tbl_purchase_indent')->result_array();
            }
        }
        

        $output['withinStateDetails'] = $withinResult;
        $output['interStateDetails'] = $interResult;
        $output['importStateDetails'] = $importsResult;
        $output['paymentRequestBill'] = [];
        $output['paymentPaidDetails'] = [];
        $output['requestPaymentLog'] = $requestPaymentLog;
        $output['requestPaymentData'] = $requestPaymentData;
        $output['vendor_data'] = $vendor_data;
        $output['fullData'] = $data;
        $output['modeOfShipment'] = $modeOfShipment;
        $output['currencyList'] = $currencyList;
        $output['req_data'] = $req_data;
        $output['pi_data'] = $pi_data;
        return $output;
    }

    // ********** MANAGEMENT SAMPLE QUEUE ENDS HERE *********** /
    
    // ********** UPDATE PURCHASE INDENT STARTS HERE *********** /

     public function updatePurchaseIndentt($data, $enqId, $reqId, $pi_cutoff_dt, $purchase_dept_note, $mode, $p_type, $pur_req_data, $vId, $pm, $pt, $log_data, $amw, $exp_dod, $type,$purchase_indent_id)
    {
        
        $requestData['enquiry_id'] = $enqId;
        $requestData['request_id'] = $reqId;
        $requestData['companyid'] = $_SESSION['UI']['companyid'];
        $requestData['pi_date'] = date('Y-m-d');
        $requestData['req_date'] = date('Y-m-d H:i:s');
        $requestData['purchase_type'] = $pm;
        $requestData['payment_terms'] = $pt;
        $requestData['amount_in_words'] = $amw;
        $requestData['exp_dod'] = $exp_dod;
        $requestData['mode'] = $mode;
        $requestData['vendor_id'] = $vId;
        $requestData['p_type'] = $p_type;
        $requestData['pi_req_by'] = $_SESSION['UI']['id'];
        //print_r($requestData); exit;
        if($type == 'save') {
            $requestData['pi_appl_status'] = 1;
            $requestData['pi_draft_status'] = 0;
        } else if($type == 'draft') {
            $requestData['pi_draft_status'] = 1;
        }
        $requestData['log'] = LOGTIME;
        // $this->db->where('request_id', $reqId);
        // $this->db->update('tbl_request', $requestData);
        if($purchase_indent_id == '') {
            $this->db->insert('tbl_purchase_indent', $requestData);
            $insert_id = $this->db->insert_id();
        } else {
            $this->db->where('purchase_indent_id', $purchase_indent_id);
            $this->db->update('tbl_purchase_indent', $requestData);
            $insert_id = $purchase_indent_id;
        }
        
        $this->db->where('request_id',$reqId)->update('tbl_request',array('log'=>LOGTIME));
        
        foreach ($log_data as $key => $value) {
            @$row_data = $this->db->where('purchase_indent_id',$insert_id)->order_by('request_status_id','desc')->get('tbl_request_status')->row()->row_id;
            if(is_null($row_data))
		    {
			    $row_id = 'RC 1'; 
		    } 
		    else
		    {
			 //   $bondLen = strlen($row_data)-3;
			 //   $bondOnlyNum = filter_var($row_data, FILTER_SANITIZE_NUMBER_INT);
			 //   $row_id = 'RC '.sprintf('%0'.$bondLen.'d', $bondOnlyNum + 1);
			    $row_id = $row_data;
		    }
            $requestStatus['enquiry_id'] = $enqId;
            $requestStatus['request_id'] = $reqId;
            $requestStatus['purchase_indent_id'] = $insert_id;
            $requestStatus['request_for'] = $value[1];
            $requestStatus['request_type'] = $value[2];
            $requestStatus['req_dt'] = $this->mysqldatetime;
            $requestStatus['payment_requirement'] = $value[4];
            $requestStatus['log'] = LOGTIME;
            if($type == 'save') {
                $requestStatus['appr_status'] = 0;
            }
            if($value[11] == '') {
                $requestStatus['row_id'] = $row_id;
                $this->db->insert('tbl_request_status', $requestStatus);
            } else {
                $this->db->where('request_status_id', $value[11]);
                $this->db->update('tbl_request_status', $requestStatus);
            }
        }
        
        foreach($data as $key => $value) {
            if($value[6] != '') {
                $proforma_date = date('Y-m-d',strtotime($value[6]));
            } else {
                $proforma_date = '';
            }
            if($value[10] != '') {
                $pay_by_date = date('Y-m-d h:i A',strtotime($value[10]));
            } else {
                $pay_by_date = '';
            }
            $requestPayment['enquiry_id'] = $enqId;
            $requestPayment['request_id'] = $reqId;
            $requestPayment['purchase_indent_id'] = $insert_id;
            $requestPayment['vendor_id'] = $value[1];
            $requestPayment['vendor_bank_name'] = $value[2];
            $requestPayment['acc_no'] = $value[3];
            $requestPayment['ifsc'] = $value[4];
            $requestPayment['proforma_no'] = $value[5];
            $requestPayment['proforma_date'] = $value[6];
            $requestPayment['proforma_value'] = $value[7];
            $requestPayment['quoted_currency'] = $value[8];
            $requestPayment['mode_of_payment'] = $value[9];
            $requestPayment['pay_by_date'] = $value[10];
            $requestPayment['amount_payable'] = $value[11];
            $requestPayment['currency'] = $value[12];
            $requestPayment['log'] = LOGTIME;
            if($value[13] == '') {
                // if($value[7] > 0) {
                    $requestPayment['row_id'] = $row_id;
                    $this->db->insert('tbl_request_payment', $requestPayment);
               // }
            } else {
                $this->db->where('request_payment_id', $value[13]);
                $this->db->update('tbl_request_payment', $requestPayment);
            }
            
        }

        //////delte purchase indent
          foreach($pur_req_data as $key1 => $value) {
             if($type == 'save') {
         if($value[2] == 'true') {
                if($value[0] == 'add') {
                     $this->db->where('request_bom_id', $value[1]);
                    $this->db->delete('tbl_request_purchase_indent');

                    //$this->db->insert('tbl_request_purchase_indent', $purchaseIndent);
                }
            }
        }
    }
     //////delte purchase indent
        
            foreach($pur_req_data as $key => $value) {
            $bomRequestData['pi_appl_req_date_time'] = $this->mysqldatetime;
            // if($value[2] == 'true') {
            //     $bomRequestData['pi_draft_status'] = 1;
            // } else {
            //     $bomRequestData['pi_draft_status'] = 0;
            // }
            $bomRequestData['log'] = LOGTIME;
            if($type == 'save') {
                if($value[2] == 'true') {
                    $bomRequestData['purchase_indent_id'] = $insert_id;
                    $bomRequestData['pi_status'] = 1;
                    $bomRequestData['pi_draft_status'] = 0;
                } else {
                    $bomRequestData['purchase_indent_id'] = '';
                    $bomRequestData['pi_status'] = 0;
                    $bomRequestData['pi_draft_status'] = 0;
                }
                
            } else if($type == 'draft') {
                if($value[2] == 'true') {
                    $bomRequestData['purchase_indent_id'] = $insert_id;
                    $bomRequestData['pi_draft_status'] = 1;
                    $bomRequestData['pi_status'] = 0;
                } else {
                    $bomRequestData['purchase_indent_id'] = '';
                    $bomRequestData['pi_draft_status'] = 0;
                    $bomRequestData['pi_status'] = 0;
                }
            }
            $this->db->where('request_bom_id', $value[1]);
            $this->db->update('tbl_request_bom', $bomRequestData);

            $purchaseIndent['enquiry_id'] = $enqId;
            $purchaseIndent['mode'] = $mode;
            $purchaseIndent['vendor_id'] = $vId;
            $purchaseIndent['request_id'] = $reqId;
            $purchaseIndent['request_bom_id'] = $value[1];
            //$purchaseIndent['mode'] = $mode;
            $purchaseIndent['p_type'] = $p_type;
            $purchaseIndent['qty'] = $value[10];
            $purchaseIndent['uom'] = $value[9];
            $purchaseIndent['log'] = LOGTIME;
            if($mode == 'within')
            {
                $purchaseIndent['unit_rate'] = $value[12];
                $purchaseIndent['amount'] = $value[13];
                $purchaseIndent['gst'] = $value[14];
                $purchaseIndent['gst_value'] = $value[15];
                $purchaseIndent['sub_total'] = $value[16];
            }
            else if($mode == 'inter')
            {
                $purchaseIndent['unit_rate'] = $value[12];
                $purchaseIndent['amount'] = $value[13];
                $purchaseIndent['igst'] = $value[14];
                $purchaseIndent['igst_value'] = $value[15];
                $purchaseIndent['sub_total'] = $value[16];
            }
            else if($mode == 'imports')
            {
                $purchaseIndent['currency'] = $value[12];
                $purchaseIndent['unit_rate'] = $value[13];
                //$purchaseIndent['amount'] = $value[14];
                $purchaseIndent['sub_total'] = $value[14];
            }
            if($value[2] == 'true') {
                if($value[0] == 'add') {
                     $this->db->where('request_bom_id', $value[1]);
                    $this->db->delete('tbl_request_purchase_indent');

                    $this->db->insert('tbl_request_purchase_indent', $purchaseIndent);
                } else {
                    $this->db->where('request_bom_id', $value[1]);
                    $this->db->update('tbl_request_purchase_indent', $purchaseIndent);
                }
                
            }
        }

    }
    
    public function updatePurchaseIndent_requestt($data, $enqId, $reqId, $pi_cutoff_dt, $purchase_dept_note, $mode, $p_type, $pur_req_data, $vId, $pm, $pt, $log_data, $amw, $exp_dod, $type,$purchase_indent_id)
    {
        $requestData['purchase_type'] = $pm;
        $requestData['payment_terms'] = $pt;
        $requestData['amount_in_words'] = $amw;
        $requestData['exp_dod'] = $exp_dod;
        $requestData['log'] = LOGTIME;
        
        $this->db->where('purchase_indent_id', $purchase_indent_id);
        $this->db->update('tbl_purchase_indent', $requestData);
            
        foreach ($log_data as $key => $value) {
            $requestStatus['appr_status'] = 3;
            $requestStatus['request_type'] = $value[3];
            $requestStatus['appr_by'] = '';
            $requestStatus['appr_dt'] = '';
            $requestStatus['appr_type'] = '';
            $requestStatus['req_dt'] = $this->mysqldatetime;
            $requestStatus['payment_requirement'] = $value[5];
            $requestStatus['log'] = LOGTIME;
            $this->db->where('request_status_id', $value[0]);
            $this->db->update('tbl_request_status', $requestStatus);
        }
        
        foreach($data as $key => $value) {
            
            $requestPayment['enquiry_id'] = $enqId;
            $requestPayment['request_id'] = $reqId;
            $requestPayment['purchase_indent_id'] = $purchase_indent_id;
            $requestPayment['vendor_id'] = $value[2];
            $requestPayment['vendor_bank_name'] = $value[3];
            $requestPayment['acc_no'] = $value[4];
            $requestPayment['ifsc'] = $value[5];
            $requestPayment['proforma_no'] = $value[6];
            $requestPayment['proforma_date'] = $value[7];
            $requestPayment['proforma_value'] = $value[8];
            $requestPayment['quoted_currency'] = $value[9];
            $requestPayment['mode_of_payment'] = $value[10];
            $requestPayment['pay_by_date'] = $value[11];
            $requestPayment['amount_payable'] = $value[12];
            $requestPayment['currency'] = $value[13];
            $requestPayment['log'] = LOGTIME;
            
                $this->db->where('request_payment_id', $value[0]);
                $this->db->update('tbl_request_payment', $requestPayment);
        }
        
        foreach($pur_req_data as $key => $value) {
        if($mode == 'within')
            {
                $purchaseIndent['unit_rate'] = $value[11];
                $purchaseIndent['amount'] = $value[12];
                $purchaseIndent['gst'] = $value[13];
                $purchaseIndent['gst_value'] = $value[14];
                $purchaseIndent['sub_total'] = $value[15];
            }
            else if($mode == 'inter')
            {
                $purchaseIndent['unit_rate'] = $value[11];
                $purchaseIndent['amount'] = $value[12];
                $purchaseIndent['igst'] = $value[13];
                $purchaseIndent['igst_value'] = $value[14];
                $purchaseIndent['sub_total'] = $value[15];
            }
            else if($mode == 'imports')
            {
                $purchaseIndent['currency'] = $value[11];
                $purchaseIndent['unit_rate'] = $value[12];
                //$purchaseIndent['amount'] = $value[13];
                $purchaseIndent['sub_total'] = $value[14];
            }
            $purchaseIndent['log'] = LOGTIME;
            //echo "<pre>"; print_r($purchaseIndent); 
            $this->db->where('request_purchase_indent_id', $value[1]);
            $this->db->update('tbl_request_purchase_indent', $purchaseIndent);
        }
        
    }

    public function updatePurchaseIndent_cancelpii($data, $enqId, $reqId, $pi_cutoff_dt, $purchase_dept_note, $mode, $p_type, $pur_req_data, $vId, $pm, $pt, $log_data, $amw, $exp_dod, $type,$purchase_indent_id)
    {
        $requestData['purchase_type'] = $pm;
        $requestData['payment_terms'] = $pt;
        $requestData['amount_in_words'] = $amw;
        $requestData['exp_dod'] = $exp_dod;
        $requestData['log'] = LOGTIME;
        
        $this->db->where('purchase_indent_id', $purchase_indent_id);
        $this->db->update('tbl_purchase_indent', $requestData);
        
        foreach ($log_data as $key => $value) {
           
            $requestStatus['appr_status'] = 4;
           
            $requestStatus['req_dt'] = $this->mysqldatetime;
           
            $requestStatus['log'] = LOGTIME;
            $this->db->where('request_status_id', $value[0]);
            $this->db->update('tbl_request_status', $requestStatus);

        }
        if($purchase_indent_id != '')
        {
             $requestStatus1['pi_status'] = 0;
            $requestStatus1['log'] = LOGTIME;

            $this->db->where('purchase_indent_id', $purchase_indent_id);
            $this->db->update('tbl_request_bom', $requestStatus1);
        }

         
    }

    // ********** UPDATE PURCHASE INDENT ENDS HERE *********** /

    // *********************************************************************************************************** 
    // MANAGEMENT DEPARTMENT STARTS HERE 
    // **********************************************************************************************************//

    public function getManagementRequestDetailss($enqId, $reqId) {
        $sql = "SELECT a.* from tbl_request_bom as a 
                where a.enquiry_id='$enqId' and request_id='$reqId' and a.flag=1";
        $data = $this->db->query($sql)->result_array();

        $result = [];
        $refResult = [];

        foreach($data as $key => $value) {
            $result[$key] = [
                $value["item_desc"], $value["blend"], $value["content"], $value["material"], $value["garment_size"],
                $value["appr_item_code"], $value["appr_item_col_code"],$value["size_dim"], $value["uom"],
                $value["consl_bom_qty"], $value["excess_qty"], $value["plan_bom_qty"], $value["requirement_uom"]
            ];
            
            $refResult[$key] = [
                $value["item_desc"], $value["blend"], $value["content"], $value["material"], $value["sourcing_advice"],
                $value["vendor_location"], $value["vendor_name_address"],$value["contact_email"], $value["gst"],
                $value["online_order_sys"], $value["pass_expiry_date"]
            ];
        }

        $output['data'] = $result;
        $output['refResult'] = $refResult;
        return $output;
    }

    public function updateManagementBomRequestt($id, $auth_status, $auth_type, $mgmt_remark) {
        $requestValue['auth_status'] = $auth_status;
        $requestValue['mgmt_approval'] = $auth_status;
        $requestValue['auth_by'] = $this->userid;
        $requestValue['auth_date'] = $this->mysqldatetime;
        $requestValue['auth_type'] = $auth_type;
        $requestValue['mgmt_remark'] = $mgmt_remark;
        $requestValue['log'] = LOGTIME;

        $this->db->where('request_id', $id);
        $this->db->update('tbl_request', $requestValue);
    }
    
    public function updateManagementBomRequestt_new($id, $req_type, $cutoff_date, $merchant_note, $bom_data) {
        foreach($bom_data as $key => $value) {
                
            $draftVal['consl_bom_qty'] = $value[9];
            $draftVal['excess_qty'] = $value[10];
            $draftVal['plan_bom_qty'] = $value[11];
            $draftVal['log'] = LOGTIME;
            $this->db->where('request_bom_id', $value[1]);
            $this->db->update('tbl_request_bom', $draftVal);
        }
            $req_data['auth_status'] = 3;
            $req_data['mgmt_approval'] = 3;
            $req_data['req_type'] = $req_type;
            $req_data['cutoff_date'] = $cutoff_date;
            $req_data['merchant_note'] = $merchant_note;
            $req_data['req_date'] = $this->mysqldatetime;
            $req_data['log'] = LOGTIME;
        $this->db->where('request_id',$id)->update('tbl_request',$req_data);
                    
                    
                
    }
    
    // *********************************************************************************************************** 
    // MANAGEMENT DEPARTMENT ENDS HERE 
    // **********************************************************************************************************//

    // *********************************************************************************************************** 
    // PURCHASE DEPARTMENT STARTS HERE 
    // **********************************************************************************************************//

    public function updateDepartmentPurchaseRequestt($id, $req_status) {
        $requestValue['req_status'] = $req_status;
        $requestValue['deprt_approval'] = $req_status;
        $requestValue['que_assign_date'] = $this->mysqldatetime;
        $requestValue['log'] = LOGTIME;

        $this->db->where('request_id', $id);
        $this->db->update('tbl_request', $requestValue);
    }
    
    // *********************************************************************************************************** 
    // PURCHASE DEPARTMENT ENDS HERE 
    // **********************************************************************************************************//

    public function getPIRequestSendDetailss($enqId, $reqId, $pId) {
        $sql = "SELECT a.*,c.item_desc,c.bcm,c.garment_size,c.appr_item_code,c.appr_item_col_code,c.size_dim,c.uom,c.plan_bom_qty,c.requirement_uom FROM tbl_purchase_indent a
                INNER JOIN tbl_request b on a.request_id=b.request_id
                INNER JOIN tbl_request_bom c on a.purchase_indent_id=c.purchase_indent_id
                WHERE a.purchase_indent_id = " . $pId . " AND b.flag=1";
        $data = $this->db->query($sql)->result_array();

        $vendor_sql = "SELECT *, vendorname as name FROM kn_master_bom_vendor";
        $vendor_data = $this->db->query($vendor_sql)->result_array();

        //$req_pi_sql = "SELECT * FROM tbl_request_purchase_indent WHERE request_id = $reqId ";
        $req_pi_sql = "SELECT a.* FROM tbl_request_purchase_indent a
                INNER JOIN tbl_request_bom b on a.request_bom_id=b.request_bom_id
                WHERE b.purchase_indent_id = " . $pId . " AND a.flag=1";
        $req_pi_data = $this->db->query($req_pi_sql)->result_array();
        
        // $payment_sql = "SELECT a.*,b.*,b.vendor_id as vendor_ids, c.*,d.contactname FROM tbl_request_status as a
        //                 INNER JOIN tbl_request_payment b ON a.purchase_indent_id = b.purchase_indent_id
        //                 INNER JOIN kn_master_bom_vendor c ON b.vendor_id=c.id
        //                 LEFT JOIN ".KN_USERS." d ON a.appr_by=d.id
        //                 WHERE b.purchase_indent_id = $pId ";
        $payment_sql = "SELECT a.*,d.contactname FROM tbl_request_status as a
                         LEFT JOIN ".KN_USERS." d ON a.appr_by=d.id
                         WHERE a.purchase_indent_id = $pId ";
        $payment_data = $this->db->query($payment_sql)->result_array();
                        
        $payment_sql1 = "SELECT a.*,b.*,b.vendor_id as vendor_ids, c.* FROM tbl_request_status as a
                        INNER JOIN tbl_request_payment b ON a.purchase_indent_id = b.purchase_indent_id
                        INNER JOIN kn_master_bom_vendor c ON b.vendor_id=c.id
                        WHERE b.purchase_indent_id = $pId ";
        $payment_data1 = $this->db->query($payment_sql1)->result_array();
//print_r($payment_sql); exit;
        $withinResult = $interResult = $importsResult = $paymentRequst = $paymentLog = $paymentRequestBill = $paymentPaidDetails = [];

        //$vendor_id = '';

        $status_sql = "SELECT * FROM tbl_request_payment as a
                        INNER JOIN kn_master_bom_vendor b ON a.vendor_id=b.id
                        WHERE a.purchase_indent_id = $pId ";
        $status_data = $this->db->query($status_sql)->result_array();

        $paid_sql = "SELECT * FROM tbl_bom_payment_paid WHERE purchase_indent_id = $pId";
        $paid_data = $this->db->query($paid_sql)->result_array();
        $advance_amt = [];
        
        foreach ($status_data as $key => $value) {
            $appr_status = $this->db->where('purchase_indent_id',$pId)->where('row_id',$value['row_id'])->get('tbl_request_status')->row()->appr_status;
            if($appr_status == '' || $appr_status == 2) {
                $mode = 'ADD';
            } else {
                $mode = 'CLOSED';
            }
            
            $paymentRequst[$key] = [ $value['request_payment_id'], $value['row_id'], $value['vendor_id'], $value['vendor_bank_name'],
                $value['acc_no'], $value['ifsc'], $value['proforma_no'], $value['proforma_date'],
                $value['proforma_value'], $value['quoted_currency'], $value['mode_of_payment'],
                $value['pay_by_date'], $value['amount_payable'], $value['currency'], $mode
            ];
            $advance_amt[$key] = $value['amount_payable'];
        }
    foreach ($payment_data1 as $key => $value) {
        $vendor_id1 = $value['vendor_ids'];
    }
    
    $vendor_id = $this->db->where('purchase_indent_id',$pId)->get('tbl_purchase_indent')->row()->vendor_id;
    
        foreach ($payment_data as $key => $value) {
            
            if($value['appr_status'] == 0 || $value['appr_status'] == 3) {
                $mode = 'ADD';
            } else {
                $mode = 'CLOSED';
            }
            
            if($value['appr_status'] == '' || $value['appr_status'] == 2) {
                $pur_mode = 'ADD';
            } else {
                $pur_mode = 'CLOSED';
            }
            if($value['payment_requirement'] == 'NIL') {
                $p_date = $value['appr_dt'];
            } else {
                $p_date = $value['pay_paid_sta_upd_dt'];
            }

            $paymentLog[$key] = [ $value['request_status_id'], $value['row_id'], $value['request_for'], $value['request_type'], $value['req_dt'], $value['payment_requirement'],
                $value['appr_status'], $value['appr_type'], $value['contactname'], $value['appr_dt'], 
                $value['payment_paid_status'], $p_date, $mode, $pur_mode
            ];

            foreach ($paid_data as $r => $res) {
                $paymentPaidDetails[$r] = [
                    '', $res['payment_paid_id'], $res['row_id'], @$res['vendor_id'], @$res['vendor_bank_name'], @$res['acc_no'], $res['mop'], $res['trans_id_code'], 
                    date('d-m-Y',strtotime($res['trans_date'])), $res['paid_towards'],$res['amount'], $res['currency'], $res['unit'], $res['total'], 
                    
                ];
            }

        }
        
        $pay_others = $this->db->where('purchase_indent_id',$pId)->get('tbl_request_payment_others')->result_array();
        if($pay_others) {
            foreach ($pay_others as $key => $value) {
                $appr_status = $this->db->where('purchase_indent_id',$pId)->where('row_id',$value['row_id'])->get('tbl_request_status')->row()->appr_status;
                if($appr_status == '' || $appr_status == 2) {
                    $mode = 'ADD';
                } else {
                    $mode = 'CLOSED';
                }
                if($value['invoice_date'] != '') {
                    $invoice_date = date('d-m-Y',strtotime($value['invoice_date']));
                } else {
                    $invoice_date = '';
                }
                if($value['dc_date'] != '') {
                    $dc_date = date('d-m-Y',strtotime($value['dc_date']));
                } else {
                    $dc_date = '';
                }
                @$paymentOthers[$key] = [ $value['others_id'], $value['row_id'], $value['pay_code'], $value['bank_name'] , $value['account_no'], $value['ifsc_code'], $value['expanse_incured'] ,$value['ref_dcno'] , $dc_date ,$value['ref_invoice_no'] ,$invoice_date ,$value['amount_payable'] ,$value['currency'] ,$value['mode_of_payment'] ,date('d-m-Y',strtotime($value['pay_by_date'])), $mode ];
            }
        } else {
            $paymentOthers[] = [ '', '', '', '', '', '', '', '' , '', '', '', '', '', '', '' ,'ADD' ];
        }
        
        $inv_data = $this->db->where('purchase_indent_id',$pId)->get('tbl_payment_invoice')->result_array();
        if($inv_data) {
            foreach ($inv_data as $key => $value) {
                $appr_status = $this->db->where('purchase_indent_id',$pId)->where('row_id',$value['row_id'])->get('tbl_request_status')->row()->appr_status;
                if($appr_status == '' || $appr_status == 2) {
                    $mode = 'ADD';
                } else {
                    $mode = 'CLOSED';
                }
                @$paymentRequestBill[$key] = [ $value['payment_id'], $value['row_id'], $value['vendor_name'] , $value['vendor_bankname'], $value['vendor_accountno'], $value['ifsc_code'] ,$value['invoice_no'] ,$value['invoice_date'] ,$value['invoice_value'] ,$value['advance_paid'] ,$value['debits'] ,$value['credits'], $value['amount_payable'], $value['curency'] , $value['mode_of_payment'], $value['pay_by_date'], $mode ];
            }
        } else {
            $paymentRequestBill[] = [ '', '', '', '', '', '', '', '', '', '', '', '', '','ADD' ];
        }
        
        //$cr_data = $this->db->where('purchase_indent_id',$pId)->get('tbl_credit_note')->result_array();
        $sql = "SELECT a.*,b.contactname as req_name FROM tbl_credit_note a 
                LEFT JOIN ".KN_USERS." b on a.approved_by=b.id
                WHERE a.purchase_indent_id='$pId' ";
        $cr_data = $this->db->query($sql)->result_array();
        if($cr_data) {
            foreach ($cr_data as $key => $value) {
                @$creditNoteDetails[$key] = [ '', $value['credit_note_id'], $value['description'], $value['invoice_no'], $value['account_name'], $value['account_no'] ,$value['transaction_id'] ,$value['transaction_date'] ,$value['credit_amount'] ,$value['currency'] , $value['approval_status'], $value['req_name'] , $value['approved_date'] ];
            }
        } else {
            $creditNoteDetails[] = [ '', '', '', '', '', '', '', '', '', '', '','','' ];
        }
        
        

        foreach ($data as $key => $value)
        {

            @$withinResult[$key] = [ 'add', $req_pi_data[$key]['request_purchase_indent_id'], $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $req_pi_data[$key]['unit_rate'], 
                $req_pi_data[$key]['amount'], $req_pi_data[$key]['gst'], $req_pi_data[$key]['gst_value'],$req_pi_data[$key]['sub_total']
            ];

            @$interResult[$key] = [ 'add', $req_pi_data[$key]['request_purchase_indent_id'], $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $req_pi_data[$key]['unit_rate'], 
                $req_pi_data[$key]['amount'], $req_pi_data[$key]['igst'], $req_pi_data[$key]['igst_value'], $req_pi_data[$key]['sub_total']
            ];

            @$importsResult[$key] = [ 'add', $req_pi_data[$key]['request_purchase_indent_id'], $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $req_pi_data[$key]['currency'], 
                $req_pi_data[$key]['unit_rate'], $req_pi_data[$key]['amount']
            ];

        }
        
        $pi_data = $this->db->where('purchase_indent_id',$pId)->get('tbl_purchase_indent')->result_array();

        $modeOfShipment = [ 'IMPS', 'NEFT', 'RTGS', 'SWIFT', 'CASH', 'CHEQUE' ];
        $currencyList = unserialize(ARRCURRENCYLIST);
        
        $inv_data = $this->db->where('purchase_indent_id',$pId)->get('tbl_payment_invoice')->result_array();
        if($inv_data) {
            foreach($inv_data as $key => $inv) {
                $inv_no[] = $inv['invoice_no'];
            }
        } else {
            $inv_no[] = '';
        }
        
        $rc_data = $this->db->where('purchase_indent_id',$pId)->get('tbl_request_status')->result_array();
        if($rc_data) {
            foreach($rc_data as $key => $rc) {
                $rc_no[] = $rc['row_id'];
            }
        } else {
            $rc_no[] = '';
        }
        
        $pi_invno = $pi_invdate = $pi_dcno = $pi_dcdate = [];
        
        
        $inv_nos = $this->db->where('purchase_indent_id',$pId)->group_by('invoice_no')->get('tbl_bom_in_house')->result_array();
        // $inv_sql = "SELECT * FROM tbl_bom_in_house as a where a.purchase_indent_id='$pId' GROUP BY a.invoice_no";
        // $inv_nos = $this->db->query($inv_sql)->result_array();

        foreach($inv_nos as $key =>$value) {
            // $pi_invno[] = $value['invoice_no'];
            $pi_invno[] = [ 'id'=> $value['invoice_no'], 'item_id'=> $value['dc_no'], 'name'=> $value['invoice_no'] ];
            $inv_date = date('d-m-Y',strtotime($value['invoice_date']));
            $pi_invdate[$key] = [ 'id'=> $inv_date, 'item_id'=> $value['invoice_no'], 'name'=> $inv_date ];
            $pi_dcno[] = $value['dc_no'];
            $dc_date = date('d-m-Y',strtotime($value['dc_date']));
            $pi_dcdate[$key] = [ 'id'=> $dc_date, 'item_id'=> $value['dc_no'], 'name'=> $dc_date ];
            
        }
        
        $output['withinStateDetails'] = $withinResult;
        $output['interStateDetails'] = $interResult;
        $output['importStateDetails'] = $importsResult;
        $output['paymentRequst'] = $paymentRequst;
        $output['advancepaiddetails'] = [];
        $output['vendor_data'] = $vendor_data;
        $output['vendor_id'] = $vendor_id;
        $output['fullData'] = $data;
        $output['pi_data'] = $pi_data;
        $output['modeOfShipment'] = $modeOfShipment;
        $output['currencyList'] = $currencyList;
        $output['paymentLog'] = $paymentLog;
        $output['paymentOthers'] = $paymentOthers;
        $output['paymentRequestBill'] = $paymentRequestBill;
        $output['creditNoteDetails'] = $creditNoteDetails;
        $output['paymentPaidDetails'] = $paymentPaidDetails;
        $output['inv_no'] = $inv_no;
        $output['advance_amt'] = $advance_amt;
        $output['pi_invno'] = $pi_invno;
        $output['pi_invdate'] = $pi_invdate;
        $output['pi_dcno'] = $pi_dcno;
        $output['pi_dcdate'] = $pi_dcdate;
        //$output['rc_no'] = $rc_no;
        return $output;
    }

    public function getBillPaidDetailss($enqId, $reqId, $pId) {
        $sql = "SELECT a.*,c.item_desc,c.bcm,c.garment_size,c.appr_item_code,c.appr_item_col_code,c.size_dim,c.uom,c.plan_bom_qty,c.requirement_uom FROM tbl_purchase_indent a
                INNER JOIN tbl_request b on a.request_id=b.request_id
                INNER JOIN tbl_request_bom c on a.purchase_indent_id=c.purchase_indent_id
                WHERE a.purchase_indent_id = $pId AND b.flag=1";
        $data = $this->db->query($sql)->result_array();
    //print_r($sql); exit;
        $vendor_sql = "SELECT *, vendorname as name FROM kn_master_bom_vendor";
        $vendor_data = $this->db->query($vendor_sql)->result_array();

       $req_pi_sql = "SELECT a.* FROM tbl_request_purchase_indent a
                INNER JOIN tbl_request_bom b on a.request_bom_id=b.request_bom_id
                WHERE b.purchase_indent_id = " . $pId . " AND a.flag=1";
        $req_pi_data = $this->db->query($req_pi_sql)->result_array();
        
        $payment_sql = "SELECT a.*,d.contactname FROM tbl_request_status as a
                         LEFT JOIN ".KN_USERS." d ON a.appr_by=d.id
                         WHERE a.purchase_indent_id = $pId ";
        $payment_data = $this->db->query($payment_sql)->result_array();
                        
        $payment_sql1 = "SELECT a.*,b.*,b.vendor_id as vendor_ids, c.* FROM tbl_request_status as a
                        INNER JOIN tbl_request_payment b ON a.purchase_indent_id = b.purchase_indent_id
                        INNER JOIN kn_master_bom_vendor c ON b.vendor_id=c.id
                        WHERE b.purchase_indent_id = $pId ";
        $payment_data1 = $this->db->query($payment_sql1)->result_array();

        $withinResult = $interResult = $importsResult = $paymentRequst = $paymentLog = $paymentRequestBill = $paymentPaidDetails = [];

        $vendor_id = '';

        $status_sql = "SELECT * FROM tbl_request_payment as a
                        INNER JOIN kn_master_bom_vendor b ON a.vendor_id=b.id
                        WHERE a.purchase_indent_id = $pId ";
        $status_data = $this->db->query($status_sql)->result_array();
        //print_r($status_sql); exit;
        $others_sql = "SELECT * FROM tbl_request_payment_others WHERE purchase_indent_id = $pId";
        $payment_others = $this->db->query($others_sql)->result_array();
        
        if($payment_others) {
            foreach($payment_others as $key => $value) {
                if($value['invoice_date'] != '') {
                    $invoice_date = date('d-m-Y',strtotime($value['invoice_date']));
                } else {
                    $invoice_date = '';
                }
                if($value['dc_date'] != '') {
                    $dc_date = date('d-m-Y',strtotime($value['dc_date']));
                } else {
                    $dc_date = '';
                }
                
                $paymentOthers[$key] = [ $value['others_id'], $value['row_id'], $value['pay_code'], $value['bank_name'],
                    $value['account_no'], $value['ifsc_code'], $value['expanse_incured'], $value['ref_dcno'],
                    $dc_date, $value['ref_invoice_no'],
                    $invoice_date, $value['amount_payable'], $value['currency'], $value['mode_of_payment'], date('d-m-Y',strtotime($value['pay_by_date']))
                ];
            }
        } else {
            $paymentOthers[] = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '' ];
        }

        $paid_sql = "SELECT * FROM tbl_bom_payment_paid WHERE purchase_indent_id = $pId";
        $paid_data = $this->db->query($paid_sql)->result_array();
        $advance_amt = [];
        
        foreach ($status_data as $key => $value) {
            $paymentRequst[$key] = [ $value['request_id'], $value['row_id'], $value['vendor_id'], $value['vendor_bank_name'],
                $value['acc_no'], $value['ifsc'], $value['proforma_no'], $value['proforma_date'],
                $value['proforma_value'], $value['quoted_currency'], $value['mode_of_payment'],
                $value['pay_by_date'], $value['amount_payable'], $value['currency']
            ];
            $advance_amt[$key] = $value['amount_payable'];
        }

        foreach ($payment_data1 as $key => $value) {
            $vendor_id1 = $value['vendor_ids'];
        }
        
        $vendor_id = $this->db->where('purchase_indent_id',$pId)->get('tbl_purchase_indent')->row()->vendor_id;
    
        foreach ($payment_data as $key => $value) {
            $tec_total = 0;
            if($value['payment_paid_status'] == 'PAID' || $value['payment_requirement'] == 'NIL') {
                $mode = 'PAID';
            } else {
                $mode = 'PENDING';
            }
            $rec_data['row_id'] = $value['row_id'];
            $rec_data['pId'] = $pId;
            
            $tec_totals = $this->getRCDataa($rec_data);
            $tec_total =  $tec_totals['total_amt']; 
            if($value['payment_requirement'] == 'NIL') {
                $p_date = $value['appr_dt'];
            } else {
                $p_date = $value['pay_paid_sta_upd_dt'];
            }
            $paymentLog[$key] = [ $value['request_status_id'], $value['row_id'], $value['request_for'], $value['request_type'], $value['req_dt'], $value['payment_requirement'],
                $value['appr_status'], $value['appr_type'], $value['contactname'], $value['appr_dt'], $value['payment_paid_status'], $p_date , $mode, $tec_total
            ];

            //$inv_sql = "SELECT * FROM tbl_request_payment_invoice WHERE payment_id = ".$value['request_payment_id'];
            //$inv_data = $this->db->query($inv_sql)->result_array();

            // foreach ($inv_data as $r => $res) {
            //     $paymentRequestBill[$r] = [
            //         '', '', $value['vendor_id'], $value['vendor_bank_name'], $value['acc_no'], $value['ifsc'], $value['shift_code'], 
            //         $res['invoice_no'], $res['invoice_date'], $res['invoice_value'], '', $res['debit_if_any'], 
            //         $res['acc_mop'], $res['pay_by_date'], '', $res['currency']
            //     ];
            // }

            foreach ($paid_data as $r => $res) {
                $paymentPaidDetails[$r] = [
                    '', $res['payment_paid_id'], $res['row_id'], @$res['vendor_id'], @$res['vendor_bank_name'], @$res['acc_no'], $res['mop'], $res['trans_id_code'], 
                    $res['trans_date'], $res['paid_towards'],$res['amount'], $res['currency'], $res['unit'], $res['total']
                    
                ];
            }

        }

        foreach ($data as $key => $value)
        {

            $withinResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $req_pi_data[$key]['unit_rate'], 
                $req_pi_data[$key]['amount'], $req_pi_data[$key]['gst'], $req_pi_data[$key]['gst_value'], $req_pi_data[$key]['sub_total']
            ];

            $interResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $req_pi_data[$key]['unit_rate'], 
                $req_pi_data[$key]['amount'], $req_pi_data[$key]['igst'], $req_pi_data[$key]['igst_value'], $req_pi_data[$key]['sub_total']
            ];

            $importsResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $req_pi_data[$key]['currency'], 
                $req_pi_data[$key]['unit_rate'], $req_pi_data[$key]['amount']
            ];

        }
        
        $inv_data = $this->db->where('purchase_indent_id',$pId)->get('tbl_payment_invoice')->result_array();
        if($inv_data) {
            foreach ($inv_data as $key => $value) {
                @$paymentRequestBill[$key] = [ $value['payment_id'], $value['row_id'], $value['vendor_name'], $value['vendor_bankname'], $value['vendor_accountno'], $value['ifsc_code'] ,$value['invoice_no'] ,$value['invoice_date'] ,$value['invoice_value'] ,$value['advance_paid'] ,$value['debits'] , $value['credits'], $value['amount_payable'], $value['curency'] , $value['mode_of_payment'], date('d-m-Y',strtotime($value['pay_by_date'])) ];
            }
        } else {
            $paymentRequestBill[] = [ '', '', '', '', '', '', '', '', '', '', '', '', '' ];
        }
        
        // $cr_data = $this->db->where('purchase_indent_id',$pId)->get('tbl_credit_note')->result_array();
        $sql = "SELECT a.*,b.contactname as req_name FROM tbl_credit_note a 
                LEFT JOIN ".KN_USERS." b on a.approved_by=b.id
                WHERE a.purchase_indent_id='$pId' ";
        $cr_data = $this->db->query($sql)->result_array();
        if($cr_data) {
            foreach ($cr_data as $key => $value) {
                @$creditNoteDetails[$key] = [ '', $value['credit_note_id'], $value['description'], $value['invoice_no'], $value['account_name'], $value['account_no'] ,$value['transaction_id'] ,$value['transaction_date'] ,$value['credit_amount'] ,$value['currency'] , $value['approval_status'], $value['req_name'] , $value['approved_date'] ];
            }
        } else {
            $creditNoteDetails[] = [ '', '', '', '', '', '', '', '', '', '', '','','' ];
        }
        
        //$creditNoteDetails[] = [ '', '', '', '', '', '', '', '', '', '', '', '' ];

        $modeOfShipment = [ 'IMPS', 'NEFT', 'RTGS', 'SWIFT', 'CASH', 'CHEQUE' ];
        $currencyList = unserialize(ARRCURRENCYLIST);
        
        $inv_nos = $this->db->where('purchase_indent_id',$pId)->group_by('invoice_no')->get('tbl_bom_in_house')->result_array();
        foreach($inv_nos as $key =>$value) {
            $pi_invno[] = $value['invoice_no'];
        }
        
        $inv_data = $this->db->where('purchase_indent_id',$pId)->get('tbl_payment_invoice')->result_array();
        if($inv_data) {
            foreach($inv_data as $key => $inv) {
                $inv_no[] = $inv['invoice_no'];
            }
        } else {
            $inv_no[] = '';
        }
        
        $rc_data = $this->db->where('purchase_indent_id',$pId)->where('appr_status',1)->where('payment_requirement !=','NIL')->get('tbl_request_status')->result_array();
        if($rc_data) {
            foreach($rc_data as $key => $rc) {
                $rc_no[] = $rc['row_id'];
            }
        } else {
            $rc_no[] = '';
        }

        $output['withinStateDetails'] = $withinResult;
        $output['interStateDetails'] = $interResult;
        $output['importStateDetails'] = $importsResult;
        $output['paymentRequst'] = $paymentRequst;
        $output['paymentOthers'] = $paymentOthers;
        $output['advancepaiddetails'] = $paymentPaidDetails;
        $output['vendor_data'] = $vendor_data;
        $output['vendor_id'] = $vendor_id;
        $output['fullData'] = $data;
        $output['modeOfShipment'] = $modeOfShipment;
        $output['currencyList'] = $currencyList;
        $output['paymentLog'] = $paymentLog;
        $output['paymentRequestBill'] = $paymentRequestBill;
        $output['creditNoteDetails'] = $creditNoteDetails;
        $output['paymentPaidDetails'] = $paymentPaidDetails;
        $output['inv_no'] = $inv_no;
        $output['pi_invno'] = $pi_invno;
        $output['rc_no'] = $rc_no;
        $output['advance_amt'] = $advance_amt;
        return $output;
    }
    
    public function getMgmtPIApplDetailss($enqId, $reqId,  $type, $pId) {
        $sql = "SELECT a.*,c.item_desc,c.bcm,c.garment_size,c.appr_item_code,c.appr_item_col_code,c.size_dim,c.uom,c.plan_bom_qty,c.requirement_uom FROM tbl_purchase_indent a
                INNER JOIN tbl_request b on a.request_id=b.request_id
                INNER JOIN tbl_request_bom c on a.purchase_indent_id=c.purchase_indent_id
                WHERE a.purchase_indent_id = " . $pId . " ";
        $data = $this->db->query($sql)->result_array();

        $vendor_sql = "SELECT *, vendorname as name FROM kn_master_bom_vendor";
        $vendor_data = $this->db->query($vendor_sql)->result_array();

        //$req_pi_sql = "SELECT * FROM tbl_request_purchase_indent WHERE purchase_indent_id = $pId ";
        $req_pi_sql = "SELECT a.* FROM tbl_request_purchase_indent a
                INNER JOIN tbl_request_bom b on a.request_bom_id=b.request_bom_id
                WHERE b.purchase_indent_id = " . $pId . " AND a.flag=1";
        $req_pi_data = $this->db->query($req_pi_sql)->result_array();
        
        $payment_sql = "SELECT a.*,b.*,c.*,d.contactname FROM tbl_request_status as a
                        INNER JOIN tbl_purchase_indent b ON a.purchase_indent_id = b.purchase_indent_id
                        INNER JOIN kn_master_bom_vendor c ON b.vendor_id=c.id
                        LEFT JOIN ".KN_USERS." d ON a.appr_by=d.id
                        WHERE b.purchase_indent_id = $pId ";
        $payment_data = $this->db->query($payment_sql)->result_array();
    //print_r($payment_data); exit;
        $withinResult = $interResult = $importsResult = $paymentRequst = $paymentLog = $paymentRequestBill = $paymentPaidDetails = [];

        $vendor_id = '';

        $status_sql = "SELECT * FROM tbl_request_status as a
                        INNER JOIN tbl_request_payment b ON a.purchase_indent_id = b.purchase_indent_id
                        INNER JOIN kn_master_bom_vendor c ON b.vendor_id=c.id
                        WHERE b.purchase_indent_id = $pId GROUP BY b.vendor_id ";
        $status_data = $this->db->query($status_sql)->result_array();

        $paid_sql = "SELECT * FROM tbl_bom_payment_paid WHERE purchase_indent_id = $pId";
        $paid_data = $this->db->query($paid_sql)->result_array();
        
        foreach ($status_data as $key => $value) {
            $paymentRequst[$key] = [ $value['request_id'], $value['vendor_id'], $value['vendor_bank_name'],
                $value['acc_no'], $value['ifsc'], $value['proforma_no'], $value['proforma_date'],
                $value['proforma_value'], $value['quoted_currency'], $value['mode_of_payment'],
                $value['pay_by_date'], $value['amount_payable'], $value['currency']
            ];
        }

        foreach ($payment_data as $key => $value) {


            $paymentLog[$key] = [ $value['request_status_id'], $value['request_for'], $value['request_type'], $value['req_dt'], $value['payment_requirement'],
                $value['appr_status'], $value['appr_type'], $value['contactname'], $value['appr_dt'], 
                $value['payment_paid_status'], $value['pay_paid_sta_upd_dt'], $value['appr_status'],
            ];
        }

        foreach ($data as $key => $value)
        {

            $withinResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $req_pi_data[$key]['unit_rate'], 
                $req_pi_data[$key]['amount'], $req_pi_data[$key]['gst'], $req_pi_data[$key]['gst_value'],$req_pi_data[$key]['sub_total']
            ];

            $interResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $req_pi_data[$key]['unit_rate'], 
                $req_pi_data[$key]['amount'], $req_pi_data[$key]['igst'], $req_pi_data[$key]['igst_value'], $req_pi_data[$key]['sub_total']
            ];

            $importsResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $req_pi_data[$key]['currency'], 
                $req_pi_data[$key]['unit_rate'], $req_pi_data[$key]['amount']
            ];

        }
        $vendor_id = $this->db->where('purchase_indent_id',$pId)->get('tbl_purchase_indent')->row()->vendor_id;

        $pi_data = $this->db->where('purchase_indent_id',$pId)->get('tbl_purchase_indent')->result_array();

        $modeOfShipment = [ 'IMPS', 'NEFT', 'RTGS', 'SWIFT', 'CASH', 'CHEQUE' ];
        $currencyList = unserialize(ARRCURRENCYLIST);

        $output['withinStateDetails'] = $withinResult;
        $output['interStateDetails'] = $interResult;
        $output['importStateDetails'] = $importsResult;
        $output['paymentRequst'] = $paymentRequst;
        $output['advancepaiddetails'] = [];
        $output['vendor_data'] = $vendor_data;
        $output['vendor_id'] = $vendor_id;
        $output['fullData'] = $data;
        $output['pi_data'] = $pi_data;
        $output['modeOfShipment'] = $modeOfShipment;
        $output['currencyList'] = $currencyList;
        $output['paymentLog'] = $paymentLog;
        return $output;
    }

    public function getMgmtPIRequestSendDetailss($enqId, $reqId,  $type, $pId) {
        $sql = "SELECT a.*,c.item_desc,c.bcm,c.garment_size,c.appr_item_code,c.appr_item_col_code,c.size_dim,c.uom,c.plan_bom_qty,c.requirement_uom FROM tbl_purchase_indent a
                INNER JOIN tbl_request b on a.request_id=b.request_id
                INNER JOIN tbl_request_bom c on a.purchase_indent_id=c.purchase_indent_id
                WHERE a.purchase_indent_id = " . $pId . " ";
        $data = $this->db->query($sql)->result_array();

        $vendor_sql = "SELECT *, vendorname as name FROM kn_master_bom_vendor";
        $vendor_data = $this->db->query($vendor_sql)->result_array();

        //$req_pi_sql = "SELECT * FROM tbl_request_purchase_indent WHERE purchase_indent_id = $pId ";
        $req_pi_sql = "SELECT a.* FROM tbl_request_purchase_indent a
                INNER JOIN tbl_request_bom b on a.request_bom_id=b.request_bom_id
                WHERE b.purchase_indent_id = " . $pId . " AND a.flag=1";
        $req_pi_data = $this->db->query($req_pi_sql)->result_array();
        
        $payment_sql = "SELECT * FROM tbl_request_status as a
                        INNER JOIN tbl_request_payment b ON a.purchase_indent_id = b.purchase_indent_id
                        INNER JOIN kn_master_bom_vendor c ON b.vendor_id=c.id
                        WHERE b.purchase_indent_id = $pId ";
        $payment_data = $this->db->query($payment_sql)->result_array();

        $withinResult = $interResult = $importsResult = $paymentRequst = $paymentLog = $paymentRequestBill = $paymentPaidDetails = [];

        $vendor_id = '';

        $status_sql = "SELECT * FROM tbl_request_status as a
                        INNER JOIN tbl_request_payment b ON a.purchase_indent_id = b.purchase_indent_id
                        INNER JOIN kn_master_bom_vendor c ON b.vendor_id=c.id
                        WHERE b.purchase_indent_id = $pId GROUP BY b.vendor_id ";
        $status_data = $this->db->query($status_sql)->result_array();

        $paid_sql = "SELECT * FROM tbl_bom_payment_paid WHERE purchase_indent_id = $pId";
        $paid_data = $this->db->query($paid_sql)->result_array();
        
        foreach ($status_data as $key => $value) {
            $paymentRequst[$key] = [ $value['request_id'], $value['vendor_id'], $value['vendor_bank_name'],
                $value['acc_no'], $value['ifsc'], $value['proforma_no'], date('d-m-Y',strtotime($value['proforma_date'])),
                $value['proforma_value'], $value['quoted_currency'], $value['mode_of_payment'],
                $value['pay_by_date'], $value['amount_payable'], $value['currency']
            ];
        }

        foreach ($payment_data as $key => $value) {

            // $vendor_id = $value['vendor_id'];

            $paymentLog[$key] = [ $value['request_status_id'], $value['request_for'], $value['request_type'], $value['req_dt'], $value['payment_requirement'],
                $value['appr_status'], $value['appr_type'], $value['appr_by'], $value['appr_dt'], 
                $value['payment_paid_status'], $value['pay_paid_sta_upd_dt'],
            ];

            $inv_sql = "SELECT * FROM tbl_request_payment_invoice WHERE payment_id = ".$value['request_payment_id'];
            $inv_data = $this->db->query($inv_sql)->result_array();

            foreach ($inv_data as $r => $res) {
                $paymentRequestBill[$r] = [
                    '', $res['payment_invoice_id'], $value['vendor_id'], $value['vendor_bank_name'], $value['acc_no'], $value['ifsc'], 
                    $res['invoice_no'], $res['proforma_date'], $res['proforma_value'], $res['quoted_currency'], $res['mode_of_payment'], 
                    $res['pay_by_date'], $res['amount_payable'],$res['currency']
                ];
            }

            foreach ($paid_data as $r => $res) {
                $paymentPaidDetails[$r] = [
                    '', $value['request_status_id'], '', $value['vendor_id'], $value['vendor_bank_name'], $value['acc_no'], $res['mop'], $res['trans_id_code'], 
                    @$res['proforma_no'], $res['cheque_no'], $res['cheque_date'], $res['paid_towards'], 
                    $res['amount'], $res['currency']
                ];
            }

        }

        foreach ($data as $key => $value)
        {

            $withinResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $req_pi_data[$key]['unit_rate'], 
                $req_pi_data[$key]['amount'], $req_pi_data[$key]['gst'], $req_pi_data[$key]['gst_value'],$req_pi_data[$key]['sub_total']
            ];

            $interResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $req_pi_data[$key]['unit_rate'], 
                $req_pi_data[$key]['amount'], $req_pi_data[$key]['igst'], $req_pi_data[$key]['igst_value'], $req_pi_data[$key]['sub_total']
            ];

            $importsResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $req_pi_data[$key]['currency'], 
                $req_pi_data[$key]['unit_rate'], $req_pi_data[$key]['amount']
            ];

        }
        $vendor_id = $this->db->where('purchase_indent_id',$pId)->get('tbl_purchase_indent')->row()->vendor_id;

        $pi_data = $this->db->where('purchase_indent_id',$pId)->get('tbl_purchase_indent')->result_array();

        $modeOfShipment = [ 'IMPS', 'NEFT', 'RTGS', 'SWIFT', 'CASH', 'CHEQUE' ];
        $currencyList = unserialize(ARRCURRENCYLIST);

        $output['withinStateDetails'] = $withinResult;
        $output['interStateDetails'] = $interResult;
        $output['importStateDetails'] = $importsResult;
        $output['paymentRequst'] = $paymentRequst;
        $output['advancepaiddetails'] = [];
        $output['vendor_data'] = $vendor_data;
        $output['vendor_id'] = $vendor_id;
        $output['fullData'] = $data;
        $output['pi_data'] = $pi_data;
        $output['modeOfShipment'] = $modeOfShipment;
        $output['currencyList'] = $currencyList;
        $output['paymentLog'] = $paymentLog;
        $output['paymentRequestBill'] = $paymentRequestBill;
        $output['paymentPaidDetails'] = $paymentPaidDetails;
        return $output;
    }

    public function getManagementPIApprovalListt()
    {
        // $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.* FROM tbl_request as a 
        //         inner join kn_order_enquiry as b on a.enquiry_id=b.id 
        //         inner join kn_master_brands as c on b.brandId=c.id
        //         inner join kn_users as d on a.auth_by=d.id
        //         inner join tbl_request_status as e on a.request_id=e.request_id
        //         where a.type=3 and a.mgmt_approval=1 and a.pi_appl_status=1 and a.flag=1 GROUP BY e.request_id ORDER BY a.log DESC";
        
        $sql = "SELECT a.*, a.log as recent_log,f.cutoff_date,f.auth_type, c.brandname, b.isriorcode, d.contactname as auth_name, e.req_dt, e.appr_type, e.request_type, e.log as logs, e.* FROM tbl_purchase_indent as a
                inner join tbl_request as f on a.request_id=f.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join kn_master_brands as c on b.brandId=c.id
                left join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join ".KN_USERS." as d on e.appr_by=d.id
                where f.type=3 and f.mgmt_approval=1 and a.pi_appl_status=1 and a.pi_list_status=0 and f.flag=1 and f.subscriberid = " . $this->db->escape($this->subscriberId)." ORDER BY a.log DESC";
                
        $data = $this->db->query($sql)->result_array();
      
        

        return $data;

    }

    public function updateManagementPurchaseIndentAppll($eId, $reqId, $req_data, $inv_data, $amt_pay, $pId)
    {
        //echo "<pre>"; print_r($req_data); exit;

        $queue_sql = "SELECT MAX(pi_queue_no)+1 as last_queue_no FROM tbl_purchase_indent ORDER BY log DESC";
        $queue_data = $this->db->query($queue_sql)->result_array();

        $q_sql = "SELECT ref_queue_no FROM tbl_request WHERE request_id =".$reqId;
        $q_data = $this->db->query($q_sql)->result_array();

        $ord_sql = "SELECT reqforisrior FROM kn_order_enquiry WHERE id=$eId";
        $ord_data = $this->db->query($ord_sql)->result_array();
        $reqforisrior = $ord_data[0]['reqforisrior'];
        $ArrIsrIor   = unserialize(ARRISRIOR);

        $queue_no = $queue_data[0]['last_queue_no']; 
        if($queue_no == "") { $queue_no = 1; }

        $ref_queue_no = $q_data[0]['ref_queue_no']."/BPI-".$queue_no;
        
        foreach ($req_data as $key => $value) {
        if($value[5] == 1 && ($value[7] == '' || $value[7] == null))
        {
            $this->db->where('purchase_indent_id', $pId);
            $this->db->update('tbl_purchase_indent', array('pi_dt'=> $this->mysqldatetime, 'pi_ref_queue_no'=> $ref_queue_no, 'pi_queue_no'=> $queue_no,'log'=>LOGTIME));
            
            $count = 0;

                $updateValue = [];
                $updateValue['appr_status'] = $value[5];
                $updateValue['appr_type'] = $value[6];
                //$updateValue['appr_dt'] = LOGTIME;
                $updateValue['appr_by'] = $this->userid;
                $updateValue['log'] = LOGTIME;
                $updateValue['appr_dt'] = $this->mysqldatetime;
                $count++; 
                
                $this->db->where('request_status_id', $value[0]);
                $this->db->update('tbl_request_status', $updateValue);
            
        } else {
            $updateValue['appr_status'] = $value[5];
            $updateValue['appr_type'] = $value[6];
            $updateValue['appr_by'] = $this->userid;
            $updateValue['appr_dt'] = $this->mysqldatetime;
            $updateValue['log'] = LOGTIME;
            $this->db->where('request_status_id', $value[0]);
            $this->db->update('tbl_request_status', $updateValue);
        }
        
        if($count>0) {
            $this->db->where('purchase_indent_id', $pId);
            $this->db->update('tbl_purchase_indent', array('pi_list_status'=> 1, 'log'=>LOGTIME));
        }
            
        }


        
    }
    
    public function updateManagementPIDataa($eId, $reqId, $req_data, $inv_data, $amt_pay, $pId)
    {
        //echo "<pre>"; print_r($req_data); exit;

        $queue_sql = "SELECT MAX(pi_queue_no)+1 as last_queue_no FROM tbl_purchase_indent ORDER BY log DESC";
        $queue_data = $this->db->query($queue_sql)->result_array();

        $q_sql = "SELECT ref_queue_no FROM tbl_request WHERE request_id =".$reqId;
        $q_data = $this->db->query($q_sql)->result_array();

        $ord_sql = "SELECT reqforisrior FROM kn_order_enquiry WHERE id=$eId";
        $ord_data = $this->db->query($ord_sql)->result_array();
        $reqforisrior = $ord_data[0]['reqforisrior'];
        $ArrIsrIor   = unserialize(ARRISRIOR);

        $queue_no = $queue_data[0]['last_queue_no']; 
        if($queue_no == "") { $queue_no = 1; }

        $ref_queue_no = $q_data[0]['ref_queue_no']."/BPI-".$queue_no;
        
        foreach ($req_data as $key => $value) {
        if($value[6] == 1 && $value[8] == '')
        {
            $this->db->where('purchase_indent_id', $pId);
            $this->db->update('tbl_purchase_indent', array('pi_dt'=> $this->mysqldatetime, 'pi_ref_queue_no'=> $ref_queue_no, 'pi_queue_no'=> $queue_no,'log'=>LOGTIME));
            
                $count = 0;
             
                $updateValue = [];
                $updateValue['appr_status'] = $value[6];
                $updateValue['appr_type'] = $value[7];
                //$updateValue['appr_dt'] = LOGTIME;
                $updateValue['appr_by'] = $this->userid;
                $updateValue['log'] = LOGTIME;
                $updateValue['appr_dt'] = $this->mysqldatetime;
                $count++; 
                
                $this->db->where('request_status_id', $value[0]);
                $this->db->update('tbl_request_status', $updateValue);
            
        } else {
                $updateValue = [];
                $updateValue['appr_status'] = $value[6];
                $updateValue['appr_type'] = $value[7];
                $updateValue['log'] = LOGTIME;
                $this->db->where('request_status_id', $value[0]);
                $this->db->update('tbl_request_status', $updateValue);
        }
        
        if($count > 0) {
            $this->db->where('purchase_indent_id', $pId);
            $this->db->update('tbl_purchase_indent', array('pi_list_status'=> 1, 'log'=>LOGTIME));
        }
            
        }


        
    }
    
    public function updateManagementCreditDataa($eId, $reqId, $pId, $credit_data)
    {
        //echo "<pre>"; print_r($credit_data); exit;
        
        foreach ($credit_data as $key => $value) {
        if($value[10] == 1 && $value[11] == '')
        {
                $count = 0;
             
                $updateValue = [];
                $updateValue['approval_status'] = $value[10];
                $updateValue['approved_by'] = $this->userid;
                $updateValue['log'] = LOGTIME;
                $updateValue['approved_date'] = $this->mysqldatetime;
                $count++; 
                
                $this->db->where('credit_note_id', $value[1]);
                $this->db->update('tbl_credit_note', $updateValue);
            
        } 
        else {
                $updateValue = [];
                $updateValue['approval_status'] = $value[10];
                $updateValue['log'] = LOGTIME;
                $this->db->where('credit_note_id', $value[1]);
                $this->db->update('tbl_credit_note', $updateValue);
        }
        
            if($count > 0) {
                $this->db->where('purchase_indent_id', $pId);
                $this->db->update('tbl_purchase_indent', array('log'=>LOGTIME));
            }
            
        }


        
    }    
    public function updateAdvanceDetailss($eId, $reqId, $pId, $req_data)
    {
        @$row_data = $this->db->where('purchase_indent_id',$pId)->order_by('request_status_id','desc')->get('tbl_request_status')->row()->row_id;
        if(is_null($row_data))
        {
            $row_id = 'RC 1'; 
        } 
        else
        {
            $bondLen = strlen($row_data)-3;
            $bondOnlyNum = filter_var($row_data, FILTER_SANITIZE_NUMBER_INT);
            $row_id = 'RC '.sprintf('%0'.$bondLen.'d', $bondOnlyNum + 1);
        }
        $this->db->where('purchase_indent_id', $pId);
        $this->db->update('tbl_purchase_indent', array('log'=>LOGTIME));
        
        foreach ($req_data as $key => $value) {
            if($value[0] != '') {
                $adval['mode_of_payment'] = $value['10'];
                $adval['pay_by_date'] = $value['11'];
                $adval['amount_payable'] = $value['12'];
                $adval['currency'] = $value['13'];
                $adval['log'] = LOGTIME;
                $this->db->where('request_payment_id', $value[0]);
                $this->db->update('tbl_request_payment', $adval); 
                    
            } else if($value[0] == '' && $value[10] != '') {
                    
                @$row_data = $this->db->where('purchase_indent_id',$pId)->order_by('request_status_id','desc')->get('tbl_request_status')->row()->row_id;
                if(is_null($row_data))
                {
                    $row_id = 'RC 1'; 
                } 
                else
                {
                    $bondLen = strlen($row_data)-3;
                    $bondOnlyNum = filter_var($row_data, FILTER_SANITIZE_NUMBER_INT);
                    $row_id = 'RC '.sprintf('%0'.$bondLen.'d', $bondOnlyNum + 1);
                }
                $old_data = $this->db->where('purchase_indent_id',$pId)->get('tbl_request_payment')->row();
                $adval['enquiry_id'] = $old_data->enquiry_id;
                $adval['request_id'] = $old_data->request_id;
                $adval['purchase_indent_id'] = $old_data->purchase_indent_id;
                $adval['vendor_id'] = $old_data->vendor_id;
                $adval['proforma_no'] = $old_data->proforma_no;
                $adval['proforma_value'] = $old_data->proforma_value;
                $adval['proforma_date'] = $old_data->proforma_date;
                $adval['quoted_currency'] = $old_data->quoted_currency;
                $adval['vendor_bank_name'] = $old_data->vendor_bank_name;
                $adval['acc_no'] = $old_data->acc_no;
                $adval['ifsc'] = $old_data->ifsc;
                $adval['mode_of_payment'] = $value['10'];
                $adval['pay_by_date'] = $value['11'];
                $adval['amount_payable'] = $value['12'];
                $adval['currency'] = $value['13'];
                $adval['type_of_mode'] = 'S';
                $adval['row_id'] = $row_id;
                $adval['log'] = LOGTIME;
                $this->db->insert('tbl_request_payment', $adval); 
                    
                $adStatus['enquiry_id'] = $eId;
                $adStatus['request_id'] = $reqId;
                $adStatus['purchase_indent_id'] = $pId;
                $adStatus['request_for'] = 'PAY. APPROVAL';
                $adStatus['payment_requirement'] = 'ADV. PAYMENT';
                $adStatus['type_of_mode'] = 'S';
                $adStatus['row_id'] = $row_id;
                $this->db->insert('tbl_request_status', $adStatus);
            }
            
        }
        
        
        
    }
    
    public function updateOthersDetailss($eId, $reqId, $pId, $others_data)
    {
        
        foreach($others_data as $key => $ovalue) {
                    //$othersvalue = [];
                    $othersvalue['pay_code'] = $ovalue[2];
                    $othersvalue['bank_name'] = $ovalue[3];
                    $othersvalue['account_no'] = $ovalue[4];
                    $othersvalue['ifsc_code'] = $ovalue[5];
                    $othersvalue['expanse_incured'] = $ovalue[6];
                    $othersvalue['ref_dcno'] = $ovalue[7];
                    $othersvalue['dc_date'] = $ovalue[8];
                    $othersvalue['ref_invoice_no'] = $ovalue[9];
                    $othersvalue['invoice_date'] = $ovalue[10];
                    $othersvalue['amount_payable'] = $ovalue[11];
                    $othersvalue['currency'] = $ovalue[12];
                    $othersvalue['mode_of_payment'] = $ovalue[13];
                    $othersvalue['pay_by_date'] = $ovalue[14];
                    $othersvalue['log'] = LOGTIME;
                    $othersvalue['purchase_indent_id'] = $pId;
                    // print_r($ovalue[0]); exit;
                    if($ovalue[0] == '' && $ovalue[2] != '' ) {
                        @$row_data = $this->db->where('purchase_indent_id',$pId)->order_by('request_status_id','desc')->get('tbl_request_status')->row()->row_id;
                        if(is_null($row_data))
                        {
                            $row_id = 'RC 1'; 
                        } 
                        else
                        {
                            $bondLen = strlen($row_data)-3;
                            $bondOnlyNum = filter_var($row_data, FILTER_SANITIZE_NUMBER_INT);
                            $row_id = 'RC '.sprintf('%0'.$bondLen.'d', $bondOnlyNum + 1);
                        }
                        $othersvalue['row_id'] = $row_id;
                        $this->db->insert('tbl_request_payment_others', $othersvalue);
                        
                        $othStatus['enquiry_id'] = $eId;
                        $othStatus['request_id'] = $reqId;
                        $othStatus['purchase_indent_id'] = $pId;
                        $othStatus['request_for'] = 'PAY. APPROVAL';
                        //$othStatus['request_type'] = 'REGULAR';
                        $othStatus['payment_requirement'] = 'OTH. PAYMENT';
                        //$othStatus['req_dt'] = date('d/m/Y h:i A',strtotime(LOGTIME));
                        $othStatus['type_of_mode'] = 'S';
                        $othStatus['row_id'] = $row_id;
                        $this->db->insert('tbl_request_status', $othStatus);
                    }
                }
    }
    
    public function updateBillDetailss($eId, $reqId, $pId, $bill_data)
    {
        
        foreach($bill_data as $key => $invalue) {
                    $invValue = [];
                    $invValue['vendor_name'] = $invalue[2];
                    $invValue['vendor_bankname'] = $invalue[3];
                    $invValue['vendor_accountno'] = $invalue[4];
                    $invValue['ifsc_code'] = $invalue[5];
                    $invValue['invoice_no'] = $invalue[6];
                    $invValue['invoice_date'] = $invalue[7];
                    $invValue['invoice_value'] = $invalue[8];
                    $invValue['advance_paid'] = $invalue[9];
                    $invValue['debits'] = $invalue[10];
                    $invValue['amount_payable'] = $invalue[12];
                    $invValue['curency'] = $invalue[13];
                    $invValue['mode_of_payment'] = $invalue[14];
                    $invValue['pay_by_date'] = $invalue[15];
                    $invValue['log'] = LOGTIME;
                    $invValue['purchase_indent_id'] = $pId;
                    if($invalue[0] == '' && $invalue[6] != '') {
                        @$row_data = $this->db->where('purchase_indent_id',$pId)->order_by('request_status_id','desc')->get('tbl_request_status')->row()->row_id;
                        if(is_null($row_data))
                        {
                            $row_id = 'RC 1'; 
                        } 
                        else
                        {
                            $bondLen = strlen($row_data)-3;
                            $bondOnlyNum = filter_var($row_data, FILTER_SANITIZE_NUMBER_INT);
                            $row_id = 'RC '.sprintf('%0'.$bondLen.'d', $bondOnlyNum + 1);
                        }
                        $invValue['row_id'] = $row_id;
                        $this->db->insert('tbl_payment_invoice', $invValue);
                        
                        $bilStatus['enquiry_id'] = $eId;
                        $bilStatus['request_id'] = $reqId;
                        $bilStatus['purchase_indent_id'] = $pId;
                        $bilStatus['request_for'] = 'PAY. APPROVAL';
                        //$bilStatus['request_type'] = 'REGULAR';
                        $bilStatus['payment_requirement'] = 'BILL PAYMENT';
                        //$bilStatus['req_dt'] = date('d/m/Y h:i A',strtotime(LOGTIME));
                        $bilStatus['type_of_mode'] = 'S';
                        $bilStatus['row_id'] = $row_id;
                        $this->db->insert('tbl_request_status', $bilStatus);
                        
                    } else {
                        $this->db->where('payment_id', $invalue[0]);
                        $this->db->update('tbl_payment_invoice', $invValue);
                    }
                }
        
    }
    
    public function updatePIListt($eId, $reqId, $pId, $req_data)
    {
        
        //print_r($req_data); exit;
            
        $this->db->where('purchase_indent_id', $pId);
        $this->db->update('tbl_purchase_indent', array('log'=>LOGTIME));
                
        foreach ($req_data as $key => $value) {
            if($value[6] == 2) {
                $pending_status = 3;
            } else {
                $pending_status = 0;
            }
            $updateValue['request_type'] = $value['3'];
            $updateValue['req_dt'] = date('d/m/Y h:i A',strtotime(LOGTIME));
            $updateValue['appr_status'] = $pending_status;
            $updateValue['log'] = LOGTIME;
            if($value[3] != '' && $value[13] == 'ADD') {
                $this->db->where('request_status_id', $value[0]);
                $this->db->update('tbl_request_status', $updateValue);
            }
                
        }
        
    }

    // public function getManagementPIListt()
    // {
    //     $sql = "SELECT a.*,h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, e.*, e.log as logs, f.*, g.vendorname FROM tbl_purchase_indent as a 
    //             inner join tbl_request as h on a.request_id=h.request_id
    //             inner join kn_order_enquiry as b on a.enquiry_id=b.id 
    //             inner join kn_master_brands as c on b.brandId=c.id
    //             inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
    //             inner join tbl_request_payment as f on a.purchase_indent_id=f.purchase_indent_id
    //             inner join kn_master_bom_vendor as g on f.vendor_id=g.id
    //             where h.type=3 and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and h.flag=1 GROUP BY a.purchase_indent_id ORDER BY e.log DESC";
    //     $data = $this->db->query($sql)->result_array();
    //     return $data;
    // }
    
    public function getManagementPIListt()
    {
        $sql = "SELECT a.*, a.flag as flags,h.type,h.cutoff_date, c.brandname, b.isriorcode, e.*, e.log as logs, f.proforma_value, f.currency as pay_currency, f.pay_by_date, f.request_payment_id, g.vendorname FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join kn_master_brands as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join tbl_request_payment as f on f.type_of_mode='M' and a.purchase_indent_id=f.purchase_indent_id
                left join kn_master_bom_vendor as g on a.vendor_id=g.id
                where  h.type =3 and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and a.bill_paid_status = 0 and e.type_of_mode='M'  and h.subscriberid = " . $this->db->escape($this->subscriberId)." ORDER BY e.log DESC";
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
    
    public function getMerchantPIListt()
    {
        $sql = "SELECT a.*, a.flag as flags,h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, e.*, a.log as logs, f.*, g.vendorname FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join kn_master_brands as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                inner join tbl_request_payment as f on a.purchase_indent_id=f.purchase_indent_id
                inner join kn_master_bom_vendor as g on f.vendor_id=g.id
                where h.type=3 and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and h.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.purchase_indent_id ORDER BY e.log DESC";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function getFinanceReqRecListt()
    {
        $sql = "SELECT a.*, h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, e.*, e.log as logs, f.proforma_value, f.currency as pay_currency, f.pay_by_date, f.request_payment_id, g.vendorname FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join kn_master_brands as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join tbl_request_payment as f on f.type_of_mode='M' and a.purchase_indent_id=f.purchase_indent_id
                left join kn_master_bom_vendor as g on a.vendor_id=g.id
                where h.type IN ('3','4') and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and e.type_of_mode='M'  and h.flag=1 and a.bill_paid_status = 0 and h.subscriberid = " . $this->db->escape($this->subscriberId)."ORDER BY e.log DESC";
        $result = $this->db->query($sql)->result_array();
        
        foreach ($result as $key => $res) {
         $request_for = []; $payment_requirement = []; $vendorname = []; $inv_status = []; $log = []; $pay_by_date = [];
         $payment_requirement[] = $res['payment_requirement'];
         $request_for[] = $res['request_for'];
         $vendorname[] = $res['vendorname'];
         if($res['pay_by_date'] == '') {
             $pay_by_date[] = '-';
         } else {
            $pay_by_date[] = date('d-m-Y h:i A',strtotime($res['pay_by_date']));    
         }
         
         $log[] = date('d-m-Y h:i A',strtotime($res['log']));
         if($res['payment_paid_status'] == 'PAID' || $res['payment_paid_status'] == 'PART PAID') {
             if($res['payment_paid_status'] == 'PAID') {
                $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>';    
             } else if($res['payment_paid_status'] == 'PART PAID') {
                 $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>';
             }
             
         } else {
             
            if($res['req_status'] == 0 || $res['req_status'] == '') {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
            } else if($res['req_status'] == 1) {
                $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>ACCEPTED</strong></span>';
            } else if($res['req_status'] == 2) {
                $inv_status[] = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
            } else if($res['req_status'] == 3) {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING-RR</strong></span>';
            } else {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
            }
         }
         
        //  if($res['payment_paid_status'] == 'PENDING' || $res['payment_paid_status'] == '') {
        //      $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
        //  } else if($res['payment_paid_status'] == 'PART PAID') {
        //      $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>';
        //  } else if($res['payment_paid_status'] == 'PAID') {
        //      $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>';
        //  } else  {
        //      $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING-RR</strong></span>';
        //  }
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
         $results[$key]['pay_by_date'] = $pay_by_date;
         $results[$key]['inv_status'] = $inv_status;
         $results[$key]['type_of_mode'] = $res['type_of_mode'];
         $results[$key]['logs'] = $log;
         $results[$key]['flag'] = $res['flag'];
         
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
            
            if($value['payment_paid_status'] == 'PENDING' || $value['payment_paid_status'] == '') {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>');
            } else if($value['payment_paid_status'] == 'PART PAID') {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>');
            } else if($value['payment_paid_status'] == 'PAID') {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>');
            } else if($value['payment_paid_status'] == 'BILL CLOSED') {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>BILL CLOSED</strong></span>');
            } else {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>');
            }
            //echo $value['request_for'];
            if($value['payment_requirement'] == 'OTH. PAYMENT') {
                @$pay_date = $this->db->where('purchase_indent_id',$value['purchase_indent_id'])->where('row_id',$value['row_id'])->get('tbl_request_payment_others')->row()->pay_by_date;
            } else if($value['payment_requirement'] == 'ADV. PAYMENT') {
                @$pay_date = $this->db->where('purchase_indent_id',$value['purchase_indent_id'])->where('row_id',$value['row_id'])->get('tbl_request_payment')->row()->pay_by_date;
            } else if($value['payment_requirement'] == 'BILL PAYMENT' ) {
                @$pay_date = $this->db->where('purchase_indent_id',$value['purchase_indent_id'])->where('row_id',$value['row_id'])->get('tbl_payment_invoice')->row()->pay_by_date;
            } else {
                $pay_date = '';
            }
            //echo $this->db->last_query(); exit;
            $logg = date('d-m-Y h:i A',strtotime($value['log']));
            array_push($request_for, $value['request_for']);
            array_push($payment_requirement, $value['payment_requirement']);
            array_push($vendorname, $vendor_name);
            array_push($pay_by_date, date('d-m-Y h:i A',strtotime($pay_date)));
            array_push($log, $logg);
         
            $results[$key]['request_for'] = implode(' <br /> ', $request_for);
            $results[$key]['payment_requirement'] = implode(' <br /> ', $payment_requirement);
            $results[$key]['vendorname'] = implode(' <br /> ', $vendorname);
            $results[$key]['pay_by_date'] = implode(' <br /> ', @$pay_by_date);
            $results[$key]['inv_status'] = implode(' <br /> ', $inv_status);
            $results[$key]['logs'] = implode(' <br /> ', $log);
        } 
        }
        }
        return $results;
    }
      public function getFinanceReqRecListt_bom1()
    {
        $sql = "SELECT a.*, h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, e.*, e.log as logs, f.proforma_value, f.currency as pay_currency, f.pay_by_date, f.request_payment_id, g.vendorname FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join kn_master_brands as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join tbl_request_payment as f on f.type_of_mode='M' and a.purchase_indent_id=f.purchase_indent_id
                left join kn_master_bom_vendor as g on a.vendor_id=g.id
                where h.type IN ('3') and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and e.type_of_mode='M'  and h.flag=1 and a.bill_paid_status = 0 and h.subscriberid = " . $this->db->escape($this->subscriberId)."ORDER BY e.log DESC";
        $result = $this->db->query($sql)->result_array();
        
        foreach ($result as $key => $res) {
         $request_for = []; $payment_requirement = []; $vendorname = []; $inv_status = []; $log = []; $pay_by_date = [];
         $payment_requirement[] = $res['payment_requirement'];
         $request_for[] = $res['request_for'];
         $vendorname[] = $res['vendorname'];
         if($res['pay_by_date'] == '') {
             $pay_by_date[] = '-';
         } else {
            $pay_by_date[] = date('d-m-Y h:i A',strtotime($res['pay_by_date']));    
         }
         
         $log[] = date('d-m-Y h:i A',strtotime($res['log']));
         if($res['payment_paid_status'] == 'PAID' || $res['payment_paid_status'] == 'PART PAID') {
             if($res['payment_paid_status'] == 'PAID') {
                $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>';    
             } else if($res['payment_paid_status'] == 'PART PAID') {
                 $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>';
             }
             
         } else {
             
            if($res['req_status'] == 0 || $res['req_status'] == '') {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
            } else if($res['req_status'] == 1) {
                $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>ACCEPTED</strong></span>';
            } else if($res['req_status'] == 2) {
                $inv_status[] = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
            } else if($res['req_status'] == 3) {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING-RR</strong></span>';
            } else {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
            }
         }
         
        //  if($res['payment_paid_status'] == 'PENDING' || $res['payment_paid_status'] == '') {
        //      $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
        //  } else if($res['payment_paid_status'] == 'PART PAID') {
        //      $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>';
        //  } else if($res['payment_paid_status'] == 'PAID') {
        //      $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>';
        //  } else  {
        //      $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING-RR</strong></span>';
        //  }
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
         $results[$key]['pay_by_date'] = $pay_by_date;
         $results[$key]['inv_status'] = $inv_status;
         $results[$key]['type_of_mode'] = $res['type_of_mode'];
         $results[$key]['logs'] = $log;
         $results[$key]['flag'] = $res['flag'];
         
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
            
            if($value['payment_paid_status'] == 'PENDING' || $value['payment_paid_status'] == '') {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>');
            } else if($value['payment_paid_status'] == 'PART PAID') {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>');
            } else if($value['payment_paid_status'] == 'PAID') {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>');
            } else if($value['payment_paid_status'] == 'BILL CLOSED') {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>BILL CLOSED</strong></span>');
            } else {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>');
            }
            //echo $value['request_for'];
            if($value['payment_requirement'] == 'OTH. PAYMENT') {
                @$pay_date = $this->db->where('purchase_indent_id',$value['purchase_indent_id'])->where('row_id',$value['row_id'])->get('tbl_request_payment_others')->row()->pay_by_date;
            } else if($value['payment_requirement'] == 'ADV. PAYMENT') {
                @$pay_date = $this->db->where('purchase_indent_id',$value['purchase_indent_id'])->where('row_id',$value['row_id'])->get('tbl_request_payment')->row()->pay_by_date;
            } else if($value['payment_requirement'] == 'BILL PAYMENT' ) {
                @$pay_date = $this->db->where('purchase_indent_id',$value['purchase_indent_id'])->where('row_id',$value['row_id'])->get('tbl_payment_invoice')->row()->pay_by_date;
            } else {
                $pay_date = '';
            }
            //echo $this->db->last_query(); exit;
            $logg = date('d-m-Y h:i A',strtotime($value['log']));
            array_push($request_for, $value['request_for']);
            array_push($payment_requirement, $value['payment_requirement']);
            array_push($vendorname, $vendor_name);
            array_push($pay_by_date, date('d-m-Y h:i A',strtotime($pay_date)));
            array_push($log, $logg);
         
            $results[$key]['request_for'] = implode(' <br /> ', $request_for);
            $results[$key]['payment_requirement'] = implode(' <br /> ', $payment_requirement);
            $results[$key]['vendorname'] = implode(' <br /> ', $vendorname);
            $results[$key]['pay_by_date'] = implode(' <br /> ', @$pay_by_date);
            $results[$key]['inv_status'] = implode(' <br /> ', $inv_status);
            $results[$key]['logs'] = implode(' <br /> ', $log);
        } 
        }
        }
        return $results;
    }

      public function getFinanceReqRecListt_bom2()
    {
        $sql = "SELECT a.*, h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, e.*, e.log as logs, f.proforma_value, f.currency as pay_currency, f.pay_by_date, f.request_payment_id, g.vendorname FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join kn_master_brands as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join tbl_request_payment as f on f.type_of_mode='M' and a.purchase_indent_id=f.purchase_indent_id
                left join kn_master_bom_vendor as g on a.vendor_id=g.id
                where h.type IN ('4') and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and e.type_of_mode='M'  and h.flag=1 and a.bill_paid_status = 0 and h.subscriberid = " . $this->db->escape($this->subscriberId)."ORDER BY e.log DESC";
        $result = $this->db->query($sql)->result_array();
        
        foreach ($result as $key => $res) {
         $request_for = []; $payment_requirement = []; $vendorname = []; $inv_status = []; $log = []; $pay_by_date = [];
         $payment_requirement[] = $res['payment_requirement'];
         $request_for[] = $res['request_for'];
         $vendorname[] = $res['vendorname'];
         if($res['pay_by_date'] == '') {
             $pay_by_date[] = '-';
         } else {
            $pay_by_date[] = date('d-m-Y h:i A',strtotime($res['pay_by_date']));    
         }
         
         $log[] = date('d-m-Y h:i A',strtotime($res['log']));
         if($res['payment_paid_status'] == 'PAID' || $res['payment_paid_status'] == 'PART PAID') {
             if($res['payment_paid_status'] == 'PAID') {
                $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>';    
             } else if($res['payment_paid_status'] == 'PART PAID') {
                 $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>';
             }
             
         } else {
             
            if($res['req_status'] == 0 || $res['req_status'] == '') {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
            } else if($res['req_status'] == 1) {
                $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>ACCEPTED</strong></span>';
            } else if($res['req_status'] == 2) {
                $inv_status[] = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
            } else if($res['req_status'] == 3) {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING-RR</strong></span>';
            } else {
                $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
            }
         }
         
        //  if($res['payment_paid_status'] == 'PENDING' || $res['payment_paid_status'] == '') {
        //      $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
        //  } else if($res['payment_paid_status'] == 'PART PAID') {
        //      $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>';
        //  } else if($res['payment_paid_status'] == 'PAID') {
        //      $inv_status[] = '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>';
        //  } else  {
        //      $inv_status[] = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING-RR</strong></span>';
        //  }
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
         $results[$key]['pay_by_date'] = $pay_by_date;
         $results[$key]['inv_status'] = $inv_status;
         $results[$key]['type_of_mode'] = $res['type_of_mode'];
         $results[$key]['logs'] = $log;
         $results[$key]['flag'] = $res['flag'];
         
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
            
            if($value['payment_paid_status'] == 'PENDING' || $value['payment_paid_status'] == '') {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>');
            } else if($value['payment_paid_status'] == 'PART PAID') {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>PART PAID</strong></span>');
            } else if($value['payment_paid_status'] == 'PAID') {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>PAID</strong></span>');
            } else if($value['payment_paid_status'] == 'BILL CLOSED') {
                array_push($inv_status, '<span class="text-light knGreenColor bg-dark"><strong>BILL CLOSED</strong></span>');
            } else {
                array_push($inv_status, '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>');
            }
            //echo $value['request_for'];
            if($value['payment_requirement'] == 'OTH. PAYMENT') {
                @$pay_date = $this->db->where('purchase_indent_id',$value['purchase_indent_id'])->where('row_id',$value['row_id'])->get('tbl_request_payment_others')->row()->pay_by_date;
            } else if($value['payment_requirement'] == 'ADV. PAYMENT') {
                @$pay_date = $this->db->where('purchase_indent_id',$value['purchase_indent_id'])->where('row_id',$value['row_id'])->get('tbl_request_payment')->row()->pay_by_date;
            } else if($value['payment_requirement'] == 'BILL PAYMENT' ) {
                @$pay_date = $this->db->where('purchase_indent_id',$value['purchase_indent_id'])->where('row_id',$value['row_id'])->get('tbl_payment_invoice')->row()->pay_by_date;
            } else {
                $pay_date = '';
            }
            //echo $this->db->last_query(); exit;
            $logg = date('d-m-Y h:i A',strtotime($value['log']));
            array_push($request_for, $value['request_for']);
            array_push($payment_requirement, $value['payment_requirement']);
            array_push($vendorname, $vendor_name);
            array_push($pay_by_date, date('d-m-Y h:i A',strtotime($pay_date)));
            array_push($log, $logg);
         
            $results[$key]['request_for'] = implode(' <br /> ', $request_for);
            $results[$key]['payment_requirement'] = implode(' <br /> ', $payment_requirement);
            $results[$key]['vendorname'] = implode(' <br /> ', $vendorname);
            $results[$key]['pay_by_date'] = implode(' <br /> ', @$pay_by_date);
            $results[$key]['inv_status'] = implode(' <br /> ', $inv_status);
            $results[$key]['logs'] = implode(' <br /> ', $log);
        } 
        }
        }
        return $results;
    }

    public function getMgmtBillPaidListt()
    {
        $sql = "SELECT a.*, a.flag as flags, h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, e.*, e.log as logs, a.log as clog, f.invoice_no, f.invoice_date, f.invoice_value, f.curency as pay_currency, f.pay_by_date, f.vendor_name FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join kn_master_brands as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                inner join tbl_payment_invoice as f on a.purchase_indent_id=f.purchase_indent_id
                where type IN ('3','4') and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and a.bill_paid_status=1 and e.type_of_mode='M' and h.flag=1 and h.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.purchase_indent_id ORDER BY e.log DESC";
        $result = $this->db->query($sql)->result_array();

       
        
        
        foreach ($result as $key => $res) {
        //  $request_for = []; $payment_requirement = []; $vendorname = []; $inv_status = []; $log = [];
         $inv_no = []; $inv_date = []; $inv_value = []; $inv_status = []; $currency = []; $mop = [];
         $payment_requirement[] = $res['payment_requirement'];
         
         $log = date('d-m-Y h:i A',strtotime($res['clog']));
         
         
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
         $results[$key]['flag'] = $res['flags'];
         
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

     public function getMgmtBillPaidListtBOM1()
    {
        $sql = "SELECT a.*, a.flag as flags, h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, e.*, e.log as logs, a.log as clog, f.invoice_no, f.invoice_date, f.invoice_value, f.curency as pay_currency, f.pay_by_date, f.vendor_name FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join kn_master_brands as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                inner join tbl_payment_invoice as f on a.purchase_indent_id=f.purchase_indent_id
                where type IN ('3') and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and a.bill_paid_status=1 and e.type_of_mode='M' and h.flag=1 and h.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.purchase_indent_id ORDER BY e.log DESC";
        $result = $this->db->query($sql)->result_array();

       
        
        
        foreach ($result as $key => $res) {
        //  $request_for = []; $payment_requirement = []; $vendorname = []; $inv_status = []; $log = [];
         $inv_no = []; $inv_date = []; $inv_value = []; $inv_status = []; $currency = []; $mop = [];
         $payment_requirement[] = $res['payment_requirement'];
         
         $log = date('d-m-Y h:i A',strtotime($res['clog']));
         
         
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
         $results[$key]['flag'] = $res['flags'];
         
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
     public function getMgmtBillPaidListtBOM2()
    {
        $sql = "SELECT a.*, a.flag as flags, h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, e.*, e.log as logs, a.log as clog, f.invoice_no, f.invoice_date, f.invoice_value, f.curency as pay_currency, f.pay_by_date, f.vendor_name FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join kn_master_brands as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                inner join tbl_payment_invoice as f on a.purchase_indent_id=f.purchase_indent_id
                where type IN ('4') and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and a.bill_paid_status=1 and e.type_of_mode='M' and h.flag=1 and h.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.purchase_indent_id ORDER BY e.log DESC";
        $result = $this->db->query($sql)->result_array();

       
        
        
        foreach ($result as $key => $res) {
        //  $request_for = []; $payment_requirement = []; $vendorname = []; $inv_status = []; $log = [];
         $inv_no = []; $inv_date = []; $inv_value = []; $inv_status = []; $currency = []; $mop = [];
         $payment_requirement[] = $res['payment_requirement'];
         
         $log = date('d-m-Y h:i A',strtotime($res['clog']));
         
         
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
         $results[$key]['flag'] = $res['flags'];
         
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

    
    // *********************************************************************************************************** 
    // BOM FINANCE DEPARTMENT STARTS HERE 
    // **********************************************************************************************************//
    
    public function getPaymentReceivedListt() {
        // $sql = "SELECT a.*, c.brandname, b.orderenqrefno FROM tbl_request as a 
        //         inner join kn_order_enquiry as b on a.enquiry_id=b.id 
        //         inner join kn_master_brands as c on b.brandId=c.id 
        //         where a.type=3 and a.mgmt_approval=1 and a.deprt_approval=1 and a.flag=1";
        $sql = "SELECT a.*, d.*, c.brandname, b.orderenqrefno, e.vendorname FROM tbl_request_purchase_indent as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join kn_master_brands as c on b.brandId=c.id 
                inner join tbl_request as d on a.request_id=d.request_id 
                left join kn_master_bom_vendor as e on a.vendor_id=e.id 
                where d.type=3 and d.mgmt_approval=1 and d.deprt_approval=1 and a.flag=1";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function getPaymentRequestReceiveDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                INNER JOIN tbl_request_payment d on b.request_id=d.request_id
                INNER JOIN tbl_request_purchase_indent e on b.request_id=e.request_id
                INNER JOIN kn_master_bom_vendor f on d.vendor_id=f.id
                WHERE b.request_id = " . $reqId . " AND b.flag=1 AND b.qa_req_status = 1";
        $data = $this->db->query($sql)->result_array();

        $vendor_sql = "SELECT *, vendorname as name FROM kn_master_bom_vendor";
        $vendor_data = $this->db->query($vendor_sql)->result_array();

        $result = $paymentRequst = [];

        foreach ($data as $key => $value)
        {

            $bcm = $value['blend'].' / '.$value['content'].' / '.$value['material'];

            $result[$key] = [ $value['request_id'], $value['item_desc'], $bcm,
                            $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                            $value['size_dim'], $value['uom']
                        ];

            $paymentRequst[$key] = [ $value['request_id'], $value['vendor_id'], $value['proforma_no'], $value['proforma_date'],
                            $value['proforma_value'], $value['quoted_currency'], $value['mode_of_payment'],
                            $value['pay_by_date'], $value['amount_payable'], $value['currency'], $value['vendor_bank_name'],
                            $value['acc_no'], $value['ifsc'], $value['shift_code']
                        ];
            
            $advancepaiddetails[$key] = [ 
                            $value['request_id'], $value["request_payment_id"], $value['vendorname'], $value['vendor_bank_name'], $value['acc_no'],
                            $value['mode_payment'], $value['trans_id'], $value['trans_date'],
                            $value['cheque_no'], $value['cheque_date'], $value['amount_paid'], $value['trans_currency'],
                            $value['adv_paid']
                        ];

        }

        $output['stateDetails'] = $result;
        $output['paymentRequst'] = $paymentRequst;
        $output['advancepaiddetails'] = $advancepaiddetails;
        $output['vendor_data'] = $vendor_data;
        $output['fullData'] = $data;
        return $output;
    }
   
    public function updatePaymentAdvanceDetailss($data, $enqId, $reqId) {

        foreach($data as $key => $value) 
        {
            $requestValue['mode_payment'] = $value[5];
            $requestValue['trans_id'] = $value[6];
            $requestValue['trans_date'] = $value[7];
            $requestValue['cheque_no'] = $value[8];
            $requestValue['cheque_date'] = $value[9];
            $requestValue['amount_paid'] = $value[10];
            $requestValue['trans_currency'] = $value[11];
            $requestValue['adv_paid'] = $value[12];
            $requestValue['log'] = LOGTIME;

            $this->db->where('request_payment_id', $value[1]);
            $this->db->update('tbl_request_payment', $requestValue);
        }
    }

    public function getAdvancePadiListt() {
        $sql = "SELECT a.*, d.*, c.brandname, b.orderenqrefno, e.vendorname, f.pi_date, f.pi_ref_no, f.amount
                FROM tbl_request_payment as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join kn_master_brands as c on b.brandId=c.id 
                inner join tbl_request as d on a.request_id=d.request_id 
                inner join tbl_request_purchase_indent as f on a.request_id=f.request_id
                left join kn_master_bom_vendor as e on a.vendor_id=e.id 
                where d.type=3 and d.mgmt_approval=1 and d.deprt_approval=1 and a.flag=1";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    public function getAdvancePaidDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                INNER JOIN tbl_request_payment d on b.request_id=d.request_id
                INNER JOIN tbl_request_purchase_indent e on b.request_id=e.request_id
                INNER JOIN kn_master_bom_vendor f on d.vendor_id=f.id
                WHERE b.request_id = " . $reqId . " AND b.flag=1 AND b.qa_req_status = 1";
        $data = $this->db->query($sql)->result_array();

        $vendor_sql = "SELECT *, vendorname as name FROM kn_master_bom_vendor";
        $vendor_data = $this->db->query($vendor_sql)->result_array();

        $result = $paymentRequst = [];

        foreach ($data as $key => $value)
        {

            $bcm = $value['blend'].' / '.$value['content'].' / '.$value['material'];

            $result[$key] = [ $value['request_id'], $value['item_desc'], $bcm,
                            $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                            $value['size_dim'], $value['uom']
                        ];

            $paymentRequst[$key] = [ $value['request_id'], $value['vendor_id'], $value['proforma_no'], $value['proforma_date'],
                            $value['proforma_value'], $value['quoted_currency'], $value['mode_of_payment'],
                            $value['pay_by_date'], $value['amount_payable'], $value['currency'], $value['vendor_bank_name'],
                            $value['acc_no'], $value['ifsc'], $value['shift_code']
                        ];
            
            $advancepaiddetails[$key] = [ 
                            $value['request_id'], $value["request_payment_id"], $value['vendorname'], $value['vendor_bank_name'], $value['acc_no'],
                            $value['mode_payment'], $value['trans_id'], $value['trans_date'],
                            $value['cheque_no'], $value['cheque_date'], $value['amount_paid'], $value['trans_currency'],
                            $value['adv_paid']
                        ];

        }

        $output['stateDetails'] = $result;
        $output['paymentRequst'] = $paymentRequst;
        $output['advancepaiddetails'] = $advancepaiddetails;
        $output['vendor_data'] = $vendor_data;
        $output['fullData'] = $data;
        return $output;
    }
    // *********************************************************************************************************** 
    // BOM FINANCE DEPARTMENT ENDS HERE 
    // **********************************************************************************************************//

    // *********************************************************************************************************** 
    // STORE DEPARTMENT STARTS HERE 
    // **********************************************************************************************************//

    public function getStorepiListt() {
        $sql = "SELECT a.*, c.brandname, b.orderenqrefno, d.*, e.*, g.vendorname FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join kn_master_brands as c on b.brandId=c.id 
                inner join tbl_request_purchase_indent as d on a.request_id=d.request_id
                inner join tbl_request_bom as e on a.request_id=e.request_id
                inner join tbl_request_payment as f on a.request_id=f.request_id
                inner join kn_master_bom_vendor as g on f.vendor_id=g.id
                where a.type=3 and a.mgmt_approval=1 and a.deprt_approval=1 and a.flag=1 and e.req_status=1";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function updateStorePiDetailss($data, $data2, $data3, $enqId, $reqId, $pId)
    {
        foreach($data as $key => $value) {
            $inhouseData['request_id'] = $reqId;
            $inhouseData['purchase_indent_id'] = $pId;
            $inhouseData['request_bom_id'] = $value[25];
            $inhouseData['checkStatus'] = $value[1];
            $inhouseData['item_desc'] = $value[2];
            $inhouseData['garment_size'] = $value[3];
            $inhouseData['appr_item_code'] = $value[4];
            $inhouseData['appr_item_color_code'] = $value[5];
            $inhouseData['size_dim'] = $value[6];
            $inhouseData['uom'] = $value[7];
            $inhouseData['dc_no'] = $value[8];
            $inhouseData['dc_date'] = $value[9];
            $inhouseData['batch_ref_no'] = $value[10];
            $inhouseData['dc_qty'] = $value[11];
            $inhouseData['invoice_no'] = $value[12];
            $inhouseData['invoice_date'] = $value[13];
            $inhouseData['invoice_qty'] = $value[14];
            $inhouseData['invoice_rate'] = $value[15];
            $inhouseData['currency'] = $value[16];
            $inhouseData['foreign_exch_rate'] = $value[17];
            $inhouseData['invoice_value'] = $value[18];
            $inhouseData['gst'] = $value[19];
            $inhouseData['total_invoice_value'] = $value[20];
            $inhouseData['received_qty'] = $value[21];
            $inhouseData['received_uom'] = $value[22];
            $inhouseData['received_date'] = $value[23];
            $inhouseData['storage_bin'] = $value[24];
            //print_r($inhouseData); exit;
            if($value[0] != '')
            {
                $this->db->where('bom_in_house_id', $value[0]);
                $this->db->update('tbl_bom_in_house', $inhouseData);
                $this->db->where('request_id',$reqId)->update('tbl_request',array('log'=>LOGTIME));
            }
            else {
               
                    $this->db->insert('tbl_bom_in_house', $inhouseData);    
                    $this->db->where('request_id',$reqId)->update('tbl_request',array('log'=>LOGTIME));
                    
                
                // $this->db->where('request_bom_id', $value[25]);
                //  $query = $this->db->get('tbl_bom_in_house');  
                //  if ($query->num_rows() == 0) {
                //     $this->db->insert('tbl_bom_in_house', $inhouseData);    
                //     $this->db->where('request_id',$reqId)->update('tbl_request',array('log'=>LOGTIME));
                //       }  else{
                //         return false;
                //       }             

            }
        }

        foreach ($data2 as $key2 => $value2) {
            if($value2[11] != 0) {
                $itemAcceptData['qa_status'] = $value2[11];
                $itemAcceptData['qa_status_upt_dt'] = $this->mysqldatetime;
                $this->db->where('bom_in_house_id', $value2[0]);
                $this->db->update('tbl_bom_in_house', $itemAcceptData);
            }

            if($value2[13] != 0) {
                $itemAcceptDatas['mgmt_ovrd_status'] = $value2[13];
                $itemAcceptDatas['mgmt_status_upd_dt'] = $this->mysqldatetime;
                $this->db->where('bom_in_house_id', $value2[0]);
                $this->db->update('tbl_bom_in_house', $itemAcceptDatas);
            }
        }

        foreach ($data3 as $key3 => $value3) {
            $consolidatedQty['supply_closure_status'] = $value3[11];
            $this->db->where('bom_in_house_id', $value3[0]);
            $this->db->update('tbl_bom_in_house', $consolidatedQty);
        }
    }

    public function moveToOrderStockListt($data)
    {
        foreach ($data as $key => $value) {
            if($value[1] == "true") {
                $itemAcceptData['order_stock_status'] = 1;
                $itemAcceptData['log'] = LOGTIME;
                $this->db->where('bom_in_house_id', $value[0]);
                $this->db->update('tbl_bom_in_house', $itemAcceptData);
            }
        }
    }

    public function getNewItemDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND b.qa_req_status = 1";
        $data = $this->db->query($sql)->result_array();
        
        $inhousestatusdetails = $itemDetails = [];

        // return $data;

        foreach ($data as $key => $value)
        {
            $bcm = $value['blend'].' / '.$value['content'].' / '.$value['material'];

            $inhousestatusdetails[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], '', '', '', '', ''
                        ];

        }
        
        $sql1 = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND b.qa_req_status = 1";
        $data1 = $this->db->query($sql1)->result_array();
        
        foreach ($data1 as $key1 => $value)
        {
            $bcm = $value['blend'].' / '.$value['content'].' / '.$value['material'];

            $inhousestatusdetails[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], '', '', '', '', ''
                        ];

        }
        
        $itemDetails = [ '', '', '', '', '', '', '', '', '' ];

        $UOM = unserialize(ARRUNITOFMEASURE);

        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }
        
        $output['itemDetails'] = $itemDetails;
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['uomData'] = $UOMDetails;
        return $output;
    }

    public function getSurplusStockDetailss($enqId, $reqId, $pId, $itemCode) {
        $itemCode = "'".$itemCode."'";
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND b.qa_req_status = 1";
        $data = $this->db->query($sql)->result_array();

        $sql1 = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_bom_in_house c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 ";
        $data1 = $this->db->query($sql1)->result_array();

        // $sql2 = "SELECT * FROM tbl_request_bom b
        //         LEFT JOIN tbl_bom_in_house c on c.request_id=b.request_id
        //         WHERE c.request_id = " . $reqId . " AND c.flag=1 AND c.order_stock_status=0 ";
        // $data2 = $this->db->query($sql2)->result_array();
        
        
        $itemSql = "SELECT a.*,b.*,c.*,e.brandname FROM tbl_bom_in_house a 
        INNER JOIN tbl_purchase_indent b ON a.purchase_indent_id = b.purchase_indent_id
        INNER JOIN tbl_request c ON b.request_id = c.request_id 
        inner join kn_order_enquiry as d on c.enquiry_id=d.id 
        inner join kn_master_brands as e on d.brandId=e.id
        WHERE a.purchase_indent_id = " . $pId . " AND a.appr_item_code = " . $itemCode . " GROUP BY a.appr_item_code ";
        $itemData = $this->db->query($itemSql)->result_array();
        
        
        $inhousestatusdetails = $itemacceptstatus = $inhouseconsolidatedqtydetails = $itemDetails = $purchaseIndent = $piRefNo = $lot_no = $rateList = $issued_details = [];
        
        $insql = "SELECT * FROM tbl_surplus_stock_details a
                INNER JOIN tbl_request b on a.req_id=b.request_id
                INNER JOIN kn_order_enquiry as c on b.enquiry_id=c.id
                WHERE  a.item_code = " .$itemCode. " AND a.flag=1 ";
        $indata = $this->db->query($insql)->result_array();
            
        
        foreach ($indata as $key => $value)
        {
            //$lot_no[] = $value['lot_no'];
            $inv_no[$key] = [ 'id'=> $value['inv_no'], 'name'=> $value['inv_no'] ];
            $inv_date[$key] = [ 'id'=> $value['inv_date'], 'item_id'=> $value['inv_no'], 'name'=> $value['inv_date'] ];
            $lot_no[$key] = [ 'id'=> $value['lot_no'], 'name'=> $value['lot_no'] ];
            $rateList[$key] = [ 'id'=> $value['rate'], 'item_id'=> $value['lot_no'], 'name'=> $value['rate'] ];
            $gstList[$key] = [ 'id'=> $value['gst'], 'item_id'=> $value['lot_no'], 'name'=> $value['gst'] ];
            $uomList[$key] = [ 'id'=> $value['uom'], 'item_id'=> $value['lot_no'], 'name'=> $value['uom'] ];
            $inhousestatusdetails[$key] = [ $value['surplus_id'], '', $value['isriorcode'], $value['pi_ref_no'], $value['inv_no'],
                            $value['inv_date'], $value['lot_no'], $value['surplus_qty'], $value['uom'], $value['rate'], '', $value['gst'], '', $value['order_stock_date'], $value['bin']
                        ];

        }

        foreach ($data1 as $key => $value)
        {

            $itemacceptstatus[$key] = [ $value['bom_in_house_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'], $value['dc_no'], $value['dc_date'], $value['dc_qty'], $value['received_uom'], $value['merchant_item_status'], $value['merchant_appl_date_time'], $value['qa_status'], $value['qa_status_upt_dt'], $value['mgmt_ovrd_status'], $value['mgmt_status_upd_dt'] ];

        }
        
        $insql = "SELECT a.*,SUM(d.issued_qty) as issued_qtys,d.uom as uoms FROM tbl_surplus_stock_details a
                INNER JOIN tbl_request b on a.req_id=b.request_id
                INNER JOIN kn_order_enquiry as c on b.enquiry_id=c.id
                INNER JOIN tbl_surplus_issued_details as d on a.item_code = d.item_code AND a.lot_no = d.lot_no
                WHERE a.item_code = " .$itemCode. " AND a.enq_id=".$enqId." AND a.flag=1  GROUP BY a.item_code,a.lot_no ";
        $data2 = $this->db->query($insql)->result_array();
       // print_r($insql); exit;

        foreach ($data2 as $key => $value)
        {
            $qty = $value['invoice_qty'];
            $qty1 = $value['received_qty'];
            $diff = floatval($qty)-floatval($qty1);

            $inhouseconsolidatedqtydetails[$key] = [ $value['surplus_id'], '', '', $value['lot_no'], $value['rate'], $value['surplus_qty'], $value['issued_qtys'], '', $value['uom'], '', $value['uoms'], '', '','', '', '', ];

        }
        
        if($itemData) {
        foreach ($itemData as $key1 => $value1)
        {
            $bcm = $this->db->where('appr_item_code',$value1['appr_item_code'])->get('tbl_request_bom')->row()->bcm;

            $itemDetails[$key1] = [  $value1['request_bom_id'], '', $value1['brandname'], $value1['item_desc'], $bcm, $value1['garment_size'], $value1['appr_item_code'],
                            $value1['appr_item_color_code'], $value1['size_dim'], $value1['uom']
                        ];

        }
        } else {
            $itemDetails[0] = [ '', '', '', '', '', '', '', '', '', '' ];
        }
        
        // INNER JOIN tbl_bom_in_house f on e.request_bom_id = f.request_bom_id
        // $pursql = "SELECT a.surplus_id,a.pi_ref_no,c.ref_queue_no,c.cutoff_date,c.purchase_req_type,b.pi_dt,e.plan_bom_qty,e.uom,d.isriorcode, SUM(g.issued_qty) as issued_qtys FROM tbl_surplus_stock_details a
        //         INNER JOIN tbl_purchase_indent b on a.pId = b.purchase_indent_id
        //         INNER JOIN tbl_request c on b.request_id=c.request_id
        //         INNER JOIN kn_order_enquiry as d on c.enquiry_id=d.id
        //         INNER JOIN tbl_request_bom e on a.pId = e.purchase_indent_id
        //         INNER JOIN tbl_bom_in_house f on e.request_bom_id = f.request_bom_id AND a.lot_no = f.batch_ref_no
        //         INNER JOIN tbl_mi_issued_details g on f.batch_ref_no = g.lot_no
        //         INNER JOIN tbl_mi_bom_details h ON g.mi_bom_details_id = h.mi_bom_details_id
        //         INNER JOIN tbl_mi_bom i ON h.mi_bom_id = i.mi_bom_id
        //         WHERE  a.item_code = " .$itemCode. " AND b.supply_closed_status = 0 AND a.flag=1 GROUP BY a.pId ";
        
        $pursql = "SELECT d.request_bom_id,c.pi_ref_queue_no,b.ref_queue_no,b.cutoff_date,b.purchase_req_type,c.pi_dt,d.plan_bom_qty,d.uom,a.isriorcode  FROM kn_order_enquiry a
                   INNER JOIN tbl_request b on a.id=b.enquiry_id
                   INNER JOIN tbl_purchase_indent c on b.request_id = c.request_id
                   INNER JOIN tbl_request_bom d ON b.request_id = d.request_id 
                  WHERE  d.appr_item_code = " .$itemCode. " AND c.supply_closed_status = 0 AND c.pi_list_status = 1 AND d.flag=1 ";
        $purdata = $this->db->query($pursql)->result_array();
        //print_r($pursql); exit;
        $k=0;
        foreach ($purdata as $key1 => $value1)
        {
            // $diff_qty = $value1['plan_bom_qty'] - $value1['issued_qtys'];
            // if($diff_qty > 0) {
            //     $piRefNo[$k] = $value1['pi_ref_queue_no'];
            //     $k++;
            // }
            $piRefNo[$key1] = $value1['pi_ref_queue_no'];
            $purchaseIndent[$key1] = [ $value1['surplus_id'], '', $value1['isriorcode'], $value1['ref_queue_no'], $value1['pi_ref_queue_no'],
                            $value1['pi_dt'], $value1['cutoff_date'], 'BOM - Order Stock List', $value1['purchase_req_type'], $value1['plan_bom_qty'], '' , '', $value1['uom']
                        ];

        }

        $UOM = unserialize(ARRUNITOFMEASURE);

        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }
        
        // foreach($pidata as $key => $value) {
        //     $piRefNo[$key] = $value['pi_ref_queue_no'];
        // }
        
        $issql = "SELECT * FROM tbl_surplus_issued_details a
                WHERE  a.item_code = " .$itemCode. " AND a.flag=1 ";
        $isdata = $this->db->query($issql)->result_array();
        
        foreach($isdata as $key => $value) {
            $issued_details[$key] = [ $value['issued_id'], '', '', $value['pi_ref_no'], $value['transfer_category'],
                            $value['stm_ref_no'], $value['stm_date_time'], $value['inv_no'], $value['inv_date'], $value['lot_no'], $value['rate'] , $value['gst'], $value['issued_qty'], $value['uom']
                        ];
        }
        
        
        $output['itemdetails'] = $itemDetails;
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['purchaseIndent'] = $purchaseIndent;
        $output['uomData'] = $UOMDetails;
        $output['piRefNo'] = $piRefNo;
        $output['inv_no'] = $inv_no;
        $output['inv_date'] = $inv_date;
        $output['lot_no'] = $lot_no;
        $output['rateList'] = $rateList;
        $output['gstList'] = $gstList;
        $output['uomList'] = $uomList;
        $output['issued_details'] = $issued_details;
        $output['itemacceptstatus'] = $itemacceptstatus;
        $output['inhouseconsolidatedqtydetails'] = $inhouseconsolidatedqtydetails;
        

        return $output;
    }

    // *********************************************************************************************************** 
    // STORE DEPARTMENT STARTS HERE 
    // **********************************************************************************************************//

    // public function getPurchaseRequestSentDetailss($id, $reqId) {
    //     $sql = "SELECT *, SUM(`req_bom_qty`) as calc_bom_qty 
    //             FROM tbl_bom_article_1_requirement as a 
    //             where a.enquiry_id='$id' AND a.flag=1
    //             GROUP BY a.item_desc, a.blend, a.content, a.material, a.garment_size, a.appr_item_code, 
    //                     a.appr_item_col_code, a.size_dim, a.uom";
    //     $data = $this->db->query($sql)->result_array();
        
    //     $sql1 = "SELECT * FROM tbl_bom_article_1_req_consld as a 
    //             LEFT JOIN tbl_request as b ON a.request_id=b.request_id
    //             WHERE a.enquiry_id='$id' AND a.flag=1";
    //     $data1 = $this->db->query($sql1)->result_array();

    //     $sql2 = "SELECT * FROM tbl_bom_1_sourcing_details as a WHERE a.enquiry_id='$id' AND a.flag=1";
    //     $data2 = $this->db->query($sql2)->result_array();

    //     $sql3 = "SELECT * FROM tbl_bom_article_1_requirement as a 
    //             where a.enquiry_id='$id' AND a.flag=1
    //             GROUP BY a.item_desc";

    //     $data3 = $this->db->query($sql3)->result_array();

    //     $result = [];
    //     $sourcing_result = [];

    //     for($i=0; $i < sizeof($data); $i++)
    //     {
    //         if( isset($data1[$i]['excess_qty'])) {
    //             $excess_qty = $data1[$i]['excess_qty'];
    //             $data[$i]['excess_qty'] = $data1[$i]['excess_qty'];
    //         }
    //         else {
    //             $excess_qty = "";
    //             $data[$i]['excess_qty'] = "";
    //         }
            
    //         if( isset($data1[$i]['plan_bom_qty'])) {
    //             $plan_bom_qty = $data1[$i]['plan_bom_qty'];
    //             $data[$i]['plan_bom_qty'] = $data1[$i]['plan_bom_qty'];
    //         }
    //         else {
    //             $plan_bom_qty = "";
    //             $data[$i]['plan_bom_qty'] = "";
    //         }
            
    //         if( isset($data1[$i]['bom_1_req_consld_id'])) {
    //             $bom_1_req_consld_id = $data1[$i]['bom_1_req_consld_id'];
    //             $data[$i]['bom_1_req_consld_id'] = $data1[$i]['bom_1_req_consld_id'];
    //         }
    //         else {
    //             $bom_1_req_consld_id = "";
    //             $data[$i]['bom_1_req_consld_id'] = "";
    //         }

    //         $data[$i]['req_type'] = $data1[$i]['req_type'];
    //         $data[$i]['req_date'] = $data1[$i]['req_date'];
    //         $data[$i]['cutoff_date'] = $data1[$i]['cutoff_date'];
    //         $data[$i]['merchant_note'] = $data1[$i]['merchant_note'];
    //         $data[$i]['req_sent_status'] = $data1[$i]['req_sent_status'];

    //         $data[$i]['bom_1_source_id'] = $data[$i]['sourcing_advice'] = $data[$i]['vendor_location'] = $data[$i]['vendor_name_address'] =
    //         $data[$i]['contact_email'] = $data[$i]['gst'] = $data[$i]['online_order_sys'] = $data[$i]['pass_expiry_date'] = '';

    //         for ($j=0; $j < sizeof($data3); $j++) { 
    //             if($data3[$j]['item_desc'] == $data[$i]['item_desc']) {
    //                 $data[$i]['bom_1_source_id']  = $data2[$j]['bom_1_source_id'];    
    //                 $data[$i]['sourcing_advice'] = $data2[$j]['sourcing_advice'];    
    //                 $data[$i]['vendor_location'] = $data2[$j]['vendor_location'];    
    //                 $data[$i]['vendor_name_address'] = $data2[$j]['vendor_name_address'];    
    //                 $data[$i]['contact_email'] = $data2[$j]['contact_email'];    
    //                 $data[$i]['gst'] = $data2[$j]['gst'];
    //                 $data[$i]['online_order_sys'] = $data2[$j]['online_order_sys'];    
    //                 $data[$i]['pass_expiry_date'] = $data2[$j]['pass_expiry_date'];
    //             }
    //         }

    //         $bcm = $data[$i]['blend'] . ' / '. $data[$i]['content'] . ' / ' . $data[$i]['material'];
    //         $data[$i]['bcm'] = $data[$i]['blend'] . ' / '. $data[$i]['content'] . ' / ' . $data[$i]['material'];

    //         if($data1[$i]['req_draft_status'] == 0 && $data1[$i]['req_sent_status'] == 0)
    //         {
    //             $combineValue = ['', $bom_1_req_consld_id, false, $data[$i]['item_desc'], $bcm, 
    //                             $data[$i]['garment_size'], $data[$i]['appr_item_code'], $data[$i]['appr_item_col_code'], 
    //                             $data[$i]['size_dim'], $data[$i]['uom'], $data[$i]['calc_bom_qty'], $excess_qty, 
    //                             $plan_bom_qty, $data[$i]['requirement_uom']
    //                         ];
    //             array_push($result, $combineValue);
    //         }
    //         else if($data1[$i]['req_draft_status'] == 1 && $data1[$i]['req_sent_status'] == 1) {
    //             $combineValue = ['sent', $bom_1_req_consld_id, $data[$i]['item_desc'], $bcm, 
    //                             $data[$i]['garment_size'], $data[$i]['appr_item_code'], $data[$i]['appr_item_col_code'], 
    //                             $data[$i]['size_dim'], $data[$i]['uom'], $data[$i]['calc_bom_qty'], $excess_qty, 
    //                             $plan_bom_qty, $data[$i]['requirement_uom']
    //                         ];
    //             array_push($result, $combineValue);

    //             $cValue = ['sent', $bom_1_req_consld_id, $data[$i]['item_desc'], $bcm, 
    //                             $data[$i]['sourcing_advice'], $data[$i]['vendor_location'], $data[$i]['vendor_name_address'], 
    //                             $data[$i]['contact_email'], $data[$i]['gst'], $data[$i]['online_order_sys'], $data[$j]['pass_expiry_date']
    //                         ];
    //             array_push($sourcing_result, $cValue);
    //         }

    //     }

    //     for ($i=0; $i < sizeof($data); $i++) { 
    //         if($data[$i]['req_sent_status'] == 0)
    //         {
    //             unset($data[$i]);
    //         }
    //     }

    //     foreach ($result as $key => $value) {
    //         if($value[0] == "")
    //         {
    //             unset($result[$key]);
    //         }
    //     }

    //     $req_sql = "SELECT a.*, b.contactname as auth_name FROM tbl_request as a LEFT JOIN kn_users as b ON a.auth_by=b.id WHERE a.request_id='$reqId' ";
    //     $req_data = $this->db->query($req_sql)->result_array();

    //     $output['data'] = array_values($result);
    //     $output['totData'] = array_values($data);
    //     $output['sourcing_result'] = array_values($sourcing_result);
    //     $output['req_data'] = $req_data;
    //     return $output;
    // }

    public function getPurchaseRequestSentDetailss($id, $reqId) {
        $sql = "SELECT a.*, b.vendorname as v_name, b.address as v_address FROM tbl_request_bom as a 
                LEFT JOIN kn_master_bom_vendor as b ON a.vendor_name_address=b.id
                where a.enquiry_id='$id' AND a.request_id = '$reqId' AND a.flag=1";
        $data = $this->db->query($sql)->result_array();

        $result = [];
        $sourcing_result = [];

        foreach ($data as $key => $value) {

            //$v_name_address = $value['v_name'] .' / '. $value['v_address'];

            $combineValue = ['sent', $value['request_bom_id'], $value['item_desc'], $value['bcm'], 
                            $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'], 
                            $value['size_dim'], $value['uom'], $value['consl_bom_qty'], $value['excess_qty'], 
                            $value['plan_bom_qty'], $value['requirement_uom']
                        ];
            array_push($result, $combineValue);

            $cValue = ['sent', $value['bom_req_consld_id'], true, $value['item_desc'], $value['appr_item_code'], $value['appr_item_col_code'], 
                            $value['sourcing_advice'], $value['vendor_location'], $value['vendor_name_address'], 
                            $value['contact_email'], $value['gst'], $value['online_order_sys'], $value['pass_expiry_date']
                        ];
            array_push($sourcing_result, $cValue);
        }

        $req_sql = "SELECT a.*, b.contactname as auth_name FROM tbl_request as a LEFT JOIN ".KN_USERS." as b ON a.auth_by=b.id WHERE a.request_id='$reqId' ";
        $req_data = $this->db->query($req_sql)->result_array();

        $output['data'] = $result;
        $output['totData'] = $data;
        $output['sourcing_result'] = $sourcing_result;
        $output['req_data'] = $req_data;
        return $output;
    }

    public function updateDeptBOMRequestt($eid, $reqId, $req_status)
    {
        if($req_status == '1')
        {
            
            $req_sql = "SELECT MAX(queue_no)+1 as last_queue_no FROM tbl_request";
            $req_data = $this->db->query($req_sql)->result_array();
    
            $ord_sql = "SELECT reqforisrior FROM kn_order_enquiry WHERE id=$eid";
            $ord_data = $this->db->query($ord_sql)->result_array();
            $reqforisrior = $ord_data[0]['reqforisrior'];
            $ArrIsrIor   = unserialize(ARRISRIOR);
    
            $queue_no = $req_data[0]['last_queue_no'];
            if($queue_no == "") { $queue_no = 1; }
    
            //$ref_queue_no = $ArrIsrIor[$reqforisrior]."-BSG".$eid."/".date('my')."/BQ-".$queue_no;
            $ref_queue_no = $ArrIsrIor[$reqforisrior]."/".date('my')."/BSG-".$eid."/BQ-".$queue_no;

            $updateValue['queue_no'] = $queue_no;
            $updateValue['ref_queue_no'] = $ref_queue_no;
            $updateValue['qno_assign_dt'] = $this->mysqldatetime;
        }

        $updateValue['req_status'] = $req_status;
        $updateValue['deprt_approval'] = $req_status;
        $updateValue['log'] = LOGTIME;

        $this->db->where('request_id', $reqId);
        $this->db->update('tbl_request', $updateValue);
    }

    public function getPurchaseBomQueueDetailss($enqId, $reqId) {
        $k1=0;
        $j1=0;
        $sql = "SELECT * FROM tbl_request_bom b
                LEFT JOIN tbl_request c on b.request_id=c.request_id
                LEFT JOIN kn_master_bom_vendor d on b.vendor_id=d.id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND c.mgmt_approval = 1 AND c.deprt_approval = 1  ";
        $data = $this->db->query($sql)->result_array();
        // print_r($sql); exit;
        $pisql = "SELECT * FROM tbl_request_bom b
                LEFT JOIN tbl_request c on b.request_id=c.request_id
                LEFT JOIN kn_master_bom_vendor d on b.vendor_id=d.id
                LEFT JOIN tbl_purchase_indent e on b.request_id=e.request_id
                LEFT JOIN tbl_request_status f on e.purchase_indent_id = f.purchase_indent_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND c.mgmt_approval = 1 AND c.deprt_approval = 1 and f.type_of_mode='M' GROUP BY b.request_bom_id ";
        $pidata = $this->db->query($pisql)->result_array();
        
        $insql = "SELECT * FROM tbl_bom_in_house b
                LEFT JOIN tbl_request c on b.request_id=c.request_id
                LEFT JOIN tbl_purchase_indent d on b.purchase_indent_id=d.purchase_indent_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND c.mgmt_approval = 1 AND c.deprt_approval = 1 AND b.type='M' ";
        $inHousedata = $this->db->query($insql)->result_array();
        
        $result = $att_result = $pidetails = $inhousestatusdetails = $itemacceptstatus = $inhouseconsolidatedqtydetails = [];
        
        foreach ($data as $key => $value)
        {

            $vendor_name_address = $value['vendorname'] . ' / ' . $value['address'];

            $result[$key] = [ $value['request_bom_id'] , $value['item_desc'], $value['bcm'], $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                            $value['size_dim'], $value['uom'], $value['consl_bom_qty'], $value['excess_qty'], $value['plan_bom_qty'], 
                            $value['requirement_uom'] ];

            $att_result[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['appr_item_code'], $value['appr_item_col_code'],
                            $value['sourcing_advice'], $value['vendor_location'], $value['vendor_name_address'], 
                            $value['contact_email'], $value['gst'], $value['online_order_sys'], $value['pass_expiry_date']
                        ];
        }
        
        if($inHousedata) {
        
        foreach ($inHousedata as $key => $value)
        {
            
            $inhousestatusdetails[$key] = [ $value['bom_in_house_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_color_code'], $value['size_dim'], $value['uom'], $value['pi_ref_queue_no'], $value['dc_no'], $value['dc_date'], $value['dc_qty'], $value['invoice_no'], $value['invoice_date'], $value['invoice_qty'], $value['received_date'], $value['received_qty'], $value['received_uom']
                        ];
                        
            $j1 = $key;
        }
        } 
        
        
        foreach ($pidata as $key => $value) {
            if($value['pi_appl_status'] == 0) {
                $status = 'PENDING';
            } else if($value['pi_appl_status'] == 1) {
                $status = 'APPROVED';
            } else if($value['pi_appl_status'] == 2) {
                $status = 'DECLINED';
            } else if($value['pi_appl_status'] == 1) {
                $status = 'PENDING - RR';
            } else {
                $status = '-';
            }
            $pidetails[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], $value['pi_dt'], $value['pi_ref_queue_no'], $value['plan_bom_qty'], $value['requirement_uom'], $status, $value['appr_dt'], $value['exp_dod']
                        ];
             
            $k1 = $key;
        }
        
        $pisql1 = "SELECT b.*,a.*,e.pi_dt,e.pi_ref_queue_no,e.pi_appl_status,f.appr_dt,e.exp_dod FROM tbl_surplus_issued_details b
                LEFT JOIN tbl_request_bom a on b.bom_id=a.request_bom_id
                LEFT JOIN tbl_purchase_indent e on b.pi_ref_no=e.pi_ref_queue_no
                LEFT JOIN tbl_request c on e.request_id=c.request_id
                LEFT JOIN kn_master_bom_vendor d on a.vendor_id=d.id
                LEFT JOIN tbl_request_status f on e.purchase_indent_id = f.purchase_indent_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND c.mgmt_approval = 1 AND c.deprt_approval = 1 and f.type_of_mode='M' AND e.purchase_indent_id != 0 GROUP BY b.bom_id ";
        $pidata1 = $this->db->query($pisql1)->result_array();
        //print_r($pidata1); exit;
        if($pidata1) {
            foreach($pidata1 as $key1 => $value1) {
                if($k1 > 0) {
                    $k1++;
                }
                if($value1['pi_appl_status'] == 0) {
                    $status = 'PENDING';
                } else if($value1['pi_appl_status'] == 1) {
                    $status = 'APPROVED';
                } else if($value1['pi_appl_status'] == 2) {
                    $status = 'DECLINED';
                } else if($value1['pi_appl_status'] == 1) {
                    $status = 'PENDING - RR';
                } else {
                    $status = '-';
                }
                $pidetails[$k1] = [ $value1['request_bom_id'], $value1['item_desc'], $value1['garment_size'], $value1['appr_item_code'],
                            $value1['appr_item_col_code'], $value1['size_dim'], $value1['uom'], $value1['pi_dt'], $value1['pi_ref_queue_no'], $value1['plan_bom_qty'], $value1['requirement_uom'], $status, $value1['appr_dt'], $value1['exp_dod']
                        ];
                
            }
        }
        
        $insql1 = "SELECT * FROM tbl_bom_in_house b
                LEFT JOIN tbl_request c on b.request_id=c.request_id
                LEFT JOIN tbl_purchase_indent d on b.purchase_indent_id=d.purchase_indent_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND c.mgmt_approval = 1 AND c.deprt_approval = 1 AND b.type='S' ";
        $inHousedata1 = $this->db->query($insql1)->result_array();
        if($inHousedata1) {
            foreach($inHousedata1 as $key2 => $value2) {
                
                if($j1 > 0) {
                    $j1++;
                }
            }
        }
        
        
        
        $sql1 = "SELECT b.*,c.plan_bom_qty,e.pi_ref_queue_no FROM tbl_bom_in_house b
                LEFT JOIN tbl_request_bom c on b.request_bom_id = c.request_bom_id
                LEFT JOIN tbl_request d on c.request_id=d.request_id
                LEFT JOIN tbl_purchase_indent e on b.purchase_indent_id=e.purchase_indent_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 ";
        @$req_data = $this->db->query($sql1)->result_array();
        //print_r($sql1); exit;
        if($req_data) {
            // $k = 0;
            foreach ($req_data as $key => $value)
            {
                // print_r($value['merchant_item_status']); exit;
                if($value['merchant_item_status'] == 1 && $value['qa_status'] == 1 && $value['mgmt_ovrd_status'] == 0) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 1 && $value['qa_status'] == 2 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 2 && $value['qa_status'] == 1 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 2 && $value['qa_status'] == 2 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else {
                    $con_status = 'Failed';
                }
                
                if($value['mgmt_ovrd_status'] == 3) {
                    $col_status = 'Yes';
                } else {
                    $col_status = 'No';
                }
                
                if($value['order_stock_status'] == 1) {
                    $check_status = true;
                } else {
                    $check_status = false;
                }
                $inhousestatusdetails[$key] = [ $value['bom_in_house_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_color_code'], $value['size_dim'], $value['uom'], $value['pi_ref_queue_no'], $value['dc_no'], date('d-m-Y',strtotime($value['dc_date'])),  $value['dc_qty'], $value['invoice_no'], date('d-m-Y',strtotime($value['invoice_date'])), $value['invoice_qty'], date('d-m-Y',strtotime($value['received_date'])),  $value['received_qty'], $value['received_uom']
                        ];
                $itemacceptstatus[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_color_code'], @$value['dc_no'], date('d-m-Y',strtotime($value['dc_date'])),  @$value['dc_qty'], @$value['uom'], $value['merchant_item_status'], $value['merchant_appl_date_time'], 
                            @$value['qa_status'], @$value['qa_status_upt_dt'], @$value['mgmt_ovrd_status'], @$value['mgmt_status_upd_dt']
                        ];
            }
             
        }
        
        // print_r($inHousedata1); exit;
        $UOM = unserialize(ARRUNITOFMEASURE);

        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data12 = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data12);
        }
        
        $where = '((b.merchant_item_status = 1 and b.qa_status = 1 and b.mgmt_ovrd_status = 0) or (b.merchant_item_status = 1 and b.qa_status = 2 and b.mgmt_ovrd_status = 1) or (b.merchant_item_status=2 and b.qa_status = 1 and b.mgmt_ovrd_status = 1) or (b.merchant_item_status=2 and b.qa_status = 2 and b.mgmt_ovrd_status = 1) )';
        $sql2 = "SELECT c.*, b.order_stock_status,b.received_uom,b.supply_closure_status,b.supply_closure_date,b.received_uom, SUM(b.received_qty) as received_qtys, c.plan_bom_qty FROM tbl_request_bom c
                LEFT JOIN tbl_bom_in_house b on c.request_bom_id = b.request_bom_id  AND $where
                LEFT JOIN tbl_request d on c.request_id=d.request_id
                WHERE c.request_id = " . $reqId . " AND c.flag=1 GROUP BY c.item_desc,c.garment_size,c.appr_item_code,c.appr_item_col_code,c.size_dim,c.uom ";
        @$req_data1 = $this->db->query($sql2)->result_array();
        $k = 0;
        if($req_data1) {
        foreach ($req_data1 as $key => $value)
        {
                $item_dec = $value['item_desc'];
                $garment_size = $value['garment_size'];
                $appr_item_code = $value['appr_item_code'];
                
                
                //$item[$item_dec][$garment_size][$appr_item_code][] = $value['received_qty'];
                
                if($value['merchant_item_status'] == 1 && $value['qa_status'] == 1 && $value['mgmt_ovrd_status'] == 0) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 1 && $value['qa_status'] == 2 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 2 && $value['qa_status'] == 1 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 2 && $value['qa_status'] == 2 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else {
                    $con_status = 'Failed';
                }
    
                
                // if($con_status === 'Consolidated') {
                //     $plan_bom_qty = $value['plan_bom_qty'];
                //     $received_qty = $value['received_qtys'];
                //     $received_uom = $value['received_uom'];
                // } else if($value['order_stock_status'] == 1) {
                //     $plan_bom_qty = $value['plan_bom_qty'];
                //     $received_qty = $value['received_qtys'];
                //     $received_uom = $value['received_uom'];
                // } else {
                //     $plan_bom_qty = 0.00;
                //     $received_qty = 0.00;
                //     $received_uom = '';
                // }
                
                $plan_bom_qty = $value['plan_bom_qty'];
                $received_qty = $value['received_qtys'];
                $received_uom = $value['received_uom'];
                
            if($value['supply_closure_status'] == 0) {
                $status = 'PENDING';
            } else if($value['supply_closure_status'] == 1) {
                $status = 'DISC SUPPLY CLOSED';
            } else if($value['supply_closure_status'] == 2) {
                $status = 'SHORT SUPPLY CLOSED';
            } else if($value['supply_closure_status'] == 3) {
                $status = 'FULL SUPPLY CLOSED';
            } else if($value['supply_closure_status'] == 4) {
                $status = 'P.I. CANCELLED';
            } else {
                $status = '-';
            }
                
                // if($con_status == 'Consolidated' || $value['order_stock_status'] == 1) {
                    $diff = $received_qty - $plan_bom_qty;
                    $inhouseconsolidatedqtydetails[$k] = [ $value['bom_in_house_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], $plan_bom_qty, $received_qty, $diff, $received_uom, $status, $value['supply_closure_date']
                        ];
                    $k++;
                // }
        }
        } 
        
        $req_sql = "SELECT * FROM tbl_request
                WHERE request_id = " . $reqId;
        $req_data = $this->db->query($req_sql)->result_array();
        
        $output['purchaserequest'] = $result;
        $output['sourcedetails'] = $att_result;
        $output['pidetails'] = $pidetails;
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['itemacceptstatus'] = $itemacceptstatus;
        $output['inhouseconsolidatedqtydetails'] = $inhouseconsolidatedqtydetails;
        $output['uomData'] = $UOMDetails;
        $output['req_data'] = $req_data;
        return $output;
    }

    public function updateDeptNoteequestt($reqId, $dep_remarks)
    {
        $updateValue['dep_remarks'] = $dep_remarks;
        $updateValue['log'] = LOGTIME;

        $this->db->where('request_id', $reqId);
        $this->db->update('tbl_request', $updateValue);
    }
    public function updateCreditDetailss($eId, $reqId, $pId, $credit_data, $payment_data)
    {
        foreach ($payment_data as $key => $rvalue) {
            $reValue['credits'] = $rvalue[11];
            if($rvalue[11] > 0) {
                $reValue['amount_payable'] = $rvalue[12];    
            }
            $reValue['log'] = LOGTIME;
            $this->db->where('payment_id', $rvalue[0]);
            $this->db->update('tbl_payment_invoice', $reValue);
        }
        
        foreach ($credit_data as $key => $cvalue) {
            $creValue['enquiry_id'] = $eId;
            $creValue['request_id'] = $reqId;
            $creValue['purchase_indent_id'] = $pId;
            $creValue['description'] = $cvalue[2];
            $creValue['invoice_no'] = $cvalue[3];
            $creValue['account_name'] = $cvalue[4];
            $creValue['account_no'] = $cvalue[5];
            $creValue['transaction_id'] = $cvalue[6];
            $creValue['transaction_date'] = $cvalue[7];
            $creValue['credit_amount'] = $cvalue[8];
            $creValue['currency'] = $cvalue[9];
            
            //$creValue['approved_by'] = $cvalue[12];
            //$creValue['approved_date	'] = $cvalue[13];
            $creValue['log'] = LOGTIME;
            if($cvalue[1] == '' && $cvalue[2] != '') {
                $creValue['approval_status'] = 0;
                $this->db->insert('tbl_credit_note', $creValue);    
            } else {
                $creValue['approval_status'] = 3;
                $this->db->where('credit_note_id',$cvalue['1']);
                $this->db->update('tbl_credit_note', $creValue);
            }
            
        }
        
        
    }
    
    // public function updatePaymentDetailss($eId, $reqId, $pId, $paid_data)
    // {
        
    //     foreach ($paid_data as $key => $value) {
    //         $paidValue['request_id'] = $reqId;
    //         $paidValue['purchase_indent_id'] = $pId;
    //         $paidValue['row_id'] = $value[2];
    //         $paidValue['vendor_id'] = $value[3];
    //         $paidValue['vendor_bank_name'] = $value[4];
    //         $paidValue['acc_no'] = $value[5];
    //         $paidValue['mop'] = $value[6];
    //         $paidValue['trans_id_code'] = $value[7];
    //         $paidValue['trans_date'] = $value[8];
    //         $paidValue['paid_towards'] = $value[9];
    //         $paidValue['amount'] = $value[10];
    //         $paidValue['currency'] = $value[11];
    //         $paidValue['unit'] = $value[12];
    //         $paidValue['total'] = $value[13];
    //         $paidValue['log'] = LOGTIME;
    //         if($value[1] == '' && $value[6] != '') {
    //             $this->db->insert('tbl_bom_payment_paid', $paidValue);    
    //         } else {
    //             $this->db->where('payment_paid_id',$value['1']);
    //             $this->db->update('tbl_bom_payment_paid', $paidValue);
    //         }
            
    //     }
    // }


    public function updatePaymentDetailss($eId, $reqId, $pId, $paid_data)
{
    foreach ($paid_data as $key => $value) {
        // Prepare the paidValue array with the required data
        $paidValue = [
            'request_id' => $reqId,
            'purchase_indent_id' => $pId,
            'row_id' => $value[2],
            'vendor_id' => $value[3],
            'vendor_bank_name' => $value[4],
            'acc_no' => $value[5],
            'mop' => $value[6],
            'trans_id_code' => $value[7],
            'trans_date' => $value[8],
            'paid_towards' => $value[9],
            'amount' => $value[10],
            'currency' => $value[11],
            'unit' => $value[12],
            'total' => $value[13],
            'log' => LOGTIME
        ];

        // Check if the payment record already exists using a unique identifier (e.g., trans_id_code)
        $existingPayment = $this->db->get_where('tbl_bom_payment_paid', [
            'request_id' => $reqId,
            'purchase_indent_id' => $pId,
            'trans_id_code' => $value[7],
             'total' => $value[13],
        ])->row_array();

        if ($existingPayment) {
            // If the payment exists, update the record
            $this->db->where('payment_paid_id', $existingPayment['payment_paid_id']);
            $this->db->update('tbl_bom_payment_paid', $paidValue);
        } else {
            // If the payment does not exist, insert a new record
            $this->db->insert('tbl_bom_payment_paid', $paidValue);
        }
    }
}

    public function updateFinanceReqRecDetailss($eId, $reqId, $pId, $req_data)
    {
        //print_r($req_data); exit;

        // foreach ($paid_data as $key => $value) {
        //     $paidValue['request_id'] = $reqId;
        //     $paidValue['purchase_indent_id'] = $pId;
        //     $paidValue['row_id'] = $value[2];
        //     $paidValue['vendor_id'] = $value[3];
        //     $paidValue['vendor_bank_name'] = $value[4];
        //     $paidValue['acc_no'] = $value[5];
        //     $paidValue['mop'] = $value[6];
        //     $paidValue['trans_id_code'] = $value[7];
        //     $paidValue['trans_date'] = $value[8];
        //     $paidValue['paid_towards'] = $value[9];
        //     $paidValue['amount'] = $value[10];
        //     $paidValue['currency'] = $value[11];
        //     $paidValue['unit'] = $value[12];
        //     $paidValue['total'] = $value[13];
        //     $paidValue['log'] = LOGTIME;
        //     if($value[1] == '' && $value[6] != '') {
        //         $this->db->insert('tbl_bom_payment_paid', $paidValue);    
        //     } else {
        //         $this->db->where('payment_paid_id',$value['1']);
        //         $this->db->update('tbl_bom_payment_paid', $paidValue);
        //     }
            
        // }

        foreach ($req_data as $key => $value) {
            $updateValue = array();
            $updateValue['log'] = LOGTIME;
            if($value[10] != 'PENDING' && $value[10] != '' && $value[12] == 'PENDING') {
                $updateValue['payment_paid_status'] = $value[10];
                $updateValue['pay_paid_sta_upd_dt'] = $this->mysqldatetime;
            }
            $this->db->where('request_status_id', $value[0]);
            $this->db->where('request_id', $reqId);
            $this->db->update('tbl_request_status', $updateValue);
        }
        // foreach ($request_data as $key => $rvalue) {
        //     $reValue['credits'] = $rvalue[11];
        //     $reValue['log'] = LOGTIME;
        //     $this->db->where('payment_id', $rvalue[0]);
        //     $this->db->update('tbl_payment_invoice', $reValue);
        // }
        
        // foreach ($credit_data as $key => $cvalue) {
        //     $creValue['enquiry_id'] = $eId;
        //     $creValue['request_id'] = $reqId;
        //     $creValue['purchase_indent_id'] = $pId;
        //     $creValue['description'] = $cvalue[2];
        //     $creValue['invoice_no'] = $cvalue[3];
        //     $creValue['account_name'] = $cvalue[4];
        //     $creValue['account_no'] = $cvalue[5];
        //     $creValue['transaction_id'] = $cvalue[6];
        //     $creValue['transaction_date'] = $cvalue[7];
        //     $creValue['credit_amount'] = $cvalue[8];
        //     $creValue['currency'] = $cvalue[9];
        //     //$creValue['mode_of_payment'] = $cvalue[10];
        //     //$creValue['approval_status'] = $cvalue[11];
        //     //$creValue['approved_by'] = $cvalue[12];
        //     //$creValue['approved_date	'] = $cvalue[13];
        //     $creValue['log'] = LOGTIME;
        //     if($cvalue[1] == '' && $cvalue[2] != '') {
        //         $this->db->insert('tbl_credit_note', $creValue);    
        //     } else {
        //         $this->db->where('credit_note_id',$cvalue['1']);
        //         $this->db->update('tbl_credit_note', $creValue);
        //     }
            
        // }

        //$this->db->where('request_id', $reqId);
        //$this->db->update('tbl_request', array('bill_paid_status'=> 1,'log'=>LOGTIME));

    }

    public function imageUploadDetailss($type, $id, $reqId, $filepathName)
    {
        $this->db->insert('tbl_wip_files', array('enquiry_id'=> $id, 'request_id'=> $reqId, 'type'=> $type, 'image_url'=>$filepathName));
        $result["status"] = "success";
        return $result;
    }
    
    public function imageUploadDetailss_decline($type, $id, $reqId, $filepathName, $pId)
    {
        $this->db->insert('tbl_wip_files', array('enquiry_id'=> $id, 'request_id'=> $reqId, 'purchase_indent_id' => $pId, 'type'=> $type, 'image_url'=>$filepathName));
        $result["status"] = "success";
        return $result;
    }
    
    public function getMIBOMReceivedDetailss($id, $reqId) {
        
        $sql = "SELECT a.*, b.*, c.*, d.*, e.*
                FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id 
                LEFT JOIN tbl_mi_bom as d on b.request_id=d.request_id 
                LEFT JOIN tbl_mi_bom_details as e on d.mi_bom_id = e.mi_bom_id 
                WHERE a.enquiry_id = ".$id." AND a.flag=1 and a.req_sent_status = 1 ORDER BY a.sample_requirement_id desc";
        $data = $this->db->query($sql)->result_array();
        
        // print_r($data); exit;
        $referResult = [];
        $ref_status = 0;
        $bomMaterialIndent = [];
        foreach ($data as $key => $nValue) {
            
                if($nValue['bom_status'] == 0) 
                {
                    $status = false;
                }
                else {
                    $status = true;
                    $bomMaterialIndent[$key] = [ $nValue['mi_bom_details_id'], $status, $nValue['item_desc'], $nValue['bcm'], $nValue['gar_size'], $nValue['item_code'],
                                $nValue['item_color_code'], $nValue['size_dim'], $nValue['uom'], $nValue['ind_qty'], $nValue['ind_uom'], '', '' , '', '' ];
                }
                
                $ref_status += (int) $nValue['req_reference_status'];
                if($nValue['req_reference_status'] == "1" || $nValue['req_reference_status'] == 1) 
                {
                    $referResult[$key] = ['edit', $nValue['sample_requirement_id'], $nValue['req_reference_status'] , $nValue['po_enq_ref_id'], $nValue['combo_id'], $nValue['component_id'],
                            $nValue['spec_code_id'], $nValue['grad_measure_chart'], $nValue['artwork'], $nValue['measure_details'], $nValue['buyer_sample'], $nValue['buyer_comment'] ];
                }
        }
        
        if($reqId != null) {
            $mi_sql = "SELECT * FROM tbl_mi_details WHERE request_id = ".$reqId." AND flag=1 ";
            $mi_data = $this->db->query($mi_sql)->result_array();
        }
        
        $bomMIDetails = $this->bomMIDetails($id);
        
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $UOM = unserialize(ARRUNITOFMEASURE);
        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }

        $output['sizeData'] = $sizeMaster;
        $output['UOMDetails'] = $UOMDetails;
        $output['ref_status'] = $ref_status;
        $output['referResult'] = array_values($referResult);
        $output['BOMAppendData'] = $bomMIDetails;
        $output['bom_mi_tbl_data'] = $bomMaterialIndent;
        $output['mi_data'] = $mi_data;
        
        return $output;
        
    }

    
    public function getMIReceivedDetailss($id, $reqId) {
        
        $sql = "SELECT a.*, b.*, c.* FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id 
                LEFT JOIN tbl_mi_bom as d on a.sample_requirement_id = d.sample_req_id
                WHERE a.enquiry_id = ".$id." AND d.request_id = ".$reqId." AND a.flag=1 and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $data = $this->db->query($sql)->result_array();
        // print_r($data); exit;
        $referResult = [];
        $ref_status = 0;

        foreach ($data as $key => $value)
        {
            $ref_status += (int) $value['req_reference_status'];
            if($value['req_reference_status'] == "1" || $value['req_reference_status'] == 1) 
            {
                $referResult[$key] = ['edit', $value['sample_requirement_id'], $value['req_reference_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];
            }
        }
        
        $bom_sql = "SELECT b.*
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_mi_bom as b on a.sample_requirement_id = b.sample_req_id
                WHERE a.enquiry_id = ".$id." AND b.request_id = ".$reqId." AND a.flag=1 and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $bom_data = $this->db->query($bom_sql)->result_array();
    
        $bom_mi_tbl_data = [];
        
        for ($i=0; $i < sizeof($bom_data); $i++) { 
            
            $bom_sql1 = "SELECT a.*,c.bom_ref_no FROM tbl_mi_bom_details as a
                INNER JOIN tbl_mi_bom as b on a.mi_bom_id = b.mi_bom_id
                INNER JOIN tbl_mi_details as c on b.request_id = c.request_id
                WHERE a.mi_bom_id = ".$bom_data[$i]['mi_bom_id']." ";
            $bom_details_data = $this->db->query($bom_sql1)->result_array();  
                
            array_push($bom_mi_tbl_data, $bom_details_data);
        }
    
        $bomMaterialIndent = [];
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
                $bomMaterialIndent[$key][$nKey] = [ $nValue['mi_bom_details_id'], $status, $status, $nValue['item_desc'], $nValue['bcm'], $nValue['gar_size'], $nValue['item_code'],
                                $nValue['item_color_code'], $nValue['size_dim'], $nValue['uom'], $nValue['ind_qty'], $nValue['ind_uom'], $tot_qty, $uom , $issuse_status , $nValue['issue_date'] ];
            }
        }
        
        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $mi_data = [];
        
        if($reqId != null) {
            $mi_sql = "SELECT * FROM tbl_mi_details WHERE request_id = ".$reqId." AND flag=1 ";
            $mi_data = $this->db->query($mi_sql)->result_array();
        }
        
        $UOM = unserialize(ARRUNITOFMEASURE);
        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }

        $bomMIDetails = $this->bomMIDetails($id);
        
        $output['sizeData'] = $sizeMaster;
        $output['UOMDetails'] = $UOMDetails;
        $output['ref_status'] = $ref_status;
        $output['referResult'] = array_values($referResult);
        $output['BOMAppendData'] = $bomMIDetails;
        $output['bom_mi_tbl_data'] = $bomMaterialIndent;
        $output['mi_data'] = $mi_data;
        return $output;
    }
    
    
    public function getDraftDcDetailss($id, $reqId, $drafdc) {
        
        $ind_qtyss  = [];
        $sql = "SELECT a.*, b.*, c.* FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id 
                LEFT JOIN tbl_mi_bom as d on a.sample_requirement_id = d.sample_req_id
                WHERE a.enquiry_id = ".$id." AND d.request_id = ".$reqId." AND a.flag=1 and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $data = $this->db->query($sql)->result_array();
        // print_r($data); exit;
        $referResult = [];
        $ref_status = 0;

        foreach ($data as $key => $value)
        {
            $ref_status += (int) $value['req_reference_status'];
            if($value['req_reference_status'] == "1" || $value['req_reference_status'] == 1) 
            {
                $referResult[$key] = ['edit', $value['sample_requirement_id'], $value['req_reference_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];
            }
        }
        
        $bom_sql = "SELECT b.*
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_mi_bom as b on a.sample_requirement_id = b.sample_req_id
                WHERE a.enquiry_id = ".$id." AND b.request_id = ".$reqId." AND a.flag=1 and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $bom_data = $this->db->query($bom_sql)->result_array();
    
        $bom_mi_tbl_data = [];
        
        for ($i=0; $i < sizeof($bom_data); $i++) { 
          
            $drafdaa = $drafdc;
if ($drafdaa == $i) {
            
            
             $bom_sql1 = "SELECT a.*, c.bom_ref_no  FROM tbl_mi_bom_details AS a
            INNER JOIN tbl_mi_bom AS b ON a.mi_bom_id = b.mi_bom_id
            INNER JOIN tbl_mi_details AS c ON b.request_id = c.request_id
            WHERE a.mi_bom_id = ".$bom_data[$i]['mi_bom_id']." 
                  AND (a.issue_qty > 0 OR a.issue_qty IS NULL OR a.issue_qty = '')";

            


              $bom_details_data = $this->db->query($bom_sql1)->result_array();  
            array_push($bom_mi_tbl_data, $bom_details_data);
}
        }
    // print_r($bom_mi_tbl_data); exit;
        $bomMaterialIndent = $lotnos = $rateLists = [];
        $lotNo = $rateList = [];
        $j =0;
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
                $pendingQty = 0;
                $dc_no = [];
                $dc_date = [];
                $uom = [];
                $bom_ref_no = "'".$nValue['bom_ref_no']."'";
                $sample_no = "'".$nValue['sample_no']."'";
                //$issue_data = $this->db->where('mi_ref_no',$nValue['bom_ref_no'])->where('mi_serial_no',$nValue['sample_no'])->get('tbl_mi_issued_details')->result_array();
                $issue_sql = "SELECT * FROM tbl_mi_issued_details as a WHERE a.mi_ref_no = ".$bom_ref_no." AND a.mi_serial_no = ".$sample_no." AND a.mi_bom_details_id = ".$nValue['mi_bom_details_id']." ";
                $issue_data = $this->db->query($issue_sql)->result_array();  
                //echo "<pre>"; print_r($issue_sql);
                foreach($issue_data as $key2 => $value2) {
                    //array_push($dc_no, $value2['dc_no']);
                    //array_push($dc_date, $value2['dc_dt']);
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
                
                $code = "'".$nValue['item_code']."'";
                $h_sql = "SELECT a.*,b.*,c.pi_ref_queue_no FROM tbl_bom_in_house a 
                INNER JOIN tbl_request b ON a.request_id = b.request_id
                INNER JOIN tbl_purchase_indent c ON a.purchase_indent_id = c.purchase_indent_id
                WHERE a.order_stock_status = 1 AND b.enquiry_id = " . $id . " AND a.appr_item_code = " . $code . " ";
                $h_data = $this->db->query($h_sql)->result_array();
                foreach ($h_data as $key1 => $value1)
                {
                    //$lotNo[] = $value1['batch_ref_no'];
                    //$rateList[] = $value1['invoice_rate'];
                    $lotNo[] = [ 'id'=> $value1['bom_in_house_id'], 'item' => $nValue['item_code'], 'name'=> $value1['batch_ref_no'] ];
                    $rateList[] = [ 'id'=> $value1['invoice_rate'], 'item' => $nValue['item_code'], 'item_id'=> $value1['bom_in_house_id'], 'name'=> $value1['invoice_rate'] ];
                }
                // echo $nValue['ind_qty']; exit;
                 $pendingQty = $nValue['ind_qty'] - $tot_qty; 
                
                //$lotnos[$key][$nKey] = $lotNo;
                //$rateLists[$key][$nKey] = $rateList;
                //   $bomMaterialIndent[$key][$nKey] = [ $nValue['mi_bom_details_id'], $status, 0, $nValue['item_desc'], $nValue['bcm'], $nValue['gar_size'], $nValue['item_code'],
                //                 $nValue['item_color_code'], $nValue['size_dim'], $nValue['uom'], $nValue['ind_qty'], $nValue['ind_uom'],'','','', $uom];
                  

                $bomMaterialIndent[$key][$nKey] = [ $nValue['mi_bom_details_id'], $status,0, $nValue['item_desc'], $nValue['bcm'], $nValue['gar_size'], $nValue['item_code'],
                                $nValue['item_color_code'], $nValue['size_dim'], $nValue['uom'], $nValue['ind_qty'], $nValue['ind_uom'], $uom,$value2['mi_issue_id']];
                  
                // $miDraftData[$key2] = [  $value2['mi_bom_details_id'], 1,  $value2['item_desc'], $value2['bcm'], $value2['gar_size'], $value2['item_code'],
                //                 $value2['item_color_code'], $value2['size_dim'], $value2['uom'], $value2['mi_pending_qty'], $value2['ind_uom'], $value2['lot_no'], $value2['rate'], $value2['issued_qty'], $value2['uomss'], $value2['mi_issue_id'] ];
       


                                

                    
                    // if($pendingQty > 0) {
                    //     $itemDescription[$j] = [ 'id'=> $nValue['mi_bom_details_id'], 'name'=> $nValue['item_desc'] ];
                    //     $j++;
                    // }
                    $itemDescription[$nKey] = [ 'id'=> $nValue['mi_bom_details_id'], 'name'=> $nValue['item_desc'] ];
                    $bcm[$nKey] = [ 'id'=> $nValue['bcm'], 'name'=> $nValue['bcm'], 'item_id'=> $nValue['mi_bom_details_id'], ];
                    $garmentSize[$nKey] = [ 'id'=> $nValue['gar_size'], 'item_id'=> $nValue['mi_bom_details_id'], 'name'=> $nValue['gar_size'] ];
                    $itemCode[$nKey] = [ 'id'=> $nValue['item_code'], 'item_id'=> $nValue['mi_bom_details_id'], 'size_id'=> $nValue['gar_size'], 'name'=> $nValue['item_code'] ];
                    $itemColorCode[$nKey] = [ 'id'=> $nValue['item_color_code'], 'item_id'=> $nValue['mi_bom_details_id'], 'size_id'=> $nValue['gar_size'], 'item_code_id'=> $nValue['item_code'], 'name'=> $nValue['item_color_code'] ];
                    $sizes[$nKey] = [ 'id'=> $nValue['size_dim'], 'item_id'=> $nValue['mi_bom_details_id'], 'size_id'=> $nValue['gar_size'], 'item_code_id'=> $nValue['item_code'], 'color_id'=> $nValue['item_color_code'], 'name'=> $nValue['size_dim'] ];
                    $uomData[$nKey] = [ 'id'=> $nValue['uom'], 'item_id'=> $nValue['mi_bom_details_id'], 'size_id'=> $nValue['gar_size'], 'item_code_id'=> $nValue['item_code'], 'color_id'=> $nValue['item_color_code'], 'dia_id'=> $nValue['size_dim'], 'name'=> $nValue['uom'] ];
                    $ind_qtyss[$nKey] = [ 'id'=> $pendingQty, 'item_id'=> $nValue['mi_bom_details_id'], 'name'=>$pendingQty ];
                    $ind_uom[$nKey] = [ 'id'=> $nValue['ind_uom'], 'item_id'=> $nValue['mi_bom_details_id'], 'name'=>$nValue['ind_uom'] ];
                
                
            }
        }
        
        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $mi_data = [];
        
        if($reqId != null) {
            $mi_sql = "SELECT * FROM tbl_mi_details WHERE request_id = ".$reqId." AND flag=1 ";
            $mi_data = $this->db->query($mi_sql)->result_array();
        }
        
        $UOM = unserialize(ARRUNITOFMEASURE);
        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }

        $bomMIDetails = $this->bomMIDetails($id);
        $MImi_serial_no='SAMPLE-2';
        
        $draft_sql = "SELECT a.*,b.*,d.bom_ref_no, a.uom as uomss FROM tbl_mi_issued_details as a
                INNER JOIN tbl_mi_bom_details as b on a.mi_bom_details_id = b.mi_bom_details_id
                INNER JOIN tbl_mi_bom as c on b.mi_bom_id = c.mi_bom_id
                INNER JOIN tbl_mi_details as d on c.request_id = d.request_id
                WHERE c.request_id = $reqId AND a.dc_status = 0 AND a.draf_id=$drafdc";
              $bom_details_data = $this->db->query($bom_sql1)->result_array(); 
              //print_r($bom_sql1);
        $draft_data = $this->db->query($draft_sql)->result_array();

        //$recived_by
        
        foreach($draft_data as $key2 => $value2) {
            //$recived_by=($value2['recived_by']);
        
            // $sample_no1 = "'".$value2['sample_no']."'";
            // $issue_sql = "SELECT SUM(issued_qty) as issued_qtys  FROM tbl_mi_issued_details WHERE mi_serial_no = ".$sample_no1." AND mi_bom_details_id = ".$value2['mi_bom_details_id']." GROUP BY mi_bom_details_id ";
            // $issue_qty = $this->db->query($issue_sql)->result_array();  
            // $ind_qty = $value2['ind_qty'];
            // $ind_qtys = $value2['ind_qty'] - $issue_qty[0]['issued_qtys'];
             //$bomMaterialIndent[$key2] = [  $value2['mi_bom_details_id'], 1,  $value2['item_desc'], $value2['bcm'], $value2['gar_size'], $value2['item_code'],
                                //$value2['item_color_code'], $value2['size_dim'], $value2['uom'], $value2['mi_pending_qty'], $value2['ind_uom'], $value2['lot_no'], $value2['rate'], $value2['issued_qty'], $value2['uomss'], $value2['mi_issue_id'] ];
            
            $miDraftData[$key2] = [  $value2['mi_bom_details_id'], 1,  $value2['item_desc'], $value2['bcm'], $value2['gar_size'], $value2['item_code'],
                                $value2['item_color_code'], $value2['size_dim'], $value2['uom'], $value2['mi_pending_qty'], $value2['ind_uom'], $value2['lot_no'], $value2['rate'], $value2['issued_qty'], $value2['uomss'], $value2['mi_issue_id'] ];
       
                            }
        
        
        $output['sizeData'] = $sizeMaster;
        $output['UOMDetails'] = $UOMDetails;
        $output['ref_status'] = $ref_status;
        $output['referResult'] = array_values($referResult);
        $output['BOMAppendData'] = $bomMIDetails;
        $output['bom_mi_tbl_data'] = $bomMaterialIndent;
        $output['miDraftData'] = $miDraftData;
        $output['mi_data'] = $mi_data;
        $output['lotNos'] = $lotNo;
        $output['rateLists'] = $rateList;
        $output['itemDescription'] = $itemDescription;
        $output['bcm'] = $bcm;
        $output['garmentSize'] = $garmentSize;
        $output['itemCode'] = $itemCode;
        $output['itemColor'] = $itemColorCode;
        $output['sizes'] = $sizes;
        $output['uomData'] = $uomData;
        $output['ind_qtyss'] = $ind_qtyss;
        $output['ind_uom'] = $ind_uom;
        //$output['recivedby'] = $recived_by;
        return $output;
    }
    
    // public function getMIReceivedDetailss($id, $reqId) {
        
    //     $sql = "SELECT a.*, b.*, c.*, d.cad_ref, d.mat_ind_cad_id, d.req as cad_req, d.purpose as cad_purpose, d.req_size as cad_req_size
    //             FROM tbl_sample_requirement as a 
    //             LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
    //             LEFT JOIN tbl_request as c on b.request_id=c.request_id 
    //             LEFT JOIN tbl_mi_cad_details as d on a.sample_requirement_id = d.sample_req_id
    //             WHERE a.enquiry_id = ".$id." AND a.flag=1 and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
    //     $data = $this->db->query($sql)->result_array();
    //     print_r($data); exit;
    //     $referResult = [];
    //     $ref_status = 0;

    //     foreach ($data as $key => $value)
    //     {
    //         $ref_status += (int) $value['req_reference_status'];
    //         if($value['req_reference_status'] == "1" || $value['req_reference_status'] == 1) 
    //         {
    //             $referResult[$key] = ['edit', $value['sample_requirement_id'], $value['req_reference_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
    //                         $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];
    //         }
    //     }
        
    //     $bom_sql = "SELECT b.*
    //             FROM tbl_sample_requirement as a
    //             INNER JOIN tbl_mi_bom as b on a.sample_requirement_id = b.sample_req_id
    //             WHERE a.enquiry_id = ".$id." AND a.flag=1 and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
    //     $bom_data = $this->db->query($bom_sql)->result_array();

    //     $bom_mi_tbl_data = [];
    //     for ($i=0; $i < sizeof($bom_data); $i++) { 
    //         $bom_details_sql = "SELECT * FROM tbl_mi_bom_details WHERE mi_bom_id = ".$bom_data[$i]['mi_bom_id']."";
    //         $bom_details_data = $this->db->query($bom_details_sql)->result_array();
    //         array_push($bom_mi_tbl_data, $bom_details_data);
    //     }

    //     $bomMaterialIndent = [];
    //     foreach ($bom_mi_tbl_data as $key => $value) {
    //         foreach ($value as $nKey => $nValue) {
    //             if($nValue['bom_status'] == 0) 
    //             {
    //                 $status = false;
    //             }
    //             else {
    //                 $status = true;
    //             }
    //             $bomMaterialIndent[$key][$nKey] = [ $nValue['mi_bom_details_id'], $status, $nValue['item_desc'], $nValue['bcm'], $nValue['gar_size'], $nValue['item_code'],
    //                             $nValue['item_color_code'], $nValue['size_dim'], $nValue['uom'], $nValue['ind_qty'], $nValue['ind_uom'], '', '' , '', '' ];
    //         }
    //     }

    //     // *** get garment size *** //
    //     $sizeChart    = $this->getSizeChart($id);
    //     $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
    //     $mi_data = [];
        
    //     if($reqId != null) {
    //         $mi_sql = "SELECT * FROM tbl_mi_details WHERE request_id = ".$reqId." AND flag=1 ";
    //         $mi_data = $this->db->query($mi_sql)->result_array();
    //     }
        
    //     $UOM = unserialize(ARRUNITOFMEASURE);
    //     $UOMDetails = [];
    //     for($i = 1; sizeof($UOM) > $i; $i++)
    //     {
    //         $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
    //         array_push($UOMDetails, $data);
    //     }

    //     $bomMIDetails = $this->bomMIDetails($id);

    //     $output['sizeData'] = $sizeMaster;
    //     $output['UOMDetails'] = $UOMDetails;
    //     $output['ref_status'] = $ref_status;
    //     $output['referResult'] = array_values($referResult);
    //     $output['BOMAppendData'] = $bomMIDetails;
    //     $output['bom_mi_tbl_data'] = $bomMaterialIndent;
    //     $output['mi_data'] = $mi_data;
    //     return $output;
    // }

    public function bomMIDetails($id)
    {
        
        $sql = "SELECT *, SUM(`req_bom_qty`) as calc_bom_qty FROM tbl_bom_article_1_requirement as a 
                where a.enquiry_id='$id' AND a.flag=1
                GROUP BY a.item_desc, a.blend, a.content, a.material, a.garment_size, a.appr_item_code, 
                        a.appr_item_col_code, a.size_dim, a.uom";
        
        $data = $this->db->query($sql)->result_array();
        
        $sql1 = "SELECT * FROM tbl_bom_article_1_req_consld as a WHERE a.enquiry_id='$id' AND a.flag=1";
        $data1 = $this->db->query($sql1)->result_array();

        $result = [];
        for($i=0; $i < sizeof($data); $i++)
        {
            if( isset($data1[$i]['excess_qty'])) {
                $excess_qty = $data1[$i]['excess_qty'];
            }
            else {
                $excess_qty = "";
            }
            
            if( isset($data1[$i]['plan_bom_qty'])) {
                $plan_bom_qty = $data1[$i]['plan_bom_qty'];
            }
            else {
                $plan_bom_qty = "";
            }
            
            if( isset($data1[$i]['bom_1_req_consld_id'])) {
                $bom_1_req_consld_id = $data1[$i]['bom_1_req_consld_id'];
            }
            else {
                $bom_1_req_consld_id = "";
            }
            
            $result[$i] = ['edit', $bom_1_req_consld_id, $data[$i]['bom_1_req_id'], $data[$i]['item_desc'], $data[$i]['blend'],
                            $data[$i]['content'], $data[$i]['material'], 
                            $data[$i]['garment_size'], $data[$i]['appr_item_code'], $data[$i]['appr_item_col_code'], 
                            $data[$i]['size_dim'], $data[$i]['uom'], $data[$i]['req_bom_qty'], $excess_qty, 
                            $plan_bom_qty, $data[$i]['requirement_uom']];
        }
        
        $itemDescription = $blendContentMaterial = $garmentSize = $itemCode = $itemColor = $sizeDimension = $uom = [];
        foreach ($result as $key => $value) {
            $bcm = $value[4] .' / '. $value[5] . ' / ' . $value[6];
            // array data
            $itemData = [ 'id'=> $value[3], 'name' => $value[3] ];
            $bcmData = [ 'id'=> $bcm, 'item_id' => $value[3], 'name' => $bcm ];
            $garmentData = [ 'id'=> $value[7], 'item_id' => $value[3], 'bcm_id'=> $bcm, 'name' => $value[7] ];
            $itemCodeData = [ 'id'=> $value[8], 'item_id' => $value[3], 'bcm_id'=> $bcm, 'garment_id'=> $value[7], 'name' => $value[8] ];
            $itemColorData = [ 'id'=> $value[9], 'item_id' => $value[3], 'bcm_id'=> $bcm, 'garment_id'=> $value[7], 'item_code_id' => $value[8], 'name' => $value[9] ];
            $sizeData = [ 'id'=> $value[10], 'item_id' => $value[3], 'bcm_id'=> $bcm, 'garment_id'=> $value[7], 'item_code_id' => $value[8], 'item_color_id' => $value[9], 'name' => $value[10] ];
            $uomData = [ 'id'=> $value[11], 'item_id' => $value[3], 'bcm_id'=> $bcm, 'garment_id'=> $value[7], 'item_code_id' => $value[8], 'item_color_id' => $value[9], 'size_id' => $value[10], 'name' => $value[11] ];
            // count set
            $count = $blendCount = $garmentCount = $itemCount = $itemColorCount = $sizeCount = $uomCount = 0;
            // loop
            for ($i=0; $i < sizeof($itemDescription); $i++) { if($value[3] == $itemDescription[$i]['name']) { $count++; } }
            for ($i=0; $i < sizeof($blendContentMaterial); $i++) { if($value[3] == $blendContentMaterial[$i]['item_id'] && $bcm == $blendContentMaterial[$i]['name']) { $blendCount++; } }
            for ($i=0; $i < sizeof($garmentSize); $i++) { if($value[3] == $garmentSize[$i]['item_id'] && $bcm == $garmentSize[$i]['bcm_id'] && $value[7] == $garmentSize[$i]['name']) { $garmentCount++; } }
            for ($i=0; $i < sizeof($itemCode); $i++) { if($value[3] == $itemCode[$i]['item_id'] && $bcm == $itemCode[$i]['bcm_id'] && $value[7] == $itemCode[$i]['garment_id'] && $value[8] == $itemCode[$i]['name']) { $itemCount++; } }
            for ($i=0; $i < sizeof($itemColor); $i++) { if($value[3] == $itemColor[$i]['item_id'] && $bcm == $itemColor[$i]['bcm_id'] && $value[7] == $itemColor[$i]['garment_id'] && $value[8] == $itemColor[$i]['item_code_id'] && $value[9] == $itemColor[$i]['name']) { $itemColorCount++; } }
            for ($i=0; $i < sizeof($sizeDimension); $i++) { if($value[3] == $sizeDimension[$i]['item_id'] && $bcm == $sizeDimension[$i]['bcm_id'] && $value[7] == $sizeDimension[$i]['garment_id'] && $value[8] == $sizeDimension[$i]['item_code_id'] && $value[9] == $sizeDimension[$i]['item_color_id'] && $value[10] == $sizeDimension[$i]['name']) { $sizeCount++; } }
            for ($i=0; $i < sizeof($uom); $i++) { if($value[3] == $uom[$i]['item_id'] && $bcm == $uom[$i]['bcm_id'] && $value[7] == $uom[$i]['garment_id'] && $value[8] == $uom[$i]['item_code_id'] && $value[9] == $uom[$i]['item_color_id'] && $value[10] == $uom[$i]['size_id'] && $value[11] == $uom[$i]['name']) { $uomCount++; } }
            // array push
            if($count == 0) { array_push($itemDescription, $itemData); }
            if($blendCount == 0) { array_push($blendContentMaterial, $bcmData); }
            if($garmentCount == 0) { array_push($garmentSize, $garmentData); }
            if($itemCount == 0) { array_push($itemCode, $itemCodeData); }
            if($itemColorCount == 0) { array_push($itemColor, $itemColorData); }
            if($sizeCount == 0) { array_push($sizeDimension, $sizeData); }
            if($uomCount == 0) { array_push($uom, $uomData); }
        }

        $output['itemDescription'] = $itemDescription;
        $output['bcm'] = $blendContentMaterial;
        $output['garmentSize'] = $garmentSize;
        $output['itemCode'] = $itemCode;
        $output['itemColor'] = $itemColor;
        $output['sizeDimension'] = $sizeDimension;
        $output['uom'] = $uom;
        return $output;
    }



    
    

    public function updateOrderStockBomm($enqId, $reqId, $req_data)
    {
        // return $req_data;
        foreach ($req_data as $key => $value) {
            if($value[2] == true)
            {
                $this->db->where('mi_bom_details_id', $value[0]);
                $this->db->update('tbl_mi_bom_details', array('bom_status'=> 1));
            }
        }
    }

    public function updateOrderStockissuedcc($enqId, $reqId, $req_data1)
    {

       
        $req_sql = "SELECT ref_queue_no FROM tbl_request WHERE request_id = ".$reqId;
        $req_data = $this->db->query($req_sql)->result_array();
         
        
        $cad_mi_sql = "SELECT MAX(dc_queue_no)+1 as last_queue_no FROM tbl_mi_bom_details";
        $cad_mi_data = $this->db->query($cad_mi_sql)->result_array();

       

        $ord_sql = "SELECT reqforisrior FROM kn_order_enquiry WHERE id=$enqId";
        $ord_data = $this->db->query($ord_sql)->result_array();
        $reqforisrior = $ord_data[0]['reqforisrior'];
        $ArrIsrIor   = unserialize(ARRISRIOR);

        $queue_no = $cad_mi_data[0]['last_queue_no'];
        if($queue_no == "") { $queue_no = 1; }

        $ref_queue_no = $req_data[0]['ref_queue_no']."/BDC-".$queue_no;
        
        
        $this->db->where('request_id', $reqId);
        $this->db->update('tbl_mi_details', array( 'dc_bom_status'=> 1, 'req_bom_id' => $reqId ));
        

        foreach ($req_data1 as $key1 => $value1) {
             $bomid       = $value1[0];
         

          $issued_qty_query = "SELECT SUM(issued_qty) as issued_qty FROM tbl_mi_issued_details WHERE mi_bom_details_id = $bomid AND dc_status=0 ";
          $issued_qty_data = $this->db->query($issued_qty_query)->result_array();
          $issued_qty  = $issued_qty_data[0]['issued_qty'];

       if (!empty($issued_qty)) {
    $req_sql1  = "SELECT issue_qty, ind_qty 
                  FROM tbl_mi_bom_details 
                  WHERE mi_bom_details_id = ".$bomid;
    $req_data1 = $this->db->query($req_sql1)->result_array();
    

    if (!empty($req_data1)) {
        $balence_qty = (float) $req_data1[0]['issue_qty'];
        $ind_qty     = (float) $req_data1[0]['ind_qty'];

        if ($balence_qty != 0) {
            if($issued_qty>0){
            $pending_qty = $balence_qty - $issued_qty;
            }
           
        } else {
            if ($ind_qty == $issued_qty) {
                $pending_qty = 0;
            } else {
                if($issued_qty>0){
                $pending_qty = $ind_qty-$issued_qty;
                }
               
            }
        }
    }
     } 
           
           

                $updateValue['dc_status'] = 0;
                $updateValue['issue_date'] = $this->mysqldatetime;
                $updateValue['issue_by'] = $this->userid;
                $updateValue['dc_queue_no'] = $queue_no;
                $updateValue['dc_ref_queue_no'] = $ref_queue_no;
                $updateValue['dc_dt'] = $this->mysqldatetime;
                $updateValue['issue_qty'] = $pending_qty;
                $updateValue["dc_status_save"] =1;
                
                
                $this->db->where('mi_bom_details_id', $value1[0])->where('dc_status_save',0);
                $this->db->update('tbl_mi_bom_details', $updateValue);
              
                
                $issuse_data['dc_no'] = $ref_queue_no;
                $issuse_data['dc_dt'] = $this->mysqldatetime;
                $issuse_data['dc_status'] = 1;
                
                $issuse_data['log'] = LOGTIME;
                
                $this->db->where('mi_bom_details_id', $value1[0])->where('dc_status',0);
                $this->db->update('tbl_mi_issued_details', $issuse_data);
                $pending_qty=null;
               
        
        
        }
        
    }

    public function getOrderStockDetailss($enqId, $reqId, $code, $pId) {
        
        $code = "'".$code."'";
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                INNER JOIN tbl_purchase_indent d on c.request_id=d.request_id
                WHERE c.request_id = " . $reqId . " AND d.purchase_indent_id = " . $pId . " AND b.appr_item_code = " . $code . " AND b.flag=1 AND d.bill_paid_status = 1";
        $data = $this->db->query($sql)->result_array();
        
        $enquiry_id = $this->db->where('purchase_indent_id',$pId)->get('tbl_purchase_indent')->row()->enquiry_id;
        
        $h_sql = "SELECT a.*,b.*,c.pi_ref_queue_no  FROM tbl_bom_in_house a 
                INNER JOIN tbl_request b ON a.request_id = b.request_id
                INNER JOIN tbl_purchase_indent c ON a.purchase_indent_id = c.purchase_indent_id
                WHERE a.order_stock_status = 1 AND b.enquiry_id = " . $enquiry_id . " AND a.appr_item_code = " . $code . "  AND a.mgmt_ovrd_status NOT IN (2, 3, 4) ";
        $h_data = $this->db->query($h_sql)->result_array();

        $mi_sql = "SELECT a.*,b.*,c.*,d.*,e.*,d.type as types,c.item_color_code as mi_item_color_code  FROM tbl_mi_bom a 
                    INNER JOIN tbl_sample_requirement b ON a.sample_req_id = b.sample_requirement_id
                    INNER JOIN tbl_mi_bom_details c ON a.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_mi_details d ON a.request_id = d.request_id
                    INNER JOIN tbl_request e ON a.request_id = e.request_id
                    WHERE c.item_code = " . $code . "  AND a.enquiry_id = ".$enquiry_id."   AND e.deprt_approval = 1 ";
        $mi_data = $this->db->query($mi_sql)->result_array();
//print_r($mi_sql); exit;
        $mi_issued_sql = "SELECT a.* FROM tbl_mi_issued_details a 
                    INNER JOIN tbl_mi_bom_details b ON a.mi_bom_details_id = b.mi_bom_details_id
                    INNER JOIN tbl_mi_bom c ON b.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_request d ON c.request_id = d.request_id
                    WHERE b.item_code = ".$code." AND d.enquiry_id = ".$enquiry_id." ";
        $mi_issued_data = $this->db->query($mi_issued_sql)->result_array();
    
        
        $shipment_sql = "SELECT a.batch_ref_no,a.invoice_rate,SUM(a.invoice_qty) as invoice_qty, a.uom as ind_uom FROM tbl_bom_in_house a
                        INNER JOIN tbl_request b ON a.request_id = b.request_id
                        WHERE a.order_stock_status = 1 AND b.enquiry_id = " . $enquiry_id . " AND a.mgmt_ovrd_status NOT IN (2, 3, 4) AND a.appr_item_code = " . $code . " GROUP BY batch_ref_no ";
        $shipment_data = $this->db->query($shipment_sql)->result_array();
        // print_r($shipment_sql); exit;
        $inhousestatusdetails = $miindentreceiveddetails = $miissueddetails = $shipmentorderclosuredetails = $itemDetails =  [];

        foreach ($data as $key => $value)
        {
            $itemDescription[$key] = [ 'id'=> $value['item_desc'], 'name'=> $value['item_desc'] ];
            $garmentSize[$key] = [ 'id'=> $value['garment_size'], 'item_id'=> $value['item_desc'], 'name'=> $value['garment_size'] ];
            $itemCode[$key] = [ 'id'=> $value['appr_item_code'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'name'=> $value['appr_item_code'] ];
            $itemColorCode[$key] = [ 'id'=> $value['appr_item_col_code'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'item_code_id'=> $value['appr_item_code'], 'name'=> $value['appr_item_col_code'] ];
            $sizeDia[$key] = [ 'id'=> $value['size_dim'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'item_code_id'=> $value['appr_item_code'], 'color_id'=> $value['appr_item_col_code'], 'name'=> $value['size_dim'] ];
            $uom[$key] = [ 'id'=> $value['uom'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'item_code_id'=> $value['appr_item_code'], 'color_id'=> $value['appr_item_col_code'], 'dia_id'=> $value['size_dim'], 'name'=> $value['uom'] ];
            $piRefNo[$key] = [ 'id'=> $value['pi_ref_queue_no'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'item_code_id'=> $value['appr_item_code'], 'color_id'=> $value['appr_item_col_code'], 'dia_id'=> $value['size_dim'], 'uom_id'=> $value['uom'], 'name'=> $value['pi_ref_queue_no'] ];
        }
        
        $itemSql = "SELECT * FROM tbl_bom_in_house a 
        INNER JOIN tbl_purchase_indent b ON a.purchase_indent_id = b.purchase_indent_id
        INNER JOIN tbl_request c ON b.request_id = c.request_id 
        WHERE a.purchase_indent_id = " . $pId . " AND a.appr_item_code = " . $code . " GROUP BY a.appr_item_code ";
        $itemData = $this->db->query($itemSql)->result_array();
        
        // print_r($itemData); exit;
        
        if($itemData) {
        foreach ($itemData as $key1 => $value1)
        {
            //$bcm = $value1['blend'].' / '.$value1['content'].' / '.$value1['material'];
            @$bcm = $this->db->where('appr_item_code',$value1['appr_item_code'])->get('tbl_request_bom')->row()->bcm;
           // $bcm = 'Test';
            $itemDetails[$key1] = [ '', $value1['request_bom_id'], $value1['item_desc'], $bcm, $value1['garment_size'], $value1['appr_item_code'],
                            $value1['appr_item_color_code'], $value1['size_dim'], $value1['uom'], $value1['bom_in_house_id']
                        ];

        }
        } else {
            $itemDetails[0] = [ '', '', '', '', '', '', '', '', '', '' ];
        }
        
    
        $lotNo = [];
        $rateList = [];
        $receivedQty = 0;
        foreach ($h_data as $key => $value)
        {
            $rec_qty = $value['received_qty'];
            if($rec_qty != '' ) {
                $receivedQty += $rec_qty;
            }
            
            $lotNo[$key] = [ 'id'=> $value['bom_in_house_id'], 'name'=> $value['batch_ref_no'] ];
            $rateList[$key] = [ 'id'=> $value['invoice_rate'],  'item_id'=> $value['bom_in_house_id'], 'name'=> $value['invoice_rate'] ];
            
            // $lotNo[$key] = $value['batch_ref_no'];
            // $rateList[$key] = $value['invoice_rate'];

            $appr_item_code = "'" . $value['appr_item_code'] . "'";
            $sql2 = "SELECT bcm FROM tbl_request_bom WHERE appr_item_code = " . $appr_item_code;
            $data2 = $this->db->query($sql2)->result_array();

            if(sizeof($data2) > 0) { $value['bcm'] = $data2[0]['bcm']; } else { $value['bcm'] = ""; }

            $inhousestatusdetails[$key] = [ 'edit', $value['bom_in_house_id'], $value['pi_ref_queue_no'], $value['invoice_no'], date('d-m-Y',strtotime($value['invoice_date'])), $value['batch_ref_no'], $value['invoice_qty'], $value['uom'], $value['invoice_rate'], $value['invoice_value'],
                    $value['gst'], $value['total_invoice_value'], $value['supply_closure_date'], $value['storage_bin']
                ];
                

            $inhouseconsolidatedqtydetails[$key] = [ $value['bom_in_house_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                    $value['appr_item_color_code'], $value['size_dim'], $value['uom'], '', '', '', '', '', '', '', '', $value['supply_closure_status']
                ];

        }

        $MIRefNo = $MINo = $deptList = [];
        $miId = 0;
        $k = 0;
        foreach ($mi_data as $key => $value) {
    
            // tbl_mi_issued_details
            $miId = $value['mi_id'];
            
            
            $deptList[$key] = [ 'id'=> $value['bom_dept'], 'item_id'=> $value['mi_bom_details_id'], 'name'=> $value['bom_dept'] ];
            
            $ref_sql = "SELECT * FROM tbl_mi_issued_details WHERE mi_bom_details_id = ".$value['mi_bom_details_id'];
            $ref_data = $this->db->query($ref_sql)->result_array();

            $total_issue_qty = 0;
            foreach ($ref_data as $key => $res) {
               $total_issue_qty += $res['issued_qty'];
            }
            
            $total_qty = $value['ind_qty'];
            //$issued_qty = 0;
            $bal_qty = $total_qty - $total_issue_qty;
            $mi_ref_id = "MI".$value['mi_bom_details_id'];
            
            if($value['types'] == 'INTERNAL') {
                $bom_dept = $value['bom_dept'];
            } else {
                $bom_dept = $this->db->where('id',$value['bom_dept'])->get('kn_master_bom_vendor')->row()->vendorname;
            }

            $miindentreceiveddetails[$k] =  [ "", $value['mi_bom_details_id'], $value['bom_ref_no'], $value['sample_no'],  $value['bom_req_date'], $value['bom_cutoff_date'],
                $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['mi_item_color_code'], 
                $value['types'], $bom_dept, $value['ind_qty'], $total_issue_qty, $bal_qty,$value['uom'] 
            ];
            $k++;
            

            if($bal_qty > 0) {
                $mi_id = "MI".$value['mi_bom_details_id'];
                $mi_ref_id = $value['bom_ref_no'];
                $res = [ 'id'=> $value['mi_bom_details_id'], 'item_id'=>$value['bom_ref_no'], 'name'=> $value['sample_no'], ];
                array_push($MINo, $res);     
                $MIRefNo[] = $value['bom_ref_no'];
            }
            
            
                

        }

        foreach ($mi_issued_data as $key => $value) {
            $miissueddetails[$key] = [ 'edit', $value['mi_issue_id'], '', $value['mi_ref_no'], $value['mi_serial_no'], $value['issued_to'], $value['dc_no'],
                $value['dc_dt'], $value['lot_no'], $value['rate'], $value['issued_qty'], $value['return_defective_qty'], $value['replace_defective_qty'], $value['return_excess_qty'],  $value['uom']
            ];
        }
        // print_r($shipment_data); exit;
        foreach ($shipment_data as $key => $value) {
            $lot_no = "'".$value['batch_ref_no']."'";
            $misql = "SELECT d.supply_closed_status,a.supply_closed_date, SUM(a.issued_qty) as issued_qty,SUM(a.return_defective_qty) as return_defective_qty,SUM(a.replace_defective_qty) as replace_defective_qty,SUM(a.return_excess_qty) as return_excess_qty FROM tbl_mi_issued_details a 
                     LEFT JOIN tbl_mi_bom_details b ON a.mi_bom_details_id = b.mi_bom_details_id
                     LEFT JOIN tbl_mi_bom c ON b.mi_bom_id = c.mi_bom_id
                     LEFT JOIN tbl_bom_in_house d ON a.lot_no = d.bom_in_house_id
                     WHERE b.item_code = ".$code." AND d.batch_ref_no = ".$lot_no." AND a.dc_status = 1  ";

                     $misql2="select * from tbl_bom_in_house where batch_ref_no = ".$lot_no." AND   appr_item_code= ".$code." ";

            //print_r($misql2); echo "<br>";
            @$misql_data = $this->db->query($misql)->row();
            @$misql_data1 = $this->db->query($misql2)->row();
            if($misql_data ) {
            $supply_closed_status = $misql_data->supply_closed_status;
            $supply_closed_date = $misql_data->supply_closed_date;
            

             $supply_closed_status_bominhouse = $misql_data1->supply_closed_status;
             $logdata=$misql_data->log;
            if(@$misql_data->issued_qty) {
                $issued_qty = $misql_data->issued_qty;
            } else {
                $issued_qty = 0;
            }
            if(@$misql_data->return_defective_qty != '' || @$misql_data->return_defective_qty != NULL) {
                $return_defective_qty = $misql_data->return_defective_qty;
            } else {
                $return_defective_qty = 0;
            }
            if(@$misql_data->replace_defective_qty != '' || @$misql_data->replace_defective_qty != NULL) {
                $replace_defective_qty = $misql_data->replace_defective_qty;
            } else {
                $replace_defective_qty = 0;
            }
            if(@$misql_data->return_excess_qty != '' || @$misql_data->return_excess_qty != NULL) {
                $return_excess_qty = $misql_data->return_excess_qty;
            } else {
                $return_excess_qty = 0;
            }
            } else {
                $issued_qty = 0;
                $return_defective_qty = 0;
                $replace_defective_qty = 0;
                $return_excess_qty = 0;
                $supply_closed_status = '';
                $supply_closed_date = '';
            }
            $shipmentorderclosuredetails[$key] = [ '', '', $value['batch_ref_no'], $value['invoice_rate'], $value['invoice_qty'], $issued_qty, $return_defective_qty, $replace_defective_qty, $return_excess_qty, '', $value['ind_uom'], '', '', '', $supply_closed_status_bominhouse, $logdata,
            ];
        }
        
        $UOM = unserialize(ARRUNITOFMEASURE);

        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }
        $MIRefNo1 = [];
        $MIRefNo = array_unique($MIRefNo);
        foreach($MIRefNo as $key => $val) {
            $MIRefNo1[] = $val;
        }

        //print_r($shipmentorderclosuredetails);
        //die;
        $output['itemDescription'] = $itemDescription;
        $output['garmentSize'] = $garmentSize;
        $output['itemCode'] = $itemCode;
        $output['itemColorCode'] = $itemColorCode;
        $output['sizeDia'] = $sizeDia;
        $output['uom'] = $uom;
        $output['piRefNo'] = $piRefNo;
        $output['itemDetails'] = $itemDetails;
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['miindentreceiveddetails'] = $miindentreceiveddetails;
        $output['miissueddetails'] = $miissueddetails;
        $output['issueCount'] = sizeof($miissueddetails);
        $output['receivedQty'] = $receivedQty;
        $output['uomData'] = $UOMDetails;
        $output['shipmentorderclosuredetails'] = $shipmentorderclosuredetails;
        $output['MIRefNo'] = $MIRefNo1;
        $output['MINo'] = $MINo;
        $output['lotNo'] = $lotNo;
        $output['rateList'] = $rateList;
        $output['deptList'] = $deptList;
        $output['miId'] = $miId;
        return $output;
    }
    
    public function supplyclosuredataa($enqId, $reqId, $code, $pId) {
        
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                INNER JOIN tbl_purchase_indent d on c.request_id=d.request_id
                WHERE c.request_id = " . $reqId . " AND d.purchase_indent_id = " . $pId . " AND b.appr_item_code = " . $code . " AND b.flag=1 AND d.bill_paid_status = 1  ";
        $data = $this->db->query($sql)->result_array();
        
        $enquiry_id = $this->db->where('purchase_indent_id',$pId)->get('tbl_purchase_indent')->row()->enquiry_id;
        
        $h_sql = "SELECT a.*,b.*,c.pi_ref_queue_no FROM tbl_bom_in_house a 
                INNER JOIN tbl_request b ON a.request_id = b.request_id
                INNER JOIN tbl_purchase_indent c ON a.purchase_indent_id = c.purchase_indent_id
                WHERE a.supply_closed_status = 1 AND b.enquiry_id = " . $enquiry_id . " AND a.appr_item_code = " . $code . " ";
        $h_data = $this->db->query($h_sql)->result_array();

        $mi_sql = "SELECT * FROM tbl_mi_bom a 
                    INNER JOIN tbl_sample_requirement b ON a.sample_req_id = b.sample_requirement_id
                    INNER JOIN tbl_mi_bom_details c ON a.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_mi_details d ON a.request_id = d.request_id
                    INNER JOIN tbl_request e ON a.request_id = e.request_id
                    WHERE c.item_code = " . $code . " AND e.deprt_approval = 1 ";
        $mi_data = $this->db->query($mi_sql)->result_array();

        $mi_issued_sql = "SELECT a.* FROM tbl_mi_issued_details a 
                    INNER JOIN tbl_mi_bom_details b ON a.mi_bom_details_id = b.mi_bom_details_id
                    INNER JOIN tbl_mi_bom c ON b.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_request d ON c.request_id = d.request_id
                    WHERE b.item_code = ".$code." ";
        $mi_issued_data = $this->db->query($mi_issued_sql)->result_array();
    
        
        $shipment_sql = "SELECT a.batch_ref_no,a.invoice_rate,invoice_qty as invoice_qty, a.uom as ind_uom FROM tbl_bom_in_house a
                        INNER JOIN tbl_request b ON a.request_id = b.request_id
                        WHERE a.order_stock_status = 1 AND b.enquiry_id = " . $enquiry_id . " AND a.appr_item_code = " . $code . "  AND a.supply_closed_status_moved = 1";
        $shipment_data = $this->db->query($shipment_sql)->result_array();
        // print_r($shipment_sql); exit;
        $inhousestatusdetails = $miindentreceiveddetails = $miissueddetails = $shipmentorderclosuredetails = $itemDetails =  [];

        foreach ($data as $key => $value)
        {
            $itemDescription[$key] = [ 'id'=> $value['item_desc'], 'name'=> $value['item_desc'] ];
            $garmentSize[$key] = [ 'id'=> $value['garment_size'], 'item_id'=> $value['item_desc'], 'name'=> $value['garment_size'] ];
            $itemCode[$key] = [ 'id'=> $value['appr_item_code'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'name'=> $value['appr_item_code'] ];
            $itemColorCode[$key] = [ 'id'=> $value['appr_item_col_code'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'item_code_id'=> $value['appr_item_code'], 'name'=> $value['appr_item_col_code'] ];
            $sizeDia[$key] = [ 'id'=> $value['size_dim'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'item_code_id'=> $value['appr_item_code'], 'color_id'=> $value['appr_item_col_code'], 'name'=> $value['size_dim'] ];
            $uom[$key] = [ 'id'=> $value['uom'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'item_code_id'=> $value['appr_item_code'], 'color_id'=> $value['appr_item_col_code'], 'dia_id'=> $value['size_dim'], 'name'=> $value['uom'] ];
            $piRefNo[$key] = [ 'id'=> $value['pi_ref_queue_no'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'item_code_id'=> $value['appr_item_code'], 'color_id'=> $value['appr_item_col_code'], 'dia_id'=> $value['size_dim'], 'uom_id'=> $value['uom'], 'name'=> $value['pi_ref_queue_no'] ];
        }
        
        $itemSql = "SELECT * FROM tbl_bom_in_house a 
        INNER JOIN tbl_purchase_indent b ON a.purchase_indent_id = b.purchase_indent_id
        INNER JOIN tbl_request c ON b.request_id = c.request_id 
        WHERE a.purchase_indent_id = " . $pId . " AND a.appr_item_code = " . $code . " ";
        $itemData = $this->db->query($itemSql)->result_array();

        //   $itemSql = "SELECT * FROM tbl_bom_in_house a 
        // INNER JOIN tbl_purchase_indent b ON a.purchase_indent_id = b.purchase_indent_id
        // INNER JOIN tbl_request c ON b.request_id = c.request_id 
        // WHERE a.purchase_indent_id = " . $pId . " AND a.appr_item_code = " . $code . " GROUP BY a.appr_item_code ";
        // $itemData = $this->db->query($itemSql)->result_array();
        
       
        
        if($itemData) {
              
        foreach ($itemData as $key1 => $value1)
        {
            
            //$bcm = $value1['blend'].' / '.$value1['content'].' / '.$value1['material'];
            @$bcm = $this->db->where('appr_item_code',$value1['appr_item_code'])->get('tbl_request_bom')->row()->bcm;

            $itemDetails[$key1] = [ '', $value1['request_bom_id'], $value1['item_desc'], $bcm, $value1['garment_size'], $value1['appr_item_code'],
                            $value1['appr_item_color_code'], $value1['size_dim'], $value1['uom']
                        ];

        }
        } else {
            $itemDetails[0] = [ '', '', '', '', '', '', '', '', '' ];
        }
        
    
        $lotNo = [];
        $rateList = [];
        $receivedQty = 0;
        foreach ($h_data as $key => $value)
        {
            $rec_qty = $value['received_qty'];
            if($rec_qty != '' ) {
                $receivedQty += $rec_qty;
            }
            
            $lotNo[$key] = [ 'id'=> $value['batch_ref_no'], 'name'=> $value['batch_ref_no'] ];
            $rateList[$key] = [ 'id'=> $value['invoice_rate'], 'item_id'=> $value['batch_ref_no'], 'name'=> $value['invoice_rate'] ];
            
            // $lotNo[$key] = $value['batch_ref_no'];
            // $rateList[$key] = $value['invoice_rate'];

            $appr_item_code = "'" . $value['appr_item_code'] . "'";
            $sql2 = "SELECT bcm FROM tbl_request_bom WHERE appr_item_code = " . $appr_item_code;
            $data2 = $this->db->query($sql2)->result_array();

            if(sizeof($data2) > 0) { $value['bcm'] = $data2[0]['bcm']; } else { $value['bcm'] = ""; }

            $inhousestatusdetails[$key] = [ 'edit', $value['bom_in_house_id'], $value['pi_ref_queue_no'], $value['invoice_no'], date('d-m-Y',strtotime($value['invoice_date'])), $value['batch_ref_no'], $value['invoice_qty'], $value['uom'], $value['invoice_rate'], $value['invoice_value'],
                    $value['gst'], $value['total_invoice_value'], $value['received_date'], $value['storage_bin']
                ];
                
            // $inhousestatusdetails[$key] = [ 'edit', $value['bom_in_house_id'], $value['item_desc'], $value['bcm'], $value['garment_size'], $value['appr_item_code'],
            //         $value['appr_item_color_code'], $value['size_dim'], $value['uom'], $value['pi_ref_no'], $value['invoice_no'], $value['invoice_date'], $value['invoice_qty'], $value['invoice_rate'], $value['invoice_value'],
            //         $value['received_qty'], $value['received_uom'], $value['received_date'], $value['storage_bin']
            //     ];

            $inhouseconsolidatedqtydetails[$key] = [ $value['bom_in_house_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                    $value['appr_item_color_code'], $value['size_dim'], $value['uom'], '', '', '', '', '', '', '', '', $value['supply_closure_status']
                ];

        }

        $MIRefNo = $MINo = $deptList = [];
        $miId = 0;
        $k = 0;
        foreach ($mi_data as $key => $value) {
    
            // tbl_mi_issued_details
            $miId = $value['mi_id'];
            
            
            $deptList[$key] = [ 'id'=> $value['bom_dept'], 'item_id'=> $value['mi_bom_details_id'], 'name'=> $value['bom_dept'] ];
            
            $ref_sql = "SELECT * FROM tbl_mi_issued_details WHERE mi_bom_details_id = ".$value['mi_bom_details_id'];
            $ref_data = $this->db->query($ref_sql)->result_array();

            $total_issue_qty = 0;
            foreach ($ref_data as $key => $res) {
               $total_issue_qty += $res['issued_qty'];
            }
            
            $total_qty = $value['ind_qty'];
            //$issued_qty = 0;
            $bal_qty = $total_qty - $total_issue_qty;
            $mi_ref_id = "MI".$value['mi_bom_details_id'];

            $miindentreceiveddetails[$k] =  [ "", $value['mi_bom_details_id'], $value['bom_ref_no'], $value['sample_no'], $value['bom_cutoff_date'],
                $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'], 
                $value['type'], $value['bom_dept'], $value['ind_qty'], $total_issue_qty, $bal_qty, 
            ];
            $k++;
            

            // if($total_issue_qty < $value['ind_qty']){
            //     $mi_ref_id = "MI".$value['mi_bom_details_id'];
            //     $res = [ 'id'=> $value['mi_bom_details_id'], 'name'=> $mi_ref_id ];
            //     array_push($MIRefNo, $res);
            // }
            // if($bal_qty > 0) {
            //     $mi_ref_id = "MI".$value['mi_bom_details_id'];
            //     $res = [ 'id'=> $value['mi_bom_details_id'], 'name'=> $mi_ref_id ];
            //     array_push($MIRefNo, $res);
            // }
            if($bal_qty > 0) {
                $mi_id = "MI".$value['mi_bom_details_id'];
                $mi_ref_id = $value['bom_ref_no'];
                $res = [ 'id'=> $value['mi_bom_details_id'], 'item_id'=>$value['bom_ref_no'], 'name'=> $value['sample_no'], ];
                array_push($MINo, $res);           
            }
            
            $MIRefNo[] = $value['bom_ref_no'];
                

        }

        foreach ($mi_issued_data as $key => $value) {
            $miissueddetails[$key] = [ 'edit', $value['mi_issue_id'], '', $value['mi_ref_no'], $value['mi_serial_no'], $value['issued_to'], $value['dc_no'],
                $value['dc_dt'], $value['lot_no'], $value['rate'], $value['issued_qty'], $value['return_defective_qty'], $value['replace_defective_qty'], $value['return_excess_qty'],  $value['uom']
            ];
        }
        // print_r($shipment_data); exit;
        foreach ($shipment_data as $key => $value) {
            
            // $misql = "SELECT a.supply_closed_status,a.supply_closed_date, SUM(a.issued_qty) as issued_qty,SUM(a.return_defective_qty) as return_defective_qty,SUM(a.replace_defective_qty) as replace_defective_qty,SUM(a.return_excess_qty) as return_excess_qty FROM tbl_mi_issued_details a 
            //          LEFT JOIN tbl_mi_bom_details b ON a.mi_bom_details_id = b.mi_bom_details_id
            //          LEFT JOIN tbl_mi_bom c ON b.mi_bom_id = c.mi_bom_id
            //          WHERE a.lot_no = '".$value['batch_ref_no']."' AND a.dc_status = 1 AND a.supply_closed_status =1 GROUP BY a.lot_no ";
            //        //  print_r($misql); exit;
            // @$misql_data = $this->db->query($misql)->row();
             $lot_no = "'".$value['batch_ref_no']."'";
             $misql = "SELECT d.supply_closed_status,a.supply_closed_date, SUM(a.issued_qty) as issued_qty,SUM(a.return_defective_qty) as return_defective_qty,SUM(a.replace_defective_qty) as replace_defective_qty,SUM(a.return_excess_qty) as return_excess_qty FROM tbl_mi_issued_details a 
                     LEFT JOIN tbl_mi_bom_details b ON a.mi_bom_details_id = b.mi_bom_details_id
                     LEFT JOIN tbl_mi_bom c ON b.mi_bom_id = c.mi_bom_id
                     LEFT JOIN tbl_bom_in_house d ON a.lot_no = d.bom_in_house_id
                     WHERE b.item_code = ".$code." AND d.batch_ref_no = ".$lot_no."  ";

                     $misql2="select * from tbl_bom_in_house where batch_ref_no = ".$lot_no." AND   appr_item_code= ".$code."  AND request_id = ".$reqId."  ";

            //print_r($misql2); echo "<br>";
            @$misql_data = $this->db->query($misql)->row();
            @$misql_data1 = $this->db->query($misql2)->row();
            if($misql_data) {
            $supply_closed_status = $misql_data->supply_closed_status;
            $supply_closed_date = $misql_data->supply_closed_date;

            $supply_closed_status_bominhouse = $misql_data1->supply_closed_status;
             //print_r($supply_closed_status_bominhouse); echo "<br>";
            //$supply_closed_status_bominhouse = $misql_data1->supply_closed_status;
             $logdata=$misql_data1->log;

            if(@$misql_data->issued_qty) {
                $issued_qty = $misql_data->issued_qty; 
            } else {
                $issued_qty = 0;
            }
            if(@$misql_data->return_defective_qty != '' || @$misql_data->return_defective_qty != NULL) {
                $return_defective_qty = $misql_data->return_defective_qty;
            } else {
                $return_defective_qty = 0;
            }
            if(@$misql_data->replace_defective_qty != '' || @$misql_data->replace_defective_qty != NULL) {
                $replace_defective_qty = $misql_data->replace_defective_qty;
            } else {
                $replace_defective_qty = 0;
            }
            if(@$misql_data->return_excess_qty != '' || @$misql_data->return_excess_qty != NULL) {
                $return_excess_qty = $misql_data->return_excess_qty;
            } else {
                $return_excess_qty = 0;
            }
            } else {
                $issued_qty = 0;
                $return_defective_qty = 0;
                $replace_defective_qty = 0;
                $return_excess_qty = 0;
                $supply_closed_status = '';
                $supply_closed_date = '';
            }
            // if($misql_data1->supply_closed_status_bominhouse == 1) {
            //     $shipmentorderclosuredetails[$key] = [ '', '', $value['batch_ref_no'], $value['invoice_rate'], $value['invoice_qty'], $issued_qty, $return_defective_qty, $replace_defective_qty, $return_excess_qty, '', $value['ind_uom'], '', '', '', $supply_closed_status, $supply_closed_date,
            //     ];
            // }
             $shipmentorderclosuredetails[$key] = [ '', '', $value['batch_ref_no'], $value['invoice_rate'], $value['invoice_qty'], $issued_qty, $return_defective_qty, $replace_defective_qty, $return_excess_qty, '', $value['ind_uom'], '', '', '', $supply_closed_status_bominhouse, $logdata,
                ];
        }
        
        $UOM = unserialize(ARRUNITOFMEASURE);

        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }
        $MIRefNo1 = [];
        $MIRefNo = array_unique($MIRefNo);
        foreach($MIRefNo as $key => $val) {
            $MIRefNo1[] = $val;
        }
        $output['itemDescription'] = $itemDescription;
        $output['garmentSize'] = $garmentSize;
        $output['itemCode'] = $itemCode;
        $output['itemColorCode'] = $itemColorCode;
        $output['sizeDia'] = $sizeDia;
        $output['uom'] = $uom;
        $output['piRefNo'] = $piRefNo;
        $output['itemDetails'] = $itemDetails;
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['miindentreceiveddetails'] = $miindentreceiveddetails;
        $output['miissueddetails'] = $miissueddetails;
        $output['issueCount'] = sizeof($miissueddetails);
        $output['receivedQty'] = $receivedQty;
        $output['uomData'] = $UOMDetails;
        $output['shipmentorderclosuredetails'] = $shipmentorderclosuredetails;
        $output['MIRefNo'] = $MIRefNo1;
        $output['MINo'] = $MINo;
        $output['lotNo'] = $lotNo;
        $output['rateList'] = $rateList;
        $output['deptList'] = $deptList;
        $output['miId'] = $miId;
        return $output;
    }

    public function getInHouseDetailss($enqId, $reqId) {
        $itemDescription = array();
        $garmentSize = array();
        $itemCode = array();
        $itemColorCode = array();
        $sizeDia = array();
        $uom = array();
        $piRefNo = array();
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND c.bill_paid_status = 1";
        $data = $this->db->query($sql)->result_array();

        foreach ($data as $key => $value)
        {
            $itemDescription[$key] = [ 'id'=> $value['item_desc'], 'name'=> $value['item_desc'] ];
            $garmentSize[$key] = [ 'id'=> $value['garment_size'], 'item_id'=> $value['item_desc'], 'name'=> $value['garment_size'] ];
            $itemCode[$key] = [ 'id'=> $value['appr_item_code'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'name'=> $value['appr_item_code'] ];
            $itemColorCode[$key] = [ 'id'=> $value['appr_item_col_code'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'item_code_id'=> $value['appr_item_code'], 'name'=> $value['appr_item_col_code'] ];
            $sizeDia[$key] = [ 'id'=> $value['size_dim'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'item_code_id'=> $value['appr_item_code'], 'color_id'=> $value['appr_item_col_code'], 'name'=> $value['size_dim'] ];
            $uom[$key] = [ 'id'=> $value['uom'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'item_code_id'=> $value['appr_item_code'], 'color_id'=> $value['appr_item_col_code'], 'dia_id'=> $value['size_dim'], 'name'=> $value['uom'] ];
            $piRefNo[$key] = [ 'id'=> $value['pi_ref_queue_no'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'item_code_id'=> $value['appr_item_code'], 'color_id'=> $value['appr_item_col_code'], 'dia_id'=> $value['size_dim'], 'uom_id'=> $value['uom'], 'name'=> $value['pi_ref_queue_no'] ];
        }
        
        $output['itemDescription'] = $itemDescription;
        $output['garmentSize'] = $garmentSize;
        $output['itemCode'] = $itemCode;
        $output['itemColorCode'] = $itemColorCode;
        $output['sizeDia'] = $sizeDia;
        $output['uom'] = $uom;
        $output['piRefNo'] = $piRefNo;
        return $output;
    }
    
    public function getBOMInHouseDetailss($enqId, $reqId, $pId) {
        $itemDescriptions = array();
        $itemDescription = array();
        $garmentSize = array();
        $itemCode = array();
        $itemColorCode = array();
        $sizeDia = array();
        $uom = array();
        $req_bom_id = array();
        $piRefNo = array();
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_purchase_indent d on b.purchase_indent_id=d.purchase_indent_id
                INNER JOIN tbl_request c on d.request_id=c.request_id
                WHERE d.purchase_indent_id = " . $pId . " AND b.flag=1 ";
        $data = $this->db->query($sql)->result_array();

        foreach ($data as $key => $value)
        {
            $itemDescription[$key] = [ 'id'=> $value['item_desc'], 'name'=> $value['item_desc'] ];
            $garmentSize[$key] = [ 'id'=> $value['garment_size'], 'item_id'=> $value['item_desc'], 'name'=> $value['garment_size'] ];
            $itemCode[$key] = [ 'id'=> $value['appr_item_code'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'name'=> $value['appr_item_code'] ];
            $itemColorCode[$key] = [ 'id'=> $value['appr_item_col_code'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'item_code_id'=> $value['appr_item_code'], 'name'=> $value['appr_item_col_code'] ];
            $sizeDia[$key] = [ 'id'=> $value['size_dim'], 'item_id'=> $value['appr_item_code'], 'name'=> $value['size_dim'] ];
            $uom[$key] = [ 'id'=> $value['uom'], 'item_id'=> $value['appr_item_code'], 'name'=> $value['uom'] ];
            $req_bom_id[$key] = [ 'id'=> $value['request_bom_id'], 'item_id'=> $value['appr_item_code'], 'name'=> $value['request_bom_id'] ];
            $piRefNo[$key] = [ 'id'=> $value['pi_ref_queue_no'], 'item_id'=> $value['item_desc'], 'size_id'=> $value['garment_size'], 'item_code_id'=> $value['appr_item_code'], 'color_id'=> $value['appr_item_col_code'], 'dia_id'=> $value['size_dim'], 'uom_id'=> $value['uom'], 'name'=> $value['pi_ref_queue_no'] ];
        }
        
        foreach ($itemDescription as $key => $value){
        if(!in_array($value, $itemDescriptions))
                $itemDescriptions[$key]=$value;
        }
        
        $output['itemDescription'] = $itemDescriptions;
        $output['garmentSize'] = $garmentSize;
        $output['itemCode'] = $itemCode;
        $output['itemColorCode'] = $itemColorCode;
        $output['sizeDia'] = $sizeDia;
        $output['uom'] = $uom;
        $output['req_bom_id'] = $req_bom_id;
        $output['piRefNo'] = $piRefNo;
        return $output;
    }

    public function updateOrderStockDetailss($enqId, $reqId, $itemCode, $in_house_qty, $mi_issued_details, $shipment_order_details)
    {
        $itemCodes = "'" . $itemCode . "'";
        $k=1;
       // print_r($mi_issued_details);
        foreach ($mi_issued_details as $key => $value) {
            
            
            //$miissuedData["mi_ref_no"] = $value[3];
            // $miissuedData["issued_to"] = $value[5];
            // $miissuedData["dc_no"] = $value[6];
            // $miissuedData["dc_dt"] = $value[7];
             //$miissuedData["lot_no"] = $value[8];
            // $miissuedData["rate"] = $value[9];
            // $miissuedData["issued_qty"] = $value[10];
            $miissuedData["enquiryId"] = $enqId;
            $miissuedData["return_defective_qty"] = $value[11];
            $miissuedData["replace_defective_qty"] = $value[12];
            $miissuedData["return_excess_qty"] = $value[13];
            // $miissuedData["uom"] = $value[14];
            $miissuedData["log"] = LOGTIME;
            
            if($value[1] == "")
            {
                
                $sam_val = $this->db->where('mi_bom_details_id',$value[4])->get('tbl_mi_bom_details')->row()->sample_no;
    
                $miissuedData["mi_serial_no"] = $sam_val;
                $miissuedData["mi_bom_details_id"] = $value[4];
                
            
                $this->db->insert('tbl_mi_issued_details', $miissuedData);
            }
            else {
                 $this->db->where('mi_issue_id', $value[1]);
                 $this->db->update('tbl_mi_issued_details', $miissuedData);
            }
        }

    }
    
     public function updateDraftStockDetailss($enqId, $reqId, $mi_issued_details, $bom_ref_no, $bom_dept,$draf_id)
    {
        
        foreach ($mi_issued_details as $key => $value) {
            
            
            $miissuedData["mi_ref_no"] = $bom_ref_no;
            $miissuedData["issued_to"] = $bom_dept;
            $miissuedData["issued_qty"] = $value[13];
            $miissuedData["lot_no"] = $value[11];
            $miissuedData["rate"] = $value[12];
            $miissuedData["mi_pending_qty"] = $value[9];
            $miissuedData["uom"] = $value[14];
            $miissuedData["log"] = LOGTIME;
            $miissuedData["draf_id"] =$draf_id;
            
            if($value[1] == '' &&  $value[0] == '' )
            {
                $sam_val = $this->db->where('mi_bom_details_id',$value[2])->get('tbl_mi_bom_details')->row()->sample_no;
    
                $miissuedData["mi_serial_no"] = $sam_val;
                $miissuedData["mi_bom_details_id"] = $value[2];
                
            
                $this->db->insert('tbl_mi_issued_details', $miissuedData);
            }
            else {
                 $this->db->where('mi_issue_id', $value[1]);
                 $this->db->update('tbl_mi_issued_details', $miissuedData);
            }
        }

    }

    public function updateSaveStockDetailss_oldd($enqId, $reqId, $mi_issued_details, $bom_ref_no, $bom_dept, $received_by, $draf_id)
{
    // ---- Index mapping: adjust these two if needed ----
    $IDX_MI_BOM_DETAILS_ID = 2;  // where you read mi_bom_details_id from $value[*]
    $IDX_MI_ISSUE_ID       = 15; // where you read existing mi_issue_id from $value[*]
    // ---------------------------------------------------

    $now = $this->mysqldatetime;

    $this->db->trans_start();

    // 1) INSERT new issued rows
    foreach ($mi_issued_details as $key => $value) {

        // Always reset per row
        $miissuedData = [];

        $miissuedData['mi_ref_no']       = $bom_ref_no;
        $miissuedData['issued_to']       = $bom_dept;
        $miissuedData['issued_qty']      = isset($value[13]) ? $value[13] : 0;
        $miissuedData['lot_no']          = isset($value[11]) ? $value[11] : null;
        $miissuedData['rate']            = isset($value[12]) ? $value[12] : 0;
        $miissuedData['mi_pending_qty']  = isset($value[9])  ? $value[9]  : 0;
        $miissuedData['uom']             = isset($value[14]) ? $value[14] : null;
        $miissuedData['log']             = LOGTIME;
        $miissuedData['draf_id']         = $draf_id;

        // safer emptiness check (handles '', null, 0, '  ')
        $noIssueId        = empty(trim((string)($value[$IDX_MI_ISSUE_ID] ?? '')));
        $noBomDetailsIdInPayload = empty(trim((string)($value[0] ?? ''))); // you had $value[0] in your condition; keep semantics

        if ($noIssueId && $noBomDetailsIdInPayload) {
            $mi_bom_details_id = $value[$IDX_MI_BOM_DETAILS_ID] ?? null;
            if (empty($mi_bom_details_id)) {
                // nothing to insert for this row
                continue;
            }

            // fetch sample_no safely
            $sam_row = $this->db->select('sample_no')
                                ->from('tbl_mi_bom_details')
                                ->where('mi_bom_details_id', $mi_bom_details_id)
                                ->get()->row();

            if (!$sam_row) {
                // missing FK/row → skip to avoid constraint errors
                log_message('error', "updateSaveStockDetailss: bom_details not found for id={$mi_bom_details_id}");
                continue;
            }

            $miissuedData['mi_serial_no']      = $sam_row->sample_no;
            $miissuedData['mi_bom_details_id'] = $mi_bom_details_id;

            $this->db->insert('tbl_mi_issued_details', $miissuedData);

            // Check DB error (helps catch intermittent failures)
            $dbErr = $this->db->error();
            if (!empty($dbErr['code'])) {
                log_message('error', "updateSaveStockDetailss INSERT error: code={$dbErr['code']} msg={$dbErr['message']}");
                // You can throw/rollback if you want hard guarantees:
                // $this->db->trans_rollback(); return false;
            }
        } else {
            // keep your update branch commented, if not needed yet
            // $this->db->where('mi_issue_id', $value[$IDX_MI_ISSUE_ID])->update('tbl_mi_issued_details', $miissuedData);
        }
    }

    // 2) Get ref_queue_no
    $req_data = $this->db->select('ref_queue_no')->from('tbl_request')->where('request_id', $reqId)->get()->row_array();
    $ref_queue_base = $req_data ? $req_data['ref_queue_no'] : '';

    // 3) Robust next queue number
    $cad_mi_data = $this->db->query("SELECT COALESCE(MAX(dc_queue_no),0)+1 AS last_queue_no FROM tbl_mi_bom_details")->row_array();
    $queue_no = (int)($cad_mi_data['last_queue_no'] ?? 1);
    if ($queue_no <= 0) { $queue_no = 1; }

    $ref_queue_no = $ref_queue_base . "/BDC-" . $queue_no;

    // 4) Update mi_details
    $this->db->where('request_id', $reqId)->update('tbl_mi_details', [
        'dc_bom_status' => 1,
        'req_bom_id'    => $reqId
    ]);

    // 5) Update bom_details (use the SAME index as for insert: $IDX_MI_BOM_DETAILS_ID)
    foreach ($mi_issued_details as $key1 => $value1) {
        $mi_bom_details_id = $value1[$IDX_MI_BOM_DETAILS_ID] ?? null;
        if (empty($mi_bom_details_id)) continue;

        $updateValue = [
            'dc_status'       => 0,
            'issue_date'      => $now,
            'issue_by'        => $this->userid,
            'dc_queue_no'     => $queue_no,
            'dc_ref_queue_no' => $ref_queue_no,
            'dc_dt'           => $now,
            'received_name'   => $received_by,
        ];

        $this->db->where('mi_bom_details_id', $mi_bom_details_id)
                 ->update('tbl_mi_bom_details', $updateValue);
    }

    // 6) Mark issued_details as DC-ed (only rows not yet DC-ed)
    foreach ($mi_issued_details as $row) {
        $mi_issue_id = $row[$IDX_MI_ISSUE_ID] ?? null;
        if (empty($mi_issue_id)) continue;

        $issuse_data = [
            'dc_no'         => $ref_queue_no,
            'dc_dt'         => $now,
            'received_name' => $received_by,
            'dc_status'     => 1,
            'log'           => LOGTIME,
        ];

        $this->db->where('mi_issue_id', $mi_issue_id)
                 ->where('dc_status', 0)
                 ->update('tbl_mi_issued_details', $issuse_data);
    }

    $this->db->trans_complete();
    return $this->db->trans_status();
}

    
     public function updateSaveStockDetailss_WORKING($enqId, $reqId, $mi_issued_details, $bom_ref_no, $bom_dept, $received_by, $draf_id)
    {
        //echo "<pre>"; print_r($mi_issued_details); exit;
        foreach ($mi_issued_details as $key => $value) {
            
            
            $miissuedData["mi_ref_no"] = $bom_ref_no;
            $miissuedData["issued_to"] = $bom_dept;
            $miissuedData["issued_qty"] = $value[13];
            $miissuedData["lot_no"] = $value[11];
            $miissuedData["rate"] = $value[12];
            $miissuedData["mi_pending_qty"] = $value[9];
            $miissuedData["uom"] = $value[14];
            $miissuedData["log"] = LOGTIME;
            $miissuedData["draf_id"] =$draf_id;
            //  print_r($miissuedData); exit;
            if($value[1] == '' &&  $value[0] == '' )
            {
                $sam_val = $this->db->where('mi_bom_details_id',$value[2])->get('tbl_mi_bom_details')->row()->sample_no;
    
                $miissuedData["mi_serial_no"] = $sam_val;
                $miissuedData["mi_bom_details_id"] = $value[2];
                
            
                $this->db->insert('tbl_mi_issued_details', $miissuedData);

                 $bomid=$value[2];
           
            }
            else {
                // $this->db->where('mi_issue_id', $value[1]);
                // $this->db->update('tbl_mi_issued_details', $miissuedData);
            }
            
        }
        
        $req_sql = "SELECT ref_queue_no FROM tbl_request WHERE request_id = ".$reqId;
        $req_data = $this->db->query($req_sql)->result_array();
        
        $cad_mi_sql = "SELECT MAX(dc_queue_no)+1 as last_queue_no FROM tbl_mi_bom_details";
        $cad_mi_data = $this->db->query($cad_mi_sql)->result_array();

        $ord_sql = "SELECT reqforisrior FROM kn_order_enquiry WHERE id=$enqId";
        $ord_data = $this->db->query($ord_sql)->result_array();
        $reqforisrior = $ord_data[0]['reqforisrior'];
        $ArrIsrIor   = unserialize(ARRISRIOR);

        $queue_no = $cad_mi_data[0]['last_queue_no'];
        if($queue_no == "") { $queue_no = 1; }

        $ref_queue_no = $req_data[0]['ref_queue_no']."/BDC-".$queue_no;
        
        
        $this->db->where('request_id', $reqId);
        $this->db->update('tbl_mi_details', array( 'dc_bom_status'=> 1, 'req_bom_id' => $reqId ));

        foreach ($mi_issued_details as $key1 => $value1) {
           
            // $bomid=$value1[0];
            // $issued_qty=$value1[13];
            // if($bomid != '') {
            //     $req_sql1 = "SELECT issue_qty,ind_qty FROM tbl_mi_bom_details WHERE mi_bom_details_id=".$bomid;
            //     $req_data1 = $this->db->query($req_sql1)->result_array();
            //     $balence_qty = $req_data1[0]['issue_qty'];
            //     $ind_qty = $req_data1[1]['ind_qty'];
            //     if($balence_qty!='') {
            //          $pending_qty = $balence_qty-$issued_qty;
            //     }else{
            //        if($ind_qty==$issued_qty) {
            //         $pending_qty = 0;
            //         }else{
            //         $pending_qty = $issued_qty;
            //         }
                    
            //     }
               


            // }else{
            //     $pending_qty = '';
            // }

            $bomid       = $value1[0];
         $issued_qty  = (float) $value1[13];

if (!empty($bomid)) {
    $req_sql1  = "SELECT issue_qty, ind_qty 
                  FROM tbl_mi_bom_details 
                  WHERE mi_bom_details_id = ".$bomid;
    $req_data1 = $this->db->query($req_sql1)->result_array();

    if (!empty($req_data1)) {
        $balence_qty = (float) $req_data1[0]['issue_qty'];
        $ind_qty     = (float) $req_data1[0]['ind_qty'];

        if ($balence_qty != 0) {
            $pending_qty = $balence_qty - $issued_qty;
        } else {
            if ($ind_qty == $issued_qty) {
                $pending_qty = 0;
            } else {
                $pending_qty = $ind_qty-$issued_qty;
            }
        }
    } else {
        // no row found
        $pending_qty = null;
    }
} else {
    $pending_qty = null;
}
             
             //print_r($ind_qty);
             //die;
                $updateValue['dc_status'] = 0;
                $updateValue['issue_date'] = $this->mysqldatetime;
                $updateValue['issue_by'] = $this->userid;
                $updateValue['dc_queue_no'] = $queue_no;
                $updateValue['dc_ref_queue_no'] = $ref_queue_no;
                $updateValue['dc_dt'] = $this->mysqldatetime;
                $updateValue['received_name'] = $received_by;
                $updateValue['issue_qty'] = $pending_qty;
                $updateValue["draft_id"] =$draf_id;
                 //$updateValue['issue_qty'] = 5;
                
                $this->db->where('mi_bom_details_id', $value1[0]);
                $this->db->update('tbl_mi_bom_details', $updateValue);
                //echo $this->db->last_query();
                
                $issuse_data['dc_no'] = $ref_queue_no;
                $issuse_data['dc_dt'] = $this->mysqldatetime;
                $issuse_data['received_name'] = $received_by;
                $issuse_data['dc_status'] = 1;
                
                $issuse_data['log'] = LOGTIME;
                
                $this->db->where('mi_issue_id', $value1[15])->where('dc_status',0);
                $this->db->update('tbl_mi_issued_details', $issuse_data);
                //echo $this->db->last_query();

                
            //}
        }
        

    }


     public function updateSaveStockDetailss($enqId, $reqId, $mi_issued_details, $bom_ref_no, $bom_dept, $received_by, $draf_id)
    {
        //echo "<pre>"; print_r($mi_issued_details); exit;
        foreach ($mi_issued_details as $key => $value) {
            
            
            $miissuedData["mi_ref_no"] = $bom_ref_no;
            $miissuedData["issued_to"] = $bom_dept;
            $miissuedData["issued_qty"] = $value[13];
            $miissuedData["lot_no"] = $value[11];
            $miissuedData["rate"] = $value[12];
            $miissuedData["mi_pending_qty"] = $value[9];
            $miissuedData["uom"] = $value[14];
            $miissuedData["log"] = LOGTIME;
            $miissuedData["draf_id"] =$draf_id;
            $miissuedData['received_name'] = $received_by;
            $miissuedData['enquiryId'] = $enqId;
            //  print_r($miissuedData); exit;
            if($value[1] == '' &&  $value[0] == '' )
            {
                $sam_val = $this->db->where('mi_bom_details_id',$value[2])->get('tbl_mi_bom_details')->row()->sample_no;
    
                $miissuedData["mi_serial_no"] = $sam_val;
                $miissuedData["mi_bom_details_id"] = $value[2];
               
                
            
                $this->db->insert('tbl_mi_issued_details', $miissuedData);

                 $updateValue1["dc_status_save"] = 0;

                 $this->db->where('mi_bom_details_id', $value[2]);

                 $this->db->update('tbl_mi_bom_details', $updateValue1);

                 $bomid=$value[2];
           
            }
            else {
                // $this->db->where('mi_issue_id', $value[1]);
                // $this->db->update('tbl_mi_issued_details', $miissuedData);
            }
            
        }
        
        $req_sql = "SELECT ref_queue_no FROM tbl_request WHERE request_id = ".$reqId;
        $req_data = $this->db->query($req_sql)->result_array();
        
        $cad_mi_sql = "SELECT MAX(dc_queue_no)+1 as last_queue_no FROM tbl_mi_bom_details";
        $cad_mi_data = $this->db->query($cad_mi_sql)->result_array();

        $ord_sql = "SELECT reqforisrior FROM kn_order_enquiry WHERE id=$enqId";
        $ord_data = $this->db->query($ord_sql)->result_array();
        $reqforisrior = $ord_data[0]['reqforisrior'];
        $ArrIsrIor   = unserialize(ARRISRIOR);

        $queue_no = $cad_mi_data[0]['last_queue_no'];
        if($queue_no == "") { $queue_no = 1; }

        $ref_queue_no = $req_data[0]['ref_queue_no']."/BDC-".$queue_no;
        
        
        $this->db->where('request_id', $reqId);
        $this->db->update('tbl_mi_details', array( 'dc_bom_status'=> 1, 'req_bom_id' => $reqId ));

        foreach ($mi_issued_details as $key1 => $value1) {
           
           

         
            
                //$updateValue['dc_status'] = 0;
                $updateValue['issue_date'] = $this->mysqldatetime;
                $updateValue['received_name'] = $received_by;
                $updateValue["draft_id"] =$draf_id;
                $updateValue["dc_status_save"] = 0;
                $this->db->where('mi_bom_details_id', $value1[0]);
                $this->db->update('tbl_mi_bom_details', $updateValue);
              
                //$issuse_data['received_name'] = $received_by;
                $issuse_data["issued_qty"] = $value1[13];
              
                $issuse_data["lot_no"] = $value1[11];
                $issuse_data["rate"] = $value1[12];
                $issuse_data["mi_pending_qty"] = $value1[9];
                $issuse_data["uom"] = $value1[14];
                $issuse_data["log"] = LOGTIME;
                $issuse_data["draf_id"] =$draf_id;
                //$issuse_data['received_name'] = $received_by;
                $issuse_data['received_name'] = $received_by;
                
                $issuse_data['log'] = LOGTIME;
                
                $this->db->where('mi_issue_id', $value1[15])->where('dc_status',0);
                $this->db->update('tbl_mi_issued_details', $issuse_data);
                //echo $this->db->last_query();

                
            //}
        }
        

    }

    public function updateSaveStockDetailsdc($enqId, $reqId, $mi_issued_details, $bom_ref_no, $bom_dept, $received_by, $draf_id)
    {
        foreach ($mi_issued_details as $key => $value) {
            
            
            $miissuedData["mi_ref_no"] = $bom_ref_no;
            $miissuedData["issued_to"] = $bom_dept;
            $miissuedData["issued_qty"] = $value[13];
            $miissuedData["lot_no"] = $value[11];
            $miissuedData["rate"] = $value[12];
            $miissuedData["mi_pending_qty"] = $value[9];
            $miissuedData["uom"] = $value[14];
            $miissuedData["log"] = LOGTIME;
            $miissuedData["draf_id"] =$draf_id;
            //  print_r($miissuedData); exit;
            if($value[1] == '' &&  $value[0] == '' )
            {
                $sam_val = $this->db->where('mi_bom_details_id',$value[2])->get('tbl_mi_bom_details')->row()->sample_no;
    
                $miissuedData["mi_serial_no"] = $sam_val;
                $miissuedData["mi_bom_details_id"] = $value[2];
                
            
                $this->db->insert('tbl_mi_issued_details', $miissuedData);

                 $bomid=$value[2];
           
            }
            else {
                // $this->db->where('mi_issue_id', $value[1]);
                // $this->db->update('tbl_mi_issued_details', $miissuedData);
            }
            
        }
        
        $req_sql = "SELECT ref_queue_no FROM tbl_request WHERE request_id = ".$reqId;
        $req_data = $this->db->query($req_sql)->result_array();
        
        $cad_mi_sql = "SELECT MAX(dc_queue_no)+1 as last_queue_no FROM tbl_mi_bom_details";
        $cad_mi_data = $this->db->query($cad_mi_sql)->result_array();

        $ord_sql = "SELECT reqforisrior FROM kn_order_enquiry WHERE id=$enqId";
        $ord_data = $this->db->query($ord_sql)->result_array();
        $reqforisrior = $ord_data[0]['reqforisrior'];
        $ArrIsrIor   = unserialize(ARRISRIOR);

        $queue_no = $cad_mi_data[0]['last_queue_no'];
        if($queue_no == "") { $queue_no = 1; }

        $ref_queue_no = $req_data[0]['ref_queue_no']."/BDC-".$queue_no;
        
        
        $this->db->where('request_id', $reqId);
        $this->db->update('tbl_mi_details', array( 'dc_bom_status'=> 1, 'req_bom_id' => $reqId ));

        foreach ($mi_issued_details as $key1 => $value1) {
           
           

//            
             
             //print_r($ind_qty);
             //die;
                $updateValue['dc_status'] = 0;
                $updateValue['issue_date'] = $this->mysqldatetime;
                $updateValue['issue_by'] = $this->userid;
                $updateValue['dc_queue_no'] = $queue_no;
                $updateValue['dc_ref_queue_no'] = $ref_queue_no;
                $updateValue['dc_dt'] = $this->mysqldatetime;
                $updateValue['received_name'] = $received_by;
                //$updateValue['issue_qty'] = $pending_qty;
                $updateValue["draft_id"] =$draf_id;
                 //$updateValue['issue_qty'] = 5;
                
                $this->db->where('mi_bom_details_id', $value1[0]);
                $this->db->update('tbl_mi_bom_details', $updateValue);
                //echo $this->db->last_query();
                
                $issuse_data['dc_no'] = $ref_queue_no;
                $issuse_data['dc_dt'] = $this->mysqldatetime;
                $issuse_data['received_name'] = $received_by;
                $issuse_data["issued_qty"] = $value[13];
                $issuse_data['dc_status'] = 1;
                
                $issuse_data['log'] = LOGTIME;
                
                $this->db->where('mi_issue_id', $value1[15])->where('dc_status',0);
                $this->db->update('tbl_mi_issued_details', $issuse_data);
                //echo $this->db->last_query();

                
            //}
        
        
        }

      
        

    }
    public function updateClearDraftDetailss($mi_issued_details, $draf_id)
    {
        foreach ($mi_issued_details as $key => $value) {
            
                //$this->db->where('mi_issue_id',$value[15])->delete('tbl_mi_issued_details');
                $this->db->where('mi_issue_id', $value[15])
         ->where('draf_id', $draf_id)   // new condition
         ->delete('tbl_mi_issued_details');
            
        }

    }
    
    public function updateOrderCloseDetailss($enqId, $reqId, $pId, $shipment_order_details, $itemDetails, $in_house_qty)
    {
        foreach ($itemDetails as $key => $value) {
            $request_bom_id = $value[1];
            $item_desc = $value[2];
            $bcm = $value[3];
            $garment_size = $value[4];
            $item_code = $value[5];
            $item_col_code = $value[6];
            $size_dim = $value[7];
            $uom = $value[8];
            $inHouse_id = $value[9];
        
        }
        $pi_no = $inv_no = $inv_date = $gst = [];
        $count = sizeof($shipment_order_details);
        $count1 = sizeof($in_house_qty);
        for($i=0;$i<$count;$i++) {
            if($shipment_order_details[$i][2] != '') {
                for($j=0;$j<$count1;$j++) {
                    if($shipment_order_details[$i][2] == $in_house_qty[$j][5]) {
                        $pi_no[] = $in_house_qty[$j][2];
                        $inv_no[] = $in_house_qty[$j][3];
                        $inv_date[] = $in_house_qty[$j][4];
                        $gst[] = $in_house_qty[$j][10];
                        $recd_date[] = $in_house_qty[$j][12];
                        $bin[] = $in_house_qty[$j][13];
                    }
                }
            }
            
        }
        //print_r($inv_no); print_r($inv_date); exit;
        
        foreach ($shipment_order_details as $key => $value) {
            $data['stock_date'] = $this->mysqldatetime;
            $data['enq_id'] = $enqId;
            $data['req_id'] = $reqId;
            $data['pId'] = $pId;
            $data['request_bom_id'] = $request_bom_id;
            $data['inHouse_id'] = $inHouse_id;
            $data['pi_ref_no'] = $pi_no[$key];
            $data['lot_no'] = $value[2];
            $data['inv_no'] = $inv_no[$key];
            $data['inv_date'] = $inv_date[$key];
            $data['item_desc'] = $item_desc;
            $data['bcm'] = $bcm;
            $data['garment_size'] = $garment_size;
            $data['item_code'] = $item_code;
            $data['item_col_code'] = $item_col_code;
            $data['size_dim'] = $size_dim;
            $data['uom'] = $uom;
            $data['surplus_qty'] = $value[9];
            $data['rate'] = $value[3];
            $data['gst'] = $gst[$key];
            $data['bin'] = $bin[$key];
            $data['order_stock_date'] = $recd_date[$key];
            $data['log'] = LOGTIME;
           // print_r($data); exit;
           if($value[14] == 1 && $value[15] == '' ) {
               if($value[9] > 0) {
                    $this->db->insert('tbl_surplus_stock_details',$data);
               }
            
                $where = array('item_desc' => $item_desc,'garment_size' => $garment_size,'appr_item_code' => $item_code,'appr_item_color_code' => $item_col_code,'size_dim' => $size_dim,'uom' => $uom,'batch_ref_no' => $value[2]);
                $this->db->where($where);
                $this->db->update('tbl_bom_in_house', array('supply_closed_status' => 1, 'enquiryId' => $enqId,'log'=>LOGTIME));
                $this->db->where('request_id',$reqId)->update('tbl_request',array('log'=>LOGTIME));
                $this->db->where('purchase_indent_id',$pId)->update('tbl_purchase_indent',array('supply_closed_status'=>1));
                
                //$this->db->where('lot_no',$value[2]);
                $this->db->where('lot_no',$inHouse_id);
                $this->db->update('tbl_mi_issued_details', array('supply_closed_status' => 1, 'supply_closed_date'=> $this->mysqldatetime, 'log'=>LOGTIME));    
           }
        }
        
        // foreach ($shipment_order_details as $key => $value) {
        //     if($value[14] == 1) {
        //         $this->db->where('lot_no',$value[2]);
        //         $this->db->update('tbl_mi_issued_details', array('supply_closed_status' => 1, 'supply_closed_date'=> $this->mysqldatetime, 'log'=>LOGTIME));    
        //     }
        // }
                 
    }
    
    /**
     * This function will update the status of the item in the tbl_mi_issued_details
     * table based on the status of the shipment order details.
     *
     * @param int $enqId - The enquiry id
     * @param int $reqId - The request id
     * @param int $pId - The purchase indent id
     * @param array $shipment_order_details - The shipment order details array
     * @param array $itemDetails - The item details array
     * @return void
     */
    public function updateStatusDetailss($enqId, $reqId, $pId, $shipment_order_details, $itemDetails)
    {
        
        foreach ($shipment_order_details as $key => $value) {
            if($value[14] == 1) {
                $this->db->where('lot_no',$value[2]);
                $this->db->update('tbl_mi_issued_details', array('supply_closed_status' => 1, 'supply_closed_date'=> $this->mysqldatetime, 'log'=>LOGTIME));    
            }
            
            
        }
                 
    }

    /**
     * This function will update the status of the item in the tbl_mi_issued_details
     * table based on the status of the shipment order details.
     *
     * @param int $enqId - The enquiry id
     * @param int $reqId - The request id
     * @param int $pId - The purchase indent id
     * @param array $shipment_order_details - The shipment order details array
     * @param array $itemDetails - The item details array
     * @return void
     */
     public function updateorderclosurelistt($enqId, $reqId)
    {
        
       
            if($enqId !='') {
                 $this->db->where('enquiryId',$enqId);
                 $this->db->update('tbl_mi_issued_details', array('supply_closed_status_moved' => 1 )); 
                 $this->db->where('enquiryId',$enqId);
                 $this->db->update('tbl_bom_in_house', array('supply_closed_status_moved' => 1 )); 
                 $this->db->where('enq_id',$enqId);
                 $this->db->update('tbl_surplus_stock_details', array('supply_closed_status_moved' => 1 ));    
            }
            
            
        
                 
    }

    public function getDCDetails($enqId, $reqId)
    {
        $sql = "SELECT a.*, b.*, b.contactname as sam_name
                from tbl_request a
                INNER JOIN ".KN_USERS." as b on a.cad_by=b.id 
                where enquiry_id='$enqId' and request_id='$reqId' and flag=1";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    public function getBOMDCData($enqId, $reqId)
    {
        $sql = "SELECT * from tbl_request 
                where enquiry_id='$enqId' and request_id='$reqId' and flag=1";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function getMIData($miId)
    {
        $sql = "SELECT * FROM tbl_mi_details as a WHERE a.mi_id = " . $miId;
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    public function getPIData($pId)
    {
        $sql = "SELECT * FROM tbl_purchase_indent as a WHERE a.purchase_indent_id = " . $pId;
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    // public function getdraftData($itemCode)
    // {
        
    //     $mi_sql = "SELECT * FROM tbl_mi_bom a 
    //                 INNER JOIN tbl_sample_requirement b ON a.sample_req_id = b.sample_requirement_id
    //                 INNER JOIN tbl_mi_bom_details c ON a.mi_bom_id = c.mi_bom_id
    //                 INNER JOIN tbl_mi_details d ON a.request_id = d.request_id
    //                 WHERE c.item_code = " . $itemCode . " ";
    //     $mi_data = $this->db->query($mi_sql)->result_array();
        
    //     foreach($mi_data as $key => $value) {
    //         $miId = $value['mi_id'];
    //     }
        
    //     $sql = "SELECT * FROM tbl_mi_details as a
    //             INNER JOIN tbl_mi_bom as b on a.request_id=b.request_id
    //             INNER JOIN tbl_mi_bom_details as c on b.mi_bom_id=c.mi_bom_id
    //             INNER JOIN tbl_mi_issued_details as d on c.mi_bom_details_id=d.mi_bom_details_id
    //             WHERE a.mi_id=".$miId." AND c.dc_status = 0 AND a.flag=1";
    //     $result = $this->db->query($sql)->result_array();
        
    //     return $mi_data;
    // }
    
    public function getdraftData($itemCode,$VarId)
    {
      
        $itemCode = "'".$itemCode."'";
         // print_r($VarId);
        $mi_issued_sql = "SELECT a.*,c.request_id FROM tbl_mi_issued_details a 
                    INNER JOIN tbl_mi_bom_details b ON a.mi_bom_details_id = b.mi_bom_details_id
                    INNER JOIN tbl_mi_bom c ON b.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_request d ON c.request_id = d.request_id
                    WHERE b.item_code = ".$itemCode." AND c.enquiry_id = ".$VarId."  AND a.dc_status=0 ";
        $mi_issued_data = $this->db->query($mi_issued_sql)->result_array();
        //print_r($mi_issued_sql); exit;
        return $mi_issued_data;
    }
    
    public function getMIReqData($itemCode,$VarId)
    {
        $itemCode = "'".$itemCode."'";
        $mi_issued_sql = "SELECT c.request_id FROM tbl_mi_issued_details a 
                    INNER JOIN tbl_mi_bom_details b ON a.mi_bom_details_id = b.mi_bom_details_id
                    INNER JOIN tbl_mi_bom c ON b.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_request d ON c.request_id = d.request_id
                    WHERE b.item_code = ".$itemCode." AND c.enquiry_id = ".$VarId." AND a.dc_status=0 ";
        $mi_issued_data = $this->db->query($mi_issued_sql)->result_array();
        //print_r($mi_issued_sql); exit;
        return $mi_issued_data;
    }
    
    public function getdraftMINo($itemCode)
    {
        $itemCode = "'".$itemCode."'";
        // $mi_issued_sql = "SELECT a.* FROM tbl_mi_issued_details a 
        //             INNER JOIN tbl_mi_bom_details b ON a.mi_bom_details_id = b.mi_bom_details_id
        //             INNER JOIN tbl_mi_bom c ON b.mi_bom_id = c.mi_bom_id
        //             INNER JOIN tbl_request d ON c.request_id = d.request_id
        //             WHERE b.item_code = ".$itemCode." AND a.dc_status=0 GROUP BY mi_ref_no ";
                    $mi_issued_sql = "SELECT a.* FROM tbl_mi_issued_details a 
                    INNER JOIN tbl_mi_bom_details b ON a.mi_bom_details_id = b.mi_bom_details_id
                    INNER JOIN tbl_mi_bom c ON b.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_request d ON c.request_id = d.request_id
                    WHERE b.item_code = ".$itemCode." AND a.dc_status=0 GROUP BY mi_ref_no ";
        @$mi_issued_data = $this->db->query($mi_issued_sql)->row()->mi_ref_no;
        //print_r($mi_issued_sql); exit;
        return $mi_issued_data;
    }
    
    public function getBOMMIData($miId, $dc)
    {
        $sql = 'SELECT a.*,b.*,c.*,e.ref_queue_no,d.contactname as issued_name FROM tbl_mi_details as a 
                INNER JOIN tbl_mi_bom as b ON a.request_id=b.request_id
                INNER JOIN tbl_mi_bom_details as c ON b.mi_bom_id=c.mi_bom_id
                INNER JOIN '.kn_users.' as d on c.issue_by=d.id
                INNER JOIN tbl_request e on a.req_bom_id = e.request_id
                WHERE c.dc_ref_queue_no = '.$dc.' GROUP BY c.dc_ref_queue_no';
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
   
    
    public function getMIReceivedData($miId, $dc)
    {
        $sql = 'SELECT * FROM tbl_mi_issued_details WHERE dc_no = '.$dc.' GROUP BY dc_no';
        $data = $this->db->query($sql)->result_array();
        return $data;
        
    }
    
    public function getDCListt($id, $reqId, $miId)
    {
        
        // $sql = "SELECT * FROM tbl_mi_details as a
        //         INNER JOIN tbl_mi_bom as b on a.request_id=b.request_id
        //         INNER JOIN tbl_mi_bom_details as c on b.mi_bom_id=c.mi_bom_id
        //         INNER JOIN tbl_mi_issued_details as d on c.mi_bom_details_id=d.mi_bom_details_id
        //         WHERE a.mi_id=".$miId." AND c.dc_status = 0 AND a.flag=1";
        
        $sql = "SELECT * FROM tbl_mi_issued_details as a
                INNER JOIN tbl_mi_bom_details as b on a.mi_bom_details_id=b.mi_bom_details_id
                INNER JOIN tbl_mi_bom as c on b.mi_bom_id=c.mi_bom_id
                INNER JOIN tbl_mi_details as d on c.request_id=d.request_id
                WHERE a.dc_status = 0 AND c.request_id = ".$reqId." AND a.flag=1";
        $result = $this->db->query($sql)->result_array();
        //print_r($sql); exit;

        $bomMIData = [];

        foreach ($result as $key => $value) {
            $bomMIData[$key] = [
                $value['mi_bom_details_id'], true, $value['mi_serial_no'], $value['item_desc'], $value['bcm'], $value['gar_size'], $value['item_code'],
                $value['item_color_code'], $value['size_dim'], $value['ind_uom'], $value['issued_qty'], $value['ind_uom']
            ];
        }

        // *** get garment size *** //
        $sizeChart  = $this->getSizeChart($id);
        $sizeMaster = $this->getSizeMasterDropdown($sizeChart);

        $data['bomMIData'] = $bomMIData;
        $data['sizeData'] = $sizeMaster;
        return $data;
    }

    public function updateDCListt($id, $reqId, $miId, $data, $received_by, $mi_type)
    {

        $req_sql = "SELECT ref_queue_no FROM tbl_request WHERE request_id = ".$reqId;
        $req_data = $this->db->query($req_sql)->result_array();
        
        $req_id = $this->db->where('mi_id',$miId)->get('tbl_mi_details')->row()->request_id;

        $cad_mi_sql = "SELECT MAX(dc_queue_no)+1 as last_queue_no FROM tbl_mi_bom_details";
        $cad_mi_data = $this->db->query($cad_mi_sql)->result_array();

        $ord_sql = "SELECT reqforisrior FROM kn_order_enquiry WHERE id=$id";
        $ord_data = $this->db->query($ord_sql)->result_array();
        $reqforisrior = $ord_data[0]['reqforisrior'];
        $ArrIsrIor   = unserialize(ARRISRIOR);

        $queue_no = $cad_mi_data[0]['last_queue_no'];
        if($queue_no == "") { $queue_no = 1; }

        $ref_queue_no = $req_data[0]['ref_queue_no']."/BDC-".$queue_no;

        // $sql = "SELECT * FROM tbl_mi_bom_details WHERE request_id = " . $req_id . " AND dc_status = 1";
        // $bomMIData = $this->db->query($sql)->result_array();

        foreach ($data as $key => $value) {
            if($value[1] == false) {
                $this->db->where('mi_bom_details_id',$value[0])->where('dc_status',0)->delete('tbl_mi_issued_details');
                unset($data[$key]);
                
            }
        }

        $data = array_values($data);

        // if(sizeof($data) == sizeof($bomMIData))
        // {
        //     $this->db->where('request_id', $reqId);
        //     $this->db->update('tbl_mi_details', array( 'dc_bom_status'=> 1 ));
        // }
        
        $this->db->where('request_id', $reqId);
        $this->db->update('tbl_mi_details', array( 'dc_bom_status'=> 1, 'req_bom_id' => $reqId ));

        foreach ($data as $key => $value) {
            if($value[1] == true)
            {
                $updateValue['dc_status'] = 0;
                $updateValue['issue_date'] = $this->mysqldatetime;
                $updateValue['issue_by'] = $this->userid;
                $updateValue['dc_queue_no'] = $queue_no;
                $updateValue['dc_ref_queue_no'] = $ref_queue_no;
                $updateValue['dc_dt'] = $this->mysqldatetime;
                $updateValue['received_name'] = $received_by;
                //$updateValue['mi_type'] = $mi_type;
                
                $this->db->where('mi_bom_details_id', $value[0]);
                $this->db->update('tbl_mi_bom_details', $updateValue);
                
                $issuse_data['dc_no'] = $ref_queue_no;
                $issuse_data['dc_dt'] = $this->mysqldatetime;
                $issuse_data['received_name'] = $received_by;
                $issuse_data['dc_status'] = 1;
                $issuse_data['log'] = LOGTIME;
                
                $this->db->where('mi_bom_details_id', $value[0])->where('dc_status',0);
                $this->db->update('tbl_mi_issued_details', $issuse_data);
                
                
            }
        }
        if($this->db->affected_rows() > 0)
        {
            $result['status'] = "success";
        }
        else {
            $result['status'] = "failure";
        }
        return $result;
    }


    public function get_draft_valuee($enq_id)
    {
        $tot = 0;
        $tot = $this->db->where('enquiry_id',$enq_id)->where('req_draft_status',1)->where('req_sent_status',0)->get('tbl_bom_article_1_req_consld')->num_rows();
        return $tot;
    }
    
    public function get_req_datass($enq_id)
    {
        $tot = 0;
        $tot = $this->db->where('enquiry_id',$enq_id)->where('purchase_req_type','BULK')->get('tbl_request')->num_rows();
        return $tot;
    }
    
    public function get_draft_typee($enq_id)
    {
        $arr1 = [];
        $res = [];
        $tot = 0;
        $type = '';
        $sql = $this->db->where('enquiry_id',$enq_id)->where('req_draft_status',1)->where('req_sent_status',0)->get('tbl_bom_article_1_req_consld')->result_array();
        $tot = count($sql);
        foreach($sql as $r) {
            $res[] = $r['request_id'];
        }
        
        if(count($res) > 0) {
        $arr1 = array_unique($res);
        if(count($arr1) > 0) {
            if($arr1[0] != 0) {
                $type = $this->db->where('request_id',$arr1[0])->get('tbl_request')->row()->purchase_req_type;    
            }
        }
        }
        
        $data['total'] = $tot;
        $data['type'] = $type;
        return $data;
    }

    public function clearPurchaseRequest( $id) {
        $enquiry_id = $id;

        $req_data = $this->db->where('enquiry_id',$enquiry_id)->where('req_draft_status',1)->get('tbl_bom_article_1_req_consld')->result_array();
        foreach($req_data as $r) {
            $this->db->where('request_id',$r['request_id'])->delete('tbl_request');
        }
        $res = $this->db->where('enquiry_id',$enquiry_id)->where('req_draft_status',1)->update('tbl_bom_article_1_req_consld',array('req_draft_status'=>0,'request_id'=>0,'draft_consl_bom_qty'=>'','draft_excess_qty'=>'','draft_plan_bom_qty'=>''));

        if($res) {
            $result['status'] = 'Success';
        } else {
            $result['status'] = 'Fail';
        }

        return $result;

    }
    
    public function get_pi_draft_valuee($req_id)
    {
        $tot = 0;
        $tot = $this->db->where('request_id',$req_id)->where('pi_draft_status',1)->get('tbl_request_bom')->num_rows();
        return $tot;
    }
    
    public function clearPiDraftt( $id) {

        $pId = $this->db->where('request_id',$id)->where('pi_draft_status',1)->get('tbl_purchase_indent')->row()->purchase_indent_id;
        $request_data = $this->db->where('request_id',$id)->where('pi_draft_status',1)->get('tbl_request_bom')->result_array();
        if(count($request_data) > 0); {
            for($i=0;$i<count($request_data);$i++) {
                $this->db->where('request_bom_id',$request_data[$i]['request_bom_id'])->delete('tbl_request_purchase_indent');
            }
        }

        $this->db->where('purchase_indent_id',$pId)->delete('tbl_request_status');
        $this->db->where('purchase_indent_id',$pId)->delete('tbl_request_payment');
        $this->db->where('request_id',$id)->delete('tbl_wip_files');
        $this->db->where('purchase_indent_id',$pId)->delete('tbl_purchase_indent');
        //$this->db->where('request_id',$id)->where('pi_draft_status',1)->update('tbl_request',array('pi_draft_status'=>0));
        $res = $this->db->where('purchase_indent_id',$pId)->where('pi_draft_status',1)->update('tbl_request_bom',array('pi_draft_status'=>0));

        if($res) {
            $result['status'] = 'Success';
        } else {
            $result['status'] = 'Fail';
        }

        return $result;

    }
    
    public function getpurchaserequestImagess($data)
    {
        $result = $this->db->from('tbl_wip_files')->where('enquiry_id', $data['enquiry_id'])->where('request_id', $data['reqId'])->where('type', $data['type'])->get()->result_array();
        return $result;
    }
    
    public function getbomrequestImagess($data)
    {
        $result= '';
        if($data['reqId'] != 0) {
            $result = $this->db->from('tbl_wip_files')->where('enquiry_id', $data['enquiry_id'])->where('request_id', $data['reqId'])->where('type', $data['type'])->get()->result_array();
        }
        return $result;
    }
    // public function getbomrequestImagess($data)
    // {
    //     if($data['reqId'] != 0) {
    //         $sql = "SELECT a.*,c.item_desc,c.bcm,c.garment_size,c.appr_item_code,c.appr_item_col_code,c.size_dim,c.uom,c.plan_bom_qty,c.requirement_uom FROM tbl_purchase_indent a
    //             INNER JOIN tbl_request b on a.request_id=b.request_id
    //             INNER JOIN tbl_request_bom c on a.purchase_indent_id=c.purchase_indent_id
    //             WHERE a.purchase_indent_id = " . $pId . " ";
    //         $data = $this->db->query($sql)->result_array();
    //     }
    //     return $result;
    // }
    
    public function invoicebilll($data)
    {
        $company_id = $_SESSION['UI']['companyid']; 
        $company_data = $this->db->from('kn_company_details')->where('id', $company_id)->get()->result_array();
        if($data['pId'] != '') {
            $pId = $data['pId'];
          $sql = "SELECT a.*, b.ref_queue_no,e.isriorcode,g.contactname as pi_req_name, h.contactname as pi_appr_name FROM tbl_purchase_indent a
                INNER JOIN tbl_request b on a.request_id=b.request_id
                INNER JOIN tbl_request_bom c on a.purchase_indent_id=c.purchase_indent_id
                INNER JOIN kn_order_enquiry as e on a.enquiry_id=e.id
                INNER JOIN tbl_request_status f on a.purchase_indent_id=f.purchase_indent_id
                INNER JOIN ".KN_USERS." g on a.pi_req_by=g.id
                INNER JOIN ".KN_USERS." h on f.appr_by=h.id
                WHERE a.purchase_indent_id = " . $pId . " ";
            $pi_data = $this->db->query($sql)->result_array();
            
            $vendor_sql = "SELECT a.*,c.*,d.contactname FROM tbl_request_status as a
                        INNER JOIN tbl_request_payment b ON a.purchase_indent_id = b.purchase_indent_id
                        INNER JOIN kn_master_bom_vendor c ON b.vendor_id=c.id
                        INNER JOIN ".KN_USERS." d ON a.appr_by=d.id
                        WHERE b.purchase_indent_id = $pId ";
            $vendor_data = $this->db->query($vendor_sql)->result_array();
            
            $itemsql = "SELECT c.item_desc,c.bcm,c.garment_size,c.appr_item_code,c.appr_item_col_code,c.size_dim,c.uom as uoms,c.plan_bom_qty,c.requirement_uom,d.* FROM tbl_purchase_indent a
                INNER JOIN tbl_request b on a.request_id=b.request_id
                INNER JOIN tbl_request_bom c on a.purchase_indent_id=c.purchase_indent_id
                INNER JOIN tbl_request_purchase_indent d on c.request_bom_id=d.request_bom_id
                WHERE a.purchase_indent_id = " . $pId . " ";
            $item_data = $this->db->query($itemsql)->result_array();
        
            $result['pi_data'] = $pi_data;
            $result['item_data'] = $item_data;
            $result['company_data'] = $company_data;
            $result['vendor_data'] = $vendor_data;
        } else {
            $pi_data = '';
            $vendor_data = '';
            $item_data = '';
            $result['pi_data'] = $pi_data;
            $result['item_data'] = $item_data;
            $result['company_data'] = $company_data;
            $result['vendor_data'] = $vendor_data;
        }
        return $result;
    }
    
    public function getRCDataa($data)
    {
        $pId = $data['pId'];
        $row_id = $data['row_id'];
        $amount_payable = 0;
        $paid_amt = 0;
        $adAmt = 0;
        @$pay_amt = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_request_payment')->row();
        if($pay_amt) {
            $amount_payable = $pay_amt->amount_payable;
            $bank_name = $pay_amt->vendor_bank_name;
            $acc_no = $pay_amt->acc_no;
            $vendor_id = $pay_amt->vendor_id;
            $vendor_name = $this->db->where('id',$vendor_id)->get('kn_master_bom_vendor')->row()->vendorname;
            $currency = $pay_amt->currency;
        }
        @$inv_amt = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_payment_invoice')->row();
        if($inv_amt) {
            @$inv_data = $this->db->where('purchase_indent_id',$pId)->where('row_id != ',$row_id)->where('amount_payable <', 0)->get('tbl_payment_invoice')->result_array();
            if($inv_data) {
                foreach($inv_data as $key) {
                    $adAmt += $key['amount_payable'];
                }
            }
            $amount_payable = $inv_amt->amount_payable + $adAmt;
            $bank_name = $inv_amt->vendor_bankname;
            $acc_no = $inv_amt->vendor_accountno;
            $vendor_name = $inv_amt->vendor_name;
            $currency = $inv_amt->curency;
        }
        // @$inv_amt = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_payment_invoice')->row();
        // if($inv_amt) {
        //     $amount_payable = $inv_amt->amount_payable;
        //     $bank_name = $inv_amt->vendor_bankname;
        //     $acc_no = $inv_amt->vendor_accountno;
        //     $vendor_name = $inv_amt->vendor_name;
        //     $currency = $inv_amt->curency;
        // }
        @$others_amt = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_request_payment_others')->row();
        if($others_amt) {
            $amount_payable = $others_amt->amount_payable;
            $bank_name = $others_amt->bank_name;
            $acc_no = $others_amt->account_no;
            $vendor_name = $others_amt->pay_code;
            $currency = $others_amt->currency;
        }
        
        @$paid_amt1 = $this->db->select_sum('amount')->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_bom_payment_paid')->row()->amount;
        if($paid_amt1 > 0) {
            $paid_amt = $paid_amt1;
        }
        
        @$req_for = $this->db->where('purchase_indent_id',$pId)->where('row_id',$row_id)->get('tbl_request_status')->row()->payment_requirement;
        if($req_for) {
            $req_for = $req_for;
        } else {
            $req_for = '';
        }
        
        
        $datas['total_amt'] = @$amount_payable;
        $datas['paid_amt'] = @$paid_amt;
        $datas['bank_name'] = @$bank_name;
        $datas['acc_no'] = @$acc_no;
        $datas['vendor_name'] = @$vendor_name;
        $datas['req_for'] = @$req_for;
        $datas['currency'] = @$currency;
        
        return $datas;
    }
    
    public function updateBillClosedd($eId,$reqId,$pId)
    {
        $res = $this->db->where('purchase_indent_id',$pId)->update('tbl_purchase_indent',array('bill_paid_status'=>1,'log'=>LOGTIME));
        if($res) {
            $data['status'] = 'Success';
        } else {
            $data['status'] = 'Failed';
        }
        
        return $data;
        
    }
    
    public function getWithinSateDetailss($reqId) {
        
        $sql = "SELECT b.*,d.unit_rate,d.gst,d.gst_value,d.igst,d.igst_value,d.currency,d.sub_total,d.vendor_id FROM tbl_request_bom b
                LEFT JOIN tbl_request c on b.request_id=c.request_id
                LEFT JOIN tbl_purchase_indent e on b.purchase_indent_id=e.purchase_indent_id
                LEFT JOIN tbl_request_purchase_indent d on b.request_bom_id=d.request_bom_id AND b.plan_bom_qty = d.qty
                WHERE b.request_id = " . $reqId . " AND b.flag=1 AND b.pi_status=0 AND c.mgmt_approval = 1 AND c.deprt_approval = 1";
                
        $data = $this->db->query($sql)->result_array();

        
        $pi_sql = "SELECT * FROM tbl_purchase_indent
                WHERE request_id = " . $reqId . " AND pi_draft_status=1";
        $pi_data = $this->db->query($pi_sql)->result_array();

        @$pi_id = $this->db->where('request_id',$reqId)->where('pi_draft_status',1)->get('tbl_purchase_indent')->row()->purchase_indent_id;
        
        $vendor_sql = "SELECT *, vendorname as name FROM kn_master_bom_vendor";
        $vendor_data = $this->db->query($vendor_sql)->result_array();
        

        $withinResult = $interResult = $importsResult = [];
        
        $modeOfShipment = [ 'IMPS', 'NEFT', 'RTGS', 'SWIFT', 'CHEQUE', 'CASH' ];
        $currencyList = unserialize(ARRCURRENCYLIST);


        foreach ($data as $key => $value)
        {
            if($value['pi_draft_status'] == 1) {
                $status = 'true';
                $type = 'edit';
            } else {
                $status = 'false';
                $type = 'add';
            }
            $withinResult[$key] = [ $type, $value['request_bom_id'], $status, $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $value['unit_rate'],'', $value['gst'], $value['gst_value']
            ];

            $interResult[$key] = [ $type, $value['request_bom_id'], $status, $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $value['unit_rate'], '', $value['igst'], '', $value['igst_value'] 
            ];

            $importsResult[$key] = [ $type, $value['request_bom_id'], $status, $value['item_desc'], $value['bcm'],
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], $value['currency'], $value['unit_rate'], $value['sub_total']
            ];

        }

        $output['withinStateDetails'] = $withinResult;
        $output['interStateDetails'] = $interResult;
        $output['importStateDetails'] = $importsResult;
        $output['modeOfShipment'] = $modeOfShipment;
        $output['currencyList'] = $currencyList;
        $output['vendor_data'] = $vendor_data;
        
        return $output;
    }
    
    
    // *********** BOM STORE ******** //
    
    public function getBomStoreDetailss($enqId, $reqId, $pId) {
        // LEFT JOIN tbl_bom_in_house e on b.request_bom_id = e.request_bom_id
        $sql = "SELECT * FROM tbl_request_bom b
                LEFT JOIN tbl_request c on b.request_id=c.request_id
                LEFT JOIN kn_master_bom_vendor d on b.vendor_id=d.id
                LEFT JOIN tbl_bom_in_house e on b.request_bom_id = e.request_bom_id
                WHERE b.purchase_indent_id = " . $pId . " AND b.flag=1 AND c.mgmt_approval = 1 AND c.deprt_approval = 1 ";
        $data = $this->db->query($sql)->result_array();
        //print_r($data); exit;
        
        $pisql = "SELECT * FROM tbl_request_bom b
                LEFT JOIN tbl_request c on b.request_id=c.request_id
                LEFT JOIN kn_master_bom_vendor d on b.vendor_id=d.id
                LEFT JOIN tbl_purchase_indent e on b.request_id=e.request_id
                LEFT JOIN tbl_request_status f on e.purchase_indent_id = f.purchase_indent_id
                WHERE c.request_id = " . $reqId . " AND b.flag=1 AND c.mgmt_approval = 1 AND c.deprt_approval = 1 and f.type_of_mode='M' GROUP BY b.request_bom_id ";
        $pidata = $this->db->query($pisql)->result_array();
        
        $result = $att_result = $pidetails = $inhousestatusdetails = $itemacceptstatus = $inhouseconsolidatedqtydetails = $req_datas = $inhouse_data = $item_desc = $garment_size = $appr_item_code = $appr_item_col_code = $size_dim = $uom = []; 
        
        foreach ($data as $key => $value)
        {

            $vendor_name_address = $value['vendorname'] . ' / ' . $value['address'];

            $result[$key] = [ $value['request_bom_id'] ,  $value['item_desc'], $value['bcm'], $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                            $value['size_dim'], $value['uom'], $value['consl_bom_qty'], $value['excess_qty'], $value['plan_bom_qty'], 
                            $value['requirement_uom'] ];

            $att_result[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['appr_item_code'], $value['appr_item_col_code'],
                            $value['sourcing_advice'], $value['vendor_location'], $vendor_name_address, 
                            $value['contact_email'], $value['gst'], $value['online_order_sys'], $value['pass_expiry_date']
                        ];

            $inhousestatusdetails[$key] = [ '', '', $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], '', '', '', '', '', '', '', '', '', '' , '', '', '', '', '', '', '',  $value['request_bom_id']
                        ];
                            
        }
        
        foreach ($pidata as $key => $value) {
            if($value['pi_appl_status'] == 0) {
                $status = 'PENDING';
            } else if($value['pi_appl_status'] == 1) {
                $status = 'APPROVED';
            } else if($value['pi_appl_status'] == 2) {
                $status = 'DECLINED';
            } else if($value['pi_appl_status'] == 1) {
                $status = 'PENDING - RR';
            } else {
                $status = '-';
            }
            $pidetails[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], $value['pi_dt'], $value['pi_ref_queue_no'], $value['plan_bom_qty'], $value['requirement_uom'], $status, $value['appr_dt'], $value['exp_dod']
                        ];
        }
        
        // $sql = "SELECT b.*,c.plan_bom_qty FROM tbl_bom_in_house b
        //         LEFT JOIN tbl_request_bom c on b.request_bom_id = c.request_bom_id
        //         WHERE b.purchase_indent_id = " . $pId . " AND b.flag=1 ";
        // @$req_data = $this->db->query($sql)->result_array();

         
        $sql = "SELECT b.*,c.plan_bom_qty FROM tbl_bom_in_house b
                LEFT JOIN tbl_request_bom c on b.request_bom_id = c.request_bom_id
                WHERE b.purchase_indent_id = " . $pId . " AND b.flag=1 ";
        @$req_data = $this->db->query($sql)->result_array();

        //print_r($sql); exit;
        
        if($req_data) {
            // $k = 0;
            foreach ($req_data as $key => $value)
            {
                // print_r($value['merchant_item_status']); exit;
                if($value['merchant_item_status'] == 1 && $value['qa_status'] == 1 && $value['mgmt_ovrd_status'] == 0) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 1 && $value['qa_status'] == 2 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 2 && $value['qa_status'] == 1 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 2 && $value['qa_status'] == 2 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else {
                    $con_status = 'Failed';
                }
                
                if($value['mgmt_ovrd_status'] == 3 || $value['mgmt_ovrd_status'] == 2) {
                    $col_status = 'Yes';
                } else {
                    $col_status = 'No';
                }
                
                // if($value['order_stock_status'] == 1) {
                //     $check_status = true;
                // } else {
                //     $check_status = false;
                // }

                $inhouse_data[$key] = [ $value['bom_in_house_id'], $check_status, $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_color_code'], $value['size_dim'], $value['uom'], $value['dc_no'], $value['dc_date'], $value['batch_ref_no'], $value['dc_qty'], $value['invoice_no'], $value['invoice_date'], $value['invoice_qty'], $value['invoice_rate'], $value['currency'], $value['foreign_exch_rate'] , $value['invoice_value'], $value['gst'], $value['total_invoice_value'], $value['received_qty'], $value['received_uom'], $value['received_date'], $value['storage_bin'],  $value['request_bom_id'], $con_status, $check_status, $col_status, $value['mgmt_ovrd_status'],$value['supply_closed_status'],$value['order_stock_status']
                        ];
                $itemacceptstatus[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_color_code'], @$value['dc_no'], @$value['dc_date'], @$value['batch_ref_no'], @$value['dc_qty'], @$value['uom'], $value['merchant_item_status'], $value['merchant_appl_date_time'], 
                            @$value['qa_status'], @$value['qa_status_upt_dt'], @$value['mgmt_ovrd_status'], @$value['mgmt_status_upd_dt'],@$value['bom_in_house_id'],$con_status, $col_status
                        ];
                
            }
             
        } else {
             
              $inhouse_data[0] = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '','','' ];
             $itemacceptstatus[0] = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ];
             
        }
        $item = array();
        $items = array();
        
        $where = '((b.merchant_item_status = 1 and b.qa_status = 1 and b.mgmt_ovrd_status = 0) or (b.merchant_item_status = 1 and b.qa_status = 2 and b.mgmt_ovrd_status = 1) or (b.merchant_item_status=2 and b.qa_status = 1 and b.mgmt_ovrd_status = 1) or (b.merchant_item_status=2 and b.qa_status = 2 and b.mgmt_ovrd_status = 1) )';
        
        // $sql1 = "SELECT b.*, SUM(b.received_qty) as received_qtys, c.plan_bom_qty FROM tbl_bom_in_house b
        //         LEFT JOIN tbl_request_bom c on b.request_bom_id = c.request_bom_id
        //         WHERE b.purchase_indent_id = " . $pId . " AND $where AND b.flag=1 GROUP BY b.item_desc,b.garment_size,b.appr_item_code,appr_item_color_code,b.size_dim,b.uom ";
        // @$req_data1 = $this->db->query($sql1)->result_array();
        
        $sql2 = "SELECT c.*, b.order_stock_status as order_stock_status ,b.received_uom,b.supply_closure_status,b.supply_closure_date, SUM(b.received_qty) as received_qtys, c.plan_bom_qty FROM tbl_request_bom c
                LEFT JOIN tbl_bom_in_house b on c.request_bom_id = b.request_bom_id  AND $where
                LEFT JOIN tbl_request d on c.request_id=d.request_id
                WHERE c.purchase_indent_id = " . $pId . " AND c.flag=1 GROUP BY c.item_desc,c.garment_size,c.appr_item_code,c.appr_item_col_code,c.size_dim,c.uom ";
        @$req_data1 = $this->db->query($sql2)->result_array();
        
      
        $k = 0;
        if($req_data1) {
            foreach ($req_data1 as $key => $value)
            {
                $item_dec = $value['item_desc'];
                $garment_size = $value['garment_size'];
                $appr_item_code = $value['appr_item_code'];
                
                
                //$item[$item_dec][$garment_size][$appr_item_code][] = $value['received_qty'];
                
                if($value['merchant_item_status'] == 1 && $value['qa_status'] == 1 && $value['mgmt_ovrd_status'] == 0) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 1 && $value['qa_status'] == 2 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 2 && $value['qa_status'] == 1 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 2 && $value['qa_status'] == 2 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else {
                    $con_status = 'Failed';
                }
    
                
                // if($con_status === 'Consolidated') {
                //     $plan_bom_qty = $value['plan_bom_qty'];
                //     $received_qty = $value['received_qtys'];
                //     $received_uom = $value['received_uom'];
                // } else if($value['order_stock_status'] == 1) {
                //     $plan_bom_qty = $value['plan_bom_qty'];
                //     $received_qty = $value['received_qtys'];
                //     $received_uom = $value['received_uom'];
                // } else {
                //     $plan_bom_qty = 0;
                //     $received_qty = 0;
                //     $received_uom = '';
                // }
                
                    $plan_bom_qty = $value['plan_bom_qty'];
                    $received_qty = $value['received_qtys'];
                    $received_uom = $value['received_uom'];
                
                // if($con_status == 'Consolidated' || $value['order_stock_status'] == 1) {
                    $inhouseconsolidatedqtydetails[$k] = [ $value['bom_in_house_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], $plan_bom_qty, $received_qty, '', $received_uom, $value['supply_closure_status'], $value['supply_closure_date'], $con_status,$value['order_stock_status']
                        ];
                    $k++;
                // }
                
                
            }
             
        } else {
             
             $inhouseconsolidatedqtydetails[0] = [ '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        }
        
        //$where = '((b.merchant_item_status != 1 and b.qa_status != 1 and b.mgmt_ovrd_status != 0) or (b.merchant_item_status != 1 and b.qa_status != 2 and b.mgmt_ovrd_status != 1) or (b.merchant_item_status != 2 and b.qa_status != 1 and b.mgmt_ovrd_status != 1) or (b.merchant_item_status != 2 and b.qa_status != 2 and b.mgmt_ovrd_status != 1) )';
        
        $sql2 = "SELECT b.*, c.plan_bom_qty FROM tbl_bom_in_house b
                LEFT JOIN tbl_request_bom c on b.request_bom_id = c.request_bom_id
                WHERE b.purchase_indent_id = " . $pId . " AND b.flag=1 ";
        @$req_data2 = $this->db->query($sql2)->result_array();
       
        if($req_data2) {
            foreach($req_data2 as $key2 => $value) {
                
                
                if($value['merchant_item_status'] == 1 && $value['qa_status'] == 1 && $value['mgmt_ovrd_status'] == 0) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 1 && $value['qa_status'] == 2 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 2 && $value['qa_status'] == 1 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else if($value['merchant_item_status'] == 2 && $value['qa_status'] == 2 && $value['mgmt_ovrd_status'] == 1) {
                    $con_status = 'Consolidated';
                } else {
                    $con_status = 'Failed';
                }
    
                
                if($con_status === 'Consolidated') {
                    $plan_bom_qty = $value['plan_bom_qty'];
                    $received_qty = $value['received_qty'];
                    $received_uom = $value['received_uom'];
                } else if($value['order_stock_status'] == 1) {
                    $plan_bom_qty = $value['plan_bom_qty'];
                    $received_qty = $value['received_qty'];
                    $received_uom = $value['received_uom'];
                } else {
                    $plan_bom_qty = $value['plan_bom_qty'];
                    $received_qty = 0;
                    $received_uom = $value['received_uom'];
                }
                
            }
        }
        
        @$item_data = $this->db->where('purchase_indent_id',$pId)->get('tbl_request_bom')->result_array();
        if($item_data) {
            foreach($item_data as $key => $val) {
                $item_desc[$key] = $val['item_desc'];
                $garment_size[$key] = $val['garment_size'];
                $appr_item_code[$key] = $val['appr_item_code'];
                $appr_item_col_code[$key] = $val['appr_item_col_code'];
                $size_dim[$key] = $val['size_dim'];
                $uom[$key] = $val['uom'];
            }
        } else {
            $item_desc = '';
        }
        //print_r($item_data); exit;
        $req_sql = "SELECT a.*, b.contactname as auth_name FROM tbl_request as a LEFT JOIN ".KN_USERS." as b ON a.auth_by=b.id WHERE a.request_id='$reqId' ";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $UOM = unserialize(ARRUNITOFMEASURE);
        $currencyList = unserialize(ARRCURRENCYLIST);
        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }
        
        $bomMIDetails = $this->getBOMInHouseDetailss($enqId, $reqId, $pId);
        // print_r($currencyList);
        //$output['purchaserequest'] = $result;
        //$output['sourcedetails'] = $att_result;
        //$output['pidetails'] = $pidetails;
        $output['inhousestatusdetails'] = $inhouse_data;
        $output['itemacceptstatus'] = $itemacceptstatus;
        $output['inhouseconsolidatedqtydetails'] = $inhouseconsolidatedqtydetails;
        $output['uomData'] = $UOMDetails;
        //$output['req_data'] = $req_data;
        $output['item_desc'] = $item_desc;
        $output['garment_size'] = $garment_size;
        $output['appr_item_code'] = $appr_item_code;
        $output['appr_item_col_code'] = $appr_item_col_code;
        $output['size_dim'] = $size_dim;
        $output['uom'] = $uom;
        $output['currency'] = $currencyList;
        $output['bomAppendData'] = $bomMIDetails;
        return $output;
    }
    
    public function updateMerchantInHouseDetailss($data, $enqId, $reqId)
    {
        //print_r($data); exit;
        foreach($data as $key => $value) {
            if($value[9] != 0 && $value[10] == '') {
                $this->db->where('bom_in_house_id', $value[0]);
                $this->db->update('tbl_bom_in_house', array('merchant_item_status' => $value[9], "merchant_appl_date_time"=> $this->mysqldatetime,'log'=>LOGTIME));
                $this->db->where('request_id',$reqId)->update('tbl_request',array('log'=>LOGTIME));
            }
        }
    }
    
    public function updateStoreAcceptDetailss($itemAccept, $enqId, $reqId,$pId)
    {
        //print_r($itemAccept); exit;
        foreach($itemAccept as $key => $value) {
            if($value[12] != 0 && $value[13] == '') {
                $this->db->where('bom_in_house_id', $value[16]);
                $this->db->update('tbl_bom_in_house', array('qa_status' => $value[12], "qa_status_upt_dt"=> $this->mysqldatetime,'log'=>LOGTIME));
                $this->db->where('request_id',$reqId)->update('tbl_request',array('log'=>LOGTIME));
            }
        }
    }
    
    public function updateSupplyClosedDetailss($consolidated, $enqId, $reqId,$pId)
    {
        //print_r($consolidated); exit;
        foreach($consolidated as $key => $value) {
            $where = array('item_desc' => $value[1],'garment_size' => $value[2],'appr_item_code' => $value[3],'appr_item_color_code' => $value[4],'purchase_indent_id' => $pId);
             if($value[11] != 0 && $value[12] == '') {
                 $this->db->where($where);
                 $this->db->update('tbl_bom_in_house', array('supply_closure_status' => $value[11], "supply_closure_date"=> $this->mysqldatetime,'log'=>LOGTIME));
                 $this->db->where('request_id',$reqId)->update('tbl_request',array('log'=>LOGTIME));
             }
        }
    }
    
    public function updateSupplyClosureDetailss($consolidated, $inHouseStatus, $enqId, $reqId, $pId, $supply_status)
    {
        //print_r($consolidated); exit;
        foreach($consolidated as $key => $value) {
            $where = array('item_desc' => $value[1],'garment_size' => $value[2],'appr_item_code' => $value[3],'appr_item_color_code' => $value[4],'size_dim' => $value[5],'uom' => $value[6],'purchase_indent_id' => $pId);
            $this->db->where($where);
            $this->db->update('tbl_bom_in_house', array('supply_closed_status' => 1, 'log'=>LOGTIME));
            $this->db->where('request_id',$reqId)->update('tbl_request',array('log'=>LOGTIME));
            $this->db->where('purchase_indent_id',$pId)->update('tbl_purchase_indent',array('supply_closed_status'=>1, 'supply_status' => $supply_status,'log'=>LOGTIME));

        }
        
        foreach ($inHouseStatus as $key => $value) {
            
            $itemAcceptData['order_stock_status'] = 1;
            $itemAcceptData['log'] = LOGTIME;
            $this->db->where('bom_in_house_id', $value[0]);
            $this->db->update('tbl_bom_in_house', $itemAcceptData);
        }
    }
    
    public function getItemDataa($data)
    {
        $where = array('item_desc' => $data['item_desc'],'garment_size' => $data['size'],'appr_item_code' => $data['appr_item_code'],'appr_item_col_code' => $data['appr_item_col_code'],'purchase_indent_id' => $data['pId'],);
        $getData = $this->db->where($where)->get('tbl_request_bom')->row();
        if($getData) {
            $res['size_dim'] = $getData->size_dim;
            $res['uom'] = $getData->uom;
            $res['request_bom_id'] = $getData->request_bom_id;
        } else {
            $res['size_dim'] = '';
            $res['uom'] = '';
            $res['request_bom_id'] = '';
        }
        
        return $res;
    }
    
    public function getDeptt($data)
    {
        $reqId = $data['reqId'];
        $mi_id = $data['mi_id'];
        $bom_dept = '';
        
        $bom_depts = $this->db->where('bom_ref_no',$mi_id)->get('tbl_mi_details')->row();
        if($bom_depts->type == "INTERNAL") {
            $bom_dept = $bom_depts->bom_dept;
        } else {
            $bom_dept = $this->db->where('id',$bom_depts->bom_dept)->get('kn_master_bom_vendor')->row()->vendorname;
        }
        
        return $bom_dept; 
        
    }
    
    public function getMIUomm($data)
    {
        $reqId = $data['reqId'];
        $mi_id = "'".$data['mi_id']."'";
        $itemCode = "'".$data['itemCode']."'";
        $sam_val = "'".$data['sam_val']."'";
        
        $sql = "SELECT a.uom FROM tbl_mi_bom_details a
                INNER JOIN tbl_mi_bom b on a.mi_bom_id=b.mi_bom_id
                INNER JOIN tbl_mi_details c on b.request_id=c.request_id
                WHERE a.item_code = ".$itemCode." AND c.bom_ref_no = ".$mi_id." AND a.sample_no = ".$sam_val." AND b.flag=1 ";
        $datass = $this->db->query($sql)->row();
        
        $uom = $datass->uom;
        
        return $uom; 
        
    }
    
    public function getMIUom11($data)
    {
        $reqId = $data['reqId'];
        $mi_id = "'".$data['mi_id']."'";
        $itemCode = "'".$data['itemCode']."'";
        $sam_val = "'".$data['sam_val']."'";
        
        
        $sql = "SELECT a.uom FROM tbl_mi_bom_details a
                INNER JOIN tbl_mi_bom b on a.mi_bom_id=b.mi_bom_id
                INNER JOIN tbl_mi_details c on b.request_id=c.request_id
                WHERE a.item_code = ".$itemCode." AND c.bom_ref_no = ".$mi_id." AND a.mi_bom_details_id = ".$sam_val." AND b.flag=1 ";
        $datass = $this->db->query($sql)->row();
        
        $uom = $datass->uom;
        
        return $uom; 
        
    }
    
    public function getDebitDatas($data) 
    {
        $inv_no = $data['invoice_no'];
        $pId = $data['pId'];
    }
    
    public function getBOMDCDetailss($id, $miId, $dc)
    {
        $dc = '"'.$dc.'"';
        
        $sql = "SELECT * FROM tbl_mi_issued_details as a
                INNER JOIN tbl_mi_bom_details as b on a.mi_bom_details_id=b.mi_bom_details_id
                INNER JOIN tbl_mi_bom as c on b.mi_bom_id=c.mi_bom_id
                INNER JOIN tbl_mi_details as d on c.request_id=d.request_id
                WHERE a.dc_no=".$dc." AND a.flag=1";
                
        $result = $this->db->query($sql)->result_array();
        $BomMIData = [];

        foreach ($result as $key => $value) {
            $BomMIData[$key] = [
                $value['mi_bom_details_id'], $value['mi_bom_id'], $value['mi_serial_no'], $value['item_desc'], $value['bcm'], $value['gar_size'],
                $value['item_code'], $value['item_color_code'], $value['size_dim'], $value['ind_uom'], $value['issued_qty'], $value['ind_uom']
            ];
        }

        // *** get garment size *** //
        $sizeChart  = $this->getSizeChart($id);
        $sizeMaster = $this->getSizeMasterDropdown($sizeChart);
        
        $cad_sql = "SELECT c.po_enq_ref_id as po_id, c.combo_id, c.component_id, c.spec_code_id as size_id, a.request_cad_id as id, a.ref_queue_no as name FROM tbl_request_cad a
                INNER JOIN tbl_request b ON a.request_id = b.request_id
                INNER JOIN tbl_cad_requirement c ON a.cad_id = c.cad_requirement_id
                WHERE b.enquiry_id = ".$id." AND a.flag=1 ";
        $cad_ref_data = $this->db->query($cad_sql)->result_array();

        $data['cadMIData'] = $BomMIData;
        $data['sizeData'] = $sizeMaster;
        $data['cadRefNo'] = $cad_ref_data;
        return $data;
    }
    
    public function bomstore_dc_printt($data)
    {
        $company_id = $_SESSION['UI']['companyid']; 
        $company_data = $this->db->from('kn_company_details')->where('id', $company_id)->get()->result_array();
        if($data['pId'] != '') {
            $pId = $data['pId'];
          $sql = "SELECT a.*, b.ref_queue_no,e.isriorcode,g.contactname as pi_req_name, h.contactname as pi_appr_name FROM tbl_purchase_indent a
                INNER JOIN tbl_request b on a.request_id=b.request_id
                INNER JOIN tbl_request_bom c on a.purchase_indent_id=c.purchase_indent_id
                INNER JOIN kn_order_enquiry as e on a.enquiry_id=e.id
                INNER JOIN tbl_request_status f on a.purchase_indent_id=f.purchase_indent_id
                INNER JOIN ".KN_USERS." g on a.pi_req_by=g.id
                INNER JOIN ".KN_USERS." h on f.appr_by=h.id
                WHERE a.purchase_indent_id = " . $pId . " ";
            $pi_data = $this->db->query($sql)->result_array();
            
            $vendor_sql = "SELECT a.*,c.*,d.contactname FROM tbl_request_status as a
                        INNER JOIN tbl_request_payment b ON a.purchase_indent_id = b.purchase_indent_id
                        INNER JOIN kn_master_bom_vendor c ON b.vendor_id=c.id
                        INNER JOIN ".KN_USERS." d ON a.appr_by=d.id
                        WHERE b.purchase_indent_id = $pId ";
            $vendor_data = $this->db->query($vendor_sql)->result_array();
            
            $itemsql = "SELECT c.item_desc,c.bcm,c.garment_size,c.appr_item_code,c.appr_item_col_code,c.size_dim,c.uom as uoms,c.plan_bom_qty,c.requirement_uom,d.* FROM tbl_purchase_indent a
                INNER JOIN tbl_request b on a.request_id=b.request_id
                INNER JOIN tbl_request_bom c on a.purchase_indent_id=c.purchase_indent_id
                INNER JOIN tbl_request_purchase_indent d on c.request_bom_id=d.request_bom_id
                WHERE a.purchase_indent_id = " . $pId . " ";
            $item_data = $this->db->query($itemsql)->result_array();
        
            $result['pi_data'] = $pi_data;
            $result['item_data'] = $item_data;
            $result['company_data'] = $company_data;
            $result['vendor_data'] = $vendor_data;
        } else {
            $pi_data = '';
            $vendor_data = '';
            $item_data = '';
            $result['pi_data'] = $pi_data;
            $result['item_data'] = $item_data;
            $result['company_data'] = $company_data;
            $result['vendor_data'] = $vendor_data;
        }
        return $result;
    }
    
    public function getBOMDCprintData($id, $miId, $dc)
    {
        $dc = '"'.$dc.'"';
    
        
        $sql = "SELECT * FROM tbl_mi_issued_details as a
                INNER JOIN tbl_mi_bom_details as b on a.mi_bom_details_id=b.mi_bom_details_id
                INNER JOIN tbl_mi_bom as c on b.mi_bom_id=c.mi_bom_id
                INNER JOIN tbl_mi_details as d on c.request_id=d.request_id
                WHERE a.dc_no=".$dc." AND a.flag=1";
                
        $result = $this->db->query($sql)->result_array();
        $BomMIData = [];

        foreach ($result as $key => $value) {
            $BomMIData[$key] = [
                $value['mi_bom_details_id'], $value['mi_bom_id'], $value['mi_serial_no'], $value['item_desc'], $value['bcm'], $value['gar_size'],
                $value['item_code'], $value['item_color_code'], $value['size_dim'], $value['ind_uom'], $value['issued_qty'], $value['ind_uom']
            ];
        }


        
        return $result;
    }
    
    public function clearDraftFunctionn($id, $reqId, $miId, $data, $received_by, $mi_type)
    {
        foreach ($data as $key => $value) {
            $this->db->where('mi_bom_details_id',$value[0])->where('dc_status',0)->delete('tbl_mi_issued_details');
            
        }
        
    }
    
    public function getsurplusstocklistt() {
        $sql = "SELECT a.*,c.*,SUM(a.surplus_qty) as surplus_qtys,d.cutoff_date FROM tbl_surplus_stock_details as a 
                inner join kn_order_enquiry as b on a.enq_id=b.id 
                inner join kn_master_brands as c on b.brandId=c.id 
                inner join tbl_request as d on a.req_id=d.request_id 
                where a.supply_closed_status_moved = 1 and d.subscriberid = ".$this->db->escape($this->subscriberId)." GROUP BY a.item_code ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();
        
        // if($data) {
        //     foreach($data as $key => $value) {
        //         $mi_ref_no = '';
        //         $cut_off_date = '';
        //         $res[$key] = [ $value['brandname'], $value['item_code'], $value['garment_size'], $value['item_col_code'], $value['size_dim'], $value['uom'], $value['surplus_qtys'], $mi_ref_no ,$cut_off_date, $value['log'], $value['flag'] ];
        //     }
        // }
        return $data;
    }
    
    public function updateSurplusDraftt($enqId, $reqId, $pId, $itemCode, $issuedData)
    {
        
        foreach($issuedData as $key => $value) {
            $bom_id = $this->db->where('inv_no',$value[7])->where('lot_no',$value[9])->get('tbl_surplus_stock_details')->row();
            $data['enqId'] = $enqId;
            $data['reqId'] = $reqId;
            $data['pId'] = $pId;
            $data['bom_id'] = $bom_id->request_bom_id;
            $data['inHouse_id'] = $bom_id->inHouse_id;
            $data['surplus_id'] = $bom_id->surplus_id;
            $data['item_code'] = $itemCode;
            $data['pi_ref_no'] = $value[3];
            $data['transfer_category'] = $value[4];
            $data['stm_ref_no'] = $value[5];
            $data['stm_date_time'] = $value[6];
            $data['inv_no'] = $value[7];
            $data['inv_date'] = $value[8];
            $data['lot_no'] = $value[9];
            $data['rate'] = $value[10];
            $data['gst'] = $value[11];
            $data['issued_qty'] = $value[12];
            $data['uom'] = $value[13];
            $data['log'] = LOGTIME;
            if($value[0] == '') {
                $this->db->insert('tbl_surplus_issued_details',$data);
            }
        }
    }
    
    
    public function getsurplusDraftCountt($itemCode)
    {
        //$data = $this->db->where('item_code',$itemCode)->where('draft_status',1)->get('tbl_surplus_issued_details')->result_array();
        $sql = "SELECT * FROM tbl_surplus_issued_details as a
                WHERE a.item_code = ".$itemCode." AND a.draft_status=1  ";
        $data = $this->db->query($sql)->result_array();
        
        return $data;
    }
    
    public function getSurplusDCList($id, $reqId, $itemCode)
    {
        $itemCode = "'".$itemCode."'";
        $sql = "SELECT a.*,b.*, a.pi_ref_no as pi_no, a.uom as auom,b.uom as buom FROM tbl_surplus_issued_details as a
                INNER JOIN tbl_bom_in_house as b on a.lot_no=b.batch_ref_no AND a.item_code = b.appr_item_code
                WHERE a.item_code = ".$itemCode." AND a.draft_status=1  ";
        $result = $this->db->query($sql)->result_array();
        //print_r($result); exit;
        $bcm_sql = "SELECT * FROM tbl_mi_bom_details as a
                WHERE a.item_code = ".$itemCode." ";
        $bcm = $this->db->query($bcm_sql)->row()->bcm;
        
        $draftData = [];

        foreach ($result as $key => $value) {
            $draftData[$key] = [
                $value['issued_id'], '', true, $value['pi_no'], $value['inv_no'], $value['item_desc'], $bcm, $value['garment_size'], $value['item_code'],
                $value['appr_item_color_code'], $value['size_dim'], $value['buom'], $value['lot_no'], $value['issued_qty'], $value['auom'], $value['rate'], '', $value['gst'], ''
            ];
        }


        $data['draftData'] = $draftData;
        
        return $data;
    }
    
    public function getdraftDataa($reqId)
    {
        $sql = "SELECT * FROM tbl_request as a
                WHERE a.request_id = ".$reqId." ";
        $data = $this->db->query($sql)->result_array();
        
        return $data;
        
    }
    
    
    public function clearSurplusDraftFunctionn($enqId, $reqId, $itemCode, $req_data )
    {
        foreach ($req_data as $key => $value) {
            $this->db->where('issued_id',$value[0])->where('draft_status',1)->delete('tbl_surplus_issued_details');
            
        }
        
    }
    
    public function updateSurplusDCListt($enqId, $reqId, $pId, $itemCode, $req_data, $received_by, $amt_words, $pay_terms)
    {
        //echo "<pre>"; print_r($req_data); exit;
         $itemCode = "'".$itemCode."'";
        $ord_sql = "SELECT pi_ref_no FROM tbl_surplus_issued_details WHERE item_code=".$itemCode." AND draft_status=1 " ;
        $ord_data = $this->db->query($ord_sql)->result_array();
        
        $issue_sql = "SELECT MAX(stm_queue_no)+1 as last_queue_no FROM tbl_surplus_issued_details";
        $issue_data = $this->db->query($issue_sql)->result_array();

        
        $queue_no = $issue_data[0]['last_queue_no'];
        if($queue_no == "") { $queue_no = 1; }

        $ref_queue_no = $ord_data[0]['pi_ref_no']."/STM-".$queue_no;
        

        foreach ($req_data as $key => $value) {
            if($value[2] == false) {
                $this->db->where('issued_id',$value[0])->where('draft_status',1)->delete('tbl_surplus_issued_details');
                unset($req_data[$key]);
                
            }
        }

        $data = array_values($data);
        
        foreach ($req_data as $key => $value) {
            if($value[2] == true)
            {
                $issuse_data['stm_queue_no'] = $queue_no;
                $issuse_data['stm_ref_no'] = $ref_queue_no;
                $issuse_data['stm_date_time'] = $this->mysqldatetime;
                $issuse_data['received_name'] = $received_by;
                $issuse_data['amount_in_words'] = $amt_words;
                $issuse_data['payment_terms'] = $pay_terms;
                $issuse_data['draft_status'] = 0;
                $issuse_data['log'] = LOGTIME;
                
                $this->db->where('issued_id', $value[0]);
                $this->db->update('tbl_surplus_issued_details', $issuse_data);
                
                $bom_data = $this->db->where('issued_id',$value[0])->get('tbl_surplus_issued_details')->row();
                
                $lot_no = "'".$bom_data->lot_no."'";
                $ord_sql = "SELECT * FROM tbl_bom_in_house WHERE request_bom_id=$bom_data->bom_id AND batch_ref_no=".$lot_no." AND appr_item_code=$itemCode" ;
                $item_data = $this->db->query($ord_sql)->row();
                
                $pi_ref_no = "'".$bom_data->pi_ref_no."'";
                $pi_sql = "SELECT * FROM tbl_purchase_indent WHERE pi_ref_queue_no=".$pi_ref_no." " ;
                $pi_data = $this->db->query($pi_sql)->row();
                $in_data['enquiryId'] = $enqId;
                $in_data['request_id'] = $pi_data->request_id;
                $in_data['purchase_indent_id'] = $pi_data->purchase_indent_id;
                $in_data['request_bom_id'] = $bom_data->bom_id;
                $in_data['type'] = 'S';
                $in_data['item_desc'] = $item_data->item_desc;
                $in_data['garment_size'] = $item_data->garment_size;
                $in_data['appr_item_code'] = $item_data->appr_item_code;
                $in_data['appr_item_color_code	'] = $item_data->appr_item_color_code;
                $in_data['size_dim'] = $item_data->size_dim;
                $in_data['uom'] = $item_data->uom;
                $in_data['dc_no'] = $bom_data->stm_ref_no;
                $in_data['dc_date'] = date('Y-m-d',strtotime($bom_data->stm_date_time));
                $in_data['batch_ref_no'] = $item_data->batch_ref_no;
                $in_data['dc_qty'] = $bom_data->issued_qty;
                $in_data['invoice_no'] = $item_data->invoice_no;
                $in_data['invoice_date'] = $item_data->invoice_date;
                $in_data['invoice_qty'] = $item_data->invoice_qty;
                $in_data['invoice_rate'] = $item_data->invoice_rate;
                $in_data['currency'] = $item_data->currency;
                $in_data['foreign_exch_rate'] = $item_data->foreign_exch_rate;
                $in_data['invoice_value'] = $item_data->invoice_value;
                $in_data['gst'] = $item_data->gst;
                $in_data['total_invoice_value'] = $item_data->total_invoice_value;
                $in_data['received_qty'] = $value[13];
                $in_data['received_uom'] = $item_data->received_uom;
                $in_data['received_date'] = $item_data->received_date;
                $in_data['storage_bin'] = $item_data->storage_bin;
                $in_data['merchant_item_status'] = $item_data->merchant_item_status;
                $in_data['merchant_appl_date_time'] = $item_data->merchant_appl_date_time;
                $in_data['qa_status'] = $item_data->qa_status;
                $in_data['qa_status_upt_dt'] = $item_data->qa_status_upt_dt;
                $in_data['supply_closure_status'] = $item_data->supply_closure_status;
                $in_data['supply_closure_date'] = $item_data->supply_closure_date;
                $in_data['mgmt_ovrd_status'] = $item_data->mgmt_ovrd_status;
                $in_data['mgmt_status_upd_dt'] = $item_data->mgmt_status_upd_dt;
                $in_data['supply_closed_status'] = 0;
                $in_data['order_stock_status'] = $item_data->order_stock_status;
                $in_data['log'] = LOGTIME;
                
                //print_r($in_data); exit;
                $this->db->insert('tbl_bom_in_house', $in_data);
                
            }
        }
        
        
        if($this->db->affected_rows() > 0)
        {
            $result['status'] = "success";
        }
        else {
            $result['status'] = "failure";
        }
        
        $insql = "SELECT a.*,SUM(d.issued_qty) as issued_qtys,d.uom as uoms FROM tbl_surplus_stock_details a
                INNER JOIN tbl_request b on a.req_id=b.request_id
                INNER JOIN kn_order_enquiry as c on b.enquiry_id=c.id
                INNER JOIN tbl_surplus_issued_details as d on a.pi_ref_no = d.pi_ref_no AND a.item_code = d.item_code AND a.lot_no = d.lot_no
                WHERE a.item_code = " .$itemCode. " AND a.flag=1  GROUP BY a.pi_ref_no,a.item_code,a.lot_no ";
        $data2 = $this->db->query($insql)->result_array();
    
        foreach ($data2 as $key => $value)
        {
            
            $qty = $value['surplus_qty']; 
            $qty1 = $value['issued_qtys']; 
            $diff = floatval($qty)-floatval($qty1);
           
            if($diff == 0) {
                // $scrap_data['stm_queue_no'] = $queue_no;
                // $scrap_data['stm_ref_no'] = $ref_queue_no;
            }

        }
        
        return $result;
    }
    
    
    // public function getstocktransferlistt() {
    //     $sql = "SELECT a.*,b.isriorcode,c.brandname,d.cutoff_date,e.pi_dt FROM tbl_surplus_issued_details as a 
    //             inner join kn_order_enquiry as b on a.enqId=b.id 
    //             inner join kn_master_brands as c on b.brandId=c.id 
    //             inner join tbl_request as d on a.reqId=d.request_id 
    //             inner join tbl_purchase_indent as e on a.pId=e.purchase_indent_id
    //             WHERE a.draft_status=0 GROUP BY a.enqId ";
    //     $data = $this->db->query($sql)->result_array();
    //     return $data;
    // }
    
    public function getstocktransferlistt() {
        $sql = "SELECT a.*,b.isriorcode, c.brandname,d.cutoff_date,e.pi_dt FROM tbl_surplus_issued_details as a 
                inner join kn_order_enquiry as b on a.enqId=b.id 
                inner join kn_master_brands as c on b.brandId=c.id 
                inner join tbl_request as d on a.reqId=d.request_id 
                inner join tbl_purchase_indent as e on a.pId=e.purchase_indent_id
                WHERE a.draft_status=0 and d.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.enqId ";
        $result = $this->db->query($sql)->result_array();
        foreach ($result as $key => $res) {
            $stm_ref_no = $stm_date = $trans_category = $status = $log = [] ;
            $enq_id = $res['enqId'];
            $sql1 = "SELECT *,SUM(issued_qty) as issued_qtys FROM tbl_surplus_issued_details WHERE enqId=".$enq_id." AND draft_status=0 GROUP BY stm_ref_no ";
            $result1 = $this->db->query($sql1)->result_array();
            $tot_qty = [] ;
            $tot_qtys = 0;
            foreach($result1 as $key1 => $val) {
                $stm_ref = "'".$val['stm_ref_no']."'";
                $sql2 = "SELECT * FROM tbl_surplus_issued_details as a
                WHERE stm_ref_no=".$stm_ref." AND draft_status=0  ";
                $result2 = $this->db->query($sql2)->result_array();
                //print_r($result2); 
                
                foreach($result2 as $key2 => $val2) {
                    
                    $surplus_id = $val2['surplus_id'];
                    $tot_qty[$surplus_id][] = $val2['issued_qty'];
                }
                // foreach($tot_qty as $key3 => $val3) {
                //     $bom_id = $key3;
                //     //print_r($val3); echo "<br>";
                //     $tot_qtys = array_sum($val3);
                // }
                // print_r($tot_qtys); 
                $enq_id1 = base64_encode($enq_id);
                $stm_ref_no1 = base64_encode($val['stm_ref_no']);
                array_push($stm_ref_no,'<a class="bold" href="'.base_url().'request/Bomrequest/stocktransferdetails/'.$enq_id1.'/stm_ref_no/'.$stm_ref_no1.' ">'.$val['stm_ref_no'].'</a>');
                // $stm_ref_no[] = $val['stm_ref_no'];
                $stm_date[] = $val['stm_date_time'];
                $trans_category[] = $val['transfer_category'];
                //$status[] = '<span class="text-light knGreenColor bg-dark"><strong>ISSUED - PART</strong></span>';
                $log[] = date('d-m-Y H:i A',strtotime($val['log']));
            }  
            $tot_qtys = $bom_id = [];
            foreach($tot_qty as $key3 => $val3) {
                $issuesql = "SELECT * FROM tbl_surplus_stock_details WHERE surplus_id=".$key3." ";
                $issue_res = $this->db->query($issuesql)->result_array();
                $bom_id[] = $key3;
                $issue_qtys = number_format(array_sum($val3),2);
                
                $tot_qty1 = $issue_res[0]['surplus_qty']; 
                $tot_qty = number_format($tot_qty1,2); 
                if($val3 == 0) {
                    $status[] = '<span class="text-light knGreenColor bg-dark"><strong>PENDING</strong></span>';
                } else if($issue_qtys == $tot_qty) {
                    $status[] = '<span class="text-light knGreenColor bg-dark"><strong>ISSUED - FULL</strong></span>';
                } else {
                    $status[] = '<span class="text-light knGreenColor bg-dark"><strong>ISSUED - PART</strong></span>';
                }
            }
            // exit;
            // print_r($bom_id); exit;
            $data[$key]['surplus_id'] = $res['surplus_id'];
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
            $data[$key]['log'] = implode(' <br /> ', $log);
            $data[$key]['flag'] = $res['flag'];
            
            
        }
        
        
        return $data;
    }
    
    public function getStockTransferDetailss($stm_ref_no)
    {
        $stm_ref_no = "'".$stm_ref_no."'";
        $sql = "SELECT a.*,b.*, a.pi_ref_no as pi_no, a.uom as auom,b.uom as buom FROM tbl_surplus_issued_details as a
                INNER JOIN tbl_bom_in_house as b on a.inHouse_id=b.bom_in_house_id 
                WHERE a.stm_ref_no = ".$stm_ref_no."  ";
        $result = $this->db->query($sql)->result_array();
        
        
        
        $draftData = [];

        foreach ($result as $key => $value) {
            $bcm_sql = "SELECT * FROM tbl_surplus_stock_details WHERE inHouse_id = ".$value['inHouse_id']." ";
            $bcm = $this->db->query($bcm_sql)->row()->bcm;
            $draftData[$key] = [
                $value['issued_id'], '', true, $value['pi_no'], $value['inv_no'], $value['item_desc'], $bcm, $value['garment_size'], $value['item_code'],
                $value['appr_item_color_code'], $value['size_dim'], $value['buom'], $value['lot_no'], $value['issued_qty'], $value['auom'], $value['rate'], '', $value['gst'], ''
            ];
        }


        $data['draftData'] = $draftData;
        
        return $data;
    }
    
    public function getsurplusData($stm_ref_no)
    {
        $stm_ref_no = "'".$stm_ref_no."'";
        $sql = "SELECT a.*,c.cutoff_date,c.ref_queue_no,c.purchase_req_type,b.mode FROM tbl_surplus_issued_details as a
                INNER JOIN tbl_purchase_indent as b on a.pi_ref_no=b.pi_ref_queue_no
                INNER JOIN tbl_request as c on b.request_id=c.request_id
                
                WHERE a.stm_ref_no = ".$stm_ref_no."  ";
        $result = $this->db->query($sql)->result_array();
        
        return $result;
    }
    
    public function getBomStatus($req_id)
    {
        $mi_bom_id = $this->db->where('request_id',$req_id)->get('tbl_mi_bom')->row()->mi_bom_id;
        
        $bomStatus = $this->db->where('mi_bom_id',$mi_bom_id)->where('bom_status',0)->get('tbl_mi_bom_details')->result_array();
        
        return $bomStatus;
    }

     public function getorderclosure($enq_id)
    {
       
        $sql = "SELECT b.* FROM tbl_mi_issued_details b WHERE b.enquiryId = " . $enq_id . " and (b.supply_closed_status = 0)   ";
        $req_data = $this->db->query($sql)->result_array();

       
       
          if (count($req_data) > 0) {
        return 1;  // Data exists
       } else {
      return 0;  // No data found
         }
    }
    
    public function getMIDraftData($req_id)
    {
        $result = [];
        $sql = "SELECT * FROM tbl_mi_issued_details as a
                INNER JOIN tbl_mi_bom_details as b on a.mi_bom_details_id=b.mi_bom_details_id
                INNER JOIN tbl_mi_bom as c on b.mi_bom_id=c.mi_bom_id
                WHERE c.request_id = ".$req_id." AND a.dc_status = 0 ";
        $result = $this->db->query($sql)->result_array();
        
        // $mi_bom_id = $this->db->where('request_id',$req_id)->get('tbl_mi_bom')->row()->mi_bom_id;
        
        // $bomStatus = $this->db->where('mi_bom_id',$mi_bom_id)->where('bom_status',1)->get('tbl_mi_bom_details')->result_array();
        
        return $result;
    }

     public function getMIDraftData3($req_id,$draf_id)
    {
        $result = [];
        $sql = "SELECT * FROM tbl_mi_issued_details as a
                INNER JOIN tbl_mi_bom_details as b on a.mi_bom_details_id=b.mi_bom_details_id
                INNER JOIN tbl_mi_bom as c on b.mi_bom_id=c.mi_bom_id
                WHERE c.request_id = ".$req_id." AND a.dc_status = 0 AND a.draf_id = ".$draf_id." ";
        $result = $this->db->query($sql)->result_array();
        
        // $mi_bom_id = $this->db->where('request_id',$req_id)->get('tbl_mi_bom')->row()->mi_bom_id;
        
        // $bomStatus = $this->db->where('mi_bom_id',$mi_bom_id)->where('bom_status',1)->get('tbl_mi_bom_details')->result_array();
        
        return $result;
    }

     public function getMIDraftData2($req_id)
    {
        $result = [];
        $sql = "SELECT * FROM tbl_mi_bom_details as a
                INNER JOIN tbl_mi_bom as c on a.mi_bom_id=c.mi_bom_id
                WHERE c.request_id = ".$req_id." ";
                 $result = $this->db->query($sql)->result_array();
                //PRINT_R($sql);
        // $sql = "SELECT *
        // FROM tbl_mi_bom_details AS a
        // INNER JOIN tbl_mi_bom AS c ON a.mi_bom_id = c.mi_bom_id
        // WHERE c.request_id = ".$req_id."
        //   AND a.issue_qty IS  NULL
        //   AND TRIM(a.issue_qty) <> ''
        //   AND CAST(a.issue_qty AS DECIMAL(18,6)) >= 0";
        // $result = $this->db->query($sql)->result_array();
        
        // $mi_bom_id = $this->db->where('request_id',$req_id)->get('tbl_mi_bom')->row()->mi_bom_id;
        
        // $bomStatus = $this->db->where('mi_bom_id',$mi_bom_id)->where('bom_status',1)->get('tbl_mi_bom_details')->result_array();
        
        return $result;
    }
    
    
    
    

}
