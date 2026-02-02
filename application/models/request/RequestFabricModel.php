<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RequestFabricModel extends CI_Model
{
    private $mysqldatetime;
    public function __construct()
    {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo   = fnGetUserLoggedInfo('1');
        $this->companyid     = $ArrUserLoggedInfo['companyid'];
        $this->mysqldatetime = date('d/m/Y h:i A');
        $this->userid        = $ArrUserLoggedInfo['id'];
        $this->subscriberId = $ArrUserLoggedInfo['subscriber_id'];
    }
    
    function getSizeChart($enqId = '')
    {
        $this->db->select('size_ids');
        $ArrRes = $this->db->get_where('tbl_pc_size_chart', array('enquiry_id' => $enqId));
        return $ArrRes->row()->size_ids;
    }

    function getSizeMaster($size_ids = '')
    {
        $userInfoQry = "SELECT size_name FROM tbl_size_master sm WHERE sm.id IN (" . $size_ids . ")";
        $data        = $this->db->query($userInfoQry)->result_array();
        return $data;
    }

    function getSizeMasterDropdown($size_ids = '')
    {
        $userInfoQry = "SELECT id as id, size_name as name FROM tbl_size_master sm WHERE sm.id IN (" . $size_ids . ")";
        $data = $this->db->query($userInfoQry)->result_array();
        $allVar = [ 'id'=> '0', 'name' => 'All' ];
        $allVar1 = [ 'id'=> '00', 'name' => 'Running Size'];
        array_push($data, $allVar);
        array_push($data, $allVar1);
        return $data;
    }
    
    // ********** FABRIC REQUEST STARTS HERE *********** /

    
    public function getFabricProcessLossDetailss($id) {

        $color_wise_sql = "SELECT a.*, b.gpdname FROM tbl_fab_color_wise_garment_parts a 
                           INNER JOIN kn_master_garment_part_desc b ON a.gar_parts=b.id 
                           WHERE a.enquiry_id='$id' AND a.flag=1
                           ORDER BY a.fab_color_wise_garment_part_id ASC";
        $color_wise_data = $this->db->query($color_wise_sql)->result_array();

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);

        // ******* GET THE DATA DETAILS ENDS ********* //

        $finalValue = [];
            // *** final data *** //
        for($i=0; $i < sizeof($color_wise_data); $i++) {
            $component = $color_wise_data[$i]['component'];
            $spec_code = $color_wise_data[$i]['spec_code'];
            $ex_qty = $color_wise_data[$i]['ex_qty'];

            $fab_color_wise_garment_part_id = $color_wise_data[$i]['fab_color_wise_garment_part_id'];
            $getData = "select * from tbl_fab_garment_piece_weight where flag=1";
            $getData_sql = $this->db->query($getData)->result_array();
            
            $piece_sizes = [];
            for ($j=0; $j < sizeof($getData_sql); $j++) { 
                $ids =  $getData_sql[$j]['fab_color_wise_garment_part_id'];
                $array_ids = explode(',', $ids);
                for ($k=0; $k < sizeof($array_ids); $k++) { 
                    if($fab_color_wise_garment_part_id == $array_ids[$k])
                    {
                        $piece_sizes = $getData_sql[$j]['piece_sizes'];
                    }
                }
            }

            $combineValue = ['edit', $color_wise_data[$i]['fab_color_wise_garment_part_id'], $color_wise_data[$i]['po_enq'], 
            $color_wise_data[$i]['combo'], $component, $color_wise_data[$i]['colour'], 
            $color_wise_data[$i]['gpdname'], $spec_code ];
            
            $sizes = $color_wise_data[$i]['sizes'];
            // $piece_sizes = $color_wise_data[$i]['piece_sizes'];

            $array_sizes = explode(',', $sizes);
            $array_piece_sizes = explode(',', $piece_sizes);
            
            $consumption_calc_sizes = [];
            for($j=0;$j<sizeof($array_sizes);$j++)
            {
                $dis_qty_size = $array_sizes[$j] * $ex_qty / 100;
                $ex_qty_size = $array_sizes[$j] + $dis_qty_size;
                $calc_sizes = (float)$array_piece_sizes[$j] * (float)$ex_qty_size;
                $calc_sizes = number_format((float)$calc_sizes, 3, '.', '');
                $process_loss = $color_wise_data[$i]['process_loss'];

                $calc_sizes = (float)$calc_sizes * (int)$process_loss / 100 + (float)$calc_sizes;
                
                $calc_sizes = number_format((float)$calc_sizes, 3, '.', '');

                array_push($consumption_calc_sizes, $calc_sizes);
            }

            $combineValue = array_merge($combineValue, $consumption_calc_sizes);
            $sum_of_sizes = array_sum($consumption_calc_sizes);
            $f_sum_sizes = array(number_format((float)$sum_of_sizes, 3, '.', ''));
            $combineValue = array_merge($combineValue, $f_sum_sizes);
            array_push($finalValue, $combineValue);
        }                

        
        
        // ******* GET THE DATA DETAILS START ********* //
        
        return $finalValue;
    }
    
    public function getFabricSizeSpecCodeDetailss($id) {
                        
        $consolidated_sql = "SELECT a.*, b.gpdname FROM tbl_fab_color_wise_garment_parts a 
                            INNER JOIN kn_master_garment_part_desc b ON a.gar_parts=b.id 
                            WHERE a.enquiry_id='$id' AND a.flag=1 
                            group by a.combo, a.component, a.colour, a.gar_parts, a.spec_code  
                            ORDER BY a.fab_color_wise_garment_part_id ASC";
        $consolidated_data = $this->db->query($consolidated_sql)->result_array();

        $color_wise_sql = $this->getFabricProcessLossDetailss($id);

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);
       
        // ******* GET DATA DETAILS STARTS ********* //

        $finalValue = [];
        $referenceArr = [];
        for($i=0; $i < sizeof($consolidated_data); $i++) 
        {
            $combineValue = ["", "", $consolidated_data[$i]['po_enq'], $consolidated_data[$i]['combo'], $consolidated_data[$i]['component'],
                            $consolidated_data[$i]['colour'], $consolidated_data[$i]['gpdname'], $consolidated_data[$i]['spec_code']];

            $sizeArr = [];
            foreach ($sizeMaster as $key => $value) {
                array_push($sizeArr, 0);             
            }
            $combineValue = array_merge($combineValue, $sizeArr);
            $combineValue = array_merge($combineValue, [0]);
            array_push($referenceArr, $combineValue);             
        }  

        for($i=0; $i < sizeof($referenceArr); $i++) 
        {
            $a = $referenceArr[$i][3];
            $b = $referenceArr[$i][4];
            $c = $referenceArr[$i][5];
            $d = $referenceArr[$i][6];
            $e = $referenceArr[$i][7];

            for($j=0; $j < sizeof($color_wise_sql); $j++) 
            {
                $aa = $color_wise_sql[$j][3];
                $bb = $color_wise_sql[$j][4];
                $cc = $color_wise_sql[$j][5];
                $dd = $color_wise_sql[$j][6];
                $ee = $color_wise_sql[$j][7];

                if($a == $aa && $b == $bb && $c == $cc && $d == $dd && $e == $ee) {
                    
                    for($k=0; $k < sizeof($color_wise_sql[$j]); $k++) 
                    {
                        if($k >= 8){
                            $referenceArr[$i][$k] = $referenceArr[$i][$k] + $color_wise_sql[$j][$k];
                            $referenceArr[$i][$k] = number_format((float)$referenceArr[$i][$k], 3, '.', '');
                        }
                    }
                }
            }
            array_push($finalValue, $referenceArr[$i]);
        }
   
        // ******* GET THE DATA DETAILS ENDS ********* //

        return $finalValue;
    }
    public function get_sizewise_dia_dimensionn($id) {

        $sql = "SELECT a.fab_color_wise_garment_part_id, a.component, a.spec_code, a.enquiry_id, b.gpdname as gar_parts,
                group_concat(fab_color_wise_garment_part_id) as selected_ids
                FROM tbl_fab_color_wise_garment_parts a
                INNER JOIN kn_master_garment_part_desc b ON a.gar_parts=b.id
                WHERE a.enquiry_id='$id' AND a.flag=1
                group by component, gar_parts, spec_code";
        $data = $this->db->query($sql)->result_array();

        $consl_sql = "SELECT * FROM tbl_fab_final_dia_dim WHERE enquiry_id='$id' AND flag=1";
        $consl_data = $this->db->query($consl_sql)->result_array();

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);


        // ******* GET THE DATA DETAILS START ********* //
        $finalValue = [];
        if(sizeof($consl_data) == 0) {

            foreach($data as $key => $value) {
                $resultValue = ['', '', $value['selected_ids'], $value["component"], $value["gar_parts"], $value["spec_code"], "", ""];
                $emp = [];
                foreach ($sizeMaster as $key => $value)
                {
                    array_push($emp, "");
                }
                $finalData = [""];
                $resultValue = array_merge($resultValue, $emp);
                $resultValue = array_merge($resultValue, $finalData);
                array_push($finalValue, $resultValue);
            }
        }
        else {
            if(sizeof($data) == sizeof($consl_data)) {
                for($i = 0; $i < sizeof($data); $i++) 
                {
                    $selected_ids = $data[$i]["selected_ids"];
                    $color_wise_garment_part_id = $consl_data[$i]["fab_color_wise_garment_part_id"];
                    $description = $consl_data[$i]["description"];
                    $uom = $consl_data[$i]["uom"];
                    
                    $sizess = $consl_data[$i]['piece_sizes'];
                    $array_sizes = explode(',', $sizess);
                    $piece_sizes = [];
                    for($j=0; $j<sizeof($array_sizes); $j++) {
                        array_push($piece_sizes, $array_sizes[$j]);
                    }
                    
                    $resultValue = ['edit', $consl_data[$i]['fab_final_dia_dim_id'], $selected_ids, $data[$i]["component"], $data[$i]["gar_parts"], $data[$i]["spec_code"], $description, $uom];
                    $resultValue = array_merge($resultValue, $piece_sizes);
                    $finalData = [$consl_data[$i]['avg_weight_piece']];
                    $resultValue = array_merge($resultValue, $finalData);
                    array_push($finalValue, $resultValue);

                }
            }
            else if(sizeof($data) > sizeof($consl_data)) {
                for($i = 0; $i < sizeof($data); $i++) 
                {
                    // *** DATA *** //
                    if($i+1 > sizeof($consl_data)) 
                    {
                        // *** ADDING NEW DATA IN AN ARRAY *** //
                        $resultValue = ['', '', $data[$i]['selected_ids'], $data[$i]["component"], $data[$i]["gar_parts"], $data[$i]["spec_code"], "", ""];
                        $emp = [];
                        foreach ($sizeMaster as $key => $value)
                        {
                            array_push($emp, "");
                        }
                        $finalData = [""];
                        $resultValue = array_merge($resultValue, $emp);
                        $resultValue = array_merge($resultValue, $finalData);
                        array_push($finalValue, $resultValue);
                    }
                    else {
                        // *** ADDING EXISTING DATA IN AN ARRYA *** //
                        $selected_ids = $data[$i]["selected_ids"];
                        $color_wise_garment_part_id = $consl_data[$i]["fab_color_wise_garment_part_id"];
                        $description = $consl_data[$i]["description"];
                        $uom = $consl_data[$i]["uom"];
                        
                        $sizess = $consl_data[$i]['piece_sizes'];
                        $array_sizes = explode(',', $sizess);
                        $piece_sizes = [];
                        for($j=0; $j<sizeof($array_sizes); $j++)
                        {
                            array_push($piece_sizes, $array_sizes[$j]);
                        }
                        
                        $resultValue = ['edit', $consl_data[$i]['fab_final_dia_dim_id'], $selected_ids, $data[$i]["component"], $data[$i]["gar_parts"], $data[$i]["spec_code"], $description, $uom];
                        $resultValue = array_merge($resultValue, $piece_sizes);
                        $finalData = [$consl_data[$i]['avg_weight_piece']];
                        $resultValue = array_merge($resultValue, $finalData);
                        array_push($finalValue, $resultValue);
                    }
                }
            }
            else if(sizeof($consl_data) > sizeof($data)) {
                for($i = 0; $i < sizeof($consl_data); $i++) 
                {
                    // *** DATA *** //
                    if($i+1 > sizeof($data)) 
                    {
                        
                    }
                    else {
                        // *** ADDING EXISTING DATA IN AN ARRYA *** //
                        $selected_ids = $data[$i]["selected_ids"];
                        $color_wise_garment_part_id = $consl_data[$i]["fab_color_wise_garment_part_id"];
                        $description = $consl_data[$i]["description"];
                        $uom = $consl_data[$i]["uom"];
                        
                        $sizess = $consl_data[$i]['piece_sizes'];
                        $array_sizes = explode(',', $sizess);
                        $piece_sizes = [];
                        for($j=0; $j<sizeof($array_sizes); $j++)
                        {
                            array_push($piece_sizes, $array_sizes[$j]);
                        }
                        
                        $resultValue = ['edit', $consl_data[$i]['fab_final_dia_dim_id'], $selected_ids, $data[$i]["component"], $data[$i]["gar_parts"], $data[$i]["spec_code"], $description, $uom];
                        $resultValue = array_merge($resultValue, $piece_sizes);
                        $finalData = [$consl_data[$i]['avg_weight_piece']];
                        $resultValue = array_merge($resultValue, $finalData);
                        array_push($finalValue, $resultValue);
                    }
                }
            }
        }
        // ******* GET THE DATA DETAILS ENDS ********* //

        return $finalValue;
    }


   
    public function getItemizedFabricRequirementDetailss($id) {

        $consolidated_sql = "SELECT a.*, b.gpdname FROM tbl_fab_color_wise_garment_parts a 
                            INNER JOIN kn_master_garment_part_desc b ON a.gar_parts=b.id 
                            WHERE a.enquiry_id='$id' AND a.flag=1 
                            group by a.combo, a.component, a.colour, a.gar_parts  
                            ORDER BY a.fab_color_wise_garment_part_id ASC";
        $consolidated_data = $this->db->query($consolidated_sql)->result_array();

        $itemized_fabric_sql = "SELECT * FROM tbl_fab_itemized_fabric_requirement
                            WHERE enquiry_id='$id' AND flag=1 
                            ORDER BY itemized_fabric_requirement_id ASC";
        $itemized_fabric_data = $this->db->query($itemized_fabric_sql)->result_array();

        $color_wise_data = $this->getFabricSizeSpecCodeDetailss($id);

        $size_wise_dia_data = $this->get_sizewise_dia_dimensionn($id);

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);

        // ******* GET DATA DETAILS START ********* //

        $finalValue = [];
        $referenceArr = [];

        
        for($i=0; $i < sizeof($consolidated_data); $i++) 
        {
            $combo = $consolidated_data[$i]['combo'];
            $component = $consolidated_data[$i]['component'];
            $gpdname = $consolidated_data[$i]['gpdname'];
            $colour = $consolidated_data[$i]['colour'];
            $fil_sizes = $fil_prices = $mer_sizes = $uom = [];
            for ($j=0; $j < sizeof($size_wise_dia_data); $j++) {
                $component2 = $size_wise_dia_data[$j][3];
                $gpdname2 = $size_wise_dia_data[$j][4];
                array_push($uom, $size_wise_dia_data[$j][7]);
                if($component === $component2 && $gpdname === $gpdname2)
                {
                    $sizes = [];
                    for ($k=8; $k < sizeof($size_wise_dia_data[$j]) - 1; $k++) {
                        array_push($sizes, $size_wise_dia_data[$j][$k]);
                        array_push($mer_sizes, $size_wise_dia_data[$j][$k]);
                    }
                    array_push($fil_sizes, $sizes);
                }
            }

            // return $color_wise_data;

            for ($j=0; $j < sizeof($color_wise_data); $j++) { 
                $combo2 = $color_wise_data[$j][3];
                $component2 = $color_wise_data[$j][4];
                $colour2 = $color_wise_data[$j][5];
                $gpdname2 = $color_wise_data[$j][6];
                if($component === $component2 && $gpdname === $gpdname2 && $combo === $combo2 && $colour === $colour2)
                {
                    $prices = [];
                    for ($k=8; $k < sizeof($color_wise_data[$j]) - 1; $k++) {
                        array_push($prices, $color_wise_data[$j][$k]);
                    }
                    array_push($fil_prices, $prices);
                }
            }

            $dis_sizes = array_values(array_unique($mer_sizes));
            
            $fin_prices = [];
            for ($j=0; $j < sizeof($dis_sizes); $j++) { 
                $dis_size = $dis_sizes[$j];
                $totalPrice = 0;
                for ($k=0; $k < sizeof($fil_sizes); $k++) { 
                    $asas = $fil_sizes[$k];
                    for ($l=0; $l < sizeof($asas); $l++) { 
                        $fil_size = $fil_sizes[$k][$l];
                        if($dis_size == $fil_size) {
                            $totalPrice += (float)$fil_prices[$k][$l];
                            $totalPrice = number_format((float)$totalPrice, 3, '.', '');
                        }
                    }
                }
                array_push($fin_prices, $totalPrice);
            }

            // print_r($fin_prices);

            $totalSizePrice = number_format((float)array_sum($fin_prices), 3, '.', '');

            for ($j=0; $j < sizeof($dis_sizes); $j++) { 
                // if($j == 0)
                // {
                    if(!isset($itemized_fabric_data[$i]))
                    // if(isset($itemized_fabric_data[$i]) === 0)
                    {
                        $combineValue = [ '', '', $consolidated_data[$i]['combo'], $consolidated_data[$i]['component'],
                                        $consolidated_data[$i]['colour'], $consolidated_data[$i]['gpdname'], '', '', '', '', '', '', '', '',
                                        $dis_sizes[$j], $uom[$j], $fin_prices[$j], '', '' ];
                    }
                    else
                    {
                        $combineValue = [ 'edit', $itemized_fabric_data[$i]['itemized_fabric_requirement_id'], $consolidated_data[$i]['combo'], 
                                        $consolidated_data[$i]['component'], $consolidated_data[$i]['colour'], $consolidated_data[$i]['gpdname'], 
                                        $itemized_fabric_data[$i]['yarn_blend'], $itemized_fabric_data[$i]['yarn_content'], $itemized_fabric_data[$i]['yarn_count'],
                                        $itemized_fabric_data[$i]['fabric_name'], $itemized_fabric_data[$i]['finishing_gsm'], $itemized_fabric_data[$i]['no_of_feed_pi'],
                                        $itemized_fabric_data[$i]['lycra'], $itemized_fabric_data[$i]['dyeing_type'], $dis_sizes[$j], $uom[$j], 
                                        $fin_prices[$j], '', $totalSizePrice ];
                    }
                // }
                // else if($j > 0)
                // {
                //     $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', $dis_sizes[$j], $uom[$j], $fin_prices[$j], '', '' ];
                // }
                if($combineValue != '')
                {
                    // print_r('');
                    array_push($finalValue, $combineValue);
                }
            }

        }

        
        // ******* GET DATA DETAILS ENDS ********* //

        return $finalValue;
    }

    public function getFabricRequestDetailss($id) {
        
        $itemized_data = $this->getItemizedFabricRequirementDetailss($id);

        $itemized_fabric_data = [];

        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $dyeing = $itemized_data[$i][13];
            array_push($itemized_fabric_data, $itemized_data[$i]);
        }

        $array = $finalValue = [];

        foreach ($itemized_fabric_data as $index => $value)
        {
            $combo = $value[2];
            $component = $value[3];
            $colour = $value[4];
            $gpdname = $value[5];
            $blend = $value[6];
            $content = $value[7];
            $feed = $value[11];
            $lycra = $value[12];

            if($combo == '' && $component == '' && $colour == '' && $gpdname == '') { }
            else {
                $split_blend = $split_content = $split_feed = [];

                $split = explode(";", $blend);
                $split = array_filter($split);
                foreach ($split as $key => $value) {
                    array_push($split_blend, $value);
                }

                $split = explode(";", $content);
                $split = array_filter($split);
                foreach ($split as $key => $value) {
                    array_push($split_content, $value);
                }

                $split = explode("/", $feed);
                $split = array_filter($split);
                foreach ($split as $key => $value) {
                    array_push($split_feed, $value);
                }

                $total_feed_val = array_sum($split_feed);

                $fabric_blend = $arr_fabric_blend = $split_blend_data = $blend_lycra = $arr_blend_lycra = $arr_fabric_content = $fin_blend_lycra = $arr_itemized_content = [];
                
                for($i=0; $i < sizeof($split_blend); $i++) 
                {
                    $sql2 = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$split_blend[$i]."' ";
                    $feed_data = $this->db->query($sql2)->result_array();
                    $blend_data = $feed_data[0]['misc_name'];

                    $sql2 = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$split_content[$i]."' ";
                    $feed_data = $this->db->query($sql2)->result_array();
                    $content_data = $feed_data[0]['misc_name'];

                    $split_blend_data = $this->multiexplode(array("+",'/',":"), $blend_data);
                    $fin_split_content = $this->multiexplode(array("+",'/',":"), $content_data);

                    $fabric_blend_val = (float)$split_feed[$i] * 100 / (float)$total_feed_val;
                    array_push($fabric_blend, round($fabric_blend_val));
                        
                    for ($j=0; $j < sizeof($split_blend_data); $j++) { 
                        array_push($arr_fabric_blend, $fabric_blend[$i]);
                        array_push($arr_itemized_content, trim($split_blend_data[$j]));
                        array_push($arr_fabric_content, trim($fin_split_content[$j]));
                    }
                    
                }
                
                $total_fabric_blend = array_sum($fabric_blend);
                
                // print_r($total_fabric_blend);

                for ($i=0; $i < sizeof($split_blend); $i++) { 
                    $blend_lycra_val = $fabric_blend[$i] * ($total_fabric_blend - $lycra) / 100;
                    array_push($blend_lycra, round($blend_lycra_val));
                }
                
                // print_r($arr_fabric_blend);
                
                for($i=0; $i < sizeof($split_blend); $i++) 
                {
                    $sql2 = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$split_blend[$i]."' ";
                    $feed_data = $this->db->query($sql2)->result_array();
                    $blend_data = $feed_data[0]['misc_name'];

                    $split_blend_data = $this->multiexplode(array("+",'/',":"), $blend_data);
                        
                    for ($j=0; $j < sizeof($split_blend_data); $j++) { 
                        array_push($arr_blend_lycra, $blend_lycra[$i]);
                    }
                    
                }
                
                // print_r($arr_blend_lycra);

                for ($i=0; $i < sizeof($arr_blend_lycra); $i++) { 
                    $fin_blend = (float)$arr_blend_lycra[$i] * (float)$arr_itemized_content[$i] / 100;
                    array_push($fin_blend_lycra, (float)$fin_blend);
                }

                $unique_content = array_values(array_unique($arr_fabric_content));

                $unique_fabric_blend = [];
                $final_fabric_content = '';
                $final_fabric_blend = '';
                for ($j=0; $j < sizeof($unique_content); $j++) {
                    $s_content = $unique_content[$j];
                    $totalPrice = 0;
                    for ($k=0; $k < sizeof($arr_fabric_content); $k++) {
                        $n_content = $arr_fabric_content[$k];
                        if($s_content == $n_content) {
                            $totalPrice += (int)$fin_blend_lycra[$k];
                        }
                    }
                    if($j == 0) { 
                        $final_fabric_content = $unique_content[$j];
                        $final_fabric_blend = $totalPrice;
                    }
                    else if($j > 0) { 
                        $final_fabric_content = $final_fabric_content.' : '.$unique_content[$j]; 
                        $final_fabric_blend = $final_fabric_blend.' : '.$totalPrice; 
                    }

                }

                if($lycra == '' || $lycra == '0' || $lycra == 0) { }
                else {
                    $final_fabric_content = $final_fabric_content.' : Lycra';
                    $final_fabric_blend = $final_fabric_blend.' : '.$lycra;
                }

                $itemized_fabric_data[$index][6] = $final_fabric_blend;
                $itemized_fabric_data[$index][7] = $final_fabric_content;
            }
        }

        $finalValue = [];

        for ($i=0; $i < sizeof($itemized_fabric_data); $i++) {
            $combineValue = '';
            if($itemized_fabric_data[$i][2] == '' && $itemized_fabric_data[$i][17] == '')
            {
                $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', $itemized_fabric_data[$i][14], $itemized_fabric_data[$i][15], $itemized_fabric_data[$i][16]
                    ];
            }
            else
            {
                $combineValue = [ 'edit', '', $itemized_fabric_data[$i][2], $itemized_fabric_data[$i][3], $itemized_fabric_data[$i][4], $itemized_fabric_data[$i][5],
                        $itemized_fabric_data[$i][6], $itemized_fabric_data[$i][7],
                        $itemized_fabric_data[$i][9], $itemized_fabric_data[$i][13], $itemized_fabric_data[$i][10], 
                        $itemized_fabric_data[$i][14], $itemized_fabric_data[$i][15], $itemized_fabric_data[$i][16]
                    ];
            }
            array_push($finalValue, $combineValue);
        }

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        $output['data'] = $finalValue;
        $output['fabric_name_data'] = $fabric_name_data;
        return $output;
    }

    // ********** FABRIC REQUEST ENDS HERE *********** /

    // ********** CREATE FABRIC REQUEST STARTS HERE *********** /

    public function createFabricRequestt($id, $req_type, $cutoff_date, $merchant_note, $purchase_req_type) {
        $requestValue['enquiry_id'] = $id;
        $requestValue['companyid'] = $this->companyid;
        $requestValue['type'] = 5;
        $requestValue['req_type'] = $req_type;
        $requestValue['req_date'] = $this->mysqldatetime;
        $requestValue['cutoff_date'] = $cutoff_date;
        $requestValue['merchant_note'] = $merchant_note;
        $requestValue['purchase_req_type'] = $purchase_req_type;
        $requestValue['log'] = LOGTIME;
        $this->db->insert('tbl_request', $requestValue);
    }

    public function multiexplode ($delimiters,$string) {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    // ********** CREATE FABRIC REQUEST ENDS HERE *********** /

    // ********** FABRIC REQUEST STARTS HERE *********** /

    function getRequestDetails($enqId, $reqId)
    {
        $sql = "SELECT * from tbl_request a
                where a.request_id='$reqId' and a.flag=1";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function getRequestData($enqId, $reqId)
    {
        $sql = "SELECT * from tbl_request a
                INNER JOIN ".KN_USERS." b ON a.auth_by=b.id
                where a.request_id='$reqId' and a.flag=1";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function getRequestDataa($enqId, $reqYarnId)
    {
        $sql = "SELECT * from tbl_request a
                INNER JOIN tbl_request_yarn b ON a.request_id = b.request_id
                INNER JOIN ".KN_USERS." c ON a.auth_by=c.id
                where b.request_yarn_id='$reqYarnId' and a.flag=1";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function getManagementFabricRequestDetailss($id) {
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE a.enquiry_id = " . $id . " AND a.flag=1 and a.req_sent_status = 1";
        $data = $this->db->query($sql)->result_array();

        $req_sql = "SELECT * FROM tbl_request as a WHERE a.enquiry_id = " . $id . " AND a.flag=1 AND type=2";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $result = [];
        $att_result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], "", $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $output['data'] = $result;
        $output['attachmentdata'] = $att_result;
        $output['req_data'] = $req_data;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }

    // ********** FABRIC REQUEST ENDS HERE *********** /
    
    // ********** CREATE FABRIC REQUEST STARTS HERE *********** /

    public function updateManagementFabricRequestt($id, $auth_status, $auth_type, $mgmt_remark) {
        $requestValue['auth_status'] = $auth_status;
        $requestValue['mgmt_approval'] = $auth_status;
        $requestValue['auth_by'] = $this->userid;
        $requestValue['auth_date'] = $this->mysqldatetime;
        $requestValue['auth_type'] = $auth_type;
        $requestValue['mgmt_remark'] = $mgmt_remark;
        $requestValue['log'] = LOGTIME;

        $this->db->where('request_id', $id);
        $this->db->update('tbl_request', $requestValue);
    }
    // ********** CREATE FABRIC REQUEST ENDS HERE *********** /

    // ********** FABRIC DEPARTMENT REQUEST STARTS HERE *********** /

    public function getDepartmentFabricRequestDetailss($id) {
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $id . " AND a.flag=1 and a.req_sent_status = 1 and c.mgmt_approval=1";
        $data = $this->db->query($sql)->result_array();

        $req_sql = "SELECT * FROM tbl_request as a WHERE a.request_id = " . $id . " AND a.flag=1 AND type=2 and a.mgmt_approval=1";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $result = [];
        $att_result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], "", $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], 
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];
        }
        
        $output['data'] = $result;
        $output['attachmentdata'] = $att_result;
        $output['req_data'] = $req_data;
        return $output;
    }

    // ********** FABRIC DEPARTMENT REQUEST ENDS HERE *********** /

    // ********** UPDATE FABRIC DEPARTMENT REQUEST STARTS HERE *********** /

    public function updateDepartmentFabricRequestt($id, $req_status) {
        $requestValue['req_status'] = $req_status;
        $requestValue['deprt_approval'] = $req_status;
        $requestValue['log'] = LOGTIME;

        $this->db->where('request_id', $id);
        $this->db->update('tbl_request', $requestValue);
    }
    // ********** UPDATE FABRIC DEPARTMENT REQUEST ENDS HERE *********** /

    // ********** FABRIC QA REQUEST STARTS HERE *********** /

    public function getQAFabricRequestDetailss($id) {
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $id . " AND a.flag=1 and a.req_sent_status = 1 and c.qa_approval=0";
        $data = $this->db->query($sql)->result_array();

        $req_sql = "SELECT * FROM tbl_request as a WHERE a.request_id = " . $id . " AND a.flag=1 AND type=2 and a.qa_approval=0";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $result = $att_result = $qa_status_result = $job_status_result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], "", $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

            $qa_status_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], '', $value['qa_req_date'], $value['qa_schd_date'], $value['qa_status'], $value['qa_status_update'] ];
                            
            $job_status_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sam_ref_no'], $value['job_schd_date'], $value['job_status'], $value['job_status_update'] ];
        }
        
        $output['data'] = $result;
        $output['attachmentdata'] = $att_result;
        $output['qastatusdata'] = $qa_status_result;
        $output['jobstatusdata'] = $job_status_result;
        $output['req_data'] = $req_data;
        return $output;
    }

    // ********** FABRIC QA REQUEST ENDS HERE *********** /

    // ********** FABRIC QA REQUEST STARTS HERE *********** /

    public function getQARequestDetailss($enqId, $reqId) {
        $sql = "SELECT * FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $reqId . " AND a.flag=1 and a.req_sent_status = 1 and a.qa_req_status=0 and c.qa_approval=0";
        $data = $this->db->query($sql)->result_array();
        
        $result = [];

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($enqId);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $output['data'] = $data;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }

    // ********** FABRIC QA REQUEST ENDS HERE *********** /

    // ********** UPDATE FABRIC DEPARTMENT REQUEST STARTS HERE *********** /

    public function updateQARequestDetailss($id, $data, $date, $note) {

        $qa_pending_sql = "SELECT * FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $id . " AND a.flag=1 and a.req_sent_status = 1 and a.qa_req_status=0";
        $qa_pending_data = $this->db->query($qa_pending_sql)->result_array();

        $qa_update_sql = "SELECT * FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE c.request_id = " . $id . " AND a.flag=1 and a.req_sent_status = 1 and a.qa_req_status=1";
        $qa_update_data = $this->db->query($qa_update_sql)->result_array();

        $req_data = json_decode($data);

        foreach($req_data as $key => $value) {
            $sampleValue["qa_req_status"] = 1;
            $sampleValue["qa_req_date"] = $this->mysqldatetime;
            $sampleValue["qa_cutoff_date"] = $date;
            $sampleValue["sam_dept_note"] = $note;
            $this->db->where('sample_requirement_id', $value[0]);
            $this->db->update('tbl_sample_requirement', $sampleValue);
        }

        if(sizeof($qa_pending_data) == sizeof($qa_update_data)) {
            $this->db->where('request_id', $id);
            $this->db->update('tbl_request', array('qa_approval'=>1,'log'=>LOGTIME));
            $result['status'] = "200";
            return $result;
        }
        else {
            $result['status'] = "201";
            return $result;
        }

    }

    // ********** UPDATE FABRIC DEPARTMENT REQUEST ENDS HERE *********** /

    // ********** FABRIC QA QUEUE STARTS HERE *********** /

    public function getQAQueueDetailss($enqId, $samReqId) {
        $sql = "SELECT * FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE a.sample_requirement_id = " . $samReqId;
        $data = $this->db->query($sql)->result_array();
        
        $result = $att_result = $qa_status = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], '', $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

            $qa_status[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['qa_req_date'], $value['qa_schd_date'], $value['qa_status'], $value['qa_status_update'] ];
        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($enqId);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $output['data'] = $result;
        $output['req_data'] = $data;
        $output['attachmentdata'] = $att_result;
        $output['qa_status_data'] = $qa_status;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }

    // ********** FABRIC QA QUEUE ENDS HERE *********** /

    // ********** UPDATE FABRIC DEPARTMENT QUEUE STARTS HERE *********** /

    public function updateQAQueueDetailss($id, $data, $note) {

        $req_data = json_decode($data);

        foreach ($req_data as $key => $value)
        {
            if($value[7] == '') {
                $sampleValue["qa_schd_date"] = $this->mysqldatetime;
            }
            $sampleValue["qa_status"] = $value[8];
            $sampleValue["qa_status_update"] = $this->mysqldatetime;
            $sampleValue["qa_dept_remarks"] = $note;
            $this->db->where('sample_requirement_id', $value[0]);
            $this->db->update('tbl_sample_requirement', $sampleValue);
        }

    }

    // ********** UPDATE FABRIC DEPARTMENT QUEUE ENDS HERE *********** /   

    // ********** MERCHANT FABRIC QUEUE STARTS HERE *********** /

    public function getMerchantFabricQueueDetailss($enqId, $samReqId) {
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE a.sample_requirement_id = " . $samReqId . " AND a.flag=1 and a.req_sent_status = 1";
        $data = $this->db->query($sql)->result_array();
        
        $result = $att_result = $qa_status_result = $job_status_result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], "", $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

            $qa_status_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], '', $value['qa_req_date'], $value['qa_schd_date'], $value['qa_status'], $value['qa_status_update'] ];
                            
            $job_status_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sam_ref_no'], $value['job_schd_date'], $value['job_status'], $value['job_status_update'] ];
        }
        
        $output['data'] = $result;
        $output['attachmentdata'] = $att_result;
        $output['qastatusdata'] = $qa_status_result;
        $output['jobstatusdata'] = $job_status_result;
        $output['req_data'] = $data;
        return $output;
    }

    // ********** MERCHANT FABRIC QUEUE ENDS HERE *********** /

    // ********** FABRIC QUEUE LIST DETAIL STARTS HERE *********** /

    public function getFabricQueueDetailss($enqId, $samReqId) {
        $sql = "SELECT * FROM tbl_sample_requirement as a 
                INNER JOIN tbl_request_sample b on a.sample_requirement_id=b.sample_id
                INNER JOIN tbl_request c on b.request_id=c.request_id
                WHERE a.sample_requirement_id = " . $samReqId . " AND a.flag=1 and a.req_sent_status = 1";
        $data = $this->db->query($sql)->result_array();
        
        $result = $att_result = $qa_status_result = $job_status_result = [];

        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['sample_requirement'], $value['purpose'], $value['category'], "", $value['req_size'], $value['req_qty'] ];

            $att_result[$key] = [ $value['sample_requirement_id'] , $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['color_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'], $value['buyer_sample'], $value['buyer_comment'] ];

        }

        // *** get garment size *** //
        $sizeChart    = $this->getSizeChart($enqId);
        $sizeMaster   = $this->getSizeMasterDropdown($sizeChart);
        
        $output['data'] = $result;
        $output['attachmentdata'] = $att_result;
        $output['req_data'] = $data;
        $output['sizeData'] = $sizeMaster;
        return $output;
    }

    // ********** FABRIC QUEUE LIST DETAIL ENDS HERE *********** /

    // ********************     FABRIC REQUEST STARTS HERE    **************** //

    public function getItemRequirementDetail($id) {

        $consolidated_sql = "SELECT a.*, b.gpdname FROM tbl_fab_color_wise_garment_parts a 
                            INNER JOIN kn_master_garment_part_desc b ON a.gar_parts=b.id 
                            WHERE a.enquiry_id='$id' AND a.flag=1 
                            group by a.combo, a.component, a.colour, a.gar_parts  
                            ORDER BY a.fab_color_wise_garment_part_id ASC";
        $consolidated_data = $this->db->query($consolidated_sql)->result_array();

        $itemized_fabric_sql = "SELECT * FROM tbl_fab_itemized_fabric_requirement
                            WHERE enquiry_id='$id' AND flag=1 
                            ORDER BY itemized_fabric_requirement_id ASC";
        $itemized_fabric_data = $this->db->query($itemized_fabric_sql)->result_array();

        $color_wise_data = $this->getFabricSizeSpecCodeDetailss($id);

        $size_wise_dia_data = $this->get_sizewise_dia_dimensionn($id);

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);

        // ******* GET DATA DETAILS START ********* //

        $finalValue = [];
        $referenceArr = [];

        
        for($i=0; $i < sizeof($consolidated_data); $i++) 
        {
            $combo = $consolidated_data[$i]['combo'];
            $component = $consolidated_data[$i]['component'];
            $gpdname = $consolidated_data[$i]['gpdname'];
            $colour = $consolidated_data[$i]['colour'];
            $fil_sizes = $fil_prices = $mer_sizes = $uom = [];
            for ($j=0; $j < sizeof($size_wise_dia_data); $j++) {
                $component2 = $size_wise_dia_data[$j][3];
                $gpdname2 = $size_wise_dia_data[$j][4];
                array_push($uom, $size_wise_dia_data[$j][7]);
                if($component === $component2 && $gpdname === $gpdname2)
                {
                    $sizes = [];
                    for ($k=8; $k < sizeof($size_wise_dia_data[$j]) - 1; $k++) {
                        array_push($sizes, $size_wise_dia_data[$j][$k]);
                        array_push($mer_sizes, $size_wise_dia_data[$j][$k]);
                    }
                    array_push($fil_sizes, $sizes);
                }
            }

            // return $color_wise_data;

            for ($j=0; $j < sizeof($color_wise_data); $j++) { 
                $combo2 = $color_wise_data[$j][3];
                $component2 = $color_wise_data[$j][4];
                $colour2 = $color_wise_data[$j][5];
                $gpdname2 = $color_wise_data[$j][6];
                if($component === $component2 && $gpdname === $gpdname2 && $combo === $combo2 && $colour === $colour2)
                {
                    $prices = [];
                    for ($k=8; $k < sizeof($color_wise_data[$j]) - 1; $k++) {
                        array_push($prices, $color_wise_data[$j][$k]);
                    }
                    array_push($fil_prices, $prices);
                }
            }

            $dis_sizes = array_values(array_unique($mer_sizes));
            
            $fin_prices = [];
            for ($j=0; $j < sizeof($dis_sizes); $j++) { 
                $dis_size = $dis_sizes[$j];
                $totalPrice = 0;
                for ($k=0; $k < sizeof($fil_sizes); $k++) { 
                    $asas = $fil_sizes[$k];
                    for ($l=0; $l < sizeof($asas); $l++) { 
                        $fil_size = $fil_sizes[$k][$l];
                        if($dis_size == $fil_size) {
                            $totalPrice += (float)$fil_prices[$k][$l];
                            $totalPrice = number_format((float)$totalPrice, 3, '.', '');
                        }
                    }
                }
                array_push($fin_prices, $totalPrice);
            }

            // print_r($fin_prices);

            $totalSizePrice = number_format((float)array_sum($fin_prices), 3, '.', '');

            for ($j=0; $j < sizeof($dis_sizes); $j++) { 
                if($j == 0)
                {
                    if(!isset($itemized_fabric_data[$i]))
                    // if(isset($itemized_fabric_data[$i]) === 0)
                    {
                        $combineValue = [ '', '', $consolidated_data[$i]['combo'], $consolidated_data[$i]['component'],
                                        $consolidated_data[$i]['colour'], $consolidated_data[$i]['gpdname'], '', '', '', '', '', '', '', '',
                                        $dis_sizes[$j], $uom[$j], $fin_prices[$j], '', '' ];
                    }
                    else
                    {
                        $combineValue = [ 'edit', $itemized_fabric_data[$i]['itemized_fabric_requirement_id'], $consolidated_data[$i]['combo'], 
                                        $consolidated_data[$i]['component'], $consolidated_data[$i]['colour'], $consolidated_data[$i]['gpdname'], 
                                        $itemized_fabric_data[$i]['yarn_blend'], $itemized_fabric_data[$i]['yarn_content'], $itemized_fabric_data[$i]['yarn_count'],
                                        $itemized_fabric_data[$i]['fabric_name'], $itemized_fabric_data[$i]['finishing_gsm'], $itemized_fabric_data[$i]['no_of_feed_pi'],
                                        $itemized_fabric_data[$i]['lycra'], $itemized_fabric_data[$i]['dyeing_type'], $dis_sizes[$j], $uom[$j], 
                                        $fin_prices[$j], '', $totalSizePrice ];
                    }
                }
                else if($j > 0)
                {
                    $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', $dis_sizes[$j], $uom[$j], $fin_prices[$j], '', '' ];
                }
                array_push($finalValue, $combineValue);
            }

            $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $totalSizePrice, '' ];
            array_push($finalValue, $combineValue);

        }

        
        // ******* GET DATA DETAILS ENDS ********* //

        // ******* GET THE COLUMN START ********* //

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);

        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        $lycra_percentage = [ ['id'=> '0', 'name' => '0'] ];
        for($i=1;$i<=10;$i++) 
        {
            $value = [ 'id'=> $i, 'name' => $i ];
            array_push($lycra_percentage, $value);
        }

        $dyeing_type = [ 'FD', 'YDS', 'YDJ', 'SDB', 'DDB' ];

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '8%', 'align'=> 'left', 'readOnly'=> true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'align' => 'left', 'type' => 'dropdown', 'source' => $yarn_blend_data, 'multiple' => true, 'autoComplete'=> true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $yarn_content_data, 'multiple' => true, 'autoComplete'=> true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $yarn_count_data, 'multiple' => true, 'autoComplete'=> true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source'=> $fabric_name_data],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'right'],
            ['title' => "No. of Feed.\n Per Item", 'width' => '8%', 'align'=> 'center'],
            ['title' => "Lycra\n (%)", 'width' => '8%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $lycra_percentage],
            ['title' => "Dyeing\n Type", 'width' => '8%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $dyeing_type ],
            ['title' => "Fin. DIA / DIM\n (W*H)", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Unit of\n Measure", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "DIA Wise Plan.\n Fab. Wgt. (Kgs.)", 'width' => '10%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Plan. Fab. Wgt.\n Subtotal (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "total_avg", 'type'=> 'hidden'],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        $output['yarn_blend_data'] = $yarn_blend_data;
        return $output;
    }

    public function getYarnDyeingColourWiseQtyDetailss($id) {

        $itemize_fabric = $this->getItemRequirementDetail($id);
        $itemized_data = $itemize_fabric['data'];

        $sql2 = "SELECT yarn_spe_req, yarn_purchase_type FROM tbl_fab_itemized_fabric_requirement WHERE (dyeing_type = 'YDS' OR dyeing_type = 'YDJ')";
        $yarnData = $this->db->query($sql2)->result_array();

        $itemized_fabric_data = [];
        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $type = $itemized_data[$i][13];
            if($combo != '' && $component != '' && $colour != '' && $gpdname != '' && ($type == 'YDS' || $type == 'YDJ')) {
                array_push($itemized_fabric_data, $itemized_data[$i]);
            }
        }

        // ******* GET THE DATA DETAILS ENDS ********* //

        //***** final data *****
        $finalValue = [];

        for($i=0; $i < sizeof($itemized_fabric_data); $i++) {

            $id            = $itemized_fabric_data[$i][1];
            $combo         = $itemized_fabric_data[$i][2];
            $component     = $itemized_fabric_data[$i][3];
            $colour        = $itemized_fabric_data[$i][4];
            $gpdname       = $itemized_fabric_data[$i][5];
            $yarn_blend    = $itemized_fabric_data[$i][6];
            $yarn_content  = $itemized_fabric_data[$i][7];
            $yarn_count    = $itemized_fabric_data[$i][8];
            $fabric_name   = $itemized_fabric_data[$i][9];
            $finishing_gsm = $itemized_fabric_data[$i][10];
            $no_of_feed_pi = $itemized_fabric_data[$i][11];
            $lycra         = $itemized_fabric_data[$i][12];
            $dyeing_type   = $itemized_fabric_data[$i][13];
            $total_avg     = $itemized_fabric_data[$i][18];
            $yarn_req     = $yarnData[$i]['yarn_spe_req'];
            $yarn_pur_type     = $yarnData[$i]['yarn_purchase_type'];

            $split_colour = [];
            $split_yarn_blend = [];
            $split_yarn_content = [];
            $split_yarn_count = [];
            $split_no_of_feed_pi = [];
            $split_total_feed = [];
            $split_yarn_req = [];
            $split_yarn_pur_type = [];
            $split = [];

            $split_color = [];
            $split_plan_yarn_wet = [];

            $split = explode(":", $colour);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_colour, $value);
            }

            $split = explode(";", $yarn_blend);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_blend, $value);
            }
            
            $split = explode(";", $yarn_content);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_content, $value);
            }
            
            $split = explode(";", $yarn_count);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_count, $value);
            }

            $split = explode(",", $yarn_req);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_req, $value);
            }

            $split = explode(",", $yarn_pur_type);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_pur_type, $value);
            }

            $split = explode("/", $no_of_feed_pi);
            $totalNoFeed = 0;
            foreach ($split as $key => $value) {
                $totalNoFeed += (int)$value;
            }

            $split = explode("/", $no_of_feed_pi);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_no_of_feed_pi, $value);

                $colour_per = (float)$value * 100 / (float)$totalNoFeed;
                $colour_per = number_format((float)$colour_per, 2, '.', '');
                // $colour_per = (int)$colour_per;
                array_push($split_color, $colour_per);

                $plan_yarn_wet = (float)$total_avg * (float)$colour_per / 100;
                $plan_yarn_wet = number_format((float)$plan_yarn_wet, 3, '.', '');
                array_push($split_plan_yarn_wet, $plan_yarn_wet);
            }

            // return $split_yarn_blend;

            for($j=0; $j < sizeof($split_yarn_blend); $j++) 
            {
                if(sizeof($split_yarn_req) > 0) {
                    $combineValue = [ 'edit', $id, $combo, $component, $split_colour[$j], $gpdname, $split_yarn_blend[$j], $split_yarn_content[$j], 
                        $split_yarn_count[$j], $fabric_name, $finishing_gsm, $lycra, $dyeing_type, $totalNoFeed, $split_no_of_feed_pi[$j], 
                        $split_color[$j], $total_avg, $split_plan_yarn_wet[$j], $split_yarn_req[$j], $split_yarn_pur_type[$j]];
                }
                else {
                    $combineValue = [ 'edit', $id, $combo, $component, $split_colour[$j], $gpdname, $split_yarn_blend[$j], $split_yarn_content[$j], 
                    $split_yarn_count[$j], $fabric_name, $finishing_gsm, $lycra, $dyeing_type, $totalNoFeed, $split_no_of_feed_pi[$j], 
                    $split_color[$j], $total_avg, $split_plan_yarn_wet[$j], '', ''];
                }
                array_push($finalValue, $combineValue);
            }

        }

        // ******* GET THE DATA DETAILS START ********* //
        
        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        // ******* GET THE COLUMN START ********* //

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'left', 'readOnly'=> true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type' => 'dropdown', 'source' => $fabric_name_data],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Lycra (%)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Dyeing\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "No. of Feed.\n Per Repeat", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "No. of Feed.\n Per Colour", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Colour\n (%)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Plan. Fab. Wgt.\n Subtotal (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "yarn_req", 'type' => 'hidden'],
            ['title' => "yarn_purchase_type", 'type' => 'hidden'],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    
    public function getSingleDoubleDyeBathDetailss($id) {

        $itemize_fabric = $this->getItemRequirementDetail($id);
        $itemized_data = $itemize_fabric['data'];

        $sql2 = "SELECT yarn_spe_req, yarn_purchase_type FROM tbl_fab_itemized_fabric_requirement WHERE (dyeing_type = 'SDB' OR dyeing_type = 'DDB')";
        $yarnData = $this->db->query($sql2)->result_array();

        $itemized_fabric_data = [];
        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $type = $itemized_data[$i][13];
            if($combo != '' && $component != '' && $colour != '' && $gpdname != '' && ($type == 'SDB' || $type == 'DDB')) {
                array_push($itemized_fabric_data, $itemized_data[$i]);
            }
        }

        // ******* GET THE DATA DETAILS ENDS ********* //
        
        //***** final data *****

        $finalValue = [];

        for($i=0; $i < sizeof($itemized_fabric_data); $i++) {

            $id            = $itemized_fabric_data[$i][1];
            $combo         = $itemized_fabric_data[$i][2];
            $component     = $itemized_fabric_data[$i][3];
            $colour        = $itemized_fabric_data[$i][4];
            $gpdname       = $itemized_fabric_data[$i][5];
            $yarn_blend    = $itemized_fabric_data[$i][6];
            $yarn_content  = $itemized_fabric_data[$i][7];
            $yarn_count    = $itemized_fabric_data[$i][8];
            $fabric_name   = $itemized_fabric_data[$i][9];
            $finishing_gsm = $itemized_fabric_data[$i][10];
            $no_of_feed_pi = $itemized_fabric_data[$i][11];
            $lycra         = $itemized_fabric_data[$i][12];
            $dyeing_type   = $itemized_fabric_data[$i][13];
            $total_avg     = $itemized_fabric_data[$i][18];
            $yarn_req     = $yarnData[$i]['yarn_spe_req'];
            $yarn_pur_type     = $yarnData[$i]['yarn_purchase_type'];

            $split_yarn_blend = [];
            $split_yarn_content = [];
            $split_yarn_count = [];
            $split_no_of_feed_pi = [];
            $split_total_feed = [];
            $split_yarn_req = [];
            $split_yarn_pur_type = [];
            $split = [];

            $split_color = [];
            $split_plan_yarn_wet = [];

            $split = explode(";", $yarn_blend);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_blend, $value);
            }
            
            $split = explode(";", $yarn_content);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_content, $value);
            }
            
            $split = explode(";", $yarn_count);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_count, $value);
            }

            $split = explode(",", $yarn_req);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_req, $value);
            }

            $split = explode(",", $yarn_pur_type);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_pur_type, $value);
            }

            $split = explode("/", $no_of_feed_pi);
            $totalNoFeed = 0;
            foreach ($split as $key => $value) {
                $totalNoFeed += (int)$value;
            }

            $split = explode("/", $no_of_feed_pi);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_no_of_feed_pi, $value);

                $colour_per = (float)$value / 100 * (float)$totalNoFeed;
                $colour_per = number_format((float)$colour_per, 3, '.', '');
                array_push($split_color, $colour_per);

                $plan_yarn_wet = (float)$total_avg * (float)$colour_per / 100;
                $plan_yarn_wet = number_format((float)$plan_yarn_wet, 3, '.', '');
                array_push($split_plan_yarn_wet, $plan_yarn_wet);
            }

            for($j=0; $j < sizeof($split_yarn_blend); $j++) 
            {
                if(sizeof($split_yarn_req) > 0) {
                    $combineValue = [ 'edit', $id, $combo, $component, $colour, $gpdname, $split_yarn_blend[$j], $split_yarn_content[$j], 
                        $split_yarn_count[$j], $fabric_name, $finishing_gsm, $lycra, $dyeing_type, $totalNoFeed, $split_no_of_feed_pi[$j], 
                        $split_color[$j], $total_avg, $split_plan_yarn_wet[$j], $split_yarn_req[$j], $split_yarn_pur_type[$j]];
                }
                else {
                    $combineValue = [ 'edit', $id, $combo, $component, $colour, $gpdname, $split_yarn_blend[$j], $split_yarn_content[$j], 
                    $split_yarn_count[$j], $fabric_name, $finishing_gsm, $lycra, $dyeing_type, $totalNoFeed, $split_no_of_feed_pi[$j], 
                    $split_color[$j], $total_avg, $split_plan_yarn_wet[$j], '', ''];
                }

                array_push($finalValue, $combineValue);
            }

        }

        // ******* GET THE DATA DETAILS START ********* //
        
        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        // ******* GET THE COLUMN START ********* //

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'left', 'readOnly'=> true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type' => 'dropdown', 'source' => $fabric_name_data],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Lycra (%)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Dyeing\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "No. of Feed.\n Per Repeat", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "No. of Feed.\n Per Colour", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Colour\n (%)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Plan. Fab. Wgt.\n Subtotal (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "yarn_req", 'type' => 'hidden'],
            ['title' => "yarn_purchase_type", 'type' => 'hidden'],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

       
    public function getYarnProgrammeDetailss($id) {

        $itemize_fabric = $this->getItemRequirementDetail($id);
        $itemized_data = $itemize_fabric['data'];

        $yarn_dyeing = $this->getYarnDyeingColourWiseQtyDetailss($id);
        $yarn_dyeing_data = $yarn_dyeing['data'];

        $single_double_dye = $this->getSingleDoubleDyeBathDetailss($id);
        $single_double_dye_data = $single_double_dye['data'];

        $sql2 = "SELECT yarn_spe_req, yarn_purchase_type FROM tbl_fab_itemized_fabric_requirement WHERE dyeing_type = 'FD' ";
        $yarnData = $this->db->query($sql2)->result_array();

        $itemized_fabric_data = [];
        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $type = $itemized_data[$i][13];
            if($combo != '' && $component != '' && $colour != '' && $gpdname != '' && $type == 'FD') {
                array_push($itemized_fabric_data, $itemized_data[$i]);
            }
        }

        // ******* GET THE DATA DETAILS ENDS ********* //

        //***** final data *****
        
        $oldfinalValue = [];

        for($i=0; $i < sizeof($itemized_fabric_data); $i++) {

            $id            = $itemized_fabric_data[$i][1];
            $combo         = $itemized_fabric_data[$i][2];
            $component     = $itemized_fabric_data[$i][3];
            $colour        = $itemized_fabric_data[$i][4];
            $gpdname       = $itemized_fabric_data[$i][5];
            $yarn_blend    = $itemized_fabric_data[$i][6];
            $yarn_content  = $itemized_fabric_data[$i][7];
            $yarn_count    = $itemized_fabric_data[$i][8];
            $fabric_name   = $itemized_fabric_data[$i][9];
            $finishing_gsm = $itemized_fabric_data[$i][10];
            $no_of_feed_pi = $itemized_fabric_data[$i][11];
            $lycra         = $itemized_fabric_data[$i][12];
            $dyeing_type   = $itemized_fabric_data[$i][13];
            $total_avg     = $itemized_fabric_data[$i][18];
            $yarn_req     = $yarnData[$i]['yarn_spe_req'];
            $yarn_pur_type     = $yarnData[$i]['yarn_purchase_type'];

            $split_yarn_blend = [];
            $split_yarn_content = [];
            $split_yarn_count = [];
            $split_no_of_feed_pi = [];
            $split_total_feed = [];
            $split_yarn_req = [];
            $split_yarn_pur_type = [];
            $split = [];

            $split_color = [];
            $split_plan_yarn_wet = [];

            $split = explode(";", $yarn_blend);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_blend, $value);
            }
            
            $split = explode(";", $yarn_content);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_content, $value);
            }
            
            $split = explode(";", $yarn_count);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_count, $value);
            }

            $split = explode(",", $yarn_req);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_req, $value);
            }

            $split = explode(",", $yarn_pur_type);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_yarn_pur_type, $value);
            }

            $split = explode("/", $no_of_feed_pi);
            $totalNoFeed = 0;
            foreach ($split as $key => $value) {
                $totalNoFeed += (int)$value;
            }

            $split = explode("/", $no_of_feed_pi);
            $split = array_filter($split);
            foreach ($split as $key => $value) {
                array_push($split_no_of_feed_pi, $value);

                $colour_per = (float)$value / 100 * (float)$totalNoFeed;
                $colour_per = number_format((float)$colour_per, 3, '.', '');
                array_push($split_color, $colour_per);

                $plan_yarn_wet = (float)$total_avg * (float)$colour_per / 100;
                $plan_yarn_wet = number_format((float)$plan_yarn_wet, 3, '.', '');
                array_push($split_plan_yarn_wet, $plan_yarn_wet);
            }

            for($j=0; $j < sizeof($split_yarn_blend); $j++) 
            {
                if(sizeof($split_yarn_req) > 0) {
                    $combineValue = [ 'edit', $id, $combo, $component, $colour, $gpdname, $split_yarn_blend[$j], $split_yarn_content[$j], 
                        $split_yarn_count[$j], $fabric_name, $finishing_gsm, $lycra, $dyeing_type, $totalNoFeed, $split_no_of_feed_pi[$j], 
                        $split_color[$j], $total_avg, $split_plan_yarn_wet[$j], $split_yarn_req[$j], $split_yarn_pur_type[$j]];
                }
                else {
                    $combineValue = [ 'edit', $id, $combo, $component, $colour, $gpdname, $split_yarn_blend[$j], $split_yarn_content[$j], 
                    $split_yarn_count[$j], $fabric_name, $finishing_gsm, $lycra, $dyeing_type, $totalNoFeed, $split_no_of_feed_pi[$j], 
                    $split_color[$j], $total_avg, $split_plan_yarn_wet[$j], '', ''];
                }
                array_push($oldfinalValue, $combineValue);
            }

        }

        $oldfinalValue = array_merge($oldfinalValue, $yarn_dyeing_data);
        $oldfinalValue = array_merge($oldfinalValue, $single_double_dye_data);

        // *** final data *** //

        $finalValue = [];
        for ($i=0; $i < sizeof($oldfinalValue); $i++) { 

            $lycraWeight = (float)$oldfinalValue[$i][17] * (float)$oldfinalValue[$i][11] / 100;
            $lycraWeight = number_format((float)$lycraWeight, 3, '.', '');

            $reqYarnWeight = (float)$oldfinalValue[$i][17] - (float)$lycraWeight;
            $reqYarnWeight = number_format((float)$reqYarnWeight, 3, '.', '');

            $combineValue = [ 'edit', $oldfinalValue[$i][1], $oldfinalValue[$i][2], $oldfinalValue[$i][3], $oldfinalValue[$i][4], $oldfinalValue[$i][5],
                $oldfinalValue[$i][6], $oldfinalValue[$i][7], $oldfinalValue[$i][8], $oldfinalValue[$i][18], $oldfinalValue[$i][19], $oldfinalValue[$i][12], $oldfinalValue[$i][17],
                $oldfinalValue[$i][11], $lycraWeight, $reqYarnWeight, $oldfinalValue[$i][9], $oldfinalValue[$i][10]
            ];

            array_push($finalValue, $combineValue);

        }

        // ******* GET THE DATA DETAILS START ********* //
        
        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        $yarn_req_sql = "SELECT id, yarnsplreq as name FROM kn_master_yarn_spl_req WHERE status=1";
        $yarn_req_data = $this->db->query($yarn_req_sql)->result_array();

        // ******* GET THE COLUMN START ********* //

        // ******* GET THE DATA DETAILS START ********* //

        // ******* GET THE COLUMN START ********* //

        $asdas = [];
        $yarnPurchase = [ 'Greige', 'Coloured', 'Melange' ];

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'left', 'readOnly'=> true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Yarn Special\n Request.", 'width' => '8%', 'align'=> 'center', 'type'=> 'dropdown', 'source'=> $yarn_req_data],
            ['title' => "Yarn Purchase\n Type", 'width' => '8%', 'align'=> 'center', 'type'=> 'dropdown', 'source'=> $yarnPurchase],
            ['title' => "Dyeing\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Lycra\n (%)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Lycra Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Reqd. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "fabricname", 'type' => 'hidden'],
            ['title' => "gsm", 'type' => 'hidden'],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }
     
    public function getYarnRequirementDetailss($id) {

        $yarn_programme = $this->getYarnProgrammeDetailss($id);
        $yarn_programme_data = $yarn_programme['data'];

        $sql2 = "SELECT * FROM tbl_fab_yarn_requirement WHERE flag = 1 ";
        $yarnData = $this->db->query($sql2)->result_array();
        
        $array = [];
        foreach ($yarn_programme_data as $key => $value)
        {
            $comboValue["id"] = $value[1];
            $comboValue["color"] = $value[4];
            if($value[10] == 'Greige') {
                $comboValue["color"] = $value[10];
            }
            else {
                $comboValue["color"] = $value[4];
            }
            $comboValue["blend"] = $value[6];
            $comboValue["content"] = $value[7];
            $comboValue["count"] = $value[8];
            $comboValue["yarn_req"] = $value[9];
            $comboValue["yarn_purchase_type"] = $value[10];
            $comboValue["reqd_yarn_wgt"] = $value[15];
            // Yarn Blend
            $blend_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[6]."' ";
            $blend_data = $this->db->query($blend_sql)->result_array();
            $comboValue["blend_name"] = $blend_data[0]['misc_name'];
            // Yarn Content
            $content_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[7]."'";
            $content_data = $this->db->query($content_sql)->result_array();
            $comboValue["content_name"] = $content_data[0]['misc_name'];
            // Yarn Count
            $count_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[8]."'";
            $count_data = $this->db->query($count_sql)->result_array();
            $comboValue["count_name"] = $count_data[0]['misc_name'];

            array_push($array, $comboValue);
        }

        $userNames = array();
        foreach($array as $key => $value) {
            $check = [$value["blend_name"], $value["content_name"], $value['count_name'], $value["yarn_req"], $value["yarn_purchase_type"], $value['color']];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);
                $array[$old_key]['reqd_yarn_wgt'] = (float)$array[$old_key]['reqd_yarn_wgt'] + (float)$value['reqd_yarn_wgt'];
                $array[$old_key]['reqd_yarn_wgt'] = number_format((float)$array[$old_key]['reqd_yarn_wgt'], 3, '.', '');

                unset($array[$key]);
            }
            $userNames[] = $check;
        }

        $array = array_values($array);

        // return $yarnData;

        $finalValue = [];

        for ($i=0; $i < sizeof($array); $i++) {
            // if($array[$i]['yarn_purchase_type'] == 'Greige') {
            //     $yarn_purchase_type = $array[$i]['yarn_purchase_type'];
            // }
            // else {
            //     $yarn_purchase_type = $array[$i]['color'];
            // }
            if(sizeof($yarnData) == 0)
            {
                $combineValue = [ '', '', '', '', $array[$i]['blend'], $array[$i]['content'], $array[$i]['count'], $array[$i]['yarn_req'], $array[$i]['yarn_purchase_type'], $array[$i]['color'], $array[$i]['reqd_yarn_wgt'], '', '' ];
                array_push($finalValue, $combineValue);
            }
            else {
                $combineValue = [ 'edit', $yarnData[$i]['fab_yarn_req_id'], $yarnData[$i]['yarn_vendor_brand'], $yarnData[$i]['yarn_product_code'], $array[$i]['blend'], $array[$i]['content'], $array[$i]['count'], $array[$i]['yarn_req'], $array[$i]['yarn_purchase_type'], $array[$i]['color'], $array[$i]['reqd_yarn_wgt'], '', '' ];
                array_push($finalValue, $combineValue);
            }
        }

        // ******* GET THE DATA DETAILS START ********* //

        // ******* GET THE COLUMN START ********* //


       //commented by myself regards new form integration $yarn_vendor_sql = "SELECT id, yarnvendor as name FROM kn_master_yarn_vendor";
        $yarn_vendor_sql = "SELECT id, vendor_name as name FROM ".KN_MASTER_YARNVENDOR."";
        $yarnVendor = $this->db->query($yarn_vendor_sql)->result_array();

        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();
        
        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $yarn_req_sql = "SELECT id, yarnsplreq as name FROM kn_master_yarn_spl_req WHERE status=1";
        $yarn_req_data = $this->db->query($yarn_req_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Yarn - Vendor / Brand", 'width' => '8%', 'align' => 'left', 'type' => 'dropdown', 'source' => $yarnVendor, 'readOnly' => true],
            ['title' => "Yarn Product Code\n (Vendor)", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Yarn Special\n Request.", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true, 'type'=> 'dropdown', 'source'=> $yarn_req_data],
            ['title' => "Yarn Purchase\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Yarn Colour", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Prog. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Raise\n Yarn P.O.", 'width' => '8%', 'align'=> 'center', 'type' => 'checkbox', 'readOnly' => true],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }
     
    public function getYarnReqRequirementDetailss($id) {

        $yarn_programme = $this->getYarnProgrammeDetailss($id);
        $yarn_programme_data = $yarn_programme['data'];

        $sql2 = "SELECT * FROM tbl_fab_yarn_requirement WHERE flag = 1";
        $yarnData = $this->db->query($sql2)->result_array();
        
        $array = [];
        foreach ($yarn_programme_data as $key => $value)
        {
            $comboValue["id"] = $value[1];
            $comboValue["color"] = $value[4];
            if($value[10] == 'Greige') {
                $comboValue["color"] = $value[10];
            }
            else {
                $comboValue["color"] = $value[4];
            }
            $comboValue["blend"] = $value[6];
            $comboValue["content"] = $value[7];
            $comboValue["count"] = $value[8];
            $comboValue["yarn_req"] = $value[9];
            $comboValue["yarn_purchase_type"] = $value[10];
            $comboValue["reqd_yarn_wgt"] = $value[15];
            // Yarn Blend
            $blend_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[6]."' ";
            $blend_data = $this->db->query($blend_sql)->result_array();
            $comboValue["blend_name"] = $blend_data[0]['misc_name'];
            // Yarn Content
            $content_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[7]."'";
            $content_data = $this->db->query($content_sql)->result_array();
            $comboValue["content_name"] = $content_data[0]['misc_name'];
            // Yarn Count
            $count_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[8]."'";
            $count_data = $this->db->query($count_sql)->result_array();
            $comboValue["count_name"] = $count_data[0]['misc_name'];

            array_push($array, $comboValue);
        }

        $userNames = array();
        foreach($array as $key => $value) {
            $check = [$value["blend_name"], $value["content_name"], $value['count_name'], $value["yarn_req"], $value["yarn_purchase_type"], $value['color']];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);
                $array[$old_key]['reqd_yarn_wgt'] = (float)$array[$old_key]['reqd_yarn_wgt'] + (float)$value['reqd_yarn_wgt'];
                $array[$old_key]['reqd_yarn_wgt'] = number_format((float)$array[$old_key]['reqd_yarn_wgt'], 3, '.', '');

                unset($array[$key]);
            }
            $userNames[] = $check;
        }

        $array = array_values($array);

        // return $yarnData;

        $totalValue = [];

        for ($i=0; $i < sizeof($array); $i++) {
            if(sizeof($yarnData) == 0)
            {
                $combineValue = [ '', '', false, '', '', $array[$i]['blend'], $array[$i]['content'], $array[$i]['count'], $array[$i]['yarn_req'], $array[$i]['yarn_purchase_type'], $array[$i]['color'], $array[$i]['reqd_yarn_wgt'], '', '' ];
                array_push($finalValue, $combineValue);
            }
            else {
                $req_status = $yarnData[$i]['req_status'];
                if($req_status == 0 || $req_status == '0') {
                    $req_status = false;
                }
                else {
                    $req_status = true;
                }
                $combineValue = [ 'edit', $yarnData[$i]['fab_yarn_req_id'], $req_status, $yarnData[$i]['yarn_vendor_brand'], $yarnData[$i]['yarn_product_code'], $array[$i]['blend'], $array[$i]['content'], $array[$i]['count'], $array[$i]['yarn_req'], $array[$i]['yarn_purchase_type'], $array[$i]['color'], $array[$i]['reqd_yarn_wgt'], '', '' ];
                array_push($totalValue, $combineValue);
            }
        }

        $finalValue = [];
        foreach ($totalValue as $key => $value) {
            if($value[2] == false)
            {
                array_push($finalValue, $value);
            }
        }

        // ******* GET THE DATA DETAILS START ********* //

        // ******* GET THE COLUMN START ********* //


        //commented by myself regards new form integration $yarn_vendor_sql = "SELECT id, yarnvendor as name FROM kn_master_yarn_vendor";
        $yarn_vendor_sql = "SELECT id, vendor_name as name FROM ".KN_MASTER_YARNVENDOR." WHERE status=1";
        $yarnVendor = $this->db->query($yarn_vendor_sql)->result_array();

        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();
        
        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $yarn_req_sql = "SELECT id, yarnsplreq as name FROM kn_master_yarn_spl_req WHERE status=1";
        $yarn_req_data = $this->db->query($yarn_req_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Mark", 'width' => '5%', 'align' => 'center', 'type'=> 'checkbox'],
            ['title' => "Yarn - Vendor / Brand", 'width' => '8%', 'align' => 'left', 'type' => 'dropdown', 'source' => $yarnVendor, 'readOnly' => true],
            ['title' => "Yarn Product Code\n (Vendor)", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Yarn Special\n Request.", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true, 'type'=> 'dropdown', 'source'=> $yarn_req_data],
            ['title' => "Yarn Purchase\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Yarn Colour", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Prog. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Raise\n Yarn P.O.", 'width' => '8%', 'align'=> 'center', 'type' => 'checkbox', 'readOnly' => true],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $pi_details = [];
        $inhousestatusdetails = [];
        $itemacceptstatus = [];
        $inhouseconsolidatedqtydetails = [];

        $output['column'] = $column;
        $output['data'] = $finalValue;
        $output['pi_details'] = $pi_details;
        $output['inhousestatusdetails'] = $inhousestatusdetails;
        $output['itemacceptstatus'] = $itemacceptstatus;
        $output['inhouseconsolidatedqtydetails'] = $inhouseconsolidatedqtydetails;
        return $output;
    }

    // ********************     FABRIC REQUEST ENDS HERE    **************** //

    // ********** YARN REQUEST STARTS HERE *********** /

    public function getYarnRequestDetailss($id) {

        $yarn_programme = $this->getYarnProgrammeDetailss($id);
        $yarn_programme_data = $yarn_programme['data'];

        $sql2 = "SELECT * FROM tbl_fab_yarn_requirement WHERE flag = 1";
        $yarnData = $this->db->query($sql2)->result_array();
        
        $array = [];
        foreach ($yarn_programme_data as $key => $value)
        {
            $comboValue["id"] = $value[1];
            $comboValue["color"] = $value[4];
            if($value[10] == 'Greige') {
                $comboValue["color"] = $value[10];
            }
            else {
                $comboValue["color"] = $value[4];
            }
            $comboValue["blend"] = $value[6];
            $comboValue["content"] = $value[7];
            $comboValue["count"] = $value[8];
            $comboValue["yarn_req"] = $value[9];
            $comboValue["yarn_purchase_type"] = $value[10];
            $comboValue["reqd_yarn_wgt"] = $value[15];
            // Yarn Blend
            $blend_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[6]."' ";
            $blend_data = $this->db->query($blend_sql)->result_array();
            $comboValue["blend_name"] = $blend_data[0]['misc_name'];
            // Yarn Content
            $content_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[7]."'";
            $content_data = $this->db->query($content_sql)->result_array();
            $comboValue["content_name"] = $content_data[0]['misc_name'];
            // Yarn Count
            $count_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[8]."'";
            $count_data = $this->db->query($count_sql)->result_array();
            $comboValue["count_name"] = $count_data[0]['misc_name'];

            array_push($array, $comboValue);
        }

        $userNames = array();
        foreach($array as $key => $value) {
            $check = [$value["blend_name"], $value["content_name"], $value['count_name'], $value["yarn_req"], $value["yarn_purchase_type"], $value['color']];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);
                $array[$old_key]['reqd_yarn_wgt'] = (float)$array[$old_key]['reqd_yarn_wgt'] + (float)$value['reqd_yarn_wgt'];
                $array[$old_key]['reqd_yarn_wgt'] = number_format((float)$array[$old_key]['reqd_yarn_wgt'], 3, '.', '');

                unset($array[$key]);
            }
            $userNames[] = $check;
        }

        $array = array_values($array);
        $finalValue = [];

        for ($i=0; $i < sizeof($array); $i++) {
            if(sizeof($yarnData) == 0)
            {
                $combineValue = [ '', '', '', '', $array[$i]['blend'], $array[$i]['content'], $array[$i]['count'], $array[$i]['yarn_req'], $array[$i]['yarn_purchase_type'], $array[$i]['color'], $array[$i]['reqd_yarn_wgt'], '', '' ];
                array_push($finalValue, $combineValue);
            }
            else {
                if($yarnData[$i]['req_sent_status'] == "0" || $yarnData[$i]['req_sent_status'] == 0) 
                {
                    $combineValue = [$yarnData[$i]['fab_yarn_req_id'], $yarnData[$i]['req_sent_status'], $yarnData[$i]['yarn_vendor_brand'], $yarnData[$i]['yarn_product_code'], 
                                    $array[$i]['blend'], $array[$i]['content'], $array[$i]['count'], $array[$i]['yarn_req'], 
                                    $array[$i]['yarn_purchase_type'], $array[$i]['color'], $array[$i]['reqd_yarn_wgt'], '', '' ];
                    array_push($finalValue, $combineValue);
                }   
            }
        }
        // ******* GET THE DATA DETAILS START ********* //

        // ******* GET THE COLUMN START ********* //
        //$yarn_vendor_sql = "SELECT id, yarnvendor as name FROM kn_master_yarn_vendor";
        $yarn_vendor_sql = "SELECT id, vendor_name as name FROM ".KN_MASTER_YARNVENDOR." WHERE status=1";
        $yarnVendor = $this->db->query($yarn_vendor_sql)->result_array();

        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();
        
        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $yarn_req_sql = "SELECT id, yarnsplreq as name FROM kn_master_yarn_spl_req WHERE status=1";
        $yarn_req_data = $this->db->query($yarn_req_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $column = [
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Mark", 'type'=> 'checkbox', 'width' => '0%', 'align' => 'center'],
            ['title' => "Yarn - Vendor / Brand", 'width' => '8%', 'align' => 'left', 'type' => 'dropdown', 'source' => $yarnVendor, 'readOnly' => true],
            ['title' => "Yarn Product Code\n (Vendor)", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Yarn Special\n Request.", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true, 'type'=> 'dropdown', 'source'=> $yarn_req_data],
            ['title' => "Yarn Purchase\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Yarn Colour", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Prog. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Raise\n Yarn P.O.", 'width' => '8%', 'align'=> 'center', 'type' => 'checkbox', 'readOnly' => true],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    // ********** YARN REQUEST ENDS HERE *********** /

    // ********** CREATE YARN REQUEST STARTS HERE *********** /

    public function sendFabricRequestt($req_data, $id, $req_type, $cutoff_date, $merchant_note) {

        $requestValue['enquiry_id'] = $id;
        $requestValue['type'] = 6;
        $requestValue['req_type'] = $req_type;
        $requestValue['req_date'] = $this->mysqldatetime;
        $requestValue['cutoff_date'] = $cutoff_date;
        $requestValue['merchant_note'] = $merchant_note;
        $requestValue['log'] = LOGTIME;

        $this->db->insert('tbl_request', $requestValue);
        $primaryId = $this->db->insert_id();

        if($primaryId) {

            $yarnValue["enquiry_id"] = $id;
            $yarnValue["request_id"] = $primaryId;
            $yarnValue['log'] = LOGTIME;
            $this->db->insert('tbl_request_yarn', $yarnValue);
            $req_yarn_id = $this->db->insert_id();

            foreach($req_data as $key => $value) {
                $this->db->where('fab_yarn_req_id', $value[0]);
                $this->db->update('tbl_fab_yarn_requirement', array('req_sent_status' => 1, 'request_id' => $primaryId, 'request_yarn_id'=> $req_yarn_id,'log'=>LOGTIME));
            }

        }

    }

    // ********** CREATE YARN REQUEST ENDS HERE *********** /

    // ********** MERCHANT REQUEST SENT STARTS HERE ************ /

    public function getYarnReceivedDetails($id, $reqId) {
        $yarn_programme = $this->getYarnProgrammeDetailss($id);
        $yarn_programme_data = $yarn_programme['data'];

        $sql2 = "SELECT * FROM tbl_fab_yarn_requirement as a 
                 LEFT JOIN tbl_request_yarn as b ON a.fab_yarn_req_id=b.fab_yarn_req_id 
                 AND b.request_id='$reqId' AND b.enquiry_id='$id' 
                 WHERE a.flag = 1";
        $yarnData = $this->db->query($sql2)->result_array();
        
        $array = [];
        foreach ($yarn_programme_data as $key => $value)
        {
            $comboValue["id"] = $value[1];
            $comboValue["color"] = $value[4];
            if($value[10] == 'Greige') {
                $comboValue["color"] = $value[10];
            }
            else {
                $comboValue["color"] = $value[4];
            }
            $comboValue["blend"] = $value[6];
            $comboValue["content"] = $value[7];
            $comboValue["count"] = $value[8];
            $comboValue["yarn_req"] = $value[9];
            $comboValue["yarn_purchase_type"] = $value[10];
            $comboValue["reqd_yarn_wgt"] = $value[15];
            // Yarn Blend
            $blend_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[6]."' ";
            $blend_data = $this->db->query($blend_sql)->result_array();
            $comboValue["blend_name"] = $blend_data[0]['misc_name'];
            // Yarn Content
            $content_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[7]."'";
            $content_data = $this->db->query($content_sql)->result_array();
            $comboValue["content_name"] = $content_data[0]['misc_name'];
            // Yarn Count
            $count_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[8]."'";
            $count_data = $this->db->query($count_sql)->result_array();
            $comboValue["count_name"] = $count_data[0]['misc_name'];

            array_push($array, $comboValue);
        }

        $userNames = array();
        foreach($array as $key => $value) {
            $check = [$value["blend_name"], $value["content_name"], $value['count_name'], $value["yarn_req"], $value["yarn_purchase_type"], $value['color']];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);
                $array[$old_key]['reqd_yarn_wgt'] = (float)$array[$old_key]['reqd_yarn_wgt'] + (float)$value['reqd_yarn_wgt'];
                $array[$old_key]['reqd_yarn_wgt'] = number_format((float)$array[$old_key]['reqd_yarn_wgt'], 3, '.', '');

                unset($array[$key]);
            }
            $userNames[] = $check;
        }

        $array = array_values($array);
        $finalValue = [];

        for ($i=0; $i < sizeof($array); $i++) {
            if(sizeof($yarnData) == 0)
            {
                $combineValue = [ '', '', '', '', $array[$i]['blend'], $array[$i]['content'], $array[$i]['count'], $array[$i]['yarn_req'], $array[$i]['yarn_purchase_type'], $array[$i]['color'], $array[$i]['reqd_yarn_wgt'], '', '' ];
                array_push($finalValue, $combineValue);
            }
            else {
                if($yarnData[$i]['fab_yarn_req_id'] == "null" || $yarnData[$i]['fab_yarn_req_id'] == null) 
                {
                    
                }   
                else {
                    $combineValue = [$yarnData[$i]['fab_yarn_req_id'], $yarnData[$i]['yarn_vendor_brand'], $yarnData[$i]['yarn_product_code'], 
                                    $array[$i]['blend'], $array[$i]['content'], $array[$i]['count'], $array[$i]['yarn_req'], 
                                    $array[$i]['yarn_purchase_type'], $array[$i]['color'], $array[$i]['reqd_yarn_wgt'], '', '' ];
                    array_push($finalValue, $combineValue);
                }
            }
        }
        // ******* GET THE DATA DETAILS START ********* //

        // ******* GET THE COLUMN START ********* //
        //$yarn_vendor_sql = "SELECT id, yarnvendor as name FROM kn_master_yarn_vendor";
        $yarn_vendor_sql = "SELECT id, vendor_name as name FROM ".KN_MASTER_YARNVENDOR." WHERE status=1";
        $yarnVendor = $this->db->query($yarn_vendor_sql)->result_array();

        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();
        
        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $yarn_req_sql = "SELECT id, yarnsplreq as name FROM kn_master_yarn_spl_req WHERE status=1";
        $yarn_req_data = $this->db->query($yarn_req_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $column = [
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Yarn - Vendor / Brand", 'width' => '8%', 'align' => 'left', 'type' => 'dropdown', 'source' => $yarnVendor, 'readOnly' => true],
            ['title' => "Yarn Product Code\n (Vendor)", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Yarn Special\n Request.", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true, 'type'=> 'dropdown', 'source'=> $yarn_req_data],
            ['title' => "Yarn Purchase\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Yarn Colour", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Prog. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Raise\n Yarn P.O.", 'width' => '8%', 'align'=> 'center', 'type' => 'checkbox', 'readOnly' => true],
        ];
        // ******* GET THE COLUMN ENDS ********* //
        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }
    // ********** MERCHANT REQUEST SENT ENDS HERE ************ /

    // *************************************************************************************** //
    // MANAGEMENT YARN DEPARTMENT STARTS HERE 
    // ************************************************************************************** //
    
    public function updateMgmtAuthorizationn($id, $request_id, $auth_status, $auth_type, $mgmt_remark) {

        $requestValue['auth_status'] = $auth_status;
        $requestValue['mgmt_approval'] = $auth_status;
        $requestValue['auth_by'] = $this->userid;
        $requestValue['auth_date'] = $this->mysqldatetime;
        $requestValue['auth_type'] = $auth_type;
        $requestValue['mgmt_remark'] = $mgmt_remark;
        $requestValue['log'] = LOGTIME;

        $this->db->where('request_id', $request_id);
        $this->db->update('tbl_request', $requestValue);

    }

    // *************************************************************************************** //
    // MANAGEMENT YARN DEPARTMENT ENDS HERE 
    // ************************************************************************************** //
    
    // ********** KNITTING PROGRAMME DETAILS STARTS HERE *********** /
    
    public function getKnittingProgrammeDetailss($id) {

        $itemized_data = $this->getItemizedFabricRequirementDetailss($id);

        $sql2 = "SELECT * FROM tbl_fab_knitting_programme WHERE flag = 1";
        $knittingData = $this->db->query($sql2)->result_array();

        $itemized_fabric_data = [];
        $lastKey = 0;
        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $blend = $itemized_data[$i][6];
            $content = $itemized_data[$i][7];
            $count = $itemized_data[$i][8];
            $fabric = $itemized_data[$i][9];
            $gsm = $itemized_data[$i][10];
            $feed = $itemized_data[$i][11];
            $lycra = $itemized_data[$i][12];
            $dyeing = $itemized_data[$i][13];
            $dia = $itemized_data[$i][14];
            $unit = $itemized_data[$i][15];
            $dia_fab = $itemized_data[$i][16];
            $plan_fab = $itemized_data[$i][17];

            if($combo != '' && $component != '' && $colour != '' && $gpdname != '') {
                $lastKey = $i;
                array_push($itemized_fabric_data, $itemized_data[$i]);
            }
            else {
                if($dia == "" && $unit == "" && $dia_fab == "") 
                {
                }
                else {
                    $arrayKey = sizeof($itemized_fabric_data) - 1;
                    // print_r($itemized_fabric_data);
                    // print_r($arrayKey);
                    $itemized_fabric_data[$arrayKey][14] = $itemized_fabric_data[$arrayKey][14].','.$dia;
                    $itemized_fabric_data[$arrayKey][15] = $itemized_fabric_data[$arrayKey][15].','.$unit;
                    $itemized_fabric_data[$arrayKey][16] = $itemized_fabric_data[$arrayKey][16].','.$dia_fab;
                }
            }
        }

        // exit();

        // return $itemized_fabric_data;

        $array = [];
        foreach ($itemized_fabric_data as $key => $value)
        {
            $comboValue["color"] = $value[4];
            $comboValue["parts"] = $value[5];
            $comboValue["blend"] = $value[6];
            $comboValue["content"] = $value[7];
            $comboValue["count"] = $value[8];
            $comboValue["fabric"] = $value[9];
            $comboValue["gsm"] = $value[10];
            $comboValue["feed"] = $value[11];
            $comboValue["lycra"] = $value[12];
            $comboValue["dyeing"] = $value[13];
            $comboValue["dia"] = $value[14];
            $comboValue["unit"] = $value[15];
            $comboValue["dia_fab"] = $value[16];
            array_push($array, $comboValue);
        }

        $userNames = array();
        foreach($array as $key => $value) {
            $check = [$value['parts'], $value["blend"], $value["content"], $value['count'], $value["fabric"], $value["gsm"], $value["feed"], $value['lycra'], $value["dyeing"]];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);
                $array[$old_key]['dia'] = $array[$old_key]['dia'].','.$value['dia'];
                $array[$old_key]['unit'] = $array[$old_key]['unit'].','.$value['unit'];
                $array[$old_key]['dia_fab'] = $array[$old_key]['dia_fab'].','.$value['dia_fab'];
                if($value["dyeing"] != 'YDS' || $value["dyeing"] != 'YDJ') {
                    unset($array[$key]);
                }
            }
            $userNames[] = $check;
        }

        $array = array_values($array);

        // return $array;

        for ($j=0; $j < sizeof($array); $j++) {
            $dia = explode(',', $array[$j]['dia']);
            $unit = explode(',', $array[$j]['unit']);
            $dia_fab = explode(',', $array[$j]['dia_fab']);
            $arr_dia = array_values(array_unique($dia));

            $arr_unit = [];
            $arr_dia_fab = [];

            for ($k=0; $k < sizeof($arr_dia); $k++) { 
                $dis_size = $arr_dia[$k];
                $totalPrice = 0;
                $totalUnit = '';
                for ($l=0; $l < sizeof($dia); $l++) {
                    $old_dia_size = $dia[$l];
                    $old_unit = $unit[$l];
                    $old_dia_fab = $dia_fab[$l];
                    if($dis_size == $old_dia_size) {
                        $totalUnit = $old_unit;
                        $totalPrice += (float)$old_dia_fab;
                        $totalPrice = number_format((float)$totalPrice, 3, '.', '');
                    }
                }
                array_push($arr_unit, $totalUnit);
                array_push($arr_dia_fab, $totalPrice);
            }
            $array[$j]['dia'] = $arr_dia;
            $array[$j]['unit'] = $arr_unit;
            $array[$j]['dia_fab'] = $arr_dia_fab;
        }

        $finalValue = [];
        foreach($array as $key => $value) {
            $color   = $value["color"];
            $parts   = $value["parts"];
            $blend   = $value["blend"];
            $content = $value["content"];
            $count   = $value["count"];
            $fabric  = $value["fabric"];
            $gsm     = $value["gsm"];
            $feed    = $value["feed"];
            $lycra   = $value["lycra"];
            $dyeing  = $value["dyeing"];
            $dia  = $value["dia"];
            $unit  = $value["unit"];
            $dia_fab  = $value["dia_fab"];

            $arr_blend = explode(';', $blend);
            $arr_blend = array_filter($arr_blend);
            if($dyeing == 'YDS' || $dyeing == 'YDJ') {
                $yarn_color = $color;
            }
            else {
                $yarn_color = 'Greige';
                // print_r(sizeof($arr_blend));
                // print_r(sizeof($dia));
                // exit();
                if(sizeof($arr_blend) > 1) {
                    for ($i=0; $i < sizeof($arr_blend)-1; $i++) { 
                        $yarn_color = $yarn_color.':'.'Greige';
                    }
                }
            }
            
            $totalSizePrice = number_format((float)array_sum($dia_fab), 3, '.', '');

            for ($i=0; $i < sizeof($dia); $i++) {
                if($i == 0) {
                    if(sizeof($knittingData) == 0)
                    {
                        $combineValue = [ '', '', $parts, $blend, $content, $count, $fabric, $gsm, $feed, $lycra, $dyeing, $yarn_color, '', '', '',
                                $dia[$i], $unit[$i], $dia_fab[$i], '', '', '', ''
                            ];
                    }
                    else {
                        $combineValue = [ 'edit', $knittingData[$key]['fab_knitting_prog_id'], $parts, $blend, $content, $count, $fabric, $gsm, $feed, $lycra, $dyeing, $yarn_color, 
                                $knittingData[$key]['knit_machine_make'], $knittingData[$key]['gauge'], $knittingData[$key]['knit_type'], 
                                $dia[$i], $unit[$i], $dia_fab[$i], '', '', '', ''
                            ];
                    }
                    array_push($finalValue, $combineValue);
                }
                else {
                    $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $dia[$i], $unit[$i], $dia_fab[$i], '', 
                            '', '', ''
                        ];
                    array_push($finalValue, $combineValue);
                }
            }
            $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $totalSizePrice, '', '', '' ];
            array_push($finalValue, $combineValue);
        }

        // ******* GET THE DATA DETAILS START ********* //

        // ******* GET THE COLUMN START ********* //
        
        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        // $knitting_sql = "SELECT id, misc_name as name FROM tbl_knitting_style WHERE status = 1";
        // $knittingData = $this->db->query($knitting_sql)->result_array();

        
        $knittingData = ["-","Mayer & Cie","Pailung","Terrot","KH-112 F","KH-323 D","KH-323 DJ"];
        // $knittingType = ["PF - Tubular","PF - Open Width","FS - Tubular","FS - Open Width","AS - Tubular","AS - Open Width","JQ - Tubular","JQ - Open Width"];

        $gauge = [ '14', '16', '18', '20', '22', '24', '26', '28' ];

        $knittingType = [ 'TUB', 'OW' ];

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Garment Parts", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type' => 'dropdown', 'source' => $fabric_name_data],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "No. of Feed.\n Per Item", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Lycra\n (%)", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Dyeing\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Yarn Colour", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Pref. Knitting\n Machine Make", 'width' => '8%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $knittingData, 'readOnly' => true],
            ['title' => "Gauge", 'width' => '8%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $gauge, 'readOnly' => true],
            ['title' => "Knitting\n Type", 'width' => '8%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $knittingType, 'readOnly' => true],
            ['title' => "Fin. DIA / DIM\n (W*H)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Unit of\n Measure", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Plan. Fab. Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Plan. Fab. Wgt.\n Subtotal (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Prog. Fab. Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Prog. Fab. Wgt.\n Subtotal (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Raise\n Knit. Prog.", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    // ********** KNITTING PROGRAMME DETAILS ENDS HERE *********** /
    
    // ********** KNITTING PROGRAMME ITEMIZED YARN REQUIREMENT DETAILS STARTS HERE *********** /
    
     public function getKnittingProgrammeItemizedYarnRequirementDetailss($id) {

        $knitting_sql = $this->getYarnProgrammeDetailss($id);
        $knitting_data = $knitting_sql['data'];

        $array = [];
        foreach ($knitting_data as $key => $value)
        {
            $comboValue["parts"] = $value[5];
            $comboValue["blend"] = $value[6];
            $comboValue["content"] = $value[7];
            $comboValue["count"] = $value[8];
            $comboValue["dyeing"] = $value[11];

            if($value[11] == 'YDS' || $value[11] == 'YDJ') { 
                $comboValue["colour"] = $value[4];
            } else { 
                $comboValue["colour"] = 'Greige';
            }

            $comboValue["plan_yarn_wgt"] = $value[12];
            $comboValue["lycra"] = $value[13];
            $comboValue["fabric_name"] = $value[16];
            $comboValue["gsm"] = $value[17];
            array_push($array, $comboValue);
        }        

        $userNames = array();
        foreach($array as $key => $value) {
            $check = [$value['parts'], $value["blend"], $value["content"], $value['count'], $value["dyeing"], $value["fabric_name"], $value['gsm']];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);
                $array[$old_key]['plan_yarn_wgt'] = (float)$array[$old_key]['plan_yarn_wgt'] + (float)$value['plan_yarn_wgt'];
                $array[$old_key]['plan_yarn_wgt'] = number_format((float)$array[$old_key]['plan_yarn_wgt'], 3, '.', '');
                unset($array[$key]);
            }
            $userNames[] = $check;
        }

        $array = array_values($array);
        
        $finalValue = [];
        for ($i=0; $i < sizeof($array); $i++) { 
            $reqd_yarn_wgt = $array[$i]['plan_yarn_wgt'] - $array[$i]['plan_yarn_wgt'] * $array[$i]['lycra'] / 100;
            $reqd_yarn_wgt = number_format((float)$reqd_yarn_wgt, 3, '.', '');
            $combineValue = [ 'edit', '', $array[$i]['parts'], $array[$i]['blend'], $array[$i]['content'], $array[$i]['count'], 
                    $array[$i]['fabric_name'], $array[$i]['gsm'], $array[$i]['dyeing'], $array[$i]['colour'], $array[$i]['plan_yarn_wgt'], 
                    $array[$i]['lycra'], $reqd_yarn_wgt, '', '' ];
            array_push($finalValue, $combineValue);
        }

        array_multisort($finalValue);

        // return $array;

        // ******* GET THE DATA DETAILS START ********* //
        
        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        // ******* GET THE COLUMN START ********* //

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Garment Parts", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'multiple' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'multiple' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'multiple' => true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type' => 'dropdown', 'source' => $fabric_name_data],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Dyeing\n Type", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Yarn Colour", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Plan. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Lycra\n (%)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Reqd. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly' => true],
            ['title' => "Prog. Yarn Wgt.\n (Kgs.)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Raise Yarn D.C. For\n Knit Programme", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    // ********** KNITTING PROGRAMME ITEMIZED YARN REQUIREMENT DETAILS ENDS HERE *********** /
     

    
    // ************************ DYEING STARTS HERE ************************* /

    // ******** FABRIC DYEING PROGRAMME - COLOUR & DIA WISE QTY. DETAILS (FD, SDB & DDB) STARTS HERE ********* /

    public function getFabricDyeingProgrammeDetails($id) {
        $consolidated_sql = "SELECT a.*, b.gpdname FROM tbl_fab_color_wise_garment_parts a 
                            INNER JOIN kn_master_garment_part_desc b ON a.gar_parts=b.id 
                            WHERE a.enquiry_id='$id' AND a.flag=1 
                            group by a.combo, a.component, a.colour, a.gar_parts  
                            ORDER BY a.fab_color_wise_garment_part_id ASC";
        $consolidated_data = $this->db->query($consolidated_sql)->result_array();

        $itemized_fabric_sql = "SELECT * FROM tbl_fab_itemized_fabric_requirement
                            WHERE enquiry_id='$id' AND flag=1 
                            ORDER BY itemized_fabric_requirement_id ASC";
        $itemized_fabric_data = $this->db->query($itemized_fabric_sql)->result_array();

        $color_wise_data = $this->getFabricSizeSpecCodeDetailss($id);

        $size_wise_dia_data = $this->get_sizewise_dia_dimensionn($id);

        $sizeChart    = $this->getSizeChart($id);
        $sizeMaster   = $this->getSizeMaster($sizeChart);

        // ******* GET DATA DETAILS START ********* //

        $finalValue = [];
        $referenceArr = [];
        
        for($i=0; $i < sizeof($consolidated_data); $i++) 
        {
            $combo = $consolidated_data[$i]['combo'];
            $component = $consolidated_data[$i]['component'];
            $gpdname = $consolidated_data[$i]['gpdname'];
            $colour = $consolidated_data[$i]['colour'];
            $fil_sizes = $fil_prices = $mer_sizes = $uom = [];
            for ($j=0; $j < sizeof($size_wise_dia_data); $j++) {
                $component2 = $size_wise_dia_data[$j][3];
                $gpdname2 = $size_wise_dia_data[$j][4];
                array_push($uom, $size_wise_dia_data[$j][7]);
                if($component === $component2 && $gpdname === $gpdname2)
                {
                    $sizes = [];
                    for ($k=8; $k < sizeof($size_wise_dia_data[$j]) - 1; $k++) {
                        array_push($sizes, $size_wise_dia_data[$j][$k]);
                        array_push($mer_sizes, $size_wise_dia_data[$j][$k]);
                    }
                    array_push($fil_sizes, $sizes);
                }
            }

            // return $color_wise_data;

            for ($j=0; $j < sizeof($color_wise_data); $j++) { 
                $combo2 = $color_wise_data[$j][3];
                $component2 = $color_wise_data[$j][4];
                $colour2 = $color_wise_data[$j][5];
                $gpdname2 = $color_wise_data[$j][6];
                if($component === $component2 && $gpdname === $gpdname2 && $combo === $combo2 && $colour === $colour2)
                {
                    $prices = [];
                    for ($k=8; $k < sizeof($color_wise_data[$j]) - 1; $k++) {
                        array_push($prices, $color_wise_data[$j][$k]);
                    }
                    array_push($fil_prices, $prices);
                }
            }

            $dis_sizes = array_values(array_unique($mer_sizes));
            
            $fin_prices = [];
            for ($j=0; $j < sizeof($dis_sizes); $j++) { 
                $dis_size = $dis_sizes[$j];
                $totalPrice = 0;
                for ($k=0; $k < sizeof($fil_sizes); $k++) { 
                    $asas = $fil_sizes[$k];
                    for ($l=0; $l < sizeof($asas); $l++) { 
                        $fil_size = $fil_sizes[$k][$l];
                        if($dis_size == $fil_size) {
                            $totalPrice += (float)$fil_prices[$k][$l];
                            $totalPrice = number_format((float)$totalPrice, 3, '.', '');
                        }
                    }
                }
                array_push($fin_prices, $totalPrice);
            }

            // print_r($fin_prices);

            $totalSizePrice = number_format((float)array_sum($fin_prices), 3, '.', '');

            for ($j=0; $j < sizeof($dis_sizes); $j++) { 
                if($j == 0)
                {
                    if(!isset($itemized_fabric_data[$i]))
                    // if(isset($itemized_fabric_data[$i]) === 0)
                    {
                        $combineValue = [ '', '', $consolidated_data[$i]['combo'], $consolidated_data[$i]['component'],
                                        $consolidated_data[$i]['colour'], $consolidated_data[$i]['gpdname'], '', '', '', '', '', '', '', '',
                                        $dis_sizes[$j], $uom[$j], $fin_prices[$j], '', '' ];
                    }
                    else
                    {
                        $combineValue = [ 'edit', $itemized_fabric_data[$i]['itemized_fabric_requirement_id'], $consolidated_data[$i]['combo'], 
                                        $consolidated_data[$i]['component'], $consolidated_data[$i]['colour'], $consolidated_data[$i]['gpdname'], 
                                        $itemized_fabric_data[$i]['yarn_blend'], $itemized_fabric_data[$i]['yarn_content'], $itemized_fabric_data[$i]['yarn_count'],
                                        $itemized_fabric_data[$i]['fabric_name'], $itemized_fabric_data[$i]['finishing_gsm'], $itemized_fabric_data[$i]['no_of_feed_pi'],
                                        $itemized_fabric_data[$i]['lycra'], $itemized_fabric_data[$i]['dyeing_type'], $dis_sizes[$j], $uom[$j], 
                                        $fin_prices[$j], '', $totalSizePrice ];
                    }
                }
                else if($j > 0)
                {
                    $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', $itemized_fabric_data[$i]['dyeing_type'], $dis_sizes[$j], $uom[$j], $fin_prices[$j], '', '' ];
                }
                array_push($finalValue, $combineValue);
            }

            $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', $itemized_fabric_data[$i]['dyeing_type'], '', '', '', $totalSizePrice, '' ];
            array_push($finalValue, $combineValue);

        }

        return $finalValue;
    }

    public function getFabricDyeingProgramme_qtyy($id) {
        
        $itemized_data = $this->getFabricDyeingProgrammeDetails($id);
        
        $itemized_fabric_data = [];

        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $dyeing = $itemized_data[$i][13];

            if($dyeing == 'YDJ' || $dyeing == 'YDS') { }
            else {
                array_push($itemized_fabric_data, $itemized_data[$i]);
            }

        }

        $array = $finalValue = [];

        foreach ($itemized_fabric_data as $index => $value)
        {
            $combo = $value[2];
            $component = $value[3];
            $colour = $value[4];
            $gpdname = $value[5];
            $blend = $value[6];
            $content = $value[7];
            $feed = $value[11];
            $lycra = $value[12];

            if($combo == '' && $component == '' && $colour == '' && $gpdname == '') { }
            else {
                $split_blend = $split_content = $split_feed = [];

                $split = explode(";", $blend);
                $split = array_filter($split);
                foreach ($split as $key => $value) {
                    array_push($split_blend, $value);
                }

                $split = explode(";", $content);
                $split = array_filter($split);
                foreach ($split as $key => $value) {
                    array_push($split_content, $value);
                }

                $split = explode("/", $feed);
                $split = array_filter($split);
                foreach ($split as $key => $value) {
                    array_push($split_feed, $value);
                }

                $total_feed_val = array_sum($split_feed);

                $arr_fabric_blend = $split_blend_data = $arr_blend_lycra = $arr_fabric_content = $fin_blend_lycra = $arr_itemized_content = [];
                
                for($i=0; $i < sizeof($split_blend); $i++) 
                {
                    $sql2 = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$split_blend[$i]."' ";
                    $feed_data = $this->db->query($sql2)->result_array();
                    $blend_data = $feed_data[0]['misc_name'];

                    $sql2 = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$split_content[$i]."' ";
                    $feed_data = $this->db->query($sql2)->result_array();
                    $content_data = $feed_data[0]['misc_name'];

                    $split_blend_data = $this->multiexplode(array("+",'/',":"), $blend_data);
                    $fin_split_content = $this->multiexplode(array("+",'/',":"), $content_data);

                    for ($j=0; $j < sizeof($split_blend_data); $j++) { 
                        $fabric_blend = (int)$split_feed[$i] * 100 / (int)$total_feed_val;
                        array_push($arr_fabric_blend, (int)$fabric_blend);
                        array_push($arr_itemized_content, $split_blend_data[$j]);
                        array_push($arr_fabric_content, trim($fin_split_content[$j]));
                    }
                }

                $total_fabric_blend = array_sum($arr_fabric_blend);

                for ($j=0; $j < sizeof($arr_fabric_blend); $j++) { 
                    $blend_lycra = $arr_fabric_blend[$j] * ($total_fabric_blend - $lycra) / 100;
                    array_push($arr_blend_lycra, (int)$blend_lycra);
                }

                for ($j=0; $j < sizeof($arr_blend_lycra); $j++) { 
                    $fin_blend = $arr_blend_lycra[$j] * $arr_itemized_content[$j] / 100;
                    array_push($fin_blend_lycra, (int)$fin_blend);
                }

                $unique_content = array_values(array_unique($arr_fabric_content));

                $unique_fabric_blend = [];
                $final_fabric_content = '';
                $final_fabric_blend = '';
                for ($j=0; $j < sizeof($unique_content); $j++) {
                    $s_content = $unique_content[$j];
                    $totalPrice = 0;
                    for ($k=0; $k < sizeof($arr_fabric_content); $k++) {
                        $n_content = $arr_fabric_content[$k];
                        if($s_content == $n_content) {
                            $totalPrice += (int)$fin_blend_lycra[$k];
                        }
                    }
                    if($j == 0) { 
                        $final_fabric_content = $unique_content[$j];
                        $final_fabric_blend = $totalPrice;
                    }
                    else if($j > 0) { 
                        $final_fabric_content = $final_fabric_content.' : '.$unique_content[$j]; 
                        $final_fabric_blend = $final_fabric_blend.' : '.$totalPrice; 
                    }

                }

                if($lycra == '' || $lycra == '0' || $lycra == 0) { }
                else {
                    $final_fabric_content = $final_fabric_content.' : Lycra';
                    $final_fabric_blend = $final_fabric_blend.' : '.$lycra;
                }

                $itemized_fabric_data[$index][6] = $final_fabric_blend;
                $itemized_fabric_data[$index][7] = $final_fabric_content;
            }
        }

        $finalValue = [];

        for ($i=0; $i < sizeof($itemized_fabric_data); $i++) {
            if($itemized_fabric_data[$i][2] == '' && $itemized_fabric_data[$i][17] == '')
            {
                $combineValue = [ '', '', '', '', '', '', '', '', '', '', $itemized_fabric_data[$i][14], $itemized_fabric_data[$i][15], 
                        $itemized_fabric_data[$i][16], '', '', '', '' 
                    ];
            }
            else if($itemized_fabric_data[$i][17] != '')
            {
                $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', $itemized_fabric_data[$i][17], '', '', '' ];
            }
            else
            {
                $combineValue = [ 'edit', '', $itemized_fabric_data[$i][2], $itemized_fabric_data[$i][3], $itemized_fabric_data[$i][4], 
                        $itemized_fabric_data[$i][5], $itemized_fabric_data[$i][6], $itemized_fabric_data[$i][7], 
                        $itemized_fabric_data[$i][9], $itemized_fabric_data[$i][10], $itemized_fabric_data[$i][14], 
                        $itemized_fabric_data[$i][15], $itemized_fabric_data[$i][16], '', '', '', ''
                    ];
            }
            array_push($finalValue, $combineValue);
        }
        
        // ******* GET THE DATA DETAILS START ********* //

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        // ******* GET THE COLUMN START ********* //

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '12%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Fabric Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true],
            ['title' => "Fabric Content", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $fabric_name_data, 'readOnly' => true],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Fin. DIA / DIM\n (W*H)", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Unit of\n Measure", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "DIA Wise Plan.\n Fab. Wgt. (Kgs.)", 'width' => '10%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Plan. Fab. Wgt.\n Subtotal (Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Prog. Fab. Wgt.(Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Prog. Fab. Wgt. Subtotal", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Raise Fabric\n Dyeing Prog.", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            // ['title' => "total_avg", 'type'=> 'hidden'],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    // ******** FABRIC DYEING PROGRAMME - COLOUR & DIA WISE QTY. DETAILS (FD, SDB & DDB) ends HERE ********* /

    // ******** FABRIC DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (FD, SDB & DDB) STARTS HERE ********* /

    public function getFabricDyeingProgramme_finishh($id) {

        $fabric_dyeing = $this->getFabricDyeingProgramme_qtyy($id);
        $fabric_dyeing_data = $fabric_dyeing['data'];

        $fabric_dye_sql = "SELECT * FROM tbl_fab_dye_programme WHERE enquiry_id='$id' AND flag=1";
        $fabDyeProgrammeData = $this->db->query($fabric_dye_sql)->result_array();

        $array = $colourContent = [];
        foreach ($fabric_dyeing_data as $key => $value) {
            $combo = $value[2];
            $component = $value[3];
            $colour = $value[4];
            $parts = $value[5];
            $fabric_content = $value[7];
            if( $combo == '' && $component == '' && $colour == '' && $parts == '') {  }
            else {
                $split_fabric_content = explode(':', $fabric_content);
                $index = array_search(' Lycra', $split_fabric_content);
                if($index !== FALSE){
                    unset($split_fabric_content[$index]);
                }
                $split_fabric_content = array_values($split_fabric_content);
                array_push($array, $value);

                for ($i=0; $i < sizeof($split_fabric_content); $i++) { 
                    $color_content_array = [
                        'combo' => $combo,
                        'component' => $component,
                        'colour' => $colour,
                        'parts' => $parts,
                        'name' => trim($split_fabric_content[$i]),
                        'id' => trim($split_fabric_content[$i]),
                    ];
                    array_push($colourContent, $color_content_array);
                }
            }
        }

        $array = array_values($array);

        $finalValue = [];

        for ($i=0; $i < sizeof($array); $i++) {  
            $combo = $array[$i][2];
            $component = $array[$i][3];
            $colour = $array[$i][4];
            $parts = $array[$i][5];
            $fabric = $array[$i][8];

            if(sizeof($fabDyeProgrammeData) == 0) {
                $combineValue = [
                    '', '', $combo, $component, $colour, $parts, $fabric, '', '', '', '', '', '', ''
                ];
            }
            else
            {
                $combineValue = [
                    'edit', $fabDyeProgrammeData[$i]['dye_programme_id'], $combo, $component, $colour, $parts, $fabric, $fabDyeProgrammeData[$i]['pantone'], 
                    $fabDyeProgrammeData[$i]['dyeing_special_req'], $fabDyeProgrammeData[$i]['req_fab'], $fabDyeProgrammeData[$i]['blend_fabric'], 
                    $fabDyeProgrammeData[$i]['colour_match_standard'], $fabDyeProgrammeData[$i]['appr_lab_dip'], $fabDyeProgrammeData[$i]['dye_vendor']
                ];
            }

            array_push($finalValue, $combineValue);

        }
        
        // ******* GET THE DATA DETAILS START ********* //

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        $dsr_sql = "SELECT id, dsrname as name FROM kn_master_dyeing_special_request WHERE companyid = $this->companyid AND status=1";
        $dsr_data = $this->db->query($dsr_sql)->result_array();

        $fabricfinish_sql = "SELECT id, fabricfinish as name FROM kn_master_fabric_finish_wet_dry WHERE companyid = $this->companyid AND status=1";
        $fabric_finish_data = $this->db->query($fabricfinish_sql)->result_array();
        
        //commented below line no:3051 by myself regards new form of dyeing job work details integration 
        //$dyeing_vendor_sql = "SELECT id, vendorname as name FROM kn_master_dyeing_vendor WHERE companyid = $this->companyid AND status=1";
        $dyeing_vendor_sql = "SELECT id, jobwrkname as name FROM ".KN_MASTER_JOBWRK." WHERE companyid = $this->companyid AND type=1 AND status=1";
        $dyeingVendor = $this->db->query($dyeing_vendor_sql)->result_array();

        $colourStandard = [ 'D65', 'TL84', 'CWF', 'F', 'A', 'UV', 'U30' ];

        // $output['column'] = $column;
        $output['data'] = $finalValue;
        $output['fabric_name_data'] = $fabric_name_data;
        $output['dsr_data'] = $dsr_data;
        $output['fabric_finish_data'] = $fabric_finish_data;
        $output['colourStandard'] = $colourStandard;
        $output['colourContent'] = $colourContent;
        $output['dyeingVendor'] = $dyeingVendor;
        return $output;
    }

    // ******** FABRIC DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (FD, SDB & DDB) ends HERE ********* /

    // ******** YARN DYEING PROGRAMME - COLOUR WISE QTY. DETAILS CONSOLIDATED (YDS & YDJ) STARTS HERE ********* /

    public function getYarnDyeingProgramme_qtyy($id) {

        $yarn_dyeing = $this->getYarnDyeingColourWiseQtyDetailss($id);
        $yarn_dyeing_data = $yarn_dyeing['data'];

        $array = [];

        foreach ($yarn_dyeing_data as $key => $value)
        {
            $comboValue["colour"] = $value[4];
            $comboValue["blend"] = $value[6];
            $comboValue["content"] = $value[7];
            $comboValue["count"] = $value[8];
            $comboValue["lycra"] = $value[11];
            $comboValue["plan_yarn_wgt"] = $value[17];

            $reqd_yarn_wgt = $comboValue["plan_yarn_wgt"] - $comboValue["plan_yarn_wgt"] * $comboValue["lycra"] / 100;

            $comboValue["reqd_yarn_wgt"] = number_format((float)$reqd_yarn_wgt, 3, '.', '');

            array_push($array, $comboValue);
        }

        $userNames = array();
        foreach($array as $key => $value) {
            $check = [$value['colour'], $value["blend"], $value["content"], $value['count']];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);
                $array[$old_key]['plan_yarn_wgt'] = (float)$array[$old_key]['plan_yarn_wgt'] + (float)$value['plan_yarn_wgt'];
                $array[$old_key]['plan_yarn_wgt'] = number_format((float)$array[$old_key]['plan_yarn_wgt'], 3, '.', '');

                $array[$old_key]['reqd_yarn_wgt'] = (float)$array[$old_key]['reqd_yarn_wgt'] + (float)$value['reqd_yarn_wgt'];
                $array[$old_key]['reqd_yarn_wgt'] = number_format((float)$array[$old_key]['reqd_yarn_wgt'], 3, '.', '');

                unset($array[$key]);
            }
            $userNames[] = $check;
        }

        $array = array_values($array);

        $finalValue = [];

        foreach ($array as $key => $value) {
            $colour = $value["colour"];            
            $blend = $value["blend"];
            $content = $value["content"];
            $count = $value["count"];
            $plan_yarn_wgt = $value["plan_yarn_wgt"];
            $reqd_yarn_wgt = $value["reqd_yarn_wgt"];
            $combineValue = [
                'edit', '', $colour, $blend, $content, $count, $plan_yarn_wgt, $reqd_yarn_wgt, '', ''
            ];
            array_push($finalValue, $combineValue);
        }

        // ******* GET THE DATA DETAILS START ********* //

        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        // ******* GET THE COLUMN START ********* //

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Yarn Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_blend_data, 'readOnly' => true],
            ['title' => "Yarn Content", 'width' => '8%', 'align'=> 'left', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_content_data, 'readOnly' => true],
            ['title' => "Yarn Count", 'width' => '8%', 'align'=> 'center', 'readOnly' => true, 'type'=>'dropdown', 'source' => $yarn_count_data, 'readOnly' => true],
            ['title' => "Plan. Yarn. Wgt.\n(Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            // ['title' => "Lycra \n(%)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            // ['title' => "Lycra Wgt.\n(Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Reqd. Yarn.\n Wgt.(Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Prog. Fab. \nWgt.(Kgs.)", 'width' => '8%', 'align'=> 'right', 'readOnly'=> true],
            ['title' => "Raise Yarn Dyeing", 'width' => '8%', 'align'=> 'center', 'type' => 'checkbox', 'readOnly'=> true],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    // ******** YARN DYEING PROGRAMME - COLOUR WISE QTY. DETAILS CONSOLIDATED (YDS & YDJ) ends HERE ********* /

    // ******** YARN DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (YDS & YDJ) STARTS HERE ********* /

    public function getYarnDyeingProgramme_finishh($id) {

        $yarn_dyeing = $this->getYarnDyeingColourWiseQtyDetailss($id);
        $yarn_dyeing_data = $yarn_dyeing['data'];

        $array = $colourContent = [];

        foreach ($yarn_dyeing_data as $key => $value)
        {
            $comboValue["combo"] = $value[2];
            $comboValue["component"] = $value[3];
            $comboValue["colour"] = $value[4];
            $comboValue["parts"] = $value[5];
            $comboValue["blend"] = $value[6];
            $comboValue["content"] = $value[7];
            $comboValue["count"] = $value[8];

            $content_sql = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$value[7]."'";
            $yarn_content_data = $this->db->query($content_sql)->result_array();

            if(sizeof($yarn_content_data) > 0)
            {
                $split_content = $this->multiexplode(array("+",'/',":"), $yarn_content_data[0]['misc_name']);
            }

            for ($i=0; $i < sizeof($split_content); $i++) { 
                $color_content_array = [
                    'combo' => $value[2],
                    'component' => $value[3],
                    'colour' => $value[4],
                    'parts' => $value[5],
                    'name' => trim($split_content[$i]),
                    'id' => trim($split_content[$i]),
                ];
                array_push($colourContent, $color_content_array);
            }

            array_push($array, $comboValue);
        }

        $userNames = array();
        foreach($array as $key => $value) {
            $check = [$value['colour'], $value["blend"], $value["content"], $value['count']];
            if(!empty($userNames) && in_array($check, $userNames)) {
                $old_key = array_search($check, $userNames);
                unset($array[$key]);
            }
            $userNames[] = $check;
        }

        $array = array_values($array);

        $fabric_dye_sql = "SELECT * FROM tbl_yarn_dye_programme WHERE enquiry_id='$id' AND flag=1";
        $fabDyeProgrammeData = $this->db->query($fabric_dye_sql)->result_array();

        $finalValue = [];

        foreach ($array as $key => $value) {
            if(sizeof($fabDyeProgrammeData) == 0) {
                $combineValue = [
                    '', '', $value['colour'], $value['combo'], $value['component'], $value['parts'], '', '', '', '', '', '', ''
                ];
            }
            else
            {
                $combineValue = [
                    'edit', $fabDyeProgrammeData[$key]['dye_programme_id'], $value['colour'], $value['combo'], $value['component'], $value['parts'], $fabDyeProgrammeData[$key]['pantone'], 
                    $fabDyeProgrammeData[$key]['dyeing_special_req'], $fabDyeProgrammeData[$key]['req_fab'], $fabDyeProgrammeData[$key]['blend_fabric'], 
                    $fabDyeProgrammeData[$key]['colour_match_standard'], $fabDyeProgrammeData[$key]['appr_lab_dip'], $fabDyeProgrammeData[$key]['dye_vendor']
                ];
            }
            array_push($finalValue, $combineValue);
        }

        // ******* GET THE DATA DETAILS START ********* //

        $dsr_sql = "SELECT id, dsrname as name FROM kn_master_dyeing_special_request WHERE companyid = $this->companyid AND status=1";
        $dsr_data = $this->db->query($dsr_sql)->result_array();

        $fabricfinish_sql = "SELECT id, fabricfinish as name FROM kn_master_fabric_finish_wet_dry WHERE companyid = $this->companyid AND status=1";
        $fabric_finish_data = $this->db->query($fabricfinish_sql)->result_array();

         //commented below line no:3250 by myself regards new form of dyeing job work details integration 
        //$dyeing_vendor_sql = "SELECT id, vendorname as name FROM kn_master_dyeing_vendor WHERE companyid = $this->companyid AND status=1";
        $dyeing_vendor_sql = "SELECT id, jobwrkname as name FROM ".KN_MASTER_JOBWRK." WHERE companyid = $this->companyid AND type=1 AND status=1";
        $dyeingVendor = $this->db->query($dyeing_vendor_sql)->result_array();

        $colourStandard = [ 'D65', 'TL84', 'CWF', 'F', 'A', 'UV', 'U30' ];

        $output['data'] = $finalValue;
        $output['dsr_data'] = $dsr_data;
        $output['fabric_finish_data'] = $fabric_finish_data;
        $output['colourStandard'] = $colourStandard;
        $output['colourContent'] = $colourContent;
        $output['dyeingVendor'] = $dyeingVendor;
        return $output;
    }

    // ******** YARN DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (YDS & YDJ) ends HERE ********* //

    // ************************ DYEING ENDS HERE ************************* //

    
    // ************************ COMPACTING STARTS HERE ************************* /

    // ******** FABRIC WASHING COMPACTING & HEAT SETTING DETAILS STARTS HERE ********* /

    public function getFabricWashingCompatingDetailss($id) {
        
        $itemized_data = $this->getItemizedFabricRequirementDetailss($id);

        $compacting_sql = "SELECT * FROM tbl_fab_wash_compacting_heat WHERE enquiry_id = '$id' AND flag=1";
        $compactingData = $this->db->query($compacting_sql)->result_array();
        
        $itemized_fabric_data = [];

        for ($i=0; $i < sizeof($itemized_data); $i++) { 
            $combo = $itemized_data[$i][2];
            $component = $itemized_data[$i][3];
            $colour = $itemized_data[$i][4];
            $gpdname = $itemized_data[$i][5];
            $dyeing = $itemized_data[$i][13];

            // if($dyeing == 'YDJ' || $dyeing == 'YDS') { }
            // else {
                array_push($itemized_fabric_data, $itemized_data[$i]);
            // }

        }

        $array = $finalValue = [];

        foreach ($itemized_fabric_data as $index => $value)
        {
            $combo = $value[2];
            $component = $value[3];
            $colour = $value[4];
            $gpdname = $value[5];
            $blend = $value[6];
            $content = $value[7];
            $feed = $value[11];
            $lycra = $value[12];

            if($combo == '' && $component == '' && $colour == '' && $gpdname == '') { }
            else {
                $split_blend = $split_content = $split_feed = [];

                $split = explode(";", $blend);
                $split = array_filter($split);
                foreach ($split as $key => $value) {
                    array_push($split_blend, $value);
                }

                $split = explode(";", $content);
                $split = array_filter($split);
                foreach ($split as $key => $value) {
                    array_push($split_content, $value);
                }

                $split = explode("/", $feed);
                $split = array_filter($split);
                foreach ($split as $key => $value) {
                    array_push($split_feed, $value);
                }

                $total_feed_val = array_sum($split_feed);

                $fabric_blend = $arr_fabric_blend = $split_blend_data = $blend_lycra = $arr_blend_lycra = $arr_fabric_content = $fin_blend_lycra = $arr_itemized_content = [];
                
                for($i=0; $i < sizeof($split_blend); $i++) 
                {
                    $sql2 = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$split_blend[$i]."' ";
                    $feed_data = $this->db->query($sql2)->result_array();
                    $blend_data = $feed_data[0]['misc_name'];

                    $sql2 = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$split_content[$i]."' ";
                    $feed_data = $this->db->query($sql2)->result_array();
                    $content_data = $feed_data[0]['misc_name'];

                    $split_blend_data = $this->multiexplode(array("+",'/',":"), $blend_data);
                    $fin_split_content = $this->multiexplode(array("+",'/',":"), $content_data);

                    $fabric_blend_val = (float)$split_feed[$i] * 100 / (float)$total_feed_val;
                    array_push($fabric_blend, round($fabric_blend_val));
                        
                    for ($j=0; $j < sizeof($split_blend_data); $j++) { 
                        array_push($arr_fabric_blend, $fabric_blend[$i]);
                        array_push($arr_itemized_content, trim($split_blend_data[$j]));
                        array_push($arr_fabric_content, trim($fin_split_content[$j]));
                    }
                    
                }
                
                $total_fabric_blend = array_sum($fabric_blend);
                
                // print_r($total_fabric_blend);

                for ($i=0; $i < sizeof($split_blend); $i++) { 
                    $blend_lycra_val = $fabric_blend[$i] * ($total_fabric_blend - $lycra) / 100;
                    array_push($blend_lycra, round($blend_lycra_val));
                }
                
                // print_r($arr_fabric_blend);
                
                for($i=0; $i < sizeof($split_blend); $i++) 
                {
                    $sql2 = "SELECT misc_name FROM kn_master_yarn_misc WHERE auth_str = '".$split_blend[$i]."' ";
                    $feed_data = $this->db->query($sql2)->result_array();
                    $blend_data = $feed_data[0]['misc_name'];

                    $split_blend_data = $this->multiexplode(array("+",'/',":"), $blend_data);
                        
                    for ($j=0; $j < sizeof($split_blend_data); $j++) { 
                        array_push($arr_blend_lycra, $blend_lycra[$i]);
                    }
                    
                }
                
                // print_r($arr_blend_lycra);

                for ($i=0; $i < sizeof($arr_blend_lycra); $i++) { 
                    $fin_blend = (float)$arr_blend_lycra[$i] * (float)$arr_itemized_content[$i] / 100;
                    array_push($fin_blend_lycra, (float)$fin_blend);
                }

                $unique_content = array_values(array_unique($arr_fabric_content));

                $unique_fabric_blend = [];
                $final_fabric_content = '';
                $final_fabric_blend = '';
                for ($j=0; $j < sizeof($unique_content); $j++) {
                    $s_content = $unique_content[$j];
                    $totalPrice = 0;
                    for ($k=0; $k < sizeof($arr_fabric_content); $k++) {
                        $n_content = $arr_fabric_content[$k];
                        if($s_content == $n_content) {
                            $totalPrice += (int)$fin_blend_lycra[$k];
                        }
                    }
                    if($j == 0) { 
                        $final_fabric_content = $unique_content[$j];
                        $final_fabric_blend = $totalPrice;
                    }
                    else if($j > 0) { 
                        $final_fabric_content = $final_fabric_content.' : '.$unique_content[$j]; 
                        $final_fabric_blend = $final_fabric_blend.' : '.$totalPrice; 
                    }

                }

                if($lycra == '' || $lycra == '0' || $lycra == 0) { }
                else {
                    $final_fabric_content = $final_fabric_content.' : Lycra';
                    $final_fabric_blend = $final_fabric_blend.' : '.$lycra;
                }

                $itemized_fabric_data[$index][6] = $final_fabric_blend;
                $itemized_fabric_data[$index][7] = $final_fabric_content;
            }
        }

        $finalValue = [];

        for ($i=0; $i < sizeof($itemized_fabric_data); $i++) {
            $combineValue = '';
            if($itemized_fabric_data[$i][2] == '' && $itemized_fabric_data[$i][17] == '')
            {
                $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $itemized_fabric_data[$i][14], $itemized_fabric_data[$i][15]
                    ];
            }
            else if($itemized_fabric_data[$i][17] != '')
            {
                $combineValue = [ '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ];
            }
            else
            {
                if(!isset($compactingData[$i])) {
                    $combineValue = [ '', '', $itemized_fabric_data[$i][2], $itemized_fabric_data[$i][3], $itemized_fabric_data[$i][4], $itemized_fabric_data[$i][5],
                            $itemized_fabric_data[$i][6], $itemized_fabric_data[$i][7],
                            $itemized_fabric_data[$i][9], $itemized_fabric_data[$i][13], '', '', $itemized_fabric_data[$i][10], '', '', '', '', $itemized_fabric_data[$i][14], 
                            $itemized_fabric_data[$i][15]
                        ];
                }
                else {
                    $combineValue = [ 'edit', '', $itemized_fabric_data[$i][2], $itemized_fabric_data[$i][3], $itemized_fabric_data[$i][4], $itemized_fabric_data[$i][5],
                            $itemized_fabric_data[$i][6], $itemized_fabric_data[$i][7],
                            $itemized_fabric_data[$i][9], $itemized_fabric_data[$i][13], $compactingData[$i]['fab_wash_req'], $compactingData[$i]['dry_set_req'], $itemized_fabric_data[$i][10], 
                            $compactingData[$i]['shrink_l'], $compactingData[$i]['shrink_w'], $compactingData[$i]['spirality_acc'], $compactingData[$i]['knit_dia_dim'], 
                            $itemized_fabric_data[$i][14], $itemized_fabric_data[$i][15]
                        ];
                }
            }
            array_push($finalValue, $combineValue);
        }

        // return $finalValue;
        
        // ******* GET THE DATA DETAILS START ********* //

        $fabric_name_sql = "SELECT id, misc_name as name FROM kn_master_fabric_misc WHERE misc_type = 3 AND status=1";
        $fabric_name_data = $this->db->query($fabric_name_sql)->result_array();

        $fabric_wet_sql = "SELECT id, fabricfinish as name FROM kn_master_fabric_finish_wet_dry WHERE processingtype = 'Wet' ";
        $fabric_wet_data = $this->db->query($fabric_wet_sql)->result_array();

        $fabric_dry_sql = "SELECT id, fabricfinish as name FROM kn_master_fabric_finish_wet_dry WHERE processingtype = 'Dry' ";
        $fabric_dry_data = $this->db->query($fabric_dry_sql)->result_array();

        $perData = [
            [ 'id'=> 'Nill', 'name' => 'Nill' ]
        ];
        for($i = 1; $i <= 20; $i++)
        {
            $per = [ 'id'=> '< '.$i.'%', 'name' => '< '.$i.'%' ];
            array_push($perData, $per);
        }
        // $lastData = [ 'id'=> 'Above 20%', 'name' => 'Above 20%' ];
        // array_push($perData, $lastData);

        // ******* GET THE COLUMN START ********* //

        $column = [
            ['title' => "mode", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "Combo", 'width' => '8%', 'align' => 'left', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Colour", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Garment Parts", 'width' => '8%', 'align'=> 'left', 'readOnly' => true],
            ['title' => "Fabric Blend (%)", 'width' => '8%', 'aligh' => 'left', 'readOnly' => true],
            ['title' => "Fabric Content", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Fabric Name", 'width' => '8%', 'align'=> 'center', 'type' => 'dropdown', 'source'=> $fabric_name_data, 'readOnly' => true],
            ['title' => "Dyeing\n Type", 'width' => '10%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Fabric Washing\n Requirement", 'width' => '10%', 'align'=> 'center', 'type'=> 'dropdown', 'source'=> $fabric_wet_data, 'readOnly' => true],
            ['title' => "Dry. / Comp. / Heat\n Set. Requirement", 'width' => '10%', 'align'=> 'center', 'type'=> 'dropdown', 'source'=> $fabric_dry_data, 'readOnly' => true],
            ['title' => "Finishing\n GSM", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Shrink.\n Acc. ( L )", 'width' => '8%', 'align'=> 'center', 'type'=> 'dropdown', 'source'=> $perData, 'readOnly' => true],
            ['title' => "Shrink.\n Acc. ( W )", 'width' => '8%', 'align'=> 'center', 'type'=> 'dropdown', 'source'=> $perData, 'readOnly' => true],
            ['title' => "Spirality\n Acc.", 'width' => '8%', 'align'=> 'center', 'type'=> 'dropdown', 'source'=> $perData, 'readOnly' => true],
            ['title' => "Knit. DIA / DIM\n (W*H)", 'width' => '8%', 'align'=> 'center', 'readOnly' => true],
            ['title' => "Reqd. Fin. DIA /\n DIM (W*H)", 'width' => '10%', 'align'=> 'center', 'readOnly'=> true],
            ['title' => "Unit of\n Measure", 'width' => '8%', 'align'=> 'center', 'readOnly'=> true],
        ];

        // ******* GET THE COLUMN ENDS ********* //

        $output['column'] = $column;
        $output['data'] = $finalValue;
        return $output;
    }

    // ******** FABRIC WASHING COMPACTING & HEAT SETTING DETAILS ENDS HERE ********* /

    // ************************ COMPACTING ENDS HERE ************************* /

    public function getAllRequestReceivedListt() {
        $sql = "SELECT a.*, c.brandname, b.orderenqrefno
            FROM tbl_request as a 
            inner join kn_order_enquiry as b on a.enquiry_id=b.id
            inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
            WHERE a.flag=1 AND a.mgmt_approval=1 AND a.deprt_approval=0";
	    $result = $this->db->query($sql)->result();
	    return $result;
    }

    public function getAllQAListt() {
        $sql = "SELECT a.*, c.brandname, b.orderenqrefno
            FROM tbl_request as a 
            inner join kn_order_enquiry as b on a.enquiry_id=b.id
            inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
            WHERE a.flag=1 AND a.mgmt_approval=1 AND a.deprt_approval=1";
	    $result = $this->db->query($sql)->result();
	    return $result;
    }

    public function updateDeptAuthorizationn($id, $req_status) {
        $requestValue['req_status'] = $req_status;
        $requestValue['deprt_approval'] = $req_status;
        $requestValue['log'] = LOGTIME;
        $this->db->where('request_id', $id);
        $this->db->update('tbl_request', $requestValue);
    }

    public function updateQADetailss($id, $dep_remarks) {
        $requestValue['dep_remarks'] = $dep_remarks;
        $requestValue['log'] = LOGTIME;
        $this->db->where('request_id', $id);
        $this->db->update('tbl_request', $requestValue);
    }

    public function sendYarnReqq($data, $id, $req_id) {
        $updateValue = [];
        foreach ($data as $key => $value) {
            if($value[2] == true)
            {
                array_push($updateValue, $value);
            }
        }

        foreach ($updateValue as $key => $value) {
            $this->db->where('fab_yarn_req_id', $value[1]);
            $this->db->update('tbl_fab_yarn_requirement', array("req_status" => 1));
        }
    }

    // YARN DRAFT PI

    public function getDraftPIRequestDetailss($enqId, $reqId) {

        $sql = "SELECT * FROM tbl_fab_yarn_requirement
                WHERE request_id = " . $reqId . " AND req_status = 1";
        $data = $this->db->query($sql)->result_array();

        $yarn_data = $this->getYarnRequirementDetailss($enqId);
        $yarndata = $yarn_data['data'];

        $finData = [];
        foreach ($yarndata as $key => $value) {
            foreach ($data as $dkey => $dvalue) {
                if($value[1] == $dvalue['fab_yarn_req_id']) {
                    array_push($finData, $value);
                }
            }
        }

       // $vendor_sql = "SELECT *, yarnvendor as name FROM kn_master_yarn_vendor";
        $vendor_sql = "SELECT *, vendor_name as name FROM ".KN_MASTER_YARNVENDOR." WHERE status=1";
        $vendor_data = $this->db->query($vendor_sql)->result_array();

        $withinResult = $interResult = $importsResult = [];

        $vendor_id = '';

        foreach ($finData as $key => $value)
        {

            $withinResult[$key] = [ 'add', $value[1], $value[2], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], 
                    $value[10], '', '', '', '', '', '', '', '', ''
            ];

            $interResult[$key] = [ 'add', $value[1], $value[2], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], 
                    $value[10], '', '', '', '', ''
            ];

            $importsResult[$key] = [ 'add', $value[1], $value[2], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], 
                    $value[10], '', '', ''
            ];

        }

        $modeOfShipment = [ 'ON-LINE', 'CHEQUE' ];
        $currencyList = unserialize(ARRCURRENCYLIST);

        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $yarn_req_sql = "SELECT id, yarnsplreq as name FROM kn_master_yarn_spl_req WHERE status=1";
        $yarn_req_data = $this->db->query($yarn_req_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $output['withinStateDetails'] = $withinResult;
        $output['interStateDetails'] = $interResult;
        $output['importStateDetails'] = $importsResult;
        $output['advancepaiddetails'] = [];
        $output['vendor_data'] = $vendor_data;
        $output['yarn_blend_data'] = $yarn_blend_data;
        $output['yarn_content_data'] = $yarn_content_data;
        $output['yarn_req_data'] = $yarn_req_data;
        $output['yarn_count_data'] = $yarn_count_data;
        $output['fullData'] = $data;
        $output['modeOfShipment'] = $modeOfShipment;
        $output['currencyList'] = $currencyList;
        return $output;

    }

    // ********** UPDATE PURCHASE INDENT STARTS HERE *********** /

    public function updatePurchaseIndentt($data, $enqId, $reqId, $pi_cutoff_dt, $purchase_dept_note, $mode, $pur_req_data, $vId, $slt, $pt, $reqyarnId)
    {
        $requestData['pi_appl_req_date_time'] = $this->mysqldatetime;
        $requestData['pi_appl_cutoff_date_time'] = $pi_cutoff_dt;
        $requestData['purchase_dept_notes'] = $purchase_dept_note;
        $requestData['mode'] = $mode;
        $requestData['vendor_id'] = $vId;
        $requestData['supply_lead_time'] = $slt;
        $requestData['payment_terms'] = $pt;
        $requestData['req_status'] = 1;
        $requestData['log'] = LOGTIME;
        $this->db->where('request_yarn_id', $reqyarnId);
        $this->db->update('tbl_request_yarn', $requestData);

        foreach($data as $key => $value) {
            $requestPayment['enquiry_id'] = $enqId;
            $requestPayment['request_yarn_id'] = $reqyarnId;
            $requestPayment['vendor_id'] = $value[1];
            $requestPayment['proforma_no'] = $value[2];
            $requestPayment['proforma_date'] = $value[3];
            $requestPayment['proforma_value'] = $value[4];
            $requestPayment['qyoted_currency'] = $value[5];
            $requestPayment['mode_of_payment'] = $value[6];
            $requestPayment['pay_by_date'] = $value[7];
            $requestPayment['amount_payable'] = $value[8];
            $requestPayment['currency'] = $value[9];
            // $requestPayment['vendor_bank_name'] = $value[10];
            $requestPayment['vendor_bank_name'] = '';
            // $requestPayment['acc_no'] = $value[11];
            $requestPayment['acc_no'] = '';
            // $requestPayment['ifsc'] = $value[12];
            $requestPayment['ifsc'] = '';
            // $requestPayment['shift_code'] = $value[13];
            $requestPayment['shift_code'] = '';
            $requestPayment['log'] = LOGTIME;
            $this->db->insert('tbl_request_yarn_payment', $requestPayment);
        }
        
        foreach($pur_req_data as $key => $value) {
            $purchaseIndent['enquiry_id'] = $enqId;
            $purchaseIndent['request_yarn_id'] = $reqyarnId;
            $purchaseIndent['fab_yarn_req_id'] = $value[1];
            $purchaseIndent['req_qty'] = $value[10];
            $purchaseIndent['plan_qty'] = $value[11];
            $purchaseIndent['log'] = LOGTIME;
            if($mode == 'within')
            {
                $purchaseIndent['rate_per_kg'] = $value[12];
                $purchaseIndent['amount'] = $value[13];
                $purchaseIndent['gst'] = $value[14];
                $purchaseIndent['cgst'] = $value[15];
                $purchaseIndent['cgst_value'] = $value[16];
                $purchaseIndent['sgst'] = $value[17];
                $purchaseIndent['sgst_value'] = $value[18];
                $purchaseIndent['sub_total'] = $value[19];
            }
            else if($mode == 'inter')
            {
                $purchaseIndent['rate_per_kg'] = $value[12];
                $purchaseIndent['amount'] = $value[13];
                $purchaseIndent['igst'] = $value[14];
                $purchaseIndent['igst_value'] = $value[15];
                $purchaseIndent['sub_total'] = $value[16];
            }
            else if($mode == 'imports')
            {
                $purchaseIndent['currency'] = $value[12];
                $purchaseIndent['rate_per_kg'] = $value[13];
                $purchaseIndent['amount'] = $value[14];
                $purchaseIndent['sub_total'] = $value[15];
            }
            $this->db->insert('tbl_request_yarn_pi', $purchaseIndent);
        }

    }

    // ********** UPDATE PURCHASE INDENT ENDS HERE *********** /

    // REQUEST SENT LIST DETAILS

    public function getReqSentListt() {
        $sql = "SELECT aa.*, a.*, c.brandname, b.orderenqrefno FROM tbl_request_yarn aa
                INNER JOIN tbl_request as a ON a.request_id = aa.request_id
                INNER JOIN kn_order_enquiry as b ON a.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c ON b.brandId=c.id
                WHERE a.type=5 AND aa.req_status = 1 AND a.mgmt_approval=1 AND a.deprt_approval=1 AND a.flag=1";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function getReqSentListDetailss($enqId, $reqYarnId) {

        $sql = "SELECT * FROM tbl_fab_yarn_requirement a 
                INNER JOIN tbl_request_yarn_pi b ON a.fab_yarn_req_id=b.fab_yarn_req_id
                WHERE a.request_yarn_id = " . $reqYarnId . " ";
        $data = $this->db->query($sql)->result_array();

        $req_payment_sql = "SELECT * FROM tbl_request_yarn a
                INNER JOIN tbl_request_yarn_payment b ON a.request_yarn_id = b.request_yarn_id
                INNER JOIN tbl_request c ON a.request_id = c.request_id
                WHERE a.request_yarn_id = " . $reqYarnId . " ";
        $req_payment_data = $this->db->query($req_payment_sql)->result_array();

        $yarn_data = $this->getYarnRequirementDetailss($enqId);
        $yarndata = $yarn_data['data'];

        $finData = [];
        foreach ($yarndata as $key => $value) {
            foreach ($data as $dkey => $dvalue) {
                if($value[1] == $dvalue['fab_yarn_req_id']) {
                    array_push($finData, $value);
                }
            }
        }

       // $vendor_sql = "SELECT *, yarnvendor as name FROM kn_master_yarn_vendor";
        $vendor_sql = "SELECT *, vendor_name as name FROM ".KN_MASTER_YARNVENDOR." WHERE status=1";
        $vendor_data = $this->db->query($vendor_sql)->result_array();

        $withinResult = $interResult = $importsResult = [];

        $vendor_id = '';

        foreach ($finData as $key => $value)
        {

            $withinResult[$key] = [ 'add', $value[1], $value[2], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], 
                    $value[10], $data[$key]['plan_qty'], $data[$key]['rate_per_kg'], $data[$key]['amount'], $data[$key]['gst'], 
                    $data[$key]['cgst'], $data[$key]['cgst_value'], $data[$key]['sgst'], $data[$key]['sgst_value'], $data[$key]['sub_total']
            ];

            $interResult[$key] = [ 'add', $value[1], $value[2], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], 
                    $value[10], $data[$key]['plan_qty'], $data[$key]['rate_per_kg'], $data[$key]['amount'], $data[$key]['igst'], $data[$key]['igst_value'],
                    $data[$key]['sub_total']
            ];

            $importsResult[$key] = [ 'add', $value[1], $value[2], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], 
                    $value[10], $data[$key]['plan_qty'], $data[$key]['currency'], $data[$key]['rate_per_kg'], $data[$key]['sub_total']
            ];

        }

        $purchaseVendorData = [];

        foreach ($req_payment_data as $key => $value) {
            $purchaseVendorData[$key] = [ $value['request_yarn_id'], $value['vendor_id'], $value['proforma_no'], $value['proforma_date'],
                $value['proforma_value'], $value['qyoted_currency'], $value['mode_of_payment'], $value['pay_by_date'], $value['amount_payable'], 
                $value['currency'], $value['vendor_bank_name'], $value['acc_no'], $value['ifsc'], $value['shift_code']
            ];

            $advancepaiddetails[$key] = [ $value['request_yarn_id'], $value['vendor_id'], $value['vendor_bank_name'], $value['acc_no'], 
                $value['mode_of_payment'], $value['trans_id'], $value['trans_date'], $value['cheque_no'], $value['cheque_date'],
                $value['paid_towards'], $value['amount'], $value['currency'], $value['paid_type']
            ];
        }

        $modeOfShipment = [ 'ON-LINE', 'CHEQUE' ];
        $currencyList = unserialize(ARRCURRENCYLIST);

        $blend_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 1 AND status=1";
        $yarn_blend_data = $this->db->query($blend_sql)->result_array();

        $content_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 2 AND status=1";
        $yarn_content_data = $this->db->query($content_sql)->result_array();

        $yarn_req_sql = "SELECT id, yarnsplreq as name FROM kn_master_yarn_spl_req WHERE status=1";
        $yarn_req_data = $this->db->query($yarn_req_sql)->result_array();

        $count_sql = "SELECT auth_str as id, misc_name as name FROM kn_master_yarn_misc WHERE misc_type = 3 AND status=1";
        $yarn_count_data = $this->db->query($count_sql)->result_array();

        $output['withinStateDetails'] = $withinResult;
        $output['interStateDetails'] = $interResult;
        $output['importStateDetails'] = $importsResult;
        $output['advancepaiddetails'] = $advancepaiddetails;
        $output['vendor_data'] = $vendor_data;
        $output['yarn_blend_data'] = $yarn_blend_data;
        $output['yarn_content_data'] = $yarn_content_data;
        $output['yarn_req_data'] = $yarn_req_data;
        $output['yarn_count_data'] = $yarn_count_data;
        $output['fullData'] = $data;
        $output['modeOfShipment'] = $modeOfShipment;
        $output['currencyList'] = $currencyList;
        $output['req_payment_data'] = $req_payment_data;
        $output['purchaseVendorData'] = $purchaseVendorData;
        return $output;

    }

    // REQUEST SENT LIST DETAILS

    // YARN MANAGEMENT PI APPROVAL LIST

    public function getYarnMgmtPIApprovalListt()
    {
        $sql = "SELECT aa.*, a.*, c.brandname, b.orderenqrefno FROM tbl_request_yarn aa
                INNER JOIN tbl_request as a ON a.request_id = aa.request_id
                INNER JOIN kn_order_enquiry as b ON a.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c ON b.brandId=c.id
                WHERE a.type=5 AND aa.req_status = 1 AND a.mgmt_approval=1 AND a.deprt_approval=1 AND a.flag=1 and a.subscriberid = " . $this->db->escape($this->subscriberId)."";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    public function updateYarnMgmtPIAppll($eId, $reqYarnId, $pi_appl_status, $mgmt_appl_remarks)
    {
        $this->db->where('request_yarn_id', $reqYarnId);
        $this->db->update('tbl_request_yarn', array('pi_appl_status' => $pi_appl_status, "mgmt_appl_remarks"=> $mgmt_appl_remarks,'log'=>LOGTIME));
    }

    // YARN MANAGEMENT PI APPROVAL LIST

    // YARN MANAGEMENT PI LIST

    public function getYarnMgmtPIListt()
    {
        // $sql = "SELECT aa.*, a.*, c.brandname, b.orderenqrefno,d.yarnvendor as vendorname FROM tbl_request_yarn aa
        //         INNER JOIN tbl_request as a ON a.request_id = aa.request_id
        //         INNER JOIN kn_order_enquiry as b ON a.enquiry_id=b.id
        //         INNER JOIN kn_master_brands as c ON b.brandId=c.id
        //         INNER JOIN kn_master_yarn_vendor as d ON aa.vendor_id=d.id
        //         WHERE a.type=5 AND aa.pi_appl_status = 1 AND a.mgmt_approval=1 AND a.deprt_approval=1 AND a.flag=1";
        // $data = $this->db->query($sql)->result_array();
        // return $data;
        
         $sql = "SELECT aa.*, a.*, c.brandname, b.orderenqrefno,d.vendor_name as vendorname FROM tbl_request_yarn aa
                INNER JOIN tbl_request as a ON a.request_id = aa.request_id
                INNER JOIN kn_order_enquiry as b ON a.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c ON b.brandId=c.id
                INNER JOIN ".KN_MASTER_YARNVENDOR." as d ON aa.vendor_id=d.id
                WHERE a.type=5 AND aa.pi_appl_status = 1 AND a.mgmt_approval=1 AND a.deprt_approval=1 AND a.flag=1 and a.subscriberid = " . $this->db->escape($this->subscriberId)."";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    // YARN MANAGEMENT PI LIST
    
    // YARN REQUEST RECEIVED LIST - PAYMENT

    public function updateYarnPaymentPaidDetailss($data, $eId, $rId)
    {
        foreach ($data as $key => $value) {
            $updateArray['mode_of_payment'] = $value[4];
            $updateArray['trans_id'] = $value[5];
            $updateArray['trans_date'] = $value[6];
            $updateArray['cheque_no'] = $value[7];
            $updateArray['cheque_date'] = $value[8];
            $updateArray['paid_towards'] = $value[9];
            $updateArray['amount'] = $value[10];
            $updateArray['currency'] = $value[11];
            $updateArray['paid_type'] = $value[12];
            $updateArray['log'] = LOGTIME;
            $this->db->where('request_yarn_id', $rId);
            $this->db->update('tbl_request_yarn_payment', $updateArray);
        }
    }

    // YARN REQUEST RECEIVED LIST - PAYMENT


}
