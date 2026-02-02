<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class menquirymodel extends CI_Model {

    function fnGetInfo($VarStatus='',$VarId='',$VarCompanyId='') {
        $ArrWhere                   = array(); $VarBuyerId = 0; $ArrFinal = array();
        if($VarStatus<>'') {

            $ArrWhere[]             = "e.status=".$VarStatus;

        } else {

            $ArrWhere[]             = "e.status in(1,2)";

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

        $VarSqlEnq                  = "SELECT e.id,e.enquirytype as enqtypeid,e.modeofenquiry as modeofenquiryid,br.brandname,br.buyerid,m.contactname,et.enquirytype as enq,e.podate,e.countryid,
e.currency,e.stylenamerefno,e.styledesc,e.pcsorset,e.quotedprice,e.buyerprice,e.confirmprice,e.exporderqty,e.reqforisrior,e.isrrefany,e.brandbuyerid,e.merchantid,
e.datecreated,e.dateupdated,e.enquirydate,e.status,e.orderstatus,e.enquiryrefpono,e.comments,e.merchantnote,mo.modeofenquiry,e.daterequested,e.dateauthorized FROM ".KN_ORDER_ENQUIRY." AS e INNER JOIN ".KN_MASTER_ENQUIRY_TYPE." AS et 
ON e.enquirytype = et.id INNER JOIN ".KN_MASTER_BRANDS." AS br ON br.id = e.brandbuyerid INNER JOIN ".KN_MASTER_MERCHANT." as m ON m.id = e.merchantid INNER JOIN 
".KN_MASTER_MODEOFENQUIRY." as mo ON mo.id = e.modeofenquiry WHERE ".$VarWhere;

        $ResSqlEnq					= $this->db->query($VarSqlEnq)->result();
        if(count($ResSqlEnq)>=1)
            $VarBuyerId                 = $ResSqlEnq[0]->buyerid;

        $VarBuyer                  = $this->getBuyer(array(),$VarBuyerId);

        $ArrFinal['et']            = $ResSqlEnq;
        $ArrFinal['buyer']         = $VarBuyer;

        return $ArrFinal;
    }

    function saveEnquiryInfo($ArrUpdateData,$VarResendId='') {
        $Vardt = $ArrUpdateData['dateupdated'];
        $draftstatus= $ArrUpdateData['draft_status'];
        if($VarResendId > 0) {
            $this->db->where('id',$VarResendId);
            if($this->db->update(KN_ORDER_ENQUIRY,$ArrUpdateData)) {
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
            $this->db->insert(KN_ORDER_ENQUIRY,$ArrUpdateData);
            $VarId = $this->db->insert_id();
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

    function fnCount($VarEnType='',$VarStyleref='',$VarIsrior='',$VarBuyerbrand='',$VarMercn='',$VarReqfrom='',$VarReqTo='',$VarStatus='',$VarAfilter='') {
        $ArrUserLoggedInfo          = fnGetUserLoggedInfo(1);

        $ArrWhere                   = array();

        if($VarEnType<>'') {

            $ArrWhere[]             = "et.enquirytype like '%".$VarEnType."%'";

        }
        if($VarStyleref<>'') {

            $ArrWhere[]             = "e.stylenamerefno like '%".$VarStyleref."%'";

        }
        if($VarIsrior<>'') {

            $ArrWhere[]             = "e.reqforisrior like '%".$VarIsrior."%'";

        }
        if($VarBuyerbrand<>'' && $VarBuyerbrand <> 'null') {

            $ArrWhere[]             = "e.brandbuyerid IN (".$VarBuyerbrand.")";

        }
        if($VarMercn<>'' && $VarMercn <> 'null') {

            $ArrWhere[]             = "e.merchantid IN (".$VarMercn.")";

        }
        if($VarReqfrom<>'' && $VarReqTo <> '') {

            $ArrWhere[]             = "date(e.datecreated) >= '$VarReqfrom' AND date(e.datecreated) <= '$VarReqTo' ";

        }
        if($VarAfilter<>'') {

            $ArrWhere[]             = "br.brandname LIKE '".$VarAfilter."%'";

        }
        if($VarStatus<>'') {

            $ArrWhere[]             = "e.status=".$VarStatus;

        } else {

            $ArrWhere[]             = "e.status in(1,2)";

        }
        $ArrWhere[]                 = "e.companyid = ".$ArrUserLoggedInfo['companyid'] . " AND e.orderstatus <> 2";
        $VarWhere                   = '';

        if(@$ArrWhere[0]<>'') {
            $VarWhere               = implode(" and ",$ArrWhere);
        }

        $VarSqlLab                  = "SELECT count(1) as trec FROM ".KN_ORDER_ENQUIRY." AS e INNER JOIN ".KN_MASTER_ENQUIRY_TYPE." AS et 
ON e.enquirytype = et.id INNER JOIN ".KN_MASTER_MODEOFENQUIRY." AS mo ON mo.id = e.modeofenquiry INNER JOIN ".KN_MASTER_BRANDS." AS br ON br.id = e.brandbuyerid INNER JOIN ".KN_MASTER_MERCHANT." as m ON m.id = e.merchantid WHERE ".$VarWhere;

        $ObjRows					= $this->db->query($VarSqlLab)->row();

        return $ObjRows->trec;

    }

    function fnList($VarEnType='',$VarStyleref='',$VarIsrior='',$VarBuyerbrand='',$VarMercn='',$VarReqfrom='',$VarReqTo='',$VarStatus='',$VarLimit = 10, $offset = 0,$VarSortBy,$VarSortOrder,$VarAfilter='') {

        $ArrUserLoggedInfo          = fnGetUserLoggedInfo(1);

        $VarSortOrder				= ($VarSortOrder=='desc')? 'desc' : 'asc';

        $VarSortCols = array('1'=>'et.enquirytype','2'=>'e.reqforisrior','3'=>'e.datecreated','4'=>'e.stylenamerefno','5'=>'brandname','6'=>'confirmprice','7'=>'currency','8'=>'contactname','9'=>'orderstatus','10'=>'dateupdated','11'=>'status');
        $VarSortBy					= (in_array($VarSortBy,$VarSortCols)) ? $VarSortBy : 'e.dateupdated';

        $VarLimitInfo				= $VarLimit;
        if($offset>=1) {$VarLimitInfo	 = $offset.",".$VarLimit;}

        $ArrWhere                   = array(); $ArrBuyerId = array();

        if($VarEnType<>'') {

            $ArrWhere[]             = "et.enquirytype like '%".$VarEnType."%'";

        }
        if($VarStyleref<>'') {

            $ArrWhere[]             = "e.stylenamerefno like '%".$VarStyleref."%'";

        }
        if($VarIsrior<>'') {

            $ArrWhere[]             = "e.reqforisrior like '%".$VarIsrior."%'";

        }
        if($VarBuyerbrand<>'' && $VarBuyerbrand <> 'null') {

            $ArrWhere[]             = "e.brandbuyerid IN (".$VarBuyerbrand.")";

        }
        if($VarMercn<>'' && $VarMercn <> 'null') {

            $ArrWhere[]             = "e.merchantid IN (".$VarMercn.")";

        }

        if($VarReqfrom<>'' && $VarReqTo <> '') {

            $ArrWhere[]             = "date(e.datecreated) >= '$VarReqfrom' AND date(e.datecreate) <= '$VarReqTo' ";

        }

        if($VarAfilter<>'') {

            $ArrWhere[]             = "br.brandname LIKE '".$VarAfilter."%'";

        }

        if($VarStatus<>'') {

            $ArrWhere[]             = "e.status=".$VarStatus;

        } else {

            $ArrWhere[]             = "e.status in(1,2)";

        }

        $ArrWhere[]                 = "e.companyid = ".$ArrUserLoggedInfo['companyid'] . " AND e.orderstatus <> 2";

        $VarWhere                   = '';

        if(@$ArrWhere[0]<>'') {
            $VarWhere               = implode(" and ",$ArrWhere) ;
        }
        $VarSqlEnq                  = "SELECT e.id,e.enquirytype,br.brandname,br.buyerid,e.countryid,e.currency,u.contactname,et.enquirytype as enq,e.podate,
e.modeofenquiry as modeofenquiryid,e.stylenamerefno,e.quotedprice,e.reqforisrior,e.brandbuyerid,e.merchantid,e.enquirydate,e.datecreated,e.dateupdated,e.dateauthorized,
e.status,e.enquiryrefpono,e.confirmprice,mo.modeofenquiry,e.orderstatus,e.isriorno,e.daterequested FROM ".KN_ORDER_ENQUIRY." AS e INNER JOIN ".KN_MASTER_ENQUIRY_TYPE." 
AS et ON e.enquirytype = et.id INNER JOIN ".KN_MASTER_BRANDS." AS br ON br.id = e.brandbuyerid INNER JOIN ".KN_USERS." as u ON u.id = e.merchantid INNER JOIN 
".KN_MASTER_MODEOFENQUIRY." AS mo ON mo.id = e.modeofenquiry WHERE ".$VarWhere." AND u.usertype = '4' order by ".$VarSortBy." ".$VarSortOrder." limit ".$VarLimitInfo;

        $ResSqlEnq					= $this->db->query($VarSqlEnq)->result();

        foreach($ResSqlEnq as $item) {
            $ArrBuyerId[] = $item->buyerid;
        }

        $ArrBuyers = $this->getBuyer($ArrBuyerId);
        $ArrFinal['enq']            = $ResSqlEnq;
        $ArrFinal['buyer']          = $ArrBuyers;

        return $ArrFinal;

    }

    function getBuyer($ArrBuyerId=array(),$VarBuyerId='') {
        if(count($ArrBuyerId) >= 1) {
            $VarIds = implode(',',$ArrBuyerId);
            $VarSql = "SELECT buyername FROM ".KN_MASTER_BUYER." WHERE id IN ($VarIds)";
            return $this->db->query($VarSql)->result();
        }
        if($VarBuyerId <> '') {
            $VarSql = "SELECT buyername FROM ".KN_MASTER_BUYER." WHERE id =".$VarBuyerId;
            return $this->db->query($VarSql)->result();
        }
    }
    function fnChangeStatus($checkboxids,$optvalue) {
        $Arrids =  json_decode($checkboxids,true);
        $this->db->where_in('id',$Arrids);
        return $this->db->update(KN_ORDER_ENQUIRY,array('status' => $optvalue));
    }
    function getMerchantData($VarCompanyId='',$VarStatus='') {
        if($VarCompanyId <> '' && $VarStatus <> '') {
            $this->db->select('m.id,contactname,m.code');
            $this->db->from(KN_USERS.' AS u');
            $this->db->join(KN_MASTER_MERCHANT.' AS m','m.updatedby = u.id');
            //$this->db->where(array('companyid'=>$VarCompanyId,'status'=>$VarStatus,'usertype'=>'3'));
            $this->db->where(array('m.companyid'=>$VarCompanyId,'m.status'=>$VarStatus,'usertype'=>'3'));
            return $this->db->get()->result();
        }
    }
    function fnAuthorize($VarCompanyId='',$VarEnquiryId='',$VarApprStatus='',$VarComments='',$VarRefNoType='') {
        $VarCode = 0;
        $VarMysqlDt = date('Y-m-d H:i:s');
        if ($VarApprStatus == 2) {
            $ArrWhere = array();
            if ($VarCompanyId <> '') {
                $ArrWhere[] = "e.companyid=" . $VarCompanyId;
            }
            if ($VarRefNoType <> '') {
                $ArrWhere[] = "e.reqforisrior=" . $VarRefNoType;
            }
            $VarWhere = '';
            if (@$ArrWhere[0] <> '') {
                $VarWhere = implode(" and ", $ArrWhere);
            }
            $ArrIsrIor   = unserialize(ARRISRIOR);
            $VarMaxSql   = "SELECT MAX(isriorno) as isriorno FROM " . KN_ORDER_ENQUIRY . " as e WHERE $VarWhere";
            $VarMax      = $this->db->query($VarMaxSql)->result();
            $VarNo       = (int)$VarMax[0]->isriorno;
            $VarCode     = $ArrIsrIor[$VarRefNoType] . "/BSG" . ++$VarNo . "/" . date('my');
            $this->fnInsertLogData(KN_ORDER_ENQUIRY,KN_ORDER_ENQUIRY_LOG,$VarEnquiryId,$VarCompanyId,$VarApprStatus,$VarComments,$VarMysqlDt);
            $this->db->where('id', $VarEnquiryId);
            $ArrData = array('orderstatus' => $VarApprStatus, 'isriorno' => $VarNo, 'isriorcode' => $VarCode, 'comments' => $VarComments,'dateupdated'=>$VarMysqlDt
            ,'dateauthorized'=>$VarMysqlDt);
            $this->db->update(KN_ORDER_ENQUIRY, $ArrData);
        }
        elseif ($VarApprStatus == 3) {
            $this->fnInsertLogData(KN_ORDER_ENQUIRY,KN_ORDER_ENQUIRY_LOG,$VarEnquiryId,$VarCompanyId,$VarApprStatus,$VarComments,$VarMysqlDt);
            $this->db->where('id', $VarEnquiryId);
            $ArrData = array('orderstatus' => $VarApprStatus, 'isriorcode' => $VarCode, 'comments' => $VarComments,'dateupdated'=>$VarMysqlDt,'dateauthorized'=>$VarMysqlDt);
            $this->db->update(KN_ORDER_ENQUIRY, $ArrData);
        }
        return $VarCode;
    }

    function fnInsertLogData($VarFromTable='',$VarToTable='',$VarId='',$VarCompanyId='',$VarApprStatus='',$VarComments='',$VarMysqlDt='') {
        $VarQry = $this->db->get_where($VarFromTable,array('id'=>$VarId,'companyid'=>$VarCompanyId));
        $ArrRes = $VarQry->result_array();
        $ArrInsertData = $ArrRes[0];
        unset($ArrInsertData['id']);
        $ArrInsertData['enquiryid'] = $VarId; $ArrInsertData['orderstatus'] = $VarApprStatus; $ArrInsertData['comments'] = $VarComments;
        $ArrInsertData['dateauthorized'] = $VarMysqlDt;
        $this->db->insert($VarToTable,$ArrInsertData);
        return $this->db->insert_id();
    }

    function fnGetApproved($VarOrderStatus='',$VarStatus='') {
        $ArrUserLoggedInfo                   = fnGetUserLoggedInfo(1);

        $VarSqlApprEnq                  = "SELECT e.id,e.isriorno,e.dateupdated,byr.brandname,byr.buyername,e.stylenamerefno,e.styledesc,e.enquiryrefpono,e.exporderqty,
e.pcsorset,m.code,e.reqforisrior FROM ".KN_ORDER_ENQUIRY." AS e INNER JOIN ".KN_MASTER_ENQUIRY_TYPE." AS et ON e.enquirytype = et.id 
INNER JOIN ".KN_MASTER_BUYER." AS byr ON byr.id = e.brandbuyerid INNER JOIN ".KN_MASTER_MERCHANT." as m 
ON m.id = e.merchantid INNER JOIN ".KN_MASTER_MODEOFENQUIRY." AS mo ON mo.id = e.modeofenquiry WHERE e.orderstatus = ".$VarOrderStatus;

        $ObjResult					= $this->db->query($VarSqlApprEnq)->result();
        return $ObjResult;
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

    function fnGetEnqLog($VarEnqLogId='')
    {
        $ArrWhere = array();
        if ($VarEnqLogId <> '') {

            $ArrWhere[] = "l.id  = " . $VarEnqLogId;

        }
        $VarWhere = '';
        if (count($ArrWhere) >= 1) {
            $VarWhere = " WHERE " . implode(" and ", $ArrWhere);
        }
        $VarLogDetail = "SELECT l.id,l.enquirydate,l.enquiryid,br.brandname,m.contactname,et.enquirytype,l.countryid,l.currency,l.stylenamerefno,
l.styledesc,l.pcsorset,l.quotedprice,l.buyerprice,l.confirmprice,l.exporderqty,l.reqforisrior,l.orderstatus,l.enquiryrefpono,l.comments,l.merchantnote,l.daterequested,
mo.modeofenquiry,br.buyerid,l.datecreated,l.dateupdated,l.dateauthorized FROM " . KN_ORDER_ENQUIRY_LOG . " AS l INNER JOIN " . KN_MASTER_ENQUIRY_TYPE . " AS et ON l.enquirytype = et.id INNER JOIN 
" . KN_MASTER_BRANDS . " AS br ON br.id = l.brandbuyerid INNER JOIN " . KN_MASTER_MERCHANT . " as m ON m.id = l.merchantid INNER JOIN " . KN_MASTER_MODEOFENQUIRY . "
 as mo ON mo.id = l.modeofenquiry " . $VarWhere;
        $ResSqlEnq = $this->db->query($VarLogDetail)->result();
        $VarBuyerId        = $ResSqlEnq[0]->buyerid;
        $VarBuyer          = $this->getBuyer(array(), $VarBuyerId);
        $ArrFinal['et']    = $ResSqlEnq[0];
        $ArrFinal['buyer'] = $VarBuyer[0];

        return $ArrFinal;

    }
}