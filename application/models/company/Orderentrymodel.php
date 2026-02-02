<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Orderentrymodel extends CI_Model {

    function getOrderDataByWip($VarOrderId, $VarCompanyId) {
        $Res = $this->db->query("SELECT season,divdept,class,sclass,payterms FROM " . ORDERENTRY_COMMONDATA . " 
        WHERE referenceid = '$VarOrderId' AND companyid = '$VarCompanyId' ");
        return $Res->result();
    }

    function fnAllBrands($VarAllBrands = '', $VarStatus = '') {
        $ArrWhere = array();
        if ($VarStatus <> '') {
            $ArrWhere[] = "status=" . $VarStatus;
        } else {
            $ArrWhere[] = "status in(1,2)";
        }
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        if ($VarAllBrands <> '') {
            $VarSql = "SELECT id,brandname FROM " . KN_MASTER_BRANDS . " ";
        }
        return $this->db->query($VarSql)->result();
    }

    function fnBuyerbyBrandId($VarBrandId = '', $VarStatus = '') {
        $ArrWhere = array();
        if ($VarStatus <> '') {
           // commented by myself  regards new brand form
           // $ArrWhere[] = "byr.status=" . $VarStatus;
             $ArrWhere[] = "br.status=" . $VarStatus;
        } else {
             // commented by myself  regards new brand form
             // $ArrWhere[] = "byr.status in(1,2)";
            $ArrWhere[] = "br.status in(1,2)";
        }
        if ($VarBrandId <> '') {
            $ArrWhere[] = "br.id = " . $VarBrandId;
        }
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        // commented by myself  regards new brand form
        //$VarSql = "SELECT byr.id,buyername,br.brandname FROM " . KN_MASTER_BUYER . " as byr INNER JOIN " . KN_MASTER_BRANDS . " as br ON br.buyerid = byr.id WHERE " . $VarWhere;
        $VarSql = "SELECT br.id,br.buyername,br.brandname,br.country FROM " . KN_MASTER_BRANDS . " as br  WHERE " . $VarWhere;
        return $this->db->query($VarSql)->result_array();
    }

    function fnGetCountryName($VarCountryId = '') {
        $qry = $this->db->get_where(KN_COUNTRIES, array('status' => 1, 'id' => $VarCountryId));
        return $qry->result();
    }

    function fnGetOrderEnquiryInfo($VarEnquiryId = '', $VarCompanyId = '', $VarStatus = '') {
        $this->db->select('e.id,enquirytype as enq,countryid,orderenqrefno,currency,stylenamerefno,styledesc,pcsorset,quotedprice,buyerprice,confirmprice,exporderqty,
        reqforisrior,br.brandname,br.buyername,br.country,brandId,buyerId,merchantid,orderstatus,comments,isriorcode,editaccess,completestatus,
		DATE_FORMAT(e.dateupdated,"%d-%m-%Y %H:%i:%s") as formattedDateUpdated, DATE_FORMAT(e.datecreated,"%d-%m-%Y %H:%i:%s") as wipdatecreated, season, class, divi_dept, sub_class, total_order_qty, uom,mgmtid');
        $ArrWhere = array();
        if ($VarStatus <> '') {
            $ArrWhere['e.status'] = $VarStatus;
        }
        if ($VarEnquiryId <> '') {
            $ArrWhere['e.id'] = $VarEnquiryId;
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['e.companyid'] = $VarCompanyId;
        }
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
        $this->db->from(KN_ORDER_ENQUIRY . ' AS e');
        $this->db->join(KN_MASTER_BRANDS . ' AS br', 'br.id = e.brandId');
       // $this->db->join(KN_MASTER_BUYER . ' AS byr', 'byr.id = e.buyerId');
        $ArrResultList = $this->db->get()->result_array();
        return $ArrResultList;
    }

    public function saveFirstTbl($jsondatagrid, $VarReferenceId, $VarDatetime, $VarUserId, $VarCompanyId,$VarRemarks) {
        $this->db->from(ORDERENTRY_FIRSTTBL);
        $this->db->where('referenceid', $VarReferenceId);
        $this->db->where('companyid', $VarCompanyId);
        $checkExists = $this->db->count_all_results();
        if ($checkExists == 1) {
            $this->db->update(ORDERENTRY_FIRSTTBL, array('jsondatagrid' => $jsondatagrid, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'remarks'=>$VarRemarks),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDERENTRY_FIRSTTBL, array('referenceid' => $VarReferenceId, 'jsondatagrid' => $jsondatagrid, 'dateupdated' => $VarDatetime,
                'updatedby' => $VarUserId, 'companyid' => $VarCompanyId,'remarks'=>$VarRemarks));
        }
        /*$this->db->update(ORDERENTRY_THIRDTBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));*/
        $this->db->update(ORDERENTRY_NEW_FOURTH_TBL, array('jsondatagrid' => '', 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_CUTTINGRATIO_FIFTHTBL, array('jsondatagrid' => '', 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_DELIVERYSCHEDULESIXTHTBL, array('jsondatagrid' => '', 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDER_ENTRY_PACKING_DETAILS_TBL, array('jsondatagrid' => '', 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));

        //Delete the fabric program data (in DB) if any
        $VarDelFabricPro = "DELETE FROM ".FABRIC_PROGRAM_ALL_JXL." WHERE referenceid = '".$VarReferenceId."' AND companyid = '".$this->companyid."' ";
        $this->db->query($VarDelFabricPro);
        //Delete the BOM program data (in DB) if any
        $VarDelBomPro = "DELETE FROM ".KN_BOM." WHERE referenceid = '".$VarReferenceId."' AND companyid = '".$this->companyid."' ";
        $this->db->query($VarDelBomPro);
        return $VarReferenceId;
    }

    /*public function insertRemarks($VarRemarks,$VarTableId,$VarReferenceId, $VarCompanyId) {
    $this->db->insert(ORDERENTRY_ALLTBL_REAMRKS, array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,
        'remarks' => $VarRemarks, 'tableid' =>$VarTableId));
    }

    public function updateRemarks($VarRemarks,$VarTableId,$VarReferenceId, $VarCompanyId) {
        $this->db->update(ORDERENTRY_ALLTBL_REAMRKS, array('remarks' => $VarRemarks),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'tableid'=>$VarTableId));
    }*/

    public function saveSizeChart($VarReferenceId = '', $VarCompanyId = '', $ArrSizeChart = array()) {
        //$this->db->update(ORDERENTRY_SIZECHART, $ArrSizeChart, array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        //return true;
        $this->db->from(ORDERENTRY_SIZECHART);
        $this->db->where('referenceid', $VarReferenceId);
        $this->db->where('companyid', $VarCompanyId);
        $checkExists = $this->db->count_all_results();
        if ($checkExists == 1) {
            $this->db->update(ORDERENTRY_SIZECHART, $ArrSizeChart, array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $ArrSizeChart['referenceid'] = $VarReferenceId;
            $ArrSizeChart['companyid'] = $VarCompanyId;
            $this->db->insert(ORDERENTRY_SIZECHART, $ArrSizeChart);
        }
        return $VarReferenceId;
    }

    public function getFirstTable($VarEnquiryId = '', $VarCompanyId = '') {
        $this->db->select('jsondatagrid,remarks');
        $VarQry = $this->db->get_where(ORDERENTRY_FIRSTTBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $VarQry->row();
    }

    public function getAllTableRemarks($VarEnquiryId = '', $VarCompanyId = '', $VarTableId = '') {
        $ArrResRemarks = $this->db->get_where(ORDERENTRY_ALLTBL_REAMRKS, array('tableid' => $VarTableId, 'referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $ArrResRemarks->row();
    }

    public function getvalues($VarTbl, $VarCol, $VarId) {
        $this->db->select($VarCol);
        $qry = $this->db->get_where($VarTbl, array('id' => $VarId));
        return $qry->row();
    }

    public function saveSecondTbl($jsondatagrid, $VarReferenceId, $VarDatetime, $VarUserId, $VarCompanyId,$VarRemarks) {
        $this->db->from(ORDERENTRY_SECONDTBL);
        $this->db->where('referenceid', $VarReferenceId);
        $this->db->where('companyid', $VarCompanyId);
        $checkExists = $this->db->count_all_results();
        if ($checkExists == 1) {
            $this->db->update(ORDERENTRY_SECONDTBL, array('jsondatagrid' => $jsondatagrid, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'remarks'=>$VarRemarks),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDERENTRY_SECONDTBL, array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,
                'jsondatagrid' => $jsondatagrid, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,'remarks'=>$VarRemarks));
        }
        $ArrData = json_decode($jsondatagrid, true);
        foreach ($ArrData as $key => $data) {
            $ArrPonoDuplicates[] = $data[3];
        }
        if (empty($ArrPonoDuplicates)) {
        } else {
            if (count($ArrPonoDuplicates) !== count(array_unique($ArrPonoDuplicates))) {
                $ArrPonoIds = get_keys_for_duplicate_values($ArrPonoDuplicates);
                foreach ($ArrPonoIds as $key => $pono) {
                    $ArrId = implode(',', $pono);
                    $this->db->insert(WORKINPROCESSTBL, array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId, 'updatedby' => $VarUserId,
                        'datecreated' => $VarDatetime, 'dateupdated' => $VarDatetime, 'arrpoids' => $ArrId));
                }
                foreach ($ArrPonoIds as $ArrPono) {
                    foreach ($ArrPono as $varpono) {
                        $this->db->insert(EACHPONOSTATUSTBL, array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId, 'ponoid' => $varpono,
                            'updatedby' => $VarUserId, 'datecreated' => $VarDatetime, 'dateupdated' => $VarDatetime));
                    }
                }
            } else {
                $ArrPonoIds = $ArrPonoDuplicates;
                foreach ($ArrPonoIds as $key => $pono) {
                    $this->db->insert(WORKINPROCESSTBL, array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId, 'updatedby' => $VarUserId,
                        'datecreated' => $VarDatetime, 'dateupdated' => $VarDatetime, 'arrpoids' => $key));
                }
                foreach ($ArrPonoIds as $keyId => $ArrPono) {
                    $this->db->insert(EACHPONOSTATUSTBL, array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId, 'ponoid' => $keyId,
                        'updatedby' => $VarUserId, 'datecreated' => $VarDatetime, 'dateupdated' => $VarDatetime));
                }
            }
        }
        /*$this->db->update(ORDERENTRY_THIRDTBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));*/
        $this->db->update(ORDERENTRY_NEW_FOURTH_TBL, array('jsondatagrid' => '', 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_CUTTINGRATIO_FIFTHTBL, array('jsondatagrid' => '', 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_DELIVERYSCHEDULESIXTHTBL, array('jsondatagrid' => '', 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDER_ENTRY_PACKING_DETAILS_TBL, array('jsondatagrid' => '', 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        return $VarReferenceId;
    }

    public function getSecondTable($VarEnquiryId, $VarCompanyId = '') {
        $this->db->select('jsondatagrid,remarks');
        $VarQry = $this->db->get_where(ORDERENTRY_SECONDTBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $VarQry->row();
    }

    public function saveCommonDetailsData($VarReferenceId = '', $VarCompanyId = '', $ArrData = array()) {
        /*$this->db->where('referenceid', $VarReferenceId);
        $this->db->where('companyid', $VarCompanyId);
        $this->db->update(ORDERENTRY_COMMONDATA, $ArrData);*/
        $this->db->from(ORDERENTRY_COMMONDATA);
        $this->db->where('referenceid', $VarReferenceId);
        $this->db->where('companyid', $VarCompanyId);
        $checkExists = $this->db->count_all_results();
        if ($checkExists == 1) {
            $this->db->update(ORDERENTRY_COMMONDATA, $ArrData, array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $ArrData['referenceid'] = $VarReferenceId;
            $ArrData['companyid'] = $VarCompanyId;
            $this->db->insert(ORDERENTRY_COMMONDATA, $ArrData);
        }
        return $VarReferenceId;
    }

    public function getCommonData($VarEnquiryId = '', $VarCompanyId = '') {
        $this->db->select('season,class,sclass,divdept,payterms,orderbookingrate,orderrealization');
        $VarQry = $this->db->get_where(ORDERENTRY_COMMONDATA, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        if (!empty($VarQry->row())) {
            return $VarQry->row();
        } else {
            return false;
        }
    }

    public function saveThirdTbl($jsondatagrid, $VarReferenceId = '', $VarCompanyId = '', $VarDatetime = '', $VarUserId = '',$VarRemarks) {
        $this->db->from(ORDERENTRY_THIRDTBL);
        $this->db->where('referenceid', $VarReferenceId);
        $this->db->where('companyid', $VarCompanyId);
        $checkExists = $this->db->count_all_results();
        if ($checkExists == 1) {
            $this->db->update(ORDERENTRY_THIRDTBL, array('jsondatagrid' => $jsondatagrid, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'remarks'=>$VarRemarks),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));

        } else {
            $this->db->insert(ORDERENTRY_THIRDTBL, array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,
                'jsondatagrid' => $jsondatagrid, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,'remarks'=>$VarRemarks));

        }
        $this->db->update(ORDERENTRY_NEW_FOURTH_TBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_CUTTINGRATIO_FIFTHTBL, array('jsondatagrid' => '', 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_DELIVERYSCHEDULESIXTHTBL, array('jsondatagrid' => '', 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDER_ENTRY_PACKING_DETAILS_TBL, array('jsondatagrid' => '', 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));

        /*$this->db->update(ORDERENTRY_NEW_FOURTH_TBL, array('jsondatagrid' => '', 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_DELIVERYSCHEDULESIXTHTBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_CUTTINGRATIO_FIFTHTBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_SEVENTHKNITTBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_B4SEVENTHKNITTBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        //Update this table - FABRIC DETAILS - KNIT : COLOR WISE LYCRA (%)
        $this->db->update(ORDERENTRY_FABRIC_DETAILS_KNIT_LYCRA, array('jsondatagrid' => '', 'nextTableData' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDER_ENTRY_FABRIC_KNIT_LYCRA_FORMULA_RES, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_GARMENTPARTSTBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_DYEINGNINTH_TBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));*/
        return true;
    }

    public function getFromThirdTbl($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid,remarks');
        $VarQry = $this->db->get_where(ORDERENTRY_THIRDTBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $VarQry->row();
    }

    public function saveNewFourthTbl($jsonDataGrid, $ArrGcd, $VarReferenceId, $VarUserId, $VarCompanyId,$VarRemarks) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_NEW_FOURTH_TBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_NEW_FOURTH_TBL, array('jsondatagrid' => $jsonDataGrid, 'gcd_for_cutting_ratio' => implode(',', $ArrGcd),
                'updatedby' => $VarUserId,'remarks'=>$VarRemarks,'dateupdated'=>$this->mysqldatetime),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDERENTRY_NEW_FOURTH_TBL, array('jsondatagrid' => $jsonDataGrid, 'gcd_for_cutting_ratio' => implode(',', $ArrGcd),
                'updatedby' => $VarUserId, 'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'remarks'=>$VarRemarks));
        }
        $this->db->update(ORDERENTRY_CUTTINGRATIO_FIFTHTBL, array('jsondatagrid' => '', 'updatedby' => $VarUserId,'dateupdated'=>$this->mysqldatetime),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));

        /*$this->db->update(ORDERENTRY_DELIVERYSCHEDULESIXTHTBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_CUTTINGRATIO_FIFTHTBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_SEVENTHKNITTBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_B4SEVENTHKNITTBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        //Update this table - FABRIC DETAILS - KNIT : COLOR WISE LYCRA (%)
        $this->db->update(ORDERENTRY_FABRIC_DETAILS_KNIT_LYCRA, array('jsondatagrid' => '', 'nextTableData' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDER_ENTRY_FABRIC_KNIT_LYCRA_FORMULA_RES, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_GARMENTPARTSTBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        $this->db->update(ORDERENTRY_DYEINGNINTH_TBL, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));*/
        return $VarReferenceId;
    }

    public function getFromNewFourthTbl($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid,gcd_for_cutting_ratio,remarks');
        $VarQry = $this->db->get_where(ORDERENTRY_NEW_FOURTH_TBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $VarQry->row();
    }

    public function saveDeliveryScheduleSixthTblOLD($jsonDataGrid, $VarCompanyId = '', $VarReferenceId = '', $VarDatetime = '', $VarUserId = '',$VarRemarks) {
        $ArrDeliverySch = json_decode($jsonDataGrid, true);
        if (!empty($ArrDeliverySch)) {
            $this->db->delete(DELIVERY_SCHEDULE_WIP_LIST, array('referenceid' => $VarReferenceId));
            foreach ($ArrDeliverySch as $value) {
                $ArrComboColor[] = $value[2];
                $ArrPoNoEnqRefNo[] = $value[3];
                $ArrPoQtySampleQty[] = $value[4];
                $ArrPcs_set[] = $value[5];
                $ArrShipmentSubDate[] = $value[7];
            }
            if (count($ArrPoNoEnqRefNo) !== count(array_unique($ArrPoNoEnqRefNo))) {
                $ArrPo = get_keys_for_duplicate_values($ArrPoNoEnqRefNo);
                //echo '<pre>'; print_r($ArrPo); die('die');
                foreach ($ArrPo as $poNo => $poNoItemId) {
                    $ArrPoqToSum = 0;
                    $ArrComboColorJoined = $gridRowId = [];
                    foreach ($poNoItemId as $ids) {
                        if (is_numeric($ArrPoQtySampleQty[$ids])) $ArrPoqToSum += $ArrPoQtySampleQty[$ids];
                        else $ArrPoqToSum += 0;
                        /*ComboColor Will change for same Po.No for Array for ComboColor
                         * */
                        $ArrComboColorJoined[] = $ArrComboColor[$ids];
                        $ArrPcsSetJoined = $ArrPcs_set[$ids];
                        /*Always shipDate must be same for same PO.NO
                         * */
                        $ArrShipmentDateJoined = $ArrShipmentSubDate[$ids];
                        $gridRowId[] = $ids;
                    }
                    $ArrPoNoPoQtyShipmentDate = array('ids' => implode(',', $gridRowId), 'companyid' => $VarCompanyId, 'referenceid' => $VarReferenceId,
                        'comboColor' => implode(',', $ArrComboColorJoined), 'poQtySampleQty' => $ArrPoqToSum, 'pcs_set' => $ArrPcsSetJoined,
                        'poNoEnqRefNo' => $poNo, 'shipmentSubDate' => $ArrShipmentDateJoined);
                    $this->db->insert(DELIVERY_SCHEDULE_WIP_LIST, $ArrPoNoPoQtyShipmentDate);
                }
            } else { // No duplicate PO.NO, shipDate
                foreach ($ArrDeliverySch as $value) {
                    $ArrUpdate['companyid'] = $VarCompanyId;
                    $ArrUpdate['referenceid'] = $VarReferenceId;
                    $ArrUpdate['dateupdated'] = $VarDatetime;
                    $ArrUpdate['updatedby'] = $VarUserId;
                    $ArrUpdate['poEnqDate'] = $value[0];
                    $ArrUpdate['poEnqRecdDate'] = $value[1];
                    $ArrUpdate['comboColor'] = $value[2];
                    $ArrUpdate['poNoEnqRefNo'] = $value[3];
                    $ArrUpdate['poQtySampleQty'] = $value[4];
                    $ArrUpdate['pcs_set'] = $value[5];
                    $ArrUpdate['mos'] = $value[6];
                    $ArrUpdate['shipmentSubDate'] = $value[7];
                    $ArrUpdate['loadingPc'] = $value[8];
                    $ArrUpdate['loadingCntry'] = $value[9];
                    $ArrUpdate['destPc'] = $value[10];
                    $ArrUpdate['destCntry'] = $value[11];
                    $this->db->insert(DELIVERY_SCHEDULE_WIP_LIST, $ArrUpdate);
                }
            }
        }
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_DELIVERYSCHEDULESIXTHTBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_DELIVERYSCHEDULESIXTHTBL, array('jsondatagrid' => $jsonDataGrid, 'dateupdated' => $VarDatetime,
                'updatedby' => $VarUserId,'remarks'=>$VarRemarks),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));

        } else {
            $this->db->insert(ORDERENTRY_DELIVERYSCHEDULESIXTHTBL, array('jsondatagrid' => $jsonDataGrid, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'remarks'=>$VarRemarks));

        }
        return $VarReferenceId;
    }

    public function saveDeliveryScheduleSixthTbl($ftDatOnlyjsonData, $allDataJsonData,$VarCompanyId = '', $VarReferenceId = '', $VarDatetime = '', $VarUserId = '',$VarRemarks) {
        //For Displaying WIP List
        $ArrDeliverySch = json_decode($allDataJsonData, true);
        if (!empty($ArrDeliverySch)) {
            $this->db->delete(DELIVERY_SCHEDULE_WIP_LIST, array('referenceid' => $VarReferenceId));
            foreach ($ArrDeliverySch as $value) {
                $ArrComboColor[] = $value[2];
                $ArrPoNoEnqRefNo[] = $value[3];
                $ArrPoQtySampleQty[] = $value[4];
                $ArrPcs_set[] = $value[5];
                $ArrShipmentSubDate[] = $value[7];
            }
            if (count($ArrPoNoEnqRefNo) !== count(array_unique($ArrPoNoEnqRefNo))) {
                $ArrPo = get_keys_for_duplicate_values($ArrPoNoEnqRefNo);
                //echo '<pre>'; print_r($ArrPo); die('die');
                foreach ($ArrPo as $poNo => $poNoItemId) {
                    $ArrPoqToSum = 0;
                    $ArrComboColorJoined = $gridRowId = [];
                    foreach ($poNoItemId as $ids) {
                        if (is_numeric($ArrPoQtySampleQty[$ids])) $ArrPoqToSum += $ArrPoQtySampleQty[$ids];
                        else $ArrPoqToSum += 0;
                        /*ComboColor Will change for same Po.No for Array for ComboColor
                         * */
                        $ArrComboColorJoined[] = $ArrComboColor[$ids];
                        $ArrPcsSetJoined = $ArrPcs_set[$ids];
                        /*Always shipDate must be same for same PO.NO
                         * */
                        $ArrShipmentDateJoined = $ArrShipmentSubDate[$ids];
                        $gridRowId[] = $ids;
                    }
                    $ArrPoNoPoQtyShipmentDate = array('ids' => implode(',', $gridRowId), 'companyid' => $VarCompanyId, 'referenceid' => $VarReferenceId,
                        'comboColor' => implode(',', $ArrComboColorJoined), 'poQtySampleQty' => $ArrPoqToSum, 'pcs_set' => $ArrPcsSetJoined,
                        'poNoEnqRefNo' => $poNo, 'shipmentSubDate' => $ArrShipmentDateJoined);
                    $this->db->insert(DELIVERY_SCHEDULE_WIP_LIST, $ArrPoNoPoQtyShipmentDate);
                }
            } else { // No duplicate PO.NO, shipDate
                //echo '<pre>'; print_r($ArrDeliverySch); die('die');
                foreach ($ArrDeliverySch as $value) {
                    $ArrUpdate['companyid'] = $VarCompanyId;
                    $ArrUpdate['referenceid'] = $VarReferenceId;
                    $ArrUpdate['dateupdated'] = $VarDatetime;
                    $ArrUpdate['updatedby'] = $VarUserId;
                    $ArrUpdate['poEnqDate'] = $value[0];
                    $ArrUpdate['poEnqRecdDate'] = $value[1];
                    $ArrUpdate['comboColor'] = $value[2];
                    $ArrUpdate['poNoEnqRefNo'] = $value[3];
                    $ArrUpdate['poQtySampleQty'] = $value[4];
                    $ArrUpdate['pcs_set'] = $value[5];
                    $ArrUpdate['mos'] = $value[6];
                    $ArrUpdate['shipmentSubDate'] = $value[7];
                    $ArrUpdate['loadingPc'] = $value[8];
                    $ArrUpdate['loadingCntry'] = $value[9];
                    $ArrUpdate['destPc'] = $value[10];
                    $ArrUpdate['destCntry'] = $value[11];
                    $this->db->insert(DELIVERY_SCHEDULE_WIP_LIST, $ArrUpdate);
                }
            }
        }
        //For Displaying WIP List ENDS
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_DELIVERYSCHEDULESIXTHTBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_DELIVERYSCHEDULESIXTHTBL, array('jsondatagrid' => $ftDatOnlyjsonData, 'dateupdated' => $VarDatetime,
                'updatedby' => $VarUserId,'remarks'=>$VarRemarks),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));

        } else {
            $this->db->insert(ORDERENTRY_DELIVERYSCHEDULESIXTHTBL, array('jsondatagrid' => $ftDatOnlyjsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'remarks'=>$VarRemarks));

        }
        return $VarReferenceId;
    }


    public function getFromDeliveryScheduleSixthTbl($VarEnquiryId, $VarCompanyId = '') {
        $this->db->select('referenceid,jsondatagrid,remarks');
        $Var4Qry = $this->db->get_where(ORDERENTRY_DELIVERYSCHEDULESIXTHTBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $Var4Qry->row();
    }

    public function getSizeChart($VarEnquiryId, $VarCompanyId = '') {
        $this->db->select('sizecharttype,sizechartvalue');
        $VarSizeChartQry = $this->db->get_where(ORDERENTRY_SIZECHART, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        if(empty($VarSizeChartQry)) {
            return false;
        }
        return $VarSizeChartQry->row();
    }

    /*    public function saveATbl($ArrData, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId) {

            foreach ($ArrData as $data) {
                $this->db->where('referenceid',$VarReferenceId);
                $this->db->where('companyid',$VarCompanyId);
                $this->db->update(ORDERENTRY_SIXTHATBL,array('combo'=>$data[0],'component'=>$data[1],'color'=>$data[2],'sizespeccode'=>$data[3],
                    'ponumber'=>is_array($data[4]) ? implode(',',$data[4]) : $data[4],'size1'=>$data[5],'size2'=>$data[6],'size3'=>$data[7],'size4'=>$data[8],'size5'=>$data[9],'size6'=>$data[10],'size7'=>$data[11],
                    'size8'=>$data[12],'itemizedpoqty'=>$data[13],'pcsorset'=>$data[14],'intakeqty'=>$data[15],'itemizedqty'=>$data[16],
                    'datecreated'=>$VarDatetime,'dateupdated'=>$VarDatetime,'status'=>'1','updatedby'=>$VarUserId));
            }
            return true;

        }*/

    public function getFromCuttingRatioFifthTbl($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid,remarks');
        $VarQry = $this->db->get_where(ORDERENTRY_CUTTINGRATIO_FIFTHTBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $VarQry->row();
    }

    /*    public function getFromATbl($VarEnquiryId, $VarCompanyId) {

            $this->db->select('*');
            $VarQry    = $this->db->get_where(ORDERENTRY_ATBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
            $ArrResult = $VarQry->row();
            return $ArrResult;
        }*/
    public function saveCuttingRatioFifthTbl($jsonData, $VarReferenceId = '', $VarCompanyId = '', $VarDatetime, $VarUserId,$VarRemarks) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_CUTTINGRATIO_FIFTHTBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_CUTTINGRATIO_FIFTHTBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'remarks'=>$VarRemarks),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDERENTRY_CUTTINGRATIO_FIFTHTBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'remarks'=>$VarRemarks));
        }
        return $VarReferenceId;
        /*$this->db->update(ORDERENTRY_CUTTINGRATIO_FIFTHTBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        return true;*/
    }

/*    public function saveSeventhTbl($jsonData, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_SEVENTHKNITTBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_SEVENTHKNITTBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDERENTRY_SEVENTHKNITTBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        }
        return $VarReferenceId;
    }*/

    /*public function saveB4SeventhTbl($jsonData, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId,$VarRemarks,$VarTableId) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_B4SEVENTHKNITTBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_B4SEVENTHKNITTBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
            $this->updateRemarks($VarRemarks,$VarTableId,$VarReferenceId,$VarCompanyId);
        } else {
            $this->db->insert(ORDERENTRY_B4SEVENTHKNITTBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
            $this->insertRemarks($VarRemarks,$VarTableId,$VarReferenceId,$VarCompanyId);
        }
        return $VarReferenceId;

    }*/
    public function saveGarmentPartsJxl($jsonData, $jsonb4Seven, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_GARMENTPARTSTBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_GARMENTPARTSTBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
            $this->db->update(ORDERENTRY_B4SEVENTHKNITTBL, array('jsondatagrid' => $jsonb4Seven, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDERENTRY_GARMENTPARTSTBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        }

        return $VarReferenceId;
        /*$this->db->update(ORDERENTRY_GARMENTPARTSTBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        return true;*/
    }
    /*public function getGarmentPartsJxl($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid');
        $VarQry = $this->db->get_where(ORDERENTRY_GARMENTPARTSTBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }*/

   /*public function getFromLycraDataTbl($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid,nexttabledata');
        $VarQry = $this->db->get_where(ORDERENTRY_FABRIC_DETAILS_KNIT_LYCRA, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        $ArrResult = $VarQry->row();
        return $ArrResult;
   }*/

    /*public function saveLycraDataTbl($jsonData, $nextTableData,$VarReferenceId, $VarCompanyId, $VarDatetime, $VarUserId,$VarRemarks,$VarTableId) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_FABRIC_DETAILS_KNIT_LYCRA)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_FABRIC_DETAILS_KNIT_LYCRA, array('jsondatagrid' => $jsonData,'nextTableData' => $nextTableData,
                'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId), array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
            $this->db->update(ORDER_ENTRY_NINTH, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
            $this->updateRemarks($VarRemarks,$VarTableId,$VarReferenceId,$VarCompanyId);
        } else {
            $this->db->insert(ORDERENTRY_FABRIC_DETAILS_KNIT_LYCRA, array('jsondatagrid' => $jsonData,'nextTableData' => $nextTableData,
                'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId, 'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
            $this->insertRemarks($VarRemarks,$VarTableId,$VarReferenceId,$VarCompanyId);
        }
        return $VarReferenceId;
    }*/

    /*public function saveFabKnitColorWiseFabBlendAndContent($jsonData, $VarReferenceId, $VarCompanyId, $VarDatetime, $VarUserId) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDER_ENTRY_FABRIC_KNIT_LYCRA_FORMULA_RES)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDER_ENTRY_FABRIC_KNIT_LYCRA_FORMULA_RES, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDER_ENTRY_FABRIC_KNIT_LYCRA_FORMULA_RES, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        }
        return $VarReferenceId;

    }*/

/*    public function saveEighth($jsonData, $VarReferenceId, $VarCompanyId, $VarDatetime, $VarUserId,$VarRemarks,$VarTableId) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDER_ENTRY_EIGHTH)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDER_ENTRY_EIGHTH, array('jsondatagrid' => $jsonData,
                'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId), array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
            $this->db->update(ORDER_ENTRY_NINTH, array('jsondatagrid' => '', 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
            $this->updateRemarks($VarRemarks,$VarTableId,$VarReferenceId,$VarCompanyId);
        } else {
            $this->db->insert(ORDER_ENTRY_EIGHTH, array('jsondatagrid' => $jsonData,
                'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId, 'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
            $this->insertRemarks($VarRemarks,$VarTableId,$VarReferenceId,$VarCompanyId);
        }
        return $VarReferenceId;
    }*/
    /*public function saveNinth($jsonData, $VarReferenceId, $VarCompanyId, $VarDatetime, $VarUserId,$VarRemarks,$VarTableId) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDER_ENTRY_NINTH)
            ->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDER_ENTRY_NINTH, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
            $this->updateRemarks($VarRemarks,$VarTableId,$VarReferenceId,$VarCompanyId);
        } else {
            $this->db->insert(ORDER_ENTRY_NINTH, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
            $this->insertRemarks($VarRemarks,$VarTableId,$VarReferenceId,$VarCompanyId);
        }
        return $VarReferenceId;
    }*/

    /*public function getNinth($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid');
        $VarQry = $this->db->get_where(ORDER_ENTRY_NINTH, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }*/

/*    public function getEighth($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid');
        $VarQry = $this->db->get_where(ORDER_ENTRY_EIGHTH, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }*/

    /*public function getFabKnitColorWiseFabBlendAndContent($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid');
        $VarQry = $this->db->get_where(ORDER_ENTRY_FABRIC_KNIT_LYCRA_FORMULA_RES, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }*/

    /*public function getFromB4SeventhTbl($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid');
        $VarQry = $this->db->get_where(ORDERENTRY_B4SEVENTHKNITTBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }*/

    /*public function getFromSeventhTbl($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid');
        $VarQry = $this->db->get_where(ORDERENTRY_SEVENTHKNITTBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }*/

    /*public function saveEighthTbl($jsonData, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_WOVENEIGHTH_TBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_WOVENEIGHTH_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDERENTRY_WOVENEIGHTH_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        }
        return $VarReferenceId;
    }*/

    public function getFromEighthTbl($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid');
        $VarQry = $this->db->get_where(ORDERENTRY_WOVENEIGHTH_TBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $VarQry->row();
    }
    public function saveNinthTbl($jsonData, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId,$VarRemarks,$VarTableId) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_DYEINGNINTH_TBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_DYEINGNINTH_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
            $this->updateRemarks($VarRemarks,$VarTableId,$VarReferenceId,$VarCompanyId);
        } else {
            $this->db->insert(ORDERENTRY_DYEINGNINTH_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
            $this->insertRemarks($VarRemarks,$VarTableId,$VarReferenceId,$VarCompanyId);
        }
        return $VarReferenceId;
        /*$this->db->update(ORDERENTRY_DYEINGNINTH_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        return true;*/
    }
    public function getFromNinthTbl($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid');
        $VarQry = $this->db->get_where(ORDERENTRY_DYEINGNINTH_TBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $VarQry->row();
    }
    public function saveTenthTbl($jsonData, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_EMBLISHMENTTENTH_TBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_EMBLISHMENTTENTH_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDERENTRY_EMBLISHMENTTENTH_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        }
        return $VarReferenceId;
        /*$this->db->update(ORDERENTRY_EMBLISHMENTTENTH_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        return true;*/
    }

    public function saveArtworkCode($jsonData, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId,$VarRemarks) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_ARTWORKCODE_TBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_ARTWORKCODE_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'remarks'=>$VarRemarks),array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));

        } else {
            $this->db->insert(ORDERENTRY_ARTWORKCODE_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'remarks'=>$VarRemarks));

        }
        return $VarReferenceId;
    }

    public function getFromTenthTbl($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid');
        $VarQry = $this->db->get_where(ORDERENTRY_EMBLISHMENTTENTH_TBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $VarQry->row();
    }

    public function getArtworkCodeTbl($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid,remarks');
        $VarQry = $this->db->get_where(ORDERENTRY_ARTWORKCODE_TBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $VarQry->row();
    }
/*    public function saveEleventhTbl($jsonData, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId, $VarArticleId = '') {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_BOMELEVENTH_TBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_BOMELEVENTH_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('companyid' => $VarCompanyId, 'referenceid' => $VarReferenceId, 'articleid' => $VarArticleId));
        } else {
            $this->db->insert(ORDERENTRY_BOMELEVENTH_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'companyid' => $VarCompanyId, 'referenceid' => $VarReferenceId, 'articleid' => $VarArticleId));
        }
        return $VarReferenceId;
    }*/

    /*public function getBomArticle($VarEnquiryId, $VarCompanyId, $VarArticleId = '') {
        $ArrWhere = array();
        if (!empty($VarEnquiryId)) {
            $ArrWhere[] = "referenceid = " . $VarEnquiryId;
        }
        if (!empty($VarCompanyId)) {
            $ArrWhere[] = "companyid = " . $VarCompanyId;
        }
        if (!empty($VarArticleId)) {
            $ArrWhere[] = "articleid = " . $VarArticleId;
        }
        $VarWhere = ' WHERE ';
        if (!empty($ArrWhere)) {
            $VarWhere .= implode(" AND ", $ArrWhere);
        }
        $VarSql = "SELECT jsondatagrid FROM " . ORDERENTRY_BOM_ARTICLE . " $VarWhere ";
        return $this->db->query($VarSql)->result();
    }*/

    /*public function saveBomArticle($jsonData, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId, $VarArticleId='',$VarRemarks,$VarTableId) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'articleid'=>$VarArticleId])->from(ORDERENTRY_BOM_ARTICLE)->count_all_results();
        if (!empty($VarCountAllRes)) {
            $this->db->update(ORDERENTRY_BOM_ARTICLE, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('companyid' => $VarCompanyId, 'referenceid' => $VarReferenceId, 'articleid' => $VarArticleId));
            $this->updateRemarks($VarRemarks,$VarTableId,$VarReferenceId,$VarCompanyId);
        } else {
            $this->db->insert(ORDERENTRY_BOM_ARTICLE, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'companyid' => $VarCompanyId, 'referenceid' => $VarReferenceId, 'articleid' => $VarArticleId));
            $this->insertRemarks($VarRemarks,$VarTableId,$VarReferenceId,$VarCompanyId);
        }
        return $VarReferenceId;
    }*/

    public function getAllPoNumber($VarEnquiryId, $VarCompanyId) {
        $ArrPoNo = [];
        $this->db->select('jsondatagrid');
        $VarQry = $this->db->get_where(ORDERENTRY_SECONDTBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        $ArrResult = $VarQry->row();
        if (!empty($ArrResult->jsondatagrid)) {
            $ArrJsonData = json_decode($ArrResult->jsondatagrid);
            foreach ($ArrJsonData as $json) {
                $ArrPoNo[] = $json[4];
            }
        }
        //return array_unique($ArrPoNo);
        return $ArrPoNo;
    }
    public function getAllSizeSpecCode($VarEnquiryId, $VarCompanyId) {
        //$this->db->distinct();
        $ArrSizeSpecCode = [];
        $this->db->select('jsondatagrid');
        $VarQry = $this->db->get_where(ORDERENTRY_NEW_FOURTH_TBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        $ArrResult = $VarQry->row();
        if (!empty($ArrResult->jsondatagrid)) {
            $ArrJsonData = json_decode($ArrResult->jsondatagrid);
            foreach ($ArrJsonData as $json) {
                $ArrSizeSpecCode[] = $json[5];
            }
        }
        return array_unique($ArrSizeSpecCode);
    }

    /*public function saveBomConsolidated($VarCompanyId = '', $jsonData, $VarReferenceId = '', $VarDatetime = '', $VarUserId = '', $VarArticleId = '') {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'articleid'=>$VarArticleId])->from(ORDERENTRY_BOM_CONSOLIDATED)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_BOM_CONSOLIDATED, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('companyid' => $VarCompanyId, 'referenceid' => $VarReferenceId, 'articleid' => $VarArticleId));
        } else {
            $this->db->insert(ORDERENTRY_BOM_CONSOLIDATED, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'companyid' => $VarCompanyId, 'referenceid' => $VarReferenceId, 'articleid' => $VarArticleId));
        }
        return $VarReferenceId;
    }*/

    /*public function getBom1_consolidated($VarEnquiryId, $VarCompanyId, $VarArticleId = '') {
        $ArrWhere = array();
        if (!empty($VarEnquiryId)) {
            $ArrWhere[] = "referenceid = " . $VarEnquiryId;
        }
        if (!empty($VarCompanyId)) {
            $ArrWhere[] = "companyid = " . $VarCompanyId;
        }
        if (!empty($VarArticleId)) {
            $ArrWhere[] = "articleid = " . $VarArticleId;
        }
        $VarWhere = ' WHERE ';
        if (!empty($ArrWhere)) {
            $VarWhere .= implode(" AND ", $ArrWhere);
        }
        $VarSql = "SELECT jsondatagrid FROM " . ORDERENTRY_BOM_CONSOLIDATED . " $VarWhere ";
        return $this->db->query($VarSql)->result();
    }*/

    /*public function saveBomSourcingDetails($jsonData, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId, $VarArtType) {
        $this->db->from(ORDER_ENTRY_BOM_SOURCING_TBL);
        $this->db->where('referenceid', $VarReferenceId);
        $this->db->where('companyid', $VarCompanyId);
        $checkExists = $this->db->count_all_results();
        if ($checkExists == 1) {
            $this->db->update(ORDER_ENTRY_BOM_SOURCING_TBL, array('jsondatagrid' => $jsonData, 'referenceid' => $VarReferenceId, 'dateupdated' => $VarDatetime,
                'updatedby' => $VarUserId), array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId, 'arttype' => $VarArtType));
        } else {
            $this->db->insert(ORDER_ENTRY_BOM_SOURCING_TBL, array('jsondatagrid' => $jsonData, 'referenceid' => $VarReferenceId, 'dateupdated' => $VarDatetime,
                'updatedby' => $VarUserId, 'companyid' => $VarCompanyId, 'arttype' => $VarArtType));
        }
        return $VarReferenceId;
    }*/

    /*public function getBomSourcingDetails($VarEnquiryId, $VarCompanyId = '', $VarArtType = '') {
        $this->db->select('jsondatagrid');
        $this->db->where('referenceid', $VarEnquiryId);
        $this->db->where('companyid', $VarCompanyId);
        if ($VarArtType <> '') {
            $this->db->where('arttype', $VarArtType);
        } else {
            $this->db->where_in('arttype', array(1, 2));
        }
        $VarQry = $this->db->get(ORDER_ENTRY_BOM_SOURCING_TBL);
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }*/

    /*public function getBomSamplingAndApprovalDetailsBothArticle($VarEnquiryId, $VarCompanyId, $VarArtType = '') {
        $this->db->select('jsondatagrid');
        $this->db->where('referenceid', $VarEnquiryId);
        $this->db->where('companyid', $VarCompanyId);
        if ($VarArtType <> '') {
            $this->db->where('arttype', $VarArtType);
        } else {
            $this->db->where_in('arttype', array(1, 2));
        }
        $VarQry = $this->db->get(ORDER_ENTRY_BOM_SAMPLING_APPROVAL_DETAILS);
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }*/

    public function saveBomSamplingAndApprovalDetailsBothArticle($jsonData, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId, $VarArtType) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDER_ENTRY_BOM_SAMPLING_APPROVAL_DETAILS)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDER_ENTRY_BOM_SAMPLING_APPROVAL_DETAILS, array('jsondatagrid' => $jsonData, 'referenceid' => $VarReferenceId, 'dateupdated' => $VarDatetime,
                'updatedby' => $VarUserId), array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId, 'arttype' => $VarArtType));
        } else {
            $this->db->insert(ORDER_ENTRY_BOM_SAMPLING_APPROVAL_DETAILS, array('jsondatagrid' => $jsonData, 'referenceid' => $VarReferenceId, 'dateupdated' => $VarDatetime,
                'updatedby' => $VarUserId, 'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId, 'arttype' => $VarArtType));
        }
        return $VarReferenceId;
        /*$this->db->update(ORDER_ENTRY_BOM_SAMPLING_APPROVAL_DETAILS, array('jsondatagrid' => $jsonData, 'referenceid' => $VarReferenceId, 'dateupdated' => $VarDatetime,
            'updatedby' => $VarUserId), array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId, 'arttype' => $VarArtType));
        return true;*/
    }

    public function saveComGarmentProcess($jsonData, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId,$VarRemarks) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_COMGARMENTPROCESSFLOW_TBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_COMGARMENTPROCESSFLOW_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime,
                'updatedby' => $VarUserId,'remarks'=>$VarRemarks),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDERENTRY_COMGARMENTPROCESSFLOW_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'remarks'=>$VarRemarks));
        }
        return $VarReferenceId;
    }

    public function getFromComGarmentProcessFourteen($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid,remarks');
        $VarQry = $this->db->get_where(ORDERENTRY_COMGARMENTPROCESSFLOW_TBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $VarQry->row();
    }

    public function savegarmentsamplingfifteen($jsonData, $VarReferenceId, $VarCompanyId, $VarDatetime, $VarUserId,$VarRemarks) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->
        from(ORDERENTRY_GARMENTSAMPLINGFIFTEEN_TBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_GARMENTSAMPLINGFIFTEEN_TBL, array('jsondatagrid' => $jsonData,'remarks'=>$VarRemarks),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDERENTRY_GARMENTSAMPLINGFIFTEEN_TBL, array('jsondatagrid' => $jsonData,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'remarks'=>$VarRemarks));
        }
        return $VarReferenceId;
    }

    public function getFromGarmentSamplingFifteeen($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid,remarks');
        $VarQry = $this->db->get_where(ORDERENTRY_GARMENTSAMPLINGFIFTEEN_TBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }

    public function saveLabtestingSixteen($jsonData, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDERENTRY_LABTESTINGSIXTEENTH_TBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_LABTESTINGSIXTEENTH_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDERENTRY_LABTESTINGSIXTEENTH_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        }
        return $VarReferenceId;
        /*$this->db->update(ORDERENTRY_LABTESTINGSIXTEENTH_TBL, array('jsondatagrid' => $jsonData,'dateupdated'=>$VarDatetime,'updatedby'=>$VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        return true;*/
    }

    public function getFromLabTestingSixteen($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid');
        $VarQry = $this->db->get_where(ORDERENTRY_LABTESTINGSIXTEENTH_TBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }

    public function saveExtLabTesting($jsonData, $VarReferenceId = '', $VarCompanyId, $VarDatetime, $VarUserId) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDER_ENTRY_EXT_LAB_TESTING)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDER_ENTRY_EXT_LAB_TESTING, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDER_ENTRY_EXT_LAB_TESTING, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        }
        return $VarReferenceId;
        /*$this->db->update(ORDER_ENTRY_EXT_LAB_TESTING, array('jsondatagrid' => $jsonData,'dateupdated'=>$VarDatetime,'updatedby'=>$VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        return true;*/
    }

    public function getExtLabTesting($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid');
        $VarQry = $this->db->get_where(ORDERENTRY_LABTESTINGSIXTEENTH_TBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }

    public function saveMasterBagCartonAssortmentRatio($jsonMainData, $jsonExtraData, $VarReferenceId,$jsonChooseTypeJxl) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $this->companyid])->
        from(ORDER_ENTRY_MASTER_BAG_CARTON_ASSORT_RATIO)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDER_ENTRY_MASTER_BAG_CARTON_ASSORT_RATIO, array('jsondatagrid' => $jsonMainData, 'jsondatagridextra' => $jsonExtraData,
                'dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid,'tableids' => $jsonChooseTypeJxl),
                array('referenceid' => $VarReferenceId, 'companyid' => $this->companyid));
        } else {
            $this->db->insert(ORDER_ENTRY_MASTER_BAG_CARTON_ASSORT_RATIO, array('jsondatagrid' => $jsonMainData, 'jsondatagridextra' => $jsonExtraData,
                'tableids' => $jsonChooseTypeJxl,'dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid,
                'referenceid' => $VarReferenceId, 'companyid' => $this->companyid));
        }
        return $VarReferenceId;
    }

    public function getMasterBagCartonAssortmentRatio($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid,jsondatagridextra');
        $VarQry = $this->db->get_where(ORDER_ENTRY_MASTER_BAG_CARTON_ASSORT_RATIO, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $VarQry->row();
    }

    public function saveLotInspection($jsonData, $VarReferenceId , $VarCompanyId, $VarDatetime, $VarUserId,$VarRemarks) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->
        from(ORDERENTRY_LOTINSPECTIONTWENTYONE_TBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_LOTINSPECTIONTWENTYONE_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime,
                'updatedby' => $VarUserId,'remarks'=>$VarRemarks),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDERENTRY_LOTINSPECTIONTWENTYONE_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'remarks'=>$VarRemarks));
        }
        return $VarReferenceId;
    }

    public function getLotInspection($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid,remarks');
        $VarQry = $this->db->get_where(ORDERENTRY_LOTINSPECTIONTWENTYONE_TBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $VarQry->row();
    }

    public function saveDocandLogisticsTwentytwo($jsonData, $VarReferenceId , $VarCompanyId, $VarDatetime, $VarUserId,$VarRemarks) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])
            ->from(ORDERENTRY_DOCANDLOGISTICSTWENTYTWO_TBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDERENTRY_DOCANDLOGISTICSTWENTYTWO_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime,
                'updatedby' => $VarUserId,'remarks'=>$VarRemarks),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDERENTRY_DOCANDLOGISTICSTWENTYTWO_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'remarks'=>$VarRemarks));
        }
        return $VarReferenceId;
    }

    public function getFromDocandLogisticsTwentytwo($VarEnquiryId, $VarCompanyId) {
        //$this->db->select('ponumber,combocolor,poquantity,pcsorset,consshipexporter,forwardingagent,clearingagent,importer,consignee');
        $this->db->select('jsondatagrid,remarks');
        $VarQry = $this->db->get_where(ORDERENTRY_DOCANDLOGISTICSTWENTYTWO_TBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $VarQry->row();
    }

    public function setOrderEntryEditAccess($VarId, $VarCompanyId, $VarToggleOnOff) {
        $this->db->update(KN_ORDER_ENQUIRY, array('editaccess' => $VarToggleOnOff), array('id' => $VarId, 'companyid' => $VarCompanyId));
        return $VarId;
    }

    public function setOrderEntryCompleteStatus($VarId, $VarCompanyId) {
        $this->db->update(KN_ORDER_ENQUIRY, array('completestatus' => 1), array('id' => $VarId, 'companyid' => $VarCompanyId));
        return $VarId;
    }

    public function savePackingDetails($jsonData, $VarReferenceId, $VarCompanyId, $VarDatetime, $VarUserId,$VarRemarks) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId])->from(ORDER_ENTRY_PACKING_DETAILS_TBL)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(ORDER_ENTRY_PACKING_DETAILS_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'remarks'=>$VarRemarks),array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        } else {
            $this->db->insert(ORDER_ENTRY_PACKING_DETAILS_TBL, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'remarks'=>$VarRemarks));
        }
        return $VarReferenceId;
        /*$this->db->update(ORDER_ENTRY_PACKING_DETAILS_TBL, array('jsondatagrid' => $jsonData,'dateupdated'=>$VarDatetime,'updatedby'=>$VarUserId),
            array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId));
        return true;*/
    }

    public function getPackingDetails($VarEnquiryId, $VarCompanyId) {
        $this->db->select('jsondatagrid,remarks');
        $VarQry = $this->db->get_where(ORDER_ENTRY_PACKING_DETAILS_TBL, array('referenceid' => $VarEnquiryId, 'companyid' => $VarCompanyId));
        return $VarQry->row();
    }

    public function saveMBagCAR($ArrPoNoTabType, $VarEnquiryId) {
        foreach($ArrPoNoTabType as $datas) {
            if($datas[2] == 0) {
                $this->db->insert(CARTON_AND_BAGS, array('pono' => $datas[0], 'table_type_id' => $datas[1],
                    'referenceid' => $VarEnquiryId, 'companyid' => $this->companyid));
            }
            else {
                $this->db->update(CARTON_AND_BAGS, array('pono' => $datas[0], 'table_type_id' => $datas[1]),
                    array('id' => $datas[2]));
            }
        }
    }

    public function saveBagAndCartons($jsonData, $jsonExtraData, $VarExtraTblCount,$VarPrimaryId) {
        $this->db->update(CARTON_AND_BAGS, array('jsondatagrid' => $jsonData,'jsondatagridextra'=>$jsonExtraData,'extra_tables'=>$VarExtraTblCount),
            array('id' => $VarPrimaryId));
        return true;
    }

    public function getCartonBags($VarPrimaryId) {
        $ArrWhere = array('id' => $VarPrimaryId);
        $Res = $this->db->where($ArrWhere)->from(CARTON_AND_BAGS)->get()->result_array();
        if($Res) {
            return $Res;
        }
    }

}