<?php $ArrProfileInfo = fnGetUserLoggedInfo(1);
$VarUserType = $ArrProfileInfo['usertype'];
$VarProfilePermission = $ArrProfileInfo['pp'];
?>
<section class="sidebar">
    <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="treeview">
            <a href="<?php echo base_url() ?>dashboard/"><i class="fa fa-dashboard"></i> <span>Menu </span> <i
                    class="fa"></i></a>
            <ul class="treeview-menu">
                <?php if ($ArrProfileInfo['usertype'] == 4) { //CAD Dept. ?>
                    <li>
                        <a href="<?php echo base_url(CNFCOMPANY.'mcaduser/cadreceivedlist') ?>" class="small-box-footer">CAD REQUEST
                            RECEIVED LIST <i class="fa fa-arrow-circle-right"></i></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(CNFCOMPANY.'mcaduser/cadqueuelist') ?>" class="small-box-footer">CAD
                            QUEUE LIST <i class="fa fa-arrow-circle-right"></i></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(CNFCOMPANY.'mcaduser/cadindentlist') ?>" class="small-box-footer">CAD INDENT
                            RECEIVED LIST <i class="fa fa-arrow-circle-right"></i></a>
                    </li>
                <?php } ?>
                <?php if ($ArrProfileInfo['usertype'] == 11) { // QA Dept. ?>
                    <li>
                        <a href="<?php echo base_url(CNFCOMPANY.'mqausers/qareceivedlist') ?>" class="small-box-footer">QA REQUEST
                            RECEIVED LIST <i class="fa fa-arrow-circle-right"></i></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(CNFCOMPANY.'mqausers/qaqueuelist') ?>" class="small-box-footer">QA
                            QUEUE LIST <i class="fa fa-arrow-circle-right"></i></a>
                    </li>
                <?php } ?>
                <?php if ($ArrProfileInfo['usertype'] == 7) { ?>
                    <li>
                        <a href="<?php echo base_url(CNFCOMPANY.'mpurchaseuser/purchasereceivedlist') ?>"
                           class="small-box-footer">PURCHASE RECEIVED LIST<i
                                class="fa fa-arrow-circle-right"></i></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('dashboard/bompiapprovallist') ?>"
                           class="small-box-footer">PURCHASE INDENT - APPROVAL REQUEST<br/>SENT LIST <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(CNFCOMPANY.'mpurchaseuser/bomPurchaseReqQueueList') ?>"
                           class="small-box-footer">QUEUE
                            LIST <i class="fa fa-arrow-circle-right"></i></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('dashboard/bompurchaseindentlist') ?>"
                           class="small-box-footer">P.I. LIST <i class="fa fa-arrow-circle-right"></i></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(CNFCOMPANY.'mpurchaseuser/managepaymentsentlist') ?>"
                           class="small-box-footer">REQUEST SENT LIST<i
                                class="fa fa-arrow-circle-right"></i></a>
                    </li>
                    <?php
                    $VarSecondUriSegment = $this->uri->segment('2');
                    if ($VarSecondUriSegment == 'bomPurchasePayemntReq' || $VarSecondUriSegment == 'bompiprocesstracking' || $VarSecondUriSegment == 'bompideliveryFollowup' || $VarSecondUriSegment = 'bominvoicedetails') {
                        $VarThirdUriSegmentBpiInvId = $this->uri->segment('3');
                        $VarSecondUriSegmentBpiReqId = $this->uri->segment('4');
                        ?>
                        <li>
                            <a href="<?php echo base_url('dashboard/bompiprocesstracking') . '/' . $VarThirdUriSegmentBpiInvId . '/' . $VarSecondUriSegmentBpiReqId; ?>"
                               class="small-box-footer">P.I. PROCESS TRACKING<i
                                    class="fa fa-arrow-circle-right"></i></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('dashboard/bompideliveryFollowup') . '/' . $VarThirdUriSegmentBpiInvId . '/' . $VarSecondUriSegmentBpiReqId; ?>"
                               class="small-box-footer">BOM P.I. DELIVERY FOLLOW-UP<i
                                    class="fa fa-arrow-circle-right"></i></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('dashboard/bomLotApprStatus') . '/' . $VarThirdUriSegmentBpiInvId . '/' . $VarSecondUriSegmentBpiReqId; ?>"
                               class="small-box-footer">BOM LOT APPROVAL STATUS<i
                                    class="fa fa-arrow-circle-right"></i></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('dashboard/bominvoicedetails') . '/' . $VarThirdUriSegmentBpiInvId . '/' . $VarSecondUriSegmentBpiReqId; ?>"
                               class="small-box-footer">BOM INVOICE DETAILS<i
                                    class="fa fa-arrow-circle-right"></i></a>
                        </li>
                        <?php
                    }
                    ?>
                <?php } ?>
                <?php if ($ArrProfileInfo['usertype'] == 12) { //Finance Dept. ?>
                    <li>
                        <a href="<?php echo base_url('mfinance/bomPiAdvPaymentList') ?>"
                           class="small-box-footer">BOM PI - ADVANCE PAYMENT LIST<i
                                class="fa fa-arrow-circle-right"></i>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('request/Fabricrequest/yarnpaymentreqreceivedlist') ?>"
                           class="small-box-footer">REQUEST RECEIVED LIST - PAYMENTS
                           <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('request/Fabricrequest/yarnpaymentpaidlist') ?>"
                           class="small-box-footer">PAYMENT PAID LIST
                           <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($ArrProfileInfo['usertype'] == 5) { //Sampleing Dept. ?>
                    <li>
                        <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/samplereceivedlist') ?>"
                           class="small-box-footer">SAMPLE RECEIVED LIST<i
                                class="fa fa-arrow-circle-right"></i></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/samplequeuelist') ?>"
                           class="small-box-footer">SAMPLE QUEUE LSIT <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </li>
                <?php } ?>
                <?php if ($ArrProfileInfo['usertype'] == 8) { //Stores Dept. ?>
                    <li>
                        <a href="<?php echo base_url('request/Bomrequest/bompurchaseindentlist') ?>"
                           class="small-box-footer">P.I. LIST <i class="fa fa-arrow-circle-right"></i></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(CNFCOMPANY.'mstoreuser/bomindentlist') ?>" class="small-box-footer">INDENT
                            LIST
                            <i class="fa fa-arrow-circle-right"></i></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('request/Bomrequest/newitemlist') ?>" class="small-box-footer">NEW ITEM
                            LIST
                            <i class="fa fa-arrow-circle-right"></i></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('request/Bomrequest/surplusstocklist') ?>" class="small-box-footer">SURPLUS STOCK LIST
                            <i class="fa fa-arrow-circle-right"></i></a>
                    </li>
                <?php } ?>
            </ul>
            <ul class="treeview-menu">
                <!--<li><a href="<?php /**/ ?> "><i class="fa fa-circle-o"></i>For Sample Menu</a></li>-->
                <?php if ($ArrProfileInfo['usertype'] == 3 || $ArrProfileInfo['usertype'] == 15) { //Merchant and Planning ?>
                    <li><a href="<?php echo base_url('preCosting/componentCreation') ?> "><i class="fa fa-circle-o"></i>New Design Templates
                        </a></li>

                    <li><a href="<?php echo base_url('merchant/orderEnquiryList') ?> "><i class="fa fa-circle-o"></i>ENQUIRY
                            LIST
                        </a></li>
                    <li><a href="<?php echo base_url('merchant/manageWip') ?> "><i
                                class="fa fa-circle-o"></i>WIP LIST</a></li>
                    <li><a href="<?php echo base_url('merchant/manageallrequest') ?> "><i class="fa fa-circle-o"></i>REQUEST
                            SENT LIST</a></li>
                    <li><a href="<?php echo base_url('merchant/queueList') ?> "><i class="fa fa-circle-o"></i>QUEUE
                            LIST</a></li>
                    <li><a href="<?php echo base_url(CNFCOMPANY.'mcaduser/cadindentlist') ?> "><i class="fa fa-circle-o"></i>CAD
                            INDENT
                            LIST</a></li>
                    <li><a href="<?php echo base_url(CNFCOMPANY.'mfabricuser/indentlist') ?> "><i class="fa fa-circle-o"></i>FABRIC
                            INDENT
                            LIST</a></li>
                    <li><a href="<?php echo base_url(CNFCOMPANY.'mstoreuser/bomindentlist') ?> "><i class="fa fa-circle-o"></i>BOM
                            INDENT
                            LIST</a></li>
                    <li><a href="<?php echo base_url('dashboard/bompurchaseindentlist/') ?> "><i
                                class="fa fa-circle-o"></i>P.I. LIST</a></li>
                <?php } elseif ($ArrProfileInfo['usertype'] == 2) {
                    ?>
                    <li><a href="<?php echo base_url('management/orderEnquiryList') ?> "><i class="fa fa-circle-o"></i>ENQUIRY
                            AUTHORIZATION LIST</a></li>
                    <li><a href="<?php echo base_url('management/manageAuthorizationRequest') ?> "><i
                                class="fa fa-circle-o"></i>OTHER AUTHORIZATION LIST</a></li>
                    <li><a href="<?php echo base_url('management/manageWip') ?> "><i class="fa fa-circle-o"></i>WIP LIST</a>
                    </li>
                    <li><a href="<?php echo base_url('management/queueList') ?> "><i class="fa fa-circle-o"></i>QUEUE
                            LIST</a></li>
                    <li><a href="<?php echo base_url(CNFCOMPANY.'mcaduser/cadindentlist') ?> "><i class="fa fa-circle-o"></i>CAD
                            INDENT
                            LIST</a></li>
                    <li><a href="<?php echo base_url(CNFCOMPANY.'mfabricuser/indentlist') ?> "><i class="fa fa-circle-o"></i>FABRIC
                            INDENT
                            LIST</a></li>
                    <li><a href="<?php echo base_url(CNFCOMPANY.'mstoreuser/bomindentlist') ?> "><i class="fa fa-circle-o"></i>BOM
                            INDENT
                            LIST</a></li>
                    <li><a href="<?php echo base_url('dashboard/bompurchaseindentlist') ?> "><i
                                class="fa fa-circle-o"></i>P.I. LIST</a></li>
                    <li><a href="<?php echo base_url('management/bomPiApprovalList') ?> "><i class="fa fa-circle-o"></i>BOM
                            P.I. APPROVAL LIST</a></li>
                <?php } elseif ($ArrProfileInfo['usertype'] == 14) // Yarn Store
                {
                    ?>
                    <li><a href="<?php echo base_url('request/myarnstore/') ?> "><i class="fa fa-circle-o"></i>PURCHASE
                            INDENT LIST</a></li>
                    <li><a href="<?php echo base_url('request/myarnstore/orderstocklist') ?> "><i class="fa fa-circle-o"></i>YARN ORDER STOCK LIST</a></li>
                    <li><a href="<?php echo base_url('request/myarnstore/surplusstocklist') ?> "><i class="fa fa-circle-o"></i>YARN SURPLUS STOCK LIST</a></li>
                    <li><a href="<?php echo base_url('request/myarnstore/generalstocklist') ?> "><i class="fa fa-circle-o"></i>YARN GENERAL STOCK LIST</a></li>
                <?php } elseif ($ArrProfileInfo['usertype'] == 6) // Yarn Store
                {
                    ?>
                    <li><a href="<?php echo base_url('request/Fabricrequest/reqreceivedlist') ?> "><i class="fa fa-circle-o"></i>REQUEST RECEIVED LIST</a></li>
                    <li><a href="<?php echo base_url('request/Fabricrequest/qalist') ?> "><i class="fa fa-circle-o"></i>QUEUE LIST</a></li>
                    <li><a href="<?php echo base_url('request/Fabricrequest/reqsentlist') ?> "><i class="fa fa-circle-o"></i>REQUEST SENT LIST</a></li>
                    <?php
                }
                ?>
            </ul>
        </li>
        <?php
        if ($VarProfilePermission == 1) {
            ?>
            <?php
        } elseif ($VarProfilePermission == 2) { ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gears"></i> <span>Config.</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Master Data Conf. 1 <i
                                class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>macceptancelevel/manage">Acceptance
                                    Level</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mbom/manage/">Bill of Material</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mbomvendor/managebomvendor/">Bill of
                                    Material Vendor</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mbrand/manage/">Brand</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mbuyer/manage/">Buyer</a></li>
                            
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bominstrumenttype/manage/">BOM - Item Description</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bomblend/manage/">BOM - Blend (%)</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bomcontent/manage/">BOM - Content</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bommaterial/manage/">BOM - Material</a></li>
                            <li>
                                <a href="<?php echo base_url() . CNFCOMPANY ?>mastersetup/managechecklist/">Checklist</a>
                            </li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mcolormatchstd/manage">Colour Matching
                                    Standard</a></li>
                        </ul>
                        
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Master Data Conf. 2 <i
                                class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <!--<li><a href="<?php /*echo base_url().CNFCOMPANY*/ ?>mdyeintype/managedyeintype/">Dyeing Type</a></li>-->
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mdsr/managedsr/">Dyeing Special
                                    Request</a>
                            </li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mdyeingvendor/managedyeingvendor/">Dyeing Vendor</a></li>
                            <!--<li><a href="<?php /*echo base_url() . CNFCOMPANY */?>membelltype/manageembelltype/">Embellishment</a></li>-->
                            <li>
                                <a href="#"><i class="fa fa-circle-o"></i>Embellishment Details
                                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="<?php echo base_url() . CNFCOMPANY ?>membellishment/manageType"><i
                                                class="fa fa-circle-o"></i>Embellishment Type</a></li>
                                    <li><a href="<?php echo base_url() . CNFCOMPANY ?>membellishment/manageMediumMaterial"><i
                                                class="fa fa-circle-o"></i>Medium / Material</a></li>
                                    <li><a href="<?php echo base_url() . CNFCOMPANY ?>membellishmentvendor/manageembellishmentvendor"><i
                                                class="fa fa-circle-o"></i>Embellishment Vendor</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mastersetup/managemodeofenquiry/">Mode of
                                    Enquiry</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>fabricblend/manage/">Fabric Blend (%)</a>
                            </li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>fabriccontent/manage/">Fabric Content</a>
                            </li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>fabricname/manage/">Fabric Name</a></li>
                            <li>
                                <a href="<?php echo base_url() . CNFCOMPANY ?>mfabricfinishwet_dry/manage">Fabric
                                    Finish Wet / Dry</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Master Data Conf. 3 <i
                                class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mgarmentsampling/manage/">Garment Sample
                                    Requirement</a>
                            </li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mgpd/managegpd/">Garment Parts</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mlab/managelab/">Lab</a></li>
                            <li>
                                <a href="#"><i class="fa fa-circle-o"></i>Logistics Details
                                    <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="<?php echo base_url() . CNFCOMPANY ?>mlogistics/forwardingAgent"><i
                                                class="fa fa-circle-o"></i>Forwarding Agent</a></li>
                                    <li><a href="<?php echo base_url() . CNFCOMPANY ?>mlogistics/clearingAgent"><i
                                                class="fa fa-circle-o"></i>Clearing Agent</a></li>
                                    <li><a href="<?php echo base_url() . CNFCOMPANY ?>mlogistics/importer"><i
                                                class="fa fa-circle-o"></i>Importer</a></li>
                                    <li><a href="<?php echo base_url() . CNFCOMPANY ?>mconsignor/manageconsignor"><i
                                                class="fa fa-circle-o"></i>Consignor</a></li>
                                    <li><a href="<?php echo base_url() . CNFCOMPANY ?>mconsignee/manageconsignee"><i
                                                class="fa fa-circle-o"></i>Consignee</a></li>
                                </ul>
                                <!--<a href="<?php /*echo base_url() . CNFCOMPANY */ ?>mlogistics/manage">Logistics Details</a>-->
                            </li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mpackingmaterial/managepackingmaterial/">Packing
                                    Material</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mpackingcode/managepackingcode/">Packing
                                    Code</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Master Data Conf. 4 <i
                                class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mprocessflow/manageprocessflow/">Process
                                    Flow</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mport/manageport/">Port</a></li>
                            <!--<li><a href="<?php /*echo base_url().CNFCOMPANY*/ ?>msizerange/managesizerange/">Size Range</a></li>-->
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mauth/managetauth">Testing Authority</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarnsplreq/manage">Yarn Spec. Request</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarnvendor/manage">Yarn Vendor</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarnblend/manage/">Yarn Blend</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarncontent/manage/">Yarn Content</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarncount/manage/">Yarn Count</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mtypemedium/manage/">Type / Medium</a></li>


                            <!------------------------------------------------- Removed master Pages------------------------------------------------->
                            <!--<li><a href="<?php /*echo base_url() . CNFCOMPANY */?>mauth/manageapprovalauth">Approval
                                    Authority</a></li>
                            <li><a href="<?php /*echo base_url() . CNFCOMPANY */?>mdyeingmethod/managedyeingmethod/">Dyeing
                                    Method</a></li>
                            <li><a href="<?php /*echo base_url() . CNFCOMPANY */?>mastersetup/manageenquirytype/">Enquiry
                                    Type</a></li>
                            <li><a href="<?php /*echo base_url() . CNFCOMPANY */?>mwpg/managewpg/">Fabric Finish </a></li>
                            <li><a href="<?php /*echo base_url() . CNFCOMPANY */?>mdpf/managedpf/">Fabric Finish Stage /
                                    Form</a></li>
                            <li><a href="<?php /*echo base_url() . CNFCOMPANY */?>mauth/manageinspectionauth">Inspection
                                    Authority</a></li>
                            <li><a href="<?php /*echo base_url() . CNFCOMPANY */?>mlotinspection/manage/">Lot Inspection
                                    <br/>Details</a></li>
                            <li><a href="<?php /*echo base_url() . CNFCOMPANY */?>myarn/manageyarnpurchasetype/">Yarn
                                    Purchase Type</a></li>
                            <li><a href="<?php /*echo base_url() . CNFCOMPANY */?>myarncount/manageyarncount/">Yarn
                                    Details</a></li>-->
                        </ul>
                    </li>
                    <!--@TODO Remove because its needed for cad,sample abd bom request. Also remove its controller,view,js,model and its db table if removing permanently-->
                    <!--<li>
                        <a href="#"><i class="fa fa-circle-o"></i> CAD Master Data <i
                                class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php /*echo base_url() . CNFCOMPANY */?>mcadrequirement/managecadrequirement/">CAD
                                    Requirement</a></li>
                            <li><a href="<?php /*echo base_url() . CNFCOMPANY */?>mcadrequirement/managecadpurpose/">CAD
                                    purpose</a></li>
                        </ul>
                    </li>-->
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-user"></i> <span>Manage User</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url( CNFCOMPANY.'mcaduser/manage') ?>">CAD Dept. LIST</a></li>
                    <li><a href="<?php echo base_url(CNFCOMPANY . 'mdocandlocuser/manage') ?>"> Documentation
                            and Logistics Dept. LIST</a>
                    </li>
                    <li><a href="<?php echo base_url(CNFCOMPANY . 'mfabricuser/manage') ?>">Fabric Dept. LIST</a>
                    </li>
                    <li><a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/manage') ?>">
                            Finance Dept. LIST</a></li>
                    <li><a href="<?php echo base_url(CNFCOMPANY.'mlabuser/manage') ?>">Lab
                            Dept. LIST</a></li>
                    <li><a href="<?php echo base_url(CNFCOMPANY.'mmanagementuser/manage'); ?>">Management LIST</a></li>
                    <li><a href="<?php echo base_url(CNFCOMPANY.'mmerchantuser/manage'); ?>">Merchant LIST</a></li>
                    <li><a href="<?php echo base_url(CNFCOMPANY.'mpurchaseuser/manage') ?>">
                            Purchase Dept. LIST</a></li>
                    <li><a href="<?php echo base_url(CNFCOMPANY.'mqausers/manage') ?>">
                            Quality assurance Dept. LIST</a></li>
                    <li><a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/manage') ?>">Sampling Dept. LIST</a>
                    </li>
                    <li><a href="<?php echo base_url(CNFCOMPANY.'mstoreuser/manage') ?>">
                            Stores Dept. LIST</a></li>
                    <li><a href="<?php echo base_url(CNFCOMPANY . 'mproductionuser/manage') ?>"> Production
                            Dept. LIST</a></li>
                </ul>
            </li>
            <!--<li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i>
                    <span class="pull-right-container">Documentation and Logistics<i
                            class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php /*echo base_url(CNFCOMPANY . 'mdocandloc/addeditdocandlocuser') */ ?>"><i
                                class="fa fa-circle-o"></i> Add Documentation and Logistics Users</a>
                    </li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i>
                    <span class="pull-right-container">Fabric<i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php /*echo base_url(CNFCOMPANY . 'mfabric/addeditfabricuser') */ ?>"><i
                                class="fa fa-circle-o"></i> Add Fabric Users</a></li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i>
                    <span class="pull-right-container">Finance<i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php /*echo base_url('mfinance/addeditfinanceuser') */ ?>"><i class="fa fa-circle-o"></i>
                            Add Finance Users</a></li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i>
                    <span class="pull-right-container">Lab<i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php /*echo base_url('mlabusers/addeditlabuser') */ ?>"><i class="fa fa-circle-o"></i> Add
                            Lab Users</a></li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gears"></i><span>Management</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php /*echo base_url('management/addeditmanagement'); */ ?>">Add Management</a></li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gears"></i><span>Merchant</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?php /*echo base_url() . CNFCOMPANY */ ?>mastersetup/addeditmerchant">Add Merchant</a>
                    </li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i>
                    <span class="pull-right-container">Purchase<i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php /*echo base_url('mpurchase/addeditpurchaseuser') */ ?>"><i class="fa fa-circle-o"></i>
                            Add Purchase Users</a></li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i>
                    <span class="pull-right-container">Quality assurance<i
                            class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php /*echo base_url('mqausers/addeditqauser') */ ?>"><i class="fa fa-circle-o"></i> Add
                            Quality assurance Users</a></li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gears"></i><span>Sampling</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php /*echo base_url('msampling/addeditsamplinguser') */ ?>">Add Sampling User</a></li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i>
                    <span class="pull-right-container">Stores<i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php /*echo base_url('mstores/addeditstoresuser') */ ?>"><i class="fa fa-circle-o"></i> Add
                            Stores Users</a></li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i>
                    <span class="pull-right-container">Production<i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php /*echo base_url(CNFCOMPANY . 'mproduction/addeditproductionsuser') */ ?>"><i
                                class="fa fa-circle-o"></i> Add Production Users</a></li>

                </ul>
            </li>-->
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gears"></i><span>Team</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('company/merteam/teamList'); ?>">Team LIST</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gears"></i><span>Enquiry Authorization Role</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('management/assignroles'); ?>">Management Roles</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i><span>User Designation</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('dashboard/manageDesignations'); ?>">Manage Designation</a></li>
                </ul>
            </li>
            <?php
        } elseif ($VarProfilePermission == 3) { ?>
        <?php }
        ?>
    </ul>
</section>
