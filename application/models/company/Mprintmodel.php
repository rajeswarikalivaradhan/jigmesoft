<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class mprintmodel extends CI_Model {

    function fnGetPrintTypeInfo($VarPrintName='',$VarStatus='',$VarId='') {
        $this->db->select('id,printname,updatedby,status,datecreated,dateupdated'); // Select field
        $this->db->from(KN_MASTER_PRINT_TYPE);
        if($VarStatus<>'') {
            $this->db->where_in('status',array($VarStatus));
        } else {
            $this->db->where_in('status',array(1,2));
        }
        if($VarId<>'') {
            $ArrWhere['id'] 				= $VarId;
        }
        if($VarPrintName<>'') {
            $ArrWhere['printname'] 		= $VarPrintName;
        }
        if(@count($ArrWhere)>=1) {
            $this->db->where($ArrWhere);
        }
        $ArrResultList	    	    		= $this->db->get()->result_array();
        return $ArrResultList;
    }

    function fnCheckPrintType($VarPrintName='',$VarId='') {
        $this->db->select('id,printname,updatedby,status,datecreated,dateupdated');
        $this->db->from(KN_MASTER_PRINT_TYPE);
        $ArrWhere = array('status'=>"1");
        if($VarId<>'') {
            $this->db->where_not_in('id',array($VarId));
        }
        if($VarPrintName<>'') {
            $ArrWhere['printname']		= $VarPrintName;
        }
        $this->db->where($ArrWhere);
        $ObjResult  					= $this->db->get();
        $ArrResultList					= $ObjResult->result_array();
        return $ArrResultList;
    }

    function savePrintTypeInfo($ArrUpdateData){
        $VarId                                                  = $ArrUpdateData['id'];
        if($VarId=="") {
            $ArrCheckExist			       					    = $this->fnGetPrintTypeInfo($ArrUpdateData['printname'],1);
            if(@$ArrCheckExist['id']=='' && @$ArrCheckExist['id']==0) {
                unset($ArrUpdateData['id']);
                $this->db->insert(KN_MASTER_PRINT_TYPE,$ArrUpdateData);
                $VarId                                          = $this->db->insert_id();
                $ArrResult['errcode']						    = 1;
                $ArrResult['msg']							    = '';
                $ArrResult['id']							    = $VarId;
                $ArrResult['eid']							    = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode']							= '-1';
                $ArrResult['msg']								= "This method name already exists!!";
            }
        } else {
            $ArrCheckExist									    = $this->fnCheckPrintType($ArrUpdateData['printname'],$VarId);
            if(@$ArrCheckExist['id']=='' && $ArrUpdateData['printname']<>'') {
                if($this->db->update(KN_MASTER_PRINT_TYPE,$ArrUpdateData,array('id'=>$VarId))) {
                    $ArrResult['errcode']					    = 1;
                    $ArrResult['msg']							= '';
                    $ArrResult['id']							= $VarId;
                    $ArrResult['eid']							= urlencode(base64_encode($VarId));
                } else {
                    $ArrResult['errcode']						= '-1';
                    $ArrResult['msg']							= 'Invalid Data!';
                }
            }  else {
                $ArrResult['errcode']						    = '-1';
                $ArrResult['msg']							    = "This dyeing method has already Exist!";
            }
        }
        return $ArrResult;
    }

    function fnCountPrintType($VarPrintName='',$VarStatus=''){
        $ArrWhere                   = array();
        if($VarPrintName<>'') {
            $ArrWhere[]             = "p.printname like '%".$VarPrintName."%'";
        }
        if($VarStatus<>'') {
            $ArrWhere[]             = "p.status=".$VarStatus;
        } else {
            $ArrWhere[]             = "p.status in(1,2)";
        }
        $VarWhere                   = '';
        if(@$ArrWhere[0]<>'') {
            $VarWhere               = implode(" and ",$ArrWhere);
        }
        $VarSqlPrintType            = "SELECT count(1) as trec  FROM ".KN_MASTER_PRINT_TYPE." AS p INNER JOIN ".KN_USERS." AS u ON p.updatedby = u.id WHERE ".$VarWhere;
        $ObjRows					= $this->db->query($VarSqlPrintType)->row();
        return $ObjRows->trec;
    }

    function fnListPrintType($VarPrintName='',$VarStatus='',$VarLimit = 10, $offset = 0,$VarSortBy,$VarSortOrder){
        $VarSortOrder				= ($VarSortOrder=='desc')? 'desc' : 'asc';
        $VarSortCols				= array("1"=>'p.printname','2'=>'p.dateupdated');
        $VarSortBy					= (in_array($VarSortBy,$VarSortCols)) ? $VarSortBy : 'p.dateupdated';
        $VarLimitInfo				= $VarLimit;
        if($offset>=1) {$VarLimitInfo	 = $offset.",".$VarLimit;}
        $ArrWhere                   = array();
        if($VarPrintName<>'') {
            $ArrWhere[]             = "p.printname like '%".$VarPrintName."%'";
        }
        if($VarStatus<>'') {
            $ArrWhere[]             = "p.status=".$VarStatus;
        } else {
            $ArrWhere[]             = "p.status in(1,2)";
        }
        $VarWhere                   = '';
        if(@$ArrWhere[0]<>'') {
            $VarWhere               = implode(" and ",$ArrWhere);
        }
        $VarSqlPrintType             = "SELECT p.id,p.printname,u.contactname,p.datecreated,p.dateupdated,p.status FROM ".KN_MASTER_PRINT_TYPE." AS p INNER JOIN ".KN_USERS." AS u ON p.updatedby = u.id WHERE ".$VarWhere." order by ".$VarSortBy." ".$VarSortOrder." limit ".$VarLimitInfo;
        $ObjResult					= $this->db->query($VarSqlPrintType);
        return $ObjResult;
    }

    function fnDelPrintType($VarFabricId='',$VarUpdatedBy='') {
        if(@$VarFabricId>=1) {
            $ArrUpdateData          = array('status'=>3,'dateupdated'=>date('Y-m-d H:i:s'),'updatedby'=>$VarUpdatedBy);
            if($this->db->update(KN_MASTER_PRINT_TYPE,$ArrUpdateData,array('id'=>$VarFabricId))) {
                $ArrResult['errcode']					    = 1;
                $ArrResult['msg']							= '';
                $ArrResult['id']							= $VarFabricId;
                $ArrResult['eid']							= urlencode(base64_encode($VarFabricId));
            } else {
                $ArrResult['errcode']						= '-1';
                $ArrResult['msg']							= 'Invalid Data!';
            }
        }
        return $ArrResult;
    }

}