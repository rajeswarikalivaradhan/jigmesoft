<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class PreCostingModels extends CI_Model
{

    function getSizeMasters($sizeType = 1){
        $this->db->select('id, size_name');
        $this->db->from('tbl_size_master');
        $this->db->where(array('size_type'=>$sizeType,'status'=>1));
        return $this->db->get() ->result_array();
    }
}
