<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Billofmaterials extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->helper('xssclean');
        $this->load->model(CNFCOMPANY . "orderentrymodel");
        $this->load->model(CNFCOMPANY . "bommodel");
        $this->load->model("commonmodel");
        $VarUserInfo = fnGetUserLoggedInfo(1);
        $this->companyid = $VarUserInfo['companyid'];
        $this->userid = $VarUserInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $ArrUnitOfMeasure = unserialize(ARRUNITOFMEASURE);
        foreach ($ArrUnitOfMeasure as $uofmitem) {
            $this->unitofmeasure[] = $uofmitem;
        }
        $this->orderEntryPages = unserialize(ARRORDERENTRYPAGES);
        $this->saveAccess = true;
        if ($VarUserInfo['usertype'] != '3'  && $VarUserInfo['usertype'] != '15') {
            $this->saveAccess = false;
        }
    }
    public function main() {
        $this->load->view( CNFCOMPANY."bill_of_materials/main", array());

    }

    public function article_1() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
            $ArrFinalSizes = array('All');
            if ($VarEnquiryId >= 1) {
                $jsonFromNewFourthTbl = '';
                $jsonFromNewFourthTblRes = $this->orderentrymodel->getFromNewFourthTbl($VarEnquiryId, $this->companyid);
                if (empty($jsonFromNewFourthTblRes->jsondatagrid)) {
                    die('No data');
                } else {
                    $jsonFromNewFourthTbl = $jsonFromNewFourthTblRes->jsondatagrid;
                }
                $ArrBomArticleRes = $this->bommodel->getBomArticle($VarEnquiryId, $this->companyid,$VarArticleId = 1);
                if(!empty($ArrBomArticleRes)) {
                    $jsonFromBomArticle = $ArrBomArticleRes[0]['jsondatagrid'];
                    $VarRemarks = $ArrBomArticleRes[0]['remarks'];
                }
                else {
                    $jsonFromBomArticle = '';
                    $VarRemarks = '';
                }
                if(!empty($ArrSizeChartData->sizechartvalue)) {
                    $ArrFinalSizes = explode(',',$ArrSizeChartData->sizechartvalue);
                }
                $ArrFinalSizes[] = "All";
                echo json_encode(
                    array('jsonFromBomArticle' => $jsonFromBomArticle, 'jsonFromNewFourthTbl' => $jsonFromNewFourthTbl,
                        'GlbGarmentSizes' => $ArrFinalSizes,'remarks'=>$VarRemarks
                    )
                );
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $ArrPonumbersRes = $this->orderentrymodel->getAllPoNumber($VarEnquiryId, $this->companyid);
            $ArrSizeSpecCodeRes = $this->orderentrymodel->getAllSizeSpecCode($VarEnquiryId, $this->companyid);
            if($ArrSizeSpecCodeRes) {
                $ArrSizeSpecCode = $ArrPonumbers = $ArrBom = array();
                foreach ($ArrPonumbersRes as $item) {
                    $ArrPonumbers[] = $item;
                }
                foreach ($ArrSizeSpecCodeRes as $item) {
                    $ArrSizeSpecCode[] = $item;
                }
                $ArrBomArticle1 = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BOM, 'id,bomitemdesc,bomblend,content,material',
                    array('status' => "1", 'companyid' => $this->companyid,'articletype'=>1));
                if (count($ArrBomArticle1) >= 1) {
                    foreach ($ArrBomArticle1 as $key => $item) {
                        $ArrBom[] = $item['bomitemdesc'] . ' / ' . $item['bomblend'] . ' / ' . $item['content'] . ' / ' . $item['material'];
                    }
                }
                $ArrData['ArrUnitMeasure'] = json_encode($this->unitofmeasure);
                $ArrData['ArrPonumbers'] = json_encode($ArrPonumbers);
                $ArrData['ArrSizeSpecCode'] = json_encode($ArrSizeSpecCode);
                $ArrData['ArrBom'] = json_encode($ArrBom);
                $this->load->view( CNFCOMPANY."bill_of_materials/bom_article1", $ArrData);
            }
            else {
                die('No data');
            }
            //echo '<pre>'; print_r($ArrSizeSpecCodeRes); die('die');

        }
    }

    public function consolidated_1() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $ArrBomArticleRes = $this->bommodel->getBomArticle($VarEnquiryId, $this->companyid,$VarArticleId = 1);
                if(!empty($ArrBomArticleRes)) {
                    $jsonFromBomArticle = $ArrBomArticleRes[0]['jsondatagrid'];
                }
                else {
                    $jsonFromBomArticle = '';
                }
                $ArrBom1_consolidated = $this->bommodel->getConsolidated($VarEnquiryId, $this->companyid, 1);
                if(!empty($ArrBom1_consolidated)) {
                    $jsonBomConsolidated = $ArrBom1_consolidated[0]['jsondatagrid'];
                    $VarRemarks = $ArrBom1_consolidated[0]['remarks'];
                }
                else {
                    $jsonBomConsolidated = '';
                    $VarRemarks = '';
                }
                echo json_encode(array('jsonBomConsolidated' => $jsonBomConsolidated,
                    'jsonFromBomArticle' => $jsonFromBomArticle,'remarks'=>$VarRemarks));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $this->load->view(CNFCOMPANY . "bill_of_materials/bom1_consolidated", $ArrData);
        }
    }

    public function saveBomArticle() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $VarArticleId = $this->input->post('aid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarRemarks = xssclean($this->input->post('e'));
                $ArrRes = $this->bommodel->saveBomArticle($jsonData, $VarEnquiryId, $this->companyid,
                    $this->mysqldatetime, $this->userid, $VarArticleId,$VarRemarks);
                if ($ArrRes) {
                    echo json_encode(array('errCode' => 1));
                } else {
                    echo json_encode(array('errCode' => -1));
                }
            }
        }
    }

    public function saveConsolidated() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $VarArticleId = $this->input->post('aid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarRemarks = xssclean($this->input->post('e'));
                $ArrRes = $this->bommodel->saveBomConsolidated($jsonData, $VarEnquiryId, $this->companyid,$this->mysqldatetime, $this->userid, $VarArticleId,$VarRemarks);
                if ($ArrRes) {
                    echo json_encode(array('errCode' => 1));
                } else {
                    echo json_encode(array('errCode' => -1));
                }
            }
        }
    }

    public function commonheaderdata($VarEnquiryId) {
        $ArrCompanyRes = $this->companymodel->fnGetCompanyInfo($this->companyid);
        $ArrEnquiryDetails = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarEnquiryId, $this->companyid);
        $ArrEnquiryDetails = @$ArrEnquiryDetails[0];
        $VarHashEnquiryId = $this->uri->segment(3);
        $ArrMerchant = $this->commonmodel->getMerchantData($this->companyid, 1, $ArrEnquiryDetails['merchantid']);
        $ArrTeam = $this->commonmodel->getTeamDetails($this->companyid, $ArrEnquiryDetails['merchantid']);
        $ArrCommonData = $this->orderentrymodel->getCommonData($VarEnquiryId, $this->companyid);
        $ArrCommonHeaderData = array(
            'companyName' => @$ArrCompanyRes[0]['companyname'], 'companyAddress' => @$ArrCompanyRes[0]['address'],
            'VarEnquiryId' => $VarEnquiryId, 'VarHashEnquiryId' => @$VarHashEnquiryId,'merchantName' => $ArrMerchant[0]['contactname'],
            'merchantMobile'=>$ArrMerchant[0]['mobile'],'merchantCode'=>$ArrMerchant[0]['code'],
            'merchantEmail'=>$ArrMerchant[0]['username'],'ArrEnquiryDetails' => $ArrEnquiryDetails,
            'ArrCommonData' => @$ArrCommonData,'ArrTeam'=>@$ArrTeam[0]
        );
        return $ArrCommonHeaderData;
    }

    public function sourcingDetailsArticle_1() {
        $VarFrom = $this->input->post('rFrom');
        $ArrBomSourcingDetails = [];
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonBomSourcingDetails = '';
                $jsonBomSourcingDetailsRes = $this->bommodel->getBomSourcingDetails($VarEnquiryId, $this->companyid, '1');
                if (!empty($jsonBomSourcingDetailsRes->jsondatagrid)) {
                    /*$jsonBomSourcingDetails = json_decode($jsonBomSourcingDetailsRes->jsondatagrid, true);
                    foreach ($jsonBomSourcingDetails as $item) {
                        $ArrBomSourcingDetails[] = array($item[0], $item[1], $item[2], $item[3], $item[4], $item[5], $item[6], $item[7], $item[8]);
                    }*/
                    $jsonBomSourcingDetails = $jsonBomSourcingDetailsRes->jsondatagrid;
                }
                $VarRemarks = '';
                if (!empty($jsonBomSourcingDetailsRes->remarks)) {
                    $VarRemarks = $jsonBomSourcingDetailsRes->remarks;
                }
                $ArrBomVendorData = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BOM_VENDOR, 'vendorname,contactpersonname,emailid,phone,mobile,gstno,iecode',
                    array('status'=>"1",'companyid' => $this->companyid));

                if(!empty($ArrBomVendorData)) {
                    foreach($ArrBomVendorData as $item) {
                        $ArrBomVendor[] = $item['vendorname'];
                        $ArrBomVendorGst[$item['vendorname']] = $item['gstno'];
                        $ArrBomVendorIecode[$item['vendorname']] = $item['iecode'];
                        $ArrBomVendorContact[$item['vendorname']] = $item['contactpersonname'] . ' / ' . $item['emailid'] . ' / '.$item['phone'].' / '.$item['mobile'];
                    }
                }
                $ArrBomConsolidated = [];
                $jsonBomConsolidatedArt1 = $this->bommodel->getConsolidated($VarEnquiryId, $this->companyid, 1);
                if (!empty($jsonBomConsolidatedArt1[0])) {
                    $ArrBomConsolidatedArt1 = json_decode($jsonBomConsolidatedArt1[0]['jsondatagrid'], true);
                    foreach ($ArrBomConsolidatedArt1 as $bomConsArt1) {
                        $ArrBomConsolidated[] = $bomConsArt1[0];

                    }
                    $ArrBomConsolidated = array_unique($ArrBomConsolidated);
                }
                echo json_encode(array('bomSourcingDetailArticle1' => $jsonBomSourcingDetails,'ArrBomVendor'=>$ArrBomVendor,
                    'ArrBomVendorGst'=>$ArrBomVendorGst,'ArrBomVendorIecode'=>$ArrBomVendorIecode,'ArrBomVendorContact'=>$ArrBomVendorContact,
                    'remarks'=>$VarRemarks,'ArrBomConsolidated'=>$ArrBomConsolidated));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $this->load->view( CNFCOMPANY."bill_of_materials/sourcingDetailsArticle1", $ArrData);
        }


    }

    public function saveSourcingDetails() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $VarArticleId = $this->input->post('aid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarRemarks = xssclean($this->input->post('e'));
                $bomSourceRes = $this->bommodel->saveBomSourcingDetails($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime,
                    $this->userid, $VarArticleId,$VarRemarks);

                if ($bomSourceRes) {
                    echo json_encode(array('errcode' => 1));
                } else {
                    echo json_encode(array('errcode' => -1));
                }
            }
        }
    }

    public function article_2() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
            $ArrFinalSizes = array('All');
            if ($VarEnquiryId >= 1) {
                $jsonFromNewFourthTblRes = $this->orderentrymodel->getFromNewFourthTbl($VarEnquiryId, $this->companyid);
                if (empty($jsonFromNewFourthTblRes->jsondatagrid)) {
                    $jsonFromNewFourthTbl = NULL;
                } else {
                    $jsonFromNewFourthTbl = $jsonFromNewFourthTblRes->jsondatagrid;
                }
                $ArrBomArticleRes = $this->bommodel->getBomArticle($VarEnquiryId, $this->companyid,$VarArticleId = 2);
                if(!empty($ArrBomArticleRes)) {
                    $jsonFromBomArticle = $ArrBomArticleRes[0]['jsondatagrid'];
                    $VarRemarks = $ArrBomArticleRes[0]['remarks'];
                }
                else {
                    $jsonFromBomArticle = '';
                    $VarRemarks = '';
                }
                if(!empty($ArrSizeChartData->sizechartvalue)) {
                    $ArrFinalSizes = explode(',',$ArrSizeChartData->sizechartvalue);
                }
                $ArrFinalSizes[] = "All";
                echo json_encode(
                    array('jsonFromBomArticle' => $jsonFromBomArticle, 'jsonFromNewFourthTbl' => $jsonFromNewFourthTbl,
                        'GlbGarmentSizes' => $ArrFinalSizes,'remarks'=>$VarRemarks
                    )
                );
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $ArrPonumbersRes = $this->orderentrymodel->getAllPoNumber($VarEnquiryId, $this->companyid);
            $ArrSizeSpecCodeRes = $this->orderentrymodel->getAllSizeSpecCode($VarEnquiryId, $this->companyid);
            $ArrSizeSpecCode = $ArrPonumbers = $ArrBom = array();
            foreach ($ArrPonumbersRes as $item) {
                $ArrPonumbers[] = $item;
            }
            foreach ($ArrSizeSpecCodeRes as $item) {
                $ArrSizeSpecCode[] = $item;
            }
            $ArrBomArticle1 = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BOM, 'id,bomitemdesc,bomblend,content,material',
                array('status' => "1", 'companyid' => $this->companyid,'articletype'=>2));
            if (count($ArrBomArticle1) >= 1) {
                foreach ($ArrBomArticle1 as $key => $item) {
                    $ArrBom[] = $item['bomitemdesc'] . ' / ' . $item['bomblend'] . ' / ' . $item['content'] . ' / ' . $item['material'];
                }
            }
            $ArrData['ArrUnitMeasure'] = json_encode($this->unitofmeasure);
            $ArrData['ArrPonumbers'] = json_encode($ArrPonumbers);
            $ArrData['ArrSizeSpecCode'] = json_encode($ArrSizeSpecCode);
            $ArrData['ArrBom'] = json_encode($ArrBom);
            //bomdetails_eleventhtbl_artcltwo
            $this->load->view( CNFCOMPANY."bill_of_materials/bom_article2", $ArrData);
        }
    }

    public function consolidated_2() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $ArrBomArticleRes = $this->bommodel->getBomArticle($VarEnquiryId, $this->companyid,$VarArticleId = 2);
                if(!empty($ArrBomArticleRes)) {
                    $jsonFromBomArticle = $ArrBomArticleRes[0]['jsondatagrid'];
                }
                else {
                    $jsonFromBomArticle = '';
                }
                $ArrBom1_consolidated = $this->bommodel->getConsolidated($VarEnquiryId, $this->companyid, 2);
                if(!empty($ArrBom1_consolidated)) {
                    $jsonBomConsolidated = $ArrBom1_consolidated[0]['jsondatagrid'];
                    $VarRemarks = $ArrBom1_consolidated[0]['remarks'];
                }
                else {
                    $jsonBomConsolidated = '';
                    $VarRemarks = '';
                }
                echo json_encode(array('jsonBomConsolidated' => $jsonBomConsolidated,
                    'jsonFromBomArticle' => $jsonFromBomArticle,'remarks'=>$VarRemarks));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $this->load->view(CNFCOMPANY . "bill_of_materials/bom2_consolidated", $ArrData);
        }
    }

    public function sourcingDetailsArticle_2() {
        $VarFrom = $this->input->post('rFrom');
        $ArrBomSourcingDetails = [];
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonBomSourcingDetails = '';
                $jsonBomSourcingDetailsRes = $this->bommodel->getBomSourcingDetails($VarEnquiryId, $this->companyid, '2');
                if (!empty($jsonBomSourcingDetailsRes->jsondatagrid)) {
                    /*$jsonBomSourcingDetails = json_decode($jsonBomSourcingDetailsRes->jsondatagrid, true);
                    foreach ($jsonBomSourcingDetails as $item) {
                        $ArrBomSourcingDetails[] = array($item[0], $item[1], $item[2], $item[3], $item[4], $item[5], $item[6], $item[7], $item[8]);
                    }*/
                    $jsonBomSourcingDetails = $jsonBomSourcingDetailsRes->jsondatagrid;
                }
                $VarRemarks = '';
                if (!empty($jsonBomSourcingDetailsRes->remarks)) {
                    $VarRemarks = $jsonBomSourcingDetailsRes->remarks;
                }
                $ArrBomVendorData = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BOM_VENDOR, 'vendorname,contactpersonname,emailid,phone,mobile,gstno,iecode',
                    array('status'=>"1",'companyid' => $this->companyid));

                if(!empty($ArrBomVendorData)) {
                    foreach($ArrBomVendorData as $item) {
                        $ArrBomVendor[] = $item['vendorname'];
                        $ArrBomVendorGst[$item['vendorname']] = $item['gstno'];
                        $ArrBomVendorIecode[$item['vendorname']] = $item['iecode'];
                        $ArrBomVendorContact[$item['vendorname']] = $item['contactpersonname'] . ' / ' . $item['emailid'] . ' / '.$item['phone'].' / '.$item['mobile'];
                    }
                }
                $ArrBomConsolidated = [];
                $jsonBomConsolidatedArt1 = $this->bommodel->getConsolidated($VarEnquiryId, $this->companyid, 1);
                if (!empty($jsonBomConsolidatedArt1[0])) {
                    $ArrBomConsolidatedArt1 = json_decode($jsonBomConsolidatedArt1[0]['jsondatagrid'], true);
                    foreach ($ArrBomConsolidatedArt1 as $bomConsArt1) {
                        $ArrBomConsolidated[] = $bomConsArt1[0];

                    }
                    $ArrBomConsolidated = array_unique($ArrBomConsolidated);
                }
                echo json_encode(array('bomSourcingDetailArticle2' => $jsonBomSourcingDetails,'ArrBomVendor'=>$ArrBomVendor,
                    'ArrBomVendorGst'=>$ArrBomVendorGst,'ArrBomVendorIecode'=>$ArrBomVendorIecode,'ArrBomVendorContact'=>$ArrBomVendorContact,
                    'remarks'=>$VarRemarks,'ArrBomConsolidated'=>$ArrBomConsolidated));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $this->load->view( CNFCOMPANY."bill_of_materials/sourcingDetailsArticle2", $ArrData);
        }
    }

    public function samplingAndApprovalDetails_1() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonSamplingAndApprovalDetails = '';
                $VarRemarks = '';
                $samplingAndApprovalDetailsRes = $this->bommodel->getSamplingAndApprovalDetails($VarEnquiryId, $this->companyid, '1');
                if (!empty($samplingAndApprovalDetailsRes)) {
                    $jsonSamplingAndApprovalDetails = $samplingAndApprovalDetailsRes->jsondatagrid;
                    $VarRemarks = $samplingAndApprovalDetailsRes->remarks;
                }

                    //[["Main Label / (%) 5 % / Content 1 / Material 1","All/All","BOM-5000/BOM-5006","PTN-20001/PTN-30001","In-line","No","","","","","","",""],["Size Label / (%) 100 % / Content 2 / Material 2","All/All","BOM-5001/BOM-5002/BOM-5003/BOM-5004/BOM-5007/BOM-5008/BOM-5009/BOM-5010","PTN-20001/PTN-30001","In-line","No","","","","","","",""],["Wash care label / (%) 100 % / Content 3 / Material 3","All/All","BOM-5005/BOM-5011","PTN-20002/PTN-30002","In-line","No","","","","","","",""]]

                echo json_encode(array('samplingAndApprovalDetails' => $jsonSamplingAndApprovalDetails,'remarks'=>$VarRemarks));
            }
        } else {
            $ArrFromBomConsolidated = [];
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $jsonBomConsolidatedArt1 = $this->bommodel->getConsolidated($VarEnquiryId, $this->companyid, 1);
            if (!empty($jsonBomConsolidatedArt1[0]['jsondatagrid'])) {
                $ArrBomConsolidatedArt1 = json_decode($jsonBomConsolidatedArt1[0]['jsondatagrid'], true);
                foreach ($ArrBomConsolidatedArt1 as $bomConsArt1) {
                    $ArrFromBomConsolidated[] = array($bomConsArt1[0], $bomConsArt1[1], $bomConsArt1[2], $bomConsArt1[3]);
                }
            }
            $ArrData['ArrFromBomConsolidated'] = json_encode($ArrFromBomConsolidated);
            $this->load->view(CNFCOMPANY . "bill_of_materials/samplingAndApprovalDetails_1", $ArrData);
        }
    }

    public function saveSamplingAndApprovalDetails() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $VarArticleId = $this->input->post('aid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarRemarks = xssclean($this->input->post('e'));
                $Res = $this->bommodel->saveSamplingAndApprovalDetails($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime, $this->userid,
                    $VarArticleId,$VarRemarks);
                if ($Res) {
                    echo json_encode(array('errCode' => 1));
                } else {
                    echo json_encode(array('errCode' => -1));
                }
            }
        }

    }

    public function samplingAndApprovalDetails_2() {
        $VarFrom = $this->input->post('rFrom');
        if ($VarFrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonSamplingAndApprovalDetails = '';
                $VarRemarks = '';
                $samplingAndApprovalDetailsRes = $this->bommodel->getSamplingAndApprovalDetails($VarEnquiryId, $this->companyid, '2');
                if (!empty($samplingAndApprovalDetailsRes->jsondatagrid))
                    $jsonSamplingAndApprovalDetails = $samplingAndApprovalDetailsRes->jsondatagrid;
                //[["Main Label / (%) 5 % / Content 1 / Material 1","All/All","BOM-5000/BOM-5006","PTN-20001/PTN-30001","In-line","No","","","","","","",""],["Size Label / (%) 100 % / Content 2 / Material 2","All/All","BOM-5001/BOM-5002/BOM-5003/BOM-5004/BOM-5007/BOM-5008/BOM-5009/BOM-5010","PTN-20001/PTN-30001","In-line","No","","","","","","",""],["Wash care label / (%) 100 % / Content 3 / Material 3","All/All","BOM-5005/BOM-5011","PTN-20002/PTN-30002","In-line","No","","","","","","",""]]

                if (!empty($samplingAndApprovalDetailsRes->remarks))
                    $VarRemarks = $samplingAndApprovalDetailsRes->remarks;
                echo json_encode(array('samplingAndApprovalDetails' => $jsonSamplingAndApprovalDetails,'remarks'=>$VarRemarks));
            }
        } else {
            $ArrFromBomConsolidated = [];
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $jsonBomConsolidatedArt1 = $this->bommodel->getConsolidated($VarEnquiryId, $this->companyid, 2);
            if (!empty($jsonBomConsolidatedArt1[0]['jsondatagrid'])) {
                $ArrBomConsolidatedArt1 = json_decode($jsonBomConsolidatedArt1[0]['jsondatagrid'], true);
                foreach ($ArrBomConsolidatedArt1 as $bomConsArt1) {
                    $ArrFromBomConsolidated[] = array($bomConsArt1[0], $bomConsArt1[1], $bomConsArt1[2], $bomConsArt1[3]);
                }
            }
            $ArrData['ArrFromBomConsolidated'] = json_encode($ArrFromBomConsolidated);
            $this->load->view(CNFCOMPANY . "bill_of_materials/samplingAndApprovalDetails_2", $ArrData);
        }
    }

}