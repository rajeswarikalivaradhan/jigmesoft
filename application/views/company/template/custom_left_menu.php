<?php $ArrProfileInfo = fnGetUserLoggedInfo(1);
$ArrCompanyRes = $this->companymodel->fnGetCompanyInfo($ArrProfileInfo['companyid']);
$ArrRoleGetInfo = $this->companymodel->fnGetRoleWiseInfo($ArrProfileInfo['id'],'');
$ArrRoleInfo=count($ArrRoleGetInfo)>0 ? explode(',',$ArrRoleGetInfo[0]['title']):[];
$VarDesignation = '-';
// if(!empty($ArrProfileInfo['desgnid'])) {
    // $ObjDesignation = $this->companymodel->headerUserDesignation($ArrProfileInfo['desgnid']);
    // $VarDesignation = @$ObjDesignation->desgn;
// }
if(!empty($ArrProfileInfo['id'])) {
    $ObjDesignation = $this->companymodel->headerUserDesignationNew($ArrProfileInfo['id']);
    $VarDesignation = @$ObjDesignation->designation;
}
?>


<nav class="navbar-default navbar-header" >
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <!--<div class="navbar-header">-->
        <!--    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">-->
        <!--        <span class="sr-only">Toggle navigation</span>-->
        <!--        <span class="icon-bar"></span>-->
        <!--        <span class="icon-bar"></span>-->
        <!--        <span class="icon-bar"></span>-->
        <!--    </button>-->
        <!--</div>-->

        <?php 
            // print_r(fnGetUserLoggedInfo('1')); 
            $userInfo = fnGetUserLoggedInfo('1');
            $userType = $userInfo['usertype'];
        ?>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <?php if($userType == '8') { ?>
                <ul class="menu-nav nav navbar-nav pull-right">
                    <?php if(count($ArrRoleInfo)>0 && in_array('Purchase Indent List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'purchaseindentlist' || $this->router->fetch_method() == 'storepiupdate' || $this->router->fetch_method() == 'storepurchaseindentdetails') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/purchaseindentlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Purchase Indent List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Supply Closure List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'supplyclosurelist' || $this->router->fetch_method() == 'supplyclosuredetails' || $this->router->fetch_method() == 'pirefdetails') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/supplyclosurelist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Supply Closure List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('M.I. List',$ArrRoleInfo)) {?>
                    <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'mireceivedlist' || $this->router->fetch_method() == 'mipendinglist' ||$this->router->fetch_method() == 'mireceiveddetails' ||$this->router->fetch_method() == 'miissuedlist' || $this->router->fetch_method() == 'orderIssuedlist'
                            ) echo "active";?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">M.I. List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/mireceivedlist') ?>" class="nav-link">
                                       <span class="nav-text"><span>M.I. List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/mipendinglist') ?>" class="nav-link">
                                       <span class="nav-text"><span>M.I. Pending List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/miissuedlist') ?>" class="nav-link">
                                       <span class="nav-text"><span>M.I. Issued List</span></span>
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Order Stock List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'itemList' || $this->router->fetch_method() == 'orderstockdetails' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/itemList') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Order Stock List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Order Closure List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'orderclosurelist' || $this->router->fetch_method() == 'orderclosuredetails' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/orderclosurelist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Order Closure List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('D.C. List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'dclist' || $this->router->fetch_method() == 'bomDCDetails' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/dclist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>D.C. List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Surplus Stock List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'surplusstocklist' || $this->router->fetch_method() == 'surplusstockdetails' || $this->router->fetch_method() == 'surplus_draftdc' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/surplusstocklist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Surplus Stock List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Stock Transfer Memo List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'stockTransferMemoList' || $this->router->fetch_method() == 'stocktransferdetails') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/stockTransferMemoList') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Stock Transfer Memo List</span></span>                  
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            <?php } else if($userType == '12') { ?>
                <ul class="menu-nav nav navbar-nav pull-right">
                    <?php if(count($ArrRoleInfo)>0 && in_array('Request Received List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'requestreceivedlist' || $this->router->fetch_method() == 'financereqreceiveddetails') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/requestreceivedlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Request Received List</span></span>                  
                        </a>
                    </li>
                    <?php }  if(count($ArrRoleInfo)>0 && in_array('Bill Paid List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'billpaidlist' || $this->router->fetch_method() == 'billpaiddetails') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/billpaidlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Bill Paid List</span></span>                  
                        </a>
                    </li>
                    <?php }  if(count($ArrRoleInfo)>0 && in_array('Stock Transfer Memo List',$ArrRoleInfo)) { ?>
                    <li class="<?php if($this->router->fetch_method() == 'stockTransferMemoList' || $this->router->fetch_method() == 'stocktransferdetails') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/stockTransferMemoList') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Stock Transfer Memo List</span></span>                  
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            <?php } else if($userType == '7') { ?> 
                <ul class="menu-nav nav navbar-nav pull-right">
                    <?php if(count($ArrRoleInfo)>0 && in_array('Request Received List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'purchasereceivedlist' || $this->router->fetch_method() == 'departmentapproval') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchasereceivedlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Request Received List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Queue List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'bomPurchaseReqQueueList' || $this->router->fetch_method() == 'purchaseQueueDetails' || $this->router->fetch_method() == 'draftpi') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/bomPurchaseReqQueueList') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Queue List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Request Sent List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'purchasesentlist' || $this->router->fetch_method() == 'requestsentdetails') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchasesentlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Request Sent List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('P.I. List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'purchaseindentlist' || $this->router->fetch_method() == 'purchaseindentdetails') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchaseindentlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>P.I. List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Bill Paid List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'billpaidlist' || $this->router->fetch_method() == 'billpaidlist') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/billpaidlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Bill Paid List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Stock Transfer Memo List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'stockTransferMemoList' || $this->router->fetch_method() == 'stocktransferdetails') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/stockTransferMemoList') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Stock Transfer Memo List</span></span>                  
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            <?php } else if($userType == '5') { ?>
                <ul class="menu-nav nav navbar-nav pull-right">
                    <?php if(count($ArrRoleInfo)>0 && in_array('Request Received List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'samplereceivedlist' || $this->router->fetch_method() == 'department' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/samplereceivedlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Request Received List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Queue List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'samplequeuelist' || $this->router->fetch_method() == 'qa' || $this->router->fetch_method() == 'dclist' || $this->router->fetch_method() ==  'qarequest') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/samplequeuelist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Queue List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Request Sent List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'samplesentlist' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/samplesentlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Request Sent List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Garment Issued List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'garmentissuedlist' || $this->router->fetch_method() == 'garmentissueddetails' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/garmentissuedlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Garment Issued List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('D.C. List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'midclist' || $this->router->fetch_method() == 'sampleDCDetails' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/midclist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>D.C. List</span></span>                  
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            <?php } else if($userType == '4') { ?>
                <ul class="menu-nav nav navbar-nav pull-right">
                    <?php if(count($ArrRoleInfo)>0 && in_array('Request Received List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'cadreceivedlist' || $this->router->fetch_method() == 'cadDeptDetails' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'mcaduser/cadreceivedlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Request Received List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Queue List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'cadqueuelist' || $this->router->fetch_method() == 'cadDeptQueueDetails' || $this->router->fetch_method() == 'qarequest' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'mcaduser/cadqueuelist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Queue List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Request Sent List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'cadsentlist' || $this->router->fetch_method() == 'cadDeptSentDetails' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'mcaduser/cadsentlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Request Sent List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('M.I. Received List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'cadindentlist'|| $this->router->fetch_method() == 'cadIndentDetails' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'mcaduser/cadindentlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>M.I. Received List</span></span>
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('D.C. List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'caddclist'|| $this->router->fetch_method() == 'cadDCDetails' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'mcaduser/caddclist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>D.C. List</span></span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            <?php } else if ($userType == '11') { ?>
                <ul class="menu-nav nav navbar-nav pull-right">
                    <?php if(count($ArrRoleInfo)>0 && in_array('Request Received List',$ArrRoleInfo)) {?>
                    <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'qareceivedlist'||$this->router->fetch_method() == 'cadqareceivedlist' || $this->router->fetch_method() == 'sampleqareceivedlist'||$this->router->fetch_method() == 'productionqareceiveddetails'||$this->router->fetch_method() == 'qareceiveddetails' || $this->router->fetch_method() == 'productionqareceivedlist') echo "active";?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Request Received List<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/qareceivedlist') ?>" class="nav-link">
                                    <span class="nav-text"><span>All QA Request</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/cadqareceivedlist') ?>" class="nav-link">
                                    <span class="nav-text"><span>CAD QA Request</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/sampleqareceivedlist') ?>" class="nav-link">
                                    <span class="nav-text"><span>Sample QA Request</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/productionqareceivedlist') ?>" class="nav-link">
                                    <span class="nav-text"><span>Production QA Request</span></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Queue List',$ArrRoleInfo)) {?>
                    <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'qaqueuelist'||$this->router->fetch_method() == 'cadqaqueuelist'||$this->router->fetch_method() == 'sampleqaqueuelist'||$this->router->fetch_method() == 'productionqaqueuelist'||$this->router->fetch_method() == 'queuelist') echo "active";?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Queue List<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/qaqueuelist') ?>" class="nav-link">
                                    <span class="nav-text"><span>All QA Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/cadqaqueuelist') ?>" class="nav-link">
                                    <span class="nav-text"><span>CAD QA Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/sampleqaqueuelist') ?>" class="nav-link">
                                    <span class="nav-text"><span>Sample QA Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/productionqaqueuelist') ?>" class="nav-link">
                                    <span class="nav-text"><span>Production QA Queue</span></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                     <?php } ?>
                </ul>
            <?php } else { ?>
                <ul class="menu-nav nav navbar-nav pull-right">
                    <li class="<?php if($this->router->fetch_method() == 'orderEnquiryList' || $this->router->fetch_method() == 'addenquiry' || $this->router->fetch_method()=='iorenquirylist' || $this->router->fetch_method()=='isrenquirylist' || $this->router->fetch_method()=='index' || $this->router->fetch_method()=='enquiryview' || ($this->router->fetch_method()=='componentCreation' && $this->uri->segment(4)!='wiplist')) echo "active";?> nav-item dropdown">
                    <?php if($userType != '2' && (count($ArrRoleInfo)>0 && in_array('Enquiry List',$ArrRoleInfo))) { ?>
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Enquiry List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="allenquiry dropdown-submenu nav-item <?php if($this->router->fetch_method() == 'orderEnquiryList' || $this->router->fetch_method() == 'addenquiry') echo "active";?>">
                                <a tabindex="-1" href="<?= base_url('merchant/orderEnquiryList') ?>">All Enquiry List <span class="carets"></span></a>
                                <ul class="dropdown-menu">
                                    <li class="<?php if($this->router->fetch_method() == 'orderEnquiryList') echo "active";?>"><a href="<?= base_url('merchant/orderEnquiryList') ?>">All</a></li>
                                    <li><a href="#" id="active">Active</a></li>
                                    <li><a href="#" id="inactive">Inactive</a></li>
                                </ul>
                            </li>
                            <li class="allenquiry dropdown-submenu nav-item  <?php if($this->router->fetch_method() == 'iorenquirylist') echo "active";?>">
                              <a href="<?= base_url('merchant/iorenquirylist') ?>">IOR List <span class="carets"></span></a>
                                <ul class="dropdown-menu">
                                    <li class="<?php if($this->router->fetch_method() == 'iorenquirylist') echo "active";?>"><a href="<?= base_url('merchant/iorenquirylist') ?>">All</a></li>
                                    <li><a href="#" id="ioractive">Active</a></li>
                                    <li><a href="#" id="iorinactive">Inactive</a></li>
                                </ul>
                            </li>
                            <li class="allenquiry dropdown-submenu nav-item <?php if($this->router->fetch_method() == 'isrenquirylist') echo "active";?>">
                                <a href="<?= base_url('merchant/isrenquirylist')?>">ISR List <span class="carets"></span></a>
                                <ul class="dropdown-menu">
                                    <li class="<?php if($this->router->fetch_method() == 'isrenquirylist') echo "active";?>"><a href="<?= base_url('merchant/isrenquirylist') ?>">All</a></li>
                                    <li><a href="#" id="isractive">Active</a></li>
                                    <li><a href="#" id="isrinactive">Inactive</a></li>
                                </ul>
                            </li>
                         </ul>
                     <?php } else if($userType == '2' && (count($ArrRoleInfo)>0 && in_array('Enquiry Authorization List',$ArrRoleInfo))) { ?>
                        <a href="<?= base_url('management/orderEnquiryList') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Enq. Auth. List</span></span>
                        </a>
                    <?php } ?>     
                     </li>
                    <?php if(count($ArrRoleInfo)>0 && in_array('WIP List',$ArrRoleInfo)) {?>
                    <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'manageWip'||$this->router->fetch_method() == 'manageIOR'
                            ||$this->router->fetch_method() == 'manageISR'|| $this->uri->segment(2) == 'wipPrecosting' || ($this->router->fetch_method()=='componentCreation' && $this->uri->segment(4)=='wiplist') ||
                            ($this->uri->segment(1) == 'WorkInProcess'&&($this->router->fetch_method()=='index'||$this->router->fetch_method()=='fabric_program'))
                        ) echo "active";?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">WIP List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('merchant/manageWip') ?>" class="nav-link">
                                        <span class="nav-text"><span>All WIP List</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/manageWip') ?>" class="nav-link">
                                        <span class="nav-text"><span>All WIP List</span></span>
                                    </a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('merchant/manageIOR') ?>" class="nav-link">
                                        <span class="nav-text"><span>IOR List</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/manageIOR') ?>" class="nav-link">
                                        <span class="nav-text"><span>IOR List</span></span>
                                    </a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('merchant/manageISR') ?>" class="nav-link">
                                        <span class="nav-text"><span>ISR List</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/manageISR') ?>" class="nav-link">
                                        <span class="nav-text"><span>ISR List</span></span>
                                    </a>
                                <?php } ?>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <li class="nav-item dropdown <?php if(($this->router->fetch_method() == 'index'&&$this->uri->segment(1)=='MerchantRequestSent')||$this->router->fetch_method() == 'cad'||
                        $this->router->fetch_method() == 'sample'||$this->router->fetch_method() == 'bom'||$this->router->fetch_method() == 'bom2'||
                        $this->router->fetch_method() == 'embellishment'||$this->router->fetch_method() == 'fabric'||$this->router->fetch_method() == 'production'||
                        $this->router->fetch_method() == 'vessel'||$this->router->fetch_method() == 'stationery'||$this->router->fetch_method() == 'cadrequestlist'||
                        $this->router->fetch_method() == 'samplerequestlist'||$this->router->fetch_method() == 'bomrequestlist'||$this->router->fetch_method() == 'requestlist' || $this->router->fetch_method() == 'managament') echo "active";?>">
                        <?php if($userType != '2' && (count($ArrRoleInfo)>0 && in_array('Request Sent List',$ArrRoleInfo))) { ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Request Sent List <span class="caret"></span></a>
                        <?php } else if($userType == '2' && (count($ArrRoleInfo)>0 && in_array('Work Authorization List',$ArrRoleInfo))) { ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Work Auth. List<span class="caret"></span></a>
                        <?php } ?>
                        <ul class="dropdown-menu">
                            <!-- <li class="nav-item">
                                <a href="<?= base_url('MerchantRequestSent') ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>All Request</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>All Req. Auth.</span></span>
                                    <?php } ?>
                                </a>
                            </li> -->

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent') ?>" class="nav-link">
                                        <span class="nav-text"><span>All Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/common_list') ?>" class="nav-link">
                                        <span class="nav-text"><span>All Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/cad') ?>" class="nav-link">
                                        <span class="nav-text"><span>CAD Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/cad') ?>" class="nav-link">
                                        <span class="nav-text"><span>CAD Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                    <?php if($userType != '2') { ?>
                                        <a href="<?= base_url('MerchantRequestSent/sample') ?>" class="nav-link">
                                            <span class="nav-text"><span>Sample Request</span></span>
                                        </a>
                                    <?php } else if($userType == '2') { ?>
                                        <a href="<?= base_url('management/sample') ?>" class="nav-link">
                                            <span class="nav-text"><span>Sample Req. Auth.</span></span>
                                        </a>
                                    <?php } ?>
                            </li>
                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/bom') ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/bom') ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/bom2') ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/bom2') ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/embellishment') ?>" class="nav-link">
                                        <span class="nav-text"><span>Embellishment Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/embellishment') ?>" class="nav-link">
                                        <span class="nav-text"><span>Embellishment Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/fabric') ?>" class="nav-link">
                                        <span class="nav-text"><span>Fabric Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/fabric') ?>" class="nav-link">
                                        <span class="nav-text"><span>Fabric Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/production') ?>" class="nav-link">
                                        <span class="nav-text"><span>Production Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/production') ?>" class="nav-link">
                                        <span class="nav-text"><span>Production Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/vessel') ?>" class="nav-link">
                                        <span class="nav-text"><span>Vessel Booking Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/vessel') ?>" class="nav-link">
                                        <span class="nav-text"><span>Vessel Booking Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/stationery') ?>" class="nav-link">
                                        <span class="nav-text"><span>Stationery Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/stationery') ?>" class="nav-link">
                                        <span class="nav-text"><span>Stationery Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                        </ul>
                    </li>
                    <?php if(count($ArrRoleInfo)>0 && in_array('Queue List',$ArrRoleInfo)) {?>
                    <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'merchantallqueue'||$this->router->fetch_method() == 'merchantcadqueue'||
                        $this->router->fetch_method() == 'merchantsamplequeue'||$this->router->fetch_method() == 'merchantbom2queue'||$this->router->fetch_method() == 'merchantembellishmentqueue'||
                        $this->router->fetch_method() == 'merchantfabricqueue'||$this->router->fetch_method() == 'merchantproductionqueue'||$this->router->fetch_method() == 'merchantvesselqueue'||
                        $this->router->fetch_method() == 'merchantstationeryqueue'||$this->uri->segment(3) == 'merchantqueue'||$this->router->fetch_method() == 'managementcadqueue'||
                        $this->router->fetch_method() == 'managementsamplequeue'||$this->router->fetch_method() == 'managementbomqueue'|| $this->router->fetch_method() == 'merchantbomqueue'|| $this->router->fetch_method() == 'managementqueue'|| $this->router->fetch_method() == 'managementfabricqueue') echo "active";?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Queue List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                <a href="<?= base_url('company/mqausers/merchantallqueue') ?>" class="nav-link">
                                    <span class="nav-text"><span>All Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('company/mqausers/merchantcadqueue') ?>" class="nav-link">
                                        <span class="nav-text">
                                            <span>CAD Queue</span>
                                        </span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('company/mqausers/managementcadqueue') ?>" class="nav-link">
                                        <span class="nav-text">
                                            <span>CAD Queue</span>
                                        </span>
                                    </a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?php echo base_url('company/mqausers/merchantsamplequeue') ?>" class="nav-link">
                                        <span class="nav-text"><span>Sample Queue</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?php echo base_url('company/mqausers/managementsamplequeue') ?>" class="nav-link">
                                        <span class="nav-text"><span>SAMPLE Queue</span></span>
                                    </a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <?php if($userType == '2') { ?>
                                    <a href="<?php echo base_url('company/mqausers/managementbomqueue') ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) Queue</span></span>
                                    </a>
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) Queue</span></span>
                                    </a>
                                <?php } else if($userType == '3' || $userType == '15') { ?> <!-- added planning dept. condition|| $userType == '15' -->
                                    <a href="<?php echo base_url('company/mqausers/merchantbomqueue') ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) Queue</span></span>
                                    </a>
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) Queue</span></span>
                                    </a>
                                <?php } else { ?>
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) Queue</span></span>
                                    </a>
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) Queue</span></span>
                                    </a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('company/mqausers/merchantembellishmentqueue') ?>" class="nav-link">
                                    <span class="nav-text"><span>Embellishment Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <?php if($userType == '2') { ?>
                                    <a href="<?php echo base_url('company/mqausers/managementfabricqueue') ?>" class="nav-link">
                                        <span class="nav-text"><span>Fabric Queue</span></span>
                                    </a>
                                <?php } else if($userType == '3' || $userType == '15') { ?> <!-- added planning dept. condition|| $userType == '15' -->
                                    <a href="<?php echo base_url('company/mqausers/merchantfabricqueue') ?>" class="nav-link">
                                        <span class="nav-text"><span>Fabric Queue</span></span>
                                    </a>
                                <?php } else { ?>
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>Fabric Queue</span></span>
                                    </a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('company/mqausers/merchantproductionqueue') ?>" class="nav-link">
                                    <span class="nav-text"><span>Production Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('company/mqausers/merchantvesselqueue') ?>" class="nav-link">
                                    <span class="nav-text"><span>Vessel Booking Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('company/mqausers/merchantstationeryqueue') ?>" class="nav-link">
                                    <span class="nav-text"><span>Stationery Queue</span></span>
                                </a>
                            </li>
                        </ul>
                        <b class="sub-arrow"></b>
                    </li>
                    <?php } ?>
                    <?php if(count($ArrRoleInfo)>0 && in_array('M.I. List',$ArrRoleInfo)) { ?>
                    <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'allmaterialindent'||$this->router->fetch_method() == 'cadmaterialindent'||
                        $this->router->fetch_method() == 'bommaterialindent'||$this->router->fetch_method() == 'bom2materialindent'||$this->router->fetch_method() == 'fabricmaterialindent'||
                        $this->router->fetch_method() == 'stationerymaterialindent') echo "active";?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">M.I. List <span class="caret"></span></a>
                        <!-- <?php if($userType != '2') { ?> -->
                        <!-- <?php } else if($userType == '2') { ?> -->
                            <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Material Indent Auth. List <span class="caret"></span></a> -->
                        <!-- <?php } ?> -->
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                <a href="<?= base_url('merchant/allmaterialindent') ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>All Material Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>All M.I. Auth.</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('merchant/cadmaterialindent') ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>CAD Material Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>CAD M.I. Auth.</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('merchant/bommaterialindent') ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>BOM (A1) Material Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>BOM (A1) M.I. Auth.</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('merchant/bom2materialindent') ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>BOM (A2) Material Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>BOM (A2) M.I. Auth.</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('merchant/fabricmaterialindent') ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>Fabric Material Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>Fabric M.I. Auth.</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('merchant/stationerymaterialindent') ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>Stationery Material Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>Stationery M.I. Auth.</span></span>
                                    <?php } ?>
                                </a>
                            </li>

                        </ul>
                        <b class="sub-arrow"></b>
                    </li>
                    <?php } ?>
                    <?php if($userType != '2' && (count($ArrRoleInfo)>0 && in_array('M.I. List',$ArrRoleInfo))) { ?>
                    <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'allpurchaseindent'||$this->router->fetch_method() == 'yarndeptpilist'||
                        $this->router->fetch_method() == 'fabricpurchaseindent'||$this->router->fetch_method() == 'stationerypurchaseindent'||$this->router->fetch_method() == 'merchantpurchaseindentlist' || $this->router->fetch_method() == 'merchantpurchaseindentdetails')  echo "active";?>">
                        <?php if($userType != '2') { ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">P.I. List <span class="caret"></span></a>
                        <?php } else if($userType == '2') { ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">M.I. List <span class="caret"></span></a>
                        <?php } ?>
                        <ul class="dropdown-menu submenu-inner">
                            <li class="nav-item">
                                <a href="<?php echo base_url('merchant/allpurchaseindent'); ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>All Purchase Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>All M.I</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                    <?php if($userType != '2') { ?>
                                        <a href="<?php echo base_url('request/Fabricrequest/yarndeptpilist'); ?>" class="nav-link">
                                            <span class="nav-text"><span>Yarn Purchase Indent</span></span>
                                        </a>
                                    <?php } else if($userType == '2') { ?>
                                        <a href="#" class="nav-link">
                                            <span class="nav-text"><span>CAD M.I</span></span>
                                        </a>
                                    <?php } ?>
                            </li>
                            <li class="nav-item">
                                <?php if($userType == '7') { ?>
                                    <a href="<?php echo base_url('request/Bomrequest/purchaseindentlist'); ?>" class="nav-link"><span class="nav-text"><span>BOM (A1) Purchase Indent</span></span></a>
                                <?php } else if($userType == '3' || $userType == '15') { ?> <!-- added planning dept. condition|| $userType == '15' -->
                                    <a href="<?php echo base_url('request/Bomrequest/merchantpurchaseindentlist'); ?>" class="nav-link"><span class="nav-text"><span>BOM (A1) Purchase Indent</span></span></a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="#" class="nav-link"><span class="nav-text"><span>BOM (A1) M.I</span></span></a>
                                <?php } else { ?>
                                    <a href="#" class="nav-link"><span class="nav-text"><span>BOM (A1) Purchase Indent</span></span></a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <?php if($userType == '3' || $userType == '15') { ?> <!-- added planning dept. condition|| $userType == '15' -->
                                    <a href="<?php echo base_url('request/Bom2request/merchantpurchaseindentlist'); ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) Purchase Indent</span></span>
                                    </a>
                                <?php } else if($userType != '2') { ?>
                                    <span class="nav-text"><span>BOM (A2) Purchase Indent</span></span>
                                <?php } else if($userType == '2') { ?>
                                    <span class="nav-text"><span>BOM (A2) M.I</span></span>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('merchant/fabricpurchaseindent'); ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>Fabric Purchase Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>Fabric M.I</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('merchant/stationerypurchaseindent'); ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>Stationery Purchase Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>Stationery M.I</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                        </ul>
                        <b class="sub-arrow"></b>
                    </li>
                    <?php } ?>
                    <?php if($userType != '2') { ?>
                    <?php if(count($ArrRoleInfo)>0 && in_array('Stationery List',$ArrRoleInfo)) {?>
                        <li class="nav-item <?php if($this->router->fetch_method() == 'stationerylist') echo "active";?>">
                            <a href="<?php echo base_url('merchant/stationerylist'); ?>" class="nav-link">
                                <span class="nav-text fadeable"><span>Stationery List</span></span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if(count($ArrRoleInfo)>0 && in_array('Sample Received List',$ArrRoleInfo)) {?>
                        <li class="<?php if($this->router->fetch_method() == 'garmentreceivedlist' || $this->uri->segment(3) == 'garmentreceiveddetails') echo "active";?> nav-item">
                            <a href="<?php echo base_url('merchant/garmentreceivedlist') ?>" class="nav-link">
                                <span class="nav-text fadeable"><span>Sample Received List</span></span>
                            </a>
                        </li>
                    <?php } ?>    
                    <?php } else if($userType == '2') { ?>
					 <?php if (count($ArrRoleInfo)>0 && in_array('P.I. Approval List',$ArrRoleInfo)) {?>
                        <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'allmgmtpiapproval'||$this->router->fetch_method() == 'yarnmgmtpiapproval'||
                                $this->router->fetch_method() == 'managementpurchaseindentapproval'||$this->router->fetch_method() == 'managementpurchaseindentapprovaldetails'||
                                $this->router->fetch_method() == 'fabricmgmtpiapproval'||$this->router->fetch_method() == 'stationerypiapproval') echo "active";?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">P.I. Appl. List<span class="caret"></span></a>
                            <ul class="dropdown-menu submenu-inner">
                                <li class="nav-item">
                                    <a href="<?php echo base_url('management/allmgmtpiapproval'); ?>" class="nav-link">
                                        <span class="nav-text"><span>All P.I. Appl.</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('request/Fabricrequest/yarnmgmtpiapproval'); ?>" class="nav-link">
                                        <span class="nav-text"><span>Yarn P.I. Appl.</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('request/Bomrequest/managementpurchaseindentapproval'); ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) P.I. Appl.</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('request/Bom2request/managementpurchaseindentapproval'); ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) P.I. Appl.</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('management/fabricmgmtpiapproval'); ?>" class="nav-link">
                                        <span class="nav-text"><span>Fabric P.I. Appl.</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('management/stationerypiapproval'); ?>" class="nav-link">
                                        <span class="nav-text"><span>Stationery P.I. Appl.</span></span>
                                    </a>
                                </li>
                            </ul>
                            <b class="sub-arrow"></b>
                        </li> 
						<?php } ?>
						<?php if (count($ArrRoleInfo)>0 && in_array('P.I. List',$ArrRoleInfo)) {?>
                        <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'allmanagamentpurchaseindent'||$this->router->fetch_method() == 'yarnmanagamentpurchaseindent'||
                                $this->router->fetch_method() == 'managamentpurchaseindent'||$this->router->fetch_method() == 'managamentpurchaseindent'||
                                $this->router->fetch_method() == 'fabricmanagamentpurchaseindent'||$this->router->fetch_method() == 'stationerymanagamentpurchaseindent' ||$this->router->fetch_method() == 'managamentpurchaseindentdetails') echo "active";?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">P.I. List<span class="caret"></span></a>
                            <ul class="dropdown-menu submenu-inner">
                                <li class="nav-item">
                                    <a href="<?php echo base_url('management/allmanagamentpurchaseindent'); ?>" class="nav-link">
                                        <span class="nav-text"><span>All P.I. </span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('management/yarnmanagamentpurchaseindent'); ?>" class="nav-link">
                                        <span class="nav-text"><span>Yarn P.I. </span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('request/Bomrequest/managamentpurchaseindent'); ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) P.I. </span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('request/Bom2request/managamentpurchaseindent'); ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) P.I. </span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('management/fabricmanagamentpurchaseindent'); ?>" class="nav-link">
                                        <span class="nav-text"><span>Fabric P.I. </span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('management/stationerymanagamentpurchaseindent'); ?>" class="nav-link">
                                        <span class="nav-text"><span>Stationery P.I. </span></span>
                                    </a>
                                </li>
                            </ul>
                            <b class="sub-arrow"></b>
                        </li>
						<?php } ?>
						
                        <li class="<?php if($this->router->fetch_method() == 'billpaidlist'||$this->uri->segment(3)=="billpaiddetails") echo "active";?> nav-item">
                            <?php if($userType == '2' && (count($ArrRoleInfo)>0 && in_array('Bill Paid List',$ArrRoleInfo))) { ?>
                                <a href="<?= base_url('request/Bomrequest/billpaidlist') ?>" class="nav-link">
                                    <span class="nav-text fadeable"><span>Bill Paid List</span></span>
                                </a>
                            <?php } ?>         
                        </li>

                    <?php } ?>
                    <li class="nav-item">
                        <a href="#" class="">
                            &nbsp;
                        </a>
                    </li>
                </ul>
            <?php } ?>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>

<style>
    /*worst design*/
    .sidebar .submenu .nav-link {
        padding: 6px;
        font-size: 13px !important;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
    .sidebar .nav > .nav-item > .submenu > .submenu-inner {
        padding-top: 0.30rem;
        padding-bottom: 0.30rem;
    }

    .body-container > .navbar .navbar-intro {
        width: 16rem;
    }
    .navbar-intro {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        height: 100%;
    }
    @media (min-width: 1200px)
        .justify-content-xl-between {
            -ms-flex-pack: justify !important;
            justify-content: space-between !important;
        }
        *, *::before, *::after {
            box-sizing: border-box;
        }
        .navbar-inner {
            height: inherit;
            width: 100%;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-pack: justify;
            justify-content: space-between;
            background-color: inherit;
            visibility: visible;
        }
        .navbar {
            /*padding: 0;*/
            /*-ms-flex: 0 0 auto;*/
            /*flex: 0 0 auto;*/
            /*height: 4.5rem;*/
            /*height: var(--navbar-height);*/
            visibility: visible;
            /*z-index: 1022;*/
            min-height: 10px!important;
            margin-bottom: 0;
        }
        body {
            overflow-x: hidden;
            background-color: #e4e6e9;
            color: #022b61;
            font-size: 13px !important;
        }
        .form-control {
            color: #00050b;
            font-size: 13px;
        }
        select.form-control {
            padding-left: 0.55rem;
            padding-right: 0.75rem;
        }
        html, body {
            height: 100%;
            font-family: "Helvetica Neue",Helvetica,Arial;
        }
    .nav_items{
        font-size: 14px !important;
        font-family: "Helvetica Neue",Helvetica,Arial;
        font-weight: 400 !important;
        color: white !important;
    }
   
        .btn-royal-blue-submit{
            background-color: #008bfe;
            color: white;
            border-color: #008bfe;
            font-weight: 600 !important;
        }
        .btn-royal-blue-submit:hover{
            background-color: #055ee1;
            border-color: #055ee1;
            color: white;
        }
        .bgc-gray{
            background-color: #f7f7f7;
        }
        .btn-royal-blue {
            color: #022B61;
            background-color: #ebecec;
            border-color: #D0D1D1;
            transition:none !important;
        }
        .btn-royal-blue:hover {
            color: #fff;
            background-color: #011b3e;
            border-color: #00142f;
        }
        .btn-upload{
            color: white;
            font-weight: 600 !important;
            background-color: #ff9900;
            border-color: #ff9900;
        }

        .btn-upload:hover{
            color: white;
            background-color: #f79000;
            border-color: #f79000;
        }
       
.navbar-header {
    /*position: fixed;*/
    width: 100%;
    top: 58px;
    z-index: 99;
}
.navbar-default {
    background-color: #EBECEC;
    border-color: #EBECEC;
     /*padding: 5px 0px !important;*/
    
}
.navbar-nav {
    flex-direction:row!important;
}
 .collapse:not(.show) {
    display: block;
}

.navbar-default .navbar-nav > li > a {
    color: #022B61;
    font-size: 12px !important;
    padding: 15px 15px !important;
    text-decoration:none!important;
}
    .dropdown-menu > li > a:hover {
        background-color: #d5d5d5;
        text-decoration:none!important;
    }
    .container-fluid {
        background-color: #EBECEC;
    }

    .gray_header{
        background-color: #D0D1D1;
        color: #022B61;
    }
    .navbar{
        min-height: 10px!important;
        margin-bottom: 0;
         height: 58px !important;
    }
    .menu-nav > li > a {
        line-height: 5px !important;
    }
    .navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav > .active > a:hover {
        color: #011f49;
        background-color: #d5d5d5;
    }

    .bgn-white{
        background-color: white;
    }
    .dropdown-menu > li > a {
    color: #022B61;
    background-color: #EBECEC;
    border: 1px solid #fff;
    font-size: 12px !important;
      padding: 6px 5px!important;
        border-radius: 1px;
        display:block;
        
        
}
.dropdown-submenu {
  position: relative;
}

.dropdown-submenu>.dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -6px;
  margin-left: -1px;
  -webkit-border-radius: 0 6px 6px 6px;
  -moz-border-radius: 0 6px 6px;
  border-radius: 0 6px 6px 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
  display: block;
}

.carets{
  display: block;
  content: " ";
  float: right;
  width: 0;
  height: 0;
  border-color: transparent;
  border-style: solid;
  border-width: 4px 0 4px 4px;
  border-left-color: #022B61;
  margin-top: 5px;
  margin-right: -3px;
}

.dropdown-submenu:hover>a:after {
  border-left-color: #fff;
}
.active > a >.carets{
    border-left-color: #fff!important;
}
 .active > a:hover >.carets{
    border-left-color: #fff!important;
}
.dropdown-submenu.pull-left {
  float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
  left: -100%;
  margin-left: 10px;
  -webkit-border-radius: 6px 0 6px 6px;
  -moz-border-radius: 6px 0 6px 6px;
  border-radius: 6px 0 6px 6px;
}
.caret{
  display: inline-block;
    width: 0;
    height: 0;
    margin-left: 2px;
    vertical-align: middle;
    border-top: 4px dashed;
    border-top: 4px solid \9;
    border-right: 4px solid transparent;
    border-left: 4px solid transparent
}
</style>
