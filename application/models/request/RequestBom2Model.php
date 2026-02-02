<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RequestBom2Model extends CI_Model
{
    private $mysqldatetime;
    public function __construct()
    {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->model('commonmodel');
        $ArrUserLoggedInfo   = fnGetUserLoggedInfo('1');
        $this->companyid     = $ArrUserLoggedInfo['companyid'];
        $this->mysqldatetime = date('d/m/Y h:i A');
        $this->userid        = $ArrUserLoggedInfo['id'];
        $ArrObjsubscriber_id = $this->commonmodel->getsubscriberid($this->userid = $ArrUserLoggedInfo['id']);
        $this->subscriber_id = $ArrObjsubscriber_id->subscriber_id;
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
        $sql = "SELECT *, SUM(`req_bom_qty`) as calc_bom_qty FROM tbl_bom_article_2_requirement as a 
                where a.enquiry_id='$id' 
                GROUP BY a.item_desc, a.blend, a.content, a.material, a.garment_size, a.appr_item_code, 
                        a.appr_item_col_code, a.size_dim, a.uom ORDER BY a.bom_2_req_id";
        $data = $this->db->query($sql)->result_array();

        
        
        $sql1 = "SELECT * FROM tbl_bom_article_2_req_consld as a 
                LEFT JOIN tbl_request as b ON a.request_id=b.request_id
                WHERE a.enquiry_id='$id' ";
        $data1 = $this->db->query($sql1)->result_array();
       

        $sql2 = "SELECT * FROM tbl_bom_2_sourcing_details as a 
                INNER JOIN kn_master_bom_vendor as b ON a.vendor_name_address=b.id
                WHERE a.enquiry_id='$id' ";
        $data2 = $this->db->query($sql2)->result_array();
        
        

        $sql3 = "SELECT * FROM tbl_bom_article_2_requirement as a 
                where a.enquiry_id='$id' 
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
            
            if( isset($data1[$i]['bom_2_req_consld_id'])) {
                $bom_1_req_consld_id = $data1[$i]['bom_2_req_consld_id'];
                $data[$i]['bom_2_req_consld_id'] = $data1[$i]['bom_2_req_consld_id'];
            }
            else {
                $bom_1_req_consld_id = "";
                $data[$i]['bom_2_req_consld_id'] = "";
            }

            if( isset($data1[$i]['bom_2_req_consld_id'])) {
                $request_id[] = $data1[$i]['request_id'];
            }


   
            $data[$i]['req_type'] = $data1[$i]['req_type'];
            $data[$i]['req_date'] = $data1[$i]['req_date'];
            $data[$i]['cutoff_date'] = $data1[$i]['cutoff_date'];
            $data[$i]['merchant_note'] = $data1[$i]['merchant_note'];
            $data[$i]['req_sent_status'] = $data1[$i]['req_sent_status'];

            $data[$i]['bom_2_source_id'] = $data[$i]['sourcing_advice'] = $data[$i]['vendor_location'] = $data[$i]['vendor_name_address'] =
            $data[$i]['contact_email'] = $data[$i]['gst'] = $data[$i]['online_order_sys'] = $data[$i]['pass_expiry_date'] = '';

           

            for ($j=0; $j < sizeof($data3); $j++) { 
                if($data3[$j]['item_desc'] == $data[$i]['item_desc']) {
                    $vendor_name_address = $data2[$j]['vendorname'].' / '.$data2[$j]['address'];
                    $data[$i]['bom_2_source_id']  = $data2[$j]['bom_2_source_id'];    
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
                    WHERE a.enquiry_id='$id' AND a.appr_item_code=".$item_code." AND b.purchase_req_type = 'BULK' ";
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
            else if($data1[$i]['req_draft_status'] == 1 ) {
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
         
        
        $type_sql = "SELECT * FROM tbl_request as a where a.enquiry_id='$id' AND a.type = 4 AND a.purchase_req_type = 'BULK'  ";
        $type_data = $this->db->query($type_sql)->num_rows();
        
        $bulk_sql = "SELECT * FROM tbl_request_bom as a 
                INNER JOIN tbl_request as b ON a.request_id=b.request_id
                WHERE a.enquiry_id='$id' AND b.type = 4 AND b.purchase_req_type = 'BULK' ";
        $bulk_data = $this->db->query($bulk_sql)->result_array();
        
        $output['data'] = $result;
        $output['bulk_count'] = $type_data;
        $output['bulk_data'] = $bulk_data;
        $output['totData'] = array_values($data);
        $output['sourcing_result'] = array_values($sourcing_result);
        $output['req_data'] = $req_data;
        return $output;
    }

     public function clearPurchaseRequest( $id) {
        $enquiry_id = $id;

        $req_data = $this->db->where('enquiry_id',$enquiry_id)->where('req_draft_status',1)->get('tbl_bom_article_2_req_consld')->result_array();
        foreach($req_data as $r) {
            $this->db->where('request_id',$r['request_id'])->delete('tbl_request');
        }
        $res = $this->db->where('enquiry_id',$enquiry_id)->where('req_draft_status',1)->update('tbl_bom_article_2_req_consld',array('req_draft_status'=>0,'request_id'=>0,'draft_consl_bom_qty'=>'','draft_excess_qty'=>'','draft_plan_bom_qty'=>''));

        if($res) {
            $result['status'] = 'Success';
        } else {
            $result['status'] = 'Fail';
        }

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
    
     public function get_draft_valuee($enq_id)
    {
        $tot = 0;
        $tot = $this->db->where('enquiry_id',$enq_id)->where('req_draft_status',1)->where('req_sent_status',0)->get('tbl_bom_article_2_req_consld')->num_rows();
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
        $sql = $this->db->where('enquiry_id',$enq_id)->where('req_draft_status',1)->get('tbl_bom_article_2_req_consld')->result_array();
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
    // ********** SAMPLE REQUEST ENDS HERE *********** /

    // ********** CREATE SAMPLE REQUEST STARTS HERE *********** /

    // public function createPurchaseRequestt($req_data, $id, $req_type, $cutoff_date, $merchant_note, $purchase_req_type, $mode, $type, $req_id) {
    //     $requestValue['enquiry_id'] = $id;
    //      $requestValue['subscriberid'] = $this->subscriber_id;
    //      $requestValue['req_by'] = $this->userid;
    //     $requestValue['companyid'] = $this->companyid;
    //     $requestValue['type'] = 4;
    //     $requestValue['req_type'] = $req_type;
    //     $requestValue['req_date'] = $this->mysqldatetime;
    //     $requestValue['cutoff_date'] = $cutoff_date;
    //     $requestValue['merchant_note'] = $merchant_note;
    //     $requestValue['purchase_req_type'] = $purchase_req_type;
    //      $requestValue['log'] = LOGTIME;
    //     if($type == 'save') {
    //         $requestValue['draft_status'] = 0;
    //         $requestValue['req_date'] = $this->mysqldatetime;
    //     }
    //     else {
    //         $requestValue['draft_status'] = 1;
    //     }

    //     foreach ($bom_data as $key => $value) 
    //     {
    //         if($value[2] == false)
    //         {
    //             unset($bom_data[$key]);
    //         }
    //     }

    //     $bom_data = array_values($bom_data)
    //     if($mode == "add") {
    //         $this->db->insert('tbl_request', $requestValue);
    //         $primaryId = $this->db->insert_id();        
    //         if($primaryId) {
    //             foreach($req_data as $key => $value) {
    //                 if($type == 'draft') {
    //                     $this->db->where('bom_2_req_consld_id', $value[1]);
    //                     $this->db->update('tbl_bom_article_2_req_consld', array('req_draft_status' => 1));
    //                 }
    //                 else if($type == 'save') {
    //                     $this->db->where('bom_2_req_consld_id', $value[1]);
    //                     $this->db->update('tbl_bom_article_2_req_consld', array('req_sent_status' => 1));
    //                 }
    //             }
    //         }
    //     } else {
    //         $updateValue['req_type'] = $req_type;
    //         $updateValue['cutoff_date'] = $cutoff_date;
    //         $updateValue['merchant_note'] = $merchant_note;
    //         $updateValue['purchase_req_type'] = $purchase_req_type;

    //         $this->db->where('request_id', $req_id);
    //         $this->db->update('tbl_request', $updateValue);

    //         foreach($req_data as $key => $value) {
    //             if($type == 'draft') {
    //                 $status = 1;
    //                 if($value[2] == false) {
    //                     $status = 0;
    //                 }
    //                 $this->db->where('bom_2_req_consld_id', $value[1]);
    //                 $this->db->update('tbl_bom_article_2_req_consld', array('req_draft_status'=> $status));
    //             }
    //             else if($type == 'save') {
    //                 if($value[2] == false) {
    //                     $status = 0;
    //                     $this->db->where('bom_2_req_consld_id', $value[1]);
    //                     $this->db->update('tbl_bom_article_2_req_consld', array('req_draft_status'=> $status));
    //                 }
    //                 else if($value[2] == true) {
    //                     $this->db->where('bom_2_req_consld_id', $value[1]);
    //                     $this->db->update('tbl_bom_article_2_req_consld', array('req_sent_status' => 1));
    //                 }
    //             }
    //         }

    //     }
        
    // }

     public function createPurchaseRequestt($req_data, $id, $req_type, $cutoff_date, $merchant_note, $purchase_req_type, $mode, $type, $req_id, $bom_data) {
        //echo "<pre>"; print_r($_POST); exit;
       //print_r($bom_data);
       //die;
       
        $userInfo = fnGetUserLoggedInfo(1);
        $subscriberId =  $userInfo['subscriber_id'];
        $requestValue['enquiry_id'] = $id;
        $requestValue['companyid'] = $this->companyid;
        $requestValue['req_by'] = $this->userid;
        $requestValue['type'] = 4;
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
                        $this->db->where('bom_2_req_consld_id', $value[1]);
                        $this->db->update('tbl_bom_article_2_req_consld', $draftVal);
                    }
                    else if($type == 'save') {
                        $this->db->insert('tbl_request_bom', $requestBOMValue);
                        if($purchase_req_type == 'BULK') {
                            
                            $this->db->where('bom_2_req_consld_id', $value[1]);
                            $this->db->update('tbl_bom_article_2_req_consld', array('req_sent_status' => 1, 'request_id' => $primaryId));
                        } else {
                            $this->db->where('bom_2_req_consld_id', $value[1]);
                            $this->db->update('tbl_bom_article_2_req_consld', array('req_draft_status'=>0,'request_id'=>0,'draft_consl_bom_qty'=>'','draft_excess_qty'=>'','draft_plan_bom_qty'=>''));
                            
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

            $this->db->where('request_id',$req_id)->update('tbl_bom_article_2_req_consld',$draftVal);

            foreach($bom_data as $key => $value) {
                
                if($type == 'draft') {
                    
                     if($value[2] == true) {
                        
                        $draftVal['draft_consl_bom_qty'] = $value[10];
                        $draftVal['draft_excess_qty'] = $value[11];
                        $draftVal['draft_plan_bom_qty'] = $value[12];
                        $draftVal['req_draft_status'] = 1;
                        $draftVal['request_id'] = $req_id;
                        $this->db->where('bom_2_req_consld_id', $value[1]);
                        $this->db->update('tbl_bom_article_2_req_consld', $draftVal);
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
                        $this->db->where('bom_2_req_consld_id', $value[1]);
                        $this->db->update('tbl_bom_article_2_req_consld', $draftVal);
                    }
                    else if($value[2] == true) {
                        if($purchase_req_type == 'BULK') {
                        foreach($bom_data as $key => $value) {
                            $this->db->where('bom_2_req_consld_id', $value[1]);
                            $this->db->update('tbl_bom_article_2_req_consld', array('req_sent_status' => 1, 'request_id'=> $req_id,'req_draft_status'=>0,'draft_consl_bom_qty'=>'','draft_excess_qty'=>'','draft_plan_bom_qty'=>''));
                        }
                           
                        } else {
                            foreach($bom_data as $key => $value) {
                            $this->db->where('bom_2_req_consld_id', $value[1]);
                            $this->db->update('tbl_bom_article_2_req_consld', array('req_draft_status'=>0,'request_id'=>0,'draft_consl_bom_qty'=>'','draft_excess_qty'=>'','draft_plan_bom_qty'=>''));
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
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . "  AND b.qa_req_status = 1";
        $data = $this->db->query($sql)->result_array();
        
        $result = $att_result = $qa_status_result = $job_status_result = [];

        // return $data;

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

        $uomData = unserialize(ARRUNITOFMEASURE);
        
        $output['purchaserequest'] = $result;
        $output['sourcedetails'] = $att_result;
        $output['pidetails'] = $pidetails;
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['itemacceptstatus'] = $itemacceptstatus;
        $output['inhouseconsolidatedqtydetails'] = $inhouseconsolidatedqtydetails;
        $output['uomData'] = $uomData;
        return $output;
    }

    // ********** MERCHANT SAMPLE QUEUE ENDS HERE *********** /
    
    // ********** UPDATE MERCHANT SAMPLE QUEUE ENDS HERE *********** /

    public function updateMerchantQueuee($data, $enqId)
    {
        foreach($data as $key => $value) {
            $this->db->where('request_bom_id', $value[0]);
            $this->db->update('tbl_request_bom', array('merchant_item_status' => $value[9], "merchant_appl_date_time"=> $this->mysqldatetime));
        }
    }

    // ********** UPDATE MERCHANT SAMPLE QUEUE ENDS HERE *********** /

    function getRequestDataa($enqId, $reqId)
    {
        $sql = "SELECT *,b.req_status as pi_req_status,b.mgmt_appl_remarks from tbl_request a 
                INNER JOIN tbl_request_bom b ON a.request_id = b.request_id
                where a.request_id='$reqId' ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function getBomRequestDataa($enqId, $reqId)
    {
        $sql = "SELECT * from  tbl_request where request_id='$reqId' ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    // ********** MANAGEMENT SAMPLE QUEUE STARTS HERE *********** /

    public function getManagementBomQueueDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . "  AND b.qa_req_status = 1";
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

    public function updateManagementQueuee($data, $enqId)
    {
        foreach($data as $key => $value) {
            $this->db->where('request_bom_id', $value[0]);
            $this->db->update('tbl_request_bom', array('mgmt_ovrd_status' => $value[13], "mgmt_status_upd_dt"=> $this->mysqldatetime));
        }
    }

    // ********** UPDATE MANAGEMENT SAMPLE QUEUE ENDS HERE *********** /

    // ********** MANAGEMENT SAMPLE QUEUE STARTS HERE *********** /

    public function getDraftPIRequestDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE b.request_id = " . $reqId . "  AND b.qa_req_status = 1";
        $data = $this->db->query($sql)->result_array();

        $vendor_sql = "SELECT *, vendorname as name FROM kn_master_bom_vendor";
        $vendor_data = $this->db->query($vendor_sql)->result_array();

        $pi_sql = "SELECT * FROM tbl_request_purchase_indent
                WHERE request_id = " . $reqId . " ";
        $pi_data = $this->db->query($pi_sql)->result_array();

        $withinResult = $interResult = $importsResult = [];

        $vendor_id = '';

        foreach ($data as $key => $value)
        {

            $bcm = $value['blend'].' / '.$value['content'].' / '.$value['material'];

            $withinResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $bcm,
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], '', '', '', '', '', '', '', ''
            ];

            $interResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $bcm,
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], '', '', '', '', ''
            ];

            $importsResult[$key] = [ 'add', $value['request_id'], $value['item_desc'], $bcm,
                $value['garment_size'], $value['appr_item_code'], $value['appr_item_col_code'],
                $value['size_dim'], $value['uom'], $value['plan_bom_qty'], $value['requirement_uom'], '', '', ''
            ];

        }

        $modeOfShipment = [ 'ON-LINE', 'CHEQUE' ];
        $currencyList = unserialize(ARRCURRENCYLIST);

        $output['withinStateDetails'] = $withinResult;
        $output['interStateDetails'] = $interResult;
        $output['importStateDetails'] = $importsResult;
        $output['advancepaiddetails'] = [];
        $output['vendor_data'] = $vendor_data;
        $output['fullData'] = $data;
        $output['modeOfShipment'] = $modeOfShipment;
        $output['currencyList'] = $currencyList;
        return $output;
    }

    // ********** MANAGEMENT SAMPLE QUEUE ENDS HERE *********** /
    
    // ********** UPDATE PURCHASE INDENT STARTS HERE *********** /

    public function updatePurchaseIndentt($data, $enqId, $reqId, $pi_cutoff_dt, $purchase_dept_note, $mode, $pur_req_data, $vId, $slt, $pt)
    {
        $requestData['pi_appl_req_date_time'] = $this->mysqldatetime;
        $requestData['pi_appl_cutoff_date_time'] = $pi_cutoff_dt;
        $requestData['purchase_dept_notes'] = $purchase_dept_note;
        $requestData['pi_appl_status'] = 1;
        $this->db->where('request_id', $reqId);
        $this->db->update('tbl_request_bom', $requestData);
        
        foreach($data as $key => $value) {
            $requestPayment['enquiry_id'] = $enqId;
            $requestPayment['request_id'] = $reqId;
            $requestPayment['vendor_id'] = $value[1];
            $requestPayment['proforma_no'] = $value[2];
            $requestPayment['proforma_date'] = $value[3];
            $requestPayment['proforma_value'] = $value[4];
            $requestPayment['qyoted_currency'] = $value[5];
            $requestPayment['mode_of_payment'] = $value[6];
            $requestPayment['pay_by_date'] = $value[7];
            $requestPayment['amount_payable'] = $value[8];
            $requestPayment['currency'] = $value[9];
            $requestPayment['vendor_bank_name'] = $value[10];
            $requestPayment['acc_no'] = $value[11];
            $requestPayment['ifsc'] = $value[12];
            $requestPayment['shift_code'] = $value[13];
            $this->db->insert('tbl_request_payment', $requestPayment);
        }
        
        foreach($pur_req_data as $key => $value) {
            $purchaseIndent['enquiry_id'] = $enqId;
            $purchaseIndent['mode'] = $mode;
            $purchaseIndent['vendor_id'] = $vId;
            $purchaseIndent['supply_lead_time'] = $slt;
            $purchaseIndent['payment_terms'] = $pt;
            $purchaseIndent['request_id'] = $value[0];
            $purchaseIndent['mode'] = $mode;
            $purchaseIndent['qty'] = $value[8];
            $purchaseIndent['uom'] = $value[9];
            if($mode == 'within')
            {
                $purchaseIndent['unit_rate'] = $value[10];
                $purchaseIndent['amount'] = $value[11];
                $purchaseIndent['gst'] = $value[12];
                $purchaseIndent['cgst'] = $value[13];
                $purchaseIndent['cgst_value'] = $value[14];
                $purchaseIndent['sgst'] = $value[15];
                $purchaseIndent['sgst_value'] = $value[16];
                $purchaseIndent['sub_total'] = $value[17];
            }
            else if($mode == 'inter')
            {
                $purchaseIndent['unit_rate'] = $value[10];
                $purchaseIndent['amount'] = $value[11];
                $purchaseIndent['igst'] = $value[12];
                $purchaseIndent['igst_value'] = $value[13];
                $purchaseIndent['sub_total'] = $value[14];
            }
            else if($mode == 'imports')
            {
                $purchaseIndent['currency'] = $value[10];
                $purchaseIndent['unit_rate'] = $value[11];
                $purchaseIndent['amount'] = $value[12];
                $purchaseIndent['sub_total'] = $value[13];
            }
            $this->db->insert('tbl_request_purchase_indent', $purchaseIndent);
        }

    }

    // ********** UPDATE PURCHASE INDENT ENDS HERE *********** /

    // *********************************************************************************************************** 
    // MANAGEMENT DEPARTMENT STARTS HERE 
    // **********************************************************************************************************//

    public function getManagementRequestDetailss($enqId, $reqId) {
        $sql = "SELECT a.* from tbl_request_bom as a 
                where a.enquiry_id='$enqId' and request_id='$reqId' ";
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
 public function getBrandListt()
    {
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_MASTER_BRANDS);
        $this->db->where('companyid', $userInfo['companyid']);
	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
    }
     public function getsubscribercompanydetail($sub_id)
    {
        
        // $sql = "SELECT * FROM " . KN_SUBSCRIBERENQUIRY . " as a
        //         WHERE id = " . $sub_id." ";
        // $result = $this->db->query($sql)->result_array();

        
        $sql = "SELECT * FROM " . KN_PROFORMAINVOICE . " as a
                WHERE subscriber_id = " . $sub_id." ";
        $result = $this->db->query($sql)->result_array();
        
        
        return $result;
    }
    public function updateManagementBomRequestt($id, $auth_status, $auth_type, $mgmt_remark) {
        $requestValue['auth_status'] = $auth_status;
        $requestValue['mgmt_approval'] = $auth_status;
        $requestValue['auth_by'] = $this->userid;
        $requestValue['auth_date'] = $this->mysqldatetime;
        $requestValue['auth_type'] = $auth_type;
        $requestValue['mgmt_remark'] = $mgmt_remark;

        $this->db->where('request_id', $id);
        $this->db->update('tbl_request', $requestValue);
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

        $this->db->where('request_id', $id);
        $this->db->update('tbl_request', $requestValue);
    }
    
    // *********************************************************************************************************** 
    // PURCHASE DEPARTMENT ENDS HERE 
    // **********************************************************************************************************//

    public function getPIRequestSendDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                INNER JOIN tbl_request_payment d on b.request_id=d.request_id
                INNER JOIN tbl_request_purchase_indent e on b.request_id=e.request_id
                INNER JOIN kn_master_bom_vendor f on d.vendor_id=f.id
                WHERE b.request_id = " . $reqId . "  AND b.qa_req_status = 1";
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

    // public function getManagementPIApprovalListt()
    // {
    //     $sql = "SELECT a.*, c.brandname, b.orderenqrefno, d.*, e.* FROM tbl_request as a 
    //             inner join kn_order_enquiry as b on a.enquiry_id=b.id 
    //             inner join kn_master_brands as c on b.brandId=c.id 
    //             inner join tbl_request_purchase_indent as d on a.request_id=d.request_id
    //             inner join tbl_request_bom_2 as e on a.request_id=e.request_id
    //             where a.type=4 and a.mgmt_approval=1 and a.deprt_approval=1 and a.flag=1";
    //     $data = $this->db->query($sql)->result_array();
    //     return $data;
    // }


    public function getManagementPIApprovalListt()
    {
        
        // $sql = "SELECT a.*, a.log as recent_log,f.cutoff_date,f.auth_type, c.brandname, b.isriorcode, d.contactname as auth_name, e.req_dt, e.appr_type, e.request_type, e.log as logs, e.* FROM tbl_purchase_indent as a
        //         inner join tbl_request as f on a.request_id=f.request_id
        //         inner join kn_order_enquiry as b on a.enquiry_id=b.id 
        //         inner join kn_master_brands as c on b.brandId=c.id
        //         left join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
        //         left join ".KN_USERS." as d on e.appr_by=d.id
        //         where f.type=4 and f.mgmt_approval=1 and a.pi_appl_status=1 and a.pi_list_status=0 and f.flag=1 and f.subscriberid = " . $this->db->escape($this->subscriber_id)."ORDER BY a.log DESC";
                
        // $data = $this->db->query($sql)->result_array();
         $sql = "SELECT a.*, a.log as recent_log,f.cutoff_date,f.auth_type, c.brandname, b.isriorcode, d.contactname as auth_name, e.req_dt, e.appr_type, e.request_type, e.log as logs, e.* FROM tbl_purchase_indent as a
                inner join tbl_request as f on a.request_id=f.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                left join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join ".KN_USERS." as d on e.appr_by=d.id
                where f.type=4 and f.mgmt_approval=1 and a.pi_appl_status=1 and a.pi_list_status=0  and f.subscriberid = " . $this->db->escape($this->subscriber_id)." ORDER BY e.log DESC";
                    
        $data = $this->db->query($sql)->result_array();
       
        

        return $data;

    }

     public function getPurchaseRequestDetailss_bulk($id) {
        $sql = "SELECT *, SUM(`req_bom_qty`) as calc_bom_qty FROM tbl_bom_article_2_requirement as a 
                where a.enquiry_id='$id' 
                GROUP BY a.item_desc, a.blend, a.content, a.material, a.garment_size, a.appr_item_code, 
                        a.appr_item_col_code, a.size_dim, a.uom ORDER BY a.bom_2_req_id";
        $data = $this->db->query($sql)->result_array();
        
        $sql1 = "SELECT * FROM tbl_bom_article_2_req_consld as a 
                LEFT JOIN tbl_request as b ON a.request_id=b.request_id
                WHERE a.enquiry_id='$id' ";
        $data1 = $this->db->query($sql1)->result_array();

        $sql2 = "SELECT * FROM tbl_bom_2_sourcing_details as a 
                INNER JOIN kn_master_bom_vendor as b ON a.vendor_name_address=b.id
                WHERE a.enquiry_id='$id' ";
        $data2 = $this->db->query($sql2)->result_array();

        $sql3 = "SELECT * FROM tbl_bom_article_2_requirement as a 
                where a.enquiry_id='$id' 
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
            
            if( isset($data1[$i]['bom_2_req_consld_id'])) {
                $bom_1_req_consld_id = $data1[$i]['bom_2_req_consld_id'];
                $data[$i]['bom_2_req_consld_id'] = $data1[$i]['bom_2_req_consld_id'];
            }
            else {
                $bom_1_req_consld_id = "";
                $data[$i]['bom_2_req_consld_id'] = "";
            }

            if( isset($data1[$i]['bom_2_req_consld_id'])) {
                $request_id[] = $data1[$i]['request_id'];
            }

            $data[$i]['req_type'] = $data1[$i]['req_type'];
            $data[$i]['req_date'] = $data1[$i]['req_date'];
            $data[$i]['cutoff_date'] = $data1[$i]['cutoff_date'];
            $data[$i]['merchant_note'] = $data1[$i]['merchant_note'];
            $data[$i]['req_sent_status'] = $data1[$i]['req_sent_status'];

            $data[$i]['bom_2_source_id'] = $data[$i]['sourcing_advice'] = $data[$i]['vendor_location'] = $data[$i]['vendor_name_address'] =
            $data[$i]['contact_email'] = $data[$i]['gst'] = $data[$i]['online_order_sys'] = $data[$i]['pass_expiry_date'] = '';

            for ($j=0; $j < sizeof($data3); $j++) { 
                if($data3[$j]['item_desc'] == $data[$i]['item_desc']) {
                    $vendor_name_address = $data2[$j]['vendorname'].' / '.$data2[$j]['address'];
                    $data[$i]['bom_2_source_id']  = $data2[$j]['bom_2_source_id'];    
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
    
    public function updateManagementPurchaseIndentAppll($eId, $reqId, $pi_req_status, $mgmt_appl_remarks)
    {
        $this->db->where('request_id', $reqId);
        $this->db->update('tbl_request_bom', array('req_status' => $pi_req_status, "mgmt_appl_remarks"=> $mgmt_appl_remarks));
    }
 public function getMerchantPIListt()
    {
        $sql = "SELECT a.*, e.flag as flags,h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, e.*,  e.log as logs,e.request_status_id, f.*, g.vendorname FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                inner join tbl_request_payment as f on a.purchase_indent_id=f.purchase_indent_id
                inner join kn_master_bom_vendor as g on f.vendor_id=g.id
                where h.type=4 and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and h.subscriberid = " . $this->db->escape($this->subscriber_id)." GROUP BY a.purchase_indent_id ORDER BY e.log DESC";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    // public function getManagementPIListt()
    // {
    //     $sql = "SELECT a.*, c.brandname, b.orderenqrefno, d.*, e.*, g.vendorname FROM tbl_request as a 
    //             inner join kn_order_enquiry as b on a.enquiry_id=b.id 
    //             inner join kn_master_brands as c on b.brandId=c.id 
    //             inner join tbl_request_purchase_indent as d on a.request_id=d.request_id
    //             inner join tbl_request_bom_2 as e on a.request_id=e.request_id
    //             inner join tbl_request_payment as f on a.request_id=f.request_id
    //             inner join kn_master_bom_vendor as g on f.vendor_id=g.id
    //             where a.type=3 and a.mgmt_approval=1 and a.deprt_approval=1 and a.flag=1 and e.req_status=1 ";
    //     $data = $this->db->query($sql)->result_array();
    //     return $data;
    // }

      public function getManagementPIListt()
    {
        $sql = "SELECT a.*, e.flag as flags,h.type,h.cutoff_date, c.brandname, b.isriorcode, e.*, e.log as logs, f.proforma_value, f.currency as pay_currency, f.pay_by_date, f.request_payment_id, g.vendorname FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join tbl_request_payment as f on f.type_of_mode='M' and a.purchase_indent_id=f.purchase_indent_id
                left join kn_master_bom_vendor as g on a.vendor_id=g.id
                where  h.type = 4 and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and a.bill_paid_status = 0 and e.type_of_mode='M' and h.subscriberid = " . $this->db->escape($this->subscriber_id)."  ORDER BY e.log DESC";
        $result = $this->db->query($sql)->result_array();
        
        foreach ($result as $key => $res) {
         $request_for = []; $payment_requirement = []; $vendorname = []; $inv_status = []; $log = []; 
         $payment_requirement[] = 'BOM (A2)';
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
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                inner join tbl_request as d on a.request_id=d.request_id 
                left join kn_master_bom_vendor as e on a.vendor_id=e.id 
                where d.type=3 and d.mgmt_approval=1 and d.deprt_approval=1 ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function getPaymentRequestReceiveDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                INNER JOIN tbl_request_payment d on b.request_id=d.request_id
                INNER JOIN tbl_request_purchase_indent e on b.request_id=e.request_id
                INNER JOIN kn_master_bom_vendor f on d.vendor_id=f.id
                WHERE b.request_id = " . $reqId . "  AND b.qa_req_status = 1";
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
                            $value['proforma_value'], $value['qyoted_currency'], $value['mode_of_payment'],
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

            $this->db->where('request_payment_id', $value[1]);
            $this->db->update('tbl_request_payment', $requestValue);
        }
    }

    public function getAdvancePadiListt() {
        $sql = "SELECT a.*, d.*, c.brandname, b.orderenqrefno, e.vendorname, f.pi_date, f.pi_ref_no, f.amount
                FROM tbl_request_payment as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                inner join tbl_request as d on a.request_id=d.request_id 
                inner join tbl_request_purchase_indent as f on a.request_id=f.request_id
                left join kn_master_bom_vendor as e on a.vendor_id=e.id 
                where d.type=3 and d.mgmt_approval=1 and d.deprt_approval=1 ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    public function getAdvancePaidDetailss($enqId, $reqId) {

            $results=[];
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                INNER JOIN tbl_request_payment d on b.request_id=d.request_id
                INNER JOIN tbl_request_purchase_indent e on b.request_id=e.request_id
                INNER JOIN kn_master_bom_vendor f on d.vendor_id=f.id
                WHERE b.request_id = " . $reqId . "  AND b.qa_req_status = 1";
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
                            $value['proforma_value'], $value['qyoted_currency'], $value['mode_of_payment'],
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
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                inner join tbl_request_purchase_indent as d on a.request_id=d.request_id
                inner join tbl_request_bom as e on a.request_id=e.request_id
                inner join tbl_request_payment as f on a.request_id=f.request_id
                inner join kn_master_bom_vendor as g on f.vendor_id=g.id
                where a.type=3 and a.mgmt_approval=1 and a.deprt_approval=1  and e.req_status=1";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function updateStorePiDetailss($data, $data2, $data3, $enqId)
    {
        foreach($data as $key => $value) {
            $inhouseData['request_bom_id'] = $value[0];
            $inhouseData['dc_no'] = $value[8];
            $inhouseData['dc_date'] = $value[9];
            $inhouseData['dc_qty'] = $value[10];
            $inhouseData['invoice_no'] = $value[11];
            $inhouseData['invoice_date'] = $value[12];
            $inhouseData['invoice_qty'] = $value[13];
            $inhouseData['received_qty'] = $value[14];
            $inhouseData['uom'] = $value[15];
            $inhouseData['received_date'] = $value[16];
            $this->db->insert('tbl_request_bom_inhouse', $inhouseData);

            $this->db->where('request_bom_id', $value[0]);
            $this->db->update('tbl_request_bom', array('qa_status' => $data2[$key][15], "qa_status_upt_dt"=> $this->mysqldatetime,
            'supply_closure_status'=> $data3[$key][15], 'item_rti_status'=> $data3[$key][16]));

        }
    }

    public function getNewItemDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . "  AND b.qa_req_status = 1";
        $data = $this->db->query($sql)->result_array();
        
        $inhousestatusdetails = [];

        // return $data;

        foreach ($data as $key => $value)
        {
            $bcm = $value['blend'].' / '.$value['content'].' / '.$value['material'];

            $inhousestatusdetails[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], '', '', '', '', ''
                        ];

        }

        $uomData = unserialize(ARRUNITOFMEASURE);
        
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['uomData'] = $uomData;
        return $output;
    }

    public function getSurplusStockDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_request_bom b
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . "  AND b.qa_req_status = 1";
        $data = $this->db->query($sql)->result_array();
        
        $inhousestatusdetails = [];

        // return $data;

        foreach ($data as $key => $value)
        {
            $bcm = $value['blend'].' / '.$value['content'].' / '.$value['material'];

            $inhousestatusdetails[$key] = [ $value['request_bom_id'], $value['item_desc'], $value['garment_size'], $value['appr_item_code'],
                            $value['appr_item_col_code'], $value['size_dim'], $value['uom'], '', '', '', '', ''
                        ];

        }

        $uomData = unserialize(ARRUNITOFMEASURE);
        
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['uomData'] = $uomData;
        return $output;
    }

    // *********************************************************************************************************** 
    // STORE DEPARTMENT STARTS HERE 
    // **********************************************************************************************************//


}
