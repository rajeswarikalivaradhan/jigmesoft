<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PreCosting extends CI_Controller
{

    public $companyid;
    public $userid;
    public $mysqldatetime;

    public function __construct()
    {
        parent::__construct();
        //error_reporting(E_ALL);
        $this->load->helper('xssclean');
        fnIfCheckUserLoggedIn();
        $this->load->model('commonmodel');
        $this->load->model('merchantmodel');
        $this->load->model('componentsModel'); 
        $this->load->model(CNFCOMPANY . 'menquirymodel');
        $this->load->model('merchant/preCostingModels');
        $this->load->model(CNFCOMPANY . 'workinprogressmodel');
        $this->load->model(CNFCOMPANY . "mcadrequestmodel");
        $this->load->model(CNFCOMPANY . "orderentrymodel");
        $this->load->model("preCostingModel");
        $ArrUserLoggedInfo   = fnGetUserLoggedInfo('1');
        $this->companyid     = $ArrUserLoggedInfo['companyid'];
        $this->userid        = $ArrUserLoggedInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
    }

    /* public function index() {
      $this->load->view('merchant/merchantdashboard');
      } */

    public function loadBom()
    {
        $enquiry_id   = xssclean($this->input->get('enquiry_id'));
        $art_id       = xssclean($this->input->get('art_id'));
        $component_id = xssclean($this->input->get('component_id'));
        $model        = new PreCostingModel();
        $bomDetails   = $model->getBom($enquiry_id, $component_id, $art_id);
        echo json_encode($bomDetails);
    }

    public function componentCreation()
    {
        $ArrUserType    = unserialize(ARRUSERTYPE);
        $VarId          = $this->uri->segment(3);
        $VarEnqId       = '';
        $ArrEnquiryInfo = array();
        $components     = [];
        if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
        {
            $VarEnqId       = base64_decode(urldecode($VarId)); 
            $ArrEnquiryInfo = $this->menquirymodel->fnGetInfo('', $VarEnqId, $this->companyid);

            $qry        = $this->db->get_where('tbl_components', array('enquiry_id' => $VarEnqId));
            $components = $qry->result_array();
        }
        $this->load->view('pre_costing/component_creation', array('enqId' => $VarEnqId, 'ArrEnquiryInfo' => $ArrEnquiryInfo, 'components' => $components));
    }

    public function componentUpdate()
    {
        $ArrUpdateData               = array();
        $compObject                  = xssclean($this->input->post('object'));
        $ArrUpdateData['enquiry_id'] = xssclean($this->input->post('enqId'));
        $ArrUpdateData['user_id']    = $this->userid;
        $data                        = json_decode($compObject, true);

        //echo json_encode(array('id' => $this->db->insert_id()));
        // Componet Creation
        $this->db->delete('tbl_components', array('enquiry_id' => $ArrUpdateData['enquiry_id']));
        foreach ($data as $key => $value)
        {
            $ArrUpdateData['comp_name'] = $value['value'];
            $this->db->insert('tbl_components', $ArrUpdateData);
        }

        //Size chart update
        $VarEnq = "SELECT count(1) as count FROM tbl_pc_size_chart WHERE enquiry_id = '" . $ArrUpdateData['enquiry_id'] . "'";
        $count  = $this->db->query($VarEnq)->row();

        $ArrSize['size_ids']     = xssclean($this->input->post('GlbSelSizeChart'));
        $ArrSize['size_type']    = xssclean($this->input->post('GlbMasterSizeChartId'));
        $ArrSize['created_date'] = $this->mysqldatetime;
        $ArrSize['created_by']   = $this->userid;
        $ArrSize['enquiry_id']   = $ArrUpdateData['enquiry_id'];

        if ($count->count > 0)
        {
            $ArrSize['modified_date'] = $this->mysqldatetime;
            $this->db->where('enquiry_id', $ArrUpdateData['enquiry_id']);
            $this->db->update('tbl_pc_size_chart', $ArrSize);
        }
        else
        {
            $this->db->insert('tbl_pc_size_chart', $ArrSize);
        }
    }

    public function index()
    {
        /*
         * For getting folder name of uploads in for order enquiry we need to send user type
         * */
        $ArrUserType    = unserialize(ARRUSERTYPE);
        $VarId          = $this->uri->segment(3);
        $VarEnqId       = '';
        $ArrEnquiryInfo = array();
        if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
        {
            $VarEnqId       = base64_decode(urldecode($VarId));
            $ArrEnquiryInfo = $this->menquirymodel->fnGetInfo('', $VarEnqId, $this->companyid);
        }
        $ArrUserInfo    = fnGetUserLoggedInfo(1);
        $VarUserType    = $ArrUserType[$ArrUserInfo['usertype']];
        $ArrOrderStatus = unserialize(ORDERENQUIRYSTATUS);
        $ArrCountries   = unserialize(ARRCOUNTRYLIST);
        $ArrEnquiryType = ARRENQUIRYTYPE;
        $ArrModeType    = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_MODEOFENQUIRY, 'id,modeofenquiry as name', array('status' => '1', 'companyid' => $this->companyid));
        $ArrCurrency    = unserialize(ARRCURRENCYLIST);
        $ArrBrand       = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BRANDS . ' AS br', 'br.id,br.brandname', array('br.status' => '1', 'br.companyid' => $this->companyid), 3);
        $ArrBuyer       = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BUYER . ' AS byr', 'byr.id,byr.buyername', array('byr.status' => '1', 'byr.companyid' => $this->companyid), 3);
               // print_r($ArrEnquiryInfo);
        // Size
        // $size_ids = 'SELECT size_ids FROM tbl_pc_size_chart WHERE enquiry_id = ' . $VarEnqId;
        // $ids      = $this->db->query($size_ids)->row();
        // $sizeIds  = isset($ids->size_ids) ? $ids->size_ids : '';

        // $size_wise_parts = array();
        // if (!empty($sizeIds))
        // {
        //     $userInfoQry     = "SELECT size_name FROM tbl_size_master sm WHERE sm.id IN (" . $sizeIds . ")";
        //     $size_wise_parts = $this->db->query($userInfoQry)->result_array();
        // }

// Size
        $size_ids = 'SELECT size_ids,size_type FROM tbl_pc_size_chart WHERE enquiry_id = ' . $VarEnqId;
        $ids      = $this->db->query($size_ids)->row();
        $sizeIds  = isset($ids->size_ids) ? $ids->size_ids : '';
        $sizetype  = isset($ids->size_type ) ? $ids->size_type : '';
//var_dump($sizetype);
        $size_wise_parts = array();
        if (!empty($sizeIds) && (!empty($sizetype)) && $sizetype==1)
        {
            $userInfoQry     = "SELECT size_name FROM tbl_size_master sm WHERE sm.id IN (" . $sizeIds . ")";
            $size_wise_parts = $this->db->query($userInfoQry)->result_array();
        }else{
             //$size_wise_parts[] = $ids->size_ids;
             $size_wise_parts[] = $sizeIds;
        }



        $userInfoQry = 'SELECT c.id AS component_id, c.comp_name AS component_name, c.dying_type,c.draft_status FROM tbl_components c WHERE c.draft_status=2 AND c.enquiry_id=' . $VarEnqId;
        $components  = $this->db->query($userInfoQry)->result_array();
        
        $checkDraftorNot = $this->componentsModel->checkDraftorNot($VarEnqId);
        
        $this->load->view('pre_costing/index', array('ArrEnquiryType' => $ArrEnquiryType, 'ArrCountries'   => $ArrCountries,
            'ArrModeType'    => $ArrModeType, 'ArrCurrency'    => $ArrCurrency, 'ArrBrand'       => $ArrBrand, 'ArrBuyer'       => $ArrBuyer, 'VarEnqId'       => $VarEnqId,
            'ArrEnquiryInfo' => $ArrEnquiryInfo, 'ArrOrderStatus' => $ArrOrderStatus, 'UserType'       => $VarUserType,
            'components'     => $components,'checkDraftorNot'=> $checkDraftorNot, 
            'VarEnquiryId'   => ''
        ));
    }

    public function getSizeCharts()
    {
        $ArrSizeChartDetails = ARR_STD_SIZE;
        $VarSizeCharId       = xssclean($this->input->post('sc'));
        if (!empty($VarSizeCharId))
        {
            $VarSizeChartHTML = "";
            if ($VarSizeCharId <> 2)
            {
                $ArrSubChartDetails = $this->preCostingModels->getSizeMasters(1);
                //$ArrSubChartDetails = $ArrSizeChartDetails[$VarSizeCharId];
                foreach ($ArrSubChartDetails as $VarSubChartId => $VarSubChartName)
                {
                    $VarSizeChartHTML .= "<div class='col-lg-1 p-lg-0 m-lg-0 form-check form-check-inline'>
                                          <input type='checkbox' name='frmSubChartSelection' class='frmSubChartSelection custom-control-input' id='frmSubChartSel" . $VarSubChartName['id'] . "' value='" . $VarSubChartName['id'] . "'> <label class='custom-control-label' for='frmSubChartSel" . $VarSubChartName['id'] . "'>" . $VarSubChartName['size_name'] . "</label> </div>";
                }
            }
            else
            {
                for ($i = 1; $i <= 24; $i++)
                {
                    $VarSizeChartHTML .= "<input type='text' name='frmSubChartCustomSelection[]' id='frmSubChartCustomSelection" . $i . "' value='' class='form-control' style='width:4%;padding:3px 6px;float:left;margin:1%;'>&nbsp;&nbsp;";
                }
                $VarSizeChartHTML .= "<div class='clearfix'></div>";
            }
        }
        else
        {
            $VarSizeChartHTML = '';
        }
        $ArrResult['errcode'] = '1';
        $ArrResult['ss']      = $VarSizeChartHTML;
        echo json_encode($ArrResult);
    }

    public function preCostingColumns()
    {
        $enqId          = xssclean($this->input->post('enquiry_id'));
        $component_id   = xssclean($this->input->post('component_id'));
        $grid_unique_id = xssclean($this->input->post('grid_unique_id'));

        switch ($grid_unique_id)
        {
            case 1:
                $data = $this->preCostingModel->getGarmentPieceWeight($enqId, $component_id);
                break;
            case 2:
                $data = $this->preCostingModel->getYarnCost($enqId, $component_id);
                break;
            case 3:
                $data = $this->preCostingModel->getKnittingCost($enqId, $component_id);
                break;
            case 4:
                $data = $this->preCostingModel->getDyingCost($enqId, $component_id);
                break;
            case 5:
                $data = $this->preCostingModel->getDyingCostAvg($enqId, $component_id);
                break;
            case 7:
                $data = $this->preCostingModel->getEmpCost($enqId, $component_id);
                break;
            case 8:
                // $data = $this->preCostingModel->getBom($enqId, $component_id, $grid_unique_id); // before integration of new form
                 $data = $this->preCostingModel->getBom1($enqId, $component_id, $grid_unique_id);
                break;
            case 9:
                //$data = $this->preCostingModel->getBom($enqId, $component_id, $grid_unique_id);// before integration of new form
                $data = $this->preCostingModel->getBom2($enqId, $component_id, $grid_unique_id);
                break;
            case 10:
                $data = $this->preCostingModel->getcmtcipCost($enqId, $component_id);
                break;
            case 11:
                $data = $this->preCostingModel->getOtherExp($enqId, $component_id);
                break;

            case 12:
                $data = $this->preCostingModel->getdyeing_fabric_process($enqId, $component_id);
                break;
        }

        echo json_encode($data);
    }

    public function preCostingUpdate()
    {
        $enqId          = xssclean($this->input->post('enquiry_id'));
        $object         = xssclean($this->input->post('object'));
        $grid_unique_id = xssclean($this->input->post('grid_unique_id'));
        $component_id   = xssclean($this->input->post('component_id'));
        $data           = json_decode($object);

        if ($grid_unique_id == 1)
        {
            $data = $this->preCostingModel->updateGarmentPieceWeight($data, $enqId, $component_id);
        }
        elseif ($grid_unique_id == 2)
        {
            $data = $this->preCostingModel->updateYarnCost($data, $enqId, $component_id);
        }
        elseif ($grid_unique_id == 3)
        {
            $data = $this->preCostingModel->updateKnittingCost($data, $enqId, $component_id);
        }
        elseif ($grid_unique_id == 7)
        {
            $data = $this->preCostingModel->updateEmpCost($data, $enqId, $component_id);
        }
        elseif ($grid_unique_id == 8 || $grid_unique_id == 9)
        {
            $data = $this->preCostingModel->updateBomCost($data, $enqId, $component_id, $grid_unique_id);
        }
        elseif ($grid_unique_id == 10)
        {
            $data = $this->preCostingModel->updateCmtCipCost($data, $enqId, $component_id);
        }
        elseif ($grid_unique_id == 11)
        {
            $data = $this->preCostingModel->updateOtherExp($data, $enqId, $component_id);
        }
        echo json_encode($data);
    }

    public function updateDyingCost()
    {
        $enqId          = xssclean($this->input->post('enquiry_id'));
        $object         = xssclean($this->input->post('object'));
        $grid_unique_id = xssclean($this->input->post('grid_unique_id'));
        $component_id   = xssclean($this->input->post('component_id'));
        $combo_id       = xssclean($this->input->post('combo_id'));
        $data           = json_decode($object);

        $data = $this->preCostingModel->updateDyingCostGrid($data, $enqId, $component_id, $combo_id);
        echo json_encode($data);
    }

    public function updateDyingCostAvg()
    {
        $enqId          = xssclean($this->input->post('enquiry_id'));
        $object         = xssclean($this->input->post('object'));
        $grid_unique_id = xssclean($this->input->post('grid_unique_id'));
        $component_id   = xssclean($this->input->post('component_id'));
        $dyingType      = xssclean($this->input->post('dyingType'));
        $combo_id       = xssclean($this->input->post('combo_id'));
        $data           = json_decode($object);

        $data = $this->preCostingModel->updateDyingCostAvgGrid($data, $enqId, $component_id, $dyingType, $combo_id);
        echo json_encode($data);
    }

    public function updateDyingCostAvg_fabricprocess()
    {
        $enqId          = xssclean($this->input->post('enquiry_id'));
        $object         = xssclean($this->input->post('object'));
        $grid_unique_id = xssclean($this->input->post('grid_unique_id'));
        $component_id   = xssclean($this->input->post('component_id'));
        $dyingType      = xssclean($this->input->post('dyingType'));
        $data           = json_decode($object);

        $data = $this->preCostingModel->updateDyingCostAvg_fabricprocess($data, $enqId, $component_id, $dyingType);
        echo json_encode($data);
    }

    public function getPieceWeight()
    {
        $enqId        = xssclean($this->input->post('enquiry_id'));
        $component_id = xssclean($this->input->post('component_id'));
        $parts_weight = [];
        $piecequery   = "SELECT p.garm_parts_id AS id,d.gpdname AS gpdname , ROUND(cast(sum(m.`values`) AS DECIMAL(10,4)) / COUNT(m.garment_piece_weight_id) , 3) AS total FROM tbl_garment_piece_weight  p 
                             JOIN tbl_garment_piece_weight_mapping m ON p.id = m.garment_piece_weight_id
                             JOIN kn_master_garment_part_desc d ON p.garm_parts_id = d.id
                             WHERE p.enquiry_id = " . $enqId . " AND p.component_id = " . $component_id . " GROUP BY p.garm_parts_id";
        $parts_weight = $this->db->query($piecequery)->result_array();

        echo json_encode($parts_weight);
    }

    public function getDyingDropDownData()
    {
        $enqId        = xssclean($this->input->post('enquiry_id'));
        $component_id = xssclean($this->input->post('component_id'));
        $data         = [];
        $query        = "SELECT  c.garment_parts_id AS grament_id,c.blend_id, b.misc_name AS blend_name, c.content_id, cn.misc_name AS content_name, c.counts_id, cu.misc_name AS count_name, ROUND((weight.total * c.content_count_wise / 100),3) AS total  FROM tbl_yarn_cost c LEFT JOIN kn_master_yarn_misc b 
                        ON c.blend_id = b.id 
                        LEFT JOIN kn_master_yarn_misc cn ON cn.id = c.content_id
                        LEFT JOIN kn_master_yarn_misc cu ON cu.id = c.counts_id
                        LEFT JOIN (
                            SELECT w.garm_parts_id, ROUND(SUM(m.`values`),3) AS total FROM tbl_garment_piece_weight w 
                            LEFT JOIN  tbl_garment_piece_weight_mapping m 
                            ON w.id = m.garment_piece_weight_id 
                            WHERE w.enquiry_id = " . $enqId . " AND w.component_id = " . $component_id . "
                            GROUP BY m.garment_piece_weight_id
                                       )   AS weight
                        ON weight.garm_parts_id = c.garment_parts_id
                        WHERE c.enquiry_id = " . $enqId . " AND c.component_id = " . $component_id . " GROUP BY c.blend_id, c.content_id, c.counts_id";
        $data         = $this->db->query($query)->result_array();

        echo json_encode($data);
    }

    public function getfabricCost()
    {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $data  = $this->preCostingModel->getFabricCostGrid($enqId);
        echo json_encode($data);
    }

    public function getActualCost()
    {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $data  = $this->preCostingModel->getActualCostGrid($enqId);
        echo json_encode($data);
    }
    
    public function getIsrCost()
    {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $data  = $this->preCostingModel->getIsrCostGrid($enqId);
        echo json_encode($data);
    }
    
    public function getIorCost()
    {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $data  = $this->preCostingModel->getIorCostGrid($enqId);
        echo json_encode($data);
    }

    public function updateFabricCost()
    {
        $enqId  = xssclean($this->input->post('enquiry_id'));
        $object = xssclean($this->input->post('object'));
        $data   = json_decode($object);
        $data   = $this->preCostingModel->updateFabricCostGrid($enqId, $data);
        echo json_encode($data);
    }

    public function updateActualCost()
    {
        $enqId  = xssclean($this->input->post('enquiry_id'));
        $object = xssclean($this->input->post('object'));
        $data   = json_decode($object);
        $data   = $this->preCostingModel->updateActualCostGrid($enqId, $data);
        echo json_encode($data);
    }
    
    public function updateIsrCost()
    {
        $enqId  = xssclean($this->input->post('enquiry_id'));
        $object = xssclean($this->input->post('object'));
        $data   = json_decode($object);
        $data   = $this->preCostingModel->updateIsrCostGrid($enqId, $data);
        echo json_encode($data);
    }
    
    public function updateIorCost()
    {
        $enqId  = xssclean($this->input->post('enquiry_id'));
        $object = xssclean($this->input->post('object'));
        $data   = json_decode($object);
        $data   = $this->preCostingModel->updateIorCostGrid($enqId, $data);
        echo json_encode($data);
    }

}
