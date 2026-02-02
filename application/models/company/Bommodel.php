<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bommodel extends CI_Model {
    public function saveBomArticle($jsonData, $VarReferenceId, $VarCompanyId, $VarDatetime, $VarUserId, $VarArticleId,$VarRemarks) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'articleid'=>$VarArticleId])->
        from(KN_BOM)->count_all_results();
        if (!empty($VarCountAllRes)) {
            $this->db->update(KN_BOM, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,'remarks'=>$VarRemarks),
                array('companyid' => $VarCompanyId, 'referenceid' => $VarReferenceId, 'articleid' => $VarArticleId));

        } else {
            $this->db->insert(KN_BOM, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'companyid' => $VarCompanyId, 'referenceid' => $VarReferenceId, 'articleid' => $VarArticleId,'remarks'=>$VarRemarks));

        }
        return $VarReferenceId;
    }
    public function getBomArticle($VarEnquiryId, $VarCompanyId, $VarArticleId = '') {
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
        $VarSql = "SELECT jsondatagrid,remarks FROM " . KN_BOM . " $VarWhere ";
        return $this->db->query($VarSql)->result_array();
    }

    public function getConsolidated($VarEnquiryId, $VarCompanyId, $VarArticleId = '') {
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
        $VarSql = "SELECT jsondatagrid,remarks FROM " . KN_BOM_CONSOLIDATED . " $VarWhere ";
        return $this->db->query($VarSql)->result_array();
    }
    public function saveBomConsolidated($jsonData, $VarReferenceId, $VarCompanyId,$VarDatetime , $VarUserId , $VarArticleId,$VarRemarks) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'articleid'=>$VarArticleId])
            ->from(KN_BOM_CONSOLIDATED)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(KN_BOM_CONSOLIDATED, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,'remarks'=>$VarRemarks),
                array('companyid' => $VarCompanyId, 'referenceid' => $VarReferenceId, 'articleid' => $VarArticleId));
        } else {
            $this->db->insert(KN_BOM_CONSOLIDATED, array('jsondatagrid' => $jsonData, 'dateupdated' => $VarDatetime, 'updatedby' => $VarUserId,
                'companyid' => $VarCompanyId, 'referenceid' => $VarReferenceId, 'articleid' => $VarArticleId,'remarks'=>$VarRemarks));
        }
        return $VarReferenceId;
    }

    public function saveBomSourcingDetails($jsonData, $VarReferenceId, $VarCompanyId, $VarDatetime, $VarUserId, $VarArtType,$VarRemarks) {
        $this->db->from(KN_BOM_SOURCING);
        $this->db->where('referenceid', $VarReferenceId);
        $this->db->where('companyid', $VarCompanyId);
        $this->db->where('articleid', $VarArtType);
        $checkExists = $this->db->count_all_results();
        if ($checkExists == 1) {
            $this->db->update(KN_BOM_SOURCING, array('jsondatagrid' => $jsonData, 'referenceid' => $VarReferenceId, 'dateupdated' => $VarDatetime,
                'updatedby' => $VarUserId,'remarks'=>$VarRemarks),
                array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId, 'articleid' => $VarArtType));
        } else {
            $this->db->insert(KN_BOM_SOURCING, array('jsondatagrid' => $jsonData, 'referenceid' => $VarReferenceId, 'dateupdated' => $VarDatetime,
                'updatedby' => $VarUserId, 'companyid' => $VarCompanyId, 'articleid' => $VarArtType,'remarks'=>$VarRemarks));
        }
        return $VarReferenceId;
    }
    public function getBomSourcingDetails($VarEnquiryId, $VarCompanyId = '', $VarArtType = '') {
        $this->db->select('jsondatagrid,remarks');
        $this->db->where('referenceid', $VarEnquiryId);
        $this->db->where('companyid', $VarCompanyId);
        if ($VarArtType <> '') {
            $this->db->where('articleid', $VarArtType);
        } else {
            $this->db->where_in('articleid', array(1, 2));
        }
        $VarQry = $this->db->get(KN_BOM_SOURCING);
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }
    public function getSamplingAndApprovalDetails($VarEnquiryId, $VarCompanyId, $VarArtType = '') {
        $this->db->select('jsondatagrid,remarks');
        $this->db->where('referenceid', $VarEnquiryId);
        $this->db->where('companyid', $VarCompanyId);
        if ($VarArtType <> '') {
            $this->db->where('articleid', $VarArtType);
        } else {
            $this->db->where_in('articleid', array(1, 2));
        }
        $VarQry = $this->db->get(KN_BOM_SAMPLING_APPROVAL_DETAILS);
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }

    public function saveSamplingAndApprovalDetails($jsonData, $VarReferenceId, $VarCompanyId, $VarDatetime, $VarUserId, $VarArtType,$VarRemarks) {
        $VarCountAllRes = $this->db->where(['referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId,'articleid'=>$VarArtType])
            ->from(KN_BOM_SAMPLING_APPROVAL_DETAILS)->count_all_results();
        if ($VarCountAllRes >= 1) {
            $this->db->update(KN_BOM_SAMPLING_APPROVAL_DETAILS, array('jsondatagrid' => $jsonData, 'referenceid' => $VarReferenceId, 'dateupdated' => $VarDatetime,
                'updatedby' => $VarUserId,'remarks'=>$VarRemarks), array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId, 'articleid' => $VarArtType));
        } else {
            $this->db->insert(KN_BOM_SAMPLING_APPROVAL_DETAILS, array('jsondatagrid' => $jsonData, 'referenceid' => $VarReferenceId, 'dateupdated' => $VarDatetime,
                'updatedby' => $VarUserId, 'companyid' => $VarCompanyId, 'articleid' => $VarArtType,'remarks'=>$VarRemarks));
        }
        return $VarReferenceId;
        /*$this->db->update(ORDER_ENTRY_BOM_SAMPLING_APPROVAL_DETAILS, array('jsondatagrid' => $jsonData, 'referenceid' => $VarReferenceId, 'dateupdated' => $VarDatetime,
            'updatedby' => $VarUserId), array('referenceid' => $VarReferenceId, 'companyid' => $VarCompanyId, 'arttype' => $VarArtType));
        return true;*/
    }
}
