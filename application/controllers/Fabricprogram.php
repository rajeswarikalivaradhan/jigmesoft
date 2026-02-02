<?php if ( ! defined('BASEPATH')) { exit('No direct script access allowed'); }

class Fabricprogram extends CI_Controller {
    public $companyid;
    public $userid;
    public $mysqldatetime;
    public $saveAccess;
    public function __construct() {
        parent::__construct();
        //$this->load->model("fabricprogrammodel");
        $this->load->model(CNFCOMPANY . "orderentrymodel");
        $this->load->helper('xssclean');
        $this->load->helper('common');
        $this->load->model("commonmodel");
        fnIfCheckUserLoggedIn();
        $VarUserInfo = fnGetUserLoggedInfo(1);
        $this->companyid = $VarUserInfo['companyid'];
        $this->userid = $VarUserInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->saveAccess = true;
        if ($VarUserInfo['usertype'] != '3' && $VarUserInfo['usertype'] != '15') {
            $this->saveAccess = false;
        }
    }

    /*
     * $VarPageId is the function / method name
    ** */
    public function home() {
        $ArrFabricPages = unserialize(ARR_FABRIC_PROGRAM_PAGES);
        $VarPageId = $this->router->method;
        $ArrTableId = $ArrFabricPages[$VarPageId];
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        if ($VarEnquiryId >= 1) {
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $ArrData['VarHashEnquiryId'] = $VarHashEnquiryId;
            $ArrData['VarPageId'] = $VarPageId;
            $ArrData['VarTable1Id'] = $ArrTableId[0];
            $ArrData['VarTable1AId'] = $ArrTableId[1];
            $this->load->view('fabric_program/fabricProgram', $ArrData);
        }
    }

    public function saveOneJxl() {
        $VarFrom = $this->input->post('rFrom');
        $VarReferenceId = $this->input->post('enqId');
        $jsonData = $this->input->post('d');
        $VarTableId = $this->input->post('tid');
        if($VarFrom == 1) {
            $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $this->companyid,'tableid'=>$VarTableId])
                ->from(FABRIC_PROGRAM_ALL_JXL)->count_all_results();
            if ($VarCountAllRes >= 1) {
                $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData, 'dateupdated' => $this->mysqldatetime,
                    'updatedby' => $this->userid), array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid, 'tableid'=>$VarTableId));

                $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => '', 'dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid, 'tableid'=>'feederLycra'));
            } else {
                $this->db->insert(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData, 'dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid,
                    'referenceid' => $VarReferenceId, 'companyid' => $this->companyid,'tableid'=>$VarTableId));
            }
            /*
             * Make 1A as empty so that if any changes made in garment parts will reflect when entering in this table (entering yarn blend Content)
             * */
            $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => '', 'dateupdated' => $this->mysqldatetime,
                'updatedby' => $this->userid), array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid, 'tableid'=>'yarnBC'));
            echo json_encode(array('errCode'=>1));
        }

    }

    public function saveOneAJxl() {
        $VarFrom = $this->input->post('rFrom');
        $VarReferenceId = $this->input->post('enqId');
        $jsonData = $this->input->post('d');
        if($VarFrom == 1) {
            $VarTableId = $this->input->post('tid');
            $VarFeederLycraJxl = $this->input->post('feederLycraJxl');
            $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $this->companyid,'tableid'=>$VarTableId])
                ->from(FABRIC_PROGRAM_ALL_JXL)->count_all_results();
            if ($VarCountAllRes >= 1) {
                $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData, 'dateupdated' => $this->mysqldatetime,
                    'updatedby' => $this->userid), array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid, 'tableid'=>$VarTableId));
            } else {
                $this->db->insert(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData, 'dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid,
                    'referenceid' => $VarReferenceId, 'companyid' => $this->companyid,'tableid'=>$VarTableId));
            }

            /*$ArrFabricProgramPages = array("home" => array("1", "1A"), "two" => "2", "three" => "3", "three_a" => "3A",
    "four" => "3in4p", "five" => "5", "six" => "6", "seven" => array("7", "7A"), "eight" => "8", "fabRequirement"=>"fR", "yarnDyeingStrips" => "yds",
    "singleDyeingBath" => "sdb", "yarnDyeingJacquard" => "ydj",
    "eleven" => "11", "thirteen" => array("13", "13A"), "fourteen" => "14", "labTesting" => "15", "extLabTesting" => "16");*/

            /*$ArrFabricProgramPages = array("home" => array("parts", "yarnBC"), "two" => "feederLycra", "three" => "fF", "dyeingColDetails" => "dyeD",
                "partsXsQty" => "xsQty", "pcsWeight" => "pW", "fabCcProcessLoss" => "fCcPL", "fabConCalc" => array("fabCc1", "cummuConCalc"), "diaOrDim" => "dDimension",
                "fabRequirement"=>"fR", "yarnDyeingStrips" => "yds", "singleDyeingBath" => "sdb", "yarnDyeingJacquard" => "ydj",
                "eleven" => "11", "thirteen" => array("13", "13A"), "fourteen" => "14", "labTesting" => "15", "extLabTesting" => "16");*/
            $ArrTables = array('feederLycra', 'fF', 'dyeD','fCcPL','cummuConCalc');
            $this->db->where('referenceid',$VarReferenceId);
            $this->db->where('companyid',$this->companyid);

            $this->db->where_in('tableid',$ArrTables);
            $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid));

            /*$this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid,'tableid'=>'feederLycra'));
            $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid,'tableid'=>'fF'));*/
            /*
             * "dyeD" and "pW" Table No Need to make empty Because only the FT Data is saved to DB others come from base table
             * */
            $this->db->update(FP_EXCESS_QTY, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid));

            /*$this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid,'tableid'=>'fCcPL'));

            $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid,'tableid'=>'cummuConCalc'));*/

            $this->db->update(PLAN_FAB_WGT_SUBTOTAL, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid));
            $this->db->update(YARN_DYE_STRIPS, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid));
            $this->db->update(SINGLE_DYE_BATH, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid));
            $this->db->update(YARN_DYEING_JACQUARD, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid));

            $this->db->update(FP_YARN_PROGRAM, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid));

            $this->db->update(FP_CONS_KNITTING_PROGRAM, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid));

            /*$this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid,'tableid'=>'14'));*/
            /*$VarSql = "DELETE FROM FABRIC_PROGRAM_ALL_JXL WHERE referenceid = '".$VarReferenceId."' AND companyid = '".$this->companyid."' AND tableid != (1 AND '1A') ";
            $this->db->query($VarSql);*/
            echo json_encode(array('errCode'=>1));
        }
    }

    /* fabric 2 and 3 JXl is common on the top page "four"
        This is common function for all fabric program pages
    */
    public function ajaxData() {
        $VarFrom = $this->input->post('rFrom');
        $VarEnquiryId = $this->input->post('enqId');
        $VarPageId = $this->input->post('pid');
        if ($VarFrom == 1) {
            $ArrFabricPages = unserialize(ARR_FABRIC_PROGRAM_PAGES);
            $ArrTableId = $ArrFabricPages[$VarPageId];
            if($VarPageId == 'home') {
                $ArrYarnBlend = [];
                $ArrYarnContent = [];
                $ArrYarnCount = [];
                $ArrKnitFabricName = [];
                $ArrYarnSplReq = [];
                $ArrFromFirstTbl = '';
                $jsonFromFirstTblRes = $this->orderentrymodel->getFirstTable($VarEnquiryId, $this->companyid);
                if (!empty($jsonFromFirstTblRes->jsondatagrid)) {
                    $ArrFromFirstTbl = json_decode($jsonFromFirstTblRes->jsondatagrid);
                }
                $ArrYarnMisc = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_YARN_MISC, 'misc_name,misc_type', array('status' => "1", 'companyid' => $this->companyid));
                if (!empty($ArrYarnMisc)) {
                    foreach ($ArrYarnMisc as $item) {
                        if ($item['misc_type'] == 1) {
                            $ArrYarnBlend[] = $item['misc_name'];
                        }
                        if ($item['misc_type'] == 2) {
                            $ArrYarnContent[] = $item['misc_name'];
                        }
                        if ($item['misc_type'] == 3) {
                            $ArrYarnCount[] = $item['misc_name'];
                        }
                    }
                }
                $ArrFabricName = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_MISC, 'misc_name', array('status' => 1, 'companyid' => $this->companyid,
                    'misc_type' => 3));
                if (!empty($ArrFabricName)) {
                    foreach ($ArrFabricName as $item) {
                        $ArrKnitFabricName[] = $item['misc_name'];
                    }
                }
                $ArrYarnSplReqRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_YARN_SPL_REQ, 'yarnsplreq', array('status' => 1, 'companyid' => $this->companyid));
                if (!empty($ArrYarnSplReqRes)) {
                    foreach ($ArrYarnSplReqRes as $item) {
                        $ArrYarnSplReq[] = $item['yarnsplreq'];
                    }
                }
                $ArrDyeingType = unserialize(ARRDYEINGTYPE);
                $jsonGarmentPartsJxl = '';
                $this->db->select('jsondatagrid');
                $VarQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,
                'tableid'=>$ArrTableId[0]));
                $ArrResult = $VarQry->row();
                if(!empty($ArrResult->jsondatagrid)) {
                    $jsonGarmentPartsJxl = $ArrResult->jsondatagrid;
                }
                $jsonFabricOneAJxl = '';
                $this->db->select('jsondatagrid');
                $VarQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,
                    'tableid'=>$ArrTableId[1]));
                $ArrResult = $VarQry->row();
                if(!empty($ArrResult)) {
                    $jsonFabricOneAJxl = $ArrResult->jsondatagrid;
                }
                $ArrGarmentParts = [];
                $ArrGarmentPartsRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_GARMENT_PART_DESC, 'gpdname', array('status' => "1",
                    'companyid' => $this->companyid));
                if (!empty($ArrGarmentPartsRes)) {
                    foreach ($ArrGarmentPartsRes as $item) {
                        $ArrGarmentParts[] = $item['gpdname'];
                    }
                }
                $ArrData['ArrGarmentParts']=$ArrGarmentParts;
                $ArrData['savedFabricOneJxl']=$jsonGarmentPartsJxl;
                $ArrData['savedFabricOneAJxl'] = $jsonFabricOneAJxl;
                $ArrData['ArrFromFirstTbl']=$ArrFromFirstTbl;
                $ArrData['ArrYarnBlend']=$ArrYarnBlend;
                $ArrData['ArrYarnContent']=$ArrYarnContent;
                $ArrData['ArrYarnCount']=$ArrYarnCount;
                $ArrData['ArrKnitFabricName']=$ArrKnitFabricName;
                $ArrData['ArrDyeingType']=$ArrDyeingType;
                $ArrData['ArrYarnSplReq']=$ArrYarnSplReq;
            }
            if($VarPageId == "two") {
                $jsonFabricTwoJxl = '';
                $this->db->select('jsondatagrid');

                $VarCurrentQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL,
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid, 'tableid'=>$ArrTableId));

                $ArrCurrentResult = $VarCurrentQry->row();
                if(!empty($ArrCurrentResult->jsondatagrid)) {
                    $jsonFabricTwoJxl = $ArrCurrentResult->jsondatagrid;
                }
                //echo '<pre>'; print_r($jsonFabricTwoJxl); die('die');
                $this->db->select('jsondatagrid');
                $VarQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,
                    'tableid'=>'yarnBC'));
                $ArrResult = $VarQry->row();
                $ArrColor = $ArrYarnBlend = $ArrYarnContent = $ArrYarnCount = $ArrYarnSplReq = $ArrFilteredFab1A = [];
                $ArrFinal = [];
                if(!empty($ArrResult->jsondatagrid)) {
                $jsonFabricOneAJxl = $ArrResult->jsondatagrid;
                    $ArrFabricOneAJxl = json_decode($jsonFabricOneAJxl, true);
                    /*
                     * Splitting slash if there is in yarn blend , content , count and in spl request.
                     * 14Mar2021*/
                    /*
                     * $SplitFabricOneAJxl = $ArrBlend = $ArrContent = $ArrCount = $ArrSplReq = [];
                     * foreach($ArrFabricOneAJxl as $split) {
                        $ArrBlend = explode('/',$split[4]);
                        $ArrContent = explode('/',$split[5]);
                        $ArrCount = explode('/',$split[8]);
                        $ArrSplReq = explode('/',$split[10]);
                        foreach($ArrBlend as $cmnKey => $blend) {
                            $SplitFabricOneAJxl[] = array($split[0],$split[1],$split[2],$split[3],$blend,$ArrContent[$cmnKey],$split[6],$split[7],
                                $ArrCount[$cmnKey],$split[9],$ArrSplReq[$cmnKey]);
                        }
                    }*/

                    foreach($ArrFabricOneAJxl as $item) {
                        $ArrColor[$item[0].'##'.$item[1].'##'.$item[3]][] = $item[2];
                        $ArrYarnBlend[$item[0].'##'.$item[1].'##'.$item[3]][] = $item[4];
                        $ArrYarnContent[$item[0].'##'.$item[1].'##'.$item[3]][] = $item[5];
                        $ArrYarnCount[$item[0].'##'.$item[1].'##'.$item[3]][] = $item[8];
                        $ArrYarnSplReq[$item[0].'##'.$item[1].'##'.$item[3]][] = $item[10];

                    }
                    foreach($ArrFabricOneAJxl as $items) {
                        $ArrFilteredFab1A[$items[0].'##'.$items[1].'##'.$items[3]] = array($items[0],$items[1],$items[2],$items[3],
                            $items[4],$items[5],$items[6],$items[7],$items[8],$items[9],$items[10]);
                    }
                    foreach($ArrColor as $keys => $colorItem) {
                        if(count($colorItem) >= 2) {
                            $ArrJoinedColor[$keys] = implode(' : ',$colorItem);
                        }
                    }
                    foreach($ArrYarnBlend as $keys => $yarnBlendItem) {
                        if(count($yarnBlendItem) >= 2) {
                            $ArrJoinedYarnBlend[$keys] = implode(' / ',$yarnBlendItem);
                        }
                    }
                    foreach ($ArrYarnContent as $keys => $yarnContentItem) {
                        if(count($yarnContentItem) >= 2) {
                            $ArrJoinedYarnContent[$keys] = implode(' / ',$yarnContentItem);
                        }
                    }
                    /* Changed / to :: in yarn count joining
                     * Used Double colon here in yarn count because If already slash is present in yarn count
                    */
                    foreach ($ArrYarnCount as $keys => $yarnCountItem) {
                        if(count($yarnCountItem) >= 2) {
                            $ArrJoinedYarnCount[$keys] = implode(' / ',$yarnCountItem);
                        }
                    }
                    foreach ($ArrYarnSplReq as $keys => $splReq) {
                        if(count($splReq) >= 2) {
                            $ArrJoinedYarnSplReq[$keys] = implode(' / ',$splReq);
                        }
                    }
                    foreach($ArrFilteredFab1A as $finalKey => $final) {
                        $ArrFinal[] = array($final[0],$final[1],!empty($ArrJoinedColor[$finalKey]) ? $ArrJoinedColor[$finalKey] : $final[2],$final[3],
                            !empty($ArrJoinedYarnBlend[$finalKey]) ? $ArrJoinedYarnBlend[$finalKey] : $final[4],
                            !empty($ArrJoinedYarnContent[$finalKey]) ? $ArrJoinedYarnContent[$finalKey] : $final[5],'','',
                            $final[6],$final[7],!empty($ArrJoinedYarnCount[$finalKey]) ? $ArrJoinedYarnCount[$finalKey] : $final[8],$final[9],!empty($ArrJoinedYarnSplReq[$finalKey]) ? $ArrJoinedYarnSplReq[$finalKey] : $final[10]);
                    }
                }
                $ArrData['fabricProTwoJxl']=$jsonFabricTwoJxl;
                $ArrData['ArrFabricOneAJxlMergedColor']=$ArrFinal;
            }
            if($VarPageId == "three") {
                $ArrDryProcess = $ArrWetProcess = [];
                $ArrFabricFinish = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_FINISH_WET_DRY, 'fabricfinish,processingtype', array('status' => "1",
                    'companyid' => $this->companyid));
                if (!empty($ArrFabricFinish)) {
                    foreach ($ArrFabricFinish as $item) {
                        if ($item['processingtype'] == 'Dry') $ArrDryProcess[] = $item['fabricfinish'];
                        if ($item['processingtype'] == 'Wet') $ArrWetProcess[] = $item['fabricfinish'];
                    }
                }
                $jsonFabricThree = '';
                $this->db->select('jsondatagrid');
                $VarQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,
                    'tableid'=>$ArrTableId));
                $threeResult = $VarQry->row();
                if(!empty($threeResult->jsondatagrid)) {
                    $jsonFabricThree = $threeResult->jsondatagrid;
                }
                //echo '<pre>'; print_r($jsonFabricThree); die('die');
                $ArrData['jsonFabricThreeJxl'] = $jsonFabricThree;
                $ArrData['WetProcess']=$ArrWetProcess;
                $ArrData['DryProcess']=$ArrDryProcess;
            }
            if($VarPageId == 'dyeingColDetails') {
                $ArrWetDryProcess = [];
                $ArrFabricFinish = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_FINISH_WET_DRY, 'fabricfinish', array('status' => "1",
                    'companyid' => $this->companyid));
                if (!empty($ArrFabricFinish)) {
                    foreach ($ArrFabricFinish as $item) {
                        $ArrWetDryProcess[] = $item['fabricfinish'];
                    }
                }
                $ArrData['wetDryProcess']=$ArrWetDryProcess;
                $this->db->select('jsondatagrid');
                $VarFfQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,
                    'tableid'=>'fF'));
                $ArrFfResult = $VarFfQry->row();
                if(!empty($ArrFfResult->jsondatagrid)) {
                    $ArrAllFabProThree = json_decode($ArrFfResult->jsondatagrid,true);
                    foreach($ArrAllFabProThree as $item) {
                        //$ArrSpliceFabProThree = array_splice($item,0,-2);
                        //$ArrFabProThree[] = $ArrSpliceFabProThree;
                        /* @TODO Ony if color has slash with space. Like "Melange / Navy" */
                        $ArrColor = explode(' / ',$item[2]);
                        /*If y count has slash split with color.
                          Some places only color has split where y count has no slash. at that place use !empty()
                         */
                        $yCount = $item[8];
                        $ArrYCount = explode(' / ',$yCount);
                        if(count($ArrColor) > 1) {

                            foreach($ArrColor as $colorKi => $color) {
                                $ArrFabProThree[] = array(
                                    $item[0],$item[1],$color,$item[3],$item[6],$item[7],!empty($ArrYCount[$colorKi]) ? $ArrYCount[$colorKi] : $yCount,$item[9]
                                );
                            }
                        }
                        else {
                            $ArrFabProThree[] = array($item[0],$item[1],$item[2],$item[3],$item[6],$item[7],$yCount,$item[9]);
                        }
                    }
                }
                else {
                    $ArrFabProThree[] = [];
                }
                $jsonFabProCurrent = '';
                $this->db->select('jsondatagrid');
                $VarCurrentQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,
                    'tableid'=>$ArrTableId));
                $ArrCurrentResult = $VarCurrentQry->row();
                if(!empty($ArrCurrentResult->jsondatagrid)) {
                    $jsonFabProCurrent = $ArrCurrentResult->jsondatagrid;
                }

                $ArrKnitFabricContent = [];
                $ArrColorMatchStdRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_COLOR_MATCH_STD, 'clrmatchingstd', array('status' => "1",
                    'companyid' => $this->companyid));
                if (!empty($ArrColorMatchStdRes)) {
                    foreach ($ArrColorMatchStdRes as $item) {
                        $ArrColorMatchStd[] = $item['clrmatchingstd'];
                    }
                }
                else $ArrColorMatchStd = array();
                $ArrDyeingSplReqRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_DYEING_SPECIAL_REQUEST, 'dsrname', array('status' => "1",
                    'companyid' => $this->companyid));
                if (!empty($ArrDyeingSplReqRes)) {
                    foreach ($ArrDyeingSplReqRes as $item)
                        $ArrDyeingSplReq[] = $item['dsrname'];
                }
                else $ArrDyeingSplReq = [];
                $ArrFabricInfo = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_MISC, 'misc_name', array('status' => 1, 'companyid' => $this->companyid,
                    'misc_type' => 2));
                if (!empty($ArrFabricInfo)) {
                    foreach ($ArrFabricInfo as $item) {
                        $ArrKnitFabricContent[] = $item['misc_name'];
                    }
                }
                $ArrData['knitFabricContent'] = $ArrKnitFabricContent;
                $ArrData['colorMatchStd'] = $ArrColorMatchStd;
                $ArrData['dyeingSplReq'] = $ArrDyeingSplReq;
                $ArrData['currentTbl'] = $jsonFabProCurrent;
                $ArrData['ArrFabProThree'] = $ArrFabProThree;
            }
            if($VarPageId == 'partsXsQty') {
                $jsonFabProCurrent = '';
                $ArrGarmentSizes = $ArrRes = [];
                $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
                if(!empty($ArrSizeChartData->sizechartvalue)) {
                    $ArrGarmentSizes = explode(',',$ArrSizeChartData->sizechartvalue);
                }
                $VarSizeColStart = count($ArrGarmentSizes) + 6;

                $this->db->select('jsondatagrid');
                $VarCurrentQry = $this->db->get_where(FP_EXCESS_QTY, array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                $ArrCurrentResult = $VarCurrentQry->row();
                if(!empty($ArrCurrentResult->jsondatagrid)) {
                    $jsonFabProCurrent = $ArrCurrentResult->jsondatagrid;
                }
                $jsonNewFourthTblRes = $this->orderentrymodel->getFromNewFourthTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonNewFourthTblRes->jsondatagrid)) {
                    $ArrFourthTbl = json_decode($jsonNewFourthTblRes->jsondatagrid,true);
                    foreach($ArrFourthTbl as $item) {
                        $ArrNewFourthTbl[] = array($item[0],$item[1],$item[2],"",$item[4],$item[5],"");
                    }
                    foreach ($ArrFourthTbl as $key => $item) {
                        for ($ii = 6; $ii < $VarSizeColStart; $ii++) {
                            $ArrNewFourthTblSizeCol[$key][] = $item[$ii];
                        }
                        //Itemized PO Qty Last Column
                        $ArrNewFourthTblSizeCol[$key][] = $item[$ii];
                    }
                    foreach($ArrNewFourthTbl as $key => $val) {
                        foreach($ArrNewFourthTblSizeCol as $size) {
                            $ArrRes[$key] = array_merge($val,$ArrNewFourthTblSizeCol[$key]);
                        }
                    }
                    /* Splitting color which has - Hyphen */
                    foreach($ArrRes as $keys => $data) {
                        $ArrColor = explode('-',$data[2]);
                        if(count($ArrColor) > 1) {
                            foreach($ArrColor as $color) {
                                //echo '<pre>'; print_r($color);
                                $data[2] = $color;
                                $ArrFinalFourth[] = $data;
                            }
                        }
                        else {
                            $ArrFinalFourth[] = $data;
                        }
                    }
                }
                $jsonFabricFinishTbl = '';
                $this->db->select('jsondatagrid');
                $VarFabricFinishQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,
                    'tableid'=>'fF'));
                $ArrFabricFinishResult = $VarFabricFinishQry->row();
                if(!empty($ArrFabricFinishResult->jsondatagrid)) {
                    $jsonFabricFinishTbl = $ArrFabricFinishResult->jsondatagrid;
                }
                $ArrGarmentPartsRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_GARMENT_PART_DESC, 'gpdname', array('status' => "1",
                    'companyid' => $this->companyid));
                if (!empty($ArrGarmentPartsRes)) {
                    foreach ($ArrGarmentPartsRes as $item) {
                        $ArrGarmentParts[] = $item['gpdname'];
                    }
                }
                $ArrData['ArrSizeChart'] =$ArrGarmentSizes;
                $ArrData['ArrGarmentParts'] =$ArrGarmentParts;
                $ArrData['jsonFabricFinishTbl']=$jsonFabricFinishTbl;
                $ArrData['initialData']=$ArrFinalFourth;
                $ArrData['jsonFabProCurrent']=$jsonFabProCurrent;
            }
            if($VarPageId == "pcsWeight") {
                $ArrGarmentSizes = [];
                $jsonPieceWeightJxl = '';
                $this->db->select('jsondatagrid');
                $VarCurrentQry    = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                    'tableid'=>$ArrTableId));
                $fabricProPieceWeight = $VarCurrentQry->row();
                if(!empty($fabricProPieceWeight->jsondatagrid)) {
                    $jsonPieceWeightJxl = $fabricProPieceWeight->jsondatagrid;
                }
                $jsonExcessQty = '';
                $this->db->select('jsondatagrid');
                $VarExcessQtyQry = $this->db->get_where(FP_EXCESS_QTY, array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                $ArrExcessResult = $VarExcessQtyQry->row();
                if(!empty($ArrExcessResult->jsondatagrid)) {
                    $jsonExcessQty = $ArrExcessResult->jsondatagrid;
                }
                $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
                if(!empty($ArrSizeChartData->sizechartvalue)) {
                    $ArrGarmentSizes = explode(',',$ArrSizeChartData->sizechartvalue);
                }
                $ArrData['ArrSizeChart'] =$ArrGarmentSizes;
                $ArrData['fabricProCurrent'] = $jsonPieceWeightJxl;
                $ArrData['jsonExcessQty']=$jsonExcessQty;
            }
            if($VarPageId == "fabCcProcessLoss") {
                $jsonPartsAndExcessQty = '';
                $this->db->select('jsondatagrid');
                $VarPartsAndExcessQtyQry = $this->db->get_where(FP_EXCESS_QTY, array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                $ArrPartsAndExcessQtyResult = $VarPartsAndExcessQtyQry->row();
                if(!empty($ArrPartsAndExcessQtyResult->jsondatagrid)) {
                    $jsonPartsAndExcessQty = $ArrPartsAndExcessQtyResult->jsondatagrid;
                }
                $jsonFabProPieceWeight = '';
                $this->db->select('jsondatagrid');
                $VarPieceWeightQry    = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                    'tableid'=>'pW'));
                $fabricProPieceWeight = $VarPieceWeightQry->row();
                if(!empty($fabricProPieceWeight->jsondatagrid)) {
                    $jsonFabProPieceWeight = $fabricProPieceWeight->jsondatagrid;
                }
                $ArrData['jsonPartsAndExcessQty']=$jsonPartsAndExcessQty;
                $ArrData['jsonFabProPieceWeight'] =$jsonFabProPieceWeight;
                $jsonCurrentJxl = '';
                $this->db->select('jsondatagrid');
                $VarCurrentQry    = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                    'tableid'=>$ArrTableId));
                $fabricProCurrentRes = $VarCurrentQry->row();
                if(!empty($fabricProCurrentRes->jsondatagrid)) {
                    $jsonCurrentJxl = $fabricProCurrentRes->jsondatagrid;
                }
                $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
                if(!empty($ArrSizeChartData->sizechartvalue)) {
                    $ArrGarmentSizes = explode(',',$ArrSizeChartData->sizechartvalue);
                }
                $ArrData['ArrSizeChart']=$ArrGarmentSizes;
                $ArrData['currentJxl'] = $jsonCurrentJxl;
            }
            if($VarPageId == "fabConCalc") {
                $jsonProCcProcessLoss = '';
                $this->db->select('jsondatagrid');
                $VarCcProcessLossQry    = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                    'tableid'=>'fCcPL'));
                $fabricCcProcessLoss = $VarCcProcessLossQry->row();
                if(!empty($fabricCcProcessLoss->jsondatagrid)) {
                    $jsonProCcProcessLoss = $fabricCcProcessLoss->jsondatagrid;
                }
                $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
                $ArrGarmentSizes = [];
                if(!empty($ArrSizeChartData->sizechartvalue)) {
                    $ArrGarmentSizes = explode(',',$ArrSizeChartData->sizechartvalue);
                }
                $ArrData['ArrSizeChart'] = $ArrGarmentSizes;
                $ArrData['jsonProCcProcessLoss'] = $jsonProCcProcessLoss;
            }
            if($VarPageId == "diaOrDim") {
                //dDimension
                $jsonConCalcProcessLoss = '';
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                    'tableid'=>'fCcPL'));
                $fabricProSixJxlRes = $VarQry->row();
                if(!empty($fabricProSixJxlRes->jsondatagrid)) {
                    $jsonConCalcProcessLoss = $fabricProSixJxlRes->jsondatagrid;
                }
                $ArrData['jsonConCalcProcessLoss'] = $jsonConCalcProcessLoss;
                $fabricProEightJxl = '';
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                    'tableid'=>$ArrTableId));
                $fabricProEightJxlRes = $VarQry->row();
                if(!empty($fabricProEightJxlRes->jsondatagrid)) {
                    $fabricProEightJxl = $fabricProEightJxlRes->jsondatagrid;
                }
                $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
                $ArrGarmentSizes = [];
                if(!empty($ArrSizeChartData->sizechartvalue)) {
                    $ArrGarmentSizes = explode(',',$ArrSizeChartData->sizechartvalue);
                }
                $ArrData['ArrSizeChart'] = $ArrGarmentSizes;
                $ArrData['currentJxl'] = $fabricProEightJxl;
            }
            if($VarPageId == "fabRequirement") {
                //In pagination links its 10th

                /*
                 * Here the table 10 (Fab Requirement) is not saved in DB, Because all are AUTO and Hence no Save button
                * */
                $fabricProSevenAJxl = '';
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                    'tableid'=>'cummuConCalc'));
                $fabricProSevenAJxlRes = $VarQry->row();
                if(!empty($fabricProSevenAJxlRes->jsondatagrid)) {
                    $fabricProSevenAJxl = $fabricProSevenAJxlRes->jsondatagrid;
                }
                $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
                $ArrGarmentSizes = [];
                if(!empty($ArrSizeChartData->sizechartvalue)) {
                    $ArrGarmentSizes = explode(',',$ArrSizeChartData->sizechartvalue);
                }
                $jsonFabricFinish = '';
                $this->db->select('jsondatagrid');
                $VarQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,
                    'tableid'=>'fF'));
                $ArrResult = $VarQry->row();
                if(!empty($ArrResult->jsondatagrid)) {
                    $jsonFabricFinish = $ArrResult->jsondatagrid;
                }
                $ArrData['jsonFabricFinish'] = $jsonFabricFinish;
                $ArrData['ArrSizeChart']=$ArrGarmentSizes;
                $ArrData['sevenAJxl'] = $fabricProSevenAJxl;
                $jsonFabProFeederLycra = '';
                $this->db->select('jsondatagrid');
                $VarFeederLycraQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,
                    'tableid'=>'feederLycra'));
                $ArrFeederLycraResult = $VarFeederLycraQry->row();
                if(!empty($ArrFeederLycraResult->jsondatagrid)) {
                    $jsonFabProFeederLycra = $ArrFeederLycraResult->jsondatagrid;
                }
                $ArrData['jsonFabricTwoJxl'] = $jsonFabProFeederLycra;
                $jsonConCalcProcessLoss = '';
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                    'tableid'=>'fCcPL'));
                $fabricProSixJxlRes = $VarQry->row();
                if(!empty($fabricProSixJxlRes->jsondatagrid)) {
                    $jsonConCalcProcessLoss = $fabricProSixJxlRes->jsondatagrid;
                }
                $ArrData['jsonConCalcProcessLoss'] = $jsonConCalcProcessLoss;
                $fabricProEightJxl = '';
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                    'tableid'=>'dDimension'));
                $fabricProEightJxlRes = $VarQry->row();
                if(!empty($fabricProEightJxlRes->jsondatagrid)) {
                    $fabricProEightJxl = $fabricProEightJxlRes->jsondatagrid;
                }
                $ArrData['savedDiaDimension'] = $fabricProEightJxl;
            }
            if($VarPageId == "yarnDyeingStrips") {
                $fabricProElevenJxl = '';
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(YARN_DYE_STRIPS, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid));
                $fabricProElevenJxlRes = $VarQry->row();
                if(!empty($fabricProElevenJxlRes->jsondatagrid)) {
                    $fabricProElevenJxl = $fabricProElevenJxlRes->jsondatagrid;
                }
                $ArrData['yarnDyeingStrips'] = $fabricProElevenJxl;
                $fabricProEightJxl = '';
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                    'tableid'=>'dDimension'));
                $fabricProEightJxlRes = $VarQry->row();
                if(!empty($fabricProEightJxlRes->jsondatagrid)) {
                    $fabricProEightJxl = $fabricProEightJxlRes->jsondatagrid;
                }
                $ArrData['savedDiaDimension'] = $fabricProEightJxl;

                //fab Requirement STARTS
                $ArrPlannedFabWeight = [];
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(PLAN_FAB_WGT_SUBTOTAL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid));
                $VarRes = $VarQry->row();
                if(!empty($VarRes->jsondatagrid)) {
                    $planFabWeightSubTotal = $VarRes->jsondatagrid;
                    $ArrPlannedFabWgt = json_decode($planFabWeightSubTotal,true);
                    foreach($ArrPlannedFabWgt as $keys => $plannedWgt) {
                        $ArrLeftSide = explode('##',$keys);
                        if(count($ArrLeftSide) > 1) {
                            $VarColor = $ArrLeftSide[2];
                            $ArrColor = explode(':',$ArrLeftSide[2]);
                            if(count($ArrColor) > 1) {
                                foreach($ArrColor as $color) {
                                    $ArrPlannedFabWeight[$ArrLeftSide[0].'##'.$ArrLeftSide[1].'##'.trim($color).'##'.$ArrLeftSide[3].'##'.$ArrLeftSide[4]] = $plannedWgt;
                                }
                            }
                            else {
                                $ArrPlannedFabWeight[$ArrLeftSide[0].'##'.$ArrLeftSide[1].'##'.$VarColor.'##'.$ArrLeftSide[3].'##'.$ArrLeftSide[4]] = $plannedWgt;
                            }
                        }
                    }
                }
                $ArrData['planFabWeightSubTotal'] = $ArrPlannedFabWeight;
                //ENDS
                //No of feed and lycra STARTS
                $jsonFeederLycra = '';
                $this->db->select('jsondatagrid');
                $VarQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                    'tableid'=>'feederLycra'));
                $VarRes = $VarQry->row();
                if(!empty($VarRes->jsondatagrid)) {
                    $jsonFeederLycra = $VarRes->jsondatagrid;
                }
                $ArrData['jsonFeederLycra'] = $jsonFeederLycra;
                //ENDS

            }
            if($VarPageId == "singleDyeingBath") {
                $fabricProTenJxl = '';
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(SINGLE_DYE_BATH, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid));
                $fabricProNineJxlRes = $VarQry->row();
                if(!empty($fabricProNineJxlRes->jsondatagrid)) {
                    $fabricProTenJxl = $fabricProNineJxlRes->jsondatagrid;
                }
                $ArrData['singleDyeBath'] = $fabricProTenJxl;
                //fab Requirement STARTS
                $ArrPlannedFabWeight = [];
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(PLAN_FAB_WGT_SUBTOTAL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid));
                $VarRes = $VarQry->row();
                if(!empty($VarRes->jsondatagrid)) {
                    $planFabWeightSubTotal = $VarRes->jsondatagrid;
                    $ArrPlannedFabWgt = json_decode($planFabWeightSubTotal,true);
                    foreach($ArrPlannedFabWgt as $keys => $plannedWgt) {
                        $ArrLeftSide = explode('##',$keys);
                        if(count($ArrLeftSide) > 1) {
                            $ArrColor = explode(':',$ArrLeftSide[2]);
                            if(count($ArrColor) > 1) {
                                foreach($ArrColor as $color) {
                                    $ArrPlannedFabWeight[$ArrLeftSide[0].'##'.$ArrLeftSide[1].'##'.trim($color).'##'.$ArrLeftSide[3].'##'.$ArrLeftSide[4]] = $plannedWgt;
                                }
                            }
                            else {
                                $ArrPlannedFabWeight[$ArrLeftSide[0].'##'.$ArrLeftSide[1].'##'.$ArrLeftSide[2].'##'.$ArrLeftSide[3].'##'.$ArrLeftSide[4]] = $plannedWgt;
                            }
                        }
                    }
                }
                $ArrData['planFabWeightSubTotal'] = $ArrPlannedFabWeight;
                //ENDS

                //No of feed and lycra STARTS
                $jsonFeederLycra = '';
                $this->db->select('jsondatagrid');
                $VarQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                'tableid'=>'feederLycra'));
                $VarRes = $VarQry->row();
                if(!empty($VarRes->jsondatagrid)) {
                    $jsonFeederLycra = $VarRes->jsondatagrid;
                }
                $ArrData['jsonFeederLycra'] = $jsonFeederLycra;
                //ENDS

            }
            if($VarPageId == "yarnDyeingJacquard") {
                $jsonJacquard = '';
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(YARN_DYEING_JACQUARD, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid));
                $ArrResult = $VarQry->row();
                if(!empty($ArrResult->jsondatagrid)) {
                    $jsonJacquard = $ArrResult->jsondatagrid;
                }
                $ArrData['jsonJacquard'] = $jsonJacquard;

                //fab Requirement STARTS
                $ArrPlannedFabWeight = [];
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(PLAN_FAB_WGT_SUBTOTAL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid));
                $VarRes = $VarQry->row();
                if(!empty($VarRes->jsondatagrid)) {
                    $planFabWeightSubTotal = $VarRes->jsondatagrid;
                    $ArrPlannedFabWgt = json_decode($planFabWeightSubTotal,true);
                    foreach($ArrPlannedFabWgt as $keys => $plannedWgt) {
                        $ArrLeftSide = explode('##',$keys);
                        if(count($ArrLeftSide) > 1) {
                            $ArrColor = explode(':',$ArrLeftSide[2]);
                            if(count($ArrColor) > 1) {
                                foreach($ArrColor as $color) {
                                    $ArrPlannedFabWeight[$ArrLeftSide[0].'##'.$ArrLeftSide[1].'##'.trim($color).'##'.$ArrLeftSide[3].'##'.$ArrLeftSide[4]] = $plannedWgt;
                                }
                            }
                            else {
                                $ArrPlannedFabWeight[$ArrLeftSide[0].'##'.$ArrLeftSide[1].'##'.$ArrLeftSide[2].'##'.$ArrLeftSide[3].'##'.$ArrLeftSide[4]] = $plannedWgt;
                            }
                        }
                    }
                }
                $ArrData['planFabWeightSubTotal'] = $ArrPlannedFabWeight;
                //ENDS

                //No of feed and lycra STARTS
                $jsonFeederLycra = '';
                $this->db->select('jsondatagrid');
                $VarQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                    'tableid'=>'feederLycra'));
                $VarRes = $VarQry->row();
                if(!empty($VarRes->jsondatagrid)) {
                    $jsonFeederLycra = $VarRes->jsondatagrid;
                }
                $ArrData['jsonFeederLycra'] = $jsonFeederLycra;
                //ENDS
            }
            if($VarPageId == "thirteen") {
                //fab Requirement STARTS
                $ArrPlannedFabWeight = [];
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(PLAN_FAB_WGT_SUBTOTAL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid));
                $VarRes = $VarQry->row();
                if(!empty($VarRes->jsondatagrid)) {
                    $planFabWeightSubTotal = $VarRes->jsondatagrid;
                    $ArrPlannedFabWgt = json_decode($planFabWeightSubTotal,true);
                    foreach($ArrPlannedFabWgt as $keys => $plannedWgt) {
                        $ArrLeftSide = explode('##',$keys);
                        if(count($ArrLeftSide) > 1) {
                            $ArrColor = explode(':',$ArrLeftSide[2]);
                            if(count($ArrColor) > 1) {
                                foreach($ArrColor as $color) {
                                    $ArrPlannedFabWeight[$ArrLeftSide[0].'##'.$ArrLeftSide[1].'##'.trim($color).'##'.$ArrLeftSide[3].'##'.$ArrLeftSide[4]] = $plannedWgt;
                                }
                            }
                            else {
                                $ArrPlannedFabWeight[$ArrLeftSide[0].'##'.$ArrLeftSide[1].'##'.$ArrLeftSide[2].'##'.$ArrLeftSide[3].'##'.$ArrLeftSide[4]] = $plannedWgt;
                            }
                        }
                    }
                }
                $ArrData['planFabWeightSubTotal'] = $ArrPlannedFabWeight;
                //ENDS

                //No of feed and lycra STARTS
                $jsonFeederLycra = '';
                $this->db->select('jsondatagrid');
                $VarQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                    'tableid'=>'feederLycra'));
                $VarRes = $VarQry->row();
                if(!empty($VarRes->jsondatagrid)) {
                    $jsonFeederLycra = $VarRes->jsondatagrid;
                }
                $ArrData['jsonFeederLycra'] = $jsonFeederLycra;
                //ENDS

                $fabricProTenJxl = '';
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(SINGLE_DYE_BATH, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid));
                $fabricProNineJxlRes = $VarQry->row();
                if(!empty($fabricProNineJxlRes->jsondatagrid)) {
                    $fabricProTenJxl = $fabricProNineJxlRes->jsondatagrid;
                }

                $ArrData['singleDyeBath'] = $fabricProTenJxl;
                $fabricProElevenJxl = '';
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(YARN_DYE_STRIPS, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid));
                $fabricProElevenJxlRes = $VarQry->row();
                if(!empty($fabricProElevenJxlRes->jsondatagrid)) {
                    $fabricProElevenJxl = $fabricProElevenJxlRes->jsondatagrid;

                }
                $ArrData['yarnDyeStrips'] = $fabricProElevenJxl;
                $fabricProTwelveJxl = '';
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(YARN_DYEING_JACQUARD, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid));
                $ArrResult = $VarQry->row();
                if(!empty($ArrResult->jsondatagrid)) {
                    $fabricProTwelveJxl = $ArrResult->jsondatagrid;
                }
                $ArrData['jsonJacquard'] = $fabricProTwelveJxl;
                $fabricProThirteenJxl = '';
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(FP_YARN_PROGRAM, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid));
                $ArrResult = $VarQry->row();
                if(!empty($ArrResult->jsondatagrid)) {
                    $fabricProThirteenJxl = $ArrResult->jsondatagrid;
                }
                $ArrData['yarnProgram'] = $fabricProThirteenJxl;
            }
            if($VarPageId == "fabKnittingProgram") {
                $consKnittingPro = '';
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(FP_CONS_KNITTING_PROGRAM, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid));
                $ArrResult = $VarQry->row();
                if(!empty($ArrResult->jsondatagrid)) {
                    $consKnittingPro = $ArrResult->jsondatagrid;
                }
                $ArrData['consKnittingProgram'] = $consKnittingPro;

                $jsonYarnPgm = '';
                $this->db->select('jsondatagrid');
                $VarYarnPgmQry    = $this->db->get_where(FP_YARN_PROGRAM, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid));
                $ArrYarnPgmQryResult = $VarYarnPgmQry->row();
                if(!empty($ArrYarnPgmQryResult->jsondatagrid)) {
                    $jsonYarnPgm = $ArrYarnPgmQryResult->jsondatagrid;
                }
                $ArrData['yarnPgm'] = $jsonYarnPgm;

                //fab Requirement STARTS
                $ArrPlannedFabWeight = [];
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(PLAN_FAB_WGT_SUBTOTAL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid));
                $VarRes = $VarQry->row();
                if(!empty($VarRes->jsondatagrid)) {
                    $planFabWeightSubTotal = $VarRes->jsondatagrid;
                    $ArrPlannedFabWgt = json_decode($planFabWeightSubTotal,true);
                    foreach($ArrPlannedFabWgt as $keys => $plannedWgt) {
                        $ArrLeftSide = explode('##',$keys);
                        if(count($ArrLeftSide) > 1) {
                            $ArrColor = explode(':',$ArrLeftSide[2]);
                            if(count($ArrColor) > 1) {
                                foreach($ArrColor as $color) {
                                    $ArrPlannedFabWeight[$ArrLeftSide[0].'##'.$ArrLeftSide[1].'##'.trim($color).'##'.$ArrLeftSide[3].'##'.$ArrLeftSide[4]] = $plannedWgt;
                                }
                            }
                            else {
                                $ArrPlannedFabWeight[$ArrLeftSide[0].'##'.$ArrLeftSide[1].'##'.$ArrLeftSide[2].'##'.$ArrLeftSide[3].'##'.$ArrLeftSide[4]] = $plannedWgt;
                            }
                        }
                    }
                }
                $ArrData['planFabWeightSubTotal'] = $ArrPlannedFabWeight;
                //ENDS

            }
            if($VarPageId == "labTesting") {
                $ArrLabTestDesc = $ArrLabAcceptLevel = [];
                $jsonNewFourthTbl = $jsonLabTesting = '';
                $ArrLabTestDescRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_LAB, 'labname', array('status' => "1", 'companyid' => $this->companyid));
                if (empty(!$ArrLabTestDescRes)) {
                    foreach ($ArrLabTestDescRes as $item)
                        $ArrLabTestDesc[] = $item['labname'];
                }
                $ArrData['ArrLabTestDesc'] = $ArrLabTestDesc;
                $ArrLabAcceptLevelRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_ACCEPTANCE_LEVEL, 'acceptablelevel',
                    array('status' => "1", 'companyid' => $this->companyid));
                if(!empty($ArrLabAcceptLevelRes)) {
                    foreach($ArrLabAcceptLevelRes as $item)
                        $ArrLabAcceptLevel[] = $item['acceptablelevel'];
                }
                $ArrData['ArrAcceptanceLevel'] = $ArrLabAcceptLevel;
                $jsonNewFourthTblRes = $this->orderentrymodel->getFromNewFourthTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonNewFourthTblRes->jsondatagrid))
                    $jsonNewFourthTbl = $jsonNewFourthTblRes->jsondatagrid;
                $ArrData['jsonNewFourth'] = $jsonNewFourthTbl;
                $this->db->select('jsondatagrid');
                $VarQry    = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId,'companyid'=>$this->companyid,
                    'tableid'=>$ArrTableId));
                $ArrResult = $VarQry->row();
                if(!empty($ArrResult->jsondatagrid)) {
                    $jsonLabTesting = $ArrResult->jsondatagrid;
                }
                $ArrData['jsonLabTesting'] = $jsonLabTesting;

                $jsonFabricOneAJxl = '';
                $this->db->select('jsondatagrid');
                $VarQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,
                    'tableid'=>'yarnBC'));
                $ArrResult = $VarQry->row();
                if(!empty($ArrResult->jsondatagrid)) {
                    $jsonFabricOneAJxl = $ArrResult->jsondatagrid;
                }
                $ArrData['jsonFromB4Seventh'] = $jsonFabricOneAJxl;
            }
            if($VarPageId == "extLabTesting") {
                $jsonExtLabTesting = '';
                $this->db->select('jsondatagrid');
                $VarQry = $this->db->get_where(FABRIC_PROGRAM_ALL_JXL, array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,
                    'tableid'=>$ArrTableId));
                $ArrResult = $VarQry->row();
                if(!empty($ArrResult->jsondatagrid)) {
                    $jsonExtLabTesting = $ArrResult->jsondatagrid;
                }
                $ArrData['jsonExtLabTesting'] = $jsonExtLabTesting;
                $ArrExtLabAuthTesting = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_EXT_LAB,'test_auth_name,address,gst,contact_name,email,phone,mobile',
                    array('companyid' => $this->companyid));
                $ArrExtLabAuthTest = $ArrAddress = $ArrGst = $ArrContactDetail = [];
                foreach($ArrExtLabAuthTesting as $auth) {
                    $ArrExtLabAuthTest[] = $auth['test_auth_name'];
                    $ArrAddress[$auth['test_auth_name']] = $auth['address'];
                    $ArrGst[$auth['test_auth_name']] = $auth['gst'];
                    $ArrContactDetail[$auth['test_auth_name']] = $auth['contact_name'] . ' / ' . $auth['email'] . ' / ' . $auth['phone'] . ' / ' .$auth['mobile'];

                }
                //echo '<pre>'; print_r($ArrExtLabAuthTest); die('die');
                $ArrData['ArrLabAuthName'] = $ArrExtLabAuthTest;
                $ArrData['ArrAddress'] = $ArrAddress;
                $ArrData['ArrGst'] = $ArrGst;
                $ArrData['ArrContactDetail'] = $ArrContactDetail;
            }
            echo json_encode($ArrData);
        }
    }

    public function two() {
        $ArrFabricPages = unserialize(ARR_FABRIC_PROGRAM_PAGES);
        $VarPageId = $this->router->method;
        $ArrTableId = $ArrFabricPages[$VarPageId];
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
        $ArrData['VarPageId'] = $VarPageId;
        $this->load->view("fabric_program/two", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId,'VarTableId'=>$ArrTableId
            )
        );
    }

    public function saveTwoJxl() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonTwoData = xssclean($this->input->post('twoData'));
                $jsonThreeData = xssclean($this->input->post('threeData'));
                $VarTableId = xssclean($this->input->post('tid'));
                $ArrWhere = array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>'fF');
                $VarCountThreeTbl = $this->db->where($ArrWhere)->from(FABRIC_PROGRAM_ALL_JXL)->count_all_results();

                if($VarCountThreeTbl >= 1) {
                    $this->db->where(array('referenceid' => $VarEnquiryId,'companyid' => $this->companyid,'tableid'=>'fF'));
                    $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonThreeData,
                        'dateupdated' =>  $this->mysqldatetime, 'updatedby' => $this->userid));
                }
                else {
                    $this->db->insert(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonThreeData,
                        'dateupdated' =>  $this->mysqldatetime, 'updatedby' => $this->userid, 'referenceid' => $VarEnquiryId,
                        'companyid' => $this->companyid,'tableid'=>'fF'));
                }

                $ArrWhere = array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$VarTableId);
                $VarCountAllRes = $this->db->where($ArrWhere)->from(FABRIC_PROGRAM_ALL_JXL)->count_all_results();
                if ($VarCountAllRes >= 1) {
                    $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonTwoData,'dateupdated' =>
                        $this->mysqldatetime, 'updatedby' => $this->userid),
                        array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$VarTableId));

                } else {
                    $this->db->insert(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonTwoData,
                        'dateupdated' =>  $this->mysqldatetime, 'updatedby' => $this->userid, 'referenceid' => $VarEnquiryId,
                        'companyid' => $this->companyid,'tableid'=>$VarTableId));
                }
                $ArrTables = array('dyeD','fCcPL','cummuConCalc');
                $this->db->where('referenceid',$VarEnquiryId);
                $this->db->where('companyid',$this->companyid);
                $this->db->where_in('tableid',$ArrTables);
                $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid));

                /*
                 * "dyeD" and "pW" Table No Need to make empty Because only the FT Data is saved to DB others come from base table
                 * */
                $this->db->update(FP_EXCESS_QTY, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));

                $this->db->update(PLAN_FAB_WGT_SUBTOTAL, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                $this->db->update(YARN_DYE_STRIPS, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                $this->db->update(SINGLE_DYE_BATH, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                $this->db->update(YARN_DYEING_JACQUARD, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                $this->db->update(FP_YARN_PROGRAM, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                $this->db->update(FP_CONS_KNITTING_PROGRAM, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                echo json_encode(array('errCode' => 1));
            }
        }
    }

    public function three() {
        $ArrFabricPages = unserialize(ARR_FABRIC_PROGRAM_PAGES);
        $VarPageId = $this->router->method;
        $ArrTableId = $ArrFabricPages[$VarPageId];
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
        $this->load->view("fabric_program/three", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId,'VarTableId'=>$ArrTableId
            )
        );
    }

    public function saveThreeJxl() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $jsonData = xssclean($this->input->post('d'));
            if ($VarEnquiryId >= 1) {
                $VarTableId = xssclean($this->input->post('tid'));
                $VarUpdate = $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData,'dateupdated' =>$this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$VarTableId));
                if($VarUpdate)
                    echo json_encode(array('errCode' => 1));
            }
        }
    }

    public function dyeingColDetails() {
        $ArrFabricPages = unserialize(ARR_FABRIC_PROGRAM_PAGES);
        $VarPageId = $this->router->method;
        $VarTableId = $ArrFabricPages[$VarPageId];
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
        $this->load->view("fabric_program/three_a", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId,
                'VarTableId'=>$VarTableId
            )
        );
    }

    public function saveThreeAJxl() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $jsonData = xssclean($this->input->post('d'));
            if ($VarEnquiryId >= 1) {
                $VarTableId = xssclean($this->input->post('tid'));
                $VarCountAllRes = $this->db->where(['referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$VarTableId])->
                from(FABRIC_PROGRAM_ALL_JXL)->count_all_results();
                if ($VarCountAllRes >= 1) {
                    $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData,'dateupdated' =>
                        $this->mysqldatetime, 'updatedby' => $this->userid),
                        array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$VarTableId));
                }
                else {
                    $this->db->insert(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData,'dateupdated' => $this->mysqldatetime,
                        'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$VarTableId));
                }
                echo json_encode(array('errCode'=>1));
            }
        }
    }

    public function partsXsQty() {
        $ArrFabricPages = unserialize(ARR_FABRIC_PROGRAM_PAGES);
        $VarPageId = $this->router->method;
        $VarTableId = $ArrFabricPages[$VarPageId];
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        $this->load->view("fabric_program/fabricProgramFour",
            array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId, 'VarTableId'=>$VarTableId
            )
        );
    }

    public function saveExcessQtyJxl() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('saveJxl'));
                $ArrWhere = array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid);
                $VarCountAllRes = $this->db->where($ArrWhere)->from(FP_EXCESS_QTY)->count_all_results();
                if ($VarCountAllRes >= 1) {
                    $this->db->update(FP_EXCESS_QTY, array('jsondatagrid' => $jsonData,'dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                        array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                }
                else {
                    $this->db->insert(FP_EXCESS_QTY, array('jsondatagrid' => $jsonData,'dateupdated' => $this->mysqldatetime,
                        'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                }
                $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>'fCcPL'));
                /*$this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => '','dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>''));*/
                /*$VarSql = "DELETE FROM FABRIC_PROGRAM_ALL_JXL WHERE referenceid = '".$VarEnquiryId."' AND companyid = '".$this->companyid."' AND tableid IN(5,6) ";
                $this->db->query($VarSql);*/
                echo json_encode(array('errCode'=>1));
            }
        }
    }


    public function pcsWeight() {
        $ArrFabricPages = unserialize(ARR_FABRIC_PROGRAM_PAGES);
        $VarPageId = $this->router->method;
        $VarTableId = $ArrFabricPages[$VarPageId];
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        $this->load->view("fabric_program/fabricProgramFive", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId,
                'VarTableId'=>$VarTableId
            )
        );
    }

    /* Update 6th Table (tableid) to empty always after saving 5th table
     * Otherwise it will show old data
    */
    public function saveFiveJxl() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $VarTableId = $this->input->post('tid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarCountAllRes = $this->db->where(['referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableId'=>$VarTableId])->
                from(FABRIC_PROGRAM_ALL_JXL)->count_all_results();
                if ($VarCountAllRes >= 1) {
                    $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData,'dateupdated' =>
                        $this->mysqldatetime, 'updatedby' => $this->userid),
                        array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$VarTableId));
                }
                else {
                    $this->db->insert(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData,'dateupdated' => $this->mysqldatetime,
                        'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$VarTableId));
                }
                $this->db->update(FABRIC_PROGRAM_ALL_JXL,
                    array('jsondatagrid' => '','dateupdated' =>$this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>'fCcPL'));
                echo json_encode(array('errCode'=>1));
            }
        }
    }

    public function fabCcProcessLoss() {
        $ArrFabricPages = unserialize(ARR_FABRIC_PROGRAM_PAGES);
        $VarPageId = $this->router->method;
        $VarTableId = $ArrFabricPages[$VarPageId];
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        $this->load->view("fabric_program/fabCcProcessLoss", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId,'VarTableId'=>$VarTableId
            )
        );
    }

    public function saveSixJxl() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $VarTableId = $this->input->post('tid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarCountAllRes = $this->db->where(['referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$VarTableId])->
                from(FABRIC_PROGRAM_ALL_JXL)->count_all_results();
                if ($VarCountAllRes >= 1) {
                    $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData,'dateupdated' =>
                        $this->mysqldatetime, 'updatedby' => $this->userid),
                        array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$VarTableId));
                }
                else {
                    $this->db->insert(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData,'dateupdated' => $this->mysqldatetime,
                        'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$VarTableId));
                }

                /*
                $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => '','dateupdated' =>
                    $this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>'cummuConCalc'));*/
                echo json_encode(array('errCode'=>1));
            }
        }
    }

    public function fabConCalc() {
        $VarPageId = $this->router->method;
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        $this->load->view("fabric_program/fabricProgramSeven", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId
            )
        );
    }

    public function diaOrDim() {
        $VarPageId = $this->router->method;
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        $this->load->view("fabric_program/fabricProgramEight", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId
            )
        );
    }

    public function saveEightJxl() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $ArrFabricPages = unserialize(ARR_FABRIC_PROGRAM_PAGES);
            $VarPageId = $this->input->post('pid');
            $jsonCummuConCalc = $this->input->post('cummuConCalc');
            $VarCountAllRes = $this->db->where(['referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>'cummuConCalc'])->
            from(FABRIC_PROGRAM_ALL_JXL)->count_all_results();

            if ($VarCountAllRes >= 1) {
                $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonCummuConCalc,'dateupdated' =>
                    $this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>'cummuConCalc'));
            }
            else {
                $this->db->insert(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonCummuConCalc,'dateupdated' => $this->mysqldatetime,
                    'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>'cummuConCalc'));
            }
            $ArrTableId = $ArrFabricPages[$VarPageId];
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarCountAllRes = $this->db->where(['referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$ArrTableId])->
                from(FABRIC_PROGRAM_ALL_JXL)->count_all_results();
                if ($VarCountAllRes >= 1) {
                    $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData,'dateupdated' =>
                        $this->mysqldatetime, 'updatedby' => $this->userid),
                        array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$ArrTableId));
                }
                else {
                    $this->db->insert(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData,'dateupdated' => $this->mysqldatetime,
                        'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$ArrTableId));
                }
                echo json_encode(array('errCode'=>1));
            }
        }

    }

    public function fabRequirement() {
        $VarPageId = $this->router->method;
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        $this->load->view("fabric_program/fabricProgramNine", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId
            )
        );
    }

    public function saveFabRequirement() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $ArrData = $this->input->post('d');
            $ArrWhere = array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid);
            $VarCountAllRes = $this->db->where($ArrWhere)->from(PLAN_FAB_WGT_SUBTOTAL)->count_all_results();
            if($VarCountAllRes >= 1) {
                $this->db->update(PLAN_FAB_WGT_SUBTOTAL, array('jsondatagrid' => $ArrData,'dateupdated' =>
                    $this->mysqldatetime, 'updatedby' => $this->userid),
                    array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
            }
            else {
                $this->db->insert(PLAN_FAB_WGT_SUBTOTAL, array('jsondatagrid' => $ArrData,'dateupdated' => $this->mysqldatetime,
                    'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
            }
        }
        echo json_encode(array('errCode'=>1));
    }

    public function singleDyeingBath() {
        $VarPageId = $this->router->method;
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        $this->load->view("fabric_program/singleDyeingBath", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId
            )
        );
    }

    public function saveTenJxl() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarCountAllRes = $this->db->where(['referenceid' => $VarEnquiryId, 'companyid' => $this->companyid])->
                from(SINGLE_DYE_BATH)->count_all_results();
                if ($VarCountAllRes >= 1) {
                    $this->db->update(SINGLE_DYE_BATH, array('jsondatagrid' => $jsonData,'dateupdated' =>
                        $this->mysqldatetime, 'updatedby' => $this->userid),
                        array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                }
                else {
                    $this->db->insert(SINGLE_DYE_BATH, array('jsondatagrid' => $jsonData,'dateupdated' => $this->mysqldatetime,
                        'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                }
                echo json_encode(array('errCode'=>1));
            }
        }
    }

    public function eleven() {
        $VarPageId = $this->router->method;
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        $this->load->view("fabric_program/fabricProgramEleven", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId
            )
        );
    }

    public function saveElevenJxl() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarCountAllRes = $this->db->where(['referenceid' => $VarEnquiryId, 'companyid' => $this->companyid])->
                from(YARN_DYE_STRIPS)->count_all_results();
                if ($VarCountAllRes >= 1) {
                    $this->db->update(YARN_DYE_STRIPS, array('jsondatagrid' => $jsonData,'dateupdated' =>
                        $this->mysqldatetime, 'updatedby' => $this->userid),
                        array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                }
                else {
                    $this->db->insert(YARN_DYE_STRIPS, array('jsondatagrid' => $jsonData,'dateupdated' => $this->mysqldatetime,
                        'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                }
                echo json_encode(array('errCode'=>1));
            }
        }

    }

    public function yarnDyeingStrips() {
        $VarPageId = $this->router->method;
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        $this->load->view("fabric_program/yarnDyeingStrips", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId
            )
        );
    }

    public function saveJacquard() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $ArrWhere = array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid);
                $VarCountAllRes = $this->db->where($ArrWhere)->from(YARN_DYEING_JACQUARD)->count_all_results();

                if ($VarCountAllRes >= 1) {
                    $this->db->update(YARN_DYEING_JACQUARD, array('jsondatagrid' => $jsonData,'dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid),
                        array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                }
                else {
                    $this->db->insert(YARN_DYEING_JACQUARD, array('jsondatagrid' => $jsonData,'dateupdated' => $this->mysqldatetime,
                        'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                }
                echo json_encode(array('errCode'=>1));
            }
        }
    }

    public function yarnDyeingJacquard() {
        $VarPageId = $this->router->method;
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        $this->load->view("fabric_program/yarnDyeingJacquard", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId
            )
        );
    }

    public function thirteen() {
        $VarPageId = $this->router->method;
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        $this->load->view("fabric_program/fabricProgramThirteen", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId
            )
        );
    }

    public function saveYarnProgram() {
            $VarFrom = $this->input->post('rFrom');
            if ($VarFrom == 1) {
                $VarEnquiryId = $this->input->post('enqId');
                if ($VarEnquiryId >= 1) {
                    $jsonData = xssclean($this->input->post('d'));
                    $ArrWhere = array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid);
                    $VarCountAllRes = $this->db->where($ArrWhere)->from(FP_YARN_PROGRAM)->count_all_results();

                    if ($VarCountAllRes >= 1) {
                        $this->db->update(FP_YARN_PROGRAM, array('jsondatagrid' => $jsonData,'dateupdated' =>
                            $this->mysqldatetime, 'updatedby' => $this->userid),
                            array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                    }
                    else {
                        $this->db->insert(FP_YARN_PROGRAM, array('jsondatagrid' => $jsonData,'dateupdated' => $this->mysqldatetime,
                            'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                    }

                    /*$VarCountAllRes = $this->db->where(['referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$ArrTableId[1]])->
                    from(FABRIC_PROGRAM_ALL_JXL)->count_all_results();
                    if ($VarCountAllRes >= 1) {
                        $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonDataTwo,'dateupdated' =>
                            $this->mysqldatetime, 'updatedby' => $this->userid),
                            array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$ArrTableId[1]));
                    }
                    else {
                        $this->db->insert(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonDataTwo,'dateupdated' => $this->mysqldatetime,
                            'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$ArrTableId[1]));
                    }*/
                    echo json_encode(array('errCode'=>1));
                }
            }
    }


    public function fabKnittingProgram() {
        $VarPageId = $this->router->method;
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        $this->load->view("fabric_program/fabricKnittingProgram", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId
            )
        );
    }

    public function saveKnittingProgram() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $ArrWhere = array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid);
                $VarCountAllRes = $this->db->where($ArrWhere)->from(FP_CONS_KNITTING_PROGRAM)->count_all_results();
                if ($VarCountAllRes >= 1) {
                    $this->db->update(FP_CONS_KNITTING_PROGRAM, array('jsondatagrid' => $jsonData,'dateupdated' =>
                        $this->mysqldatetime, 'updatedby' => $this->userid),
                        array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                }
                else {
                    $this->db->insert(FP_CONS_KNITTING_PROGRAM, array('jsondatagrid' => $jsonData,'dateupdated' => $this->mysqldatetime,
                        'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
                }
                echo json_encode(array('errCode'=>1));
            }
        }
    }

    public function labTesting() {
        $VarPageId = $this->router->method;
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        $this->load->view("fabric_program/labTesting", array(
                'VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId
            )
        );

    }

    public function saveLabTesting() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $ArrFabricPages = unserialize(ARR_FABRIC_PROGRAM_PAGES);
            $VarPageId = $this->input->post('pid');
            $jsonData = xssclean($this->input->post('d'));
            $ArrTableId = $ArrFabricPages[$VarPageId];
            if ($VarEnquiryId >= 1) {
                $VarCountAllRes = $this->db->where(['referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$ArrTableId])->
                from(FABRIC_PROGRAM_ALL_JXL)->count_all_results();
                if ($VarCountAllRes >= 1) {
                    $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData,'dateupdated' =>
                        $this->mysqldatetime, 'updatedby' => $this->userid),
                        array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$ArrTableId));
                }
                else {
                    $this->db->insert(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData,'dateupdated' => $this->mysqldatetime,
                        'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$ArrTableId));
                }
                echo json_encode(array('errCode'=>1));
            }
        }
    }

    public function extLabTesting() {
        $VarPageId = $this->router->method;
        $VarHashEnquiryId = $this->uri->segment(3);
        $VarEnquiryId = base64_decode(urldecode($VarHashEnquiryId));
        $this->load->view("fabric_program/externalLabTesting",
            array('VarEnquiryId' => $VarEnquiryId,'VarHashEnquiryId'=>$VarHashEnquiryId,'VarPageId'=>$VarPageId)
        );

    }

    public function saveExtLabTesting() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $ArrFabricPages = unserialize(ARR_FABRIC_PROGRAM_PAGES);
            $VarPageId = $this->input->post('pid');
            $jsonData = xssclean($this->input->post('d'));
            $ArrTableId = $ArrFabricPages[$VarPageId];
            if ($VarEnquiryId >= 1) {
                $VarCountAllRes = $this->db->where(['referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$ArrTableId])->
                from(FABRIC_PROGRAM_ALL_JXL)->count_all_results();
                if ($VarCountAllRes >= 1) {
                    $this->db->update(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData,'dateupdated' =>
                        $this->mysqldatetime, 'updatedby' => $this->userid),
                        array('referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$ArrTableId));
                }
                else {
                    $this->db->insert(FABRIC_PROGRAM_ALL_JXL, array('jsondatagrid' => $jsonData,'dateupdated' => $this->mysqldatetime,
                        'updatedby' => $this->userid,'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid,'tableid'=>$ArrTableId));
                }
                echo json_encode(array('errCode'=>1));
            }
        }
    }
}