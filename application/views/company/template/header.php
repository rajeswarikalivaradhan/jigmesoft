<head lang="en" style="font-size: 0.9em">
<?php $ArrProfileInfo = fnGetUserLoggedInfo(1);
$ArrCompanyRes = $this->companymodel->fnGetCompanyInfo($ArrProfileInfo['companyid']);
$VarDesignation = '';
// if(!empty($ArrProfileInfo['desgnid'])) {
//     $ObjDesignation = $this->companymodel->headerUserDesignation($ArrProfileInfo['desgnid']);
//     $VarDesignation = @$ObjDesignation->desgn;
// }
if(!empty($ArrProfileInfo['id'])) {
    $ObjDesignation = $this->companymodel->headerUserDesignationNew($ArrProfileInfo['id']);
    $VarDesignation = @$ObjDesignation->designation;
}
?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
    <title><?php echo COMPANYNAME ?></title>
    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/node_modules/bootstrap/dist/css/bootstrap.css">

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600&display=swap">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/node_modules/@fortawesome/fontawesome-free/css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/node_modules/@fortawesome/fontawesome-free/css/regular.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/node_modules/@fortawesome/fontawesome-free/css/brands.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/node_modules/@fortawesome/fontawesome-free/css/solid.css">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/dist/css/ace-font.css">
    <!-- ace.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/dist/css/ace.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/dist/css/ace-themes.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/uploadfile-order.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <style>
        .f-16{
            font-size: 16px;
        }
        .f-14{
            font-size: 14px;
        }
        .f-13{
            font-size: 13px;
        }
        .f-12{
            font-size: 12px;
        }
        .text-royal-blue {
            font-weight: 600;
             /*color: #022B61 !important; */
             color: #0036ae !important; 
            /*color: #000000 !important;*/
        }
        .swal2-title {
            font-size: 20px !important;
        }
        .nav-tabs .btn.active {
            font-weight: 400;
        }
        /*@media (min-width: 1200px) {
            .navbar-white .navbar-inner {
                border-bottom-color: #e6eaed !important;
            }
        }*/
        @media (max-width: 999px) {
            .d-xl-none_custom {
                display: block !important;
            }
        }
        @media (min-width: 1000px) {
            .d-xl-none_custom {
                display: none !important;
            }
        }

        #infobox-row {
            box-shadow: 0 0 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.25rem;
            overflow: hidden;
        }

        @media (max-width: 991.98px) {
            #infobox-row {
                box-shadow: none;
                border-radius: 0;
            }

            #infobox-row div[role=button] {
                border-width: 0 !important;
                border-radius: 0.25rem;
                box-shadow: 0 0 0.25rem rgba(0, 0, 0, 0.075);
            }
        }
        .user-image {
    width: 35px;
    margin-top: -7px;
    margin-left: 10px;
    height: 35px;
    border-radius:50%;
        }
    </style>
    <!-- overlayScrollbars -->
    <script src="<?php echo base_url(); ?>assets/js/new/jquery.min.js"></script>
    <script>
        var base_path     = '<?php echo base_url();?>';
        var GlbCAdminFdr  = '<?php echo CNFCADMIN;?>';
        var GlbCompanyFdr = '<?php echo CNFCOMPANY;?>';
    </script>
<style>
    .bg-lg{background-color: #b0efb0 !important; }
    .bg-red{background-color: #ef5454 !important; }
    .hide{display:none !important}
    .no-border{border:0 !important}
    .no-padding{padding:0 !important}
    .no-margin{margin:0 !important}
    .no-shadow{box-shadow:none!important}
    .nav{
        margin-left:auto !important;
        font-size: 14px !important;
        font-family: "Helvetica Neue",Helvetica,Arial;
    }
    .navbar-blue-royal {
        background-color: #022B61;
    }
    .sidebar-white .nav > .nav-item > .nav-link:hover > .nav-icon {
        color: #498ac4;
    }
    .sidebar.sidebar-h .nav-fill .nav-item {
        font-size: small;
    }
    .sidebar .submenu .nav-link {
        min-height: 1.6rem !important;
        background-color: #ebecec;
        color: #022B61;
    }
    .sidebar .submenu .nav-link:hover {
        background-color: #c2c0c0;
        color: #011532;
        list-style-type: none;
        list-style: unset !important;
        list-style-position: outside;
    }
    .sidebar.sidebar-h {
        height: 33% !important;
    }
    .sidebar .nav > .nav-item > .nav-link {
        color: #022B61 !important;
        min-height: 35px;
        background-color: #ebecec;
        font-family: "Helvetica Neue",Helvetica,Arial;
        font-size: 13px !important;
        font-weight: 400 !important;
    }
    .sidebar.sidebar-h .nav > .nav-item > .submenu {
        min-width: 15em !important;
    }
</style>

</head>
<body>
<div class="body-container">
<nav class="navbar navbar-sm navbar-fixed-xl navbar-expand-lg navbar-blue-royal">

    <div class="navbar-inner">
        <!--<div class="container container-plus">-->
        <div class="navbar-intro justify-content-xl-between">
            <button type="button" class="btn btn-burger burger-arrowed static collapsed ml-2 d-flex d-xl-none_custom" data-toggle-mobile="sidebar" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle sidebar">
                <span class="bars"></span>
            </button><!-- mobile sidebar toggler button -->
            <div class="navbar-content flex-grow-0 ml-4 text-white nav_items">
                 |<span class="px-2 text-center">AZIBO INFOTECH PRIVATE LIMITED</span>|
            </div>
            <!--<a class="navbar-brand text-white" href="#">-->
            <!--    <img src="<?php echo base_url(); ?>assets/users/img/logo.png" alt="AdminLTE Logo" class="hide brand-image img-circle elevation-3" style="width: 25%;">-->
            <!--    <span style="position: relative;border: 2px solid white;border-radius: 34px;padding: 1px 21px;background: #022B61;top: 30px;">JIGME</span>-->
            <!--</a><!-- /.navbar-brand -->-->

            <button type="button" class="btn btn-burger mr-2 d-none d-xl-flex hide" data-toggle="sidebar" data-target="#sidebar" aria-controls="sidebar" aria-expanded="true" aria-label="Toggle sidebar">
                <span class="bars"></span>
            </button>
        </div><!-- /.navbar-intro -->
        <!--<div class="navbar-content text-whitev nav_items">-->
        <!--    |<span class="px-2 text-center"><?= @$ArrCompanyRes[0]['companyname'] ?></span>|-->
        <!--</div>-->
        <!-- .navbar-content -->
        <!-- mobile #navbarMenu toggler button -->
        <button class="navbar-toggler ml-1 mr-2 px-1" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navbar menu">
            <span class="pos-rel">
                <img class="border-2 brc-white-tp1 radius-round" width="36"
                     src="<?php echo base_url()?>assets/img/avatar6.jpg" alt="Jason's Photo">
                  <span class="bgc-warning radius-round border-2 brc-white p-1 position-tr mr-n1px mt-n1px"></span>
            </span>
        </button>
        <div class="navbar-content flex-grow-0 ml-auto text-white nav_items">
            <div class=" mx-lg-2">
                |<span class="px-2">
                        <?php
                        if (isset($ArrProfileInfo['usertype'])) {
                            $ArrUt = unserialize(ARRUSERTYPE);
                            echo $ArrUt[$ArrProfileInfo['usertype']].(!empty($ArrProfileInfo['dept_usercount'])?' - '.$ArrProfileInfo['dept_usercount']:'');
                        }
                        ?>
                  </span>|<span class="px-2"><?= @$ArrProfileInfo['name']; ?></span>|
                  
            </div>
        </div>
        <div class="navbar-menu collapse navbar-collapse navbar-backdrop pl-0" id="navbarMenu">

            <div class="navbar-nav pl-0 pr-5">
                <ul class="nav pl-0">
                    <li class="nav-item dropdown dropdown-mega   flex-grow-0 ml-auto pl-0 text-white">
                        <a class="pl-0 nav-link nav_items dropdown-toggle text-white" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-list-alt d-lg-none"></i>
                            <span class="pl-0 pr-2 "><?= $VarDesignation?></span>|
                            <i class="caret fa fa-angle-down d-none d-lg-block"></i>
                            <i class="caret fa fa-angle-left d-block d-lg-none"></i>
                        </a>
                        <div class="dropdown-menu p-0 dropdown-animated bgc-secondary-l4 brc-primary-m3 border-t-0 border-b-2 ace-scrollbar">
                            <div class="d-flex flex-column">

                                <div class="row mx-0">

                                    <div class="col-lg-4 col-12 p-2 p-lg-3 p-xl-4 d-flex flex-column align-items-center">
                                        <div class="w-100 mb-3">
                                            <h5 class="col-lg-9 mx-auto text-dark-m2 px-0">
                                                <i class="fa fa-clipboard-check mr-1 text-purple-m1"></i>
                                                Current Tasks
                                            </h5>
                                        </div>

                                        <div class="col-lg-9 list-group px-0 border-1 brc-default-l2 radius-1 shadow-md">
                                            <a href="#" class="border-0 bgc-h-primary-l4 list-group-item list-group-item-action">
                                                <i class="fab fa-facebook text-blue-m1 text-110 mr-2"></i>
                                                Cras justo odio
                                            </a>

                                            <a href="#" class="border-0 list-group-item list-group-item-action disabled">Vestibulum at eros</a>
                                        </div>
                                    </div><!-- .col:mega tasks -->



                                    <div class="bgc-white col-lg-4 col-12 p-4">
                                        <h5 class="text-dark-m2">
                                            <i class="fas fa-bullhorn mr-1 text-primary-m1"></i>
                                            Notifications
                                        </h5>

                                        <div class="mt-3">
                                            <div class="media mt-2 px-3 pt-1 border-l-2 brc-purple-m2">
                                                <div class="bgc-purple radius-1 mr-3 p-3">
                                                    <i class="fa fa-user text-white text-150"></i>
                                                </div>
                                                <div class="media-body pb-0 mb-0 text-90 text-grey-m1">
                                                    <div class="text-grey-d2 font-bolder">@username1</div>
                                                    Donec id elit non mi porta gravida at eget metus. Fusce dapibus...
                                                </div>
                                            </div>

                                            <hr />

                                            <div class="media mt-2 px-3 pt-1 border-l-2 brc-warning-m2">
                                                <div class="bgc-warning radius-1 mr-3 p-3">
                                                    <i class="fa fa-user text-white text-150"></i>
                                                </div>
                                                <div class="media-body pb-0 mb-0 text-90 text-grey-m1">
                                                    <div class="text-grey-d2 font-bolder">@username2</div>
                                                    Fusce dapibus, tellus ac cursus commodo, tortor mauris...
                                                </div>
                                            </div>

                                            <hr />

                                        </div>

                                    </div><!-- .col:mega notifications -->


                                    <div class="col-lg-4 col-12 p-4 dropdown-clickable">
                                        <h5 class="text-dark-m2">
                                            <i class="fa fa-envelope mr-1 text-green-m1"></i>
                                            Contact Us
                                        </h5>

                                        <form class="my-3">
                                            <div class="form-group mb-2">
                                                <input placeholder="Name" type="text" class="form-control border-l-2" />
                                            </div>

                                            <div class="form-group mb-2">
                                                <input placeholder="Email" type="text" class="form-control border-l-2" />
                                            </div>

                                            <div class="form-group mb-4">
                                                <textarea class="form-control brc-primary-m2 border-l-2 text-grey-d1" rows="3" placeholder="Your message..."></textarea>
                                            </div>

                                            <div class="text-center">
                                                <button type="reset" class="btn px-3 btn-secondary btn-bold tex1t-110">
                                                    Reset
                                                </button>

                                                <button data-dismiss="dropdown" type="button" class="btn btn-outline-primary btn-bgc-white px-3 btn-bold btn-text-slide-x" style="width: 8rem;">
                                                    Submit<i class="btn-text-2  move-right fa fa-arrow-right text-120 align-text-bottom ml-1"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div><!-- .col:mega contact -->

                                </div><!-- .row: mega -->



                                <!-- Big Action Buttons -->
                                <div class="order-first order-lg-last ">
                                    <hr class="d-none d-lg-block brc-default-l1 my-0" /><!-- border above buttons in desktop mode -->

                                    <div class="row mx-0 bgc-primary-l4">
                                        <div class="col-lg-8 offset-lg-2 d-flex justify-content-center py-4 d-flex">

                                            <button class="mx-2px btn btn-sm btn-app btn-outline-warning btn-h-outline-warning btn-a-outline-warning radius-1 border-2">
                                                <i class="fa fa-cog text-190 d-block mb-2 h-4"></i>
                                                <span class="text-muted">Settings</span>
                                            </button>

                                            <button class="mx-2px btn btn-sm btn-app btn-outline-info btn-h-outline-info radius-1 border-2">
                                                <i class="fa fa-edit text-190 d-block mb-2 h-4"></i>
                                                Edit
                                                <span class="position-tr text-danger-m2 text-130 mr-1">*</span>
                                            </button>

                                            <button class="mx-2px btn btn-sm btn-app btn-dark radius-1">
                                                <i class="fa fa-lock text-150 d-block mb-2 h-4"></i>
                                                Lock
                                            </button>

                                        </div>
                                    </div><!-- .row:megamenu big buttons -->

                                    <hr class="d-lg-none brc-default-l1 mt-0" /><!-- border below buttons in mobile mode -->
                                </div>


                            </div>
                        </div>

                    </li>

                    <li class="pl-0 nav-item dropdown order-first order-lg-last">
                        <a class="pl-0 nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <img id="id-navbar-user-image" class="d-none d-lg-inline-block radius-round border-0 pl-0  mr-2 w-5" src="<?= base_url(); ?>assets/img/avatar5.png" alt="Jason's Photo">

                            <i style="font-size: 13px !important;" class="text-white caret fa fa-angle-down d-none d-xl-block"></i>
                            <i class="text-white small caret fa fa-angle-left d-block d-lg-none"></i>
                        </a>

                        <div class="dropdown-menu dropdown-caret dropdown-menu-right dropdown-animated brc-primary-m3 py-1">
                            <div class="d-none d-lg-block d-xl-none">
                                <div class="dropdown-header">Welcome, Jason</div>
                                <div class="dropdown-divider"></div>
                            </div>

                            <div class="dropdown-clickable px-3 py-25 bgc-h-secondary-l3 border-b-1 brc-secondary-l2">
                                <!-- online/offline toggle -->
                                <div class="d-flex justify-content-center align-items-center tex1t-600">
                                    <label for="id-user-online" class="text-grey-d1 pt-2 px-2">offline</label>
                                    <input type="checkbox" class="ace-switch ace-switch-sm text-grey-l1 brc-green-d1" id="id-user-online" />
                                    <label for="id-user-online" class="text-green-d1 text-600 pt-2 px-2">online</label>
                                </div>
                            </div>

                            <a class="mt-1 dropdown-item btn btn-outline-grey bgc-h-primary-l3 btn-h-light-primary btn-a-light-primary" href="<?php echo base_url('profile') ?>">
                                <i class="fa fa-user text-primary-m1 text-105 mr-1"></i>
                                Profile
                            </a>

                            <a class="dropdown-item btn btn-outline-grey bgc-h-success-l3 btn-h-light-success btn-a-light-success" href="#" data-toggle="modal" data-target="#id-ace-settings-modal">
                                <i class="fa fa-cog text-success-m1 text-105 mr-1"></i>
                                Settings
                            </a>

                            <div class="dropdown-divider brc-primary-l2"></div>

                            <a class="dropdown-item btn btn-outline-grey bgc-h-secondary-l3 btn-h-light-secondary btn-a-light-secondary" href="<?php echo base_url() ?>login/signout/">
                                <i class="fa fa-power-off text-warning-d1 text-105 mr-1"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                    <!-- /.nav-item:last -->

                </ul><!-- /.navbar-nav menu -->
            </div><!-- /.navbar-nav -->

        </div><!-- /#navbarMenu -->
        <!--</div>-->
    </div><!-- /.navbar-inner -->

</nav>
    <?php $this->load->view(CNFCOMPANY . 'template/left_menu'); ?>
<div class="main-container bgc-white">
    <div role="main" class="main-content">
        <div class="page-content p-1">


