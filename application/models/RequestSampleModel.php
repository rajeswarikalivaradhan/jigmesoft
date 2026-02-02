<?php
error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RequestSampleModel extends CI_Model
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
        $this->subscriberid = $ArrUserLoggedInfo['subscriber_id'];
    }
    
    function getSizeChart($enqId = '')
    {
        $this->db->select('size_ids');
        $ArrRes = $this->db->get_where('tbl_pc_size_chart', array('enquiry_id' => $enqId));
        return $ArrRes->row()->size_ids;
    }

    public function getBlendContentMaterial2($type,$tablename) {
       $data;
        if($type == 'items') {
            $data = $this->db->query('SELECT id as id, description as name FROM '.$tablename.' where type=1 and status=1')->result();
        
        
            echo $this->db->last_query();
        }
        if($type == 'blend') {
            $data = $this->db->query('SELECT id as id, description as name FROM '.$tablename.' where type=2 and status=1')->result();
        }
        if($type == 'content') {
            $data = $this->db->query('SELECT id as id, description as name FROM '.$tablename.' where type=3 and status=1')->result();
        }
        if($type == 'material') {
            $data = $this->db->query('SELECT id as id, description as name FROM '.$tablename.' where type=4 and status=1')->result();
        }
        return $data;

       
        $data = $query->result();
        file_put_contents("error_log", print_r($data, true));
        
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

    public function getSampleRequestDetailss($id) {
        $sql = "SELECT a.*, b.*, c.*, d.cad_ref, d.mat_ind_cad_id, d.req as cad_req, d.purpose as cad_purpose, d.req_size as cad_req_size
                FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id 
                LEFT JOIN tbl_mi_cad_details as d on a.sample_requirement_id = d.sample_req_id
                WHERE a.enquiry_id = ".$id." and a.req_sent_status = 0 ORDER BY a.sample_requirement_id asc";
        $data = $this->db->query($sql)->result_array();
        
        $result = [];
        $referResult = [];
        $cadMaterialIndent = [];
        $ref_status = 0;
        $req_id = "";
        $reqData = [];

        foreach ($data as $key => $value)
        {
            
            $ref_status += (int) $value['req_reference_status'];
            if($value['request_id'] != null || $value['request_id'] != "") {
                $req_id = $value['request_id'];
                $reqData = $this->db->where('request_id',$value['request_id'])->get('tbl_request')->result_array();
                
            } 
            if($value['req_reference_status'] == "1" || $value['req_reference_status'] == 1) 
            {
                $result[$key] = ['edit', $value['sample_requirement_id'], $value['req_reference_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['sample_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value['req_size'], $value['req_qty'] ];

                $referResult[$key] = ['edit', $value['sample_requirement_id'], $value['req_reference_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

                $cadMaterialIndent[$key] = [$value['mat_ind_cad_id'], $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['cad_ref'], $value['cad_req'], $value['cad_purpose'], $value['cad_req_size'], '', '' ];
            }
            else {
                $result[$key] = ['', $value['sample_requirement_id'], $value['req_reference_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                        $value['spec_code_id'], $value['sample_requirement'], "", "", "", $value['req_size'], $value['req_qty'] ];
            }
        }
        
        $bom_sql = "SELECT b.*
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_mi_bom as b on a.sample_requirement_id = b.sample_req_id
                WHERE a.enquiry_id = ".$id."  and a.req_sent_status = 0 ORDER BY a.sample_requirement_id asc";
        $bom_data = $this->db->query($bom_sql)->result_array();
        
        $fabric_sql = "SELECT b.*
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_mi_fabric as b on a.sample_requirement_id = b.sample_req_id
                WHERE a.enquiry_id = ".$id."  and a.req_sent_status = 0 ORDER BY a.sample_requirement_id asc";
        $fabric_data = $this->db->query($fabric_sql)->result_array();

        $bom_mi_tbl_data = [];
        for ($i=0; $i < sizeof($bom_data); $i++) { 
            $bom_details_sql = "SELECT * FROM tbl_mi_bom_details WHERE mi_bom_id = ".$bom_data[$i]['mi_bom_id']."";
            $bom_details_data = $this->db->query($bom_details_sql)->result_array();
            array_push($bom_mi_tbl_data, $bom_details_data);
        }

        $fabric_mi_tbl_data = [];
        for ($i=0; $i < sizeof($fabric_data); $i++) { 
            $fabric_details_sql = "SELECT * FROM tbl_mi_fabric_details WHERE mi_fabric_id = ".$fabric_data[$i]['mi_fabric_id']."";
            $fabric_details_data = $this->db->query($fabric_details_sql)->result_array();
            array_push($fabric_mi_tbl_data, $fabric_details_data);
        }

        $bomMaterialIndent = [];
        foreach ($bom_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                $bomMaterialIndent[$key][$nKey] = [ $nValue['mi_bom_details_id'], $nValue['item_desc'], $nValue['bcm'], $nValue['gar_size'], $nValue['item_code'],
                                $nValue['item_color_code'], $nValue['size_dim'], $nValue['uom'], $nValue['ind_qty'], $nValue['ind_uom'], '', '' ];
            }
        }

        $fabricMaterialIndent = [];
        foreach ($fabric_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                $fabricMaterialIndent[$key][$nKey] = [ $nValue['mi_fabric_details_id'], $nValue['fab_ref_no'], $nValue['colour'], $nValue['garment_part'], 
                    $nValue['fabric_blend'], $nValue['fabric_content'], $nValue['fabric_name'], $nValue['gsm'], 
                    $nValue['size_dim'], $nValue['uom'], $nValue['ind_qty'], $nValue['ind_uom'], $nValue['dc_no'], $nValue['issue_date'] ];
            }
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $cad_sql = "SELECT c.po_enq_ref_id as po_id, c.combo_id, c.component_id, c.spec_code_id as size_id, a.request_cad_id as id, a.ref_queue_no as name FROM tbl_request_cad a
                INNER JOIN tbl_request b ON a.request_id = b.request_id
                INNER JOIN tbl_cad_requirement c ON a.cad_id = c.cad_requirement_id
                WHERE b.enquiry_id = ".$id."  ";
        $cad_ref_data = $this->db->query($cad_sql)->result_array();
        
        $mi_data = [];
        if($req_id != null) {
            $mi_sql = "SELECT * FROM tbl_mi_details WHERE request_id = ".$req_id."  ";
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
        $bom2MIDetails = $this->bom2MIDetails($id);
        $fabricMIDetails = $this->fabricMIDetails($id);

        $ref_sql = "SELECT b.ref_queue_no as id, b.ref_queue_no as name, a.po_enq_ref_id as po_enq_id, a.combo_id, a.component_id, a.spec_code_id as size_id, a.sample_requirement as req_id 
                    FROM tbl_sample_requirement as a 
                    LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id
                    WHERE a.enquiry_id = " . $id . "  and a.req_sent_status = 1 and b.ref_queue_no != '' ";
        $ref_data = $this->db->query($ref_sql)->result_array();

         $query_qty = "SELECT a.appr_item_code, SUM(a.dc_qty) as total_qty FROM tbl_bom_in_house a INNER JOIN tbl_request b ON a.request_id = b.request_id 
       INNER JOIN tbl_purchase_indent c ON a.purchase_indent_id = c.purchase_indent_id WHERE a.order_stock_status = 1 AND b.enquiry_id = " . $id . " 
       AND a.mgmt_ovrd_status NOT IN (2, 3, 4) GROUP BY a.appr_item_code";
        $query_qty1 = $this->db->query($query_qty)->result_array();

    
        $output['data'] = $result;
        $output['sizeData'] = $sizeMaster;
        $output['ref_status'] = $ref_status;
        $output['req_id'] = $req_id;
        $output['referResult'] = array_values($referResult);
        $output['cad_ref_data'] = $cad_ref_data;
        $output['cadMaterialIndent'] = array_values($cadMaterialIndent);
        $output['UOMDetails'] = $UOMDetails;
        $output['BOMAppendData'] = $bomMIDetails;
        $output['BOM2AppendData'] = $bom2MIDetails;
        $output['FabricAppendData'] = $fabricMIDetails;
        $output['bom_mi_tbl_data'] = $bomMaterialIndent;
        $output['fabric_mi_tbl_data'] = $fabricMaterialIndent;
        $output['mi_data'] = $mi_data;
        $output['sampleRefNo'] = $ref_data;
        $output['reqData'] = $reqData;
        $output['sumqty'] = $query_qty1;

      
        
        return $output;

       
    }

   


    public function bomMIDetails($id)
    {
        
        $sql = "SELECT *, SUM(`req_bom_qty`) as calc_bom_qty FROM tbl_bom_article_1_requirement as a 
                where a.enquiry_id='$id' 
                GROUP BY a.item_desc, a.blend, a.content, a.material, a.garment_size, a.appr_item_code, 
                        a.appr_item_col_code, a.size_dim, a.uom";
        
        $data = $this->db->query($sql)->result_array();
        file_put_contents("error_log", print_r($data, true));

        
        $sql1 = "SELECT * FROM tbl_bom_article_1_req_consld as a WHERE a.enquiry_id='$id' ";
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
            $itemData = [ 'id'=> $value[3], 'type' => 'bom1','name' => $value[3]];
            $bcmData = [ 'id'=> $bcm, 'item_id' => $value[3], 'type' => 'bom1','name' => $bcm ];
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


        $itemdatasample= $this->getBlendContentMaterial2('items','tbl_bom1_master');

        //$output['itemDescription'] = $itemdatasample;
         $output['itemDescription'] = $itemDescription;
        //$output['itemDescription'] = $itemdata;
        $output['bcm'] = $blendContentMaterial;
        $output['garmentSize'] = $garmentSize;
        $output['itemCode'] = $itemCode;
        $output['itemColor'] = $itemColor;
        $output['sizeDimension'] = $sizeDimension;
        $output['uom'] = $uom;
        $output['type'] = 'bom1';
        // print_r($output);
        // file_put_contents("error_log", print_r($output, true));
        // die;
       
        return $output;
    }



     public function bom2MIDetails($id)
    {
        
        $sql = "SELECT *, SUM(`req_bom_qty`) as calc_bom_qty FROM tbl_bom_article_2_requirement as a 
                where a.enquiry_id='$id' 
                GROUP BY a.item_desc, a.blend, a.content, a.material, a.garment_size, a.appr_item_code, 
                        a.appr_item_col_code, a.size_dim, a.uom ORDER BY a.bom_2_req_id ";
        
        $data = $this->db->query($sql)->result_array();
        
        $sql1 = "SELECT * FROM tbl_bom_article_2_req_consld as a WHERE a.enquiry_id='$id' ";
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
            
            if( isset($data1[$i]['bom_2_req_consld_id'])) {
                $bom_1_req_consld_id = $data1[$i]['bom_2_req_consld_id'];
            }
            else {
                $bom_1_req_consld_id = "";
            }
            
            $result[$i] = ['edit', $bom_1_req_consld_id, $data[$i]['bom_2_req_id'], $data[$i]['item_desc'], $data[$i]['blend'],
                            $data[$i]['content'], $data[$i]['material'], 
                            $data[$i]['garment_size'], $data[$i]['appr_item_code'], $data[$i]['appr_item_col_code'], 
                            $data[$i]['size_dim'], $data[$i]['uom'], $data[$i]['req_bom_qty'], $excess_qty, 
                            $plan_bom_qty, $data[$i]['requirement_uom']];
        }
        
        $itemDescription = $blendContentMaterial = $garmentSize = $itemCode = $itemColor = $sizeDimension = $uom = [];
        foreach ($result as $key => $value) {
            $bcm = $value[4] .' / '. $value[5] . ' / ' . $value[6];
            // array data
            $itemData = [ 'id'=> $value[3], 'type' => 'bom2' ,'name' => $value[3] ];
            $bcmData = [ 'id'=> $bcm, 'item_id' => $value[3], 'type' => 'bom2','name' => $bcm ];
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
        $output['type'] = 'bom2';
        return $output;
    }



    public function fabricMIDetails($id) {
        
        $itemized_data = $this->getItemizedFabricRequirementDetailss($id);

        $compacting_sql = "SELECT * FROM tbl_fab_wash_compacting_heat WHERE enquiry_id = '$id' ";
        $compactingData = $this->db->query($compacting_sql)->result_array();
        
        $itemized_fabric_data = [];

        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $dyeing = $itemized_data[$i][13];
            array_push($itemized_fabric_data, $itemized_data[$i]);
        }

        $array = $finalValue = [];

        foreach ($itemized_fabric_data as $index => $value)
        {
            $combo = $value[2];
            $component = $value[3];
            $colour = $value[4];
            $gpdname = $value[5];
            $blend = $value[6];
            $content = $value[7];
            $feed = $value[11];
            $lycra = $value[12];

            if($combo == '' && $component == '' && $colour == '' && $gpdname == '') { }
            else {
                $split_blend = $split_content = $split_feed = [];

                $split = explode(";", $blend);
                $split = array_filter($split);
                foreach ($split as $key => $value) {
                    array_push($split_blend, $value);
                }

                $split = explode(";", $content);
                $split = array_filter($split);
                foreach ($split as $key => $value) {
                    array_push($split_content, $value);
                }

                $split = explode("/", $feed);
                $split = array_filter($split);
                foreach ($split as $key => $value) {
                    array_push($split_feed, $value);
                }

                $total_feed_val = array_sum($split_feed);

                $fabric_blend = $arr_fabric_blend = $split_blend_data = $blend_lycra = $arr_blend_lycra = $arr_fabric_content = $fin_blend_lycra = $arr_itemized_content = [];
                
                for($i=0; $i < sizeof($split_blend); $i++) 
                {
                    $sql2 = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$split_blend[$i]."' ";
                    $feed_data = $this->db->query($sql2)->result_array();
                    $blend_data = $feed_data[0]['misc_name'];

                    $sql2 = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$split_content[$i]."' ";
                    $feed_data = $this->db->query($sql2)->result_array();
                    $content_data = $feed_data[0]['misc_name'];

                    $split_blend_data = $this->multiexplode(array("+",'/',":"), $blend_data);
                    $fin_split_content = $this->multiexplode(array("+",'/',":"), $content_data);

                    $fabric_blend_val = (float)$split_feed[$i] * 100 / (float)$total_feed_val;
                    array_push($fabric_blend, round($fabric_blend_val));
                        
                    for ($j=0; $j < sizeof($split_blend_data); $j++) { 
                        array_push($arr_fabric_blend, $fabric_blend[$i]);
                        array_push($arr_itemized_content, trim($split_blend_data[$j]));
                        array_push($arr_fabric_content, trim($fin_split_content[$j]));
                    }
                    
                }
                
                $total_fabric_blend = array_sum($fabric_blend);

                for ($i=0; $i < sizeof($split_blend); $i++) { 
                    $blend_lycra_val = $fabric_blend[$i] * ($total_fabric_blend - $lycra) / 100;
                    array_push($blend_lycra, round($blend_lycra_val));
                }
                
                for($i=0; $i < sizeof($split_blend); $i++) 
                {
                    $sql2 = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$split_blend[$i]."' ";
                    $feed_data = $this->db->query($sql2)->result_array();
                    $blend_data = $feed_data[0]['misc_name'];

                    $split_blend_data = $this->multiexplode(array("+",'/',":"), $blend_data);
                        
                    for ($j=0; $j < sizeof($split_blend_data); $j++) { 
                        array_push($arr_blend_lycra, $blend_lycra[$i]);
                    }
                    
                }

                for ($i=0; $i < sizeof($arr_blend_lycra); $i++) { 
                    $fin_blend = (float)$arr_blend_lycra[$i] * (float)$arr_itemized_content[$i] / 100;
                    array_push($fin_blend_lycra, (float)$fin_blend);
                }

                $unique_content = array_values(array_unique($arr_fabric_content));

                $unique_fabric_blend = [];
                $final_fabric_content = '';
                $final_fabric_blend = '';
                for ($j=0; $j < sizeof($unique_content); $j++) {
                    $s_content = $unique_content[$j];
                    $totalPrice = 0;
                    for ($k=0; $k < sizeof($arr_fabric_content); $k++) {
                        $n_content = $arr_fabric_content[$k];
                        if($s_content == $n_content) {
                            $totalPrice += (int)$fin_blend_lycra[$k];
                        }
                    }
                    if($j == 0) { 
                        $final_fabric_content = $unique_content[$j];
                        $final_fabric_blend = $totalPrice;
                    }
                    else if($j > 0) { 
                        $final_fabric_content = $final_fabric_content.' : '.$unique_content[$j]; 
                        $final_fabric_blend = $final_fabric_blend.' : '.$totalPrice; 
                    }

                }

                if($lycra == '' || $lycra == '0' || $lycra == 0) { }
                else {
                    $final_fabric_content = $final_fabric_content.' : Lycra';
                    $final_fabric_blend = $final_fabric_blend.' : '.$lycra;
                }

                $itemized_fabric_data[$index][6] = $final_fabric_blend;
                $itemized_fabric_data[$index][7] = $final_fabric_content;
            }
        }

        $finalValue = [];

        for ($i=0; $i < sizeof($itemized_fabric_data); $i++) {
            $combineValue = '';
            if($itemized_fabric_data[$i][2] == '' && $itemized_fabric_data[$i][17] == '')
            {
                $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $itemized_fabric_data[$i][14], $itemized_fabric_data[$i][15]
                    ];
            }
            else if($itemized_fabric_data[$i][17] != '')
            {
                $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ];
            }
            else
            {
                if(!isset($compactingData[$i])) {
                    $combineValue = [ '', '', $itemized_fabric_data[$i][2], $itemized_fabric_data[$i][3], $itemized_fabric_data[$i][4], $itemized_fabric_data[$i][5],
                            $itemized_fabric_data[$i][6], $itemized_fabric_data[$i][7],
                            $itemized_fabric_data[$i][9], $itemized_fabric_data[$i][13], '', '', $itemized_fabric_data[$i][10], '', '', '', '', $itemized_fabric_data[$i][14], 
                            $itemized_fabric_data[$i][15]
                        ];
                }
                else {
                    $combineValue = [ 'edit', '', $itemized_fabric_data[$i][2], $itemized_fabric_data[$i][3], $itemized_fabric_data[$i][4], $itemized_fabric_data[$i][5],
                            $itemized_fabric_data[$i][6], $itemized_fabric_data[$i][7],
                            $itemized_fabric_data[$i][9], $itemized_fabric_data[$i][13], $compactingData[$i]['fab_wash_req'], $compactingData[$i]['dry_set_req'], $itemized_fabric_data[$i][10], 
                            $compactingData[$i]['shrink_l'], $compactingData[$i]['shrink_w'], $compactingData[$i]['spirality_acc'], $compactingData[$i]['knit_dia_dim'], 
                            $itemized_fabric_data[$i][14], $itemized_fabric_data[$i][15]
                        ];
                }
            }
            array_push($finalValue, $combineValue);
        }
        
        $fabricColor = $fabricGarment = $fabricBlend = $fabricContent = $fabricName = $fabricGSM = $fabricDIA = $fabricUOM = [];

        // return $finalValue;

        foreach ($finalValue as $key => $value) {
            if($value[2] != '')
            {

                $fabric_name_sql = "SELECT misc_name FROM kn_master_fabric_misc WHERE id = ".$value[8];
                $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

                $o_color = $value[4];
                $o_garment = $value[5];
                $o_blend = $value[6];
                $o_content = $value[7];
                $o_name = $fabric_name_data[0]['misc_name'];
                $o_gsm = $value[12];
                // array data
                $colorData = [ 'id'=> $value[4], 'name' => $value[4] ];
                $garmentData = [ 'id'=> $value[5], 'color_id' => $value[4], 'name' => $value[5] ];
                $blendData = [ 'id'=> $value[6], 'color_id' => $value[4], 'garment_id'=> $value[5], 'name' => $value[6] ];
                $contentData = [ 'id'=> $value[7], 'color_id' => $value[4], 'garment_id'=> $value[5], 'blend_id'=> $value[6], 'name' => $value[7] ];
                $nameData = [ 'id'=> $o_name, 'color_id' => $value[4], 'garment_id'=> $value[5], 'blend_id'=> $value[6], 'content_id' => $value[7], 'name' => $o_name ];
                $gsmData = [ 'id'=> $value[12], 'color_id' => $value[4], 'garment_id'=> $value[5], 'blend_id'=> $value[6], 'content_id' => $value[7], 'name_id' => $o_name, 'name' => $value[12] ];
                // count set
                $count = $garmentCount = $blendCount = $contentCount = $nameCount = $gsmCount = 0;
                // loop
                for ($i=0; $i < sizeof($fabricColor); $i++) { if($value[4] == $fabricColor[$i]['name']) { $count++; } }
                for ($i=0; $i < sizeof($fabricGarment); $i++) { if($value[4] == $fabricGarment[$i]['color_id'] && $value[5] == $fabricGarment[$i]['name']) { $garmentCount++; } }
                for ($i=0; $i < sizeof($fabricBlend); $i++) { if($value[4] == $fabricBlend[$i]['color_id'] && $value[5] == $fabricBlend[$i]['garment_id'] && $value[5] == $fabricBlend[$i]['name']) { $blendCount++; } }
                for ($i=0; $i < sizeof($fabricContent); $i++) { if($value[4] == $fabricContent[$i]['color_id'] && $value[5] == $fabricContent[$i]['garment_id'] && $value[6] == $fabricContent[$i]['blend_id'] && $value[7] == $fabricContent[$i]['name']) { $contentCount++; } }
                for ($i=0; $i < sizeof($fabricName); $i++) { if($value[4] == $fabricName[$i]['color_id'] && $value[5] == $fabricName[$i]['garment_id'] && $value[6] == $fabricName[$i]['blend_id'] && $value[7] == $fabricName[$i]['content_id'] && $o_name == $fabricName[$i]['name']) { $nameCount++; } }
                for ($i=0; $i < sizeof($fabricGSM); $i++) { if($value[4] == $fabricGSM[$i]['color_id'] && $value[5] == $fabricGSM[$i]['garment_id'] && $value[6] == $fabricGSM[$i]['blend_id'] && $value[7] == $fabricGSM[$i]['content_id'] && $o_name == $fabricGSM[$i]['name_id'] && $value[12] == $fabricGSM[$i]['name']) { $gsmCount++; } }
                // array push
                if($count == 0) { array_push($fabricColor, $colorData); }
                if($garmentCount == 0) { array_push($fabricGarment, $garmentData); }
                if($blendCount == 0) { array_push($fabricBlend, $blendData); }
                if($contentCount == 0) { array_push($fabricContent, $contentData); }
                if($nameCount == 0) { array_push($fabricName, $nameData); }
                if($gsmCount == 0) { array_push($fabricGSM, $gsmData); }
            }
            if($value[17] != '')
            {
                // array data
                $diaData = [ 'id'=> $value[17], 'color_id' => $o_color, 'garment_id'=> $o_garment, 'blend_id'=> $o_blend, 'content_id' => $o_content, 'name_id' => $o_name, 'gsm_id' => $o_gsm, 'name' => $value[17] ];
                $uomData = [ 'id'=> $value[18], 'color_id' => $o_color, 'garment_id'=> $o_garment, 'blend_id'=> $o_blend, 'content_id' => $o_content, 'name_id' => $o_name, 'gsm_id' => $o_gsm, 'dia_id' => $value[17], 'name' => $value[18] ];
                // count set
                $diaCount = $uomCount = 0;
                // loop
                for ($i=0; $i < sizeof($fabricDIA); $i++) { if($o_color == $fabricDIA[$i]['color_id'] && $o_garment == $fabricDIA[$i]['garment_id'] && $o_blend == $fabricDIA[$i]['blend_id'] && $o_content == $fabricDIA[$i]['content_id'] && $o_name == $fabricDIA[$i]['name_id'] && $o_gsm == $fabricDIA[$i]['gsm_id'] && $value[17] == $fabricDIA[$i]['name']) { $diaCount++; } }
                for ($i=0; $i < sizeof($fabricUOM); $i++) { if($o_color == $fabricUOM[$i]['color_id'] && $o_garment == $fabricUOM[$i]['garment_id'] && $o_blend == $fabricUOM[$i]['blend_id'] && $o_content == $fabricUOM[$i]['content_id'] && $o_name == $fabricUOM[$i]['name_id'] && $o_gsm == $fabricUOM[$i]['gsm_id'] && $value[17] == $fabricUOM[$i]['dia_id'] && $value[18] == $fabricUOM[$i]['name']) { $uomCount++; } }
                // array push
                if($diaCount == 0) { array_push($fabricDIA, $diaData); }
                if($uomCount == 0) { array_push($fabricUOM, $uomData); }
            }
        }

        $output['fabricColor'] = $fabricColor;
        $output['fabricGarment'] = $fabricGarment;
        $output['fabricBlend'] = $fabricBlend;
        $output['fabricContent'] = $fabricContent;
        $output['fabricName'] = $fabricName;
        $output['fabricGSM'] = $fabricGSM;
        $output['fabricDIA'] = $fabricDIA;
        $output['fabricUOM'] = $fabricUOM;
        return $output;
    }

    public function getItemizedFabricRequirementDetailss($id) {

        $consolidated_sql = "SELECT a.*, b.gpdname, min(fab_color_wise_garment_part_id) as min_id
                            FROM tbl_fab_color_wise_garment_parts a
                            INNER JOIN kn_master_garment_part_desc b ON a.gar_parts=b.id
                            WHERE a.enquiry_id='$id' 
                            group by a.combo, a.component, a.colour, a.gar_parts
                            ORDER BY min_id asc";
        $consolidated_data = $this->db->query($consolidated_sql)->result_array();

        $itemized_fabric_sql = "SELECT * FROM tbl_fab_itemized_fabric_requirement
                            WHERE enquiry_id='$id'  
                            ORDER BY itemized_fabric_requirement_id ASC";
        $itemized_fabric_data = $this->db->query($itemized_fabric_sql)->result_array();

        $color_wise_data = $this->getFabricSizeSpecCodeDetailss($id);

        $size_wise_dia_data = $this->get_sizewise_dia_dimensionn($id);

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);

        // ******* GET DATA DETAILS START ********* //

        $finalValue = [];
        $referenceArr = [];

        for($i=0; $i < sizeof($consolidated_data); $i++) 
        {
            $combo = $consolidated_data[$i]['combo'];
            $component = $consolidated_data[$i]['component'];
            $gpdname = $consolidated_data[$i]['gpdname'];
            $colour = $consolidated_data[$i]['colour'];
            $fil_sizes = $fil_prices = $mer_sizes = $uom = [];
            for ($j=0; $j < sizeof($size_wise_dia_data); $j++) {

                $component2 = $size_wise_dia_data[$j][3];
                $gpdname2 = $size_wise_dia_data[$j][4];
                array_push($uom, $size_wise_dia_data[$j][7]);
                if($component === $component2 && $gpdname === $gpdname2)
                {
                    $sizes = [];
                    for ($k=8; $k < sizeof($size_wise_dia_data[$j]); $k++) {
                        array_push($sizes, $size_wise_dia_data[$j][$k]);
                        array_push($mer_sizes, $size_wise_dia_data[$j][$k]);
                    }
                    array_push($fil_sizes, $sizes);
                }
            }

            for ($j=0; $j < sizeof($color_wise_data); $j++) { 
                $combo2 = $color_wise_data[$j][3];
                $component2 = $color_wise_data[$j][4];
                $colour2 = $color_wise_data[$j][5];
                $gpdname2 = $color_wise_data[$j][6];
                if($component === $component2 && $gpdname === $gpdname2 && $combo === $combo2 && $colour === $colour2)
                {
                    $prices = [];
                    for ($k=8; $k < sizeof($color_wise_data[$j]); $k++) {
                        array_push($prices, $color_wise_data[$j][$k]);
                    }
                    array_push($fil_prices, $prices);
                }
            }

            $dis_sizes = array_values(array_unique($mer_sizes));
            
            $fin_prices = [];
            for ($j=0; $j < sizeof($dis_sizes); $j++) { 
                $dis_size = $dis_sizes[$j];
                $totalPrice = 0;
                for ($k=0; $k < sizeof($fil_sizes); $k++) { 
                    $asas = $fil_sizes[$k];
                    for ($l=0; $l < sizeof($asas); $l++) { 
                        $fil_size = $fil_sizes[$k][$l];
                        if($dis_size == $fil_size) {
                            $totalPrice += (float)$fil_prices[$k][$l];
                            $totalPrice = number_format((float)$totalPrice, 3, '.', '');
                        }
                    }
                }
                array_push($fin_prices, $totalPrice);
            }

            $totalSizePrice = number_format((float)array_sum($fin_prices), 3, '.', '');

            for ($j=0; $j < sizeof($dis_sizes); $j++) { 
                if($j == 0)
                {
                    if(!isset($itemized_fabric_data[$i]))
                    {
                        $combineValue = [ '', '', $consolidated_data[$i]['combo'], $consolidated_data[$i]['component'],
                                        $consolidated_data[$i]['colour'], $consolidated_data[$i]['gpdname'], '', '', '', '', '', '', '', '',
                                        $dis_sizes[$j], $uom[$j], $fin_prices[$j], '', '' ];
                    }
                    else
                    {
                        $combineValue = [ 'edit', $itemized_fabric_data[$i]['itemized_fabric_requirement_id'], $consolidated_data[$i]['combo'], 
                                        $consolidated_data[$i]['component'], $consolidated_data[$i]['colour'], $consolidated_data[$i]['gpdname'], 
                                        $itemized_fabric_data[$i]['yarn_blend'], $itemized_fabric_data[$i]['yarn_content'], $itemized_fabric_data[$i]['yarn_count'],
                                        $itemized_fabric_data[$i]['fabric_name'], $itemized_fabric_data[$i]['finishing_gsm'], $itemized_fabric_data[$i]['no_of_feed_pi'],
                                        $itemized_fabric_data[$i]['lycra'], $itemized_fabric_data[$i]['dyeing_type'], $dis_sizes[$j], $uom[$j], 
                                        $fin_prices[$j], '', $totalSizePrice ];
                    }
                }
                else if($j > 0)
                {
                    $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', $dis_sizes[$j], $uom[$j], $fin_prices[$j], '', '' ];
                }
                array_push($finalValue, $combineValue);
            }

            $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $totalSizePrice, '' ];
            array_push($finalValue, $combineValue);

        }

        return $finalValue;
    }

    public function getFabricSizeSpecCodeDetailss($id) {
                        
        $consolidated_sql = "SELECT a.*, b.gpdname, group_concat(a.po_enq SEPARATOR' / ') as all_po_enq FROM tbl_fab_color_wise_garment_parts a 
                            INNER JOIN kn_master_garment_part_desc b ON a.gar_parts=b.id 
                            WHERE a.enquiry_id='$id'  
                            group by a.combo, a.component, a.colour, a.gar_parts, a.spec_code  
                            ORDER BY a.fab_color_wise_garment_part_id ASC";
        $consolidated_data = $this->db->query($consolidated_sql)->result_array();

        $color_wise_sql = $this->getFabricProcessLossDetailss($id);

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);
       
        // ******* GET DATA DETAILS STARTS ********* //

        $finalValue = [];
        $referenceArr = [];
        for($i=0; $i < sizeof($consolidated_data); $i++) 
        {
            $combineValue = ["", "", $consolidated_data[$i]['all_po_enq'], $consolidated_data[$i]['combo'], $consolidated_data[$i]['component'],
                            $consolidated_data[$i]['colour'], $consolidated_data[$i]['gpdname'], $consolidated_data[$i]['spec_code']];

            $sizeArr = [];
            foreach ($sizeMaster as $key => $value) {
                array_push($sizeArr, 0);             
            }
            $combineValue = array_merge($combineValue, $sizeArr);
            $combineValue = array_merge($combineValue, [0]);
            array_push($referenceArr, $combineValue);             
        }  

        for($i=0; $i < sizeof($referenceArr); $i++) 
        {
            $a = $referenceArr[$i][3];
            $b = $referenceArr[$i][4];
            $c = $referenceArr[$i][5];
            $d = $referenceArr[$i][6];
            $e = $referenceArr[$i][7];

            for($j=0; $j < sizeof($color_wise_sql); $j++) 
            {
                $aa = $color_wise_sql[$j][3];
                $bb = $color_wise_sql[$j][4];
                $cc = $color_wise_sql[$j][5];
                $dd = $color_wise_sql[$j][6];
                $ee = $color_wise_sql[$j][7];

                if($a == $aa && $b == $b && $c == $cc && $d == $dd && $e == $ee) {
                    
                    for($k=0; $k < sizeof($color_wise_sql[$j]); $k++) 
                    {
                        if($k >= 8){
                            $referenceArr[$i][$k] = $referenceArr[$i][$k] + $color_wise_sql[$j][$k];
                            $referenceArr[$i][$k] = number_format((float)$referenceArr[$i][$k], 3, '.', '');
                        }
                    }
                }
            }
            array_push($finalValue, $referenceArr[$i]);
        }
   
        // ******* GET THE DATA DETAILS ENDS ********* //

        return $finalValue;
    }

    public function get_sizewise_dia_dimensionn($id) {

        $sql = "SELECT min(fab_color_wise_garment_part_id) as min_id, a.fab_color_wise_garment_part_id, a.component, a.spec_code, a.enquiry_id, b.gpdname as gar_parts,
                group_concat(fab_color_wise_garment_part_id) as selected_ids
                FROM tbl_fab_color_wise_garment_parts a
                INNER JOIN kn_master_garment_part_desc b ON a.gar_parts=b.id
                WHERE a.enquiry_id='$id' 
                group by a.component, a.gar_parts, a.spec_code
                ORDER BY min_id asc";
        $data = $this->db->query($sql)->result_array();

        $consl_sql = "SELECT * FROM tbl_fab_final_dia_dim WHERE enquiry_id='$id'  ORDER BY fab_final_dia_dim_id asc";
        $consl_data = $this->db->query($consl_sql)->result_array();

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);


        // ******* GET THE DATA DETAILS START ********* //
        $finalValue = [];
        if(sizeof($consl_data) == 0) {

            foreach($data as $key => $value) {
                $resultValue = ['', '', $value['selected_ids'], $value["component"], $value["gar_parts"], $value["spec_code"], "", ""];
                $emp = [];
                foreach ($sizeMaster as $key => $value)
                {
                    array_push($emp, "");
                }
                $finalData = [""];
                $resultValue = array_merge($resultValue, $emp);
                $resultValue = array_merge($resultValue, $finalData);
                array_push($finalValue, $resultValue);
            }
        }
        else {
            if(sizeof($data) == sizeof($consl_data)) {
                for($i = 0; $i < sizeof($data); $i++) 
                {
                    $selected_ids = $data[$i]["selected_ids"];
                    $color_wise_garment_part_id = $consl_data[$i]["fab_color_wise_garment_part_id"];
                    $description = $consl_data[$i]["description"];
                    $uom = $consl_data[$i]["uom"];
                    
                    $sizess = $consl_data[$i]['piece_sizes'];
                    $array_sizes = explode(',', $sizess);
                    $piece_sizes = [];
                    for($j=0; $j<sizeof($array_sizes); $j++) {
                        array_push($piece_sizes, $array_sizes[$j]);
                    }
                    
                    $resultValue = ['edit', $consl_data[$i]['fab_final_dia_dim_id'], $selected_ids, $data[$i]["component"], $data[$i]["gar_parts"], $data[$i]["spec_code"], $description, $uom];
                    $resultValue = array_merge($resultValue, $piece_sizes);
                    // $finalData = [$consl_data[$i]['avg_weight_piece']];
                    // $resultValue = array_merge($resultValue, $finalData);
                    array_push($finalValue, $resultValue);

                }
            }
            else if(sizeof($data) > sizeof($consl_data)) {
                for($i = 0; $i < sizeof($data); $i++) 
                {
                    // *** DATA *** //
                    if($i+1 > sizeof($consl_data)) 
                    {
                        // *** ADDING NEW DATA IN AN ARRAY *** //
                        $resultValue = ['', '', $data[$i]['selected_ids'], $data[$i]["component"], $data[$i]["gar_parts"], $data[$i]["spec_code"], "", ""];
                        $emp = [];
                        foreach ($sizeMaster as $key => $value)
                        {
                            array_push($emp, "");
                        }
                        $finalData = [""];
                        $resultValue = array_merge($resultValue, $emp);
                        $resultValue = array_merge($resultValue, $finalData);
                        array_push($finalValue, $resultValue);
                    }
                    else {
                        // *** ADDING EXISTING DATA IN AN ARRYA *** //
                        $selected_ids = $data[$i]["selected_ids"];
                        $color_wise_garment_part_id = $consl_data[$i]["fab_color_wise_garment_part_id"];
                        $description = $consl_data[$i]["description"];
                        $uom = $consl_data[$i]["uom"];
                        
                        $sizess = $consl_data[$i]['piece_sizes'];
                        $array_sizes = explode(',', $sizess);
                        $piece_sizes = [];
                        for($j=0; $j<sizeof($array_sizes); $j++)
                        {
                            array_push($piece_sizes, $array_sizes[$j]);
                        }
                        
                        $resultValue = ['edit', $consl_data[$i]['fab_final_dia_dim_id'], $selected_ids, $data[$i]["component"], $data[$i]["gar_parts"], $data[$i]["spec_code"], $description, $uom];
                        $resultValue = array_merge($resultValue, $piece_sizes);
                        // $finalData = [$consl_data[$i]['avg_weight_piece']];
                        // $resultValue = array_merge($resultValue, $finalData);
                        array_push($finalValue, $resultValue);
                    }
                }
            }
            else if(sizeof($consl_data) > sizeof($data)) {
                for($i = 0; $i < sizeof($consl_data); $i++) 
                {
                    // *** DATA *** //
                    if($i+1 > sizeof($data)) 
                    {
                        
                    }
                    else {
                        // *** ADDING EXISTING DATA IN AN ARRYA *** //
                        $selected_ids = $data[$i]["selected_ids"];
                        $color_wise_garment_part_id = $consl_data[$i]["fab_color_wise_garment_part_id"];
                        $description = $consl_data[$i]["description"];
                        $uom = $consl_data[$i]["uom"];
                        
                        $sizess = $consl_data[$i]['piece_sizes'];
                        $array_sizes = explode(',', $sizess);
                        $piece_sizes = [];
                        for($j=0; $j<sizeof($array_sizes); $j++)
                        {
                            array_push($piece_sizes, $array_sizes[$j]);
                        }
                        
                        $resultValue = ['edit', $consl_data[$i]['fab_final_dia_dim_id'], $selected_ids, $data[$i]["component"], $data[$i]["gar_parts"], $data[$i]["spec_code"], $description, $uom];
                        $resultValue = array_merge($resultValue, $piece_sizes);
                        // $finalData = [$consl_data[$i]['avg_weight_piece']];
                        // $resultValue = array_merge($resultValue, $finalData);
                        array_push($finalValue, $resultValue);
                    }
                }
            }
        }
        // ******* GET THE DATA DETAILS ENDS ********* //
        
        return $finalValue;
    }

    public function getFabricProcessLossDetailss($id) {

        $color_wise_sql = "SELECT a.*, b.gpdname FROM tbl_fab_color_wise_garment_parts a 
                           INNER JOIN kn_master_garment_part_desc b ON a.gar_parts=b.id 
                           WHERE a.enquiry_id='$id' 
                           ORDER BY a.fab_color_wise_garment_part_id ASC";
        $color_wise_data = $this->db->query($color_wise_sql)->result_array();

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);

        // ******* GET THE DATA DETAILS ENDS ********* //

        $finalValue = [];
            // *** final data *** //
        for($i=0; $i < sizeof($color_wise_data); $i++) {
            $component = $color_wise_data[$i]['component'];
            $spec_code = $color_wise_data[$i]['spec_code'];
            $ex_qty = $color_wise_data[$i]['ex_qty'];

            $fab_color_wise_garment_part_id = $color_wise_data[$i]['fab_color_wise_garment_part_id'];
            $getData = "select * from tbl_fab_garment_piece_weight ";
            $getData_sql = $this->db->query($getData)->result_array();
            
            $piece_sizes = [];
            for ($j=0; $j < sizeof($getData_sql); $j++) { 
                $ids =  $getData_sql[$j]['fab_color_wise_garment_part_id'];
                $array_ids = explode(',', $ids);
                for ($k=0; $k < sizeof($array_ids); $k++) { 
                    if($fab_color_wise_garment_part_id == $array_ids[$k])
                    {
                        $piece_sizes = $getData_sql[$j]['piece_sizes'];
                    }
                }
            }

            $combineValue = ['edit', $color_wise_data[$i]['fab_color_wise_garment_part_id'], $color_wise_data[$i]['po_enq'], 
            $color_wise_data[$i]['combo'], $component, $color_wise_data[$i]['colour'], 
            $color_wise_data[$i]['gpdname'], $spec_code ];
            
            $sizes = $color_wise_data[$i]['sizes'];
            // $piece_sizes = $color_wise_data[$i]['piece_sizes'];

            $array_sizes = explode(',', $sizes);
            $array_piece_sizes = explode(',', $piece_sizes);
            
            $consumption_calc_sizes = [];
            for($j=0;$j<sizeof($array_sizes);$j++)
            {
                $dis_qty_size = $array_sizes[$j] * $ex_qty / 100;
                $ex_qty_size = $array_sizes[$j] + $dis_qty_size;
                $calc_sizes = (float)$array_piece_sizes[$j] * (float)$ex_qty_size;
                $calc_sizes = number_format((float)$calc_sizes, 3, '.', '');
                $process_loss = $color_wise_data[$i]['process_loss'];

                $calc_sizes = (float)$calc_sizes * (int)$process_loss / 100 + (float)$calc_sizes;
                
                $calc_sizes = number_format((float)$calc_sizes, 3, '.', '');

                array_push($consumption_calc_sizes, $calc_sizes);
            }

            $combineValue = array_merge($combineValue, $consumption_calc_sizes);
            $sum_of_sizes = array_sum($consumption_calc_sizes);
            $f_sum_sizes = array(number_format((float)$sum_of_sizes, 3, '.', ''));
            $combineValue = array_merge($combineValue, $f_sum_sizes);
            array_push($finalValue, $combineValue);
        }                

        // ******* GET THE DATA DETAILS START ********* //

        return $finalValue;
    }

    public function multiexplode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
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
        $requestValue['companyid'] = $this->companyid;
        $requestValue['req_by'] = $this->userid;
        $requestValue['draft_status'] = 0;
        $requestValue['log'] = LOGTIME;

        // return $requestValue;
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
                    $sampleValue['log'] = LOGTIME;
    
                    $this->db->insert('tbl_request_sample', $sampleValue);
                    // update request sent status 
                    $this->db->where('sample_requirement_id', $value[1]);
                    $this->db->update('tbl_sample_requirement', array('req_reference_status' => 1, 'req_draft_status'=> 1, 'log'=> LOGTIME));
                }
            }
        } else {
            $this->db->where('request_id', $req_id);
            $this->db->update('tbl_request', array('log'=>LOGTIME));

            foreach($req_data as $key => $value) {
                if($value[0] == "edit") {
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
                    $sampleValue_update['log'] = LOGTIME;

                    $this->db->where('sample_id', $value[1]);
                    $this->db->update('tbl_request_sample', $sampleValue_update);
                }
                else if($value[0] == "") {
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
                    $sampleValue_ins['log'] = LOGTIME;
    
                    $this->db->insert('tbl_request_sample', $sampleValue_ins);
                    // update request sent status 
                    $this->db->where('sample_requirement_id', $value[1]);
                    $this->db->update('tbl_sample_requirement', array('req_reference_status' => 1, 'req_draft_status'=> 1, 'log'=> LOGTIME));
                }
            } 

        }
        
    }

    // ********** CREATE SAMPLE REQUEST ENDS HERE *********** /

    // ********** CREATE SAMPLE REQUEST ENDS HERE *********** /

    public function checkDraftorNot($id)
    {
        $result = $this->db->from('tbl_sample_requirement')->where('enquiry_id', $id)->where('req_draft_status', 1)->get()->num_rows();
        return $result;
    }

    public function bomMITableData($id) {
        $result = $this->db->from('tbl_sample_requirement')->where('enquiry_id', $id)->where('req_draft_status', 1)->get()->result_array();
        return $result;
    }
    public function bomMITableDataqty($id) {
       $query = "SELECT a.appr_item_code, SUM(a.dc_qty) as total_qty FROM tbl_bom_in_house a INNER JOIN tbl_request b ON a.request_id = b.request_id 
       INNER JOIN tbl_purchase_indent c ON a.purchase_indent_id = c.purchase_indent_id WHERE a.order_stock_status = 1 AND b.enquiry_id = " . $id . " 
       AND a.mgmt_ovrd_status NOT IN (2, 3, 4) GROUP BY a.appr_item_code";
        $result = $this->db->query($query)->result_array();
        //print_r($result);
        return $result;
    }

    public function fabricMITableData($id) {
        $result = $this->db->from('tbl_sample_requirement')->where('enquiry_id', $id)->where('req_draft_status', 1)->get()->result_array();
        return $result;
    }

    public function miDetails($id) {
        $result = $this->db->from('tbl_mi_details')->where('enquiry_id', $id)->where('req_sent_status', 0)->get()->result_array();
        if(sizeof($result) == 0)
        {
            $result[0]['cad_req_date'] = '';
            $result[0]['cad_cutoff_date'] = '';
            $result[0]['cad_dept'] = '';
            $result[0]['fab_req_date'] = '';
            $result[0]['fab_cutoff_date'] = '';
            $result[0]['fab_dept'] = '';
            $result[0]['bom_req_date'] = '';
            $result[0]['bom_cutoff_date'] = '';
            $result[0]['bom_dept'] = '';
        }
        return $result;
    }

    
    public function sendbomMITableData($id, $reqId) {
        $result = $this->db->from('tbl_request_sample a')
                    ->join('tbl_sample_requirement b', 'a.sample_id=b.sample_requirement_id', 'inner')
                    ->where('b.enquiry_id', $id)
                    ->where('a.request_id', $reqId)
                    ->where('req_sent_status', 1)
                    ->get()->result_array();
        return $result;
    }
    
    public function sendbomMITableDatass($id, $reqId) {
        $result = $this->db->from('tbl_mi_details a')
                    ->join('tbl_mi_bom b', 'a.request_id=b.request_id', 'inner')
                    ->where('a.enquiry_id', $id)
                    ->where('a.request_id', $reqId)
                    ->where('req_sent_status', 1)
                    ->get()->result_array();
        return $result;
    }
    
    public function seperatebomMITableData($id, $reqId, $samId) {
        $result = $this->db->from('tbl_request_sample a')
                    ->join('tbl_sample_requirement b', 'a.sample_id=b.sample_requirement_id', 'inner')
                    ->where('b.enquiry_id', $id)
                    ->where('a.request_id', $reqId)
                    ->where('b.sample_requirement_id', $samId)
                    ->where('req_sent_status', 1)
                    ->get()->result_array();
        return $result;
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
    public function sendmiDetails($id, $reqId) {
        $result = $this->db->from('tbl_mi_details')->where('request_id', $reqId)->get()->result_array();
        if(sizeof($result) == 0)
        {
            $result[0]['cad_req_date'] = '';
            $result[0]['cad_cutoff_date'] = '';
            $result[0]['cad_dept'] = '';
            $result[0]['fab_req_date'] = '';
            $result[0]['fab_cutoff_date'] = '';
            $result[0]['fab_dept'] = '';
            $result[0]['bom_req_date'] = '';
            $result[0]['bom_cutoff_date'] = '';
            $result[0]['bom_dept'] = '';
        }
        return $result;
    }
    
    public function getBomStatus($req_id)
    {
        $mi_bom_id = $this->db->where('request_id',$req_id)->get('tbl_mi_bom')->row()->mi_bom_id;
        
        $bomStatus = $this->db->where('mi_bom_id',$mi_bom_id)->where('bom_status',1)->get('tbl_mi_bom_details')->result_array();
        
        return $bomStatus;
    }

    public function clearSampleReqDetailss($req_data, $req_id)
    {

        foreach ($req_data as $key => $value) {
            
            // clear from sample requirement table
            $this->db->where('request_id', $req_id);
            $this->db->delete('tbl_request');

            // clear from sample requirement table
            $this->db->where('sample_id', $value[1]);
            $this->db->delete('tbl_request_sample');

            // update request sent status 
            $this->db->where('sample_requirement_id', $value[1]);
            $this->db->update('tbl_sample_requirement', array('req_reference_status'=> 0, 'req_draft_status'=> 0, 'log'=> LOGTIME));
            
            // clear from sample requirement table
            $this->db->where('request_id', $req_id);
            $this->db->delete('tbl_mi_details');

            // clear from cad material indent table
            $this->db->where('sample_req_id', $value[1]);
            $this->db->where('request_id', $req_id);
            $this->db->delete('tbl_mi_cad_details');

            $sql = "SELECT mi_fabric_id FROM tbl_mi_fabric WHERE sample_req_id = ".$value[1]." AND request_id = $req_id ";
            $data = $this->db->query($sql)->result_array();

            // clear from fabric material indent table
            $this->db->where('sample_req_id', $value[1]);
            $this->db->where('request_id', $req_id);
            $this->db->delete('tbl_mi_fabric');

            if(sizeof($data) > 0)
            {
                $this->db->where('mi_fabric_id', $data[0]['mi_fabric_id']);
                $this->db->delete('tbl_mi_fabric_details');
            }

            $sql = "SELECT mi_bom_id FROM tbl_mi_bom WHERE sample_req_id = ".$value[1]." AND request_id = $req_id ";
            $data = $this->db->query($sql)->result_array();

            // clear from bom material indent table
            $this->db->where('sample_req_id', $value[1]);
            $this->db->where('request_id', $req_id);
            $this->db->delete('tbl_mi_bom');

            if(sizeof($data) > 0)
            {
                $this->db->where('mi_bom_id', $data[0]['mi_bom_id']);
                $this->db->delete('tbl_mi_bom_details');
            }
            
        }
        $result['status'] = 'success';
        return $result;
    }

    // ********** CREATE SAMPLE REQUEST ENDS HERE *********** /

    // ********** SAMPLE REQUEST STARTS HERE *********** /

    public function getManagementSampleRequestDetailss($id) {
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE a.enquiry_id = " . $id . "  and a.req_sent_status = 1";
        $data = $this->db->query($sql)->result_array();

        $req_sql = "SELECT * FROM tbl_request as a WHERE a.enquiry_id = " . $id . "  AND type=2";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $result = [];
        $att_result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], $value['if_revised'], $value['req_size'], $value['req_qty'] ];

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

    public function updateManagementSampleRequestt($eId, $id, $auth_status, $auth_type, $mgmt_remark) {
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

    // ********** CREATE SAMPLE REQUEST ENDS HERE *********** /

    // ********** SAMPLE DEPARTMENT REQUEST STARTS HERE *********** /

    public function getDepartmentSampleRequestDetailss($id) {
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $id . "  and a.req_sent_status = 1 and c.mgmt_approval=1";
        $data = $this->db->query($sql)->result_array();

        $req_sql = "SELECT * FROM tbl_request as a WHERE a.request_id = " . $id . "  AND type=2 and a.mgmt_approval=1";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $result = [];
        $att_result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], $value['if_revised'], $value['req_size'], $value['req_qty'] ];

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

    public function updateDepartmentSampleRequestt($eid, $id, $req_status,$dep_remarks, $data) {
        
        $cad_ref_queue_no = '';
        $cad_queue_no = '';
        $fab_ref_queue_no = '';
        $fab_queue_no = '';
        $bom_ref_queue_no = '';
        $bom_queue_no = '';
        $r_data = $this->db->where('request_id',$id)->get('tbl_mi_details')->row();
        
        $issued_type = explode(",",$r_data->issued_type);
        
        $miValue = [];
        if($req_status == 1)
        {

            $req_sql = "SELECT MAX(queue_no)+1 as last_queue_no FROM tbl_request";
            $req_data = $this->db->query($req_sql)->result_array();
            
            $ord_sql = "SELECT reqforisrior,isriorcode FROM kn_order_enquiry WHERE id=$eid";
            $ord_data = $this->db->query($ord_sql)->result_array();
            $reqforisrior = $ord_data[0]['reqforisrior'];
            $ArrIsrIor   = unserialize(ARRISRIOR);
             ///
             $reqisriorcode = $ord_data[0]['isriorcode'];
             $parts = explode('/', $reqisriorcode);
             $companyname = isset($parts[2]) ? $parts[2] : null;
    
            //$queue_no = $req_data[0]['last_queue_no'];
            //if($queue_no == "") { $queue_no = 1; }
              $subid = $this->subscriberid;

             $wipcount = $this->commonmodel->getsamplerequestCountsq($subid);
             $count = ($wipcount > 0) ? $wipcount+1 : 1; // Default to 1 if no records exist
             $queue_no = $count; 
    
            //$ref_queue_no = $ArrIsrIor[$reqforisrior]."-BSG".$id."/".date('my')."/SQ-".$queue_no;
            //$ref_queue_no = $ArrIsrIor[$reqforisrior]."/".date('my')."/BSG-".$eid."/SQ-".$queue_no;

            //$ref_queue_no = $ArrIsrIor[$reqforisrior]."/".date('my')."/".$companyname."/SQ-".$queue_no;
             $ref_queue_no = $ArrIsrIor[$reqforisrior]."/".date('my')."/".$companyname."/SQ-".$queue_no;

           
    
            foreach ($data as $key => $value) {
    
                $sam_sql = "SELECT MAX(queue_no)+1 as last_queue_no FROM tbl_request_sample";
                $sam_data = $this->db->query($sam_sql)->result_array();
                //$sam_queue_no = $sam_data[0]['last_queue_no'];
                if($sam_queue_no == "") { $sam_queue_no = 1; }
                  $wipcounts = $this->commonmodel->getsamplerequestCountsr($subid);
                  $counts = ($wipcounts > 0) ? $wipcounts+1 : 1; // Default to 1 if no records exist
                  $sam_queue_no = $counts;

                //$ref_sam_queue_no = $ArrIsrIor[$reqforisrior]."-BSG".$id."/".date('my')."/SR-".$sam_queue_no;
                //$ref_sam_queue_no = $ArrIsrIor[$reqforisrior]."/".date('my')."/BSG-".$eid."/SR-".$sam_queue_no;
                 $ref_sam_queue_no = $ArrIsrIor[$reqforisrior]."/".date('my')."/".$companyname."/SR-".$sam_queue_no;
    
                $samUpdateData['queue_no'] = $sam_queue_no;
                $samUpdateData['ref_queue_no'] = $ref_sam_queue_no;
                $samUpdateData['log'] = LOGTIME;
    
                $this->db->where('sample_id', $value[1]);
                $this->db->update('tbl_request_sample', $samUpdateData);
    
            }
    
            $requestValue['queue_no'] = $queue_no;
            $requestValue['ref_queue_no'] = $ref_queue_no;
            $requestValue['qno_assign_dt'] = $this->mysqldatetime;
            $requestValue['que_assign_date'] = $this->mysqldatetime;

            
            
            if(in_array("BOM",$issued_type)) {
                // $mi_bom_sql = "SELECT  MAX(bom_queue)+1 as bom_queue_no FROM tbl_mi_details";
                // $mi_bom_data = $this->db->query($mi_bom_sql)->result_array();
                
                // if(isset($mi_bom_data)) {
                //     $bom_queue_no = $mi_bom_data[0]['bom_queue_no'];
                // } else {
                //     $bom_queue_no = 1;
                // }
                 $wipcountMIB = $this->commonmodel->getBOMrequestCountMI($subid);
                  $countMIB = ($wipcountMIB > 0) ? $wipcounts+1 : 1; // Default to 1 if no records exist
                  $bom_queue_no = $countMIB;
                
                $bom_ref_queue_no = $ref_queue_no."/BMI-".$bom_queue_no;
            } 
            
            if(in_array("CAD",$issued_type)) {
                // $mi_cad_sql = "SELECT  MAX(cad_queue)+1 as cad_queue_no FROM tbl_mi_details";
                // $mi_cad_data = $this->db->query($mi_cad_sql)->result_array();
                
                // if(isset($mi_cad_data)) {
                //     $cad_queue_no = $mi_cad_data[0]['cad_queue_no'];
                // } else {
                //     $cad_queue_no = 1;
                // }

                 $wipcountMI = $this->commonmodel->getcadrequestCountMI($subid);
                  $countMI = ($wipcountMI > 0) ? $wipcounts+1 : 1; // Default to 1 if no records exist
                  $cad_queue_no = $countMI;
                $cad_ref_queue_no = $ref_queue_no."/CMI-".$cad_queue_no;
            }
            
            if(in_array("FABRIC",$issued_type)) {
                $mi_fab_sql = "SELECT  MAX(fab_queue)+1 as fab_queue_no FROM tbl_mi_details";
                $mi_fab_data = $this->db->query($mi_fab_sql)->result_array();
                
                if(isset($mi_cad_data)) {
                    $fab_queue_no = $mi_fab_data[0]['fab_queue_no'];
                } else {
                    $fab_queue_no = 1;
                }
                
                $fab_ref_queue_no = $ref_queue_no."/FMI-".$fab_queue_no;
            }
            
            // $mi_req_sql = "SELECT MAX(cad_queue)+1 as cad_queue_no, MAX(fab_queue)+1 as fab_queue_no, MAX(bom_queue)+1 as bom_queue_no FROM tbl_mi_details";
            // $mi_req_data = $this->db->query($mi_req_sql)->result_array();
    
            // $cad_queue_no = $mi_req_data[0]['cad_queue_no'];
            // $fab_queue_no = $mi_req_data[0]['fab_queue_no'];
            // $bom_queue_no = $mi_req_data[0]['bom_queue_no'];

            // if($cad_queue_no == "") { $cad_queue_no = 1; }
            // if($fab_queue_no == "") { $fab_queue_no = 1; }
            // if($bom_queue_no == "") { $bom_queue_no = 1; }
    
            // $cad_ref_queue_no = $ref_queue_no."/CMI-".$cad_queue_no;
            // $fab_ref_queue_no = $ref_queue_no."/FMI-".$fab_queue_no;
            // $bom_ref_queue_no = $ref_queue_no."/BMI-".$bom_queue_no;

            $miValue['cad_queue'] = $cad_queue_no;
            $miValue['cad_ref_no'] = $cad_ref_queue_no;
            $miValue['fab_queue'] = $fab_queue_no;
            $miValue['fab_ref_no'] = $fab_ref_queue_no;
            $miValue['bom_queue'] = $bom_queue_no;
            $miValue['bom_ref_no'] = $bom_ref_queue_no;
            $miValue['log'] = LOGTIME;
            
            $this->db->where('request_id', $id);
            $this->db->update('tbl_mi_details', $miValue);

        }

        $requestValue['req_status'] = $req_status;
        $requestValue['dep_remarks'] = $dep_remarks;
        $requestValue['deprt_approval'] = $req_status;
        $requestValue['log'] = LOGTIME;

        $this->db->where('request_id', $id);
        $this->db->update('tbl_request', $requestValue);
    }

    // ********** UPDATE SAMPLE DEPARTMENT REQUEST ENDS HERE *********** /

    // ********** SAMPLE QA REQUEST STARTS HERE *********** /
    
    public function updateQASampleRequestt_old($eId, $id, $dep_remarks, $req_data) {
        //echo "<pre>"; print_r($req_data); exit;
        foreach ($req_data as $key => $value) {
            $updateValue = [];
            // $updateValue['job_status'] = $value[11];
            if($value[12] == 0 && $value[11] != "" ) {
                $updateValue['job_status'] = 1;
                $updateValue['job_schd_date'] = $value[11];
                $updateValue['job_sta_upd'] = 0;
                
                $updateValue['job_sta_upd_dt'] = $this->mysqldatetime;
                $updateValue['log'] = LOGTIME;
                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', $updateValue);
            }
            else if(($value[12] == 1 || $value[12] == 2) && $value[11] != "") {
                $updateValue['job_status'] = 2;
                $updateValue['job_schd_date'] = $value[11];
                $updateValue['job_re_sta_upd'] = 0;
                
                $updateValue['job_sta_upd_dt'] = $this->mysqldatetime;
                $updateValue['log'] = LOGTIME;
                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', $updateValue);
            } else {
                
            }
            // else if($value[11] == 8) {
            //     $updateValue["qa_req_status"] = 0;
            //     $updateValue["sam_qa_status"] = 0;
            //     $updateValue["qa_status"] = 0;
            //     $updateValue["qa_schd_date"] = '';
            //     $updateValue["qa_sta_upd_dt"] = '';
            //     $updateValue["qa_sta_upd"] = 1;
            //     $updateValue["qa_re_sta_upd"] = 1;
            // }
            
        }
        $requestValue['dep_remarks'] = $dep_remarks;
        $requestValue['log'] = LOGTIME;
        $this->db->where('request_id', $id);
        $this->db->update('tbl_request', $requestValue);

    }

    public function updateQASampleRequestt($eId, $id, $dep_remarks, $req_data) {
        //echo "<pre>"; print_r($req_data); exit;
        
         foreach ($req_data as $key => $value) {
    if ($value[3] == true && $value[12] == 4) {
       $id_arr[] = $value[0];

    }
   }
       $is_satisfied=false;
    $id_string = implode(',', $id_arr);
   

    if(empty($id_string)) {
          $is_satisfied=true;
    }else{
       
 $sql = "SELECT * FROM tbl_sample_requirement as a 
                        WHERE a.sample_requirement_id IN ({$id_string}) 
                        AND a.enquiry_id = {$eId} 
                        AND (a.qa_status = 5 OR a.qa_status = 6) 
                        AND (a.job_status = 3 OR a.job_status = 5)";
                $data = $this->db->query($sql)->result_array();
               //print_r($sql);

                

                $jobstatus_count=count($data);
              

                if(count($data) == count($id_arr) ) {
                     $is_satisfied=true;
                }else{
                     $is_satisfied=false;
                }

    }


    // print_r($is_satisfied);
    // die;
        
        if($is_satisfied==true) {
            
        
        
        foreach ($req_data as $key => $value) {
            $updateValue = [];
             $id_arr = array();
   

            // $updateValue['job_status'] = $value[11];
            if($value[3] == true ) {
                
           
            if($value[12] == 0 && $value[11] != "" ) {
                $updateValue['job_status'] = 1;
                $updateValue['job_schd_date'] = $value[11];
                $updateValue['job_sta_upd'] = 0;
                
                $updateValue['job_sta_upd_dt'] = $this->mysqldatetime;
                $updateValue['log'] = LOGTIME;
                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', $updateValue);
            }
              else if(($value[12] == 1 || $value[12] == 2) && $value[11] != "") {
                $updateValue['job_status'] = 2;
                $updateValue['job_schd_date'] = $value[11];
                $updateValue['job_re_sta_upd'] = 0;
                
                $updateValue['job_sta_upd_dt'] = $this->mysqldatetime;
                $updateValue['log'] = LOGTIME;
                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', $updateValue);
            }  
            else if($value[12] == 8) {
                $updateValue['job_status'] = $value[12];
                if($value[11] != "") {
                $updateValue["job_schd_date"] = $value[11];  
                }else{
                $updateValue["job_schd_date"] = date('Y-m-d H:i:s');
                }
               
                $updateValue['job_sta_upd'] = 0;
                $updateValue['job_sta_upd_dt'] = $this->mysqldatetime;
                $updateValue['log'] = LOGTIME;
                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', $updateValue);
            }
            else if($value[12] == 7) {
                $updateValue['job_status'] = $value[12];
                $updateValue['job_schd_date'] = $value[11];
                $updateValue['job_re_sta_upd'] = 0;


               // $updateValue['qa_req_status'] = 1;

                
                $updateValue['job_sta_upd_dt'] = $this->mysqldatetime;
                $updateValue['log'] = LOGTIME;
                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', $updateValue);
            }
          
            else if(($value[12] == 3 || $value[12] == 4 || $value[12] == 5 || $value[12] == 6 || $value[12] == 9 || $value[12] ==10  ) && $value[11] != "") {
                $updateValue['job_status'] = $value[12];
                $updateValue['job_schd_date'] = $value[11];
                $updateValue['job_re_sta_upd'] = 0;
                
                $updateValue['job_sta_upd_dt'] = $this->mysqldatetime;
                $updateValue['log'] = LOGTIME;
                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', $updateValue);
            }
             }
              $result['status'] = 'success';
           
            
        }

    }else{
          $result['status'] = 'job_failure';
    }
        $requestValue['dep_remarks'] = $dep_remarks;
        $requestValue['log'] = LOGTIME;
        $this->db->where('request_id', $id);
        $this->db->update('tbl_request', $requestValue);

         return $result;

    }


    // ********** SAMPLE QA REQUEST ENDS HERE *********** /

    // ********** SAMPLE QA REQUEST STARTS HERE *********** /

    public function getQARequestDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . "  and a.req_sent_status = 1  and (a.qa_req_status=0 or a.qa_req_status=1 )and (a.qa_status = '' or a.qa_status = 7 or a.qa_status = 4) ";
        $data = $this->db->query($sql)->result_array();
        
        $result = [];

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($enqId);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);

        $ref_sql = "SELECT b.ref_queue_no as id, b.ref_queue_no as name, a.po_enq_ref_id as po_enq_id, a.combo_id, a.component_id, a.spec_code_id as size_id, a.sample_requirement as req_id 
                    FROM tbl_sample_requirement as a 
                    LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id
                    WHERE a.enquiry_id = " . $enqId . "  and a.req_sent_status = 1 and b.ref_queue_no != '' ";
        $ref_data = $this->db->query($ref_sql)->result_array();
        
        $output['data'] = $data;
        $output['sizeData'] = $sizeMaster;
        $output['sampleRefNo'] = $ref_data;
        return $output;
    }

    // ********** SAMPLE QA REQUEST ENDS HERE *********** /

    // ********** UPDATE SAMPLE DEPARTMENT REQUEST STARTS HERE *********** /

    public function updateQARequestDetailss($enq_id, $id, $data, $date, $note) {
    //echo "<pre>"; print_r($data); exit;
        $qa_pending_sql = "SELECT * FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $id . " and a.req_sent_status = 1 and a.qa_req_status=0";
        $qa_pending_data = $this->db->query($qa_pending_sql)->result_array();

        $qa_update_sql = "SELECT * FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $id . "  and a.req_sent_status = 1 and a.qa_req_status=1";
        $qa_update_data = $this->db->query($qa_update_sql)->result_array();

        $req_data = json_decode($data);
        //echo "<pre>"; print_r($req_data); exit;
        $this->db->where('request_id', $id);
        $this->db->update('tbl_request', array('cad_by'=>$this->userid, 'log'=> LOGTIME));
        
        $qaData['enquiry_id'] =$enq_id;
        $qaData['request_id'] =$id;
        
        
        if($req_data[0][13] == 0 || $req_data[0][13] == "0") {
            $this->db->insert('sample_qa_request',$qaData);
            $qa_req_id = $this->db->insert_id();
        } else {
            $qa_req_id = $req_data[0][13];
        }

        foreach($req_data as $key => $value) {
            if($value[1] == true)
            {
                if($value[12] == 7 || $value[12] == "7" || $value[12] == 4 || $value[12] == "4") {
                    $job_status = 5;
                    $qa_approval = 2;
                } else {
                    $job_status = 3;
                    $qa_approval = 0;
                }

                //print_r($value[12]);
                //die;



                //  if($value[12] == 4 ||$value[12] == "4") {
                //     $job_status = 5;
                //     $qa_approval = 2;
                // } else {
                //     $job_status = 3;
                //     $qa_approval = 0;
                // }
                
                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', array('qa_req_id' => $qa_req_id, 'job_status' => $job_status, 'qa_approval' => $qa_approval, 'qa_req_status'=> 1, 'qa_req_sent_dt'=> $this->mysqldatetime,'job_sta_upd_dt'=> $this->mysqldatetime, 'qa_cutoff_date' => $date, 'log' => LOGTIME));
                
            }
           
        }

        // $this->db->where('request_id', $id);
        // $this->db->update('tbl_request', array('qa_approval'=>1));
        // $result['status'] = "200";
        // return $result;

    }


    // ********** UPDATE SAMPLE DEPARTMENT REQUEST ENDS HERE *********** /

    // ********** SAMPLE QA QUEUE STARTS HERE *********** /

    public function getQAQueueDetailss($enqId, $reqId, $samId) {
        $sql = "SELECT *, b.ref_queue_no as sam_ref_no,a.qa_approval as qa_approval,c.qno_assign_dt as qno_assign_dt,a.log as logs FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . " AND a.qa_req_id = " .$samId;
        $data = $this->db->query($sql)->result_array();
        
        $result = $att_result = $qa_status = [];

        foreach ($data as $key => $value)
        {
            
              if($value['qa_approval'] == 1 &&  $value['qa_schd_date'] == null) {
               $value['qa_sta_upd_dt'] = date('d/m/Y h:i A',strtotime($value['logs'])); ;
            
              }
             $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], $value['if_revised'], $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

            $qa_status[$key] = [ $value['sample_requirement_id'], $value['qa_sta_upd'], $value['qa_re_sta_upd'],'', $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['sam_ref_no'], $value['qa_schd_date'], $value['qa_status'], $value['qa_sta_upd_dt'] ];
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($enqId);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);

        $ref_sql = "SELECT b.ref_queue_no as id, b.ref_queue_no as name, a.po_enq_ref_id as po_enq_id, a.combo_id, a.component_id, a.spec_code_id as size_id, a.sample_requirement as req_id 
                    FROM tbl_sample_requirement as a 
                    LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id
                    WHERE a.enquiry_id = " . $enqId . "  and a.req_sent_status = 1 and b.ref_queue_no != '' ";
        $ref_data = $this->db->query($ref_sql)->result_array();
        
        $output['data'] = $result;
        $output['req_data'] = $data;
        $output['attachmentdata'] = $att_result;
        $output['qa_status_data'] = $qa_status;
        $output['sizeData'] = $sizeMaster;
        $output['sampleRefNo'] = $ref_data;
        return $output;
    }

    // ********** SAMPLE QA QUEUE ENDS HERE *********** /

    // ********** UPDATE SAMPLE DEPARTMENT QUEUE STARTS HERE *********** /

    public function updateQAQueueDetailss_OLD($id, $req_data, $note)
    {
        //echo "<pre>"; print_r($req_data); exit;
        foreach ($req_data as $key => $value)
        {
            //$sampleValue["qa_status"] = $value[10];
            $sampleValue["qa_sta_upd_dt"] = $this->mysqldatetime;
            if(($value[11] == 0 || $value[11] == 3) && $value[10] != "" ) {
                $sampleValue['qa_status'] = 1;
                $sampleValue["qa_schd_date"] = $value[10];
                $sampleValue['qa_sta_upd'] = 0;
            }
            else if(($value[11] == 1 || $value[11] == 2) && $value[10] != "") {
                $sampleValue['qa_status'] = 2;
                $sampleValue["qa_schd_date"] = $value[10];
                $sampleValue['qa_re_sta_upd'] = 0;
            }
            $sampleValue["qa_dept_remarks"] = $note;
            $sampleValue['log'] = LOGTIME;
            if($value[11] == 0 || $value[11] == 1 || $value[11] == 2 || $value[11] == 3) {
                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', $sampleValue);
            }
        }

    }
      public function updateQAQueueDetailss($id, $req_data, $note)
    {
        //echo "<pre>"; print_r($req_data); exit;
        foreach ($req_data as $key => $value)
        {
            if($value[3] == true) {
                
           
            //$sampleValue["qa_status"] = $value[10];
            $sampleValue["qa_sta_upd_dt"] = $this->mysqldatetime;
            if(($value[12] == 0  ) && $value[11] != "") {
                $sampleValue['qa_status'] = 1;
                $sampleValue["qa_schd_date"] = $value[11];
                $sampleValue['qa_re_sta_upd'] = 0;
            }
            if(($value[12] == 9 ) && $value[11] != "" ) {
                $sampleValue['qa_status'] = 1;
                $sampleValue['qa_schd_date'] = $value[11];
                $sampleValue['qa_sta_upd'] = 0;
            }
           
            if(($value[12] == 1 || $value[12] == 2) && $value[11] != "") {
                $sampleValue['qa_status'] = 2;
                $sampleValue["qa_schd_date"] = $value[11];
                $sampleValue['qa_re_sta_upd'] = 0;
            }
            else if($value[12] == 3) {
                $sampleValue['qa_status'] = $value[12];
                if($value[11] != "") {
                   $sampleValue["qa_schd_date"] = $value[11];  
                }else{
                     $sampleValue["qa_schd_date"] = date('Y-m-d H:i:s');
                }
               
                $sampleValue['qa_re_sta_upd'] = 0;
            }
            else if(($value[12] == 4 ) && $value[11] != "" ) {
                $sampleValue['qa_status'] = $value[12];
                $sampleValue["qa_schd_date"] = $value[11];
                $sampleValue['qa_re_sta_upd'] = 0;
                //$sampleValue['qa_req_status'] = 0;
                $sampleValue['job_status'] = 6;
                $sampleValue['job_sta_upd_dt'] = $this->mysqldatetime;

                
            }
             else if(($value[12] == 7 ) && $value[11] != "" ) {
                $sampleValue['qa_status'] = $value[12];
                $sampleValue["qa_schd_date"] = $value[11];
            
                $sampleValue['job_status'] = 10;
                $sampleValue['job_sta_upd_dt'] = $this->mysqldatetime;

                
            }
             else if(($value[12] == 5 || $value[12] == 6 ) && $value[11] != "") {
                $sampleValue['qa_status'] = $value[12];
                $sampleValue["qa_schd_date"] = $value[11];
                $sampleValue['qa_re_sta_upd'] = 0;
            }
            $sampleValue["qa_dept_remarks"] = $note;
            $sampleValue['log'] = LOGTIME;
            if(($value[12] == 0 || $value[12] == "0"  || $value[12] == 1 || $value[12] == 2 || $value[12] == 3 ||$value[12] == 4 || $value[12] == 5 || $value[12] == 6|| $value[12] == 7 || $value[12] == 9)&& $value[11] != "") {
                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', $sampleValue);
                 $this->db->last_query(); 
            }
             }
        }

    }
    
    
    public function updateQASampleQueueDetailss($id, $req_data, $note)
    {
       // echo "<pre>"; print_r($req_data); exit;
        foreach ($req_data as $key => $value)
        {
            if($value[12] == 4 || $value[12] == '4' || $value[12] == 7 || $value[12] == '7') {
                $sampleValue['qa_req_status'] = 0;
            }
            $sampleValue["qa_sta_upd_dt"] = $this->mysqldatetime;
            $sampleValue['qa_status'] = $value[12];
            $sampleValue["qa_dept_remarks"] = $note;
            $sampleValue['log'] = LOGTIME;

            if($value[14] == 0 || $value[14] == "0") {
                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', $sampleValue);
            }
            
           
        }

    }

    // ********** UPDATE SAMPLE DEPARTMENT QUEUE ENDS HERE *********** /   

    // ********** MERCHANT SAMPLE QUEUE STARTS HERE *********** /

    public function getMerchantSampleQueueDetailss($enqId, $samReqId) {
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE a.sample_requirement_id = " . $samReqId . "  and a.req_sent_status = 1";
        $data = $this->db->query($sql)->result_array();
        
        $result = $att_result = $qa_status_result = $job_status_result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], $value['if_revised'], $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

            $qa_status_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['qa_req_sent_dt'], $value['qa_schd_date'], $value['qa_status'], $value['qa_sta_upd_dt'] ];
                            
            $job_status_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sam_ref_no'], $value['job_schd_date'], $value['job_status'], $value['job_sta_upd_dt'] ];
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
                WHERE a.sample_requirement_id = " . $samReqId . " and a.req_sent_status = 1";
        $data = $this->db->query($sql)->result_array();
        
        $result = $att_result = $qa_status_result = $job_status_result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], $value['if_revised'], $value['req_size'], $value['req_qty'] ];

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

public function saveSampleReqDraftt($data)
    {
        $userInfo = fnGetUserLoggedInfo(1);
        $subscriberId =  $userInfo['subscriber_id'];
        //$requestValue['enquiry_id'] = $id;
      
       
        $cad_mi_tbl_data = json_decode($data['cad_mi_tbl_data']);
        $bom_mi_tbl_data = json_decode($data['bom_mi_tbl_data']);
        $fabric_mi_tbl_data = json_decode($data['fabric_mi_tbl_data']);
       

        $enquiry_id = $data['enquiry_id'];
        $request_id = $data['request_id'];
        $issued_type = explode(',',$data['issued_type']);
        $requestValue['enquiry_id'] = $enquiry_id;
        $requestValue['req_type'] = $data['req_type'];
        $requestValue['req_date'] = $this->mysqldatetime;
        $requestValue['cutoff_date'] = $data['cutoff_date'];
        $requestValue['merchant_note'] = $data['merchant_note'];
        $requestValue['subscriberid'] = $subscriberId;
        $requestValue['draft_status'] = 1;
        $requestValue['log'] = LOGTIME;
        $this->db->where('request_id', $request_id);
        $this->db->update('tbl_request', $requestValue);
        
        @$mi_data = $this->db->select('mi_id')->from('tbl_mi_details')->where('request_id', $request_id)->get()->result_array();
         //   print_r($mi_data); exit;
        // insert material indent details
        $mi_details['enquiry_id'] = $enquiry_id;
        $mi_details['request_id'] = $request_id;
        $mi_details['cad_dept'] = $data['cad_dept'];
        $mi_details['cad_cutoff_date'] = $data['cad_cutoff_date'];
        $mi_details['fab_dept'] = $data['fab_dept'];
        $mi_details['fab_req_date'] = $this->mysqldatetime;
        $mi_details['fab_cutoff_date'] = $data['fab_cutoff_date'];
        $mi_details['bom_dept'] = $data['bom_dept'];
        $mi_details['bom_cutoff_date'] = $data['bom_cutoff_date'];
        $mi_details['type'] = $data['type'];
        $mi_details['issued_type'] = $data['issued_type'];
        $mi_details['log'] = LOGTIME;
        
        $mi_id = 0;
        
        if(sizeof($mi_data) == 0)
        {
            $this->db->insert('tbl_mi_details', $mi_details);
            $mi_id = $this->db->insert_id();
        }
        else
        {
            $mi_id = $mi_data[0]['mi_id'];
            $this->db->where('mi_id', $mi_data[0]['mi_id']);
            $this->db->update('tbl_mi_details', $mi_details);
        }

        // insert cad material indent details
        if (in_array("CAD", $issued_type))
        {
         foreach ($cad_mi_tbl_data as $key => $value) {
            $cad_mi_details["mi_id"] = $mi_id;
            $cad_mi_details["enquiry_id"] = $enquiry_id;
            $cad_mi_details["request_id"] = $request_id;
            $cad_mi_details["sample_req_id"] = $value[1];
            $cad_mi_details["po_no"] = $value[2];
            $cad_mi_details["combo"] = $value[3];
            $cad_mi_details["component"] = $value[4];
            $cad_mi_details["spec_code"] = $value[5];
            $cad_mi_details["cad_ref"] = $value[6];
            $cad_mi_details["req"] = $value[7];
            $cad_mi_details["purpose"] = $value[8];
            $cad_mi_details["req_size"] = $value[9];
            $cad_mi_details['log'] = LOGTIME;
            if($value[0] == '' || $value[0] == null)
            {
                $this->db->insert('tbl_mi_cad_details', $cad_mi_details);
            }
            else {
                $this->db->where('mat_ind_cad_id', $value[0]);
                $this->db->update('tbl_mi_cad_details', $cad_mi_details);
            }
        }
        }

        
        if (in_array("FABRIC", $issued_type))
        {
        // get sample requirement id from array
        $f_keys = [];
        foreach ($fabric_mi_tbl_data as $key => $value) {
            foreach($value as $key => $nValue)
            {
                array_push($f_keys, $key);
            }
        }

        // insert fabric mi details
        foreach ($fabric_mi_tbl_data as $key => $value) {

            $mi_fabric_data = $this->db->from('tbl_mi_fabric')->where('sample_req_id', $f_keys[$key])->get()->result_array();

            // // insert fabric material indent data
            $fab_mi_value["sample_req_id"] = $keys[$key];
            $fab_mi_value["enquiry_id"] = $enquiry_id;
            $fab_mi_value["request_id"] = $request_id;
            $fab_mi_value['log'] = LOGTIME;
            if(sizeof($mi_fabric_data) == 0)
            {
                $this->db->insert('tbl_mi_fabric', $fab_mi_value);
                $last_insert_id = $this->db->insert_id();
            }

            // insert fabric material indent details data
            foreach($value as $nkey => $nValue)
            {
                foreach ($nValue as $key => $fValue) {
                    // $bom_mi_details["fab_ref_no"] = $fValue[1];
                    $fabric_mi_details["colour"] = $fValue[2];
                    $fabric_mi_details["garment_part"] = $fValue[3];
                    $fabric_mi_details["fabric_blend"] = $fValue[4];
                    $fabric_mi_details["fabric_content"] = $fValue[5];
                    $fabric_mi_details["fabric_name"] = $fValue[6];
                    $fabric_mi_details["gsm"] = $fValue[7];
                    $fabric_mi_details["size_dim"] = $fValue[8];
                    $fabric_mi_details["uom"] = $fValue[9];
                    $fabric_mi_details["ind_qty"] = $fValue[10];
                    $fabric_mi_details["ind_uom"] = $fValue[11];
                    $fabric_mi_details['log'] = LOGTIME;

                    if($fValue[0] == "")
                    {
                        $fabric_mi_details["mi_fabric_id"] = $last_insert_id;
                        $this->db->insert('tbl_mi_fabric_details', $fabric_mi_details);
                    }
                    else {
                        $this->db->where('mi_fabric_details_id', $fValue[0]);
                        $this->db->update('tbl_mi_fabric_details', $fabric_mi_details);
                    }
                }
            }
        }
        }
            
        if (in_array("BOM", $issued_type))
        {
        // insert bom mi details
        // get sample requirement id from array
        $keys = [];
        foreach ($bom_mi_tbl_data as $key => $value) {
            foreach($value as $key => $nValue)
            {
                array_push($keys, $key);
            }
        }
        $k = 1;
        foreach ($bom_mi_tbl_data as $key => $value) {

            $mi_bom_data = $this->db->from('tbl_mi_bom')->where('sample_req_id', $keys[$key])->get()->result_array();
            // print_r($mi_bom_data); 
            // insert bom material indent data
            $bom_mi_value["sample_req_id"] = $keys[$key];
            $bom_mi_value["enquiry_id"] = $enquiry_id;
            $bom_mi_value["request_id"] = $request_id;
            $bom_mi_value['log'] = LOGTIME;
            // print_r($bom_mi_value);
            if(sizeof($mi_bom_data) == 0) {
                $this->db->insert('tbl_mi_bom', $bom_mi_value);
                $last_insert_id = $this->db->insert_id();
            } else {
                $last_insert_id = $this->db->where('sample_req_id',$keys[$key])->get('tbl_mi_bom')->row()->mi_bom_id;
            }

            // insert bom material indent details data
            
            foreach($value as $nkey => $nValue)
            {
                foreach ($nValue as $key => $fValue) {
                    $bom_mi_details["item_desc"] = $fValue[1];
                    $bom_mi_details["bcm"] = $fValue[2];
                    $bom_mi_details["gar_size"] = $fValue[3];
                    $bom_mi_details["item_code"] = $fValue[4];
                    $bom_mi_details["item_color_code"] = $fValue[5];
                    $bom_mi_details["size_dim"] = $fValue[6];
                    $bom_mi_details["uom"] = $fValue[7];
                    $bom_mi_details["ind_qty"] = $fValue[8];
                    $bom_mi_details["ind_uom"] = $fValue[9];
                    $bom_mi_details['sample_no'] = "SAMPLE-".$k;
                    $bom_mi_details['log'] = LOGTIME;

                    if($fValue[0] == "")
                    {
                        
                        $bom_mi_details["mi_bom_id"] = $last_insert_id;
                        $this->db->insert('tbl_mi_bom_details', $bom_mi_details);
                        
                    }
                    else {
                        $this->db->where('mi_bom_details_id', $fValue[0]);
                        $this->db->update('tbl_mi_bom_details', $bom_mi_details);
                    }
                }
            }
            $k++;
        }
        }
    
        $result['status'] = 'success';
        return $result;
    }

    public function saveSampleReqDetailss($data)
    {
        $sample_details = json_decode($data['sample_details']);
        $cad_mi_tbl_data = json_decode($data['cad_mi_tbl_data']);
        $bom_mi_tbl_data = json_decode($data['bom_mi_tbl_data']);
        $fabric_mi_tbl_data = json_decode($data['fabric_mi_tbl_data']);
        $enquiry_id = $data['enquiry_id'];
        $request_id = $data['request_id'];
        $issued_type = explode(',',$data['issued_type']);

        // update request details
        if(@$data['auth_status'] == '3' || @$data['auth_status'] == 3) {
            $requestValue['auth_status'] = 3;
            $requestValue['mgmt_approval'] = 3;
            $requestValue['auth_by'] = '';
            $requestValue['auth_date'] = '';
            $requestValue['auth_type'] = '';
        } else {
            $requestValue['auth_status'] = 0;
            $requestValue['mgmt_approval'] = 0;
        }
        $requestValue['req_type'] = $data['req_type'];
        $requestValue['req_date'] = $this->mysqldatetime;
        $requestValue['cutoff_date'] = $data['cutoff_date'];
        $requestValue['merchant_note'] = $data['merchant_note'];
        $requestValue['draft_status'] = 1;
        $requestValue['subscriberid'] = $this->subscriberid;
        $requestValue['log'] = LOGTIME;
        $this->db->where('request_id', $request_id);
        $this->db->update('tbl_request', $requestValue);

        $mi_data = $this->db->select('mi_id')->from('tbl_mi_details')->where('request_id', $request_id)->get()->result_array();

        // insert material indent details
       
        $mi_details['enquiry_id'] = $enquiry_id;
        $mi_details['request_id'] = $request_id;
        $mi_details['cad_dept'] = @$data['cad_dept'];
        $mi_details['cad_cutoff_date'] = @$data['cad_cutoff_date'];
        $mi_details['fab_dept'] = @$data['fab_dept'];
        $mi_details['fab_cutoff_date'] = @$data['fab_cutoff_date'];
        $mi_details['bom_dept'] = @$data['bom_dept'];
        $mi_details['bom_cutoff_date'] = @$data['bom_cutoff_date'];
        $mi_details['req_sent_status'] = 1;
        $mi_details['cad_req_date'] = $this->mysqldatetime;
        $mi_details['bom_req_date'] = $this->mysqldatetime;
        $mi_details['fab_req_date'] = $this->mysqldatetime;
        $mi_details['type'] = $data['type'];
        $mi_details['issued_type'] = $data['issued_type'];
        $mi_details['log'] = LOGTIME;

        $mi_id = 0;
        
        if(sizeof($mi_data) == 0)
        {
            $this->db->insert('tbl_mi_details', $mi_details);
            $mi_id = $this->db->insert_id();
        }
        else
        {
            $mi_id = $mi_data[0]['mi_id'];
            $this->db->where('mi_id', $mi_data[0]['mi_id']);
            $this->db->update('tbl_mi_details', $mi_details);
        }
        
        if (in_array("CAD", $issued_type))
        {

        // insert cad material indent details
        foreach ($cad_mi_tbl_data as $key => $value) {
            $cad_mi_details["mi_id"] = $mi_id;
            $cad_mi_details["enquiry_id"] = $enquiry_id;
            $cad_mi_details["request_id"] = $request_id;
            $cad_mi_details["sample_req_id"] = $value[1];
            $cad_mi_details["po_no"] = $value[2];
            $cad_mi_details["combo"] = $value[3];
            $cad_mi_details["component"] = $value[4];
            $cad_mi_details["spec_code"] = $value[5];
            $cad_mi_details["cad_ref"] = $value[6];
            $cad_mi_details["req"] = $value[7];
            $cad_mi_details["purpose"] = $value[8];
            $cad_mi_details["req_size"] = $value[9];
            $cad_mi_details['log'] = LOGTIME;
            if($value[0] == '' || $value[0] == null)
            {
                $this->db->insert('tbl_mi_cad_details', $cad_mi_details);
            }
            else {
                $this->db->where('mat_ind_cad_id', $value[0]);
                $this->db->update('tbl_mi_cad_details', $cad_mi_details);
            }
        }
        
        }

        if (in_array("FABRIC", $issued_type))
        {
        
        // get sample requirement id from array
        $f_keys = [];
        foreach ($fabric_mi_tbl_data as $key => $value) {
            foreach($value as $key => $nValue)
            {
                array_push($f_keys, $key);
            }
        }
        
        // insert fabric mi details
        foreach ($fabric_mi_tbl_data as $key => $value) {

            $mi_fabric_data = $this->db->from('tbl_mi_fabric')->where('sample_req_id', $f_keys[$key])->get()->result_array();

            // // insert fabric material indent data
            $fab_mi_value["sample_req_id"] = $keys[$key];
            $fab_mi_value["enquiry_id"] = $enquiry_id;
            $fab_mi_value["request_id"] = $request_id;
            $fab_mi_value['log'] = LOGTIME;
            if(sizeof($mi_fabric_data) == 0)
            {
                $this->db->insert('tbl_mi_fabric', $fab_mi_value);
                $last_insert_id = $this->db->insert_id();
            }

            // insert fabric material indent details data
            foreach($value as $nkey => $nValue)
            {
                foreach ($nValue as $key => $fValue) {
                    // $bom_mi_details["fab_ref_no"] = $fValue[1];
                    $fabric_mi_details["colour"] = $fValue[2];
                    $fabric_mi_details["garment_part"] = $fValue[3];
                    $fabric_mi_details["fabric_blend"] = $fValue[4];
                    $fabric_mi_details["fabric_content"] = $fValue[5];
                    $fabric_mi_details["fabric_name"] = $fValue[6];
                    $fabric_mi_details["gsm"] = $fValue[7];
                    $fabric_mi_details["size_dim"] = $fValue[8];
                    $fabric_mi_details["uom"] = $fValue[9];
                    $fabric_mi_details["ind_qty"] = $fValue[10];
                    $fabric_mi_details["ind_uom"] = $fValue[11];
                    $fabric_mi_details['log'] = LOGTIME;

                    if($fValue[0] == "")
                    {
                        $fabric_mi_details["mi_fabric_id"] = $last_insert_id;
                        $this->db->insert('tbl_mi_fabric_details', $fabric_mi_details);
                    }
                    else {
                        $this->db->where('mi_fabric_details_id', $fValue[0]);
                        $this->db->update('tbl_mi_fabric_details', $fabric_mi_details);
                    }
                }
            }
        }
        
        }
        
        if (in_array("BOM", $issued_type))
        {
            
        // get sample requirement id from array
        $keys = [];
        foreach ($bom_mi_tbl_data as $key => $value) {
            foreach($value as $key => $nValue)
            {
                array_push($keys, $key);
            }
        }

        // insert bom mi details
        $k = 1;
        foreach ($bom_mi_tbl_data as $key => $value) {

            $mi_bom_data = $this->db->from('tbl_mi_bom')->where('sample_req_id', $keys[$key])->get()->result_array();

            // // insert bom material indent data
            $bom_mi_value["sample_req_id"] = $keys[$key];
            $bom_mi_value["enquiry_id"] = $enquiry_id;
            $bom_mi_value["request_id"] = $request_id;
            $bom_mi_value['log'] = LOGTIME;
            if(sizeof($mi_bom_data) == 0) {
                $this->db->insert('tbl_mi_bom', $bom_mi_value);
                $last_insert_id = $this->db->insert_id();
            } else {
                $last_insert_id = $this->db->where('sample_req_id',$keys[$key])->get('tbl_mi_bom')->row()->mi_bom_id;
            }

            // insert bom material indent details data
            foreach($value as $nkey => $nValue)
            {
                foreach ($nValue as $key => $fValue) {
                    // $bom_mi_details["item_desc"] = $fValue[1];
                    // $bom_mi_details["bcm"] = $fValue[2];
                    // $bom_mi_details["gar_size"] = $fValue[3];
                    // $bom_mi_details["item_code"] = $fValue[4];
                    // $bom_mi_details["item_color_code"] = $fValue[5];
                    // $bom_mi_details["size_dim"] = $fValue[6];
                    // $bom_mi_details["uom"] = $fValue[7];
                    // $bom_mi_details["ind_qty"] = $fValue[8];
                    // $bom_mi_details["ind_uom"] = $fValue[9];
                    // $bom_mi_details['sample_no'] = "SAMPLE-".$k;
                    // $bom_mi_details['log'] = LOGTIME;
                    $bom_mi_details["BOMartical"] = $fValue[1];
                    $bom_mi_details["item_desc"] = $fValue[2];
                    $bom_mi_details["bcm"] = $fValue[3];
                    $bom_mi_details["gar_size"] = $fValue[4];
                    $bom_mi_details["item_code"] = $fValue[5];
                    $bom_mi_details["item_color_code"] = $fValue[6];
                    $bom_mi_details["size_dim"] = $fValue[7];
                    $bom_mi_details["uom"] = $fValue[8];
                    $bom_mi_details["ind_qty"] = $fValue[9];
                    $bom_mi_details["ind_uom"] = $fValue[10];
                    $bom_mi_details['sample_no'] = "SAMPLE-".$k;
                    $bom_mi_details['log'] = LOGTIME;

                    if($fValue[0] == "")
                    {
                        $bom_mi_details["mi_bom_id"] = $last_insert_id;
                        $this->db->insert('tbl_mi_bom_details', $bom_mi_details);
                    }
                    else {
                        $this->db->where('mi_bom_details_id', $fValue[0]);
                        $this->db->update('tbl_mi_bom_details', $bom_mi_details);
                    }
                }
            }
            $k++;
        }
        
        }

        // update sample requiment request status
        foreach ($sample_details as $key => $value) {
            $sampleValue['req_draft_status'] = 0;
            $sampleValue['req_sent_status'] = 1;
            $sampleValue['log'] = LOGTIME;
            $this->db->where('sample_requirement_id', $value[1]);
            $this->db->update('tbl_sample_requirement', $sampleValue);
        }

        $result['status'] = 'success';
        return $result;

    }
    
    public function getSampleRequestListt($id) {
        $sql = "SELECT a.*, b.*, c.*, d.cad_ref, d.mat_ind_cad_id, d.req as cad_req, d.purpose as cad_purpose, d.req_size as cad_req_size
                FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id 
                LEFT JOIN tbl_mi_cad_details as d on a.sample_requirement_id = d.sample_req_id
                WHERE a.enquiry_id = ".$id."  and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $data = $this->db->query($sql)->result_array();
        
        $result = [];
        $referResult = [];
        $cadMaterialIndent = [];
        $ref_status = 0;
        $req_id = "";

        foreach ($data as $key => $value)
        {
            $ref_status += (int) $value['req_reference_status'];
            $req_id = $value['request_id'];
            if($value['req_reference_status'] == "1" || $value['req_reference_status'] == 1) 
            {
                $result[$key] = ['edit', $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['sample_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value['req_size'], $value['req_qty'] ];

                $referResult[$key] = ['edit', $value['sample_requirement_id'], $value['req_reference_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

                $cadMaterialIndent[$key] = [$value['mat_ind_cad_id'], $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['cad_ref'], $value['cad_req'], $value['cad_purpose'], $value['cad_req_size'], '', '', '', '', '' ];
            }
            else {
                $result[$key] = ['', $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                        $value['spec_code_id'], $value['sample_requirement'], "", "", "", $value['req_size'], $value['req_qty'] ];
            }
        }
        
        $bom_sql = "SELECT b.*
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_mi_bom as b on a.sample_requirement_id = b.sample_req_id
                WHERE a.enquiry_id = ".$id." and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $bom_data = $this->db->query($bom_sql)->result_array();

        $bom_mi_tbl_data = [];
        for ($i=0; $i < sizeof($bom_data); $i++) { 
            $bom_details_sql = "SELECT * FROM tbl_mi_bom_details WHERE mi_bom_id = ".$bom_data[$i]['mi_bom_id']."";
            $bom_details_data = $this->db->query($bom_details_sql)->result_array();
            array_push($bom_mi_tbl_data, $bom_details_data);
        }

        $bomMaterialIndent = [];
        foreach ($bom_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                $bomMaterialIndent[$key][$nKey] = [ $nValue['mi_bom_details_id'], $nValue['item_desc'], $nValue['bcm'], $nValue['gar_size'], $nValue['item_code'],
                                $nValue['item_color_code'], $nValue['size_dim'], $nValue['uom'], $nValue['ind_qty'], $nValue['ind_uom'], '', '', '', '', '' ];
            }
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $cad_sql = "SELECT a.request_cad_id as id, a.ref_queue_no as name FROM tbl_request_cad a
                INNER JOIN tbl_request b ON a.request_id = b.request_id
                WHERE b.enquiry_id = ".$id."  ";
        $cad_ref_data = $this->db->query($cad_sql)->result_array();
        
        $mi_data = [];
        if($req_id != null) {
            $mi_sql = "SELECT * FROM tbl_mi_details WHERE request_id = ".$req_id."  ";
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

        $output['data'] = $result;
        $output['sizeData'] = $sizeMaster;
        $output['ref_status'] = $ref_status;
        $output['req_id'] = $req_id;
        $output['referResult'] = array_values($referResult);
        $output['cad_ref_data'] = $cad_ref_data;
        $output['cadMaterialIndent'] = array_values($cadMaterialIndent);
        $output['UOMDetails'] = $UOMDetails;
        $output['BOMAppendData'] = $bomMIDetails;
        $output['bom_mi_tbl_data'] = $bomMaterialIndent;
        return $output;
    }
    
    public function getSampleRequestSentListt($id, $reqId) {
        
        $miData = $this->db->where('request_id',$reqId)->get('tbl_mi_details')->row();
        
        $sql = "SELECT a.*, b.*, c.*, c.cutoff_date as r_cutoff_date2 FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                INNER JOIN tbl_request as c on b.request_id=c.request_id 
                WHERE c.enquiry_id = ".$id." AND c.request_id= ".$reqId."  AND a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $data = $this->db->query($sql)->result_array();
        
        $cadsql = "SELECT a.*, b.*, c.*, c.cutoff_date as r_cutoff_date2, d.cad_ref, d.mat_ind_cad_id, d.req as cad_req, d.purpose as cad_purpose, d.req_size as cad_req_size
                FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                INNER JOIN tbl_request as c on b.request_id=c.request_id 
                INNER JOIN tbl_mi_cad_details as d on a.sample_requirement_id = d.sample_req_id
                WHERE c.enquiry_id = ".$id." AND c.request_id= ".$reqId."  AND a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $caddata = $this->db->query($cadsql)->result_array();
        //print_r($sql); exit;
        $req_sql = "SELECT * FROM tbl_request WHERE request_id = ".$reqId;
        $req_data = $this->db->query($req_sql)->result_array();
        
        $result = [];
        $referResult = [];
        $cadMaterialIndent = [];
        $ref_status = 0;
        $req_id = "";

        foreach ($data as $key => $value)
        {
            $ref_status += (int) $value['req_reference_status'];
            $req_id = $value['request_id'];
            if($value['req_reference_status'] == "1" || $value['req_reference_status'] == 1) 
            {
                $result[$key] = ['edit', $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['sample_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value['req_size'], $value['req_qty'] ];

                $referResult[$key] = ['edit', $value['sample_requirement_id'], $value['req_reference_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

                // $cadMaterialIndent[$key] = [$value['mat_ind_cad_id'], $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                //                 $value['spec_code_id'], $value['cad_ref'], $value['cad_req'], $value['cad_purpose'], $value['cad_req_size'], '', '' ];
            }
            else {
                $result[$key] = ['', $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                        $value['spec_code_id'], $value['sample_requirement'], "", "", "", $value['req_size'], $value['req_qty'] ];
            }
        }
        foreach ($caddata as $key => $value)
        {
            $cadMaterialIndent[$key] = [$value['mat_ind_cad_id'], $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['cad_ref'], $value['cad_req'], $value['cad_purpose'], $value['cad_req_size'], '', '' ];
        }
        
        $bom_sql = "SELECT b.*
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_mi_bom as b on a.sample_requirement_id = b.sample_req_id
                WHERE a.enquiry_id = ".$id." AND b.request_id=".$reqId."  and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $bom_data = $this->db->query($bom_sql)->result_array();

        $fabric_sql = "SELECT b.*
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_mi_fabric as b on a.sample_requirement_id = b.sample_req_id
                WHERE a.enquiry_id = ".$id." AND b.request_id=".$reqId." and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $fabric_data = $this->db->query($fabric_sql)->result_array();

        $bom_mi_tbl_data = [];
        for ($i=0; $i < sizeof($bom_data); $i++) { 
            $bom_details_sql = "SELECT * FROM tbl_mi_bom_details WHERE mi_bom_id = ".$bom_data[$i]['mi_bom_id']."";
            $bom_details_data = $this->db->query($bom_details_sql)->result_array();
            array_push($bom_mi_tbl_data, $bom_details_data);
        }

        $fabric_mi_tbl_data = [];
        for ($i=0; $i < sizeof($fabric_data); $i++) { 
            $fabric_details_sql = "SELECT * FROM tbl_mi_fabric_details WHERE mi_fabric_id = ".$fabric_data[$i]['mi_fabric_id']."";
            $fabric_details_data = $this->db->query($fabric_details_sql)->result_array();
            array_push($fabric_mi_tbl_data, $fabric_details_data);
        }

        $bomMaterialIndent = [];
        foreach ($bom_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                $bomMaterialIndent[$key][$nKey] = [ $nValue['mi_bom_details_id'], $nValue['item_desc'], $nValue['bcm'], $nValue['gar_size'], $nValue['item_code'],
                                $nValue['item_color_code'], $nValue['size_dim'], $nValue['uom'], $nValue['ind_qty'], $nValue['ind_uom'], '', '' ];
            }
        }

        $fabricMaterialIndent = [];
        foreach ($fabric_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                $fabricMaterialIndent[$key][$nKey] = [ $nValue['mi_fabric_details_id'], $nValue['fab_ref_no'], $nValue['colour'], $nValue['garment_part'], 
                    $nValue['fabric_blend'], $nValue['fabric_content'], $nValue['fabric_name'], $nValue['gsm'], 
                    $nValue['size_dim'], $nValue['uom'], $nValue['ind_qty'], $nValue['ind_uom'], $nValue['dc_no'], $nValue['issue_date'] ];
            }
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $cad_sql = "SELECT a.request_cad_id as id, a.ref_queue_no as name FROM tbl_request_cad a
                INNER JOIN tbl_request b ON a.request_id = b.request_id
                WHERE b.enquiry_id = ".$id."";
        $cad_ref_data = $this->db->query($cad_sql)->result_array();
        
        $mi_data = [];
        if($req_id != null) {
            $mi_sql = "SELECT * FROM tbl_mi_details WHERE request_id = ".$req_id."  ";
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
        $bom2MIDetails = $this->bom2MIDetails($id);
        $fabricMIDetails = $this->fabricMIDetails($id);

        $ref_sql = "SELECT b.ref_queue_no as id, b.ref_queue_no as name, a.po_enq_ref_id as po_enq_id, a.combo_id, a.component_id, a.spec_code_id as size_id, a.sample_requirement as req_id 
                    FROM tbl_sample_requirement as a 
                    LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id
                    WHERE a.enquiry_id = " . $id . "  and a.req_sent_status = 1 and b.ref_queue_no != '' ";
        $ref_data = $this->db->query($ref_sql)->result_array();

        $output['data'] = $result;
        $output['sizeData'] = $sizeMaster;
        $output['ref_status'] = $ref_status;
        $output['req_id'] = $req_id;
        $output['referResult'] = array_values($referResult);
        $output['cad_ref_data'] = $cad_ref_data;
        $output['cadMaterialIndent'] = array_values($cadMaterialIndent);
        $output['UOMDetails'] = $UOMDetails;
        $output['BOMAppendData'] = $bomMIDetails;
        $output['BOM2AppendData'] = $bom2MIDetails;
        $output['FabricAppendData'] = $fabricMIDetails;
        $output['bom_mi_tbl_data'] = $bomMaterialIndent;
        $output['fabric_mi_tbl_data'] = $fabricMaterialIndent;
        $output['req_data'] = $req_data;
        $output['mi_data'] = $mi_data;
        $output['sampleRefNo'] = $ref_data;
        return $output;
    }

    function getRequestData($enqId, $reqId)
    {
        $sql = "SELECT *, b.contactname as auth_name, a.cutoff_date as r_cutoff_date, a.ref_queue_no as sam_queue_no, a.qno_assign_dt as sam_no_a_dt, a.qa_dept_remarks as sam_qa_dep_rem
                from tbl_request a
                INNER JOIN tbl_request_sample as c ON c.request_id = a.request_id
                INNER JOIN tbl_sample_requirement as d ON c.sample_id = d.sample_requirement_id
                Left JOIN ".KN_USERS." b ON a.auth_by=b.id
                where a.request_id='$reqId' ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function getjobstatusData($enqId, $reqId)
    {
         $sql = "SELECT a.*, b.*, c.*, d.cad_ref, d.mat_ind_cad_id, d.req as cad_req, d.purpose as cad_purpose, d.req_size as cad_req_size, b.ref_queue_no as sam_ref_no, d.dc_ref_queue_no as cad_dc_no, d.dc_dt as cad_dc_dt
                FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id 
                LEFT JOIN tbl_mi_cad_details as d on a.sample_requirement_id = d.sample_req_id
                WHERE a.enquiry_id = ".$enqId." AND c.request_id= ".$reqId." AND A.job_status = 4  and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $data = $this->db->query($sql)->result_array();
        
       
         $job_status_result = [];
        $ref_status = 0;
        $req_id = "";
        //  print_r($data);
        //  die;
        foreach ($data as $key => $value)
        {
            $ref_status += (int) $value['req_reference_status'];
            $req_id = $value['request_id'];
            if($value['req_reference_status'] == "1" || $value['req_reference_status'] == 1) 
            {
                                
                if(($value['qa_status'] == 5 || $value['qa_status'] == 6) && ($value['job_status'] == 3 || $value['job_status'] == 5)) {
                    $qaPass = 'Yes';
                } else {
                    $qaPass = 'No';;
                }
               
               
      $job_status_result[$key] = [ $value['sample_requirement_id'],'', $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['sam_ref_no'], $value['reqrd_size'], $value['job_status'], $value['job_sta_upd_dt'] ];

 
            
            
                        }
        }
           $sizeChart  = $this->getSizeChart($enqId);
          $sizeMaster = $this->getSizeMasterDropdown($sizeChart);
   
            $output['jobstatusdata'] = $job_status_result;
             $output['sizeData'] = $sizeMaster;

            

            //  print_r($output);
            //  die;


       
        return $output;
    }

    function getSampleRequestData($enqId, $reqId, $samId)
    {
        $sql = "SELECT *, b.contactname as auth_name, a.cutoff_date as r_cutoff_date, a.ref_queue_no as sam_queue_no, a.qno_assign_dt as sam_no_a_dt, a.qa_dept_remarks as sam_qa_dep_rem,
                d.qa_req_sent_dt, d.qa_cutoff_date, d.ref_queue_no as que_queue_no, d.dc_ref_queue_no,d.sam_dept_note, d.qno_assign_dt, d.sam_qa_status
                from tbl_request a
                INNER JOIN tbl_request_sample as c ON c.request_id = a.request_id
                INNER JOIN tbl_sample_requirement as d ON c.sample_id = d.sample_requirement_id
                Left JOIN ".KN_USERS." b ON a.auth_by=b.id
                where a.request_id='$reqId' AND d.qa_req_id='$samId' ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    public function getQASampleRequestListt($id, $reqId) {
        $sql = "SELECT a.*, b.*, c.*, d.cad_ref, d.mat_ind_cad_id, c.que_assign_date as que_assign_date,d.req as cad_req, d.purpose as cad_purpose, d.req_size as cad_req_size, b.ref_queue_no as sam_ref_no, d.dc_ref_queue_no as cad_dc_no, d.dc_dt as cad_dc_dt
                FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id 
                LEFT JOIN tbl_mi_cad_details as d on a.sample_requirement_id = d.sample_req_id
                WHERE a.enquiry_id = ".$id." AND c.request_id= ".$reqId."  and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $data = $this->db->query($sql)->result_array();
        
        $result = [];
        $referResult = [];
        $cadMaterialIndent = [];
        $job_status_result = [];
        $ref_status = 0;
        $req_id = "";

        foreach ($data as $key => $value)
        {
            $ref_status += (int) $value['req_reference_status'];
            $req_id = $value['request_id'];
            if($value['req_reference_status'] == "1" || $value['req_reference_status'] == 1) 
            {
                $result[$key] = ['edit', $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['sample_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value['req_size'], $value['req_qty'] ];

                $referResult[$key] = ['edit', $value['sample_requirement_id'], $value['req_reference_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

                $cadMaterialIndent[$key] = [$value['mat_ind_cad_id'], $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['cad_ref'], $value['cad_req'], $value['cad_purpose'], $value['cad_req_size'], $value['cad_dc_no'], $value['cad_dc_dt'] ];
                                
                if(($value['qa_status'] == 5 || $value['qa_status'] == 6) && ($value['job_status'] == 3 || $value['job_status'] == 5)) {
                    $qaPass = 'Yes';
                } else {
                    $qaPass = 'No';;
                }
                if($value['job_status'] == 4 || $value['job_status'] == 9) {
                    $checkVal = 0;
                } else {
                    $checkVal = 0;
                }
                if($value['job_status'] == 0 && $value['job_schd_date'] == "" ) {
        // If 'qa_schd_date' is empty, set 'qa_status' to 8
                $value['job_sta_upd_dt'] = $value['que_assign_date'];
                    }

                
                $job_status_result[$key] = [ $value['sample_requirement_id'], $value['job_sta_upd'], $value['job_re_sta_upd'], $checkVal, $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
               $value['spec_code_id'], $value['sample_requirement'], $value['sam_ref_no'], $value['job_schd_date'], $value['job_status'], $value['job_sta_upd_dt'], $qaPass,$value['qa_status']];

            }
            else {
                $result[$key] = ['',$value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                        $value['spec_code_id'], $value['sample_requirement'], "", "", "", $value['req_size'], $value['req_qty'],$value['qa_status'], ];
            }
        }
        
        $bom_sql = "SELECT b.*
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_mi_bom as b on a.sample_requirement_id = b.sample_req_id
                WHERE a.enquiry_id = ".$id." AND b.request_id= ".$reqId."  and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $bom_data = $this->db->query($bom_sql)->result_array();

        $fab_sql = "SELECT b.*
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_mi_fabric as b on a.sample_requirement_id = b.sample_req_id
                WHERE a.enquiry_id = ".$id." AND b.request_id= ".$reqId."  and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $fab_data = $this->db->query($fab_sql)->result_array();

        $bom_mi_tbl_data = [];
        for ($i=0; $i < sizeof($bom_data); $i++) { 
            $bom_details_sql = "SELECT * FROM tbl_mi_bom_details WHERE mi_bom_id = ".$bom_data[$i]['mi_bom_id']."";
            $bom_details_data = $this->db->query($bom_details_sql)->result_array();
            array_push($bom_mi_tbl_data, $bom_details_data);
        }

        $fab_mi_tbl_data = [];
        for ($i=0; $i < sizeof($fab_data); $i++) { 
            $fab_details_sql = "SELECT * FROM tbl_mi_fabric_details WHERE mi_fabric_id = ".$fab_data[$i]['mi_fabric_id']."";
            $fab_details_data = $this->db->query($fab_details_sql)->result_array();
            array_push($fab_mi_tbl_data, $fab_details_data);
        }

        $bomMaterialIndent = [];
        foreach ($bom_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                $bomMaterialIndent[$key][$nKey] = [ $nValue['mi_bom_details_id'], $nValue['item_desc'], $nValue['bcm'], $nValue['gar_size'], $nValue['item_code'],
                                $nValue['item_color_code'], $nValue['size_dim'], $nValue['uom'], $nValue['ind_qty'], $nValue['ind_uom'], $nValue['dc_ref_queue_no'], $nValue['dc_dt'] ];
            }
        }

        $fabricMaterialIndent = [];
        foreach ($fab_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                $fabricMaterialIndent[$key][$nKey] = [ $nValue['mi_fabric_details_id'], $nValue['fab_ref_no'], $nValue['colour'], $nValue['garment_part'], 
                    $nValue['fabric_blend'], $nValue['fabric_content'], $nValue['fabric_name'], $nValue['gsm'], 
                    $nValue['size_dim'], $nValue['uom'], $nValue['ind_qty'], $nValue['ind_uom'], $nValue['dc_no'], $nValue['issue_date'] ];
            }
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $cad_sql = "SELECT a.request_cad_id as id, a.ref_queue_no as name FROM tbl_request_cad a
                INNER JOIN tbl_request b ON a.request_id = b.request_id
                WHERE b.enquiry_id = ".$id."  ";
        $cad_ref_data = $this->db->query($cad_sql)->result_array();
        
        $mi_data = [];
        if($req_id != null) {
            $mi_sql = "SELECT * FROM tbl_mi_details WHERE request_id = ".$req_id."  ";
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
        $bom2MIDetails = $this->bom2MIDetails($id);

        $fabricMIDetails = $this->fabricMIDetails($id);

        $qa_sql = "SELECT a.*, b.*, c.*, d.cad_ref, d.mat_ind_cad_id,a.log as logs,a.qa_approval as qa_approvel_status, d.req as cad_req, d.purpose as cad_purpose, d.req_size as cad_req_size, b.ref_queue_no as sam_ref_no
                FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id 
                LEFT JOIN tbl_mi_cad_details as d on a.sample_requirement_id = d.sample_req_id
                WHERE a.enquiry_id = ".$id." AND c.request_id= ".$reqId." and a.qa_req_status = 1 ORDER BY a.sample_requirement_id asc";
        $qa_data = $this->db->query($qa_sql)->result_array();
                
        $qa_status_result = [];

        foreach ($qa_data as $key => $value) {
           if (!isset($value['qa_approvel_status']) || $value['qa_approvel_status'] == 0 ) {
        $value['qa_status'] = '8'; // PENDING
         }
         if($value['qa_approvel_status'] == 1 || $value['qa_schd_date'] == null) {
               $value['qa_sta_upd_dt'] = date('d/m/Y h:i A',strtotime($value['logs']));
            
              }


            $qa_status_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
            $value['spec_code_id'], $value['sam_ref_no'], $value['qa_req_sent_dt'], $value['qa_schd_date'], $value['qa_status'], $value['qa_sta_upd_dt'] ];
        }

        $ref_sql = "SELECT b.ref_queue_no as id, b.ref_queue_no as name, a.po_enq_ref_id as po_enq_id, a.combo_id, a.component_id, a.spec_code_id as size_id, a.sample_requirement as req_id 
                    FROM tbl_sample_requirement as a 
                    LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id
                    WHERE a.enquiry_id = " . $id . "  and a.req_sent_status = 1 and b.ref_queue_no != '' ";
        $ref_data = $this->db->query($ref_sql)->result_array();

        $output['data'] = $result;
        $output['sizeData'] = $sizeMaster;
        $output['ref_status'] = $ref_status;
        $output['req_id'] = $req_id;
        $output['referResult'] = array_values($referResult);
        $output['cad_ref_data'] = $cad_ref_data;
        $output['cadMaterialIndent'] = array_values($cadMaterialIndent);
        $output['UOMDetails'] = $UOMDetails;
        $output['BOMAppendData'] = $bomMIDetails;
        $output['BOM2AppendData'] = $bom2MIDetails;

        $output['FabricAppendData'] = $fabricMIDetails;
        $output['bom_mi_tbl_data'] = $bomMaterialIndent;
        $output['fabric_mi_tbl_data'] = $fabricMaterialIndent;
        $output['qastatusdata'] = $qa_status_result;
        $output['jobstatusdata'] = $job_status_result;
        $output['mi_data'] = $mi_data;
        $output['sampleRefNo'] = $ref_data;
        return $output;
    }
    
    public function getSeperateQASampleRequestListt($id, $reqId, $samId) {
        $sql = "SELECT a.*, b.*, c.*, d.cad_ref, d.mat_ind_cad_id, d.req as cad_req, d.purpose as cad_purpose, d.req_size as cad_req_size, b.ref_queue_no as sam_ref_no
                FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id 
                LEFT JOIN tbl_mi_cad_details as d on a.sample_requirement_id = d.sample_req_id
                WHERE a.enquiry_id = ".$id." AND a.sample_requirement_id = ".$samId." AND c.request_id= ".$reqId."  and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $data = $this->db->query($sql)->result_array();
        
        $result = [];
        $referResult = [];
        $cadMaterialIndent = [];
        $job_status_result = [];
        $ref_status = 0;
        $req_id = "";

        foreach ($data as $key => $value)
        {
            $ref_status += (int) $value['req_reference_status'];
            $req_id = $value['request_id'];
            if($value['req_reference_status'] == "1" || $value['req_reference_status'] == 1) 
            {
                $result[$key] = ['edit', $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['sample_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value['req_size'], $value['req_qty'] ];

                $referResult[$key] = ['edit', $value['sample_requirement_id'], $value['req_reference_status'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

                $cadMaterialIndent[$key] = [$value['mat_ind_cad_id'], $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                                $value['spec_code_id'], $value['cad_ref'], $value['cad_req'], $value['cad_purpose'], $value['cad_req_size'], '', '', '', '', '' ];
                
                $job_status_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['sam_ref_no'], $value['job_schd_date'], $value['job_status'], $value['job_sta_upd_dt'] ];

            }
            else {
                $result[$key] = ['', $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                        $value['spec_code_id'], $value['sample_requirement'], "", "", "", $value['req_size'], $value['req_qty'] ];
            }
        }
        
        $bom_sql = "SELECT b.*
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_mi_bom as b on a.sample_requirement_id = b.sample_req_id
                WHERE a.enquiry_id = ".$id."  and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $bom_data = $this->db->query($bom_sql)->result_array();

        $fab_sql = "SELECT b.*
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_mi_fabric as b on a.sample_requirement_id = b.sample_req_id
                WHERE a.enquiry_id = ".$id."  and a.req_sent_status = 1 ORDER BY a.sample_requirement_id asc";
        $fab_data = $this->db->query($fab_sql)->result_array();

        $bom_mi_tbl_data = [];
        for ($i=0; $i < sizeof($bom_data); $i++) { 
            $bom_details_sql = "SELECT * FROM tbl_mi_bom_details WHERE mi_bom_id = ".$bom_data[$i]['mi_bom_id']."";
            $bom_details_data = $this->db->query($bom_details_sql)->result_array();
            array_push($bom_mi_tbl_data, $bom_details_data);
        }

        $fab_mi_tbl_data = [];
        for ($i=0; $i < sizeof($fab_data); $i++) { 
            $fab_details_sql = "SELECT * FROM tbl_mi_fabric_details WHERE mi_fabric_id = ".$fab_data[$i]['mi_fabric_id']."";
            $fab_details_data = $this->db->query($fab_details_sql)->result_array();
            array_push($fab_mi_tbl_data, $fab_details_data);
        }

        $bomMaterialIndent = [];
        foreach ($bom_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                $bomMaterialIndent[$key][$nKey] = [ $nValue['mi_bom_details_id'], $nValue['item_desc'], $nValue['bcm'], $nValue['gar_size'], $nValue['item_code'],
                                $nValue['item_color_code'], $nValue['size_dim'], $nValue['uom'], $nValue['ind_qty'], $nValue['ind_uom'], '', '', '', '', '' ];
            }
        }

        $fabricMaterialIndent = [];
        foreach ($fab_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                $fabricMaterialIndent[$key][$nKey] = [ $nValue['mi_fabric_details_id'], $nValue['fab_ref_no'], $nValue['colour'], $nValue['garment_part'], 
                    $nValue['fabric_blend'], $nValue['fabric_content'], $nValue['fabric_name'], $nValue['gsm'], 
                    $nValue['size_dim'], $nValue['uom'], $nValue['ind_qty'], $nValue['ind_uom'], $nValue['issue_qty'], 
                    $nValue['issue_uom'], $nValue['dc_no'], $nValue['issue_date'] ];
            }
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $cad_sql = "SELECT a.request_cad_id as id, a.ref_queue_no as name FROM tbl_request_cad a
                INNER JOIN tbl_request b ON a.request_id = b.request_id
                WHERE b.enquiry_id = ".$id."  ";
        $cad_ref_data = $this->db->query($cad_sql)->result_array();
        
        $mi_data = [];
        if($req_id != null) {
            $mi_sql = "SELECT * FROM tbl_mi_details WHERE request_id = ".$req_id."  ";
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
        $fabricMIDetails = $this->fabricMIDetails($id);

        $qa_sql = "SELECT a.*, b.*, c.*, d.cad_ref, d.mat_ind_cad_id, d.req as cad_req, d.purpose as cad_purpose, d.req_size as cad_req_size, b.ref_queue_no as sam_ref_no
                FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id 
                LEFT JOIN tbl_mi_cad_details as d on a.sample_requirement_id = d.sample_req_id
                WHERE a.enquiry_id = ".$id." AND a.sample_requirement_id = ".$samId." AND c.request_id= ".$reqId."  and a.qa_req_status = 1 ORDER BY a.sample_requirement_id asc";
        $qa_data = $this->db->query($qa_sql)->result_array();
                
        $qa_status_result = [];

        foreach ($qa_data as $key => $value) {
            $qa_status_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
            $value['spec_code_id'], $value['sam_ref_no'], $value['qa_req_sent_dt'], $value['qa_schd_date'], $value['qa_status'], $value['qa_sta_upd_dt'] ];
        }

        $output['data'] = $result;
        $output['sizeData'] = $sizeMaster;
        $output['ref_status'] = $ref_status;
        $output['req_id'] = $req_id;
        $output['referResult'] = array_values($referResult);
        $output['cad_ref_data'] = $cad_ref_data;
        $output['cadMaterialIndent'] = array_values($cadMaterialIndent);
        $output['UOMDetails'] = $UOMDetails;
        $output['BOMAppendData'] = $bomMIDetails;
        $output['FabricAppendData'] = $fabricMIDetails;
        $output['bom_mi_tbl_data'] = $bomMaterialIndent;
        $output['fabric_mi_tbl_data'] = $fabricMaterialIndent;
        $output['qastatusdata'] = $qa_status_result;
        $output['jobstatusdata'] = $job_status_result;
        $output['mi_data'] = $mi_data;
        return $output;
    }

    public function getQAReceivedDetailss($enqId, $reqId, $samId) {
        
        $sql = "SELECT b.*,c.*, d.*, d.qa_cutoff_date as qa_cod, d.qa_req_sent_dt as qa_qrd, a.qa_req_id as qa_req_ids FROM sample_qa_request as a 
                INNER JOIN tbl_sample_requirement d on a.qa_req_id=d.qa_req_id
                INNER JOIN tbl_request_sample b on d.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE a.qa_req_id= " . $samId . " AND c.request_id = " . $reqId . "  and d.req_sent_status = 1 and d.qa_req_status=1 and (d.qa_approval=0 or d.qa_approval=2)";
        $data = $this->db->query($sql)->result_array();
        
        $result = $sampleReqData = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], $value['if_revised'], $value['req_size'], $value['req_qty'] ];

            $attachRefData[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($enqId);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);

        $ref_sql = "SELECT b.ref_queue_no as id, b.ref_queue_no as name, c.po_enq_ref_id as po_enq_id, c.combo_id, c.component_id, c.spec_code_id as size_id, c.sample_requirement as req_id FROM sample_qa_request as a 
                    LEFT JOIN tbl_sample_requirement as c on a.qa_req_id = c.qa_req_id
                    LEFT JOIN tbl_request_sample as b on c.sample_requirement_id = b.sample_id
                    WHERE c.enquiry_id = " . $enqId . "  and c.req_sent_status = 1 and b.ref_queue_no != '' ";
        $ref_data = $this->db->query($ref_sql)->result_array();
        
        $output['data'] = $data;
        $output['sizeData'] = $sizeMaster;
        $output['sampleReqData'] = $result;
        $output['attachRefData'] = $attachRefData;
        $output['sampleRefNo'] = $ref_data;
        return $output;
    }

    public function updateQAReceivedDetailss($eId, $rId, $qa_status, $req_data) {

        if($qa_status == 1 || $qa_status == "1")
        {

            $cad_req_sql = "SELECT MAX(queue_no)+1 as last_queue_no FROM tbl_cad_requirement";
            $cad_req_data = $this->db->query($cad_req_sql)->result_array();

            $samp_req_sql = "SELECT MAX(queue_no)+1 as last_queue_no FROM tbl_sample_requirement";
            $sam_req_data = $this->db->query($samp_req_sql)->result_array();
    
            $ord_sql = "SELECT reqforisrior FROM kn_order_enquiry WHERE id=$eId";
            $ord_data = $this->db->query($ord_sql)->result_array();
            $reqforisrior = $ord_data[0]['reqforisrior'];
            $ArrIsrIor   = unserialize(ARRISRIOR);
    
            $cad_queue_no = $cad_req_data[0]['last_queue_no'];
            $sam_queue_no = $sam_req_data[0]['last_queue_no'];

            if($cad_queue_no == "" && $sam_queue_no == "") 
            { 
                $queue_no = 1;
            }
            else {
                if($cad_queue_no > $sam_queue_no) {
                    $queue_no = $cad_queue_no;
                }
                else {
                    $queue_no = $sam_queue_no;
                }
            }
            
            
    
            $ref_queue_no = $ArrIsrIor[$reqforisrior]."-BSG".$eId."/".date('my')."/QQ".$queue_no;

            // $requestValue['queue_no'] = $queue_no;
            // $requestValue['ref_queue_no'] = $ref_queue_no;
            
        }

        $requestValue['qa_approval'] = $qa_status;
        $requestValue['log'] = LOGTIME;

        foreach ($req_data as $key => $value) {
            
            $qa_app = $this->db->where('sample_requirement_id',$value[0])->get('tbl_sample_requirement')->row()->qa_approval;
           
            if($qa_app == 0) {
                $requestValue['queue_no'] = $queue_no;
                $requestValue['ref_queue_no'] = $ref_queue_no;
                $requestValue['qno_assign_dt'] = $this->mysqldatetime;
                $requestValue['qa_status'] = 0;
                $requestValue['qa_req_status'] = $qa_status;
            } else {
                //$requestValue['qa_status'] = 3;
                 $requestValue['qa_status'] = 9;
                $requestValue['qa_schd_date'] = '';
        
                
            }
            //echo "<pre>"; print_r($requestValue); exit;
            $this->db->where('enquiry_id', $eId);
            $this->db->where('sample_requirement_id', $value[0]);
            $this->db->update('tbl_sample_requirement', $requestValue);
        }
    }

    
    public function imageUploadDetailss($type, $id, $reqId, $filepathName, $deptId)
    {
        if(!isset($deptId))
        {
            $this->db->insert('tbl_wip_files', array('enquiry_id'=> $id, 'request_id'=> $reqId, 'type'=> $type, 'image_url'=>$filepathName));
            $result["status"] = "success";
            return $result;
        }
        else {
            $this->db->insert('tbl_wip_files', array('enquiry_id'=> $id, 'request_id'=> $reqId, 'type'=> $type, 'image_url'=>$filepathName, 'dept_id'=> $deptId));
            $result["status"] = "success";
            return $result;
        }
    }

    public function getQaStatus($id, $reqId)
    {
        $qa_sql = "SELECT *
                FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id
                WHERE a.enquiry_id = ".$id." AND c.request_id= ".$reqId."  and (a.qa_req_status = 0 or a.qa_status = 7 or a.qa_status = 4) ";
        $qa_data = $this->db->query($qa_sql)->result_array();
        return $qa_data;
    }

    public function getRequestSentdetailss($enqId, $reqId, $samId) {
        $sql = "SELECT * FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE a.qa_req_id = " . $samId . " AND c.request_id = " . $reqId . "  and a.req_sent_status = 1 and a.qa_req_status=1  and (a.qa_approval=0 or a.qa_approval=2)";
        $data = $this->db->query($sql)->result_array();
        
        $result = [];

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($enqId);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);

        $ref_sql = "SELECT b.ref_queue_no as id, b.ref_queue_no as name, a.po_enq_ref_id as po_enq_id, a.combo_id, a.component_id, a.spec_code_id as size_id, a.sample_requirement as req_id 
                    FROM tbl_sample_requirement as a 
                    LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id
                    WHERE a.enquiry_id = " . $enqId . "  and a.req_sent_status = 1 and b.ref_queue_no != '' ";
        $ref_data = $this->db->query($ref_sql)->result_array();
        
        $output['data'] = $data;
        $output['sizeData'] = $sizeMaster;
        $output['sampleRefNo'] = $ref_data;
        return $output;
    }

    public function getDCStatus($id, $reqId)
    {
        $qa_sql = "SELECT *
                FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id
                WHERE a.enquiry_id = ".$id." AND c.request_id= ".$reqId." AND a.job_status = 7 AND a.dc_status = 1";
        $qa_data = $this->db->query($qa_sql)->result_array();
        return $qa_data;
    }

    public function getDCListt($id, $reqId)
    {
        $sql = "SELECT a.*, b.*, c.*, b.ref_queue_no as sam_queue_no
                FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id
                WHERE a.enquiry_id = ".$id." AND c.request_id= ".$reqId."  AND a.job_status = 7 AND a.dc_status = 1 ORDER BY a.sample_requirement_id asc";
        $data = $this->db->query($sql)->result_array();
        
        $result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'], false, $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['sam_queue_no'], $value['reqrd_size'], $value['dc_issued_qty'] ];
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);

        // *** Columns *** //
        // $output['column'] = [
        //     [ 'title' => "id", 'align' => 'center', 'type'=> 'hidden' ],
        //     [ 'type' => 'checkbox', 'title' => "Mark", 'width' => '8%', 'align' => 'left' ],
        //     [ 'type' => 'text', 'title' => "P.O. No.", 'width' => '8%', 'align' => 'left', 'readOnly'=> true ],
        //     [ 'type' => 'text', 'title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly'=> true ],
        //     [ 'type' => 'text', 'title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly'=> true ],
        //     [ 'type' => 'text', 'title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly'=> true ],
        //     [ 'type' => 'text', 'title' => "Size Spec Code", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true ],
        //     [ 'type' => 'text', 'title' => "Item Issued", 'width' => '7%', 'align'=> 'right', 'readOnly'=> true ],
        //     [ 'type' => 'text', 'title' => "Sample Ref. No.", 'width' => '8%', 'text' => 'center', 'readOnly'=> true ],
        //     [ 'type' => 'dropdown', 'title' => "Issued \n Size(s)", 'width' => '7%', 'source'=> $sizeMaster, 'align'=> 'center', 'readOnly'=> true ],
        //     [ 'type' => 'text', 'title' => "Issued Qty. \n (Nos.)", 'width' => '8%', 'align'=> 'right' ],
        //     [ 'type' => 'hidden', 'title' => "Hidden" ],
        // ];

        $output['data'] = $result;
        $output['req_sizes'] = $sizeMaster;
        return $output;
    }

    public function updategarmentDCListt($id, $reqId, $data, $received_by)
    {

         $req_sql = "SELECT ref_queue_no FROM tbl_request WHERE request_id = ".$reqId;
         $req_data = $this->db->query($req_sql)->result_array();

        $dc_sql = "SELECT MAX(dc_queue_no)+1 as last_queue_no FROM tbl_sample_requirement";
        $dc_data = $this->db->query($dc_sql)->result_array();

        //$queue_no = $dc_data[0]['last_queue_no'];
        //if($queue_no == "") { $queue_no = 1; }
             $subid = $this->subscriberid;
             $wipcount = $this->commonmodel->getsamplerequestCountgi($subid);
             $count = ($wipcount > 0) ? $wipcount+1 : 1; // Default to 1 if no records exist
             $queue_no = $count; 

        $ref_queue_no = $req_data[0]['ref_queue_no']."/GDC-".$queue_no;
        foreach ($data as $key => $value) {
            if($value[1] == true)
            {
                $updateValue['dc_status'] = 0;
                $updateValue['job_status'] = 9;
                $updateValue['dc_ref_queue_no'] = $ref_queue_no;
                $updateValue['dc_queue_no'] = $queue_no;
                $updateValue['dc_dt'] = $this->mysqldatetime;
                $updateValue['job_sta_upd_dt'] = $this->mysqldatetime;
                $updateValue['dc_issued_size'] = $value[9];
                $updateValue['dc_issued_qty'] = $value[10];
                $updateValue['dc_received_by'] = $received_by;
                $updateValue['log'] = LOGTIME;
                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', $updateValue);
            }
        }
    }


     public function updateDCListt($id, $reqId, $data, $received_by)
    {

        $dc_sql = "SELECT MAX(dc_queue_no)+1 as last_queue_no FROM tbl_sample_requirement";
        $dc_data = $this->db->query($dc_sql)->result_array();
           
        $req_sql = "SELECT queue_no FROM tbl_request WHERE request_id = $reqId";
        $req_data = $this->db->query($req_data)->result_array();
           
        $ord_sql = "SELECT reqforisrior FROM kn_order_enquiry WHERE id=$id";
        $ord_data = $this->db->query($ord_sql)->result_array();
        $reqforisrior = $ord_data[0]['reqforisrior'];
        $ArrIsrIor   = unserialize(ARRISRIOR);

         echo("<pre>");print_r($ArrIsrIor);exit;

        $queue_no = $dc_data[0]['last_queue_no'];
        if($queue_no == "") { $queue_no = 1; }

        $ref_queue_no = $ArrIsrIor[$reqforisrior]."-BSG".$id."/".date('my')."/SQ-".$req_data[0]['queue_no']."/GDC-".$queue_no;

        foreach ($data as $key => $value) {
            if($value[1] == true)
            {
                $updateValue['dc_status'] = 0;
                $updateValue['dc_ref_queue_no'] = $ref_queue_no;
                $updateValue['dc_queue_no'] = $queue_no;
                $updateValue['dc_dt'] = $this->mysqldatetime;
                $updateValue['dc_issued_size'] = $value[9];
                $updateValue['dc_issued_qty'] = $value[10];
                $updateValue['dc_received_by'] = $received_by;
                $updateValue['log'] = LOGTIME;
                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', $updateValue);
            }
        }
    }
    public function getGarmentReceivedListt($id, $reqId, $gdcno)
    {
        $sql = "SELECT a.*, b.*, c.*, b.ref_queue_no as sam_queue_no
                FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id
                WHERE a.enquiry_id = ".$id." AND c.request_id= ".$reqId." AND a.dc_ref_queue_no='".$gdcno."'   AND a.job_status = 9 AND a.dc_status = 0 ORDER BY a.sample_requirement_id asc";
        $data = $this->db->query($sql)->result_array();
        
        $result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['sam_queue_no'], $value['dc_issued_size'], $value['dc_issued_qty'] ];
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);

        $output['data'] = $result;
        $output['req_sizes'] = $sizeMaster;
        //$output['requestData'] = $data;
        

       
        
        return $output;
    }


    public function getGarmentReceivedListt_print($id, $reqId, $gdcno)
    {
        $sql = "SELECT a.*, b.*, c.*, b.ref_queue_no as sam_queue_no
                FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id
                WHERE a.enquiry_id = ".$id." AND c.request_id= ".$reqId." AND a.dc_ref_queue_no='".$gdcno."'   AND a.job_status = 9 AND a.dc_status = 0 ORDER BY a.sample_requirement_id asc";
        $data = $this->db->query($sql)->result_array();
        
        $result = [];

        foreach ($data as $key => $value)
        {

            $dc_issued_size = rtrim($value['dc_issued_size'], ';');
         if (!empty($dc_issued_size)) {
        $sizeMaster = $this->getSizeMasterDropdown($dc_issued_size);
        if (!empty($sizeMaster) && isset($sizeMaster[0]['name'])) {
            $sizeMasterss = $sizeMaster[0]['name'];
        }
    }
            $result[$key] = [ $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['sam_queue_no'], $sizeMasterss, $value['dc_issued_qty'] ];
        }



        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);

        $output['data'] = $result;
        $output['req_sizes'] = $sizeMaster;
        //$output['requestData'] = $data;
        

       
        
        return $output;
    }

     public function getGarmentRecListt($id, $reqId,$samid)
    {
        $sql = "SELECT a.*, b.*, c.*, b.ref_queue_no as sam_queue_no
                FROM tbl_sample_requirement as a 
                LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id 
                LEFT JOIN tbl_request as c on b.request_id=c.request_id
                WHERE a.enquiry_id = ".$id." AND c.request_id= ".$reqId." AND a.sample_requirement_id = $samid AND c.request_id= ".$reqId."  AND a.job_status = 9 AND a.dc_status = 0 ORDER BY a.sample_requirement_id asc";
        $data = $this->db->query($sql)->result_array();
        
        

       
        
        return $data;
    }

    public function updateGarmentReceivedListt($id, $reqId, $data, $irs)
    {
        foreach ($data as $key => $value) {
            if($value[1] == true)
            {
                $updateValue['item_sta_upt_dt'] = $this->mysqldatetime;
                $updateValue['item_received_status'] = $irs;
                $updateValue['log'] = LOGTIME;
                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', $updateValue);
            }
        }
    }

    function getDCDetails($enqId, $reqId, $miId)
    {
        $sql = "SELECT a.*, b.*, b.contactname as sam_name, a.ref_queue_no as sam_queue_no 
                from tbl_request a
                LEFT JOIN ".KN_USERS." as b on a.cad_by=b.id 
                where enquiry_id='$enqId' and request_id='$reqId' ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function getCADMIData($miId, $dc)
    {
        // $sql = 'SELECT * FROM tbl_mi_details as a
        //         INNER JOIN tbl_mi_cad_details as b ON a.request_id=b.request_id
        //         WHERE b.dc_ref_queue_no = '.$dc.' GROUP BY b.dc_ref_queue_no';
        // $data = $this->db->query($sql)->result_array();
        // return $data;
        
        $sql = 'SELECT a.*,b.*,c.*,e.ref_queue_no,d.contactname as issued_name,f.cad_ref_no FROM tbl_mi_details as a 
                INNER JOIN tbl_mi_bom as b ON a.request_id=b.request_id
                INNER JOIN tbl_mi_bom_details as c ON b.mi_bom_id=c.mi_bom_id
                INNER JOIN '.KN_USERS.' as d on c.issue_by=d.id
                INNER JOIN tbl_request e on a.req_bom_id = e.request_id
                INNER JOIN tbl_mi_details f on a.mi_id = f.mi_id
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
     public function getStoreDetails()
    {
        $sql = "SELECT * FROM ".KN_USERS." WHERE usertype = 8 ";
        $data = $this->db->query($sql)->result_array();
        return $data;
        
    }

    public function updateMIDCListt($id, $reqId, $dc, $irs)
    {
        $updateValue['item_sta_upt_dt'] = $this->mysqldatetime;
        $updateValue['item_received_status'] = $irs;
        $updateValue['log'] = LOGTIME;
        $this->db->where('dc_no', $dc);
        $this->db->update('tbl_mi_issued_details', $updateValue);
    }

    public function getVendorDetailss() 
    {
        $sql = 'SELECT id, vendorname as name FROM kn_master_bom_vendor';
        $data = $this->db->query($sql)->result_array();
       
        return $data;
    }
    
    public function getSamDCDetailss($id, $miId, $dc)
    {
        $dc = '"'.$dc.'"';
        // $sql = "SELECT * FROM tbl_mi_details as a
        //         INNER JOIN tbl_mi_bom_details as c on a.request_id=c.request_id
        //         WHERE c.mi_id=".$miId." AND c.dc_ref_queue_no = $dc AND c.dc_status = 0 AND a.flag=1";
        
        // $sql = 'SELECT a.*,b.*,c.*,d.* FROM tbl_mi_details as a 
        //         INNER JOIN tbl_mi_bom as b ON a.request_id=b.request_id
        //         INNER JOIN tbl_mi_bom_details as c ON b.mi_bom_id=c.mi_bom_id
        //         INNER JOIN tbl_mi_issued_details as d on c.mi_bom_details_id=d.mi_bom_details_id
        //         WHERE c.dc_ref_queue_no = '.$dc.' ';
        // $result = $this->db->query($sql)->result_array();
        
        $sql = "SELECT * FROM tbl_mi_issued_details as a
                INNER JOIN tbl_mi_bom_details as b on a.mi_bom_details_id=b.mi_bom_details_id
                INNER JOIN tbl_mi_bom as c on b.mi_bom_id=c.mi_bom_id
                INNER JOIN tbl_mi_details as d on c.request_id=d.request_id
                WHERE a.dc_no=".$dc." ";
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
                WHERE b.enquiry_id = ".$id."  ";
        $cad_ref_data = $this->db->query($cad_sql)->result_array();

        $data['cadMIData'] = $BomMIData;
        $data['sizeData'] = $sizeMaster;
        $data['cadRefNo'] = $cad_ref_data;
        return $data;
    }
    
    
    public function getQASampleQueueDetailss($enqId, $reqId, $samId) {
        $sql = "SELECT *, b.ref_queue_no as sam_ref_no FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . " AND a.qa_req_id = " .$samId;
        $data = $this->db->query($sql)->result_array();
        
        $result = $att_result = $qa_status = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], $value['if_revised'], $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];
                            
            if($value['qa_status'] == "5" || $value['qa_status'] == "6" || $value['qa_status'] == "7") {
                $checkVal = 1;
                $editStatus = 1;
                $qaStatusss = 1;
                $qaStatus[] = 1;
            } else {
                $checkVal = 0;
                $editStatus = 0;
                $qaStatusss = 0;
                $qaStatus[] = 0;
            }

            $qa_status[$key] = [ $value['sample_requirement_id'], $value['qa_sta_upd'], $value['qa_re_sta_upd'], $checkVal, $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['sam_ref_no'], $value['qa_schd_date'], $value['qa_status'], $value['qa_sta_upd_dt'], $editStatus, $qaStatusss ];
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($enqId);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);

        $ref_sql = "SELECT b.ref_queue_no as id, b.ref_queue_no as name, a.po_enq_ref_id as po_enq_id, a.combo_id, a.component_id, a.spec_code_id as size_id, a.sample_requirement as req_id 
                    FROM tbl_sample_requirement as a 
                    LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id
                    WHERE a.enquiry_id = " . $enqId . "  and a.req_sent_status = 1 and b.ref_queue_no != '' ";
        $ref_data = $this->db->query($ref_sql)->result_array();
        
        $output['data'] = $result;
        $output['req_data'] = $data;
        $output['attachmentdata'] = $att_result;
        $output['qa_status_data'] = $qa_status;
        $output['sizeData'] = $sizeMaster;
        $output['sampleRefNo'] = $ref_data;
        return $output;
    }
    
    function getQACompletedStatus($enqId, $reqId)
    {
        
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                    LEFT JOIN tbl_request_sample as b on a.sample_requirement_id = b.sample_id
                    WHERE a.enquiry_id = " . $enqId . " AND b.request_id = " . $reqId . "  AND (a.qa_status = 5 OR a.qa_status = 6) AND (a.job_status = 4) ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    public function updateJobCompletedd($id, $req_id, $req_data) {
        
        $requestValue['log'] = LOGTIME;

        $this->db->where('enquiry_id', $id);
        $this->db->where('request_id', $req_id);
        $this->db->update('tbl_request', $requestValue);  
        foreach($req_data as $key => $value) {
            if($value[3] == true && $value[14] == 'Yes' ) {
                $jobStatus['job_status'] = 4;                
                $jobStatus['job_sta_upd_dt'] = $this->mysqldatetime;
                $jobStatus['log'] = LOGTIME;

                $this->db->where('sample_requirement_id', $value[0]);
                $this->db->update('tbl_sample_requirement', $jobStatus);
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

}
