<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msampling extends CI_Controller {
    private $limit = 10;
    public $companyid;
    public $userid;
    public $mysqldatetime;
    public $samplingusertblname;
    public $usertblname;
    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL);
        $this->load->helper('xssclean');
        $this->load->model('commonmodel');
        $ArrUserLoggedInfo         = fnGetUserLoggedInfo('1');
        $this->companyid           = $ArrUserLoggedInfo['companyid'];
        $this->userid              = $ArrUserLoggedInfo['id'];
        $this->samplingusertblname = 'kn_samplingusers';
        $this->mysqldatetime       = date('Y-m-d H:i:s');
    }



    public function addeditsamplinguser() {
        $VarRemainingUser = $this->commonmodel->remaininguseravailable($this->companyid,1);
        if($VarRemainingUser == 0) {
            die('User Limit Ended. Can\'t add more users');
        }
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '', 'VarNew' => 0);
        $VarId   = $this->uri->segment(3);
        if ($VarId <> '' && base64_decode(urldecode($VarId))) {
            $VarUserId = base64_decode(urldecode($VarId));
            $ArrResults = $this->fnGetInfo('', '', $VarUserId);
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {
            $ArrData['VarNew'] = 1;
        }
        $this->load->view('sampling/addeditsamplinguser', $ArrData);
    }
    public function fnGetInfo($VarEmailId = '', $VarStatus = '', $VarUserId = '') {
        //$this->db->from($this->cadusertblname.' AS c');
        $this->db->from(KN_USERS . ' AS u');
        //$this->db->join(KN_USERS .' AS u','u.id = c.userid');
        if ($VarStatus <> '') {
            $this->db->where_in('u.status', array($VarStatus));
        } else {
            $this->db->where_in('u.status', array(1, 2));
        }
        if ($VarUserId <> '') {
            $ArrWhere['u.id'] = $VarUserId;
        }
        if ($VarEmailId <> '') {
            $ArrWhere['username'] = $VarEmailId;
        }
        if (@count($ArrWhere) >= 1) {
            $this->db->where($ArrWhere);
        }
        $ArrResultList = $this->db->get()->result_array();
        return $ArrResultList;
    }
    public function updateSamplingUser() {
        $ArrUpdateData                      = array();
        $ArrUpdateData['id']                = xssclean($this->input->post('id'));
        $ArrUpdateData['username']          = xssclean($this->input->post('e'));
        $ArrUpdateData['contactname']       = xssclean($this->input->post('n'));
        $ArrUpdateData['mobile']            = xssclean($this->input->post('m'));
        $ArrUpdateData['companyid']         = $this->companyid;
        $ArrUpdateData['dateupdated']       = $this->mysqldatetime;
        $ArrSamplingData['dateupdated']     = $this->mysqldatetime;
        $ArrUpdateData['updatedby']         = $ArrCadData['updatedby'] = $this->userid;
        $ArrSamplingData['dateupdated']     = $this->mysqldatetime;
        $ArrSamplingData['companyid']       = $ArrUpdateData['companyid'] = $this->companyid;
        $ArrSamplingData['status']          = 1;
        $ArrUpdateData['status']            = xssclean($this->input->post('s'));
        $ArrUpdateData['password']          = 'Password123';
        $ArrUpdateData['usertype']          = '6';
        $ArrUpdateData['profilepermission'] = '6';
        if ($ArrUpdateData['username'] <> '') {
            if ($ArrUpdateData['id'] == '' || $ArrUpdateData['id'] == 0) {
                $ArrSamplingData['code']        = mt_rand();
                $ArrUpdateData['datecreated']   = date('Y-m-d H:i:s');
                $ArrSamplingData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->saveSamplingUser($ArrUpdateData, $ArrSamplingData);
        } else {
            $ArrResult['errcode'] = '-1';
            $ArrResult['msg']     = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function saveSamplingUser($ArrUpdateData, $ArrCadData) {
        $VarId = $ArrUpdateData['id'];
        if ($VarId == "") {
            $ArrCheckExist = $this->fnGetInfo($ArrUpdateData['username'], 1);
            if (@$ArrCheckExist[0]['id'] == '' && @$ArrCheckExist[0]['id'] == 0) {
                unset($ArrUpdateData['id']);
                $this->db->insert(KN_USERS, $ArrUpdateData);
                $VarUserId            = $this->db->insert_id();
                $ArrCadData['userid'] = $VarUserId;
                $this->db->insert($this->samplingusertblname, $ArrCadData);
                $ArrResult['errcode'] = 1;
                $ArrResult['msg']     = 'Successfully updated';
                $ArrResult['id']      = $VarId;
                $ArrResult['eid']     = urlencode(base64_encode($VarUserId));
            } else {
                $ArrResult['errcode'] = '-1';
                $ArrResult['msg']     = "This E-mail Id already exists!!";
            }
        } else {
            if ($this->db->update(KN_USERS, $ArrUpdateData, array('id' => $VarId))) {
                $this->db->update($this->samplingusertblname, $ArrCadData, array('userid' => $VarId));
                $ArrResult['errcode'] = 1;
                $ArrResult['msg']     = 'Successfully updated';
                $ArrResult['id']      = $VarId;
                $ArrResult['eid']     = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = '-1';
                $ArrResult['msg']     = 'Invalid Data!';
            }
        }
        return $ArrResult;
    }
    public function managesamplingusers() {
        $VarFrom         = xssclean($this->input->post('rfrom'));
        $VarName         = xssclean($this->input->post('n'));
        $VarEmail        = xssclean($this->input->post('e'));
        $VarStatus       = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder    = xssclean($this->input->post('sortorder'));
        $VarAfilter      = xssclean($this->input->post('afilter'));
        $ArrDbCols       = array('u.contactname', 'u.username', 'u.mobile', 'u.status', 'u.dateupdated');
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url']    = base_url('msampling/managesamplingusers/');
            $config['total_rows']  = $this->fnCount($VarName, $VarEmail, $this->companyid, $VarStatus, $VarAfilter);
            $config['per_page']    = 10;
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
            $ArrLabList = $this->fnList($VarName, $VarEmail, $this->companyid, $VarStatus, $this->limit, $offset,
                $sortby, $sortorder, $VarAfilter)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('Samplinguser');
            $i                  = 0;
            $ArrFnlList         = array();
            $ArrStatus          = unserialize(ARRSTATUS);
            foreach ($ArrLabList as $ObjUnit) {
                $ArrFnlList[$i]['id'] = $ObjUnit->id;
                $ArrFnlList[$i]['n']  = $ObjUnit->contactname;
                $ArrFnlList[$i]['e']  = $ObjUnit->username;
                $ArrFnlList[$i]['m']  = $ObjUnit->mobile;
                $VarUser              = $this->commonmodel->getUserInfo($ObjUnit->updatedby);
                $ArrFnlList[$i]['ub'] = @$VarUser[0]->contactname;
                $ArrFnlList[$i]['s']  = $ArrStatus[$ObjUnit->status];
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i:s', strtotime($ObjUnit->dateupdated));
                $i                    = $i + 1;
            }
            echo json_encode(array('errcode' => '1', 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view('sampling/managesamplingusers');
        }
    }
    public function fnCount($VarName = '', $VarEmailId = '', $VarCompanyId = '', $VarStatus = '', $VarAfilter = '') {
        $ArrWhere = array();
        if ($VarName <> '') {
            $ArrWhere[] = "u.contactname like '%" . $VarName . "%'";
        }
        if ($VarCompanyId <> '') {
            $ArrWhere[] = "u.companyid =" . $VarCompanyId;
        }
        if ($VarEmailId <> '') {
            $ArrWhere[] = "u.username = '" . $VarEmailId . "'";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "u.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "u.status in(1,2)";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "u.contactname like '" . $VarAfilter . "%'";
        }
        $VarWhere = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere) . " AND usertype = 6";
        }
        $VarSqlLab = "SELECT count(1) as trec  FROM " . $this->samplingusertblname . " AS c INNER JOIN " . KN_USERS . " AS u ON c.userid = u.id WHERE " . $VarWhere;
        $ObjRows   = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }

    function fnList($VarMgmtname = '', $VarEmailId = '', $VarCompanyId = '', $VarStatus = '', $VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder, $VarAfilter = '') {
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols  = array("1" => 'u.contactname', '2' => 'u.username', '3' => 'u.mobile', '4' => 'u.status', '5' => 'u.dateupdated');
        $VarSortBy    = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'u.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($VarMgmtname <> '') {
            $ArrWhere[] = "u.contactname like '%" . $VarMgmtname . "%'";
        }
        if ($VarCompanyId <> '') {
            $ArrWhere[] = "u.companyid =" . $VarCompanyId;
        }
        if ($VarEmailId <> '') {
            $ArrWhere[] = "u.username = '" . $VarEmailId . "'";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "u.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "u.status in(1,2)";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "u.contactname like '" . $VarAfilter . "%'";
        }
        $VarWhere = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere) . " AND usertype = 6";
        }
        $VarSqlLab = "SELECT u.id,u.contactname,u.mobile,u.username,u.datecreated,u.dateupdated,u.status,u.updatedby FROM " . $this->samplingusertblname . " 
        AS c INNER JOIN " . KN_USERS . " AS u ON c.userid = u.id WHERE " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder . " limit " . $VarLimitInfo;
        $ObjResult = $this->db->query($VarSqlLab);
        return $ObjResult;
    }

    public function changemStatus() {
        $VarActDeactOption = xssclean($this->input->post('actdeact'));
        $VarCid            = xssclean($this->input->post('cid'));
        if ($VarActDeactOption <> '' && $VarCid <> '') {
            $Arrids = json_decode($VarCid, true);
            $this->db->where_in('userid', $Arrids);
            if ($this->db->update($this->samplingusertblname, array('status' => $VarActDeactOption))) {
                $this->db->where_in('id', $Arrids);
                $this->db->update(KN_USERS, array('status' => $VarActDeactOption));
                echo json_encode(array('errcode' => '1'));
                die;
            } else {
                echo json_encode(array('errcode' => '-1'));
            }
        }
    }

    public function deletedata() {
        $VarId = xssclean($this->input->post('id'));
        if ($VarId >= 1) {
            $ArrUpdateData = array('status' => '3', 'dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid);
            if ($this->db->update($this->samplingusertblname, $ArrUpdateData, array('userid' => $VarId))) {
                $this->db->update(KN_USERS, $ArrUpdateData, array('id' => $VarId));
                $ArrResult['errcode'] = 1;
                $ArrResult['msg']     = '';
                $ArrResult['id']      = $VarId;
                $ArrResult['eid']     = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = '-1';
                $ArrResult['msg']     = 'Invalid Data!';
            }
        } else {
            $ArrResult['errcode'] = '-1';
            $ArrResult['msg']     = '';
        }
        echo json_encode($ArrResult);
    }
}