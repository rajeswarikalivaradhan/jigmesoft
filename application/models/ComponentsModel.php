<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property ComponentsModel $componentsModel Optional description
 * @property menquirymodel $menquirymodel Optional description
 */
class ComponentsModel extends CI_Model
{
    public $_companyId = 0;
    public $_userId = 0;

    function _loggedUserInfo()
    {
        fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->_companyId = $ArrUserLoggedInfo['companyid'];
        $this->_userId = $ArrUserLoggedInfo['id'];
    }

    //private $menquirymodel;
    function deleteComponentById($componentId)
    {   
        
        $fabric_enqid = $this->getEnquiryidCompo($componentId);  
        if(!empty($fabric_enqid)){
          $this->db->where('enquiry_id',$fabric_enqid['enquiry_id']);
          $this->db->update('tbl_fabric_cost_garment', ['fabric_processing_loss' => '0.00']);
        }
        $this->db->delete('tbl_components', array('id' => $componentId));
        $this->db->delete('tbl_actual_cost_garment', array('component_id' => $componentId));
        $this->db->delete('tbl_avg_dyeing_processing_cost', array('component_id' => $componentId));
        $this->db->delete('tbl_avg_embellishment_cost', array('component_id' => $componentId));
        $this->db->delete('tbl_bom_cost', array('component_id' => $componentId));
        $this->db->delete('tbl_cmt_cip_garment', array('component_id' => $componentId));
        $this->db->delete('tbl_color_combo', array('component_id' => $componentId));
        $this->db->delete('tbl_dyeing_cost_single_color', array('component_id' => $componentId));
        $this->db->delete('tbl_fabric_cost_garment', array('component_id' => $componentId));
        //$this->db->delete('tbl_garment_piece_weight', array('component_id' => $componentId));
        $this->db->delete('tbl_knitting_cost', array('component_id' => $componentId));
        $this->db->delete('tbl_other_expenses', array('component_id' => $componentId));
        $this->db->delete('tbl_yarn_cost', array('component_id' => $componentId));
       
        $garmentData = $this->getGarmentDataCompo($componentId); // getting garment data
        
        if (!empty($garmentData))
        {
            $this->db->delete('tbl_garment_piece_weight', array('component_id' => $componentId));
            foreach ($garmentData as $key => $value)
            {
                $this->db->delete('tbl_garment_piece_weight_mapping', array('garment_piece_weight_id' => $value['id']));
            }
            $this->db->delete('tbl_dyeing_cost', array('component_id' => $componentId));
        }
    }

    function deleteColorComboById($comboId)
    {
        $this->db->delete('tbl_color_combo', array('id' => $comboId));
        $this->db->delete('tbl_avg_dyeing_processing_cost', array('combo_id' => $comboId));
        $this->db->delete('tbl_dyeing_cost_single_color', array('combo_id' => $comboId));
    }

    function getComponentsByEnqId($enquiry_id)
    {
        $qry = $this->db->get_where('tbl_components', ['enquiry_id' => $enquiry_id]);
        $components = $qry->result_array();
        $qry = $this->db->get_where('tbl_color_combo', ['enquiry_id' => $enquiry_id]);
        $colorCombos = $qry->result_array();
        $combos = [];
        foreach ((array)$colorCombos as $colorCombo) {
            $combos[$colorCombo['component_id']][] = [
                'id' => intval($colorCombo['id']),
                'name' => $colorCombo['name']
            ];
        }
        $result = [];
        foreach ((array)$components as $component) {
            $result[] = [
                'id' => intval($component['id']),
                'comp_name' => $component['comp_name'],
                'draft_status' => $component['draft_status'],
                'dying_type' => intval($component['dying_type']),
                'colourCombos' => isset($combos[$component['id']]) ? $combos[$component['id']] : [],
            ];
        }
        return $result;
    }

    function getPcSize($enquiry_id)
    {
        $this->db->select('id, enquiry_id, size_type,size_ids,totalsize');
        $this->db->from('tbl_pc_size_chart');
        $this->db->where(['enquiry_id' => $enquiry_id]);
        return $this->db->get()->row_array();
    }

    function getSizeMasters($sizeType = 1)
    {
        $this->db->select('id, size_name');
        $this->db->from('tbl_size_master');
        $this->db->where(array('size_type' => $sizeType, 'status' => 1));
        return $this->db->get()->result_array();
    }

    /*
     * `name` VARCHAR(100) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`enquiry_id` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL COMMENT 'enquiry id',
	`component_id` INT(11) UNSIGNED NULL DEFAULT NULL,
	`created_by` SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
	`created_date` TIMESTAMP NULL DEFAULT current_timestamp(),
	`modified_by` SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
	`modified_date` DATETIME NULL DEFAULT NULL,
     * */
    function insertAndUpdateComponents($enquiry_id, $post_data)
    {
      
        if (!empty($post_data['components']) && !empty($enquiry_id)) {
            $now = date('Y-m-d H:i:s');
            $this->_loggedUserInfo();
           // var_dump(implode(',', $post_data['custom_size_values']));die;
            $sizeIds = $post_data['size_type'] == 1 ? (isset($post_data['std_size_ids'])? implode(",", $post_data['std_size_ids']):'') : (isset($post_data['custom_size_values'])?implode(',', $post_data['custom_size_values']):'');
            $updateaction=$post_data['updateaction'];
            $draft_status=$post_data['draft_status'];
            $dyeingchange=$post_data['dyeingchange'];
            //echo $updateaction;die;
           // var_dump($sizeIds);die;
            $pcsizeupdate=false;
            
                $pcSizeData = [
                'enquiry_id' => $enquiry_id,
                'size_type' => $post_data['size_type'],
                'totalsize' => $post_data['totalsize'],
                'size_ids' => $sizeIds,
                'modified_by' => $this->_userId,
                'modified_date' => $now
            ];
            
            
            if (!empty($post_data['pc_size_insert']) && $post_data['pc_size_insert']==1) {
                $pcsizeupdate=$this->db->update('tbl_pc_size_chart', $pcSizeData, ['enquiry_id' => $enquiry_id]);
                if($pcsizeupdate==true && $updateaction==2){
                    $this->deleteExistingParts($enquiry_id);
                }
            } else {
                if($draft_status==1 && $updateaction==2){
                    //$pcsizeupdate=$this->db->update('tbl_pc_size_chart', $pcSizeData, ['enquiry_id' => $enquiry_id]);
                    $this->db->delete('tbl_pc_size_chart', array('enquiry_id' => $enquiry_id));
                    $pcSizeData['created_by'] = $this->_userId;
                    $pcSizeData['created_date'] = $now;
                    $this->db->insert('tbl_pc_size_chart', $pcSizeData);
                }else{
                    $pcSizeData['created_by'] = $this->_userId;
                    $pcSizeData['created_date'] = $now;
                    $this->db->insert('tbl_pc_size_chart', $pcSizeData);
                }
                
            }

            foreach ($post_data['components'] as $component) {
                $componentData = [
                    'enquiry_id' => $enquiry_id,
                    'dying_type' => intval($component['dying_type']),
                    'comp_name' => trim($component['comp_name']),
                    'draft_status'=>$draft_status,
                    'modified_by' => $this->_userId,
                    'modified_date' => $now
                ];
                if (!empty($component['id'])) {
                    $this->db->update('tbl_components', $componentData, ['id' => $component['id']]);
                    if($draft_status==2 && $dyeingchange==1 && intval($component['dying_type'])==2){
                         $this->db->delete('tbl_avg_dyeing_processing_cost', array('enquiry_id' => $enquiry_id, 'component_id' => $component['id']));
                    }
                } else {
                    $componentData['created_by'] = $this->_userId;
                    $componentData['created_date'] = $now;
                    $this->db->insert('tbl_components', $componentData);
                    $component['id'] = $this->db->insert_id();
                }
                if (!empty($component['colourCombos'])) {
                    $this->_insertAndUpdateColorCombos($component, $enquiry_id, $now);
                }
            }
            
            $ArrResult['msg'] = 'insert & update';
           $ArrResult['draftstatus'] = $draft_status;
           // return 'insert & update';
        }else{
           $ArrResult['msg'] ='failed to do the operation';  
        }
        //return 'failed to do the operation ';
        
       
        return $ArrResult;
    }

    function _insertAndUpdateColorCombos($component, $enquiry_id, $now)
    {
        foreach ($component['colourCombos'] as $colourCombo) {
            if (!empty($colourCombo['id'])) {
                $this->db->update('tbl_color_combo', [
                    'name' => $colourCombo['name'],
                    'modified_by' => $this->_userId,
                    'modified_date' => $now
                ], ['id' => $colourCombo['id']]);
            } else {
                $this->db->insert('tbl_color_combo', [
                    'name' => $colourCombo['name'],
                    'enquiry_id' => $enquiry_id,
                    'component_id' => $component['id'],
                    'created_by' => $this->_userId,
                    'created_date' => $now,
                    'modified_by' => $this->_userId,
                    'modified_date' => $now,
                ]);
            }
        }
    }
    
    function deleteExistingParts($enqId)  /// delete existing garment parts based on respective id's here
    {
        $garmentData = $this->getGarmentData($enqId); // getting garment data
        
        if (!empty($garmentData))
        {
            $this->db->delete('tbl_garment_piece_weight', array('enquiry_id' => $enqId));
            foreach ($garmentData as $key => $value)
            {
                $this->db->delete('tbl_garment_piece_weight_mapping', array('garment_piece_weight_id' => $value['id']));
            }
            
        $this->db->delete('tbl_yarn_cost', array('enquiry_id' => $enqId));
        $this->db->delete('tbl_knitting_cost', array('enquiry_id' => $enqId));
        $this->db->delete('tbl_dyeing_cost', array('enquiry_id' => $enqId));
        $this->db->delete('tbl_avg_dyeing_processing_cost', array('enquiry_id' => $enqId)); 
           
        }
        
    }
    
    function getGarmentData($enqId) /// getting garment parts details here based on enquiryid
    {
        $query = "SELECT gpw.id,pa.gpdname,gpw.size_per_code,GROUP_CONCAT(m.VALUES ORDER BY m.id) AS 'values', gpw.parts_wise_avg FROM tbl_garment_piece_weight gpw JOIN kn_master_garment_part_desc pa 
                  ON gpw.garm_parts_id = pa.id
                  JOIN tbl_garment_piece_weight_mapping m 
                  ON m.garment_piece_weight_id = gpw.id
                  WHERE gpw.enquiry_id = " . $enqId . " GROUP BY gpw.id";
        $data  = $this->db->query($query)->result_array();
        return $data;
    }
    
     function getGarmentDataCompo($compoId) /// getting garment parts details here based on componentid
    {
        $query = "SELECT gpw.id,pa.gpdname,gpw.size_per_code,GROUP_CONCAT(m.VALUES ORDER BY m.id) AS 'values', gpw.parts_wise_avg FROM tbl_garment_piece_weight gpw JOIN kn_master_garment_part_desc pa 
                  ON gpw.garm_parts_id = pa.id
                  JOIN tbl_garment_piece_weight_mapping m 
                  ON m.garment_piece_weight_id = gpw.id
                  WHERE gpw.component_id = " . $compoId . " GROUP BY gpw.id";
        $data  = $this->db->query($query)->result_array();
        return $data;
    }
    
     function getEnquiryidCompo($compoId) /// getting garment parts details here based on componentid
    {
        $query = "SELECT f.enquiry_id FROM tbl_fabric_cost_garment f 
                  WHERE f.component_id = " . $compoId . "";
        $data  = $this->db->query($query)->row_array();
        return $data;
    }
    
    public function checkDraftorNot($enquiry_id)
    {
        $result = $this->db->from('tbl_components')->where('enquiry_id', $enquiry_id)->where('draft_status', 1)->get()->num_rows();
        return $result;
    }
    
    public function cleardraft($enquiry_id)
    {   
        if($this->db->delete('tbl_components',array('enquiry_id' => $enquiry_id))){
        $this->db->delete('tbl_color_combo', array('enquiry_id' => $enquiry_id));
        $this->db->delete('tbl_pc_size_chart', array('enquiry_id' => $enquiry_id));
        $ArrResult['success']					    = 1;
        }else{
        $ArrResult['success']					    = 0;
        }
        return $ArrResult;
    }
    function deletecomponentdetail($enqId)  /// delete existing component,colour/combo,garment parts based on respective id's here
    {
       if($this->db->delete('tbl_components',array('enquiry_id' => $enqId))){
        $this->db->delete('tbl_color_combo', array('enquiry_id' => $enqId));
        $this->db->delete('tbl_pc_size_chart', array('enquiry_id' => $enqId));
        $this->deleteExistingParts($enqId);
        }
    }
    

}
