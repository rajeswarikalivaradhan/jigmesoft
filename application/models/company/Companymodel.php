<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Companymodel extends CI_Model {
    function fnSaveCompanyInfo($ArrCompanyDetails = array(),$ArrUserLoginTblInfo=array()) {
        $VarEmailId = $ArrUserLoginTblInfo['username'];
        if ($VarEmailId != "") {
            $VarRes = $this->fnGetCompanyInfo('', $VarEmailId);
            if (@$VarRes[0]['id'] >= 1) {
                return false;
            } else {
                $ArrCompanyDetails['companycode'] = $this->fnGenerateCompanyCode();
                $this->db->insert(KN_COMPANY_DETAILS, $ArrCompanyDetails);
                $VarCompanyId      = $this->db->insert_id();
                $VarLoggedinUserid = fnGetUserLoggedInfo();
                //base64_encode($VarPassword)
                /*
                 * Removed password encode
                 * */
                if ($VarCompanyId >= 1) {
                    $VarPassword         = str_rand(8, 'alpha');
                    $VarTimeStamp = date('Y-m-d H:i:s');
                    $ArrUserLoginTblInfo['companyid'] = $VarCompanyId;
                    $this->db->insert(KN_USERS, $ArrUserLoginTblInfo);
					$VarUserId = $this->db->insert_id();

                    $this->db->insert(KN_USER_DETAILS, array('userid' => $VarUserId,'code'=>'ComAdmin-'.$VarCompanyId));
                    $this->db->query("INSERT INTO ".KN_MASTER_PORT." (companyid,portname,portaddress,portcity,portstate,portcountry,status,updatedby,datecreated,dateupdated)
                    SELECT ".$VarCompanyId.",portname,portaddress,portcity,portstate,portcountry,1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' 
                    FROM ".KN_MASTER_PORT." WHERE companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_GARMENT_PART_DESC." (companyid,gpdname,status,updatedby,datecreated,dateupdated) 
                    SELECT ".$VarCompanyId.",gpdname,1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_GARMENT_PART_DESC." WHERE 
                    companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_FABRIC_FINISH." (companyid,type,name,status,updatedby,datecreated,dateupdated) 
                    SELECT ".$VarCompanyId.",type,name,1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_FABRIC_FINISH." WHERE 
                    companyid = 0 AND status = 1 ");

                    /*
                    $this->db->query("INSERT INTO ".KN_MASTER_FABRIC_DETAILS." (companyid,fabrictype,blend,content,lycra,fabric,status,updatedby,datecreated,dateupdated) 
                    SELECT ".$VarCompanyId.",fabrictype,blend,content,lycra,fabric,1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_FABRIC_DETAILS." WHERE 
                    companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_YARN." (companyid,type,yarnsplreq,yarncontent,count,yarnblendperc,yarnpurchasetype,yarngrade,status,updatedby,datecreated,dateupdated) 
                    SELECT ".$VarCompanyId.",type,yarnsplreq,yarncontent,count,yarnblendperc,yarnpurchasetype,yarngrade,1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_YARN." WHERE 
                    companyid = 0 AND status = 1 ");



                    $this->db->query("INSERT INTO ".KN_MASTER_DYEING_SPECIAL_REQUEST." (companyid,type,dsrname,colormatchstd,status,updatedby,datecreated,dateupdated)
                    SELECT ".$VarCompanyId.",type,dsrname,colormatchstd,1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_DYEING_SPECIAL_REQUEST." 
                    WHERE companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_EMBELL_TYPE." (companyid,embellname,medium,status,updatedby,datecreated,dateupdated)
                    SELECT ".$VarCompanyId.",embellname,medium,1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_EMBELL_TYPE." 
                    WHERE companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_BOM." (companyid,content,material,bomblend,bomsplreq,status,updatedby,datecreated,dateupdated) SELECT ".$VarCompanyId.",content,material,bomblend,bomsplreq,
                    1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_BOM." WHERE companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_AUTHORITIES." (companyid,authoritytype,approvalname,status,updatedby,datecreated,dateupdated)
                    SELECT ".$VarCompanyId.",authoritytype,approvalname,1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_AUTHORITIES." 
                    WHERE companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_PROCESS_FLOW." (companyid,processflowname,status,updatedby,datecreated,dateupdated)
                    SELECT ".$VarCompanyId.",processflowname,1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_PROCESS_FLOW." 
                    WHERE companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_LAB." (companyid,type,labname,acceptablelevel,status,updatedby,datecreated,dateupdated)
                    SELECT ".$VarCompanyId.",type,labname,acceptablelevel,1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_LAB." WHERE 
                    companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_PACKING_CODE." (companyid,packingname,status,updatedby,datecreated,dateupdated)
                    SELECT ".$VarCompanyId.",packingname,1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_PACKING_CODE." WHERE 
                    companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_PACKING_MATERIAL." (companyid,packingmaterialname,status,updatedby,datecreated,dateupdated)
                    SELECT ".$VarCompanyId.",packingmaterialname,1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_PACKING_MATERIAL." WHERE 
                    companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_LOTINSPECTION." (companyid,level,codeletter,aql,samplesize,updatedby,status,datecreated,dateupdated)
                    SELECT ".$VarCompanyId.",level,codeletter,aql,samplesize,".$VarLoggedinUserid.",1,'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_LOTINSPECTION." WHERE 
                    companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_LOGISTICS." (consignorexporter,consignee,importer,forwardingagent,clearingagent,companyid,status,updatedby,
                    datecreated,dateupdated) SELECT consignorexporter,consignee,importer,forwardingagent,clearingagent,".$VarCompanyId.",1,".$VarLoggedinUserid.",
                    '".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_LOGISTICS." WHERE companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_ENQUIRY_TYPE." (companyid,enquirytype,status,updatedby,datecreated,dateupdated) SELECT ".$VarCompanyId.",enquirytype,
                    1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_ENQUIRY_TYPE." WHERE companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_MODEOFENQUIRY." (companyid,modeofenquiry,status,updatedby,datecreated,dateupdated) SELECT ".$VarCompanyId.",modeofenquiry,
                    1,".$VarLoggedinUserid.",'".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_MODEOFENQUIRY." WHERE companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_BRANDS." (brandname,buyerid,companyid,contactname,emailid,mobileno,phoneno,address,designation,status,updatedby,
                    datecreated,dateupdated) SELECT brandname,buyerid,".$VarCompanyId.",contactname,emailid,mobileno,phoneno,address,designation,1,".$VarLoggedinUserid.",
                    '".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_BRANDS." WHERE companyid = 0 AND status = 1 ");

                    $this->db->query("INSERT INTO ".KN_MASTER_BUYER." (companyid,buyername,type,contactname,emailid,mobileno,phoneno,address,designation,status,updatedby,
                    datecreated,dateupdated) SELECT ".$VarCompanyId.",buyername,type,contactname,emailid,mobileno,phoneno,address,designation,1,".$VarLoggedinUserid.",
                    '".$VarTimeStamp."','".$VarTimeStamp."' FROM ".KN_MASTER_BUYER." WHERE companyid = 0 AND status = 1 ");*/



                    /*
                     * companyid 0 is from admins
                     * */
                    return $VarCompanyId;
                }
            }
        }
    }

    function fnGenerateCompanyCode() {
        $VarSqlCompanyCode = 'SELECT max(companycode) as companycode FROM ' . KN_COMPANY_DETAILS;
        $ArrGenerateCompanyStatus = $this->db->query($VarSqlCompanyCode)->result_array();
        if(empty($ArrGenerateCompanyStatus[0]['companycode'])) {
            $VarCompanyCode = str_rand(4,'numeric');
        }
        else {
            $VarCompanyCode = $ArrGenerateCompanyStatus[0]['companycode'];
            $VarCompanyCode = $VarCompanyCode + 1;
        }
        return $VarCompanyCode;
    }

    function fnGetCompanyInfo($VarCompanyId = '', $VarEmailId = '', $VarStatus = '',$VarUserType = '') {
        $this->db->select('c.id,u.contactname,companyname,companycode,businesstype,factorysize,c.address,c.gst,c.emailid,noofmachine,c.city,c.state,country,c.zipcode,productioncapacity,annualturnover,
        noofemployee,noofcontract,factoryownership,majorcustomer,yearofest,exportcustomer,companyprofile,c.updatedby,c.status,c.datecreated,c.dateupdated,c.mobile,u.username');

        //$this->db->join(KN_COMPANY_CONTACT_DETAILS .' AS cc','cc.companyid = c.id');
        $this->db->join(KN_USERS .' AS u','u.companyid = c.id');
        $this->db->from(KN_COMPANY_DETAILS .' AS c');
        if ($VarStatus == '') {
            $this->db->where_in('c.status', array(1, 2, 3));
        } else {
            $this->db->where_in('c.status', array(1));
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['c.id'] = $VarCompanyId;
        }
        if($VarEmailId <> '') {
            $ArrWhere['u.username'] = $VarEmailId;
        }
        if($VarUserType <> '') {
            $ArrWhere['u.usertype'] = $VarUserType;
        }
        if (count($ArrWhere) >= 1) {
            $this->db->where($ArrWhere);
        }
        $ArrCompanyList = $this->db->get()->result_array();
        return $ArrCompanyList;
    }
    function fnCountCompany($VarCompanyName = '', $VarEmail = '', $VarComCode = '', $VarCountry = '', $VarCity = '', $VarMobile = '', $VarState = '', $VarCompanyStatus = '',
                            $VarBusinessType = '',$VarUserTypeId='') {
        $ArrWhere = $this->fnConstructCompanyWhereCond($VarCompanyName, $VarEmail, $VarComCode, $VarCountry, $VarCity, $VarMobile, $VarState, $VarCompanyStatus,
            $VarBusinessType, $VarUserTypeId);
        $VarWhere = '';
        $VarWhere = implode(" and ", $ArrWhere);
        /*$VarSqlCompanyInfo = "SELECT c.id,c.companyname,c.businesstype,c.factoryownership,c.mobile,c.dateupdated,c.city,c.state,cny.countryname,
        c.status,c.id,u.username,u.password FROM ". KN_COMPANY_DETAILS . " AS c INNER JOIN " . KN_COUNTRIES . " AS cny ON c.country = cny.id
        INNER JOIN ".KN_USERS." AS u ON u.companyid = c.id WHERE
          " . $VarWhere . " order by " . $VarSortBy . " " .$VarSortOrder . " limit " . $VarLimitInfo;*/
        $VarSqlCompanyInfo = "SELECT count(1) as trec FROM " . KN_COMPANY_DETAILS . " AS c INNER JOIN 
        " . KN_COUNTRIES . " AS cny ON c.country = cny.id INNER JOIN ".KN_USERS." AS u ON u.companyid = c.id WHERE " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlCompanyInfo)->row();
        return $ObjRows->trec;
    }
    function fnListCompany($VarCompanyName = '', $VarEmail = '', $VarComCode = '', $VarCountry = '', $VarCity = '', $VarMobile = '', $VarState = '',
                           $VarCompanyStatus = '', $VarBusinessType = '', $VarUserTypeId='',$VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('c.companyname', 'c.mobile','c.businesstype', 'c.factoryownership', 'c.city', 'c.state', 'cny.countryname', 'c.dateupdated', 'c.status');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'c.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = $this->fnConstructCompanyWhereCond($VarCompanyName, $VarEmail, $VarComCode,$VarCountry, $VarCity, $VarMobile, $VarState,
            $VarCompanyStatus, $VarBusinessType,$VarUserTypeId);
        /*$ArrWhere                   = array();
        if($VarEmail<>'') {
            $ArrWhere[]             = "cc.contactemail like '%".$VarEmail."%'";
        }
        if($VarMobile<>'') {
            $ArrWhere[] = "cd.mobile like '" . $VarMobile . "%'";
        }
        if($VarPhoneNo<>'') {
            $ArrWhere[]             = "cd. like '".$VarPhoneNo."%'";
        }
        if($VarContactName<>'') {
            $ArrWhere[]             = "cd. like '".$VarContactName."%'";
        }
        $VarWhere                   = '';
        */
        if(@$ArrWhere[0]<>'') {
            $VarWhere               = implode(" and ",$ArrWhere);
        }

        $VarSqlCompanyInfo = "SELECT c.id,c.companyname,c.businesstype,c.factoryownership,c.mobile,c.dateupdated,c.city,
          c.state,cny.countryname,c.status,c.id,u.username,u.password 
          FROM ". KN_COMPANY_DETAILS . " AS c INNER JOIN " . KN_COUNTRIES . " AS cny ON c.country = cny.id INNER JOIN ".KN_USERS." AS u ON u.companyid = c.id WHERE 
          " . $VarWhere . " order by " . $VarSortBy . " " .$VarSortOrder . " limit " . $VarLimitInfo;


        /*        if ($VarEmail <> '' || $VarMobile <> '' || $VarPhoneNo <> '' || $VarContactName <> '') {

                } elseif ($VarAlphaFilter <> '') {
                    $VarSqlCompanyInfo
                        = "SELECT c.companyname,u.username,c.businesstype,c.factoryownership,u.password,c.dateupdated,c.city,c.state,cny.countryname,c.status,c.id FROM

        " . KN_USERS . " as u INNER JOIN " . KN_COMPANY_DETAILS . " AS c ON c.id=u.companyid INNER JOIN " . KN_COUNTRIES . " AS cny ON

        c.country = cny.id WHERE c.companyname LIKE '" . $VarAlphaFilter . "%'" . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder . " limit " . $VarLimitInfo;
                } else {
                    $VarSqlCompanyInfo
                        = "SELECT c.companyname,u.username,c.businesstype,c.factoryownership,u.password,c.dateupdated,c.city,c.state,cny.countryname,c.status,c.id FROM

        " . KN_USERS . " as u INNER JOIN " . KN_COMPANY_DETAILS . " AS c ON c.id=u.companyid INNER JOIN " . KN_COUNTRIES . " AS cny ON c.country = cny.id" . $VarWhere .
                        " order by " . $VarSortBy . " " . $VarSortOrder . " limit " . $VarLimitInfo;
                }*/
        $ObjResult = $this->db->query($VarSqlCompanyInfo);
        return $ObjResult;
    }
    function fnConstructCompanyWhereCond($VarCompanyName = '', $VarEmail = '', $VarComCode = '', $VarCountry = '', $VarCity = '', $VarMobile = '',
                                         $VarState = '', $VarCompanyStatus = '', $VarBusinessType = '', $VarUserTypeId='') {
        $ArrWhere = array();
        if ($VarCompanyName <> '') {
            $ArrWhere[] = "c.companyname like '%" . $VarCompanyName . "%'";
        }

        /*if ($VarContactName <> '') {
            $ArrWhere[] = "cc.contactname like '%" . $VarContactName . "%'";
        }*/
        if ($VarEmail <> '') {
            $ArrWhere[] = "u.username like '%" . $VarEmail . "%'";
        }
        if ($VarComCode <> '') {
            $ArrWhere[] = "c.companycode like '%" . $VarComCode . "%'";
        }

        if ($VarCountry <> '') {
            $ArrWhere[] = "c.country=".$VarCountry;
        }
        if ($VarCity <> '') {
            $ArrWhere[] = "c.city like '%" . $VarCity . "%'";
        }
        if ($VarState <> '') {
            $ArrWhere[] = "c.state =". $VarState;
        }
        if ($VarMobile <> '') {
            $ArrWhere[] = "c.mobile like '%" . $VarMobile . "%'";
        }

        if ($VarBusinessType <> '') {
            $ArrWhere[] = "c.businesstype=" . $VarBusinessType;
        }
        if ($VarCountry <> '') {
            $ArrWhere[] = "c.country=" . $VarCountry;
        }
        if ($VarUserTypeId <> '') {
            $ArrWhere[] = "u.usertype = " . $VarUserTypeId;
        }
        if (@$VarCompanyStatus[0] <> '') {
            $ArrWhere[] = " c.status in(" . implode(",", $VarCompanyStatus) . ")";
        }
        return $ArrWhere;
    }

    function fnUpdateCompanyBasicInfo($ArrCompanyBasicInfo, $VarCompanyId,$ArrUserLoginTblInfo) {
        $this->db->update(KN_USERS, $ArrUserLoginTblInfo, array('id' => $ArrUserLoginTblInfo['id']));
        $VarResult = $this->db->update(KN_COMPANY_DETAILS, $ArrCompanyBasicInfo, array('id' => $VarCompanyId));
        return $VarResult;
    }

    function fnChangeComStat($compids, $optvalue) {
        $Arrcompanyid = json_decode($compids, true);
        if ($optvalue == '1') //Activate
            $updateVal = array('status' => 1);
        else //Deactivate
            $updateVal = array('status' => 2);
        $this->db->where_in('id', $Arrcompanyid);
        if ($this->db->update(KN_COMPANY_DETAILS, $updateVal)) {
            return true;
        }
        return false;
    }

    function fnDelCompany($VarId) {
        $this->db->where('id', $VarId);
        if ($this->db->update(KN_COMPANY_DETAILS, array('status' => 3))) {
            return true;
        }
        return false;
    }

    public function headerUserDesignation($VarDesignationId='') {
        if($VarDesignationId <> '') {
            $VarSql = "SELECT desgn FROM ".KN_USER_DESGN." WHERE designationid = ".$VarDesignationId;
            return $this->db->query($VarSql)->row();
        }
    }
    
    public function headerUserDesignationNew($VarUserId='') { // from newly created common user table of kn_mngusers based on userid
        if($VarUserId <> '') {
            $VarSql = "SELECT designation FROM ".KN_MUSERS." WHERE id = ".$VarUserId;
            return $this->db->query($VarSql)->row();
        }
    }
    public function fnGetRoleWiseInfo($VarUserId = '', $VarCompanyId = '') {
        
        $this->db->select('GROUP_CONCAT(title) AS title')->from(KN_USERROLE_PERMISSION);
        
        if ($VarUserId <> '') {
            $ArrWhere['userid'] = $VarUserId;
        }
       
        /*if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }*/
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
        $ArrResultList = $this->db->get()->result_array();
        return $ArrResultList;
    }
    public function headerusercompanyname($VarSubscriberId='') { // from newly created common user table of kn_mngusers based on userid
        if($VarSubscriberId <> '') {
            $VarSql = "SELECT * FROM ".KN_SUBSCRIBERENQUIRY." WHERE id = ".$VarSubscriberId;
            return $this->db->query($VarSql)->row();
        }
    }
     public function subscriber_detail($VarSubscriberId) { // from newly created common user table of kn_mngusers based on userid
        if (!empty($VarSubscriberId)) {
            $VarSql = "SELECT * FROM ".KN_SUBSCRIBERENQUIRY." WHERE id = ".$VarSubscriberId;
            return $this->db->query($VarSql)->row();
        }
    }
    public function fnGetCommonRoleWiseInfo($VarUserId = '', $VarCompanyId = '',$VarUserType = '',$tablename) {
        
        $this->db->select('GROUP_CONCAT(title) AS title')->from($tablename);
        
        if ($VarUserId <> '') {
            $ArrWhere['subscriber_id'] = $VarUserId;
        }
        if ($VarUserType <> '') {
            $ArrWhere['usertype'] = $VarUserType;
        }
        $ArrWhere['status'] = 1; // newly added to show only active records.
        /*if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }*/
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
        // echo $this->db->get_compiled_select();
        $ArrResultList = $this->db->get()->result_array();
        return $ArrResultList;
    }
}