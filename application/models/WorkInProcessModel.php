<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WorkInProcessModel extends CI_Model
{

    private $mysqldatetime;
    public function __construct()
    {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo   = fnGetUserLoggedInfo('1');
        $this->companyid     = $ArrUserLoggedInfo['companyid'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->mysqltime = date('h:m:s');
        $this->userid        = $ArrUserLoggedInfo['id'];
          $this->subscriberid     = $ArrUserLoggedInfo['subscriber_id'];

    }

    // ********** COLOR / COMBO QUANTITY BREAKUP STARTS HERE *********** /
    public function getComboColourDetailss($id) {
        $sql = "SELECT * FROM tbl_oe_combo_color WHERE enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = ['edit', $value['combo_color_id'], $value['combo'], $value['component'], $value['colour'], $value['intake_qty'], $value['qty'], $value['pcs_set']];
        }

        $output['column'] = [
            ['title' => "mode", 'width' => '10%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '10%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Combo", 'width' => '10%', 'align' => 'left'],
            ['title' => "Component", 'width' => '10%', 'align'=> 'left'],
            ['title' => "Colour", 'width' => '10%', 'align'=> 'left'],
            ['title' => "Intake Qty. Per\nComp. (Nos.)", 'width' => '10%', 'align'=> 'center'],
            ['title' => "Qty.", 'width' => '10%', 'align'=> 'right'],
            ['title' => "Pcs. / Set", 'width' => '10%', 'type'=> 'dropdown', 'source'=> ['Set', 'Pcs'], 'align'=> 'center']
        ];
        $output['data'] = $result;
        return $output;
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

     public function getorderentrycompanydetail($enq_id)
    {
        
           $sql = "SELECT * FROM " . KN_ORDER_ENQUIRY . " as a
                WHERE id = " . $enq_id." ";
        $result = $this->db->query($sql)->result_array();
         foreach ($result as $key => $value) {
        $result[$key]['pcsorset_text'] = ($value['pcsorset'] == "1") ? "Pcs" : "Set";
    }
        
        
        return $result;
    }

    public function updateColorComboDetailss_old($req_data, $id) {
        foreach ($req_data as $key => $value)
        {
            $comboColor["combo_color_id"] = $value[1];
            $comboColor["combo"] = $value[2];
            $comboColor["component"] = $value[3];
            $comboColor["colour"] = $value[4];
            $comboColor["intake_qty"] = $value[5];
            $comboColor["qty"] = $value[6];
            $comboColor["pcs_set"] = $value[7];
            $comboColor["enquiry_id"] = $id;

            if($value[0] == "edit") { // check mode*********
                $this->db->where('combo_color_id', $comboColor["combo_color_id"]);
                $this->db->update('tbl_oe_combo_color', $comboColor);
            }
            else {
                unset($comboColor["combo_color_id"]);
                $this->db->insert('tbl_oe_combo_color', $comboColor);
                $primaryId = $this->db->insert_id();
            }
        }
        $result["status"] = "success";
        $result["statusCode"] = "200";
        return $result;
    }

    public function updateColorComboDetailss($req_data, $id) {
    $incoming_ids = [];

    // Step 1: Collect existing combo_color_id from incoming data
    foreach ($req_data as $key => $value) {
        if ($value[0] == "edit") {
            $incoming_ids[] = $value[1]; // Collect existing IDs
        }
    }

    // Step 2: Delete rows not present in incoming data
    if (!empty($incoming_ids)) {
        $this->db->where('enquiry_id', $id);
        $this->db->where_not_in('combo_color_id', $incoming_ids);
        $this->db->delete('tbl_oe_combo_color');
    } else {
        // No existing IDs in request — delete all related to enquiry_id
        $this->db->delete('tbl_oe_combo_color', ['enquiry_id' => $id]);
    }

    // Step 3: Insert or Update logic
    foreach ($req_data as $key => $value) {
        $comboColor["combo_color_id"] = $value[1];
        $comboColor["combo"] = $value[2];
        $comboColor["component"] = $value[3];
        $comboColor["colour"] = $value[4];
        $comboColor["intake_qty"] = $value[5];
        $comboColor["qty"] = $value[6];
        $comboColor["pcs_set"] = $value[7];
        $comboColor["enquiry_id"] = $id;

        if ($value[0] == "edit") {
            $this->db->where('combo_color_id', $comboColor["combo_color_id"]);
            $this->db->update('tbl_oe_combo_color', $comboColor);
        } else {
            unset($comboColor["combo_color_id"]);
            $this->db->insert('tbl_oe_combo_color', $comboColor);
        }
    }

    return [
        "status" => "success",
        "statusCode" => "200"
    ];
}

    
    // ********** PO SIZE WISE QUANTITY BREAKUP STARTS HERE *********** /

    public function getPoSizewiseDetailsS($id) 
    {
        $sql = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();
        
        $result = [];
        foreach ($data as $key => $value)
        {
            // $result[$key] = ['edit', $value['po_size_wise_id'], $value['pono_enq_refno'], $value['combo_color_id'], $value['combo'], $value['component'], $value['po_qty']];
            $result[$key] = ['edit', $value['po_size_wise_id'], $value['pono_enq_refno'], $value['combo_color_id'], $value['component'], $value['colour'], $value['intake_qty']];
            $sizewise_data = explode(', ', $value['sizewise_data']);
            for($j = 0; sizeof($sizewise_data) > $j; $j++)
            {
                array_push($result[$key], $sizewise_data[$j]);
            }
            array_push($result[$key], $value['po_qty']);
            array_push($result[$key], $value['pcs_set']);
        }

        // GET THE COLUMN
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);

        $column = [
            ['title' => "mode", 'width' => '10%', 'align' => 'center', 'type'=> 'hidden',],
            ['title' => "id", 'width' => '10%', 'align' => 'center', 'type'=> 'hidden',],
            ['title' => "P.O. No. /\nEnq. Ref. No.", 'width' => '10%', 'align' => 'left'],
            ['title' => "Combo", 'width' => '10%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $this->_getCombo($id)],
            ['title' => "Component", 'width' => '10%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '10%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Intake Qty. Per\nComp. (Nos.)", 'width' => '10%', 'align'=> 'center', 'readOnly' => true]
        ];

        foreach ($sizeMaster as $key => $value)
        {
            $size[] = ['title' => $value['size_name'], 'width' => 20, 'align' => 'right'];
        }

        $finalData = [
            ['title' => "P.O. Qty. /\n Sample Qty.", 'readOnly' => true, 'width' => '10%', 'align'=> 'right'],
            ['title' => "Pcs. / Set", 'width' => '10%', 'readOnly' => true, 'align'=> 'center']
        ];
        $column     = array_merge($column, $size);
        $column     = array_merge($column, $finalData);

        // *** NESTED HEADER STARTS *** //

        $nestedHeader = [["title" => "", "colspan" => "5"]];
        $sizeCount = sizeof($sizeMaster);
        $sizeNestedHeader = [["title" => "Garment Sizes", "colspan" => $sizeCount]];
        $finalNestedHeader = [["title" => "", "colspan" => "2"]];
        $nestedHeader = array_merge($nestedHeader, $sizeNestedHeader);
        $nestedHeader = array_merge($nestedHeader, $finalNestedHeader);

        // *** NESTED HEADER ENDS *** //

        $output['column'] = $column;
        $output['nestedHeader'] = $nestedHeader;
        $output['data'] = $result;
        $output['inputCount'] = sizeof($sizeMaster);
        return $output;
    }

    function _getCombo($id)
    {
        $this->db->select('combo_color_id as id,combo as name, component, colour, intake_qty, qty, pcs_set');
        $ArrRes = $this->db->get_where('tbl_oe_combo_color', ['enquiry_id' => $id, ]);
        return $ArrRes->result_array();
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

    

public function updatePOSizeDetailss($req_data, $id) {
    $incoming_ids = [];

    // Step 1: Collect existing po_size_wise_id from incoming data
    foreach ($req_data as $key => $value) {
        if ($value[0] == "edit") {
            $incoming_ids[] = $value[1]; // Collect existing IDs
        }
    }

    // Step 2: Delete rows not present in incoming data
    if (!empty($incoming_ids)) {
        $this->db->where('enquiry_id', $id);
        $this->db->where_not_in('po_size_wise_id', $incoming_ids);
        $this->db->delete('tbl_oe_po_wise');
    } else {
        // No existing IDs in request — delete all related to enquiry_id
        $this->db->delete('tbl_oe_po_wise', ['enquiry_id' => $id]);
    }

    // Step 3: Insert or Update logic
    foreach ($req_data as $key => $value) {
        $length = sizeof($value);

        $comboColor["po_size_wise_id"] = $value[1];
        $comboColor["enquiry_id"] = $id;
        $comboColor["pono_enq_refno"] = $value[2];
        $comboColor["combo_color_id"] = $value[3];
        $comboColor["combo"] = $value[4];
        $comboColor["po_qty"] = $value[$length - 2];

        // Extract sizewise data from dynamic columns
        $as = [];
        for ($i = 0; $i < $length; $i++) {
            if ($i >= 7 && $i <= $length - 3) {
                $as[] = $value[$i];
            }
        }
        $comboColor["sizewise_data"] = implode(', ', $as);

        if ($value[0] == "edit") {
            $this->db->where('po_size_wise_id', $comboColor["po_size_wise_id"]);
            $this->db->update('tbl_oe_po_wise', $comboColor);
        } else {
            unset($comboColor["po_size_wise_id"]);
            $this->db->insert('tbl_oe_po_wise', $comboColor);
        }
    }

    return [
        "status" => "success",
        "statusCode" => "200"
    ];
}
    
    // ********** COMPONENT INTAKE WISE ITEMIZED STARTS HERE *********** / 

    public function getOrderEntryComponentItemizedd($id)
    {
        $sql = "SELECT *, b.combo as po_combo FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();
     

        $component_sql = "SELECT * from tbl_oe_component_intake_wise WHERE enquiry_id = '$id'  ORDER BY component_intake_wise_id ASC";
        $component_data = $this->db->query($component_sql)->result_array();
        // ******* GET THE DATA DETAILS ENDS ********* //

        $split_component = [];
        $split_colour = [];
        $split_intake_qty = [];
        $split = [];

        for($i=0; $i < sizeof($data); $i++) {
            $component = $data[$i]['component'];
            $split = explode("/", $component);
            foreach ($split as $key => $value) {
                array_push($split_component, $value);
            }
            
            $colour = $data[$i]['colour'];
            $split = explode("/", $colour);
            foreach ($split as $key => $value) {
                array_push($split_colour, $value);
            }
            
            $intake_qty = $data[$i]['intake_qty'];
            $split = explode("/", $intake_qty);
            foreach ($split as $key => $value) {
                array_push($split_intake_qty, $value);
            }
        }
        
        //***** spec data *****
        $spec_code_data = [];
        $intake_id_data = [];
        if(sizeof($component_data) == 0) {
            foreach($split_component as $key => $value) {
                $empty = '';
                array_push($spec_code_data, $empty);
                array_push($intake_id_data, $empty);
            }
        }

        //***** final data *****
        $finalValue = [];
        for($i=0; $i < sizeof($split_component); $i++) {

            $po_size_wise_id = $pono_enq_refno = $combo_color_id = $combo = $sizewise_data = $po_qty = $component = 
            $combo = $colour = $intake_qty = $qty = "";

            //***** spec data *****
            if(sizeof($component_data) > 0) {
                if( isset( $component_data[$i]['size_spec_code_fit'] ) ) 
                {
                    $spec_code = $component_data[$i]['size_spec_code_fit'];
                    $intake_id_ = $component_data[$i]['component_intake_wise_id'];
                    if($spec_code == 'novalue') { $spec_code = ""; }
                    array_push($spec_code_data, $spec_code);
                    array_push($intake_id_data, $intake_id_);
                }
                else {
                    $spec_code = "";
                    $intake_id_ = '000';
                    array_push($spec_code_data, $spec_code);
                    array_push($intake_id_data, $intake_id_);
                }
            }
            
            $pono_enq_split = [];
            $combo_split = [];
            $sizewise_split = [];

            for($j=0; $j < sizeof($data); $j++) {

                $po_size_wise_id    = $data[$j]['po_size_wise_id'];
                $pono_enq_refno     = $data[$j]['pono_enq_refno'];
                $combo_color_id     = $data[$j]['combo_color_id'];
                $combo              = $data[$j]['combo'];
                $sizewise_data      = $data[$j]['sizewise_data'];
                $po_qty             = $data[$j]['po_qty'];
                $component          = $data[$j]['component'];
                $combo              = $data[$j]['po_combo'];
                $colour             = $data[$j]['colour'];
                $intake_qty         = $data[$j]['intake_qty'];
                $qty                = $data[$j]['qty'];

                $data_component_split = explode("/", $component);
                foreach ($data_component_split as $key => $value) {
                    array_push($pono_enq_split, $pono_enq_refno);
                    array_push($combo_split, $combo);
                    array_push($sizewise_split, $sizewise_data);
                }
            }

            $split_sizewise_data = [];
            $split_value = [];
            $split_value = explode(",", $sizewise_split[$i]);
            foreach ($split_value as $key => $value) {
                $value = (int)$value * (int)$split_intake_qty[$i];
                array_push($split_sizewise_data, $value);
            }

            $combineValue = ['edit', $po_size_wise_id, $pono_enq_split[$i], $combo_split[$i], $split_component[$i], $split_colour[$i], $split_intake_qty[$i], $spec_code_data[$i]];
            $combineValue = array_merge($combineValue, $split_sizewise_data);
            $sumValue = array(array_sum($split_sizewise_data));
            $combineValue = array_merge($combineValue, $sumValue);
            $component_intake_id_value = array($intake_id_data[$i]);
            $combineValue = array_merge($combineValue, $component_intake_id_value);
            array_push($finalValue, $combineValue);
            
        }
        // print_r($finalValue);
        // exit();
        // ******* GET THE DATA DETAILS START ********* //

        // ******* GET THE COLUMN START ********* //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);
        $column = [
            ['title' => "mode", 'width' => '10%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '10%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "P.O. No. /\nEnq. Ref. No.", 'width' => '10%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Combo", 'width' => '10%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '10%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '10%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Intake Qty. Per\nComp. (Nos.)", 'width' => '10%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Size Spec\n Code / Fit", 'width' => '10%', 'align'=> 'left']
        ];
        foreach ($sizeMaster as $key => $value)
        {
            $size[] = ['title' => $value['size_name'], 'width' => 20, 'align' => 'right', 'readOnly' => true];
        }
        $finalData = [
            ['title' => "Itemized Qty.\n(Pcs.)", 'width' => '10%', 'readOnly' => true, 'align'=> 'right'],
            ['title' => "component_intake_id", 'type'=> 'hidden']
        ];
        $column     = array_merge($column, $size);
        $column     = array_merge($column, $finalData);
        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    public function updateOrderEntryComponentItemizedd($req_data, $id) {
    
        $ArrRes = $this->db->get_where('tbl_oe_component_intake_wise', ['enquiry_id' => $id, ]);
        $res = $ArrRes->result_array();
        if(sizeof($res) > 0) {

            if(sizeof($res) == sizeof($req_data)) 
            {
                foreach ($req_data as $key => $value) {
                    if($value[7] == "") { $value[7] = 'novalue'; } 

                    $length = sizeof($value);
                    // $component_intake_wise_id = $value[13];
                    $component_intake_wise_id = $value[$length-1];
                    $values['size_spec_code_fit'] = $value[7];
                    $this->updateComponentIntake($id, $component_intake_wise_id, $values);
                }
                return true;
            }
            else {
                foreach ($req_data as $key => $value) {
                    if($value[7] == "") { $value[7] = 'novalue'; } 

                    $length = sizeof($value);
                    // $component_intake_wise_id = $value[13];
                    $component_intake_wise_id = $value[$length-1];
                    if($component_intake_wise_id === "000") 
                    {
                        $values['size_spec_code_fit'] = $value[7];
                        $values['enquiry_id'] = $id;
                        $this->saveComponentIntake($values);
                    }
                    else {
                        $values['size_spec_code_fit'] = $value[7];
                        $this->updateComponentIntake($id, $component_intake_wise_id, $values);
                    }
                    
                }
                return true;
            }
        }
        else {
            foreach ($req_data as $key => $value) {
                if($value[7] == "") { $value[7] = 'novalue'; } 
                $values['size_spec_code_fit'] = $value[7];
                $values['enquiry_id'] = $id;
                $this->saveComponentIntake($values);
            }
            return true;
        }
    }

    public function saveComponentIntake($values) 
    {
        $this->db->insert('tbl_oe_component_intake_wise', $values);
    }
    
    public function updateComponentIntake($id, $component_intake_wise_id, $values) 
    {
        $this->db->where('enquiry_id', $id);
        $this->db->where('component_intake_wise_id', $component_intake_wise_id);
        $this->db->update('tbl_oe_component_intake_wise', $values);
        return true;
    }

    // ********** COMPONENT INTAKE WISE ITEMIZED ENDS HERE *********** /

    // ********** PO WISE DELIVERY STARTS HERE *********** /

    public function getPoWiseDeliveryDetailss($id) 
    {
        $sql = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = ['edit', $value['po_size_wise_id'], $value['pono_enq_refno'], $value['po_enquiry_date'], $value['po_shipment_date'], 
            $value['combo'], $value['po_qty'], $value['pcs_set'], $value['po_shipment_mode'], $value['loading_port_city'], 
            $value['loading_country'], $value['destination_port_city'], $value['destination_country']];
        }

        $modeOfShipment = unserialize(ORDERMODEOFSHIPMENT);

        $ArrPortList = $this->portList();

        //file_put_contents("error_log", print_r($ArrPortList, true));

        $i = 0;
        $ArrFnlList = array();
        $ArrStatus = unserialize(ARRSTATUS);
        $ArrCountryList = unserialize(ARRCOUNTRYLIST);
        foreach ($ArrPortList as $ObjPort) {
            $ArrFnlList[$i]['id'] = $ObjPort->id;
            $ArrFnlList[$i]['name'] = $ObjPort->portname . ', ' . $ObjPort->portcity;
            $ArrFnlList[$i]['cty'] = $ObjPort->portcity;
            $ArrFnlList[$i]['pcntry'] = $ObjPort->countryname;
            $ArrFnlList[$i]['s'] = $ArrStatus[$ObjPort->status];
            $ArrFnlList[$i]['du'] = date('d-m-Y H:i:s', strtotime($ObjPort->dateupdated));
            $i = $i + 1;
        }

         // file_put_contents("error_log", print_r($ArrFnlList, true));

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden',],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden',],
            ['title' => "P.O. No. / Enq. Ref. No.", 'width' => '12%', 'align' => 'left', 'readOnly' => true],
            ['title' => "P.O. / Enquiry \n Date", 'width' => '8%', 'align'=> 'center', 'type' => 'calendar'],
            ['title' => "Shipment /\n Submission Date", 'width' => '8%', 'align'=> 'center', 'type' => 'calendar'],
            ['title' => "Combo / Colour", 'width' => '10%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "P.O. / Sample\n Qty.", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Pcs. / Set", 'width' => '6%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Mode of\n Shipment", 'width' => '6%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $modeOfShipment],
            ['title' => "Loading Port & City", 'width' => '15%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $ArrFnlList],
            ['title' => "Loading Country", 'width' => '10%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Destination Port & City", 'width' => '15%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $ArrFnlList],
            ['title' => "Destination Country", 'width' => '10%', 'align'=> 'left', 'readOnly' => true]
        ];

        $output['column'] = $column;
        $output['data'] = $result;
        return $output;
    }

    public function portList()
    {     
        
        $masterAdmins = $this->master_loginid();
        $subscriber_ids = array_column($masterAdmins, 'subscriber_id');

        // $VarSqlPort = "SELECT pn.id,pn.portname,pn.dateupdated,pn.status,pn.portcity,countryname FROM " . KN_MASTER_PORT . " AS pn INNER JOIN " . KN_COUNTRIES . " AS c ON pn.portcountry = c.id";
        // $ObjResult = $this->db->query($VarSqlPort);
        // return $ObjResult->result();


    $VarSqlPort = "SELECT pn.id,pn.portname,pn.dateupdated,pn.status,pn.portcity,c.countryname
            FROM " . KN_MASTER_PORT . " AS pn INNER JOIN " . KN_COUNTRIES . " AS c  ON pn.portcountry = c.id
            LEFT JOIN " . KN_USERS . " AS u  ON pn.updatedby = u.id WHERE u.subscriber_id IN (" . implode(',', array_map('intval', $subscriber_ids)) . ")
            AND pn.companyid = " . (int)$this->companyid . " AND pn.status = 1";
        
    $ObjResult = $this->db->query($VarSqlPort);
    return $ObjResult->result();
    }

    public function updatePoWiseDeliveryDetailss($req_data, $id) {
        foreach ($req_data as $key => $value)
        {
            $comboColor["po_size_wise_id"] = $value[1];
            $comboColor["enquiry_id"] = $id;
            $comboColor["pono_enq_refno"] = $value[2];
            $comboColor["po_enquiry_date"] = $value[3];
            $comboColor["po_shipment_date"] = $value[4];
            $comboColor["po_shipment_mode"] = $value[8];
            $comboColor["loading_port_city"] = $value[9];
            $comboColor["loading_country"] = $value[10];
            $comboColor["destination_port_city"] = $value[11];
            $comboColor["destination_country"] = $value[12];
            
            if($value[0] == "edit") {
                $this->db->where('po_size_wise_id', $comboColor["po_size_wise_id"]);
                $this->db->update('tbl_oe_po_wise', $comboColor);
            }
            else {
                unset($comboColor["po_size_wise_id"]);
                $this->db->insert('tbl_oe_po_wise', $comboColor);
                $primaryId = $this->db->insert_id();
            }
        }
        $result["status"] = "success";
        $result["statusCode"] = "200";
        return $result;
    }

    // ********** COMPLETE GARMENT PROCESS FLOW STARTS HERE *********** /

    public function getOrderEntryCompleteProcesss($id) {
         $masterAdmins = $this->master_loginid();
         $subscriber_ids = array_column($masterAdmins, 'subscriber_id');

        // $sql = "SELECT *, b.combo as po_combo FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " AND a.flag=1";
        $sql = "SELECT * FROM tbl_oe_cmplt_garment_process as a WHERE a.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = ['edit', $value['process_flow_id'], $value['flow_po_enq_ref'], $value['flow_combo'], $value['flow_component'], 
            $value['flow_color'], $value['flow_size_spec'], $value['process_flow_desc'], $value['remarks']];
        }

        // ******* GET THE DATA DETAILS START ********* //

        //$processFlow = $this->db->query('SELECT id, processflowname as name FROM '. KN_MASTER_PROCESS_FLOW)->result();
       $processFlow = $this->db->query("SELECT b.id, b.processflowname AS name FROM " . KN_MASTER_PROCESS_FLOW . " AS b LEFT JOIN " . KN_USERS . " AS u  ON b.updatedby = u.id
             WHERE u.subscriber_id IN (" . implode(',', array_map('intval', $subscriber_ids)) . ") AND b.status = 1")->result();
       
        
        $poEnqRefNo = $this->db->query('SELECT pow.pono_enq_refno as id, pow.pono_enq_refno as name, pow.po_size_wise_id as valueId FROM tbl_oe_po_wise as pow INNER JOIN tbl_oe_combo_color as cc ON pow.combo_color_id = cc.combo_color_id WHERE pow.enquiry_id="'.$id.'" GROUP BY pow.pono_enq_refno ORDER BY pow.po_size_wise_id')->result();
        $poCombo = $this->db->query('SELECT cc.combo as id, pow.pono_enq_refno as po_enq_id, cc.combo as name, cc.combo_color_id as valueId 
                                     FROM tbl_oe_po_wise as pow INNER JOIN tbl_oe_combo_color as cc ON pow.combo_color_id = cc.combo_color_id
                                     WHERE pow.enquiry_id="'.$id.'" ')->result();
        
        $poComponent = $this->db->query('SELECT * FROM tbl_oe_combo_color WHERE enquiry_id="'.$id.'" ')->result_array();


          
        $poEnqRefNo[] = (object)[
    'id' => 'All',  // same id as first object
    'name' => 'All',
    'valueId' => 'All'
];
        // ***** component Source ****** 
        $componentSplit = [];
        $componentSource = [];
        for($i=0; $i < sizeof($poComponent); $i++) 
        {
            $component = $poComponent[$i]['component'];
            $componentSplit = explode("/", $component);
            foreach ($componentSplit as $key => $value) 
            {
                $newArray["id"]         = $value;
                $newArray["combo_id"]   = $poComponent[$i]['combo'];
                $newArray["name"]       = $value;
                $newArray["valueId"]    = $poComponent[$i]['combo'];
                $newArray["indexKey"]   = $key;
                array_push($componentSource, $newArray);
            }
        }

        // ***** color Source ****** 
        $colorSplit = [];
        $colorSource = [];
        $componentColorSplit = [];
        for($i=0; $i < sizeof($poComponent); $i++) 
        {
            // *** color ****
            $color = $poComponent[$i]['colour'];
            $colorSplit = explode("/", $color);
            // *** component ***
            $component = $poComponent[$i]['component'];
            $componentColorSplit = explode("/", $component);

            // make new array
            foreach ($colorSplit as $key => $value) 
            {
                $newColorArray["id"]             = $value;
                $newColorArray["component_id"]   = $componentColorSplit[$key];
                $newColorArray["name"]           = $value;
                $newColorArray["combo_id"]       = $poComponent[$i]['combo'];
                $newColorArray["indexKey"]       = $i;
                array_push($colorSource, $newColorArray);
            }
        }

        $getSpecCode = $this->getOrderEntryComponentItemizedd($id);
        $dataArray = $getSpecCode['data'];
        $specSource = [];
        foreach ($dataArray as $key => $value1) 
        {
            
            $newSpecArray["id"]             = $value1[7];
            $newSpecArray["name"]           = $value1[7];
            $newSpecArray["po_enq_id"]      = $value1[2];
            $newSpecArray["combo_id"]       = $value1[3];
            $newSpecArray["component_id"]   = $value1[4];
            $newSpecArray["colour_id"]      = $value1[5];
            // $newSpecArray["component_id"]   = rtrim($value1[4]," ");
            // $newSpecArray["colour_id"]      = rtrim($value1[5]," ");
            $newSpecArray["indexKey"]       = $key;
            array_push($specSource, $newSpecArray);
        }
        
        // print_r($specSource);
        // exit();
        $output['data'] = $result;
        $output['poEnqRefNo'] = $poEnqRefNo;
        $output['poCombo'] = $poCombo;
        $output['poComponent'] = $componentSource;
        $output['poColor'] = $colorSource;
        $output['specCode'] = $specSource;
        $output['processFlow'] = $processFlow;
        return $output;
    }

   

   public function updatePoCompleteProcesss($req_data, $id) {
    $incoming_ids = [];

    // Step 1: Collect existing process_flow_id from incoming data
    foreach ($req_data as $key => $value) {
        if ($value[0] == "edit") {
            $incoming_ids[] = $value[1]; // Collect existing IDs
        }
    }

    // Step 2: Delete rows not present in incoming data
    if (!empty($incoming_ids)) {
        $this->db->where('enquiry_id', $id);
        $this->db->where_not_in('process_flow_id', $incoming_ids);
        $this->db->delete('tbl_oe_cmplt_garment_process');
    } else {
        // No existing IDs in request — delete all related to enquiry_id
        $this->db->delete('tbl_oe_cmplt_garment_process', ['enquiry_id' => $id]);
    }

    // Step 3: Insert or Update logic
    foreach ($req_data as $key => $value) {
        $length = sizeof($value);

        $comboColor["process_flow_id"] = $value[1];
        $comboColor["enquiry_id"] = $id;
        $comboColor["flow_po_enq_ref"] = $value[2];
        $comboColor["flow_combo"] = $value[3];
        $comboColor["flow_component"] = $value[4];
        $comboColor["flow_color"] = $value[5];
        $comboColor["flow_size_spec"] = $value[6];
        $comboColor["process_flow_desc"] = $value[7];
        $comboColor["remarks"] = $value[8];

        if ($value[0] == "edit") {
            $this->db->where('process_flow_id', $comboColor["process_flow_id"]);
            $this->db->update('tbl_oe_cmplt_garment_process', $comboColor);
        } else {
            unset($comboColor["process_flow_id"]);
            $this->db->insert('tbl_oe_cmplt_garment_process', $comboColor);
        }
    }

    return [
        "status" => "success",
        "statusCode" => "200"
    ];
}

    // ********** COMPLETE GARMENT PROCESS FLOW ENDS HERE *********** /

    // ********** CAD REQUIREMENT DETAILS STARTS HERE *********** /

    public function getCADRequirementt($id) {
        $sql = "SELECT * FROM tbl_cad_requirement as a WHERE a.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();
        $result = [];

        foreach ($data as $key => $value)
        {
            // if($value['job_status'] != 7) {
            //     $value['job_status_upd_dt'] = "";
            // }
            if($value['req_sent_status'] == 0 || $value['req_sent_status'] == "0") {
                $sentStatus = 'Pending';
            } else {
                $sentStatus = 'Sent';
            }

            $result[$key] = ['edit', $value['cad_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['cad_requirement'], $value['req_size'], $value['plan_date'], 
                            $value['job_status_upd_dt'], $sentStatus ];
        }

        // ******* GET THE DATA DETAILS START ********* //

        $processFlow = $this->db->query('SELECT id, processflowname as name FROM '. KN_MASTER_PROCESS_FLOW)->result();
        $poEnqRefNo = $this->db->query('SELECT pow.pono_enq_refno as id, pow.pono_enq_refno as name, pow.po_size_wise_id as valueId FROM tbl_oe_po_wise as pow INNER JOIN tbl_oe_combo_color as cc ON pow.combo_color_id = cc.combo_color_id WHERE pow.enquiry_id="'.$id.'" GROUP BY pow.pono_enq_refno ORDER BY pow.po_size_wise_id')->result();
        $poCombo = $this->db->query('SELECT cc.combo as id, pow.pono_enq_refno as po_enq_id, cc.combo as name, cc.combo_color_id as valueId 
                                     FROM tbl_oe_po_wise as pow INNER JOIN tbl_oe_combo_color as cc ON pow.combo_color_id = cc.combo_color_id
                                     WHERE pow.enquiry_id="'.$id.'" ')->result();

        
        $poComponent = $this->db->query('SELECT * FROM tbl_oe_combo_color WHERE enquiry_id="'.$id.'" ')->result_array();
           $poEnqRefNo[] = (object)[
    'id' => 'All',  // same id as first object
    'name' => 'All',
    'valueId' => 'All'
];
        // ***** component Source ****** 
        $componentSplit = [];
        $componentSource = [];
        for($i=0; $i < sizeof($poComponent); $i++) 
        {
            $component = $poComponent[$i]['component'];
            $componentSplit = explode("/", $component);
            foreach ($componentSplit as $key => $value) 
            {
                $newArray["id"]         = $value;
                $newArray["combo_id"]   = $poComponent[$i]['combo'];
                $newArray["name"]       = $value;
                $newArray["valueId"]    = $poComponent[$i]['combo'];
                $newArray["indexKey"]   = $key;
                array_push($componentSource, $newArray);
            }
        }

        $getSpecCode = $this->getOrderEntryComponentItemizedd($id);
        $dataArray = $getSpecCode['data'];
        $specSource = [];
        foreach ($dataArray as $key => $value1) 
        {
            $newSpecArray["id"]             = $value1[7];
            $newSpecArray["name"]           = $value1[7];
            $newSpecArray["po_enq_id"]      = $value1[2];
            $newSpecArray["combo_id"]       = $value1[3];
            $newSpecArray["component_id"]   = $value1[4];
            $newSpecArray["colour_id"]      = $value1[5];
            $newSpecArray["indexKey"]       = $key;
            array_push($specSource, $newSpecArray);
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $output['data'] = $result;
        $output['poEnqRefNo'] = $poEnqRefNo;
        $output['poCombo'] = $poCombo;
        $output['poComponent'] = $componentSource;
        $output['specCode'] = $specSource;
        $output['processFlow'] = $processFlow;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }

   
    public function updateCADRequirementt($req_data, $id) {
    $incoming_ids = [];

    // Step 1: Collect existing cad_requirement_id from incoming data
    foreach ($req_data as $key => $value) {
        if ($value[0] == "edit") {
            $incoming_ids[] = $value[1]; // Collect existing IDs
        }
    }

    // Step 2: Delete rows not present in incoming data
    if (!empty($incoming_ids)) {
        $this->db->where('enquiry_id', $id);
        $this->db->where_not_in('cad_requirement_id', $incoming_ids);
        $this->db->delete('tbl_cad_requirement');
    } else {
        // No existing IDs in request — delete all related to enquiry_id
        $this->db->delete('tbl_cad_requirement', ['enquiry_id' => $id]);
    }

    // Step 3: Insert or Update logic
    foreach ($req_data as $key => $value) {
        $comboColor["cad_requirement_id"] = $value[1];
        $comboColor["enquiry_id"] = $id;
        $comboColor["po_enq_ref_id"] = $value[2];
        $comboColor["combo_id"] = $value[3];
        $comboColor["component_id"] = $value[4];
        $comboColor["spec_code_id"] = $value[5];
        $comboColor["cad_requirement"] = $value[6];
        $comboColor["req_size"] = $value[7];
        $comboColor["plan_date"] = $value[8];
        $comboColor["actual_date"] = $value[9];

        if ($value[0] == "edit") {
            $this->db->where('cad_requirement_id', $comboColor["cad_requirement_id"]);
            $this->db->update('tbl_cad_requirement', $comboColor);
        } else {
            unset($comboColor["cad_requirement_id"]);
            $this->db->insert('tbl_cad_requirement', $comboColor);
        }
    }

    return [
        "status" => "success",
        "statusCode" => "200"
    ];
}

   

    // ********** CAD REQUIREMENT DETAILS ENDS HERE *********** /

    // ********** SAMPLE DETAILS STARTS HERE *********** /

    public function getSampleDetailss($id) {
        // $sql = "SELECT *, b.combo as po_combo FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " AND a.flag=1";
        $sql = "SELECT * FROM tbl_sample_requirement as a WHERE a.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            // if($value['job_status'] != 7) {
            //     $value['job_sta_upd_dt'] = "";
            // }

            $result[$key] = ['edit', $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['color_id'], $value['spec_code_id'], $value['sample_requirement'], $value['req_size'], 
                            $value['req_qty'], $value['buyer_appl_day'], $value['plan_date'], 
                            $value['job_sta_upd_dt'], $value['req_sent_status']];
        }

        // ******* GET THE DATA DETAILS START ********* //

        $processFlow = $this->db->query('SELECT id, processflowname as name FROM '. KN_MASTER_PROCESS_FLOW)->result();
        $poEnqRefNo = $this->db->query('SELECT pow.pono_enq_refno as id, pow.pono_enq_refno as name, pow.po_size_wise_id as valueId FROM tbl_oe_po_wise as pow INNER JOIN tbl_oe_combo_color as cc ON pow.combo_color_id = cc.combo_color_id WHERE pow.enquiry_id="'.$id.'" GROUP BY pow.pono_enq_refno ORDER BY pow.po_size_wise_id')->result();
        $poCombo = $this->db->query('SELECT cc.combo as id, pow.pono_enq_refno as po_enq_id, cc.combo as name, cc.combo_color_id as valueId 
                                     FROM tbl_oe_po_wise as pow INNER JOIN tbl_oe_combo_color as cc ON pow.combo_color_id = cc.combo_color_id
                                     WHERE pow.enquiry_id="'.$id.'" ')->result();

        
        $poComponent = $this->db->query('SELECT * FROM tbl_oe_combo_color WHERE enquiry_id="'.$id.'" ')->result_array();
   

         $poEnqRefNo[] = (object)[
    'id' => 'All',  // same id as first object
    'name' => 'All',
    'valueId' => 'All'
];
        // ***** component Source ****** 
        $componentSplit = [];
        $componentSource = [];
        for($i=0; $i < sizeof($poComponent); $i++) 
        {
            $component = $poComponent[$i]['component'];
            $componentSplit = explode("/", $component);
            foreach ($componentSplit as $key => $value) 
            {
                $newArray["id"]         = $value;
                $newArray["combo_id"]   = $poComponent[$i]['combo'];
                $newArray["name"]       = $value;
                $newArray["valueId"]    = $poComponent[$i]['combo'];
                $newArray["indexKey"]   = $key;
                array_push($componentSource, $newArray);
            }
        }

        // ***** color Source ****** 
        $colorSplit = [];
        $colorSource = [];
        $componentColorSplit = [];
        for($i=0; $i < sizeof($poComponent); $i++) 
        {
            // *** color ****
            $color = $poComponent[$i]['colour'];
            $colorSplit = explode("/", $color);
            // *** component ***
            $component = $poComponent[$i]['component'];
            $componentColorSplit = explode("/", $component);

            // make new array
            foreach ($colorSplit as $key => $value) 
            {
                $newColorArray["id"]             = $value;
                $newColorArray["component_id"]   = $componentColorSplit[$key];
                $newColorArray["name"]           = $value;
                $newColorArray["combo_id"]       = $poComponent[$i]['combo'];
                $newColorArray["indexKey"]       = $i;
                array_push($colorSource, $newColorArray);
            }
        }

        $getSpecCode = $this->getOrderEntryComponentItemizedd($id);
        $dataArray = $getSpecCode['data'];
        $specSource = [];
        foreach ($dataArray as $key => $value1) 
        {
            
            $newSpecArray["id"]             = $value1[7];
            $newSpecArray["name"]           = $value1[7];
            $newSpecArray["po_enq_id"]      = $value1[2];
            $newSpecArray["combo_id"]       = $value1[3];
            $newSpecArray["component_id"]   = $value1[4];
            $newSpecArray["colour_id"]      = $value1[5];
            // $newSpecArray["component_id"]   = rtrim($value1[4]," ");
            // $newSpecArray["colour_id"]      = rtrim($value1[5]," ");
            $newSpecArray["indexKey"]       = $key;
            array_push($specSource, $newSpecArray);
        }
        
        // print_r($specSource);
        // exit();

        // *** REQUIRED SIZE FROM GARMENT SIZE ***
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);

        $output['data'] = $result;
        $output['poEnqRefNo'] = $poEnqRefNo;
        $output['poCombo'] = $poCombo;
        $output['poComponent'] = $componentSource;
        $output['poColor'] = $colorSource;
        $output['specCode'] = $specSource;
        $output['processFlow'] = $processFlow;
        $output['sizeData'] = $sizeMaster;
        return $output;
        
    }

    public function getSampleDetailssa($id) {
        // $sql = "SELECT *, b.combo as po_combo FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " AND a.flag=1";
        $sql = "SELECT * FROM tbl_sample_requirement as a WHERE a.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            // if($value['job_status'] != 7) {
            //     $value['job_sta_upd_dt'] = "";
            // }

            $result[$key] = ['edit', $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['color_id'], $value['spec_code_id'], $value['sample_requirement'], $value['req_size'], 
                            $value['req_qty'], $value['buyer_appl_day'], $value['plan_date'], 
                            $value['job_sta_upd_dt'], $value['req_sent_status']];
        }

        // ******* GET THE DATA DETAILS START ********* //

        $processFlow = $this->db->query('SELECT id, processflowname as name FROM '. KN_MASTER_PROCESS_FLOW)->result();
        $poEnqRefNo = $this->db->query('SELECT pow.pono_enq_refno as id, pow.pono_enq_refno as name, pow.po_size_wise_id as valueId FROM tbl_oe_po_wise as pow INNER JOIN tbl_oe_combo_color as cc ON pow.combo_color_id = cc.combo_color_id WHERE pow.enquiry_id="'.$id.'" GROUP BY pow.pono_enq_refno ORDER BY pow.po_size_wise_id')->result();
        $poCombo = $this->db->query('SELECT cc.combo as id, pow.pono_enq_refno as po_enq_id, cc.combo as name, cc.combo_color_id as valueId 
                                     FROM tbl_oe_po_wise as pow INNER JOIN tbl_oe_combo_color as cc ON pow.combo_color_id = cc.combo_color_id
                                     WHERE pow.enquiry_id="'.$id.'" ')->result();

     $poEnqRefNo[] = (object)[
    'id' => 'All',  // same id as first object
    'name' => 'All',
    'valueId' => 'All'
];

        
        $poComponent = $this->db->query('SELECT * FROM tbl_oe_combo_color WHERE enquiry_id="'.$id.'" ')->result_array();

        // ***** component Source ****** 
        $componentSplit = [];
        $componentSource = [];
        for($i=0; $i < sizeof($poComponent); $i++) 
        {
            $component = $poComponent[$i]['component'];
            $componentSplit = explode("/", $component);
            foreach ($componentSplit as $key => $value) 
            {
                $newArray["id"]         = $value;
                $newArray["combo_id"]   = $poComponent[$i]['combo'];
                $newArray["name"]       = $value;
                $newArray["valueId"]    = $poComponent[$i]['combo'];
                $newArray["indexKey"]   = $key;
                array_push($componentSource, $newArray);
            }
        }

        // ***** color Source ****** 
        $colorSplit = [];
        $colorSource = [];
        $componentColorSplit = [];
        for($i=0; $i < sizeof($poComponent); $i++) 
        {
            // *** color ****
            $color = $poComponent[$i]['colour'];
            $colorSplit = explode("/", $color);
            // *** component ***
            $component = $poComponent[$i]['component'];
            $componentColorSplit = explode("/", $component);

            // make new array
            foreach ($colorSplit as $key => $value) 
            {
                $newColorArray["id"]             = $value;
                $newColorArray["component_id"]   = $componentColorSplit[$key];
                $newColorArray["name"]           = $value;
                $newColorArray["combo_id"]       = $poComponent[$i]['combo'];
                $newColorArray["indexKey"]       = $i;
                array_push($colorSource, $newColorArray);
            }
        }

        $getSpecCode = $this->getOrderEntryComponentItemizedd($id);
        $dataArray = $getSpecCode['data'];
        $specSource = [];
        foreach ($dataArray as $key => $value1) 
        {
            
            $newSpecArray["id"]             = $value1[7];
            $newSpecArray["name"]           = $value1[7];
            $newSpecArray["po_enq_id"]      = $value1[2];
            $newSpecArray["combo_id"]       = $value1[3];
            $newSpecArray["component_id"]   = $value1[4];
            $newSpecArray["colour_id"]      = $value1[5];
            // $newSpecArray["component_id"]   = rtrim($value1[4]," ");
            // $newSpecArray["colour_id"]      = rtrim($value1[5]," ");
            $newSpecArray["indexKey"]       = $key;
            array_push($specSource, $newSpecArray);
        }
        
        // print_r($specSource);
        // exit();

        // *** REQUIRED SIZE FROM GARMENT SIZE ***
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
            $firstObject = $poEnqRefNo[0]; // ← semicolon added here


        $output['data'] = $result;
        $output['poEnqRefNo'] = $poEnqRefNo;
        $output['poCombo'] = $poCombo;
        $output['poComponent'] = $componentSource;
        $output['poColor'] = $colorSource;
        $output['specCode'] = $specSource;
        $output['processFlow'] = $processFlow;
        $output['sizeData'] = $sizeMaster;
        return $output;
        
    }


    

   public function updateSampleDetailss($req_data, $id) {
    $incoming_ids = [];

    // Step 1: Collect existing sample_requirement_id from incoming data
    foreach ($req_data as $key => $value) {
        if ($value[0] == "edit") {
            $incoming_ids[] = $value[1]; // Collect existing IDs
        }
    }

    // Step 2: Delete rows not present in incoming data
    if (!empty($incoming_ids)) {
        $this->db->where('enquiry_id', $id);
        $this->db->where_not_in('sample_requirement_id', $incoming_ids);
        $this->db->delete('tbl_sample_requirement');
    } else {
        // No existing IDs in request — delete all related to enquiry_id
        $this->db->delete('tbl_sample_requirement', ['enquiry_id' => $id]);
    }

    // Step 3: Insert or Update logic
    foreach ($req_data as $key => $value) {
        $comboColor["sample_requirement_id"] = $value[1];
        $comboColor["enquiry_id"] = $id;
        $comboColor["po_enq_ref_id"] = $value[2];
        //$comboColor["po_enq_ref_id"] = ($value[2] == '0' || $value[2] === 0) ? "All" : $value[2];
        $comboColor["combo_id"] = $value[3];
        $comboColor["component_id"] = $value[4];
        $comboColor["color_id"] = $value[5];
        $comboColor["spec_code_id"] = $value[6];
        $comboColor["sample_requirement"] = $value[7];
        $comboColor["req_size"] = $value[8];
        $comboColor["req_qty"] = $value[9];
        $comboColor["buyer_appl_day"] = $value[10];
        $comboColor["plan_date"] = $value[11];
        $comboColor["actual_date"] = $value[12];

        if ($value[0] == "edit") {
            $this->db->where('sample_requirement_id', $comboColor["sample_requirement_id"]);
            $this->db->update('tbl_sample_requirement', $comboColor);
        } else {
            unset($comboColor["sample_requirement_id"]);
            $this->db->insert('tbl_sample_requirement', $comboColor);
        }
    }

    return [
        "status" => "success",
        "statusCode" => "200"
    ];
}

    // ********** SAMPLE DETAILS ENDS HERE *********** /

    // ********** EMBELLISHMENT DETAILS STARTS HERE *********** /

    public function getEmbellishmentDetailss($id) {
        $sql = "SELECT * FROM tbl_embl_details as a WHERE a.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();
        $result = [];
       // Note:$value['type'], newly added here
        foreach ($data as $key => $value)
        {
            $result[$key] = ['edit', $value['tbl_embl_detail_id'], $value['po_enq'], $value['combo'], 
                            $value['component'], $value['colour'], $value['artwork_name'], $value['type'],
                            $value['type_medium'], $value['grading_details'], $value['size_group'], $value['aproval_status'], 
                            $value['approved_by'], $value['approved_date'], $value['app_samp_ref_no']];
        }

        // ******* GET THE DATA DETAILS START ********* //

        $processFlow = $this->db->query('SELECT id, processflowname as name FROM '. KN_MASTER_PROCESS_FLOW)->result();
        $poEnqRefNo = $this->db->query('SELECT pow.pono_enq_refno as id, pow.pono_enq_refno as name, pow.po_size_wise_id as valueId FROM tbl_oe_po_wise as pow INNER JOIN tbl_oe_combo_color as cc ON pow.combo_color_id = cc.combo_color_id WHERE pow.enquiry_id="'.$id.'" GROUP BY pow.pono_enq_refno ORDER BY pow.po_size_wise_id')->result();
        $poCombo = $this->db->query('SELECT cc.combo as id, pow.pono_enq_refno as po_enq_id, cc.combo as name, cc.combo_color_id as valueId 
                                     FROM tbl_oe_po_wise as pow INNER JOIN tbl_oe_combo_color as cc ON pow.combo_color_id = cc.combo_color_id
                                     WHERE pow.enquiry_id="'.$id.'" ')->result();

        
        $poComponent = $this->db->query('SELECT * FROM tbl_oe_combo_color WHERE enquiry_id="'.$id.'" ')->result_array();
            $poEnqRefNo[] = (object)[
    'id' => 'All',  // same id as first object
    'name' => 'All',
    'valueId' => 'All'
];  
        // ***** component Source ****** 
        $componentSplit = [];
        $componentSource = [];
        for($i=0; $i < sizeof($poComponent); $i++) 
        {
            $component = $poComponent[$i]['component'];
            $componentSplit = explode("/", $component);
            foreach ($componentSplit as $key => $value) 
            {
                $newArray["id"]         = $value;
                $newArray["combo_id"]   = $poComponent[$i]['combo'];
                $newArray["name"]       = $value;
                $newArray["valueId"]    = $poComponent[$i]['combo'];
                $newArray["indexKey"]   = $key;
                array_push($componentSource, $newArray);
            }
        }

        // ***** color Source ****** 
        $colorSplit = [];
        $colorSource = [];
        $componentColorSplit = [];
        for($i=0; $i < sizeof($poComponent); $i++) 
        {
            // *** color ****
            $color = $poComponent[$i]['colour'];
            $colorSplit = explode("/", $color);
            // *** component ***
            $component = $poComponent[$i]['component'];
            $componentColorSplit = explode("/", $component);

            // make new array
            foreach ($colorSplit as $key => $value) 
            {
                $newColorArray["id"]             = $value;
                $newColorArray["component_id"]   = $componentColorSplit[$key];
                $newColorArray["name"]           = $value;
                $newColorArray["combo_id"]       = $poComponent[$i]['combo'];
                $newColorArray["indexKey"]       = $i;
                array_push($colorSource, $newColorArray);
            }
        }

        $getSpecCode = $this->getOrderEntryComponentItemizedd($id);
        $dataArray = $getSpecCode['data'];
        $specSource = [];
        foreach ($dataArray as $key => $value1) 
        {
            
            $newSpecArray["id"]             = $value1[7];
            $newSpecArray["name"]           = $value1[7];
            $newSpecArray["po_enq_id"]      = $value1[2];
            $newSpecArray["combo_id"]       = $value1[3];
            $newSpecArray["component_id"]   = $value1[4];
            $newSpecArray["colour_id"]      = $value1[5];
            // $newSpecArray["component_id"]   = rtrim($value1[4]," ");
            // $newSpecArray["colour_id"]      = rtrim($value1[5]," ");
            $newSpecArray["indexKey"]       = $key;
            array_push($specSource, $newSpecArray);
        }
        
        // print_r($specSource);
        // exit();

        // *** GET TYPE / MEDIUM DETAILS FROM MASTER PAGE //
        // commented by myself regards new form integration $masterTypeMedium = $this->db->query('SELECT id as id, type_medium as name FROM kn_master_type_medium')->result();

        $masterTypeMedium = $this->db->query('SELECT id as id, medium_material as name FROM '.KN_MASTER_MEDIUM_MATERIAL.' WHERE status=1 and companyid='.$this->companyid.'')->result();
        
        // Note:newly added here
        $masterType = $this->db->query('SELECT id as id, embellname as name FROM '.KN_MASTER_EMBELLISHMENT_TYPE.' WHERE status=1 and companyid='.$this->companyid.'')->result();
        
        
        $output['data'] = $result;
        $output['poEnqRefNo'] = $poEnqRefNo;
        $output['poCombo'] = $poCombo;
        $output['poComponent'] = $componentSource;
        $output['poColor'] = $colorSource;
        $output['specCode'] = $specSource;
        $output['processFlow'] = $processFlow;
        $output['type_medium'] = $masterTypeMedium;
        $output['type'] = $masterType;
        return $output;
        
    }

    
    public function updateEmbellishmentDetailss($req_data, $id) {
    $incoming_ids = [];

    // Step 1: Collect existing tbl_embl_detail_id from incoming data
    foreach ($req_data as $key => $value) {
        if ($value[0] == "edit") {
            $incoming_ids[] = $value[1]; // Collect existing IDs
        }
    }

    // Step 2: Delete rows not present in incoming data
    if (!empty($incoming_ids)) {
        $this->db->where('enquiry_id', $id);
        $this->db->where_not_in('tbl_embl_detail_id', $incoming_ids);
        $this->db->delete('tbl_embl_details');
    } else {
        // No existing IDs in request — delete all related to enquiry_id
        $this->db->delete('tbl_embl_details', ['enquiry_id' => $id]);
    }

    // Step 3: Insert or Update logic
    foreach ($req_data as $key => $value) {
        $comboColor["tbl_embl_detail_id"] = $value[1];
        $comboColor["enquiry_id"] = $id;
        $comboColor["po_enq"] = $value[2];
        $comboColor["combo"] = $value[3];
        $comboColor["component"] = $value[4];
        $comboColor["colour"] = $value[5];
        $comboColor["artwork_name"] = $value[6];
        $comboColor["type"] = $value[7];
        $comboColor["type_medium"] = $value[8];
        $comboColor["grading_details"] = $value[9];
        $comboColor["size_group"] = $value[10];
        $comboColor["aproval_status"] = $value[11];
        $comboColor["approved_by"] = $value[12];
        $comboColor["approved_date"] = $value[13];
        $comboColor["app_samp_ref_no"] = $value[14];

        if ($value[0] == "edit") {
            $this->db->where('tbl_embl_detail_id', $comboColor["tbl_embl_detail_id"]);
            $this->db->update('tbl_embl_details', $comboColor);
        } else {
            unset($comboColor["tbl_embl_detail_id"]);
            $this->db->insert('tbl_embl_details', $comboColor);
        }
    }

    return [
        "status" => "success",
        "statusCode" => "200"
    ];
}

    
    

    // ********** EMBELLISHMENT DETAILS ENDS HERE *********** /

    
    // ********** EMBELLISHMENT APPROVAL DETAILS STARTS HERE *********** /

    public function getEmbellishmentStatusDetailss($id) {
        $sql = "SELECT * FROM tbl_embl_details as a WHERE a.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = ['edit', $value['tbl_embl_detail_id'], $value['po_enq'], $value['combo'], 
                            $value['component'], $value['colour'], $value['spec_code'], $value['artwork_name'], 
                            $value['aproval_status'], $value['approved_by'], $value['approved_date'], $value['app_samp_ref_no']];
        }
        
        $output['data'] = $result;
        return $output;
        
    }

    public function updateEmbellishmentStatusDetailss($req_data, $id) {
        foreach ($req_data as $key => $value)
        {
            $comboColor["tbl_embl_detail_id"] = $value[1];
            $comboColor["enquiry_id"] = $id;
            $comboColor["po_enq"] = $value[2];
            $comboColor["combo"] = $value[3];
            $comboColor["component"] = $value[4];
            $comboColor["colour"] = $value[5];
            $comboColor["spec_code"] = $value[6];
            $comboColor["artwork_name"] = $value[7];
            $comboColor["aproval_status"] = $value[8];
            $comboColor["approved_by"] = $value[9];
            $comboColor["approved_date"] = $value[10];
            $comboColor["app_samp_ref_no"] = $value[11];
            
            if($value[0] == "edit") {
                $this->db->where('tbl_embl_detail_id', $comboColor["tbl_embl_detail_id"]);
                $this->db->update('tbl_embl_details', $comboColor);
            }
            else {
                unset($comboColor["tbl_embl_detail_id"]);
                $this->db->insert('tbl_embl_details', $comboColor);
                $primaryId = $this->db->insert_id();
            }
        }
    }

    // ********** EMBELLISHMENT APPROVAL DETAILS ENDS HERE *********** /
    
    // ********** EMBELLISHMENT VENDOR DETAILS STARTS HERE *********** /

      public function getEmbellishmentVendorDetailss($id) {
        $sql = "SELECT * FROM tbl_embl_details as a WHERE 
                a.enquiry_id = ".$id." AND a.aproval_status !='PENDING' and a.aproval_status !=''  ";
        $data = $this->db->query($sql)->result_array();

        // return $data;
        
        $userNames = array();
        foreach($data as $key => $value) {
            $check = [$value['combo'], $value["component"], $value['colour'], $value['artwork_name'], $value['app_samp_ref_no']];
            if(!empty($userNames) && in_array($check, $userNames)) {

                // $old_key = array_search($check, $userNames);

                // $data[$old_key]['combo'] = $data[$old_key]['combo'].' / '.$value['combo'];
                // $data[$old_key]['combo'] = $data[$old_key]['combo'];

                // $data[$old_key]['po_qty'] = (int)$data[$old_key]['po_qty'] + (int)$value['po_qty'];
                // $data[$old_key]['po_qty'] = $data[$old_key]['po_qty'];

                unset($data[$key]);
            }
            $userNames[] = $check;
        }

        $data = array_values($data);

        // return $data;    

        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = ['edit', $value['tbl_embl_detail_id'], $value['po_enq'], $value['combo'], 
                            $value['component'], $value['colour'], $value['artwork_name'], $value['app_samp_ref_no'], 
                            $value['vendor_name'], $value['contacts'], $value['quotation_ref_no_date'], $value['quotation_approved_by'],
                            $value['job_scheduled_date'], $value['expected_job_comp_date']];
        }
        $masterAdmins = $this->master_loginid();
        $subscriber_ids = array_column($masterAdmins, 'subscriber_id');

        //commented below line no:1107 by myself regards new form of dyeing job work details integration
        //$embellishmentVendor = $this->db->query('SELECT id as id, vendorname as name, contactpersonname, address, emailid, phone, mobile FROM '. KN_MASTER_EMBELLISHMENT_VENDOR)->result();
        
        //$embellishmentVendor = $this->db->query("SELECT id as id, jobwrkname as name , contactperson as contactpersonname, address, emailid, phone, mobile FROM ". KN_MASTER_JOBWRK ." WHERE type=2 AND status=1")->result();
      $embellishmentVendor = $this->db
    ->select(' b.id AS id, b.jobwrkname AS name, b.contactperson AS contactpersonname,b.address, b.emailid,b.phone,b.mobile')
    ->from(KN_MASTER_JOBWRK . ' AS b')
    ->join(KN_USERS . ' AS u', 'b.updatedby = u.id', 'left')
    ->where('b.status', 1)
    ->where('b.type', 2)
    ->where_in('u.subscriber_id', $subscriber_ids)
    ->get()
    ->result_array();

      

        
        $output['data'] = $result;
        $output['embellishmentVendor'] = $embellishmentVendor;
        return $output;
        
    }

    public function updateEmbellishmentVendorDetailss($req_data, $id) {
        foreach ($req_data as $key => $value)
        {
            $comboColor["tbl_embl_detail_id"] = $value[1];
            $comboColor["enquiry_id"] = $id;
            $comboColor["po_enq"] = $value[2];
            $comboColor["combo"] = $value[3];
            $comboColor["component"] = $value[4];
            $comboColor["colour"] = $value[5];
            $comboColor["artwork_name"] = $value[6];
            $comboColor["app_samp_ref_no"] = $value[7];
            $comboColor["vendor_name"] = $value[8];
            $comboColor["contacts"] = $value[9];
            $comboColor["quotation_ref_no_date"] = $value[10];
            $comboColor["quotation_approved_by"] = $value[11];
            $comboColor["job_scheduled_date"] = $value[12];
            $comboColor["expected_job_comp_date"] = $value[13];
            
            if($value[0] == "edit") {
                $this->db->where('tbl_embl_detail_id', $comboColor["tbl_embl_detail_id"]);
                $this->db->update('tbl_embl_details', $comboColor);
            }
            else {
                unset($comboColor["tbl_embl_detail_id"]);
                $this->db->insert('tbl_embl_details', $comboColor);
                $primaryId = $this->db->insert_id();
            }
        }
    }

    // ********** EMBELLISHMENT VENDPR DETAILS ENDS HERE *********** /
    
    // ********** BOM 1 SAMPLING APPROVAL DETAILS STARTS HERE *********** /

    public function getBOMSamplingApprovalDetailss($id) {
        $sql = "SELECT * FROM tbl_bom_article_1 as a WHERE a.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = ['edit', $value['bom_article_id'], $value['item_description'], $value['blend'], $value['content'], $value['material'], $value['garment_size'], 
                            // $value['category'], $value['bom_appr_need'], 
                            $value['sample_submission_planned_date'], $value['sample_submission_actual_date'], $value['appr_status'], $value['appr_item_code'], $value['appr_item_colour_code'],
                            $value['size_dim'], $value['uom'], $value['approved_by'], $value['approved_date'], 
                            // $value['despatch_approval']
                        ];
        }
        
        //$materialData = $this->db->query('SELECT id as id, bomitemdesc as name, content, material, bomblend FROM '. KN_MASTER_BOM)->result();

        $UOM = unserialize(ARRUNITOFMEASURE);

        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }
        
        // *** REQUIRED GARMENT SIZE *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
         // *** BLEND . CONTENT / MATERIAL DATA *** //
         $itemData   = $this->getBlendContentMaterial('item',KN_BOM1_MASTER);
         $blendDetails   = $this->getBlendContentMaterial('blend',KN_BOM1_MASTER);
         $contentDetails   = $this->getBlendContentMaterial('content',KN_BOM1_MASTER);
         $materialDetails   = $this->getBlendContentMaterial('material',KN_BOM1_MASTER);

        $output['data'] = $result;
        $output['materialData'] = $itemData;
        $output['UOMDetails'] = $UOMDetails;
        $output['sizeData'] = $sizeMaster;
        $output['blendSource'] = $blendDetails;
        $output['contentSource'] = $contentDetails;
        $output['materialSource'] = $materialDetails;
        return $output;
        
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

    
    function master_loginid() {
    $logisubid = $this->subscriberid;

    return $this->db->select('proforma_id, subscriber_id')
                    ->from(KN_SUBSCRIBERLIST)
                    ->where('master_admin_statuss', 1)
                    ->or_where('subscriber_id', $logisubid)
                    ->get()
                    ->result();   // ✅ MULTIPLE rows
}

    public function getBlendContentMaterial($type,$tablename) {
      
     $masterAdmins = $this->master_loginid();

     //print_r($masterAdmins);
     $subscriber_ids = array_column($masterAdmins, 'subscriber_id');

    
      
        

        if($type == 'item') {
             //$data = $this->db->query('SELECT id as id, description as name FROM '.$tablename.' where type=1 and status=1')->result();
           $data = $this->db
            ->select('b.id AS id, b.description AS name') ->from($tablename . ' b')
            ->join(KN_USERS . ' u', 'b.updatedby = u.id', 'left')
            ->where('b.type', 1)->where('b.status', 1)
            ->where_in('u.subscriber_id', $subscriber_ids)   
            ->get() ->result();
        }
        if($type == 'blend') {
            //$data = $this->db->query('SELECT id as id, description as name FROM '.$tablename.' where type=2 and status=1')->result();
             $data = $this->db
            ->select('b.id AS id, b.description AS name') ->from($tablename . ' b')
            ->join(KN_USERS . ' u', 'b.updatedby = u.id', 'left')
            ->where('b.type', 2)->where('b.status', 1)
            ->where_in('u.subscriber_id', $subscriber_ids)   
            ->get() ->result();
        }
        if($type == 'content') {
            //$data = $this->db->query('SELECT id as id, description as name FROM '.$tablename.' where type=3 and status=1')->result();
            $data = $this->db
            ->select('b.id AS id, b.description AS name') ->from($tablename . ' b')
            ->join(KN_USERS . ' u', 'b.updatedby = u.id', 'left')
            ->where('b.type', 3)->where('b.status', 1)
            ->where_in('u.subscriber_id', $subscriber_ids)   
            ->get() ->result();
        }
        if($type == 'material') {
            //$data = $this->db->query('SELECT id as id, description as name FROM '.$tablename.' where type=4 and status=1')->result();
            $data = $this->db
            ->select('b.id AS id, b.description AS name') ->from($tablename . ' b')
            ->join(KN_USERS . ' u', 'b.updatedby = u.id', 'left')
            ->where('b.type', 4)->where('b.status', 1)
            ->where_in('u.subscriber_id', $subscriber_ids)   
            ->get() ->result();
        }
        return $data;
    }

    
    public function updateBOMSamplingApprovalDetailss($req_data, $id) {
    $incoming_ids = [];

    // Step 1: Collect existing bom_article_id from incoming data
    foreach ($req_data as $key => $value) {
        if ($value[0] == "edit") {
            $incoming_ids[] = $value[1]; // Collect existing IDs
        }
    }

    // Step 2: Delete rows not present in incoming data
    if (!empty($incoming_ids)) {
        $this->db->where('enquiry_id', $id);
        $this->db->where_not_in('bom_article_id', $incoming_ids);
        $this->db->delete('tbl_bom_article_1');
    } else {
        // No existing IDs in request — delete all related to enquiry_id
        $this->db->delete('tbl_bom_article_1', ['enquiry_id' => $id]);
    }

    // Step 3: Insert or Update logic
    foreach ($req_data as $key => $value) {
        $comboColor["bom_article_id"] = $value[1];
        $comboColor["enquiry_id"] = $id;
        $comboColor["item_description"] = $value[2];
        $comboColor["blend"] = $value[3];
        $comboColor["content"] = $value[4];
        $comboColor["material"] = $value[5];
        $comboColor["garment_size"] = $value[6];
        $comboColor["sample_submission_planned_date"] = $value[7];
        $comboColor["sample_submission_actual_date"] = $value[8];
        $comboColor["appr_status"] = $value[9];
        $comboColor["appr_item_code"] = $value[10];
        $comboColor["appr_item_colour_code"] = $value[11];
        $comboColor["size_dim"] = $value[12];
        $comboColor["uom"] = $value[13];
        $comboColor["approved_by"] = $value[14];
        $comboColor["approved_date"] = $value[15];

        if ($value[0] == "edit") {
            $this->db->where('bom_article_id', $comboColor["bom_article_id"]);
            $this->db->update('tbl_bom_article_1', $comboColor);
        } else {
            unset($comboColor["bom_article_id"]);
            $this->db->insert('tbl_bom_article_1', $comboColor);
        }
    }

    return [
        "status" => "success",
        "statusCode" => "200"
    ];
}
    
    // ********** BOM 1 SAMPLE DETAILS ENDS HERE *********** /

    // ********** BOM 1 REQUIREMENT DETAILS STARTS HERE *********** /

    public function getBOM1RequirementDetailss($id) {
        $sql = "SELECT * FROM tbl_bom_article_1_requirement as a WHERE a.enquiry_id='$id' ";
        $data = $this->db->query($sql)->result_array();
        
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = ['edit', $value['bom_1_req_id'], $value['po_enq_ref'], $value['combo'], $value['component'],
                            $value['color'], $value['spec_code'], $value['item_desc'], $value['blend'], $value['content'],
                            $value['material'], $value['garment_size'], $value['appr_item_code'], 
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], 
                            $value['itemized_qty'], $value['bom_intake'], $value['req_bom_qty'], $value['requirement_uom']];
        
                        }
        
       // $materialData = $this->db->query('SELECT id as id, description as name FROM '.KN_BOM1_MASTER.' where type=1')->result();
       //echo("1111");
        $UOM = unserialize(ARRUNITOFMEASURE);
        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }

        $getdata = $this->getSampleDetailss($id);
        $amountCalculation = $this->getOrderEntryComponentItemizedd($id);
        $amountCalculation = $amountCalculation["data"];
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
    
            $Amount = [];

            
       
           for ($i = 0; $i < count($amountCalculation); $i++) {
    $row = $amountCalculation[$i];
  

    $AmountValue = array();
    $AmountValue['po_ref']    = $row[2] ?? '';
    $AmountValue['combo']     = $row[3] ?? '';
    $AmountValue['component'] = $row[4] ?? '';
    $AmountValue['colour']    = $row[5] ?? '';
    $AmountValue['spec']      = $row[7] ?? '';
    $AmountValue['amount']    = isset($row[count($row) - 2]) ? $row[count($row) - 2] : '';

     $startIndex = 8;
if (!empty($sizeMaster)) {
    $j = 0;
    foreach ($sizeMaster as $size) {
        if ($size['id'] == 0) continue; // 🚫 Skip 'id' = 0
        $index = $startIndex + $j;
        $keyName = $size['name'];
        $AmountValue[$keyName] = isset($row[$index]) ? $row[$index] : '';
        $j++;
    }
}

    $Amount[] = $AmountValue;

 //print_r($Amount);
}

        

        // *** REQUIRED GARMENT SIZE *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);

        // *** ITEM DESCRIPTION *** //
        // $req_sql = "SELECT a.*, b.misc_name as blend_name, c.misc_name as content_name, 
        //             d.misc_name as material_name 
        //             FROM tbl_bom_article_1 as a 
        //             inner join kn_master_bom_misc as b on a.blend=b.id 
        //             inner join kn_master_bom_misc as c on a.content=c.id 
        //             inner join kn_master_bom_misc as d on a.material=d.id 
        //             WHERE a.enquiry_id='$id' AND a.flag=1 and a.appr_status=2";
        
        $req_sql = "SELECT a.*, b.description as blend_name, c.description as content_name, 
                    d.description as material_name 
                    FROM tbl_bom_article_1 as a 
                    inner join ".KN_BOM1_MASTER." as b on a.blend=b.id 
                    inner join ".KN_BOM1_MASTER." as c on a.content=c.id 
                    inner join ".KN_BOM1_MASTER." as d on a.material=d.id 
                    WHERE a.enquiry_id='$id'  and a.appr_status=2";
        $req_data = $this->db->query($req_sql)->result_array();

        // print_r($sizeMaster);
        // exit();
        $itemSource = [];
        $blendSource = [];
        $contentSource = [];
        $materialSource = [];
        $garmentSizeSource = [];
        $apprItemCodeSource = [];
        $apprItemColourCodeSource = [];
        $sizeDimSource = [];
        $uomSource = [];
        foreach ($req_data as $key => $value) 
        {
            $item_description = $value["item_description"];
            $garment_size = $value["garment_size"];
            $material_name = $value["material_name"];
            $content_name = $value["content_name"];
            $blend_name = $value["blend_name"];
            $appr_item_code = $value["appr_item_code"];
            $appr_item_colour_code = $value["appr_item_colour_code"];
            $size_dim = $value["size_dim"];
            $uom = $value["uom"];

            //$item_description_name = ""; // commented by mysellf regards new bom1 integration
            $item_description_name = $item_description; // newly added
            $garment_size_name = "";
          
           $materialData= $this->getBlendContentMaterial('item',KN_BOM1_MASTER);
          
            foreach ($materialData as $material) {
    if ($material->id == $item_description) {
        $item_description_name = $material->name;
        break;
    }
}
            
            for($j = 0; $j < sizeof($sizeMaster); $j++) {
                if($sizeMaster[$j]['id'] == $garment_size) {
                    $garment_size_name = $sizeMaster[$j]['name'];
                    break;
                }
            }

            $itemArray["id"]         = $item_description_name;
            $itemArray["name"]       = $item_description_name;
            $itemArray["idValue"]    = $value['bom_article_id'];
            $itemArray["indexKey"]   = $key;
            array_push($itemSource, $itemArray);

            $blendArray["id"]         = $blend_name;
            $blendArray["name"]       = $blend_name;
            $blendArray["item_name"]  = $item_description_name;
            $blendArray["idValue"]    = $value['bom_article_id'];
            $blendArray["indexKey"]   = $key;
            array_push($blendSource, $blendArray);

            $contentArray["id"]         = $content_name;
            $contentArray["name"]       = $content_name;
            $contentArray["blend_name"] = $blend_name;
            $contentArray["item_name"]  = $item_description_name;
            $contentArray["idValue"]    = $value['bom_article_id'];
            $contentArray["indexKey"]   = $key;
            array_push($contentSource, $contentArray);

            $materialArray["id"]           = $material_name;
            $materialArray["name"]         = $material_name;
            $materialArray["content_name"] = $content_name;
            $materialArray["blend_name"]   = $blend_name;
            $materialArray["item_name"]    = $item_description_name;
            $materialArray["idValue"]      = $value['bom_article_id'];
            $materialArray["indexKey"]     = $key;
            array_push($materialSource, $materialArray);

            $garmentSizeArray["id"]            = $garment_size_name;
            $garmentSizeArray["name"]          = $garment_size_name;
            $garmentSizeArray["material_name"] = $material_name;
            $garmentSizeArray["content_name"]  = $content_name;
            $garmentSizeArray["blend_name"]    = $blend_name;
            $garmentSizeArray["item_name"]     = $item_description_name;
            $garmentSizeArray["idValue"]       = $value['bom_article_id'];
            $garmentSizeArray["indexKey"]      = $key;
            array_push($garmentSizeSource, $garmentSizeArray);

            $apprItemCodeArray["id"]            = $appr_item_code;
            $apprItemCodeArray["name"]          = $appr_item_code;
            $apprItemCodeArray["garment_size_name"] = $garment_size_name;
            $apprItemCodeArray["material_name"] = $material_name;
            $apprItemCodeArray["content_name"]  = $content_name;
            $apprItemCodeArray["blend_name"]    = $blend_name;
            $apprItemCodeArray["item_name"]     = $item_description_name;
            $apprItemCodeArray["idValue"]       = $value['bom_article_id'];
            $apprItemCodeArray["indexKey"]      = $key;
            array_push($apprItemCodeSource, $apprItemCodeArray);

            $appr_item_colour_code_Array["id"]            = $appr_item_colour_code;
            $appr_item_colour_code_Array["name"]          = $appr_item_colour_code;
            $appr_item_colour_code_Array["appr_item_code"] = $appr_item_code;
            $appr_item_colour_code_Array["garment_size_name"] = $garment_size_name;
            $appr_item_colour_code_Array["material_name"] = $material_name;
            $appr_item_colour_code_Array["content_name"]  = $content_name;
            $appr_item_colour_code_Array["blend_name"]    = $blend_name;
            $appr_item_colour_code_Array["item_name"]     = $item_description_name;
            $appr_item_colour_code_Array["idValue"]       = $value['bom_article_id'];
            $appr_item_colour_code_Array["indexKey"]      = $key;
            array_push($apprItemColourCodeSource, $appr_item_colour_code_Array);

            $size_dimArray["id"]            = $size_dim;
            $size_dimArray["name"]          = $size_dim;
            $size_dimArray["appr_item_colour_code"] = $appr_item_colour_code;
            $size_dimArray["appr_item_code"] = $appr_item_code;
            $size_dimArray["garment_size_name"] = $garment_size_name;
            $size_dimArray["material_name"] = $material_name;
            $size_dimArray["content_name"]  = $content_name;
            $size_dimArray["blend_name"]    = $blend_name;
            $size_dimArray["item_name"]     = $item_description_name;
            $size_dimArray["idValue"]       = $value['bom_article_id'];
            $size_dimArray["indexKey"]      = $key;
            array_push($sizeDimSource, $size_dimArray);

            $uom_Array["id"]            = $uom;
            $uom_Array["name"]          = $uom;
            $uom_Array["size_dim"]      = $size_dim;
            $uom_Array["appr_item_colour_code"] = $appr_item_colour_code;
            $uom_Array["appr_item_code"] = $appr_item_code;
            $uom_Array["garment_size_name"] = $garment_size_name;
            $uom_Array["material_name"] = $material_name;
            $uom_Array["content_name"]  = $content_name;
            $uom_Array["blend_name"]    = $blend_name;
            $uom_Array["item_name"]     = $item_description_name;
            $uom_Array["idValue"]       = $value['bom_article_id'];
            $uom_Array["indexKey"]      = $key;
            array_push($uomSource, $uom_Array);           
        }       

        // *** remove dupliate *** //
        $itemSource = $this->removeDuplicate($itemSource, 'name');
        $blendSource = $this->removeDuplicate($blendSource, 'item_name');
        $contentSource = $this->removeDuplicate($contentSource, 'item_name');
        $materialSource = $this->removeDuplicate($materialSource, 'item_name');
        //$garmentSizeSource = $this->removeDuplicate($garmentSizeSource, 'item_name');
        // $apprItemCodeSource = $this->removeDuplicate($apprItemCodeSource, 'item_name');
        // $apprItemColourCodeSource = $this->removeDuplicate($apprItemColourCodeSource, 'item_name');
        // $sizeDimSource = $this->removeDuplicate($sizeDimSource, 'item_name');
        // $uomSource = $this->removeDuplicate($uomSource, 'item_name');

        // *** OUTPUT RESULT *** //
        $getdata['calculatedAmmount'] = $Amount;
        $output['data'] = $result;
        //$output['materialData'] = $materialData;
        $output['UOMDetails'] = $UOMDetails;
        $output['appendData'] = $getdata;
        $output['itemSource'] = $itemSource;
        $output['blendSource'] = $blendSource;
        $output['contentSource'] = $contentSource;
        $output['materialSource'] = $materialSource;
        $output['garmentsizeSource'] = $garmentSizeSource;
        $output['apprItemCodeSource'] = $apprItemCodeSource;
        $output['apprItemColourCodeSource'] = $apprItemColourCodeSource;
        $output['sizeDimSource'] = $sizeDimSource;
        $output['uomSource'] = $uomSource;
        $output['sizeData'] = $sizeMaster;

        //print_r($garmentSizeSource);
        return $output;
    }

    public function removeDuplicate($arrayValue, $name) {
        $new_array = array();
        $exists    = array();
        foreach( $arrayValue as $element ) {
            if( !in_array( $element[$name], $exists )) {
                $new_array[] = $element;
                $exists[]    = $element[$name];
            }
        }
        return $new_array;
    }

    public function updateBOM1RequirementDetailss__($req_data, $id) {
        foreach ($req_data as $key => $value)
        {
            $comboColor["enquiry_id"] = $id;
            $comboColor["bom_1_req_id"] = $value[1];
            $comboColor["po_enq_ref"] = $value[2];
            $comboColor["combo"] = $value[3];
            $comboColor["component"] = $value[4];
            $comboColor["color"] = $value[5];
            $comboColor["spec_code"] = $value[6];
            $comboColor["item_desc"] = $value[7];
            $comboColor["blend"] = $value[8];
            $comboColor["content"] = $value[9];
            $comboColor["material"] = $value[10];
            $comboColor["garment_size"] = $value[11];
            $comboColor["appr_item_code"] = $value[12];
            $comboColor["appr_item_col_code"] = $value[13];
            $comboColor["size_dim"] = $value[14];
            $comboColor["uom"] = $value[15];
            $comboColor["itemized_qty"] = $value[16];
            $comboColor["bom_intake"] = $value[17];
            $comboColor["req_bom_qty"] = $value[18];
            $comboColor["requirement_uom"] = $value[19];
            
            


            if ($value[0] == "edit") {
    // Step 1: Delete all old rows for this ID
    $this->db->delete('tbl_bom_article_1_requirement', array('bom_1_req_id' => $id));

    // Step 2: Re-insert all current rows from the frontend
    foreach ($comboColor as $row) {
        $row['bom_1_req_id'] = $id; // Ensure the ID is assigned
        unset($row["enquiry_id"]);  // Optional cleanup
        unset($row["bom_article_id"]);
        $this->db->insert('tbl_bom_article_1_requirement', $row);
    }
} else {
    // For new insert
    foreach ($comboColor as $row) {
        $this->db->insert('tbl_bom_article_1_requirement', $row);
    }
}
        }
    }



   public function updateBOM1RequirementDetailss($req_data, $id) {
    $incoming_ids = [];

    // Step 1: Collect existing bom_1_req_id from incoming data
    foreach ($req_data as $key => $value) {
        if ($value[0] == "edit") {
            $incoming_ids[] = $value[1]; // Collect existing IDs
        }
    }

    // Step 2: Delete rows not present in incoming data
    if (!empty($incoming_ids)) {
        $this->db->where('enquiry_id', $id);
        $this->db->where_not_in('bom_1_req_id', $incoming_ids);
        $this->db->delete('tbl_bom_article_1_requirement');
    } else {
        // No existing IDs in request — delete all related to enquiry_id
        $this->db->delete('tbl_bom_article_1_requirement', ['enquiry_id' => $id]);
    }

    // Step 3: Insert or Update logic
    foreach ($req_data as $key => $value) {
        $comboColor["enquiry_id"] = $id;
        $comboColor["bom_1_req_id"] = $value[1];
        $comboColor["po_enq_ref"] = $value[2];
        $comboColor["combo"] = $value[3];
        $comboColor["component"] = $value[4];
        $comboColor["color"] = $value[5];
        $comboColor["spec_code"] = $value[6];
        $comboColor["item_desc"] = $value[7];
        $comboColor["blend"] = $value[8];
        $comboColor["content"] = $value[9];
        $comboColor["material"] = $value[10];
        $comboColor["garment_size"] = $value[11];
        $comboColor["appr_item_code"] = $value[12];
        $comboColor["appr_item_col_code"] = $value[13];
        $comboColor["size_dim"] = $value[14];
        $comboColor["uom"] = $value[15];
        $comboColor["itemized_qty"] = $value[16];
        $comboColor["bom_intake"] = $value[17];
        $comboColor["req_bom_qty"] = $value[18];
        $comboColor["requirement_uom"] = $value[19];

        if ($value[0] == "edit") {
            $this->db->where('bom_1_req_id', $comboColor["bom_1_req_id"]);
            $this->db->update('tbl_bom_article_1_requirement', $comboColor);
        } else {
            unset($comboColor["bom_1_req_id"]);
            $this->db->insert('tbl_bom_article_1_requirement', $comboColor);
        }
    }

    return [
        "status" => "success",
        "statusCode" => "200"
    ];
}

    
    // ********** BOM 1 REQUIREMENT DETAILS ENDS HERE *********** /

    // ********** BOM 1 REQ CONSOLIDARED APPROVAL DETAILS STARTS HERE *********** /

    public function getBOM1ConsolidatedReqq($id) {
        $sql = "SELECT *, SUM(`req_bom_qty`) as calc_bom_qty FROM tbl_bom_article_1_requirement as a 
                where a.enquiry_id='$id' 
                GROUP BY a.item_desc, a.blend, a.content, a.material, a.garment_size, a.appr_item_code, 
                        a.appr_item_col_code, a.size_dim, a.uom ORDER BY a.bom_1_req_id";
                
        $data = $this->db->query($sql)->result_array();
        
        $sql1 = "SELECT * FROM tbl_bom_article_1_req_consld as a WHERE a.enquiry_id='$id'  ORDER BY a.bom_1_req_consld_id";
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
                            $data[$i]['size_dim'], $data[$i]['uom'], $data[$i]['calc_bom_qty'], $excess_qty, 
                            $plan_bom_qty, $data[$i]['requirement_uom']];
        }

        // print_r($result);
        // exit();
        
       // commented by myself regards new form integration
       // $materialData = $this->db->query('SELECT id as id, bomitemdesc as name, content, material, bomblend FROM '. KN_MASTER_BOM)->result();
        
        $UOM = unserialize(ARRUNITOFMEASURE);
        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }


        $getdata = $this->getSampleDetailss($id);
        $amountCalculation = $this->getOrderEntryComponentItemizedd($id);
        $amountCalculation = $amountCalculation["data"];
        $Amount = [];
        for($i=0; $i < sizeof($amountCalculation); $i++) {
            //print_r($amountCalculation[$i]);
            $AmountValue['po_ref'] = $amountCalculation[$i][2];
            $AmountValue['combo'] = $amountCalculation[$i][3];
            $AmountValue['component'] = $amountCalculation[$i][4];
            $AmountValue['colour'] = $amountCalculation[$i][5];
            $AmountValue['spec'] = $amountCalculation[$i][7];
            $AmountValue['amount'] = $amountCalculation[$i][12];
            array_push($Amount, $AmountValue);
        }
        $getdata['calculatedAmmount'] = $Amount;
        $output['data'] = $result;
       // $output['materialData'] = $materialData;
        $output['UOMDetails'] = $UOMDetails;
        $output['appendData'] = $getdata;
        return $output;
    }

    public function updateBom1ReqConsolidatedd($req_data, $id) {
        foreach ($req_data as $key => $value)
        {
            $comboColor["enquiry_id"] = $id;
            $comboColor["bom_1_req_consld_id"] = $value[1];
            $comboColor["excess_qty"] = $value[13];
            $comboColor["plan_bom_qty"] = $value[14];
            
            if($value[0] == "edit" && $comboColor["bom_1_req_consld_id"] !="") {
                $this->db->where('bom_1_req_consld_id', $comboColor["bom_1_req_consld_id"]);
                $this->db->update('tbl_bom_article_1_req_consld', $comboColor);
            }
            else {
                unset($comboColor["bom_1_req_consld_id"]);
                $this->db->insert('tbl_bom_article_1_req_consld', $comboColor);
                $primaryId = $this->db->insert_id();
            }
        }
    }    

    // ********** BOM 1 REQ CONSOLIDARED APPROVAL DETAILS ENDS HERE *********** /
    
    // ********** BOM 1 SOURCE DETAILS STARTs HERE *********** /

    public function getBOM1Sourcingg($id) {
        
        
        $sql = "SELECT * FROM tbl_bom_1_sourcing_details as a where a.enquiry_id='$id' ";
        $data = $this->db->query($sql)->result_array();
        $count = count($data);
         

if ($count > 0) {
     $sql3 = "SELECT * FROM tbl_bom_1_sourcing_details WHERE enquiry_id = ".$id."  ";
     $data3 = $this->db->query($sql3)->result_array();
} else {
  
     $sql3 = "SELECT * FROM tbl_bom_article_1_requirement WHERE enquiry_id = ".$id."   GROUP  BY appr_item_code ";
     $data3 = $this->db->query($sql3)->result_array();
}

        
    
        $result = [];
        for($i=0; $i < sizeof($data3); $i++)
        {
            $bom_1_req_id  = "";
            $sourcing_advice = "";
            $appr_item_code  = "";
            $appr_item_col_code  = "";
            $vendor_location = "";
            $vendor_name_address = "";
            $contact_email = "";
            $gst = "";
            $online_order_sys = "";
            $pass_expiry_date = "";
            $type = 'add';
            $bom_ven = array();

            if( isset($data3[$i]['bom_1_source_id'])) {
                @$bom_1_req_id  = $data3[$i]['bom_1_source_id'];
                @$type = 'edit';
            }
            
            if( isset($data3[$i]['sourcing_advice'])) {
                @$sourcing_advice = $data3[$i]['sourcing_advice'];
            }
      if($type == 'edit') {
      if( isset($data3[$i]['item_code'])) {
                @$appr_item_code = $data3[$i]['item_code'];
            }
       }else{
         if( isset($data3[$i]['appr_item_code'])) {
                @$appr_item_code = $data3[$i]['item_desc'];
            }
       }
          

            if( isset($data3[$i]['vendor_location'])) {
                @$vendor_location = $data3[$i]['vendor_location'];
            }
            
            if( isset($data3[$i]['vendor_name_address'])) {
               
                $vendor_name_address = $data3[$i]['vendor_name_address'];
            }
            
            if( isset($data3[$i]['contact_email'])) {
                @$contact_email = $data3[$i]['contact_email'];
            }
            
            if( isset($data3[$i]['gst'])) {
                @$gst = $data3[$i]['gst'];
            }
            
            if( isset($data3[$i]['online_order_sys'])) {
                @$online_order_sys = $data3[$i]['online_order_sys'];
            }
            
            if( isset($data3[$i]['pass_expiry_date'])) {
                @$pass_expiry_date = $data3[$i]['pass_expiry_date'];
            }

            if( $type=='edit') {
                $result[$i] = [$type, $bom_1_req_id,$appr_item_code,$sourcing_advice, $vendor_location, $vendor_name_address, $contact_email,
                            $gst, $online_order_sys, $pass_expiry_date
                        ];
            }else{
                $result[$i] = [$type, $bom_1_req_id,$appr_item_code,$sourcing_advice, $vendor_location, $vendor_name_address, $contact_email,
                            $gst, $online_order_sys, $pass_expiry_date
                        ];
            }
            
        }

        // print_r($result);
        // exit();
        
       // $materialData = $this->db->query('SELECT id as id, bomitemdesc as name, content, material, bomblend FROM '. KN_MASTER_BOM)->result();
        $sourceData = unserialize(ORDERSOURCEDETAIL);

        $getdata = $this->getSampleDetailss($id);
        $amountCalculation = $this->getOrderEntryComponentItemizedd($id);
        $amountCalculation = $amountCalculation["data"];
        $Amount = [];
        for($i=0; $i < sizeof($amountCalculation); $i++) {
            //print_r($amountCalculation[$i]);
            $AmountValue['po_ref'] = $amountCalculation[$i][2];
            $AmountValue['combo'] = $amountCalculation[$i][3];
            $AmountValue['component'] = $amountCalculation[$i][4];
            $AmountValue['colour'] = $amountCalculation[$i][5];
            $AmountValue['spec'] = $amountCalculation[$i][7];
            $AmountValue['amount'] = $amountCalculation[$i][12];
            array_push($Amount, $AmountValue);
        }

         $masterAdmins = $this->master_loginid();
         $subscriber_ids = array_column($masterAdmins, 'subscriber_id');

        //$bomVendor = $this->db->query('SELECT id as id, vendorname as name, contactpersonname, address, emailid, phone, mobile, gstno, iecode FROM '. KN_MASTER_BOM_VENDOR)->result_array();
       
       $sql = "SELECT b.id AS id, b.vendorname AS name, b.contactpersonname, b.address, b.emailid, b.phone, b.mobile, b.gstno, b.iecode
    FROM " . KN_MASTER_BOM_VENDOR . " AS b LEFT JOIN " . KN_USERS . " AS u ON b.updatedby = u.id
    WHERE u.subscriber_id IN (" . implode(',', array_map('intval', $subscriber_ids)) . ") AND b.status = 1";

       $bomVendor = $this->db->query($sql)->result_array();

        foreach($bomVendor as $key => $value) {
            // $bom_ven[$r] = $val['name'].','.$val['contactpersonname'].','.$val['address'];
            // $bom_email[$r] = $val['emailid'].','.$val['phone'].','.$val['mobile'];
            // $bom_gst[$r] = $val['gstno'].','.$val['iecode'];
             $address_parts = !empty($value['address']) ? explode(',', $value['address']) : [];
             $formatted_address = implode(",\n", $address_parts);
           
            $bom_ven[$key] = [ 'id'=> $value['id'], 'name'=> $value['name']. ",\n" .$formatted_address ];
            $bom_email[$key] = [ 'id'=> $value['emailid'], 'item_id'=> $value['id'], 'name'=>$value['contactpersonname']. ",\n" . $value['emailid']. ",\n" .$value['phone']. ",\n" .$value['mobile'] ];
            $bom_gst[$key] = [ 'id'=> $value['gstno'], 'item_id'=> $value['id'], 'name'=> $value['gstno']. ",\n" .$value['iecode'] ];
            
            
        }

        $getdata['calculatedAmmount'] = $Amount;
        $output['data'] = $result;
        //$output['materialData'] = $materialData;
        $output['sourceData'] = $sourceData;
        $output['bomVendor'] = $bom_ven;
        $output['bom_email'] = $bom_email;
        $output['bom_gst'] = $bom_gst;
        $output['appendData'] = $getdata;

       
        return $output;
    }
    
    public function updateBom1Sourcingg($req_data, $id) {
        //print_r($req_data); exit;
        foreach ($req_data as $key => $value)
        {
            $comboColor["enquiry_id"] = $id;
            $comboColor["bom_1_source_id"] = $value[1];
            $comboColor["item_code"] = $value[2];
            $comboColor["sourcing_advice"] = $value[3];
            $comboColor["vendor_location"] = $value[4];
            $comboColor["vendor_name_address"] = $value[5];
            $comboColor["contact_email"] = $value[6];
            $comboColor["gst"] = $value[7];
            $comboColor["online_order_sys"] = $value[8];
            $comboColor["pass_expiry_date"] = $value[9];
            
            if($value[0] == "edit" && $comboColor["bom_1_source_id"] !="") {
                $this->db->where('bom_1_source_id', $comboColor["bom_1_source_id"]);
                $this->db->update('tbl_bom_1_sourcing_details', $comboColor);
            }
            else {
                unset($comboColor["bom_1_source_id"]);
                $this->db->insert('tbl_bom_1_sourcing_details', $comboColor);
                $primaryId = $this->db->insert_id();
            }
        }
    }
    
    // ********** BOM 1 SOURCE DETAILS ENDS HERE *********** /

    public function get_bom1_sampling_despatchh($id) {
        
        $sql = "SELECT * FROM tbl_bom_article_1 as a WHERE a.enquiry_id ='$id'  and a.despatch_approval=1";
        $data = $this->db->query($sql)->result_array();

        // print_r($data);
        // exit();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = ['edit', $value['bom_article_id'], $value['item_description'], $value['blend'], $value['content'], $value['material'],
                            $value['garment_size'], $value['assigned_sample'], $value['vendor_name'], $value['despatch_airway_bill_no'], 
                            $value['airway_bill_date'], $value['delivery_sts'], $value['delivery_date']
                        ];
        }

        $bomVendor = $this->db->query('SELECT id as id, vendorname as name, contactpersonname, address, emailid, phone, mobile, gstno, iecode FROM '. KN_MASTER_BOM_VENDOR)->result();

        // *** BLEND . CONTENT / MATERIAL DATA *** //
        $itemData   = $this->getBlendContentMaterial('item',KN_BOM1_MASTER);
        $blendDetails   = $this->getBlendContentMaterial('blend',KN_BOM1_MASTER);
        $contentDetails   = $this->getBlendContentMaterial('content',KN_BOM1_MASTER);
        $materialDetails   = $this->getBlendContentMaterial('material',KN_BOM1_MASTER);

        // *** REQUIRED GARMENT SIZE *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);

        $output['data'] = $result;
        $output['materialData'] = $itemData;
        $output['blendSource'] = $blendDetails;
        $output['contentSource'] = $contentDetails;
        $output['materialSource'] = $materialDetails;
        $output['bomVendor'] = $bomVendor;
        $output['sizeData'] = $sizeMaster;

        return $output;
    }
    
    public function update_bom1_sampling_despatchh($req_data, $id) {
        foreach ($req_data as $key => $value)
        {
            $comboColor["enquiry_id"] = $id;
            $comboColor["bom_article_id"] = $value[1];
            $comboColor["assigned_sample"] = $value[7];
            $comboColor["vendor_name"] = $value[8];
            $comboColor["despatch_airway_bill_no"] = $value[9];
            $comboColor["airway_bill_date"] = $value[10];
            $comboColor["delivery_sts"] = $value[11];
            $comboColor["delivery_date"] = $value[12];
            
            if($value[0] == "edit" && $comboColor["bom_article_id"] !="") {
                $this->db->where('bom_article_id', $comboColor["bom_article_id"]);
                unset($comboColor["bom_article_id"]);
                unset($comboColor["enquiry_id"]);
                $this->db->update('tbl_bom_article_1', $comboColor);
            }
        }
    }

    // ********** BOM 1 SAMPLE DESPATCH STARTS HERE *********** /


    // ********** BOM 1 SAMPLEs ENDS HERE *********** /

    // ********** BOM 2 SAMPLE STARTS HERE *********** /
    
    // ********** BOM 2 SAMPLING APPROVAL DETAILS STARTS HERE *********** /

    public function getBOM2SamplingApprovalDetailss($id) {
        $sql = "SELECT * FROM tbl_bom_article_2 as a WHERE a.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = ['edit', $value['bom_article_id'], $value['item_description'], $value['blend'], $value['content'], $value['material'],
                            $value['garment_size'], 
                            // $value['category'], $value['bom_appr_need'], 
                            $value['sample_submission_planned_date'], 
                            $value['sample_submission_actual_date'], $value['appr_status'], $value['appr_item_code'], $value['appr_item_colour_code'],
                            $value['size_dim'], $value['uom'], $value['approved_by'], $value['approved_date'], 
                            // $value['despatch_approval']
                        ];
        }
        
        //$materialData = $this->db->query('SELECT id as id, bomitemdesc as name, content, material, bomblend FROM '. KN_MASTER_BOM)->result();

        $UOM = unserialize(ARRUNITOFMEASURE);

        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }
        
        // *** REQUIRED GARMENT SIZE *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
         // *** BLEND . CONTENT / MATERIAL DATA *** //
         $itemData   = $this->getBlendContentMaterial('item',KN_BOM_MASTER);
         $blendDetails   = $this->getBlendContentMaterial('blend',KN_BOM_MASTER);
         $contentDetails   = $this->getBlendContentMaterial('content',KN_BOM_MASTER);
         $materialDetails   = $this->getBlendContentMaterial('material',KN_BOM_MASTER);

        $output['data'] = $result;
        $output['materialData'] = $itemData;
        $output['UOMDetails'] = $UOMDetails;
        $output['sizeData'] = $sizeMaster;
        $output['blendSource'] = $blendDetails;
        $output['contentSource'] = $contentDetails;
        $output['materialSource'] = $materialDetails;
        return $output;
        
    }

    public function updateBOM2SamplingApprovalDetailss($req_data, $id) {
        foreach ($req_data as $key => $value)
        {
            $comboColor["bom_article_id"] = $value[1];
            $comboColor["enquiry_id"] = $id;
            $comboColor["item_description"] = $value[2];
            $comboColor["blend"] = $value[3];
            $comboColor["content"] = $value[4];
            $comboColor["material"] = $value[5];
            $comboColor["garment_size"] = $value[6];
            // $comboColor["category"] = $value[7];
            // $comboColor["bom_appr_need"] = $value[6];
            $comboColor["sample_submission_planned_date"] = $value[7];
            $comboColor["sample_submission_actual_date"] = $value[8];
            $comboColor["appr_status"] = $value[9];
            $comboColor["appr_item_code"] = $value[10];
            $comboColor["appr_item_colour_code"] = $value[11];
            $comboColor["size_dim"] = $value[12];
            $comboColor["uom"] = $value[13];
            $comboColor["approved_by"] = $value[14];
            $comboColor["approved_date"] = $value[15];
            // $comboColor["despatch_approval"] = $value[18];
            
            if($value[0] == "edit") {
                $this->db->where('bom_article_id', $comboColor["bom_article_id"]);
                $this->db->update('tbl_bom_article_2', $comboColor);
            }
            else {
                unset($comboColor["bom_article_id"]);
                $this->db->insert('tbl_bom_article_2', $comboColor);
                $primaryId = $this->db->insert_id();
            }
        }
    }
    
    // ********** BOM 2 SAMPLE DETAILS ENDS HERE *********** /

    // ********** BOM 2 REQUIREMENT DETAILS STARTS HERE *********** /

    /**
     * Retrieves Bill of Materials (BOM) requirements for a specific enquiry.
     *
     * This function queries the database to retrieve BOM requirements associated with
     * a given enquiry ID from the 'tbl_bom_article_2_requirement' table where the flag is set to 1.
     * It processes the data to include unit of measure details, sample details, amount calculations,
     * and related item, blend, content, material, garment size, approval item code, approval item
     * colour code, size dimension, and UOM sources. The function also removes duplicates from
     * certain data sources and returns a comprehensive dataset.
     *
     * @param int $id The enquiry ID for which BOM requirements are to be retrieved.
     * @return array An array containing the BOM requirements data, UOM details, calculated amounts,
     *               and various sources related to item, blend, content, material, garment size,
     *               approval item codes, approval item colour codes, size dimensions, and UOMs.
     */

    public function getBOM2RequirementDetailss($id) {
        $sql = "SELECT * FROM tbl_bom_article_2_requirement as a WHERE a.enquiry_id='$id' ";
        $data = $this->db->query($sql)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = ['edit', $value['bom_2_req_id'], $value['po_enq_ref'], $value['combo'], $value['component'],
                            $value['color'], $value['spec_code'], $value['item_desc'], $value['blend'], $value['content'],
                            $value['material'], $value['garment_size'], $value['appr_item_code'], 
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], 
                            $value['itemized_qty'], $value['bom_intake'], $value['req_bom_qty'], $value['requirement_uom']];
        }
        
       // $materialData = $this->db->query('SELECT id as id, description as name FROM '.KN_BOM_MASTER.' where type=1')->result();
        
        $UOM = unserialize(ARRUNITOFMEASURE);
        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }

        $getdata = $this->getSampleDetailss($id);
        $amountCalculation = $this->getOrderEntryComponentItemizedd($id);
        $amountCalculation = $amountCalculation["data"];
       
        // for($i=0; $i < sizeof($amountCalculation); $i++) {
        //     //print_r($amountCalculation[$i]);
        //     $AmountValue['po_ref'] = $amountCalculation[$i][2];
        //     $AmountValue['combo'] = $amountCalculation[$i][3];
        //     $AmountValue['component'] = $amountCalculation[$i][4];
        //     $AmountValue['colour'] = $amountCalculation[$i][5];
        //     $AmountValue['spec'] = $amountCalculation[$i][7];
        //     $AmountValue['amount'] = $amountCalculation[$i][sizeof($amountCalculation[$i])-2];
        //     array_push($Amount, $AmountValue);
        // }

        // *** REQUIRED GARMENT SIZE *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
         $Amount = [];

         for ($i = 0; $i < count($amountCalculation); $i++) {
    $row = $amountCalculation[$i];
  

    $AmountValue = array();
    $AmountValue['po_ref']    = $row[2] ?? '';
    $AmountValue['combo']     = $row[3] ?? '';
    $AmountValue['component'] = $row[4] ?? '';
    $AmountValue['colour']    = $row[5] ?? '';
    $AmountValue['spec']      = $row[7] ?? '';
    $AmountValue['amount']    = isset($row[count($row) - 2]) ? $row[count($row) - 2] : '';

     $startIndex = 8;
if (!empty($sizeMaster)) {
    $j = 0;
    foreach ($sizeMaster as $size) {
        if ($size['id'] == 0) continue; 
        $index = $startIndex + $j;
        $keyName = $size['name'];
        $AmountValue[$keyName] = isset($row[$index]) ? $row[$index] : '';
        $j++;
    }
}

    $Amount[] = $AmountValue;

}

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $req_sql = "SELECT a.*, b.description as blend_name, c.description as content_name, 
                    d.description as material_name 
                    FROM tbl_bom_article_2 as a 
                    inner join ".KN_BOM_MASTER." as b on a.blend=b.id 
                    inner join ".KN_BOM_MASTER." as c on a.content=c.id 
                    inner join ".KN_BOM_MASTER." as d on a.material=d.id 
                    WHERE a.enquiry_id='$id'  and a.appr_status=2";
        $req_data = $this->db->query($req_sql)->result_array();
        
        // return $req_data;

        // print_r($sizeMaster);
        // exit();

         
        $itemSource = [];
        $blendSource = [];
        $contentSource = [];
        $materialSource = [];
        $garmentSizeSource = [];
        $apprItemCodeSource = [];
        $apprItemColourCodeSource = [];
        $sizeDimSource = [];
        $uomSource = [];
        foreach ($req_data as $key => $value) 
        {
            $item_description = $value["item_description"];
            $garment_size = $value["garment_size"];
            $material_name = $value["material_name"];
            $content_name = $value["content_name"];
            $blend_name = $value["blend_name"];
            $appr_item_code = $value["appr_item_code"];
            $appr_item_colour_code = $value["appr_item_colour_code"];
            $size_dim = $value["size_dim"];
            $uom = $value["uom"];

           // commented by myself regards new form integration
           // $item_description_name = "";
            $item_description_name = $item_description;
            $garment_size_name = "";

             $materialData   = $this->getBlendContentMaterial('item',KN_BOM_MASTER);
            
               foreach ($materialData as $material) {
              if ($material->id == $item_description) {
              $item_description_name = $material->name;
              break;
                 }
                      }
            
            
            for($j = 0; $j < sizeof($sizeMaster); $j++) {
                if($sizeMaster[$j]['id'] == $garment_size) {
                    $garment_size_name = $sizeMaster[$j]['name'];
                    break;
                }
            }

            $itemArray["id"]         = $item_description_name;
            $itemArray["name"]       = $item_description_name;
            $itemArray["idValue"]    = $value['bom_article_id'];
            $itemArray["indexKey"]   = $key;
            array_push($itemSource, $itemArray);

            $blendArray["id"]         = $blend_name;
            $blendArray["name"]       = $blend_name;
            $blendArray["item_name"]  = $item_description_name;
            $blendArray["idValue"]    = $value['bom_article_id'];
            $blendArray["indexKey"]   = $key;
            array_push($blendSource, $blendArray);

            $contentArray["id"]         = $content_name;
            $contentArray["name"]       = $content_name;
            $contentArray["blend_name"] = $blend_name;
            $contentArray["item_name"]  = $item_description_name;
            $contentArray["idValue"]    = $value['bom_article_id'];
            $contentArray["indexKey"]   = $key;
            array_push($contentSource, $contentArray);

            $materialArray["id"]           = $material_name;
            $materialArray["name"]         = $material_name;
            $materialArray["content_name"] = $content_name;
            $materialArray["blend_name"]   = $blend_name;
            $materialArray["item_name"]    = $item_description_name;
            $materialArray["idValue"]      = $value['bom_article_id'];
            $materialArray["indexKey"]     = $key;
            array_push($materialSource, $materialArray);

            $garmentSizeArray["id"]            = $garment_size_name;
            $garmentSizeArray["name"]          = $garment_size_name;
            $garmentSizeArray["material_name"] = $material_name;
            $garmentSizeArray["content_name"]  = $content_name;
            $garmentSizeArray["blend_name"]    = $blend_name;
            $garmentSizeArray["item_name"]     = $item_description_name;
            $garmentSizeArray["idValue"]       = $value['bom_article_id'];
            $garmentSizeArray["indexKey"]      = $key;
            array_push($garmentSizeSource, $garmentSizeArray);

            $apprItemCodeArray["id"]            = $appr_item_code;
            $apprItemCodeArray["name"]          = $appr_item_code;
            $apprItemCodeArray["garment_size_name"] = $garment_size_name;
            $apprItemCodeArray["material_name"] = $material_name;
            $apprItemCodeArray["content_name"]  = $content_name;
            $apprItemCodeArray["blend_name"]    = $blend_name;
            $apprItemCodeArray["item_name"]     = $item_description_name;
            $apprItemCodeArray["idValue"]       = $value['bom_article_id'];
            $apprItemCodeArray["indexKey"]      = $key;
            array_push($apprItemCodeSource, $apprItemCodeArray);

            $appr_item_colour_code_Array["id"]            = $appr_item_colour_code;
            $appr_item_colour_code_Array["name"]          = $appr_item_colour_code;
            $appr_item_colour_code_Array["appr_item_code"] = $appr_item_code;
            $appr_item_colour_code_Array["garment_size_name"] = $garment_size_name;
            $appr_item_colour_code_Array["material_name"] = $material_name;
            $appr_item_colour_code_Array["content_name"]  = $content_name;
            $appr_item_colour_code_Array["blend_name"]    = $blend_name;
            $appr_item_colour_code_Array["item_name"]     = $item_description_name;
            $appr_item_colour_code_Array["idValue"]       = $value['bom_article_id'];
            $appr_item_colour_code_Array["indexKey"]      = $key;
            array_push($apprItemColourCodeSource, $appr_item_colour_code_Array);

            $size_dimArray["id"]            = $size_dim;
            $size_dimArray["name"]          = $size_dim;
            $size_dimArray["appr_item_colour_code"] = $appr_item_colour_code;
            $size_dimArray["appr_item_code"] = $appr_item_code;
            $size_dimArray["garment_size_name"] = $garment_size_name;
            $size_dimArray["material_name"] = $material_name;
            $size_dimArray["content_name"]  = $content_name;
            $size_dimArray["blend_name"]    = $blend_name;
            $size_dimArray["item_name"]     = $item_description_name;
            $size_dimArray["idValue"]       = $value['bom_article_id'];
            $size_dimArray["indexKey"]      = $key;
            array_push($sizeDimSource, $size_dimArray);

            $uom_Array["id"]            = $uom;
            $uom_Array["name"]          = $uom;
            $uom_Array["size_dim"]      = $size_dim;
            $uom_Array["appr_item_colour_code"] = $appr_item_colour_code;
            $uom_Array["appr_item_code"] = $appr_item_code;
            $uom_Array["garment_size_name"] = $garment_size_name;
            $uom_Array["material_name"] = $material_name;
            $uom_Array["content_name"]  = $content_name;
            $uom_Array["blend_name"]    = $blend_name;
            $uom_Array["item_name"]     = $item_description_name;
            $uom_Array["idValue"]       = $value['bom_article_id'];
            $uom_Array["indexKey"]      = $key;
            array_push($uomSource, $uom_Array);           
        }       

        // *** remove dupliate *** //
        $itemSource = $this->removeDuplicate($itemSource, 'name');
        $blendSource = $this->removeDuplicate($blendSource, 'item_name');
        $contentSource = $this->removeDuplicate($contentSource, 'item_name');
        $materialSource = $this->removeDuplicate($materialSource, 'item_name');
        // $garmentSizeSource = $this->removeDuplicate($garmentSizeSource, 'item_name');
        // $apprItemCodeSource = $this->removeDuplicate($apprItemCodeSource, 'item_name');
        // $apprItemColourCodeSource = $this->removeDuplicate($apprItemColourCodeSource, 'item_name');
        // $sizeDimSource = $this->removeDuplicate($sizeDimSource, 'item_name');
        // $uomSource = $this->removeDuplicate($uomSource, 'item_name');

        // *** OUTPUT RESULT *** //
        $getdata['calculatedAmmount'] = $Amount;
        $output['data'] = $result;
        //$output['materialData'] = $materialData;
        $output['UOMDetails'] = $UOMDetails;
        $output['appendData'] = $getdata;
        $output['itemSource'] = $itemSource;
        $output['blendSource'] = $blendSource;
        $output['contentSource'] = $contentSource;
        $output['materialSource'] = $materialSource;
        $output['garmentsizeSource'] = $garmentSizeSource;
        $output['apprItemCodeSource'] = $apprItemCodeSource;
        $output['apprItemColourCodeSource'] = $apprItemColourCodeSource;
        $output['sizeDimSource'] = $sizeDimSource;
        $output['uomSource'] = $uomSource;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }


    public function updateBOM2RequirementDetailss($req_data, $id) {

         $incoming_ids = [];

    // Step 1: Collect existing bom_1_req_id from incoming data
    foreach ($req_data as $key => $value) {
        if ($value[0] == "edit") {
            $incoming_ids[] = $value[1]; // Collect existing IDs
        }
    }

    // Step 2: Delete rows not present in incoming data
    if (!empty($incoming_ids)) {
        $this->db->where('enquiry_id', $id);
        $this->db->where_not_in('bom_2_req_id', $incoming_ids);
        $this->db->delete('tbl_bom_article_2_requirement');
    } else {
        // No existing IDs in request — delete all related to enquiry_id
        $this->db->delete('tbl_bom_article_2_requirement', ['enquiry_id' => $id]);
    }

        foreach ($req_data as $key => $value)
        {
            $comboColor["enquiry_id"] = $id;
            $comboColor["bom_2_req_id"] = $value[1];
            $comboColor["po_enq_ref"] = $value[2];
            $comboColor["combo"] = $value[3];
            $comboColor["component"] = $value[4];
            $comboColor["color"] = $value[5];
            $comboColor["spec_code"] = $value[6];
            $comboColor["item_desc"] = $value[7];
            $comboColor["blend"] = $value[8];
            $comboColor["content"] = $value[9];
            $comboColor["material"] = $value[10];
            $comboColor["garment_size"] = $value[11];
            $comboColor["appr_item_code"] = $value[12];
            $comboColor["appr_item_col_code"] = $value[13];
            $comboColor["size_dim"] = $value[14];
            $comboColor["uom"] = $value[15];
            $comboColor["itemized_qty"] = $value[16];
            $comboColor["bom_intake"] = $value[17];
            $comboColor["req_bom_qty"] = $value[18];
            $comboColor["requirement_uom"] = $value[19];
            
            // $bom_id = $comboColor['bom_article_id'];
            // $get_req = "SELECT * FROM tbl_bom_article_2_requirement WHERE enquiry_id='$id' AND flag=1 and bom_article_id='$bom_id'";
            // $get_req_data = $this->db->query($get_req)->result_array();
            // if(sizeof($get_req_data) > 0) {
            if($value[0] == "edit") {
                $this->db->where('bom_2_req_id', $comboColor["bom_2_req_id"]);
                unset($comboColor["enquiry_id"]);
                unset($comboColor["bom_article_id"]);
                $this->db->update('tbl_bom_article_2_requirement', $comboColor);
            }
            else {
                $this->db->insert('tbl_bom_article_2_requirement', $comboColor);
                $primaryId = $this->db->insert_id();
            }
        }
    }
    
    // ********** BOM 2 REQUIREMENT DETAILS ENDS HERE *********** /

    // ********** BOM 2 REQ CONSOLIDARED APPROVAL DETAILS STARTS HERE *********** /

    public function getBOM2ConsolidatedReqq($id) {
        // $sql = "SELECT *, SUM(`req_bom_qty`) as calc_bom_qty FROM tbl_bom_article_2_requirement as a 
        //         where a.enquiry_id='$id' AND a.flag=1
        //         GROUP BY a.po_enq_ref, a.combo, a.component, a.color, a.spec_code,
        //                 a.item_desc, a.blend, a.content, a.material, a.garment_size, a.appr_item_code, 
        //                 a.appr_item_col_code, a.size_dim, a.uom";
        $sql = "SELECT *, SUM(`req_bom_qty`) as calc_bom_qty FROM tbl_bom_article_2_requirement as a 
                where a.enquiry_id='$id' 
                GROUP BY a.item_desc, a.blend, a.content, a.material, a.garment_size, a.appr_item_code, 
                        a.appr_item_col_code, a.size_dim, a.uom ORDER BY a.bom_2_req_id";
                
        $data = $this->db->query($sql)->result_array();
        
        $sql1 = "SELECT * FROM tbl_bom_article_2_req_consld as a WHERE a.enquiry_id='$id'  ORDER BY a.bom_2_req_consld_id";
        $data1 = $this->db->query($sql1)->result_array();
        // print_r($data1);
        // exit();

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
                $bom_2_req_consld_id = $data1[$i]['bom_2_req_consld_id'];
            }
            else {
                $bom_2_req_consld_id = "";
            }
            
            $result[$i] = ['edit', $bom_2_req_consld_id, $data[$i]['bom_2_req_id'], $data[$i]['item_desc'], $data[$i]['blend'],
                            $data[$i]['content'], $data[$i]['material'], 
                            $data[$i]['garment_size'], $data[$i]['appr_item_code'], $data[$i]['appr_item_col_code'], 
                            $data[$i]['size_dim'], $data[$i]['uom'], $data[$i]['calc_bom_qty'], $excess_qty, 
                            $plan_bom_qty, $data[$i]['requirement_uom']];
        }

        // print_r($result);
        // exit();
        
       // $materialData = $this->db->query('SELECT id as id, bomitemdesc as name, content, material, bomblend FROM '. KN_MASTER_BOM)->result();
        
        $UOM = unserialize(ARRUNITOFMEASURE);
        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }


        $getdata = $this->getSampleDetailss($id);
        $amountCalculation = $this->getOrderEntryComponentItemizedd($id);
        $amountCalculation = $amountCalculation["data"];
        $Amount = [];
        for($i=0; $i < sizeof($amountCalculation); $i++) {
            //print_r($amountCalculation[$i]);
            $AmountValue['po_ref'] = $amountCalculation[$i][2];
            $AmountValue['combo'] = $amountCalculation[$i][3];
            $AmountValue['component'] = $amountCalculation[$i][4];
            $AmountValue['colour'] = $amountCalculation[$i][5];
            $AmountValue['spec'] = $amountCalculation[$i][7];
            $AmountValue['amount'] = $amountCalculation[$i][12];
            array_push($Amount, $AmountValue);
        }
        $getdata['calculatedAmmount'] = $Amount;
        $output['data'] = $result;
      //  $output['materialData'] = $materialData;
        $output['UOMDetails'] = $UOMDetails;
        $output['appendData'] = $getdata;
        return $output;
    }

    public function updateBom2ReqConsolidatedd($req_data, $id) {
        foreach ($req_data as $key => $value)
        {
            $comboColor["enquiry_id"] = $id;
            $comboColor["bom_2_req_consld_id"] = $value[1];
            $comboColor["excess_qty"] = $value[13];
            $comboColor["plan_bom_qty"] = $value[14];
            
            if($value[0] == "edit" && $comboColor["bom_2_req_consld_id"] !="") {
                $this->db->where('bom_2_req_consld_id', $comboColor["bom_2_req_consld_id"]);
                $this->db->update('tbl_bom_article_2_req_consld', $comboColor);
            }
            else {
                unset($comboColor["bom_2_req_consld_id"]);
                $this->db->insert('tbl_bom_article_2_req_consld', $comboColor);
                $primaryId = $this->db->insert_id();
            }
        }
    }    

    // ********** BOM 2 REQ CONSOLIDARED APPROVAL DETAILS ENDS HERE *********** /
    
    // ********** BOM 2 SOURCE DETAILS STARTs HERE *********** /

    public function getBOM2Sourcingg($id) {
        
        // $sql = "SELECT * FROM tbl_bom_article_2_requirement as a 
        //         where a.enquiry_id='$id' AND a.flag=1
        //         GROUP BY a.item_desc";
        // $data = $this->db->query($sql)->result_array();
         $sql = "SELECT * FROM tbl_bom_2_sourcing_details as a where a.enquiry_id='$id' ";
        $data = $this->db->query($sql)->result_array();
        $count = count($data);

         

        
        // $sql1 = "SELECT * FROM tbl_bom_2_sourcing_details as a WHERE a.enquiry_id='$id' AND a.flag=1";
        // $data1 = $this->db->query($sql1)->result_array();
       

        if ($count > 0) {
     $sql3 = "SELECT * FROM tbl_bom_2_sourcing_details WHERE enquiry_id = ".$id."  ";
     $data1 = $this->db->query($sql3)->result_array();
    
} else {
  
     $sql3 = "SELECT * FROM tbl_bom_article_2_requirement WHERE enquiry_id = ".$id."   GROUP  BY appr_item_code ";
     $data1 = $this->db->query($sql3)->result_array();
}



        $result = [];
        for($i=0; $i < sizeof($data1); $i++)
        {
            $bom_2_source_id  = "";
            $sourcing_advice = "";
            $vendor_location = "";
            $vendor_name_address = "";
            $contact_email = "";
            $gst = "";
            $online_order_sys = "";
            $pass_expiry_date = "";
            $type = 'add';
             $bom_ven = array();

            if( isset($data1[$i]['bom_2_source_id'])) {
                @$bom_2_source_id  = $data1[$i]['bom_2_source_id'];
                 @$type = 'edit';
            }
            
            if( isset($data1[$i]['sourcing_advice'])) {
                @$sourcing_advice = $data1[$i]['sourcing_advice'];
            }
            
            if( isset($data1[$i]['vendor_location'])) {
                @$vendor_location = $data1[$i]['vendor_location'];
            }
            
            if( isset($data1[$i]['vendor_name_address'])) {
                @$vendor_name_address = $data1[$i]['vendor_name_address'];
            }
            
            if( isset($data1[$i]['contact_email'])) {
                @$contact_email = $data1[$i]['contact_email'];
            }
            
            if( isset($data1[$i]['gst'])) {
                @$gst = $data1[$i]['gst'];
            }
            
            if( isset($data1[$i]['online_order_sys'])) {
                @$online_order_sys = $data1[$i]['online_order_sys'];
            }
            
            if( isset($data1[$i]['pass_expiry_date'])) {
                @$pass_expiry_date = $data1[$i]['pass_expiry_date'];
            }
            if($type == 'edit') {
      if( isset($data1[$i]['item_code'])) {
                @$appr_item_code = $data1[$i]['item_code'];
            }
       }else{
         if( isset($data1[$i]['appr_item_code'])) {
                @$appr_item_code = $data1[$i]['item_desc'];
            }
            
       }

           
            //  $result[$i] = [$type, $bom_2_source_id, $data[$i]['item_desc'],
            //                   $sourcing_advice, $vendor_location, $vendor_name_address, $contact_email,
            //                 $gst, $online_order_sys, $pass_expiry_date
            //             ];

            
            if( $type=='edit') {
                $result[$i] = [$type, $bom_2_source_id,$appr_item_code,$sourcing_advice, $vendor_location, $vendor_name_address, $contact_email,
                            $gst, $online_order_sys, $pass_expiry_date
                        ];
            }else{
                $result[$i] = [$type, $bom_2_source_id,$appr_item_code,$sourcing_advice, $vendor_location, $vendor_name_address, $contact_email,
                            $gst, $online_order_sys, $pass_expiry_date
                        ];
            }
        }

        
        
        //$materialData = $this->db->query('SELECT id as id, bomitemdesc as name, content, material, bomblend FROM '. KN_MASTER_BOM)->result();
        $sourceData = unserialize(ORDERSOURCEDETAIL);

        $getdata = $this->getSampleDetailss($id);
        $amountCalculation = $this->getOrderEntryComponentItemizedd($id);
        $amountCalculation = $amountCalculation["data"];
        $Amount = [];
        for($i=0; $i < sizeof($amountCalculation); $i++) {
            //print_r($amountCalculation[$i]);
            $AmountValue['po_ref'] = $amountCalculation[$i][2];
            $AmountValue['combo'] = $amountCalculation[$i][3];
            $AmountValue['component'] = $amountCalculation[$i][4];
            $AmountValue['colour'] = $amountCalculation[$i][5];
            $AmountValue['spec'] = $amountCalculation[$i][7];
            $AmountValue['amount'] = $amountCalculation[$i][12];
            array_push($Amount, $AmountValue);
        }

        
    //  $bomVendor = $this->db->query('SELECT id as id, vendorname as name, contactpersonname, address, emailid, phone, mobile, gstno, iecode FROM '. KN_MASTER_BOM_VENDOR)->result_array();
    //     foreach($bomVendor as $key => $value) {
    //          $address_parts = !empty($value['address']) ? explode(',', $value['address']) : [];
    //          $formatted_address = implode(",\n", $address_parts);
           
    //         $bom_ven[$key] = [ 'id'=> $value['id'], 'name'=> $value['name']. ",\n" .$formatted_address ];
    //        $bom_email[$key] = [ 'id'=> $value['emailid'], 'item_id'=> $value['id'], 'name'=>$value['contactpersonname']. ",\n" . $value['emailid']. ",\n" .$value['phone']. ",\n" .$value['mobile'] ];
            
    //         $bom_gst[$key] = [ 'id'=> $value['gstno'], 'item_id'=> $value['id'], 'name'=> $value['gstno']. ",\n" .$value['iecode'] ];
            
            
    //     }

      $masterAdmins = $this->master_loginid();
     $subscriber_ids = array_column($masterAdmins, 'subscriber_id');


         
      $sql = "SELECT b.id AS id, b.vendorname AS name, b.contactpersonname, b.address, b.emailid, b.phone, b.mobile, b.gstno, b.iecode
    FROM " . KN_MASTER_BOM_VENDOR . " AS b LEFT JOIN " . KN_USERS . " AS u ON b.updatedby = u.id
    WHERE u.subscriber_id IN (" . implode(',', array_map('intval', $subscriber_ids)) . ") AND b.status = 1";

       $bomVendor = $this->db->query($sql)->result_array();
       //$bomVendor = $this->db->query('SELECT id as id, vendorname as name, contactpersonname, address, emailid, phone, mobile, gstno, iecode FROM '. KN_MASTER_BOM_VENDOR)->result_array();
    

     foreach($bomVendor as $key => $value) {
            // $bom_ven[$r] = $val['name'].','.$val['contactpersonname'].','.$val['address'];
            // $bom_email[$r] = $val['emailid'].','.$val['phone'].','.$val['mobile'];
            // $bom_gst[$r] = $val['gstno'].','.$val['iecode'];
             $address_parts = !empty($value['address']) ? explode(',', $value['address']) : [];
             $formatted_address = implode(",\n", $address_parts);
           
            $bom_ven[$key] = [ 'id'=> $value['id'], 'name'=> $value['name']. ",\n" .$formatted_address ];
            $bom_email[$key] = [ 'id'=> $value['emailid'], 'item_id'=> $value['id'], 'name'=>$value['contactpersonname']. ",\n" . $value['emailid']. ",\n" .$value['phone']. ",\n" .$value['mobile'] ];
            $bom_gst[$key] = [ 'id'=> $value['gstno'], 'item_id'=> $value['id'], 'name'=> $value['gstno']. ",\n" .$value['iecode'] ];
            
            
        }

        $getdata['calculatedAmmount'] = $Amount;
        $output['data'] = $result;
        //$output['materialData'] = $materialData;
        $output['sourceData'] = $sourceData;
        $output['bomVendor'] = $bom_ven;
        $output['bom_email'] = $bom_email;
        $output['bom_gst'] = $bom_gst;
        $output['appendData'] = $getdata;

        //print_r($output);
        // exit();
         return $output;
    }

    
    public function updateBom2Sourcingg($req_data, $id) {
        foreach ($req_data as $key => $value)
        {
            $comboColor["enquiry_id"] = $id;
            $comboColor["bom_2_source_id"] = $value[1];
             $comboColor["item_code"] = $value[2];
            $comboColor["sourcing_advice"] = $value[3];
            $comboColor["vendor_location"] = $value[4];
            $comboColor["vendor_name_address"] = $value[5];
            $comboColor["contact_email"] = $value[6];
            $comboColor["gst"] = $value[7];
            $comboColor["online_order_sys"] = $value[8];
            $comboColor["pass_expiry_date"] = $value[9];
            
            if($value[0] == "edit" && $comboColor["bom_2_source_id"] !="") {
                $this->db->where('bom_2_source_id', $comboColor["bom_2_source_id"]);
                $this->db->update('tbl_bom_2_sourcing_details', $comboColor);
            }
            else {
                unset($comboColor["bom_2_source_id"]);
                $this->db->insert('tbl_bom_2_sourcing_details', $comboColor);
                $primaryId = $this->db->insert_id();
            }
        }
    }
     
    // ********** BOM 2 SOURCE DETAILS ENDS HERE *********** /

    public function get_bom2_sampling_despatchh($id) {
        
        $sql = "SELECT * FROM tbl_bom_article_2 as a WHERE a.enquiry_id ='$id'  and a.despatch_approval=1";
        $data = $this->db->query($sql)->result_array();

        // print_r($data);
        // exit();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = ['edit', $value['bom_article_id'], $value['item_description'], $value['blend'], $value['content'], $value['material'],
                            $value['garment_size'], $value['assigned_sample'], $value['vendor_name'], $value['despatch_airway_bill_no'], 
                            $value['airway_bill_date'], $value['delivery_sts'], $value['delivery_date']
                        ];
        }

        $bomVendor = $this->db->query('SELECT id as id, vendorname as name, contactpersonname, address, emailid, phone, mobile, gstno, iecode FROM '. KN_MASTER_BOM_VENDOR)->result();

        // *** BLEND . CONTENT / MATERIAL DATA *** //
        $itemData   = $this->getBlendContentMaterial('item',KN_BOM_MASTER);
        $blendDetails   = $this->getBlendContentMaterial('blend',KN_BOM_MASTER);
        $contentDetails   = $this->getBlendContentMaterial('content',KN_BOM_MASTER);
        $materialDetails   = $this->getBlendContentMaterial('material',KN_BOM_MASTER);

        // *** REQUIRED GARMENT SIZE *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);

        $output['data'] = $result;
        $output['materialData'] = $itemData;
        $output['blendSource'] = $blendDetails;
        $output['contentSource'] = $contentDetails;
        $output['materialSource'] = $materialDetails;
        $output['bomVendor'] = $bomVendor;
        $output['sizeData'] = $sizeMaster;

        return $output;
    }
    
    public function update_bom2_sampling_despatchh($req_data, $id) {
        foreach ($req_data as $key => $value)
        {
            $comboColor["enquiry_id"] = $id;
            $comboColor["bom_article_id"] = $value[1];
            $comboColor["assigned_sample"] = $value[7];
            $comboColor["vendor_name"] = $value[8];
            $comboColor["despatch_airway_bill_no"] = $value[9];
            $comboColor["airway_bill_date"] = $value[10];
            $comboColor["delivery_sts"] = $value[11];
            $comboColor["delivery_date"] = $value[12];
            
            if($value[0] == "edit" && $comboColor["bom_article_id"] !="") {
                $this->db->where('bom_article_id', $comboColor["bom_article_id"]);
                unset($comboColor["bom_article_id"]);
                unset($comboColor["enquiry_id"]);
                $this->db->update('tbl_bom_article_2', $comboColor);
            }
        }
    }

    // ********** BOM 2 SAMPLE DESPATCH STARTS HERE *********** /


    // ********** BOM 2 SAMPLE DESPATCH ENDS HERE *********** /

    // // ********** BOM 2 SAMPLING APPROVAL DETAILS STARTS HERE *********** /

    // public function getBOM2SamplingApprovalDetailss($id) {
    //     $sql = "SELECT * FROM tbl_bom_article_2 as a WHERE a.enquiry_id = " . $id . " AND a.flag=1";
    //     $data = $this->db->query($sql)->result_array();
    //     $result = [];
    //     foreach ($data as $key => $value)
    //     {
    //         $result[$key] = ['edit', $value['bom_article_id'], $value['item_description'], $value['blend'], 
    //                         $value['garment_size'], $value['category'], $value['bom_appr_need'], $value['sample_submission_planned_date'], 
    //                         $value['sample_submission_actual_date'], $value['appr_status'], $value['appr_item_code'], $value['appr_item_colour_code'],
    //                         $value['size_dim'], $value['uom'], $value['approved_by'], $value['approved_date']];
    //     }
        
    //     $materialData = $this->db->query('SELECT id as id, bomitemdesc as name, content, material, bomblend FROM '. KN_MASTER_BOM)->result();
        
    //     $UOM = unserialize(ARRUNITOFMEASURE);
    //     $UOMDetails = [];
    //     for($i = 1; sizeof($UOM) > $i; $i++)
    //     {
    //         $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
    //         array_push($UOMDetails, $data);
    //     }
        
    //     $sizeChart    = $this->getSizeChart($id);
    //     $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);

    //     $output['data'] = $result;
    //     $output['materialData'] = $materialData;
    //     $output['UOMDetails'] = $UOMDetails;
    //     $output['sizeData'] = $sizeMaster;
    //     return $output;
        
    // }

    // public function updateBOM2SamplingApprovalDetailss($req_data, $id) {
    //     foreach ($req_data as $key => $value)
    //     {
    //         $comboColor["bom_article_id"] = $value[1];
    //         $comboColor["enquiry_id"] = $id;
    //         $comboColor["item_description"] = $value[2];
    //         $comboColor["blend"] = $value[3];
    //         $comboColor["garment_size"] = $value[4];
    //         $comboColor["category"] = $value[5];
    //         $comboColor["bom_appr_need"] = $value[6];
    //         $comboColor["sample_submission_planned_date"] = $value[7];
    //         $comboColor["sample_submission_actual_date"] = $value[8];
    //         $comboColor["appr_status"] = $value[9];
    //         $comboColor["appr_item_code"] = $value[10];
    //         $comboColor["appr_item_colour_code"] = $value[11];
    //         $comboColor["size_dim"] = $value[12];
    //         $comboColor["uom"] = $value[13];
    //         $comboColor["approved_by"] = $value[14];
    //         $comboColor["approved_date"] = $value[15];
            
    //         if($value[0] == "edit") {
    //             $this->db->where('bom_article_id', $comboColor["bom_article_id"]);
    //             $this->db->update('tbl_bom_article_2', $comboColor);
    //         }
    //         else {
    //             unset($comboColor["bom_article_id"]);
    //             $this->db->insert('tbl_bom_article_2', $comboColor);
    //             $primaryId = $this->db->insert_id();
    //         }
    //     }
    // }
    
    // // ********** BOM 2 SAMPLING APPROVAL ENDS HERE *********** /

    // // ********** BOM 2 REQUIREMENT DETAILS STARTS HERE *********** /

    // public function getBOM2RequirementDetailss($id) {
    //     $sql = "SELECT * FROM tbl_bom_article_2 as a WHERE a.enquiry_id='$id' AND a.flag=1 and appr_status=2";
    //     $data = $this->db->query($sql)->result_array();

    //     for($i=0; $i < sizeof($data); $i++) {
    //         $bom_id = $data[$i]['bom_article_id'];
    //         $get_req = "SELECT * FROM tbl_bom_article_2_requirement WHERE enquiry_id='$id' AND flag=1 and bom_article_id='$bom_id'";
    //         $get_req_data = $this->db->query($get_req)->result_array();
    //         if(sizeof($get_req_data) > 0) {
    //             $data[$i]['po_enq_ref'] = $get_req_data[0]['po_enq_ref'];
    //             $data[$i]['combo'] = $get_req_data[0]['combo'];
    //             $data[$i]['component'] = $get_req_data[0]['component'];
    //             $data[$i]['color'] = $get_req_data[0]['color'];
    //             $data[$i]['spec_code'] = $get_req_data[0]['spec_code'];
    //             $data[$i]['itemized_qty'] = $get_req_data[0]['itemized_qty'];
    //             $data[$i]['bom_intake'] = $get_req_data[0]['bom_intake'];
    //             $data[$i]['req_bom_qty'] = $get_req_data[0]['req_bom_qty'];
    //             $data[$i]['requirement_uom'] = $get_req_data[0]['requirement_uom'];
    //         }
    //         else {
    //             $data[$i]['po_enq_ref'] = "";
    //             $data[$i]['combo'] = "";
    //             $data[$i]['component'] = "";
    //             $data[$i]['color'] = "";
    //             $data[$i]['spec_code'] = "";
    //             $data[$i]['itemized_qty'] = "";
    //             $data[$i]['bom_intake'] = "";
    //             $data[$i]['req_bom_qty'] = "";
    //             $data[$i]['requirement_uom'] = "";
    //         }
    //     }

    //     $result = [];
    //     foreach ($data as $key => $value)
    //     {
    //         $result[$key] = ['edit', $value['bom_article_id'], $value['po_enq_ref'], $value['combo'], $value['component'],
    //                         $value['color'], $value['spec_code'], $value['item_description'], $value['blend'], 
    //                         $value['garment_size'], $value['appr_item_code'], 
    //                         $value['appr_item_colour_code'], $value['size_dim'], $value['uom'], 
    //                         $value['itemized_qty'], $value['bom_intake'], $value['bom_intake'], $value['requirement_uom']];
    //     }
        
    //     $materialData = $this->db->query('SELECT id as id, bomitemdesc as name, content, material, bomblend FROM '. KN_MASTER_BOM)->result();
        
    //     $UOM = unserialize(ARRUNITOFMEASURE);
    //     $UOMDetails = [];
    //     for($i = 1; sizeof($UOM) > $i; $i++)
    //     {
    //         $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
    //         array_push($UOMDetails, $data);
    //     }

    //     $getdata = $this->getSampleDetailss($id);
    //     $amountCalculation = $this->getOrderEntryComponentItemizedd($id);
    //     $amountCalculation = $amountCalculation["data"];
    //     $Amount = [];
    //     for($i=0; $i < sizeof($amountCalculation); $i++) {
    //         //print_r($amountCalculation[$i]);
    //         $AmountValue['po_ref'] = $amountCalculation[$i][2];
    //         $AmountValue['combo'] = $amountCalculation[$i][3];
    //         $AmountValue['component'] = $amountCalculation[$i][4];
    //         $AmountValue['colour'] = $amountCalculation[$i][5];
    //         $AmountValue['spec'] = $amountCalculation[$i][7];
    //         $AmountValue['amount'] = $amountCalculation[$i][12];
    //         array_push($Amount, $AmountValue);
    //     }
    //     $getdata['calculatedAmmount'] = $Amount;
    //     $output['data'] = $result;
    //     $output['materialData'] = $materialData;
    //     $output['UOMDetails'] = $UOMDetails;
    //     $output['appendData'] = $getdata;
    //     return $output;
    // }

    // public function updateBOM2RequirementDetailss($req_data, $id) {
    //     foreach ($req_data as $key => $value)
    //     {
    //         $comboColor["enquiry_id"] = $id;
    //         $comboColor["bom_article_id"] = $value[1];
    //         $comboColor["po_enq_ref"] = $value[2];
    //         $comboColor["combo"] = $value[3];
    //         $comboColor["component"] = $value[4];
    //         $comboColor["color"] = $value[5];
    //         $comboColor["spec_code"] = $value[6];
    //         // $comboColor["item_description"] = $value[7];
    //         // $comboColor["blend"] = $value[8];
    //         // $comboColor["garment_size"] = $value[9];
    //         // $comboColor["appr_item_code"] = $value[10];
    //         // $comboColor["appr_item_colour_code"] = $value[11];
    //         // $comboColor["size_dim"] = $value[12];
    //         // $comboColor["uom"] = $value[13];
    //         $comboColor["itemized_qty"] = $value[14];
    //         $comboColor["bom_intake"] = $value[15];
    //         $comboColor["req_bom_qty"] = $value[16];
    //         $comboColor["requirement_uom"] = $value[17];
            
    //         $bom_id = $comboColor['bom_article_id'];
    //         $get_req = "SELECT * FROM tbl_bom_article_2_requirement WHERE enquiry_id='$id' AND flag=1 and bom_article_id='$bom_id'";
    //         $get_req_data = $this->db->query($get_req)->result_array();
    //         if(sizeof($get_req_data) > 0) {
    //             $this->db->where('bom_article_id', $comboColor["bom_article_id"]);
    //             unset($comboColor["enquiry_id"]);
    //             unset($comboColor["bom_article_id"]);
    //             $this->db->update('tbl_bom_article_2_requirement', $comboColor);
    //         }
    //         else {
    //             $this->db->insert('tbl_bom_article_2_requirement', $comboColor);
    //             $primaryId = $this->db->insert_id();
    //         }
    //     }
    // }
    
    // // ********** BOM 2 REQUIREMENT DETAILS ENDS HERE *********** /

    // // ********** BOM 2 REQ CONSOLIDARED APPROVAL DETAILS STARTS HERE *********** /

    // public function getBOM2ConsolidatedReqq($id) {
    //     $sql = "SELECT *, SUM(`bom_intake`) as calc_bom_qty FROM tbl_bom_article_2_requirement as a 
    //             Inner join tbl_bom_article_2 as b on a.bom_article_id = b.bom_article_id 
    //             where a.enquiry_id='$id' AND a.flag=1
    //             GROUP BY a.po_enq_ref, a.combo, a.component, a.color, a.spec_code";
    //     $data = $this->db->query($sql)->result_array();
        
    //     $sql1 = "SELECT * FROM tbl_bom_article_2_req_consld as a WHERE a.enquiry_id='$id' AND a.flag=1";
    //     $data1 = $this->db->query($sql1)->result_array();
    //     // print_r($data1);
    //     // exit();

    //     $result = [];
    //     for($i=0; $i < sizeof($data); $i++)
    //     {
    //         if( isset($data1[$i]['excess_qty'])) {
    //             $excess_qty = $data1[$i]['excess_qty'];
    //         }
    //         else {
    //             $excess_qty = "";
    //         }
            
    //         if( isset($data1[$i]['plan_bom_qty'])) {
    //             $plan_bom_qty = $data1[$i]['plan_bom_qty'];
    //         }
    //         else {
    //             $plan_bom_qty = "";
    //         }
            
    //         if( isset($data1[$i]['bom_2_req_consld_id'])) {
    //             $bom_2_req_consld_id = $data1[$i]['bom_2_req_consld_id'];
    //         }
    //         else {
    //             $bom_2_req_consld_id = "";
    //         }
            
    //         $result[$i] = ['edit', $bom_2_req_consld_id, $data[$i]['bom_2_req_id'], $data[$i]['item_description'], $data[$i]['blend'], 
    //                         $data[$i]['garment_size'], $data[$i]['appr_item_code'], $data[$i]['appr_item_colour_code'], 
    //                         $data[$i]['size_dim'], $data[$i]['uom'], $data[$i]['calc_bom_qty'], $excess_qty, 
    //                         $plan_bom_qty, $data[$i]['requirement_uom']];
    //     }

    //     // print_r($result);
    //     // exit();
        
    //     $materialData = $this->db->query('SELECT id as id, bomitemdesc as name, content, material, bomblend FROM '. KN_MASTER_BOM)->result();
        
    //     $UOM = unserialize(ARRUNITOFMEASURE);
    //     $UOMDetails = [];
    //     for($i = 1; sizeof($UOM) > $i; $i++)
    //     {
    //         $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
    //         array_push($UOMDetails, $data);
    //     }


    //     $getdata = $this->getSampleDetailss($id);
    //     $amountCalculation = $this->getOrderEntryComponentItemizedd($id);
    //     $amountCalculation = $amountCalculation["data"];
    //     $Amount = [];
    //     for($i=0; $i < sizeof($amountCalculation); $i++) {
    //         //print_r($amountCalculation[$i]);
    //         $AmountValue['po_ref'] = $amountCalculation[$i][2];
    //         $AmountValue['combo'] = $amountCalculation[$i][3];
    //         $AmountValue['component'] = $amountCalculation[$i][4];
    //         $AmountValue['colour'] = $amountCalculation[$i][5];
    //         $AmountValue['spec'] = $amountCalculation[$i][7];
    //         $AmountValue['amount'] = $amountCalculation[$i][12];
    //         array_push($Amount, $AmountValue);
    //     }
    //     $getdata['calculatedAmmount'] = $Amount;
    //     $output['data'] = $result;
    //     $output['materialData'] = $materialData;
    //     $output['UOMDetails'] = $UOMDetails;
    //     $output['appendData'] = $getdata;
    //     return $output;
    // }

    // public function updateBom2ReqConsolidatedd($req_data, $id) {
    //     foreach ($req_data as $key => $value)
    //     {
    //         $comboColor["enquiry_id"] = $id;
    //         $comboColor["bom_2_req_consld_id"] = $value[1];
    //         $comboColor["excess_qty"] = $value[11];
    //         $comboColor["plan_bom_qty"] = $value[12];
            
    //         if($value[0] == "edit" && $comboColor["bom_2_req_consld_id"] !="") {
    //             $this->db->where('bom_2_req_consld_id', $comboColor["bom_2_req_consld_id"]);
    //             $this->db->update('tbl_bom_article_2_req_consld', $comboColor);
    //         }
    //         else {
    //             unset($comboColor["bom_2_req_consld_id"]);
    //             $this->db->insert('tbl_bom_article_2_req_consld', $comboColor);
    //             $primaryId = $this->db->insert_id();
    //         }
    //     }
    // }    

    // // ********** BOM 2 REQ CONSOLIDARED APPROVAL DETAILS ENDS HERE *********** /
    
    // // ********** BOM 2 SOURCE DETAILS STARTs HERE *********** /

    // public function getBOM2Sourcingg($id) {
    //     $sql = "SELECT b.item_description, b.blend FROM tbl_bom_article_2_requirement as a 
    //             Inner join tbl_bom_article_2 as b on a.bom_article_id = b.bom_article_id 
    //             where a.enquiry_id='$id' AND a.flag=1
    //             GROUP BY a.po_enq_ref, a.combo, a.component, a.color, a.spec_code";
    //     $data = $this->db->query($sql)->result_array();
        
    //     $sql1 = "SELECT * FROM tbl_bom_2_sourcing_details as a WHERE a.enquiry_id='$id' AND a.flag=1";
    //     $data1 = $this->db->query($sql1)->result_array();
    //     // print_r($data);
    //     // exit();

    //     $result = [];
    //     for($i=0; $i < sizeof($data); $i++)
    //     {
    //         $bom_2_source_id  = "";
    //         $sourcing_advice = "";
    //         $vendor_location = "";
    //         $vendor_name_address = "";
    //         $contact_email = "";
    //         $gst = "";
    //         $online_order_sys = "";
    //         $pass_expiry_date = "";

    //         if( isset($data1[$i]['bom_1_source_id'])) {
    //             $bom_1_source_id  = $data1[$i]['bom_1_source_id'];
    //         }
            
    //         if( isset($data1[$i]['sourcing_advice'])) {
    //             $sourcing_advice = $data1[$i]['sourcing_advice'];
    //         }
            
    //         if( isset($data1[$i]['vendor_location'])) {
    //             $vendor_location = $data1[$i]['vendor_location'];
    //         }
            
    //         if( isset($data1[$i]['vendor_name_address'])) {
    //             $vendor_name_address = $data1[$i]['vendor_name_address'];
    //         }
            
    //         if( isset($data1[$i]['contact_email'])) {
    //             $contact_email = $data1[$i]['contact_email'];
    //         }
            
    //         if( isset($data1[$i]['gst'])) {
    //             $gst = $data1[$i]['gst'];
    //         }
            
    //         if( isset($data1[$i]['online_order_sys'])) {
    //             $online_order_sys = $data1[$i]['online_order_sys'];
    //         }
            
    //         if( isset($data1[$i]['pass_expiry_date'])) {
    //             $pass_expiry_date = $data1[$i]['pass_expiry_date'];
    //         }

            
    //         $result[$i] = ['edit', $bom_1_source_id, $data[$i]['item_description'], $data[$i]['blend'], 
    //                          $sourcing_advice,$vendor_location,$vendor_name_address,$contact_email,
    //                         $gst,$online_order_sys,$pass_expiry_date
    //                     ];
    //     }

    //     // print_r($result);
    //     // exit();
        
    //     $materialData = $this->db->query('SELECT id as id, bomitemdesc as name, content, material, bomblend FROM '. KN_MASTER_BOM)->result();
    //     $sourceData = unserialize(ORDERSOURCEDETAIL);

    //     $getdata = $this->getSampleDetailss($id);
    //     $amountCalculation = $this->getOrderEntryComponentItemizedd($id);
    //     $amountCalculation = $amountCalculation["data"];
    //     $Amount = [];
    //     for($i=0; $i < sizeof($amountCalculation); $i++) {
    //         //print_r($amountCalculation[$i]);
    //         $AmountValue['po_ref'] = $amountCalculation[$i][2];
    //         $AmountValue['combo'] = $amountCalculation[$i][3];
    //         $AmountValue['component'] = $amountCalculation[$i][4];
    //         $AmountValue['colour'] = $amountCalculation[$i][5];
    //         $AmountValue['spec'] = $amountCalculation[$i][7];
    //         $AmountValue['amount'] = $amountCalculation[$i][12];
    //         array_push($Amount, $AmountValue);
    //     }

        
    //     $bomVendor = $this->db->query('SELECT id as id, vendorname as name, contactpersonname, address, emailid, phone, mobile, gstno, iecode FROM '. KN_MASTER_BOM_VENDOR)->result();

    //     $getdata['calculatedAmmount'] = $Amount;
    //     $output['data'] = $result;
    //     $output['materialData'] = $materialData;
    //     $output['sourceData'] = $sourceData;
    //     $output['bomVendor'] = $bomVendor;
    //     $output['appendData'] = $getdata;
    //     return $output;
    // }
    
    // public function updateBom2Sourcingg($req_data, $id) {
    //     foreach ($req_data as $key => $value)
    //     {
    //         $comboColor["enquiry_id"] = $id;
    //         $comboColor["bom_2_source_id"] = $value[1];
    //         $comboColor["sourcing_advice"] = $value[4];
    //         $comboColor["vendor_location"] = $value[5];
    //         $comboColor["vendor_name_address"] = $value[6];
    //         $comboColor["contact_email"] = $value[7];
    //         $comboColor["gst"] = $value[8];
    //         $comboColor["online_order_sys"] = $value[9];
    //         $comboColor["pass_expiry_date"] = $value[10];
            
    //         if($value[0] == "edit" && $comboColor["bom_2_source_id"] !="") {
    //             $this->db->where('bom_2_source_id', $comboColor["bom_2_source_id"]);
    //             $this->db->update('tbl_bom_2_source_id_sourcing_details', $comboColor);
    //         }
    //         else {
    //             unset($comboColor["bom_2_source_id"]);
    //             $this->db->insert('tbl_bom_2_sourcing_details', $comboColor);
    //             $primaryId = $this->db->insert_id();
    //         }
    //     }
    // }
    
    // // ********** BOM 2 SOURCE DETAILS ENDS HERE *********** /

    // ********** ORDER ENTRY REMARKS IMAGE DETAILS STARTS HERE *********** /

    public function getRemarksNImageDetailss($data)
    {
        $this->db->where('enquiry_id', $data['enquiry_id']);
        $this->db->where('type', $data['type']);
        $query = $this->db->get('tbl_wip_remarks_disc');
        $remarksData = $query->result();

        $this->db->where('enquiry_id', $data['enquiry_id']);
        $this->db->where('type', $data['type']);
        $query2 = $this->db->get('tbl_wip_files');
        $filesData = $query2->result();

        $ourquery= $this->db->last_query();
        print_r($ourquery);
        file_put_contents("error_log", print_r($ourquery, true));

        $result['remarksData'] = $remarksData;
        $result['filesData'] = $filesData;
        return $result;
    }

    public function updateWipFormDetailss($type, $id, $filepathName, $remarks)
    {

      
        if($filepathName == ''   )
        {     
            
          
            $this->db->where('enquiry_id', $id);
            $this->db->where('type', $type);
            $query = $this->db->get('tbl_wip_remarks_disc');
            if($query->num_rows()>0) {
                $this->db->where('enquiry_id', $id);
                $this->db->where('type', $type);
                $this->db->update('tbl_wip_remarks_disc', array('remarks'=>$remarks));
                if($this->db->affected_rows() == '1')
                {
                    $result["status"] = "success";
                }
                else
                {
                    $result["status"] = "fail";
                }
                return $result;
            }
            else {
                $this->db->insert('tbl_wip_remarks_disc', array('enquiry_id'=> $id, 'type'=> $type, 'remarks'=>$remarks));
                if($this->db->affected_rows() == '1')
                {
                    $result["status"] = "success";
                }
                else
                {
                    $result["status"] = "fail";
                }
                return $result;
            }
        }
        else {
             //print_r("pavi3333333333333333");
             //print_r($filepathName);
            //print_r($remarks);
            //die;
            $this->db->where('enquiry_id', $id);
            $this->db->where('type', $type);
            $query = $this->db->get('tbl_wip_remarks_disc');
            if($query->num_rows()>0) {
                $this->db->where('enquiry_id', $id);
                $this->db->where('type', $type);
                $this->db->update('tbl_wip_remarks_disc', array('remarks'=>$remarks));
                $this->db->insert('tbl_wip_files', array('enquiry_id'=> $id, 'type'=> $type, 'image_url'=>$filepathName));
                $result["status"] = "success";
                return $result;
            }
            else {
                $this->db->insert('tbl_wip_remarks_disc', array('enquiry_id'=> $id, 'type'=> $type, 'remarks'=>$remarks));
                if($this->db->affected_rows() == '1')
                {
                    $this->db->insert('tbl_wip_files', array('enquiry_id'=> $id, 'type'=> $type, 'image_url'=>$filepathName));
                    $result["status"] = "success";
                }
                else
                {
                    $result["status"] = "fail";
                }
                return $result;
            }
        }
    }

    public function updateWipRemarksDetailss($data) {
        $this->db->where('enquiry_id', $data["enquiry_id"]);
        $this->db->where('type', $data["type"]);
        $query = $this->db->get('tbl_wip_remarks_disc');
        if($query->num_rows()>0) {
            $this->db->where('enquiry_id', $data["enquiry_id"]);
            $this->db->where('type', $data["type"]);
            $this->db->update('tbl_wip_remarks_disc', $data);
        }
        else {
            $this->db->insert('tbl_wip_remarks_disc', $data);
        }
        if($this->db->affected_rows() == '1')
        {
            $result["status"] = "success";
        }
        else
        {
            $result["status"] = "fail";
        }
        return $result;
    }

    public function deleteImageDetailss($data) {
        $this->db->where('wip_files_id', $data["id"]);
        $this->db->delete('tbl_wip_files');
        if($this->db->affected_rows() == '1')
        {
            $result["status"] = "success";
        }
        else
        {
            $result["status"] = "fail";
        }
        return $result;
    }


   public function orderupdateWipDetailss($data) {

    $this->db->where('id', $data["enquiry_id"]);
    $this->db->update('kn_order_enquiry', array(
        'season'    => $data["season"],
        'class'     => $data["class"],
        'divi_dept' => $data["dividept"],
        'sub_class' => $data["subclass"]
    ));

    if ($this->db->affected_rows() > 0) {
        return array("status" => "success");
    } else {
        return array("status" => "fail");
    }
}

    public function updateWipDetailss($data) {
        $this->db->where('enquiry_id', $data["enquiry_id"]);
		$this->db->where('type', $data["type"]);
		$query = $this->db->get('tbl_wip_remarks_disc');
		if($query->num_rows()>0) {
            $this->db->where('enquiry_id', $data["enquiry_id"]);
            $this->db->where('type', $data["type"]);
            $this->db->update('tbl_wip_remarks_disc', array('remarks'=>$data["remarks"]));
        }
        else 
        {
            $this->db->insert('tbl_wip_remarks_disc', array('enquiry_id'=> $id, 'type'=> $type, 'remarks'=>$data["remarks"]));
        }
		// if($this->db->affected_rows() == '1')
		// {
            $this->db->where('id', $data["enquiry_id"]);
            $this->db->update('kn_order_enquiry', array('season'=> $data["season"], 'class'=> $data["class"], 'divi_dept'=>$data["dividept"], 'sub_class'=>$data["subclass"]));
            if($this->db->affected_rows() == '1')
            {
                $result["status"] = "success";
            }
            else
            {
                $result["status"] = "fail 2";
            }
		// }
		// else
		// {
        //     $result["status"] = "fail";
		// }
        return $result;
    }
    // ********** ORDER ENTRY REMARKS IMAGE DETAILS ENDS HERE *********** /

    //*************** ASSORTMENT TYPE STARTS HERE *************** //

    public function getAssortmentType() {
        $sql = "SELECT * FROM tbl_assortment_type ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    public function getAssortmentDetails($id) {
        $sql = "SELECT * FROM tbl_wip_packing twp WHERE  twp.enquiry_id='$id'";
        $data = $this->db->query($sql)->result_array();
        if(sizeof($data) == 0) 
        {
            $mode = "add";
            $getsql = "SELECT a.po_size_wise_id, a.enquiry_id, a.pono_enq_refno FROM tbl_oe_po_wise as a
                       WHERE a.enquiry_id = ".$id."  group by a.pono_enq_refno";
            $data = $this->db->query($getsql)->result_array();

            // GET THE COLUMN
            $sizeChart    = $this->getSizeChart($id);
            $sizeMaster   = $this->getSizeMaster($sizeChart);

            $result_value = [];
            for($i = 0; $i < sizeof($data); $i++) 
            {
                $item = $data[$i]['pono_enq_refno'];

                $data[$i]["pck_id"] = "";
                $data[$i]["po_enq_id"] = "";
                $data[$i]["assortment_type"] = 0;
                $data[$i]["editStatus"] = "false";
                $data[$i]["enquiry_id"] = $id;
                $data[$i]["sourceColourCombos"] = $this->getPackingComboColor($id, $item);
                $data[$i]["sourceSizes"] = $sizeMaster;
                // $data[$i]["selectedCombo"] = [];
                // $data[$i]["selectedSizes"] = [];
                $data[$i]["colourCombos"] = [];
                $data[$i]["sizes"] = [];
                $data[$i]["ids"] = [];
                array_push($result_value, $data[$i]);
            }
        }
        else {

            $mode = "edit";
            // GET THE COLUMN
            $sizeChart    = $this->getSizeChart($id);
            $sizeMaster   = $this->getSizeMaster($sizeChart);

            $result_value = [];
            for($i = 0; $i < sizeof($data); $i++) 
            {
                $pck_id = $data[$i]['pck_id'];
                $type = $data[$i]['assortment_type'];
                if($type == "5" || $type == "6" || $type == "7" || $type == "8") 
                {
                    // *** GET COMBO COLOR / GARMENT SIZES FOR PO *** //
                    $getTypeCombo = "SELECT pck_combo_color_id, pck_id, table_unique, combo_color, selected_size, 
                                     group_concat(combo_color) as selected_combo, group_concat(pck_combo_color_id) as selected_id
                                     from tbl_pck_combo_color where pck_id='$pck_id' and enquiry_id='$id'  group by table_unique";
                    $getTypeCombo_data = $this->db->query($getTypeCombo)->result_array();


                    // *** GET SELECTED COMBO AND SELECTED SIZE FOR FRONTEND RENDERINGG *** //
                    $selected_combo = [];
                    $selected_size = [];
                    $selected_id = [];
                    for($j = 0; $j < sizeof($getTypeCombo_data); $j++) 
                    {
                        $a = explode(',', $getTypeCombo_data[$j]['selected_combo']);
                        $b = explode(',', $getTypeCombo_data[$j]['selected_size']);
                        $c = explode(',', $getTypeCombo_data[$j]['selected_id']);
                        array_push($selected_combo, $a);
                        array_push($selected_size, $b);
                        array_push($selected_id, $c);
                    }

                    $qwerty["pck_id"] = $data[$i]['pck_id'];
                    $qwerty["po_enq_id"] = $data[$i]['po_enq_id'];
                    $qwerty["pono_enq_refno"] = $data[$i]['po_enq'];
                    $qwerty["assortment_type"] = $data[$i]['assortment_type'];
                    $qwerty["editStatus"] = "false";
                    $qwerty["enquiry_id"] = $id;
                    $qwerty["sourceColourCombos"] = $this->getPackingComboColor($id, $data[$i]['po_enq']);
                    $qwerty["sourceSizes"] = $sizeMaster;
                    $qwerty["colourCombos"] = $selected_combo;
                    $qwerty["sizes"] = $selected_size;
                    $qwerty["ids"] = $selected_id;
                    array_push($result_value, $qwerty);
                }
                else {
                    $qwerty["pck_id"] = $data[$i]['pck_id'];
                    $qwerty["po_enq_id"] = $data[$i]['po_enq_id'];
                    $qwerty["pono_enq_refno"] = $data[$i]['po_enq'];
                    $qwerty["assortment_type"] = $data[$i]['assortment_type'];
                    $qwerty["editStatus"] = "false";
                    $qwerty["enquiry_id"] = $id;
                    $qwerty["sourceColourCombos"] = $this->getPackingComboColor($id, $data[$i]['po_enq']);
                    $qwerty["sourceSizes"] = $sizeMaster;
                    $qwerty["colourCombos"] = [];
                    $qwerty["sizes"] = [];
                    $qwerty["ids"] = [];
                    array_push($result_value, $qwerty);
                }
            }
        }
        // echo "<pre>";
        // print_r($result_value);
        // echo "</pre>";
        // exit();
        $result["data"] = $result_value;
        $result["mode"] = $mode;
        return $result;
    }

    public function getPackingComboColor($id, $item) {
        $sql = "SELECT a.pono_enq_refno, b.combo_color_id, b.colour, b.combo as po_combo 
                       FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b 
                       ON a.combo_color_id = b.combo_color_id 
                       WHERE a.enquiry_id = '$id'  and a.pono_enq_refno='$item'";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function updateAssortmentDetailss($data) {
        
        $packingData = json_decode($data['dataValue']);
        $mode = $data['mode'];
        // print_r($packingData);
        // exit();
        for($i=0;$i<sizeof($packingData);$i++)
        {
            $enquiry_id = $packingData[$i]->enquiry_id;
            $po_enq_id = $packingData[$i]->po_enq_id;
            $po_enq = $packingData[$i]->pono_enq_refno;
            $assortment_type = $packingData[$i]->assortment_type;
            $pck_id = $packingData[$i]->pck_id;

            if($mode == "add") {

                $this->db->select('*');
                $ArrRes = $this->db->get_where('tbl_wip_packing', ['enquiry_id' => $enquiry_id, 'po_enq' => $po_enq, ]);
                $getres = $ArrRes->result_array();
                // print_r($getres);
                if(sizeof($getres) > 0) {
                    $pck_id = $getres[0]['pck_id'];
                }
                else {
                    $this->db->insert('tbl_wip_packing', array('enquiry_id'=> $enquiry_id, 'po_enq_id'=> $po_enq_id,
                    'po_enq'=>$po_enq, 'assortment_type'=> $assortment_type));
                    $pck_id = $this->db->insert_id();
                }

            }
            else if($mode == "edit") {
                $pck_sql = "SELECT * FROM tbl_wip_packing WHERE pck_id='$pck_id'";
                $pck_data = $this->db->query($pck_sql)->result_array();

                if(sizeof($pck_data) > 0) {
                    if($pck_data[0]['assortment_type'] !=  $assortment_type) {
                        $assrt = $pck_data[0]['assortment_type'];
                        if($assrt == "1" || $assrt == "2" || $assrt == "3" || $assrt == "4") 
                        {
                            $this->db->where('enquiry_id', $enquiry_id);
                            $this->db->where('pck_id', $pck_id);
                            $this->db->update('tbl_packing_assortment_details', array('flag'=> 0));
                            // $this->db->delete('tbl_packing_assortment_details');
                        }
                        else if($assrt == "5" || $assrt == "6" || $assrt == "7" || $assrt == "8") 
                        {
                            $this->db->where('enquiry_id', $enquiry_id);
                            $this->db->where('pck_id', $pck_id);
                            $this->db->update('tbl_pck_combo_color', array('flag'=> 0));
                            // $this->db->delete('tbl_pck_combo_color');
                        }
                    }
                }
                $this->db->where('pck_id', $pck_id);
                $this->db->update('tbl_wip_packing', array('assortment_type'=> $assortment_type));
            }

            // exit();

            if($pck_id != "" || $pck_id != "0")
            {
                if($assortment_type == "5" || $assortment_type == "6" || $assortment_type == "7" || $assortment_type == "8") 
                {
                    $uniqueId = $packingData[$i]->uniqueId;
                    $names = $packingData[$i]->names;
                    $sizes = $packingData[$i]->sizes;
                    $ids = $packingData[$i]->pck_combo_color_id;
                    $selected_size = implode(',', $sizes);

                    
                    //  *** CHECK UPDATE AND INSERT HERE *** //
                    if(sizeof($ids) > 0) {
                        $pck_color_ids = $ids[$i];
                        if(sizeof($pck_color_ids) > 0) 
                        {
                            // ** CALL FOR EDIT FUNCTION ** //
                            for($j=0; $j<sizeof($names); $j++)
                            {
                                $updateValue["table_unique"] = $uniqueId;
                                $updateValue["combo_color"] = $names[$j];
                                $updateValue["selected_size"] = $selected_size;
                                $updateValue["pck_combo_color_id"] = $pck_color_ids[$j];
                                // print_r($updateValue);

                                $this->db->where('enquiry_id', $enquiry_id);
                                $this->db->where('pck_id', $pck_id);
                                $this->db->where('pck_combo_color_id', $updateValue["pck_combo_color_id"]);
                                unset($updateValue["pck_combo_color_id"]);
                                $this->db->update('tbl_pck_combo_color', $updateValue);
                            }
                        }
                        else {
                            // ** CALL FOR ADD FUNCTION ** //
                            for($j=0; $j<sizeof($names); $j++)
                            {
                                $updateInsertValue["pck_id"] = $pck_id;
                                $updateInsertValue["enquiry_id"] = $enquiry_id;
                                $updateInsertValue["table_unique"] = $uniqueId;
                                $updateInsertValue["combo_color"] = $names[$j];
                                $updateInsertValue["selected_size"] = $selected_size;
                                // print_r($updateInsertValue);
                                
                                $this->db->insert('tbl_pck_combo_color', $updateInsertValue);
                            }
                        }
                        
                    }
                    else {
                        // ** CALL FOR ADD FUNCTION ** //
                        for($j=0; $j<sizeof($names); $j++)
                        {
                            $insertValue["pck_id"] = $pck_id;
                            $insertValue["enquiry_id"] = $enquiry_id;
                            $insertValue["table_unique"] = $uniqueId;
                            $insertValue["combo_color"] = $names[$j];
                            $insertValue["selected_size"] = $selected_size;
                            // print_r($updateInsertValue);
                            
                            $this->db->insert('tbl_pck_combo_color', $insertValue);
                        }
                    }
                }
                $result["status"] = "success";
            }
            else
            {
                $result["status"] = "fail";
            }
            
        }
        return $result;
    }

    public function deleteAssortEntryy($ids) {
        $id_value = explode(',', $ids);
        // print_r($id_value);
        foreach ($id_value as $key => $value)
        {
            $this->db->where('pck_combo_color_id', $value);
            $this->db->update('tbl_pck_combo_color', array('flag'=> 0));
        }
        $result["status"] = "success";
        $result["statusCode"] = "200";
    }
    
    //*************** ASSORTMENT TYPE ENDS HERE *************** //

    //*************** PACKING STARTS HERE *************** //

    public function getPackingDetailss($id) {

        $this->db->select('a.*, b.type as assort_type_name');
        $this->db->from('tbl_wip_packing a');
        $this->db->join('tbl_assortment_type b', 'a.assortment_type=b.assortment_id', 'inner');
        $this->db->where('a.enquiry_id', $id);
        $this->db->where('a.flag', 1);
        $query = $this->db->get();
        $data = $query->result_array();

        for($i=0; $i<sizeof($data); $i++) 
        {
            $type = $data[$i]["assortment_type"];
            $po_enq = $data[$i]["po_enq"];
            // *** DATA AND COLUMN BASED ON ASSORTMENT TYPE *** //
            
            if($type == "1" || $type == "2" || $type == "3" || $type == "4") 
            {
                $getAssort_column = $this->getAssortmentColumn($type, $id, $po_enq, $unique=0);
                $get_data = $this->getAssortmentData($type, $id, $po_enq, $data);
                $data[$i]["column"] = $getAssort_column;
                $data[$i]["data"] = $get_data;
            }
            else {
                // $data[$i]["column"] = [];
                // $data[$i]["data"] = [];
                $get_data = $this->getAssortmentData($type, $id, $po_enq, $data);
                $data[$i]["packingTableData"] = $get_data;
            }
        }

        $result['packingDetails'] = $data;
        return $result;
    }

     public function getPackingDetailsmails($id) {

       // $sql = "SELECT * FROM tbl_checklist as tcl LEFT JOIN tbl_management_checklist as tmcl ON tcl.checklist_id=tmcl.checklist_id WHERE tmcl.enquiry_id='$id' AND tmcl.flag=1";
        $sql = "SELECT id as checklist_id, mail_description FROM tbl_checklist WHERE status=1";
        $checkList = $this->db->query($sql)->result_array();

         $orderentry_data = $this->WorkInProcessModel->getorderentrycompanydetail($id);
         $orderrefnumber=$orderentry_data[0]['orderenqrefno'];

          $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
          $user_id=$this->userid = $ArrUserLoggedInfo['id'];
          $ArrObjsubscriber_id = $this->commonmodel->getsubscriberid($user_id);
          $username=$ArrObjsubscriber_id->contactname;
          $cdate=date("Y-m-d h:i:s");

          //print_r($username);



          $sql1 = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
          $data1 = $this->db->query($sql1)->result_array();

             $po_list = [];

         foreach ($data1 as $row) {
         $po_list[] = $row['pono_enq_refno'];  // <-- collect PO numbers
         }
         $po_list = array_unique($po_list);
         $po_list = array_values($po_list);
         
        

        $finalVal = [];

        // print_r(sizeof($checkList));
            $serial = 1;
        for ($i=0; $i < sizeof($checkList); $i++) { 
            # code...
            $checkListId = $checkList[$i]['checklist_id'];
            $sql2 = "SELECT * FROM tbl_management_checklist WHERE enquiry_id='$id' AND checklist_id='$checkListId'   AND req_sent_for_missing_details = '1' ";
            $list = $this->db->query($sql2)->result_array();
             //print_r(sql2)

             if(sizeof($list) == 1)
            {
                if( $list[0]['req_sent_for_missing_details'] == '1') {
                    $checkid=$list[0]['management_checklist_id'];
                    //$list = [ $checkList[$i]['checklist_id'], $checkList[$i]['mail_description']];
                     $list = [ $serial, $checkList[$i]['mail_description']];
                     $this->db->where('management_checklist_id', $checkid);
    $this->db->update('tbl_management_checklist', [
        'is_send' => 1,      // <-- update column
        'req_date' => date('Y-m-d h:i:s')  // optional
    ]);
           $serial++; // increment
                    
                }
                
                array_push($finalVal, $list);
          }
        }

        $output['data'] = $finalVal;
        $output['po_list'] = $po_list;
        $output['orderrefnumber'] = $orderrefnumber;
         $output['username'] = $username;
         $output['currentdate'] = $cdate;

       // PRINT_R($output);
        return $output;
    }

    public function getAssortmentColumn($type, $id, $po_enq, $unique) {

        if($type == "1" || $type == "2" || $type == "3" || $type == "4") {

            // ****** GET THE COLUMN ****** 
            $sizeChart    = $this->getSizeChart($id);
            $sizeMaster   = $this->getSizeMaster($sizeChart);
            foreach ($sizeMaster as $key => $value)
            {
                $size[] = ['title' => $value['size_name'], 'width' => 20, 'align' => 'right'];
            }
            
            // ****** GET THE COMBO SOURCE ****** 
            $sql = "SELECT b.colour, b.combo as name, b.combo as id FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b 
                        ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = '$id'  and a.pono_enq_refno='$po_enq'";
            $comboData = $this->db->query($sql)->result_array();


            if($type == "1" || $type == "3") {

                $assortCoulmn = [
                    ['title' => "mode", 'width' => '10%', 'align' => 'center', 'type'=> 'hidden'],
                    ['title' => "id", 'width' => '10%', 'align' => 'center', 'type'=> 'hidden'],
                    ['title' => "Combo / Color", 'width' => '10%', 'align' => 'left', 'type' => 'dropdown', 'source' => $comboData],
                ];

                $finalData = [
                    ['title' => "No. of item \n Per Master", 'width' => '10%', 'align'=> 'right'],
                    ['title' => "No. of Master \n Bag Per Carton", 'width' => '10%', 'align'=> 'right'],
                    ['title' => "Total No. of \n Item Per Carton", 'width' => '10%', 'align'=> 'right'],
                ];

                $assortCoulmn = array_merge($assortCoulmn, $size);
                $assortCoulmn = array_merge($assortCoulmn, $finalData);
            }
            else if($type == "2" || $type == "4") {
                
                $assortCoulmn = [
                    ['title' => "mode", 'width' => '10%', 'align' => 'center', 'type'=> 'hidden'],
                    ['title' => "id", 'width' => '10%', 'align' => 'center', 'type'=> 'hidden'],
                    ['title' => "Combo / Color", 'width' => '10%', 'align' => 'left', 'type' => 'dropdown', 'source' => $comboData],
                ];

                $finalData = [
                    ['title' => "Total No. of \n Item Per", 'width' => '10%', 'align'=> 'left'],
                ];

                $assortCoulmn = array_merge($assortCoulmn, $size);
                $assortCoulmn = array_merge($assortCoulmn, $finalData);
            }
        }
        else {
            // ****** GET THE TABLE SIZES ****** 
            $getsql = "SELECT * FROM tbl_wip_packing as a inner join tbl_pck_combo_color as b on a.pck_id=b.pck_id 
                               WHERE a.enquiry_id = '$id'  and a.assortment_type='$type' and b.table_unique ='$unique'";
            $getdata = $this->db->query($getsql)->result_array();

            if($type == "5" || $type == "6" || $type == "7" || $type == "8") {
                $assortCoulmn = [
                    ['title' => "mode", 'width' => '10%', 'align' => 'center', 'type'=> 'hidden'],
                    ['title' => "id", 'width' => '10%', 'align' => 'center', 'type'=> 'hidden'],
                    ['title' => "Combo / Color", 'width' => '30%', 'align' => 'left', 'readOnly' => true],
                ];
                
                if(sizeof($getdata) > 0) 
                {    
                    $size_data = explode(',', $getdata[0]['selected_size']);
                    foreach ($size_data as $key => $value1)
                    {
                        $sizes[] = ['title' => $value1, 'width' => 20, 'align' => 'right']; 
                    }
                    $assortCoulmn = array_merge($assortCoulmn, $sizes);
                }  
                
                // ****** IF ASSORT TYPE 7 AND 8 *****
                if($type == "7") {
                    $finalData = [
                        ['title' => "No. of item \n Per Master1", 'width' => '10%', 'align'=> 'right', 'readOnly' => true],
                        ['title' => "No. of Master \n Bag Per Carton2", 'width' => '10%', 'align'=> 'right'],
                        ['title' => "Total No. of \n Item Per Carton", 'width' => '10%', 'align'=> 'right', 'readOnly' => true],
                    ];
                    $assortCoulmn = array_merge($assortCoulmn, $finalData);
                }
                else if($type == "8") {
                    $finalData = [
                        ['title' => "Total No. of \n Item Per", 'width' => '10%', 'align'=> 'left', 'readOnly' => true],
                    ];
                    $assortCoulmn = array_merge($assortCoulmn, $finalData);
                }
            }
            else {
                $assortCoulmn = [
                    ['title' => "mode", 'width' => '10%', 'align' => 'center', 'type'=> 'hidden'],
                    ['title' => "id", 'width' => '10%', 'align' => 'center', 'type'=> 'hidden'],
                    ['title' => "Combo / Color", 'width' => '10%', 'align' => 'left', 'readOnly' => true],
                ];
            }
        }

        return $assortCoulmn;
    }

    public function getAssortmentData($type, $id, $po_enq, $assortData) {

        if($type == "1" || $type == "2" || $type == "3" || $type == "4") {

            // ****** GET THE TABLE SIZES ****** 
            $sql = "SELECT * FROM tbl_wip_packing as a inner join tbl_packing_assortment_details as b 
                    on a.pck_id=b.pck_id WHERE a.enquiry_id = '$id'  and a.assortment_type='$type'
                    and a.po_enq = '$po_enq'";
            $data = $this->db->query($sql)->result_array();

            $result = [];
            foreach ($data as $key => $value)
            {
                $result[$key] = ['edit', $value['pck_assortment_details_id'], $value['combo_color']];
                $size_data = explode(',', $value['sizes']);
                for($j = 0; sizeof($size_data) > $j; $j++)
                {
                    array_push($result[$key], $size_data[$j]);
                }

                if($type == "1" || $type == "3") {
                    array_push($result[$key], $value['no_item_per_master']);
                    array_push($result[$key], $value['no_mast_bag_per_carton']);
                }
                array_push($result[$key], $value['total_item']);
            }
        }
        else if($type == "5" || $type == "6" || $type == "7" || $type == "8") {
                // ****** GET THE TABLE SIZES ****** 
                $sql = "SELECT * FROM tbl_wip_packing as a inner join tbl_pck_combo_color as b 
                on a.pck_id=b.pck_id WHERE a.enquiry_id = '$id'  and assortment_type='$type' group by b.table_unique";
                $data = $this->db->query($sql)->result_array();

                $result = [];
                for($i=0; $i<sizeof($data); $i++) 
                {
                    $unique = $data[$i]['table_unique'];
                    $getsql = "SELECT * FROM tbl_wip_packing as a inner join tbl_pck_combo_color as b on a.pck_id=b.pck_id 
                               WHERE a.enquiry_id = '$id'  and assortment_type='$type' and b.table_unique ='$unique'";
                    $getdata = $this->db->query($getsql)->result_array();
                    // print_r($getdata);
                    $uniqueResullt = [];
                    for($j=0; $j<sizeof($getdata); $j++) 
                    {
                        $assortData[0]['pck_combo_color_id'] = $getdata[$j]['pck_combo_color_id'];
                        $uniqueResullt['column'] = $this->getAssortmentColumn($type, $id, $po_enq, $unique);
                        $uniqueResullt['details'] = $assortData[0];
                        $uniqueDataResult = [];
                        // print_r($getdata);
                        foreach ($getdata as $key => $value)
                        {
                            $uniqueDataValue[$key] = ['edit', $value['pck_combo_color_id'], $value['combo_color']];
                            $selectedSize = explode(',', $value['selected_size']);
                            $size_data = explode(',', $value['sizes']);
                            
                            for($k = 0; sizeof($selectedSize) > $k; $k++)
                            {
                                if(isset($size_data[$k])) {
                                    array_push($uniqueDataValue[$key], $size_data[$k]);
                                }
                                else {
                                    array_push($uniqueDataValue[$key], '0');
                                }
                                
                            }

                            if($type == "7") {
                                array_push($uniqueDataValue[$key], $value['no_item_per_master']);
                                array_push($uniqueDataValue[$key], $value['no_mast_bag_per_carton']);
                                array_push($uniqueDataValue[$key], $value['total_item']);
                            } 
                            else if($type == "8") {
                                array_push($uniqueDataValue[$key], $value['total_item']);
                            } 
                            array_push($uniqueDataResult, $uniqueDataValue[$key]);
                        }

                        // *** FOOTER STARTS HERE *** //
                        if($type == "5") {
                            $footer_1 = ['footer', '0', 'No. of Item Per Master Bag:'];
                            // $footer_2 = ['editinput', '0', 'No. of Master Bag Per Carton:'];
                            $footer_2 = [];
                            foreach ($getdata as $key => $value)
                            {
                                $cartonData[$key] = ['editinput', $value['pck_combo_color_id'], "No. of Master Bag Per Carton:"];
                                $size_data = explode(',', $value['no_mast_bag_per_carton']);
                                for($k = 0; sizeof($size_data) > $k; $k++)
                                {
                                    array_push($cartonData[$key], $size_data[$k]);
                                }
                                array_push($footer_2, $cartonData[$key]);
                                break;
                            }
                            
                            $footer_3 = ['footer', '0', 'Total No. of Item Per Carton:'];
                            array_push($uniqueDataResult, $footer_1);
                            if(sizeof($footer_2) > 0) {
                                array_push($uniqueDataResult, $footer_2[0]);
                            }
                            else {
                                $footer_2 = ['footer', '0', 'No. of Master Bag Per Carton:'];
                                array_push($uniqueDataResult, $footer_2);
                            }
                            array_push($uniqueDataResult, $footer_3);
                        }
                        else if($type == "6") {
                            $footer_3 = ['footer', '0', 'Total No. of Item Per Carton:'];
                            array_push($uniqueDataResult, $footer_3);
                        }
                        
                        
                        $uniqueResullt['data'] = $uniqueDataResult;
                    }
                    array_push($result, $uniqueResullt);
                }
        }
        else {
            $result = [];
        }

        return $result;
    }

    public function updatePackingDetailss($req_data, $id, $type, $packing_id, $pck_combo_color_id) {
        // print_r($data);
        // echo $type;

        if($type == '1' || $type == '2' || $type == '3' || $type == '4' ) 
        {
            foreach ($req_data as $key => $value)
            {
                $length = sizeof($value);
                $comboColor["pck_assortment_details_id"] = $value[1];
                $comboColor["combo_color"] = $value[2];
                $comboColor["enquiry_id"] = $id;
                $comboColor["pck_id"] = $packing_id;
                
                $size_data = [];
                if($type == '1' || $type == '3') 
                {
                    for($i = 0; $i < $length; $i++) {
                        if ($i >= 3 && $i < $length - 3) 
                        {
                            array_push($size_data, $value[$i]);
                        }
                    }
                    $comboColor["sizes"] = implode(',', $size_data);
                    $comboColor["no_item_per_master"] = $value[$length - 3];
                    $comboColor["no_mast_bag_per_carton"] = $value[$length - 2];
                }
                else if($type == '2' || $type == '4') 
                {
                    for($i = 0; $i < $length; $i++) {
                        if ($i >= 3 && $i < $length - 1) 
                        {
                            array_push($size_data, $value[$i]);
                        }
                    }
                    $comboColor["sizes"] = implode(',', $size_data);
                }
                $comboColor["total_item"] = $value[$length - 1];

                if($value[0] == "edit") { 
                    $this->db->where('pck_assortment_details_id', $comboColor["pck_assortment_details_id"]);
                    unset($comboColor["pck_assortment_details_id"]);
                    unset($comboColor["enquiry_id"]);
                    $this->db->update('tbl_packing_assortment_details', $comboColor);
                }
                else {
                    unset($comboColor["pck_assortment_details_id"]);
                    $this->db->insert('tbl_packing_assortment_details', $comboColor);
                    // $primaryId = $this->db->insert_id();
                }
            }
            $result["status"] = "success";
            $result["statusCode"] = "200";
        }
        else if($type == '5' || $type == '6' || $type == '7' || $type == '8' ) 
        {
            foreach ($req_data as $key => $value)
            {
                $length = sizeof($value);
                $comboColor["pck_combo_color_id"] = $value[1];
                $comboColor["combo_color"] = $value[2];
                
                $size_data = [];
                if($type == '5' || $type == '6') 
                {
                    for($i = 0; $i < $length; $i++) {
                        if ($i >= 3) 
                        {
                            array_push($size_data, $value[$i]);
                        }
                    }
                    $comboColor["sizes"] = implode(',', $size_data);
                }
                else if($type == '7') {
                    for($i = 0; $i < $length; $i++) {
                        if ($i >= 3 && $i < $length-3) 
                        {
                            array_push($size_data, $value[$i]);
                        }
                    }
                    $comboColor["sizes"] = implode(',', $size_data);
                }
                else if($type == '8') {
                    for($i = 0; $i < $length; $i++) {
                        if ($i >= 3 && $i < $length-1) 
                        {
                            array_push($size_data, $value[$i]);
                        }
                    }
                    $comboColor["sizes"] = implode(',', $size_data);
                }

                if($value[0] == "edit") { 
                    // print_r($comboColor);
                    if($type == '7') {
                        $comboColor["no_mast_bag_per_carton"] = $value[$length - 2];
                    }
                    $this->db->where('pck_combo_color_id', $comboColor["pck_combo_color_id"]);
                    unset($comboColor["pck_combo_color_id"]);
                    $this->db->update('tbl_pck_combo_color', $comboColor);
                }
                else if($value[0] == "editinput") {

                    $insertcomboColor["no_mast_bag_per_carton"] = $comboColor["sizes"];
                    $insertcomboColor["pck_combo_color_id"] = $comboColor["pck_combo_color_id"];

                    $this->db->where('pck_combo_color_id', $comboColor["pck_combo_color_id"]);
                    unset($insertcomboColor["pck_combo_color_id"]);
                    $this->db->update('tbl_pck_combo_color', $insertcomboColor);
                }
            }
            $result["status"] = "success";
            $result["statusCode"] = "200";
        }
        return $result;
    }
    //*************** PACKING ENDS HERE *************** //

    // ********** MANAGEMENT CHECKLIST STARTS HERE *********** /
    
    public function getManagementChecklistDetailss($id) {
    $masterAdmins = $this->master_loginid();
    $subscriber_ids = array_column($masterAdmins, 'subscriber_id');

             //$sql = "SELECT id as checklist_id, description FROM tbl_checklist WHERE status=1  ";

    $sql = "SELECT b.*, b.id as checklist_id,b.description AS name FROM tbl_checklist AS b LEFT JOIN " . KN_USERS . " AS u ON b.updatedby = u.id
             WHERE u.subscriber_id IN (" . implode(',', array_map('intval', $subscriber_ids)) . ") AND b.status = 1 ";
        
        
        $checkList = $this->db->query($sql)->result_array();

        $finalVal = [];

        // print_r(sizeof($checkList));

        for ($i=0; $i < sizeof($checkList); $i++) { 
            # code...
            $checkListId = $checkList[$i]['checklist_id'];
            $sql2 = "SELECT * FROM tbl_management_checklist WHERE enquiry_id='$id' AND checklist_id='$checkListId' ";
            $list = $this->db->query($sql2)->result_array();
             //return $list;
            if(sizeof($list) == 1)
            {
                if($list[0]['NA'] == '0' && $list[0]['NR'] == '0' && $list[0]['PR'] == '0' && $list[0]['record'] == '0' && $list[0]['req_sent_for_missing_details'] == '0') {
                    $list = ['edit', $checkList[$i]['checklist_id'], $checkList[$i]['description'], $list[0]['NA'], $list[0]['NR'], $list[0]['PR'], 
                    $list[0]['record'], $list[0]['req_sent_for_missing_details'], $list[0]['req_date'], $list[0]['receive_date'], $list[0]['remarks'],$list[0]['is_send'],$list[0]['is_save']];
                }
                else {
                    $list = ['edit', $checkList[$i]['checklist_id'], $checkList[$i]['description'], $list[0]['NA'], $list[0]['NR'], $list[0]['PR'], 
                    $list[0]['record'], $list[0]['req_sent_for_missing_details'], $list[0]['req_date'], $list[0]['receive_date'],$list[0]['remarks'],$list[0]['is_send'],$list[0]['is_save']];
                }
                array_push($finalVal, $list);
            }
            else {
                $list = ['', $checkList[$i]['checklist_id'], $checkList[$i]['description'], '','', '', '', '', '', '', '', ''];
                array_push($finalVal, $list);
            }
        }

        $output['data'] = $finalVal;
        return $output;
    }
    
    public function updateManagementCheckListt($req_data, $id) {        
        foreach ($req_data as $key => $value)
        {
            $checkList["enquiry_id"] = $id;
            $checkList["checklist_id"] = $value[1];
            $checkList["NA"] = $value[3];
            $checkList["NR"] = $value[4];
            $checkList["PR"] = $value[5];
            $checkList["record"] = $value[6];
            $checkList["req_sent_for_missing_details"] = $value[7];
            $checkList["req_date"] = $value[8];
            $checkList["is_save"] = 1;
        
            if ($value[6] == 1) {
      $checkList["receive_date"] = date('Y-m-d H:i:s');   // update receive_date
           } else {
       $checkList["receive_date"] = $value[9];                 // update req_date from index 9
         }
            //$checkList["receive_date"] = $value[9];
            $checkList["remarks"] = $value[10];
            if($value[0] == "edit" && $checkList["checklist_id"] !="") {
                $this->db->where('enquiry_id', $checkList["enquiry_id"]);
                $this->db->where('checklist_id', $checkList["checklist_id"]);
                $this->db->update('tbl_management_checklist', $checkList);
                
            }
            else {
                $this->db->insert('tbl_management_checklist', $checkList);
            }
        }
    }

    // ********** MANAGEMENT CHECKLIST ENDS HERE *********** /

    ///********** Document shipping STANDARD STARTS HERE *********** /
    public function getshippingDetailsOfDocument($id) {
        // $sql = "SELECT * FROM tbl_final_inspection_standard as tfis INNER JOIN tbl_oe_po_wise as topw ON tfis.po_size_wise_id=topw.po_size_wise_id WHERE tfis.enquiry_id='$id' AND tfis.flag=1";
        $sql = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $poWise = $this->db->query($sql)->result_array();

        $finalVal = [];
        
       
        $userNames = array();
        foreach($poWise as $key => $value) {
            $check = [$value['pono_enq_refno'], $value["pcs_set"]];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);

                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'].' / '.$value['combo'];
                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'];

                $poWise[$old_key]['po_qty'] = (int)$poWise[$old_key]['po_qty'] + (int)$value['po_qty'];
                $poWise[$old_key]['po_qty'] = $poWise[$old_key]['po_qty'];

                unset($poWise[$key]);
            }
            $userNames[] = $check;
        }

        // return $poWise;
        
        $poWise = array_values($poWise);

        for ($i=0; $i < sizeof($poWise); $i++) { 
            
            $qty = $poWise[$i]['po_qty'];

           
            $poSizeWiseId = $poWise[$i]['po_size_wise_id'];
            $sql2 = "SELECT * FROM tbl_shipping_document WHERE enquiry_id='$id' AND po_size_wise_id='$poSizeWiseId' ";
            $list = $this->db->query($sql2)->result_array();
            // return $list;
            if(sizeof($list) == 1)
            {
                $list = ['edit', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], 
                $list[0]['shipment_qty'] ,$poWise[$i]['pcs_set'], $list[0]['Invoice_no'], $list[0]['inv_Date'], $list[0]['packing_list'], 
                $list[0]['e_invoice_no'], $list[0]['invoice_date'], $list[0]['e_way_Bill_no'], $list[0]['e_way_date'], $list[0]['insurance_policy_no'], 
                $list[0]['insurance_policy_date'], $list[0]['enquiry_id']];
                array_push($finalVal, $list);
            }
            else {
                $list = ['', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], '', 
                $poWise[$i]['pcs_set'], '', '', '', '', '','', '', '', ''];
                array_push($finalVal, $list);
            }
        }

        $output['data'] = $finalVal;
        return $output;
    }

    // public function getshippingDetailsOfDocument($id) {
    //     // $sql = "SELECT * FROM tbl_final_inspection_standard as tfis INNER JOIN tbl_oe_po_wise as topw ON tfis.po_size_wise_id=topw.po_size_wise_id WHERE tfis.enquiry_id='$id' AND tfis.flag=1";
    //     $sql = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
    //     $poWise = $this->db->query($sql)->result_array();

    //     $finalVal = [];

    //        $porefnumber = [];
        
       
    //     $userNames = array();
    //     foreach($poWise as $key => $value) {
    //         $check = [$value['pono_enq_refno'], $value["pcs_set"]];
    //         if(!empty($userNames) && in_array($check, $userNames)) {
    //             $old_key = array_search($check, $userNames);

    //             $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'].' / '.$value['combo'];
    //             $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'];

    //             $poWise[$old_key]['po_qty'] = (int)$poWise[$old_key]['po_qty'] + (int)$value['po_qty'];
    //             $poWise[$old_key]['po_qty'] = $poWise[$old_key]['po_qty'];

    //             unset($poWise[$key]);
    //         }
    //         $userNames[] = $check;

    //           array_push($porefnumber, $value['pono_enq_refno']);
    //           $porefnumber = array_values(array_unique($porefnumber));
    //     }

    //     // return $poWise;
        
    //     $poWise = array_values($poWise);

    //     print_r($poWise);

    //     for ($i=0; $i < sizeof($poWise); $i++) { 
            
    //         $qty = $poWise[$i]['po_qty'];

           
    //         $poSizeWiseId = $poWise[$i]['po_size_wise_id'];
    //         $sql2 = "SELECT * FROM tbl_shipping_document WHERE enquiry_id='$id' AND po_size_wise_id='$poSizeWiseId' ";
    //         $list = $this->db->query($sql2)->result_array();
    //         // return $list;
    //         if(sizeof($list) == 1)
    //         {
    //             $list = ['edit', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], 
    //             $list[0]['shipment_qty'] ,$poWise[$i]['pcs_set'], $list[0]['Invoice_no'], $list[0]['inv_Date'], $list[0]['packing_list'], 
    //             $list[0]['e_invoice_no'], $list[0]['invoice_date'], $list[0]['e_way_Bill_no'], $list[0]['e_way_date'], $list[0]['insurance_policy_no'], 
    //             $list[0]['insurance_policy_date'], $list[0]['enquiry_id']];
    //             array_push($finalVal, $list);
    //         }
    //         else {
    //             $list = ['', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], '', 
    //             $poWise[$i]['pcs_set'], '', '', '', '', '','', '', '', ''];
    //             array_push($finalVal, $list);
    //         }
    //     }

    //     $output['data'] = $finalVal;
    //     $output['porefnumber'] = $porefnumber;
    //     return $output;
    //     print_r($output);
    // }

    public function getroadDetailsOfDocument($id) {
        // $sql = "SELECT * FROM tbl_final_inspection_standard as tfis INNER JOIN tbl_oe_po_wise as topw ON tfis.po_size_wise_id=topw.po_size_wise_id WHERE tfis.enquiry_id='$id' AND tfis.flag=1";
        $sql = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $poWise = $this->db->query($sql)->result_array();

        $finalVal = [];
        
       
        $userNames = array();
        foreach($poWise as $key => $value) {
            $check = [$value['pono_enq_refno'], $value["pcs_set"]];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);

                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'].' / '.$value['combo'];
                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'];

                $poWise[$old_key]['po_qty'] = (int)$poWise[$old_key]['po_qty'] + (int)$value['po_qty'];
                $poWise[$old_key]['po_qty'] = $poWise[$old_key]['po_qty'];

                unset($poWise[$key]);
            }
            $userNames[] = $check;
        }

        // return $poWise;
        
        $poWise = array_values($poWise);

        for ($i=0; $i < sizeof($poWise); $i++) { 
            
            $qty = $poWise[$i]['po_qty'];

           
            $poSizeWiseId = $poWise[$i]['po_size_wise_id'];
            $sql2 = "SELECT * FROM tbl_road_transport_detail WHERE enquiry_id='$id' AND po_size_wise_id='$poSizeWiseId' ";
            $list = $this->db->query($sql2)->result_array();
            // return $list;
            if(sizeof($list) == 1)
            {
                $list = ['edit', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], 
                $list[0]['shipment_qty'] ,$poWise[$i]['pcs_set'], $list[0]['transport_name'], $list[0]['transport_address'], $list[0]['vehicle_no'], 
                $list[0]['driver_name'], $list[0]['driver_mobile_no'], $list[0]['connect_port_name'], $list[0]['vehical_start_date_time'], $list[0]['goods_hand_date_time'], 
                ];
                array_push($finalVal, $list);
            }
            else {
                $list = ['', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], '', 
                $poWise[$i]['pcs_set'], '', '', '', '', '','', '', '', ''];
                array_push($finalVal, $list);
            }
        }

        $output['data'] = $finalVal;
        return $output;
    }


     public function getattachmentDetailsOfDocument($id) {
        // $sql = "SELECT * FROM tbl_final_inspection_standard as tfis INNER JOIN tbl_oe_po_wise as topw ON tfis.po_size_wise_id=topw.po_size_wise_id WHERE tfis.enquiry_id='$id' AND tfis.flag=1";
        $sql = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $poWise = $this->db->query($sql)->result_array();

        $finalVal = [];
        
       
        $userNames = array();
        foreach($poWise as $key => $value) {
            $check = [$value['pono_enq_refno'], $value["pcs_set"]];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);

                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'].' / '.$value['combo'];
                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'];

                $poWise[$old_key]['po_qty'] = (int)$poWise[$old_key]['po_qty'] + (int)$value['po_qty'];
                $poWise[$old_key]['po_qty'] = $poWise[$old_key]['po_qty'];

                unset($poWise[$key]);
            }
            $userNames[] = $check;
        }

        // return $poWise;
        
        $poWise = array_values($poWise);

        for ($i=0; $i < sizeof($poWise); $i++) { 
            
            $qty = $poWise[$i]['po_qty'];

           
            $poSizeWiseId = $poWise[$i]['po_size_wise_id'];
            $sql2 = "SELECT * FROM document_attachment_detail WHERE enquiry_id='$id' AND po_size_wise_id='$poSizeWiseId' ";
            $list = $this->db->query($sql2)->result_array();
            // return $list;
            if(sizeof($list) == 1)
            {
                $list = ['edit', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], 
                $poWise[$i]['pcs_set'], $list[0]['buyer_orgianl'], $list[0]['po_wise_packing'], $list[0]['Buyer_commet'], 
                ];
                array_push($finalVal, $list);
            }
            else {
                $list = ['', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], 
                $poWise[$i]['pcs_set'], '', '', ''];
                array_push($finalVal, $list);
            }
        }

        $output['data'] = $finalVal;
        return $output;
    }

    //*******************Document shipping STANDARD STARTS HERE***************/

     public function getcertificationDetailsOfDocuments($id) {
        // $sql = "SELECT * FROM tbl_final_inspection_standard as tfis INNER JOIN tbl_oe_po_wise as topw ON tfis.po_size_wise_id=topw.po_size_wise_id WHERE tfis.enquiry_id='$id' AND tfis.flag=1";
        $sql = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $poWise = $this->db->query($sql)->result_array();

        $finalVal = [];
        
       
        $userNames = array();
        foreach($poWise as $key => $value) {
            $check = [$value['pono_enq_refno'], $value["pcs_set"]];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);

                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'].' / '.$value['combo'];
                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'];

                $poWise[$old_key]['po_qty'] = (int)$poWise[$old_key]['po_qty'] + (int)$value['po_qty'];
                $poWise[$old_key]['po_qty'] = $poWise[$old_key]['po_qty'];

                unset($poWise[$key]);
            }
            $userNames[] = $check;
        }

        // return $poWise;
        
        $poWise = array_values($poWise);

        for ($i=0; $i < sizeof($poWise); $i++) { 
            
            $qty = $poWise[$i]['po_qty'];

           
            $poSizeWiseId = $poWise[$i]['po_size_wise_id'];
            $sql2 = "SELECT * FROM tbl_certification_detail WHERE enquiry_id='$id' AND po_size_wise_id='$poSizeWiseId' ";
            $list = $this->db->query($sql2)->result_array();
            // return $list;
            if(sizeof($list) == 1)
            {
                $list = ['edit', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], 
                $list[0]['shipment_qty'] ,$poWise[$i]['pcs_set'], $list[0]['cert_orgin_no'], $list[0]['cert_date'], $list[0]['inspection_ref_no'], 
                $list[0]['insp_date'], $list[0]['custom_ship_bill'], $list[0]['custom_ship_date'], $list[0]['bill_of_exchange'], $list[0]['bill_of_exch_date'], 
                $list[0]['letter_under_ref_no'], $list[0]['letter_under_date'] ];
                array_push($finalVal, $list);
            }
            else {
                $list = ['', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], '', 
                $poWise[$i]['pcs_set'], '', '', '', '', '','', '', '', ''];
                array_push($finalVal, $list);
            }
        }

        $output['data'] = $finalVal;
        return $output;
    }
     public function getgoodsDetailsOfDocuments($id) {
        // $sql = "SELECT * FROM tbl_final_inspection_standard as tfis INNER JOIN tbl_oe_po_wise as topw ON tfis.po_size_wise_id=topw.po_size_wise_id WHERE tfis.enquiry_id='$id' AND tfis.flag=1";
        $sql = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $poWise = $this->db->query($sql)->result_array();

        $finalVal = [];
        
       
        $userNames = array();
        foreach($poWise as $key => $value) {
            $check = [$value['pono_enq_refno'], $value["pcs_set"]];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);

                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'].' / '.$value['combo'];
                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'];

                $poWise[$old_key]['po_qty'] = (int)$poWise[$old_key]['po_qty'] + (int)$value['po_qty'];
                $poWise[$old_key]['po_qty'] = $poWise[$old_key]['po_qty'];

                unset($poWise[$key]);
            }
            $userNames[] = $check;
        }

        // return $poWise;
        
        $poWise = array_values($poWise);

        for ($i=0; $i < sizeof($poWise); $i++) { 
            
            $qty = $poWise[$i]['po_qty'];

           
            $poSizeWiseId = $poWise[$i]['po_size_wise_id'];
            $sql2 = "SELECT * FROM tbl_goods_detail WHERE enquiry_id='$id' AND po_size_wise_id='$poSizeWiseId' ";
            $list = $this->db->query($sql2)->result_array();
            // return $list;
            if(sizeof($list) == 1)
            {
                $list = ['edit', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], 
                $list[0]['shipment_qty'] ,$poWise[$i]['pcs_set'], $list[0]['good_rec_ref_no'], $list[0]['goods_date'], $list[0]['grn_recived_from'], 
                $list[0]['email_mobile'], $list[0]['bill_lan_ref_no'], $list[0]['bill_date'], $list[0]['bill_recived_from'], $list[0]['bill_email_mobile'], 
                 ];
                array_push($finalVal, $list);
            }
            else {
                $list = ['', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], '', 
                $poWise[$i]['pcs_set'], '', '', '', '', '','', '', '', ''];
                array_push($finalVal, $list);
            }
        }

        $output['data'] = $finalVal;
        return $output;
    }


    public function getpaymentDetailsOfDocuments($id) {
         $masterAdmins = $this->master_loginid();
         $subscriber_ids = array_column($masterAdmins, 'subscriber_id');

        // $sql = "SELECT * FROM tbl_final_inspection_standard as tfis INNER JOIN tbl_oe_po_wise as topw ON tfis.po_size_wise_id=topw.po_size_wise_id WHERE tfis.enquiry_id='$id' AND tfis.flag=1";
        $sql = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $poWise = $this->db->query($sql)->result_array();

        $finalVal = [];
        
       
        $userNames = array();
        foreach($poWise as $key => $value) {
            $check = [$value['pono_enq_refno'], $value["pcs_set"]];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);

                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'].' / '.$value['combo'];
                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'];

                $poWise[$old_key]['po_qty'] = (int)$poWise[$old_key]['po_qty'] + (int)$value['po_qty'];
                $poWise[$old_key]['po_qty'] = $poWise[$old_key]['po_qty'];

                unset($poWise[$key]);
            }
            $userNames[] = $check;
        }

        // return $poWise;
        
        $poWise = array_values($poWise);

        for ($i=0; $i < sizeof($poWise); $i++) { 
            
            $qty = $poWise[$i]['po_qty'];

           
            $poSizeWiseId = $poWise[$i]['po_size_wise_id'];
            $sql2 = "SELECT * FROM tbl_document_payment WHERE enquiry_id='$id' AND po_size_wise_id='$poSizeWiseId' ";
            $list = $this->db->query($sql2)->result_array();
            // return $list;
            if(sizeof($list) == 1)
            {
                $list = ['edit', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], 
                $list[0]['shipment_qty'] ,$poWise[$i]['pcs_set'], $list[0]['document_sub_mode'], $list[0]['list_of_document'], $list[0]['ariwall_vill_no'], 
                $list[0]['ariwal_date'], $list[0]['bill_cleared_status'], $list[0]['bill_date'], 
                 ];
                array_push($finalVal, $list);
            }
            else {
                $list = ['', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], '', 
                $poWise[$i]['pcs_set'], '', '', '', '', '','', '' ];
                array_push($finalVal, $list);
            }
        }

         $sql2 = $this->db->select('b.id AS id, b.exportname AS name')->from(KN_MASTER_EXPORT_DOCUMENT . ' b')
         ->join(KN_USERS . ' u', 'b.updatedby = u.id', 'left') 
         ->where('b.status', 1)->where_in('u.subscriber_id', $subscriber_ids)->get();

      $export_document = $sql2->result_array();
           

        $output['data'] = $finalVal;
        $output['export_document'] = $export_document;
        return $output;
    }

    // ********** FINAL INSPECTION STANDARD STARTS HERE *********** /
    
    
    public function getFinalInspectionStandardDetailss($id) {
        // $sql = "SELECT * FROM tbl_final_inspection_standard as tfis INNER JOIN tbl_oe_po_wise as topw ON tfis.po_size_wise_id=topw.po_size_wise_id WHERE tfis.enquiry_id='$id' AND tfis.flag=1";
        $sql = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $poWise = $this->db->query($sql)->result_array();

        $finalVal = [];
        
        $LotSize = ['2 to 8', '9 to 15', '16 to 25', '26 to 50', '51 to 90', '91 to 150', '151 to 280', '281 to 500', '501 to 1200', 
        '1201 to 3200', '3201 to 10000', '10001 to 35000', '35001 to 150000', '150001 to 500000', '500001 and over'];

        // return $poWise;

        $userNames = array();
        foreach($poWise as $key => $value) {
            $check = [$value['pono_enq_refno'], $value["pcs_set"]];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);

                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'].' / '.$value['combo'];
                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'];

                $poWise[$old_key]['po_qty'] = (int)$poWise[$old_key]['po_qty'] + (int)$value['po_qty'];
                $poWise[$old_key]['po_qty'] = $poWise[$old_key]['po_qty'];

                unset($poWise[$key]);
            }
            $userNames[] = $check;
        }

        // return $poWise;
        
        $poWise = array_values($poWise);

        for ($i=0; $i < sizeof($poWise); $i++) { 
            
            $qty = $poWise[$i]['po_qty'];

            if($qty > 2 && $qty < 8) {
                $lotBatchSize = $LotSize[0];
            }
            else if($qty > 9 && $qty < 15) {
                $lotBatchSize = $LotSize[1];
            }
            else if($qty > 16 && $qty < 25) {
                $lotBatchSize = $LotSize[2];
            }
            else if($qty > 26 && $qty < 50) {
                $lotBatchSize = $LotSize[3];
            }
            else if($qty > 51 && $qty < 90) {
                $lotBatchSize = $LotSize[4];
            }
            else if($qty > 91 && $qty < 150) {
                $lotBatchSize = $LotSize[5];
            }
            else if($qty > 151 && $qty < 280) {
                $lotBatchSize = $LotSize[6];
            }
            else if($qty > 281 && $qty < 500) {
                $lotBatchSize = $LotSize[7];
            }
            else if($qty > 501 && $qty < 1200) {
                $lotBatchSize = $LotSize[8];
            }
            else if($qty > 1201 && $qty < 3200) {
                $lotBatchSize = $LotSize[9];
            }
            else if($qty > 3201 && $qty < 10000) {
                $lotBatchSize = $LotSize[10];
            }
            else if($qty > 10001 && $qty < 35000) {
                $lotBatchSize = $LotSize[11];
            }
            else if($qty > 35001 && $qty < 150000) {
                $lotBatchSize = $LotSize[12];
            }
            else if($qty > 150001 && $qty < 500000) {
                $lotBatchSize = $LotSize[13];
            }
            else if($qty > 500001) {
                $lotBatchSize = $LotSize[14];
            }
            
            $poSizeWiseId = $poWise[$i]['po_size_wise_id'];
            $sql2 = "SELECT * FROM tbl_final_inspection_standard WHERE enquiry_id='$id' AND po_size_wise_id='$poSizeWiseId' ";
            $list = $this->db->query($sql2)->result_array();
            // return $list;
            if(sizeof($list) == 1)
            {
                $list = ['edit', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], 
                $poWise[$i]['pcs_set'], $list[0]['lot_batch_size'], $list[0]['general_special_inpection_lvl'], $list[0]['sample_size_code_letter'], 
                $list[0]['sample_size'], $list[0]['critical_aql'], $list[0]['major_aql'], $list[0]['minor_aql'], $list[0]['inspection_authority'], 
                $list[0]['fi_sheduled_date'], $list[0]['fi_offered_date']];
                array_push($finalVal, $list);
            }
            else {
                $list = ['', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], 
                $poWise[$i]['pcs_set'], $lotBatchSize, '', '', '', '','', '', '', '', ''];
                array_push($finalVal, $list);
            }
        }

        $output['data'] = $finalVal;
        return $output;
    }


     public function getFinalInspectionStandardDetailsoffers($id) {
        // $sql = "SELECT * FROM tbl_final_inspection_standard as tfis INNER JOIN tbl_oe_po_wise as topw ON tfis.po_size_wise_id=topw.po_size_wise_id WHERE tfis.enquiry_id='$id' AND tfis.flag=1";
        $sql = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $poWise = $this->db->query($sql)->result_array();

        $finalVal = [];
        
        $LotSize = ['2 to 8', '9 to 15', '16 to 25', '26 to 50', '51 to 90', '91 to 150', '151 to 280', '281 to 500', '501 to 1200', 
        '1201 to 3200', '3201 to 10000', '10001 to 35000', '35001 to 150000', '150001 to 500000', '500001 and over'];

        // return $poWise;

        $userNames = array();
        foreach($poWise as $key => $value) {
            $check = [$value['pono_enq_refno'], $value["pcs_set"]];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);

                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'].' / '.$value['combo'];
                $poWise[$old_key]['combo'] = $poWise[$old_key]['combo'];

                $poWise[$old_key]['po_qty'] = (int)$poWise[$old_key]['po_qty'] + (int)$value['po_qty'];
                $poWise[$old_key]['po_qty'] = $poWise[$old_key]['po_qty'];

                unset($poWise[$key]);
            }
            $userNames[] = $check;
        }

        // return $poWise;
        
        $poWise = array_values($poWise);

        for ($i=0; $i < sizeof($poWise); $i++) { 
            
            $qty = $poWise[$i]['po_qty'];

            if($qty > 2 && $qty < 8) {
                $lotBatchSize = $LotSize[0];
            }
            else if($qty > 9 && $qty < 15) {
                $lotBatchSize = $LotSize[1];
            }
            else if($qty > 16 && $qty < 25) {
                $lotBatchSize = $LotSize[2];
            }
            else if($qty > 26 && $qty < 50) {
                $lotBatchSize = $LotSize[3];
            }
            else if($qty > 51 && $qty < 90) {
                $lotBatchSize = $LotSize[4];
            }
            else if($qty > 91 && $qty < 150) {
                $lotBatchSize = $LotSize[5];
            }
            else if($qty > 151 && $qty < 280) {
                $lotBatchSize = $LotSize[6];
            }
            else if($qty > 281 && $qty < 500) {
                $lotBatchSize = $LotSize[7];
            }
            else if($qty > 501 && $qty < 1200) {
                $lotBatchSize = $LotSize[8];
            }
            else if($qty > 1201 && $qty < 3200) {
                $lotBatchSize = $LotSize[9];
            }
            else if($qty > 3201 && $qty < 10000) {
                $lotBatchSize = $LotSize[10];
            }
            else if($qty > 10001 && $qty < 35000) {
                $lotBatchSize = $LotSize[11];
            }
            else if($qty > 35001 && $qty < 150000) {
                $lotBatchSize = $LotSize[12];
            }
            else if($qty > 150001 && $qty < 500000) {
                $lotBatchSize = $LotSize[13];
            }
            else if($qty > 500001) {
                $lotBatchSize = $LotSize[14];
            }
            
            $poSizeWiseId = $poWise[$i]['po_size_wise_id'];
            $sql2 = "SELECT * FROM tbl_final_inspection_standard WHERE enquiry_id='$id' AND po_size_wise_id='$poSizeWiseId' ";
            $list = $this->db->query($sql2)->result_array();
            // return $list;
            if(sizeof($list) == 1)
            {
                $list = ['edit', '', $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], 
                $poWise[$i]['pcs_set'], $list[0]['lot_batch_size'], $list[0]['general_special_inpection_lvl'], $list[0]['sample_size_code_letter'], 
                $list[0]['sample_size'], $list[0]['critical_aql'], $list[0]['major_aql'], $list[0]['minor_aql'], $list[0]['inspection_authority'], 
                $list[0]['fi_sheduled_date'], $list[0]['fi_offered_date'],$list[0]['final_inspection_standard_id'],$poWise[$i]['po_size_wise_id'],$list[0]['fi_status_re']];
                array_push($finalVal, $list);
            }
            else {
                $list = ['', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $poWise[$i]['combo'], $poWise[$i]['po_qty'], 
                $poWise[$i]['pcs_set'], $lotBatchSize, '', '', '', '','', '', '', '', ''];
                array_push($finalVal, $list);
            }
        }

        $output['data'] = $finalVal;
        return $output;
    }

   public function getFinalInspectionStandardDetailssreports($id)
{
    $finalVal = [];

    $sql2 = "SELECT * FROM tbl_final_inspection_status_report WHERE enquiryid = '$id'";
    $rows = $this->db->query($sql2)->result_array();

    // If no rows found, return empty structure
    if (empty($rows)) {
        return ['data' => []];
    }

    // Build final array
    for ($i = 0; $i < sizeof($rows); $i++) {

        $row = $rows[$i]; // take each row properly

        $item = [
            'edit',
            $row['po_size_wise_id'],
            $row['po_ref_no'],
            $row['combo_colour'],
            $row['sample_size'],
            $row['psc_set'],
            $row['critical_mistake'],
            $row['Major_mistake'],
            $row['minor_mistake'],
            $row['fi_status'],
            $row['fi_done_by'],
            $row['fi_completion_date'],
            $row['fi_pass_fail'],
            $row['remark_action'],
            $row['fi_status_report_id'],
            $row['enquiryId'],
            $row['fi_standard_id'],
            
        ];

        $finalVal[] = $item; // Push array
    }

    //print_r($finalVal);
    return ['data' => $finalVal];
}

       
    

   



    
    public function updateFinalInspectionStandardd($req_data, $id) {        
        foreach ($req_data as $key => $value)
        {
            $finalInspection["enquiry_id"] = $id;
            $finalInspection["po_size_wise_id"] = $value[1];
            $finalInspection["lot_batch_size"] = $value[6];
            $finalInspection["general_special_inpection_lvl"] = $value[7];
            $finalInspection["sample_size_code_letter"] = $value[8];
            $finalInspection["sample_size"] = $value[9];
            $finalInspection["critical_aql"] = $value[10];
            $finalInspection["major_aql"] = $value[11];
            $finalInspection["minor_aql"] = $value[12];
            $finalInspection["inspection_authority"] = $value[13];
            $finalInspection["fi_sheduled_date"] = $value[14];
          
            if($value[0] == "edit" && $finalInspection["po_size_wise_id"] !="") {
                $this->db->where('enquiry_id', $finalInspection["enquiry_id"]);
                $this->db->where('po_size_wise_id', $finalInspection["po_size_wise_id"]);
                $this->db->update('tbl_final_inspection_standard', $finalInspection);
            }
    else {
                $this->db->insert('tbl_final_inspection_standard', $finalInspection);
            }
        }                                                                                                                                                                                                                                                                                                                                                   
    }

     public function updateshippingdetailss($req_data, $id) {    
        //print_r($req_data);    
        foreach ($req_data as $key => $value)
        {
            $finalInspection["enquiry_id"] = $id;
            $finalInspection["po_size_wise_id"] = $value[1];
            $finalInspection["shipment_qty"] = $value[5];
            $finalInspection["Invoice_no"] = $value[7];
            $finalInspection["inv_Date"] = $value[8];
            $finalInspection["packing_list"] = $value[9];
            $finalInspection["e_invoice_no"] = $value[10];
            $finalInspection["invoice_date"] = $value[11];
            $finalInspection["e_way_Bill_no"] = $value[12];
            $finalInspection["e_way_date"] = $value[13];
            $finalInspection["insurance_policy_no"] = $value[14];
             $finalInspection["insurance_policy_date"] = $value[15];
          
            if($value[0] == "edit" && $finalInspection["po_size_wise_id"] !="") {
                $this->db->where('enquiry_id', $finalInspection["enquiry_id"]);
                $this->db->where('po_size_wise_id', $finalInspection["po_size_wise_id"]);
                $this->db->update('tbl_shipping_document', $finalInspection);
            }
    else {
                $this->db->insert('tbl_shipping_document', $finalInspection);
            }
        }                                                                                                                                                                                                                                                                                                                                                   
    }

    public function updateroaddetailss($req_data, $id) {    
        //print_r($req_data);    
        foreach ($req_data as $key => $value)
        {
            $finalInspection["enquiry_id"] = $id;
            $finalInspection["po_size_wise_id"] = $value[1];
            $finalInspection["shipment_qty"] = $value[5];
            $finalInspection["transport_name"] = $value[7];
            $finalInspection["transport_address"] = $value[8];
            $finalInspection["vehicle_no"] = $value[9];
            $finalInspection["driver_name"] = $value[10];
            $finalInspection["driver_mobile_no"] = $value[11];
            $finalInspection["connect_port_name"] = $value[12];
            $finalInspection["vehical_start_date_time"] = $value[13];
            $finalInspection["goods_hand_date_time"] = $value[14];
        
          
            if($value[0] == "edit" && $finalInspection["po_size_wise_id"] !="") {
                $this->db->where('enquiry_id', $finalInspection["enquiry_id"]);
                $this->db->where('po_size_wise_id', $finalInspection["po_size_wise_id"]);
                $this->db->update('tbl_road_transport_detail', $finalInspection);
            }
    else {
                $this->db->insert('tbl_road_transport_detail', $finalInspection);
            }
        }                                                                                                                                                                                                                                                                                                                                                   
    }

    public function updatedocument_attachment($req_data, $id) {    
        //print_r($req_data);    
        foreach ($req_data as $key => $value)
        {
            $finalInspection["enquiry_id"] = $id;
            $finalInspection["po_size_wise_id"] = $value[1];
            $finalInspection["buyer_orgianl"] = $value[6];
            $finalInspection["po_wise_packing"] = $value[7];
            $finalInspection["Buyer_commet"] = $value[8];
           
          
        if($value[0] == "edit" && $finalInspection["po_size_wise_id"] !="") {
                $this->db->where('enquiry_id', $finalInspection["enquiry_id"]);
                $this->db->where('po_size_wise_id', $finalInspection["po_size_wise_id"]);
                $this->db->update('document_attachment_detail', $finalInspection);
            }
        else {
                $this->db->insert('document_attachment_detail', $finalInspection);
            }
        }                                                                                                                                                                                                                                                                                                                                                   
    }
    public function updatecertificationdetailss($req_data, $id) {    
        //print_r($req_data);    
        foreach ($req_data as $key => $value)
        {
            $finalInspection["enquiry_id"] = $id;
            $finalInspection["po_size_wise_id"] = $value[1];
            $finalInspection["shipment_qty"] = $value[5];
            $finalInspection["cert_orgin_no"] = $value[7];
            $finalInspection["cert_date"] = $value[8];
            $finalInspection["inspection_ref_no"] = $value[9];
            $finalInspection["insp_date"] = $value[10];
            $finalInspection["custom_ship_bill"] = $value[11];
            $finalInspection["custom_ship_date"] = $value[12];
            $finalInspection["bill_of_exchange"] = $value[13];
            $finalInspection["bill_of_exch_date"] = $value[14];
              $finalInspection["letter_under_ref_no"] = $value[13];
            $finalInspection["letter_under_date"] = $value[14];
        
          
            if($value[0] == "edit" && $finalInspection["po_size_wise_id"] !="") {
                $this->db->where('enquiry_id', $finalInspection["enquiry_id"]);
                $this->db->where('po_size_wise_id', $finalInspection["po_size_wise_id"]);
                $this->db->update('tbl_certification_detail', $finalInspection);
            }
    else {
                $this->db->insert('tbl_certification_detail', $finalInspection);
            }
        }                                                                                                                                                                                                                                                                                                                                                   
    }


     public function updategoodsdetailss($req_data, $id) {    
        //print_r($req_data);    
        foreach ($req_data as $key => $value)
        {
            $finalInspection["enquiry_id"] = $id;
            $finalInspection["po_size_wise_id"] = $value[1];
            $finalInspection["shipment_qty"] = $value[5];
            $finalInspection["good_rec_ref_no"] = $value[7];
            $finalInspection["goods_date"] = $value[8];
            $finalInspection["grn_recived_from"] = $value[9];
            $finalInspection["email_mobile"] = $value[10];
            $finalInspection["bill_lan_ref_no"] = $value[11];
            $finalInspection["bill_date"] = $value[12];
            $finalInspection["bill_recived_from"] = $value[13];
            $finalInspection["bill_email_mobile"] = $value[14];
              
          
            if($value[0] == "edit" && $finalInspection["po_size_wise_id"] !="") {
                $this->db->where('enquiry_id', $finalInspection["enquiry_id"]);
                $this->db->where('po_size_wise_id', $finalInspection["po_size_wise_id"]);
                $this->db->update('tbl_goods_detail', $finalInspection);
            }
    else {
                $this->db->insert('tbl_goods_detail', $finalInspection);
            }
        }                                                                                                                                                                                                                                                                                                                                                   
    }
    
     public function updatepaymentdetailss($req_data, $id) {    
        //print_r($req_data);    
        foreach ($req_data as $key => $value)
        {
            $finalInspection["enquiry_id"] = $id;
            $finalInspection["po_size_wise_id"] = $value[1];
            $finalInspection["shipment_qty"] = $value[5];
            $finalInspection["document_sub_mode"] = $value[7];
            $finalInspection["list_of_document"] = $value[8];
            $finalInspection["ariwall_vill_no"] = $value[9];
            $finalInspection["ariwal_date"] = $value[10];
            $finalInspection["bill_cleared_status"] = $value[11];
            $finalInspection["bill_date"] = $value[12];
          
              
          
            if($value[0] == "edit" && $finalInspection["po_size_wise_id"] !="") {
                $this->db->where('enquiry_id', $finalInspection["enquiry_id"]);
                $this->db->where('po_size_wise_id', $finalInspection["po_size_wise_id"]);
                $this->db->update('tbl_document_payment', $finalInspection);
            }
    else {
                $this->db->insert('tbl_document_payment', $finalInspection);
            }
        }                                                                                                                                                                                                                                                                                                                                                   
    }

    public function updateFinalInspectionStatusReport($req_data, $id) {  
        
        //print_r($req_data);
        foreach ($req_data as $key => $value)
        {
            $finalInspection["sample_size"] = $value[4];
            $finalInspection["critical_mistake"] = $value[6];
            $finalInspection["Major_mistake"] = $value[7];
            $finalInspection["minor_mistake"] = $value[8];
            $finalInspection["fi_status"] = $value[9];
            $finalInspection["fi_done_by"] = $value[10];
            $finalInspection["fi_completion_date"] = $value[11];
            $finalInspection["remark_action"] = $value[13];
            //$finalInspection["postatus_report_id"] = $value[14];
            //$finalInspection["enquiry_id"] = $value[15];
            //print_r($value[0]);
            //print_r($value[14]);
           
          
            if($value[0] == "edit" && $value[14] !="") {
                $this->db->where('enquiryId', $value[15]);
                $this->db->where('fi_status_report_id', $value[14]);
                if($value[12] = "" || $value[12] == null) {
                    print_r('pavi');
                    if($value[9] == 4 || $value[9] == 5 || $value[9] == 6 ) {
                        //$finalInspection["fi_pass_fail"] = 1;
                    
                  $sql2 = "SELECT COUNT(*) AS total_count FROM tbl_final_inspection_status_report WHERE enquiryid = '$value[15]' AND fi_standard_id = '$value[16]'  ";
                  $rows = $this->db->query($sql2)->result_array();
                  $count = $rows[0]['total_count'];
                  $finalInspection["fi_pass_fail"] = $count;
                   $finalInspection["fi_completion_date"] = date('Y-m-d h:i:s');
                  }
                    
                } 
                $this->db->update('tbl_final_inspection_status_report', $finalInspection);
               if (!empty($value[16]) && !empty($value[15])) {

                 $finalInspection1["fi_status_re"] = $value[9];

        $this->db->where('enquiry_id', $value[15]);
        $this->db->where('final_inspection_standard_id', $value[16]);
        $this->db->update('tbl_final_inspection_standard', $finalInspection1);
    }
            }
    else {
                $this->db->insert('tbl_final_inspection_status_report', $finalInspection);
            }
        }                                                                                                                                                                                                                                                                                                                                                   
    }

    public function updateFinalInspectionStandardd_offer($req_data, $id) {     
        
        //print_r($req_data);
       // die;
        foreach ($req_data as $key => $value)
        {

           if (!empty($value[1]) && $value[1] !== "false" && $value[1] !== 0) {
                //print_r('pavi');
                $finalInspection["enquiry_id"] = $id;
           
            $finalInspection["lot_batch_size"] = $value[6];
            $finalInspection["general_special_inpection_lvl"] = $value[7];
            $finalInspection["sample_size_code_letter"] = $value[8];
            $finalInspection["sample_size"] = $value[9];
            $finalInspection["critical_aql"] = $value[10];
            $finalInspection["major_aql"] = $value[11];
            $finalInspection["minor_aql"] = $value[12];
            $finalInspection["inspection_authority"] = $value[13];
            $finalInspection["fi_sheduled_date"] = $value[14];
            $finalInspection["final_inspection_standard_id"] = $value[16];
            $finalInspection["fi_offered_date"] = date("Y-m-d h:i:s");;
            $finalInspection["fi_offer_status"] = 1;
            $finalInspection["po_size_wise_id"] = $value[17];
            if($value[0] == "edit" && $finalInspection["po_size_wise_id"] !="") {
                $this->db->where('enquiry_id', $finalInspection["enquiry_id"]);
                $this->db->where('po_size_wise_id', $finalInspection["po_size_wise_id"]);
                $this->db->update('tbl_final_inspection_standard', $finalInspection);
             if($value[0] == "edit" && $finalInspection["po_size_wise_id"] !="") {
                 $finalInspection1["po_ref_no"] = $value[2];
            $finalInspection1["combo_colour"] = $value[3];
            $finalInspection1["psc_set"] = $value[5];
                   $finalInspection1["fi_standard_id"] = $value[16];
                   $finalInspection1["enquiryid"] = $id;
                    $finalInspection1["po_size_wise_id"] = $value[17];

               

               $this->db->insert('tbl_final_inspection_status_report', $finalInspection1);
                  }
                 
            }
            }
            // else {
            //     $this->db->insert('tbl_final_inspection_standard', $finalInspection);
            // } 
            
           
        }                                                                                                                                                                                                                                                                                                                                                   
    }

    // ********** FINAL INSPECTION STANDARD ENDS HERE *********** /

    // ********** DETAILS OF CONSIGNEE & LOGISTICS STARTS HERE *********** /
    
    public function getDetailsOfConsigneeLogisticss($id) {
        $sql = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        //$sql = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . "   group by a.pono_enq_refno";
        
        $poWise = $this->db->query($sql)->result_array();

        $finalVal = [];
          $powiseVal = [];
        
        for ($i=0; $i < sizeof($poWise); $i++) { 

            $poSizeWiseId = $poWise[$i]['po_size_wise_id'];
            $sql2 = "SELECT * FROM tbl_documentation_consignee WHERE enquiry_id='$id' AND po_size_wise_id='$poSizeWiseId' ";
            $list = $this->db->query($sql2)->result_array();
            if(sizeof($list) == 1)
            {
                $list = ['edit', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], $list[0]['consigner_shipper_exporter'], 
                $list[0]['clearing_agent_name'], $list[0]['forwarding_agent_name'], $list[0]['importer'], $list[0]['consigner_name']];
                array_push($finalVal, $list);

                 array_push($powiseVal, $poWise[$i]['pono_enq_refno']);
            }
            else {
                if(sizeof($poWise) == 1){
               $list = ['', $poWise[$i]['po_size_wise_id'], $poWise[$i]['pono_enq_refno'], '', '', '', '', ''];
                array_push($finalVal, $list);
                }
               
                 array_push($powiseVal, $poWise[$i]['pono_enq_refno']);
            }

            $powiseVal = array_values(array_unique($powiseVal));
        }
         $masterAdmins = $this->master_loginid();
         $subscriber_ids = array_column($masterAdmins, 'subscriber_id');


        
       // Note: commented below queries due to new form integration for forwarding,clearing,importer,consignor,consignee
       // $sql2 = "SELECT id as id, logistic as name FROM kn_master_logistics WHERE type=1 AND status=1";


        // $sql2 = "SELECT id as id, agentname as name FROM ".KN_MASTER_AGENTS." WHERE type=1 AND status=1";
        //  $forwadingAgent = $this->db->query($sql2)->result_array();

      $sql2 = $this->db->select('b.id AS id, b.agentname AS name')->from(KN_MASTER_AGENTS . ' b')
      ->join(KN_USERS . ' u', 'b.updatedby = u.id', 'left') ->where('b.type', 1)
      ->where('b.status', 1)->where_in('u.subscriber_id', $subscriber_ids)->get();

      $forwadingAgent = $sql2->result_array();
           

       
       // $sql3 = "SELECT id as id, logistic as name FROM kn_master_logistics WHERE type=2 AND status=1";
        //$sql3 = "SELECT id as id, agentname as name FROM ".KN_MASTER_AGENTS." WHERE type=2 AND status=1";
        //$clearingAgent = $this->db->query($sql3)->result_array();

        
      $sql3 = $this->db->select('b.id AS id, b.agentname AS name')->from(KN_MASTER_AGENTS . ' b')
         ->join(KN_USERS . ' u', 'b.updatedby = u.id', 'left') ->where('b.type', 2)
         ->where('b.status', 1)->where_in('u.subscriber_id', $subscriber_ids)->get();
       $clearingAgent = $sql3->result_array();


        //$sql4 = "SELECT id as id, logistic as name FROM kn_master_logistics WHERE type=3 AND status=1";
        // $sql4 = "SELECT id as id, agentname as name FROM ".KN_MASTER_AGENTS." WHERE type=3 AND status=1";
        // $importerAgent = $this->db->query($sql4)->result_array();

        $sql4 = $this->db->select('b.id AS id, b.agentname AS name')->from(KN_MASTER_AGENTS . ' b')
         ->join(KN_USERS . ' u', 'b.updatedby = u.id', 'left') ->where('b.type', 3)
         ->where('b.status', 1)->where_in('u.subscriber_id', $subscriber_ids)->get();
         $importerAgent = $sql4->result_array();

        // $sql5 = "SELECT id, name FROM kn_master_consignor WHERE status=1";
        // $sql5 = "SELECT id, agentname as name FROM ".KN_MASTER_AGENTS." WHERE type=4 AND status=1";
        // $consignorAgent = $this->db->query($sql5)->result_array();

         $sql5 = $this->db->select('b.id AS id, b.agentname AS name')->from(KN_MASTER_AGENTS . ' b')
         ->join(KN_USERS . ' u', 'b.updatedby = u.id', 'left') ->where('b.type', 4)
         ->where('b.status', 1)->where_in('u.subscriber_id', $subscriber_ids)->get();
         $consignorAgent = $sql5->result_array();
        
        // $sql6 = "SELECT id, name FROM kn_master_consignee WHERE status=1";
        // $sql6 = "SELECT id, agentname as name FROM ".KN_MASTER_AGENTS." WHERE type=5 AND status=1";
        // $consigneeAgent = $this->db->query($sql6)->result_array();

         $sql6 = $this->db->select('b.id AS id, b.agentname AS name')->from(KN_MASTER_AGENTS . ' b')
         ->join(KN_USERS . ' u', 'b.updatedby = u.id', 'left') ->where('b.type', 5)
         ->where('b.status', 1)->where_in('u.subscriber_id', $subscriber_ids)->get();
         $consigneeAgent = $sql6->result_array();

        $output['data'] = $finalVal;
        $output['powiseVal'] = $powiseVal;
        $output['clearingAgent'] = $clearingAgent;
        $output['forwadingAgent'] = $forwadingAgent;
        $output['importerAgent'] = $importerAgent;
        $output['consignorAgent'] = $consignorAgent;
        $output['consigneeAgent'] = $consigneeAgent;
        return $output;
    }

    public function getDetailsOfConsigneeLogistics_doc_requests($id) {
        $sql = "SELECT * FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $poWise = $this->db->query($sql)->result_array();

        $finalVal = [];
        
        for ($i=0; $i < sizeof($poWise); $i++) { 

            $poSizeWiseId = $poWise[$i]['po_size_wise_id'];
            $sql2 = "SELECT * FROM tbl_documentation_consignee WHERE enquiry_id='$id' AND po_size_wise_id='$poSizeWiseId' ";
            $list = $this->db->query($sql2)->result_array();
            // if(sizeof($list) == 1)
            // {
            //     $list = ['edit', $list[0]['documentation_consignee_id'], $list[0]['req_sent_status'],$poWise[$i]['pono_enq_refno'],$poWise[$i]['combo'],$poWise[$i]['po_qty'],$poWise[$i]['pcs_set'], $list[0]['consigner_shipper_exporter'], 
            //     $list[0]['clearing_agent_name'], $list[0]['forwarding_agent_name'], $list[0]['importer'], $list[0]['consigner_name']];
            //     array_push($finalVal, $list);
            // }
            // else {
            //     $list = ['', $list[0]['documentation_consignee_id'], '',$poWise[$i]['pono_enq_refno'], '', '', '', '', '','','',''];
            //     array_push($finalVal, $list);
            // }

             if(sizeof($list) > 0)
            {
                $list = ['edit', $list[0]['documentation_consignee_id'], $list[0]['req_sent_status'],$poWise[$i]['pono_enq_refno'],$poWise[$i]['combo'],$poWise[$i]['po_qty'],$poWise[$i]['pcs_set'], $list[0]['consigner_shipper_exporter'], 
                $list[0]['clearing_agent_name'], $list[0]['forwarding_agent_name'], $list[0]['importer'], $list[0]['consigner_name']];
                array_push($finalVal, $list);
            }


        }
        
       // Note: commented below queries due to new form integration for forwarding,clearing,importer,consignor,consignee
       // $sql2 = "SELECT id as id, logistic as name FROM kn_master_logistics WHERE type=1 AND status=1";
        $sql2 = "SELECT id as id, agentname as name FROM ".KN_MASTER_AGENTS." WHERE type=1 AND status=1";
        $forwadingAgent = $this->db->query($sql2)->result_array();

       // $sql3 = "SELECT id as id, logistic as name FROM kn_master_logistics WHERE type=2 AND status=1";
        $sql3 = "SELECT id as id, agentname as name FROM ".KN_MASTER_AGENTS." WHERE type=2 AND status=1";
        $clearingAgent = $this->db->query($sql3)->result_array();

        //$sql4 = "SELECT id as id, logistic as name FROM kn_master_logistics WHERE type=3 AND status=1";
        $sql4 = "SELECT id as id, agentname as name FROM ".KN_MASTER_AGENTS." WHERE type=3 AND status=1";
        $importerAgent = $this->db->query($sql4)->result_array();

        // $sql5 = "SELECT id, name FROM kn_master_consignor WHERE status=1";
        $sql5 = "SELECT id, agentname as name FROM ".KN_MASTER_AGENTS." WHERE type=4 AND status=1";
        $consignorAgent = $this->db->query($sql5)->result_array();
        
        // $sql6 = "SELECT id, name FROM kn_master_consignee WHERE status=1";
        $sql6 = "SELECT id, agentname as name FROM ".KN_MASTER_AGENTS." WHERE type=5 AND status=1";
        $consigneeAgent = $this->db->query($sql6)->result_array();

        $output['data'] = $finalVal;
        $output['clearingAgent'] = $clearingAgent;
        $output['forwadingAgent'] = $forwadingAgent;
        $output['importerAgent'] = $importerAgent;
        $output['consignorAgent'] = $consignorAgent;
        $output['consigneeAgent'] = $consigneeAgent;
        return $output;

        //PRINT_R($output);
    }
    
    public function updateDetailsOfConsigneeLogisticss($req_data, $id) {        
        foreach ($req_data as $key => $value)
        {
            $consigneeList["enquiry_id"] = $id;
            $consigneeList["po_size_wise_id"] = $value[1];
            $consigneeList["consigner_shipper_exporter"] = $value[3];
            $consigneeList["clearing_agent_name"] = $value[4];
            $consigneeList["forwarding_agent_name"] = $value[5];
            $consigneeList["importer"] = $value[6];
            $consigneeList["consigner_name"] = $value[7];
            if($value[0] == "edit" && $consigneeList["po_size_wise_id"] !="") {
                $this->db->where('enquiry_id', $consigneeList["enquiry_id"]);
                $this->db->where('po_size_wise_id', $consigneeList["po_size_wise_id"]);
                $this->db->update('tbl_documentation_consignee', $consigneeList);
            }
            else {
              $pono_enq_refno = $value[2];
            if (empty($pono_enq_refno)) {
                continue;
            }

            // ✅ Correct query
            $poWise = $this->db->select('po_size_wise_id')->from('tbl_oe_po_wise')
                ->where('pono_enq_refno', $pono_enq_refno)->where('enquiry_id', $id)
                ->limit(1)->get()->row_array();

            // Safety check
            if (empty($poWise)) {
                continue;
            }

            $consigneeList['po_size_wise_id'] = $poWise['po_size_wise_id'];

            // Prevent duplicate insert
            $exists = $this->db
                ->where('enquiry_id', $id)
                ->where('po_size_wise_id', $consigneeList['po_size_wise_id'])
                ->get('tbl_documentation_consignee')
                ->row_array();

            if (empty($exists)) {
                $this->db->insert('tbl_documentation_consignee', $consigneeList);
            }
        
            }
        }
    }

    // ********** DETAILS OF CONSIGNEE & LOGISTICS ENDS HERE *********** /

    // fabric

    // ********** COLOR WISE GARMENT PART DETAILS STARTS HERE *********** /
    
    
    public function getColourWiseGarmentPartsDetailss($id) {
        $sql = "SELECT *, b.combo as po_combo FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();

        $component_sql = "SELECT * from tbl_oe_component_intake_wise WHERE enquiry_id = '$id'  ORDER BY component_intake_wise_id ASC";
        $component_data = $this->db->query($component_sql)->result_array();

        $color_wise_sql = "SELECT *, group_concat(`gar_parts` SEPARATOR ';') as selected_garment, 
        group_concat(fab_color_wise_garment_part_id SEPARATOR ';') as selected_ids 
        FROM tbl_fab_color_wise_garment_parts WHERE enquiry_id='$id' 
        group by `po_enq`,`combo`,`component`,`colour` ORDER BY fab_color_wise_garment_part_id ASC";
        $color_wise_data = $this->db->query($color_wise_sql)->result_array();

        // ******* GET THE DATA DETAILS ENDS ********* //

        $finalValue = [];
        if(sizeof($color_wise_data) === 0) {

            $split_component = [];
            $split_c = [];
            $split_colour = [];
            $split_co = [];
            $split_intake_qty = [];
            $split_iq = [];
            $split = [];
            
            //***** spec data *****
            $spec_code_data = [];
            $intake_id_data = [];
            if(sizeof($component_data) == 0) {
                foreach($split_component as $key => $value) {
                    $empty = '';
                    array_push($spec_code_data, $empty);
                    array_push($intake_id_data, $empty);
                }
            }

            for($i=0; $i < sizeof($data); $i++) {
                $component = $data[$i]['component'];
                $split = explode("/", $component);
                foreach ($split as $key => $value) {
                    array_push($split_c, $value);
                }
                
                $intake_qty = $data[$i]['intake_qty'];
                $split = explode("/", $intake_qty);
                foreach ($split as $key => $value) {
                    array_push($split_iq, $value);
                }
                
                $colour = $data[$i]['colour'];
                $split = explode("/", $colour);
                foreach ($split as $key => $value) {
                    array_push($split_co, $value);
                }

            }

            for ($i=0; $i < sizeof($split_co); $i++) { 
                $exp_colour = explode('-', $split_co[$i]);
                for ($j=0; $j < sizeof($exp_colour); $j++) { 
                    array_push($split_component, $split_c[$i]);
                    array_push($split_colour, $exp_colour[$j]);
                    array_push($split_intake_qty, $split_iq[$i]);

                    //***** spec data *****
                    if(sizeof($component_data) > 0) {
                        if( isset( $component_data[$i]['size_spec_code_fit'] ) ) 
                        {
                            
                            // print_r($component_data[$i]['size_spec_code_fit']);

                            $spec_code = $component_data[$i]['size_spec_code_fit'];
                            $intake_id_ = $component_data[$i]['component_intake_wise_id'];
                            if($spec_code == 'novalue') { $spec_code = ""; }
                            array_push($spec_code_data, $spec_code);
                            array_push($intake_id_data, $intake_id_);
                        }
                        else {
                            $spec_code = "";
                            $intake_id_ = '000';
                            array_push($spec_code_data, $spec_code);
                            array_push($intake_id_data, $intake_id_);
                        }
                    }   

                }
            }

            //***** final data *****
            for($i=0; $i < sizeof($split_component); $i++) {

                $po_size_wise_id = $pono_enq_refno = $combo_color_id = $combo = $sizewise_data = $po_qty = $component = 
                $combo = $colour = $intake_qty = $qty = "";   
            
                $pono_enq_split = [];
                $combo_split = [];
                $sizewise_split = [];

                for($j=0; $j < sizeof($data); $j++) {

                    $po_size_wise_id    = $data[$j]['po_size_wise_id'];
                    $pono_enq_refno     = $data[$j]['pono_enq_refno'];
                    $combo_color_id     = $data[$j]['combo_color_id'];
                    $combo              = $data[$j]['combo'];
                    $sizewise_data      = $data[$j]['sizewise_data'];
                    $po_qty             = $data[$j]['po_qty'];
                    $component          = $data[$j]['component'];
                    $combo              = $data[$j]['po_combo'];
                    $colour             = $data[$j]['colour'];
                    $intake_qty         = $data[$j]['intake_qty'];
                    $qty                = $data[$j]['qty'];

                    // $data_component_split = explode("/", $component);
                    $data_colour_split = explode("/", $colour);

                    $split_cc = [];
                    for ($k=0; $k < sizeof($data_colour_split); $k++) { 
                        $exp_colour = explode('-', $data_colour_split[$k]);
                        for ($l=0; $l < sizeof($exp_colour); $l++) { 
                            array_push($split_cc, $exp_colour[$l]);
                        }
                    }

                    foreach ($split_cc as $key => $value) {
                        array_push($pono_enq_split, $pono_enq_refno);
                        array_push($combo_split, $combo);
                        array_push($sizewise_split, $sizewise_data);
                    }

                }

                $split_sizewise_data = [];
                $split_value = [];
                $split_value = explode(",", $sizewise_split[$i]);
                foreach ($split_value as $key => $value) {
                    $value = (int)$value * (int)$split_intake_qty[$i];
                    array_push($split_sizewise_data, $value);
                }

                $combineValue = ['', $po_size_wise_id, $pono_enq_split[$i], $combo_split[$i], $split_component[$i], $split_colour[$i], '', $spec_code_data[$i], '' ];
                
                $combineValue = array_merge($combineValue, $split_sizewise_data);
                $sumValue = array(array_sum($split_sizewise_data));
                $combineValue = array_merge($combineValue, $sumValue);
                $combineValue = array_merge($combineValue, ['']);

                array_push($finalValue, $combineValue);
                
            }
        }
        else
        {
            // *** final data *** //
            for($i=0; $i < sizeof($color_wise_data); $i++) {
                $component = $color_wise_data[$i]['component'];
                $gar_parts = $color_wise_data[$i]['selected_garment'];
                $spec_code = $color_wise_data[$i]['spec_code'];
                $selected_ids = $color_wise_data[$i]['selected_ids'];

                $combineValue = ['edit', $selected_ids, $color_wise_data[$i]['po_enq'], 
                $color_wise_data[$i]['combo'], $component, $color_wise_data[$i]['colour'], 
                $gar_parts, $spec_code, $color_wise_data[$i]['ex_qty'] ];
                
                $sizes = $color_wise_data[$i]['sizes'];
                $array_sizes = explode(',', $sizes);
                
                $combineValue = array_merge($combineValue, $array_sizes);
                
                $itemized_qty = array($color_wise_data[$i]['itemized_qty']);
                $fab_color_wise_garment_part_id = array($color_wise_data[$i]['fab_color_wise_garment_part_id']);
                $combineValue = array_merge($combineValue, $itemized_qty);
                $combineValue = array_merge($combineValue, $fab_color_wise_garment_part_id);

                array_push($finalValue, $combineValue);
            }                

        }
        
        // ******* GET THE DATA DETAILS START ********* //

        // ******* GET THE COLUMN START ********* //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);
        
        $sql2 = "SELECT id, gpdname as name FROM kn_master_garment_part_desc WHERE status=1";
        $garmentParts = $this->db->query($sql2)->result_array();

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "P.O. / Enq.\n Ref. No.", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'type'=> 'dropdown', 'source'=> $garmentParts, 'align'=> 'left', "multiple"=> true],
            ['title' => "Size Spec\n Code / Fit", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Ex. Qty.\n (%) / Set", 'width' => '8%', 'aligh' => 'left']
        ];
        foreach ($sizeMaster as $key => $value)
        {
            $size[] = ['title' => $value['size_name'], 'width' => 20, 'align' => 'right', 'readOnly' => true];
        }
        $finalData = [
            ['title' => "Itemized Qty.\n (Pcs.)", 'width' => '8%', 'readOnly' => true, 'align'=> 'right'],
            ['title' => "color_wise_garment_part_id", 'type'=> 'hidden']
        ];
        $column     = array_merge($column, $size);
        $column     = array_merge($column, $finalData);
        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        $output['garmentParts'] = $garmentParts;
        return $output;
    }

    public function updateColourWiseGarmentPartsDetailss($req_data, $id) {

        // print_r($req_data);
        // exit();
        foreach ($req_data as $key => $value)
        {
            $length = sizeof($value);
            $inputValue["enquiry_id"] = $id;
            $inputValue["fab_color_wise_garment_part_id"] = $value[1];
            $inputValue["po_enq"] = $value[2];
            $inputValue["combo"] = $value[3];
            $inputValue["component"] = $value[4];
            $inputValue["colour"] = $value[5];
            $inputValue["garment"] = $value[6];
            $inputValue["spec_code"] = $value[7];
            $inputValue["ex_qty"] = $value[8];
            $empVar = [];
            for($i = 0; $length > $i; $i++) {
                if ($i >= 9 && $i < $length - 2) {
                    array_push($empVar, $value[$i]);
                }
            }
            $inputValue["sizes"] = implode(',', $empVar);
            $inputValue["itemized_qty"] = $value[$length - 2];
            // print_r($inputValue);

            $selected_ids = explode(";", $inputValue["fab_color_wise_garment_part_id"]);
            $garment_parts = explode(";", $inputValue["garment"]);

            if($value[0] == "edit") {
                if(sizeof($garment_parts) == sizeof($selected_ids)) {
                    foreach ($garment_parts as $key1 => $value1)
                    {
                        if($value1 != '') {
                            $updateValue["gar_parts"] = $value1;
                            $updateValue['ex_qty'] = $value[8];
                            $updateValue["sizes"] = implode(',', $empVar);
                            $updateValue["itemized_qty"] = $value[$length - 2];
                            
                            $updateValue["fab_color_wise_garment_part_id"] = $selected_ids[$key1];
                            // print_r($updateValue);
                            $this->db->where('fab_color_wise_garment_part_id', $updateValue["fab_color_wise_garment_part_id"]);
                            unset($updateValue["fab_color_wise_garment_part_id"]);
                            $this->db->update('tbl_fab_color_wise_garment_parts', $updateValue);
                        }
                    }     
                }
                else if(sizeof($garment_parts) > sizeof($selected_ids)) {
                    for($i = 0; $i < sizeof($garment_parts); $i++) 
                    {
                        if($i+1 > sizeof($selected_ids)) 
                        {
                            if($garment_parts[$i] != '') 
                            {
                                unset($inputValue["garment"]);
                                $inputValue["gar_parts"] = $garment_parts[$i];
                                unset($inputValue["fab_color_wise_garment_part_id"]);
                                $this->db->insert('tbl_fab_color_wise_garment_parts', $inputValue);
                            }
                        }
                        else 
                        {
                            if($garment_parts[$i] != '') {
                                $updateValue["gar_parts"] = $garment_parts[$i];
                                $updateValue['ex_qty'] = $value[8];
                                $updateValue["sizes"] = implode(',', $empVar);
                                $updateValue["itemized_qty"] = $value[$length - 2];
                                    
                                $updateValue["fab_color_wise_garment_part_id"] = $selected_ids[$i];
                                $this->db->where('fab_color_wise_garment_part_id', $updateValue["fab_color_wise_garment_part_id"]);
                                unset($updateValue["fab_color_wise_garment_part_id"]);
                                $this->db->update('tbl_fab_color_wise_garment_parts', $updateValue);
                            }
                        }
                    }
                }
                else if(sizeof($selected_ids) > sizeof($garment_parts)) {
                    for($i = 0; $i < sizeof($selected_ids); $i++) {

                        // echo sizeof($selected_ids);
                        // echo sizeof($garment_parts);
                        // exit();
                        if($i+1 > sizeof($garment_parts)) 
                        {
                            if($selected_ids[$i] != '') {
                                echo $selected_ids[$i];
                                $deleteValue["gar_parts"] = $selected_ids[$i];
                                $this->db->where('fab_color_wise_garment_part_id', $deleteValue["gar_parts"]);
                                $this->db->update('tbl_fab_color_wise_garment_parts', array('flag' => 0));
                            }
                        }
                        else 
                        {
                            if($garment_parts[$i] != '') {
                                $updateValue["gar_parts"] = $garment_parts[$i];
                                $updateValue['ex_qty'] = $value[8];
                                $updateValue["sizes"] = implode(',', $empVar);
                                $updateValue["itemized_qty"] = $value[$length - 2];
                                    
                                $updateValue["fab_color_wise_garment_part_id"] = $selected_ids[$i];
                                // print_r($updateValue);
                                $this->db->where('fab_color_wise_garment_part_id', $updateValue["fab_color_wise_garment_part_id"]);
                                unset($updateValue["fab_color_wise_garment_part_id"]);
                                $this->db->update('tbl_fab_color_wise_garment_parts', $updateValue);
                            }
                        }
                    }
                }
            }
            else {
                foreach ($garment_parts as $key2 => $value2)
                {
                    if($value2 != '') {
                        unset($inputValue["garment"]);
                        $inputValue["gar_parts"] = $value2;
                        unset($inputValue["fab_color_wise_garment_part_id"]);
                        $this->db->insert('tbl_fab_color_wise_garment_parts', $inputValue);
                    }
                }
            }            
        }

        $result["status"] = "success";
        $result["statusCode"] = "200";
        return $result;


        // $ArrRes = $this->db->get_where('tbl_color_wise_garment_parts', ['enquiry_id' => $id, 'flag' => 1]);
        // $res = $ArrRes->result_array();
        
        // if(sizeof($res) > 0) {
        //     if(sizeof($res) == sizeof($req_data)) 
        //     {
        //         foreach ($req_data as $key => $value) {
        //             if($value[6] == "") { $value[6] = ''; } 
        //             if($value[8] == "") { $value[8] = ''; } 

        //             $color_wise_garment_part_id = $value[14];
        //             $values['garment_parts'] = $value[6];
        //             $values['ex_qty'] = $value[8];
        //             $this->updateColorWiseGarment($id, $color_wise_garment_part_id, $values);
        //         }
        //         return true;
        //     }
        //     else {
        //         foreach ($req_data as $key => $value) {
        //             if($value[6] == "") { $value[6] = ''; } 
        //             if($value[8] == "") { $value[8] = ''; } 

        //             $color_wise_garment_part_id = $value[14];
        //             if($color_wise_garment_part_id === "000") 
        //             {
        //                 // $values['po_size_wise_id'] = $value[1];
        //                 $values['garment_parts'] = $value[6];
        //                 $values['ex_qty'] = $value[8];
        //                 $values['enquiry_id'] = $id;
        //                 $this->saveColorWiseGarment($values);
        //             }
        //             else {
        //                 // $values['po_size_wise_id'] = $value[1];
        //                 $values['garment_parts'] = $value[6];
        //                 $values['ex_qty'] = $value[8];
        //                 $this->updateColorWiseGarment($id, $color_wise_garment_part_id, $values);
        //             }
                    
        //         }
        //         return true;
        //     }
        // }
        // else {
        //     foreach ($req_data as $key => $value) {
        //         if($value[6] == "") { $value[6] = ''; } 
        //         if($value[8] == "") { $value[8] = ''; } 
        //         // $values['po_size_wise_id'] = $value[1];
        //         $values['garment_parts'] = $value[6];
        //         $values['ex_qty'] = $value[8];
        //         $values['enquiry_id'] = $id;
        //         $this->saveColorWiseGarment($values);
        //     }
        //     return true;
        // }
    }

    public function saveColorWiseGarment($values) 
    {
        $this->db->insert('tbl_color_wise_garment_parts', $values);
    }
    
    public function updateColorWiseGarment($id, $color_wise_garment_part_id, $values) 
    {
        $this->db->where('enquiry_id', $id);
        $this->db->where('color_wise_garment_part_id', $color_wise_garment_part_id);
        $this->db->update('tbl_color_wise_garment_parts', $values);
        return true;
    }

    // ********** COLOR WISE GARMENT PART DETAILS ENDS HERE *********** /

    // ********** GERMENT PARTS WISE QTY DETAILS STARTS HERE *********** /
    
    public function getGarmentPartsWiseQtyDetailss($id) {
        $sql = "SELECT *, b.combo as po_combo FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();

        $component_sql = "SELECT * from tbl_oe_component_intake_wise WHERE enquiry_id = '$id'  ORDER BY component_intake_wise_id ASC";
        $component_data = $this->db->query($component_sql)->result_array();

        $color_wise_sql = "SELECT a.*,b.gpdname FROM tbl_fab_color_wise_garment_parts a INNER JOIN kn_master_garment_part_desc b ON a.gar_parts=b.id WHERE a.enquiry_id='$id'  ORDER BY a.fab_color_wise_garment_part_id ASC";
        $color_wise_data = $this->db->query($color_wise_sql)->result_array();

        // ******* GET THE DATA DETAILS ENDS ********* //

        $finalValue = [];
        if(sizeof($color_wise_data) > 0) {
            // *** final data *** //
            for($i=0; $i < sizeof($color_wise_data); $i++) {
                $component = $color_wise_data[$i]['component'];
                $spec_code = $color_wise_data[$i]['spec_code'];
                $ex_qty = $color_wise_data[$i]['ex_qty'];

                $combineValue = ['edit', $color_wise_data[$i]['fab_color_wise_garment_part_id'], $color_wise_data[$i]['po_enq'], 
                $color_wise_data[$i]['combo'], $component, $color_wise_data[$i]['colour'], 
                $color_wise_data[$i]['gpdname'], $spec_code ];
                
                $sizes = $color_wise_data[$i]['sizes'];

                $array_sizes = explode(',', $sizes);
                
                $ex_qty_sizes = [];
                for($j=0;$j<sizeof($array_sizes);$j++)
                {
                    $dis_qty_size = $array_sizes[$j] * $ex_qty / 100;
                    $ex_qty_size = $array_sizes[$j] + $dis_qty_size;
                    array_push($ex_qty_sizes, $ex_qty_size);
                }

                $combineValue = array_merge($combineValue, $ex_qty_sizes);
                
                $sum_of_sizes = array(array_sum($ex_qty_sizes));

                $combineValue = array_merge($combineValue, $sum_of_sizes);

                array_push($finalValue, $combineValue);
            }                

        }
        
        // ******* GET THE DATA DETAILS START ********* //

        // ******* GET THE COLUMN START ********* //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "P.O. / Enq.\n Ref. No.", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '8%', 'align'=> 'left', "readOnly"=> true],
            ['title' => "Size Spec\n Code / Fit", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
        ];
        foreach ($sizeMaster as $key => $value)
        {
            $size[] = ['title' => $value['size_name'], 'width' => 20, 'align' => 'right', 'readOnly' => true];
        }
        $finalData = [
            ['title' => "Itemized Qty.\n (Pcs.)", 'width' => '8%', 'readOnly' => true, 'align'=> 'right'],
            ['title' => "color_wise_garment_part_id", 'type'=> 'hidden']
        ];
        $column     = array_merge($column, $size);
        $column     = array_merge($column, $finalData);
        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    // ********** GERMENT PARTS WISE QTY DETAILS ENDS HERE *********** /

    // ********** SIZE & GARMENT PARTS WISE PIECE WEIGHT PER UNIT STARTS HERE *********** /
    public function getSizeWiseGarmentPartsDetailss($id) {

        $sql = "SELECT fab_color_wise_garment_part_id, component, gar_parts, spec_code, enquiry_id,  
                group_concat(fab_color_wise_garment_part_id) as selected_ids 
                FROM tbl_fab_color_wise_garment_parts WHERE enquiry_id='$id' 
                group by component, gar_parts, spec_code
                ORDER BY fab_color_wise_garment_part_id ASC";
        $data = $this->db->query($sql)->result_array();

        $consl_sql = "SELECT * FROM tbl_fab_garment_piece_weight WHERE enquiry_id='$id' ";
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
                    
                    $resultValue = ['edit', $consl_data[$i]['fab_garment_piece_weight_id'], $selected_ids, $data[$i]["component"], $data[$i]["gar_parts"], $data[$i]["spec_code"], $description, $uom];
                    $resultValue = array_merge($resultValue, $piece_sizes);
                    $finalData = [$consl_data[$i]['avg_weight_piece']];
                    $resultValue = array_merge($resultValue, $finalData);
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
                        
                        $resultValue = ['edit', $consl_data[$i]['fab_garment_piece_weight_id'], $selected_ids, $data[$i]["component"], $data[$i]["gar_parts"], $data[$i]["spec_code"], $description, $uom];
                        $resultValue = array_merge($resultValue, $piece_sizes);
                        $finalData = [$consl_data[$i]['avg_weight_piece']];
                        $resultValue = array_merge($resultValue, $finalData);
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
                        
                        $resultValue = ['edit', $consl_data[$i]['fab_garment_piece_weight_id'], $selected_ids, $data[$i]["component"], $data[$i]["gar_parts"], $data[$i]["spec_code"], $description, $uom];
                        $resultValue = array_merge($resultValue, $piece_sizes);
                        $finalData = [$consl_data[$i]['avg_weight_piece']];
                        $resultValue = array_merge($resultValue, $finalData);
                        array_push($finalValue, $resultValue);
                    }
                }
            }
        }
        // ******* GET THE DATA DETAILS ENDS ********* //

        // ******* GET THE COLUMN START ********* //
        
        
        $sql2 = "SELECT id, gpdname as name FROM kn_master_garment_part_desc WHERE status=1";
        $garmentParts = $this->db->query($sql2)->result_array();
        $UOM = unserialize(ARRUNITOFMEASURE);
        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }
        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "selectedid", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $garmentParts, "readOnly"=> true],
            ['title' => "Size Spec\n Code / Fit", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Description", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => ["Piece Weight", "Fabric Consumption"]],
            ['title' => "UOM", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $UOMDetails],
        ];
        foreach ($sizeMaster as $key => $value)
        {
            $size[] = ['title' => $value['size_name'], 'width' => 20, 'align' => 'right'];
        }
        $finalData = [
            ['title' => "Average Weight\n Per Piece (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "color_wise_garment_part_id", 'type'=> 'hidden']
        ];
        $column     = array_merge($column, $size);
        $column     = array_merge($column, $finalData);
        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        $output['garmentParts'] = $garmentParts;
        $output['garmentSizes'] = sizeof($sizeMaster);
        return $output;
    }

    public function updateSizeWiseGarmentPartsDetailss($req_data, $id) {

        foreach ($req_data as $key => $value)
        {
            $empVar = [];
            $length = sizeof($value);
            $updateValue["enquiry_id"] = $id;
            $updateValue["fab_garment_piece_weight_id"] = $value[1];
            $updateValue["fab_color_wise_garment_part_id"] = $value[2];
            $updateValue['description'] = $value[6];
            $updateValue['uom'] = $value[7];
            for($i = 0; $length > $i; $i++) {
                if ($i >= 8 && $i < $length - 2) {
                    array_push($empVar, $value[$i]);
                }
            }
            $updateValue["piece_sizes"] = implode(',', $empVar);
            $updateValue["avg_weight_piece"] = $value[$length - 2];
            
            if($value[0] == "edit") { 
                $this->db->where('fab_garment_piece_weight_id', $updateValue["fab_garment_piece_weight_id"]);
                unset($updateValue["fab_garment_piece_weight_id"]);
                $this->db->update('tbl_fab_garment_piece_weight', $updateValue);
            }
            else {
                unset($updateValue["fab_garment_piece_weight_id"]);
                $this->db->insert('tbl_fab_garment_piece_weight', $updateValue);
            }            
        } 
    }
    
    // ********** SIZE & GARMENT PARTS WISE PIECE WEIGHT PER UNIT ENDS HERE *********** /
    // ********** FABRIC CONSUMPTION CALCULATION DETAILS STARTS HERE *********** /
    
    public function getFabricConsumptionCalcDetailss($id) {

        $color_wise_sql = "SELECT a.*, b.gpdname FROM tbl_fab_color_wise_garment_parts a 
                            INNER JOIN kn_master_garment_part_desc b ON a.gar_parts=b.id 
                            WHERE a.enquiry_id='$id' 
                            ORDER BY a.fab_color_wise_garment_part_id ASC";
        $color_wise_data = $this->db->query($color_wise_sql)->result_array();

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);

        // ******* GET THE DATA DETAILS ENDS ********* //

        // return $color_wise_data;

        $finalValue = [];
        // *** final data *** //
        for($i=0; $i < sizeof($color_wise_data); $i++) {
            $component = $color_wise_data[$i]['component'];
            $spec_code = $color_wise_data[$i]['spec_code'];
            $ex_qty = $color_wise_data[$i]['ex_qty'];

            $fab_color_wise_garment_part_id = $color_wise_data[$i]['fab_color_wise_garment_part_id'];
            $getData = "SELECT * FROM tbl_fab_garment_piece_weight WHERE enquiry_id = $id ";
            $getData_sql = $this->db->query($getData)->result_array();

            for ($j=0; $j < sizeof($getData_sql); $j++) { 
                $ids =  $getData_sql[$j]['fab_color_wise_garment_part_id'];
                $array_ids = explode(',', $ids);
                for ($k=0; $k < sizeof($array_ids); $k++) { 
                    if($fab_color_wise_garment_part_id == $array_ids[$k])
                    {
                        // print_r($fab_color_wise_garment_part_id);
                        // print_r($getData_sql[$j]);
                        $piece_sizes = $getData_sql[$j]['piece_sizes'];
                    }
                }
            }

            $combineValue = ['edit', $color_wise_data[$i]['fab_color_wise_garment_part_id'], $color_wise_data[$i]['po_enq'], 
                    $color_wise_data[$i]['combo'], $component, $color_wise_data[$i]['colour'], 
                    $color_wise_data[$i]['gpdname'], $spec_code, $color_wise_data[$i]['process_loss']
                ];
            
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
                array_push($consumption_calc_sizes, $calc_sizes);
            }

            $combineValue = array_merge($combineValue, $consumption_calc_sizes);
            $sum_of_sizes = array_sum($consumption_calc_sizes);
            $f_sum_sizes = array(number_format((float)$sum_of_sizes, 3, '.', ''));
            $combineValue = array_merge($combineValue, $f_sum_sizes);
            array_push($finalValue, $combineValue);
        }     

        
        
        // ******* GET THE DATA DETAILS START ********* //

        // ******* GET THE COLUMN START ********* //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "P.O. / Enq.\n Ref. No.", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'left', 'readOnly'=> true],
            ['title' => "Size Spec\n Code / Fit", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Process\n Loss (%)", 'width' => '8%', 'aligh' => 'left'],
        ];
        foreach ($sizeMaster as $key => $value)
        {
            $size[] = ['title' => $value['size_name'], 'width' => 20, 'align' => 'right', 'readOnly' => true];
        }
        $finalData = [
            ['title' => "Total Qty.\n (Kgs.) ", 'width' => '8%', 'readOnly' => true, 'align'=> 'right'],
            // ['title' => "color_wise_garment_part_id", 'type'=> 'hidden']
        ];
        $column     = array_merge($column, $size);
        $column     = array_merge($column, $finalData);
        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    public function updateFabricConsumptionCalcDetailss($req_data, $id)
    {
        foreach ($req_data as $key => $value)
        {
            $fabricList["enquiry_id"] = $id;
            $fabricList["fab_color_wise_garment_part_id"] = $value[1];
            $fabricList["process_loss"] = $value[8];
            if($value[0] == "edit" && $fabricList["fab_color_wise_garment_part_id"] !="") {
                $this->db->where('enquiry_id', $fabricList["enquiry_id"]);
                $this->db->where('fab_color_wise_garment_part_id', $fabricList["fab_color_wise_garment_part_id"]);
                $this->db->update('tbl_fab_color_wise_garment_parts', $fabricList);
            }
        }
    }

    // ********** FABRIC CONSUMPTION CALCULATION DETAILS ENDS HERE *********** /

    // ********** FABRIC PROCESS LOSS DETAILS STARTS HERE *********** /
    
    
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
            $getData = "select * from tbl_fab_garment_piece_weight  ";
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

        // ******* GET THE COLUMN START ********* //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "P.O. / Enq.\n Ref. No.", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'left', 'readOnly'=> true],
            ['title' => "Size Spec\n Code / Fit", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
        ];
        foreach ($sizeMaster as $key => $value)
        {
            $size[] = ['title' => $value['size_name'], 'width' => 20, 'align' => 'right', 'readOnly' => true];
        }
        $finalData = [
            ['title' => "Total Qty.\n (Kgs.) ", 'width' => '8%', 'readOnly' => true, 'align'=> 'right'],
            // ['title' => "color_wise_garment_part_id", 'type'=> 'hidden']
        ];
        $column     = array_merge($column, $size);
        $column     = array_merge($column, $finalData);
        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    // ********** FABRIC PROCESS LOSS DETAILS ENDS HERE *********** /

    // ********** FABRIC SIZE SPEC CODE DETAILS STARTS HERE *********** /
    
    public function getFabricSizeSpecCodeDetailss($id) {
                        
        $consolidated_sql = "SELECT a.*, b.gpdname, group_concat(a.po_enq SEPARATOR' / ') as all_po_enq FROM tbl_fab_color_wise_garment_parts a 
                            INNER JOIN kn_master_garment_part_desc b ON a.gar_parts=b.id 
                            WHERE a.enquiry_id='$id' 
                            group by a.combo, a.component, a.colour, a.gar_parts, a.spec_code  
                            ORDER BY a.fab_color_wise_garment_part_id ASC";
        $consolidated_data = $this->db->query($consolidated_sql)->result_array();

        $get_fabric_process_loss = $this->getFabricProcessLossDetailss($id);
        $color_wise_sql = $get_fabric_process_loss["data"];

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

        // ******* GET THE COLUMN START ********* //

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "P.O. / Enq.\n Ref. No.", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'left', 'readOnly'=> true],
            ['title' => "Size Spec\n Code / Fit", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
        ];
        foreach ($sizeMaster as $key => $value)
        {
            $size[] = ['title' => $value['size_name'], 'width' => 20, 'align' => 'right', 'readOnly' => true];
        }
        $finalData = [
            ['title' => "Total Qty.\n (Kgs.) ", 'width' => '8%', 'readOnly' => true, 'align'=> 'right'],
            ['title' => "color_wise_garment_part_id", 'type'=> 'hidden']
        ];
        $column     = array_merge($column, $size);
        $column     = array_merge($column, $finalData);
        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    // ********** FABRIC SIZE SPEC CODE DETAILS ENDS HERE *********** /

    // ********** SIZE & GARMENT PARTS WISE PIECE WEIGHT PER UNIT STARTS HERE (table 7) *********** /
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

        // ******* GET THE COLUMN START ********* //
        
        
        $sql2 = "SELECT id, gpdname as name FROM kn_master_garment_part_desc WHERE status=1";
        $garmentParts = $this->db->query($sql2)->result_array();
        $UOM = unserialize(ARRUNITOFMEASURE);
        $UOMDetails = [];
        for($i = 1; sizeof($UOM) > $i; $i++)
        {
            $data = [ 'id'=> $UOM[$i], 'name' => $UOM[$i] ];
            array_push($UOMDetails, $data);
        }
        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "selectedid", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'left', "readOnly"=> true],
            ['title' => "Size Spec\n Code / Fit", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Description", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => ["DIA", "DIM (W*H)"]],
            ['title' => "UOM", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $UOMDetails],
        ];
        foreach ($sizeMaster as $key => $value)
        {
            $size[] = ['title' => $value['size_name'], 'width' => 20, 'align' => 'right'];
        }
        $finalData = [
            // ['title' => "Average Weight\n Per Piece (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "color_wise_garment_part_id", 'type'=> 'hidden']
        ];
        $column     = array_merge($column, $size);
        $column     = array_merge($column, $finalData);
        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        $output['garmentParts'] = $garmentParts;
        $output['garmentSizes'] = sizeof($sizeMaster);
        return $output;
    }

    public function update_sizewise_dia_dimensionn($req_data, $id) {

        foreach ($req_data as $key => $value)
        {
            $empVar = [];
            $length = sizeof($value);
            $updateValue["enquiry_id"] = $id;
            $updateValue["fab_final_dia_dim_id"] = $value[1];
            $updateValue["fab_color_wise_garment_part_id"] = $value[2];
            $updateValue['description'] = $value[6];
            $updateValue['uom'] = $value[7];
            for($i = 0; $length > $i; $i++) {
                if ($i >= 8 && $i < $length - 1) {
                    array_push($empVar, $value[$i]);
                }
            }
            $updateValue["piece_sizes"] = implode(',', $empVar);
            $updateValue["avg_weight_piece"] = $value[$length - 1];
            
            if($value[0] == "edit") { 
                $this->db->where('fab_final_dia_dim_id', $updateValue["fab_final_dia_dim_id"]);
                unset($updateValue["fab_final_dia_dim_id"]);
                $this->db->update('tbl_fab_final_dia_dim', $updateValue);
            }
            else {
                unset($updateValue["fab_final_dia_dim_id"]);
                $this->db->insert('tbl_fab_final_dia_dim', $updateValue);
            }            
        } 
    }
    
    // ********** SIZE & GARMENT PARTS WISE PIECE WEIGHT PER UNIT ENDS HERE *********** /

    // ********** ITEMIZED FABRIC REQUIREMENT DETAILS STARTS HERE *********** /
        
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

        $get_fabric_process_loss = $this->getFabricSizeSpecCodeDetailss($id);
        $color_wise_data = $get_fabric_process_loss["data"];

        $get_sizewise_dia_dimensionn = $this->get_sizewise_dia_dimensionn($id);
        $size_wise_dia_data = $get_sizewise_dia_dimensionn["data"];

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
                 $uom1 = [] ; $uom2 = [];
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
                         array_push($uom1, $size_wise_dia_data[$j][7]);
                    }
                    array_push($fil_sizes, $sizes);
                }
            }

            // print_r($fil_sizes);

            for ($j=0; $j < sizeof($color_wise_data); $j++) { 
                $combo2 = $color_wise_data[$j][3];
                $component2 = $color_wise_data[$j][4];
                $colour2 = $color_wise_data[$j][5];
                $gpdname2 = $color_wise_data[$j][6];
                //if($component === $component2 && $gpdname === $gpdname2 && $combo === $combo2 && $colour === $colour2)
                // {
                //     $prices = [];
                //     for ($k=8; $k < sizeof($color_wise_data[$j]) - 1; $k++) {
                //         array_push($prices, $color_wise_data[$j][$k]);
                //     }
                //     array_push($fil_prices, $prices);
                // }
                 if($component === $component2 && $gpdname === $gpdname2 )
                {
                    $prices = [];
                    for ($k=8; $k < sizeof($color_wise_data[$j]) - 1; $k++) {
                        array_push($prices, $color_wise_data[$j][$k]);
                    }
                    array_push($fil_prices, $prices);
                }
            }

            // print_r($fil_prices);

            $dis_sizes = array_values(array_unique($mer_sizes));
              $uom2 = array_values(array_unique($uom1));
            
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

            // print_r($fin_prices);

            $totalSizePrice = number_format((float)array_sum($fin_prices), 3, '.', '');

            for ($j=0; $j < sizeof($dis_sizes); $j++) { 
                if($j == 0)
                {
                    if(!isset($itemized_fabric_data[$i]))
                    // if(isset($itemized_fabric_data[$i]) === 0)
                    {
                        $combineValue = [ '', '', $consolidated_data[$i]['combo'], $consolidated_data[$i]['component'],
                                        $consolidated_data[$i]['colour'], $consolidated_data[$i]['gpdname'], '', '', '', '', '', '', '', '',
                                        $dis_sizes[$j], $uom2[$j], $fin_prices[$j], '', '' ];
                    }
                    else
                    {
                        $combineValue = [ 'edit', $itemized_fabric_data[$i]['itemized_fabric_requirement_id'], $consolidated_data[$i]['combo'], 
                                        $consolidated_data[$i]['component'], $consolidated_data[$i]['colour'], $consolidated_data[$i]['gpdname'], 
                                        $itemized_fabric_data[$i]['yarn_blend'], $itemized_fabric_data[$i]['yarn_content'], $itemized_fabric_data[$i]['yarn_count'],
                                        $itemized_fabric_data[$i]['fabric_name'], $itemized_fabric_data[$i]['finishing_gsm'], $itemized_fabric_data[$i]['no_of_feed_pi'],
                                        $itemized_fabric_data[$i]['lycra'], $itemized_fabric_data[$i]['dyeing_type'], $dis_sizes[$j], $uom2[$j], 
                                        $fin_prices[$j], '', $totalSizePrice ];
                    }
                }
                else if($j > 0)
                {
                    $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', $dis_sizes[$j], $uom1[$j], $fin_prices[$j], '', '' ];
                }
                array_push($finalValue, $combineValue);
            }

            $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $totalSizePrice, '' ];
            array_push($finalValue, $combineValue);

        }

        
        // ******* GET DATA DETAILS ENDS ********* //

        // ******* GET THE COLUMN START ********* //

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);

        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        $lycra_percentage = [ ['id'=> '0', 'name' => '0'] ];
        for($i=1;$i<=10;$i++) 
        {
            $value = [ 'id'=> $i, 'name' => $i ];
            array_push($lycra_percentage, $value);
        }

        $dyeing_type = [ 'FD', 'YDS', 'YDJ', 'SDB', 'DDB' ];

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '8%', 'align'=> 'left', 'readOnly'=> true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'align' => 'left', 'type' => 'dropdown', 'source' => $yarn_blend_data, 'multiple' => true, 'autoComplete'=> true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $yarn_content_data, 'multiple' => true, 'autoComplete'=> true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $yarn_count_data, 'multiple' => true, 'autoComplete'=> true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source'=> $fabric_name_data],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'right'],
            ['title' => "No. of Feed.\n Per Item", 'width' => '8%', 'align'=> 'center'],
            ['title' => "Lycra\n (%)", 'width' => '8%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $lycra_percentage],
            ['title' => "Dyeing\n Type", 'width' => '8%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $dyeing_type ],
            ['title' => "Fin. DIA / DIM\n (W*H)", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Unit of\n Measure", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "DIA Wise Plan.\n Fab. Wgt. (Kgs.)", 'width' => '10%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Plan. Fab. Wgt.\n Subtotal (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "total_avg", 'type'=> 'hidden'],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        $output['yarn_blend_data'] = $yarn_blend_data;

        //PRINT_R($output['data']);
        return $output;
    }

    public function updateItemizedFabricRequirementDetailss($req_data, $id)
    {
        foreach ($req_data as $key => $value)
        {
            if($value[2] != '' && $value[3] != '' && $value[4] != '' && $value[5])
            {
                $fabricList["enquiry_id"] = $id;
                $fabricList["itemized_fabric_requirement_id"] = $value[1];
                $fabricList["yarn_blend"] = $value[6];
                $fabricList["yarn_content"] = $value[7];
                $fabricList["yarn_count"] = $value[8];
                $fabricList["fabric_name"] = $value[9];
                $fabricList["finishing_gsm"] = $value[10];
                $fabricList["no_of_feed_pi"] = $value[11];
                $fabricList["lycra"] = $value[12];
                $fabricList["dyeing_type"] = $value[13];
                if($value[0] == "edit" && $fabricList["itemized_fabric_requirement_id"] !="") {
                    $this->db->where('enquiry_id', $fabricList["enquiry_id"]);
                    $this->db->where('itemized_fabric_requirement_id', $fabricList["itemized_fabric_requirement_id"]);
                    $this->db->update('tbl_fab_itemized_fabric_requirement', $fabricList);
                }
                else {
                    unset($fabricList["itemized_fabric_requirement_id"]);
                    $this->db->insert('tbl_fab_itemized_fabric_requirement', $fabricList);
                }
            }
        }
    }

    // ********** ITEMIZED FABRIC REQUIREMENT DETAILS ENDS HERE *********** /

    // ********** YARN DYEING COLOUR WISE QTY DETAILS STARTS HERE *********** /
        
     public function getYarnDyeingColourWiseQtyDetailss_old($id) {

        $itemize_fabric = $this->getItemizedFabricRequirementDetailss($id);
        $itemized_data = $itemize_fabric['data'];

        $sql2 = "SELECT yarn_spe_req, yarn_purchase_type FROM tbl_fab_itemized_fabric_requirement WHERE enquiry_id = '$id' AND (dyeing_type = 'YDS' OR dyeing_type = 'YDJ')";
        $yarnData = $this->db->query($sql2)->result_array();

        $itemized_fabric_data = [];
        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $type = $itemized_data[$i][13];
            if($combo != '' && $component != '' && $colour != '' && $gpdname != '' && ($type == 'YDS' || $type == 'YDJ')) {
                array_push($itemized_fabric_data, $itemized_data[$i]);
            }
        }

        // ******* GET THE DATA DETAILS ENDS ********* //

        //***** final data *****
        $finalValue = [];

        for($i=0; $i < sizeof($itemized_fabric_data); $i++) {

            $id            = $itemized_fabric_data[$i][1];
            $combo         = $itemized_fabric_data[$i][2];
            $component     = $itemized_fabric_data[$i][3];
            $colour        = $itemized_fabric_data[$i][4];
            $gpdname       = $itemized_fabric_data[$i][5];
            $yarn_blend    = $itemized_fabric_data[$i][6];
            $yarn_content  = $itemized_fabric_data[$i][7];
            $yarn_count    = $itemized_fabric_data[$i][8];
            $fabric_name   = $itemized_fabric_data[$i][9];
            $finishing_gsm = $itemized_fabric_data[$i][10];
            $no_of_feed_pi = $itemized_fabric_data[$i][11];
            $lycra         = $itemized_fabric_data[$i][12];
            $dyeing_type   = $itemized_fabric_data[$i][13];
            $total_avg     = $itemized_fabric_data[$i][18];
            $yarn_req     = $yarnData[$i]['yarn_spe_req'];
            $yarn_pur_type     = $yarnData[$i]['yarn_purchase_type'];

            $split_colour = [];
            $split_yarn_blend = [];
            $split_yarn_content = [];
            $split_yarn_count = [];
            $split_no_of_feed_pi = [];
            $split_total_feed = [];
            $split_yarn_req = [];
            $split_yarn_pur_type = [];
            $split = [];

            $split_color = [];
            $split_plan_yarn_wet = [];

            $split = explode(":", $colour);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_colour, $value);
            }

            $split = explode(";", $yarn_blend);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_blend, $value);
            }
            
            $split = explode(";", $yarn_content);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_content, $value);
            }
            
            $split = explode(";", $yarn_count);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_count, $value);
            }

            $split = explode(",", $yarn_req);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_req, $value);
            }

            $split = explode(",", $yarn_pur_type);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_pur_type, $value);
            }

            $split = explode("/", $no_of_feed_pi);
            $totalNoFeed = 0;
            foreach ($split as $key => $value) {
                $totalNoFeed += (int)$value;
            }

            $split = explode("/", $no_of_feed_pi);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_no_of_feed_pi, $value);

                $colour_per = (float)$value * 100 / (float)$totalNoFeed;
                $colour_per = number_format((float)$colour_per, 2, '.', '');
                // $colour_per = (int)$colour_per;
                array_push($split_color, $colour_per);

                $plan_yarn_wet = (float)$total_avg * (float)$colour_per / 100;
                $plan_yarn_wet = number_format((float)$plan_yarn_wet, 3, '.', '');
                array_push($split_plan_yarn_wet, $plan_yarn_wet);
            }

            // return $split_yarn_blend;

            for($j=0; $j < sizeof($split_yarn_blend); $j++) 
            {
                if(sizeof($split_yarn_req) > 0) {
                    $combineValue = [ 'edit', $id, $combo, $component, $split_colour[$j], $gpdname, $split_yarn_blend[$j], $split_yarn_content[$j], 
                        $split_yarn_count[$j], $fabric_name, $finishing_gsm, $lycra, $dyeing_type, $totalNoFeed, $split_no_of_feed_pi[$j], 
                        $split_color[$j], $total_avg, $split_plan_yarn_wet[$j], $split_yarn_req[$j], $split_yarn_pur_type[$j]];
                }
                else {
                    $combineValue = [ 'edit', $id, $combo, $component, $split_colour[$j], $gpdname, $split_yarn_blend[$j], $split_yarn_content[$j], 
                    $split_yarn_count[$j], $fabric_name, $finishing_gsm, $lycra, $dyeing_type, $totalNoFeed, $split_no_of_feed_pi[$j], 
                    $split_color[$j], $total_avg, $split_plan_yarn_wet[$j], '', ''];
                }
                array_push($finalValue, $combineValue);
            }

        }

        // ******* GET THE DATA DETAILS START ********* //
        
        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        // ******* GET THE COLUMN START ********* //

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'left', 'readOnly'=> true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type' => 'dropdown', 'source' => $fabric_name_data],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Lycra (%)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Dyeing\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "No. of Feed.\n Per Repeat", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "No. of Feed.\n Per Colour", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Colour\n (%)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Plan. Fab. Wgt.\n Subtotal (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "yarn_req", 'type' => 'hidden'],
            ['title' => "yarn_purchase_type", 'type' => 'hidden'],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }
      public function getYarnDyeingColourWiseQtyDetailss($id) {

        $itemize_fabric = $this->getItemizedFabricRequirementDetailss($id);
        $itemized_data = $itemize_fabric['data'];

        $sql2 = "SELECT yarn_spe_req, yarn_purchase_type FROM tbl_fab_itemized_fabric_requirement WHERE enquiry_id = '$id' AND (dyeing_type = 'YDS' OR dyeing_type = 'YDJ')";
        $yarnData = $this->db->query($sql2)->result_array();

        $itemized_fabric_data = [];
        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $type = $itemized_data[$i][13];
            if($combo != '' && $component != '' && $colour != '' && $gpdname != '' && ($type == 'YDS' || $type == 'YDJ')) {
                array_push($itemized_fabric_data, $itemized_data[$i]);
            }
        }

        // ******* GET THE DATA DETAILS ENDS ********* //

        //***** final data *****
        $finalValue = [];

        for($i=0; $i < sizeof($itemized_fabric_data); $i++) {

            $id            = $itemized_fabric_data[$i][1];
            $combo         = $itemized_fabric_data[$i][2];
            $component     = $itemized_fabric_data[$i][3];
            $colour        = $itemized_fabric_data[$i][4];
            $gpdname       = $itemized_fabric_data[$i][5];
            $yarn_blend    = $itemized_fabric_data[$i][6];
            $yarn_content  = $itemized_fabric_data[$i][7];
            $yarn_count    = $itemized_fabric_data[$i][8];
            $fabric_name   = $itemized_fabric_data[$i][9];
            $finishing_gsm = $itemized_fabric_data[$i][10];
            $no_of_feed_pi = $itemized_fabric_data[$i][11];
            $lycra         = $itemized_fabric_data[$i][12];
            $dyeing_type   = $itemized_fabric_data[$i][13];
            $total_avg     = $itemized_fabric_data[$i][18];
            $yarn_req     = $yarnData[$i]['yarn_spe_req'];
            $yarn_pur_type     = $yarnData[$i]['yarn_purchase_type'];

            $split_colour = [];
            $split_yarn_blend = [];
            $split_yarn_content = [];
            $split_yarn_count = [];
            $split_no_of_feed_pi = [];
            $split_total_feed = [];
            $split_yarn_req = [];
            $split_yarn_pur_type = [];
            $split = [];

            $split_color = [];
            $split_plan_yarn_wet = [];

            $split = explode(":", $colour);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_colour, $value);
            }

            $split = explode(";", $yarn_blend);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_blend, $value);
            }
            
            $split = explode(";", $yarn_content);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_content, $value);
            }
            
            $split = explode(";", $yarn_count);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_count, $value);
            }

            $split = explode(",", $yarn_req);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_req, $value);
            }

            $split = explode(",", $yarn_pur_type);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_pur_type, $value);
            }

            $split = explode("/", $no_of_feed_pi);
            $totalNoFeed = 0;
            foreach ($split as $key => $value) {
                $totalNoFeed += (int)$value;
            }

            $split = explode("/", $no_of_feed_pi);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_no_of_feed_pi, $value);

                $colour_per = (float)$value * 100 / (float)$totalNoFeed;
                $colour_per = number_format((float)$colour_per, 2, '.', '');
                // $colour_per = (int)$colour_per;
                array_push($split_color, $colour_per);

                $plan_yarn_wet = (float)$total_avg * (float)$colour_per / 100;
                $plan_yarn_wet = number_format((float)$plan_yarn_wet, 3, '.', '');
                array_push($split_plan_yarn_wet, $plan_yarn_wet);
            }

            // return $split_yarn_blend;

            for($j=0; $j < sizeof($split_yarn_blend); $j++) 
            {
                if(sizeof($split_yarn_req) > 0) {
                    // $combineValue = [ 'edit', $id, $combo, $component, $split_colour[$j], $gpdname, $split_yarn_blend[$j], $split_yarn_content[$j], 
                    //     $split_yarn_count[$j], $fabric_name, $finishing_gsm, $lycra, $dyeing_type, $totalNoFeed, $split_no_of_feed_pi[$j], 
                    //     $split_color[$j], $total_avg, $split_plan_yarn_wet[$j], $split_yarn_req[$j], $split_yarn_pur_type[$j]];
                $combineValue = [ 'edit', $id, $combo, $component, $split_colour[$j] ?? '', $gpdname, $split_yarn_blend[$j] ?? '', $split_yarn_content[$j] ?? '', 
                     $split_yarn_count[$j] ?? '',$fabric_name, $finishing_gsm,$lycra,$dyeing_type,$totalNoFeed,$split_no_of_feed_pi[$j] ?? 0,$split_color[$j] ?? 0,
                     $total_avg,$split_plan_yarn_wet[$j] ?? 0,$split_yarn_req[$j] ?? '',$split_yarn_pur_type[$j] ?? ''];
                    }
                else {
                    $combineValue = [ 'edit', $id, $combo, $component, $split_colour[$j], $gpdname, $split_yarn_blend[$j], $split_yarn_content[$j], 
                    $split_yarn_count[$j], $fabric_name, $finishing_gsm, $lycra, $dyeing_type, $totalNoFeed, $split_no_of_feed_pi[$j], 
                    $split_color[$j], $total_avg, $split_plan_yarn_wet[$j], '', ''];
                }
                array_push($finalValue, $combineValue);
            }

        }

        // ******* GET THE DATA DETAILS START ********* //
        
        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        // ******* GET THE COLUMN START ********* //

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'left', 'readOnly'=> true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type' => 'dropdown', 'source' => $fabric_name_data],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Lycra (%)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Dyeing\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "No. of Feed.\n Per Repeat", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "No. of Feed.\n Per Colour", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Colour\n (%)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Plan. Fab. Wgt.\n Subtotal (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "yarn_req", 'type' => 'hidden'],
            ['title' => "yarn_purchase_type", 'type' => 'hidden'],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    // ********** YARN DYEING COLOUR WISE QTY DETAILS ENDS HERE *********** /

    // ********** YARN SINGLE DOUBLE DYE BATH DETAILS STARTS HERE *********** /
        
    public function getSingleDoubleDyeBathDetailss($id) {

        $itemize_fabric = $this->getItemizedFabricRequirementDetailss($id);
        $itemized_data = $itemize_fabric['data'];

        $sql2 = "SELECT yarn_spe_req, yarn_purchase_type FROM tbl_fab_itemized_fabric_requirement WHERE enquiry_id = '$id' AND (dyeing_type = 'SDB' OR dyeing_type = 'DDB')";
        $yarnData = $this->db->query($sql2)->result_array();

        $itemized_fabric_data = [];
        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $type = $itemized_data[$i][13];
            if($combo != '' && $component != '' && $colour != '' && $gpdname != '' && ($type == 'SDB' || $type == 'DDB')) {
                array_push($itemized_fabric_data, $itemized_data[$i]);
            }
        }

        // ******* GET THE DATA DETAILS ENDS ********* //
        
        //***** final data *****

        $finalValue = [];

        for($i=0; $i < sizeof($itemized_fabric_data); $i++) {

            $id            = $itemized_fabric_data[$i][1];
            $combo         = $itemized_fabric_data[$i][2];
            $component     = $itemized_fabric_data[$i][3];
            $colour        = $itemized_fabric_data[$i][4];
            $gpdname       = $itemized_fabric_data[$i][5];
            $yarn_blend    = $itemized_fabric_data[$i][6];
            $yarn_content  = $itemized_fabric_data[$i][7];
            $yarn_count    = $itemized_fabric_data[$i][8];
            $fabric_name   = $itemized_fabric_data[$i][9];
            $finishing_gsm = $itemized_fabric_data[$i][10];
            $no_of_feed_pi = $itemized_fabric_data[$i][11];
            $lycra         = $itemized_fabric_data[$i][12];
            $dyeing_type   = $itemized_fabric_data[$i][13];
            $total_avg     = $itemized_fabric_data[$i][18];
            $yarn_req     = $yarnData[$i]['yarn_spe_req'];
            $yarn_pur_type     = $yarnData[$i]['yarn_purchase_type'];

            $split_yarn_blend = [];
            $split_yarn_content = [];
            $split_yarn_count = [];
            $split_no_of_feed_pi = [];
            $split_total_feed = [];
            $split_yarn_req = [];
            $split_yarn_pur_type = [];
            $split = [];

            $split_color = [];
            $split_plan_yarn_wet = [];

            $split = explode(";", $yarn_blend);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_blend, $value);
            }
            
            $split = explode(";", $yarn_content);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_content, $value);
            }
            
            $split = explode(";", $yarn_count);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_count, $value);
            }

            $split = explode(",", $yarn_req);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_req, $value);
            }

            $split = explode(",", $yarn_pur_type);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_pur_type, $value);
            }

            $split = explode("/", $no_of_feed_pi);
            $totalNoFeed = 0;
            foreach ($split as $key => $value) {
                $totalNoFeed += (int)$value;
            }

            $split = explode("/", $no_of_feed_pi);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_no_of_feed_pi, $value);

                $colour_per = (float)$value / 100 * (float)$totalNoFeed;
                $colour_per = number_format((float)$colour_per, 3, '.', '');
                array_push($split_color, $colour_per);

                $plan_yarn_wet = (float)$total_avg * (float)$colour_per / 100;
                $plan_yarn_wet = number_format((float)$plan_yarn_wet, 3, '.', '');
                array_push($split_plan_yarn_wet, $plan_yarn_wet);
            }

            for($j=0; $j < sizeof($split_yarn_blend); $j++) 
            {
                if(sizeof($split_yarn_req) > 0) {
                    $combineValue = [ 'edit', $id, $combo, $component, $colour, $gpdname, $split_yarn_blend[$j], $split_yarn_content[$j], 
                        $split_yarn_count[$j], $fabric_name, $finishing_gsm, $lycra, $dyeing_type, $totalNoFeed, $split_no_of_feed_pi[$j], 
                        $split_color[$j], $total_avg, $split_plan_yarn_wet[$j], $split_yarn_req[$j], $split_yarn_pur_type[$j]];
                }
                else {
                    $combineValue = [ 'edit', $id, $combo, $component, $colour, $gpdname, $split_yarn_blend[$j], $split_yarn_content[$j], 
                    $split_yarn_count[$j], $fabric_name, $finishing_gsm, $lycra, $dyeing_type, $totalNoFeed, $split_no_of_feed_pi[$j], 
                    $split_color[$j], $total_avg, $split_plan_yarn_wet[$j], '', ''];
                }

                array_push($finalValue, $combineValue);
            }

        }

        // ******* GET THE DATA DETAILS START ********* //
        
        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        // ******* GET THE COLUMN START ********* //

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'left', 'readOnly'=> true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type' => 'dropdown', 'source' => $fabric_name_data],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Lycra (%)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Dyeing\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "No. of Feed.\n Per Repeat", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "No. of Feed.\n Per Colour", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Colour\n (%)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Plan. Fab. Wgt.\n Subtotal (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "yarn_req", 'type' => 'hidden'],
            ['title' => "yarn_purchase_type", 'type' => 'hidden'],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    // ********** YARN SINGLE DOUBLE DYE BATH DETAILS ENDS HERE *********** /

    // ********** YARN PROGRAMME DETAILS STARTS HERE *********** /
        
/*************  ✨ Windsurf Command ⭐  *************/
/**
 * This function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.
 * The function will return the programme details in a JSON format.
 * The function will return the programme details for yarn and fabric.

/*******  c4046871-8649-4d27-8ff1-c4be6ab1a1c3  *******/
    public function getYarnProgrammeDetailss($id) {

        $itemize_fabric = $this->getItemizedFabricRequirementDetailss($id);
        $itemized_data = $itemize_fabric['data'];

        //PRINT_R($itemized_data);

        $yarn_dyeing = $this->getYarnDyeingColourWiseQtyDetailss($id);
        $yarn_dyeing_data = $yarn_dyeing['data'];

        $single_double_dye = $this->getSingleDoubleDyeBathDetailss($id);
        $single_double_dye_data = $single_double_dye['data'];

        $sql2 = "SELECT yarn_spe_req, yarn_purchase_type,dyeing_type FROM tbl_fab_itemized_fabric_requirement WHERE enquiry_id = '$id' AND  dyeing_type  = 'FD' ";
        //$sql2 = "SELECT yarn_spe_req, yarn_purchase_type FROM tbl_fab_itemized_fabric_requirement WHERE enquiry_id = '$id'ORDER BY yarn_purchase_type ASC";
        $yarnData = $this->db->query($sql2)->result_array();
        
       // print_r($yarnData);
        
        //print_r($yarnData);

        $itemized_fabric_data = [];
        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $type = $itemized_data[$i][13];
            if($combo != '' && $component != '' && $colour != '' && $gpdname != '' && $type == 'FD') {
                array_push($itemized_fabric_data, $itemized_data[$i]);
            }
            //  if($combo != '' && $component != '' && $colour != '' && $gpdname != '' && ($type == 'FD' || $type == 'YDS' || $type == 'YDJ') ) {
            //     array_push($itemized_fabric_data, $itemized_data[$i]);
            // }
        }

        // ******* GET THE DATA DETAILS ENDS ********* //

        //***** final data *****
        //PRINT_R($itemized_fabric_data[4]);
        $oldfinalValue = [];

        for($i=0; $i < sizeof($itemized_fabric_data); $i++) {
            
           // print_r()

            $id            = $itemized_fabric_data[$i][1];
            $combo         = $itemized_fabric_data[$i][2];
            $component     = $itemized_fabric_data[$i][3];
            $colour        = $itemized_fabric_data[$i][4];
            $gpdname       = $itemized_fabric_data[$i][5];
            $yarn_blend    = $itemized_fabric_data[$i][6];
            $yarn_content  = $itemized_fabric_data[$i][7];
            $yarn_count    = $itemized_fabric_data[$i][8];
            $fabric_name   = $itemized_fabric_data[$i][9];
            $finishing_gsm = $itemized_fabric_data[$i][10];
            $no_of_feed_pi = $itemized_fabric_data[$i][11];
            $lycra         = $itemized_fabric_data[$i][12];
            $dyeing_type   = $itemized_fabric_data[$i][13];
            $total_avg     = $itemized_fabric_data[$i][18];
            $yarn_req     = $yarnData[$i]['yarn_spe_req'];
            $yarn_pur_type     = $yarnData[$i]['yarn_purchase_type'];

            $split_yarn_blend = [];
            $split_yarn_content = [];
            $split_yarn_count = [];
            $split_no_of_feed_pi = [];
            $split_total_feed = [];
            $split_yarn_req = [];
            $split_yarn_pur_type = [];
            $split = [];

            $split_color = [];
            $split_plan_yarn_wet = [];

            $split = explode(";", $yarn_blend);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_blend, $value);
            }
            
            $split = explode(";", $yarn_content);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_content, $value);
            }
            
            $split = explode(";", $yarn_count);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_count, $value);
            }

            $split = explode(",", $yarn_req);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_req, $value);
            }

            $split = explode(",", $yarn_pur_type);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_pur_type, $value);
            }

            $split = explode("/", $no_of_feed_pi);
            $totalNoFeed = 0;
            foreach ($split as $key => $value) {
                $totalNoFeed += (int)$value;
            }

            $split = explode("/", $no_of_feed_pi);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_no_of_feed_pi, $value);

                $colour_per = (float)$value / 100 * (float)$totalNoFeed;
                $colour_per = number_format((float)$colour_per, 3, '.', '');
                array_push($split_color, $colour_per);

                $plan_yarn_wet = (float)$total_avg * (float)$colour_per / 100;
                $plan_yarn_wet = number_format((float)$plan_yarn_wet, 3, '.', '');
                array_push($split_plan_yarn_wet, $plan_yarn_wet);
            }
//PRINT_R($split_yarn_pur_type);
            for($j=0; $j < sizeof($split_yarn_blend); $j++) 
            {
                if(sizeof($split_yarn_req) > 0) {
                //     $combineValue = [ 'edit', $id, $combo, $component, $colour, $gpdname, $split_yarn_blend[$j], $split_yarn_content[$j], 
                //         $split_yarn_count[$j], $fabric_name, $finishing_gsm, $lycra, $dyeing_type, $totalNoFeed, $split_no_of_feed_pi[$j], 
                //         $split_color[$j], $total_avg, $split_plan_yarn_wet[$j], $split_yarn_req[$j], $split_yarn_pur_type[$j]];
               $combineValue = ['edit',$id ?? '',$combo ?? '',$component ?? '',$colour ?? '',$gpdname ?? '',$split_yarn_blend[$j] ?? '',$split_yarn_content[$j] ?? '',
    $split_yarn_count[$j] ?? '',$fabric_name ?? '',$finishing_gsm ?? '',$lycra ?? '',$dyeing_type ?? '',$totalNoFeed ?? '',$split_no_of_feed_pi[$j] ?? '',
    $split_color[$j] ?? '',$total_avg ?? '',$split_plan_yarn_wet[$j] ?? '',$split_yarn_req[$j] ?? '',
    $split_yarn_pur_type[$j] ?? ''
];
                }
                else {
                    // $combineValue = [ 'edit', $id, $combo, $component, $colour, $gpdname, $split_yarn_blend[$j], $split_yarn_content[$j], 
                    // $split_yarn_count[$j], $fabric_name, $finishing_gsm, $lycra, $dyeing_type, $totalNoFeed, $split_no_of_feed_pi[$j], 
                    // $split_color[$j], $total_avg, $split_plan_yarn_wet[$j], '', ''];
                    $combineValue = ['edit',$id,$combo, $component,$colour,$gpdname,$split_yarn_blend[$j] ?? '',$split_yarn_content[$j] ?? '',$split_yarn_count[$j] ?? '',
                              $fabric_name,$finishing_gsm,$lycra,$dyeing_type,$totalNoFeed,$split_no_of_feed_pi[$j] ?? 0,$split_color[$j] ?? 0,$total_avg,
                              $split_plan_yarn_wet[$j] ?? 0,'',''];
                }
                array_push($oldfinalValue, $combineValue);
            }

        }
        
        //print_r($oldfinalValue);

        $oldfinalValue = array_merge($oldfinalValue, $yarn_dyeing_data);
        $oldfinalValue = array_merge($oldfinalValue, $single_double_dye_data);

        //PRINT_R($oldfinalValue);

        // *** final data *** //

        $finalValue = [];
        for ($i=0; $i < sizeof($oldfinalValue); $i++) { 

            $lycraWeight = (float)$oldfinalValue[$i][17] * (float)$oldfinalValue[$i][11] / 100;
            $lycraWeight = number_format((float)$lycraWeight, 3, '.', '');

            $reqYarnWeight = (float)$oldfinalValue[$i][17] - (float)$lycraWeight;
            $reqYarnWeight = number_format((float)$reqYarnWeight, 3, '.', '');

            $combineValue = [ 'edit', $oldfinalValue[$i][1], $oldfinalValue[$i][2], $oldfinalValue[$i][3], $oldfinalValue[$i][4], $oldfinalValue[$i][5],
                $oldfinalValue[$i][6], $oldfinalValue[$i][7], $oldfinalValue[$i][8], $oldfinalValue[$i][18], $oldfinalValue[$i][19], $oldfinalValue[$i][12], $oldfinalValue[$i][17],
                $oldfinalValue[$i][11], $lycraWeight, $reqYarnWeight, $oldfinalValue[$i][9], $oldfinalValue[$i][10]
            ];

            array_push($finalValue, $combineValue);

        }

        // ******* GET THE DATA DETAILS START ********* //
        
        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        $yarn_req_sql = "SELECT id, yarnsplreq as name FROM kn_master_yarn_spl_req WHERE status=1";
        $yarn_req_data = $this->db->query($yarn_req_sql)->result_array();

        // ******* GET THE COLUMN START ********* //

        // ******* GET THE DATA DETAILS START ********* //

        // ******* GET THE COLUMN START ********* //

        $asdas = [];
        $yarnPurchase = [ 'Greige', 'Coloured', 'Melange' ];

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'left', 'readOnly'=> true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Yarn Special\n Request.", 'width' => '8%', 'align'=> 'center', 'type'=> 'dropdown', 'source'=> $yarn_req_data],
            ['title' => "Yarn Purchase\n Type", 'width' => '8%', 'align'=> 'center', 'type'=> 'dropdown', 'source'=> $yarnPurchase],
            ['title' => "Dyeing\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Lycra\n (%)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Lycra Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Reqd. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "fabricname", 'type' => 'hidden'],
            ['title' => "gsm", 'type' => 'hidden'],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }



    public function updateYarnProgrammeDetailss_old($req_data, $id)
    {

       // print_r($req_data);
        $res = $result = [];

        array_map(function($v) use (&$res) {
            array_key_exists($v[1], $res) ?
                (
                    $res[$v[1]]['yarn_req'] = $res[$v[1]]['yarn_req'].','.$v[9]
                )
            :
                (
                    $res[$v[1]] = ['mode' => $v[0], 'id' => $v[1], 'yarn_req' => $v[9]]
                );
        }, $req_data);

        array_map(function($v) use (&$result) {
            array_key_exists($v[1], $result) ?
                (
                    $result[$v[1]]['yarn_pur_type'] = $result[$v[1]]['yarn_pur_type'].','.$v[10]
                )
            :
                (
                    $result[$v[1]] = ['yarn_pur_type' => $v[10]]
                );
        }, $req_data);
        
        $res = array_values($res);
        $result = array_values($result);

        for ($i=0; $i < sizeof($res); $i++) {
            $fabricList["enquiry_id"] = $id;
            $fabricList["itemized_fabric_requirement_id"] = $res[$i]['id'];
            $fabricList["yarn_spe_req"] = $res[$i]['yarn_req'];
            $fabricList["yarn_purchase_type"] = $result[$i]['yarn_pur_type'];
            if($res[$i]['mode'] == "edit" && $fabricList["itemized_fabric_requirement_id"] !="") {
                $this->db->where('enquiry_id', $fabricList["enquiry_id"]);
                $this->db->where('itemized_fabric_requirement_id', $fabricList["itemized_fabric_requirement_id"]);
                $this->db->update('tbl_fab_itemized_fabric_requirement', $fabricList);
            }
        }
    }

    public function updateYarnProgrammeDetailss_local($req_data, $id)
{
    foreach ($req_data as $row) {

        if ($row[0] !== 'edit' || empty($row[1])) {
            continue;
        }

        $fabricList = [
            'enquiry_id' => $id,
            'itemized_fabric_requirement_id' => $row[1],
            'yarn_spe_req' => $row[9],
            'yarn_purchase_type' => $row[10]
        ];

        $this->db->where('enquiry_id', $id);
        $this->db->where('itemized_fabric_requirement_id', $row[1]);
        $this->db->update(
            'tbl_fab_itemized_fabric_requirement',
            $fabricList
        );
    }
}

public function updateYarnProgrammeDetailss($req_data, $id)
{
    if (empty($req_data) || empty($id)) {
        return false;
    }

    $rows = [];

    foreach ($req_data as $v) {

        $mode        = $v[0]  ?? '';
        $itemId      = $v[1]  ?? '';
        $yarnReq     = $v[9]  ?? '';
        $yarnPurType = $v[10] ?? '';

        if ($mode !== 'edit' || $itemId === '') {
            continue;
        }

        if (!isset($rows[$itemId])) {
            $rows[$itemId] = [
                'yarn_req' => '',
                'yarn_pur_type' => ''
            ];
        }

        if ($yarnReq !== '') {
            $rows[$itemId]['yarn_req'] .=
                ($rows[$itemId]['yarn_req'] ? ',' : '') . $yarnReq;
        }

        if ($yarnPurType !== '') {
            $rows[$itemId]['yarn_pur_type'] .=
                ($rows[$itemId]['yarn_pur_type'] ? ',' : '') . $yarnPurType;
        }
    }

    foreach ($rows as $itemId => $data) {

        $fabricList = [
            'enquiry_id' => $id,
            'itemized_fabric_requirement_id' => $itemId,
            'yarn_spe_req' => $data['yarn_req'],
            'yarn_purchase_type' => $data['yarn_pur_type']
        ];

        $this->db->where('enquiry_id', $id);
        $this->db->where('itemized_fabric_requirement_id', $itemId);
        $this->db->update('tbl_fab_itemized_fabric_requirement', $fabricList);
    }

    return true;
}

    // ********** YARN PROGRAMME DETAILS ENDS HERE *********** /

    // ********** YARN REQUIREMENT DETAILS STARTS HERE *********** /
        
    public function getYarnRequirementDetailss_oldd($id) {

        $yarn_programme = $this->getYarnProgrammeDetailss($id);
        $yarn_programme_data = $yarn_programme['data'];

        $sql2 = "SELECT * FROM tbl_fab_yarn_requirement WHERE enquiry_id = '$id'  ";
        $yarnData = $this->db->query($sql2)->result_array();
        
        $array = [];
        $comboValue = [];
        foreach ($yarn_programme_data as $key => $value)
        {
            $comboValue["id"] = $value[1];
            $comboValue["color"] = $value[4];
            if($value[10] == 'Greige') {
                $comboValue["color"] = $value[10];
            }
            else {
                $comboValue["color"] = $value[4];
            }
            $comboValue["blend"] = $value[6];
            $comboValue["content"] = $value[7];
            $comboValue["count"] = $value[8];
            $comboValue["yarn_req"] = $value[9];
            $comboValue["yarn_purchase_type"] = $value[10];
            $comboValue["reqd_yarn_wgt"] = $value[15];
            // Yarn Blend
            $blend_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[6]."' ";
            $blend_data = $this->db->query($blend_sql)->result_array();
            $comboValue["blend_name"] = $blend_data[0]['misc_name'];
            // Yarn Content
            $content_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[7]."'";
            $content_data = $this->db->query($content_sql)->result_array();
            $comboValue["content_name"] = $content_data[0]['misc_name'];
            // Yarn Count
            $count_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[8]."'";
            $count_data = $this->db->query($count_sql)->result_array();
            $comboValue["count_name"] = $count_data[0]['misc_name'];

            array_push($array, $comboValue);
        }

        $userNames = array();
        foreach($array as $key => $value) {
            $check = [$value["blend_name"], $value["content_name"], $value['count_name'], $value["yarn_req"], $value["yarn_purchase_type"], $value['color']];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);
                $array[$old_key]['reqd_yarn_wgt'] = (float)$array[$old_key]['reqd_yarn_wgt'] + (float)$value['reqd_yarn_wgt'];
                $array[$old_key]['reqd_yarn_wgt'] = number_format((float)$array[$old_key]['reqd_yarn_wgt'], 3, '.', '');

                unset($array[$key]);
            }
            $userNames[] = $check;
        }

        $array = array_values($array);

        // return $yarnData;

        $finalValue = [];

        for ($i=0; $i < sizeof($array); $i++) {
            // if($array[$i]['yarn_purchase_type'] == 'Greige') {
            //     $yarn_purchase_type = $array[$i]['yarn_purchase_type'];
            // }
            // else {
            //     $yarn_purchase_type = $array[$i]['color'];
            // }
            if(sizeof($yarnData) == 0)
            {
                $combineValue = [ '', '', '', '', $array[$i]['blend'], $array[$i]['content'], $array[$i]['count'], $array[$i]['yarn_req'], $array[$i]['yarn_purchase_type'], $array[$i]['color'], $array[$i]['reqd_yarn_wgt'], '', '' ];
                array_push($finalValue, $combineValue);
            }
            else {
                //$combineValue = [ 'edit', $yarnData[$i]['fab_yarn_req_id'], $yarnData[$i]['yarn_vendor_brand'], $yarnData[$i]['yarn_product_code'], $array[$i]['blend'], $array[$i]['content'], $array[$i]['count'], $array[$i]['yarn_req'], $array[$i]['yarn_purchase_type'], $array[$i]['color'], $array[$i]['reqd_yarn_wgt'], '', '' ];
                
                  $combineValue = [
    'edit',
    $yarnData[$i]['fab_yarn_req_id'] ?? '',
    $yarnData[$i]['yarn_vendor_brand'] ?? '',
    $yarnData[$i]['yarn_product_code'] ?? '',
    $array[$i]['blend'] ?? '',
    $array[$i]['content'] ?? '',
    $array[$i]['count'] ?? '',
    $array[$i]['yarn_req'] ?? '',
    $array[$i]['yarn_purchase_type'] ?? '',
    $array[$i]['color'] ?? '',
    $array[$i]['reqd_yarn_wgt'] ?? '',
    '',
    ''
];
                array_push($finalValue, $combineValue);
            }
        }

        // ******* GET THE DATA DETAILS START ********* //

        // ******* GET THE COLUMN START ********* //
            $masterAdmins = $this->master_loginid();
            $subscriber_ids = array_column($masterAdmins, 'subscriber_id');



        // commented by myself regards new form integration $yarn_vendor_sql = "SELECT id, yarnvendor as name FROM kn_master_yarn_vendor";
        
        //$yarn_vendor_sql = "SELECT id, vendor_name as name FROM ".KN_MASTER_YARNVENDOR." WHERE status=1";
        ///$yarnVendor = $this->db->query($yarn_vendor_sql)->result_array();

        $yarn_vendor_sql = "SELECT b.id, b.vendor_name as name FROM ".KN_MASTER_YARNVENDOR."  AS b LEFT JOIN " . KN_USERS . " AS u ON b.updatedby = u.id
                WHERE u.subscriber_id IN (" . implode(',', array_map('intval', $subscriber_ids)) . ")  AND b.status = 1";
        $yarnVendor = $this->db->query($yarn_vendor_sql)->result_array();



        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();
        
        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $yarn_req_sql = "SELECT id, yarnsplreq as name FROM kn_master_yarn_spl_req WHERE status=1";
        $yarn_req_data = $this->db->query($yarn_req_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Yarn - Vendor / Brand", 'width' => '8%', 'align' => 'left', 'type' => 'dropdown', 'source' => $yarnVendor],
            ['title' => "Yarn Product Code\n (Vendor)", 'width' => '8%', 'align'=> 'left'],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Yarn Special\n Request.", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true, 'type'=> 'dropdown', 'source'=> $yarn_req_data],
            ['title' => "Yarn Purchase\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Yarn Colour", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Prog. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Raise\n Yarn P.O.", 'width' => '8%', 'align'=> 'center', 'type' => 'checkbox', 'readOnly' => true],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

      
    public function getYarnRequirementDetailss($id)
{
     $yarn_programme = $this->getYarnProgrammeDetailss($id);
        $yarn_programme_data = $yarn_programme['data'];

        $sql2 = "SELECT * FROM tbl_fab_yarn_requirement WHERE enquiry_id = '$id'  ";
        $yarnData = $this->db->query($sql2)->result_array();
        
        $array = [];
        $comboValue = [];
        foreach ($yarn_programme_data as $key => $value)
        {
            $comboValue["id"] = $value[1];
            $comboValue["color"] = $value[4];
            if($value[10] == 'Greige') {
                $comboValue["color"] = $value[10];
            }
            else {
                $comboValue["color"] = $value[4];
            }
           $comboValue["blend"] = $value[6] ?? '';
           $comboValue["content"] = $value[7] ?? '';
          $comboValue["count"] = $value[8] ?? '';
         $comboValue["yarn_req"] = $value[9] ?? '';
         $comboValue["yarn_purchase_type"] = $value[10] ?? '';
         $comboValue["reqd_yarn_wgt"] = $value[15] ?? 0;
            // Yarn Blend
            $blend_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[6]."' ";
            $blend_data = $this->db->query($blend_sql)->result_array();
            $comboValue["blend_name"] = $blend_data[0]['misc_name'];
            // Yarn Content
            $content_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[7]."'";
            $content_data = $this->db->query($content_sql)->result_array();
            $comboValue["content_name"] = $content_data[0]['misc_name'];
            // Yarn Count
            $count_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[8]."'";
            $count_data = $this->db->query($count_sql)->result_array();
            $comboValue["count_name"] = $count_data[0]['misc_name'];

            array_push($array, $comboValue);
        }

        $userNames = array();
        foreach($array as $key => $value) {
            $check = [$value["blend_name"], $value["content_name"], $value['count_name'], $value["yarn_req"], $value["yarn_purchase_type"], $value['color']];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);
                $array[$old_key]['reqd_yarn_wgt'] = (float)$array[$old_key]['reqd_yarn_wgt'] + (float)$value['reqd_yarn_wgt'];
                $array[$old_key]['reqd_yarn_wgt'] = number_format((float)$array[$old_key]['reqd_yarn_wgt'], 3, '.', '');

                unset($array[$key]);
            }
            $userNames[] = $check;
        }

        $array = array_values($array);

        // return $yarnData;

        $finalValue = [];

        //$max = min(count($array), count($yarnData));

      for ($i = 0; $i < count($array); $i++) {
            // if($array[$i]['yarn_purchase_type'] == 'Greige') {
            //     $yarn_purchase_type = $array[$i]['yarn_purchase_type'];
            // }
            // else {
            //     $yarn_purchase_type = $array[$i]['color'];
            // }
            if(sizeof($yarnData) == 0)
            {
               // $combineValue = [ '', '', '', '', $array[$i]['blend'], $array[$i]['content'], $array[$i]['count'], $array[$i]['yarn_req'], $array[$i]['yarn_purchase_type'], $array[$i]['color'], $array[$i]['reqd_yarn_wgt'], '', '' ];
                 $combineValue = ['','','','',$array[$i]['blend'] ?? '',$array[$i]['content'] ?? '',$array[$i]['count'] ?? '',$array[$i]['yarn_req'] ?? '',$array[$i]['yarn_purchase_type'] ?? '',
                         $array[$i]['color'] ?? '',$array[$i]['reqd_yarn_wgt'] ?? 0, '', '' ];
                array_push($finalValue, $combineValue);
            }
            else {
                //$combineValue = [ 'edit', $yarnData[$i]['fab_yarn_req_id'], $yarnData[$i]['yarn_vendor_brand'], $yarnData[$i]['yarn_product_code'], $array[$i]['blend'], $array[$i]['content'], $array[$i]['count'], $array[$i]['yarn_req'], $array[$i]['yarn_purchase_type'], $array[$i]['color'], $array[$i]['reqd_yarn_wgt'], '', '' ];
               $combineValue = [
    'edit',
    $yarnData[$i]['fab_yarn_req_id'] ?? '',
    $yarnData[$i]['yarn_vendor_brand'] ?? '',
    $yarnData[$i]['yarn_product_code'] ?? '',
    $array[$i]['blend'] ?? '',
    $array[$i]['content'] ?? '',
    $array[$i]['count'] ?? '',
    $array[$i]['yarn_req'] ?? '',
    $array[$i]['yarn_purchase_type'] ?? '',
    $array[$i]['color'] ?? '',
    $array[$i]['reqd_yarn_wgt'] ?? '',
    '',
    ''
];
                array_push($finalValue, $combineValue);
            }
        }

        // ******* GET THE DATA DETAILS START ********* //

        // ******* GET THE COLUMN START ********* //
            $masterAdmins = $this->master_loginid();
            $subscriber_ids = array_column($masterAdmins, 'subscriber_id');



        // commented by myself regards new form integration $yarn_vendor_sql = "SELECT id, yarnvendor as name FROM kn_master_yarn_vendor";
        
        //$yarn_vendor_sql = "SELECT id, vendor_name as name FROM ".KN_MASTER_YARNVENDOR." WHERE status=1";
        ///$yarnVendor = $this->db->query($yarn_vendor_sql)->result_array();

        $yarn_vendor_sql = "SELECT b.id, b.vendor_name as name FROM ".KN_MASTER_YARNVENDOR."  AS b LEFT JOIN " . KN_USERS . " AS u ON b.updatedby = u.id
                WHERE u.subscriber_id IN (" . implode(',', array_map('intval', $subscriber_ids)) . ")  AND b.status = 1";
        $yarnVendor = $this->db->query($yarn_vendor_sql)->result_array();



        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();
        
        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $yarn_req_sql = "SELECT id, yarnsplreq as name FROM kn_master_yarn_spl_req WHERE status=1";
        $yarn_req_data = $this->db->query($yarn_req_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Yarn - Vendor / Brand", 'width' => '8%', 'align' => 'left', 'type' => 'dropdown', 'source' => $yarnVendor],
            ['title' => "Yarn Product Code\n (Vendor)", 'width' => '8%', 'align'=> 'left'],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Yarn Special\n Request.", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true, 'type'=> 'dropdown', 'source'=> $yarn_req_data],
            ['title' => "Yarn Purchase\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Yarn Colour", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Prog. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Raise\n Yarn P.O.", 'width' => '8%', 'align'=> 'center', 'type' => 'checkbox', 'readOnly' => true],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;

}
   
    public function updateYarnRequirementDetailss($req_data, $id)
    {
        foreach ($req_data as $key => $value)
        {
            $yarnList["enquiry_id"] = $id;
            $yarnList["fab_yarn_req_id"] = $value[1];
            $yarnList["yarn_vendor_brand"] = $value[2];
            $yarnList["yarn_product_code"] = $value[3];
            if($value[0] == "edit" && $yarnList["fab_yarn_req_id"] !="") {
                $this->db->where('enquiry_id', $yarnList["enquiry_id"]);
                $this->db->where('fab_yarn_req_id', $yarnList["fab_yarn_req_id"]);
                $this->db->update('tbl_fab_yarn_requirement', $yarnList);
            }
            else {
                $this->db->insert('tbl_fab_yarn_requirement', $yarnList);
            }
        }
    }

    // ********** YARN REQUIREMENT DETAILS ENDS HERE *********** /


    // ********** KNITTING PROGRAMME DETAILS STARTS HERE *********** /
    
    public function getKnittingProgrammeDetailss($id) {

        $fab_req = $this->getItemizedFabricRequirementDetailss($id);
        $itemized_data = $fab_req['data'];

        $sql2 = "SELECT * FROM tbl_fab_knitting_programme WHERE enquiry_id = '$id' ";
        $knittingData = $this->db->query($sql2)->result_array();

        $itemized_fabric_data = [];
        $lastKey = 0;
        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $blend = $itemized_data[$i][6];
            $content = $itemized_data[$i][7];
            $count = $itemized_data[$i][8];
            $fabric = $itemized_data[$i][9];
            $gsm = $itemized_data[$i][10];
            $feed = $itemized_data[$i][11];
            $lycra = $itemized_data[$i][12];
            $dyeing = $itemized_data[$i][13];
            $dia = $itemized_data[$i][14];
            $unit = $itemized_data[$i][15];
            $dia_fab = $itemized_data[$i][16];
            $plan_fab = $itemized_data[$i][17];

            if($combo != '' && $component != '' && $colour != '' && $gpdname != '') {
                $lastKey = $i;
                array_push($itemized_fabric_data, $itemized_data[$i]);
            }
            else {
                if($dia == "" && $unit == "" && $dia_fab == "") 
                {
                }
                else {
                    $arrayKey = sizeof($itemized_fabric_data) - 1;
                    // print_r($itemized_fabric_data);
                    // print_r($arrayKey);
                    $itemized_fabric_data[$arrayKey][14] = $itemized_fabric_data[$arrayKey][14].','.$dia;
                    $itemized_fabric_data[$arrayKey][15] = $itemized_fabric_data[$arrayKey][15].','.$unit;
                    $itemized_fabric_data[$arrayKey][16] = $itemized_fabric_data[$arrayKey][16].','.$dia_fab;
                }
            }
        }

        // exit();

        // return $itemized_fabric_data;

        $array = [];
        foreach ($itemized_fabric_data as $key => $value)
        {
            $comboValue["color"] = $value[4];
            $comboValue["parts"] = $value[5];
            $comboValue["blend"] = $value[6];
            $comboValue["content"] = $value[7];
            $comboValue["count"] = $value[8];
            $comboValue["fabric"] = $value[9];
            $comboValue["gsm"] = $value[10];
            $comboValue["feed"] = $value[11];
            $comboValue["lycra"] = $value[12];
            $comboValue["dyeing"] = $value[13];
            $comboValue["dia"] = $value[14];
            $comboValue["unit"] = $value[15];
            $comboValue["dia_fab"] = $value[16];
            array_push($array, $comboValue);
        }

        $userNames = array();
        foreach($array as $key => $value) {
            $check = [$value['parts'], $value["blend"], $value["content"], $value['count'], $value["fabric"], $value["gsm"], $value["feed"], $value['lycra'], $value["dyeing"]];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);
                $array[$old_key]['dia'] = $array[$old_key]['dia'].','.$value['dia'];
                $array[$old_key]['unit'] = $array[$old_key]['unit'].','.$value['unit'];
                $array[$old_key]['dia_fab'] = $array[$old_key]['dia_fab'].','.$value['dia_fab'];
                if($value["dyeing"] != 'YDS' || $value["dyeing"] != 'YDJ') {
                    unset($array[$key]);
                }
            }
            $userNames[] = $check;
        }

        $array = array_values($array);

        // return $array;

        for ($j=0; $j < sizeof($array); $j++) {
            $dia = explode(',', $array[$j]['dia']);
            $unit = explode(',', $array[$j]['unit']);
            $dia_fab = explode(',', $array[$j]['dia_fab']);
            $arr_dia = array_values(array_unique($dia));

            $arr_unit = [];
            $arr_dia_fab = [];

            for ($k=0; $k < sizeof($arr_dia); $k++) { 
                $dis_size = $arr_dia[$k];
                $totalPrice = 0;
                $totalUnit = '';
                for ($l=0; $l < sizeof($dia); $l++) {
                    $old_dia_size = $dia[$l];
                    $old_unit = $unit[$l];
                    $old_dia_fab = $dia_fab[$l];
                    if($dis_size == $old_dia_size) {
                        $totalUnit = $old_unit;
                        $totalPrice += (float)$old_dia_fab;
                        $totalPrice = number_format((float)$totalPrice, 3, '.', '');
                    }
                }
                array_push($arr_unit, $totalUnit);
                array_push($arr_dia_fab, $totalPrice);
            }
            $array[$j]['dia'] = $arr_dia;
            $array[$j]['unit'] = $arr_unit;
            $array[$j]['dia_fab'] = $arr_dia_fab;
        }

        $finalValue = [];
        foreach($array as $key => $value) {
            $color   = $value["color"];
            $parts   = $value["parts"];
            $blend   = $value["blend"];
            $content = $value["content"];
            $count   = $value["count"];
            $fabric  = $value["fabric"];
            $gsm     = $value["gsm"];
            $feed    = $value["feed"];
            $lycra   = $value["lycra"];
            $dyeing  = $value["dyeing"];
            $dia  = $value["dia"];
            $unit  = $value["unit"];
            $dia_fab  = $value["dia_fab"];

            $arr_blend = explode(';', $blend);
            $arr_blend = array_filter($arr_blend);
            if($dyeing == 'YDS' || $dyeing == 'YDJ') {
                $yarn_color = $color;
            }
            else {
                $yarn_color = 'Greige';
                // print_r(sizeof($arr_blend));
                // print_r(sizeof($dia));
                // exit();
                if(sizeof($arr_blend) > 1) {
                    for ($i=0; $i < sizeof($arr_blend)-1; $i++) { 
                        $yarn_color = $yarn_color.':'.'Greige';
                    }
                }
            }
            
            $totalSizePrice = number_format((float)array_sum($dia_fab), 3, '.', '');

            for ($i=0; $i < sizeof($dia); $i++) {
                if($i == 0) {
                     if(sizeof($knittingData) == 0)
                    {
                        $combineValue = [ '', '', $parts ?? '' , $blend ?? '' , $content ?? '' , $count ?? '' , $fabric ?? '' , $gsm ?? '' , $feed ?? '' , $lycra ?? '' , $dyeing ?? '' , $yarn_color ?? '' , '', '', '',
                                $dia[$i] ?? '' , $unit[$i] ?? '' , $dia_fab[$i] ?? '' , '', '', '', ''
                            ];
                    }
                    else {
                        // $combineValue = [ 'edit', $knittingData[$key]['fab_knitting_prog_id'], $parts, $blend, $content, $count, $fabric, $gsm, $feed, $lycra, $dyeing, $yarn_color, 
                        //         $knittingData[$key]['knit_machine_make'], $knittingData[$key]['gauge'], $knittingData[$key]['knit_type'], 
                        //         $dia[$i], $unit[$i], $dia_fab[$i], '', '', '', ''
                        //     ];
                        $combineValue = ['edit', $knittingData[$key]['fab_knitting_prog_id'] ?? '', $parts ?? '', $blend ?? '', $content ?? '', $count ?? '',
    $fabric ?? '',$gsm ?? 0,$feed ?? 0,$lycra ?? '',$dyeing ?? '',$yarn_color ?? '',$knittingData[$key]['knit_machine_make'] ?? '',$knittingData[$key]['gauge'] ?? '',
    $knittingData[$key]['knit_type'] ?? '',$dia[$i] ?? '',$unit[$i] ?? '',$dia_fab[$i] ?? '','','','',''];
                    }
                    array_push($finalValue, $combineValue);
                }
                else {
                    $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $dia[$i], $unit[$i], $dia_fab[$i], '', 
                            '', '', ''
                        ];
                    array_push($finalValue, $combineValue);
                }
            }
            $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $totalSizePrice, '', '', '' ];
            array_push($finalValue, $combineValue);
        }

        // ******* GET THE DATA DETAILS START ********* //

        // ******* GET THE COLUMN START ********* //
        
        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        // $knitting_sql = "SELECT id, misc_name as name FROM tbl_knitting_style WHERE status = 1";
        // $knittingData = $this->db->query($knitting_sql)->result_array();

        
        $knittingData = ["-","Mayer & Cie","Pailung","Terrot","KH-112 F","KH-323 D","KH-323 DJ"];
        // $knittingType = ["PF - Tubular","PF - Open Width","FS - Tubular","FS - Open Width","AS - Tubular","AS - Open Width","JQ - Tubular","JQ - Open Width"];

        $gauge = [ '14', '16', '18', '20', '22', '24', '26', '28' ];

        $knittingType = [ 'TUB', 'OW' ];

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Garment Parts", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type' => 'dropdown', 'source' => $fabric_name_data],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "No. of Feed.\n Per Item", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Lycra\n (%)", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Dyeing\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Yarn Colour", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Pref. Knitting\n Machine Make", 'width' => '8%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $knittingData],
            ['title' => "Gauge", 'width' => '8%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $gauge],
            ['title' => "Knitting\n Type", 'width' => '8%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $knittingType],
            ['title' => "Fin. DIA / DIM\n (W*H)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Unit of\n Measure", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Plan. Fab. Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Plan. Fab. Wgt.\n Subtotal (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Prog. Fab. Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Prog. Fab. Wgt.\n Subtotal (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Raise\n Knit. Prog.", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    public function updateKnittingProgrammeDetailss($req_data, $id)
    {
        foreach ($req_data as $key => $value)
        {
            
            $knittingList["enquiry_id"] = $id;
            $parts = $value[2];
            if($parts != '') {
                $knittingList["fab_knitting_prog_id"] = $value[1];
                $knittingList["knit_machine_make"] = $value[12];
                $knittingList["gauge"] = $value[13];
                $knittingList["knit_type"] = $value[14];
                if($value[0] == "edit" && $knittingList["fab_knitting_prog_id"] !="") {
                    $this->db->where('enquiry_id', $knittingList["enquiry_id"]);
                    $this->db->where('fab_knitting_prog_id', $knittingList["fab_knitting_prog_id"]);
                    $this->db->update('tbl_fab_knitting_programme', $knittingList);
                }
                else {
                    $this->db->insert('tbl_fab_knitting_programme', $knittingList);
                }
            }
        }
    }

    // ********** KNITTING PROGRAMME DETAILS ENDS HERE *********** /

    
    // ********** KNITTING PROGRAMME ITEMIZED YARN REQUIREMENT DETAILS STARTS HERE *********** /
    
     public function getKnittingProgrammeItemizedYarnRequirementDetailss($id) {

        $knitting_sql = $this->getYarnProgrammeDetailss($id);
        $knitting_data = $knitting_sql['data'];

        $array = [];
        foreach ($knitting_data as $key => $value)
        {
            $comboValue["parts"] = $value[5];
            $comboValue["blend"] = $value[6];
            $comboValue["content"] = $value[7];
            $comboValue["count"] = $value[8];
            $comboValue["dyeing"] = $value[11];

            if($value[11] == 'YDS' || $value[11] == 'YDJ') { 
                $comboValue["colour"] = $value[4];
            } else { 
                $comboValue["colour"] = 'Greige';
            }

            $comboValue["plan_yarn_wgt"] = $value[12];
            $comboValue["lycra"] = $value[13];
            $comboValue["fabric_name"] = $value[16];
            $comboValue["gsm"] = $value[17];
            array_push($array, $comboValue);
        }        

        $userNames = array();
        foreach($array as $key => $value) {
            $check = [$value['parts'], $value["blend"], $value["content"], $value['count'], $value["dyeing"], $value["fabric_name"], $value['gsm']];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);
                $array[$old_key]['plan_yarn_wgt'] = (float)$array[$old_key]['plan_yarn_wgt'] + (float)$value['plan_yarn_wgt'];
                $array[$old_key]['plan_yarn_wgt'] = number_format((float)$array[$old_key]['plan_yarn_wgt'], 3, '.', '');
                unset($array[$key]);
            }
            $userNames[] = $check;
        }

        $array = array_values($array);
        
        $finalValue = [];
        for ($i=0; $i < sizeof($array); $i++) { 
            $reqd_yarn_wgt = $array[$i]['plan_yarn_wgt'] - $array[$i]['plan_yarn_wgt'] * $array[$i]['lycra'] / 100;
            $reqd_yarn_wgt = number_format((float)$reqd_yarn_wgt, 3, '.', '');
            $combineValue = [ 'edit', '', $array[$i]['parts'], $array[$i]['blend'], $array[$i]['content'], $array[$i]['count'], 
                    $array[$i]['fabric_name'], $array[$i]['gsm'], $array[$i]['dyeing'], $array[$i]['colour'], $array[$i]['plan_yarn_wgt'], 
                    $array[$i]['lycra'], $reqd_yarn_wgt, '', '' ];
            array_push($finalValue, $combineValue);
        }

        array_multisort($finalValue);

        // return $array;

        // ******* GET THE DATA DETAILS START ********* //
        
        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        // ******* GET THE COLUMN START ********* //

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Garment Parts", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type' => 'dropdown', 'source' => $fabric_name_data],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Dyeing\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Yarn Colour", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Lycra\n (%)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Reqd. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Prog. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Raise Yarn D.C. For\n Knit Programme", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    // ********** KNITTING PROGRAMME ITEMIZED YARN REQUIREMENT DETAILS ENDS HERE *********** /


    // ************************ DYEING STARTS HERE ************************* /

    // ******** FABRIC DYEING PROGRAMME - COLOUR & DIA WISE QTY. DETAILS (FD, SDB & DDB) STARTS HERE ********* /

    public function getFabricDyeingProgrammeDetails($id) {
        
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

        $get_fabric_process_loss = $this->getFabricSizeSpecCodeDetailss($id);
        $color_wise_data = $get_fabric_process_loss["data"];

        $get_sizewise_dia_dimensionn = $this->get_sizewise_dia_dimensionn($id);
        $size_wise_dia_data = $get_sizewise_dia_dimensionn["data"];

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

            // return $color_wise_data;

            for ($j=0; $j < sizeof($color_wise_data); $j++) { 
                $combo2 = $color_wise_data[$j][3];
                $component2 = $color_wise_data[$j][4];
                $colour2 = $color_wise_data[$j][5];
                $gpdname2 = $color_wise_data[$j][6];
                if($component === $component2 && $gpdname === $gpdname2 && $combo === $combo2 && $colour === $colour2)
                {
                    $prices = [];
                    for ($k=8; $k < sizeof($color_wise_data[$j]) - 1; $k++) {
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

            // print_r($fin_prices);

            $totalSizePrice = number_format((float)array_sum($fin_prices), 3, '.', '');

            for ($j=0; $j < sizeof($dis_sizes); $j++) { 
                if($j == 0)
                {
                    if(!isset($itemized_fabric_data[$i]))
                    // if(isset($itemized_fabric_data[$i]) === 0)
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
                    $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', $itemized_fabric_data[$i]['dyeing_type'], $dis_sizes[$j], $uom[$j], $fin_prices[$j], '', '' ];
                }
                array_push($finalValue, $combineValue);
            }

            $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', $itemized_fabric_data[$i]['dyeing_type'], '', '', '', $totalSizePrice, '' ];
            array_push($finalValue, $combineValue);

        }

        return $finalValue;
    }

    public function getFabricDyeingProgramme_qtyy($id) {
        
        $itemized_data = $this->getFabricDyeingProgrammeDetails($id);
        
        $itemized_fabric_data = [];

        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $dyeing = $itemized_data[$i][13];

            if($dyeing == 'YDJ' || $dyeing == 'YDS') { }
            else {
                array_push($itemized_fabric_data, $itemized_data[$i]);
            }

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

                $arr_fabric_blend = $split_blend_data = $arr_blend_lycra = $arr_fabric_content = $fin_blend_lycra = $arr_itemized_content = [];
                
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

                    for ($j = 0; $j < count($split_blend_data); $j++) {

                      $feedVal  = $split_feed[$i] ?? 0;
                      $totalVal = $total_feed_val ?? 0;

                 if ($totalVal > 0) {
                  $fabric_blend = ($feedVal * 100) / $totalVal;
                   } else {
                    $fabric_blend = 0; // avoid division by zero
                   }

                $arr_fabric_blend[]   = (int)$fabric_blend;
                 $arr_itemized_content[] = $split_blend_data[$j] ?? '';
                 $arr_fabric_content[]   = trim($fin_split_content[$j] ?? '');
                           }
                }

                $total_fabric_blend = array_sum($arr_fabric_blend);

                for ($j=0; $j < sizeof($arr_fabric_blend); $j++) { 
                    $blend_lycra = $arr_fabric_blend[$j] * ($total_fabric_blend - $lycra) / 100;
                    array_push($arr_blend_lycra, (int)$blend_lycra);
                }

                for ($j=0; $j < sizeof($arr_blend_lycra); $j++) { 
                    $fin_blend = $arr_blend_lycra[$j] * $arr_itemized_content[$j] / 100;
                    array_push($fin_blend_lycra, (int)$fin_blend);
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
            if($itemized_fabric_data[$i][2] == '' && $itemized_fabric_data[$i][17] == '')
            {
                $combineValue = [ '', '', '', '', '', '', '', '', '', '', $itemized_fabric_data[$i][14], $itemized_fabric_data[$i][15], 
                        $itemized_fabric_data[$i][16], '', '', '', '' 
                    ];
            }
            else if($itemized_fabric_data[$i][17] != '')
            {
                $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', $itemized_fabric_data[$i][17], '', '', '' ];
            }
            else
            {
                $combineValue = [ 'edit', '', $itemized_fabric_data[$i][2], $itemized_fabric_data[$i][3], $itemized_fabric_data[$i][4], 
                        $itemized_fabric_data[$i][5], $itemized_fabric_data[$i][6], $itemized_fabric_data[$i][7], 
                        $itemized_fabric_data[$i][9], $itemized_fabric_data[$i][10], $itemized_fabric_data[$i][14], 
                        $itemized_fabric_data[$i][15], $itemized_fabric_data[$i][16], '', '', '', ''
                    ];
            }
            array_push($finalValue, $combineValue);
        }
        
        // ******* GET THE DATA DETAILS START ********* //

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        // ******* GET THE COLUMN START ********* //

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Fabric Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true],
            ['title' => "Fabric Content", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $fabric_name_data, 'readOnly' => true],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Fin. DIA / DIM\n (W*H)", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Unit of\n Measure", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "DIA Wise Plan.\n Fab. Wgt. (Kgs.)", 'width' => '10%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Plan. Fab. Wgt.\n Subtotal (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Prog. Fab. Wgt.(Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Prog. Fab. Wgt. Subtotal", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Raise Fabric\n Dyeing Prog.", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            // ['title' => "total_avg", 'type'=> 'hidden'],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    public function multiexplode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    // ******** FABRIC DYEING PROGRAMME - COLOUR & DIA WISE QTY. DETAILS (FD, SDB & DDB) ends HERE ********* /

    // ******** FABRIC DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (FD, SDB & DDB) STARTS HERE ********* /

    public function getFabricDyeingProgramme_finishh($id) {

        $fabric_dyeing = $this->getFabricDyeingProgramme_qtyy($id);
        $fabric_dyeing_data = $fabric_dyeing['data'];

        $fabric_dye_sql = "SELECT * FROM tbl_fab_dye_programme WHERE enquiry_id='$id' ";
        $fabDyeProgrammeData = $this->db->query($fabric_dye_sql)->result_array();

        $array = $colourContent = [];
        foreach ($fabric_dyeing_data as $key => $value) {
            $combo = $value[2];
            $component = $value[3];
            $colour = $value[4];
            $parts = $value[5];
            $fabric_content = $value[7];
            if( $combo == '' && $component == '' && $colour == '' && $parts == '') {  }
            else {
                $split_fabric_content = explode(':', $fabric_content);
                $index = array_search(' Lycra', $split_fabric_content);
                if($index !== FALSE){
                    unset($split_fabric_content[$index]);
                }
                $split_fabric_content = array_values($split_fabric_content);
                array_push($array, $value);

                for ($i=0; $i < sizeof($split_fabric_content); $i++) { 
                    $color_content_array = [
                        'combo' => $combo,
                        'component' => $component,
                        'colour' => $colour,
                        'parts' => $parts,
                        'name' => trim($split_fabric_content[$i]),
                        'id' => trim($split_fabric_content[$i]),
                    ];
                    array_push($colourContent, $color_content_array);
                }
            }
        }

        $array = array_values($array);

        $finalValue = [];

        for ($i=0; $i < sizeof($array); $i++) {  
            $combo = $array[$i][2];
            $component = $array[$i][3];
            $colour = $array[$i][4];
            $parts = $array[$i][5];
            $fabric = $array[$i][8];

            if(sizeof($fabDyeProgrammeData) == 0) {
                $combineValue = [
                    '', '', $combo, $component, $colour, $parts, $fabric, '', '', '', '', '', '', ''
                ];
            }
            else
            {
                $combineValue = [
                    'edit', $fabDyeProgrammeData[$i]['dye_programme_id'], $combo, $component, $colour, $parts, $fabric, $fabDyeProgrammeData[$i]['pantone'], 
                    $fabDyeProgrammeData[$i]['dyeing_special_req'], $fabDyeProgrammeData[$i]['req_fab'], $fabDyeProgrammeData[$i]['blend_fabric'], 
                    $fabDyeProgrammeData[$i]['colour_match_standard'], $fabDyeProgrammeData[$i]['appr_lab_dip'], $fabDyeProgrammeData[$i]['dye_vendor']
                ];
            }

            array_push($finalValue, $combineValue);

        }
        
        // ******* GET THE DATA DETAILS START ********* //
         $masterAdmins = $this->master_loginid();
         $subscriber_ids = array_column($masterAdmins, 'subscriber_id');


        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        //$dsr_sql = "SELECT id, dsrname as name FROM kn_master_dyeing_special_request WHERE companyid = $this->companyid AND status=1";
        //$dsr_data = $this->db->query($dsr_sql)->result_array();

        $dsr_sql = "SELECT b.id, b.dsrname as name FROM kn_master_dyeing_special_request  AS b LEFT JOIN " . KN_USERS . " AS u ON b.updatedby = u.id
                  WHERE u.subscriber_id IN (" . implode(',', array_map('intval', $subscriber_ids)) . ") AND b.companyid = $this->companyid AND b.status = 1";
       $dsr_data = $this->db->query($dsr_sql)->result_array();






        $fabricfinish_sql = "SELECT id, fabricfinish as name FROM kn_master_fabric_finish_wet_dry WHERE companyid = $this->companyid AND status=1";
        $fabric_finish_data = $this->db->query($fabricfinish_sql)->result_array();

        // commented below line no:7037 by myself regards new form of dyeing job work details integration
        //$dyeing_vendor_sql = "SELECT id, vendorname as name FROM kn_master_dyeing_vendor WHERE companyid = $this->companyid AND status=1";
        
        //$dyeing_vendor_sql = "SELECT id, jobwrkname as name FROM ".KN_MASTER_JOBWRK." WHERE companyid = $this->companyid AND type=1 AND status=1";
        //$dyeingVendor = $this->db->query($dyeing_vendor_sql)->result_array();

         $dyeing_vendor_sql = "SELECT b.id, b.jobwrkname as name FROM ".KN_MASTER_JOBWRK."  AS b LEFT JOIN " . KN_USERS . " AS u ON b.updatedby = u.id
                  WHERE u.subscriber_id IN (" . implode(',', array_map('intval', $subscriber_ids)) . ") AND b.companyid = $this->companyid AND b.status = 1 AND b.type = 1";
        $dyeingVendor = $this->db->query($dyeing_vendor_sql)->result_array();


        $colourStandard = [ 'D65', 'TL84', 'CWF', 'F', 'A', 'UV', 'U30' ];

        // ******* GET THE COLUMN START ********* //

        // $column = [
        //     ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
        //     ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
        //     ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
        //     ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
        //     ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
        //     ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'left', 'readOnly'=> true],
        //     ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source'=> $fabric_name_data, 'readOnly' => true],
        //     ['title' => "Pantone No./\n Swatch Ref.", 'width' => '12%', 'align'=> 'center'],
        //     ['title' => "Dyeing Special \nRequest If Any", 'width' => '12%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $dsr_data, 'multiple'=> true],
        //     ['title' => "Reqd. Fabric \nFinishing Process", 'width' => '12%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $fabric_finish_data],
        //     ['title' => "Blended Fabric - \nColour Matching\nContent", 'width' => '12%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $colourContent],
        //     ['title' => "Colour Matching\n Standards", 'width' => '12%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $colourStandard],
        //     ['title' => "Approved Lab Dip\n Ref. No", 'width' => '12%', 'align'=> 'center'],
        //     ['title' => "Dyeing Vendor Name", 'width' => '12%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $dyeingVendor],
        // ];

        // ******* GET THE COLUMN ENDS ********* //

        // $output['column'] = $column;
        $output['data'] = $finalValue;
        $output['fabric_name_data'] = $fabric_name_data;
        $output['dsr_data'] = $dsr_data;
        $output['fabric_finish_data'] = $fabric_finish_data;
        $output['colourStandard'] = $colourStandard;
        $output['colourContent'] = $colourContent;
        $output['dyeingVendor'] = $dyeingVendor;
        return $output;
    }

    public function updateFabricDyeingProgrammeDetailss($req_data, $id)
    {
        foreach ($req_data as $key => $value)
        {
            $dyeingList["enquiry_id"] = $id;
            $dyeingList["dye_programme_id"] = $value[1];
            $dyeingList["pantone"] = $value[7];
            $dyeingList["dyeing_special_req"] = $value[8];
            $dyeingList["req_fab"] = $value[9];
            $dyeingList["blend_fabric"] = $value[10];
            $dyeingList["colour_match_standard"] = $value[11];
            $dyeingList["appr_lab_dip"] = $value[12];
            $dyeingList["dye_vendor"] = $value[13];
            
            if($value[0] == "edit" && $dyeingList["dye_programme_id"] !="") {
                $this->db->where('enquiry_id', $dyeingList["enquiry_id"]);
                $this->db->where('dye_programme_id', $dyeingList["dye_programme_id"]);
                $this->db->update('tbl_fab_dye_programme', $dyeingList);
            }
            else {
                $this->db->insert('tbl_fab_dye_programme', $dyeingList);
            }
        }
    }

    // ******** FABRIC DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (FD, SDB & DDB) ends HERE ********* /

    // ******** YARN DYEING PROGRAMME - COLOUR WISE QTY. DETAILS CONSOLIDATED (YDS & YDJ) STARTS HERE ********* /

    public function getYarnDyeingProgramme_qtyy($id) {

        $yarn_dyeing = $this->getYarnDyeingColourWiseQtyDetailss($id);
        $yarn_dyeing_data = $yarn_dyeing['data'];

        $array = [];

        foreach ($yarn_dyeing_data as $key => $value)
        {
            $comboValue["colour"] = $value[4];
            $comboValue["blend"] = $value[6];
            $comboValue["content"] = $value[7];
            $comboValue["count"] = $value[8];
            $comboValue["lycra"] = $value[11];
            $comboValue["plan_yarn_wgt"] = $value[17];

            $reqd_yarn_wgt = $comboValue["plan_yarn_wgt"] - $comboValue["plan_yarn_wgt"] * $comboValue["lycra"] / 100;

            $comboValue["reqd_yarn_wgt"] = number_format((float)$reqd_yarn_wgt, 3, '.', '');

            array_push($array, $comboValue);
        }

        $userNames = array();
        foreach($array as $key => $value) {
            $check = [$value['colour'], $value["blend"], $value["content"], $value['count']];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);
                $array[$old_key]['plan_yarn_wgt'] = (float)$array[$old_key]['plan_yarn_wgt'] + (float)$value['plan_yarn_wgt'];
                $array[$old_key]['plan_yarn_wgt'] = number_format((float)$array[$old_key]['plan_yarn_wgt'], 3, '.', '');

                $array[$old_key]['reqd_yarn_wgt'] = (float)$array[$old_key]['reqd_yarn_wgt'] + (float)$value['reqd_yarn_wgt'];
                $array[$old_key]['reqd_yarn_wgt'] = number_format((float)$array[$old_key]['reqd_yarn_wgt'], 3, '.', '');

                unset($array[$key]);
            }
            $userNames[] = $check;
        }

        $array = array_values($array);

        $finalValue = [];

        foreach ($array as $key => $value) {
            $colour = $value["colour"];            
            $blend = $value["blend"];
            $content = $value["content"];
            $count = $value["count"];
            $plan_yarn_wgt = $value["plan_yarn_wgt"];
            $reqd_yarn_wgt = $value["reqd_yarn_wgt"];
            $combineValue = [
                'edit', '', $colour, $blend, $content, $count, $plan_yarn_wgt, $reqd_yarn_wgt, '', ''
            ];
            array_push($finalValue, $combineValue);
        }

        // ******* GET THE DATA DETAILS START ********* //

        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        // ******* GET THE COLUMN START ********* //

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'readOnly' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'readOnly' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'readOnly' => true],
            ['title' => "Plan. Yarn. Wgt.\n(Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            // ['title' => "Lycra \n(%)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            // ['title' => "Lycra Wgt.\n(Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Reqd. Yarn.\n Wgt.(Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Prog. Fab. \nWgt.(Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Raise Yarn Dyeing", 'width' => '8%', 'align'=> 'center', 'type' => 'checkbox', 'readOnly'=> true],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    // ******** YARN DYEING PROGRAMME - COLOUR WISE QTY. DETAILS CONSOLIDATED (YDS & YDJ) ends HERE ********* /

    // ******** YARN DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (YDS & YDJ) STARTS HERE ********* /

    public function getYarnDyeingProgramme_finishh($id) {

        $yarn_dyeing = $this->getYarnDyeingColourWiseQtyDetailss($id);
        $yarn_dyeing_data = $yarn_dyeing['data'];

        $array = $colourContent = [];

        foreach ($yarn_dyeing_data as $key => $value)
        {
            $comboValue["combo"] = $value[2];
            $comboValue["component"] = $value[3];
            $comboValue["colour"] = $value[4];
            $comboValue["parts"] = $value[5];
            $comboValue["blend"] = $value[6];
            $comboValue["content"] = $value[7];
            $comboValue["count"] = $value[8];

            $content_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[7]."'";
            $yarn_content_data = $this->db->query($content_sql)->result_array();

            if(sizeof($yarn_content_data) > 0)
            {
                $split_content = $this->multiexplode(array("+",'/',":"), $yarn_content_data[0]['misc_name']);
            }

            for ($i=0; $i < sizeof($split_content); $i++) { 
                $color_content_array = [
                    'combo' => $value[2],
                    'component' => $value[3],
                    'colour' => $value[4],
                    'parts' => $value[5],
                    'name' => trim($split_content[$i]),
                    'id' => trim($split_content[$i]),
                ];
                array_push($colourContent, $color_content_array);
            }

            array_push($array, $comboValue);
        }

        $userNames = array();
        foreach($array as $key => $value) {
            $check = [$value['colour'], $value["blend"], $value["content"], $value['count']];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);
                unset($array[$key]);
            }
            $userNames[] = $check;
        }

        $array = array_values($array);

        $fabric_dye_sql = "SELECT * FROM tbl_yarn_dye_programme WHERE enquiry_id='$id' ";
        $fabDyeProgrammeData = $this->db->query($fabric_dye_sql)->result_array();

        $finalValue = [];

        foreach ($array as $key => $value) {
            if(sizeof($fabDyeProgrammeData) == 0) {
                $combineValue = [
                    '', '', $value['colour'], $value['combo'], $value['component'], $value['parts'], '', '', '', '', '', '', ''
                ];
            }
            else
            {
                $combineValue = [
                    'edit', $fabDyeProgrammeData[$key]['dye_programme_id'], $value['colour'], $value['combo'], $value['component'], $value['parts'], $fabDyeProgrammeData[$key]['pantone'], 
                    $fabDyeProgrammeData[$key]['dyeing_special_req'], $fabDyeProgrammeData[$key]['req_fab'], $fabDyeProgrammeData[$key]['blend_fabric'], 
                    $fabDyeProgrammeData[$key]['colour_match_standard'], $fabDyeProgrammeData[$key]['appr_lab_dip'], $fabDyeProgrammeData[$key]['dye_vendor']
                ];
            }
            array_push($finalValue, $combineValue);
        }

        // ******* GET THE DATA DETAILS START ********* //

        $dsr_sql = "SELECT id, dsrname as name FROM kn_master_dyeing_special_request WHERE companyid = $this->companyid AND status=1";
        $dsr_data = $this->db->query($dsr_sql)->result_array();

        $fabricfinish_sql = "SELECT id, fabricfinish as name FROM kn_master_fabric_finish_wet_dry WHERE companyid = $this->companyid AND status=1";
        $fabric_finish_data = $this->db->query($fabricfinish_sql)->result_array();

       //commented below line no:7282 by myself regards new form of dyeing job work details integration 
       /// $dyeing_vendor_sql = "SELECT id, vendorname as name FROM kn_master_dyeing_vendor WHERE companyid = $this->companyid AND status=1";
        $dyeing_vendor_sql = "SELECT id, jobwrkname as name FROM ".KN_MASTER_JOBWRK." WHERE companyid = $this->companyid AND type=1 AND status=1";
        $dyeingVendor = $this->db->query($dyeing_vendor_sql)->result_array();

        $colourStandard = [ 'D65', 'TL84', 'CWF', 'F', 'A', 'UV', 'U30' ];

        $output['data'] = $finalValue;
        $output['dsr_data'] = $dsr_data;
        $output['fabric_finish_data'] = $fabric_finish_data;
        $output['colourStandard'] = $colourStandard;
        $output['colourContent'] = $colourContent;
        $output['dyeingVendor'] = $dyeingVendor;
        return $output;
    }

    public function updateYarnDyeingProgrammeDetailss($req_data, $id)
    {
        foreach ($req_data as $key => $value)
        {
            $dyeingList["enquiry_id"] = $id;
            $dyeingList["dye_programme_id"] = $value[1];
            $dyeingList["pantone"] = $value[6];
            $dyeingList["dyeing_special_req"] = $value[7];
            $dyeingList["req_fab"] = $value[8];
            $dyeingList["blend_fabric"] = $value[9];
            $dyeingList["colour_match_standard"] = $value[10];
            $dyeingList["appr_lab_dip"] = $value[11];
            $dyeingList["dye_vendor"] = $value[12];
            
            if($value[0] == "edit" && $dyeingList["dye_programme_id"] !="") {
                $this->db->where('enquiry_id', $dyeingList["enquiry_id"]);
                $this->db->where('dye_programme_id', $dyeingList["dye_programme_id"]);
                $this->db->update('tbl_yarn_dye_programme', $dyeingList);
            }
            else {
                $this->db->insert('tbl_yarn_dye_programme', $dyeingList);
            }
        }
    }

    // ******** YARN DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (YDS & YDJ) ends HERE ********* /

    // ************************ DYEING ENDS HERE ************************* /

    // ************************ COMPACTING STARTS HERE ************************* /

    // ******** FABRIC WASHING COMPACTING & HEAT SETTING DETAILS STARTS HERE ********* /


    public function getFabricWashingCompatingDetailss($id) {
        
        $itemized_details = $this->getItemizedFabricRequirementDetailss($id);
        $itemized_data = $itemized_details['data'];

        $compacting_sql = "SELECT * FROM tbl_fab_wash_compacting_heat WHERE enquiry_id = '$id' ";
        $compactingData = $this->db->query($compacting_sql)->result_array();
        
        $itemized_fabric_data = [];

        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $dyeing = $itemized_data[$i][13];
            $tol_qty = $itemized_data[$i][17];

            if($tol_qty != '') { }
            else {
                array_push($itemized_fabric_data, $itemized_data[$i]);
            }

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
                
                for ($i = 0; $i < count($split_blend); $i++) {

    // -------- BLEND NAME ----------
                      $blend_auth = $split_blend[$i] ?? '';
                      $blend_data = '';

                 if ($blend_auth !== '') {
                    $sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$blend_auth."'";
                    $feed_data = $this->db->query($sql)->result_array();
                    $blend_data = $feed_data[0]['misc_name'] ?? '';
                  }

    // -------- CONTENT NAME ----------
                   $content_auth = $split_content[$i] ?? '';
                   $content_data = '';

         if ($content_auth !== '') {
                   $sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$content_auth."'";
                   $feed_data = $this->db->query($sql)->result_array();
                   $content_data = $feed_data[0]['misc_name'] ?? '';
                        }

    // -------- SPLIT VALUES ----------
                $split_blend_data   = $this->multiexplode(['+','/',':'], $blend_data);
                $fin_split_content  = $this->multiexplode(['+','/',':'], $content_data);

    // -------- SAFE FEED CALC ----------
                    $feedVal  = $split_feed[$i] ?? 0;
                     $totalVal = $total_feed_val ?? 0;

                 if ($totalVal > 0) {
               $fabric_blend_val = ($feedVal * 100) / $totalVal;
                } else {
                $fabric_blend_val = 0;
                  }

             $fabric_blend[$i] = round($fabric_blend_val);

             // -------- PUSH VALUES ----------
            for ($j = 0; $j < count($split_blend_data); $j++) {
            $arr_fabric_blend[]   = $fabric_blend[$i];
           $arr_itemized_content[] = trim($split_blend_data[$j] ?? '');
            $arr_fabric_content[]   = trim($fin_split_content[$j] ?? '');
             }
              }
                
                $total_fabric_blend = array_sum($fabric_blend);
                
                // print_r($total_fabric_blend);

                for ($i=0; $i < sizeof($split_blend); $i++) { 
                    $blend_lycra_val = $fabric_blend[$i] * ($total_fabric_blend - $lycra) / 100;
                    array_push($blend_lycra, round($blend_lycra_val));
                }
                
                // print_r($arr_fabric_blend);
                
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
                
                // print_r($arr_blend_lycra);

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

        $cc = 0;
        for ($i=0; $i < sizeof($itemized_fabric_data); $i++) {
            $combineValue = '';
            if($itemized_fabric_data[$i][14] != '' && $i != 0)
            {
                $cc++;
            }
            if($itemized_fabric_data[$i][2] == '' && $itemized_fabric_data[$i][17] == '')
            {
                if(!isset($compactingData[$cc])) {
                    $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $itemized_fabric_data[$i][14], $itemized_fabric_data[$i][15]
                        ];
                }
                else {
                    $combineValue = [ 'edit', $compactingData[$cc]['fab_wash_compacting_heat_id'], '', '', '', '', '', '', '', '', '', '', '', '', '', '', $compactingData[$cc]['knit_dia_dim'], $itemized_fabric_data[$i][14], $itemized_fabric_data[$i][15]
                        ];
                }
            }
            // else if($itemized_fabric_data[$i][17] != '')
            // {
                // $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ];
            // }
            else
            {
                if(!isset($compactingData[$cc])) {
                    $combineValue = [ '', '', $itemized_fabric_data[$i][2], $itemized_fabric_data[$i][3], $itemized_fabric_data[$i][4], $itemized_fabric_data[$i][5],
                            $itemized_fabric_data[$i][6], $itemized_fabric_data[$i][7],
                            $itemized_fabric_data[$i][9], $itemized_fabric_data[$i][13], '', '', $itemized_fabric_data[$i][10], '', '', '', '', $itemized_fabric_data[$i][14], 
                            $itemized_fabric_data[$i][15]
                        ];
                }
                else {
                    $combineValue = [ 'edit', $compactingData[$cc]['fab_wash_compacting_heat_id'], $itemized_fabric_data[$i][2], $itemized_fabric_data[$i][3], $itemized_fabric_data[$i][4], $itemized_fabric_data[$i][5],
                            $itemized_fabric_data[$i][6], $itemized_fabric_data[$i][7],
                            $itemized_fabric_data[$i][9], $itemized_fabric_data[$i][13], $compactingData[$cc]['fab_wash_req'], $compactingData[$cc]['dry_set_req'], $itemized_fabric_data[$i][10], 
                            $compactingData[$cc]['shrink_l'], $compactingData[$cc]['shrink_w'], $compactingData[$cc]['spirality_acc'], $compactingData[$cc]['knit_dia_dim'], 
                            $itemized_fabric_data[$i][14], $itemized_fabric_data[$i][15]
                        ];
                }
            }
            array_push($finalValue, $combineValue);
        }

        // return $finalValue;
        
        // ******* GET THE DATA DETAILS START ********* //

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        $fabric_wet_sql = "SELECT id, fabricfinish as name FROM kn_master_fabric_finish_wet_dry WHERE processingtype = 'Wet' ";
        $fabric_wet_data = $this->db->query($fabric_wet_sql)->result_array();

        $fabric_dry_sql = "SELECT id, fabricfinish as name FROM kn_master_fabric_finish_wet_dry WHERE processingtype = 'Dry' ";
        $fabric_dry_data = $this->db->query($fabric_dry_sql)->result_array();

        $perData = [
            [ 'id'=> 'Nill', 'name' => 'Nill' ]
        ];
        for($i = 1; $i <= 20; $i++)
        {
            $per = [ 'id'=> '< '.$i.'%', 'name' => '< '.$i.'%' ];
            array_push($perData, $per);
        }
        // $lastData = [ 'id'=> 'Above 20%', 'name' => 'Above 20%' ];
        // array_push($perData, $lastData);

        // ******* GET THE COLUMN START ********* //

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Fabric Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true],
            ['title' => "Fabric Content", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $fabric_name_data, 'readOnly' => true],
            ['title' => "Dyeing\n Type", 'width' => '10%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Fabric Washing\n Requirement", 'width' => '10%', 'align'=> 'center', 'type'=> 'dropdown', 'source'=> $fabric_wet_data, 'multiple'=> true],
            ['title' => "Dry. / Comp. / Heat\n Set. Requirement", 'width' => '10%', 'align'=> 'center', 'type'=> 'dropdown', 'source'=> $fabric_dry_data, 'multiple'=> true],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Shrink.\n Acc. ( L )", 'width' => '8%', 'align'=> 'center', 'type'=> 'dropdown', 'source'=> $perData],
            ['title' => "Shrink.\n Acc. ( W )", 'width' => '8%', 'align'=> 'center', 'type'=> 'dropdown', 'source'=> $perData],
            ['title' => "Spirality\n Acc.", 'width' => '8%', 'align'=> 'center', 'type'=> 'dropdown', 'source'=> $perData],
            ['title' => "Knit. DIA / DIM\n (W*H)", 'width' => '8%', 'align'=> 'center'],
            ['title' => "Reqd. Fin. DIA /\n DIM (W*H)", 'width' => '10%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Unit of\n Measure", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    public function updateFabricWashingCompatingDetailss($req_data, $id)
    {
        foreach ($req_data as $key => $value)
        {
            if($value[17] != '')
            {
                $fabricList["enquiry_id"] = $id;
                $fabricList["fab_wash_compacting_heat_id"] = $value[1];
                $fabricList["fab_wash_req"] = $value[10];
                $fabricList["dry_set_req"] = $value[11];
                $fabricList["shrink_l"] = $value[13];
                $fabricList["shrink_w"] = $value[14];
                $fabricList["spirality_acc"] = $value[15];
                $fabricList["knit_dia_dim"] = $value[16];
                if($value[0] == "edit" && $fabricList["fab_wash_compacting_heat_id"] !="") {
                    $this->db->where('enquiry_id', $fabricList["enquiry_id"]);
                    $this->db->where('fab_wash_compacting_heat_id', $fabricList["fab_wash_compacting_heat_id"]);
                    $this->db->update('tbl_fab_wash_compacting_heat', $fabricList);
                }
                else {
                    unset($fabricList["fab_wash_compacting_heat_id"]);
                    $this->db->insert('tbl_fab_wash_compacting_heat', $fabricList);
                }
            }
        }
    }

    // ******** FABRIC WASHING COMPACTING & HEAT SETTING DETAILS ENDS HERE ********* /

    // ************************ COMPACTING ENDS HERE ************************* /
    
    // ************************ LAB STARTS HERE ************************* /

    // ********** LAB TESTING ACCEPTANCE INTERNAL STARTS HERE *********** /

    
    // ********** LAB TESTING ACCEPTANCE INTERNAL STARTS HERE *********** /

    public function getItemizedFabricRequirement($id) {
        $consolidated_sql = "SELECT a.*, b.gpdname FROM tbl_fab_color_wise_garment_parts a 
                            INNER JOIN kn_master_garment_part_desc b ON a.gar_parts=b.id 
                            WHERE a.enquiry_id='$id' 
                            group by a.combo, a.component, a.colour, a.gar_parts  
                            ORDER BY a.fab_color_wise_garment_part_id ASC";
        $consolidated_data = $this->db->query($consolidated_sql)->result_array();

        $itemized_fabric_sql = "SELECT * FROM tbl_fab_itemized_fabric_requirement
                            WHERE enquiry_id='$id'  
                            ORDER BY itemized_fabric_requirement_id ASC";
        $itemized_fabric_data = $this->db->query($itemized_fabric_sql)->result_array();

        $get_fabric_process_loss = $this->getFabricSizeSpecCodeDetailss($id);
        $color_wise_data = $get_fabric_process_loss["data"];

        $get_sizewise_dia_dimensionn = $this->get_sizewise_dia_dimensionn($id);
        $size_wise_dia_data = $get_sizewise_dia_dimensionn["data"];

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);

        // ******* GET DATA DETAILS START ********* //

        $finalValue = [];
        $referenceArr = [];

        // return $consolidated_data;

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
                    for ($k=8; $k < sizeof($size_wise_dia_data[$j]) - 1; $k++) {
                        array_push($sizes, $size_wise_dia_data[$j][$k]);
                        array_push($mer_sizes, $size_wise_dia_data[$j][$k]);
                    }
                    array_push($fil_sizes, $sizes);
                }
            }

            // return $color_wise_data;

            for ($j=0; $j < sizeof($color_wise_data); $j++) { 
                $combo2 = $color_wise_data[$j][3];
                $component2 = $color_wise_data[$j][4];
                $colour2 = $color_wise_data[$j][5];
                $gpdname2 = $color_wise_data[$j][6];
                if($component === $component2 && $gpdname === $gpdname2 && $combo === $combo2 && $colour === $colour2)
                {
                    $prices = [];
                    for ($k=8; $k < sizeof($color_wise_data[$j]) - 1; $k++) {
                        array_push($prices, $color_wise_data[$j][$k]);
                    }
                    array_push($fil_prices, $prices);
                }
            }

            $dis_sizes = array_values(array_unique($mer_sizes));

            // print_r($fil_prices);
            
            // print_r($fil_sizes);
            // print_r($dis_sizes);
            
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

            // print_r($fin_prices);

            $totalSizePrice = number_format((float)array_sum($fin_prices), 3, '.', '');

            for ($j=0; $j < sizeof($dis_sizes); $j++) { 
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
                array_push($finalValue, $combineValue);
            }

            $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $totalSizePrice, '' ];
            array_push($finalValue, $combineValue);

        }

        return $finalValue;
    }

    public function getCompatingDetails($id) {
        $itemized_data = $this->getItemizedFabricRequirement($id);

        $compacting_sql = "SELECT * FROM tbl_fab_wash_compacting_heat WHERE enquiry_id = '$id' ";
        $compactingData = $this->db->query($compacting_sql)->result_array();
        
        $itemized_fabric_data = [];

        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $dyeing = $itemized_data[$i][13];

            // if($dyeing == 'YDJ' || $dyeing == 'YDS') { }
            // else {
                array_push($itemized_fabric_data, $itemized_data[$i]);
            // }

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
                
               for ($i = 0; $i < count($split_blend); $i++) {

    // ---------- BLEND ----------
    $blend_auth = $split_blend[$i] ?? '';
    $blend_data = '';

    if ($blend_auth !== '') {
        $sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$blend_auth."'";
        $feed_data = $this->db->query($sql)->result_array();
        $blend_data = $feed_data[0]['misc_name'] ?? '';
    }

    // ---------- CONTENT ----------
    $content_auth = $split_content[$i] ?? '';
    $content_data = '';

    if ($content_auth !== '') {
        $sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$content_auth."'";
        $feed_data = $this->db->query($sql)->result_array();
        $content_data = $feed_data[0]['misc_name'] ?? '';
    }

    // ---------- SPLIT ----------
    $split_blend_data  = $this->multiexplode(['+','/',':'], $blend_data);
    $fin_split_content = $this->multiexplode(['+','/',':'], $content_data);

    // ---------- FEED CALC ----------
    $feedVal  = $split_feed[$i] ?? 0;
    $totalVal = $total_feed_val ?? 0;

    if ($totalVal > 0) {
        $fabric_blend_val = ($feedVal * 100) / $totalVal;
    } else {
        $fabric_blend_val = 0;
    }

    $fabric_blend[$i] = round($fabric_blend_val);

    // ---------- PUSH ----------
    for ($j = 0; $j < count($split_blend_data); $j++) {
        $arr_fabric_blend[]    = $fabric_blend[$i];
        $arr_itemized_content[] = trim($split_blend_data[$j] ?? '');
        $arr_fabric_content[]   = trim($fin_split_content[$j] ?? '');
    }
}
                
                $total_fabric_blend = array_sum($fabric_blend);
                
                // print_r($total_fabric_blend);

                for ($i=0; $i < sizeof($split_blend); $i++) { 
                    $blend_lycra_val = $fabric_blend[$i] * ($total_fabric_blend - $lycra) / 100;
                    array_push($blend_lycra, round($blend_lycra_val));
                }
                
                // print_r($arr_fabric_blend);
                
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
                
                // print_r($arr_blend_lycra);

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
            array_push($finalValue, $combineValue);
        }

        return $finalValue;
    }
    
    public function getLabTestingAcceptanceInternalDetailss($id) {

        $itemized_data = $this->getCompatingDetails($id);

        $itemized_fabric_data = [];
        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $dia = $itemized_data[$i][17];
            $uom = $itemized_data[$i][18];
            if($dia != '' && $uom != '') {
                array_push($itemized_fabric_data, $itemized_data[$i]);
            }
        }

        $lab_sql = "SELECT * FROM tbl_lab_testing_acceptance_internal WHERE enquiry_id = " . $id . " ";
        $lab_data = $this->db->query($lab_sql)->result_array();

        $itemized_fabric_data = array_values($itemized_fabric_data);
        
        $finalValue = [];
        $lastKey = 0;
        for ($i=0; $i < sizeof($itemized_fabric_data); $i++) { 

            $old_color = $old_combo = $old_gsm = $old_item_desc = $old_parts = '';

            if($i > 0) {                
                $old_combo = $itemized_fabric_data[$lastKey][2];
                $old_color = $itemized_fabric_data[$lastKey][4];
                $old_parts = $itemized_fabric_data[$lastKey][5];
                $old_blend = $itemized_fabric_data[$lastKey][6];
                $old_content = $itemized_fabric_data[$lastKey][7];
                $old_fabric = $itemized_fabric_data[$lastKey][8];
                $old_gsm = $itemized_fabric_data[$lastKey][12];
                $old_fabric_sql = "SELECT * FROM kn_master_fabric_misc WHERE id = '$old_fabric'";
                $old_fabric_data = $this->db->query($old_fabric_sql)->result_array();
                $old_fabric_name = $old_fabric_data[0]['misc_name'];

                $old_item_desc = $old_blend.' / '.$old_content.' / '.$old_fabric_name;
            }

            $combo = $itemized_fabric_data[$i][2];
            $colour = $itemized_fabric_data[$i][4];
            $parts = $itemized_fabric_data[$i][5];
            $blend = $itemized_fabric_data[$i][6];
            $content = $itemized_fabric_data[$i][7];
            $fabric = $itemized_fabric_data[$i][8];
            $gsm = $itemized_fabric_data[$i][12];
            $fabric_sql = "SELECT * FROM kn_master_fabric_misc WHERE id = '$fabric'";
            $fabric_data = $this->db->query($fabric_sql)->result_array();
            $fabric_name = $fabric_data[0]['misc_name'];

            $colorCombo = $combo.' / '.$colour;

            $item_description = $blend.' / '.$content.' / '.$fabric_name;

            if(sizeof($lab_data) == 0) {
                if($old_combo == $combo && $old_parts == $parts && $old_color == $colour && $old_item_desc == $item_description && $old_gsm = $gsm) {
                    $finalValue[$i] = ['', '', '', '', '', '', $itemized_fabric_data[$i][17], '', '', '', '', '', '', '', ''];
                }
                else {
                    $lastKey = $i;
                    $finalValue[$i] = ['', '', $colorCombo, $parts, $item_description, $gsm, $itemized_fabric_data[$i][17], '', '', '', '', '', '', '', '', ''];
                }
            }
            else {
                if($old_combo == $combo && $old_color == $colour && $old_gsm = $gsm) {
                    $finalValue[$i] = ['edit', $lab_data[$i]['lab_testing_acceptance_internal_id'], '', '', '', 
                    '', $itemized_fabric_data[$i][17], $lab_data[$i]['shrink_acc_lvl_l'], $lab_data[$i]['shrink_acc_lvl_w'], $lab_data[$i]['spiriality_acc_lvl'], 
                    $lab_data[$i]['crocking_acc_level_dry'], $lab_data[$i]['crocking_acc_level_wet'], $lab_data[$i]['fastness_acc_lvl_shade'], 
                    $lab_data[$i]['fastness_acc_lvl_stain'], $lab_data[$i]['testing_authority'], $lab_data[$i]['approving_athority']];
                }
                else {
                    $lastKey = $i;
                    $finalValue[$i] = ['edit', $lab_data[$i]['lab_testing_acceptance_internal_id'], $colorCombo, $parts, $item_description, $gsm, $itemized_fabric_data[$i][17], 
                    $lab_data[$i]['shrink_acc_lvl_l'], $lab_data[$i]['shrink_acc_lvl_w'], $lab_data[$i]['spiriality_acc_lvl'], 
                    $lab_data[$i]['crocking_acc_level_dry'], $lab_data[$i]['crocking_acc_level_wet'], $lab_data[$i]['fastness_acc_lvl_shade'], 
                    $lab_data[$i]['fastness_acc_lvl_stain'], $lab_data[$i]['testing_authority'], $lab_data[$i]['approving_athority']];
                }
            }
        }

        $perData = [
            [ 'id'=> 'Nill', 'name' => 'Nill' ]
        ];
        for($i = 1; $i <= 20; $i++)
        {
            $per = [ 'id'=> '< '.$i.'%', 'name' => '< '.$i.'%' ];
            array_push($perData, $per);
        }
        // $lastData = [ 'id'=> 'Above 20%', 'name' => 'Above 20%' ];
        // array_push($perData, $lastData);

        $gradeData = [];
        for($i = 1; $i <= 5; $i++)
        {
            $grade = [ 'id'=> 'Grade '.$i, 'name' => 'Grade '.$i ];
            array_push($gradeData, $grade);
        }

        // commented by myself regards new form integration $sql2 = "SELECT id, test_auth_name as name FROM kn_master_ext_lab WHERE status=1";
        $sql2 = "SELECT id, vendor_name as name FROM ".KN_MASTER_TESTINGAUTHORITY." WHERE status=1";
        $testingAuthority = $this->db->query($sql2)->result_array();

        $sql2 = "SELECT id, gpdname as name FROM kn_master_garment_part_desc WHERE status=1";
        $garmentParts = $this->db->query($sql2)->result_array();

        $approvingAuthority = [
            [ 'id'=> 'BUYER', 'name' => 'BUYER' ],
            [ 'id'=> 'LIASON OFFICE', 'name' => 'LIASON OFFICE' ],
            [ 'id'=> 'BUYING OFFICE', 'name' => 'BUYING OFFICE' ],
            [ 'id'=> 'MANAGEMENT', 'name' => 'MANAGEMENT' ],
            [ 'id'=> 'OTHERS', 'name' => 'OTHERS' ],
        ];

        $output['data'] = $finalValue;
        $output['perData'] = $perData;
        $output['gradeData'] = $gradeData;
        $output['testingAuthority'] = $testingAuthority;
        $output['approvingAuthority'] = $approvingAuthority;
        return $output;
    }
    
    public function updateLabTestingAcceptanceInternalDetailss($req_data, $id) { 
        foreach ($req_data as $key => $value)
        {
            $labTestList["enquiry_id"] = $id;
            $labTestList["lab_testing_acceptance_internal_id"] = $value[1];
            $labTestList["relation_id"] = '';
            $labTestList["shrink_acc_lvl_l"] = $value[7];
            $labTestList["shrink_acc_lvl_w"] = $value[8];
            $labTestList["spiriality_acc_lvl"] = $value[9];
            $labTestList["crocking_acc_level_dry"] = $value[10];
            $labTestList["crocking_acc_level_wet"] = $value[11];
            $labTestList["fastness_acc_lvl_shade"] = $value[12];
            $labTestList["fastness_acc_lvl_stain"] = $value[13];
            $labTestList["testing_authority"] = $value[14];
            $labTestList["approving_athority"] = $value[15];
            if($value[0] == "edit" && $labTestList["lab_testing_acceptance_internal_id"] !="") {
                $this->db->where('enquiry_id', $labTestList["enquiry_id"]);
                $this->db->where('lab_testing_acceptance_internal_id', $labTestList["lab_testing_acceptance_internal_id"]);
                $this->db->update('tbl_lab_testing_acceptance_internal', $labTestList);
            }
            else {
                $this->db->insert('tbl_lab_testing_acceptance_internal', $labTestList);
            }
        }
    }

    // ********** LAB TESTING ACCEPTANCE INTERNAL ENDS HERE *********** /

    // ********** LAB TESTING ACCEPTANCE INTERNAL ENDS HERE *********** /

    // ********** LAB TESTING ACCEPTANCE EXTERNAL STARTS HERE *********** /
    
    public function getLabTestingAcceptanceExternalDetailss($id) {

         $masterAdmins = $this->master_loginid();
         $subscriber_ids = array_column($masterAdmins, 'subscriber_id');


        $itemized_data = $this->getCompatingDetails($id);

        $itemized_fabric_data = [];
        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $dia = $itemized_data[$i][17];
            $uom = $itemized_data[$i][18];
            if($dia != '' && $uom != '') {
                array_push($itemized_fabric_data, $itemized_data[$i]);
            }
        }

        $lab_sql = "SELECT * FROM tbl_lab_testing_acceptance_external WHERE enquiry_id = " . $id . " ";
        $lab_data = $this->db->query($lab_sql)->result_array();

        $itemized_fabric_data = array_values($itemized_fabric_data);
        
        $comboData = $itemDescData = $finalValue = [];
        
        foreach ($itemized_fabric_data as $key => $value)
        {
            $combo = $value[2];
            $colour = $value[4];
            $blend = $value[6];
            $content = $value[7];
            $fabric = $value[8];

            $fabric_sql = "SELECT * FROM kn_master_fabric_misc WHERE id = '$fabric'";
            $fabric_data = $this->db->query($fabric_sql)->result_array();

            $fabric_name = $fabric_data[0]['misc_name'];

            $item_description = $blend.' / '.$content.' / '.$fabric_name;

            array_push($comboData, $combo.' / '.$colour );
            array_push($itemDescData, $item_description );
        }

        $comboData = array_values(array_unique($comboData));
        $itemDescData = array_values(array_unique($itemDescData));
        $lastData = [ 'id'=> 'Garment Sample', 'name' => 'Garment Sample' ];
        array_push($itemDescData, $lastData);

        if(sizeof($lab_data) === 0) {
            $empty = ['', '', '', '', '', '', '', ''];
            array_push($finalValue, $empty);
        }
        else if(sizeof($lab_data) > 0) {
            foreach ($lab_data as $key => $value) {
                $finalValue[$key] = ['edit', $value['lab_testing_acceptance_external_id'], $value['combo_color'], 
                $value['item_description'], $value['lab_test_parameter'], $value['acceptance_lvl'], $value['testing_authority'], 
                $value['approving_authority']];
            }
        }
        
        // $sql2 = "SELECT id, labname as name FROM kn_master_lab WHERE status=1";
        // $labTestingAuthority = $this->db->query($sql2)->result_array();

         $sql2 = "SELECT b.id, b.labname as name FROM ".KN_MASTER_LAB."  AS b LEFT JOIN " . KN_USERS . " AS u ON b.updatedby = u.id
                WHERE u.subscriber_id IN (" . implode(',', array_map('intval', $subscriber_ids)) . ")  AND b.status = 1";
        $labTestingAuthority = $this->db->query($sql2)->result_array();



        $acceptanceLevel = [
            [ 'id'=> 'Nill', 'name' => 'Nill' ]
        ];

        for($i = 1; $i <= 20; $i++)
        {
            $per = [ 'id'=> '< '.$i.'%', 'name' => '< '.$i.'%' ];
            array_push($acceptanceLevel, $per);
        }

        for($i = 1; $i <= 5; $i++)
        {
            $grade = [ 'id'=> 'Grade '.$i, 'name' => 'Grade '.$i ];
            array_push($acceptanceLevel, $grade);
        }
        $lastData = [ 'id'=> "Buyer's Standards", 'name' => "Buyer's Standards" ];
        array_push($acceptanceLevel, $lastData);


        



        //$sql3 = "SELECT id, test_auth_name as name FROM kn_master_ext_lab WHERE status=1";

        //$sql3 = "SELECT id, vendor_name as name FROM ".KN_MASTER_TESTINGAUTHORITY." WHERE status=1";
        //$testingAuthority = $this->db->query($sql3)->result_array();

     $sql3 = "SELECT b.id, b.vendor_name as name FROM ".KN_MASTER_TESTINGAUTHORITY."  AS b LEFT JOIN " . KN_USERS . " AS u ON b.updatedby = u.id
                WHERE u.subscriber_id IN (" . implode(',', array_map('intval', $subscriber_ids)) . ")  AND b.status = 1";
     $testingAuthority = $this->db->query($sql3)->result_array();



        $approvingAuthority = [
            [ 'id'=> 'BUYER', 'name' => 'BUYER' ],
            [ 'id'=> 'LIASON OFFICE', 'name' => 'LIASON OFFICE' ],
            [ 'id'=> 'BUYING OFFICE', 'name' => 'BUYING OFFICE' ],
            [ 'id'=> 'MANAGEMENT', 'name' => 'MANAGEMENT' ],
            [ 'id'=> 'OTHERS', 'name' => 'OTHERS' ],
        ];

        $output['data'] = $finalValue;
        $output['comboData'] = $comboData;
        $output['itemDescData'] = $itemDescData;
        $output['labTestingAuthority'] = $labTestingAuthority;
        $output['acceptanceLevel'] = $acceptanceLevel;
        $output['testingAuthority'] = $testingAuthority;
        $output['approvingAuthority'] = $approvingAuthority;
        return $output;
    }
    
    public function updateLabTestingAcceptanceExternalDetailss($req_data, $id) {        
        foreach ($req_data as $key => $value)
        {
            $labTestList["enquiry_id"] = $id;
            $labTestList["lab_testing_acceptance_external_id"] = $value[1];
            $labTestList["combo_color"] = $value[2];
            $labTestList["item_description"] = $value[3];
            $labTestList["lab_test_parameter"] = $value[4];
            $labTestList["acceptance_lvl"] = $value[5];
            $labTestList["testing_authority"] = $value[6];
            $labTestList["approving_authority"] = $value[7];
            if($value[0] == "edit" && $labTestList["lab_testing_acceptance_external_id"] !="") {
                $this->db->where('enquiry_id', $labTestList["enquiry_id"]);
                $this->db->where('lab_testing_acceptance_external_id', $labTestList["lab_testing_acceptance_external_id"]);
                $this->db->update('tbl_lab_testing_acceptance_external', $labTestList);
            }
            else {
                $this->db->insert('tbl_lab_testing_acceptance_external', $labTestList);
            }
        }
    }

    // ********** LAB TESTING ACCEPTANCE EXTERNAL ENDS HERE *********** /

    // ********** EXTERNAL LAB TESTING AUTHORITY STARTS HERE *********** /
    
    public function getExternalLabTestingAuthorityDetailss($id) {
        //$sql = "SELECT * FROM tbl_external_lab_testing_authority telta INNER JOIN kn_master_ext_lab kmel ON telta.lab_testing_authority_id=kmel.id WHERE telta.enquiry_id = " . $id . " AND telta.flag=1";
        $sql = "SELECT * FROM tbl_external_lab_testing_authority telta INNER JOIN ".KN_MASTER_TESTINGAUTHORITY." kmel ON telta.lab_testing_authority_id=kmel.id WHERE telta.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = ['edit', $value['external_lab_testing_authority_id'], $value['lab_testing_authority_id'], '', '', '', '', '', $value['website_id'],
            $value['user_id'], $value['password_expiry_date']];
        }

        $sql2 = "SELECT id, vendor_name as name, contactperson as cname, address, emailid as email, mobile, gstno as gst  FROM ".KN_MASTER_TESTINGAUTHORITY." WHERE status=1";
        //$sql2 = "SELECT id, test_auth_name as name, contact_name as cname, address, email, mobile, gst  FROM kn_master_ext_lab WHERE status=1";
        $labTestingAuthority = $this->db->query($sql2)->result_array();

        $output['data'] = $result;
        $output['labTestingAuthority'] = $labTestingAuthority;
        return $output;
    }
    
    public function updateExternalLabTestingAuthorityDetailss($req_data, $id) {        
        foreach ($req_data as $key => $value)
        {
            $labTestList["enquiry_id"] = $id;
            $labTestList["external_lab_testing_authority_id"] = $value[1];
            $labTestList["lab_testing_authority_id"] = $value[2];
            $labTestList["website_id"] = $value[8];
            $labTestList["user_id"] = $value[9];
            $labTestList["password_expiry_date"] = $value[10];
            if($value[0] == "edit" && $labTestList["external_lab_testing_authority_id"] !="") {
                $this->db->where('enquiry_id', $labTestList["enquiry_id"]);
                $this->db->where('external_lab_testing_authority_id', $labTestList["external_lab_testing_authority_id"]);
                $this->db->update('tbl_external_lab_testing_authority', $labTestList);
            }
            else {
                $this->db->insert('tbl_external_lab_testing_authority', $labTestList);
            }
        }
    }

    // ********** EXTERNAL LAB TESTING AUTHORITY ENDS HERE *********** /

    // ************************ LAB ENDS HERE ************************* /
    
    
    public function get_component_wise_packingg($id) {
         $masterAdmins = $this->master_loginid();
         $subscriber_ids = array_column($masterAdmins, 'subscriber_id');


        $sql = "SELECT *, b.combo as po_combo FROM tbl_oe_po_wise as a INNER JOIN tbl_oe_combo_color as b ON a.combo_color_id = b.combo_color_id WHERE a.enquiry_id = " . $id . " ";
        $data = $this->db->query($sql)->result_array();

        //$packing_sql = "SELECT id as id, packingname as name from kn_master_packing_code";
        //$packing_data = $this->db->query($packing_sql)->result_array();

     $packing_sql = "SELECT b.id, b.packingname as name FROM kn_master_packing_code  AS b LEFT JOIN " . KN_USERS . " AS u ON b.updatedby = u.id
             WHERE u.subscriber_id IN (" . implode(',', array_map('intval', $subscriber_ids)) . ")  AND b.status = 1";
     $packing_data = $this->db->query($packing_sql)->result_array();


        // ******* GET THE DATA DETAILS ENDS ********* //


        //***** final data *****
        $finalValue = [];
        $lastKey = 0;
        // foreach ($data as $key => $value) {
        for($i = 0; $i < sizeof($data); $i++) {
            $old_pono_enq = '';
            if($i > 0) {
                $old_pono_enq = $data[$lastKey]['pono_enq_refno'];
            }
            $pono_enq = $data[$i]['pono_enq_refno'];
            
            if($pono_enq == $old_pono_enq) {
                // $combineValue = ['edit', $data[$i]['po_size_wise_id'], '', $data[$i]['po_combo'],
                // $data[$i]['component'], $data[$i]['colour'], $data[$i]['intake_qty'], $data[$i]['comp_wise_packing_code'], $data[$i]['pcs_set']];

                 $combineValue = [
    'edit',
    $data[$i]['po_size_wise_id'] ?? '',
    '',
    $data[$i]['po_combo'] ?? '',
    $data[$i]['component'] ?? '',
    $data[$i]['colour'] ?? '',
    $data[$i]['intake_qty'] ?? '',
    $data[$i]['comp_wise_packing_code'] ?? '',
    $data[$i]['pcs_set'] ?? ''
];

            }
            else {
                $lastKey = $i;
                // $combineValue = ['edit', $data[$i]['po_size_wise_id'], $data[$i]['pono_enq_refno'], $data[$i]['po_combo'],
                // $data[$i]['component'], $data[$i]['colour'], $data[$i]['intake_qty'], $data[$i]['comp_wise_packing_code'], $data[$i]['pcs_set']];
           $combineValue = [
    'edit',
    $data[$i]['po_size_wise_id'] ?? '',
    $data[$i]['pono_enq_refno'] ?? '',
    $data[$i]['po_combo'] ?? '',
    $data[$i]['component'] ?? '',
    $data[$i]['colour'] ?? '',
    $data[$i]['intake_qty'] ?? '',
    $data[$i]['comp_wise_packing_code'] ?? '',
    $data[$i]['pcs_set'] ?? ''
];

                }
            array_push($finalValue, $combineValue);
        }

        $output['data'] = $finalValue;
        $output['packingSource'] = $packing_data;
        return $output;
    }

    public function update_component_wise_packingg($req_data, $id) {

        foreach ($req_data as $key => $value) {
            $checkList['comp_wise_packing_code'] = $value[7];
            $this->db->where('po_size_wise_id', $value[1]);
            $this->db->where('enquiry_id', $id);
            $this->db->update('tbl_oe_po_wise', $checkList);
            
        }
        return true;
    }

    public function getCADCommonTableDetailss($enqId) {
        $sql = "SELECT *,a.cutoff_date as req_cutoff_date, b.ref_queue_no as cad_ref_no, a.ref_queue_no as cad_ref_queue_no ,a.req_status as req_status1,c.job_schd_dt as job_schd_dt,c.qno_assign_dt as qno_assign_dt_1,c.job_status_upd_dt as job_status_upd_dt,a.log as logs,c.qa_schd_date as qa_schd_date ,c.qa_approval as qa_approval FROM tbl_request a
                INNER JOIN tbl_request_cad b on a.request_id=b.request_id
                INNER JOIN tbl_cad_requirement c on b.cad_id=c.cad_requirement_id
                WHERE a.enquiry_id = " . $enqId . "  AND a.type=1";
        $query = $this->db->query($sql);
        $data = $query->result_array();

       // print_r($data);
       // die;
        
        $reqdetails = $qaauditdetails = [];

        $authStatus = [ 'PENDING', 'AUTHORIZED', 'DECLINED', 'PENDING-RR', ];

        $reqStatus = [ 'PENDING', 'ACCEPTED', 'DECLINED', 'PENDING-RR','-', ];

        //$qaStatus = [ 'PENDING', 'SCHEDULED', 'RESCHEDULED', 'PENDING - RR', 'DISCREPANCY',  'PASS', 'PASS COND.', 'FAIL' ];
        
        
        $qaStatus = [ 'IN QUEUE', 'SCHEDULED', 'RE-SCHEDULED', 'Q.A.IN PROGRESS', 'DISCREPANCY',  'PASS', 'PASS COND.', 'FAIL','-','IN-QUEUE RR', ];

       // $jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ.', 'COMPLETED', 'Q.A. RR', 'REWORK' ];
         //$jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT', 'COMPLETED', 'Q.A. RR SENT', 'RE-WORK PEND.','RE-WORK IN PROG.','JOB IN PROG.' ,'GAR. ISSUED','-' ];
        $jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT.', 'COMPLETED', 'Q.A. RR SENT', 'RE-WORK PEND.','RE-WORK IN PROG.','WORK IN PROG.','-' ];


        foreach ($data as $key => $value)
        {

          
            if($value['auth_status'] == '') { $value['auth_status'] = 0; }
            if($value['req_status'] == '') { $value['req_status'] = 0; }
            if($value['job_status'] == '') { $value['job_status'] = 0; }


                 if($value['req_status'] == 0) {
                       $value['job_status'] = '9'; 
                         $value['job_status_upd_dt'] = '-';
                       
                         
                 }
                 
                 if($value['job_status'] <=0) {
                     if($value['req_status'] == 1) {
                       $value['job_status_upd_dt'] = $value['qno_assign_dt_1']; 
                         
                 }
                 }
                 if($value['job_status'] == 0 && $value['job_schd_dt'] == null ) {
       
        //$value['job_status_upd_dt'] = date('d/m/Y h:i A',strtotime($value['logs']));
        $value['job_status_upd_dt'] = $value['que_assign_date'];
        //$value['req_status']='-'
    }
                 
                  if($value['qa_approval'] == 0) {
                       $value['qa_status'] = '8';
                       $value['qa_sta_upd_dt'] = '-'; 
                 }
                  
             if($value['qa_approval'] == 1 &&  $value['qa_schd_date'] == null ) {
                $value['qa_sta_upd_dt'] = $value['qno_assign_dt'];
            } 

                 if($value['req_status'] === 0 || $value['auth_status'] === 0)  {
                    $value['que_assign_date'] = '-';
                    $value['cad_ref_queue_no'] = '-';
                 }

                 if( $value['auth_status'] === '3' || $value['auth_status'] == '0'  || $value['auth_status'] === '2' ) { 
                    $value['req_status'] = '4';
                 }

            $reqdetails[$key] = [ $value['request_id'], $value['po_enq_ref_id'], $value['combo_id'],
                            $value['component_id'], $value['cad_requirement'], $value['req_date'], $value['req_cutoff_date'], 
                            $authStatus[$value['auth_status']], $reqStatus[$value['req_status']], $value['cad_ref_queue_no'], $value['que_assign_date']
                        ];

            $qaauditdetails[$key] = [ $value['request_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                            $value['cad_requirement'], $value['cad_ref_no'], $qaStatus[$value['qa_status']], $value['qa_sta_upd_dt'], 
                            $jobStatus[$value['job_status']], $value['job_status_upd_dt']
                        ];
                            
        }
        
        $output['reqdetails'] = $reqdetails;
        $output['qaauditdetails'] = $qaauditdetails;
        return $output;
    }

    public function getSampleCommonTableDetailss($enqId) {

        $sql = "SELECT *,a.cutoff_date as req_cutoff_date, b.ref_queue_no as sample_ref_no ,a.ref_queue_no as sample_ref_queue_no,c.job_schd_date as job_schd_dt, a.qno_assign_dt as qno_assign_dt,c.qa_approval as qa_approval,c.log as logs
                FROM tbl_request a
                INNER JOIN tbl_request_sample b on a.request_id=b.request_id
                INNER JOIN tbl_sample_requirement c on b.sample_id=c.sample_requirement_id
                WHERE a.enquiry_id = " . $enqId . "  AND a.type=2";
        $query = $this->db->query($sql);
        $data = $query->result_array();

        
        
        $authStatus = [ 'PENDING', 'AUTHORIZED', 'DECLINED', 'PENDING-RR' ];
        
        $reqStatus = [ 'PENDING', 'ACCEPTED', 'DECLINED', 'PENDING-RR','-' ];

        //$qaStatus = [ 'PENDING', 'SCHEDULED', 'RESCHEDULED', 'PENDING - RR', 'DISCREPANCY',  'PASS', 'PASS COND.', 'FAIL' ];
       $qaStatus = [ 'IN QUEUE', 'SCHEDULED', 'RE-SCHEDULED', 'Q.A. IN PROGRESS', 'NEED ALTERATION',  'PASS', 'PASS COND.', 'FAIL','-', 'IN-QUEUE RR',];

        //$jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ.', 'COMPLETED', 'Q.A. RR', 'REWORK' ];
        $jobStatus = [ 'IN-QUEUE', 'SCHEDULED', 'RESCHEDULED', 'Q.A. REQ. SENT', 'COMPLETED', 'Q.A. RR SENT', 'ALT. PEND.','ALT. IN PROG.','JOB IN PROG.' ,'GAR. ISSUED','REWORK','-' ];


        $reqdetails = $qaauditdetails = $despatchdetails =[];

        foreach ($data as $key => $value)
        {

            if($value['auth_status'] == '') { $value['auth_status'] = 0; }
            if($value['req_status'] == '') { $value['req_status'] = 0; }
            if($value['job_status'] == '') { $value['job_status'] = 0; }
            if($value['qa_status'] == '') { $value['qa_status'] = 0; }
            
           
            //  if($value['qno_assign_dt'] === '' || is_null($value['qno_assign_dt'])) {
            //         $value['que_assign_date'] = '-';
            //         $value['ref_queue_no'] = '-';
            //      }

                  if($value['qa_approval'] == 0) {
                       $value['qa_status'] = '8';
                       $value['qa_sta_upd_dt'] = '-'; 
                 }
                  if($value['qa_approval'] == 1) {
                      
                       $value['qa_sta_upd_dt'] = date('d/m/Y h:i A',strtotime($value['logs'])); 
                 }


                  if($value['req_status'] == 0) {
                       $value['job_status'] = '11'; 
                       $value['job_sta_upd_dt'] = '-';
                         
                 }
                  if($value['job_status'] <=0) {
                 if($value['req_status'] == 1) {
                       $value['job_sta_upd_dt'] = $value['qno_assign_dt']; 
                         
                 }
                }

                 if($value['job_status'] == 0 && $value['job_schd_dt'] == null ) {
       
        //$value['job_status_upd_dt'] = date('d/m/Y h:i A',strtotime($value['logs']));
        $value['job_status_upd_dt'] = $value['que_assign_date'];
        //$value['req_status']='-'
    }
                 
               if($value['req_status'] === 0 || $value['auth_status'] === 0)  {
                    $value['que_assign_date'] = '-';
                    $value['sample_ref_queue_no'] = '-';
                 }
                
                 if( $value['auth_status'] === '3' || $value['auth_status'] == '0'  || $value['auth_status'] === '2' ) { 
                    $value['req_status'] = '4';
                 }

            $reqdetails[$key] = [ $value['request_id'], $value['po_enq_ref_id'], $value['combo_id'],
                            $value['component_id'], $value['color_id'], $value['sample_requirement'], $value['req_date'], $value['req_cutoff_date'], 
                            $authStatus[$value['auth_status']], $reqStatus[$value['req_status']], $value['sample_ref_queue_no'], $value['que_assign_date']
                        ];

            $qaauditdetails[$key] = [ $value['request_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['sample_requirement'], $value['sample_ref_no'], $qaStatus[$value['qa_status']], $value['qa_sta_upd_dt'],  
                            $jobStatus[$value['job_status']], $value['job_sta_upd_dt']
                        ];
                
            if($value['des_appr_status'] == 0) {
                $app_status = 'Yes';
            } else {
                $app_status = 'No';
            }
            
            if($value['des_appr_by'] == '') {
                $app_by = 'Yes';
            } else {
                $app_by = 'No';
            }
            
            if($value['appr_rec_dt'] == '') {
                $app_dt = 'Yes';
            } else {
                $app_dt = 'No';
            }

            $despatchdetails[$key] = [ $value['sample_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['sample_requirement'], $value['sample_ref_no'], $value['air_bill_no'], $value['air_bill_date'], 
                            $value['delivery_status'], $value['delivery_date'], $value['des_appr_status'], $value['des_appr_by'], $value['appr_rec_dt'], $value['job_status'], $value['delivery_status'], $app_status, $app_by, $app_dt
                        ];
                            
        }
        
        $output['reqdetails'] = $reqdetails;
        $output['qaauditdetails'] = $qaauditdetails;
        $output['despatchdetails'] = $despatchdetails;
        return $output;
    }

    public function getBomCommonTableDetailss($enqId) {
        $sql = "SELECT b.*,c.*,d.pi_ref_queue_no,d.exp_dod as exp_dods,d.pi_appl_status as pi_appl_status, d.pi_dt,e.qty as pi_qty,e.uom as pi_uom FROM tbl_request_bom b
                LEFT JOIN tbl_request c on b.request_id=c.request_id
                LEFT JOIN tbl_purchase_indent d on b.purchase_indent_id=d.purchase_indent_id
                LEFT JOIN tbl_request_purchase_indent e on b.request_bom_id=e.request_bom_id
                WHERE c.enquiry_id = " . $enqId . "  AND c.type = 3   ";
        $data = $this->db->query($sql)->result_array();
        
        $pidetails = $inhousestatusdetails = $itemacceptstatus = $inhouseconsolidatedqtydetails = [];

        foreach ($data as $key => $value)
        {
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
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], $value['pi_dt'], $value['pi_ref_queue_no'], $value['pi_qty'], $value['pi_uom'], $status, $value['pi_appl_req_date_time'],$value['exp_dods'], $value['purchase_req_type']
                        ];

           
        }
        
        
        $sql1 = "SELECT b.*,c.plan_bom_qty,e.pi_ref_queue_no FROM tbl_bom_in_house b
                LEFT JOIN tbl_request_bom c on b.request_bom_id = c.request_bom_id
                LEFT JOIN tbl_request d on c.request_id=d.request_id
                LEFT JOIN tbl_purchase_indent e on b.purchase_indent_id=e.purchase_indent_id
                WHERE d.enquiry_id = " . $enqId . " AND d.type = 3  ";
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
                            $value['appr_item_color_code'], @$value['dc_no'], date('d/m/Y',strtotime($value['dc_date'])),  @$value['dc_qty'], @$value['uom'], $value['merchant_item_status'], $value['merchant_appl_date_time'], 
                            @$value['qa_status'], @$value['qa_status_upt_dt'], @$value['mgmt_ovrd_status'], @$value['mgmt_status_upd_dt']
                        ];
            }
             
        }
        
        $where = '((b.merchant_item_status = 1 and b.qa_status = 1 and b.mgmt_ovrd_status = 0) or (b.merchant_item_status = 1 and b.qa_status = 2 and b.mgmt_ovrd_status = 1) or (b.merchant_item_status=2 and b.qa_status = 1 and b.mgmt_ovrd_status = 1) or (b.merchant_item_status=2 and b.qa_status = 2 and b.mgmt_ovrd_status = 1) )';
        $sql2 = "SELECT c.*, b.order_stock_status,b.received_uom, b.supply_closure_status,b.supply_closure_date, SUM(b.received_qty) as received_qtys, c.plan_bom_qty FROM tbl_request_bom c
                LEFT JOIN tbl_bom_in_house b on c.request_bom_id = b.request_bom_id  AND $where
                LEFT JOIN tbl_request d on c.request_id=d.request_id
                WHERE d.enquiry_id = " . $enqId . " AND d.type = 3  GROUP BY c.item_desc,c.garment_size,c.appr_item_code,c.appr_item_col_code,c.size_dim,c.uom ORDER BY c.request_bom_id  ";
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
    
                
                // if($con_status === 'Consolidated') {
                //     $plan_bom_qty = $value['plan_bom_qty'];
                //     $received_qty = $value['received_qtys'];
                //     $received_uom = $value['received_uom'];
                // } else if($value['order_stock_status'] == 1) {
                //     $plan_bom_qty = $value['plan_bom_qty'];
                //     $received_qty = $value['received_qtys'];
                //     $received_uom = $value['received_uom'];
                // } else {
                //     $tot_diff = 0;
                //     $plan_bom_qty = 0;
                //     $received_qty = 0;
                //     $received_uom = '';
                // }
                
                $plan_bom_qty = $value['plan_bom_qty'];
                $received_qty = $value['received_qtys'];
                $received_uom = $value['requirement_uom'];
                    
                $tot_diff = $received_qty - $plan_bom_qty;
                
                // if($con_status == 'Consolidated' || $value['order_stock_status'] == 1) {
                    $inhouseconsolidatedqtydetails[$k] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], $plan_bom_qty, $received_qty, $tot_diff, $received_uom, $value['supply_closure_status'], $value['supply_closure_date'], 
                        ];
                    $k++;
                // }
                
                
            }
             
        }
        
        $output['pidetails'] = $pidetails;
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['itemacceptstatus'] = $itemacceptstatus;
        $output['inhouseconsolidatedqtydetails'] = $inhouseconsolidatedqtydetails;
        return $output;

        //print_r($output);
    }

     public function getBom2CommonTableDetailss($enqId) {
        $sql = "SELECT b.*,c.*,d.pi_ref_queue_no,d.exp_dod as exp_dods,d.pi_appl_status as pi_appl_status, d.pi_dt,e.qty as pi_qty,e.uom as pi_uom FROM tbl_request_bom b
                LEFT JOIN tbl_request c on b.request_id=c.request_id
                LEFT JOIN tbl_purchase_indent d on b.purchase_indent_id=d.purchase_indent_id
                LEFT JOIN tbl_request_purchase_indent e on b.request_bom_id=e.request_bom_id
                WHERE c.enquiry_id = " . $enqId . "  AND c.type = 4 ";
        $data = $this->db->query($sql)->result_array();
        
        $pidetails = $inhousestatusdetails = $itemacceptstatus = $inhouseconsolidatedqtydetails = [];

        foreach ($data as $key => $value)
        {
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
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], $value['pi_dt'], $value['pi_ref_queue_no'], $value['pi_qty'], $value['pi_uom'], $status, $value['pi_appl_req_date_time'],$value['exp_dods'], $value['purchase_req_type']
                        ];

           
        }
        
        
        $sql1 = "SELECT b.*,c.plan_bom_qty,e.pi_ref_queue_no FROM tbl_bom_in_house b
                LEFT JOIN tbl_request_bom c on b.request_bom_id = c.request_bom_id
                LEFT JOIN tbl_request d on c.request_id=d.request_id
                LEFT JOIN tbl_purchase_indent e on b.purchase_indent_id=e.purchase_indent_id
                WHERE d.enquiry_id = " . $enqId . " AND d.type = 4  ";
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
                            $value['appr_item_color_code'], @$value['dc_no'], date('d/m/Y',strtotime($value['dc_date'])),  @$value['dc_qty'], @$value['uom'], $value['merchant_item_status'], $value['merchant_appl_date_time'], 
                            @$value['qa_status'], @$value['qa_status_upt_dt'], @$value['mgmt_ovrd_status'], @$value['mgmt_status_upd_dt']
                        ];
            }
             
        }
        
        $where = '((b.merchant_item_status = 1 and b.qa_status = 1 and b.mgmt_ovrd_status = 0) or (b.merchant_item_status = 1 and b.qa_status = 2 and b.mgmt_ovrd_status = 1) or (b.merchant_item_status=2 and b.qa_status = 1 and b.mgmt_ovrd_status = 1) or (b.merchant_item_status=2 and b.qa_status = 2 and b.mgmt_ovrd_status = 1) )';
        $sql2 = "SELECT c.*, b.order_stock_status,b.received_uom, b.supply_closure_status,b.supply_closure_date, SUM(b.received_qty) as received_qtys, c.plan_bom_qty FROM tbl_request_bom c
                LEFT JOIN tbl_bom_in_house b on c.request_bom_id = b.request_bom_id  AND $where
                LEFT JOIN tbl_request d on c.request_id=d.request_id
                WHERE d.enquiry_id = " . $enqId . " AND d.type = 4  GROUP BY c.item_desc,c.garment_size,c.appr_item_code,c.appr_item_col_code,c.size_dim,c.uom ORDER BY c.request_bom_id ";
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
    
                
                // if($con_status === 'Consolidated') {
                //     $plan_bom_qty = $value['plan_bom_qty'];
                //     $received_qty = $value['received_qtys'];
                //     $received_uom = $value['received_uom'];
                // } else if($value['order_stock_status'] == 1) {
                //     $plan_bom_qty = $value['plan_bom_qty'];
                //     $received_qty = $value['received_qtys'];
                //     $received_uom = $value['received_uom'];
                // } else {
                //     $tot_diff = 0;
                //     $plan_bom_qty = 0;
                //     $received_qty = 0;
                //     $received_uom = '';
                // }
                
                $plan_bom_qty = $value['plan_bom_qty'];
                $received_qty = $value['received_qtys'];
                $received_uom = $value['requirement_uom'];
                    
                $tot_diff = $received_qty - $plan_bom_qty;
                
                // if($con_status == 'Consolidated' || $value['order_stock_status'] == 1) {
                    $inhouseconsolidatedqtydetails[$k] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], $plan_bom_qty, $received_qty, $tot_diff, $received_uom, $value['supply_closure_status'], $value['supply_closure_date'], 
                        ];
                    $k++;
                // }
                
                
            }
             
        }
        
        $output['pidetails'] = $pidetails;
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['itemacceptstatus'] = $itemacceptstatus;
        $output['inhouseconsolidatedqtydetails'] = $inhouseconsolidatedqtydetails;
        return $output;
    }



    public function getBom2CommonTableDetailss_old($enqId) {
        $sql = "SELECT * FROM tbl_request_bom_2 b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.enquiry_id = " . $enqId . " ";
        $data = $this->db->query($sql)->result_array();
        
        $pidetails = $inhousestatusdetails = $itemacceptstatus = $inhouseconsolidatedqtydetails = [];

        foreach ($data as $key => $value)
        {

            $pidetails[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], $value['pi_appl_req_date_time'], $value['pi_appl_status'], '', '', '',
                            $value['requirement_uom'], '', '', ''
                        ];

            $inhousestatusdetails[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], '', '', '', '', '', '', '', '', $value['requirement_uom'], ''
                        ];

            $itemacceptstatus[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], '', '', '', '', $value['merchant_item_status'], $value['merchant_appl_date_time'], 
                            $value['qa_status'], $value['qa_status_upt_dt'], $value['mgmt_ovrd_status'], $value['mgmt_status_upd_dt']
                        ];

            $inhouseconsolidatedqtydetails[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], $value['plan_bom_qty'], '', '', $value['requirement_uom'], '', '', '', ''
                        ];
                            
        }
        
        $output['pidetails'] = $pidetails;
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['itemacceptstatus'] = $itemacceptstatus;
        $output['inhouseconsolidatedqtydetails'] = $inhouseconsolidatedqtydetails;
        return $output;
    }

    public function UpdateOrderProcesss($eId, $total_order_qty, $uom, $season, $class, $divi_dept, $sub_class)
    {
        $updateValue['total_order_qty'] = $total_order_qty;
        $updateValue['uom'] = $uom;
        $updateValue['season'] = $season;
        $updateValue['class'] = $class;
        $updateValue['divi_dept'] = $divi_dept;
        $updateValue['sub_class'] = $sub_class;
        $this->db->where('id', $eId);
        $this->db->update('kn_order_enquiry', $updateValue);
    }

    public function checkDraftorNot($id)
    {
        $result = $this->db->from('tbl_sample_requirement')->where('enquiry_id', $id)->where('req_draft_status', 1)->where('req_reference_status', 1)->get()->num_rows();
        return $result;
    }

    public function updateSampleDespatchApprovall($req_data, $id) {
        foreach ($req_data as $key => $value) {
            $despatchData['des_air_bill_no'] = $value[6];
            $despatchData['air_bill_no'] = $value[7]; 
            //$despatchData['air_bill_date'] = $value[8]; 
            $despatchData['delivery_status'] = $value[9];
            $despatchData['delivery_date'] = $value[10];
            $despatchData['des_appr_status'] = $value[11];
            $despatchData['des_appr_by'] = $value[12];
            $despatchData['appr_rec_dt'] = $value[13];
            $this->db->where('sample_requirement_id', $value[0]);
            $this->db->where('enquiry_id', $id);
            $this->db->update('tbl_sample_requirement', $despatchData);
            
        }
        return true;
    }

    public function get_draft_valuee($enq_id)
    {
        $tot = 0;
        $tot = $this->db->where('enquiry_id',$enq_id)->where('req_draft_status',1)->where('req_sent_status',0)->get('tbl_bom_article_1_req_consld')->num_rows();
        return $tot;
    }
    
    public function get_req_empty_valuee($enq_id)
    {
        $tot = 0;
        $tot = $this->db->where('enquiry_id',$enq_id)->where('req_sent_status',0)->get('tbl_sample_requirement')->num_rows();
        return $tot;
    }
    
}
