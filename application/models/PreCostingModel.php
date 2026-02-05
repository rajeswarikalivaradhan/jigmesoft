<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PreCostingModel extends CI_Model
{

    private $mysqldatetime;

    public function __construct()
    {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo   = fnGetUserLoggedInfo('1');
        $this->companyid     = $ArrUserLoggedInfo['companyid'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->userid        = $ArrUserLoggedInfo['id'];
    }

    // public function getBom($enquiry_id, $component_id, $grid_unique_id) // backup of old one before new integration
    // {
    //     $art_id  = ($grid_unique_id == 8) ? 1 : 2;
    //     $results = [
    //         'column' => [
    //             ['title' => 'Item Description', 'width' => '40%', 'type' => 'dropdown', 'source' => $this->_getBomItemDesc(), 'align' => 'center'],
    //             ['title' => 'Intake Qty.Per Garment', 'width' => '20%'],
    //             ['title'  => 'UOM', 'type'   => 'dropdown', 'width'  => '10%', 'source' =>
    //                 [
    //                     ['id' => 1, 'name' => 'Qty'],
    //                     ['id' => 2, 'name' => 'Unit'],
    //                     ['id' => 3, 'name' => 'Kg'],
    //                     ['id' => 4, 'name' => 'Pics'],
    //                     ['id' => 5, 'name' => 'Inches'],
    //                     ['id' => 6, 'name' => 'Feet'],
    //                     ['id' => 7, 'name' => 'Cms.'],
    //                     ['id' => 8, 'name' => 'Millimetres'],
    //                     ['id' => 9, 'name' => 'Meter'],
    //                     ['id' => 10, 'name' => 'Nos.'],
    //                     ['id' => 11, 'name' => 'Gross'],
    //                     ['id' => 12, 'name' => 'Dozens'],
    //                     ['id' => 13, 'name' => 'Grams'],
    //                     ['id' => 14, 'name' => 'Ounces'],
    //                     ['id' => 15, 'name' => 'Pounds'],
    //                     ['id' => 16, 'name' => 'Gallons'],
    //                     ['id' => 17, 'name' => 'Liters']
    //                 ]
    //             ],
    //             ['title' => 'Cost Per Unit (Rs)', 'width' => '20%', 'align' => 'center'],
    //             ['title' => 'Cost Per Garment (Rs)', 'width' => '20%', 'readOnly' => true, 'align' => 'center'],
    //         ],
    //         'data'   => $this->_getBomCost($enquiry_id, $component_id, $art_id)
    //     ];

    //     return $results;
    // }
     public function getBom1($enquiry_id, $component_id, $grid_unique_id)
    {
        $art_id  =  1 ;
        $results = [
            'column' => [
                ['title' => 'Item Description', 'width' => '40%', 'type' => 'dropdown', 'source' => $this->_getBom1ItemDesc(), 'align' => 'center'],
                ['title' => 'Intake Qty.Per Garment', 'width' => '20%'],
                ['title'  => 'UOM', 'type'   => 'dropdown', 'width'  => '10%', 'source' =>
                    [
                        ['id' => 1, 'name' => 'Qty'],
                        ['id' => 2, 'name' => 'Unit'],
                        ['id' => 3, 'name' => 'Kg'],
                        ['id' => 4, 'name' => 'Pics'],
                        ['id' => 5, 'name' => 'Inches'],
                        ['id' => 6, 'name' => 'Feet'],
                        ['id' => 7, 'name' => 'Cms.'],
                        ['id' => 8, 'name' => 'Millimetres'],
                        ['id' => 9, 'name' => 'Meter'],
                        ['id' => 10, 'name' => 'Nos.'],
                        ['id' => 11, 'name' => 'Gross'],
                        ['id' => 12, 'name' => 'Dozens'],
                        ['id' => 13, 'name' => 'Grams'],
                        ['id' => 14, 'name' => 'Ounces'],
                        ['id' => 15, 'name' => 'Pounds'],
                        ['id' => 16, 'name' => 'Gallons'],
                        ['id' => 17, 'name' => 'Liters']
                    ]
                ],
                ['title' => 'Cost Per Unit (Rs)', 'width' => '20%', 'align' => 'center'],
                ['title' => 'Cost Per Garment (Rs)', 'width' => '20%', 'readOnly' => true, 'align' => 'center'],
            ],
            'data'   => $this->_getBomCost($enquiry_id, $component_id, $art_id)
        ];

        return $results;
    }
    public function getBom2($enquiry_id, $component_id, $grid_unique_id)
    {
        $art_id  =  2;
        $results = [
            'column' => [
                ['title' => 'Item Description', 'width' => '40%', 'type' => 'dropdown', 'source' => $this->_getBom2ItemDesc(), 'align' => 'center'],
                ['title' => 'Intake Qty.Per Garment', 'width' => '20%'],
                ['title'  => 'UOM', 'type'   => 'dropdown', 'width'  => '10%', 'source' =>
                    [
                        ['id' => 1, 'name' => 'Qty'],
                        ['id' => 2, 'name' => 'Unit'],
                        ['id' => 3, 'name' => 'Kg'],
                        ['id' => 4, 'name' => 'Pics'],
                        ['id' => 5, 'name' => 'Inches'],
                        ['id' => 6, 'name' => 'Feet'],
                        ['id' => 7, 'name' => 'Cms.'],
                        ['id' => 8, 'name' => 'Millimetres'],
                        ['id' => 9, 'name' => 'Meter'],
                        ['id' => 10, 'name' => 'Nos.'],
                        ['id' => 11, 'name' => 'Gross'],
                        ['id' => 12, 'name' => 'Dozens'],
                        ['id' => 13, 'name' => 'Grams'],
                        ['id' => 14, 'name' => 'Ounces'],
                        ['id' => 15, 'name' => 'Pounds'],
                        ['id' => 16, 'name' => 'Gallons'],
                        ['id' => 17, 'name' => 'Liters']
                    ]
                ],
                ['title' => 'Cost Per Unit (Rs)', 'width' => '20%', 'align' => 'center'],
                ['title' => 'Cost Per Garment (Rs)', 'width' => '20%', 'readOnly' => true, 'align' => 'center'],
            ],
            'data'   => $this->_getBomCost($enquiry_id, $component_id, $art_id)
        ];

        return $results;
    }

    public function getcmtcipCost($enquiry_id, $component_id)
    {
        $operation_desc = $this->db->select('id,mic_name as name')->get_where('tbl_cmt_cip_master', array('mic_type' => 1, 'status' => 1))->result_array();
        $operation_type = $this->db->select('id,mic_name as name')->get_where('tbl_cmt_cip_master', array('mic_type' => 2, 'status' => 1))->result_array();

        $results = [
            'column' => [
                ['title' => 'Operation Description', 'width' => '30%', 'type' => 'dropdown', 'source' => $operation_desc],
                ['title' => 'Operation Type', 'width' => '25%', 'type' => 'dropdown', 'source' => $operation_type],
                ['title' => 'Cost Per Operation (Rs)', 'width' => '15%', 'align' => 'center'],
                ['title' => 'No. of Operations Per Garment', 'width' => '15%', 'align' => 'center'],
                ['title' => 'CMT & CIP Cost Per Garment (Rs)', 'width' => '15%', 'align' => 'center', 'readOnly' => true],
            ],
            'data'   => $this->getCipCMTData($enquiry_id, $component_id)
        ];

        return $results;
    }

    public function getEmpCost_before($enqId ='', $component_id ='')
    {   
        //Already it is coming from KN_MASTER_TYPE_MEDIUM (old one) changed to KN_MASTER_MEDIUM_MATERIAL(new one)
        $typeMedium    = $this->db->select('id,medium_material as name')->get_where(KN_MASTER_MEDIUM_MATERIAL, array('companyid' => $this->companyid,'status'=>1))->result_array();
        $masterType    = $this->db->select('id,embellname as name')->get_where(KN_MASTER_EMBELLISHMENT_TYPE, array('companyid' => $this->companyid,'status'=>1))->result_array();
        
        $results = [
            'column' => [
                ['title' => 'Combo / Colour', 'width' => '12.5%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Artwork Name / Code', 'width' => '12.5%', 'align' => 'center'],
                ['title' => 'Type', 'type' => 'dropdown', 'width' => '12.5%', 'source' => $masterType, 'align' => 'center'],
                ['title' => 'Medium / Material', 'type' => 'dropdown', 'width' => '12.5%', 'source' => $typeMedium, 'align' => 'center'],
                ['title' => 'Grading Details', 'width' => '12.5%', 'align' => 'center'],
                ['title' => 'Size Group', 'width' => '12.5%', 'align' => 'center'],
                ['title' => 'Embellishment Cost Per Garment (Rs)', 'width' => '12.5%', 'align' => 'center'],
                ['title' => 'Order Qty. (Pcs)', 'width' => '12.5%', 'align' => 'center'],
                ['title' => 'Total Embellishment Cost (Rs)', 'width' => '12.5%', 'align' => 'center', 'readOnly' => true],
                ['title' => 'combo_id', 'width' => '0%', 'type' => 'hidden']
            ],
            'data'   => $this->getEmpCostData($enqId, $component_id)
        ];

        return $results;
    }

    public function getEmpCost($enqId, $component_id)
    {   
        //Already it is coming from KN_MASTER_TYPE_MEDIUM (old one) changed to KN_MASTER_MEDIUM_MATERIAL(new one)
        $typeMedium    = $this->db->select('id,medium_material as name')->get_where(KN_MASTER_MEDIUM_MATERIAL, array('companyid' => $this->companyid,'status'=>1))->result_array();
        $masterType    = $this->db->select('id,embellname as name')->get_where(KN_MASTER_EMBELLISHMENT_TYPE, array('companyid' => $this->companyid,'status'=>1))->result_array();
       // $combocolour    = $this->db->select('id,embellname as colour')->get_where('tbl_color_combo', array('companyid' => $this->companyid,'status'=>1))->result_array();
        $combocolour = $this->db->select('id, name as name')->from('tbl_color_combo')->where('enquiry_id', $enqId)->where('component_id', $component_id)->get()->result_array();

         foreach ($combocolour as $key => $value)
        {
            $combocolours[] = $value['name'];
        }
        $results = [
            'column' => [
                ['title' => 'Combo / Colour', 'type' => 'dropdown', 'width' => '12.5%', 'source' => $combocolours, 'align' => 'center'],
                ['title' => 'Artwork Name / Code', 'width' => '12.5%', 'align' => 'center'],
                ['title' => 'Type', 'type' => 'dropdown', 'width' => '12.5%', 'source' => $masterType, 'align' => 'center'],
                ['title' => 'Medium / Material', 'type' => 'dropdown', 'width' => '12.5%', 'source' => $typeMedium, 'align' => 'center'],
                ['title' => 'Grading Details', 'width' => '12.5%', 'align' => 'center'],
                ['title' => 'Size Group', 'width' => '12.5%', 'align' => 'center'],
                ['title' => 'Embellishment Cost Per Garment (Rs)', 'width' => '12.5%', 'align' => 'center'],
                ['title' => 'Order Qty. (Pcs)', 'width' => '12.5%', 'align' => 'center'],
                ['title' => 'Total Embellishment Cost (Rs)', 'width' => '12.5%', 'align' => 'center', 'readOnly' => true],
                ['title' => 'combo_id', 'width' => '0%', 'type' => 'hidden']
            ],
            'data'   => $this->getEmpCostData($enqId, $component_id)
        ];

        return $results;
    }

    function getEmpCostData($enqId, $component_id)
    {
        //Note:ec.type, included for new integration
        $query  = "SELECT com.name AS combo_name,com.id AS combo_id, ec.artwork_name_code,ec.type,ec.type_medium,ec.grading_details,ec.size_group,ec.emb_cost, ec.order_qty FROM tbl_components c
                   LEFT JOIN tbl_color_combo com ON com.component_id = c.id
                   LEFT JOIN tbl_avg_embellishment_cost ec ON ec.combo_id = com.id AND ec.component_id = c.id
                   WHERE c.enquiry_id = " . $enqId . " AND c.id = " . $component_id . " AND com.id=ec.combo_id";
        $data   = $this->db->query($query)->result_array();

        //print_r($data);
          
       $result = [];
       
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['combo_name'], $value['artwork_name_code'], $value['type'], $value['type_medium'], $value['grading_details'], $value['size_group'], $value['emb_cost'], $value['order_qty'], '', $value['combo_id']];
        }
        return $result;
    }

    public function getOtherExp($enquiry_id, $component_id)
    {
        $otherExp = $this->db->select('id,desc_name as name')->get_where('tbl_other_expenses_master', array('status' => 1))->result_array();
        $ArrPcsOrSet = unserialize(ARRPCSSET);
        $enquirysets=$this->db->select('pcsorset as id,totalcomponents as no_component,exporderqty as order_qty')->get_where('kn_order_enquiry',array('id'=>$enquiry_id))->result_array(); // getting pcs_set from enquiry
        $pcs_set_name=$ArrPcsOrSet[$enquirysets[0]['id']];
        $pcs_set_id=$enquirysets[0]['id']; // pcs_set_id from enquiry
        $pcs_set=[];
        $noofcomp=[array('id' => $enquirysets[0]['no_component'], 'name' => $enquirysets[0]['no_component'])];
        $totorderqty=[array('id' => $enquirysets[0]['order_qty'], 'name' => $enquirysets[0]['order_qty'])];
        foreach($ArrPcsOrSet as $key=>$value){
            if($key==$pcs_set_id){
               $pcs_set[]=array('id' => $key, 'name' => $value);
            }
        }
        
        $results = [
            'column' => [
                ['title' => 'Description', 'width' => '40%', 'type' => 'dropdown', 'source' => $otherExp, 'align' => 'center'],
                ['title' => 'Total Cost (Rs)', 'width' => '15%', 'align' => 'center'],
                ['title' => 'Total Order Qty.', 'width' => '15%','type' => 'dropdown', 'source' => $totorderqty,],
                ['title'  => 'Pcs / Set', 'width'  => '15%','align'  => '', 'type'   => 'dropdown', 'source' =>$pcs_set
                    // [
                    //     ['id' => $pcs_set_id, 'name' => $pcs_set_name] // for showing this in to dynamic from enquiry 
                    // ]
                ],
                ['title' => 'No.of Components','width' => '15%','type' => 'dropdown', 'source' =>$noofcomp, 'align' => 'center'],
                ['title' => 'Average Cost Per Garment (Rs)', 'width' => '25%', 'align' => 'center', 'readOnly' => true],
            ],
            'data'   => $this->getOtherExpData($enquiry_id, $component_id)
        ];

        return $results;
    }

    function getDyingCost($enquiry_id, $component_id)
    {
        /** Check the single or multiple color * */
        $dyingType     = $this->getDyingType($enquiry_id, $component_id);
        $check         = isset($dyingType[0]['dying_type']) ? $dyingType[0]['dying_type'] : '';
        $results       = [];
        $blend_array   = [];
        $content_array = [];
        $counts_array  = [];
        $garment_parts = [];
        
        if ($check == 2)
        {
            $DropDownData = $this->getDyingDropDownData($enquiry_id, $component_id);
            /* foreach ($DropDownData as $key => $value)
            {
                $garment_parts[$key]    = ['id' => $value['grament_id'], 'name' => $value['grament_name']];
                $blend_array[$key]   = ['id' => $value['blend_id'], 'name' => $value['blend_name']];
                $content_array[$key] = ['id' => $value['content_id'], 'name' => $value['content_name']];
                $counts_array[$key]  = ['id' => $value['counts_id'], 'name' => $value['count_name']];
            } */
            
            $garment_query = "SELECT c.garment_parts_id AS id, g.gpdname as name FROM tbl_yarn_cost c JOIN kn_master_garment_part_desc g ON g.id = c.garment_parts_id 
                             WHERE c.enquiry_id = " . $enquiry_id . " AND c.component_id = " . $component_id . " GROUP BY c.garment_parts_id";
            $garment_parts = $this->db->query($garment_query)->result_array();

            $blend_query = "SELECT c.blend_id as id, m.misc_name as name FROM  tbl_yarn_cost c JOIN kn_master_yarn_misc m ON c.blend_id = m.id 
                           WHERE c.enquiry_id = " . $enquiry_id . " AND c.component_id = " . $component_id . " GROUP BY c.blend_id";
            $blend_array = $this->db->query($blend_query)->result_array();

            $content_query = "SELECT c.content_id as id, m.misc_name as name FROM  tbl_yarn_cost c JOIN kn_master_yarn_misc m ON c.content_id = m.id 
                          WHERE c.enquiry_id = " . $enquiry_id . " AND c.component_id = " . $component_id . " GROUP BY c.content_id;";
            $content_array = $this->db->query($content_query)->result_array();

            $counts_query = "SELECT c.counts_id as id, m.misc_name as name FROM  tbl_yarn_cost c JOIN kn_master_yarn_misc m ON c.counts_id = m.id 
                          WHERE c.enquiry_id = " . $enquiry_id . " AND c.component_id = " . $component_id . " GROUP BY c.counts_id";
            $counts_array  = $this->db->query($counts_query)->result_array(); 

            $sp_request_query = "SELECT t.id AS id, t.dsrname AS name FROM kn_master_dyeing_special_request t WHERE t.companyid = " . $this->companyid . " AND t.`status` = 1";
            $sp_data          = $this->db->query($sp_request_query)->result_array();
        }

        // *** GET GARMMENT PIECE WEIGHT *** //
        // $getGarmentWeight = $this->getGarmentData($enquiry_id, $component_id);
        // print_r($getGarmentWeight);

        foreach ($dyingType as $key => $value)
        {
            if ($value['dying_type'] == 2)
            {
                $grid_data = $this->getDyingData($enquiry_id, $component_id, $value['comboid']);
                $results[] = [
                    'data'            => $grid_data,
                    'combo_name'      => $value['name'],
                    'combo_id'        => $value['comboid'],
                    'garment_parts'   => $garment_parts,
                    'blend'           => $blend_array,
                    'content'         => $content_array,
                    'counts'          => $counts_array,
                    'sp_data'         => $sp_data,
                    'allDropDownData' => $DropDownData,
                ];
            }
        }
        return $results;
    }

    public function getdyeing_fabric_process($enquiry_id, $component_id)
    {
       

        $combocolour = $this->db->select('id, name as name')->from('tbl_color_combo')->where('enquiry_id', $enquiry_id)->where('component_id', $component_id)->get()->result_array();

        
        foreach ($combocolour as $key => $value)
        {
            $combocolours[] = $value['name'];
        }

        if (count($combocolours) > 1) {
         $combocolours[] = 'All'; // Static value

          } else {
   
          } 


 // 
       // $finish = $this->db->select('id, fabricfinish,processingtype')->from('kn_master_fabric_finish_wet_dry')->where('status', '1')->get()->result_array();
          
        //$finish = $this->db->select('id, fabricfinish as name')->from('kn_master_fabric_finish_wet_dry')->where('status', '1')->get()->result_array();

        // foreach ($finish as $key => $value)
        // {
        //     $finishdata[] = $value['name'];
        // }
        $finish = $this->portList();
       
         $i = 0;
        $ArrFnlList = array();
       
        foreach ($finish as $ObjPort) {
            
          
             $ArrFnlList[$i]['id'] = $ObjPort->id; // must match the value stored in the data
             $ArrFnlList[$i]['name'] = $ObjPort->fabricfinish;
             $ArrFnlList[$i]['pcntry'] = $ObjPort->processingtype;

             //$i++;
            $i = $i + 1;
        }
        // file_put_contents("error_log", print_r($finish, true));



        $results   = [
            'column'    => [
                ['title' => 'Combo / Colour', 'type' => 'dropdown', 'width' => '25%',  'source' => $combocolours, 'align' => 'center'],
                ['title' => 'Process Description', 'type' => 'dropdown', 'width' => '25%',  'source' => $ArrFnlList, 'align' => 'left'],
                ['title' => 'Process Type'  , 'width' => '25%', 'readOnly' => true,  'align' => 'center'],
                ['title' => 'Processing Cost Per Kg (Rs)', 'width' => '25%', 'align' => 'center'],
                ['title' => 'combo_id', 'width' => '0%', 'type' => 'hidden']
               
            ],
        'data'      => $this->getdyeing_fabric_processdata($enquiry_id, $component_id)
        ];
      

        return $results;
    }

     public function portList()
    {   //$VarSqlPort = $this->db->select('id, fabricfinish,processingtype')->from('kn_master_fabric_finish_wet_dry')->where('status', '1');

        $VarSqlPort = "SELECT  id,fabricfinish,processingtype from kn_master_fabric_finish_wet_dry where  status = 1";
        
        //$VarSqlPort = "SELECT pn.id,pn.portname,pn.dateupdated,pn.status,pn.portcity,countryname FROM " . KN_MASTER_PORT . " AS pn INNER JOIN " . KN_COUNTRIES . " AS c ON pn.portcountry = c.id";
        $ObjResult = $this->db->query($VarSqlPort);
        return $ObjResult->result();
    }

     public function getdyeing_fabric_processdata($enquiry_id, $component_id)
    {

         $result = [];
         $query  = "SELECT colour_combo,process_desc,process_type,process_cost from tbl_avg_dyeing_fabric_processing WHERE enquiry_id = " . $enquiry_id . " AND component_id = " . $component_id . "";
         $data   = $this->db->query($query)->result_array();

       
       
         foreach ($data as $key => $value)
        {
            $result[$key] = [$value['colour_combo'],$value['process_desc'],$value['process_type'],$value['process_cost']];
        }

        //print_r($result);
      
        
        return $result;
       
        
    }

     public function getDyingCostAvg($enquiry_id, $component_id)
    {
        $dyingType = $this->getDyingType($enquiry_id, $component_id);
        $check     = isset($dyingType[0]['dying_type']) ? $dyingType[0]['dying_type'] : '';
        $readonly  = ($check == 2) ? true : false;
        $mode      = $readonly ? 'Combo' : 'Color';
        $results   = [
            'column'    => [
                ['title' => 'Combo / Colour', 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => ($readonly ? 'Prop.' : '') . ' Dyeing' . PHP_EOL . 'Cost Per Kg (Rs)', 'width' => '10%', 'readOnly' => $readonly, 'align' => 'center'],
                ['title' => 'Wet Processing' . PHP_EOL . 'Cost Per Kg (Rs)', 'width' => '10%', 'readOnly' => true,'align' => 'center'],
                ['title' => 'Dry Processing' . PHP_EOL . 'Cost Per Kg (Rs)', 'width' => '10%', 'readOnly' => true,'align' => 'center'],
                ['title' => 'Finishing Processing' . PHP_EOL . 'Cost Per Kg (Rs)', 'width' => '12%', 'readOnly' => true,'align' => 'center'],
                ['title' => 'Dyeing & Processing' . PHP_EOL . 'Cost Per Kg (Rs)', 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Average Wgt.' . PHP_EOL . 'Per Gar. (Kg)', 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Order Qty' . PHP_EOL . ' Per ' . $mode . ' (Pcs)', 'width' => '10%', 'align' => 'center'],
                ['title' => 'Fabric Consumption' . PHP_EOL . 'Per ' . $mode . ' (Kg)', 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Dying & Process. Cost' . PHP_EOL . 'Per ' . $mode . ' (Rs)', 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'comboId', 'width' => '0%', 'readOnly' => true, 'type' => 'hidden']
            ],
            'data'      => $this->getDyingCostAvgData($enquiry_id, $component_id, $check),
            'dyingType' => $check
        ];

        return $results;
    }


    

     function getDyingCostAvgData($enquiry_id, $component_id, $check)
    {
        $result = [];
       // echo("check:lllllllllllllpppppp" . $check);
        if ($check == 2)
        {
            $query = " SELECT a.combo_wise_pce_weight, a.dying_cost_per_garment,a.combo_id,a.combo_name , d.wet_processing_cost, d.dry_processing_cost, d.compact_heat_set_cost, d.order_qty_colour FROM 
                    (
                    SELECT SUM(ROUND(ROUND((c.no_feed_color * 100) / c.no_feed_repeat,3) * ROUND((weight.total * yarn.content_count_wise / 100),3) /100, 3)) AS
                    combo_wise_pce_weight, SUM(ROUND(ROUND((c.no_feed_color * 100) / c.no_feed_repeat,3) * ROUND((weight.total * yarn.content_count_wise / 100),3) /100 * c.dyeing_cost,2)) AS dying_cost_per_garment, c.combo_id, combo.name AS combo_name, c.enquiry_id,c.component_id			 
                    FROM tbl_dyeing_cost c
                    LEFT JOIN tbl_yarn_cost yarn ON yarn.garment_parts_id = c.garment_parts_id AND yarn.blend_id = c.yarn_blend_id AND yarn.content_id = c.yarn_content_id AND yarn.counts_id = c.yarn_counts_id 
                    AND yarn.enquiry_id = c.enquiry_id AND yarn.component_id = c.component_id
                    LEFT JOIN (
                    SELECT w.garm_parts_id, ROUND(CAST(SUM(m.`values`) AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id),3) AS total
                    FROM tbl_garment_piece_weight w
                    LEFT JOIN tbl_garment_piece_weight_mapping m ON w.id = m.garment_piece_weight_id
                    WHERE w.enquiry_id = " . $enquiry_id . " AND w.component_id = " . $component_id . "
                    GROUP BY m.garment_piece_weight_id
                    ) AS weight ON weight.garm_parts_id = c.garment_parts_id
                    LEFT JOIN tbl_color_combo combo ON combo.id = c.combo_id
                    WHERE c.enquiry_id = " . $enquiry_id . " AND c.component_id = " . $component_id . "
                    GROUP BY c.combo_id ) a 
                    LEFT JOIN tbl_avg_dyeing_processing_cost d ON d.combo_id = a.combo_id AND d.enquiry_id = a.enquiry_id AND d.component_id = a.component_id
                    WHERE a.enquiry_id = " . $enquiry_id . " AND a.component_id = " . $component_id . "";

           $query1 = " SELECT  com.name AS combo_name, com.id AS combo_id,
      COALESCE(f.process_cost, 0) + COALESCE(all_cost.process_cost, 0) AS wet_processing_cost
     FROM tbl_color_combo com LEFT JOIN (
     SELECT colour_combo, component_id, SUM(process_cost) AS process_cost
    FROM tbl_avg_dyeing_fabric_processing WHERE colour_combo != 'All' AND process_type = 'Wet'
    GROUP BY colour_combo, component_id ) f ON com.name = f.colour_combo AND com.component_id = f.component_id
     LEFT JOIN (SELECT component_id, SUM(process_cost) AS process_cost
    FROM tbl_avg_dyeing_fabric_processing WHERE colour_combo = 'All'AND process_type = 'Wet'
    GROUP BY component_id ) all_cost ON com.component_id = all_cost.component_id WHERE com.component_id = " . $component_id . ";";

        $query2 = "SELECT  com.name AS combo_name, com.id AS combo_id,COALESCE(f.process_cost, 0) + COALESCE(all_cost.process_cost, 0) AS dry_processing_cost
                    FROM tbl_color_combo com LEFT JOIN ( SELECT colour_combo, component_id, SUM(process_cost) AS process_cost
                    FROM tbl_avg_dyeing_fabric_processing WHERE colour_combo != 'All' AND process_type = 'Dry'
                    GROUP BY colour_combo, component_id ) f ON com.name = f.colour_combo AND com.component_id = f.component_id
                    LEFT JOIN (SELECT component_id, SUM(process_cost) AS process_cost
    FROM tbl_avg_dyeing_fabric_processing
    WHERE colour_combo = 'All'
      AND process_type = 'Dry'
    GROUP BY component_id
) all_cost ON com.component_id = all_cost.component_id
WHERE com.component_id = " . $component_id . ";
";

    $query3 = "SELECT com.name AS combo_name,com.id AS combo_id,
            COALESCE(f.process_cost, 0) + COALESCE(all_cost.process_cost, 0) AS finish_processing_cost
            FROM tbl_color_combo com LEFT JOIN ( SELECT colour_combo, component_id, SUM(process_cost) AS process_cost
            FROM tbl_avg_dyeing_fabric_processing WHERE colour_combo != 'All' AND process_type = 'Finishing'
            GROUP BY colour_combo, component_id ) f ON com.name = f.colour_combo AND com.component_id = f.component_id
            LEFT JOIN ( SELECT component_id, SUM(process_cost) AS process_cost
             FROM tbl_avg_dyeing_fabric_processing  WHERE colour_combo = 'All'
             AND process_type = 'Finishing' GROUP BY component_id ) all_cost ON com.component_id = all_cost.component_id
            WHERE com.component_id = " . $component_id . "; ";



           
              $data = $this->db->query($query)->result_array();
              $data1 = $this->db->query($query1)->result_array();
              $data2  = $this->db->query($query2)->result_array();
              $data3  = $this->db->query($query3)->result_array();
               //echo('<pre>'); print_r($data1); 

             $wetMap = [];
foreach ($data1 as $wet) {
    $wetMap[$wet['combo_name']] = $wet['wet_processing_cost'];
}

$dryMap = [];
foreach ($data2 as $dry) {
    $dryMap[$dry['combo_name']] = $dry['dry_processing_cost'];
}

$finishMap = [];
foreach ($data3 as $finish) {
    $finishMap[$finish['combo_name']] = $finish['finish_processing_cost'];
}



            foreach ($data as $key => $value)
            {

                //echo("gggggggggggggggggg".$value['wet_processing_cost']);
                 //$wetCost = isset($wetMap[$comboName]) ? $wetMap[$comboName] : 0;
                  $wetCost = isset($wetMap[$value['combo_name']]) ? $wetMap[$value['combo_name']] : 0;
                   $dryCost = isset($dryMap[$value['combo_name']]) ? $dryMap[$value['combo_name']] : 0;
                $finishCost = isset($finishMap[$value['combo_name']]) ? $finishMap[$value['combo_name']] : 0;
                $pro_dying_cost = (1 / $value['combo_wise_pce_weight'] * $value['dying_cost_per_garment']);
                $pro_dying_cost = number_format($pro_dying_cost, 2, '.', '');
                
                $result[$key]   = [$value['combo_name'], $pro_dying_cost, $wetCost, $dryCost, $finishCost, '', $value['combo_wise_pce_weight'], $value['order_qty_colour'], '', '', $value['combo_id']];
            }

            //echo$result;
        }
        else
        {
            $sql      = "SELECT ROUND(SUM(a.firsttable),3) as total,a.component_id FROM (SELECT ROUND(CAST(SUM(m.`values`)AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id) ,3)  AS firsttable, w.component_id
                        FROM tbl_garment_piece_weight w
                        LEFT JOIN tbl_garment_piece_weight_mapping m ON w.id = m.garment_piece_weight_id
                        WHERE w.enquiry_id = " . $enquiry_id . " AND w.component_id = " . $component_id . " GROUP BY w.id) a";
            $garments = $this->db->query($sql)->result_array();
            $total    = isset($garments[0]['total']) ? $garments[0]['total'] : 0;
            
            $query = "SELECT co.NAME AS combo_name,co.id AS combo_id, d.wet_processing_cost, d.dry_processing_cost, d.compact_heat_set_cost, d.order_qty_colour, d.pro_dying_cost
                      FROM tbl_components c
                      LEFT JOIN tbl_color_combo co ON co.component_id = c.id AND co.enquiry_id = c.enquiry_id
                      LEFT JOIN tbl_avg_dyeing_processing_cost d ON d.enquiry_id = c.enquiry_id AND d.component_id = c.id AND d.dyeing_type = " . $check . " AND co.id = d.combo_id
                      WHERE c.enquiry_id = " . $enquiry_id . " AND c.id = " . $component_id . " AND c.dying_type = " . $check . "";
//             $query1 ="SELECT  p.process_type,p.colour_combo,SUM(p.process_cost) + IFNULL(all_cost.total_all_cost, 0) AS wet_processing_cost
// FROM tbl_avg_dyeing_fabric_processing p
// LEFT JOIN (
//     SELECT SUM(process_cost) AS total_all_cost
//     FROM tbl_avg_dyeing_fabric_processing
//     WHERE component_id = " . $component_id . "
//       AND process_type = 'Wet'
//       AND colour_combo LIKE '%All%'
// ) all_cost ON 1=1
// WHERE p.component_id = " . $component_id . "
//   AND p.process_type = 'Wet'
//   AND p.colour_combo NOT LIKE '%All%' -- exclude the All row from main list
// GROUP BY p.process_type, p.colour_combo";
$query1 = "
SELECT 
    com.name AS combo_name,
    com.id AS combo_id,
    COALESCE(f.process_cost, 0) + COALESCE(all_cost.process_cost, 0) AS wet_processing_cost
FROM tbl_color_combo com
LEFT JOIN (
    SELECT colour_combo, component_id, SUM(process_cost) AS process_cost
    FROM tbl_avg_dyeing_fabric_processing
    WHERE colour_combo != 'All'
      AND process_type = 'Wet'
    GROUP BY colour_combo, component_id
) f ON com.name = f.colour_combo AND com.component_id = f.component_id
LEFT JOIN (
    SELECT component_id, SUM(process_cost) AS process_cost
    FROM tbl_avg_dyeing_fabric_processing
    WHERE colour_combo = 'All'
      AND process_type = 'Wet'
    GROUP BY component_id
) all_cost ON com.component_id = all_cost.component_id
WHERE com.component_id = " . $component_id . ";
";






$query2 = "
SELECT 
    com.name AS combo_name,
    com.id AS combo_id,
    COALESCE(f.process_cost, 0) + COALESCE(all_cost.process_cost, 0) AS dry_processing_cost
FROM tbl_color_combo com
LEFT JOIN (
    SELECT colour_combo, component_id, SUM(process_cost) AS process_cost
    FROM tbl_avg_dyeing_fabric_processing
    WHERE colour_combo != 'All'
      AND process_type = 'Dry'
    GROUP BY colour_combo, component_id
) f ON com.name = f.colour_combo AND com.component_id = f.component_id
LEFT JOIN (
    SELECT component_id, SUM(process_cost) AS process_cost
    FROM tbl_avg_dyeing_fabric_processing
    WHERE colour_combo = 'All'
      AND process_type = 'Dry'
    GROUP BY component_id
) all_cost ON com.component_id = all_cost.component_id
WHERE com.component_id = " . $component_id . ";
";

$query3 = "
SELECT 
    com.name AS combo_name,
    com.id AS combo_id,
    COALESCE(f.process_cost, 0) + COALESCE(all_cost.process_cost, 0) AS finish_processing_cost
FROM tbl_color_combo com
LEFT JOIN (
    SELECT colour_combo, component_id, SUM(process_cost) AS process_cost
    FROM tbl_avg_dyeing_fabric_processing
    WHERE colour_combo != 'All'
      AND process_type = 'Finishing'
    GROUP BY colour_combo, component_id
) f ON com.name = f.colour_combo AND com.component_id = f.component_id
LEFT JOIN (
    SELECT component_id, SUM(process_cost) AS process_cost
    FROM tbl_avg_dyeing_fabric_processing
    WHERE colour_combo = 'All'
      AND process_type = 'Finishing'
    GROUP BY component_id
) all_cost ON com.component_id = all_cost.component_id
WHERE com.component_id = " . $component_id . ";
";

            

              $data  = $this->db->query($query)->result_array();
              $data1  = $this->db->query($query1)->result_array();
              $data2  = $this->db->query($query2)->result_array();
              $data3  = $this->db->query($query3)->result_array();

            //print_r($data1);

$wetMap = [];
foreach ($data1 as $wet) {
    $wetMap[$wet['combo_name']] = $wet['wet_processing_cost'];
    //echo("gggggggggggggggggg".$wet['colour_combo']);
}
$dryMap = [];
foreach ($data2 as $dry) {
    $dryMap[$dry['combo_name']] = $dry['dry_processing_cost'];
}

$finishMap = [];
foreach ($data3 as $finish) {
    $finishMap[$finish['combo_name']] = $finish['finish_processing_cost'];
}
            foreach ($data as $key => $value)
            {   
                 //$wetCost = isset($wetMap[$row['combo_name']]) ? $wetMap[$row['combo_name']] : 0;
                $wetCost = isset($wetMap[$value['combo_name']]) ? $wetMap[$value['combo_name']] : 0;
                $dryCost = isset($dryMap[$value['combo_name']]) ? $dryMap[$value['combo_name']] : 0;
                $finishCost = isset($finishMap[$value['combo_name']]) ? $finishMap[$value['combo_name']] : 0;
                 //echo("gggggggggggggggggg".$value['combo_name']);
                //$result[$key] = [$value['combo_name'], $value['pro_dying_cost'], $wetCost, $dryCost, $finishCost,$value['compact_heat_set_cost'], '', $total, $value['order_qty_colour'], '', '', $value['combo_id']];
             $result[$key] = [$value['combo_name'], $value['pro_dying_cost'], $wetCost, $dryCost, $finishCost, '', $total, $value['order_qty_colour'], '', '', $value['combo_id']];
            }
        }

        //print_r($result);
        return $result;
    }

     function getDyingCostAvgData_old($enquiry_id, $component_id, $check)
    {
        $result = [];
        if ($check == 2)
        {
            $query = " SELECT a.combo_wise_pce_weight, a.dying_cost_per_garment,a.combo_id,a.combo_name , d.wet_processing_cost, d.dry_processing_cost, d.compact_heat_set_cost, d.order_qty_colour FROM 
                    (
                    SELECT SUM(ROUND(ROUND((c.no_feed_color * 100) / c.no_feed_repeat,3) * ROUND((weight.total * yarn.content_count_wise / 100),3) /100, 3)) AS
                    combo_wise_pce_weight, SUM(ROUND(ROUND((c.no_feed_color * 100) / c.no_feed_repeat,3) * ROUND((weight.total * yarn.content_count_wise / 100),3) /100 * c.dyeing_cost,2)) AS dying_cost_per_garment, c.combo_id, combo.name AS combo_name, c.enquiry_id,c.component_id			 
                    FROM tbl_dyeing_cost c
                    LEFT JOIN tbl_yarn_cost yarn ON yarn.garment_parts_id = c.garment_parts_id AND yarn.blend_id = c.yarn_blend_id AND yarn.content_id = c.yarn_content_id AND yarn.counts_id = c.yarn_counts_id 
                    AND yarn.enquiry_id = c.enquiry_id AND yarn.component_id = c.component_id
                    LEFT JOIN (
                    SELECT w.garm_parts_id, ROUND(CAST(SUM(m.`values`) AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id),3) AS total
                    FROM tbl_garment_piece_weight w
                    LEFT JOIN tbl_garment_piece_weight_mapping m ON w.id = m.garment_piece_weight_id
                    WHERE w.enquiry_id = " . $enquiry_id . " AND w.component_id = " . $component_id . "
                    GROUP BY m.garment_piece_weight_id
                    ) AS weight ON weight.garm_parts_id = c.garment_parts_id
                    LEFT JOIN tbl_color_combo combo ON combo.id = c.combo_id
                    WHERE c.enquiry_id = " . $enquiry_id . " AND c.component_id = " . $component_id . "
                    GROUP BY c.combo_id ) a 
                    LEFT JOIN tbl_avg_dyeing_processing_cost d ON d.combo_id = a.combo_id AND d.enquiry_id = a.enquiry_id AND d.component_id = a.component_id
                    WHERE a.enquiry_id = " . $enquiry_id . " AND a.component_id = " . $component_id . "";

            $data = $this->db->query($query)->result_array();
            foreach ($data as $key => $value)
            {
                $pro_dying_cost = (1 / $value['combo_wise_pce_weight'] * $value['dying_cost_per_garment']);
                $pro_dying_cost = number_format($pro_dying_cost, 2, '.', '');
                $result[$key]   = [$value['combo_name'], $pro_dying_cost, $value['wet_processing_cost'], $value['dry_processing_cost'], $value['compact_heat_set_cost'], '', $value['combo_wise_pce_weight'], $value['order_qty_colour'], '', '', $value['combo_id']];
            }
        }
        else
        {
            $sql      = "SELECT ROUND(SUM(a.firsttable),3) as total,a.component_id FROM (SELECT ROUND(CAST(SUM(m.`values`)AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id) ,3)  AS firsttable, w.component_id
                        FROM tbl_garment_piece_weight w
                        LEFT JOIN tbl_garment_piece_weight_mapping m ON w.id = m.garment_piece_weight_id
                        WHERE w.enquiry_id = " . $enquiry_id . " AND w.component_id = " . $component_id . " GROUP BY w.id) a";
            $garments = $this->db->query($sql)->result_array();
            $total    = isset($garments[0]['total']) ? $garments[0]['total'] : 0;
            
            $query = "SELECT co.NAME AS combo_name,co.id AS combo_id, d.wet_processing_cost, d.dry_processing_cost, d.compact_heat_set_cost, d.order_qty_colour, d.pro_dying_cost
                      FROM tbl_components c
                      LEFT JOIN tbl_color_combo co ON co.component_id = c.id AND co.enquiry_id = c.enquiry_id
                      LEFT JOIN tbl_avg_dyeing_processing_cost d ON d.enquiry_id = c.enquiry_id AND d.component_id = c.id AND d.dyeing_type = " . $check . " AND co.id = d.combo_id
                      WHERE c.enquiry_id = " . $enquiry_id . " AND c.id = " . $component_id . " AND c.dying_type = " . $check . "";
            
//            $query = "SELECT co.NAME AS combo_name,co.id as combo_id, d.wet_processing_cost, d.dry_processing_cost, d.compact_heat_set_cost, d.order_qty_colour, d.pro_dying_cost FROM tbl_components c 
//                      JOIN tbl_color_combo co ON co.component_id = c.id AND co.enquiry_id = c.enquiry_id
//                      LEFT JOIN tbl_avg_dyeing_processing_cost d  ON d.enquiry_id = " . $enquiry_id . "  AND d.component_id = " . $component_id . " AND d.dyeing_type = " . $check . "
//                      WHERE c.enquiry_id = " . $enquiry_id . " AND c.id = " . $component_id . " AND c.dying_type = " . $check . " GROUP BY co.id";
            $data  = $this->db->query($query)->result_array();

            foreach ($data as $key => $value)
            {   
                $result[$key] = [$value['combo_name'], $value['pro_dying_cost'], $value['wet_processing_cost'], $value['dry_processing_cost'], $value['compact_heat_set_cost'], '', $total, $value['order_qty_colour'], '', '', $value['combo_id']];
            }
        }
        return $result;
    }

    function getDyingData($enquiry_id, $component_id, $comboid)
    {
        // $query  = "SELECT d.color_name,d.garment_parts_id, d.yarn_blend_id, d.yarn_content_id,d.yarn_counts_id,d.dye_special_req_id,d.dyeing_type_id,d.dyeing_cost,d.no_feed_repeat,d.no_feed_color FROM tbl_dyeing_cost d
        //            WHERE d.enquiry_id = " . $enquiry_id . " AND d.component_id = " . $component_id . " AND d.combo_id = " . $comboid . "";
        // $data   = $this->db->query($query)->result_array();

        // $query  = "SELECT * FROM tbl_dyeing_cost d WHERE d.enquiry_id = " . $enquiry_id . " AND d.component_id = " . $component_id . " AND d.combo_id = " . $comboid . "";
        $query  = "SELECT d.*, b.misc_name as yarn_blend_name, cn.misc_name as yarn_content_name, cu.misc_name as yarn_counts_name 
                   FROM tbl_dyeing_cost d 
                   LEFT JOIN kn_master_yarn_misc b ON d.yarn_blend_id = b.id 
                   LEFT JOIN kn_master_yarn_misc cn ON cn.id = d.yarn_content_id
                   LEFT JOIN kn_master_yarn_misc cu ON cu.id = d.yarn_counts_id
                   WHERE d.enquiry_id = ".$enquiry_id." AND d.component_id = ".$component_id." AND d.combo_id = ".$comboid."";
        $data   = $this->db->query($query)->result_array();
        
        // if(sizeof($data) == 0) {

        //     $query_1  = "SELECT * FROM tbl_components a inner join tbl_color_combo as b on a.id=b.component_id 
        //                  WHERE a.enquiry_id ='$enquiry_id' and a.dying_type = 2 and b.id = '$comboid'";
        //     $data_1   = $this->db->query($query_1)->result_array();

        //     for($i=0; $i < sizeof($data_1); $i++) {
        //         $str = $data_1[$i]['name'];
        //         // $combo_name = explode("-",$str);
        //         $combo_name = explode("qwertyq",$str);
        //         foreach ($combo_name as $key => $item)
        //         {
        //             $combo['color_name'] = $item;
        //             $combo['garment_parts_id'] = '';
        //             $combo['yarn_blend_id'] = '';
        //             $combo['yarn_content_id'] = '';
        //             $combo['yarn_counts_id'] = '';
        //             $combo['dye_special_req_id'] = '';
        //             $combo['dyeing_type_id'] = '';
        //             $combo['dyeing_cost'] = '';
        //             $combo['no_feed_repeat'] = '';
        //             $combo['no_feed_color'] = '';
        //             $combo['yarn_blend_name'] = '';
        //             $combo['yarn_content_name'] = '';
        //             $combo['yarn_counts_name'] = '';
        //             array_push($data, $combo);
        //         } 
        //     }            
        // }
        // print_r($data);
        // exit();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['color_name'], $value['garment_parts_id'], $value['yarn_blend_id'], $value['yarn_content_id'], 
                             $value['yarn_counts_id'], $value['dye_special_req_id'], $value['dyeing_type_id'], $value['dyeing_cost'], 
                             $value['no_feed_repeat'], $value['no_feed_color'], 
                             $value['yarn_blend_name'], $value['yarn_content_name'], $value['yarn_counts_name']];
        }
        
        return $result;
    }

    function getDyingDropDownData($enquiry_id, $component_id)
    {
        //ROUND((weight.total * c.content_count_wise / 100),3) AS total  
        $query = "SELECT  c.garment_parts_id AS grament_id,dd.gpdname AS grament_name, c.blend_id, b.misc_name AS blend_name, c.content_id, 
        cn.misc_name AS content_name, c.counts_id, cu.misc_name AS count_name, 
        ROUND((weight.total * c.content_count_wise / 100),3) AS total  
        FROM tbl_yarn_cost c LEFT JOIN kn_master_yarn_misc b 
                    ON c.blend_id = b.id 
                    LEFT JOIN kn_master_yarn_misc cn ON cn.id = c.content_id
                    LEFT JOIN kn_master_yarn_misc cu ON cu.id = c.counts_id
                    LEFT JOIN (
                        SELECT w.garm_parts_id, ROUND(CAST(SUM(m.`values`) AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id) ,3) AS total FROM tbl_garment_piece_weight w 
                        LEFT JOIN  tbl_garment_piece_weight_mapping m 
                        ON w.id = m.garment_piece_weight_id 
                        WHERE w.enquiry_id = " . $enquiry_id . " AND w.component_id = " . $component_id . "
                        GROUP BY m.garment_piece_weight_id
                                   )   AS weight
                    ON weight.garm_parts_id = c.garment_parts_id
                    LEFT JOIN kn_master_garment_part_desc dd ON dd.id = c.garment_parts_id
                    WHERE c.enquiry_id = " . $enquiry_id . " AND c.component_id = " . $component_id . "";
        $data  = $this->db->query($query)->result_array();
        return $data;
    }

    function getDyingType($enquiry_id, $component_id)
    {
        $query = "SELECT c.dying_type, co.name, c.id AS compid, co.id AS comboid FROM tbl_components c JOIN tbl_color_combo co ON co.component_id = c.id WHERE c.enquiry_id = " . $enquiry_id . " AND c.id = " . $component_id . "";
        $data  = $this->db->query($query)->result_array();
        return $data;
    }

    function _getBomItemDesc() // backup of old one before integration
    {
        $this->db->select('id,bomitemdesc as name');
        $ArrRes = $this->db->get_where('kn_master_bom', ['status' => 1, 'companyid' => $this->companyid]);
        return $ArrRes->result_array();
    }
    function _getBom1ItemDesc()
    {
        $this->db->select('id,description as name');
        $ArrRes = $this->db->get_where(KN_BOM1_MASTER, ['status' => 1, 'companyid' => $this->companyid,'type'=>1]);
        return $ArrRes->result_array();
    }
    function _getBom2ItemDesc()
    {
        $this->db->select('id,description as name');
        $ArrRes = $this->db->get_where(KN_BOM_MASTER, ['status' => 1, 'companyid' => $this->companyid,'type'=>1]);
        return $ArrRes->result_array();
    }
    function _getBomCost($enquiry_id, $component_id, $article_id)
    {
        $query  = "SELECT item_desc_id,intake_qty,uom_id,cost_per_unit FROM tbl_bom_cost WHERE enquiry_id = " . $enquiry_id . " AND component_id = " . $component_id . " AND article_id = " . $article_id . "";
        $data   = $this->db->query($query)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['item_desc_id'], $value['intake_qty'], $value['uom_id'], $value['cost_per_unit']];
        }
        return $result;
    }

    function getGarmentPieceWeight($enqId = '', $component_id = '')
    {
        // GET THE COLUMN
        $sizeChartType =$this->getSizeChartType($enqId);
        $sizeChart    = $this->getSizeChart($enqId);
        $selectedsizes=($sizeChartType==2)?explode(',',$sizeChart):[];  // getting customsize into array
        $customsize=[];
        if(!empty($selectedsizes) && count($selectedsizes)>0){
            foreach($selectedsizes as $k=>$v){
                $customsize[]=['size_name'=>$v]; // given index name here 
            }
        }
        //var_dump($customsize);
        $sizeMaster   = ($sizeChartType==1)?$this->getSizeMaster($sizeChart):$customsize;
        $garmentParts = $this->getGarmentParts();
        foreach ($garmentParts as $key => $value)
        {
            $parts[] = $value['gpdname'];
        }
        foreach ($sizeMaster as $key => $value)
        {
            $size[] = array('title' => $value['size_name'], 'width' => 20, 'align' => 'center');
        }
        $column     = array(
            array('title' => 'Garment Parts', 'width' => 80, 'type' => 'dropdown', 'source' => $parts, 'align' => 'center'),
            array('title' => 'Size Spec Code', 'width' => 100, 'align' => 'center'));
        $averge_wgt = array(
            array('title' => "Parts Wise Ave. Wgt. Per Gar. (Kgs)", 'width' => 100, 'readOnly' => true, 'align' => 'center'));
        $column     = array_merge($column, $size);
        $column     = array_merge($column, $averge_wgt);

        // GET THE TABLE DATA IF EXIST
        $data   = $this->getGarmentData($enqId, $component_id);
        $format = array();
        foreach ($data as $key => $value)
        {
            $sizeData     = explode(',', $value['values']);
            $column1      = array($value['gpdname'], $value['size_per_code']);
            $format[$key] = array_merge($column1, $sizeData);
            //$format[$key][count($format[$key])] = $value['parts_wise_avg'];
        }

        return $data = array('column' => $column, 'data' => $format);
    }

    function getYarnCost($enqId, $component_id)
    {
        $yarnBlend    = $this->db->select('id,misc_name as name')->get_where('kn_master_yarn_misc', array('companyid' => $this->companyid, 'misc_type' => 1))->result_array();
        $yarnContent  = $this->db->select('id,misc_name as name')->get_where('kn_master_yarn_misc', array('companyid' => $this->companyid, 'misc_type' => 2))->result_array();
        $yarnCounts   = $this->db->select('id,misc_name as name')->get_where('kn_master_yarn_misc', array('companyid' => $this->companyid, 'misc_type' => 3))->result_array();
        $yarnCounts   = $this->db->select('id,misc_name as name')->get_where('kn_master_yarn_misc', array('companyid' => $this->companyid, 'misc_type' => 3))->result_array();
        $garmentParts = $this->getStoredGarmentParts($enqId, $component_id);

        $yarnSpecial   = $this->db->select('yarnsplreq')->get_where('kn_master_yarn_spl_req', array('status' => $this->companyid, 'status' => 1))->result_array();
        $yarnSpecialValue = [];
        for($i=0; $i < sizeof($yarnSpecial); $i++) {
            array_push($yarnSpecialValue, $yarnSpecial[$i]['yarnsplreq']);
        }
        $results = [
            'column' => [
                ['title' => 'Garment Parts', 'width' => '10%', 'type' => 'dropdown', 'source' => $garmentParts, 'align' => 'center'],
                ['title' => 'Yarn Blend (%)', 'width' => '12%', 'type' => 'dropdown', 'source' => $yarnBlend, 'align' => 'center'],
                ['title' => 'Yarn Content', 'width' => '13%', 'type' => 'dropdown', 'source' => $yarnContent, 'align' => 'center'],
                ['title' => 'Yarn Count', 'type' => 'dropdown', 'width' => '8%', 'source' => $yarnCounts],
                ['title'  => 'Yarn Purchase' . PHP_EOL . 'Type', 'width'  => '8%', 'type'   => 'dropdown', 'source' =>
                    ['Greige', 'Coloured', 'Melange'], 'align'  => 'center'
                ],
                // ['title' => 'Yarn Special' . PHP_EOL . 'Req. If Any', 'width' => '7%', 'type' => 'dropdown', 'source' => ['Combed', 'Nil', 'as'], 'align' => 'center'],
                ['title' => 'Yarn Special' . PHP_EOL . 'Req. If Any', 'width' => '7%', 'type' => 'dropdown', 'source' => $yarnSpecialValue, 'align' => 'center'],
                ['title' => 'Yarn Cost' . PHP_EOL . 'Per Kg (Rs)', 'width' => '6%', 'align' => 'center'],
                ['title' => 'Parts Wise Ave.' . PHP_EOL . 'Wgt. Per Gar. (Kg)', 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Content / Count' . PHP_EOL . 'Wise Yarn (%)', 'width' => '8%', 'align' => 'center'],
                ['title' => 'Yarn Counts Wise Ave.' . PHP_EOL . 'Wgt. Per Gar. (Kg)', 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Yarn Cost Per' . PHP_EOL . 'Garment (Rs)','readOnly' => true, 'width' => '8%', 'align' => 'center'],
            ],
            'data'   => $this->getYarnCostData($enqId, $component_id)
        ];
        return $results;
    }

    function getKnittingCost($enqId, $component_id)
    {
        $fabricName    = $this->db->select('id,misc_name as name')->get_where('kn_master_fabric_misc', array('companyid' => $this->companyid, 'misc_type' => 3, 'status' => 1))->result_array();
        $knittingStyle = $this->db->select('id,misc_name as name')->get_where('tbl_knitting_style', array('company_id' => $this->companyid, 'status' => 1))->result_array();

        $results = [
            'column' => [
                ['title' => 'Garment Parts', 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Yarn Blend (%)', 'width' => '8%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Yarn' . PHP_EOL . 'Content', 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Yarn' . PHP_EOL . 'Count', 'readOnly' => true, 'width' => '8%'],
                ['title' => 'Fabric Name', 'width' => '10%', 'type' => 'dropdown', 'source' => $fabricName, 'align' => 'center'],
                ['title' => 'Finishing' . PHP_EOL . 'GSM (Kg)', 'width' => '6%', 'align' => 'center'],
                ['title' => 'Lycra' . PHP_EOL . '(%)', 'width' => '5%'],
                ['title'  => 'Lycra Feeder' . PHP_EOL . 'Type', 'width'  => '7%', 'type'   => 'dropdown', 'source' =>
                    [
                        'All Feeder', 'Alt. Feeder', 'Nil'
                    ], 'align'  => 'center'
                ],
                ['title' => 'Knitting' . PHP_EOL . 'Style', 'width' => '8%', 'type' => 'dropdown', 'source' => $knittingStyle, 'align' => 'center'],
                ['title' => 'Knitting Cost' . PHP_EOL . 'Per Kg (Rs)', 'width' => '6%', 'align' => 'center'],
                ['title' => 'Parts Wise Ave.' . PHP_EOL . 'Wgt. Per Gar. (Kg)', 'width' => '8%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Knitting Cost' . PHP_EOL . 'Per Gar. (Rs)','readOnly' => true, 'width' => '6%', 'align' => 'center'],
            ],
            'data'   => $this->getKnittingCostData($enqId, $component_id)
        ];

        return $results;
    }

    function getGarmentData($enqId, $component_id)
    {
        $query = "SELECT gpw.id,pa.gpdname,gpw.size_per_code,GROUP_CONCAT(m.VALUES ORDER BY m.id) AS 'values', gpw.parts_wise_avg FROM tbl_garment_piece_weight gpw JOIN kn_master_garment_part_desc pa 
                  ON gpw.garm_parts_id = pa.id
                  JOIN tbl_garment_piece_weight_mapping m 
                  ON m.garment_piece_weight_id = gpw.id
                  WHERE gpw.enquiry_id = " . $enqId . " AND gpw.component_id = " . $component_id . " GROUP BY gpw.id";
        $data  = $this->db->query($query)->result_array();
        return $data;
    }

    function getYarnCostData($enqId, $component_id)
    {
        $query  = "SELECT c.garment_parts_id,c.blend_id,c.content_id,c.counts_id,c.purch_type,c.special,c.cost_per_kg,c.content_count_wise, weight.total AS total FROM tbl_yarn_cost  c
                    LEFT JOIN (
                                SELECT w.garm_parts_id, ROUND(CAST(SUM(m.`values`) AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id) ,3) AS total FROM tbl_garment_piece_weight w 
                                LEFT JOIN  tbl_garment_piece_weight_mapping m 
                                ON w.id = m.garment_piece_weight_id 
                                WHERE w.enquiry_id = " . $enqId . " AND w.component_id = " . $component_id . "
                                GROUP BY m.garment_piece_weight_id
                            )   AS weight
                    ON weight.garm_parts_id = c.garment_parts_id
                    WHERE c.enquiry_id = " . $enqId . " AND c.component_id = " . $component_id . " GROUP BY c.id";
        $data   = $this->db->query($query)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['garment_parts_id'], $value['blend_id'], $value['content_id'], $value['counts_id'], $value['purch_type'], $value['special'], $value['cost_per_kg'], $value['total'], $value['content_count_wise']];
        }
        return $result;
    }

    function getKnittingCostData($enqId, $component_id)
    {
        $result = [];
        $query  = "SELECT p.gpdname,GROUP_CONCAT(a1.misc_name SEPARATOR ' / ') AS blend,GROUP_CONCAT(a2.misc_name SEPARATOR ' / ') AS content,GROUP_CONCAT(a3.misc_name SEPARATOR ' / ') AS counts,kc.fabric_name_id,kc.finishing_gsm,kc.lycra,kc.lycra_feeder_type,kc.knitting_style_id,kc.knitting_cost_per ,
                    ROUND(SUM((weight.total * yarn.content_count_wise/ 100)),3) AS part_weight_avg
                    FROM tbl_yarn_cost yarn
                    LEFT JOIN kn_master_yarn_misc a1 ON a1.id =  yarn.blend_id AND a1.misc_type = 1
                    LEFT JOIN kn_master_yarn_misc a2 ON a2.id =  yarn.content_id AND a2.misc_type = 2
                    LEFT JOIN kn_master_yarn_misc a3 ON a3.id =  yarn.counts_id AND a3.misc_type = 3
                    LEFT JOIN kn_master_garment_part_desc p ON p.id = yarn.garment_parts_id
                    LEFT JOIN tbl_knitting_cost kc ON kc.garment_parts_id = yarn.garment_parts_id AND kc.enquiry_id = " . $enqId . " AND kc.component_id = " . $component_id . "
                    LEFT JOIN tbl_garment_piece_weight w ON w.garm_parts_id = yarn.garment_parts_id AND w.enquiry_id = " . $enqId . " AND w.component_id  = " .  $component_id . "
                    LEFT JOIN (
		              SELECT w.garm_parts_id, ROUND(CAST(SUM(m.`values`)AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id) ,3) AS total, w.component_id
		              FROM tbl_garment_piece_weight w
		              LEFT JOIN tbl_garment_piece_weight_mapping m ON w.id = m.garment_piece_weight_id
		              WHERE w.enquiry_id = " . $enqId . "
		              GROUP BY m.garment_piece_weight_id
		              ) AS weight ON weight.garm_parts_id = yarn.garment_parts_id AND weight.component_id = yarn.component_id
		                   
                    WHERE yarn.enquiry_id = " . $enqId . " AND yarn.component_id = " . $component_id . "
                    GROUP BY yarn.garment_parts_id";
        $data   = $this->db->query($query)->result_array();
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['gpdname'], $value['blend'], $value['content'], $value['counts'], $value['fabric_name_id'], $value['finishing_gsm'], $value['lycra'], $value['lycra_feeder_type'], $value['knitting_style_id'], $value['knitting_cost_per'], $value['part_weight_avg']];
        }
        return $result;
    }

    function getOtherExpData($enqId, $component_id)
    {
        $query  = "SELECT desc_id,total_cost,order_qty, pcs_set, no_component from tbl_other_expenses WHERE enquiry_id = " . $enqId . " AND component_id = " . $component_id . "";
        $data   = $this->db->query($query)->result_array();
        //  $query  = "SELECT oe.desc_id,oe.total_cost,e.pcsorset as pcs_set, e.totalcomponents as no_component,e.exporderqty as order_qty
        //              FROM kn_order_enquiry e
        //              LEFT JOIN tbl_components c ON c.enquiry_id=e.id
        //              LEFT JOIN tbl_other_expenses oe ON oe.component_id=c.id
        //              WHERE e.id = " . $enqId . " AND c.id = " . $component_id . "";
        // $data   = $this->db->query($query)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['desc_id'], $value['total_cost'], $value['order_qty'], $value['pcs_set'], $value['no_component']];
        }
        return $result;
    }
    function getOtherExpData_backumyself($enqId, $component_id)
    {
        $query  = "SELECT desc_id,total_cost,order_qty, pcs_set, no_component from tbl_other_expenses WHERE enquiry_id = " . $enqId . " AND component_id = " . $component_id . "";
        $data   = $this->db->query($query)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['desc_id'], $value['total_cost'], $value['order_qty'], $value['pcs_set'], $value['no_component']];
        }
        return $result;
    }

    function getCipCMTData($enqId, $component_id)
    {
        $query  = "SELECT operation_id, operation_type, operation_cost, no_operations from tbl_cmt_cip_garment WHERE enquiry_id = " . $enqId . " AND component_id = " . $component_id . "";
        $data   = $this->db->query($query)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['operation_id'], $value['operation_type'], $value['operation_cost'], $value['no_operations']];
        }
        return $result;
    }
    
     function getFabricCostData($enqId)
    {
        $compoquery = "SELECT c.id, c.comp_name FROM tbl_components c WHERE c.draft_status=2 AND enquiry_id = " . $enqId . "";
        $combodata  = $this->db->query($compoquery)->result_array();
        
        $result = [];
        foreach ($combodata as $key => $value)
        {   
             $query1     = "SELECT ROUND(SUM(a.firsttable),3) as total,a.component_id FROM (SELECT ROUND(CAST(SUM(m.`values`)AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id) ,3)  AS firsttable, w.component_id
                    FROM tbl_garment_piece_weight w
                    LEFT JOIN tbl_garment_piece_weight_mapping m ON w.id = m.garment_piece_weight_id
                    WHERE w.enquiry_id = " . $enqId . " GROUP BY w.id,w.component_id) a  WHERE a.component_id=$value[id] GROUP BY a.component_id";
            $firsttable = $this->db->query($query1)->row();
            
            $query2      = "SELECT ROUND((1/SUM(a.avg_pce) * SUM(a.yarn_cost)),2) AS total, a.component_id FROM 
                    (SELECT weight.total, 
                    ROUND(CAST(((weight.total * ya.content_count_wise/ 100)) AS DECIMAL(10,4)),3) AS avg_pce,
                   -- commented by me due to round issue  ROUND(weight.total * ya.content_count_wise /100,3) AS avg_pce,
                   -- commented by me due to round issue ROUND(ya.cost_per_kg * ROUND((weight.total * ya.content_count_wise /100),3),2) AS yarn_cost ,
                   ROUND(ROUND(CAST(((weight.total * ya.content_count_wise/ 100)) AS DECIMAL(10,4)),3) * CAST(ya.cost_per_kg AS DECIMAL(10,4)),2) AS yarn_cost,
                    ya.component_id , ya.cost_per_kg FROM tbl_yarn_cost ya
                    LEFT JOIN (
                        SELECT w.garm_parts_id, ROUND(CAST(SUM(m.`values`)AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id) ,3) AS total, w.component_id
                        FROM tbl_garment_piece_weight w
                        LEFT JOIN tbl_garment_piece_weight_mapping m ON w.id = m.garment_piece_weight_id
                        WHERE w.enquiry_id = " . $enqId . "
                        GROUP BY m.garment_piece_weight_id
                    ) AS weight ON weight.garm_parts_id = ya.garment_parts_id AND weight.component_id = ya.component_id
                    WHERE ya.enquiry_id = " . $enqId . ") a WHERE a.component_id=$value[id] GROUP BY a.component_id";
            $secondtable = $this->db->query($query2)->row();
            
            $query3     = "SELECT 
                        -- SUM(a.part_weight_avg),SUM(a.knitting_cost_garment) ,
                        ROUND(1 / SUM(a.part_weight_avg) * SUM(a.knitting_cost_garment),2) AS total, a.component_id
                        FROM 
                        (SELECT ROUND(SUM((weight.total * yan.content_count_wise/ 100)),3)  AS part_weight_avg,
                        ROUND(ROUND(cast(SUM((weight.total * yan.content_count_wise/ 100)) AS DECIMAL(10,4)),3) * CAST(c.knitting_cost_per AS DECIMAL(10,4)),2) AS knitting_cost_garment,
                        c.component_id
                        FROM tbl_knitting_cost c
                        LEFT JOIN tbl_yarn_cost yan  ON yan.garment_parts_id = c.garment_parts_id AND yan.enquiry_id = c.enquiry_id AND yan.component_id = c.component_id
                        LEFT JOIN (
                                    SELECT w.garm_parts_id, ROUND(CAST(SUM(m.`values`)AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id) ,3) AS total, w.component_id
                                    FROM tbl_garment_piece_weight w
                                    LEFT JOIN tbl_garment_piece_weight_mapping m ON w.id = m.garment_piece_weight_id
                                    WHERE w.enquiry_id = " . $enqId . "
                                    GROUP BY m.garment_piece_weight_id
                                ) 
		                AS weight ON weight.garm_parts_id = c.garment_parts_id AND weight.component_id = c.component_id
                        WHERE c.enquiry_id = " . $enqId . "  GROUP BY c.garment_parts_id, c.component_id) a  WHERE a.component_id=$value[id] GROUP BY a.component_id";
            $thirdtable = $this->db->query($query3)->row();
            
            $fabric_table_query = "SELECT f.fabric_processing_loss, f.component_id FROM tbl_fabric_cost_garment f WHERE f.enquiry_id = " . $enqId . " AND f.component_id=$value[id]";
            $fabric_data        = $this->db->query($fabric_table_query)->row();
        
            $first_table_record  = !empty($firsttable) ? $firsttable->total : '0.00';
            $second_table_record = !empty($secondtable) ? $secondtable->total : '0.00';
            $third_table_record  = !empty($thirdtable) ? $thirdtable->total : '0.00';
            $fith_table_record   = $this->getAvgDyingCostTotal($enqId, $value['id']);
            $fabric_loss_record  = !empty($fabric_data) ? $fabric_data->fabric_processing_loss : '0.00';
            $result[$key]        = [$value['comp_name'], $second_table_record, $third_table_record, $fith_table_record, '', $first_table_record, $fabric_loss_record, '', '', $value['id']];
        }
        return $result;
    }
    
    function getFabricCostData_backup($enqId)
    {
        $compoquery = "SELECT c.id, c.comp_name FROM tbl_components c WHERE enquiry_id = " . $enqId . "";
        $combodata  = $this->db->query($compoquery)->result_array();

        $query1     = "SELECT ROUND(SUM(a.firsttable),3) as total,a.component_id FROM (SELECT ROUND(CAST(SUM(m.`values`)AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id) ,3)  AS firsttable, w.component_id
                    FROM tbl_garment_piece_weight w
                    LEFT JOIN tbl_garment_piece_weight_mapping m ON w.id = m.garment_piece_weight_id
                    WHERE w.enquiry_id = " . $enqId . " GROUP BY w.id,w.component_id) a GROUP BY a.component_id";
        $firsttable = $this->db->query($query1)->result_array();

        $query2      = "SELECT ROUND((1/SUM(a.avg_pce) * SUM(a.yarn_cost)),2) AS total, a.component_id FROM 
                    (SELECT weight.total, 
                    ROUND(CAST(((weight.total * ya.content_count_wise/ 100)) AS DECIMAL(10,4)),3) AS avg_pce,
                   -- commented by me due to round issue  ROUND(weight.total * ya.content_count_wise /100,3) AS avg_pce,
                   -- commented by me due to round issue ROUND(ya.cost_per_kg * ROUND((weight.total * ya.content_count_wise /100),3),2) AS yarn_cost ,
                   ROUND(ROUND(CAST(((weight.total * ya.content_count_wise/ 100)) AS DECIMAL(10,4)),3) * CAST(ya.cost_per_kg AS DECIMAL(10,4)),2) AS yarn_cost,
                    ya.component_id , ya.cost_per_kg FROM tbl_yarn_cost ya
                    LEFT JOIN (
                        SELECT w.garm_parts_id, ROUND(CAST(SUM(m.`values`)AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id) ,3) AS total, w.component_id
                        FROM tbl_garment_piece_weight w
                        LEFT JOIN tbl_garment_piece_weight_mapping m ON w.id = m.garment_piece_weight_id
                        WHERE w.enquiry_id = " . $enqId . "
                        GROUP BY m.garment_piece_weight_id
                    ) AS weight ON weight.garm_parts_id = ya.garment_parts_id AND weight.component_id = ya.component_id
                    WHERE ya.enquiry_id = " . $enqId . ") a GROUP BY a.component_id";
        $secondtable = $this->db->query($query2)->result_array();

        $query3     = "SELECT 
                        -- SUM(a.part_weight_avg),SUM(a.knitting_cost_garment) ,
                        ROUND(1 / SUM(a.part_weight_avg) * SUM(a.knitting_cost_garment),2) AS total, a.component_id
                        FROM 
                        (SELECT ROUND(SUM((weight.total * yan.content_count_wise/ 100)),3)  AS part_weight_avg,
                        ROUND(ROUND(cast(SUM((weight.total * yan.content_count_wise/ 100)) AS DECIMAL(10,4)),3) * CAST(c.knitting_cost_per AS DECIMAL(10,4)),2) AS knitting_cost_garment,
                        c.component_id
                        FROM tbl_knitting_cost c
                        LEFT JOIN tbl_yarn_cost yan  ON yan.garment_parts_id = c.garment_parts_id AND yan.enquiry_id = c.enquiry_id AND yan.component_id = c.component_id
                        LEFT JOIN (
                                    SELECT w.garm_parts_id, ROUND(CAST(SUM(m.`values`)AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id) ,3) AS total, w.component_id
                                    FROM tbl_garment_piece_weight w
                                    LEFT JOIN tbl_garment_piece_weight_mapping m ON w.id = m.garment_piece_weight_id
                                    WHERE w.enquiry_id = " . $enqId . "
                                    GROUP BY m.garment_piece_weight_id
                                ) 
		  AS weight ON weight.garm_parts_id = c.garment_parts_id AND weight.component_id = c.component_id
                WHERE c.enquiry_id = " . $enqId . "  GROUP BY c.garment_parts_id, c.component_id) a GROUP BY a.component_id";
        $thirdtable = $this->db->query($query3)->result_array();


        $fabric_table_query = "SELECT f.fabric_processing_loss, f.component_id FROM tbl_fabric_cost_garment f WHERE f.enquiry_id = " . $enqId . "";
        $fabric_data        = $this->db->query($fabric_table_query)->result_array();
        $result = [];
        foreach ($combodata as $key => $value)
        {
            $first_table_record  = isset($firsttable[$key]['total']) ? $firsttable[$key]['total'] : '0.00';
            $second_table_record = isset($secondtable[$key]['total']) ? $secondtable[$key]['total'] : '0.00';
            $third_table_record  = isset($thirdtable[$key]['total']) ? $thirdtable[$key]['total'] : '0.00';
            $fith_table_record   = $this->getAvgDyingCostTotal($enqId, $value['id']);
            $fabric_loss_record  = isset($fabric_data[$key]['fabric_processing_loss']) ? $fabric_data[$key]['fabric_processing_loss'] : '0.00';
            $result[$key]        = [$value['comp_name'], $second_table_record, $third_table_record, $fith_table_record, '', $first_table_record, $fabric_loss_record, '', '', $value['id']];
        }
        return $result;
    }

    function getAvgDyingCostTotal($enquiry_id, $component_id)
    {
        $dyingType = $this->getDyingType($enquiry_id, $component_id);
        $check     = isset($dyingType[0]['dying_type']) ? $dyingType[0]['dying_type'] : '';
        if ($check == 2)
        {
            $query = "SELECT 
                    SUM(e.fabric_consumption), 
                    SUM(e.actual_cost),
                    ROUND(ROUND(SUM(e.actual_cost),2) / ROUND(SUM(e.fabric_consumption),2),2) AS total
                    FROM (SELECT 
                    ROUND((1 / a.combo_wise_pce_weight * a.dying_cost_per_garment),2) AS prop_dying_cost,
                    ROUND((a.combo_wise_pce_weight * d.order_qty_colour),2) AS fabric_consumption,
                    ROUND(ROUND(ROUND((1 / a.combo_wise_pce_weight * a.dying_cost_per_garment),2) + d.wet_processing_cost + d.dry_processing_cost + d.compact_heat_set_cost,2) * ROUND((a.combo_wise_pce_weight * d.order_qty_colour),2),2) AS actual_cost,
                     d.component_id
                    FROM 
                    (
                    SELECT SUM(ROUND(ROUND((c.no_feed_color * 100) / c.no_feed_repeat,3) * ROUND((weight.total * yarn.content_count_wise / 100),3) /100, 3)) AS
                    combo_wise_pce_weight, SUM(ROUND(ROUND((c.no_feed_color * 100) / c.no_feed_repeat,3) * ROUND((weight.total * yarn.content_count_wise / 100),3) /100 * c.dyeing_cost,3)) AS dying_cost_per_garment, c.combo_id, combo.name AS combo_name, c.enquiry_id,c.component_id			 
                    FROM tbl_dyeing_cost c
                    LEFT JOIN tbl_yarn_cost yarn ON yarn.garment_parts_id = c.garment_parts_id AND yarn.blend_id = c.yarn_blend_id AND yarn.content_id = c.yarn_content_id AND yarn.counts_id = c.yarn_counts_id AND yarn.enquiry_id = c.enquiry_id
                    LEFT JOIN (
                    SELECT w.garm_parts_id, ROUND(CAST(SUM(m.`values`)AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id),3) AS total
                    FROM tbl_garment_piece_weight w
                    LEFT JOIN tbl_garment_piece_weight_mapping m ON w.id = m.garment_piece_weight_id
                    WHERE w.enquiry_id = " . $enquiry_id . " AND w.component_id = " . $component_id . "
                    GROUP BY m.garment_piece_weight_id
                    ) AS weight ON weight.garm_parts_id = c.garment_parts_id
                    LEFT JOIN tbl_color_combo combo ON combo.id = c.combo_id
                    WHERE c.enquiry_id = " . $enquiry_id . " AND c.component_id = " . $component_id . "
                    GROUP BY c.combo_id ) a 
                    LEFT JOIN tbl_avg_dyeing_processing_cost d ON d.combo_id = a.combo_id
                    WHERE d.enquiry_id = " . $enquiry_id . " AND d.component_id = " . $component_id . ") e";
        }
        else
        {
            $query = "SELECT 
                    ROUND((actual_cost / fabric_consumption),2) AS total
                    FROM (SELECT 
                    SUM(b.total * c.order_qty_colour) AS fabric_consumption,
                    ROUND(SUM(ROUND((c.pro_dying_cost + c.wet_processing_cost + c.dry_processing_cost + c.compact_heat_set_cost), 2) * 
                    ROUND((b.total * c.order_qty_colour),2)),2) AS actual_cost
                    FROM tbl_avg_dyeing_processing_cost c
                    LEFT JOIN (SELECT ROUND(SUM(a.firsttable),3) as total,a.component_id FROM (SELECT ROUND(CAST(SUM(m.`values`) AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id) ,3)  AS firsttable, w.component_id
                    FROM tbl_garment_piece_weight w
                    LEFT JOIN tbl_garment_piece_weight_mapping m ON w.id = m.garment_piece_weight_id
                    WHERE w.enquiry_id = " . $enquiry_id . " AND w.component_id = " . $component_id . " GROUP BY w.id) a) b
                    ON b.component_id = c.component_id
                    WHERE c.enquiry_id = " . $enquiry_id . " AND c.component_id = " . $component_id . "  GROUP BY c.component_id)  e";
        }

        $data  = $this->db->query($query)->result_array();
        $total = isset($data[0]['total']) ? $data[0]['total'] : '0.00';
        return $total;
    }
    
    function getSizeChartType($enqId = '')
    {
        $this->db->select('size_type');
        $ArrRes = $this->db->get_where('tbl_pc_size_chart', array('enquiry_id' => $enqId));
        return $ArrRes->row()->size_type;
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

    function getGarmentParts()
    {
        $this->db->select('gpdname');
        $ArrRes = $this->db->get_where('kn_master_garment_part_desc', array('status' => 1));
        return $ArrRes->result_array();
    }

    function getGarmentPartsByName($name)
    {
        $name   = trim($name);
        $this->db->select('id');
        $ArrRes = $this->db->get_where('kn_master_garment_part_desc', array('gpdname' => $name));
        return $ArrRes->row()->id;
    }

    function getcombcolourName($name, $component_id)
    {
        $name   = trim($name);
        $this->db->select('id');
        $ArrRes = $this->db->get_where('tbl_color_combo', array('name' => $name,'component_id' => $component_id));
     $qur = $this->db->last_query();
    
        return $ArrRes->row()->id;
       
        

    }

    function getStoredGarmentParts($enquiry_id, $component_id)
    {
        $garmentParts = "SELECT DISTINCT gpdname AS name, g.garm_parts_id AS id FROM tbl_garment_piece_weight g JOIN kn_master_garment_part_desc d
                        ON g.garm_parts_id = d.id WHERE g.enquiry_id = " . $enquiry_id . " AND g.component_id = " . $component_id . "";
        $data         = $this->db->query($garmentParts)->result_array();
        return $data;
    }

    function deleteExistingParts($enqId, $component_id)
    {
        $garmentData = $this->getGarmentData($enqId, $component_id);
        if (!empty($garmentData))
        {
            $this->db->delete('tbl_garment_piece_weight', array('enquiry_id' => $enqId, 'component_id' => $component_id));
            foreach ($garmentData as $key => $value)
            {
                $this->db->delete('tbl_garment_piece_weight_mapping', array('garment_piece_weight_id' => $value['id']));
            }
        }
    }

    public function updateGarmentPieceWeight($data, $enqId, $component_id)
    {
        $ArrUpdateData['enquiry_id']   = $enqId;
        $ArrUpdateData['component_id'] = $component_id;
        $this->deleteExistingParts($enqId, $component_id);
        $result                        = [];
        $primaryId                     = '';
        foreach ($data as $key => $value)
        {
            $partsId                         = $this->getGarmentPartsByName($value[0]);
            $ArrUpdateData['garm_parts_id']  = $partsId;
            $ArrUpdateData['size_per_code']  = $value[1];
            $ArrUpdateData['parts_wise_avg'] = $value[count($value) - 1];

            $this->db->insert('tbl_garment_piece_weight', $ArrUpdateData);
            $primaryId = $this->db->insert_id();
            $sizeIds   = $this->getSizeChart($enqId);
            $sizeId    = explode(',', $sizeIds);
            $flag      = 2;
            foreach ($sizeId as $keyId => $valueId)
            {
                $sizedata['garment_piece_weight_id'] = $primaryId;
                $sizedata['size_id']                 = $valueId;
                $sizedata['values']                  = $value[$flag];
                $this->db->insert('tbl_garment_piece_weight_mapping', $sizedata);
                $flag++;
            }

            $primaryId = $this->db->insert_id();
        }
        return !empty($primaryId);
    }

    public function updateYarnCost($data, $enqId, $component_id)
    {
        $this->db->delete('tbl_yarn_cost', array('enquiry_id' => $enqId, 'component_id' => $component_id));
        $primaryId = '';

        foreach ($data as $key => $value)
        {
            $ArrUpdateData['enquiry_id']         = $enqId;
            $ArrUpdateData['component_id']       = $component_id;
            $ArrUpdateData['garment_parts_id']   = $value[0];
            $ArrUpdateData['blend_id']           = $value[1];
            $ArrUpdateData['content_id']         = $value[2];
            $ArrUpdateData['counts_id']          = $value[3];
            $ArrUpdateData['purch_type']         = $value[4];
            $ArrUpdateData['special']            = $value[5];
            $ArrUpdateData['cost_per_kg']        = $value[6];
            $ArrUpdateData['content_count_wise'] = $value[8];
            $ArrUpdateData['created_by']         = $this->userid;
            $ArrUpdateData['created_date']       = $this->mysqldatetime;
            $this->db->insert('tbl_yarn_cost', $ArrUpdateData);
            $primaryId                           = $this->db->insert_id();
        }
        return !empty($primaryId);
    }

    public function updateKnittingCost($data, $enqId, $component_id)
    {
        $this->db->delete('tbl_knitting_cost', array('enquiry_id' => $enqId, 'component_id' => $component_id));
        $primaryId = '';
        foreach ($data as $key => $value)
        {
            $garments_part_id                   = $this->getGarmentPartsByName($value[0]);
            $ArrUpdateData['enquiry_id']        = $enqId;
            $ArrUpdateData['component_id']      = $component_id;
            $ArrUpdateData['garment_parts_id']  = $garments_part_id;
            $ArrUpdateData['fabric_name_id']    = $value[4];
            $ArrUpdateData['finishing_gsm']     = $value[5];
            $ArrUpdateData['lycra']             = $value[6];
            $ArrUpdateData['lycra_feeder_type'] = $value[7];
            $ArrUpdateData['knitting_style_id'] = $value[8];
            $ArrUpdateData['knitting_cost_per'] = $value[9];
            $ArrUpdateData['created_by']        = $this->userid;
            $ArrUpdateData['created_date']      = $this->mysqldatetime;
            $this->db->insert('tbl_knitting_cost', $ArrUpdateData);
            $primaryId                          = $this->db->insert_id();
        }
        return !empty($primaryId);
    }

    public function updateBomCost($data, $enqId, $component_id, $grid_unique_id)
    {
        $article_id = ($grid_unique_id == 8) ? 1 : 2;
        $this->db->delete('tbl_bom_cost', array('enquiry_id' => $enqId, 'component_id' => $component_id, 'article_id' => $article_id));
        foreach ($data as $key => $value)
        {
            $ArrUpdateData['enquiry_id']    = $enqId;
            $ArrUpdateData['component_id']  = $component_id;
            $ArrUpdateData['article_id']    = $article_id;
            $ArrUpdateData['item_desc_id']  = $value[0];
            $ArrUpdateData['intake_qty']    = $value[1];
            $ArrUpdateData['uom_id']        = $value[2];
            $ArrUpdateData['cost_per_unit'] = $value[3];
            $ArrUpdateData['created_by']    = $this->userid;
            $ArrUpdateData['created_date']  = $this->mysqldatetime;
            $this->db->insert('tbl_bom_cost', $ArrUpdateData);
            $primaryId                      = $this->db->insert_id();
        }
    }

    public function updateEmpCost($data, $enqId, $component_id)
    {
        $this->db->delete('tbl_avg_embellishment_cost', array('enquiry_id' => $enqId, 'component_id' => $component_id));
        foreach ($data as $key => $value)
        {
             $partsId                         = $this->getcombcolourName($value[0],$component_id);
            $ArrUpdateData['enquiry_id']        = $enqId;
            $ArrUpdateData['component_id']      = $component_id;
            $ArrUpdateData['combo_id']          = $partsId;
            $ArrUpdateData['artwork_name_code'] = $value[1];
            $ArrUpdateData['type']              = $value[2];
            $ArrUpdateData['type_medium']       = $value[3];
            $ArrUpdateData['grading_details']   = $value[4];
            $ArrUpdateData['size_group']        = $value[5];
            $ArrUpdateData['emb_cost']          = $value[6];
            $ArrUpdateData['order_qty']         = $value[7];
            $ArrUpdateData['created_by']        = $this->userid;
            $ArrUpdateData['created_date']      = $this->mysqldatetime;
            $this->db->insert('tbl_avg_embellishment_cost', $ArrUpdateData);
            $primaryId                          = $this->db->insert_id();
        }
    }

    public function updateCmtCipCost($data, $enqId, $component_id)
    {
        $this->db->delete('tbl_cmt_cip_garment', array('enquiry_id' => $enqId, 'component_id' => $component_id));
        $primaryId = '';
        foreach ($data as $key => $value)
        {
            $ArrUpdateData['enquiry_id']     = $enqId;
            $ArrUpdateData['component_id']   = $component_id;
            $ArrUpdateData['operation_id']   = $value[0];
            $ArrUpdateData['operation_type'] = $value[1];
            $ArrUpdateData['operation_cost'] = $value[2];
            $ArrUpdateData['no_operations']  = $value[3];
            $ArrUpdateData['created_by']     = $this->userid;
            $ArrUpdateData['created_date']   = $this->mysqldatetime;
            $this->db->insert('tbl_cmt_cip_garment', $ArrUpdateData);
            $primaryId                       = $this->db->insert_id();
        }

        return !empty($primaryId);
    }

    public function updateOtherExp($data, $enqId, $component_id)
    {
        $this->db->delete('tbl_other_expenses', array('enquiry_id' => $enqId, 'component_id' => $component_id));
        $primaryId = '';
        foreach ($data as $key => $value)
        {
            $ArrUpdateData['enquiry_id']   = $enqId;
            $ArrUpdateData['component_id'] = $component_id;
            $ArrUpdateData['desc_id']      = $value[0];
            $ArrUpdateData['total_cost']   = $value[1];
            $ArrUpdateData['order_qty']    = $value[2];
            $ArrUpdateData['pcs_set']      = $value[3];
            $ArrUpdateData['no_component'] = $value[4];
            $ArrUpdateData['created_by']   = $this->userid;
            $ArrUpdateData['created_date'] = $this->mysqldatetime;
            $this->db->insert('tbl_other_expenses', $ArrUpdateData);
            $primaryId                     = $this->db->insert_id();
        }
        return !empty($primaryId);
    }

    public function updateDyingCostGrid($data, $enqId, $component_id, $combo_id)
    {
        $this->db->delete('tbl_dyeing_cost', array('enquiry_id' => $enqId, 'component_id' => $component_id, 'combo_id' => $combo_id));
        $primaryId = '';
        foreach ($data as $key => $value)
        {
            $ArrUpdateData['enquiry_id']         = $enqId;
            $ArrUpdateData['component_id']       = $component_id;
            $ArrUpdateData['combo_id']           = $combo_id;
            $ArrUpdateData['color_name']         = $value[0];
            $ArrUpdateData['garment_parts_id']   = $value[1];
            $ArrUpdateData['yarn_blend_id']      = $value[2];
            $ArrUpdateData['yarn_content_id']    = $value[3];
            $ArrUpdateData['yarn_counts_id']     = $value[4];
            $ArrUpdateData['dye_special_req_id'] = $value[5];
            $ArrUpdateData['dyeing_type_id']     = $value[6];
            $ArrUpdateData['dyeing_cost']        = $value[7];
            $ArrUpdateData['no_feed_repeat']     = $value[8];
            $ArrUpdateData['no_feed_color']      = $value[9];
            $ArrUpdateData['created_by']         = $this->userid;
            $ArrUpdateData['created_date']       = $this->mysqldatetime;
            $this->db->insert('tbl_dyeing_cost', $ArrUpdateData);
            $primaryId                           = $this->db->insert_id();
        }
        return !empty($primaryId);
    }

    public function updateDyingCostAvgGrid($data, $enqId, $component_id, $dyingType)
    {
        $this->db->delete('tbl_avg_dyeing_processing_cost', array('enquiry_id' => $enqId, 'component_id' => $component_id, 'dyeing_type' => $dyingType));
        $primaryId = '';
        foreach ($data as $key => $value)
        {
            $ArrUpdateData['enquiry_id']            = $enqId;
            $ArrUpdateData['component_id']          = $component_id;
            $ArrUpdateData['combo_id']              = $value[10];
            $ArrUpdateData['dyeing_type']           = $dyingType;
            $ArrUpdateData['pro_dying_cost']        = $value[1];
            $ArrUpdateData['wet_processing_cost']   = $value[2];
            $ArrUpdateData['dry_processing_cost']   = $value[3];
            $ArrUpdateData['compact_heat_set_cost'] = $value[4];
            $ArrUpdateData['order_qty_colour']      = $value[7];
            $ArrUpdateData['created_by']            = $this->userid;
            $ArrUpdateData['created_date']          = $this->mysqldatetime;
            $this->db->insert('tbl_avg_dyeing_processing_cost', $ArrUpdateData);
            $primaryId                              = $this->db->insert_id();
        }
        return !empty($primaryId);
    }
    
     public function updateDyingCostAvg_fabricprocess($data, $enqId, $component_id, $dyingType)
    {
        $this->db->delete('tbl_avg_dyeing_fabric_processing', array('enquiry_id' => $enqId, 'component_id' => $component_id, 'dyeing_type' => $dyingType));
        $primaryId = '';
        foreach ($data as $key => $value)
        {
            $ArrUpdateData['enquiry_id']            = $enqId;
            $ArrUpdateData['component_id']          = $component_id;
            $ArrUpdateData['dyeing_type']           = $dyingType;
            //$ArrUpdateData['colour_combo']          = $value[0];
            //$value[0] = ltrim(strstr($value[0], ';'), ';');  // Get part after first semicolon
            $ArrUpdateData['colour_combo'] = $value[0];
            $ArrUpdateData['process_desc']         = $value[1];
            $ArrUpdateData['process_type']         = $value[2];
            $ArrUpdateData['process_cost']        = $value[3];
            $ArrUpdateData['created_by']            = $this->userid;
            $ArrUpdateData['created_date']          = $this->mysqldatetime;
            $this->db->insert('tbl_avg_dyeing_fabric_processing', $ArrUpdateData);
            $primaryId                              = $this->db->insert_id();
        }
        return !empty($primaryId);
    }

    public function getFabricCostGrid($enqId)
    {
        $results = [
            'column' => [
                ['title' => 'Component', 'width' => '10%','readOnly' => true, 'align' => 'center'],
                ['title' => "Prop. Yarn Cost\nPer Kg (Rs)", 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => "Prop. Knitting Cost\nPer Kg (Rs)", 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => "Ave. Dyeing & Processing\n Cost Per Kg (Rs)", 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => "Fabric\nCost Per Kg (Rs)", 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => "Average Wgt. Per\nGarment (Kg)", 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => "Fabric Processing\nLoss (%)", 'width' => '10%', 'align' => 'center'],
                ['title' => "Actual Fabric Consumption\nPer Garment (Kg)", 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => "Fabric Cost\nPer Garment (Rs)", 'width' => '10%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'componet_id', 'width' => '10%', 'readOnly' => true, 'type' => 'hidden']
            ],
            'data'   => $this->getFabricCostData($enqId)
        ];

        return $results;
    }

    public function getActualCostGrid($enqId)
    {
        $currency = unserialize(ARRCURRENCYLIST);
        $results = [
            'column' => [
                ['title' => 'Component', 'width' => '9%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Fabric', 'width' => '4.5%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Emb.', 'width' => '4.5%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'BOM' . PHP_EOL . '(Article - 1)', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'BOM' . PHP_EOL . '(Article - 2)', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'CMT & CIP', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Other' . PHP_EOL . 'Expenses', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Excess' . PHP_EOL . 'Qty. (%)', 'width' => '5%', 'align' => 'center'],
                ['title' => 'Ave. Cost' . PHP_EOL . 'Per Gar. (Rs)', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Overheads' . PHP_EOL . '(%)', 'width' => '6%', 'align' => 'center'],
                ['title' => 'Intake' . PHP_EOL . 'Qty.', 'width' => '4%'],
                ['title' => 'Ave. Cost Plus' . PHP_EOL . 'Overheads (Rs)', 'width' => '8%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Profit' . PHP_EOL . '(%)', 'width' => '4%', 'align' => 'center'],
                ['title' => 'Act. Cost' . PHP_EOL . 'Per Gar. (Rs)', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
                // ['title' => 'Intake' . PHP_EOL . 'Qty.', 'width' => '4%'],
                // ['title' => 'Quoted' . PHP_EOL . 'Currency', 'width' => '5%', 'type' => 'dropdown', 'source' => ['USD', '']],
                ['title' => 'Quoted' . PHP_EOL . 'Currency', 'width' => '5%', 'type' => 'dropdown', 'source' => $currency],
                ['title' => 'Exch. Rate Per' . PHP_EOL . 'Unit in (Rs)', 'width' => '7%', 'center' => 'center'],
                ['title' => 'Cost in Foreign' . PHP_EOL . 'Exchange', 'width' => '7%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'componet_id', 'width' => '0', 'readOnly' => true, 'type' => 'hidden']
            ],
            'data'   => $this->getActualCostData($enqId)
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    }
    
    public function getIsrCostGrid($enqId)
    {
        
        $currency = unserialize(ARRCURRENCYLIST);
        $results = [
            'column' => [
                ['title' => 'Ave. Cost Plus' . PHP_EOL .' Overheads (Rs.)', 'width' => '8%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Invoice Value Per'. PHP_EOL .'Garment / Set', 'width' => '8%', 'align' => 'center'],
                // ['title' => 'Quoted'. PHP_EOL . 'Currency.', 'width' => '6%', 'align' => 'center', 'type' => 'dropdown', 'source' => ['USD', '']],
                ['title' => 'Quoted'. PHP_EOL . 'Currency.', 'width' => '6%', 'align' => 'center', 'type' => 'dropdown', 'source' => $currency],
                ['title' => 'Exch. Rate' . PHP_EOL . 'Per Unit in (Rs.)', 'width' => '6%', 'align' => 'center'],
                ['title' => "Agent's" . PHP_EOL . 'Commission (%)', 'width' => '6%', 'align' => 'center'],
                ['title' => 'Commission' . PHP_EOL . 'Value (Rs.)', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Cost Plus' . PHP_EOL . 'Commission (Rs.)', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Expected' . PHP_EOL . 'Invoice Value (Rs.)', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Expected' . PHP_EOL . 'Profit Value (Rs.)', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Expected' . PHP_EOL . 'Profit (%)', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
            ],
            'data'   => $this->getIsrGridData($enqId)
        ];

        return $results;
    }
    
    public function getIorCostGrid($enqId)
    {
        $ArrPcsOrSet = unserialize(ARRPCSSET);
        $enquirysets=$this->db->select('pcsorset as id')->get_where('kn_order_enquiry',array('id'=>$enqId))->result_array(); // getting pcs_set from enquiry
        $pcs_set_name=$ArrPcsOrSet[$enquirysets[0]['id']];
        $pcs_set_id=$enquirysets[0]['id']; // pcs_set_id from enquiry
        $pcs_set=[];
        foreach($ArrPcsOrSet as $key=>$value){
            if($key==$pcs_set_id){
               $pcs_set[]=array('id' => $key, 'name' => $value);
            }
        }
        $results = [
            'column' => [
                ['title' => 'Ave. Cost Plus' . PHP_EOL .' Overheads (Rs.)', 'width' => '8%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Invoice Value Per'. PHP_EOL .'Garment / Set', 'width' => '8%', 'align' => 'center'],
                ['title' => 'Quoted'. PHP_EOL . 'Currency.', 'width' => '6%', 'align' => 'center', 'type' => 'dropdown', 'source' => ['USD', '']],
                ['title' => 'Exch. Rate Per' . PHP_EOL . 'Unit in (Rs.)', 'width' => '6%', 'align' => 'center'],
                ['title' => 'Agents' . PHP_EOL . 'Commission (%)', 'width' => '6%', 'align' => 'center'],
                ['title' => 'Commission' . PHP_EOL . 'Value (Rs.)', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Cost Plus' . PHP_EOL . 'Commission (Rs.)', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Total Order' . PHP_EOL . 'Qty.', 'width' => '6%', 'align' => 'center'],
                ['title' => 'Pcs / Set', 'width' => '6%', 'align' => 'center', 'type'   => 'dropdown', 'source' =>$pcs_set
                    // [
                    //     ['id' => 1, 'name' => 'Set']
                    // ]
                ],
                ['title' => 'Budgeted Cost' . PHP_EOL . 'Without Profit (Rs.)', 'width' => '7%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Expected' . PHP_EOL . 'Total Invoice Value', 'width' => '7%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Expected' . PHP_EOL . ' Profit Value', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
                ['title' => 'Expected' . PHP_EOL . ' Profit (%)', 'width' => '6%', 'readOnly' => true, 'align' => 'center'],
            ],
            'data'   => $this->getIorGridData($enqId)
        ];

        return $results;
    }
    
    function getIsrGridData($enqId)
    {
        $isr_profit_query = "SELECT 
                                SUM(a.avg_cost_overheads) as avg_cost_overheads, a.currency, a.foreign_exch_rate,
                                isr.invoice_value_per_garment, isr.quoted_currency, isr.exch_rate_per_unit, isr.agent_commission
                                FROM tbl_actual_cost_garment a 
                                LEFT JOIN tbl_isr_profit_percentage isr ON isr.enquiry_id = a.enquiry_id
                                WHERE a.enquiry_id = " . $enqId . "";
        $isr_data  = $this->db->query($isr_profit_query)->result_array();
        foreach($isr_data as $key => $value)
        {
            //   $result[$key] = [$value['avg_cost_overheads'], $value['invoice_value_per_garment'], $value['quoted_currency'], $value['exch_rate_per_unit'],$value['agent_commission']];
            $result[$key] = [$value['avg_cost_overheads'], $value['invoice_value_per_garment'], $value['currency'], $value['foreign_exch_rate'],$value['agent_commission']];
        }
        
        return $result;
    }
    
    function getIorGridData($enqId)
    {
        $ior_profit_query = "SELECT 
                                SUM(a.avg_cost_overheads) as avg_cost_overheads, ior.invoice_value_per_garment, ior.quoted_currency, 
                                ior.exch_rate_per_unit, ior.agent_commission,ior.total_order,ior.pcs_set
                                FROM tbl_actual_cost_garment a 
                                LEFT JOIN tbl_budgeted_cost ior ON ior.enquiry_id = a.enquiry_id
                                WHERE a.enquiry_id = " . $enqId . "";
        $ior_data         = $this->db->query($ior_profit_query)->result_array();
        foreach ($ior_data as $key => $value)
        {
            $result[$key] = [$value['avg_cost_overheads'], $value['invoice_value_per_garment'], $value['quoted_currency'], $value['exch_rate_per_unit'], $value['agent_commission'], '', '', $value['total_order'], $value['pcs_set'], '', '', '', ''];
        }

        return $result;
    }

    function getActualCostData($enqId)
    {
        $compoquery = "SELECT c.id, c.comp_name FROM tbl_components c WHERE c.draft_status=2 AND enquiry_id = " . $enqId . "";
        $combodata  = $this->db->query($compoquery)->result_array();

        //  $emp_cost_query = "SELECT  ROUND(ROUND(SUM(e.emb_cost * e.order_qty),2) /  ROUND(SUM(e.order_qty),2),2) AS total, e.component_id
        //                         FROM tbl_avg_embellishment_cost e
        //                         WHERE e.enquiry_id = " . $enqId . " GROUP BY e.component_id";

       $emp_cost_query ="SELECT  ROUND( ROUND(SUM(e.emb_cost * e.order_qty), 2) /  ROUND(combo.total_order_qty, 2),  2) AS total, 
        e.component_id FROM tbl_avg_embellishment_cost e JOIN (  SELECT component_id, SUM(combo_order_qty) AS total_order_qty
         FROM (  SELECT component_id, combo_id, order_qty AS combo_order_qty FROM tbl_avg_embellishment_cost WHERE enquiry_id = " . $enqId . "
         GROUP BY component_id, combo_id ) AS combo_sums GROUP BY component_id ) AS combo ON e.component_id = combo.component_id
      WHERE e.enquiry_id = " . $enqId . " GROUP BY e.component_id, combo.total_order_qty ";





       
         $emp_cost_data  = $this->db->query($emp_cost_query)->result_array();

                  //file_put_contents("error_log", print_r($emp_cost_query, true));

    
        $emp_cost_data  = $this->db->query($emp_cost_query)->result_array();

        $bom_query = "SELECT  ROUND(SUM((b.intake_qty * b.cost_per_unit)),2) AS total ,b.article_id, b.component_id  FROM tbl_bom_cost b
                              WHERE b.enquiry_id = " . $enqId . " GROUP BY b.article_id, b.component_id";
        $bom_data  = $this->db->query($bom_query)->result_array();

        $cmt_cip_query = "SELECT ROUND(SUM(c.operation_cost * c.no_operations),2) AS total, c.component_id  FROM tbl_cmt_cip_garment c 
                          WHERE c.enquiry_id = " . $enqId . " group by c.component_id";
        $cmt_cip_data  = $this->db->query($cmt_cip_query)->result_array();

        $other_exp_query = "SELECT ROUND(SUM(o.total_cost / (o.order_qty * o.no_component)),2) AS total, o.component_id FROM tbl_other_expenses o 
                           WHERE o.enquiry_id = " . $enqId . " GROUP BY o.component_id;";
        $other_exp_data  = $this->db->query($other_exp_query)->result_array();

        $fabric_table_query = "SELECT f.fabric_cost_garment,f.component_id FROM tbl_fabric_cost_garment f WHERE f.enquiry_id = " . $enqId . "";
        $fabric_data        = $this->db->query($fabric_table_query)->result_array();

        $actual_cost_table_query = "SELECT a.excess_qty,a.overheads,a.profit,a.intake_qty,a.currency,a.foreign_exch_rate FROM tbl_actual_cost_garment a WHERE a.enquiry_id = " . $enqId . "";
        $actual_cost_data        = $this->db->query($actual_cost_table_query)->result_array();

        // print_r($combodata);
        // exit();
        $result = [];
        foreach ($combodata as $key => $value)
        {
            $fabric_cost = isset($fabric_data[$key]['fabric_cost_garment']) ? $fabric_data[$key]['fabric_cost_garment'] : '0.00';
            $emp_cost    = isset($emp_cost_data[$key]['total']) ? $emp_cost_data[$key]['total'] : '0.00';
            $article1    = $this->componentArticle($bom_data, $value['id'], 1);
            $article2    = $this->componentArticle($bom_data, $value['id'], 2);
            $cmp_cip     = isset($cmt_cip_data[$key]['total']) ? $cmt_cip_data[$key]['total'] : '0.00';
            $other_exp   = isset($other_exp_data[$key]['total']) ? $other_exp_data[$key]['total'] : '0.00';

            /** Actual Cost Data * */
            $excess_qty        = isset($actual_cost_data[$key]['excess_qty']) ? $actual_cost_data[$key]['excess_qty'] : '0.00';
            $overheads         = isset($actual_cost_data[$key]['overheads']) ? $actual_cost_data[$key]['overheads'] : '0.00';
            $profit            = isset($actual_cost_data[$key]['profit']) ? $actual_cost_data[$key]['profit'] : '0.00';
            $intake_qty        = isset($actual_cost_data[$key]['intake_qty']) ? $actual_cost_data[$key]['intake_qty'] : '0.00';
            $currency          = isset($actual_cost_data[$key]['currency']) ? $actual_cost_data[$key]['currency'] : "";
            $foreign_exch_rate = isset($actual_cost_data[$key]['foreign_exch_rate']) ? $actual_cost_data[$key]['foreign_exch_rate'] : '0.00';

            // $result[$key] = [$value['comp_name'], $fabric_cost, $emp_cost, $article1, $article2, $cmp_cip, $other_exp, $excess_qty, '', $overheads, '', $profit, '', $intake_qty, $currency, $foreign_exch_rate, '', $value['id']];
            $result[$key] = [$value['comp_name'], $fabric_cost, $emp_cost, $article1, $article2, 
                            $cmp_cip, $other_exp, $excess_qty, '', $overheads, $intake_qty, '', $profit, '', 
                            $currency, $foreign_exch_rate, '', $value['id']];
        }
        return $result;
    }

    function componentArticle($data, $comp_id, $article_id = '')
    {
        $total = '0.00';
        foreach ($data as $key => $value)
        {
            if ($value['component_id'] == $comp_id && $value['article_id'] == $article_id)
            {
                $total = $value['total'];
            }
        }
        return $total > 0 ? $total : '0.00';
    }

    public function updateFabricCostGrid($enqId, $data)
    {
        $this->db->delete('tbl_fabric_cost_garment', array('enquiry_id' => $enqId));
        $primaryId = '';
        foreach ($data as $key => $value)
        {
            $ArrUpdateData['enquiry_id']                  = $enqId;
            $ArrUpdateData['component_id']                = $value[9];
            $ArrUpdateData['proportionate_yarn_cost']     = $value[1];
            $ArrUpdateData['proportionate_knitting_cost'] = $value[2];
            $ArrUpdateData['avg_dyeing_processing_cost']  = $value[3];
            $ArrUpdateData['fabric_cost']                 = $value[4];
            $ArrUpdateData['avg_piece_weight']            = $value[5];
            $ArrUpdateData['fabric_processing_loss']      = $value[6];
            $ArrUpdateData['actual_fabric_consumption']   = $value[7];
            $ArrUpdateData['fabric_cost_garment']         = $value[8];
            $ArrUpdateData['created_by']                  = $this->userid;
            $ArrUpdateData['created_date']                = $this->mysqldatetime;
            $this->db->insert('tbl_fabric_cost_garment', $ArrUpdateData);
            $primaryId                                    = $this->db->insert_id();
        }
        return !empty($primaryId);
    }

    public function updateActualCostGrid($enqId, $data)
    {
        // print_r($enqId);
        // print_r($data);
        // exit();
        $this->db->delete('tbl_actual_cost_garment', array('enquiry_id' => $enqId));
        $primaryId = '';
        foreach ($data as $key => $value)
        {
            // $ArrUpdateData['enquiry_id']          = $enqId;
            // $ArrUpdateData['component_id']        = $value[17];
            // $ArrUpdateData['fabric']              = $value[1];
            // $ArrUpdateData['embellishment']       = $value[2];
            // $ArrUpdateData['bom_article_1']       = $value[3];
            // $ArrUpdateData['bom_article_2']       = $value[4];
            // $ArrUpdateData['cmt_cip']             = $value[5];
            // $ArrUpdateData['other_expenses']      = $value[6];
            // $ArrUpdateData['excess_qty']          = $value[7];
            // $ArrUpdateData['avg_cost_garment']    = $value[8];
            // $ArrUpdateData['overheads']           = $value[9];
            // $ArrUpdateData['avg_cost_overheads']  = $value[10];
            // $ArrUpdateData['profit']              = $value[11];
            // $ArrUpdateData['actual_cost_garment'] = $value[12];
            // $ArrUpdateData['intake_qty']          = $value[13];
            // $ArrUpdateData['currency']            = $value[14];
            // $ArrUpdateData['foreign_exch_rate']   = $value[15];
            // $ArrUpdateData['cost_in_foreign']     = $value[16];
            // $ArrUpdateData['created_by']          = $this->userid;
            // $ArrUpdateData['created_date']        = $this->mysqldatetime;
            
            $ArrUpdateData['enquiry_id']          = $enqId;
            $ArrUpdateData['component_id']        = $value[17];
            $ArrUpdateData['fabric']              = $value[1];
            $ArrUpdateData['embellishment']       = $value[2];
            $ArrUpdateData['bom_article_1']       = $value[3];
            $ArrUpdateData['bom_article_2']       = $value[4];
            $ArrUpdateData['cmt_cip']             = $value[5];
            $ArrUpdateData['other_expenses']      = $value[6];
            $ArrUpdateData['excess_qty']          = $value[7];
            $ArrUpdateData['avg_cost_garment']    = $value[8];
            $ArrUpdateData['overheads']           = $value[9];
            $ArrUpdateData['intake_qty']          = $value[10];
            $ArrUpdateData['avg_cost_overheads']  = $value[11];
            $ArrUpdateData['profit']              = $value[12];
            $ArrUpdateData['actual_cost_garment'] = $value[13];
            $ArrUpdateData['currency']            = $value[14];
            $ArrUpdateData['foreign_exch_rate']   = $value[15];
            $ArrUpdateData['cost_in_foreign']     = $value[16];
            $ArrUpdateData['created_by']          = $this->userid;
            $ArrUpdateData['created_date']        = $this->mysqldatetime;
            $this->db->insert('tbl_actual_cost_garment', $ArrUpdateData);
            $primaryId                            = $this->db->insert_id();
        }
        return !empty($primaryId);
    }
    
    public function updateIsrCostGrid($enqId, $data)
    {
        $this->db->delete('tbl_isr_profit_percentage', array('enquiry_id' => $enqId));
        $primaryId = '';
        foreach ($data as $key => $value)
        {
            $ArrUpdateData['enquiry_id']                = $enqId;
            $ArrUpdateData['invoice_value_per_garment'] = $value[1];
            $ArrUpdateData['quoted_currency']           = $value[2];
            $ArrUpdateData['exch_rate_per_unit']        = $value[3];
            $ArrUpdateData['agent_commission']          = $value[4];
            $ArrUpdateData['created_by']                = $this->userid;
            $ArrUpdateData['created_date']              = $this->mysqldatetime;
            $this->db->insert('tbl_isr_profit_percentage', $ArrUpdateData);
            $primaryId                                  = $this->db->insert_id();
        }
        return !empty($primaryId);
    }
    
    public function updateIorCostGrid($enqId, $data)
    {
        $this->db->delete('tbl_budgeted_cost', array('enquiry_id' => $enqId));
        $primaryId = '';
        foreach ($data as $key => $value)
        {
            $ArrUpdateData['enquiry_id']                = $enqId;
            $ArrUpdateData['invoice_value_per_garment'] = $value[1];
            $ArrUpdateData['quoted_currency']           = $value[2];
            $ArrUpdateData['exch_rate_per_unit']        = $value[3];
            $ArrUpdateData['agent_commission']          = $value[4];
            $ArrUpdateData['total_order']               = $value[7];
            $ArrUpdateData['pcs_set']                   = $value[8];
            $ArrUpdateData['created_by']                = $this->userid;
            $ArrUpdateData['created_date']              = $this->mysqldatetime;
            $this->db->insert('tbl_budgeted_cost', $ArrUpdateData);
            $primaryId                                  = $this->db->insert_id();
        }
        return !empty($primaryId);
    }
    public function gettest_process($enquiry_id, $component_id)
    {
   
        $otherExp = $this->db->select('id,desc_name as name')->get_where('tbl_other_expenses_master', array('status' => 1))->result_array();
        $ArrPcsOrSet = unserialize(ARRPCSSET);
        $enquirysets=$this->db->select('pcsorset as id,totalcomponents as no_component,exporderqty as order_qty')->get_where('kn_order_enquiry',array('id'=>$enquiry_id))->result_array(); // getting pcs_set from enquiry
        $pcs_set_name=$ArrPcsOrSet[$enquirysets[0]['id']];
        $pcs_set_id=$enquirysets[0]['id']; // pcs_set_id from enquiry
        $pcs_set=[];
        $noofcomp=[array('id' => $enquirysets[0]['no_component'], 'name' => $enquirysets[0]['no_component'])];
        $totorderqty=[array('id' => $enquirysets[0]['order_qty'], 'name' => $enquirysets[0]['order_qty'])];
        foreach($ArrPcsOrSet as $key=>$value){
            if($key==$pcs_set_id){
               $pcs_set[]=array('id' => $key, 'name' => $value);
            }
        }
        
        $results = [
            'column' => [
                ['title' => 'Description', 'width' => '40%', 'type' => 'dropdown', 'source' => $otherExp, 'align' => 'center'],
                ['title' => 'Total Cost (Rs)', 'width' => '15%', 'align' => 'center'],
                ['title' => 'Total Order Qty.', 'width' => '15%','type' => 'dropdown', 'source' => $totorderqty,],
                ['title'  => 'Pcs / Set', 'width'  => '15%','align'  => '', 'type'   => 'dropdown', 'source' =>$pcs_set
                    // [
                    //     ['id' => $pcs_set_id, 'name' => $pcs_set_name] // for showing this in to dynamic from enquiry 
                    // ]
                ],
                ['title' => 'No.of Components','width' => '15%','type' => 'dropdown', 'source' =>$noofcomp, 'align' => 'center'],
                ['title' => 'Average Cost Per Garment (Rs)', 'width' => '25%', 'align' => 'center', 'readOnly' => true],
            ],
            'data'   => $this->getOtherExpData($enquiry_id, $component_id)
        ];

        return $results;
    
    }

}
