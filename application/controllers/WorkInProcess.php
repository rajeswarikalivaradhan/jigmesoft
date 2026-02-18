<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class WorkInProcess extends CI_Controller {

        public function __construct() {
            parent::__construct();
            //error_reporting(E_ALL);
            $this->load->helper('xssclean');
            fnIfCheckUserLoggedIn();
            $this->load->model(CNFCOMPANY . 'menquirymodel');
            $this->load->model(CNFCOMPANY . 'workinprogressmodel');
            $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
            $this->companyid = $ArrUserLoggedInfo['companyid'];
            $this->userid = $ArrUserLoggedInfo['id'];
            $this->userid_type = $ArrUserLoggedInfo['usertype'];
            $this->mysqldatetime = date('Y-m-d H:i:s');
            $this->load->model('WorkInProcessModel');
            $this->load->model('commonmodel');
            $this->load->model('managementmodel');
            $this->load->model(CNFCOMPANY . "mcadrequestmodel");
            $this->load->model(CNFCOMPANY . 'orderentrymodel');
            $ArrObjsubscriber_id = $this->commonmodel->getsubscriberid($this->userid = $ArrUserLoggedInfo['id']);
            $this->subscriber_id = 'Sub_Id_' . $ArrObjsubscriber_id->subscriber_id;
              $this->subb_id = $ArrObjsubscriber_id->subscriber_id;
        }

        public function index() {
            $VarId = $this->uri->segment(3);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            $userid_types=$this->userid_type ;
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $data['enquiry_id'] = $VarEnqId;
            $data['type'] = 'orderEntry';
             $subid=$this->subb_id;
           
          $subcompany_data = $this->WorkInProcessModel->getsubscribercompanydetail($subid);

            $getPackingDetails = $this->WorkInProcessModel->getPackingDetailss($VarEnqId);
            $checkDraftorNot = $this->WorkInProcessModel->checkDraftorNot($VarEnqId);
            $draftVal = $this->WorkInProcessModel->get_draft_valuee($VarEnqId);
            $totVal = $this->WorkInProcessModel->get_req_empty_valuee($VarEnqId);
            $this->load->view('workinprocess/details',
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'packingDetails' => $getPackingDetails,
                                'checkDraftorNot'=> $checkDraftorNot,
                                'draftVal' => $draftVal,
                                'totVal' => $totVal,'loguserid' => $userid_types,
                                'subcompany_data' => $subcompany_data
                            ));
        }

        // ********** ORDER PROCESSING STARTS HERE *********** /

        public function commonheaderdata($VarEnquiryId)
        {
            $sizeChart    = $this->WorkInProcessModel->getSizeChart($VarEnquiryId);
            $sizeMaster   = $this->WorkInProcessModel->getSizeMaster($sizeChart);

            $sizeArray = [];
            foreach ($sizeMaster as $key => $value) {
                array_push($sizeArray, $value['size_name']);
            }
            $sizeValue = implode(", ",$sizeArray);

            $ArrCompanyRes       = $this->companymodel->fnGetCompanyInfo($this->companyid);
            $ArrEnquiryDetails   = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarEnquiryId, $this->companyid);
            $ArrEnquiryDetails   = @$ArrEnquiryDetails[0];
            $VarHashEnquiryId    = $this->uri->segment(3);
            $ArrMerchant         = $this->commonmodel->getMerchantData($this->companyid, 1, $ArrEnquiryDetails['merchantid']);
            $ArrTeam             = $this->commonmodel->getTeamDetails($this->companyid, $ArrEnquiryDetails['merchantid']);
            $ArrCommonData       = $this->orderentrymodel->getCommonData($VarEnquiryId, $this->companyid);
            // echo "<pre>";
            // print_r($ArrEnquiryDetails);
            // echo "</pre>";
            $ArrCommonHeaderData = array(
                'companyName'       => @$ArrCompanyRes[0]['companyname'], 'companyAddress'    => @$ArrCompanyRes[0]['address'],
                'VarEnquiryId'      => $VarEnquiryId, 'VarHashEnquiryId'  => @$VarHashEnquiryId, 'merchantName'      => @$ArrMerchant[0]['contactname'],
                'merchantMobile'    => @$ArrMerchant[0]['mobile'], 'merchantCode'      => @$ArrMerchant[0]['code'],
                'merchantEmail'     => @$ArrMerchant[0]['username'], 'ArrEnquiryDetails' => $ArrEnquiryDetails,
                'ArrCommonData'     => @$ArrCommonData, 'ArrTeam' => @$ArrTeam[0], 'sizeValue' => $sizeValue
            );
            return $ArrCommonHeaderData;
        }
        
        // ********** ORDER PROCESSING ENDS HERE *********** /

        public function getComboColourDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getComboColourDetailss($enqId);
            echo json_encode($data);
        }
        
        public function updateColorComboDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateColorComboDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** PO SIZE WISE QUANTITY BREAKUP STARTS HERE *********** /

        public function getPoSizewiseDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getPoSizewiseDetailsS($enqId);
            echo json_encode($data);
        }
        
        public function updatePOSizeDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updatePOSizeDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** COMPONENT INTAKE WISE ITEMIZED STARTS HERE *********** /
        public function getOrderEntryComponentItemized() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getOrderEntryComponentItemizedd($enqId);
            echo json_encode($data);
        }

        public function updateOrderEntryComponentItemized() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateOrderEntryComponentItemizedd($req_data, $id);
            echo json_encode($data);
        }

        // ********** PO WISE DELIVERY STARTS HERE *********** /

        public function getPoWiseDeliveryDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getPoWiseDeliveryDetailss($enqId);
            echo json_encode($data);
        }
        
        public function updatePoWiseDeliveryDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updatePoWiseDeliveryDetailss($req_data, $id);
            echo json_encode($data);
        }
        
        // ********** COMPLETE GARMENT PROCESS FLOW STARTS HERE *********** /
        public function getOrderEntryCompleteProcess() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getOrderEntryCompleteProcesss($enqId);
            echo json_encode($data);
        }

        public function comboFilter() {
            $id = xssclean($this->input->post('id'));
            $data = $this->WorkInProcessModel->comboFilterr($id);
            echo json_encode($data);
        }
        
        public function updatePoCompleteProcess() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updatePoCompleteProcesss($req_data, $id);
            echo json_encode($data);
        }

        // ********** CAD REQUIREMENT DETAILS STARTS HERE *********** /

        public function getCADRequirement() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getCADRequirementt($enqId);
            echo json_encode($data);
        }
        
        public function updateCADRequirement() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateCADRequirementt($req_data, $id);
            echo json_encode($data);
        }
        
        // ********** SAMPLE DETAILS STARTS HERE *********** /

        public function getSampleDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            //$data = $this->WorkInProcessModel->getSampleDetailss($enqId);
              $data = $this->WorkInProcessModel->getSampleDetailssa($enqId);
            echo json_encode($data);
        }
        
        public function get_req_empty_value() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->get_req_empty_valuee($enqId);
            echo json_encode($data);
        }
        
        public function updateSampleDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateSampleDetailss($req_data, $id);
            echo json_encode($data);
        }
        
        
        // ********** EMBELLISHMENT DETAILS STARTS HERE *********** /

        public function getEmbellishmentDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getEmbellishmentDetailss($enqId);
            echo json_encode($data);
        }
        
        public function updateEmbellishmentDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateEmbellishmentDetailss($req_data, $id);
            echo json_encode($data);
        }
        
        // ********** EMBELLISHMENT APPROVAL DETAILS STARTS HERE *********** /

        public function getEmbellishmentStatusDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getEmbellishmentStatusDetailss($enqId);
            echo json_encode($data);
        }
        
        public function updateEmbellishmentStatusDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateEmbellishmentStatusDetailss($req_data, $id);
            echo json_encode($data);
        }
        
        // ********** EMBELLISHMENT VENDOR DETAILS STARTS HERE *********** /

        public function getEmbellishmentVendorDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getEmbellishmentVendorDetailss($enqId);
            echo json_encode($data);
        }
        
        public function updateEmbellishmentVendorDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateEmbellishmentVendorDetailss($req_data, $id);
            echo json_encode($data);
        }
        
        // ********** BOM 1 SAMPLING APPROVAL DETAILS STARTS HERE *********** /

        public function getBOMSamplingApprovalDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getBOMSamplingApprovalDetailss($enqId);
            echo json_encode($data);
        }
        
        public function updateBOMSamplingApprovalDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateBOMSamplingApprovalDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** BOM 1 REQUIREMENT DETAILS STARTS HERE *********** /
        
        public function getBOM1RequirementDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getBOM1RequirementDetailss($enqId);
            echo json_encode($data);
        }
        
        public function updateBOM1RequirementDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateBOM1RequirementDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** BOM 1 CONSOLIDATE DETAILS STARTS HERE *********** /

        public function getBOM1ConsolidatedReq() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getBOM1ConsolidatedReqq($enqId);
            echo json_encode($data);
        }



        public function updateBom1ReqConsolidated() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateBom1ReqConsolidatedd($req_data, $id);
            echo json_encode($data);
        }

        // ********** BOM 1 SOURCE APPROVAL DETAILS STARTS HERE *********** /
        
        public function getBOM1Sourcing() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getBOM1Sourcingg($enqId);
            echo json_encode($data);
        }

        public function updateBom1Sourcing() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateBom1Sourcingg($req_data, $id);
            echo json_encode($data);
        }

        // ********** BOM 1 SAMPLE DESPATCH STARTS HERE *********** /
        
        public function get_bom1_sampling_despatch() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->get_bom1_sampling_despatchh($enqId);
            echo json_encode($data);
        }

        public function update_bom1_sampling_despatch() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->update_bom1_sampling_despatchh($req_data, $id);
            echo json_encode($data);
        }

        // asasasas
        // asasasas
        // asasas

        // ********** BOM 2 SAMPLING APPROVAL DETAILS STARTS HERE *********** /

        public function getBOM2SamplingApprovalDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getBOM2SamplingApprovalDetailss($enqId);
            echo json_encode($data);
        }
        
        public function updateBOM2SamplingApprovalDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateBOM2SamplingApprovalDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** BOM 2 REQUIREMENT DETAILS STARTS HERE *********** /
        
        public function getBOM2RequirementDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getBOM2RequirementDetailss($enqId);
            echo json_encode($data);
        }
        
        public function updateBOM2RequirementDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateBOM2RequirementDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** BOM 2 CONSOLIDATE DETAILS STARTS HERE *********** /

        public function getBOM2ConsolidatedReq() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getBOM2ConsolidatedReqq($enqId);
            echo json_encode($data);
        }

        public function updateBom2ReqConsolidated() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateBom2ReqConsolidatedd($req_data, $id);
            echo json_encode($data);
        }

        // ********** BOM 2 SOURCE APPROVAL DETAILS STARTS HERE *********** /
        
        public function getBOM2Sourcing() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getBOM2Sourcingg($enqId);
            echo json_encode($data);
        }

        public function updateBom2Sourcing() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateBom2Sourcingg($req_data, $id);
            echo json_encode($data);
        }

        // ********** BOM 2 SAMPLE DESPATCH STARTS HERE *********** /
        
        public function get_bom2_sampling_despatch() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->get_bom2_sampling_despatchh($enqId);
            echo json_encode($data);
        }

        public function update_bom2_sampling_despatch() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->update_bom2_sampling_despatchh($req_data, $id);
            echo json_encode($data);
        }

        // // ********** BOM 2 SAMPLING APPROVAL DETAILS STARTS HERE *********** /

        // public function getBOM2SamplingApprovalDetails() {
        //     $enqId = xssclean($this->input->post('enquiry_id'));
        //     $data = $this->WorkInProcessModel->getBOM2SamplingApprovalDetailss($enqId);
        //     echo json_encode($data);
        // }
        
        // public function updateBOM2SamplingApprovalDetails() {
        //     $object = xssclean($this->input->post('data'));
        //     $id = xssclean($this->input->post('enquiry_id'));
        //     $req_data = json_decode($object);
        //     $data = $this->WorkInProcessModel->updateBOM2SamplingApprovalDetailss($req_data, $id);
        //     echo json_encode($data);
        // }

        // // ********** BOM 2 REQUIREMENT DETAILS STARTS HERE *********** /
        
        // public function getBOM2RequirementDetails() {
        //     $enqId = xssclean($this->input->post('enquiry_id'));
        //     $data = $this->WorkInProcessModel->getBOM2RequirementDetailss($enqId);
        //     echo json_encode($data);
        // }
        
        // public function updateBOM2RequirementDetails() {
        //     $object = xssclean($this->input->post('data'));
        //     $id = xssclean($this->input->post('enquiry_id'));
        //     $req_data = json_decode($object);
        //     $data = $this->WorkInProcessModel->updateBOM2RequirementDetailss($req_data, $id);
        //     echo json_encode($data);
        // }

        // // ********** BOM 2 CONSOLIDATE DETAILS STARTS HERE *********** /

        // public function getBOM2ConsolidatedReq() {
        //     $enqId = xssclean($this->input->post('enquiry_id'));
        //     $data = $this->WorkInProcessModel->getBOM2ConsolidatedReqq($enqId);
        //     echo json_encode($data);
        // }

        // public function updateBom2ReqConsolidated() {
        //     $object = xssclean($this->input->post('data'));
        //     $id = xssclean($this->input->post('enquiry_id'));
        //     $req_data = json_decode($object);
        //     $data = $this->WorkInProcessModel->updateBom2ReqConsolidatedd($req_data, $id);
        //     echo json_encode($data);
        // }

        // // ********** BOM 2 SOURCE APPROVAL DETAILS STARTS HERE *********** /
        
        // public function getBOM2Sourcing() {
        //     $enqId = xssclean($this->input->post('enquiry_id'));
        //     $data = $this->WorkInProcessModel->getBOM2Sourcingg($enqId);
        //     echo json_encode($data);
        // }

        // public function updateBom2Sourcing() {
        //     $object = xssclean($this->input->post('data'));
        //     $id = xssclean($this->input->post('enquiry_id'));
        //     $req_data = json_decode($object);
        //     $data = $this->WorkInProcessModel->updateBom2Sourcingg($req_data, $id);
        //     echo json_encode($data);
        // }

        //*************** ORDER ENTRY REMARKS IMAGE DETAILS STARTS HERE *************** //
        
        public function getRemarksNImageDetails() {
            $data = $this->input->post();
            $result = $this->WorkInProcessModel->getRemarksNImageDetailss($data);
            $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
            $user_id=$this->userid = $ArrUserLoggedInfo['id'];
            $ArrObjsubscriber_id = $this->commonmodel->getsubscriberid($user_id);
            $subscriber_id=$ArrObjsubscriber_id->subscriber_id;
            $result['subscriber_id'] = 'Sub_Id_'.$subscriber_id;
            echo json_encode($result);
        }

        public function updateWipFormDetails() {
           // $testData = "Test data";

          
  
            $ArrExtensions = FILE_EXTENSIONS;
            $id = xssclean($this->input->post('enquiry_id'));
            $type = xssclean($this->input->post('type'));
            $remarks = xssclean($this->input->post('remarks'));

          
           
            $filepath = "";
            if($type=="orderEntry") {
                 $filepath = 'uploads/workinprocess/orderentry'. DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR;
                //$filepath = 'uploads/workinprocess/orderentry/';
            }
           
            if($type=="cad") {
                 $filepath = 'uploads/workinprocess/cad'. DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR;
            }
            
            if($type=="sample") {
               
                 $filepath = 'uploads/workinprocess/sample'. DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR;
            }
            
            if($type=="embellishment") {
                //$filepath = 'uploads/workinprocess/embellishment/';
                 $filepath = 'uploads/workinprocess/embellishment'. DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR;
                
            }
            
            if($type=="bom1") {
                
                 $filepath = 'uploads/workinprocess/bom1'. DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR;
            }
            
            if($type=="bom2") {
              
                 $filepath = 'uploads/workinprocess/bom2'. DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR;
            }
             if($type=="packing") {
               
                 $filepath = 'uploads/workinprocess/packing'. DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR;
            }
            
            if($type=="finalinspection") {
                
                 $filepath = 'uploads/workinprocess/finalinspection'. DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR;
            }
             if($type=="documentation") {
                
                 $filepath = 'uploads/workinprocess/finalinspection'. DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR;
            }

              if (file_exists($filepath)) {
            } else {
            mkdir($filepath, 0777, true);
             }
            
            if (isset($_FILES["myFile"])) {
                  
                $ret = array();
                // echo $_FILES["myFile"]["name"];
                $fName = $_FILES['myFile']['name'];
                $fName = pathinfo($fName, PATHINFO_FILENAME);

                $extension = pathinfo($_FILES["myFile"]["name"], PATHINFO_EXTENSION);
                if (in_array($extension, $ArrExtensions)) {
                    $rand = rand();
                    // $fileName = str_replace('&', '_', $_FILES["myFile"]["name"]);

                    // $fileName = trim($fName).$rand.'.'.$extension;
                    // file_put_contents("error_log", print_r($fileName, true));

                    $fileName = str_replace('&', '_', $_FILES["myFile"]["name"]);
                    $fileName = trim($fName) . $rand . '.' . $extension;
                     $fileName = preg_replace('/\s+/', '_', $fileName); // Replace spaces with underscores
                    $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $fileName); // Remove unwanted characters

                      /**MAX file size 7 MB**/
                    if ($_FILES["myFile"]["size"] <= MAXUPLSIZE) {
                        if (move_uploaded_file($_FILES["myFile"]["tmp_name"], $filepath . $fileName))
                           
                            $ret[] = $this->WorkInProcessModel->updateWipFormDetailss($type, $id, $fileName, $remarks);
                    } else {
                        $ret[] = 'Err';
                    }
                } else {
                    $ret[] = 'Err';
                }
                echo json_encode($ret);
            }
            else {
                
                $fileName = '';
                $ret[] = $this->WorkInProcessModel->updateWipFormDetailss($type, $id, $fileName, $remarks);
                echo json_encode($ret);
            }
    
        }

        /**
         * Upload multiple files for Test List; store in DB (one row per grid row, files as JSON).
         * POST: enquiry_id, row_id (DB id, empty for new row), document_type, myFile[].
         * Files saved to uploads/.../Enq_X/{document_type}_{id}/. Returns { status, id, files, folder_name }.
         */
        public function uploadTestListDocument()
        {
            $enquiry_id    = (int) xssclean($this->input->post('enquiry_id'));
            $row_id        = xssclean($this->input->post('row_id'));
            $document_type = xssclean($this->input->post('document_type'));
            $ArrExtensions = FILE_EXTENSIONS;
            $normalized_type = $this->normalizeTestListDocumentType($document_type);

            if ($enquiry_id < 1 || $normalized_type === '') {
                echo json_encode(array('status' => 'error', 'msg' => 'Document Type is required.'));
                return;
            }

            $row_id = ($row_id !== '' && $row_id !== null) ? (int) $row_id : null;
            if ($row_id !== null && $row_id > 0) {
                if ($this->WorkInProcessModel->isDuplicateTestListDocumentType($enquiry_id, $document_type, $row_id)) {
                    echo json_encode(array('status' => 'error', 'msg' => 'This Document Type already exists for this enquiry.'));
                    return;
                }
            } else {
                if ($this->WorkInProcessModel->isDuplicateTestListDocumentType($enquiry_id, $document_type)) {
                    echo json_encode(array('status' => 'error', 'msg' => 'This Document Type already exists for this enquiry.'));
                    return;
                }
            }

            if ($row_id === null || $row_id < 1) {
                $row_id = $this->WorkInProcessModel->insertTestListDocumentRow($enquiry_id, $document_type ?: '', array());
                if ($row_id < 1) {
                    echo json_encode(array('status' => 'error', 'msg' => 'This Document Type already exists for this enquiry.'));
                    return;
                }
            }

            $folder_name = $this->buildTestListFolderName($document_type, $row_id);
            $base_dir = 'uploads/workinprocess/testlist' . DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR . 'Enq_' . $enquiry_id . DIRECTORY_SEPARATOR;
            $filepath = $base_dir . $folder_name . DIRECTORY_SEPARATOR;
            if (!is_dir($filepath)) {
                mkdir($filepath, 0777, true);
            }

            $uploaded = array();
            $files = isset($_FILES['myFile']) ? $_FILES['myFile'] : null;
            if ($files && isset($files['name'])) {
                $names = is_array($files['name']) ? $files['name'] : array($files['name']);
                $tmp_names = is_array($files['tmp_name']) ? $files['tmp_name'] : array($files['tmp_name']);
                $sizes = is_array($files['size']) ? $files['size'] : array($files['size']);
                $errors = is_array($files['error']) ? $files['error'] : array($files['error']);

                for ($i = 0; $i < count($names); $i++) {
                    if (isset($errors[$i]) && $errors[$i] !== UPLOAD_ERR_OK) continue;
                    $name = $names[$i];
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (!in_array($ext, $ArrExtensions)) continue;
                    if (isset($sizes[$i]) && $sizes[$i] > MAXUPLSIZE) continue;

                    $fName = pathinfo($name, PATHINFO_FILENAME);
                    $fileName = preg_replace('/\s+/', '_', $fName) . '_' . rand() . '.' . $ext;
                    $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $fileName);
                    $dest = $filepath . $fileName;
                    if (move_uploaded_file($tmp_names[$i], $dest)) {
                        $uploaded[] = $fileName;
                    }
                }
            }

            if (count($uploaded) > 0) {
                $updated = $this->WorkInProcessModel->updateTestListDocumentRow($row_id, $enquiry_id, $document_type ?: '', $uploaded);
                if (!$updated) {
                    echo json_encode(array('status' => 'error', 'msg' => 'This Document Type already exists for this enquiry.'));
                    return;
                }
            }

            echo json_encode(array('status' => 'success', 'id' => $row_id, 'files' => $uploaded, 'folder_name' => $folder_name));
        }

        /**
         * Get test list document rows from DB (one row per grid row, uploaded_files as JSON).
         * GET/POST: enquiry_id. Returns { "base_url": "...", "rows": [ { "id", "document_type", "uploaded_files": [] }, ... ] }.
         */
        public function getTestListDocuments()
        {
            $enquiry_id = (int) xssclean($this->input->get_post('enquiry_id'));
            $rel_path = 'uploads/workinprocess/testlist/' . str_replace(DIRECTORY_SEPARATOR, '/', $this->subscriber_id) . '/Enq_' . $enquiry_id . '/';
            $rows = $this->WorkInProcessModel->getTestListDocumentRows($enquiry_id);
            foreach ($rows as &$r) {
                $r['folder_name'] = $this->resolveExistingTestListFolderName(
                    $enquiry_id,
                    isset($r['document_type']) ? $r['document_type'] : '',
                    isset($r['id']) ? $r['id'] : 0
                );
            }
            $result = array(
                'base_url' => base_url() . $rel_path,
                'rows' => $rows
            );
            echo json_encode($result);
        }

        /**
         * Save file upload grid: update document_type for each row. POST: enquiry_id, data (JSON array of [id, document_type] per row).
         */
        public function saveTestListDocuments()
        {
            $enquiry_id = (int) xssclean($this->input->post('enquiry_id'));
            $raw = xssclean($this->input->post('data'));
            $rows = json_decode($raw, true);
            if (!is_array($rows)) {
                echo json_encode(array('status' => 'error', 'msg' => 'Invalid data'));
                return;
            }

            $existingRows = $this->WorkInProcessModel->getTestListDocumentRows($enquiry_id);
            $existingById = array();
            foreach ($existingRows as $er) {
                $existingById[(int) $er['id']] = $er;
            }

            // Validate duplicates in submitted grid (per enquiry).
            $seenTypes = array();
            foreach ($rows as $row) {
                $doc_type = isset($row[1]) ? $row[1] : '';
                $normalized = $this->normalizeTestListDocumentType($doc_type);
                if ($normalized === '') {
                    continue;
                }
                if (isset($seenTypes[$normalized])) {
                    echo json_encode(array('status' => 'error', 'msg' => 'Duplicate Document Type is not allowed in the same enquiry.'));
                    return;
                }
                $seenTypes[$normalized] = 1;
            }

            $keepIds = array();
            foreach ($rows as $row) {
                $id = isset($row[0]) ? $row[0] : '';
                $doc_type = isset($row[1]) ? $row[1] : '';
                $id = ($id !== '' && $id !== null) ? (int) $id : 0;
                if ($id > 0) {
                    $keepIds[] = $id;
                    if (!$this->WorkInProcessModel->updateTestListDocumentType($id, $enquiry_id, $doc_type)) {
                        echo json_encode(array('status' => 'error', 'msg' => 'Duplicate Document Type is not allowed in the same enquiry.'));
                        return;
                    }
                } elseif ($doc_type !== '') {
                    $newId = $this->WorkInProcessModel->insertTestListDocumentRow($enquiry_id, $doc_type, array());
                    if ($newId < 1) {
                        echo json_encode(array('status' => 'error', 'msg' => 'Duplicate Document Type is not allowed in the same enquiry.'));
                        return;
                    }
                }
            }

            // Remove rows deleted in grid so they do not reappear after save.
            foreach ($existingById as $existingId => $existingRow) {
                if (!in_array((int) $existingId, $keepIds, true)) {
                    $this->deleteTestListRowArtifacts(
                        $enquiry_id,
                        (int) $existingId,
                        isset($existingRow['document_type']) ? $existingRow['document_type'] : ''
                    );
                    $this->WorkInProcessModel->deleteTestListDocumentRow((int) $existingId, $enquiry_id);
                }
            }
            echo json_encode(array('status' => 'success', 'msg' => 'Saved successfully'));
        }

        private function normalizeTestListDocumentType($value)
        {
            return strtolower(trim(preg_replace('/\s+/', ' ', (string) $value)));
        }

        /**
         * Delete one uploaded file from a specific test-list document row.
         * POST: enquiry_id, row_id, file_name
         */
        public function deleteTestListDocumentFile()
        {
            $enquiry_id = (int) xssclean($this->input->post('enquiry_id'));
            $row_id = (int) xssclean($this->input->post('row_id'));
            $file_name = basename((string) xssclean($this->input->post('file_name')));

            if ($enquiry_id < 1 || $row_id < 1 || $file_name === '') {
                echo json_encode(array('status' => 'error', 'msg' => 'Invalid request'));
                return;
            }

            $deleted = $this->WorkInProcessModel->deleteTestListDocumentFile($row_id, $enquiry_id, $file_name);
            if (!$deleted) {
                echo json_encode(array('status' => 'error', 'msg' => 'File not found'));
                return;
            }

            $rowData = $this->WorkInProcessModel->getTestListDocumentRowById($row_id, $enquiry_id);
            $folder_name = $this->resolveExistingTestListFolderName($enquiry_id, isset($rowData['document_type']) ? $rowData['document_type'] : '', $row_id);
            $base_dir = 'uploads/workinprocess/testlist' . DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR . 'Enq_' . $enquiry_id . DIRECTORY_SEPARATOR;
            $folder_path = $base_dir . $folder_name . DIRECTORY_SEPARATOR;
            $filepath = $folder_path . $file_name;
            if (is_file($filepath)) {
                @unlink($filepath);
            } else {
                $legacy_path = $base_dir . 'row_' . $row_id . DIRECTORY_SEPARATOR . $file_name;
                if (is_file($legacy_path)) {
                    @unlink($legacy_path);
                    $folder_path = $base_dir . 'row_' . $row_id . DIRECTORY_SEPARATOR;
                }
            }

            $folder_deleted = false;
            $remaining = isset($rowData['uploaded_files']) && is_array($rowData['uploaded_files']) ? $rowData['uploaded_files'] : array();
            if (count($remaining) === 0) {
                if (is_dir($folder_path)) {
                    $folder_deleted = @rmdir($folder_path) ? true : false;
                }
                $alt_folder_path = $base_dir . $this->buildTestListFolderName(isset($rowData['document_type']) ? $rowData['document_type'] : '', $row_id) . DIRECTORY_SEPARATOR;
                if (is_dir($alt_folder_path)) {
                    $folder_deleted = (@rmdir($alt_folder_path) || $folder_deleted) ? true : $folder_deleted;
                }
            }

            echo json_encode(array('status' => 'success', 'msg' => 'File deleted', 'folder_deleted' => $folder_deleted));
        }

        /**
         * Delete one test-list row with its files/folder immediately.
         * POST: enquiry_id, row_id
         */
        public function deleteTestListDocumentRow()
        {
            $enquiry_id = (int) xssclean($this->input->post('enquiry_id'));
            $row_id = (int) xssclean($this->input->post('row_id'));
            if ($enquiry_id < 1 || $row_id < 1) {
                echo json_encode(array('status' => 'error', 'msg' => 'Invalid request'));
                return;
            }

            $rowData = $this->WorkInProcessModel->getTestListDocumentRowById($row_id, $enquiry_id);
            if (empty($rowData)) {
                echo json_encode(array('status' => 'success', 'msg' => 'Already deleted'));
                return;
            }

            $this->deleteTestListRowArtifacts(
                $enquiry_id,
                $row_id,
                isset($rowData['document_type']) ? $rowData['document_type'] : ''
            );
            $this->WorkInProcessModel->deleteTestListDocumentRow($row_id, $enquiry_id);

            echo json_encode(array('status' => 'success', 'msg' => 'Row deleted'));
        }

        private function sanitizeFolderToken($value)
        {
            $value = strtolower(trim((string) $value));
            $value = preg_replace('/[^a-z0-9]+/', '_', $value);
            $value = trim($value, '_');
            return $value !== '' ? $value : 'document';
        }

        private function buildTestListFolderName($document_type, $row_id)
        {
            $row_id = (int) $row_id;
            $token = $this->sanitizeFolderToken($document_type);
            return $token . '_' . $row_id;
        }

        private function resolveExistingTestListFolderName($enquiry_id, $document_type, $row_id)
        {
            $row_id = (int) $row_id;
            $preferred = $this->buildTestListFolderName($document_type, $row_id);
            $legacy = 'row_' . $row_id;
            $base_dir = 'uploads/workinprocess/testlist' . DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR . 'Enq_' . (int) $enquiry_id . DIRECTORY_SEPARATOR;

            if (is_dir($base_dir . $preferred . DIRECTORY_SEPARATOR)) {
                return $preferred;
            }
            if (is_dir($base_dir . $legacy . DIRECTORY_SEPARATOR)) {
                return $legacy;
            }
            return $preferred;
        }

        private function deleteTestListRowArtifacts($enquiry_id, $row_id, $document_type)
        {
            $base_dir = 'uploads/workinprocess/testlist' . DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR . 'Enq_' . (int) $enquiry_id . DIRECTORY_SEPARATOR;
            $paths = array(
                $base_dir . $this->buildTestListFolderName($document_type, $row_id) . DIRECTORY_SEPARATOR,
                $base_dir . 'row_' . (int) $row_id . DIRECTORY_SEPARATOR
            );

            foreach ($paths as $path) {
                $this->deleteDirectoryRecursive($path);
            }
        }

        private function deleteDirectoryRecursive($dir)
        {
            if (!is_dir($dir)) return;
            $items = @scandir($dir);
            if ($items === false) return;
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                $full = $dir . $item;
                if (is_dir($full)) {
                    $this->deleteDirectoryRecursive($full . DIRECTORY_SEPARATOR);
                } else {
                    @unlink($full);
                }
            }
            @rmdir($dir);
        }

        public function updateWipRemarksDetails()
        {
            $data = $this->input->post();
            $result = $this->WorkInProcessModel->updateWipRemarksDetailss($data);
            echo json_encode($result);
        }

        public function deleteImageDetails()
        {
            $data = $this->input->post();
            $result = $this->WorkInProcessModel->deleteImageDetailss($data);
            echo json_encode($result);
        }

        public function updateWipDetails()
        {
            $data = $this->input->post();
            $result = $this->WorkInProcessModel->updateWipDetailss($data);
            echo json_encode($result);
        }

        //*************** ORDER ENTRY REMARKS IMAGE DETAILS ENDS HERE *************** //

        //*************** ASSORTMENT TYPE STARTS HERE *************** //

        public function assortmentType() {
            $VarId = $this->uri->segment(3);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            $getAssortmentType =  $this->WorkInProcessModel->getAssortmentType();
            $getAssortmentDetails =  $this->WorkInProcessModel->getAssortmentDetails($VarEnqId);
            $this->load->view('workinprocess/assortment_type', 
                array('VarEnqId'=>$VarEnqId, 'assortmentType' => $getAssortmentType, 
                      'assortmentDetails' => $getAssortmentDetails)
            );
        }
        
        public function updateAssortmentDetails() {
            $data = $this->input->post();
            $result = $this->WorkInProcessModel->updateAssortmentDetailss($data);
            echo json_encode($result);
        }
        
        public function deleteAssortEntry() {
            $ids = xssclean($this->input->post('data'));
            $data = $this->WorkInProcessModel->deleteAssortEntryy($ids);
            echo json_encode($data);
        }

        public function getAssortmentDesignSheetData() {
            $enquiry_id = (int) xssclean($this->input->get_post('enquiry_id'));
            if ($enquiry_id < 1) {
                echo json_encode(array('status' => 'error', 'msg' => 'Invalid enquiry id'));
                return;
            }
            $row = $this->WorkInProcessModel->getAssortmentDesignSheetData($enquiry_id);
            echo json_encode(array(
                'status' => 'success',
                'sheet_data' => !empty($row) ? $row['sheet_data'] : null
            ));
        }

        public function saveAssortmentDesignSheetData() {
            $enquiry_id = (int) xssclean($this->input->post('enquiry_id'));
            $sheet_data = $this->input->post('sheet_data', false);
            if ($enquiry_id < 1) {
                echo json_encode(array('status' => 'error', 'msg' => 'Invalid enquiry id'));
                return;
            }
            if ($sheet_data === null || $sheet_data === '') {
                echo json_encode(array('status' => 'error', 'msg' => 'Sheet data is required'));
                return;
            }

            $decoded = json_decode($sheet_data, true);
            if (!is_array($decoded)) {
                echo json_encode(array('status' => 'error', 'msg' => 'Invalid sheet data'));
                return;
            }

            $saved = $this->WorkInProcessModel->saveAssortmentDesignSheetData($enquiry_id, json_encode($decoded));
            if (!$saved) {
                echo json_encode(array('status' => 'error', 'msg' => 'Unable to save data'));
                return;
            }
            echo json_encode(array('status' => 'success', 'msg' => 'Saved successfully'));
        }

        //*************** ASSORTMENT TYPE ENDS HERE *************** //
        
        //*************** PACKING STARTS HERE *************** //

        public function getPackingDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getPackingDetailss($enqId);
            echo json_encode($data);
        }
        
        public function updatePackingDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $type = xssclean($this->input->post('assortment_type'));
            $packing_id = xssclean($this->input->post('packing_id'));
            $pck_combo_color_id = xssclean($this->input->post('pck_combo_color_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updatePackingDetailss($req_data, $id,$type, $packing_id, $pck_combo_color_id);
            echo json_encode($data);
        }

        //*************** PACKING ENDS HERE *************** //

        // ********** MANAGEMENT CHECK LIST DETAILS STARTS HERE *********** /

         public function getManagementChecklistDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getManagementChecklistDetailss($enqId);
            echo json_encode($data);
        }

        public function updateManagementCheckList() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateManagementCheckListt($req_data, $id);
            echo json_encode($data);
        }

        // ********** FINAL INSPECTION STANDARD DETAILS STARTS HERE *********** /

        public function getFinalInspectionStandardDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getFinalInspectionStandardDetailss($enqId);
            echo json_encode($data);
        }

        public function updateFinalInspectionStandard() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateFinalInspectionStandardd($req_data, $id);
            echo json_encode($data);
        }

        // ********** DETAILS OF CONSIGNEE LOGISTICS STARTS HERE *********** /

        public function getDetailsOfConsigneeLogistics() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getDetailsOfConsigneeLogisticss($enqId);
            echo json_encode($data);
        }

        public function updateDetailsOfConsigneeLogistics() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateDetailsOfConsigneeLogisticss($req_data, $id);
            echo json_encode($data);
        }

        // ********** LAB TESTING ACCEPTANCE INTERNAL STARTS HERE *********** /

        public function getLabTestingAcceptanceInternalDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getLabTestingAcceptanceInternalDetailss($enqId);
            echo json_encode($data);
        }

        public function updateLabTestingAcceptanceInternalDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateLabTestingAcceptanceInternalDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** LAB TESTING ACCEPTANCE EXTERNAL STARTS HERE *********** /

        public function getLabTestingAcceptanceExternalDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getLabTestingAcceptanceExternalDetailss($enqId);
            echo json_encode($data);
        }

        public function updateLabTestingAcceptanceExternalDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateLabTestingAcceptanceExternalDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** EXTERNAL LAB TESTING AUTHORITY STARTS HERE *********** /

        public function getExternalLabTestingAuthorityDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getExternalLabTestingAuthorityDetailss($enqId);
            echo json_encode($data);
        }

        public function updateExternalLabTestingAuthorityDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateExternalLabTestingAuthorityDetailss($req_data, $id);
            echo json_encode($data);
        }


        // ********** COLOR WISE GARMENT PART DETAILS STARTS HERE *********** /

        public function getColourWiseGarmentPartsDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getColourWiseGarmentPartsDetailss($enqId);
            echo json_encode($data);
        }

        public function updateColourWiseGarmentPartsDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateColourWiseGarmentPartsDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** GERMENT PARTS WISE QTY DETAILS STARTS HERE *********** /

        public function getGarmentPartsWiseQtyDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getGarmentPartsWiseQtyDetailss($enqId);
            echo json_encode($data);
        }

        // fabric

        // ********** COLOR WISE GARMENT PART DETAILS STARTS HERE *********** /

        public function fabric_program() {
            $VarId = $this->uri->segment(3);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            $subid=$this->subb_id;
           
              $subcompany_data = $this->WorkInProcessModel->getsubscribercompanydetail($subid);
             $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('workinprocess/fabric', array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData,'subcompany_data' => $subcompany_data,));
        }
        
        public function getColourWiseGarmentPartsDetail2s() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getColourWiseGarmentPartsDetail2ss($enqId);
            echo json_encode($data);
        }

        public function updateColourWiseGarmentPartsDetail2s() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateColourWiseGarmentPartsDetail2ss($req_data, $id);
            echo json_encode($data);
        }

        public function get_fab_size_garment_part_wise() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->get_fab_size_garment_part_wisee($enqId);
            echo json_encode($data);
        }

        // ********** SIZE WISE GARMENT PARTS DETAILS STARTS HERE *********** /

        public function getSizeWiseGarmentPartsDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getSizeWiseGarmentPartsDetailss($enqId);
            echo json_encode($data);
        }

        public function updateSizeWiseGarmentPartsDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateSizeWiseGarmentPartsDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** FABRIC CONSUMPTION CALCULATION DETAILS STARTS HERE *********** /

        public function getFabricConsumptionCalcDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getFabricConsumptionCalcDetailss($enqId);
            echo json_encode($data);
        }

        public function updateFabricConsumptionCalcDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateFabricConsumptionCalcDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** FABRIC PROCESS LOSS DETAILS STARTS HERE *********** /

        public function getFabricProcessLossDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getFabricProcessLossDetailss($enqId);
            echo json_encode($data);
        }

        // ********** FABRIC PROCESS LOSS DETAILS STARTS HERE *********** /

        public function getFabricSizeSpecCodeDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getFabricSizeSpecCodeDetailss($enqId);
            echo json_encode($data);
        }

        // ********** SIZE WISE REQUIRED FINISHING DIA / DIMENSION STARTS HERE (table 7)*********** /

        public function get_sizewise_dia_dimension() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->get_sizewise_dia_dimensionn($enqId);
            echo json_encode($data);
        }

        public function update_sizewise_dia_dimension() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->update_sizewise_dia_dimensionn($req_data, $id);
            echo json_encode($data);
        }

        // ********** SIZE WISE REQUIRED FINISHING DIA / DIMENSION ENDS HERE *********** /

        // ********** ITEMIZED FABRIC REQUIREMENT DETAILS STARTS HERE *********** /

        public function getItemizedFabricRequirementDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getItemizedFabricRequirementDetailss($enqId);
            echo json_encode($data);
        }

        public function updateItemizedFabricRequirementDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateItemizedFabricRequirementDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** YARN DYEING COLOUR WISE QTY DETAILS STARTS HERE *********** /

        public function getYarnDyeingColourWiseQtyDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getYarnDyeingColourWiseQtyDetailss($enqId);
            echo json_encode($data);
        }

        // ********** YARN SINGLE DOUBLE DYE BATH DETAILS STARTS HERE *********** /

        public function getSingleDoubleDyeBathDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getSingleDoubleDyeBathDetailss($enqId);
            echo json_encode($data);
        }

        // ********** YARN PROGRAMME DETAILS STARTS HERE *********** /

        public function getYarnProgrammeDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getYarnProgrammeDetailss($enqId);
            echo json_encode($data);
        }

        public function updateYarnProgrammeDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateYarnProgrammeDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** YARN REQUIREMENT DETAILS STARTS HERE *********** /

        public function getYarnRequirementDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getYarnRequirementDetailss($enqId);
            echo json_encode($data);
        }

        public function updateYarnRequirementDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateYarnRequirementDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** KNITTING PROGRAMME DETAILS STARTS HERE *********** /

        public function getKnittingProgrammeDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getKnittingProgrammeDetailss($enqId);
            echo json_encode($data);
        }

        public function updateKnittingProgrammeDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateKnittingProgrammeDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ********** KNITTING PROGRAMME ITEMIZED YARN REQUIREMENT DETAILS STARTS HERE *********** /

        public function getKnittingProgrammeItemizedYarnRequirementDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getKnittingProgrammeItemizedYarnRequirementDetailss($enqId);
            echo json_encode($data);
        }

        // ********** KNITTING PROGRAMME ITEMIZED YARN REQUIREMENT DETAILS ENDS HERE *********** /

        
        // ************************ DYEING STARTS HERE ************************* /

        // ******** FABRIC DYEING PROGRAMME - COLOUR & DIA WISE QTY. DETAILS (FD, SDB & DDB) STARTS HERE ********* /

        public function getFabricDyeingProgramme_qty() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getFabricDyeingProgramme_qtyy($enqId);
            echo json_encode($data);
        }

        // ******** FABRIC DYEING PROGRAMME - COLOUR & DIA WISE QTY. DETAILS (FD, SDB & DDB) ends HERE ********* /

        // ******** FABRIC DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (FD, SDB & DDB) STARTS HERE ********* /

        public function getFabricDyeingProgramme_finish() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getFabricDyeingProgramme_finishh($enqId);
            echo json_encode($data);
        }

        public function updateFabricDyeingProgrammeDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateFabricDyeingProgrammeDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ******** FABRIC DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (FD, SDB & DDB) ends HERE ********* /

        // ******** YARN DYEING PROGRAMME - COLOUR WISE QTY. DETAILS CONSOLIDATED (YDS & YDJ) STARTS HERE ********* /

        public function getYarnDyeingProgramme_qty() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getYarnDyeingProgramme_qtyy($enqId);
            echo json_encode($data);
        }

        // ******** YARN DYEING PROGRAMME - COLOUR WISE QTY. DETAILS CONSOLIDATED (YDS & YDJ) ends HERE ********* /

        // ******** YARN DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (YDS & YDJ) STARTS HERE ********* /

        public function getYarnDyeingProgramme_finish() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getYarnDyeingProgramme_finishh($enqId);
            echo json_encode($data);
        }

        public function updateYarnDyeingProgrammeDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateYarnDyeingProgrammeDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ******** YARN DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (YDS & YDJ) ENDS HERE ********* /

        // ************************ DYEING ENDS HERE ************************* /

        // ************************ COMPACTING ENDS HERE ************************* /

        // ******** FABRIC WASHING COMPACTING & HEAT SETTING DETAILS STARTS HERE ********* /

        public function getFabricWashingCompatingDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getFabricWashingCompatingDetailss($enqId);
            echo json_encode($data);
        }

        public function updateFabricWashingCompatingDetails() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateFabricWashingCompatingDetailss($req_data, $id);
            echo json_encode($data);
        }

        // ******** FABRIC WASHING COMPACTING & HEAT SETTING DETAILS ENDS HERE ********* /

        // ************************ COMPACTING STARTS HERE ************************* /
        
        public function get_component_wise_packing() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->get_component_wise_packingg($enqId);
            echo json_encode($data);
        }
        
        public function update_component_wise_packing() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->update_component_wise_packingg($req_data, $id);
            echo json_encode($data);
        }
        
        // ************************ COMPACTING ENDS HERE ************************* /
        
        // ************************ COMMON TABLE STARTS HERE ************************* /

        public function getCADCommonTableDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getCADCommonTableDetailss($enqId);
            
             echo json_encode($data);
           
        }

        public function getSampleCommonTableDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getSampleCommonTableDetailss($enqId);
            echo json_encode($data);
        }

        public function getBomCommonTableDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getBomCommonTableDetailss($enqId);
            echo json_encode($data);
        }

        public function getBom2CommonTableDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->WorkInProcessModel->getBom2CommonTableDetailss($enqId);
            echo json_encode($data);
        }

        public function UpdateOrderProcess() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $total_order_qty = xssclean($this->input->post('total_order_qty'));
            $uom = xssclean($this->input->post('uom'));
            $season = xssclean($this->input->post('season'));
            $class = xssclean($this->input->post('class'));
            $divi_dept = xssclean($this->input->post('divi_dept'));
            $sub_class = xssclean($this->input->post('sub_class'));
            $data = $this->WorkInProcessModel->UpdateOrderProcesss($enqId, $total_order_qty, $uom, $season, $class, $divi_dept, $sub_class);
            echo json_encode($data);
        }
        
        public function updateSampleDespatchApproval() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->WorkInProcessModel->updateSampleDespatchApprovall($req_data, $id);
            echo json_encode($data);
        }

        // ************************ COMMON TABLE ENDS HERE ************************* /
        
    }

?>
