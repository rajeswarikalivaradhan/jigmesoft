<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('xssclean');
        //fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->usertype = $ArrUserLoggedInfo['usertype'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->load->model("commonmodel");
        $this->load->model(CNFCOMPANY . "mcadrequestmodel");
        $this->load->model(CNFCOMPANY . "msamplerequestmodel");
        $this->load->model(CNFCOMPANY . "orderentrymodel");
        $this->limit = LIMITPERPAGE;
    }
    public function index() {
        $ArrProfileInfo = fnGetUserLoggedInfo(1);
        //var_dump($ArrProfileInfo);
        if ($ArrProfileInfo['usertype'] == 0) {
           //commented for showing business admin $this->load->view('cadmin/dashboard');
           $this->load->view('badmin/dashboard');
        } else if ($ArrProfileInfo['usertype'] == 1) {
            if(!empty($ArrProfileInfo['subscriber_id'])){
            $this->load->view('badmin/subscription/dashboard'); 
            }else{
              $this->load->view('company/dashboard');   
            }
        } else if ($ArrProfileInfo['usertype'] == 2) {
            $this->load->view('management/mgmtdashboard');
        } else if ($ArrProfileInfo['usertype'] == 3 || $ArrProfileInfo['usertype'] == 15) {
            $this->load->view('merchant/merchantdashboard');
        } else if ($ArrProfileInfo['usertype'] == 4) {
            $this->load->view('cad/dashboard');
        } else if ($ArrProfileInfo['usertype'] == 5) {
            $this->load->view('sampling/userdashboard');
        } else if ($ArrProfileInfo['usertype'] == 6) {
            $this->load->view('fabric/userdashboard');
        } else if ($ArrProfileInfo['usertype'] == 7) {
            $this->load->view('purchase/userdashboard');
        } else if ($ArrProfileInfo['usertype'] == 8) {
            $this->load->view('stores/userdashboard');
        } else if ($ArrProfileInfo['usertype'] == 9) {
            $this->load->view('production/userdashboard');
        } else if ($ArrProfileInfo['usertype'] == 10) {
            $this->load->view('lab/userdashboard');
        } else if ($ArrProfileInfo['usertype'] == 11) {
            $this->load->view('qa/userdashboard');
        } else if ($ArrProfileInfo['usertype'] == 12) {
            $this->load->view('finance/userdashboard');
        } else if ($ArrProfileInfo['usertype'] == 13) {
            $this->load->view('docandloc/userdashboard');
        } else if ($ArrProfileInfo['usertype'] == 14) {
            $this->load->view('yarnstore/userdashboard');
        } else if ($ArrProfileInfo['usertype'] == 16) {
            $this->load->view('badmin/dashboard');
        }
        

    }
    function allqueuelist() {
        $ArrProfileInfo = fnGetUserLoggedInfo(1);
        if (xssclean($this->input->post('rFrom')) == 1) {
            $this->load->model('queuesmodel');
            $ArrList = $this->queuesmodel->getQueueListDataTables();
            //echo '<pre>'; print_r($ArrList); die('die');
            $data = array();
            $ArrStatus = unserialize(ARRSTATUS);
            $ArrORDERENQUIRYSTATUS = unserialize(ORDERENQUIRYSTATUS);
            $ArrRequestType = unserialize(ARRREQUESTTYPE);
            $ArrBomRequirement = unserialize(ARRBOMREQUIREMENT);
            $QueueListDetailPageUrl = '';
            foreach ($ArrList as $Obj) {
                $row = array();
                $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->id . '">';
                if ($Obj->requestlisttypeid == 1) {
                    $QueueListDetailPageUrl = base_url('caduser/cadqueuelistdetail') . '/' . urlencode(base64_encode($Obj->id));
                    $row[] = '<a href="' . $QueueListDetailPageUrl . '">' . $Obj->isriorcode . '</a>';
                    $row[] = $Obj->brandname . ' / ' . $Obj->buyername;
                    $row[] = $Obj->queueno;
                    $row[] = 'CAD';
                    if (empty($Obj->jsondatagrid)) {
                        $row[] = '-';
                    } else {
                        $jsonGrid = json_decode($Obj->jsondatagrid, true);
                        $row[] = $jsonGrid[0][4];
                    }
                } elseif ($Obj->requestlisttypeid == 2) {
                    $QueueListDetailPageUrl = base_url('samplinguser/queuelistdetail') . '/' . urlencode(base64_encode($Obj->id));
                    $row[] = '<a href="' . $QueueListDetailPageUrl . '">' . $Obj->isriorcode . '</a>';
                    $row[] = $Obj->brandname . ' / ' . $Obj->buyername;
                    $row[] = $Obj->queueno;
                    $row[] = 'SAMPLE';
                    if (empty($Obj->jsondatagrid)) {
                        $row[] = '-';
                    } else {
                        $jsonGrid = json_decode($Obj->jsondatagrid, true);
                        $row[] = $jsonGrid[0][5];
                    }
                } elseif ($Obj->requestlisttypeid == 3) {
                    $QueueListDetailPageUrl = base_url('bompurchaseindent/preprarebompind') . '/' . urlencode(base64_encode($Obj->id));
                    $row[] = '<a href="' . $QueueListDetailPageUrl . '">' . $Obj->isriorcode . '</a>';
                    $row[] = $Obj->brandname . ' / ' . $Obj->buyername;
                    $row[] = $Obj->queueno;
                    $row[] = 'BOM';
                    $row[] = $ArrBomRequirement[$Obj->requirementforbom];
                } else {
                }
                $row[] = $Obj->datecreated;
                $row[] = $Obj->cutoffdatetime;
                $row[] = $ArrRequestType[$Obj->approvaltype];
                //Note:|| $ArrProfileInfo['usertype'] == 15 condition included newly on below line
                if ($ArrProfileInfo['usertype'] == 3 || $ArrProfileInfo['usertype'] == 15) {
                    $row[] = $Obj->merchant;
                } elseif ($ArrProfileInfo['usertype'] == 4) {
                    //$row[] = $Obj->mgmt;
                    //$row[] = $Obj->merchant;
                    $row[] = $Obj->mgmt;
                } else {
                    $row[] = '-';
                }
                $row[] = $Obj->current_status;
                $row[] = $Obj->datecreated;
                $row[] = $ArrStatus[$Obj->status];
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->queuesmodel->queueCountAll(),
                "recordsFiltered" => $this->queuesmodel->queueCountFiltered(),
                "data" => $data,
            );
            echo json_encode($output);
        } else {
            $this->load->view('allqueuelist', array('usertype' => $ArrProfileInfo['usertype']));
        }
    }
    public function mgmtbompurchaseindentapprovalreq() {
        $VarRequestId = $this->uri->segment(3);
        //$HashedCadRequestId = $this->uri->segment(3);
        if ($VarRequestId >= 1) {
            $jsBPIApprovalGridData = '';
            $ArrAllReq = unserialize(ALLREQUIREMENTS);
            $ArrAllRequestData = $this->commonmodel->getbompurchaseindentapprovalRequestDetails($VarRequestId, $this->companyid);
            $ArrData['ArrBasicInfo'] = $ArrAllRequestData;
            $VarTblName = $ArrAllRequestData->bompirequestgrid_tblname;
            $VarOrderId = $ArrData['ArrBasicInfo']->orderid;
            $ArrOrderDatas = $this->orderentrymodel->getOrderDataByWip($VarOrderId, $this->companyid);
            if (empty($ArrOrderDatas)) {
                die('Order Entry not completed');
            } else {
                $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
                $ArrBB = $this->orderentrymodel->fnBuyerbyBrandId($ArrOrderEnqData[0]['brandbuyerid']);
                $ArrMerchant = $this->commonmodel->getMerchantData($this->companyid, 1);
                $ArrTeamInfo = $this->commonmodel->getTeamDetails('', $ArrOrderDatas[0]->teamid, 1);
                $ArrCompanyRes = $this->companymodel->fnGetCompanyInfo($this->companyid);
                $ArrPurpose = $this->mcadrequestmodel->getPurpose();
                $ArrData['ArrObjPurpose'] = $ArrPurpose;
                $VarMerchantInfo = $this->commonmodel->getUserInfo($ArrData['ArrBasicInfo']->merchantid);
                $ArrData['merchantname'] = $VarMerchantInfo[0]->contactname;
                $ArrData['VarRequestId'] = $VarRequestId;
                //$ArrData['HashedCadRequestId'] = $HashedCadRequestId;
                $ArrData['ArrBB'] = $ArrBB[0];
                $ArrData['ArrMerchant'] = $ArrMerchant[0];
                $ArrData['ArrTeamInfo'] = $ArrTeamInfo[0];
                $ArrData['ArrCompanyInfo'] = $ArrCompanyRes;
                $ArrData['VarOrderId'] = $VarOrderId;
                $ArrData['ArrOrderEnqData'] = $ArrOrderEnqData[0];
                $ArrData['ArrOrderDatas'] = @$ArrOrderDatas[0];
            }
            if (!empty($VarTblName)) {
                $ArrData['VarTblName'] = @$VarTblName;
                $ArrBomPurchaseIndApprRequest = $this->commonmodel->getBomPurchaseIndApprRequest($VarTblName);
                if (!empty($ArrBomPurchaseIndApprRequest)) {
                    foreach ($ArrBomPurchaseIndApprRequest as $item) {
                        //only requested rows for mgmt grid
                        if ($item['requestforapprovalcheckbox'] == 1) {
                            $ArrBomPiGrid[] = array($item['itemdesc'], $item['garmentsize'], $item['itemcode'], $item['itemcolorcode'], $item['sizeordim'], $item['unitofmeasure_1'], $item['planbomqty'],
                                $item['unitofmeasure_2'], $item['progbomqty'], $item['unitofmeasure_3'], $item['unitrate'], $item['amount'], $item['approvalstatus'], $item['approvedby']
                            );
                        }
                    }
                    if (!empty($ArrBomPiGrid)) {
                        $jsBPIApprovalGridData = json_encode($ArrBomPiGrid);
                    } else {
                        $jsBPIApprovalGridData = '';
                    }
                }
            } else {
            }
            $ArrData['jsBPIApprovalGridData'] = $jsBPIApprovalGridData;
            $this->load->view('management/mgmtbompurchaseindentapprovalreq', $ArrData);
        }
    }

    /*@TODO use below function "cmnChangeStatus" in all places instead of this function*/
    public function changeAllListActiveStatus() {
        $VarActInActiveOption = xssclean($this->input->post('cs'));
        $ArrCheckBoxId = json_decode(xssclean($this->input->post('id')), true);
        $VarTbl = xssclean($this->input->post('tblname'));
        if ($VarActInActiveOption <> '' && $VarTbl != '') {
            if ($this->commonmodel->fnChangeAllListAciveStatus($ArrCheckBoxId, $VarActInActiveOption, $VarTbl)) {
                echo json_encode(array('errcode' => 1));
                die;
            } else                 echo json_encode(array('errcode' => -1));
        }
    }

    public function cmnChangeStatus() {
        $VarWhereField = xssclean($this->input->post('idField'));
        $VarStatusValue = xssclean($this->input->post('cs'));
        $ArrCheckBoxId = json_decode(xssclean($this->input->post('ckId')), true);
        $VarTbl = xssclean($this->input->post('tblName'));
        if(!empty($VarWhereField) && !empty($ArrCheckBoxId) && !empty($VarTbl) && !empty($VarWhereField)) {
            $this->commonmodel->cmnChangeStatus($VarTbl,$ArrCheckBoxId,$VarStatusValue,$VarWhereField);
            echo json_encode(array('errCode'=>1));
        }
        else echo json_encode(array('errCode' => -1));
    }

    public function inddetailsprintformat() {
        $VarReqId = base64_decode(urldecode($this->uri->segment(3)));
        $ArrIndDetails = $this->commonmodel->getCadIndentDetails($VarReqId);
        //echo '<pre>'; print_r($ArrIndDetails); die('');
        $ArrData['ArrBasicInfo'] = @$ArrIndDetails[0];
        $this->load->view('inddetailsprintformat', $ArrData);
    }
    public function updateIndentGridInfoinReceiver() {
        $VarRfrom = xssclean($this->input->post('rfrom'));
        if ($VarRfrom == 1) {
            $VarSamRequestId = xssclean($this->input->post('srid'));
            $VarQueueNo = xssclean($this->input->post('qno'));
            $VarOrderId = xssclean($this->input->post('oid'));
            $ArrIndentId = json_decode(xssclean($this->input->post('indentid')), true);
            $VarCadIndentRefNo = $VarQueueNo . '/' . CADINDENT_REFNO_PREFIX . $VarSamRequestId;
            $VarFabIndentRefNo = $VarQueueNo . '/' . FABINDENT_REFNO_PREFIX . $VarSamRequestId;
            $VarBomIndentRefNo = $VarQueueNo . '/' . BOMINDENT_REFNO_PREFIX . $VarSamRequestId;
            $ArrMoreCadJxl = json_decode(xssclean($this->input->post('moreCadJxl')), true);
            $ArrMoreFabJxl = json_decode(xssclean($this->input->post('moreFabJxl')), true);
            $ArrMoreBomJxl = json_decode(xssclean($this->input->post('moreBomJxl')), true);
            $jsonJxlSampleReqData = xssclean($this->input->post('jxlSampleReqData'));
            $this->commonmodel->saveMoreRequestIndents($VarSamRequestId, $VarOrderId, $ArrMoreCadJxl, $ArrMoreFabJxl, $ArrMoreBomJxl, $updateFlag = 1, $ArrIndentId);
            $ArrIndentData = array('cad_mat_ind_ref_no' => $VarCadIndentRefNo, 'fab_mat_ind_ref_no' => $VarFabIndentRefNo,
                'bom_mat_ind_ref_no' => $VarBomIndentRefNo);
            $this->commonmodel->saveSampleRequestIndentDetails($ArrIndentData, $VarSamRequestId, $VarOrderId);
            echo json_encode(array('errcode' => '1', 'ru' => date_format(date_create($this->mysqldatetime), 'd-m-Y H:i:s'), 'msg' => 'Saved',
                'cadindrefno' => $VarCadIndentRefNo, 'fabindrefno' => $VarFabIndentRefNo, 'bomindrefno' => $VarBomIndentRefNo));
            die;
        }
    }

    /**
     *
     * @todo make a database table for uploads locations
     */
    /*public function commonFileUpload() {
        $VarFdrId       = xssclean($this->input->post('folderid'));
        $VarFdrName     = xssclean($this->input->post('foldername'));
        $VarUploadedFor = xssclean($this->input->post('for'));
        $VarPiPK        = xssclean($this->input->post('PiPK'));
        $VarDir         = FCPATH . UPLOADSFDR . DIRECTORY_SEPARATOR . $VarFdrName . DIRECTORY_SEPARATOR . $VarFdrId . DIRECTORY_SEPARATOR;
        $ArrExtensions  = FILE_EXTENSIONS;
        if(!empty($VarUploadedFor) && !empty($VarPiPK)) {
            $VarDir = $VarDir .$VarUploadedFor.DIRECTORY_SEPARATOR.$VarPiPK.DIRECTORY_SEPARATOR;
            if (!file_exists($VarDir)) {
                mkdir($VarDir, 0777, true);
            }
        }
        else {
            if (!file_exists($VarDir)) {
                mkdir($VarDir, 0777, true);
            }
        }
        if (isset($_FILES["myfile"])) {
            $ret = array();
            $extension = pathinfo($_FILES["myfile"]["name"], PATHINFO_EXTENSION);
            if (in_array($extension, $ArrExtensions)) {
                $fileName = time() . '_' . $_FILES["myfile"]["name"];
                if ($_FILES["myfile"]["size"] <= 5242880) {
                    if (move_uploaded_file($_FILES["myfile"]["tmp_name"], $VarDir . $fileName))
                        $ret[] = $fileName;
                } else {
                    $ret[] = 'Err';
                }
            }
            echo json_encode($ret);
        }
    }*/
    /* For Add Enquiry no user type. because we know only merchant can add enquiry
     * Removes . and space in user type from common.php
     * ut is for all except enquiry
     * bomPurIndentId is for purchase indent only in purchase dept login
    * */
    public function commonBasicFileUpload() {
        $ArrUserType = unserialize(ARRUSERTYPE);
        $VarAllReqId = xssclean($this->input->post('id'));
        $VarReqTypeFdrName = xssclean($this->input->post('folderName'));
        $VarBy = xssclean($this->input->post('by'));
        $VarUserInfo = fnGetUserLoggedInfo(1);
        $VarUserTypeId = $VarUserInfo['usertype'];
        $VarUserType = $ArrUserType[$VarUserTypeId];
        $ArrRemove = array('&','.');
        $VarUserType = str_replace($ArrRemove, '', $VarUserType);
        $VarCommonDir = UPLOADS_SLASH . $VarReqTypeFdrName . DIRECTORY_SEPARATOR . $VarAllReqId . DIRECTORY_SEPARATOR;
        /*if($VarUserTypeId == 5) {
            $VarDir         = UPLOADS_SLASH . $VarFdrName . DIRECTORY_SEPARATOR . $VarId . DIRECTORY_SEPARATOR.$userType.DIRECTORY_SEPARATOR.;
        }*/
        /*if (!empty($VarBomPurIndentId)) {
            $VarDir = $VarCommonDir . $VarUserType . DIRECTORY_SEPARATOR . $VarBomPurIndentId . DIRECTORY_SEPARATOR;
        }*/
        if ($VarBy != '' && $VarAllReqId != '') {
            $VarDir = $VarCommonDir . $VarBy . DIRECTORY_SEPARATOR;
        }
        if(empty($VarDir)) {
            $VarDir = $VarCommonDir . $VarUserType . DIRECTORY_SEPARATOR;
        }
        if (!file_exists($VarDir)) {
            mkdir($VarDir, 0777, true);
        }
        if (isset($_FILES["myFile"])) {
            $ret = array();
            $fileName = $_FILES["myFile"]["name"];
            /**MAX file size 7 MB**/
            if ($_FILES["myFile"]["size"] <= MAXUPLSIZE) {
                if (move_uploaded_file($_FILES["myFile"]["tmp_name"], $VarDir . $fileName))
                    $ret[] = $fileName;
            } else {
                $ret[] = 'Err';
            }
            echo json_encode($ret);
        }
    }
    function commonSimpleDownload() {
        $ArrUserType = unserialize(ARRUSERTYPE);
        $VarId = urldecode(base64_decode(xssclean($this->input->get('id'))));
        $fileName = urldecode((xssclean($this->input->get('fileName'))));
        $VarReqTypeFdrName = xssclean($this->input->get('folder'));
        $VarUploadedBy = xssclean($this->input->get('by'));
        if (!empty($VarUploadedBy)) {
            $VarLocation = UPLOADS_SLASH . $VarReqTypeFdrName . DIRECTORY_SEPARATOR . $VarId . DIRECTORY_SEPARATOR . $VarUploadedBy . DIRECTORY_SEPARATOR;
        }
        //$VarLocation = UPLOADS_SLASH.$VarFolder.DIRECTORY_SEPARATOR.$VarId.DIRECTORY_SEPARATOR;
        if (isset($fileName)) {
            $file = $VarLocation . $fileName;
            $filePath = str_replace("..", "", $file);
            if (file_exists($filePath)) {
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . $fileName);
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filePath));
                readfile($filePath);
                exit;
            }
        }
    }
    public function openFileInBrowser() {
        $VarFolder = xssclean($this->input->get('folder'));
        $VarId = xssclean($this->input->get('id'));
        $VarFileName = xssclean($this->input->get('fileName'));
        $VarBy = xssclean($this->input->get('by'));
        // get contents of a file into a string
        $filePath = UPLOADS_SLASH . $VarFolder . DIRECTORY_SEPARATOR . $VarId . DIRECTORY_SEPARATOR . $VarBy . DIRECTORY_SEPARATOR . $VarFileName;
        $VarContentType = mime_content_type($filePath);
        header('Content-Type:' . $VarContentType);
        readfile($filePath);
        exit;
    }

    /**
     *
     * @todo make a database table for uploads locations
     */
    /*public function cadReqCadDeptUpload() {
        $VarFdrId = xssclean($this->input->post('folderId'));
        $VarUploadedFor = xssclean($this->input->post('for'));
        if(!empty($VarFdrId)) {
            if(!empty($VarUploadedFor)) {
                $VarDir = FCPATH . UPLOADSFDR . DIRECTORY_SEPARATOR . 'cadrequest' . DIRECTORY_SEPARATOR . $VarFdrId .
                    DIRECTORY_SEPARATOR . $VarUploadedFor . DIRECTORY_SEPARATOR;
            }
        }
        if (!file_exists($VarDir)) {
            mkdir($VarDir, 0777, true);
        }
        if (isset($_FILES["myfile"])) {
            $ret = array();
            $ArrExtensions = FILE_EXTENSIONS;
            $extension = pathinfo($_FILES["myfile"]["name"], PATHINFO_EXTENSION);
            if (in_array($extension, $ArrExtensions)) {
                $fileName = time() . '_' . $_FILES["myfile"]["name"];
                //$fileName = $_FILES["myfile"]["name"];
                if ($_FILES["myfile"]["size"] <= 5242880) {
                    if (move_uploaded_file($_FILES["myfile"]["tmp_name"], $VarDir . $fileName))
                        $ret[] = $fileName;
                } else {
                    $ret[] = 'Err';
                }
            }
            echo json_encode($ret);
        }
    }*/
    /*function downloadFileFromUploads() {
        $this->load->helper('download');
        $VarPath = $this->input->get('fpath');
        force_download($VarPath);
    }*/
    public function bompurchaseindentlist() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        if ($VarFrom == 1) {
            $this->load->model('mbompurcahseindentmodel');
            $ArrList = $this->mbompurcahseindentmodel->bomPurIndentListDatatablesAjax();
            //echo '<pre>'; print_r($ArrList); die('die');
            $data = array();
            $ArrStatus = unserialize(ARRSTATUS);
            $ArrRequestType = unserialize(ARRREQUESTTYPE);
            $VarCs = '';
            $VarCs = 'Current Status';
            $ArrBomRequirement = unserialize(ARRBOMREQUIREMENT);
            //echo '<pre>'; print_r($ArrList); die('die');
            foreach ($ArrList as $Obj) {
                $row = array();
                $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->id . '">';
                $row[] = '<a href="' . base_url('bompurchaseindent/stores_bompidetails') . '/' . urlencode(base64_encode($Obj->id)) . '">' . $Obj->isriorcode . '</a>';
                $row[] = $Obj->vendorname;
                if ($this->usertype == 9) {
                    $row[] = '<a href="' . base_url('storesuser/bomitem_received_details') . '/' . urlencode(base64_encode($Obj->id)) . '">' . $Obj->queueno . '/' . $Obj->purchaseindent_no . '</a>';
                } else {
                    $row[] = '<a href="' . base_url('dashboard/bompurchaseindentdetails_cmn') . '/' . urlencode(base64_encode($Obj->id)) . '">' . $Obj->queueno . '/' . $Obj->purchaseindent_no . '</a>';
                }
                $row[] = $ArrBomRequirement[$Obj->requirementforbom];
                $row[] = $Obj->datecreated;
                $row[] = $Obj->cutoffdatetime;
                $row[] = $Obj->approvedByMgmt;
                $row[] = $Obj->contactname;
                $row[] = '<a href="javascript:void(0)">' . $VarCs . '</a>';
                $row[] = date('d-m-Y H:i:s', strtotime($Obj->dateupdated));
                $row[] = $ArrStatus[$Obj->status];
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->mbompurcahseindentmodel->countBomPurIndentListAll(),
                "recordsFiltered" => $this->mbompurcahseindentmodel->countBomPurIndentListFiltered(),
                "data" => $data,
            );
            echo json_encode($output);
        } else {
            $this->load->view('misc/allBomPurchaseindentList', array('loggedUserType' => $this->usertype));
        }
    }
    public function storesBompurchaseIndentAppr() {
        $VarRequestId = base64_decode(urldecode($this->uri->segment(3)));
        $HashedRequestId = $this->uri->segment(3);
        if ($VarRequestId >= 1) {
            $ArrAllRequestData = $this->commonmodel->getAllRequestDetails($VarRequestId, $this->companyid);
            $ArrBomPurIndApprovalReq = $this->commonmodel->getBomPurchaseApprRequest($VarRequestId, $this->companyid);
            $VarUnitMeasure = $ArrNewBomReqGrid = array();
            $NewBomReqGridId = 0;
            $ArrData['VarReqapprMgmtName'] = '';
            if (empty($ArrBomPurIndApprovalReq[0]->bompurindapprreqgrid)) {
                $ArrBomrequestgrid = json_decode($ArrAllRequestData->bomrequestgrid);
                foreach ($ArrBomrequestgrid as $bomreqgrid) {
                    unset($bomreqgrid[5]);
                    unset($bomreqgrid[6]);
                    $ArrNewBomReqGridValues[] = array_values($bomreqgrid);
                }
                $ArrNewBomReqGrid = json_encode($ArrNewBomReqGridValues);
            } else {
                $ArrNewBomReqGrid = $ArrBomPurIndApprovalReq[0]->bompurindapprreqgrid;
                $NewBomReqGridId = $ArrBomPurIndApprovalReq[0]->id;
                /*if(!empty($ArrBomPurIndApprovalReq[0]->mgmtid)) {
                    $VarMgmtInfo               = $this->commonmodel->getUserInfo($ArrBomPurIndApprovalReq[0]->mgmtid);
                    $ArrData['VarReqapprMgmtName']       = $VarMgmtInfo[0]->contactname;
                }*/
            }
            $ArrData['ArrBasicInfo'] = $ArrAllRequestData;
            $VarOrderId = $ArrData['ArrBasicInfo']->orderid;
            $ArrOrderDatas = $this->orderentrymodel->getOrderDataByWip($VarOrderId, $this->companyid);
            if (empty($ArrOrderDatas)) {
                die('Order Entry not completed');
            } else {
                $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
                $ArrBB = $this->orderentrymodel->fnBuyerbyBrandId($ArrOrderEnqData[0]['brandbuyerid']);
                $ArrMerchant = $this->commonmodel->getMerchantData($this->companyid, 1);
                $ArrTeamInfo = $this->commonmodel->getTeamDetails('', $ArrOrderDatas[0]->teamid, 1);
                $ArrCompanyRes = $this->companymodel->fnGetCompanyInfo($this->companyid);
                $ArrPurpose = $this->mcadrequestmodel->getPurpose();
                $ArrData['ArrObjPurpose'] = $ArrPurpose;
                $VarMerchantInfo = $this->commonmodel->getUserInfo($ArrData['ArrBasicInfo']->merchantid);
                $ArrData['merchantname'] = $VarMerchantInfo[0]->contactname;
                $ArrData['VarId'] = $VarRequestId;
                $ArrData['HashedCadRequestId'] = $HashedRequestId;
                $ArrData['ArrBB'] = $ArrBB[0];
                $ArrData['ArrMerchant'] = $ArrMerchant[0];
                $ArrData['ArrTeamInfo'] = $ArrTeamInfo[0];
                $ArrData['ArrCompanyInfo'] = $ArrCompanyRes;
                $ArrData['ArrOrderEnqData'] = $ArrOrderEnqData[0];
                $ArrData['ArrOrderDatas'] = @$ArrOrderDatas[0];
                $ArrData['ArrNewBomReqGrid'] = $ArrNewBomReqGrid;
                $ArrData['ArrUnitofmeasure'] = json_encode($VarUnitMeasure);
                $ArrData['NewBomReqGridId'] = $NewBomReqGridId;
                $ArrMgmtGridData = $this->commonmodel->fnGetAllTableInfo(KN_BOMPURCHASEINDENT_MGMT, 'mgmtselected,mgmtid',
                    array('companyid' => $this->companyid, 'bompurchaseindreqid ' => $NewBomReqGridId), 3);
                foreach ($ArrMgmtGridData as $arrMgmtGridDatum) {
                    if (!empty($arrMgmtGridDatum)) {
                        $VarMgmtInfo = $this->commonmodel->getUserInfo($arrMgmtGridDatum['mgmtid']);
                        $ArrData['VarReqapprMgmtName'] = $VarMgmtInfo[0]->contactname;
                        $ArrData['ArrMgmtGridData'] = json_encode(unserialize($arrMgmtGridDatum['mgmtselected']));
                    } else {
                    }
                }
                //echo '<pre>'; print_r($ArrMgmtGridData); die('');
            }
            $this->load->view('stores/bompurchaseindentapproval', $ArrData);
        }
    }
    public function bompiprocesstracking() {
        $VarThirdUriSegmentBpiInvId = $this->uri->segment('3');
        $VarSecondUriSegmentBpiReqId = $this->uri->segment('4');
        if (isset($_COOKIE['knit20_oid'])) {
            $VarOrderId = $_COOKIE['knit20_oid'];
            if ($VarOrderId > 0) {
                $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
                $ArrData['VarCommonOrderEntryInfo'] = $VarCommonOrderEntryInfo;
                $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
                $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
                $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
                $ArrData['ArrOrderEnqData'] = $VarCommonOrderEntryInfo['ArrOrderEnqData'];
                $ArrData['ArrBB'] = $VarCommonOrderEntryInfo['ArrBB'];
                $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
                //echo '<pre>'; print_r($ArrData); die('');
                $this->load->view('purchase/bompiprocesstracking', $ArrData);
            }
        } else {
            redirect(base_url('dashboard/bompurchaseindentlist'));
        }
    }
    public function updateBompiprocesstracking() {
        $Varbom_pip_tvenenqinidate = date('Y-m-d', strtotime(xssclean($this->input->post('bom_pip_tvenenqinidate'))));
        $Varbom_pip_tproformano = xssclean($this->input->post('bom_pip_tproformano'));
        $Varbom_pip_tproformadate = date('Y-m-d', strtotime(xssclean($this->input->post('bom_pip_tproformadate'))));
        $Varbom_pip_tsuppleadtime = xssclean($this->input->post('bom_pip_tsuppleadtime'));
        $Varbom_pip_tadvpayreqdt = date('Y-m-d H:i:s', strtotime(xssclean($this->input->post('bom_pip_tadvpayreqdt'))));
        $VarAllPostData = array(
            'bom_pip_tvenenqinidate' => $Varbom_pip_tvenenqinidate,
            'bom_pip_tproformano' => $Varbom_pip_tproformano,
            'bom_pip_tproformadate' => $Varbom_pip_tproformadate,
            'bom_pip_tsuppleadtime' => $Varbom_pip_tsuppleadtime,
            'bom_pip_tadvpayreqdt' => $Varbom_pip_tadvpayreqdt
        );
        $Res = $this->commonmodel->saveBompiProcessTrack($VarAllPostData, $this->companyid, $this->mysqldatetime);
        echo json_encode($Res);
    }
    public function bompideliveryFollowup() {
        $VarThirdUriSegmentBpiInvId = $this->uri->segment('3');
        $VarSecondUriSegmentBpiReqId = $this->uri->segment('4');
        if (isset($_COOKIE['knit20_oid'])) {
            $VarOrderId = $_COOKIE['knit20_oid'];
            if ($VarOrderId > 0) {
                $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
                $ArrData['VarCommonOrderEntryInfo'] = $VarCommonOrderEntryInfo;
                $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
                $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
                $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
                $ArrData['ArrOrderEnqData'] = $VarCommonOrderEntryInfo['ArrOrderEnqData'];
                $ArrData['ArrBB'] = $VarCommonOrderEntryInfo['ArrBB'];
                $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
                $this->load->view('purchase/bompideliveryfollowup', $ArrData);
            }
        } else {
            redirect(base_url('dashboard/bompurchaseindentlist'));
        }
    }
    public function updateBompideliveryfollowup() {
        $Varbom_piSecondfupremarks = xssclean($this->input->post('bom_pi_secondfupremarks'));
        $Varbom_piBomLotApprStatus = xssclean($this->input->post('bom_pi_bomlotapprstatus'));
        //$VarAllPostData = xssclean($this->input->post());
        //echo '<pre>'; print_r($VarAllPostData); die('');
        $VarAllPostData = array(
            'bom_piSecondfupremarks' => $Varbom_piSecondfupremarks,
            'bom_piBomLotApprStatus' => $Varbom_piBomLotApprStatus
        );
        $Res = $this->commonmodel->saveBompideliveryfollowup($VarAllPostData, $this->companyid, $this->mysqldatetime);
        echo json_encode($Res);
    }
    public function bomLotApprStatus() {
        $VarThirdUriSegmentBpiInvId = $this->uri->segment('3');
        $VarSecondUriSegmentBpiReqId = $this->uri->segment('4');
        if (isset($_COOKIE['knit20_oid'])) {
            $VarOrderId = $_COOKIE['knit20_oid'];
            if ($VarOrderId > 0) {
                $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
                $ArrData['VarCommonOrderEntryInfo'] = $VarCommonOrderEntryInfo;
                $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
                $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
                $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
                $ArrData['ArrOrderEnqData'] = $VarCommonOrderEntryInfo['ArrOrderEnqData'];
                $ArrData['ArrBB'] = $VarCommonOrderEntryInfo['ArrBB'];
                $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
                $this->load->view('purchase/bomlotapprovalstatus', $ArrData);
            }
        } else {
            redirect(base_url('dashboard/bompurchaseindentlist'));
        }
    }
    /*    public function updateBomLotApprStatus() {

        }*/
    public function bominvoicedetails() {
        $VarThirdUriSegmentBpiInvId = $this->uri->segment('3');
        $VarSecondUriSegmentBpiReqId = $this->uri->segment('4');
        if (isset($_COOKIE['knit20_oid'])) {
            $VarOrderId = $_COOKIE['knit20_oid'];
            if ($VarOrderId > 0) {
                $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
                $ArrData['VarCommonOrderEntryInfo'] = $VarCommonOrderEntryInfo;
                $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
                $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
                $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
                $ArrData['ArrOrderEnqData'] = $VarCommonOrderEntryInfo['ArrOrderEnqData'];
                $ArrData['ArrBB'] = $VarCommonOrderEntryInfo['ArrBB'];
                $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
                $ArrData['ArrBomInvoiceOtherExpenses'] = unserialize(BOMEXP);
                $this->load->view('purchase/bominvoicedetails', $ArrData);
            }
        } else {
            redirect(base_url('dashboard/bompurchaseindentlist'));
        }
    }
    public function updateInvoiceDetails() {
        $AllData = xssclean($this->input->post());
        $VarTaxTypeSelectField = 'frmDom_Select_';
        $VarTaxPercentField = 'frmDom_Percent';
        $VarTaxPercentValueField = 'frmDom_PercentVal';
        $VarOtherExpField = 'OtherExpSelect';
        $VarOtherExpValueField = 'frmOtherExpValue';
        $i = 1;
        if (empty($AllData[$VarTaxTypeSelectField . $i])) {
        } else {
            while ($AllData[$VarTaxTypeSelectField . $i]) {
                $VarTaxType = $AllData[$VarTaxTypeSelectField . $i];
                if ($VarTaxType == 1) {
                } else {
                    $VarTaxPercent = $AllData[$VarTaxPercentField . $i];
                    $VarTaxPercentValue = $AllData[$VarTaxPercentValueField . $i];
                    $AllTaxTypes[] = $VarTaxType;
                    $AllTaxPercent[] = $VarTaxPercent;
                    $AllTaxPercentValue[] = $VarTaxPercentValue;
                }
                $VarOtherExp = $AllData[$VarOtherExpField . $i];
                $VarOtherExpValue = $AllData[$VarOtherExpValueField . $i];
                $AllOtherExp[] = $VarOtherExp;
                $AllOtherExpValue[] = $VarOtherExpValue;
                $i++;
            }
        }
        $ArrUpdate = array(
            'invoicetype' => $AllData['invtype'],
            'invoiceno' => $AllData['frmDom_invoiceno'],
            'invoicedate' => date('Y-m-d H:i:s', strtotime($AllData['frmDom_invoicedate'])),
            'invoicevalue' => $AllData['frmDom_invoicevalue'],
            'taxtype' => serialize($AllTaxTypes),
            'taxpercent' => serialize($AllTaxPercent),
            'taxpercentvalue' => serialize($AllTaxPercentValue),
            'invoicetotal' => $AllData['frmDom_InvoiceTaxTotal'],
            'otherexp' => serialize($AllOtherExp),
            'otherexpvalue' => serialize($AllOtherExpValue),
            'exptotal' => $AllData['frmOtherExpTotal']
        );
        $Res = $this->commonmodel->saveBomInvoiceDetailsSendReq($AllData, $this->companyid, $this->mysqldatetime);
        echo json_encode($Res);
    }
    public function commonLotApprovalAuth() {
        $ArrLotAppUserType = unserialize(ARRLOTAPPRUSERTYPE);
        $VarEmailId = xssclean($this->input->post('e'));
        $VarPassword = xssclean($this->input->post('p'));
        $VarUserTypeId = xssclean($this->input->post('userauthtypeid'));
        $VarAppRejStatus = xssclean($this->input->post('apprrejectstatus'));
        $VarPiNoRefId = base64_decode(urldecode(xssclean($this->input->post('pinorefid'))));
        $VarItemRefNo = xssclean($this->input->post('itemrefno'));
        $VarInvoiceRefNo = xssclean($this->input->post('invoicerefno'));
        if ($VarUserTypeId == 1) {
            $VarUserValidateRes = $this->commonmodel->chechUser($VarEmailId, $VarPassword, 9);
        } else {
            $VarUserValidateRes = $this->commonmodel->chechUser($VarEmailId, $VarPassword, $VarUserTypeId);
        }
        if (empty($VarUserValidateRes->id)) {
            echo json_encode(array('errcode' => '-1', 'cn' => '', 'dt' => '', 'msg' => 'Invalid E-mail Id / Password'));
        } else {
            if ($VarUserTypeId == 4) {
                $ArrCooVal = array('approveReject' => $VarAppRejStatus, 'lotApprovalUserTypeId' => 4, 'userId' => $VarUserValidateRes->id,
                    'bomPurIndentId' => $VarPiNoRefId, 'invoiceRefNo' => $VarInvoiceRefNo, 'itemRefNo' => $VarItemRefNo, 'datetime' => $this->mysqldatetime);
                setcookie('lotappr_itv_status_' . $VarItemRefNo, serialize($ArrCooVal), time() + 60 * 60 * 24 * 30, '/');
            } elseif ($VarUserTypeId == 9) {
                $ArrCooVal = array('approveReject' => $VarAppRejStatus, 'lotApprovalUserTypeId' => 9, 'userId' => $VarUserValidateRes->id,
                    'bomPurIndentId' => $VarPiNoRefId, 'invoiceRefNo' => $VarInvoiceRefNo, 'itemRefNo' => $VarItemRefNo, 'datetime' => $this->mysqldatetime);
                setcookie('lotappr_qnv_status_' . $VarItemRefNo, serialize($ArrCooVal), time() + 60 * 60 * 24 * 30, '/');
            } elseif ($VarUserTypeId == 12) {
                $ArrCooVal = array('approveReject' => $VarAppRejStatus, 'lotApprovalUserTypeId' => 12, 'userId' => $VarUserValidateRes->id,
                    'bomPurIndentId' => $VarPiNoRefId, 'invoiceRefNo' => $VarInvoiceRefNo, 'itemRefNo' => $VarItemRefNo, 'datetime' => $this->mysqldatetime);
                setcookie('lotappr_qau_status_' . $VarItemRefNo, serialize($ArrCooVal), time() + 60 * 60 * 24 * 30, '/');
            } elseif ($VarUserTypeId == 8) {
                $ArrCooVal = array('approveReject' => $VarAppRejStatus, 'lotApprovalUserTypeId' => 8, 'userId' => $VarUserValidateRes->id,
                    'bomPurIndentId' => $VarPiNoRefId, 'invoiceRefNo' => $VarInvoiceRefNo, 'itemRefNo' => $VarItemRefNo, 'datetime' => $this->mysqldatetime);
                setcookie('lotappr_pur_status_' . $VarItemRefNo, serialize($ArrCooVal), time() + 60 * 60 * 24 * 30, '/');
            } elseif ($VarUserTypeId == 1) { //stores dept is 1 for now (actually its 9)
                $ArrCooVal = array('approveReject' => $VarAppRejStatus, 'lotApprovalUserTypeId' => 1, 'userId' => $VarUserValidateRes->id,
                    'bomPurIndentId' => $VarPiNoRefId, 'invoiceRefNo' => $VarInvoiceRefNo, 'itemRefNo' => $VarItemRefNo, 'datetime' => $this->mysqldatetime);
                setcookie('lotappr_itready_status_' . $VarItemRefNo, serialize($ArrCooVal), time() + 60 * 60 * 24 * 30, '/');
            }
            if (empty($ArrCooVal)) {
                echo json_encode(array('errcode' => '-1', 'cn' => '', 'dt' => '', 'msg' => 'Error'));
            } else {
                $ArrApproverInfo = $this->commonmodel->getUserInfo($VarUserValidateRes->id);
                echo json_encode(array('errcode' => '1', 'cn' => $ArrApproverInfo[0]->contactname, 'dt' =>
                    date_format(date_create($this->mysqldatetime), 'd-m-Y H:i:s'), 'msg' => 'ok'));
            }
        }
    }
    public function updateBomLotApprCookies() {
        $VarBomPurIndId = base64_decode(urldecode(xssclean($this->input->post('pinorefid'))));
        //$VarInvoiceRefNo = xssclean($this->input->post('invoicerefno'));
        $VarItemRefNo = xssclean($this->input->post('itemrefno'));
        $ArrItemVerifyCoo = empty($_COOKIE['lotappr_itv_status_' . $VarItemRefNo]) ? '' : $_COOKIE['lotappr_itv_status_' . $VarItemRefNo];
        $ArrQuantityVerifyCoo = empty($_COOKIE['lotappr_qnv_status_' . $VarItemRefNo]) ? '' : $_COOKIE['lotappr_qnv_status_' . $VarItemRefNo];
        $ArrQualityAuditCoo = empty($_COOKIE['lotappr_qau_status_' . $VarItemRefNo]) ? '' : $_COOKIE['lotappr_qau_status_' . $VarItemRefNo];
        $ArrInvoiceVerifyCoo = empty($_COOKIE['lotappr_pur_status_' . $VarItemRefNo]) ? '' : $_COOKIE['lotappr_pur_status_' . $VarItemRefNo];
        $ArrItemReadyStatusCoo = empty($_COOKIE['lotappr_itready_status_' . $VarItemRefNo]) ? '' : $_COOKIE['lotappr_itready_status_' . $VarItemRefNo];
        $ArrUpdate = array('bompurindentid' => $VarBomPurIndId, 'itemrefno' => $VarItemRefNo,
            'itemverifyauthstatus' => $ArrItemVerifyCoo,
            'qtyverifyauthstatus' => $ArrQuantityVerifyCoo, 'qualityanaauthstatus' => $ArrQualityAuditCoo,
            'invoiceverifyauthstatus' => $ArrInvoiceVerifyCoo, 'itemreadyauthstatus' => $ArrItemReadyStatusCoo, 'status' => '1',
            'dateupdated' => $this->mysqldatetime);
        $Res = $this->commonmodel->saveBomStoreLotApprCookies($ArrUpdate, $updateFlag = 0);
        /*        setcookie('lotappr_itv_status_' . $VarItemRefNo, '', time() - 60, '/');
                setcookie('lotappr_qnv_status_' . $VarItemRefNo, '', time() - 60, '/');
                setcookie('lotappr_qau_status_' . $VarItemRefNo, '', time() - 60, '/');
                setcookie('lotappr_pur_status_' . $VarItemRefNo, '', time() - 60, '/');
                setcookie('lotappr_itready_status_' . $VarItemRefNo, '', time() - 60, '/');*/
        if ($Res)
            echo json_encode(array('errcode' => '1'));
    }
    public function updateBomItemRecdInvoiceDetails() {
        $VarOrderId = xssclean($this->input->post('oid'));
        $VarBomPurRequestId = xssclean($this->input->post('bomPurRequestId'));
        $VarBomItemId = xssclean($this->input->post('bomItemId'));
        $bomStoresReceivedInvoiceJxl = xssclean($this->input->post('bomStoresReceivedInvoiceJxl'));
        $VarBomPurIndentId = base64_decode(urldecode(xssclean($this->input->post('bomPurIndentId'))));
        $VarPriKey = xssclean($this->input->post('priKey'));
        //if(xssclean($this->input->post('itemrefno')) == 0) {
        //}
        $VarItemRefNo = xssclean($this->input->post('itemrefno'));
        $Res = $this->commonmodel->saveBomItemRecdInvoiceDetails($VarBomPurIndentId, $VarOrderId, $VarBomPurRequestId,
            $bomStoresReceivedInvoiceJxl, $VarBomItemId, $VarItemRefNo, $this->mysqldatetime, $VarPriKey);
        if ($Res) {
            echo json_encode(array('errcode' => 1, 'id' => $Res));
        }
    }
    public function getNextRequestNo() {
        $VarId = xssclean($this->input->post('id'));
        $VarRequestListTypeId = xssclean($this->input->post('reqlisttypeid'));
        $VarIsrIorCode = xssclean($this->input->post('isrIorCode'));
        if ($VarRequestListTypeId == 1) {
            $VarReqTypePrefix = CAD_REFNO_PREFIX;
        } elseif ($VarRequestListTypeId == 2) {
            $VarReqTypePrefix = SAM_REFNO_PREFIX;
        }
        $VarSql = "SELECT misc.id,sample_no FROM " . KN_ALLREQUEST . " AS a 
        INNER JOIN KN_SAMPLE_REQUEST_MISC AS misc ON misc.orderid = a.orderid WHERE a.id = '$VarId' AND 
         requestlisttypeid = '$VarRequestListTypeId' ORDER BY misc.id DESC";
        $MiscRes = $this->db->query($VarSql)->row();
        if (!empty($MiscRes)) {
            $VarPriId = $MiscRes->id + 1;
            $samRefNo = $VarPriId;
        } else {
            $samRefNo = '1';
        }
        $samRefNo = $VarIsrIorCode . '/' . $VarReqTypePrefix . $samRefNo;
        echo json_encode(array('errcode' => '1', 'refno' => $samRefNo));
    }
    public function bompiapprovallist() {
        $rFrom = xssclean($this->input->post('rfrom'));
        if ($rFrom == 1) {
            $ArrList = $this->commonmodel->bomPiApprListDatatablesAjax($ApprovedStatusFlag = 0);
            $data = array();
            $ArrStatus = unserialize(ARRSTATUS);
            $ArrRequestType = unserialize(ARRREQUESTTYPE);
            $VarCs = '';
            $VarCs = 'Current Status';
            $ArrBomRequirement = unserialize(ARRBOMREQUIREMENT);
            foreach ($ArrList as $Obj) {
                $row = array();
                $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->id . '">';
                $row[] = $Obj->isriorcode;
                $row[] = $Obj->brandname . ' / ' . $Obj->buyername;
                //Note on below line newly added || $this->usertype == 15 condition
                if ($this->usertype == 3 || $this->usertype == 15) {
                    $row[] = '<a href="' . base_url('bompurchaseindent/mgmtbompurchaseindentreq') . '/' . urlencode(base64_encode($Obj->id)) . '">P.I. Approval</a>';
                } else if ($this->usertype == 8) {
                    $row[] = '<a href="' . base_url('dashboard/bompurchaseindentdetails_cmn') . '/' . urlencode(base64_encode($Obj->id)) . '">P.I. Approval</a>';
                }
                $row[] = $ArrBomRequirement[$Obj->requirementforbom];
                $row[] = $Obj->datecreated;
                $row[] = $Obj->cutoffdatetime;
                if ($Obj->approvedstatus == 2) $row[] = 'Authorized';
                elseif ($Obj->approvedstatus == 3) $row[] = 'Not Authorized';
                else $row[] = '-';
                $row[] = $Obj->contactname;
                $row[] = '<a href="javascript:void(0)">' . $VarCs . '</a>';
                $row[] = date('d-m-Y H:i:s', strtotime($Obj->dateupdated));
                $row[] = $ArrStatus[$Obj->status];
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->commonmodel->countAllBomPiAppr($ApprovedStatusFlag = 0),
                "recordsFiltered" => $this->commonmodel->countBomPiApprFiltered($ApprovedStatusFlag = 0),
                "data" => $data,
            );
            echo json_encode($output);
        } else {
            $this->load->view('misc/bompiapproval_list', array());
        }
    }
    public function bompurchaseindentdetails_cmn() {
        $VarBomPurIndId = base64_decode(urldecode($this->uri->segment(3)));
        if ($VarBomPurIndId > 0) {
            $this->load->model('mbompurcahseindentmodel');
            $this->load->model('mbompurchaserequestmodel');
            $this->load->model(CNFCOMPANY . 'mbomvendormodel');
            $ArrBasicInfo = $this->mbompurcahseindentmodel->getBomPIDetails($VarBomPurIndId);
            $ArrBasicAdvPaymentInfo = $this->mbompurcahseindentmodel->getBomPIAdvPayment($VarBomPurIndId);
            $ArrBomPurRequestInfo = $this->mbompurchaserequestmodel->getBomPurchaseRequest($ArrBasicInfo->bompurrequestid, $this->companyid);
            $VarTblName = $ArrBomPurRequestInfo->bompirequestgrid_tblname;
            $VarOrderId = $ArrBasicInfo->orderid;
            $ArrData['PiNo'] = $ArrBasicInfo->isriorcode . '/' . BOMPURIND_PREFIX . $VarBomPurIndId;
            $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
            $ArrData['VarCommonOrderEntryInfo'] = $VarCommonOrderEntryInfo;
            $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
            $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
            $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
            $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
            $ArrData['ArrOrderEnqData'] = $ArrOrderEnqData[0];
            $ArrData['brandName'] = $VarCommonOrderEntryInfo['brandName'];
            $ArrData['buyerName'] = $VarCommonOrderEntryInfo['buyerName'];
            $ArrData['ArrCurrencyNCode'] = unserialize(ARRCURRENCYLIST);
            $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
            $ArrData['VarOrderId'] = $VarOrderId;
            $ArrData['VarBomPurIndentId'] = $VarBomPurIndId;
            $ArrData['ArrBasicInfo'] = $ArrBasicInfo;
            $ArrData['VendorInfo'] = $this->mbomvendormodel->fnGetInfo('','1', $ArrBasicInfo->vendorid);
            $ArrData['BomPurReqId'] = $ArrBasicInfo->bompurrequestid;
            $ArrData['ArrBasicPurRequestInfo'] = $ArrBomPurRequestInfo;
            $PurchaseDeptUserInfo = $this->commonmodel->getUserInfo($ArrBasicInfo->purchasedeptid);
            $ApprovedMgmtInfo = $this->commonmodel->getUserInfo($ArrBasicInfo->approvedbymgmtid);
            $ArrData['PurchaseDeptUserInfo'] = $PurchaseDeptUserInfo[0];
            $ArrData['ApprovedMgmtInfo'] = $ApprovedMgmtInfo;
            $ArrData['loggedInUserType'] = $this->usertype;
            $ArrData['ArrBomPiModeOfPayment'] = unserialize(BOMPI_MODEOFPAYMENT);
            $ArrData['ArrBasicAdvPaymentId'] = 0;
            $ArrData['paymentpaidinfotoprint'] = '';
            $jsonPaymentPaidGrid = '';
            $AdvPaymentId = 0;
            if (!empty($ArrBasicAdvPaymentInfo)) {
                $AdvPaymentId = $ArrBasicAdvPaymentInfo->id;
                if (!empty($ArrBasicAdvPaymentInfo->paymentpaidgrid)) {
                    $jsonPaymentPaidGrid = $ArrBasicAdvPaymentInfo->paymentpaidgrid;
                    $ArrAdvPayment = json_decode($ArrBasicAdvPaymentInfo->paymentpaidgrid, true);
                    foreach ($ArrAdvPayment as $data) {
                        $ArrData['paymentpaidinfotoprint'] = $data[1] . ' / ' . $data[2] . ' / ' . $data[4] . ' / ' . $data[6];
                    }
                }
            }
            $ArrData['jsonPaymentPaidGrid'] = $jsonPaymentPaidGrid;
            $ArrData['ArrBasicAdvPaymentId'] = $AdvPaymentId;
            $dynamicTblBomPI = $this->mbompurcahseindentmodel->getBomPIDynamicTblData($VarTblName, 0, $hiddenStatus = 1, '', $VarOrderId);
            //echo '<pre>'; print_r($dynamicTblBomPI); die('die');
            /**Removing last two values as its not needed for display id and selectcheckbox*/
            $ArrData['purchaseIndSavedGrid'] = '';
            //$ArrPurchaseIndSavedGrid = $ArrBasicInfo->bomitemidanddata;
            //echo '<pre>'; print_r($ArrBasicInfo); die('die');
            if (!empty($dynamicTblBomPI)) {
                foreach ($dynamicTblBomPI as $pi) {
                    if ($ArrBasicInfo->taxtype == 1) {
                        $purchaseIndSavedGrid[] = array($pi['itemdesc'], $pi['garmentsize'], $pi['itemcode'], $pi['itemcolorcode'], $pi['sizeordim'], $pi['uom1'], $pi['progbomqty'],
                            $pi['unitrate'], $pi['amount'], $pi['sgstpercent'], $pi['sgstvalue'], $pi['cgstpercent'], $pi['cgstvalue'], $pi['subtotal']);
                    }
                    if ($ArrBasicInfo->taxtype == 2) {
                        $purchaseIndSavedGrid[] = array($pi['itemdesc'], $pi['garmentsize'], $pi['itemcode'], $pi['itemcolorcode'], $pi['sizeordim'], $pi['uom1'], $pi['progbomqty'],
                            $pi['unitrate'], $pi['amount'], $pi['igstpercent'], $pi['igstvalue'], $pi['subtotal']);
                    }
                    if ($ArrBasicInfo->taxtype == 3) {
                        $purchaseIndSavedGrid[] = array($pi['itemdesc'], $pi['garmentsize'], $pi['itemcode'], $pi['itemcolorcode'], $pi['sizeordim'], $pi['uom1'], $pi['progbomqty'],
                            $pi['unitrate'], $pi['amount'], $pi['dutypercent'], $pi['dutyvalue'], $pi['subtotal']);
                    }
                }
                $ArrData['purchaseIndSavedGrid'] = json_encode($purchaseIndSavedGrid);
            }
            $this->load->view('misc/bompurchaseindentdetails_cmn', $ArrData);
        }
    }
    public function changeWipStatus() {
       
        $VarActInActiveOption = xssclean($this->input->post('cs'));
        $ArrCheckBoxId = json_decode(xssclean($this->input->post('id')), true);
        if (!empty($VarActInActiveOption)) {
            if (!empty($ArrCheckBoxId)) {
                if ($this->commonmodel->fnChangeWipStatus($ArrCheckBoxId, $VarActInActiveOption)) {
                    echo json_encode(array('errCode' => 1));
                    die;
                } else                 echo json_encode(array('errCode' => -1));
            }
        }
    }
    
    public function changeReqStatus() {
      
        $VarActInActiveOption = xssclean($this->input->post('cs'));
        $ArrCheckBoxId = json_decode(xssclean($this->input->post('id')), true);
        $tblName = xssclean($this->input->post('tblname'));
        $idName = xssclean($this->input->post('idName'));
        if (!empty($VarActInActiveOption)) {
            if (!empty($ArrCheckBoxId)) {
                if ($this->commonmodel->changeReqStatus($ArrCheckBoxId, $VarActInActiveOption, $tblName, $idName)) {
                    echo json_encode(array('errCode' => 1));
                    //die;
                } else                 echo json_encode(array('errCode' => -1));
            }
        }
    }
    public function wipDetailPage() {
        $LoadingPc = $DestPc = [];
        //$VarHashedReferenceId = $this->uri->segment('3');
        $VarReferenceId = base64_decode(urldecode($this->uri->segment('3')));
        $VarPoNo = urldecode($this->uri->segment('4'));
        $VarPoNoId = urldecode($this->uri->segment('5'));
        $ArrOriShipStatus = array('PENDING', 'GOODS SHIPPED - OT', 'PART QTY. SHIPPED', 'SHIPMENT RESCHEDULED');
        $ArrRevisedShipStatus = array('PENDING P.Q.', 'GOODS SHIPPED', 'PART QTY. SHIPPED', 'SHIPMENT RESCHEDULED');
        $ArrCommonData = $this->orderentrymodel->getCommonData($VarReferenceId, $this->companyid);
        if (is_object($ArrCommonData)) {
            $VarMerchantTeamId = $ArrCommonData->teamid;
            $ArrTeamInfo = $this->commonmodel->getTeamDetails($this->companyid, $VarMerchantTeamId, 1);
            $teamInfo = @$ArrTeamInfo[0];
        } else {
            $teamInfo = '';
        }
        $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarReferenceId, $this->companyid);
        $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
        $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
        $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
        $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
        $ArrData['ArrOrderEnqData'] = $VarCommonOrderEntryInfo['ArrOrderEnqData'];
        $ArrData['brandName'] = $VarCommonOrderEntryInfo['brandName'];
        $ArrData['buyerName'] = $VarCommonOrderEntryInfo['buyerName'];
        $ArrData['ArrCommonData'] = $ArrCommonData;
        $ArrData['ArrTeamInfo'] = $teamInfo;
        $ArrData['ReferenceId'] = $VarReferenceId;
        $ArrData['VarPoNo'] = $VarPoNo;
        $ArrData['poNoId'] = $VarPoNoId;
        $this->load->view('workInProcessDetailPage', $ArrData);
    }
    public function deliveryScheduleForStatus() {
        $ArrWipDetails = [];
        $VarPoNoId = xssclean($this->input->post('poNoId'));
        $VarReferenceId = xssclean($this->input->post('referenceId'));
        $ArrPoNoId = explode(',', $VarPoNoId);
        $deliverySchedule = $this->commonmodel->fnGetAllTableInfo(ORDERENTRY_DELIVERYSCHEDULESIXTHTBL, 'jsondatagrid', array('referenceid' => $VarReferenceId));
        if (!empty($deliverySchedule[0]['jsondatagrid'])) {
            $ArrOriShipStatus = array('PENDING', 'GOODS SHIPPED - OT', 'PART QTY. SHIPPED', 'SHIPMENT RESCHEDULED');
            $ArrDeliverySchedule = json_decode($deliverySchedule[0]['jsondatagrid'], true);
            foreach ($ArrPoNoId as $key => $item) {
                $ArrGridData = $ArrDeliverySchedule[$item];
                $ArrWipDetails[] = array($ArrGridData[2], $ArrGridData[4], $ArrGridData[5], $ArrGridData[6], $ArrGridData[7],
                    $ArrGridData[8] . '/' . $ArrGridData[9], $ArrGridData[10] . '/' . $ArrGridData[11], "", $item);
            }
            $ArrModeOfShipment = unserialize(ORDERMODEOFSHIPMENT);
            echo json_encode(array('re' => $ArrWipDetails, 'oriShipStatus' => $ArrOriShipStatus, 'ArrModeOfShipment' => $ArrModeOfShipment));
        }
    }
    public function saveOriSchStatus() {
        $From = xssclean($this->input->post('rFrom'));
        if ($From == 1) {
            $VaroStatus = xssclean($this->input->post('oStatus'));
            $VarOriId = xssclean($this->input->post('oriId'));
            echo '<pre>';
            print_r($VaroStatus);
            echo '<pre>';
            print_r($VarOriId);
            die('die');
            //$this->db->insert(REVISED_RESCHEDULE_SHIPMENT,array('revised_id'=>$VarOriId););
            //$this->db->insert(GOODS_SHIPPED_SCHEDULE,);
        }
    }
    public function fnGetUserDesgn($VarDesgn = '', $VarStatus = '', $VarId = '') {
        $this->db->from(KN_USER_DESGN);
        if ($VarDesgn <> '') {
            $ArrWhere['desgn'] = $VarDesgn;
        }
        if ($VarStatus <> '') {
            $this->db->where_in('desgn_status', array($VarStatus));
        } else {
            $this->db->where_in('desgn_status', array(1, 2));
        }
        if ($VarId <> '') {
            $ArrWhere['designationid'] = $VarId;
        }
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
        $ArrResultList = $this->db->get()->result_array();
        return $ArrResultList;
    }
    /*public function fnCheckUserDesgn($VarData = '', $VarId = '', $VarUserTypeId = '') {
        $this->db->from(KN_USER_DESGN);
        $ArrWhere = array('desgn_status' => "1");
        if ($VarId <> '') {
            $this->db->where_not_in('designationid', array($VarId));
        }
        if ($VarData <> '') {
            $ArrWhere['desgn'] = $VarData;
        }
        if ($VarUserTypeId <> '') {
            $ArrWhere['usertypeid'] = $VarUserTypeId;
        }
        $countAll = $this->db->where($ArrWhere)->count_all_results();
        return $countAll;
    }*/
    public function addedituserdesgn() {
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarId = base64_decode(urldecode($this->uri->segment(3)));
        $ArrData['Edit'] = $this->uri->segment(4);
        $ArrData['ArrStatus'] = unserialize(ARRSTATUS);
        $ArrUserTypes = unserialize(ARRUSERTYPE);
        $ArrData['ArrUserTypes'] = $ArrUserTypes;
        if (is_numeric($VarId)) {
            $ArrResults = $this->fnGetUserDesgn('', '', $VarId);
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['designationid'];
        } else {
        }
        $this->load->view('addedituserdesgn', $ArrData);
    }
    public function updateUserDesgn() {
        $ArrUpdateData = array();
        $ArrUpdateData['designationid'] = xssclean($this->input->post('id'));
        $ArrUpdateData['usertypeid'] = xssclean($this->input->post('ut'));
        $ArrUpdateData['desgn'] = xssclean($this->input->post('ds'));
        $ArrUpdateData['desgn_status'] = xssclean($this->input->post('s'));
        $ArrUpdateData['updated_date'] = date('Y-m-d H:i:s');
        $ArrUpdateData['companyid'] = $this->companyid;
        if (!empty($ArrUpdateData['desgn'])) {
            $ArrResult = $this->saveUserDesgn($ArrUpdateData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input';
        }
        echo json_encode($ArrResult);
    }
    public function saveUserDesgn($ArrUpdateData) {
        $VarId = $ArrUpdateData['designationid'];
        if (empty($VarId)) {
            $ArrCheckExist = $this->fnGetUserDesgn($ArrUpdateData['desgn'], 1);
            unset($ArrUpdateData['designationid']);
            $this->db->insert(KN_USER_DESGN, $ArrUpdateData);
            $VarUserId = $this->db->insert_id();
            $ArrResult['errcode'] = 1;
            $ArrResult['msg'] = '';
            $ArrResult['id'] = $VarId;
            $ArrResult['eid'] = urlencode(base64_encode($VarUserId));
        } else {
            if ($this->db->update(KN_USER_DESGN, $ArrUpdateData, array('designationid' => $VarId))) {
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = 'Err';
            }
        }
        return $ArrResult;
    }
    public function manageDesignations() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarUt = xssclean($this->input->post('ut'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarStatus = xssclean($this->input->post('s'));
        $ArrDbCols = array('usertypeid', 'desgn', 'desgn_status', 'updated_date');
        if ($VarFrom == 1) {
            $VarURLSegment = 3;
            $this->load->library('pagination');
            $config['base_url'] = base_url('dashboard/manageDesignations/');
            $config['total_rows'] = $this->fnCountDesignation($VarUt, $VarStatus);
            $config['per_page'] = $this->limit;
            $config['uri_segment'] = $VarURLSegment;
            $VarOffset = $this->uri->segment($VarURLSegment);
            //echo '<pre>'; print_r($VarOffset); die('die');
            $this->pagination->initialize($config);
            if (empty($VarSortOrder)) $VarSortOrder = 'desc';
            if (array_key_exists($clickedColumnId, $ArrDbCols)) $VarSortBy = $ArrDbCols[$clickedColumnId]; else $VarSortBy = '';
            $ArrList = $this->fnListDesignation($VarUt, $VarStatus, $this->limit, $VarOffset, $VarSortBy, $VarSortOrder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('User');
            $i = 0;
            $ArrFnlList = array();
            $ArrStatus = unserialize(ARRSTATUS);
            $ArrUserTypes = unserialize(ARRUSERTYPE);
            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->designationid;
                $ArrFnlList[$i]['ut'] = $ArrUserTypes[$Obj->usertypeid];
                $ArrFnlList[$i]['ds'] = $Obj->desgn;
                $ArrFnlList[$i]['s'] = $ArrStatus[$Obj->desgn_status];
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i:s', strtotime($Obj->updated_date));
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => '1', 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $ArrUserTypes = unserialize(ARRUSERTYPE);
            $this->load->view('mdesignations', array('ArrUserTypes' => $ArrUserTypes));
        }
        //$this->load->view('mdesignations', array('ArrData' => $ArrData));
    }
    public function designationWhereCond($VarUserType = '', $VarStatus = '') {
        if ($VarUserType <> '') {
            $ArrWhere[] = "usertypeid = '" . $VarUserType . "'";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "desgn_status = " . $VarStatus;
        } else {
            $ArrWhere[] = "desgn_status in(1,2)";
        }
        $VarWhere = " WHERE " . implode(" AND ", $ArrWhere);
        return $VarWhere;
    }
    public function fnCountDesignation($VarUserType = '', $VarStatus = '') {
        $VarWhere = $this->designationWhereCond($VarUserType, $VarStatus);
        $VarSql = "SELECT count(1) as trec  FROM " . KN_USER_DESGN . " AS ud" . $VarWhere;
        $ObjRows = $this->db->query($VarSql)->row();
        return $ObjRows->trec;
    }
    function fnListDesignation($VarUserType = '', $VarStatus = '', $VarLimit = 10, $VarOffSet = 0, $VarSortBy, $VarSortOrder) {
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('usertypeid', 'desgn', 'desgn_status', 'updated_date');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'updated_date';
        $VarLimitInfo = $VarLimit;
        if ($VarOffSet >= 1) {
            $VarLimitInfo = $VarOffSet . "," . $VarLimit;
        }
        $VarWhere = $this->designationWhereCond($VarUserType, $VarStatus);
        $VarSql = "SELECT designationid,usertypeid,desgn,desgn_status,updated_date FROM " . KN_USER_DESGN . " 
        AS ud" . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder . " limit " . $VarLimitInfo;
        return $this->db->query($VarSql);
    }
    function changeDesignationStatus() {
        $VarStatus = xssclean($this->input->post('actDeact'));
        $VarCheckboxIds = xssclean($this->input->post('cid'));
        $ArrIds = json_decode($VarCheckboxIds, true);
        $this->db->where_in('designationid', $ArrIds);
        if ($this->db->update(KN_USER_DESGN, array('desgn_status' => $VarStatus))) {
            $Arrresult = array('errcode'=>1);
        }
        else {
            $Arrresult = array('errcode'=>-1);
        }
        echo json_encode($Arrresult);
    }
}