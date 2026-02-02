<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mproductionuser extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->helper('xssclean');
        $this->load->model('commonmodel');
        $this->load->model('commonusermodel');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->limit = LIMITPERPAGE;
        $this->ArrDbCols = array('contactname', 'desgn', 'username', 'mobile', 'status', 'updatedby', 'dateupdated');
        $this->usertype = 10;
    }
    public function addedit() {
        $VarRemainingUser = $this->commonmodel->remaininguseravailable($this->companyid,1);
        if($VarRemainingUser == 0) {
            die('User Limit Ended. Can\'t add more users');
        }
        $ArrData												= array('ArrBasicInfo'=>array(),'VarId'=>'');
        $VarId = base64_decode(urldecode($this->uri->segment(4)));
        $VarDesgn = $this->commonmodel->getUserDesignation($this->usertype, '', 1);
        $ArrData['ArrDesgn'] = $VarDesgn;
        $ArrData['Edit'] = $this->uri->segment(5);
        $ArrData['ArrStatus'] = unserialize(ARRSTATUS);
        if(is_numeric($VarId)) {
            $ArrArrResults     									= $this->commonusermodel->fnGetInfo('','',$VarId);
            $ArrResults = $ArrArrResults[0];
            $VarDesignationId = $ArrResults['desgnid'];
            $ArrObjDesgn =$this->commonmodel->getUserDesignation('',$VarDesignationId);
            $ArrData['VarDesignation']	    				    = $ArrObjDesgn[0]['desgn'];
            $ArrData['ArrBasicInfo']	    				    = $ArrResults;
            $ArrData['VarId']					                = $ArrResults['id'];
        } else {
        }
        $this->load->view('production/addeditproductionsuser', $ArrData);
    }
    public function updateUser() {
        $ArrUpdateData                  = array();
        $ArrUpdateData['id']            = xssclean($this->input->post('id'));
        $ArrUpdateData['username']      = xssclean($this->input->post('e'));
        $ArrUpdateData['contactname']   = xssclean($this->input->post('n'));
        $ArrUpdateData['mobile']        = xssclean($this->input->post('m'));
        $ArrUpdateData['companyid']     = $this->companyid;
        $ArrUpdateData['dateupdated']   = $this->mysqldatetime;
        $ArrUpdateData['updatedby']     = $this->userid;
        $ArrUpdateData['status']        = xssclean($this->input->post('s'));
        $ArrUpdateData['desgnid'] = xssclean($this->input->post('did'));
        $ArrUpdateData['password']      = COMMONPWD;
        $ArrUpdateData['usertype']      = $this->usertype;
        $ArrUpdateData['profilepermission'] = $this->usertype;
        if($ArrUpdateData['username']<>'') {
            if($ArrUpdateData['id']=='' || $ArrUpdateData['id']==0) {
                $ArrUpdateData['datecreated']                   = $this->mysqldatetime;
            }
            $ArrResult                                          = $this->commonusermodel->saveUser($ArrUpdateData);
        } else {
            $ArrResult['errcode']							    = -1;
            $ArrResult['msg']								    = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function manage() {
        $VarFrom         = xssclean($this->input->post('rfrom'));
        $VarName = xssclean($this->input->post('n'));
        $VarMobile = xssclean($this->input->post('m'));
        $VarDesgnId = xssclean($this->input->post('d'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $VarSortOrder    = xssclean($this->input->post('sortorder'));
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url']    = base_url().CNFCOMPANY.'mproductionuser/manage/';
            $config['total_rows']  = $this->commonusermodel->fnCount($VarName, $VarMobile,$VarDesgnId,$VarStatus,$this->usertype,$this->companyid);
            $config['per_page']    = $this->limit;
            $config['uri_segment'] = $VarURLSegment;
            $VarOffset                = $this->uri->segment($VarURLSegment);
            $this->pagination->initialize($config);
            $ArrDbCols = $this->ArrDbCols;
            if(empty($VarSortOrder)) $VarSortOrder = 'desc';
            if (array_key_exists($clickedColumnId, $ArrDbCols)) $VarSortBy = $ArrDbCols[$clickedColumnId]; else $VarSortBy = '';
            $ArrList         = $this->commonusermodel->fnList($VarName, $VarMobile,$VarDesgnId,$VarStatus, $this->usertype,$this->companyid,
                $this->limit, $VarOffset, $VarSortBy, $VarSortOrder);
            $data['pagination'] = $this->pagination->create_linkswithajax('ProdUser');
            $i                  = 0;
            $ArrFnlList         = array();
            $ArrStatus          = unserialize(ARRSTATUS);
            foreach ($ArrList['listData'] as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['n']  = $Obj->contactname;
                $ArrFnlList[$i]['e']  = $Obj->username;
                $ArrFnlList[$i]['ds']  = $Obj->desgn;
                $ArrFnlList[$i]['m']  = $Obj->mobile;
                $ArrFnlList[$i]['ub'] = $ArrList['updatedByData'][$Obj->updatedby];
                $ArrFnlList[$i]['s']  = $ArrStatus[$Obj->status];
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i:s', strtotime($Obj->dateupdated));
                $i                    = $i + 1;
            }
            echo json_encode(array('errcode' => '1', 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $ArrDesignation = $this->commonmodel->getUserDesignation($this->usertype, '', 1);
            $this->load->view('production/manageproductionusers',array('ArrDesignation' => $ArrDesignation,
                'ArrStatus' => unserialize(ARRSTATUS)));
        }
    }

    public function changeStatus() {
        $VarStatus = xssclean($this->input->post('actDeact'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarStatus <> '' && $VarCid <> '') {
            $ArrIds = json_decode($VarCid, true);
            $ArrResult = $this->commonusermodel->changeStatus($ArrIds, $VarStatus);
            echo json_encode($ArrResult);
        }
    }
    /****************************** USER MODEULE ENDS *****************************/
    public function index() {
        $this->load->view('production/userdashboard');
    }
    public function productionreceivedlist() {
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $ArrUsertype = unserialize(ARRUSERTYPE);
        $this->load->model(CNFCOMPANY.'mcadrequestmodel');
        $VarFrom         = xssclean($this->input->post('rfrom'));
        $VarMerchant     = xssclean($this->input->post('mer'));
        $VarWip          = xssclean($this->input->post('wip'));
        $VarCoffDateFrom = '';
        $VarCoffDateTo   = '';
        if (xssclean($this->input->post('cutfrom'))) {
            $VarCoffDateFrom = date('Y-m-d H:i:s', strtotime(xssclean($this->input->post('cutfrom'))));
        }
        if (xssclean($this->input->post('cutto'))) {
            $VarCoffDateTo = date('Y-m-d H:i:s', strtotime(xssclean($this->input->post('cutto'))));
        }
        $VarRequirement  = xssclean($this->input->post('req'));
        $VarBB           = xssclean($this->input->post('bb'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder    = xssclean($this->input->post('sortorder'));
        $VarAfilter      = xssclean($this->input->post('afilter'));
        $VarApprovalType = xssclean($this->input->post('apprtype'));
        $VarisrIorType   = xssclean($this->input->post('isriortype'));
        $ArrDbCols       = array('oe.isriorcode', 'oe.brandbuyerid', 'c.requirementid', 'c.requesttype','c.requestdt','c.cutoffdatetime','c.approvaltype','c.mgmtid','c.merchantid',
            'c.dateupdated');
        $ArrBB           = $this->commonmodel->fnGetBuyerAndBrand();
        $ArrData         = array('ArrBB' => $ArrBB, 'ArrMerchant' => $this->commonmodel->getMerchantData($this->companyid, 1), 'ArrReqType' => unserialize(ARRREQUESTTYPE),
            'ArrRequirements' => $this->mcadrequestmodel->getCadRequirements(),'VarUserType'=>$ArrUsertype[$ArrUserLoggedInfo['usertype']]);
        if ($VarFrom == 1) {
            $this->load->model('managementmodel');
            //$Roles = $this->managementmodel->getMgmtRolesById($this->userid,$this->companyid);
            //if(@$Roles[0]->cadrole == 1) {
            $VarURLSegment = 3;
            $this->load->library('pagination');
            $config['base_url']    = base_url() . 'productionuser/productionreceivedlist/';
            $VarStatus          = 1;
            $config['total_rows']  = $this->fnReceivedCountSamUser($VarWip, $VarisrIorType, $VarCoffDateFrom, $VarCoffDateTo, $VarRequirement,
                $VarMerchant, $VarBB, $VarAfilter, $VarApprovalType, $VarStatus);
            $config['per_page']    = $this->limit;
            $config['uri_segment'] = $VarURLSegment;
            $offset                = $this->uri->segment($VarURLSegment);
            $this->pagination->initialize($config);
            $sortby    = "dateupdated";
            $sortorder = "desc";
            if ($clickedColumnId <> '' && $newsortorder <> '') {
                if (array_key_exists($clickedColumnId, $ArrDbCols)) {
                    $sortby = $ArrDbCols[$clickedColumnId];
                }
                $sortorder = $newsortorder;
            }
            $ArrFabricTypeList     = $this->fnReceivedListSamUser($VarWip, $VarisrIorType, $VarCoffDateFrom, $VarCoffDateTo, $VarRequirement, $VarMerchant, $VarBB,
                $VarAfilter, $VarApprovalType, $VarStatus, $this->limit, $offset, $sortby, $sortorder)->result();
            $data['pagination']    = $this->pagination->create_linkswithajax('Reqreceived');
            $i                     = 0;
            $ArrFnlList            = array();
            $ArrORDERENQUIRYSTATUS = unserialize(ORDERENQUIRYSTATUS);
            $ArrRequestType        = unserialize(ARRREQUESTTYPE);
            $ArrStatus             = unserialize(ARRSTATUS);
            foreach ($ArrFabricTypeList as $ObjFabric) {
                $ArrFnlList[$i]['id']    = $ObjFabric->id;
                $ArrFnlList[$i]['wip']   = $ObjFabric->isriorcode;
                $ArrBB                   = $this->commonmodel->fnGetBuyerAndBrand($ObjFabric->brandbuyerid);
                $ArrFnlList[$i]['bb']    = $ArrBB[0]->brandname . ' / ' . $ArrBB[0]->buyername;
                $ArrReqFromId            = $this->mcadrequestmodel->getCadRequirements($ObjFabric->requirementid);
                $ArrFnlList[$i]['r']     = @$ArrReqFromId[0]['requirement'];
                $ArrFnlList[$i]['reqdt'] = date('d-m-Y H:i:s', strtotime($ObjFabric->datecreated));
                if ($ObjFabric->requesttype <> 0)
                    $ArrFnlList[$i]['reqtype'] = $ArrRequestType[$ObjFabric->requesttype] ? $ArrRequestType[$ObjFabric->requesttype] : '-';
                else
                    $ArrFnlList[$i]['reqtype'] = '-';
                $ArrFnlList[$i]['cutoff'] = date('d-m-Y H:i:s', strtotime($ObjFabric->cutoffdatetime));
                if ($ObjFabric->mgmtcurrentstatus == 1)
                    $ArrFnlList[$i]['cads'] = 'Management ' . $ArrORDERENQUIRYSTATUS[$ObjFabric->mgmtcurrentstatus];
                elseif ($ObjFabric->mgmtcurrentstatus == 2) {
                    $ArrFnlList[$i]['cads'] = 'Management ' . $ArrORDERENQUIRYSTATUS[$ObjFabric->mgmtcurrentstatus];
                    if ($ObjFabric->caddeptcurrentstatus == 1) {
                        $ArrFnlList[$i]['cads'] = 'SAMPLE Dept. ' . $ArrORDERENQUIRYSTATUS[$ObjFabric->caddeptcurrentstatus];
                    } elseif ($ObjFabric->caddeptcurrentstatus == 2) {
                        $ArrFnlList[$i]['cads'] = 'SAMPLE Dept. ' . $ArrORDERENQUIRYSTATUS[$ObjFabric->caddeptcurrentstatus];
                    } elseif ($ObjFabric->caddeptcurrentstatus == 3) {
                        $ArrFnlList[$i]['cads'] = 'SAMPLE Dept. ' . $ArrORDERENQUIRYSTATUS[$ObjFabric->caddeptcurrentstatus];
                    } elseif ($ObjFabric->caddeptcurrentstatus == 4) {
                        $ArrFnlList[$i]['cads'] = 'SAMPLE Dept. ' . $ArrORDERENQUIRYSTATUS[$ObjFabric->caddeptcurrentstatus];
                    }
                } elseif ($ObjFabric->mgmtcurrentstatus == 3) {
                    $ArrFnlList[$i]['cads'] = 'Management ' . $ArrORDERENQUIRYSTATUS[$ObjFabric->mgmtcurrentstatus];
                } elseif ($ObjFabric->mgmtcurrentstatus == 4) {
                    $ArrFnlList[$i]['cads'] = 'Management ' . $ArrORDERENQUIRYSTATUS[$ObjFabric->mgmtcurrentstatus];
                } else {
                    $ArrFnlList[$i]['cads'] = '-';
                }
                $ArrFnlList[$i]['ru'] = date('d-m-Y H:i:s', strtotime($ObjFabric->dateupdated));
                $ArrFnlList[$i]['at'] = $ObjFabric->approvaltype == 0 ? '-' : $ArrRequestType[$ObjFabric->approvaltype];
                $ArrMgmtInfo = $ObjFabric->mgmtid == 0 ? '-' : $this->commonmodel->getUserInfo($ObjFabric->mgmtid,'',$this->companyid);
                $ArrFnlList[$i]['authby'] = @$ArrMgmtInfo[0]->contactname;
                $ArrMerchant         = $this->commonmodel->getMerchantData('', 1, $ObjFabric->merchantid);
                $ArrFnlList[$i]['m'] = $ArrMerchant[0]['contactname'] . ' / ' . $ArrMerchant[0]['code'];
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjFabric->status];
                $i                   = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
            //}
            //else echo json_encode(array('errcode' => '-1','msg'=>'No CAD access defined in roles'));
        } else {
            $this->load->view('production/reqreceivedlist', $ArrData);
        }
    }
    public function fnReceivedCountSamUser($VarWip = '', $VarIsrIortype = '', $VarCoffDateFrom = '', $VarCoffDateTo = '', $VarRequirement = '', $VarMerchant = '', $VarBB = '',
                                           $VarAfilter = '', $VarApprovalType = '', $VarStatus = '') {
        $userInfo = fnGetUserLoggedInfo(1);
        $ArrWhere = array();
        if ($VarWip <> '') {
            $ArrWhere[] = "oe.isriorcode like '%" . $VarWip . "%'";
        }
        if ($VarIsrIortype <> '') {
            $ArrWhere[] = "oe.reqforisrior = " . $VarIsrIortype;
        }
        if ($VarCoffDateFrom <> '' && $VarCoffDateTo <> '') {
            $ArrWhere[] = "date(c.cutoffdatetime) >= '$VarCoffDateFrom' AND date(c.cutoffdatetime) <= '$VarCoffDateTo' ";
        }
        if ($VarRequirement <> '') {
            $ArrWhere[] = "c.requirementid =" . $VarRequirement;
        }
        if ($VarMerchant <> '') {
            $ArrWhere[] = "c.merchantid like '%" . $VarMerchant . "%'";
        }
        if ($VarBB <> '') {
            $ArrWhere[] = "oe.brandbuyerid LIKE '%" . $VarBB . "%'";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "oe.isriorcode LIKE '" . $VarAfilter . "%'";
        }
        if ($VarApprovalType) {
            $ArrWhere[] = "c.approvaltype =" . $VarApprovalType;
        }
        $VarWhere = '';

        $ArrWhere[] = "c.companyid = " . $userInfo['companyid'] . " AND c.cadqueueno = '0' AND c.mgmtcurrentstatus IN(2,4)";
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_CAD_REQUEST . " AS c INNER JOIN " . KN_ORDER_ENQUIRY . " AS oe ON c.orderid = oe.id WHERE " . $VarWhere;
        $ObjRows   = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    public function fnReceivedListSamUser($VarWip = '', $VarIsrIortype = '', $VarCoffDateFrom = '', $VarCoffDateTo = '', $VarRequirement = '', $VarMerchant = '', $VarBB = '',
                                          $VarAfilter = '', $VarApprovalType = '',$VarStatus='',$VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $userInfo     = fnGetUserLoggedInfo(1);
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols  = array('oe.isriorcode', 'oe.brandbuyerid', 'c.requirementid', 'c.requesttype','c.requestdt','c.cutoffdatetime','c.approvaltype','c.mgmtid','c.merchantid',
            'c.dateupdated');
        $VarSortBy    = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'c.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($VarWip <> '') {
            $ArrWhere[] = "oe.isriorcode like '%" . $VarWip . "%'";
        }
        if ($VarIsrIortype <> '') {
            $ArrWhere[] = "oe.reqforisrior = " . $VarIsrIortype;
        }
        if ($VarCoffDateFrom <> '' && $VarCoffDateTo <> '') {
            $ArrWhere[] = "date(c.cutoffdatetime) >= '$VarCoffDateFrom' AND date(c.cutoffdatetime) <= '$VarCoffDateTo' ";
        }
        if ($VarRequirement <> '') {
            $ArrWhere[] = "c.requirementid = " . $VarRequirement;
        }
        if ($VarMerchant <> '') {
            $ArrWhere[] = "c.merchantid like '%" . $VarMerchant . "%'";
        }
        if ($VarBB <> '') {
            $ArrWhere[] = "oe.brandbuyerid LIKE '%" . $VarBB . "%'";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "oe.isriorcode LIKE '" . $VarAfilter . "%'";
        }
        if ($VarApprovalType) {
            $ArrWhere[] = "c.approvaltype =" . $VarApprovalType;
        }

        $ArrWhere[] = "c.companyid = " . $userInfo['companyid'] . " AND c.cadqueueno = '0' AND c.mgmtcurrentstatus IN(2,4)";

        $VarWhere = '';
        if (count($ArrWhere) >= 1) {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSqlLab = "SELECT c.id,oe.isriorcode,oe.stylenamerefno,c.requirementid,c.requesttype,c.cutoffdatetime,oe.brandbuyerid,c.merchantid,c.dateupdated,c.status,
c.mgmtcurrentstatus,c.datecreated,c.approvaltype,c.cadqueueno,c.caddeptcurrentstatus,c.mgmtid,c.requestdt FROM " . KN_CAD_REQUEST . " AS c INNER JOIN " . KN_ORDER_ENQUIRY . " 
AS oe ON c.orderid = oe.id WHERE " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder . " limit " . $VarLimitInfo;
        $ObjResult = $this->db->query($VarSqlLab);
        return $ObjResult;
    }
}