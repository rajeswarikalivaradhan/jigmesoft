<?php $ArrProfileInfo = fnGetUserLoggedInfo(1);
$ArrCompanyRes = $this->companymodel->fnGetCompanyInfo($ArrProfileInfo['companyid']);
$VarDesignation = '-';
// if(!empty($ArrProfileInfo['desgnid'])) {
//     $ObjDesignation = $this->companymodel->headerUserDesignation($ArrProfileInfo['desgnid']);
//     $VarDesignation = @$ObjDesignation->desgn;
// }
if(!empty($ArrProfileInfo['id'])) {
    $ObjDesignation = $this->companymodel->headerUserDesignationNew($ArrProfileInfo['id']);
    $VarDesignation = @$ObjDesignation->designation;
}
?>

<div id="sidebar" class="sidebar sidebar-fixed sidebar-hover sidebar-h sidebar-white sidebar-top" data-swipe="true" data-backdrop="true" data-dismiss="true">
    <div class="sidebar-inner border-r-0 border-b-1 brc-secondary-l2 shadow-md" style="background-color: #ebecec">
        <div class="container container-plus px-0 float-right">
            <div class="flex-grow-1 d-xl-flex  fadeable-left ace-scroll" data-ace-scroll="{}">
                <ul class="nav nav-spaced text-center nav-active-sm has-active-border active-on-top pr-4">
                <li class="nav-item-caption">
                    <span class="fadeable pl-3">
                        <?php
                        if (isset($ArrProfileInfo['usertype'])) {
                            $ArrUt = unserialize(ARRUSERTYPE);
                            echo $ArrUt[$ArrProfileInfo['usertype']].(!empty($ArrProfileInfo['dept_usercount'])?' - '.$ArrProfileInfo['dept_usercount']:'');
                        }
                        ?>
                    </span>
                    <span class="fadeinable mt-n2 text-125">&hellip;</span>
                </li>
                <li class="nav-item"> </li>
                <li class="nav-item"> </li>
                <li class="nav-item"> </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('merchant/orderEnquiryList') ?>" class="nav-link">
                        <span class="nav-text"><span>Enquiry List</span></span>
                    </a>
                </li>
                    <li class="nav-item">

                        <a href="#" class="nav-link dropdown-toggle collapsed">
                            <span class="nav-text"><span>WIP List</span></span>
                            <b class="caret fa fa-angle-left rt-n90"></b>
                        </a>

                        <div class="hideable submenu collapse">
                            <ul class="submenu-inner">
                                <li class="nav-item">
                                    <a href="<?= base_url('merchant/manageWip') ?>" class="nav-link">
                                        <span class="nav-text"><span>All WIP List</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>IOR List</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>ISR List</span></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <b class="sub-arrow"></b>
                    </li>
                <li class="nav-item">
                    <a href="#" class="nav-link dropdown-toggle collapsed">
                        <span class="nav-text"><span>Request Sent List</span>
                    </span>
                        <b class="caret fa fa-angle-left rt-n90"></b>
                    </a>
                    <div class="hideable submenu collapse">
                        <ul class="submenu-inner">
                            <li class="nav-item">
                                <a href="<?= base_url('merchant/manageallrequest') ?>" class="nav-link">
                                    <span class="nav-text"><span>All Request List</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>CAD Request</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>Sample Request</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>BOM (A1) Request</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>BOM (A2) Request</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>Fabric Request</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>Production Request</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>Vessel Booking Request</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>Stationery Request</span></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <b class="sub-arrow"></b>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link dropdown-toggle collapsed">
                        <span class="nav-text"><span>Queue List</span></span>
                        <b class="caret fa fa-angle-left rt-n90"></b>
                    </a>
                    <div class="hideable submenu collapse">
                        <ul class="submenu-inner">
                            <li class="nav-item">
                                <a href="<?= base_url('merchant/queueList') ?>" class="nav-link">
                                    <span class="nav-text"><span>All Queue List</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text">
                                        <span>CAD Queue</span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>Sample Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>BOM Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>Fabric Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>Production Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>Vessel Booking Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>Stationery Queue</span></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <b class="sub-arrow"></b>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link dropdown-toggle collapsed">
                        <span class="nav-text"><span>Material Indent List</span></span>
                        <b class="caret fa fa-angle-left rt-n90"></b>
                    </a>
                    <div class="hideable submenu collapse">
                        <ul class="submenu-inner">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>All Material Indent</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url(CNFCOMPANY.'merchant/cadindentlist') ?>" class="nav-link">
                                    <span class="nav-text"><span>CAD Material Indent</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url(CNFCOMPANY.'mcaduser/bomindentlist') ?>" class="nav-link">
                                    <span class="nav-text"><span>BOM (A1) Material Indent</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url(CNFCOMPANY.'mcaduser/bomindentlist') ?>" class="nav-link">
                                    <span class="nav-text"><span>BOM (A2) Material Indent</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url(CNFCOMPANY.'merchant/indentlist') ?>" class="nav-link">
                                    <span class="nav-text"><span>Fabric Material Indent</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <span class="nav-text"><span>Stationery Material Indent</span></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <b class="sub-arrow"></b>
                </li>
                <li class="nav-item">
                        <a href="#" class="nav-link dropdown-toggle collapsed">
                            <span class="nav-text fadeable"><span>Purchase Indent List</span></span>
                            <b class="caret fa fa-angle-left rt-n90"></b>
                        </a>
                        <div class="hideable submenu collapse">
                            <ul class="submenu-inner">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>All Purchase Indent</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>Yarn Purchase Indent</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) Purchase Indent</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) Purchase Indent</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>Fabric Purchase Indent</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>Stationery Purchase Indent</span></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <b class="sub-arrow"></b>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="nav-text fadeable"><span>Stationary List</span></span>
                        </a>
                    </li>
            </ul>
        </div>
        <!-- /.sidebar scroll -->
        <div class="sidebar-section hide">
            <div class="sidebar-section-item fadeable-bottom">
                <div class="fadeinable">
                    <!-- shows this when collapsed -->
                    <div class="pos-rel">
                        <img alt="Alexa's Photo" src="assets/image/avatar/avatar3.jpg" width="42"
                             class="px-1px radius-round mx-2 border-2 brc-default-m2"/>
                        <span class="bgc-success radius-round border-2 brc-white p-1 position-tr mr-1 mt-2px"></span>
                    </div>
                </div>
                <div class="fadeable hideable w-100 bg-transparent shadow-none border-0">
                    <!-- shows this when full-width -->
                    <div id="sidebar-footer-bg"
                         class="d-flex align-items-center bgc-white shadow-sm mx-2 mt-2px py-2 radius-t-1 border-x-1 border-t-2 brc-primary-m3">
                        <div class="d-flex mr-auto py-1">
                            <div class="pos-rel">
                                <img alt="Alexa's Photo" src="<?php echo base_url(); ?>assets/users/img/logo.png" width="42"
                                     class="px-1px radius-round mx-2 border-2 brc-default-m2"/>
                                <span class="bgc-success radius-round border-2 brc-white p-1 position-tr mr-1 mt-2px"></span>
                            </div>
                            <div>
                                <span class="text-blue-d1 font-bolder">Alexa</span>
                                <div class="text-80 text-grey">
                                    Admin
                                </div>
                            </div>
                        </div>
                        <a href="#"
                           class="d-style btn btn-outline-primary btn-h-light-primary btn-a-light-primary border-0 p-2 mr-2px ml-4"
                           title="Settings" data-toggle="modal" data-target="#id-ace-settings-modal">
                            <i class="fa fa-cog text-150 text-blue-d2 f-n-hover"></i>
                        </a>
                        <a href="#"
                           class="d-style btn btn-outline-orange btn-h-light-orange btn-a-light-orange border-0 p-2 mr-1"
                           title="Logout">
                            <i class="fa fa-sign-out-alt text-150 text-orange-d2 f-n-hover"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
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
            padding: 0;
            -ms-flex: 0 0 auto;
            flex: 0 0 auto;
            height: 4.5rem;
            height: var(--navbar-height);
            visibility: hidden;
            z-index: 1022;
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
    .navbar{
        height: 58px !important;
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
</style>
