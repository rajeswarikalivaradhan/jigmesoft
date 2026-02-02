<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Commonmodel extends CI_Model {
    private $mysqldatetime;
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->model(CNFBADMIN . "MyDateTime");
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid   = $ArrUserLoggedInfo['companyid'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->usertype = $ArrUserLoggedInfo['usertype']; // getting loggedin usertype
       
    }
    function getIndStates($VarStateId = '') {
        if ($VarStateId <> '') {
            $this->db->select('id,statename');
            $ArrRes = $this->db->get_where(KN_INDSTATES, array('id' => $VarStateId, 'status' => '1'));
            return $ArrRes->row();
        } else {
            $this->db->select('id,statename');
            $ArrRes = $this->db->get_where(KN_INDSTATES, array('status' => '1'));
            return $ArrRes->result_array();
        }
    }

    function fnCountries() {
        $qry = $this->db->get_where(KN_COUNTRIES, array('status' => 1));
        return $qry->result();
    }

    function fnGetAllTableInfo($VarTableName = '', $VarColumnList = '', $ArrWhereCond = array(), $VarType = '', $ArrJoin = array(), $ArrWherenotin = array()) {
        $this->db->select($VarColumnList);
        $this->db->from($VarTableName);
        if (count($ArrJoin) >= 1) {
            $this->db->join($ArrJoin['jointable'], $ArrJoin['joincondition']);
        }
        $this->db->where($ArrWhereCond);
        if (count($ArrWherenotin) >= 1) {
            $this->db->where_not_in($ArrWherenotin[0], $ArrWherenotin[1]);
        }
        $ObjRes = $this->db->get();
        $ArrTableResult = $ObjRes->result_array();
        if ($VarType == 1) {
            return array_values($ArrTableResult);
        } else if ($VarType == 2) {
            return array_keys($ArrTableResult);
        } else if ($VarType == 3) {
            return $ArrTableResult;
        }
        return $ArrTableResult;
    }

    public function countAllRows($VarTableName,$ArrWhere) {
        $countAll = $this->db->from($VarTableName)->where($ArrWhere)->count_all_results();
        return $countAll;
    }

    function currencyExchange($VarCurrency = '', $VarStatus = '') {
        $ArrWhere = array();
        $VarWhere = '';
        if ($VarCurrency <> '') {
            $ArrWhere[] = "currencycode = " . $VarCurrency;
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "status in(1,2)";
        }
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSql = $this->db->query("SELECT inramount,currencycode FROM " . KN_MASTER_CURRENCY_AMOUNT . " WHERE $VarWhere");
        return $VarSql->result();
    }

    function getBuyerInfoFromBrand($VarBrandId = '') {
        if ($VarBrandId <> '') {
            // $VarSql = "SELECT byr.id,byr.companyid FROM " . KN_MASTER_BUYER . " as byr INNER JOIN " . KN_MASTER_BRANDS . " as br 
            // ON br.ref_buyerid = byr.id WHERE br.id = " . $VarBrandId;
            // return $this->db->query($VarSql)->row();
            
            $VarSql = "SELECT * FROM " . KN_MASTER_BRANDBUYER . "  WHERE id = " . $VarBrandId;
            return $this->db->query($VarSql)->row();
        }
    }

    function fnValidatePin($VarUserId, $VarPw) {
        $VarSql = $this->db->get_where(KN_USERS, array('id' => $VarUserId, 'pin' => $VarPw, 'status' => '1'));
        return $VarSql->num_rows();
    }

    function fnGetBuyerAndBrand($VarBrandId = '') {
        if ($VarBrandId <> '') {
             // commented by myself regards new brand form
            // $VarSql = "SELECT br.id,brandname,buyername FROM " . KN_MASTER_BUYER . " as byr INNER JOIN " . KN_MASTER_BRANDS . " as br 
            // ON br.buyerid = byr.id WHERE br.id = " . $VarBrandId . " AND byr.type = '1' ";
            $VarSql = "SELECT br.id,br.brandname,br.buyername,br.country FROM " . KN_MASTER_BRANDS . " as br  WHERE br.id = " . $VarBrandId . " AND br.status = '1' ";
            return $this->db->query($VarSql)->result();
        } else {
            // commented by myself regards new brand form
            // $VarSql = "SELECT br.id,brandname,buyername FROM " . KN_MASTER_BUYER . " as byr INNER JOIN " . KN_MASTER_BRANDS . " as br ON br.buyerid = byr.id WHERE byr.type = '1' ";
            $VarSql = "SELECT br.id,br.brandname,br.buyername,br.country FROM  " . KN_MASTER_BRANDS . " as br WHERE br.status = '1' ";
            return $this->db->query($VarSql)->result();
        }
    }
    function getUserType($VarUserId = '') { // getting usertype based on user id
        if ($VarUserId <> '') {
             $this->db->select('usertype');
            $ArrRes = $this->db->get_where(KN_USERS, array('id' => $VarUserId, 'status' => '1'));
            return $ArrRes->row();
        } 
    }
    function getMerchantData($VarCompanyId = '', $VarStatus = '', $VarId = '', $VarTeamId = '') {
        $ArrWhere = array();
        if ($VarCompanyId <> '') {
            $ArrWhere[] = "u.companyid = " . $VarCompanyId;
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "u.status = " . $VarStatus;
        }
         // commented by myself regards planning dept.change
        // if ($VarId <> '') {
        //     $ArrWhere[] = "u.id =" . $VarId;
        // }
        if($VarId <> ''){ //merchant or plaaning dept.id's
            $usertypes=$this->getUserType($VarId); // getting usertype based on userid
              if(!empty($usertypes)){
                $ArrWhere[] = "u.id =$VarId AND u.usertype =$usertypes->usertype"; 
              }else{
                $ArrWhere[] = "u.id =" . $VarId;  
              }
        }else{
            $ArrWhere[] = "u.usertype =3 ";  
        }
        $VarWhere = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere) . "";
        }
        // commented by myself regards planning dept.change
        // if (@$ArrWhere[0] <> '') {
        //     $VarWhere = implode(" and ", $ArrWhere) . " AND u.usertype = 3";
        // }
        $VarSql = "SELECT u.id,u.contactname,u.mobile,u.username,code FROM " . KN_USERS . " as u 
        INNER JOIN ".KN_USER_DETAILS." AS ud ON u.id = ud.userid WHERE $VarWhere";
        $ArrRes = $this->db->query($VarSql)->result_array();
        return $ArrRes;
    }

    public function commonBasicInfoOrderEntry($VarOrderId = '', $VarCompanyId = '') {
        $ArrOrderCommonData = $this->fnGetAllTableInfo(ORDERENTRY_COMMONDATA, 'season,divdept,class,sclass,payterms,
        orderbookingrate,orderrealization',array('referenceid' => $VarOrderId, 'companyid' => $VarCompanyId),
            3);
        $ArrOrderEnqData = $this->fnGetAllTableInfo(KN_ORDER_ENQUIRY, 'id,stylenamerefno,styledesc,pcsorset,
        exporderqty,merchantid,isriorcode,confirmprice,currency,dateupdated,brandid,buyerid',
            array('id' => $VarOrderId, 'companyid' => $VarCompanyId), 3);
        /// commented by myself regards new brand form///
        // $VarBbSql = "SELECT byr.id as buyerid,br.id as brandid,buyername,br.brandname FROM ".KN_MASTER_BUYER." AS byr, ".KN_MASTER_BRANDS." AS br WHERE 
        // byr.id = ".$ArrOrderEnqData[0]['buyerid']." AND br.id = ".$ArrOrderEnqData[0]['brandid'];
        /////////////////////
        $VarBbSql = "SELECT '' as buyerid,br.id as brandid,br.buyername,br.brandname FROM ".KN_MASTER_BRANDS." AS br WHERE 
        br.id = ".$ArrOrderEnqData[0]['brandid'];
        $ArrBB = $this->db->query($VarBbSql)->result_array();
        $ArrMerchant = $this->getMerchantData($VarCompanyId, 1);
        $ArrTeamInfo = $this->getTeamDetails($VarCompanyId,$ArrOrderEnqData[0]['merchantid']);
        $ArrCompanyRes = $this->fnGetAllTableInfo(KN_COMPANY_DETAILS, 'ceoname,companyname,address,emailid,mobile', array('id' => $VarCompanyId), 3);
        $VarMerchantInfo = $this->getUserInfo($ArrOrderEnqData[0]['merchantid']);
        $ArrData['merchantname'] = $VarMerchantInfo[0]['contactname'];
        $ArrData['brandName'] = $ArrBB[0]['brandname'];
        $ArrData['buyerName'] = $ArrBB[0]['buyername'];
        $ArrData['ArrMerchant'] = $ArrMerchant[0];
        $ArrData['ArrTeamInfo'] = empty($ArrTeamInfo[0]) ? '' : $ArrTeamInfo[0];
        $ArrData['ArrCompanyInfo'] = $ArrCompanyRes;
        $ArrData['ArrOrderEnqData'] = $ArrOrderEnqData[0];
        $ArrData['ArrOrderCommonData'] = $ArrOrderCommonData[0];
        return $ArrData;
    }

    //TODO add code no and use for all places. Remove code no field from kn_master_merchant and wherever.
    public function getUserInfo($VarId = '', $VarEmailId = '', $VarCompanyId = '', $ArrId = array(), $VarUserTypeId='', $VarDesignationReq='') {
        $ArrSelect = array('u.id','u.contactname','u.username','u.password','usertype','mobile','status','code');
        $ArrWhere = array();
        $VarJoin = '';
        if ($VarId <> '') {
            $ArrWhere[] = "u.id =" . $VarId;
        } else {
            //return false;
        }
        if ($VarEmailId <> '') {
            $ArrWhere[] = "u.username = " . $VarEmailId;
        }
        if ($VarCompanyId <> '') {
            $ArrWhere[] = "u.companyid = " . $VarCompanyId;
        }

        if(count($ArrId) >= 1) {
            $VarCommaSepId = implode(',',$ArrId);
            $ArrWhere[] = "u.id IN (".$VarCommaSepId.")";
        }
        if($VarUserTypeId <> '') {
            $ArrWhere[] = "u.usertype = " . $VarUserTypeId;
        }
        if($VarDesignationReq <> '') {
            $ArrSelect[] ='designation';
            // commented by myself on 26_07_23 due to designation field changed to comes from common userlist form 
            // $ArrSelect[] = 'desgn';
            // $VarJoin = " INNER JOIN ".KN_USER_DESGN." AS ud ON ud.designationid = u.desgnid";
        }
        $VarWhere = '';
        if(!empty($ArrWhere)) {
            $VarWhere = ' WHERE ' . implode(" and ", $ArrWhere);
        }
        $VarSelect = implode(',',$ArrSelect);
        $userInfoQry = "SELECT $VarSelect FROM " . KN_USERS . " AS u  
        INNER JOIN ".KN_USER_DETAILS." AS us ON u.id = us.userid " . $VarJoin . $VarWhere;
        $userInfoRes = $this->db->query($userInfoQry)->result_array();
        if($userInfoRes) {
            return $userInfoRes;
        }
    }

    function getRolesByCompanyId($VarCompanyId = '') {
        $VarSqlPort = "SELECT r.id,r.role FROM " . KN_MGMT_ROLES . " AS r WHERE r.companyid = " . $VarCompanyId;
        $ObjResult = $this->db->query($VarSqlPort);
        return $ObjResult->result();
    }

    function userprofile($VarUserId = '', $VarCompanyId = '', $VarEmailId = '', $VarStatus = '') {
        $this->db->select('c.id,companyname,companycode,businesstype,factorysize,c.address,noofmachine,c.city,c.state,country,c.zipcode,productioncapacity,annualturnover,
        noofemployee,noofcontract,factoryownership,majorcustomer,yearofest,exportcustomer,companyprofile,c.updatedby,c.status,c.datecreated,c.dateupdated,u.username');
        //$this->db->join(KN_COMPANY_CONTACT_DETAILS .' AS cc','cc.companyid = c.id');
        $this->db->join(KN_USERS . ' AS u', 'u.companyid = c.id');
        $this->db->from(KN_COMPANY_DETAILS . ' AS c');
        if ($VarStatus == '') {
            $this->db->where_in('c.status', array(1, 2, 3));
        } else {
            $this->db->where_in('c.status', array(1));
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['c.id'] = $VarCompanyId;
        }
        if ($VarUserId <> '') {
            $ArrWhere['u.id'] = $VarUserId;
        }
        if ($VarEmailId <> '') {
            $ArrWhere['u.username'] = $VarEmailId;
        }
        if (@count($ArrWhere) >= 1) {
            $this->db->where($ArrWhere);
        }
        $ArrCompanyList = $this->db->get()->result_array();
        return $ArrCompanyList;
    }

    public function getTeamDetails($VarCompanyId = '', $VarMerchantId, $VarStatus = '') {
        if ($VarMerchantId <> '') {
            $ArrWhere[] = 't.merchantid = '.$VarMerchantId;
        }
        if ($VarCompanyId <> '') {
            $ArrWhere[] = 't.companyid = '.$VarCompanyId;
        }
        if ($VarStatus == '') {
            //$this->db->where_in('u.status', array(1, 2));
        } else {
            //$this->db->where_in('u.status', array(1));
        }
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" AND ", $ArrWhere);
        }
        $VarSql = "SELECT username,contactname,code,mobile FROM " . KN_MERCHANT_TEAM . " AS t 
        INNER JOIN ".KN_USERS." AS u ON t.teamid = u.id INNER JOIN ".KN_USER_DETAILS." AS ud ON ud.userid = t.teamid ". $VarWhere;
        $ObjResult = $this->db->query($VarSql);
        return $ObjResult->result_array();
    }

/*    public function getTeamDetailsOld($VarCompanyid = '', $VarTeamId = '', $VarStatus = '') {
        $ArrWhere = array();
        if ($VarCompanyid <> '') {
            $ArrWhere[] = "t.companyid = '$VarCompanyid' ";
        }
        if ($VarTeamId <> '') {
            $ArrWhere[] = "t.id = " . $VarTeamId;
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "t.status IN (" . $VarStatus . ")";
        } else {
            $ArrWhere[] = "t.status IN ('1','2')";
        }
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSqlPort = "SELECT t.id,t.email,t.contactname,t.code,t.mobile FROM " . KN_MERCHANT_TEAM . " AS t WHERE " . $VarWhere;
        $ObjResult = $this->db->query($VarSqlPort);
        return $ObjResult->result();
    }*/

    public function remaininguseravailable($VarConpanyId = '', $VarStatus = '') {
        $VarCompanySql = "SELECT noofemployee FROM " . KN_COMPANY_DETAILS . " AS cd WHERE cd.id = '$VarConpanyId' AND cd.status = '1' ";
        $ResTotalEmployeeRes = $this->db->query($VarCompanySql)->result();
        $VarTotalUser = @$ResTotalEmployeeRes[0]->noofemployee;
        $VarSql = "SELECT COUNT(1) as trec FROM " . KN_USERS . " as u WHERE u.companyid = '$VarConpanyId' AND u.status = '$VarStatus' AND u.usertype IN (3,4,5,6)";
        $VarActiveUserCount = $this->db->query($VarSql)->result();
        $VarUserCount = $VarActiveUserCount[0]->trec;
        if ($VarTotalUser > 0 && isset($VarUserCount)) {
            $VarRemaining = $VarTotalUser - $VarUserCount;
            return $VarRemaining;
        } else
            return false;
    }

    public function getBomIndent_SentStockDetails($VarRequestid = '', $VarCompanyId = '', $VarOrderId = '') {
        $ArrWhere = array();
        if ($VarRequestid <> '') {
            $ArrWhere[] = "bi.requestid = " . $VarRequestid;
        }
        if ($VarCompanyId <> '') {
            $ArrWhere[] = "bi.companyid = " . $VarCompanyId;
        }
        if ($VarOrderId <> '') {
            $ArrWhere[] = "bi.orderid = " . $VarOrderId;
        }
        $VarWhere = '';
        if (count($ArrWhere) >= 1) {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSql = "SELECT id,requestid,bomindentgrid,matissuedto,cutoffdatetime,indentrefno,matissuedby,itemstosendtostock FROM " . BOMINDENTDETAILS . " AS bi WHERE $VarWhere";
        $ObjResult = $this->db->query($VarSql)->result();

        //return $ObjResult;

        $VarJoinSql = "SELECT bi.id,bi.requestid,bomindentgrid,matissuedto,cutoffdatetime,bi.indentrefno,matissuedby,itemstosendtostock,sentitems FROM " . BOMINDENTDETAILS . " AS bi 
        INNER JOIN " . KN_SEND_BOMIND2STORES_STOCKLIST . " AS bisk ON bisk.companyid = bi.companyid WHERE $VarWhere";
        $JoinSqlRes = $this->db->query($VarJoinSql)->result();
        if (empty($JoinSqlRes[0])) {
            //echo '<pre>'; print_r($JoinSqlRes); die('');
            return $ObjResult;
        } else {
            return $JoinSqlRes;
        }
    }

    public function getDatasForCadIndentGridDropdown($VarRequestId = '', $VarCompanyId = '', $VarOrderId = '') {
        $ArrWhere = array();
        if ($VarOrderId <> '') {
            $ArrWhere[] = "a.orderid = " . $VarOrderId;
        }
        if ($VarCompanyId <> '') {
            $ArrWhere[] = "a.companyid = " . $VarCompanyId;
        }
        $ArrWhere[] = "a.status = '1' AND request_type_dept = 'CAD' ";
        $VarWhere = '';
        if (count($ArrWhere) >= 1) {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSql = "SELECT jsondatagrid FROM " . KN_ALLREQUEST . " AS a WHERE " . $VarWhere;
        return $this->db->query($VarSql)->result();
    }

    public function fnChangeAllListAciveStatus($ArrCheckboxids, $optvalue, $VarTblName='') {
        $this->db->where_in('id', $ArrCheckboxids);
        $this->db->update($VarTblName, array('status' => $optvalue));
        return $this->db->affected_rows();
    }
     public function fnChangeAllListStatus($ArrCheckboxids, $optvalue, $VarTblName='',$VarStatusField) {
        $this->db->where_in('id', $ArrCheckboxids);
        $this->db->update($VarTblName, array($VarStatusField => $optvalue));
        return $this->db->affected_rows();
    }

    public function cmnChangeStatus($VarTblName,$ArrCheckBoxId,$VarStatusValue,$VarWhereField,$VarStatusField='status') {
        $this->db->where_in($VarWhereField, $ArrCheckBoxId);
        $this->db->update($VarTblName, array($VarStatusField => $VarStatusValue));
        return $this->db->affected_rows();
    }

    function getCadLogList($VarId = '', $VarCompanyId = '', $VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $userInfo = fnGetUserLoggedInfo(1);
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('c.wip', 'c.status', 'u.contactname', 'c.dateupdated');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'c.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        if ($VarId <> '') {
            $ArrWhere[] = "c.cadrequestid ='$VarId'";
        }
        if ($VarCompanyId <> '') {
            $ArrWhere[] = "c.companyid ='$VarCompanyId'";
        }
        $VarWhere = '';
        if (count($ArrWhere) >= 1) {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSql
            = "SELECT c.id,c.requesttype,c.cutoffdatetime,c.merchantid,
c.combo,c.component,c.color,c.sizespeccode,c.purpose,c.category,c.requiredsize,c.knittingtype,c.dyeingtype,c.compactingtype,c.merchantnote,c.dateupdated,c.ponoenqrefno,
c.orderid,c.mgmtcurrentstatus,c.mgmtremarks FROM " . KN_CAD_REQUEST_LOG . " AS c WHERE " . $VarWhere . "order by " . $VarSortBy . " " . $VarSortOrder . " limit " . $VarLimitInfo;
        return $this->db->query($VarSql)->result();
    }

    function getCadLogCount($VarId = '', $VarCompanyId = '') {
        if ($VarId <> '') {
            $ArrWhere[] = "c.cadrequestid ='$VarId'";
        }
        if ($VarCompanyId <> '') {
            $ArrWhere[] = "c.companyid ='$VarCompanyId'";
        }
        $VarWhere = '';
        if (count($ArrWhere) >= 1) {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSql = "SELECT count(1) as trec FROM " . KN_CAD_REQUEST_LOG . " AS c WHERE " . $VarWhere;
        $ObjRows = $this->db->query($VarSql)->row();
        return $ObjRows->trec;
        return $this->db->query($VarSql)->result();
    }

    function getCadreqLogDetail($VarCadreqId = '', $VarCompanyId = '') {
        if ($VarCadreqId <> '') {
            $ArrWhere[] = "c.id =" . $VarCadreqId;
        }
        $ArrWhere[] = "c.companyid = " . $VarCompanyId;
        $VarWhere = '';
        if (count($ArrWhere) >= 1) {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSqlLab
            = "SELECT c.id,c.requesttype,c.cutoffdatetime,c.merchantid,c.queueno_assigned_date,c.reqtotalsam,
c.combo,c.component,c.color,c.sizespeccode,c.purpose,c.category,a.requestrefno,c.requiredsize,c.knittingtype,c.dyeingtype,c.compactingtype,c.merchantnote,c.ponoenqrefno,
c.datecreated as cadreqcreated,c.BuyersOriginalSample,c.InlineRefSample,c.BuyersComments,c.AppGradMeasChart,c.CompleteArtwork,c.MeasureDetailsArtwork,c.cadindentattach,
c.fabindentattach,c.bomindentattach,c.orderid,c.mgmtcurrentstatus,c.caddeptcurrentstatus,c.mgmtremarks,c.caddeptremarks,c.cadqueueno,c.dateupdated,jobschedule,c.approvaltype,
c.request_type_dept,c.prevcadrefno,c.cadqueuecompletestatus,oe.isriorcode,oe.stylenamerefno,oe.styledesc,oe.datecreated as wipdatecreated,c.cadrequestid 
FROM " . KN_CAD_REQUEST_LOG . " AS c INNER JOIN " . KN_ORDER_ENQUIRY . " AS oe WHERE " . $VarWhere . " LIMIT 1";
        return $this->db->query($VarSqlLab)->row();
    }

    public function searchCadReqTblDropDowns($VarQno = '') {
        if ($VarQno <> '') {
            $VarSql = "SELECT id,queueno FROM " . KN_ALLREQUEST . " WHERE status = '1' AND queueno <> '0' ";
            return $this->db->query($VarSql)->result_array();
        } else {
            return array();
        }
    }

    public function updateCadIndentDetail($ArrUpdateData, $VarReqId) {
        $this->db->where('requestid', $VarReqId);
        $this->db->update(KN_ALLREQUEST, array('indentrefno' => $ArrUpdateData['indentrefno']), array('id' => $VarReqId));
        $this->db->update(CADINDENTDETAILS, $ArrUpdateData);
        return $this->db->affected_rows();
    }

    public function getIndRefNo($ArrReqId = array(), $VarIndRefNo = '', $VarMatIssuedBy = '') {
        if ($VarIndRefNo <> '') {
            if (count($ArrReqId) >= 1) {
                $VarQuery = $this->db->select('requestid,indentrefno')->where_in('requestid', $ArrReqId)->get(CADINDENTDETAILS);
                $ObjRes = $VarQuery->result();
                if (count($ObjRes) >= 1) {
                    foreach ($ObjRes as $item) {
                        $ArrIds[$item->requestid] = $item->indentrefno;
                    }
                    return $ArrIds;
                }
            }
        }
        if ($VarMatIssuedBy <> '') {
            if (count($ArrReqId) >= 1) {
                $VarQuery = $this->db->select('requestid,matissuedby')->where_in('requestid', $ArrReqId)->get(CADINDENTDETAILS);
                $ObjRes = $VarQuery->result();
                if (count($ObjRes) >= 1) {
                    foreach ($ObjRes as $item) {
                        $ArrIds[$item->requestid] = $item->matissuedby;
                    }
                    return $ArrIds;
                }
            }
        }
    }

    public function getAllQueueNo($VarReqType,$VarCompanyId) {
		$VarSql = "SELECT qid FROM " . KN_ALLREQUEST . " WHERE request_type_dept = '$VarReqType' AND companyid = '$VarCompanyId' ORDER BY qid DESC LIMIT 1";
		return $this->db->query($VarSql)->row();
	}

    public function saveSampleRequestInfo($ArrAllRequest,$jsonAttachmentJxl) {
        $VarId = $ArrAllRequest['id'];
        if (empty($VarId)) {
            unset($ArrAllRequest['id']);
            $this->db->insert(KN_ALLREQUEST, $ArrAllRequest);
            $VarInsertId = $this->db->insert_id();
            $ArrSamReq = array('requestrefid'=>$VarInsertId,'attachment_jxl'=>$jsonAttachmentJxl);
            $this->db->insert(KN_SAMPLE_REQUEST,$ArrSamReq);
            $ArrResult['errcode'] = 1;
            $ArrResult['msg'] = '';
            $ArrResult['id'] = $VarInsertId;
            $ArrResult['datecreated'] = $this->datetime;
            $ArrResult['eid'] = urlencode(base64_encode($VarInsertId));
        } else {
            if ($this->db->update(KN_ALLREQUEST, $ArrAllRequest, array('id' => $VarId))) {
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['datecreated'] = $this->datetime;
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = 'Invalid Data!';
            }
        }
        return $ArrResult;
    }

    public function saveSampleRequestIndentDetails($ArrOtherData,$VarRequestId='',$VarOrderId='') {
        /*if(empty($VarRequestId) && empty($VarOrderId)) {
            $this->db->insert(KN_SAMPLE_REQUEST,$ArrOtherData);
        }
        else {
            $this->db->where(array('requestrefid'=>$VarRequestId,'orderid'=>$VarOrderId));
            $this->db->update(KN_SAMPLE_REQUEST,$ArrOtherData);
        }*/
        $this->db->where(array('requestrefid'=>$VarRequestId,'orderid'=>$VarOrderId));
        $this->db->update(KN_SAMPLE_REQUEST,$ArrOtherData);
    }

    public function saveMoreRequestIndents($VarRequestId='',$VarOrderId='',$ArrMoreCadJxl,$ArrMoreFabJxl,$ArrMoreBomJxl,$updateFlag='',$ArrIds=array()) {
        if($updateFlag) {
            foreach ($ArrMoreCadJxl as $cadKey => $jxlItem) {
                $this->db->where('id',$ArrIds[$cadKey]);
                $this->db->where('requestid',$VarRequestId);
                //echo '<pre>'; print_r($jxlItem);
                $this->db->update(KN_MERCHANT_SAMPLE_CAD_INDENT,array('gridindent'=>json_encode($jxlItem)));
                //$this->db->query("CALL insertSampleRequestCadIndents($VarRequestId,$VarData)");
            }
            foreach ($ArrMoreFabJxl as $fabKey => $jxlItem) {
                $this->db->where('id',$ArrIds[$fabKey]);
                $this->db->where('requestid',$VarRequestId);
                $this->db->update(KN_MERCHANT_SAMPLE_FAB_INDENT,array('gridindent'=>json_encode($jxlItem)));
                //$this->db->query("CALL insertSampleRequestCadIndents($VarRequestId,$VarData)");
            }

            foreach ($ArrMoreBomJxl as $bomKey => $jxlItem) {
                $this->db->where('id',$ArrIds[$bomKey]);
                $this->db->where('requestid',$VarRequestId);

                $this->db->update(KN_MERCHANT_SAMPLE_BOM_INDENT,array('gridindent'=>json_encode($jxlItem)));
                //$this->db->query("CALL insertSampleRequestCadIndents($VarRequestId,$VarData)");
            }

            //echo '<pre>'; print_r($ArrMoreCadJxl);
            //echo '<pre>'; print_r($ArrIds); die('die');
            //$this->db->update_batch(KN_MERCHANT_SAMPLE_CAD_INDENT,$ArrCadData,'requestid');
        }
        else {
            foreach ($ArrMoreCadJxl as $jxlItem) {
                $ArrCadData[] = array('requestid'=>$VarRequestId,'gridindent'=>json_encode($jxlItem));
                //$this->db->query("CALL insertSampleRequestCadIndents($VarRequestId,$VarData)");
            }
            $this->db->insert_batch(KN_MERCHANT_SAMPLE_CAD_INDENT,$ArrCadData);

            foreach ($ArrMoreFabJxl as $jxlItem2) {
                $ArrData2[] = array('requestid'=>$VarRequestId,'gridindent'=>json_encode($jxlItem2));
            }
            $this->db->insert_batch(KN_MERCHANT_SAMPLE_FAB_INDENT,$ArrData2);
            $sampleNo = 1;
            foreach ($ArrMoreBomJxl as $ArrBomJxl) {
                //foreach ($ArrBomJxl as $jxl) {
                    //$indent = $jxl[0].'|#|'.$jxl[1].'|#|'.$jxl[2].'|#|'.$jxl[3].'|#|'.$jxl[4].'|#|'.$jxl[5].'|#|'.$jxl[6];
                    //$indent = ;
                    /*$ArrInsBom[] = array('orderid'=>$VarOrderId,'sampleno'=>$sampleNo,'requestid'=>$VarRequestId,
                        'itemdesc'=>$jxl[0],'garmentsize'=>$jxl[1],'itemcode'=>$jxl[2],'itemcolorcode'=>$jxl[3],
                        'sizeordim'=>$jxl[4],'uom1'=>$jxl[5],'materialIndentQty'=>$jxl[6],'uom2'=>$jxl[7]);*/
                    //$this->db->insert(KN_MERCHANT_SAMPLE_BOM_INDENT,$ArrInsBom);
                //}
                //$sampleNo++;
                //echo '<pre>'; print_r($ArrBomJxl);
                //$ArrData3[] = array('requestid'=>$VarRequestId,'gridindent'=>json_encode($jxlItem3));
                //$ArrInsertBatchBom[] = array('requestid'=>$VarRequestId,'gridindent'=>json_encode($jxlItem3));
                //$this->db->insert_batch(KN_MERCHANT_SAMPLE_BOM_INDENT,$ArrData3);
                $ArrInsBom[] = array('requestid'=>$VarRequestId,'gridindent'=>json_encode($ArrBomJxl));
            }
            $this->db->insert_batch(KN_MERCHANT_SAMPLE_BOM_INDENT,$ArrInsBom);
            //die('tes');
        }
    }

    public function getSampleRequestCadIndents($VarId) {
        $VarSql = "SELECT id,gridindent FROM ".KN_MERCHANT_SAMPLE_CAD_INDENT." WHERE requestid = '$VarId' ";
        $ObjResult = $this->db->query($VarSql)->result();
        return $ObjResult;
    }

    public function getSampleRequestFabIndents($VarId) {
        $VarSql = "SELECT id,gridindent FROM ".KN_MERCHANT_SAMPLE_FAB_INDENT." WHERE requestid = '$VarId' ";
        $ObjResult = $this->db->query($VarSql)->result();
        return $ObjResult;
    }

    public function getSampleRequestBomIndents($VarId) {
        /*'orderid'=>$VarOrderId,'sampleno'=>$sampleNo,'requestid'=>$VarRequestId,
                        ''=>$jxl[0],''=>$jxl[1],''=>$jxl[2],'itemcolorcode'=>$jxl[3],
                        'sizeordim'=>$jxl[4],'uom1'=>$jxl[5],'materialIndentQty'=>$jxl[6],'uom2'=>$jxl[7]*/
        //$VarSql = "SELECT id,sampleno,gridindent FROM ".KN_MERCHANT_SAMPLE_BOM_INDENT." WHERE requestid = '$VarId' ";
        //$VarSql = "SELECT id,requestid,sampleno,GROUP_CONCAT(gridindent) as gridindent FROM `kn_merchant_sample_bom_indent` where requestid = '$VarId' GROUP by sampleno";
        /*$VarSql = "SELECT id,requestid,sampleno,GROUP_CONCAT(itemdesc,garmentsize,itemcode) as gridindent FROM `kn_merchant_sample_bom_indent` where
                    requestid = '$VarId' GROUP by sampleno";*/
        $VarSql = "SELECT id,gridindent FROM ".KN_MERCHANT_SAMPLE_BOM_INDENT." WHERE requestid = '$VarId' ";
        $ObjResult = $this->db->query($VarSql)->result();
        //echo '<pre>'; print_r($ObjResult); die('die');
        return $ObjResult;
    }

    public function getAllRequestDetails($VarId, $VarCompanyId) {
        if ($VarId <> '' && $VarCompanyId <> '') {
            $VarRequestListTypeSql = "SELECT request_type_dept FROM " . KN_ALLREQUEST . " WHERE id = '$VarId' AND companyid = '$VarCompanyId' ";
            $RequestRes = $this->db->query($VarRequestListTypeSql)->result();
            $VarRequestListType = $RequestRes[0]->request_type_dept;
            if ($VarRequestListType == "CAD") {
                //CAD req
                $VarCadSql = "SELECT a.id,c.requestrefid,a.requesttype,a.cutoffdatetime,a.merchantid,a.queueno_assigned_date,a.approvaltype,a.queueno,
a.request_type_dept,a.datecreated,a.requestrefno,a.merchantnote,a.orderid,a.mgmtcurrentstatus,a.deptcurrentstatus,a.mgmtremarks,c.combo,c.component,c.color,
c.sizespeccode,c.purpose,c.category,c.requiredsize,c.knittingtype,c.dyeingtype,c.compactingtype,c.ponoenqrefno,c.datecreated as cadreqcreated,
c.BuyersOriginalSample,c.BuyersComments,c.AppGradMeasChart,c.CompleteArtwork,c.MeasureDetailsArtwork,c.caddeptremarks,c.dateupdated,c.jobschedule,
c.prevcadrefno,c.cadqueuecompletestatus,oe.isriorcode,oe.stylenamerefno,oe.styledesc,oe.datecreated as wipdatecreated FROM
 " . KN_ALLREQUEST . " AS a INNER JOIN " . KN_CAD_REQUEST . " AS c ON a.id = c.requestrefid INNER JOIN " . KN_ORDER_ENQUIRY . " AS oe
 ON a.orderid = oe.id WHERE a.id = '$VarId' AND a.companyid = '$VarCompanyId' ";
                $ArrFinalData = $this->db->query($VarCadSql)->row();
            } elseif ($VarRequestListType == "SAMPLE") {
                $VarSampleSql = "SELECT a.id,s.requestrefid,a.requesttype,a.cutoffdatetime,a.merchantid,a.queueno_assigned_date,
a.requestrefno,a.merchantnote,s.BuyersOriginalSample,s.BuyersComments,s.AppGradMeasChart,s.CompleteArtwork,s.MeasureDetailsArtwork,a.orderid,
a.mgmtcurrentstatus,
a.deptcurrentstatus,a.mgmtremarks,a.deptremarks,a.queueno,a.jobschedule,a.approvaltype,a.request_type_dept,a.datecreated,
s.cadqueuecompletestatus,oe.isriorcode,si.materialindentrefno,si.issuedtodept,si.indentcutoffdt 
FROM 
 " . KN_ALLREQUEST . " AS a INNER JOIN " . KN_SAMPLE_REQUEST . " AS s ON a.id = s.requestrefid INNER JOIN " . KN_ORDER_ENQUIRY . " AS oe
 ON a.orderid = oe.id INNER JOIN " . KN_SAMPLEREQUESTINDENTDETAILS ." AS si ON a.id = si.requestid INNER JOIN ".KN_MERCHANT_SAMPLE_CAD_INDENT." AS ci ON ci.requestid = a.id 
 INNER JOIN ".KN_MERCHANT_SAMPLE_FAB_INDENT." AS fi ON fi.requestid = a.id INNER JOIN ".KN_MERCHANT_SAMPLE_BOM_INDENT." AS bi ON bi.requestid = a.id WHERE a.id = '$VarId' AND a.companyid = '$VarCompanyId' LIMIT 1";
                $ArrFinalData = $this->db->query($VarSampleSql)->row();
            } elseif ($VarRequestListType == "BOM") {
                $VarBomSql = "SELECT a.id,a.requesttype,a.cutoffdatetime,a.merchantid,a.queueno_assigned_date,
a.requestrefno,a.merchantnote,a.orderid,a.mgmtcurrentstatus,a.deptcurrentstatus,a.mgmtremarks,a.deptremarks,a.queueno,
a.dateupdated,a.jobschedule,a.approvaltype,mgmtid,alldeptid,a.request_type_dept,a.datecreated,oe.isriorcode,oe.stylenamerefno,oe.styledesc,oe.brandbuyerid,
oe.datecreated as wipdatecreated FROM
 " . KN_ALLREQUEST . " AS a INNER JOIN " . KN_ORDER_ENQUIRY . " AS oe
 ON a.orderid = oe.id WHERE a.id = '$VarId' AND a.companyid = '$VarCompanyId' LIMIT 1";
                $ArrFinalData = $this->db->query($VarBomSql)->row();
            }
            return $ArrFinalData;
        }
    }

    public function getbompurchaseindentapprovalRequestDetails($VarId, $VarCompanyId) {
        if ($VarId <> '' && $VarCompanyId <> '') {
            $VarRequestListTypeSql = "SELECT request_type_dept FROM " . KN_ALLREQUEST . " WHERE id = '$VarId' AND companyid = '$VarCompanyId' ";
            $RequestRes = $this->db->query($VarRequestListTypeSql)->result();

            $VarRequestListType = $RequestRes[0]->request_type_dept;
            if ($VarRequestListType == "BOM") {
                $VarBomSql = "SELECT a.id,b.requestrefid,a.requesttype,a.cutoffdatetime,a.merchantid,a.queueno_assigned_date,
b.purpose,a.requestrefno,a.merchantnote,a.orderid,a.mgmtcurrentstatus,a.deptcurrentstatus,a.mgmtremarks,a.deptremarks,a.queueno,
a.dateupdated,a.jobschedule,a.approvaltype,mgmtid,alldeptid,a.request_type_dept,a.datecreated,oe.isriorcode,oe.stylenamerefno,oe.styledesc,
oe.datecreated as wipdatecreated,b.bompirequestgrid_tblname,b.articletypeid FROM
 " . KN_ALLREQUEST . " AS a INNER JOIN " . KN_BOMPURCHASEREQ . " AS b ON a.id = b.requestrefid INNER JOIN " . KN_ORDER_ENQUIRY . " AS oe
 ON a.orderid = oe.id WHERE a.id = '$VarId' AND a.companyid = '$VarCompanyId' LIMIT 1";
                $ArrFinalData = $this->db->query($VarBomSql)->row();
                return $ArrFinalData;
            }
        }
    }

/*    public function saveBomPurchaseApprRequest($ArrUpdateData = array(), $VarTblName = '', $VarRequestRefId = '') {
        $VarNumRowsIns = $this->db->insert_batch($VarTblName, $ArrUpdateData);
        if ($VarNumRowsIns) {
            if (!empty($VarRequestRefId)) {
                $this->db->select('bompirequestgrid_tblname');
                $Qry = $this->db->get_where(KN_BOMPURCHASEREQ, array('requestrefid' => $VarRequestRefId));
                $VarCheckDynamicTbl = $Qry->result();
                if (empty($VarCheckDynamicTbl[0]->bompirequestgrid_tblname)) {
                    $this->db->where('requestrefid', $VarRequestRefId);
                    $this->db->update(KN_BOMPURCHASEREQ, array('bompirequestgrid_tblname' => $VarTblName));
                } else {

                }
            }
            return $VarNumRowsIns;
        }
    }*/

    public function getBomPurchaseRefNo($VarCompanyId = '', $VarRequestId = '') {
        $VarWhere = '';
        $ArrWhere = array();
        if (!empty($VarCompanyId)) {
            $ArrWhere[] = "companyid=" . $VarCompanyId;
        }
        if (!empty($VarRequestId)) {
            $ArrWhere[] = "bompurchaseindreqid=" . $VarRequestId;
        }
        $ArrWhere[] = "status = 1";
        if (!empty($ArrWhere)) {
            $VarWhere = implode(' AND ', $ArrWhere);
        }
        $VarSql = "SELECT id,purchaseindentno FROM " . KN_BOMPURCHASEINDENTINVOICE . " WHERE $VarWhere";
        return $this->db->query($VarSql)->result();
    }

/*    public function saveBomPurchaseIndentInvoice($ArrUpdate = array(), $VarSavingIssuePIDynamicTbl = array(), $VarTblName = '', $VarRequestRefId = '') {
        $this->db->insert(KN_BOMPURCHASEINDENTINVOICE, $ArrUpdate);
        $this->db->truncate($VarTblName);
        $VarDynamicTblRes = $this->saveBomPurchaseApprRequest($VarSavingIssuePIDynamicTbl, $VarTblName, $VarRequestRefId);
        if ($VarDynamicTblRes) {
            $ArrResult['errcode'] = 1;
            $ArrResult['msg'] = '';
            return $ArrResult;
        }
    }*/

    public function getBomPurchaseInvoiceGrid($VarId = '', $VarCompanyId = '') {
        $VarSql = "SELECT invoicegrid FROM " . KN_BOMPURCHASEINDENTINVOICE . " WHERE bompurchaseindreqid = '$VarId' AND companyid = '$VarCompanyId' ";
        return $this->db->query($VarSql)->result();
    }

    public function getVendorInfo($VarVendorId = '', $VarCompanyId = '', $VarStatus = '') {
        $ArrWhere = array();
        if ($VarVendorId <> '') {
            $ArrWhere[] = "id = '$VarVendorId' ";
        }
        if ($VarCompanyId <> '') {
            $ArrWhere[] = "companyid = '$VarCompanyId' ";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "status = '$VarStatus' ";
        } else {
            $ArrWhere[] = "status IN(1,2)";
        }
        $VarWhere = '';
        if (count($ArrWhere) >= 1) {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSql = "SELECT id,vendorname FROM " . KN_MASTER_BOM_VENDOR . " WHERE $VarWhere";
        return $this->db->query($VarSql)->result();
    }
    public function updateBomPiApprReq($ArrUpdateData, $VarTblName) {
        $this->db->select('id');
        $ResId = $this->db->get_where($VarTblName, array('requestforapprovalcheckbox' => '1'))->result();
        if (!empty($ResId)) {
            foreach ($ResId as $item) {
                $ArrId[] = $item->id;
            }
            foreach ($ArrUpdateData as $keyId => $val) {
                if (!empty($ArrId[$keyId])) {
                    $this->db->update($VarTblName, array('approvalstatus' => $val['approvalstatus'], 'approvedby' => $val['approvedby'], 'dateupdated' => $val['dateupdated']), array('id' => $ArrId[$keyId]));
                }
            }
            return $this->db->affected_rows();
        }
    }

    public function saveBompiProcessTrack($ArrSaveData = array(), $VarCompanyId = '', $VarDate) {
        $ArrSaveData['companyid'] = $VarCompanyId;
        //$ArrSaveData['bompurchaseinvoiceid'] = $VarCompanyId;
        //$ArrSaveData['bompurchaseindreqid'] = $VarCompanyId;
        $ArrSaveData['datecreated'] = $VarDate;
        $ArrSaveData['dateupdated'] = $VarDate;
        $this->db->insert(KN_BOMPI_PROCESSTRACK, $ArrSaveData);
        $VarInsertId = $this->db->insert_id();
        if ($VarInsertId) {
            $ArrResult['errcode'] = 1;
            $ArrResult['msg'] = '';
            $ArrResult['id'] = $VarInsertId;
            $ArrResult['eid'] = urlencode(base64_encode($VarInsertId));
        }
        return $ArrResult;
    }

    public function saveBompideliveryfollowup($ArrSaveData = array(), $VarCompanyId = '', $VarDate) {
        $ArrSaveData['companyid'] = $VarCompanyId;
        //$ArrSaveData['bompurchaseinvoiceid'] = $VarCompanyId;
        //$ArrSaveData['bompurchaseindreqid'] = $VarCompanyId;
        $ArrSaveData['datecreated'] = $VarDate;
        $ArrSaveData['dateupdated'] = $VarDate;
        $this->db->insert(KN_BOMPI_DELI_FOLLLUP, $ArrSaveData);
        $VarInsertId = $this->db->insert_id();
        if ($VarInsertId) {
            $ArrResult['errcode'] = 1;
            $ArrResult['msg'] = '';
            $ArrResult['id'] = $VarInsertId;
            $ArrResult['eid'] = urlencode(base64_encode($VarInsertId));
        }
        return $ArrResult;
    }

    public function saveBomInvoiceDetailsSendReq($ArrSaveData = array(), $VarCompanyId = '', $VarDate) {
        $ArrSaveData['companyid'] = $VarCompanyId;
        //$ArrSaveData['bompurchaseinvoiceid'] = $VarCompanyId;
        //$ArrSaveData['bompurchaseindreqid'] = $VarCompanyId;
        $ArrSaveData['datecreated'] = $VarDate;
        $ArrSaveData['dateupdate'] = $VarDate;
        $this->db->insert(KN_BOM_INVOICESENDREQUEST, $ArrSaveData);
        $VarInsertId = $this->db->insert_id();
        if ($VarInsertId) {
            $ArrResult['errcode'] = 1;
            $ArrResult['msg'] = '';
            $ArrResult['id'] = $VarInsertId;
            $ArrResult['eid'] = urlencode(base64_encode($VarInsertId));
        }
        return $ArrResult;
    }

    public function saveBomItemRecdInvoiceDetails($VarBomPurIndentId,$VarOrderId,$bomPurRequestId,$invoiceJxl,$VarBomItemId,$VarItemRefNo,$VarDatetime,$VarPriKey) {
        $ArrInsert = array('companyid'=>$this->companyid,'orderid'=>$VarOrderId,'bompurchasereqid'=>$bomPurRequestId,'bompurindentid'=>$VarBomPurIndentId,
            'itemrecdinvoicegrid'=>$invoiceJxl,'itemrefno'=>$VarItemRefNo,'bomItemId'=>$VarBomItemId);
        if(!empty($VarPriKey)) {
			$this->db->where('id',$VarPriKey);
			$this->db->update(KN_BOMSTORE_ITEMRECD_DETAILS_INVOICES,$ArrInsert);
			return $this->db->affected_rows();
		}
		else {
			$this->db->insert(KN_BOMSTORE_ITEMRECD_DETAILS_INVOICES,$ArrInsert);
			return $this->db->insert_id();
		}

    }

    public function getItemizedBomRecdInvoice($VarBomPurIndentId = '', $VarItemRefNo = '') {
        return $this->db->select('id,itemrecdinvoicegrid,itemrefno,itemverifyauthstatus,qtyverifyauthstatus,
            qtyverifyauthstatus,invoiceverifyauthstatus,mgmtlotapprovalstatus,itemreadyauthstatus')
            ->from(KN_BOMSTORE_ITEMRECD_DETAILS_INVOICES)
            ->where(array('bompurindentid'=>$VarBomPurIndentId,'itemrefno'=>$VarItemRefNo))
            ->get()
            ->row();
    }

    public function chechUser($VarEmailId = '', $VarPassword = '', $VarUserType = '') {
        $VarSql = "SELECT id FROM " . KN_USERS . " WHERE username = '$VarEmailId' AND password = '$VarPassword' AND 
        usertype = '$VarUserType' AND status = '1' ";
        return $this->db->query($VarSql)->row();
    }

    public function saveBomStoreLotApprCookies($ArrData=array(),$updateFlag) {
        if(empty($updateFlag)) {
            $this->db->insert(KN_BOMSTORE_ITEMRECD_DETAILS_LOT_APPROVAL,$ArrData);
        }
        else {
            /*$this->db->where('id', $PrimaryKey);
            $this->db->where('bompurindentid', $ArrData['bompurindentid']);
            $this->db->where('itemrefno', $ArrData['itemrefno']);
            $this->db->update(KN_BOMSTORE_ITEMRECD_DETAILS_LOT_APPROVAL, $ArrData);*/
        }
        return $this->db->affected_rows();
    }

    public function getBomStoreLotApprovalData($VarPurIndId,$VarItemRefNo) {
        $ArrWhere = [];
    	if(!empty($VarPurIndId)) {
    		$ArrWhere[] = "bompurindentid = '$VarPurIndId' ";
		}
		if(!empty($VarItemRefNo)) {
			$ArrWhere[] = "itemrefno = '$VarItemRefNo' ";
		}
		if(count($ArrWhere) >= 1) {
			$VarWhere = implode(" AND ", $ArrWhere);
		}
		$Qry = "SELECT * FROM ".KN_BOMSTORE_ITEMRECD_DETAILS_LOT_APPROVAL." WHERE ".$VarWhere;
    	return $this->db->query($Qry)->result();
	}

    public function bomInvoiceAndLotApproval($VarPurIndId,$VarBomItemId) {
        $ArrWhere = [];
        if(!empty($VarPurIndId)) {
            $ArrWhere[] = "inv.bompurindentid = '$VarPurIndId' ";
        }
        if(!empty($VarBomItemId)) {
            $ArrWhere[] = "inv.bomItemId = '$VarBomItemId' ";
        }
        if(count($ArrWhere) >= 1) {
            $VarWhere = implode(" AND ", $ArrWhere);
        }
        $Sql = "SELECT apr.itemrefno,itemrecdinvoicegrid,apr.itemreadyauthstatus FROM ".KN_BOMSTORE_ITEMRECD_DETAILS_INVOICES." 
        AS inv INNER JOIN ".KN_BOMSTORE_ITEMRECD_DETAILS_LOT_APPROVAL." AS apr ON inv.bompurindentid = apr.bompurindentid WHERE ".$VarWhere;
        return $this->db->query($Sql)->result();
        /*$Qry = $this->db
            ->select()
            ->from(KN_BOMSTORE_ITEMRECD_DETAILS_INVOICES.' AS in')
            ->where(['in.bompurindentid'=>$VarPurIndId,'in.bomItemId'=>$VarBomItemId])
            ->join(KN_BOMSTORE_ITEMRECD_DETAILS_LOT_APPROVAL.' AS apr','in.bompurindentid = apr.bompurindentid')->get();*/

        //return $Qry->result();
        //$Qry = "SELECT * FROM ".KN_BOMSTORE_ITEMRECD_DETAILS_LOT_APPROVAL." WHERE ".$VarWhere;
        //$Res = $this->db->query($Qry);
        //return $Res;
    }


    public function updateSend2StockListBomIndent($ArrUpdate,$ArrBomItemId,$sampleRequestId,$jsonBomIndent) {
        foreach ($ArrBomItemId as $itemId) {
            $ArrUpdate['bom_item_id'] = $itemId;
            $this->db->insert(KN_SEND_BOMIND2STORES_STOCKLIST,$ArrUpdate);
        }
        //$this->db->update(KN_MERCHANT_SAMPLE_BOM_INDENT,array('gridindent'=>$jsonBomIndent),"requestid = '$sampleRequestId' ");
        return $sampleRequestId;
    }

    public function saveNewItemList($ArrData) {
        $this->db->insert(KN_STORES_NEW_ITEM_LIST, $ArrData);
        return $this->db->insert_id();
    }

/*    public function getPurchaseIndentNo($OrderId) {
        $VarPiSql = "SELECT MAX(purchaseindent_no) as lastpino FROM " . KN_BOM_PURCHASEINDENT . " WHERE orderid = '$OrderId' AND status = '1' ";
        $Res = $this->db->query($VarPiSql)->row();
        return $Res;
    }*/

    public function getPrevCadRefNo($VarCompanyId = '', $VarOrderId = '') {
        $VarRequestTypeDept = "CAD";
        $ArrRefNo = [];
        $this->db->select('jsondatagrid');
        $qry = $this->db->get_where(KN_ALLREQUEST, array('companyid' => $VarCompanyId, 'orderid' => $VarOrderId,
            'request_type_dept' => $VarRequestTypeDept));
        if (empty($qry->result())) {
            return array('0');
        } else {
            foreach ($qry->result() as $item) {
                $ArrCadRefNo = $item->jsondatagrid;
                $ArrJson = json_decode($ArrCadRefNo);
                if (!empty($ArrJson)) {
                    foreach ($ArrJson as $json) {
                        if (!empty($json[9]))
                            $ArrRefNo[] = $json[9];
                        else
                            $ArrRefNo[] = '';
                    }
                }
            }
            if (empty($ArrRefNo)) return array('0');
            else return $ArrRefNo;
        }
    }

    public function getPrevRefNo($VarRequestType = '', $VarCompanyId = '', $VarOrderId = '') {
        if ($VarRequestType == 1) {
            $this->db->select('jsondatagrid');
            $qry = $this->db->get_where(KN_ALLREQUEST, array('companyid' => $VarCompanyId, 'orderid' => $VarOrderId,
                'request_type_dept' => $VarRequestType));
            if (empty($qry->result())) {
                return array('0');
            } else {
                foreach ($qry->result() as $item) {
                    $ArrCadRefNo = $item->jsondatagrid;
                }
                $ArrJson = json_decode($ArrCadRefNo);
                if (!empty($ArrJson)) {
                    foreach ($ArrJson as $json) {
                        if (!empty($json[9]))
                            $ArrPrevRefNo[] = $json[9];
                        else
                            $ArrPrevRefNo[] = '';

                    }
                }
                if (empty($ArrPrevRefNo)) return array('0');
                else return $ArrPrevRefNo;
            }
        } elseif ($VarRequestType == 2) {
            $this->db->select('jsondatagrid');
            $qry = $this->db->get_where(KN_ALLREQUEST, array('companyid' => $VarCompanyId, 'orderid' => $VarOrderId,
                'request_type_dept' => $VarRequestType));
            if (empty($qry->result())) {
                return array('-');
            } else {
                foreach ($qry->result() as $item) {
                    $ArrCadRefNo = $item->jsondatagrid;
                    $ArrJson = json_decode($ArrCadRefNo);
                    if (!empty($ArrJson)) {
                        foreach ($ArrJson as $json) {
                            if (!empty($json[11]))
                                $ArrPrevRefNo[] = $json[11];
                            else
                                $ArrPrevRefNo[] = '';

                        }
                    }
                }
                if (empty($ArrPrevRefNo)) return array('-');
                else return $ArrPrevRefNo;
            }
        }
    }

    public function datatablesBomPiApprListQry($ApprovedStatusFlag) {
        $this->db->select('pi.id,a.isriorcode,v.vendorname,purchaseindent_no,vendorid,articletype,pi.status,
        DATE_FORMAT(cutoffdatetime,"%d-%m-%Y %H:%i:%s") as cutoffdatetime,DATE_FORMAT(pi.dateupdated,"%d-%m-%Y %H:%i:%s") as dateupdated,
        approvedstatus,purchasedeptid,oe.isriorcode,brandname,buyername,DATE_FORMAT(pi.datecreated,"%d-%m-%Y %H:%i:%s") as datecreated,
        u.contactname,requirementforbom');
        $this->db->from(KN_BOM_PURCHASEINDENT.' AS pi');
        $this->db->join(KN_ALLREQUEST.' AS a','a.id = pi.bompurrequestid');
        $this->db->where('a.companyid',$this->companyid);
        //$this->db->where('pi.approvedstatus',$ApprovedStatusFlag);
        $this->db->join(KN_MASTER_BOM_VENDOR.' AS v','v.id = pi.vendorid');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandbuyerid');
        //$this->db->join(KN_MASTER_BUYER.' AS by','br.buyerid = by.id');
        $this->db->join(KN_USERS.' AS u','pi.purchasedeptid = u.id');

        $bomPiApprListColOrder = array('pi.dateupdated','oe.isriorcode', 'oe.brandbuyerid', 'a.id', 'a.datecreated', 'a.cutoffdatetime', 'a.merchantid',
            'a.approvaltype', 'a.mgmtid', 'a.dateupdated', 'a.status');
        $bomPiApprListColSearch = array('oe.isriorcode', 'oe.brandbuyerid', 'a.id', 'a.datecreated', 'a.cutoffdatetime', 'a.merchantid',
            'a.approvaltype', 'a.mgmtid', 'a.dateupdated', 'a.status');
        $bomPiApprListOrder = array('pi.dateupdated' => 'desc');

        $i = 0;
        foreach ($bomPiApprListColSearch as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($bomPiApprListColSearch) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if(isset($_POST['order'])) {
            $this->db->order_by($bomPiApprListColOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order)) {
            $order = $bomPiApprListColOrder;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function bomPiApprListDatatablesAjax($ApprovedStatusFlag) {
        $this->datatablesBomPiApprListQry($ApprovedStatusFlag);
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function countBomPiApprFiltered($ApprovedStatusFlag) {
        $this->datatablesBomPiApprListQry($ApprovedStatusFlag);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function countAllBomPiAppr($ApprovedStatusFlag) {
        $this->db->from(KN_BOM_PURCHASEINDENT.' AS pi');
        $this->db->join(KN_ALLREQUEST.' AS a','a.id = pi.bompurrequestid');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->join(KN_MASTER_BOM_VENDOR.' AS v','v.id = pi.vendorid');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandbuyerid');
       // commented by myself regards new brand form
       // $this->db->join(KN_MASTER_BUYER.' AS by','br.buyerid = by.id');
        $this->db->join(KN_USERS.' AS u','a.merchantid = u.id');
        return $this->db->count_all_results();
    }

    public function OLD_bulkInsertBomCompanyBased($VarOrderId='') {
		/**For BOM for PI
		 * Since each item need a id which is in jxl grid
		 **/
		$VarTblName = 'bom_companyid_'.$this->companyid;
		$VarCreateTblQry = "CREATE TABLE IF NOT EXISTS $VarTblName (
                    `id` bigint NOT NULL AUTO_INCREMENT,`orderid` bigint NOT NULL,`bom` varchar(200) NOT NULL,
                     PRIMARY KEY (`id`), KEY `orderid` (`orderid`),UNIQUE KEY `bom` (`bom`))";
		$this->db->query($VarCreateTblQry);
		//$VarCheckQry = "SELECT count(*) as trec FROM ".$VarTblName." WHERE orderid = '$VarOrderId' ";
		$ObjRes = $this->db->query($VarCheckQry)->row();
		if($ObjRes->trec == 0) {
			$jsonFromTwelfthRes = $this->fnGetAllTableInfo(ORDERENTRY_BOMELEVENTH_TBL,'jsondatagrid',array('companyid'=>$this->companyid,
				'referenceid'=>$VarOrderId));
			$ArrOne = json_decode($jsonFromTwelfthRes[0]['jsondatagrid']);
			$ArrTwo = json_decode($jsonFromTwelfthRes[1]['jsondatagrid']);
			$ArrAll = array_merge($ArrOne,$ArrTwo);
			foreach ($ArrAll as $item) {
				$ArrAllBomData[] = array('orderid'=>$VarOrderId,'bom' => $item[0].'|#|'.$item[1].'|#|'.$item[2].'|#|'.$item[3].'|#|'.$item[4].'|#|'.$item[5]);
			}
			$VarInsertRes = $this->db->insert_batch($VarTblName,$ArrAllBomData);
		}
	}

	public function bulkInsertBomCompanyBased($VarOrderId) {
        $VarTblName      = 'bom_companyid_'.$this->companyid;
        /*$VarCreateTblQry = "CREATE TABLE IF NOT EXISTS $VarTblName (
                    `id` bigint NOT NULL AUTO_INCREMENT,`orderid` bigint NOT NULL,`bom` varchar(200) NOT NULL,
                     PRIMARY KEY (`id`), KEY `orderid` (`orderid`),UNIQUE KEY `bom` (`bom`))";*/
        $VarCreateTblQry = "CREATE TABLE IF NOT EXISTS $VarTblName (
                            `id` bigint NOT NULL AUTO_INCREMENT,
                            `orderid` bigint NOT NULL,
                            `itemdesc` varchar(100) NOT NULL,
                            `garmentsize` varchar(5) NOT NULL,
                            `itemcode` varchar(60) NOT NULL,
                            `itemcolorcode` varchar(60) NOT NULL,
                            `sizeordim` varchar(10) NOT NULL,
                            `uom1` varchar(15) NOT NULL,
                            `bomreqdqty` varchar(10) NOT NULL,
                            `planbomqty` varchar(10) NOT NULL,
                            `uom2` varchar(15) NOT NULL,
                            PRIMARY KEY (`id`), KEY `orderid` (`orderid`))";
        $this->db->query($VarCreateTblQry);
///*$ArrInsBom[] = array('orderid'=>$VarOrderId,'sampleno'=>$sampleNo,'requestid'=>$VarRequestId,
//                        'itemdesc'=>$jxl[0],'garmentsize'=>$jxl[1],'itemcode'=>$jxl[2],'itemcolorcode'=>$jxl[3],
//                        'sizeordim'=>$jxl[4],'uom1'=>$jxl[5],'materialIndentQty'=>$jxl[6],'uom2'=>$jxl[7]);*/
        $checkExists = $this->db->from($VarTblName)->where(['orderid'=>$VarOrderId])->count_all_results();

        if($checkExists == 0) {
            $jsonBomConsolidated = $this->fnGetAllTableInfo(KN_BOM_CONSOLIDATED,'jsondatagrid',array('companyid'=>$this->companyid,
                'referenceid'=>$VarOrderId));
            if(!empty($jsonBomConsolidated[0]['jsondatagrid'])) {
                $ArrOne = json_decode($jsonBomConsolidated[0]['jsondatagrid']);
            }
            if(!empty($jsonBomConsolidated[1]['jsondatagrid'])) {
                $ArrTwo = json_decode($jsonBomConsolidated[1]['jsondatagrid']);
            }
            $ArrAll = array_merge($ArrOne,$ArrTwo);
            foreach ($ArrAll as $item) {
                $ArrAllBomData[] = array('orderid'=>$VarOrderId,'itemdesc' => $item[0],'garmentsize'=>$item[1],
                    'itemcode'=>$item[2],'itemcolorcode'=>$item[3],'sizeordim'=>$item[4],'uom1'=>$item[5],
                    'bomreqdqty'=>$item[6],'planbomqty'=>$item[9],'uom2'=>$item[10]);
            }
            $VarInsertRes = $this->db->insert_batch($VarTblName,$ArrAllBomData);
        }
    }

	public function getAllBom($VarOrderId,$item='',$size='',$itemcode='',$itemcolorcode='',$sizeordim='',$uom1='',$planbomqty='',$uom2='',$VarBomItemId='') {
        $VarTblName = 'bom_companyid_'.$this->companyid;
        $ArrWhere = [];
        if(!empty($VarOrderId)) {
            $ArrWhere[]             = "orderid = '$VarOrderId' ";
        }
        if(!empty($item)) {
            $ArrWhere[]             = "itemdesc = '$item' ";
        }
        if(!empty($size)) {
            $ArrWhere[]             = "garmentsize = '$size' ";
        }
        if(!empty($itemcode)) {
            $ArrWhere[]             = "itemcode = '$itemcode' ";
        }
        if(!empty($itemcolorcode)) {
            $ArrWhere[]             = "itemcolorcode = '$itemcolorcode' ";
        }
        if(!empty($sizeordim)) {
            $ArrWhere[]             = "sizeordim = '$sizeordim' ";
        }
        if(!empty($uom1)) {
            $ArrWhere[]             = "uom1 = '$uom1' ";
        }
        /*if(!empty($bomreqdqty)) {
            $ArrWhere[]             = "bomreqdqty = '$bomreqdqty' ";
        }*/
        if(!empty($planbomqty)) {
            $ArrWhere[]             = "planbomqty = '$planbomqty' ";
        }
        if(!empty($uom2)) {
            $ArrWhere[]             = "bom2 = '$uom2' ";
        }
        if(!empty($VarBomItemId)) {
            $ArrWhere[]             = "id = '$VarBomItemId' ";
        }
        if(count($ArrWhere) >= 1) {
            $VarWhere               = implode(" AND ",$ArrWhere);
        }
        $Sql          = "SELECT id,itemdesc,garmentsize,itemcode,itemcolorcode,sizeordim,uom1,bomreqdqty,planbomqty,uom2 FROM ".$VarTblName." WHERE ".$VarWhere;
        $Res = $this->db->query($Sql)->result();
        return $Res;
        //echo '<pre>'; print_r($Res); die('die');
        /*if(!empty($VarItem)) {
            $ArrBomItems = $this->fnGetAllTableInfo($VarTblName,'id',array('orderid'=>$VarOrderId,'itemdesc'=>$VarItem));
            return $ArrBomItems;
        }
        else {
            $ArrAllBomDataGroup = [];
            $ArrBomItems = $this->fnGetAllTableInfo($VarTblName,'id,bom',array('orderid'=>$VarOrderId));
            foreach ($ArrBomItems as $arr_bom_item) {
                $ArrAllBomDataGroup[$arr_bom_item['id']] = $arr_bom_item['bom'];
            }
            return $ArrAllBomDataGroup;
        }*/
	}

	public function updateCurrency($ArrResult = array(),$VarMysqlDatetime) {

	}

	public function fnChangeWipStatus($ArrCheckboxIds, $optValue) {
        $this->db->where_in('id', $ArrCheckboxIds);
        $this->db->update('kn_order_enquiry', array('status' => $optValue));

        $this->db->where_in('orderid', $ArrCheckboxIds);
        $this->db->update('kn_allrequest', array('status' => $optValue));
        return $this->db->affected_rows();
    }

    public function changeReqStatus($ArrCheckboxIds, $optValue, $tblName, $idName) {
        $this->db->where_in($idName, $ArrCheckboxIds);
        $this->db->update($tblName, array('flag' => $optValue));
        //print_r($this->db->last_query());
        //file_put_contents("error_log", print_r($this->db->last_query(), true));
        //die;
        return $this->db->affected_rows();
       
    }
    public function changeReqStatusactives($tblName, $idName,$status) {
        $VarSql = "SELECT * FROM " . $tblName . " WHERE".$idName. "=" . $status;
        return $this->db->query($VarSql)->row();
       
    }

    public function getUserDesignation($VarUserTypeId='',$VarDesignationId='',$VarStatus='') {
        if ($VarUserTypeId <> '') {
            $ArrWhere[] = "usertypeid = ".$VarUserTypeId;
        }
        if($VarDesignationId <> '') {
            $ArrWhere[] = "designationid = ".$VarDesignationId;
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "desgn_status = ".$VarStatus;
        } else {
            $ArrWhere[] = "desgn_status IN (1,2)";
        }
        if(!empty($ArrWhere)) {
            $VarWhere = "WHERE ". implode(" AND ",$ArrWhere);
        }
        $VarSql = "SELECT designationid,usertypeid,desgn FROM ".KN_USER_DESGN." $VarWhere";
        return $this->db->query($VarSql)->result_array();
    }

    function getMgmtDetails($cid, $mId) {
        $VarSql = "SELECT * FROM " . KN_USERS . " as u 
        INNER JOIN ".KN_USER_DETAILS." AS ud ON u.id = ud.userid WHERE id = ".$mId;
        $ArrRes = $this->db->query($VarSql)->result_array();
        return $ArrRes;
    }
    function getsubscriberid($mId) {
        $VarSql = "SELECT * FROM " . KN_USERS . "  WHERE id = " . $mId;
            return $this->db->query($VarSql)->row();
    }
    function getcompanyprefix($mId) {
        $VarSql = "SELECT * FROM " . KN_USERS . "  WHERE subscriber_id = " . $mId." and usertype = '1' ";
            return $this->db->query($VarSql)->row();
    }
    function getOrderEnquiryCount_test($subscriber_id) {
    $currentYear = date('Y');
    $sql = "SELECT COUNT(*) AS total FROM " . KN_ORDER_ENQUIRY . " WHERE subscriberid = ? AND orderstatus >= 2 AND YEAR(datecreated) = ?";
    $query = $this->db->query($sql, [$subscriber_id, $currentYear]);
    $result = $query->row();
   
    return $result->total;
}
function getOrderEnquiryCount($subscriber_id) {
    // Get current year and calculate start & end dates
    $currentMonth = date('n'); // 1 to 12
    $currentYear = date('Y');

    // If current month is Jan–Mar, use previous year as fiscal start
    if ($currentMonth < 4) {
        $startYear = $currentYear - 1;
    } else {
        $startYear = $currentYear;
    }

    $startDate = $startYear . '-04-01'; // April 1st of startYear
    $endDate = ($startYear + 1) . '-03-31'; // March 31st of next year

    $sql = "SELECT COUNT(*) AS total 
    FROM " . KN_ORDER_ENQUIRY . " 
     WHERE subscriberid = ? AND orderstatus >= 2 AND datecreated BETWEEN ? AND ?";

    $query = $this->db->query($sql, [$subscriber_id, $startDate, $endDate]);
    $result = $query->row();

    return isset($result->total) ? $result->total : 0;
}
// function getcadrequestCountcq($subscriber_id) {
//     // Get current year and calculate start & end dates
//     $currentMonth = date('n'); // 1 to 12
//     $currentYear = date('Y');

//     // If current month is Jan–Mar, use previous year as fiscal start
//     if ($currentMonth < 4) {
//         $startYear = $currentYear - 1;
//     } else {
//         $startYear = $currentYear;
//     }

//     $startDate = $startYear . '-04-01'; // April 1st of startYear
//     $endDate = ($startYear + 1) . '-03-31'; // March 31st of next year

//     $sql = "SELECT COUNT(*) AS total 
//     FROM tbl_request 
//      WHERE subscriberid = ? AND MAX(queue_no)+1 AND datecreated BETWEEN ? AND ?";

//     $query = $this->db->query($sql, [$subscriber_id, $startDate, $endDate]);
//     $result = $query->row();

//     return isset($result->total) ? $result->total : 0;
// }

function getcadrequestCountcq($subscriber_id) {
    // Get current year and calculate start & end dates
    $currentMonth = date('n'); // 1 to 12
    $currentYear = date('Y');

    // If current month is Jan–Mar, use previous year as fiscal start
    if ($currentMonth < 4) {
        $startYear = $currentYear - 1;
    } else {
        $startYear = $currentYear;
    }

    $startDate = $startYear . '-04-01'; // April 1st of startYear
    $endDate = ($startYear + 1) . '-03-31'; // March 31st of next year

    // SQL query to count the requests within the fiscal year range
    $sql = "SELECT COUNT(*) AS total 
            FROM tbl_request 
            WHERE subscriberid = ? AND TYPE = 1 AND  deprt_approval = 1 AND  log  BETWEEN ? AND ?";

    $query = $this->db->query($sql, [$subscriber_id, $startDate, $endDate]);
    //PRINT_R($query);
    $result = $query->row();

    return isset($result->total) ? $result->total : 0;
}


function getcadrequestCountcr($subscriber_id) {
    // Get current year and calculate start & end dates
    $currentMonth = date('n'); // 1 to 12
    $currentYear = date('Y');

    // If current month is Jan–Mar, use previous year as fiscal start
    if ($currentMonth < 4) {
        $startYear = $currentYear - 1;
    } else {
        $startYear = $currentYear;
    }

    $startDate = $startYear . '-04-01'; // April 1st of startYear
    $endDate = ($startYear + 1) . '-03-31'; // March 31st of next year

    // SQL query to count the requests within the fiscal year range
    $sql = "SELECT COUNT(*) AS total 
        FROM tbl_request_cad rc
        INNER JOIN tbl_request r ON rc.request_id = r.request_id
        WHERE r.subscriberid = ? AND  r.deprt_approval = 1 AND rc.log BETWEEN ? AND ?";
        
$query = $this->db->query($sql, [$subscriber_id, $startDate, $endDate]);
    //PRINT_R($query);
    //echo $this->db->last_query();
    $result = $query->row();

    return isset($result->total) ? $result->total : 0;
}

function getsamplerequestCountsq($subscriber_id) {
    // Get current year and calculate start & end dates
    $currentMonth = date('n'); // 1 to 12
    $currentYear = date('Y');

    // If current month is Jan–Mar, use previous year as fiscal start
    if ($currentMonth < 4) {
        $startYear = $currentYear - 1;
    } else {
        $startYear = $currentYear;
    }

    $startDate = $startYear . '-04-01'; // April 1st of startYear
    $endDate = ($startYear + 1) . '-03-31'; // March 31st of next year

    // SQL query to count the requests within the fiscal year range
    $sql = "SELECT COUNT(*) AS total 
            FROM tbl_request 
            WHERE subscriberid = ? AND TYPE = 2 AND  deprt_approval = 1 AND  log  BETWEEN ? AND ?";

    $query = $this->db->query($sql, [$subscriber_id, $startDate, $endDate]);
    //PRINT_R($query);
    $result = $query->row();

    return isset($result->total) ? $result->total : 0;
}


function getsamplerequestCountsr($subscriber_id) {
    // Get current year and calculate start & end dates
    $currentMonth = date('n'); // 1 to 12
    $currentYear = date('Y');

    // If current month is Jan–Mar, use previous year as fiscal start
    if ($currentMonth < 4) {
        $startYear = $currentYear - 1;
    } else {
        $startYear = $currentYear;
    }

    $startDate = $startYear . '-04-01'; // April 1st of startYear
    $endDate = ($startYear + 1) . '-03-31'; // March 31st of next year

    // SQL query to count the requests within the fiscal year range
    $sql = "SELECT COUNT(*) AS total 
        FROM tbl_request_sample rc
        INNER JOIN tbl_request r ON rc.request_id = r.request_id
        WHERE r.subscriberid = ? AND  r.deprt_approval = 1 AND rc.log BETWEEN ? AND ?";
        
$query = $this->db->query($sql, [$subscriber_id, $startDate, $endDate]);
    //PRINT_R($query);
    //echo $this->db->last_query();
    $result = $query->row();

    return isset($result->total) ? $result->total : 0;
}

function getBOMrequestCountbq($subscriber_id) {
    // Get current year and calculate start & end dates
    $currentMonth = date('n'); // 1 to 12
    $currentYear = date('Y');

    // If current month is Jan–Mar, use previous year as fiscal start
    if ($currentMonth < 4) {
        $startYear = $currentYear - 1;
    } else {
        $startYear = $currentYear;
    }

    $startDate = $startYear . '-04-01'; // April 1st of startYear
    $endDate = ($startYear + 1) . '-03-31'; // March 31st of next year

    // SQL query to count the requests within the fiscal year range
    $sql = "SELECT COUNT(*) AS total 
        FROM tbl_request 
        WHERE subscriberid = ? 
        AND (TYPE = 3 OR TYPE = 4) 
        AND deprt_approval = 1 
        AND log BETWEEN ? AND ?";
        
$query = $this->db->query($sql, [$subscriber_id, $startDate, $endDate]);
    //PRINT_R($query);
    $result = $query->row();

    return isset($result->total) ? $result->total : 0;
}

function getcadrequestCountMI($subscriber_id) {
    // Get current year and calculate start & end dates
    $currentMonth = date('n'); // 1 to 12
    $currentYear = date('Y');

    // If current month is Jan–Mar, use previous year as fiscal start
    if ($currentMonth < 4) {
        $startYear = $currentYear - 1;
    } else {
        $startYear = $currentYear;
    }

    $startDate = $startYear . '-04-01'; // April 1st of startYear
    $endDate = ($startYear + 1) . '-03-31'; // March 31st of next year
     $issuedType = '%CAD%';
    // SQL query to count the requests within the fiscal year range
    $sql = "SELECT COUNT(*) AS total 
        FROM tbl_mi_details rc
        INNER JOIN tbl_request r ON rc.request_id = r.request_id
        WHERE r.subscriberid = ? AND  r.deprt_approval = 1 AND rc.log BETWEEN ? AND ? AND rc.issued_type LIKE ?";
        
$query = $this->db->query($sql, [$subscriber_id, $startDate, $endDate,$issuedType]);
    //PRINT_R($query);
    //echo $this->db->last_query();
    $result = $query->row();

    return isset($result->total) ? $result->total : 0;
}
function getBOMrequestCountMI($subscriber_id) {
    // Get current year and calculate start & end dates
    $currentMonth = date('n'); // 1 to 12
    $currentYear = date('Y');

    // If current month is Jan–Mar, use previous year as fiscal start
    if ($currentMonth < 4) {
        $startYear = $currentYear - 1;
    } else {
        $startYear = $currentYear;
    }

    $startDate = $startYear . '-04-01'; // April 1st of startYear
    $endDate = ($startYear + 1) . '-03-31'; // March 31st of next year
     $issuedType = '%BOM%';
    // SQL query to count the requests within the fiscal year range
    $sql = "SELECT COUNT(*) AS total 
        FROM tbl_mi_details rc
        INNER JOIN tbl_request r ON rc.request_id = r.request_id
        WHERE r.subscriberid = ? AND  r.deprt_approval = 1 AND rc.log BETWEEN ? AND ? AND rc.issued_type LIKE ?";
        
$query = $this->db->query($sql, [$subscriber_id, $startDate, $endDate,$issuedType]);
    //PRINT_R($query);
    //echo $this->db->last_query();
    $result = $query->row();

    return isset($result->total) ? $result->total : 0;
}

function getcadrequestCountDC($subscriber_id) {
    // Get current year and calculate start & end dates
    $currentMonth = date('n'); // 1 to 12
    $currentYear = date('Y');

    // If current month is Jan–Mar, use previous year as fiscal start
    if ($currentMonth < 4) {
        $startYear = $currentYear - 1;
    } else {
        $startYear = $currentYear;
    }

    $startDate = $startYear . '-04-01'; // April 1st of startYear
    $endDate = ($startYear + 1) . '-03-31'; // March 31st of next year
    
    // SQL query to count the requests within the fiscal year range
    $sql = "SELECT COUNT(*) AS total 
        FROM tbl_mi_cad_details rc
        INNER JOIN tbl_request r ON rc.request_id = r.request_id
        WHERE r.subscriberid = ? AND  r.deprt_approval = 1 AND rc.log BETWEEN ? AND ? ";
        
$query = $this->db->query($sql, [$subscriber_id, $startDate, $endDate]);
    //PRINT_R($query);
    //echo $this->db->last_query();
    $result = $query->row();

    return isset($result->total) ? $result->total : 0;
}

function getsamplerequestCountgi($subscriber_id) {
    // Get current year and calculate start & end dates
    $currentMonth = date('n'); // 1 to 12
    $currentYear = date('Y');

    // If current month is Jan–Mar, use previous year as fiscal start
    if ($currentMonth < 4) {
        $startYear = $currentYear - 1;
    } else {
        $startYear = $currentYear;
    }

    $startDate = $startYear . '-04-01'; // April 1st of startYear
    $endDate = ($startYear + 1) . '-03-31'; // March 31st of next year
    
    // SQL query to count the requests within the fiscal year range
    $sql = "SELECT COUNT(*) AS total 
        FROM tbl_sample_requirement rc
        INNER JOIN tbl_request r ON rc.request_id = r.request_id
        WHERE r.subscriberid = ? AND  r.deprt_approval = 1 AND rc.log BETWEEN ? AND ? ";
        
$query = $this->db->query($sql, [$subscriber_id, $startDate, $endDate]);
    //PRINT_R($query);
    //echo $this->db->last_query();
    $result = $query->row();

    return isset($result->total) ? $result->total : 0;
}

function getbomrequestCountPI($subscriber_id) {
    // Get current year and calculate start & end dates
    $currentMonth = date('n'); // 1 to 12
    $currentYear = date('Y');

    // If current month is Jan–Mar, use previous year as fiscal start
    if ($currentMonth < 4) {
        $startYear = $currentYear - 1;
    } else {
        $startYear = $currentYear;
    }

    $startDate = $startYear . '-04-01'; // April 1st of startYear
    $endDate = ($startYear + 1) . '-03-31'; // March 31st of next year
    
    // SQL query to count the requests within the fiscal year range
    $sql = "SELECT COUNT(*) AS total 
        FROM tbl_purchase_indent rc
        INNER JOIN tbl_request r ON rc.request_id = r.request_id
        WHERE r.subscriberid = ? AND  rc.pi_list_status = 1 AND rc.log BETWEEN ? AND ? ";
        
$query = $this->db->query($sql, [$subscriber_id, $startDate, $endDate]);
    //PRINT_R($query);
    //echo $this->db->last_query();
    $result = $query->row();

    return isset($result->total) ? $result->total : 0;
}

function getbomrequestCountDC($subscriber_id) {
    // Get current year and calculate start & end dates
    $currentMonth = date('n'); // 1 to 12
    $currentYear = date('Y');

    // If current month is Jan–Mar, use previous year as fiscal start
    if ($currentMonth < 4) {
        $startYear = $currentYear - 1;
    } else {
        $startYear = $currentYear;
    }

    $startDate = $startYear . '-04-01'; // April 1st of startYear
    $endDate = ($startYear + 1) . '-03-31'; // March 31st of next year
    
    // SQL query to count the requests within the fiscal year range
    $sql = "SELECT COUNT(*) AS total 
        FROM tbl_mi_bom_details rc
        INNER JOIN tbl_request r ON rc.request_id = r.request_id
        WHERE r.subscriberid = ? AND  rc.dc_status_save = 1 AND rc.log BETWEEN ? AND ? ";
        
$query = $this->db->query($sql, [$subscriber_id, $startDate, $endDate]);
    //PRINT_R($query);
    //echo $this->db->last_query();
    $result = $query->row();

    return isset($result->total) ? $result->total : 0;
}

function getbomrequestCountSTM($subscriber_id) {
    // Get current year and calculate start & end dates
    $currentMonth = date('n'); // 1 to 12
    $currentYear = date('Y');

    // If current month is Jan–Mar, use previous year as fiscal start
    if ($currentMonth < 4) {
        $startYear = $currentYear - 1;
    } else {
        $startYear = $currentYear;
    }

    $startDate = $startYear . '-04-01'; // April 1st of startYear
    $endDate = ($startYear + 1) . '-03-31'; // March 31st of next year
    
    // SQL query to count the requests within the fiscal year range
    $sql = "SELECT COUNT(*) AS total 
    FROM tbl_surplus_issued_details rc
    INNER JOIN tbl_request r ON rc.request_id = r.request_id
    WHERE r.subscriberid = ? 
    AND rc.stm_queue_no IS NOT NULL 
    AND rc.stm_queue_no != '' 
    AND rc.log BETWEEN ? AND ? ";
        
$query = $this->db->query($sql, [$subscriber_id, $startDate, $endDate]);
    //PRINT_R($query);
    //echo $this->db->last_query();
    $result = $query->row();

    return isset($result->total) ? $result->total : 0;
}






function getOrderEnquiryCount_subscriber($subscriber_id) {
   
    $sql = "SELECT COUNT(*) AS total FROM " . KN_ORDER_ENQUIRY . " WHERE subscriberid = ?";
    $query = $this->db->query($sql, array($subscriber_id));
    $result = $query->row();
    
    return isset($result->total) ? $result->total : 0;
}




    
    function getPackageInfoFromPckg($VarPckgId = '') {
        if ($VarPckgId <> '') {
            $VarSql = "SELECT * FROM " . KN_MASTER_PACKAGE . "  WHERE id = " . $VarPckgId;
            return $this->db->query($VarSql)->row();
        }
    }
    function getnoofdays($startdate,$enddate){
        $dateString1 = $startdate;
        $dateString2 = $enddate;
        
        // Create DateTime objects from the date strings
        $date1 = new DateTime($dateString1);
        $date2 = new DateTime($dateString2);
        
        // Calculate the difference between two dates
        $interval = $date1->diff($date2);
        
        // Get the number of days from the interval
        $numberOfDays = $interval->days;
        return $numberOfDays;
    }
    function getIndianCurrency($number) // for converting number to words
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        if($digits_length >= 10){
            echo "Sorry this does not support more than 99 crores";
        }else {
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? '' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal) ? "and " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' paise' : '';
        return ($Rupees ? ucFirst($Rupees) . 'rupees ' : '') . $paise;
        }
    }
    
     //// for autogenerate 
   
   public function keyGenerate($table_name, $pref = NULL, $uniq_col,$start) { // used for invoice
    
    $actual_table_name = $table_name;
  
	$result = $this->MyDateTime->fiscalYear();

	$fiscalyear = $result['start']->format('y');
	
     $sql_query = "SELECT MAX($uniq_col) as $uniq_col FROM $actual_table_name WHERE SUBSTRING($uniq_col,12) = (SELECT MAX(CAST(SUBSTRING($uniq_col,12) AS UNSIGNED)) FROM $actual_table_name WHERE  $uniq_col LIKE '%$fiscalyear%')";
  
        try {
            $stmt = $this->db->query($sql_query);
            $subject = $stmt->row(); 
            
            $subject = substr($subject->$uniq_col,8,2); 
           
            if($subject === $fiscalyear && $subject && $subject !== ''){
            
            $max_no_sql = "SELECT MAX(CAST(MID($uniq_col,12)+1 AS UNSIGNED)) as $uniq_col FROM $actual_table_name WHERE $uniq_col LIKE '%$fiscalyear%'";
            $stmt = $this->db->query($max_no_sql);
            $max_no = $stmt->row();
            $max_no = $max_no ->$uniq_col;
            
            $unique_key = $start .'/'. $pref. $fiscalyear .'/'. $max_no ;            
            
            
            }else{    
	           $unique_key = $start .'/'. $pref. $fiscalyear .'/'. '1' ;            
            }
            
           return $unique_key;
        } catch (Exception $exc) {
            $unique_key = FALSE;
        }
    }
   public function keyGenerateNew($table_name, $pref = NULL, $uniq_col,$start,$suffix = NULL) { // used for proforma invoice
    
    $actual_table_name = $table_name;
  
	$result = $this->MyDateTime->fiscalYear();

	$fiscalyear = $result['start']->format('y');
	
    $sql_query = "SELECT MAX($uniq_col) as $uniq_col FROM $actual_table_name WHERE SUBSTRING($uniq_col,14) = (SELECT MAX(CAST(SUBSTRING($uniq_col,14) AS UNSIGNED)) FROM $actual_table_name WHERE  $uniq_col LIKE '%$fiscalyear%')";
  
        try {
            $stmt = $this->db->query($sql_query);
            $subject = $stmt->row(); 
            
            $subject = substr($subject->$uniq_col,8,2); 
          
            if($subject === $fiscalyear && $subject && $subject !== ''){
            
            $max_no_sql = "SELECT MAX(CAST(MID($uniq_col,14)+1 AS UNSIGNED)) as $uniq_col FROM $actual_table_name WHERE $uniq_col LIKE '%$fiscalyear%'";
            $stmt = $this->db->query($max_no_sql);
            $max_no = $stmt->row();
            $max_no = $max_no ->$uniq_col;
           
            $unique_key = $start .'/'. $pref. $fiscalyear .'/'. $suffix.'-'.$max_no ;            
            
            
            }else{    
	           $unique_key = $start .'/'. $pref. $fiscalyear .'/'.$suffix.'-'. '1' ;            
            }
           return $unique_key;
        } catch (Exception $exc) {
            $unique_key = FALSE;
        }
    }
   public function keyGenerateSubscptn($table_name, $pref = NULL, $uniq_col,$start,$suffix = NULL) { //used for subscription
    
    $actual_table_name = $table_name;
    
    $sql_query = "SELECT MAX($uniq_col) as $uniq_col FROM $actual_table_name WHERE SUBSTRING($uniq_col,14) = (SELECT MAX(CAST(SUBSTRING($uniq_col,14) AS UNSIGNED)) FROM $actual_table_name WHERE  $uniq_col LIKE '%$suffix%')";
  
        try {
            $stmt = $this->db->query($sql_query);
            $subject = $stmt->row();  
             
            $subject = substr($subject->$uniq_col,13); 
          
            if(!empty($subject)){
            
            $max_no_sql = "SELECT MAX(CAST(MID($uniq_col,14)+1 AS UNSIGNED)) as $uniq_col FROM $actual_table_name WHERE $uniq_col LIKE '%$suffix%'";
            $stmt = $this->db->query($max_no_sql);
            $max_no = $stmt->row();
            $max_no = $max_no ->$uniq_col;
              
              $unique_key = $start .'/'. $pref .'/'. $suffix.'-'.$max_no ;            
            
            }else{    
	          $unique_key = $start .'/'. $pref .'/'.$suffix.'-'. '1' ;     
            }
           
           return $unique_key;
        } catch (Exception $exc) {
            $unique_key = FALSE;
        }
    }
    public function keyGenerateSubscptn_copy($table_name, $pref = NULL, $uniq_col) { /// format :S-1
    
    $actual_table_name = $table_name;
  
    $sql_query = "SELECT MAX($uniq_col) as $uniq_col FROM $actual_table_name WHERE SUBSTRING($uniq_col,2) = (SELECT MAX(CAST(SUBSTRING($uniq_col,2) AS UNSIGNED)) FROM $actual_table_name WHERE  $uniq_col LIKE '%$pref%')";
  
        try {
            $stmt = $this->db->query($sql_query);
            $subject = $stmt->row(); 
            
            $subject = substr($subject->$uniq_col,2); 
           
            if(!empty($subject)){
            
            $max_no_sql = "SELECT MAX(CAST(MID($uniq_col,3)+1 AS UNSIGNED)) as $uniq_col FROM $actual_table_name WHERE $uniq_col LIKE '%$pref%'";
            $stmt = $this->db->query($max_no_sql);
            $max_no = $stmt->row();
            $max_no = $max_no ->$uniq_col;
            
            $unique_key = $pref.'-'. $max_no ;            
            
            
            }else{    
	           $unique_key = $pref.'-'. '1' ;            
            }
           
           return $unique_key;
        } catch (Exception $exc) {
            $unique_key = FALSE;
        }
    }
    public function keyGenerateNewone($table_name, $pref = NULL, $uniq_col, $start, $suffix = NULL) {
    $actual_table_name = $table_name;

 
    //Get the fiscal year range

    // $fiscal_year = $this->MyDateTime->fiscalYear();
    // $start_date = new DateTime($fiscal_year['end']); // Last 2 digits of fiscal year
    // $monthyear = $start_date->format('y');

    $fiscal_year = $this->MyDateTime->fiscalYear();
    $start_date = new DateTime($fiscal_year['start']); // Last 2 digits of fiscal year
    $monthyear = $start_date->format('y');
    
     // Check if today is April 1st
     $current_date = new DateTime();
     $is_april_first = $current_date->format('m-d') === '04-01';
     $start_datee = $current_date->format('y');
    
    $unique_no = $start . '/' . $pref . $start_datee . '/' . $suffix . '-1';
    $sql_query = "SELECT $uniq_col FROM $actual_table_name WHERE $uniq_col='$unique_no'";
    $stmt = $this->db->query($sql_query);
    $result = $stmt->row(); 
  
    if ($is_april_first && empty($result)) {
        // If it's April 1st, reset the count to 1
        $unique_key = $start . '/' . $pref . $start_datee . '/' . $suffix . '-1';
    } else {
        // Construct the SQL query to find the maximum value of the unique column for the current fiscal year
         $sql_query = "SELECT MAX($uniq_col) as $uniq_col FROM $actual_table_name WHERE SUBSTRING($uniq_col,14) = (SELECT MAX(CAST(SUBSTRING($uniq_col,14) AS UNSIGNED)) FROM $actual_table_name WHERE  $uniq_col LIKE '%$monthyear%')";

        try {
            // Execute the SQL query
            $stmt = $this->db->query($sql_query);
            $subject = $stmt->row(); 
            $subject = substr($subject->$uniq_col, 8, 2); 
            // var_dump($subject);
            // If there are records for the current fiscal year, increment the maximum value found
            if ($subject && $subject !== '') {
                $max_no_sql = "SELECT MAX(CAST(MID($uniq_col,14) AS UNSIGNED)) as $uniq_col FROM $actual_table_name WHERE $uniq_col LIKE '%$monthyear%'";
                $stmt = $this->db->query($max_no_sql);
                $max_no = $stmt->row()->$uniq_col;
                $max_no = $max_no + 1;

                // Construct the unique key
                $unique_key = $start . '/' . $pref . $start_datee . '/' . $suffix . '-' . $max_no;
            } else {
                // If there are no records for the current fiscal year, start numbering from 1
                $unique_key = $start . '/' . $pref . $start_datee . '/' . $suffix . '-1';
            }
           
            // Return the generated unique key
           
        } catch (Exception $exc) {
            // Handle exceptions if any
            $unique_key='';
           // return FALSE;
        }
    }
     return $unique_key;
    }
    public function keyGenerateone($table_name, $pref = NULL, $uniq_col,$start) { // used for invoice
    
    $actual_table_name = $table_name;
  
	// Get the fiscal year range
    // $fiscal_year = $this->MyDateTime->fiscalYear();
    // $start_date = new DateTime($fiscal_year['end']); // Last 2 digits of fiscal year
    // $monthyear = $start_date->format('y');

    $fiscal_year = $this->MyDateTime->fiscalYear();
    $start_date = new DateTime($fiscal_year['start']); // Last 2 digits of fiscal year
    $monthyear = $start_date->format('y');
   
   
    // Check if today is April 1st
    $current_date = new DateTime();
    $is_april_first = $current_date->format('m-d') === '04-01';
    
    $start_datee = $current_date->format('y');
    $unique_no = $start . '/' . $pref . $start_datee . '/' .'1';
    
    $sql_query = "SELECT $uniq_col FROM $actual_table_name WHERE $uniq_col='$unique_no'";
    $stmt = $this->db->query($sql_query);
    $result = $stmt->row(); 
    // var_dump($result);die;
    if ($is_april_first && empty($result)) {
        // If it's April 1st, reset the count to 1
        $unique_key = $start . '/' . $pref . $start_datee . '/' .'1';
    }else{
        $sql_query = "SELECT MAX($uniq_col) as $uniq_col FROM $actual_table_name WHERE SUBSTRING($uniq_col,12) = (SELECT MAX(CAST(SUBSTRING($uniq_col,12) AS UNSIGNED)) FROM $actual_table_name WHERE  $uniq_col LIKE '%$monthyear%')";
  
        try {
            $stmt = $this->db->query($sql_query);
            $subject = $stmt->row(); 
            
            $subject = substr($subject->$uniq_col,8,2); 
          
            if($subject && $subject !== ''){
           
            $max_no_sql = "SELECT MAX(CAST(MID($uniq_col,12) AS UNSIGNED)) as $uniq_col FROM $actual_table_name WHERE $uniq_col LIKE '%$monthyear%'";
            $stmt = $this->db->query($max_no_sql);
            $max_no = $stmt->row();
            $max_no = $max_no ->$uniq_col;
            $max_no = $max_no + 1;
            
            $unique_key = $start .'/'. $pref. $start_datee .'/'. $max_no ;            
            
            
            }else{  
                 
	           $unique_key = $start .'/'. $pref. $start_datee .'/'. '1' ;            
            }
           
            
           
        } catch (Exception $exc) {
            $unique_key='';
           // $unique_key = FALSE;
        }
    } 
    return $unique_key;
    }

}