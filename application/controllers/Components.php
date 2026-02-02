<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property ComponentsModel $componentsModel
 * @property menquirymodel $menquirymodel
 */
class Components extends CI_Controller
{

    public $_companyId=0;
    public $_userId=0;

    public function __construct()
    {
        parent::__construct();
    }

    function _loggedUserInfo(){
        fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo   = fnGetUserLoggedInfo('1');
        $this->_companyId     = $ArrUserLoggedInfo['companyid'];
        $this->_userId        = $ArrUserLoggedInfo['id'];
    }


    public function componentCreation()
    {
        $VarId          = $this->uri->segment(3);
        $enquiry_id       = '';
        $ArrEnquiryInfo = array();
        $components     = [];
        $sizes = ['sizeType'=>1];
        if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
        {
            $enquiry_id       = base64_decode(urldecode($VarId));
            $this->load->model(CNFCOMPANY . 'menquirymodel');
            $this->_loggedUserInfo();
            // echo $enquiry_id;
            // echo '<br>';
            // echo $this->_companyId;
            $ArrEnquiryInfo = $this->menquirymodel->fnGetInfo('', $enquiry_id, $this->_companyId);
            $this->load->model( 'componentsModel');
            $components = $this->componentsModel->getComponentsByEnqId($enquiry_id);
            $sizeCharts = $this->componentsModel->getSizeMasters();
            $pcSizes = $this->componentsModel->getPcSize($enquiry_id);
            $sizes = $this->_getCharts($sizeCharts,$pcSizes);
            $checkDraftorNot = $this->componentsModel->checkDraftorNot($enquiry_id);
        }

        $last = $this->uri->segment_array();
        $lastURI = end($last);

        $this->load->view('/pre_costing/component_creation', ['enquiry_id' => $enquiry_id,
            'ArrEnquiryInfo' => $ArrEnquiryInfo, 'components' => $components,'checkDraftorNot'=> $checkDraftorNot, 
            'sizes'=>$sizes, 'lastSegement' => $lastURI
        ]);
    }

    public function insertComponent(){
        $this->load->helper('xssclean');
        $enquiry_id  = xssclean($this->input->post('enquiry_id'));
        $post_data = xssclean($this->input->post('post_data'));
        if(!empty($enquiry_id)){
            $this->load->model( 'componentsModel');
            $result = $this->componentsModel->insertAndUpdateComponents($enquiry_id,$post_data);
            // echo $result;
            echo json_encode($result);
        }else
            echo 'enquiry details not valid';
    }

    function _getCharts($sizeCharts,$pcSizes){
        
        $selections = !empty($pcSizes['size_ids'])?explode(',',$pcSizes['size_ids']):[];
        $sizeType = !empty($pcSizes['size_type'])?$pcSizes['size_type']:0;
        $totalsize = !empty($pcSizes['totalsize'])?$pcSizes['totalsize']:0;
        $selected_ids = [];
        $selectedNames = false;
        if($sizeType==1){
            foreach ($selections as $selection) {
            $selected_ids[$selection]=['id'=>$selection,'size_name'=>$selection];
        }
        }
        
        $std_options = [];
        $custom_size_values = [];
        if($sizeType==1){
        if(!empty($sizeCharts)){
            foreach ($sizeCharts as $sizeChart) {
                $std_options[] = ['id'=>$sizeChart['id'],'size_name'=>$sizeChart['size_name']];
                if(isset($selected_ids[$sizeChart['id']])){
                    $selectedNames .=$sizeChart['size_name'].', ';
                }
            }

            // for ($i = 1; $i <= 24; $i++)
            // {
            //     $val = '';
            //     if($sizeType == 2){
            //         $val = isset($selections[$i-1])??'';
            //     }
            //     $custom_size_values[] = ['custom_value' => $val];
            // }
        }
        }else{
           // $custom_size_values[]= $selections;
           if(!empty($sizeCharts)){
            foreach ($sizeCharts as $sizeChart) {
                $std_options[] = ['id'=>$sizeChart['id'],'size_name'=>$sizeChart['size_name']];
            }
        }
            for ($i = 1; $i <= $totalsize; $i++)
            {
                // $val = '';
                // if($sizeType == 2){
                //     $val = isset($selections[$i-1])?$selections[$i-1]:'';
                // }
               
                    $val = isset($selections[$i-1])?$selections[$i-1]:'';
                
                // $custom_size_values[] = ['custom_value' => $val];
                $custom_size_values[] = $val;
            }
             $selectedNames=!empty($pcSizes['size_ids'])?$pcSizes['size_ids']:'';
        }
       // var_dump($custom_size_values);
        return [
            'size_type'=>$sizeType,
            'std_options'=>$std_options,
            'custom_size_values'=>$custom_size_values,
            'custom_value'=>$custom_size_values,
            'selections'=>$selections,
            'selected_ids'=>$selected_ids,
            'selectedNames'=>$selectedNames,
            'totalsize'=>$totalsize
        ];
    }

    public function delete()
    {

        $this->load->helper('xssclean');
        $component_id = intval(xssclean($this->input->post('component_id')));
        if(!empty($component_id)){
            $this->load->model( 'componentsModel');
            $this->componentsModel->deleteComponentById($component_id);
            echo true;
        }else
            echo false;
    }

    public function deleteCombo()
    {

        $this->load->helper('xssclean');
        $comboId = intval(xssclean($this->input->post('comboId')));
        if(!empty($comboId)){
            $this->load->model( 'componentsModel');
            $this->componentsModel->deleteColorComboById($comboId);
            echo true;
        }else
            echo false;
    }
    
    public function getcleardraftstatus() 
    {   
        $this->load->helper('xssclean');
        $enquiry_id = intval(xssclean($this->input->post('enquiry_id')));
        if(!empty($enquiry_id)){
        $this->load->model( 'componentsModel');
        $ArrResult=$this->componentsModel->cleardraft($enquiry_id);
        echo json_encode($ArrResult);
        }else{
           echo false; 
        }
    }

}
