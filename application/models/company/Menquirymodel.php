<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class menquirymodel extends CI_Model {
    function fnGetInfo($VarStatus='',$VarId='',$VarCompanyId='') {
        $ArrWhere                   = array(); $VarBuyerId = 0; $ArrFinal = array();
        if($VarStatus<>'') {
            $ArrWhere[]             = "e.status=".$VarStatus;
        } else {
            $ArrWhere[]             = "e.status in(0,1,2)";
        }
        if($VarId<>'') {
            $ArrWhere[] 			= "e.id=".$VarId;
        }
        if($VarCompanyId <> '') {
            $ArrWhere[]               = "e.companyid=".$VarCompanyId;
        }
        $VarWhere                   = '';
        if(@$ArrWhere[0]<>'') {
            $VarWhere               = implode(" and ",$ArrWhere) ;
        }
        // from new brand form buyername,country ,brandname included and commeted buyer table join here
        $VarSqlEnq                  = "SELECT e.id,enquirytype,orderenqrefno,subscriberid,enquid_subid,pricequotedfor,e.modeofenquiry as modeofenquiryid,e.totalcomponents AS totalcomponents,e.totalcombo As totalcombo,
e.countryid,brandId,buyerId,e.currency,e.stylenamerefno,e.styledesc,e.pcsorset,e.quotedprice,e.buyerprice,e.confirmprice,e.exporderqty,br.brandname,br.buyername,br.country,
e.reqforisrior,e.isrrefany,e.merchantid,DATE_FORMAT(e.datecreated,\"%d-%m-%Y %H:%i:%s\") AS formattedDateCreated,DATE_FORMAT(e.dateupdated,\"%d-%m-%Y %H:%i:%s\") AS formattedDateUpdated, DATE_FORMAT(e.shipmentdate,\"%d-%m-%Y\") AS shipmentdate,
IF(e.dateauthorized!='00-00-0000 00:00:00',DATE_FORMAT(e.dateauthorized,\"%d-%m-%Y %h:%i %p\"),'') AS formattedDateAuthorized,IF(e.reqdatetime!='00-00-0000 00:00:00',DATE_FORMAT(e.reqdatetime,\"%d-%m-%Y %h:%i %p\"),'') AS reqdatetime,DATE_FORMAT(e.enquirydate,\"%d-%m-%Y\") as enquirydate,e.status,e.orderstatus,e.comments,e.merchantnote,mo.modeofenquiry, mgmusr.contactname AS authorizedby,merusr.contactname AS merchantname
FROM ".KN_ORDER_ENQUIRY." AS e INNER JOIN ".KN_MASTER_BRANDS." AS br ON br.id = e.brandId 
/* LEFT JOIN ".KN_MASTER_BUYER." AS byr ON byr.id = e.buyerId */
INNER JOIN ".KN_MASTER_MODEOFENQUIRY." as mo ON mo.id = e.modeofenquiry
LEFT JOIN ".KN_USERS." mgmusr ON mgmusr.id = e.mgmtid
LEFT JOIN ".KN_USERS." merusr ON merusr.id = e.merchantid 
WHERE ".$VarWhere;
        
        $ResSqlEnq					= $this->db->query($VarSqlEnq)->result();
        return $ResSqlEnq;
    }


    function saveEnquiryInfo($ArrUpdateData,$VarResendId='') {
        $Vardt = $ArrUpdateData['dateupdated'];
        $draftstatus= $ArrUpdateData['draft_status'];
        if($VarResendId > 0) {
           $enquiry_data= $this->db->select('*')->get_where(KN_ORDER_ENQUIRY, array('id' => $VarResendId))->row();
           $noofcomponent=$enquiry_data->totalcomponents; // $noofcomponent data already exsit in enquiry
           $noofcombo=$enquiry_data->totalcombo; // $noofcombo data already exsit in enquiry
           
           $noofcomponent_update=$ArrUpdateData['totalcomponents']; // coming to update $noofcomponent
           $noofcombo_update=$ArrUpdateData['totalcombo']; // coming to update $noofcombo
           
           $totorderqty=$enquiry_data->exporderqty; // $totorderqty data already exsit in enquiry
           $totorderqty_update=$ArrUpdateData['exporderqty']; // coming to update $totorderqty
           
           $pcs_set=$enquiry_data->pcsorset; // $pcs_set data already exsit in enquiry
           $pcs_set_update=$ArrUpdateData['pcsorset']; // coming to update $pcs_set
           
           if(($noofcomponent!=$noofcomponent_update && $noofcomponent>$noofcomponent_update) || ($noofcombo!=$noofcombo_update && $noofcombo>$noofcombo_update) ){
              $this->load->model('ComponentsModel');
              $this->ComponentsModel->deletecomponentdetail($VarResendId);
           // echo '1';
           }
           
           if(($totorderqty!=$totorderqty_update) || ($noofcomponent!=$noofcomponent_update) || ($pcs_set!=$pcs_set_update)){
               $this->db->update('tbl_other_expenses',array('order_qty'=>$totorderqty_update,'pcs_set'=>$pcs_set_update,'no_component'=>$pcs_set_update),['enquiry_id' => $VarResendId]);
           }

            $this->db->where('id',$VarResendId);
            if($this->db->update(KN_ORDER_ENQUIRY,$ArrUpdateData)) {
                $ArrResult['statusCode']					= 200;
                $ArrResult['errcode']					    = 1;
                $ArrResult['dupdated']						= date('d-m-Y H:i:s',strtotime($Vardt));
                $ArrResult['msg']							= '';
                $ArrResult['id']							= $VarResendId;
                $ArrResult['draftstatus']					= $draftstatus;
                $ArrResult['eid']							= urlencode(base64_encode($VarResendId));
                return $ArrResult;
            }
        }
        else {
             
              $subid=$ArrUpdateData["subscriberid"];
              $enqcount_eachsubscriber = $this->commonmodel->getOrderEnquiryCount_subscriber($subid);
              $count = $enqcount_eachsubscriber+1;
              $ArrUpdateData["enquid_subid"] = $count; 
            $this->db->insert(KN_ORDER_ENQUIRY,$ArrUpdateData);
            $VarId = $this->db->insert_id();
    
            if($ArrUpdateData["reqforisrior"] == "2" || $ArrUpdateData["reqforisrior"] == 2) {
                $refno = $ArrUpdateData["isrrefany"];
                $getquery = "SELECT * from kn_order_enquiry WHERE reqforisrior='1' AND orderenqrefno = '$refno'";
                $getdata  = $this->db->query($getquery)->result_array();
                if(sizeof($getdata) > 0) {
                    $enquiry_id = $getdata[0]['id'];
                    $iorStatus = $this->insertIorDetails($enquiry_id, $VarId);
                }
            }

            if($VarId) {
                $ArrResult['errcode']					    = 1;
                $ArrResult['dupdated']						= date('d-m-Y / H:i:s',strtotime($ArrUpdateData['dateupdated']));
                $ArrResult['dcreated']						= date('d-m-Y / H:i:s',strtotime($ArrUpdateData['datecreated']));
                $ArrResult['msg']							= '';
                $ArrResult['id']							= $VarId;
                $ArrResult['draftstatus']					= $draftstatus;
                $ArrResult['eid']							= urlencode(base64_encode($VarId));
                return $ArrResult;
            }
        }
    }

    function insertIorDetails($id, $new_id) {
        
        // *** Component *** //
        $components = "select * from tbl_components where enquiry_id='$id'";
        $getcomponent  = $this->db->query($components)->result_array();
        if(sizeof($getcomponent) > 0) {
            $this->insertAllDataFunction($getcomponent, $new_id, 'tbl_components', "");
        }

        // *** average esatablishment cost *** //
        $avg_est_cost = "select * from tbl_avg_embellishment_cost where enquiry_id='$id'";
        $get_avg_est_cost  = $this->db->query($avg_est_cost)->result_array();
        if(sizeof($get_avg_est_cost) > 0) {
            $this->insertAllDataFunction($get_avg_est_cost, $new_id, 'tbl_avg_embellishment_cost', $getcomponent);
        }

        // *** BOM cost *** //
        $bom_cost = "select * from tbl_bom_cost where enquiry_id='$id'";
        $get_bom_cost  = $this->db->query($bom_cost)->result_array();
        if(sizeof($get_bom_cost) > 0) {
            $this->insertAllDataFunction($get_bom_cost, $new_id, 'tbl_bom_cost', $getcomponent);
        }
        
        // *** CMT CIP GARNMENT *** //
        $sql = "select * from tbl_cmt_cip_garment where enquiry_id='$id'";
        $getData  = $this->db->query($sql)->result_array();
        if(sizeof($getData) > 0) {
            $this->insertAllDataFunction($getData, $new_id, 'tbl_cmt_cip_garment', $getcomponent);
        }
        
        // *** OTHER EXPENSES *** //
        $sql = "select * from tbl_other_expenses where enquiry_id='$id'";
        $getData  = $this->db->query($sql)->result_array();
        if(sizeof($getData) > 0) {
            $this->insertAllDataFunction($getData, $new_id, 'tbl_other_expenses', $getcomponent);
        }
        
        // *** fabric cost *** //
        $fabric_cost = "select * from tbl_fabric_cost_garment where enquiry_id='$id'";
        $get_fabric_cost = $this->db->query($fabric_cost)->result_array();
        if(sizeof($get_fabric_cost) > 0) {
            $this->insertAllDataFunction($get_fabric_cost, $new_id, 'tbl_fabric_cost_garment', $getcomponent);
        }

        // *** ACTUAL COST *** //
        $sql = "select * from tbl_actual_cost_garment where enquiry_id='$id'";
        $getData  = $this->db->query($sql)->result_array();
        if(sizeof($getData) > 0) {
            $this->insertAllDataFunction($getData, $new_id, 'tbl_actual_cost_garment', $getcomponent);
        }

        // *** ISR PROFUT PERCENTAGE *** // No
        $sql = "select * from tbl_isr_profit_percentage where enquiry_id='$id'";
        $getData  = $this->db->query($sql)->result_array();
        if(sizeof($getData) > 0) {
            $this->insertAllDataFunction($getData, $new_id, 'tbl_isr_profit_percentage', $getcomponent);
        }

        // *** GARMENT PIECE WEIGHT *** //
        $sql = "select * from tbl_garment_piece_weight where enquiry_id='$id'";
        $getData  = $this->db->query($sql)->result_array();
        if(sizeof($getData) > 0) {
            $this->insertAllPieceWeight($getData, $new_id, 'tbl_garment_piece_weight', $getcomponent);
        }

        // *** YARN COST *** //
        $sql = "select * from tbl_yarn_cost where enquiry_id='$id'";
        $getData  = $this->db->query($sql)->result_array();
        if(sizeof($getData) > 0) {
            $this->insertAllDataFunction($getData, $new_id, 'tbl_yarn_cost', $getcomponent);
        }
        
        // *** KNITTING COST *** //
        $sql = "select * from tbl_knitting_cost where enquiry_id='$id'";
        $getData  = $this->db->query($sql)->result_array();
        if(sizeof($getData) > 0) {
            $this->insertAllDataFunction($getData, $new_id, 'tbl_knitting_cost', $getcomponent);
        }
        
        // *** AVG DYEING PROCESS COST *** //
        $sql = "select * from tbl_avg_dyeing_processing_cost where enquiry_id='$id'";
        $getData  = $this->db->query($sql)->result_array();
        if(sizeof($getData) > 0) {
            $this->insertAllDataFunction($getData, $new_id, 'tbl_avg_dyeing_processing_cost', $getcomponent);
        }
        
        // *** COLOR COMBO COST *** //
        $sql = "select * from tbl_color_combo where enquiry_id='$id'";
        $getData  = $this->db->query($sql)->result_array();
        if(sizeof($getData) > 0) {
            $this->insertAllDataFunction($getData, $new_id, 'tbl_color_combo', $getcomponent);
        }
        
        // *** PC SIZE CHART *** //
        $sql = "select * from tbl_pc_size_chart where enquiry_id='$id'";
        $getData  = $this->db->query($sql)->result_array();
        if(sizeof($getData) > 0) {
            $this->insertAllDataFunction($getData, $new_id, 'tbl_pc_size_chart', $getcomponent);
        } 
        
        // *** AVERAGE DYEING PROCESS COST *** //
        $sql = "select * from tbl_avg_dyeing_processing_cost where enquiry_id='$id'";
        $getData  = $this->db->query($sql)->result_array();
        if(sizeof($getData) > 0) {
            $this->insertAllDataFunction($getData, $new_id, 'tbl_avg_dyeing_processing_cost', $getcomponent);
        }
    }

    function insertAllDataFunction($dataValue, $new_id, $table_name, $getcomponent) 
    {
        if($table_name == "tbl_isr_profit_percentage" || $table_name == "tbl_components" || $table_name == "tbl_pc_size_chart" ) {
            for($i=0; $i < sizeof($dataValue); $i++) 
            {
                unset($dataValue[$i]["id"]);
                $dataValue[$i]["enquiry_id"] = $new_id;
                $this->db->insert($table_name, $dataValue[$i]);
            }
        }
        else {
            $newcomponents = "select * from tbl_components where enquiry_id='$new_id'";
            $getnewcomponents  = $this->db->query($newcomponents)->result_array();
            
            for($i=0; $i < sizeof($dataValue); $i++) 
            {
                $key = array_search($dataValue[$i]["component_id"], array_column($getcomponent, 'id'));
                unset($dataValue[$i]["id"]);
                $dataValue[$i]["enquiry_id"] = $new_id;
                $dataValue[$i]["component_id"] = $getnewcomponents[$key]["id"];
                $this->db->insert($table_name, $dataValue[$i]);
            }
        }
        // foreach($dataValue as $key => $value) {
        //     unset($value["id"]);
        //     $value["enquiry_id"] = $new_id;
        //     $this->db->insert($table_name, $value);
        // }
    }
    
    function insertAllPieceWeight($dataValue, $new_id, $table_name, $getcomponent) 
    {
        $newcomponents = "select * from tbl_components where enquiry_id='$new_id'";
        $getnewcomponents  = $this->db->query($newcomponents)->result_array();

        foreach($dataValue as $key => $value) 
        {
            $key = array_search($value["component_id"], array_column($getcomponent, 'id'));
            $value_id = $value["id"];
            unset($value["id"]);
            $value["enquiry_id"] = $new_id;
            $value["component_id"] = $getnewcomponents[$key]["id"];
            $this->db->insert($table_name, $value);
            $insertId = $this->db->insert_id();

            $sql = "select * from tbl_garment_piece_weight_mapping where garment_piece_weight_id='$value_id'";
            $getData  = $this->db->query($sql)->result_array();
            if(sizeof($getData) > 0) {
                foreach($getData as $key1 => $value1) {
                    unset($value1["id"]);
                    $value1["garment_piece_weight_id"] = $insertId;
                    $this->db->insert("tbl_garment_piece_weight_mapping", $value1);
                }
            }
            
        }
    }

    function fnChangeStatus($checkboxids,$optvalue) {
        $Arrids =  json_decode($checkboxids,true);
        $this->db->where_in('id',$Arrids);
        return $this->db->update(KN_ORDER_ENQUIRY,array('status' => $optvalue));
    }

    function fnAuthorize($VarCompanyId='',$VarEnquiryId='',$VarApprStatus='',$VarComments='',$VarIsrOrIor) {
        $VarCode = 0;
        $VarMysqlDt = date('Y-m-d H:i:s');
        $ArrEnquiryStatus = unserialize(ORDERENQUIRYSTATUS);
        $userId = fnGetUserLoggedInfo();

        //////
        $ArrObjsubscriber_id = $this->commonmodel->getsubscriberid($userId);
        $subscriber_id=$ArrObjsubscriber_id->subscriber_id;
        $Arrprefixname = $this->commonmodel->getcompanyprefix($ArrObjsubscriber_id->subscriber_id);
        $companyprefixname=$Arrprefixname->companyprefix;
        $wipcount = $this->commonmodel->getOrderEnquiryCount($subscriber_id);
        $count = $wipcount+1;
           //////
        $ArrIsrIor   = unserialize(ARRISRIOR);
         //$VarCode     = $ArrIsrIor[$VarIsrOrIor] . '/' . date('my').'/BSG-' . $VarEnquiryId;
        $VarCode     = $ArrIsrIor[$VarIsrOrIor] . '/' . date('my').'/'.$companyprefixname.'-' . $count;

        //($VarCode);
        //die;
        
        if ($VarApprStatus == 2) {
            $ArrData = array('orderstatus' => $VarApprStatus,'mgmtid'=>$userId,'isriorcode' => $VarCode, 'comments' => $VarComments,'dateupdated'=>$VarMysqlDt
            ,'dateauthorized'=>$VarMysqlDt,'order_status_value'=>$ArrEnquiryStatus[$VarApprStatus]);
            $this->db->where('id', $VarEnquiryId);
            $this->db->update(KN_ORDER_ENQUIRY, $ArrData);

            $this->fnInsertLogData(KN_ORDER_ENQUIRY,KN_ORDER_ENQUIRY_LOG,$VarEnquiryId,$VarApprStatus,$VarComments,
                $VarCode,$userId,$VarMysqlDt);
            $this->db->insert(DELIVERY_SCHEDULE_WIP_LIST,array('companyid'=>$VarCompanyId,'referenceid'=>$VarEnquiryId));
            /*$this->db->insert(ORDERENTRY_ALLTBL_REAMRKS,array('referenceid'=>$VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_FIRSTTBL,array('referenceid'=>$VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_SIZECHART,array('referenceid'=>$VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_COMMONDATA,array('referenceid'=>$VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_SECONDTBL,array('referenceid'=>$VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_THIRDTBL,array('referenceid'=>$VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_NEW_FOURTH_TBL,array('referenceid'=>$VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_DELIVERYSCHEDULESIXTHTBL,array('referenceid'=>$VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_FIFTHTBL,array('referenceid'=>$VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_CUTTINGRATIO_FIFTHTBL,array('referenceid'=>$VarEnquiryId,'companyid'=>$VarCompanyId));

            $this->db->insert(ORDERENTRY_FABRIC_DETAILS_KNIT_LYCRA, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_GARMENTPARTSTBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDER_ENTRY_FABRIC_KNIT_LYCRA_FORMULA_RES, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId));

            $this->db->insert(ORDERENTRY_B4SEVENTHKNITTBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_SEVENTHKNITTBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_WOVENEIGHTH_TBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_DYEINGNINTH_TBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_EMBLISHMENTTENTH_TBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_BOMELEVENTH_TBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId,'articleid'=>'1'));
            $this->db->insert(ORDERENTRY_BOMELEVENTH_TBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId,'articleid'=>'2'));
            $this->db->insert(ORDERENTRY_BOMCONSTWELFTH_TBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId,'articleid'=>'1'));
            $this->db->insert(ORDERENTRY_BOMCONSTWELFTH_TBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId,'articleid'=>'2'));
            $this->db->insert(ORDER_ENTRY_BOM_SOURCING_TBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId,'arttype'=>'1'));
            $this->db->insert(ORDER_ENTRY_BOM_SAMPLING_APPROVAL_DETAILS, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId,'arttype'=>'2'));
            $this->db->insert(ORDERENTRY_COMGARMENTPROCESSFLOWFOURTEENTH_TBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_GARMENTSAMPLINGFIFTEEN_TBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_LABTESTINGSIXTEENTH_TBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_PACKINGDETAILSSEVENTEEN_TBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_LOTINSPECTIONTWENTYONE_TBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId));
            $this->db->insert(ORDERENTRY_DOCANDLOGISTICSTWENTYTWO_TBL, array('referenceid' => $VarEnquiryId,'companyid'=>$VarCompanyId));*/
        }
        elseif ($VarApprStatus == 3) {
            // mgmtid updation included by myself
            $this->db->where('id', $VarEnquiryId);
            $ArrData = array('orderstatus' => $VarApprStatus,'mgmtid'=>$userId, 'isriorcode' => $VarCode, 'comments' => $VarComments,'dateupdated'=>$VarMysqlDt,'dateauthorized'=>$VarMysqlDt);
            $this->db->update(KN_ORDER_ENQUIRY, $ArrData);

            $this->fnInsertLogData(KN_ORDER_ENQUIRY,KN_ORDER_ENQUIRY_LOG,$VarEnquiryId,$VarApprStatus,$VarComments,
                $VarCode,$userId,$VarMysqlDt);
        }
        return $VarCode;
    }

    function fnInsertLogData($VarFromTable='',$VarToTable='',$VarId='',$VarApprovalStatus='',$VarMgmtComments='',$VarCode,$userId,$VarMysqlDt='') {
        $VarQry = $this->db->get_where($VarFromTable,array('id'=>$VarId));
        $Res = $VarQry->row();
        if(!empty($Res)) {
            $ArrEnquiryStatus = unserialize(ORDERENQUIRYSTATUS);
            $ArrInsertData['enquiryId'] = $VarId;
            $ArrInsertData['orderenqrefno'] = $Res->orderenqrefno;
            $ArrInsertData['enquirytype'] = $Res->enquirytype;
            $ArrInsertData['modeofenquiry'] = $Res->modeofenquiry;
            $ArrInsertData['brandId'] = $Res->brandId;
            $ArrInsertData['buyerId'] = $Res->buyerId;
            $ArrInsertData['countryid'] = $Res->countryid;
            $ArrInsertData['styledesc'] = $Res->styledesc;
            $ArrInsertData['stylenamerefno'] = $Res->stylenamerefno;
            $ArrInsertData['enquirydate'] = $Res->enquirydate;
            $ArrInsertData['quotedprice'] = $Res->quotedprice;
            $ArrInsertData['buyerprice'] = $Res->buyerprice;
            $ArrInsertData['confirmprice'] = $Res->confirmprice;
            $ArrInsertData['currency'] = $Res->currency;
            $ArrInsertData['exporderqty'] = $Res->exporderqty;
            $ArrInsertData['pcsorset'] = $Res->pcsorset;
            $ArrInsertData['merchantid'] = $Res->merchantid;
            $ArrInsertData['reqforisrior'] = $Res->reqforisrior;
            $ArrInsertData['isrrefany'] = $Res->isrrefany;
            $ArrInsertData['companyid'] = $Res->companyid;
            $ArrInsertData['merchantnote'] = $Res->merchantnote;
            $ArrInsertData['pricequotedfor'] = $Res->pricequotedfor;
            $ArrInsertData['order_status_value'] = $ArrEnquiryStatus[$VarApprovalStatus];
            $ArrInsertData['comments'] = $VarMgmtComments;
            $ArrInsertData['isriorcode'] = $VarCode;
            $ArrInsertData['mgmtid'] = $userId;
            $ArrInsertData['datecreated'] = $Res->datecreated;
            $ArrInsertData['dateupdated'] = $VarMysqlDt;
            $this->db->insert($VarToTable,$ArrInsertData);
            return $this->db->insert_id();
        }


    }

    function fnEnqLogList($VarEnquiryId='',$VarCompanyId='') {

        $ArrWhere = array();
        if($VarEnquiryId<>'') {

            $ArrWhere[]             = "l.enquiryid  = ".$VarEnquiryId;

        }
        if($VarCompanyId<>'') {
            $ArrWhere[]             = "l.companyid  = ".$VarCompanyId;
        }
        $VarWhere                   = '';
        if(@$ArrWhere[0]<>'') {
            $VarWhere               = implode(" and ",$ArrWhere) ;
        }
        $VarEnqLogSql = "SELECT l.id,l.enquiryid,l.orderstatus,l.datecreated,l.comments,l.enquirydate FROM ".KN_ORDER_ENQUIRY_LOG." AS l WHERE $VarWhere";
        return $this->db->query($VarEnqLogSql)->result();
    }

    function fnCountEnquiryLog($VarEnquiryId='',$VarCompanyId='') {
        $ArrWhere = array();
        if($VarEnquiryId<>'') {

            $ArrWhere[]             = "l.enquiryid  = ".$VarEnquiryId;

        }
        if($VarCompanyId<>'') {
            $ArrWhere[]             = "l.companyid  = ".$VarCompanyId;
        }
        $VarWhere                   = '';
        if(@$ArrWhere[0]<>'') {
            $VarWhere               = implode(" and ",$ArrWhere) ;
        }
        $VarEnqLogSql = "SELECT count(1) as trec FROM ".KN_ORDER_ENQUIRY_LOG." AS l WHERE $VarWhere";
        $ObjRes = $this->db->query($VarEnqLogSql)->row();
        return $ObjRes->trec;
    }

    function fnGetEnqLog($VarEnqLogId='') {
        $ArrWhere = array();
        if ($VarEnqLogId <> '') {

            $ArrWhere[] = "l.id  = " . $VarEnqLogId;

        }
        $VarWhere = '';
        if (count($ArrWhere) >= 1) {
            $VarWhere = " WHERE " . implode(" and ", $ArrWhere);
        }
        $VarLogDetail = "SELECT l.id,l.enquirydate,l.enquiryid,br.brandname,u.contactname,l.enquirytype,l.countryid,l.currency,l.stylenamerefno,
l.styledesc,l.pcsorset,l.quotedprice,l.buyerprice,l.confirmprice,l.exporderqty,l.reqforisrior,l.isrrefany,l.orderstatus,l.comments,l.merchantnote,l.daterequested,
mo.modeofenquiry,br.buyerid,l.datecreated,l.dateupdated,l.dateauthorized FROM " . KN_ORDER_ENQUIRY_LOG . " AS l INNER JOIN 
" . KN_MASTER_BRANDS . " AS br ON br.id = l.brandId INNER JOIN " .KN_USERS. " as u ON u.id = l.merchantid INNER JOIN " . KN_MASTER_MODEOFENQUIRY . "
 as mo ON mo.id = l.modeofenquiry " . $VarWhere;
        $ResSqlEnq = $this->db->query($VarLogDetail)->result();
        if(empty($ResSqlEnq)) {
            return false;
        }
        else {
            $VarBuyerId        = @$ResSqlEnq[0]->buyerid;
            $VarBuyer          = $this->getBuyer(array(), $VarBuyerId);
            $ArrFinal['et']    = @$ResSqlEnq[0];
            $ArrFinal['buyer'] = @$VarBuyer[0];

            return $ArrFinal;

        }

    }
}