<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Orderentryvtwo extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->helper('xssclean');
        $this->load->model(CNFCOMPANY . "orderentrymodel");
        $this->load->model("commonmodel");
        $this->load->model(CNFCADMIN . 'companymodel');
        $VarUserInfo = fnGetUserLoggedInfo(1);
        $this->saveAccess = true;
        /*
         * Only merchant can make changes in Orderentryvtwo, Billofmaterials and Fabricprogram (3 controllers)
        Management can read only access
        saveAccess is used in showing the save button in these 3 controller's view pages
        */
        if ($VarUserInfo['usertype'] != '3' && $VarUserInfo['usertype'] != '15') {
            $this->saveAccess = false;
            //$this->session->unset_userdata('UI');
            //$this->session->sess_destroy();
            //$this->_userdata = '';
            //redirect(base_url());
        }
        if (isset($VarUserInfo['companyid']) && $VarUserInfo['companyid'] >= 1) $this->companyid = $VarUserInfo['companyid'];
        $this->userid = $VarUserInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $ArrUnitofmeasure = unserialize(ARRUNITOFMEASURE);
        foreach ($ArrUnitofmeasure as $uofmitem) $this->unitofmeasure[] = $uofmitem;
        $this->orderEntryPages = unserialize(ARRORDERENTRYPAGES);
    }

    public function test_pdf() {
        error_reporting(0);
        // Include the main TCPDF library (search for installation path).
        require_once(APPPATH.'third_party/TCPDF-main/tcpdf.php');

// create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('TCPDF Example 006');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

// ---------------------------------------------------------

// set font
        $pdf->SetFont('dejavusans', '', 10);

// add a page
        $pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
        $html = $this->load->view("merchant/workinprocess",array(),true);
        $pdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
        $pdf->Output('example_006.pdf', 'I');
    }

    public function cJsonGridData() {
        $VarEnquiryId = xssclean($this->input->post('eid'));
        $jsonDataRes = $this->orderentrymodel->getFirstTable($VarEnquiryId, $this->companyid);
        if (empty($jsonDataRes->jsondatagrid)) {
        } else {
        }
    }
    public function entry() {
        $this->load->model(CNFCOMPANY . 'mcadrequestmodel');
        $this->load->model( 'merteammodel');
        $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
        $VarHashEnquiryId = $this->uri->segment(3);
        $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
        $jsonFirstTbl = $this->orderentrymodel->getFirstTable($VarEnquiryId, $this->companyid);
        $ArrCommonHeaderData = $this->commonheaderdata($VarEnquiryId);
        $this->load->view(CNFCOMPANY . "orderentry/orderentryvtwofirst",
            array(
                'ArrCommonHeaderData'=>$ArrCommonHeaderData,
                'VarHashEnquiryId' => $VarHashEnquiryId,
                'VarEnquiryId' => $VarEnquiryId,
                'ArrSizeChartData' => @$ArrSizeChartData,
                'jsonFirstTbl' => empty($jsonFirstTbl->jsondatagrid) ? 0 : $jsonFirstTbl->jsondatagrid,
                'VarRemarks' => empty($jsonFirstTbl->remarks) ? '' : $jsonFirstTbl->remarks
            )
        );
    }
    public function getcurrencyExchange() {
        $VarRfrom = xssclean($this->input->post('rfrom'));
        $VarCurrencycode = xssclean($this->input->post('currencycode'));
        $VarExchangeRate = $this->commonmodel->currencyExchange($VarCurrencycode, 1);
        echo json_encode(array('errcode' => '1', 'VarExchangeRate' => $VarExchangeRate[0]));
    }
    public function teamInfo() {
        $VarMerchantTeamId = xssclean($this->input->post('teamid'));
        $ArrTeamById = $this->commonmodel->getTeamDetails('', $VarMerchantTeamId);
        echo json_encode(array('errcode' => 1, 'ArrTeamById' => $ArrTeamById[0]));
        die;
    }
    public function getSubChartInfo() {
        $ArrSizeChartDetails = ARR_STD_SIZE;
        $VarSizeCharId = xssclean($this->input->post('sc'));
        if (!empty($VarSizeCharId)) {
            $VarSizeChartHTML = "";
            if ($VarSizeCharId <> 2) {
                $ArrSubChartDetails = $ArrSizeChartDetails[$VarSizeCharId];
                foreach ($ArrSubChartDetails as $VarSubChartId => $VarSubChartName) {
                    $VarSizeChartHTML .= "<div class='col-2 form-check form-check-inline'><input type='checkbox' name='frmSubChartSelection' class='frmSubChartSelection' class='custom-control-input' id='frmSubChartSel" . $VarSubChartId . "' value='" . $VarSubChartId . "'> <label class='custom-control-label' for='frmSubChartSel" . $VarSubChartId . "'>" . $VarSubChartName . "</label> </div>";
                }
            } else {
                for ($i = 1; $i <= 20; $i++) {
                    $VarSizeChartHTML .= "<input type='text' name='frmSubChartCustomSelection[]' id='frmSubChartCustomSelection" . $i . "' value='' class='form-control' style='width:4%;padding:3px 6px;float:left'>&nbsp;&nbsp;";
                }
                $VarSizeChartHTML .= "<div class='clearfix'></div>";
            }
        } else {
            $VarSizeChartHTML = '';
        }
        $ArrResult['errcode'] = '1';
        $ArrResult['ss'] = $VarSizeChartHTML;
        echo json_encode($ArrResult);
    }
    public function getstaticcurrency() {
        $VarRfrom = xssclean($this->input->post('rfrom'));
        if ($VarRfrom == 1) {
            $VarCurrencycode = xssclean($this->input->post('currencycode'));
            $VarExchangeRate = $this->commonmodel->currencyExchange($VarCurrencycode, 1);
            echo json_encode(array('errcode' => '1', 'VarExchangeRate' => $VarExchangeRate[0]));
        }
    }

    public function saveFirstTable() {
        $jsonDataGrid = xssclean($this->input->post('d'));
        $VarEnquiryId = xssclean($this->input->post('enqid'));
        $VarRemarks = xssclean($this->input->post('rem'));
        $VarSizeChartType = xssclean($this->input->post('sizecharttype'));
        $VarSizeChartValue = xssclean($this->input->post('sizechartvalue'));
        $ArrSizeChart = array('sizecharttype' => $VarSizeChartType, 'sizechartvalue' => $VarSizeChartValue,
            'dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid);
        $ArrRes = $this->orderentrymodel->saveFirstTbl($jsonDataGrid, $VarEnquiryId, $this->mysqldatetime, $this->userid, $this->companyid,$VarRemarks);
        if ($ArrRes) {
            if (!empty($VarSizeChartValue)) {
                $ArrSizeChartRes = $this->orderentrymodel->saveSizeChart($VarEnquiryId, $this->companyid, $ArrSizeChart);
            }
            $this->saveCommonDetails();
            echo json_encode(array('errcode' => '1', 'msg' => 'Succ'));
        } else {
            echo json_encode(array('errcode' => '-1', 'msg' => 'Err'));
        }
    }

    public function saveCommonDetails() {
        $VarEnquiryId = xssclean($this->input->post('enqid'));
        $VarSeason = xssclean($this->input->post('season'));
        $VarDivision = xssclean($this->input->post('div'));
        $VarClass = xssclean($this->input->post('cls'));
        $VarSubClass = xssclean($this->input->post('subcls'));
        $VarPaymentTerms = xssclean($this->input->post('frmPaymentTerms'));
        $VarOrderbookingrate = xssclean($this->input->post('ratebooking'));
        $VarOrderrealization = xssclean($this->input->post('ordrealization'));
        $this->orderentrymodel->saveCommonDetailsData($VarEnquiryId, $this->companyid, array('season' => $VarSeason,
            'divdept' => $VarDivision, 'class' => $VarClass, 'sclass' => $VarSubClass, 'payterms' => $VarPaymentTerms,
            'orderbookingrate' => $VarOrderbookingrate, 'orderrealization' => $VarOrderrealization));
    }

    public function secondtbl() {
        $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
        $jsonSecondTblRes = $this->orderentrymodel->getSecondTable($VarEnquiryId, $this->companyid);
        $ArrCommonHeaderData = $this->commonheaderdata($VarEnquiryId);
        if (empty($jsonSecondTblRes->jsondatagrid)) {
            $jsonSecondTbl = 0;
            $VarRemarks ='';
        } else {
            $jsonSecondTbl = $jsonSecondTblRes->jsondatagrid;
            $VarRemarks = $jsonSecondTblRes->remarks;
        }
        //$VarThisLink = $this->router->class.'/'.$this->router->method;
        $this->load->view(CNFCOMPANY . "orderentry/orderentryvtwoSecond", array(
            'VarEnquiryId' => $VarEnquiryId, 'ArrCommonHeaderData'=>$ArrCommonHeaderData,
            'jsonSecondTbl' => $jsonSecondTbl,'VarRemarks'=>$VarRemarks
            )
        );
    }

    public function getFirstTbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonFirstTbl = $this->orderentrymodel->getFirstTable($VarEnquiryId, $this->companyid);
                if (!empty($jsonFirstTbl)) {
                    $ArrFirstTbl = json_decode($jsonFirstTbl->jsondatagrid, true);
                    if (empty($ArrFirstTbl)) {
                        $ArrCombo[] = '';
                        $ArrComponent[] = '';
                        $ArrColor[] = '';
                        $ArrQty[] = '';
                        $ArrForFirstTbl[] = [];
                    } else {
                        foreach ($ArrFirstTbl as $item) {
                            $ArrCombo[] = $item[0];
                            $ArrComponent[] = $item[1];
                            $ArrColor[] = $item[2];
                            $ArrQty[] = $item[3];
                            $ArrForFirstTbl[] = array($item[0], $item[1], $item[2], $item[3], $item[4]);
                        }
                    }
                    echo json_encode(array('errcode' => '1', 'comboarr' => $ArrCombo, 'component' => $ArrComponent, 'colorarr' => $ArrColor, 'qty' => '0', '' => 'Set',
                        'firstTableData' => $ArrForFirstTbl));
                    //echo $jsonFirstTbl->jsondatagrid;
                } else {
                    echo json_encode(array('errcode' => '1', 'comboarr' => '', 'component' => '', 'colorarr' => '', 'qty' => '0', '' => 'Set',
                        'firstTableData' => ''));
                }
            }
        }
    }
    public function getsecondTblForEdit() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $ArrVal = $ArrCombo = $ArrComponent = $ArrColor = array();
                //$jsonSecondTbl = $this->orderentrymodel->getSecondTable($VarEnquiryId,$this->companyid);
                $jsonFirstTbl = $this->orderentrymodel->getFirstTable($VarEnquiryId, $this->companyid);
                if (empty($jsonFirstTbl->jsondatagrid)) {
                    echo json_encode(array('errcode' => '-1', 'allarr' => '', 'comboarr' => '', 'component' => '', 'colorarr' => ''));
                } else {
                    $ArrFirstTbl = json_decode($jsonFirstTbl->jsondatagrid, true);
                    if (empty($ArrFirstTbl)) {
                        $ArrVal = [];
                    } else {
                        foreach ($ArrFirstTbl as $item) {
                            $ArrVal[] = array($item[0], $item[1], $item[2]);
                        }
                    }
                }
                echo json_encode(array('errcode' => '1', 'allarr' => $ArrSecondTbl, 'comboarr' => $ArrCombo, 'component' => $ArrComponent, 'colorarr' => $ArrColor));
            }
        }
    }
    public function saveSecondTbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $ArrSecondTblData = xssclean($this->input->post('d'));
            $VarRemarks = xssclean($this->input->post('rem'));
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $ArrInsId = $this->orderentrymodel->saveSecondTbl($ArrSecondTblData, $VarEnquiryId, $this->mysqldatetime, $this->userid,
                    $this->companyid,$VarRemarks);
                if ($ArrInsId) {
                    echo json_encode(array('errcode' => '1', 'msg' => 'Succ'));
                } else {
                    echo json_encode(array('errcode' => '-1', 'msg' => 'Err'));
                }
            }
        }
    }
    function oeFileDownload() {
        $VarId = urldecode(base64_decode(xssclean($this->input->get('id'))));
        $VarPage = xssclean($this->input->get('page'));
        $fileName = urldecode((xssclean($this->input->get('fileName'))));
        $VarLocation = UPLOADS_SLASH . "orderentry". DIRECTORY_SEPARATOR . $VarId . DIRECTORY_SEPARATOR . $VarPage . DIRECTORY_SEPARATOR;
        if (isset($fileName)) {
            $file = $VarLocation . $fileName;
            $filePath = str_replace("..", "", $file);
            if (file_exists($filePath)) {
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . $fileName);
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filePath));
                readfile($filePath);
                exit;
            }
        }
    }
    public function oeOpenFile() {
        $VarId = xssclean($this->input->get('id'));
        $VarFileName = xssclean($this->input->get('fileName'));
        $VarPage = xssclean($this->input->get('page'));
        // get contents of a file into a string
        $filePath = UPLOADS_SLASH . "orderentry" . DIRECTORY_SEPARATOR . $VarId . DIRECTORY_SEPARATOR . $VarPage . DIRECTORY_SEPARATOR. $VarFileName;
        $VarContentType = mime_content_type($filePath);
        header('Content-Type:' . $VarContentType);
        readfile($filePath);
        exit;
    }

    public function oeFileUpload() {
        $ArrExtensions = FILE_EXTENSIONS;
        $VarFdrName = xssclean($this->input->post('id'));
        $VarPage = xssclean($this->input->post('page'));
        $VarDir = UPLOADS_SLASH . "orderentry" . DIRECTORY_SEPARATOR . $VarFdrName . DIRECTORY_SEPARATOR . $VarPage . DIRECTORY_SEPARATOR;
        if (file_exists($VarDir)) {
        } else {
            mkdir($VarDir, 0777, true);
        }
        if (isset($_FILES["myFile"])) {
            $ret = array();
            $extension = pathinfo($_FILES["myFile"]["name"], PATHINFO_EXTENSION);
            if (in_array($extension, $ArrExtensions)) {
                $fileName = str_replace('&', '_', $_FILES["myFile"]["name"]);
                /**MAX file size 7 MB**/
                if ($_FILES["myFile"]["size"] <= MAXUPLSIZE) {
                    if (move_uploaded_file($_FILES["myFile"]["tmp_name"], $VarDir . $fileName))
                        $ret[] = $fileName;
                } else {
                    $ret[] = 'Err';
                }
            } else {
                $ret[] = 'Err';
            }
            echo json_encode($ret);
        }
    }
    public function deleteimg() {
        $VarFdrName = xssclean($this->input->post('enqid'));
        $VarFname = xssclean($this->input->post('fn'));
        if (unlink($_SERVER['DOCUMENT_ROOT'] . "/uploads/orderentry/" . $VarFdrName . '/' . $VarFname)) {
            echo json_encode(array('errcode' => '1'));
        } else {
            echo json_encode(array('errcode' => '-1'));
        }
    }
    public function thirdTbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $ArrCombo = $ArrComponent = $ArrColor = $ArrIntake = $ArrPoNumber = $ArrSizes = $ArrPcsSet = $ArrSecondTbl = [];
                $jsonThirdTbl = ""; $VarRemarks = '';
                $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
                if (!empty($ArrSizeChartData->sizechartvalue)) {
                    $ArrSizes = explode(',', $ArrSizeChartData->sizechartvalue);
                }
                $VarSizesCount = count($ArrSizes);
                $jsonThirdTblRes = $this->orderentrymodel->getFromThirdTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonThirdTblRes->jsondatagrid)) {
                    $jsonThirdTbl = $jsonThirdTblRes->jsondatagrid;
                    $VarRemarks = $jsonThirdTblRes->remarks;
                }
                $jsonSecondTbl = $this->orderentrymodel->getSecondTable($VarEnquiryId, $this->companyid);
                $Tests = array_fill(0,$VarSizesCount,'');
                if (!empty($jsonSecondTbl->jsondatagrid)) {
                    $ArrSecondTblRes = json_decode($jsonSecondTbl->jsondatagrid, true);
                    foreach ($ArrSecondTblRes as $item) {
                        $ArrCombo = $item[0];
                        $ArrComponent = $item[1];
                        $ArrColor = $item[2];
                        $ArrIntake = $item[3];
                        $ArrPoNumber = $item[4];
                        $ArrSecondTbl[] = array($ArrCombo,$ArrComponent,$ArrColor,$ArrIntake,$ArrPoNumber);
                    }

                    /*foreach ($ArrSecondTblRes as $item) {
                        $ArrTemp = array($item[0], $item[1], $item[2], $item[3], $item[4]);
                        $ArrSecondTbl[] = array_merge($ArrTemp,$Tests);
                    }*/
                }

                //echo '<pre>'; print_r($Tests); die('die');
                echo json_encode(array('errcode' => '1', 'jsonThirdTbl' => $jsonThirdTbl, 'comboarr' => $ArrCombo,
                    'components' => $ArrComponent, 'colorarr' => $ArrColor, 'ArrSecondTbl' => $ArrSecondTbl,
                    'ArrIntake' => $ArrIntake, 'ArrPoNumber' => $ArrPoNumber,
                    'ArrSizeChartData' => $ArrSizes,'remarks'=>$VarRemarks));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnquiryId);
            /**For Color Column Filter**/
            $jsonFirstTblRes = $this->orderentrymodel->getFirstTable($VarEnquiryId, $this->companyid);

            $this->load->view(CNFCOMPANY . "orderentry/orderentryvtwoThird", array(
                    'VarEnquiryId' => $VarEnquiryId,
                'ArrCommonHeaderData'=>$ArrCommonHeaderData
                )
            );
        }
    }
    public function saveThirdTbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $thirdTblData = xssclean($this->input->post('d'));
                $VarRemarks = xssclean($this->input->post('e'));
                $ArrRes = $this->orderentrymodel->saveThirdTbl($thirdTblData, $VarEnquiryId, $this->companyid, $this->mysqldatetime, $this->userid
                ,$VarRemarks);
                if ($ArrRes) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }
    public function newFourthTbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $ArrTblGrid = $ArrThirdTbl = $ArrSecondTbl = $ArrSizes = [];
                $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
                $jsonNewFourthTblRes = $this->orderentrymodel->getFromNewFourthTbl($VarEnquiryId, $this->companyid);
                $VarRemarks = '';
                if (!empty($jsonNewFourthTblRes->jsondatagrid)) {
                    $jsonNewFourthTbl = json_decode($jsonNewFourthTblRes->jsondatagrid, true);
                    $VarRemarks = $jsonNewFourthTblRes->remarks;
                } else {
                    $jsonNewFourthTbl = '';
                }
                if (!empty($ArrSizeChartData->sizechartvalue)) {
                    $ArrSizes = explode(',', $ArrSizeChartData->sizechartvalue);
                }
                $jsonSecondTbl = $this->orderentrymodel->getSecondTable($VarEnquiryId, $this->companyid);
                $jsonThirdTbl = $this->orderentrymodel->getFromThirdTbl($VarEnquiryId, $this->companyid);
                if(!empty($jsonThirdTbl->jsondatagrid))
                    $ArrThirdTbl = json_decode($jsonThirdTbl->jsondatagrid, true);

                if (!empty($jsonSecondTbl->jsondatagrid)) {
                    $ArrSecondTbl = json_decode($jsonSecondTbl->jsondatagrid, true);
                    foreach ($ArrSecondTbl as $item) {
                        /**When Entering data in component spacing is must **/
                        $splitComponent = explode(' / ', $item[1]);
                        $splitColor = explode(' / ', $item[2]);
                        $splitIntake = explode(' / ', $item[3]);
                        foreach ($splitComponent as $key => $comp) {
                            $ResSize = [];
                            foreach($ArrThirdTbl[$key] as $sizes) {
                                $ResSize[] = $sizes;
                            }
                            $ArrTblGridWithSize[] = array($item[0], $comp, !empty($splitColor[$key]) ? $splitColor[$key] : '',
                                !empty($splitIntake[$key]) ? $splitIntake[$key] : '',$item[4],$ResSize);
                            $ArrTblGridWithoutSize[] = array($item[0], $comp, !empty($splitColor[$key]) ? $splitColor[$key] : '',
                                !empty($splitIntake[$key]) ? $splitIntake[$key] : '',$item[4],"");
                        }
                    }

                    foreach ($ArrTblGridWithSize as $ki => $withSizes) {
                        $VarIntake = $withSizes[3];
                        foreach($withSizes[5] as $size) {
                            $VarResult = $VarIntake * $size;
                            $ArrNew[$ki][] = $VarResult;
                        }
                    }
                    foreach ($ArrTblGridWithoutSize as $keys => $item) {
                        foreach ($ArrNew as $sizeItem) {
                            $ArrTblGrid[$keys] = array_merge($item,$ArrNew[$keys]);
                        }
                    }
                }
                echo json_encode(array('errcode' => 1,'ArrThirdTblGrid' => $ArrTblGrid,
                    'ArrSizeChartData' => $ArrSizes, 'jsonNewFourthTbl' => $jsonNewFourthTbl,'remarks'=>$VarRemarks));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnquiryId);

            $this->load->view(CNFCOMPANY . "orderentry/newFourthTbl", array(
                    'VarEnquiryId' => $VarEnquiryId,
                    'ArrCommonHeaderData'=>$ArrCommonHeaderData,
                )
            );
        }
    }
    public function saveNewFourthTbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = xssclean($this->input->post('enqid'));
            $VarRemarks = xssclean($this->input->post('e'));
            $VarPageNo = xssclean($this->input->post('n'));
            $VarSizeCount = xssclean($this->input->post('sizeCount'));
            $VarSizeColStartLoc = $VarSizeCount + 6;
            $ArrGcd = [];
            $jsonNewFourthData = xssclean($this->input->post('fullData'));
            $ArrNewFourthData = json_decode($this->input->post('fullData'), true);
            //echo '<pre>'; print_r($VarSizeColStartLoc);
            foreach ($ArrNewFourthData as $key => $datas) {
                for($ii = 6; $ii < $VarSizeColStartLoc; $ii++) {
                    //echo '<pre>'; print_r($datas[$ii]);
                    //echo '<pre>'; print_r($ii);
                    if(!empty($datas[$ii]))
                        $sizesValues[$key][] = $datas[$ii];
                    else
                        $sizesValues[$key][] = 0;
                    //echo '<pre>'; print_r($sizesValues);
                    //$ArrGcd[$key][] = array_reduce($sizesValues,'gcd');
                    //$sizesValues = array($datas[6], $datas[7], $datas[8], $datas[9], $datas[10], $datas[11]);
                    //$ArrGcd[] = array_reduce($sizesValues, 'gcd');
                }
            }
            foreach($sizesValues as $sizes) {
                $ArrGcd[] = array_reduce($sizes, 'gcd');
            }
            //echo '<pre>'; print_r($sizesValues);
            //echo '<pre>'; print_r($ArrGcd);
            //echo '<pre>'; print_r($ArrGcd);
            $Res = $this->orderentrymodel->saveNewFourthTbl($jsonNewFourthData, $ArrGcd, $VarEnquiryId, $this->userid,
                $this->companyid,$VarRemarks,$VarPageNo);
            if ($Res) {
                echo json_encode(array('errcode' => '1', 'res' => $Res));
            } else {
                echo json_encode(array('errcode' => '-1', 'res' => $Res));
            }
        }
    }

    public function deliverySchedule() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $ArrModeOfShipment = unserialize(ORDERMODEOFSHIPMENT);
            if ($VarEnquiryId >= 1) {
                $ArrFromSecondTbl = $Arr2ndTblData = $ArrFromFourthTbl = [];
                $ArrFromSecondTblRes = $this->orderentrymodel->getSecondTable($VarEnquiryId, $this->companyid);
                if (!empty($ArrFromSecondTblRes->jsondatagrid)) {
                    $ArrFromSecondTbl = json_decode($ArrFromSecondTblRes->jsondatagrid, true);
                    foreach ($ArrFromSecondTbl as $item) {
                        if (!empty($item[0])) {
                            $Arr2ndTblData[] = array("", "", $item[0], $item[4], $item[5], $item[6]);
                        } else {
                            $Arr2ndTblData[] = array("", "", $item[3], $item[4], $item[5], $item[6]);
                        }
                    }
                    foreach ($ArrFromSecondTbl as $item) {
                        if (!empty($item[0])) {
                            $Arr2nd[] = array($item[0], $item[4], $item[5], $item[6]);
                        } else {
                            $Arr2nd[] = array($item[3], $item[4], $item[5], $item[6]);
                        }
                    }
                }
                $VarRemarks = '';
                $ArrFromFourthTblRes = $this->orderentrymodel->getFromDeliveryScheduleSixthTbl($VarEnquiryId, $this->companyid);
                if (!empty($ArrFromFourthTblRes->jsondatagrid)) {
                    $ArrFromFourthTbl = json_decode($ArrFromFourthTblRes->jsondatagrid);
                    $VarRemarks = $ArrFromFourthTblRes->remarks;
                }
                $ArrPortInfo = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PORT . ' AS p', 'p.id,p.portname,p.portcity',
                    array('p.status' => 1, 'p.companyid' => $this->companyid), 3);
                $ArrPortCountryRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PORT . ' AS p', 'DISTINCT(c.countryname),p.portcountry as id', array('p.status' => '1',
                    'p.companyid' => $this->companyid), 3, array('joincondition' => 'p.portcountry = c.id', 'jointable' => KN_COUNTRIES . ' AS c'));
                $ArrPortNameCity = $ArrPortCountry = [];
                foreach ($ArrPortInfo as $key => $val) {
                    $ArrPortNameCity[] = $val['portname'] . ' - ' . $val['portcity'];
                }
                foreach($ArrPortCountryRes as $value) {
                    $ArrPortCountry[] = $value['countryname'];
                }
                echo json_encode(
                    array(
                    'errcode' => '1', 'ArrModeOfShipment' => $ArrModeOfShipment, 'ArrFromSecondTbl' => $Arr2ndTblData,
                    'ArrFromFourthTbl' => $ArrFromFourthTbl,'ArrPortNameCity'=>$ArrPortNameCity,'ArrPortCountry'=>$ArrPortCountry,
                        'remarks'=>$VarRemarks,'Arr2nd'=>$Arr2nd
                    )
                );
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnquiryId);
            $this->load->view(CNFCOMPANY . "orderentry/orderentryDeliverySchedule", array(
                'VarEnquiryId' => $VarEnquiryId,'ArrCommonHeaderData'=>$ArrCommonHeaderData
                )
            );
        }
    }

    public function saveDeliveryScheduleSixthTbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $VarRemarks = xssclean($this->input->post('e'));
                $ftDatOnlyjsonData = xssclean($this->input->post('ft_data_only'));
                $allDataJsonData = xssclean($this->input->post('all_data'));
                $ArrRes = $this->orderentrymodel->saveDeliveryScheduleSixthTbl($ftDatOnlyjsonData, $allDataJsonData, $this->companyid, $VarEnquiryId,
                    $this->mysqldatetime, $this->userid,$VarRemarks);
                if ($ArrRes) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }

    public function cuttingRatioTbl() {
        $VarRfrom = $this->input->post('rfrom');
        $VarEnquiryId = $this->input->post('enqid');
        if ($VarRfrom == 1) {
            if ($VarEnquiryId >= 1) {
                $ArrSizes = $ArrFromNewFourthTbl = $ArrGcd = [];
                $jsonCuttingRatioData = '';
                $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
                if(!empty($ArrSizeChartData)) {
                    $ArrSizes = explode(',', $ArrSizeChartData->sizechartvalue);
                }
                $jsonFromNewFourthTbl = $this->orderentrymodel->getFromNewFourthTbl($VarEnquiryId, $this->companyid);
                $jsonCuttingRatioDataRes = $this->orderentrymodel->getFromCuttingRatioFifthTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonFromNewFourthTbl->gcd_for_cutting_ratio)) {
                    $ArrGcd = explode(',', $jsonFromNewFourthTbl->gcd_for_cutting_ratio);
                }
                $VarSizeLoc = count($ArrSizes) + 6;
                if (!empty($jsonFromNewFourthTbl->jsondatagrid)) {
                    $ArrFromNewFourthTblRes = json_decode($jsonFromNewFourthTbl->jsondatagrid, true);
                    foreach ($ArrFromNewFourthTblRes as $key => $item) {
                        for($ii = 6; $ii < $VarSizeLoc; $ii++) {
                            $ArrSizesCalc[$key][] = $item[$ii] / $ArrGcd[$key];
                            $VarLastSizeCol = $ii;
                        }
                    }

                    $VarItemizedQtyPcsCol = $VarLastSizeCol + 1;
                    foreach ($ArrFromNewFourthTblRes as $key => $item) {
                        $ArrFromNewFourthTbl[] = array($item[0], $item[1], $item[2], $item[4], $item[5]);
                        $ArrItemizedQtyPcs[$key][] = $item[$VarItemizedQtyPcsCol];
                    }
                }
                $VarRemarks = '';
                if (!empty($jsonCuttingRatioDataRes->jsondatagrid)) {
                    $jsonCuttingRatioData = $jsonCuttingRatioDataRes->jsondatagrid;
                    $VarRemarks = $jsonCuttingRatioDataRes->remarks;
                }
                foreach($ArrFromNewFourthTbl as $key => $datas) {
                    foreach ($ArrSizesCalc as $calc) {
                        $ArrNewTest[$key] = array_merge($datas,$ArrSizesCalc[$key]);
                    }

                }
                $ArrNewTestTwo = [];
                foreach ($ArrNewTest as $calc) {
                    $ArrNewTestTwo[] = array_merge($calc,array(""));
                }
                foreach ($ArrNewTestTwo as $finalKey => $calc) {
                    $ArrFinal[] = array_merge($calc,$ArrItemizedQtyPcs[$finalKey]);
                }
                echo json_encode(array('errcode' => '1', 'ArrSizeChartData' => $ArrSizes, 'ArrFromNewFourthTbl' => $ArrFinal,
                    'ArrSizesCalc'=>$ArrSizesCalc,'cuttingRatioData' => $jsonCuttingRatioData,'remarks'=>$VarRemarks));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnquiryId);

            $this->load->view(CNFCOMPANY . "orderentry/orderentryCuttingRatioTbl", array(
                'VarEnquiryId' => $VarEnquiryId,'ArrCommonHeaderData'=>$ArrCommonHeaderData
                )
            );
        }
    }

    public function saveCuttingRatioFifthTbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarRemarks = xssclean($this->input->post('e'));
                $ArrRes = $this->orderentrymodel->saveCuttingRatioFifthTbl($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime,
                    $this->userid,$VarRemarks);
                if ($ArrRes) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }

    /*public function fabDetailKnitColorWiseFabBlendAndContent() {
        $VarRfrom = $this->input->post('rfrom');
        $VarEnquiryId = $this->input->post('enqid');
        if ($VarRfrom == 1) {
            if ($VarEnquiryId >= 1) {
                $ArrDryProcess = $ArrWetProcess = [];
                $currentTblData = '';
                $jsonLycraFormulaRes = '';
                $fabDetailKnitColorWiseFabBlendAndContent = $this->orderentrymodel->getFromLycraDataTbl($VarEnquiryId, $this->companyid);
                if (!empty($fabDetailKnitColorWiseFabBlendAndContent->nexttabledata)) {
                    $jsonLycraFormulaRes = $fabDetailKnitColorWiseFabBlendAndContent->nexttabledata;
                }
                $jsonSavedDataRes = $this->orderentrymodel->getFabKnitColorWiseFabBlendAndContent($VarEnquiryId, $this->companyid);
                if (!empty($jsonSavedDataRes->jsondatagrid)) {
                    $currentTblData = $jsonSavedDataRes->jsondatagrid;
                }
                $ArrFabricFinish = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_FINISH_WET_DRY, 'fabricfinish,processingtype', array('status' => "1",
                    'companyid' => $this->companyid));
                if (!empty($ArrFabricFinish)) {
                    foreach ($ArrFabricFinish as $item) {
                        if ($item['processingtype'] == 'Dry') $ArrDryProcess[] = $item['fabricfinish'];
                        if ($item['processingtype'] == 'Wet') $ArrWetProcess[] = $item['fabricfinish'];
                    }
                }
                echo json_encode(array('errcode' => 1, 'jsonLycraFormulaRes' => $jsonLycraFormulaRes,
                    'ArrDryProcess' => $ArrDryProcess, 'ArrWetProcess' => $ArrWetProcess,'currentTblData'=>$currentTblData));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $this->load->view(CNFCOMPANY . "orderentry/fabDetailKnitColorWiseFabBlendAndContent", $ArrData);
        }
    }*/

    /*public function saveFabKnitColorWiseFabBlendAndContent() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $fabKnitColorWiseFabBlendAndContent = xssclean($this->input->post('saveFabKnitColorWiseFabBlendAndContent'));
                //echo '<pre>'; print_r($jsonData); die('die');
                $Res = $this->orderentrymodel->saveFabKnitColorWiseFabBlendAndContent($fabKnitColorWiseFabBlendAndContent, $VarEnquiryId,
                    $this->companyid, $this->mysqldatetime, $this->userid);
                if ($Res) {
                    echo json_encode(array('errcode' => 1));
                } else {
                    echo json_encode(array('errcode' => -1));
                }
            }
        }
    }*/

/*    public function beforeSeventhtbl() {
        $VarRfrom = $this->input->post('rfrom');
        $VarEnquiryId = $this->input->post('enqid');
        if ($VarRfrom == 1) {
            if ($VarEnquiryId >= 1) {
                $ArrFromFirstTbl = $ArrCombo = $ArrComponent = $ArrColor = $ArrThirdTblGrid = array();
                $ArrYarnBlend = $ArrYarnContent = $ArrYarnCount = $ArrKnitFabricName = $ArrYarnSplReq = $ArrGarmentParts = [];
                $jsonGarmentPartsJxl = $jsonFromB4Seventh = 0;
                $jsonFromFirstTblRes = $this->orderentrymodel->getFirstTable($VarEnquiryId, $this->companyid);
                if (!empty($jsonFromFirstTblRes->jsondatagrid)) {
                    $ArrFromFirstTbl = json_decode($jsonFromFirstTblRes->jsondatagrid);
                }
                $jsonGarmentPartsJxlRes = $this->orderentrymodel->getGarmentPartsJxl($VarEnquiryId, $this->companyid);
                if (!empty($jsonGarmentPartsJxlRes->jsondatagrid)) {
                    $jsonGarmentPartsJxl = $jsonGarmentPartsJxlRes->jsondatagrid;
                }
                $jsonFromB4SeventhRes = $this->orderentrymodel->getFromB4SeventhTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonFromB4SeventhRes->jsondatagrid)) {
                    $jsonFromB4Seventh = $jsonFromB4SeventhRes->jsondatagrid;
                }
                $ArrYarnMisc = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_YARN_MISC, 'misc_name,misc_type', array('status' => "1", 'companyid' => $this->companyid));
                if (!empty($ArrYarnMisc)) {
                    foreach ($ArrYarnMisc as $item) {
                        if ($item['misc_type'] == 1) {
                            $ArrYarnBlend[] = $item['misc_name'];
                        }
                        if ($item['misc_type'] == 2) {
                            $ArrYarnContent[] = $item['misc_name'];
                        }
                        if ($item['misc_type'] == 3) {
                            $ArrYarnCount[] = $item['misc_name'];
                        }
                    }
                }
                $ArrFabricName = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_MISC, 'misc_name', array('status' => 1, 'companyid' => $this->companyid, 'misc_type' => 3));
                if (!empty($ArrFabricName)) {
                    foreach ($ArrFabricName as $item) {
                        $ArrKnitFabricName[] = $item['misc_name'];
                    }
                }
                $ArrYarnSplReqRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_YARN_SPL_REQ, 'yarnsplreq', array('status' => 1, 'companyid' => $this->companyid));
                if (!empty($ArrYarnSplReqRes)) {
                    foreach ($ArrYarnSplReqRes as $item) {
                        $ArrYarnSplReq[] = $item['yarnsplreq'];
                    }
                }
                $ArrGarmentpartsInfo = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_GARMENT_PART_DESC, 'gpdname', array('status' => "1",
                    'companyid' => $this->companyid));
                if (!empty($ArrGarmentpartsInfo)) {
                    foreach ($ArrGarmentpartsInfo as $item) {
                        $ArrGarmentParts[] = $item['gpdname'];
                    }
                }
                echo json_encode(array('errcode' => '1', 'jsonFromB4Seventh' => $jsonFromB4Seventh, 'ArrFromFirstTbl' => $ArrFromFirstTbl,
                    'jsonGarmentPartsJxl' => $jsonGarmentPartsJxl, 'ArrYarnBlend' => $ArrYarnBlend, 'ArrYarnContent' => $ArrYarnContent,
                    'ArrKnitFabricName' => $ArrKnitFabricName, 'ArrYarnCount' => $ArrYarnCount, 'ArrYarnSplReq' => $ArrYarnSplReq,
                    'ArrGarmentParts' => $ArrGarmentParts));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnquiryId);
            $VarCurrentPage = $this->uri->segment(2);
            $VarTableId = array_search($VarCurrentPage,$this->orderEntryPages);
            $ArrFirstRemarks = $this->orderentrymodel->getAllTableRemarks($VarEnquiryId, $this->companyid,$VarTableId);
            $VarRemarks = '';
            if (!empty($ArrFirstRemarks->remarks)) {
                $VarRemarks = $ArrFirstRemarks->remarks;
            }
            $this->load->view(CNFCOMPANY . "orderentry/beforeSeventhtbl", array(
                'VarEnquiryId' => $VarEnquiryId, 'ArrDyeingType' => json_encode(unserialize(ARRDYEINGTYPE)),
                'ArrCommonHeaderData'=>$ArrCommonHeaderData,'VarRemarks'=>$VarRemarks
                )
            );
        }
    }*/

/*    public function saveGarmentPartsJxl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $jsonb4Seven = xssclean($this->input->post('b4Seven'));
                $Res = $this->orderentrymodel->saveGarmentPartsJxl($jsonData, $jsonb4Seven,$VarEnquiryId, $this->companyid, $this->mysqldatetime, $this->userid);
                if ($Res) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }*/

    /*public function seventhtbl() {
        $VarRfrom = $this->input->post('rfrom');
        $VarEnquiryId = $this->input->post('enqid');
        if ($VarRfrom == 1) {
            if ($VarEnquiryId >= 1) {
                $ArrFromB4Seventh = [];
                $ArrColor = $ArrYarnBlend = $ArrYarnContent = $ArrFilteredSeven = [];
                $jsonFromB4Seventh = $this->orderentrymodel->getFromB4SeventhTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonFromB4Seventh->jsondatagrid)) {
                    $ArrFromB4Seventh = json_decode($jsonFromB4Seventh->jsondatagrid, true);
                    foreach($ArrFromB4Seventh as $item) {
                        $ArrColor[$item[0].'##'.$item[1].'##'.$item[3]][] = $item[2];
                        $ArrYarnBlend[$item[0].'##'.$item[1].'##'.$item[3]][] = $item[4];
                        $ArrYarnContent[$item[0].'##'.$item[1].'##'.$item[3]][] = $item[5];
                    }
                    foreach($ArrFromB4Seventh as $item) {
                        $ArrFilteredSeven[$item[0].'##'.$item[1].'##'.$item[3]] = array($item[0],$item[1],$item[2],$item[3],
                            $item[4],$item[5],$item[6],$item[7],$item[8],$item[9]);
                    }
                    foreach($ArrColor as $keys => $colorItem) {
                        if(count($colorItem) >= 2) {
                            $ArrJoinedColor[$keys] = implode(' : ',$colorItem);
                        }
                    }
                    foreach($ArrYarnBlend as $keys => $yarnBlendItem) {
                        if(count($yarnBlendItem) >= 2) {
                            $ArrJoinedYarnBlend[$keys] = implode(' : ',$yarnBlendItem);
                        }
                    }
                    foreach ($ArrYarnContent as $keys => $yarnContentItem) {
                        if(count($yarnContentItem) >= 2) {
                            $ArrJoinedYarnContent[$keys] = implode(' : ',$yarnContentItem);
                        }
                    }
                    foreach($ArrFilteredSeven as $finalKey => $final) {
                        $ArrFinal[] = array($final[0],$final[1],!empty($ArrJoinedColor[$finalKey]) ? $ArrJoinedColor[$finalKey] : $final[2],$final[3],
                            !empty($ArrJoinedYarnContent[$finalKey]) ? $ArrJoinedYarnContent[$finalKey] : $final[5],'',$final[6],$final[7],$final[8],$final[9]);
                    }
                }
                $lycraDataJxl = '';
                $jsonLycraTbl = $this->orderentrymodel->getFromLycraDataTbl($VarEnquiryId, $this->companyid);
                if(!empty($jsonLycraTbl->jsondatagrid)) {
                    $lycraDataJxl = $jsonLycraTbl->jsondatagrid;
                }
                echo json_encode(
                    array('errcode' => '1','AllB4SevenTblArr'=>$ArrFinal,'lycraDataJxl'=>$lycraDataJxl
                    )
                );
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnquiryId);
            $this->load->view(CNFCOMPANY . "orderentry/orderentryvtwoSeventh", array(
                    'VarEnquiryId' => $VarEnquiryId,'ArrCommonHeaderData'=>$ArrCommonHeaderData,
                    'ArrDyeingType' => json_encode(unserialize(ARRDYEINGTYPE))
                )
            );
        }
    }*/

/*    public function saveFabricDetailsKnitLycra() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $nextTableData = xssclean($this->input->post('lycraFormulaResult'));
                $VarRemarks = xssclean($this->input->post('e'));
                $VarTableId = xssclean($this->input->post('n'));
                $Res = $this->orderentrymodel->saveLycraDataTbl($jsonData,$nextTableData, $VarEnquiryId, $this->companyid, $this->mysqldatetime, $this->userid,$VarRemarks,$VarTableId);
                //$Res = $this->orderentrymodel->saveEighth($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime, $this->userid,$VarRemarks,$VarTableId);
                if ($Res) {
                    echo json_encode(array('errcode' => 1));
                } else {
                    echo json_encode(array('errcode' => -1));
                }
            }
        }
    }*/


    /*public function updateNinth() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $jsonThisJxl = $this->input->post('thisJxl');
            if ($VarEnquiryId >= 1) {
                $VarRemarks = xssclean($this->input->post('e'));
                $VarTableId = xssclean($this->input->post('n'));
                $Res = $this->orderentrymodel->saveNinth($jsonThisJxl, $VarEnquiryId,
                    $this->companyid, $this->mysqldatetime, $this->userid,$VarRemarks,$VarTableId);
                if ($Res) {
                    echo json_encode(array('errcode' => 1));
                } else {
                    echo json_encode(array('errcode' => -1));
                }
            }
        }

    }*/

/*    public function saveB4SeventhTbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarRemarks = xssclean($this->input->post('e'));
                $VarTableId = xssclean($this->input->post('n'));
                $Res = $this->orderentrymodel->saveB4SeventhTbl($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime,
                    $this->userid,$VarRemarks,$VarTableId);
                if ($Res) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }*/

    /*public function saveSeventhTbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $Res = $this->orderentrymodel->saveSeventhTbl($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime, $this->userid);
                if ($Res) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }*/

    /*
    public function eighthtbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonFromThirdTbl = $this->orderentrymodel->getFromThirdTbl($VarEnquiryId, $this->companyid);
                $AllArr = $ArrCombo = $ArrComponent = $ArrColor = $ArrThirdTblGrid = array();
                if (empty($jsonFromThirdTbl->jsondatagrid)) {
                    $ArrFromThirdTbl = [];
                } else {
                    $ArrFromThirdTbl = json_decode($jsonFromThirdTbl->jsondatagrid, true);
                }
                foreach ($ArrFromThirdTbl as $item) {
                    $ArrCombo[] = $item[0];
                    $ArrComponent[] = $item[1];
                    $ArrColor[] = $item[2];
                    $ArrThirdTblGrid[] = array($item[0], $item[1], $item[2]);
                }
                $jsonFromEighth = $this->orderentrymodel->getFromEighthTbl($VarEnquiryId, $this->companyid);
                if (empty($jsonFromEighth->jsondatagrid)) {
                    $ArrFromEighth = [];
                } else {
                    $ArrFromEighth = json_decode($jsonFromEighth->jsondatagrid, true);
                }
                if (count($ArrFromEighth) >= 1) {
                    foreach ($ArrFromEighth as $item) {
                        $AllArr[] = array($item[0], $item[1], $item[2], $item[3], $item[4], $item[5], $item[6], $item[7],
                            $item[8], $item[9], $item[10], $item[11], $item[12], $item[13], $item[14]);
                    }
                }
                echo json_encode(array('errcode' => '1', 'allarr' => $AllArr, 'comboarr' => $ArrCombo, 'componentarr' => $ArrComponent, 'colorarr' => $ArrColor,
                    'thirdtbldata' => $ArrThirdTblGrid));
            }
        } else {
            $ArrKnitFabricBlend = $ArrKnitFabricContent = $ArrKnitFabricName = $VarYarnCount = $ArrFabricFinish = array();
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $VarHashEnquiryId = $this->uri->segment(3);
            $ArrGarmentpartsInfo = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_GARMENT_PART_DESC, 'gpdname as name,gpdname as id', array('status' => "1",
                'companyid' => $this->companyid), 3);
            $ArrFabricBlend = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_MISC, 'id,misc_name', array('status' => "1", 'companyid' => $this->companyid, 'misc_type' => '1'));
            $ArrFabricContent = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_MISC, 'id,misc_name', array('status' => "1", 'companyid' => $this->companyid, 'misc_type' => '2'));
            $ArrFabricName = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_MISC, 'id,misc_name', array('status' => "1", 'companyid' => $this->companyid, 'misc_type' => '3'));
            //$KnitFabricBlend = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_DETAILS, 'id,blend', array('status' => "1", 'companyid' => $this->companyid, 'fabrictype' => '1'), 3);
            foreach ($ArrFabricBlend as $key => $item) $ArrKnitFabricBlend[] = $item['misc_name'];
            //$KnitFabricContent = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_DETAILS, 'id,content', array('status' => "1", 'companyid' => $this->companyid, 'fabrictype' => '1'), 3);
            foreach ($ArrFabricContent as $key => $item) $ArrKnitFabricContent[] = $item['misc_name'];
            //$KnitFabricName = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_DETAILS, 'id,fabric', array('status' => "1", 'companyid' => $this->companyid, 'fabrictype' => '1'), 3);
            foreach ($ArrFabricName as $key => $item) $ArrKnitFabricName[] = $item['misc_name'];
            //$ArrKnitFinish = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_FINISH, 'id,name', array('status' => "1", 'companyid' => $this->companyid), 3);
            //foreach ($ArrKnitFinish as $item) $ArrFabricFinish[] = $item['name'];
            foreach ($this->unitofmeasure as $item) $VarUnitMeasure[] = $item;
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnquiryId);
            $this->load->view(CNFCOMPANY . "orderentry/orderentryvtwoEighth", array(
                'ArrCommonHeaderData' => $ArrCommonHeaderData, 'VarHashEnquiryId' => $VarHashEnquiryId, 'VarEnquiryId' =>
                $VarEnquiryId, 'ArrGarmentpartsInfo' => json_encode(@$ArrGarmentpartsInfo), 'ArrKnitFabricBlend' =>
                json_encode($ArrKnitFabricBlend), 'ArrKnitFabricContent' => json_encode($ArrKnitFabricContent), 'ArrKnitFabricName' => json_encode($ArrKnitFabricName)
            , 'ArrUnitOfMeasure' => json_encode($VarUnitMeasure), 'ArrYarnCount' => json_encode($VarYarnCount), 'ArrFabricFinish' => json_encode($ArrFabricFinish)
                )
            );
        }
    }
    */

    /*public function saveEightTbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $Res = $this->orderentrymodel->saveEighthTbl($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime, $this->userid);
                if ($Res) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }*/

    /*public function dyeingninthtbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonFromThirdTbl = $this->orderentrymodel->getFromThirdTbl($VarEnquiryId, $this->companyid);
                $jsonDyeingDetails = '';
                $ArrFromThirdTbl = $ArrColor = $b4SeventhKnitTblData = $ArrFromNinth =
                $b4SeventhKnitTblDataRes = $ArrKnitFabricContent = $ArrColorMatchStd = $ArrDyeingSplReq = [];
                if (!empty($jsonFromThirdTbl->jsondatagrid)) {
                    $ArrFromThirdTbl = json_decode($jsonFromThirdTbl->jsondatagrid, true);
                    foreach ($ArrFromThirdTbl as $item) {
                        $ArrColor[] = $item[2];
                    }
                }
                $jsonDyeingDetailsRes = $this->orderentrymodel->getFromNinthTbl($VarEnquiryId, $this->companyid);
                $jsonb4SeventhKnitTblDataRes = $this->orderentrymodel->getFromB4SeventhTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonDyeingDetailsRes->jsondatagrid)) {
                    $jsonDyeingDetails = $jsonDyeingDetailsRes->jsondatagrid;
                }
                if (!empty($jsonb4SeventhKnitTblDataRes->jsondatagrid)) {
                    $b4SeventhKnitTblDataRes = json_decode($jsonb4SeventhKnitTblDataRes->jsondatagrid, true);
                }
                if (count($b4SeventhKnitTblDataRes) >= 1) {
                    foreach ($b4SeventhKnitTblDataRes as $item) {
                        $b4SeventhKnitTblData[] = array($item[0], $item[1], $item[2], $item[3], $item[4], $item[5], $item[6],
                            $item[7], $item[8], $item[9], $item[10]);
                    }
                }
                $ArrFabricContentRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_MISC, 'misc_name', array('status' => 1, 'companyid' => $this->companyid,
                    'misc_type' => 2));
                if (!empty($ArrFabricContentRes)) {
                    foreach ($ArrFabricContentRes as $item) {
                        $ArrKnitFabricContent[] = $item['misc_name'];
                    }
                }
                $ArrColorMatStdRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_COLOR_MATCH_STD, 'clrmatchingstd', array('status' => 1, 'companyid' => $this->companyid));
                $ArrDyeingSplReqRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_DYEING_SPECIAL_REQUEST, 'dsrname', array('status' => 1, 'companyid' => $this->companyid));
                if (!empty($ArrColorMatStdRes)) {
                    foreach ($ArrColorMatStdRes as $item) {
                        $ArrColorMatchStd[] = $item['clrmatchingstd'];
                    }
                    //echo '<pre>'; print_r($ArrColorMatchStd); die('die');
                }
                if (!empty($ArrDyeingSplReqRes)) {
                    foreach ($ArrDyeingSplReqRes as $item) {
                        $ArrDyeingSplReq[] = $item['dsrname'];
                    }
                }
                echo json_encode(array('errcode' => '1', 'allarr' => $jsonDyeingDetails,  'colorarr' => $ArrColor,
                    'SeventhKnitTblData' => $b4SeventhKnitTblData, 'ArrKnitFabricContent' => $ArrKnitFabricContent, 'ArrColorMatchStd' => $ArrColorMatchStd,
                    'ArrDyeingSplReq' => $ArrDyeingSplReq));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $VarCurrentPage = $this->uri->segment(2);
            $VarTableId = array_search($VarCurrentPage,$this->orderEntryPages);
            $ArrFirstRemarks = $this->orderentrymodel->getAllTableRemarks($VarEnquiryId, $this->companyid,$VarTableId);
            $VarRemarks = '';
            if (!empty($ArrFirstRemarks->remarks)) {
                $VarRemarks = $ArrFirstRemarks->remarks;
            }
            $ArrData['VarRemarks'] = $VarRemarks;
            $this->load->view(CNFCOMPANY . "orderentry/orderentryvtwoNinth", $ArrData);
        }
    }*/

    /*public function savedyeingNinthTbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarRemarks = xssclean($this->input->post('e'));
                $VarTableId = xssclean($this->input->post('n'));
                $ArrRes = $this->orderentrymodel->saveNinthTbl($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime, $this->userid,$VarRemarks,$VarTableId);
                if ($ArrRes) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }*/

    public function emblishmenttenthtbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonCurrentTbl = '';
                $ArrFromFirstTbl = [];
                $VarRemarks = '';
                $jsonFromFirstTblRes = $this->orderentrymodel->getFirstTable($VarEnquiryId, $this->companyid);
                if (!empty($jsonFromFirstTblRes->jsondatagrid)) {
                    $ArrFromFirstTbl = json_decode($jsonFromFirstTblRes->jsondatagrid);
                }
                $jsonNewFourthTblRes = $this->orderentrymodel->getFromNewFourthTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonNewFourthTblRes->jsondatagrid)) {
                    $ArrNewFourthTbl = json_decode($jsonNewFourthTblRes->jsondatagrid, true);
                } else {
                    $ArrNewFourthTbl = NULL;
                }
                $ArrEmbellishmentType = array();
                $jsonFromTenth = $this->orderentrymodel->getFromTenthTbl($VarEnquiryId, $this->companyid);
                $jsonFromArtworkCodeTbl = $this->orderentrymodel->getArtworkCodeTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonFromTenth->jsondatagrid)) {
                    $jsonCurrentTbl = $jsonFromTenth->jsondatagrid;
                }
                $jsonFromArtworkCode = '';
                if(!empty($jsonFromArtworkCodeTbl->jsondatagrid)) {
                    $jsonFromArtworkCode = $jsonFromArtworkCodeTbl->jsondatagrid;
                    $VarRemarks = $jsonFromArtworkCodeTbl->remarks;
                }
                $ArrEmbellishmentRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_EMBELLISHMENT_TYPE, 'embellname',array(
                    'status'=>1,'companyid'=>$this->companyid
                ));
                if(count($ArrEmbellishmentRes) >= 1) {
                    foreach ($ArrEmbellishmentRes as $item) {
                        $ArrEmbellishmentType[] = $item['embellname'];
                    }
                }
                $ArrMediumMaterialRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_MEDIUM_MATERIAL, 'medium_material',array(
                    'status'=>1,'companyid'=>$this->companyid
                ));
                if(count($ArrMediumMaterialRes) >= 1) {
                    foreach ($ArrMediumMaterialRes as $item) {
                        $ArrMediumMaterial[] = $item['medium_material'];
                    }
                }
                $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
                if(!empty($ArrSizeChartData)) {
                    $ArrSizes = explode(',', $ArrSizeChartData->sizechartvalue);
                }
                $ArrSizes[] = 'All';
                echo json_encode(
                    array(
                    'errcode' => '1', 'jsonCurrentTbl' => $jsonCurrentTbl,'ArrFromFirstTbl' => $ArrFromFirstTbl, 'ArrFromNewFourth' => $ArrNewFourthTbl,
                        'jsonFromArtworkCode'=>$jsonFromArtworkCode,'embellishmentType'=>$ArrEmbellishmentType,'ArrMediumMaterial'=>$ArrMediumMaterial,
                        'remarks'=>$VarRemarks,'ArrSizes'=>$ArrSizes
                    )
                );
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $this->load->view(CNFCOMPANY . "orderentry/orderEntryEmbellishment", $ArrData);
        }
    }
    public function saveEmblishmentTenthTbl() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $ArrRes = $this->orderentrymodel->saveTenthTbl($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime, $this->userid);
                if ($ArrRes) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }

    public function saveArtworkCode() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarRemarks = xssclean($this->input->post('e'));
                $ArrRes = $this->orderentrymodel->saveArtworkCode($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime, $this->userid,$VarRemarks);
                if ($ArrRes) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }

    public function commonheaderdata($VarEnquiryId) {
        $ArrCompanyRes = $this->companymodel->fnGetCompanyInfo($this->companyid);
        $ArrEnquiryDetails = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarEnquiryId, $this->companyid);
        $ArrEnquiryDetails = @$ArrEnquiryDetails[0];
        $VarHashEnquiryId = $this->uri->segment(3);
        $ArrMerchant = $this->commonmodel->getMerchantData($this->companyid, 1, $ArrEnquiryDetails['merchantid']);
        $ArrTeam = $this->commonmodel->getTeamDetails($this->companyid, $ArrEnquiryDetails['merchantid']);
        $ArrCommonData = $this->orderentrymodel->getCommonData($VarEnquiryId, $this->companyid);
        $ArrCommonHeaderData = array(
            'companyName' => @$ArrCompanyRes[0]['companyname'], 'companyAddress' => @$ArrCompanyRes[0]['address'],
            'VarEnquiryId' => $VarEnquiryId, 'VarHashEnquiryId' => @$VarHashEnquiryId,'merchantName' => @$ArrMerchant[0]['contactname'],
            'merchantMobile'=>@$ArrMerchant[0]['mobile'],'merchantCode'=>@$ArrMerchant[0]['code'],
            'merchantEmail'=>@$ArrMerchant[0]['username'],'ArrEnquiryDetails' => $ArrEnquiryDetails,
            'ArrCommonData' => @$ArrCommonData,'ArrTeam'=>@$ArrTeam[0]
        );

        return $ArrCommonHeaderData;
    }

    public function comgarmentprocessflow() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {

                $AllArr = $ArrPoNumbers = $ArrObjPoNumers = array();
                $jsonCuttingRatioData = '';
                $jsonCuttingRatioDataRes = $this->orderentrymodel->getFromCuttingRatioFifthTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonCuttingRatioDataRes->jsondatagrid)) {
                    $jsonCuttingRatioData = $jsonCuttingRatioDataRes->jsondatagrid;
                }
                $VarRemarks = '';
                $ArrObjPoNumers = $this->orderentrymodel->getAllPoNumber($VarEnquiryId, $this->companyid);
                $jsonFromFourteenRes = $this->orderentrymodel->getFromcomGarmentprocessFourteen($VarEnquiryId, $this->companyid);
                if (!empty($jsonFromFourteenRes->jsondatagrid)) {
                    $ArrFromFourteen = json_decode($jsonFromFourteenRes->jsondatagrid, true);
                    foreach ($ArrFromFourteen as $item) {
                        $AllArr[] = array($item[0], $item[1], $item[2], $item[3], $item[4], $item[5]);
                    }
                    $VarRemarks = $jsonFromFourteenRes->remarks;
                }
                if (count($ArrObjPoNumers) >= 0) {
                    foreach ($ArrObjPoNumers as $item) {
                        $ArrPoNumbers[] = $item;
                    }
                }
                echo json_encode(array('jsonCuttingRatioData' => $jsonCuttingRatioData, 'allarr' => $AllArr,
                    'ArrPoNumbers' => $ArrPoNumbers,'remarks'=>$VarRemarks));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrProcessFlowRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PROCESS_FLOW, 'processflowname',
                array('status' => "1", 'companyid' => $this->companyid), 3);
            if (empty($ArrProcessFlowRes)) {
                $ArrData['ArrProcessFlow'] = '';
            } else {
                foreach ($ArrProcessFlowRes as $item) {
                    $ArrProcessFlow[] = $item['processflowname'];
                }
                $ArrData['ArrProcessFlow'] = json_encode($ArrProcessFlow);
            }
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $this->load->view(CNFCOMPANY . "orderentry/comgarmentprocessflow", $ArrData);
        }
    }
    public function savecomgarmentflow() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'), true);
                $VarRemarks = xssclean($this->input->post('e'));
                $ArrRes = $this->orderentrymodel->saveComGarmentProcess($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime,
                    $this->userid,$VarRemarks);
                if ($ArrRes) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }
    public function garmentsamplingfifteen() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonNewFourth = $jsonFromFifteen = '';
                $jsonNewFourthRes = $this->orderentrymodel->getFromNewFourthTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonNewFourthRes->jsondatagrid)) {
                    $jsonNewFourth = $jsonNewFourthRes->jsondatagrid;
                }
                //Size
                $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
                $ArrFinalSizes = explode(',',$ArrSizeChartData->sizechartvalue);
                //Sizes
                $VarRemarks = '';
                $ArrFromFifteenRes = $this->orderentrymodel->getFromGarmentSamplingFifteeen($VarEnquiryId, $this->companyid);
                if (!empty($ArrFromFifteenRes->jsondatagrid)) {
                    $jsonFromFifteen = $ArrFromFifteenRes->jsondatagrid;
                    $VarRemarks = $ArrFromFifteenRes->remarks;
                }
                echo json_encode(array('errcode' => '1', 'jsonNewFourth' => $jsonNewFourth, 'jsonFromFifteen' => $jsonFromFifteen,
                    'ArrFinalSizes' => $ArrFinalSizes,'remarks'=>$VarRemarks));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            /*$ArrSamplingReqRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_GARMENT_SAMPLING, 'requirement', array('status' => "1", 'companyid' => $this->companyid), 3);
            if (empty($ArrSamplingReqRes)) {
                $ArrSamplingReq[] = '';
            } else {
                foreach ($ArrSamplingReqRes as $item) {
                    $ArrSamplingReq[] = $item['requirement'];
                }
            }
            $ArrData['ArrSamplingReq'] = json_encode($ArrSamplingReq);*/
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $this->load->view(CNFCOMPANY . "orderentry/orderentryvtwogarmentsamplingFifteen", $ArrData);
        }
    }
    public function savegarmentsamplingfifteen() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarRemarks = xssclean($this->input->post('e'));
                $ArrRes = $this->orderentrymodel->savegarmentsamplingfifteen($jsonData, $VarEnquiryId, $this->companyid,
                    $this->mysqldatetime, $this->userid,$VarRemarks);
                if ($ArrRes) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }
    public function labtestingsixteen() {
        $VarRfrom = $this->input->post('rfrom');
        $ArrCombo = $ArrComponent = $ArrColor = $ArrLabAcceptLevel = array();
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonNewFourth = $jsonSixteenTbl = '';
                $jsonNewFourthRes = $this->orderentrymodel->getFromNewFourthTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonNewFourthRes->jsondatagrid)) {
                    $jsonNewFourth = $jsonNewFourthRes->jsondatagrid;
                }
                $ArrFromSixteen = $this->orderentrymodel->getFromLabTestingSixteen($VarEnquiryId, $this->companyid);
                if (!empty($ArrFromSixteen->jsondatagrid)) {
                    $jsonSixteenTbl = $ArrFromSixteen->jsondatagrid;
                }
                $ArrLabAcceptLevelRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_ACCEPTANCE_LEVEL, 'acceptablelevel',
                    array('status' => "1", 'companyid' => $this->companyid));
                if(!empty($ArrLabAcceptLevelRes)) {
                    foreach($ArrLabAcceptLevelRes as $item) $ArrLabAcceptLevel[] = $item['acceptablelevel'];
                }
                echo json_encode(array('errcode' => '1', 'jsonNewFourth' => $jsonNewFourth, 'jsonSixteenTbl' => $jsonSixteenTbl,
                    'ArrAcceptanceLevel'=>$ArrLabAcceptLevel));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrLabTestDescRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_LAB, 'labname', array('status' => "1", 'companyid' => $this->companyid));
            $ArrFromB4Seventh = [];
            $jsonFromB4SeventhRes = $this->orderentrymodel->getFromB4SeventhTbl($VarEnquiryId, $this->companyid);
            if (!empty($jsonFromB4SeventhRes->jsondatagrid)) {
                $ArrFromB4Seventh = json_decode($jsonFromB4SeventhRes->jsondatagrid, true);
                $ArrData['ArrFromB4Seventh'] = json_encode($ArrFromB4Seventh);
            }
            if (empty($ArrLabAcceptLevelRes)) {
                $ArrData['ArrAcceptanceLevel'] = json_encode(array());
            } else {
                foreach ($ArrLabAcceptLevelRes as $item) {
                    $ArrLabAcceptLevel[] = $item['acceptablelevel'];
                }
                $ArrData['ArrAcceptanceLevel'] = json_encode($ArrLabAcceptLevel);
            }
            if (empty($ArrLabTestDescRes)) {
                $ArrData['ArrLabTestDesc'] = json_encode(array());
            } else {
                foreach ($ArrLabTestDescRes as $item) {
                    $ArrLabTestDesc[] = $item['labname'];
                }
                $ArrData['ArrLabTestDesc'] = json_encode($ArrLabTestDesc);
            }
            $this->load->view(CNFCOMPANY . "orderentry/orderentryLabTestingSixteen", $ArrData);
        }
    }
    public function savelabtestingsixteen() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $ArrRes = $this->orderentrymodel->saveLabtestingSixteen($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime, $this->userid);
                if ($ArrRes) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }
    public function externalLabTesting() {
        $VarRfrom = $this->input->post('rfrom');
        $ArrCombo = $ArrComponent = $ArrColor = array();
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonExtLabTestinSixteen = '';
                $getExterLabTestingSixteen = $this->orderentrymodel->getExtLabTesting($VarEnquiryId, $this->companyid);
                if (!empty($getExterLabTestingSixteen->jsondatagrid)) {
                    $jsonExtLabTestinSixteen = $getExterLabTestingSixteen->jsondatagrid;
                }
                echo json_encode(array('errcode' => '1', 'jsonExtLabTestinSixteen' => $jsonExtLabTestinSixteen));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData = $this->commonheaderdata($VarEnquiryId);
            $this->load->view(CNFCOMPANY . "orderentry/oeExternalLabTesting", $ArrData);
        }
    }
    public function saveExtLabTesting() {
        $VarRfrom = $this->input->post('rFrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $ArrRes = $this->orderentrymodel->saveExtLabTesting($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime, $this->userid);
                if ($ArrRes) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }
    public function packingdetails() {
        $VarRfrom = $this->input->post('rFrom');
        $ArrCombo = $ArrComponent = $ArrColor = $ArrPackingCode = $ArrPackingMaterial = array();
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonPackingDetails = $jsonNewFourthTbl = $jsonThirdTbl = '';
                $jsonThirdTblRes = $this->orderentrymodel->getFromThirdTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonThirdTblRes->jsondatagrid)) {
                    $jsonThirdTbl = $jsonThirdTblRes->jsondatagrid;
                }
                $jsonNewFourthTblRes = $this->orderentrymodel->getFromNewFourthTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonNewFourthTblRes->jsondatagrid)) {
                    $jsonNewFourthTbl = $jsonNewFourthTblRes->jsondatagrid;
                }
                $ArrPackingCodeRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PACKING_CODE, 'packingname', array('companyid' => $this->companyid, 'status' => '1'));
                if(!empty($ArrPackingCodeRes)) {
                    foreach ($ArrPackingCodeRes as $item) {
                        $ArrPackingCode[] = $item['packingname'];
                    }
                }
                $ArrPackingMaterialRes = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PACKING_MATERIAL, 'packingmaterialname', array('companyid' => $this->companyid, 'status' => '1'));
                if(!empty($ArrPackingMaterialRes)) {
                    foreach($ArrPackingMaterialRes as $item) {
                        $ArrPackingMaterial[] = $item['packingmaterialname'];
                    }
                }
                $VarRemarks = '';
                $getPackingDetails = $this->orderentrymodel->getPackingDetails($VarEnquiryId, $this->companyid);
                if (!empty($getPackingDetails->jsondatagrid)) {
                    $jsonPackingDetails = $getPackingDetails->jsondatagrid;
                    $VarRemarks = $getPackingDetails->remarks;
                }
                echo json_encode(array('jsonThirdTbl' => $jsonThirdTbl, 'jsonNewFourthTbl' => $jsonNewFourthTbl, 'jsonPackingDetails' => $jsonPackingDetails,
                    'ArrPackingCode'=>$ArrPackingCode,'ArrPackingMaterial'=>$ArrPackingMaterial,'remarks'=>$VarRemarks));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $this->load->view(CNFCOMPANY . "orderentry/oePackingDetails", $ArrData);
        }
    }
    public function savePackingDetails() {
        $VarRfrom = $this->input->post('rFrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonJxlData = xssclean($this->input->post('d'));
                $VarRemarks = xssclean($this->input->post('e'));
                $ArrRes = $this->orderentrymodel->savePackingDetails($jsonJxlData, $VarEnquiryId, $this->companyid, $this->mysqldatetime, $this->userid,$VarRemarks);
                if ($ArrRes) {
                    echo json_encode(array('errCode' => 1));
                } else {
                    echo json_encode(array('errCode' => -1));
                }
            }
        }
    }

    public function cartonAndBags() {
        $VarRfrom = $this->input->post('rfrom');
        $ArrPoNumbers = $jsonNewFourthTbl = $ArrCombo = $ArrComboColor = array();
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            $VarUrlId = $this->input->post('lastURLSegment');
            if ($VarEnquiryId >= 1) {
                $jsonNewFourthTblRes = $this->orderentrymodel->getFromNewFourthTbl($VarEnquiryId, $this->companyid);
                if (!empty($jsonNewFourthTblRes->jsondatagrid)) {
                    $ArrNewFourthTbl = json_decode($jsonNewFourthTblRes->jsondatagrid, true);
                    foreach ($ArrNewFourthTbl as $item) {
                        if (empty($item[0])) {
                            $ArrComboColor[] = $item[2];
                        } else {
                            $ArrComboColor[] = $item[0];
                        }
                    }
                }
                $ArrJxlResult = [];
                $ArrResult = $this->orderentrymodel->getCartonBags($VarUrlId);
                if($ArrResult) {
                    $ArrJxlResult = $ArrResult[0];
                }
                $mBagAndTableType = $this->commonmodel->fnGetAllTableInfo(CARTON_AND_BAGS,'id,pono,table_type_id,jsondatagrid,jsondatagridextra',
                    array('referenceid'=>$VarEnquiryId,'companyid'=>$this->companyid));
                $ArrRes = [];
                if($VarUrlId > 0) {
                    $poNo_TableType = $this->commonmodel->fnGetAllTableInfo(CARTON_AND_BAGS,'id,pono,table_type_id',array('id'=>$VarUrlId));
                    if($poNo_TableType) {
                        $ArrRes = $poNo_TableType[0];
                    }
                }
                echo json_encode(
                    array('ArrComboColor' => $ArrComboColor,'mBagAndTableType'=>$mBagAndTableType,'initialJxl'=>$ArrRes,
                        'ArrJxlResult'=>$ArrJxlResult)
                );
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $VarUrlId = $this->uri->segment(4);
            if($VarUrlId > 0) {
                $poNo_TableType = $this->commonmodel->fnGetAllTableInfo(CARTON_AND_BAGS,'id,pono,table_type_id',array('id'=>$VarUrlId));
                if($poNo_TableType) {
                    $ArrInitialJxl = $poNo_TableType[0];
                    $ArrData['initialJxl'] = $ArrInitialJxl;
                }
            }
            //echo '<pre>'; print_r($mBagAndTableType); die('die');
            $VarHashEnquiryId = $this->uri->segment(3);
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            //sizes
            $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
            $ArrFinalSizes = [];
            if(!empty($ArrSizeChartData->sizechartvalue))
                $ArrFinalSizes = explode(',',$ArrSizeChartData->sizechartvalue);
            //sizes
            $ArrPoNumbersRes = $this->orderentrymodel->getAllPoNumber($VarEnquiryId, $this->companyid);
            foreach ($ArrPoNumbersRes as $item) {
                $ArrPoNumbers[] = $item;
            }
            $ArrData['ArrPoNumbers'] = json_encode($ArrPoNumbers);
            $ArrData['ArrFinalSizes'] = json_encode($ArrFinalSizes);
            $ArrData['VarHashEnquiryId'] = $VarHashEnquiryId;
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $this->load->view(CNFCOMPANY . "orderentry/cartonAndBagsJxl", $ArrData);
        }
    }

    /*public function editCartonBag() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $VarPrimaryId = $this->input->post('lastURLSegment');
                $jsonNewFourthTblRes = $this->orderentrymodel->getFromNewFourthTbl($VarEnquiryId, $this->companyid);
                $ArrComboColor = [];
                if (!empty($jsonNewFourthTblRes->jsondatagrid)) {
                    $ArrNewFourthTbl = json_decode($jsonNewFourthTblRes->jsondatagrid, true);
                    foreach ($ArrNewFourthTbl as $item) {
                        if (empty($item[0])) {
                            $ArrComboColor[] = $item[2];
                        } else {
                            $ArrComboColor[] = $item[0];
                        }
                    }
                }
                $ArrJxlResult = [];
                $ArrResult = $this->orderentrymodel->getCartonBags($VarPrimaryId);
                if($ArrResult) {
                    $ArrJxlResult = $ArrResult[0];
                }
                $mBagAndTableType = $this->commonmodel->fnGetAllTableInfo(CARTON_AND_BAGS,'id,pono,table_type_id,jsondatagrid,jsondatagridextra',
                    array('referenceid'=>$VarEnquiryId,'companyid'=>$this->companyid));
                $ArrRes = [];
                if($VarPrimaryId > 0) {
                    $poNo_TableType = $this->commonmodel->fnGetAllTableInfo(CARTON_AND_BAGS,'id,pono,table_type_id',array('id'=>$VarPrimaryId));
                    if($poNo_TableType) {
                        $ArrRes = $poNo_TableType[0];
                    }
                }
                echo json_encode(
                    array('ArrComboColor' => $ArrComboColor,'mBagAndTableType'=>$mBagAndTableType,'ArrRes'=>$ArrRes,'ArrJxlResult'=>$ArrJxlResult)
                );
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $VarUrlId = $this->uri->segment(4);
            if($VarUrlId > 0) {
                $poNo_TableType = $this->commonmodel->fnGetAllTableInfo(CARTON_AND_BAGS,'id,pono,table_type_id',array('id'=>$VarUrlId));
                if($poNo_TableType) {
                    $ArrRes = $poNo_TableType[0];
                    $ArrData['ArrRes'] = $ArrRes;
                }
            }
            $VarHashEnquiryId = $this->uri->segment(3);
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
            $ArrFinalSizes = [];
            if(!empty($ArrSizeChartData->sizechartvalue))
                $ArrFinalSizes = explode(',',$ArrSizeChartData->sizechartvalue);
            $ArrPoNumbersRes = $this->orderentrymodel->getAllPoNumber($VarEnquiryId, $this->companyid);
            foreach ($ArrPoNumbersRes as $item) {
                $ArrPoNumbers[] = $item;
            }
            $ArrData['ArrPoNumbers'] = json_encode($ArrPoNumbers);
            $ArrData['ArrFinalSizes'] = json_encode($ArrFinalSizes);
            $ArrData['VarHashEnquiryId'] = $VarHashEnquiryId;
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $this->load->view(CNFCOMPANY . "orderentry/editCartonBags", $ArrData);
        }

    }*/

    public function savePoNoAndTabType() {
        $ArrPoNoTabType = json_decode($this->input->post('jxl'),true);
        $VarEnquiryId = $this->input->post('enqId');
        $this->orderentrymodel->saveMBagCAR($ArrPoNoTabType, $VarEnquiryId);
        echo json_encode(['errCode'=>1]);
    }

    public function masterBagCartonAssortmentRatio() {
        $VarRfrom = $this->input->post('rfrom');
        $ArrPoNumbers = $jsonNewFourthTbl = $ArrCombo = $ArrComboColor = array();
        $jsonMasterBagAssort = $jsonExtraDetails = '';
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonNewFourthTblRes = $this->orderentrymodel->getFromNewFourthTbl($VarEnquiryId, $this->companyid);
                $jsonMasterBagAssortRes = $this->orderentrymodel->getMasterBagCartonAssortmentRatio($VarEnquiryId, $this->companyid);
                if(!empty($jsonMasterBagAssortRes)) {
                    $jsonMasterBagAssort = $jsonMasterBagAssortRes->jsondatagrid;
                    $jsonExtraDetails = $jsonMasterBagAssortRes->jsondatagridextra;
                }
                if (!empty($jsonNewFourthTblRes->jsondatagrid)) {
                    $ArrNewFourthTbl = json_decode($jsonNewFourthTblRes->jsondatagrid, true);
                    foreach ($ArrNewFourthTbl as $item) {
                        if (empty($item[0])) {
                            $ArrComboColor[] = $item[2];
                        } else {
                            $ArrComboColor[] = $item[0];
                        }
                    }
                }
                $mBagAndTableType = $this->commonmodel->fnGetAllTableInfo(CARTON_AND_BAGS,'id,pono,table_type_id',
                    array('referenceid'=>$VarEnquiryId,'companyid'=>$this->companyid));

                echo json_encode(array('ArrComboColor' => $ArrComboColor, 'jsonMasterBagAssort' => $jsonMasterBagAssort,
                    'jsonExtraDetails' => $jsonExtraDetails,'mBagAndTableType'=>$mBagAndTableType));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $VarHashEnquiryId = $this->uri->segment(3);
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            //sizes
            $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
            $ArrFinalSizes = [];
            if(!empty($ArrSizeChartData->sizechartvalue))
            $ArrFinalSizes = explode(',',$ArrSizeChartData->sizechartvalue);
            //sizes
            $ArrPoNumbersRes = $this->orderentrymodel->getAllPoNumber($VarEnquiryId, $this->companyid);
            foreach ($ArrPoNumbersRes as $item) {
                $ArrPoNumbers[] = $item;
            }
            $ArrData['ArrPoNumbers'] = json_encode($ArrPoNumbers);
            $ArrData['ArrFinalSizes'] = json_encode($ArrFinalSizes);
            $ArrData['VarHashEnquiryId'] = $VarHashEnquiryId;
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $this->load->view(CNFCOMPANY . "orderentry/masterBagCartonAssortmentRatio", $ArrData);
        }

    }

    public function backup_masterBagCartonAssortmentRatio() {
        $VarRfrom = $this->input->post('rfrom');
        $ArrPoNumbers = $jsonNewFourthTbl = $ArrCombo = $ArrComboColor = array();
        $jsonMasterBagAssort = $jsonExtraDetails = '';
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonNewFourthTblRes = $this->orderentrymodel->getFromNewFourthTbl($VarEnquiryId, $this->companyid);
                $jsonMasterBagAssortRes = $this->orderentrymodel->getMasterBagCartonAssortmentRatio($VarEnquiryId, $this->companyid);
                if(!empty($jsonMasterBagAssortRes)) {
                    $jsonMasterBagAssort = $jsonMasterBagAssortRes->jsondatagrid;
                    $jsonExtraDetails = $jsonMasterBagAssortRes->jsondatagridextra;
                }
                if (!empty($jsonNewFourthTblRes->jsondatagrid)) {
                    $ArrNewFourthTbl = json_decode($jsonNewFourthTblRes->jsondatagrid, true);
                    foreach ($ArrNewFourthTbl as $item) {
                        if (empty($item[0])) {
                            $ArrComboColor[] = $item[2];
                        } else {
                            $ArrComboColor[] = $item[0];
                        }
                    }
                }
                echo json_encode(array('ArrComboColor' => $ArrComboColor, 'jsonMasterBagAssort' => $jsonMasterBagAssort,
                    'jsonExtraDetails' => $jsonExtraDetails));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $VarHashEnquiryId = $this->uri->segment(3);
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            //sizes
            $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
            $ArrFinalSizes = [];
            if (empty($ArrSizeChartData->sizecharttype) || empty($ArrSizeChartData->sizechartvalue)) {
            } else {
                $sizecharttype = $ArrSizeChartData->sizecharttype;
                $ArrSIZECHARTDETAILS = unserialize(ARRSIZECHARTDETAILS);
                $sizechartvalue = $ArrSizeChartData->sizechartvalue;
                $ArrSavedSize = explode(',', $sizechartvalue);
                $ArrSizes = $ArrSIZECHARTDETAILS[$sizecharttype];
                foreach ($ArrSizes as $key => $size) {
                    if (in_array($key, $ArrSavedSize)) {
                        $ArrFinalSizes[] = $ArrSIZECHARTDETAILS[$sizecharttype][$key];
                    }
                }
            }
            //sizes
            $ArrPoNumbersRes = $this->orderentrymodel->getAllPoNumber($VarEnquiryId, $this->companyid);
            foreach ($ArrPoNumbersRes as $item) {
                $ArrPoNumbers[] = $item;
            }
            $ArrData['ArrPoNumbers'] = json_encode($ArrPoNumbers);
            $ArrData['ArrFinalSizes'] = json_encode($ArrFinalSizes);
            $ArrData['VarHashEnquiryId'] = $VarHashEnquiryId;
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $this->load->view(CNFCOMPANY . "orderentry/masterBagCartonAssortmentRatio", $ArrData);
        }
    }

    public function saveBagAndCartons() {
        $VarPrimaryId = $this->input->post('priId');
        $jsonData = $this->input->post('d');
        $jsonExtraData = $this->input->post('dataExtra');
        $VarExtraTblCount = $this->input->post('extra_tables');
        $this->orderentrymodel->saveBagAndCartons($jsonData, $jsonExtraData,$VarExtraTblCount,$VarPrimaryId);
        echo json_encode(array());
    }

    public function saveMasterBagCartonAssortmentRatio() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqId');
            if ($VarEnquiryId >= 1) {
                $jsonMainData = xssclean($this->input->post('d'));
                $jsonExtraData = xssclean($this->input->post('xd'));
                $jsonChooseTypeJxl = xssclean($this->input->post('chooseTypeJxl'));
                $ArrRes = $this->orderentrymodel->saveMasterBagCartonAssortmentRatio($jsonMainData, $jsonExtraData,$VarEnquiryId,$jsonChooseTypeJxl);
                if ($ArrRes) {
                    echo json_encode(array('errCode' => 1));
                } else {
                    echo json_encode(array('errCode' => -1));
                }
            }
        }
    }
    public function lotinspectiontwentyone() {
        $VarRfrom = $this->input->post('rFrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $ArrFromSecondTbl = [];
                $jsonLotInspection = $VarRemarks = '';
                $ArrLotInspectionRes = $this->orderentrymodel->getLotInspection($VarEnquiryId, $this->companyid);
                if(!empty($ArrLotInspectionRes)) {
                    $jsonLotInspection = $ArrLotInspectionRes->jsondatagrid;
                    $VarRemarks = $ArrLotInspectionRes->remarks;
                }
                $ArrFromSecondTblRes = $this->orderentrymodel->getSecondTable($VarEnquiryId, $this->companyid);
                if (!empty($ArrFromSecondTblRes->jsondatagrid)) {
                    $ArrFromSecondTbl = json_decode($ArrFromSecondTblRes->jsondatagrid, true);
                    foreach ($ArrFromSecondTbl as $item) {
                        if (!empty($item[0])) {
                            $ArrSecondTbl[] = array($item[0], $item[4], $item[5], $item[6]);
                        } else {
                            $ArrSecondTbl[] = array($item[3], $item[4], $item[5], $item[6]);
                        }
                    }
                }
                echo json_encode(array('jsonLotInspection' => $jsonLotInspection,'remarks'=>$VarRemarks,
                    'ArrFromSecondTbl'=>$ArrSecondTbl));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $this->load->view(CNFCOMPANY . "orderentry/orderentryLotInspectionTwentyone", $ArrData);
        }
    }
    public function saveLotInspection() {
        $VarRfrom = $this->input->post('rFrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarRemarks = xssclean($this->input->post('e'));
                $ArrRes = $this->orderentrymodel->saveLotInspection($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime, $this->userid,$VarRemarks);
                if ($ArrRes) {
                    echo json_encode(array('errcode' => '1'));
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }
    public function docandlogisticstwentytwo() {
        $VarRfrom = $this->input->post('rfrom');
        $ArrForwarding = $ArrClearing = $ArrImporter = [];
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $ArrSecondTbl = [];
                $jsonDocandLogistics = '';
                $VarRemarks = '';
                $ArrFromSecondTblRes = $this->orderentrymodel->getSecondTable($VarEnquiryId, $this->companyid);
                if (!empty($ArrFromSecondTblRes->jsondatagrid)) {
                    $ArrFromSecondTbl = json_decode($ArrFromSecondTblRes->jsondatagrid, true);
                    foreach ($ArrFromSecondTbl as $item) {
                        if (!empty($item[0])) {
                            $ArrSecondTbl[] = array($item[0], $item[4], $item[5], $item[6]);
                        } else {
                            $ArrSecondTbl[] = array($item[3], $item[4], $item[5], $item[6]);
                        }
                    }
                }
                $ArrFromTwentytwoRes = $this->orderentrymodel->getFromDocandLogisticsTwentytwo($VarEnquiryId, $this->companyid);
                if (!empty($ArrFromTwentytwoRes->jsondatagrid)) {
                    $jsonDocandLogistics = $ArrFromTwentytwoRes->jsondatagrid;
                    $VarRemarks = $ArrFromTwentytwoRes->remarks;
                }

                $this->load->model(CNFCOMPANY . "mbuyermodel");
                $AllBuyersRes = $this->mbuyermodel->fnGetAllBuyer($VarStatus = 1, $this->companyid);
                foreach($AllBuyersRes as $buyer) {
                    $AllBuyers[] = $buyer->buyername;
                }
                echo json_encode(array('ArrSecondTbl' => $ArrSecondTbl,'jsonDocandLogistics' => $jsonDocandLogistics,
                    'AllBuyers'=>$AllBuyers,'remarks'=>$VarRemarks));
            }
        } else {
            $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnquiryId);
            $ArrSizeSpecCode = [];
            $ArrLogistics = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_LOGISTICS, 'logistic,type', array('companyid' => $this->companyid, 'status' => '1'));
            foreach ($ArrLogistics as $datas) {
                if ($datas['type'] == 1) {
                    $ArrForwarding[] = $datas['logistic'];
                }
                if ($datas['type'] == 2) {
                    $ArrClearing[] = $datas['logistic'];
                }
                if ($datas['type'] == 3) {
                    $ArrImporter[] = $datas['logistic'];
                }
            }
            $ArrData['ArrForwarding'] = json_encode(array());
            $ArrData['ArrClearing'] = json_encode(array());
            $ArrData['ArrImporter'] = json_encode(array());
            $ArrData['ArrConsignee'] = json_encode(array());
            if (!empty($ArrForwarding)) {
                $ArrData['ArrForwarding'] = json_encode($ArrForwarding);
            }
            if (!empty($ArrClearing)) {
                $ArrData['ArrClearing'] = json_encode($ArrClearing);
            }
            if (!empty($ArrImporter)) {
                $ArrData['ArrImporter'] = json_encode($ArrImporter);
            }
            if (!empty($ArrConsignee)) {
                $ArrData['ArrConsignee'] = json_encode($ArrConsignee);
            }
            $ArrSizeSpecCodeRes = $this->orderentrymodel->getAllSizeSpecCode($VarEnquiryId, $this->companyid);
            foreach ($ArrSizeSpecCodeRes as $item) {
                $ArrSizeSpecCode[] = $item;
            }
            $ArrData['ArrSizeSpecCode'] = json_encode($ArrSizeSpecCode);
            $ArrData['VarEnquiryId'] = $VarEnquiryId;
            $this->load->view(CNFCOMPANY . "orderentry/orderentryDocandLogisticsTwentytwo", $ArrData);
        }
    }

    public function savedocandlogisticstwentytwo() {
        $VarRfrom = $this->input->post('rfrom');
        if ($VarRfrom == 1) {
            $VarEnquiryId = $this->input->post('enqid');
            if ($VarEnquiryId >= 1) {
                $jsonData = xssclean($this->input->post('d'));
                $VarRemarks = xssclean($this->input->post('e'));
                //TODO Commented below 2 line because its not completed yet. BOM was in order entry (this controller) now moved separately in Billofmaterials controller. editaccess is still open. ie. editacess will be 1
                //$this->orderentrymodel->setOrderEntryEditAccess($VarEnquiryId, $this->companyid, 0);
                //$this->orderentrymodel->setOrderEntryCompleteStatus($VarEnquiryId, $this->companyid);
                $ArrRes = $this->orderentrymodel->saveDocandLogisticsTwentytwo($jsonData, $VarEnquiryId, $this->companyid, $this->mysqldatetime,
                    $this->userid,$VarRemarks);
                if ($ArrRes) {
                    echo json_encode(array('errCode' => 1));
                } else {
                    echo json_encode(array('errCode' => -1));
                }
            }
        }
    }

    public function checklistlast() {
        echo 'Checklist';
    }
    public function MgmtPinforOrderEntryEditAccess() {
        $VarRfrom = xssclean($this->input->post('rfrom'));
        if ($VarRfrom == 1) {
            $VarEmail = xssclean($this->input->post('frmEmail'));
            $VarPassword = xssclean($this->input->post('pwd'));
            $VarOrderId = xssclean($this->input->post('oid'));
            $this->load->model("loginmodel");
            $Res = $this->loginmodel->fnValidate($VarEmail, $VarPassword, 1, 3);
            if (empty($Res)) {
                echo json_encode(array('errcode' => -1, 'msg' => 'Invalid E-mail / Password'));
            } else {
                // Set Flag for order entry edit access
                $VarRows = $this->orderentrymodel->setOrderEntryEditAccess($VarOrderId, $this->companyid, 1);
                if ($VarRows)
                    echo json_encode(array('errcode' => 1, 'msg' => 'Success!'));
                else {
                    echo json_encode(array('errcode' => -1, 'msg' => 'Error!'));
                }
            }
        }
    }
    public function saveAllOrderEntry() {
        //Set Complete status
        $VarRfrom = xssclean($this->input->post('rfrom'));
        if ($VarRfrom == 1) {
            $VarOrderId = xssclean($this->input->post('oid'));
            $this->orderentrymodel->setOrderEntryCompleteStatus($VarOrderId, $this->companyid, 1);
            $VarRows = $this->orderentrymodel->setOrderEntryEditAccess($VarOrderId, $this->companyid, 0);
            if ($VarRows)
                echo json_encode(array('errcode' => 1, 'msg' => 'Success!'));
            else {
                echo json_encode(array('errcode' => -1, 'msg' => ''));
            }
        }
    }
}
