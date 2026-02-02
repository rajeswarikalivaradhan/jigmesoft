<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap-datepicker.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/select2.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/uploadfile-order.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/wip.css" />

<!-- *********************** JEXCEL CSS LOADS HERE ************************-->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jexcelNew/jspreadsheet.css" />
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jexcelNew/jsuites.css" />

<!-- *********************** JEXCEL SCRIPTS LOADS HERE ************************-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ajax.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/vue.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jexcelNew/jexcel.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jexcelNew/jsuites.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jexcel/numeral.min.js"></script>

<!-- *************** CUSTOM STYLE HERE ********* -->
<style>
table.table.tbl-procs-border {
    margin-bottom: 0;
}
</style>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>

        <div class="content px-4">

            <!-- *********************** ORDER PROCESSING START HERE ************************-->

            <?php require('order_processing.php'); ?>

            <!-- *********************** ORDER PROCESSING ENDS HERE ************************-->

            <!-- *********************** WIP DETAILS START HERE ************************-->
            <div class="card-header pb-3 bgc-white border-0 " style="">
                <div class="card-title f-20">
                    <b style="font-size: 20px !important; padding-left: 5px; margin-left: 3px;color: #333">ORDER PROCESSING</b>
                </div>
            </div>
            <div class="col-12 pb-3 px-0">
                <div class="col-12 px-0" style="border-top: 1px solid #022b61;"></div>
            </div>
            <ul class="nav nav-pills main-head pt-2 px-3">
                <li class="active upper-case"><a data-toggle="tab" href="#fabric">Fabric</a></li>
                <li class="upper-case"><a data-toggle="tab" href="#yarn">Yarn</a></li>
                <li class="upper-case"><a data-toggle="tab" href="#knitting">Knitting</a></li>
                <li class="upper-case"><a data-toggle="tab" href="#dyeing">Dyeing</a></li>
                <li class="upper-case"><a data-toggle="tab" href="#compacting">Compacting</a></li>
                <li class="upper-case"><a data-toggle="tab" href="#lab">Lab</a></li>
                <div class="pull-right">
                <a  id="backbtns" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
                </div>
            </ul>

            <div class="tab-content mt-1">
                <div id="fabric" class="tab-pane fade in active">
                    <?php require('fabric_details.php'); ?>
                </div>
                <div id="yarn" class="tab-pane fade">
                    <?php require('yarn.php'); ?>
                </div>
                <div id="knitting" class="tab-pane fade">
                    <?php require('knitting.php'); ?>
                </div>
                <div id="dyeing" class="tab-pane fade">
                    <?php require('dyeing.php'); ?>
                </div>
                <div id="compacting" class="tab-pane fade">
                    <?php require('compacting.php'); ?>
                </div>
                <div id="lab" class="tab-pane fade">
                    <?php require('lab.php'); ?>
                </div>
            </div>


            <!-- *********************** WIP DETAILS ENDS HERE ************************-->


        </div>

        <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
        <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
    <script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
    <?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/wipfabricdetails.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
    <script>
        var enquiry_id = <?php echo $VarEnqId; ?>;
        // *************** fabric tab panel previous and next trigger *************** 
        // $('.fabric.btnNext').click(function () {
        //     $('.nav-tabs.fabric > .d-none.active').next('li').find('a').trigger('click');
        // });

        // $('.fabric.btnPrevious').click(function () {
        //     $('.nav-tabs.fabric > .d-none.active').prev('li').find('a').trigger('click');
        // });

        $('#backbtns').click(function () {
        let $active = $('.nav-pills > li.active');
        let $prev = $active.prev('li');
        
        if ($prev.length) {
            $prev.find('a').trigger('click');
        }
    });

        $('.fabric.btnNext').click(function () {
            $('.nav-tabs.fabric > .active').next('li').find('a').trigger('click');
        });

        $('.fabric.btnPrevious').click(function () {
            $('.nav-tabs.fabric > .active').prev('li').find('a').trigger('click');
        });
        // *************** yarn tab panel previous and next trigger *************** 
        $('.yarn.btnNext').click(function () {
            $('.nav-tabs.yarn > .active').next('li').find('a').trigger('click');
        });

        $('.yarn.btnPrevious').click(function () {
            $('.nav-tabs.yarn > .active').prev('li').find('a').trigger('click');
        });
        
        // *************** knitting tab panel previous and next trigger *************** 
        $('.knitting.btnNext').click(function () {
            $('.nav-tabs.knitting > .active').next('li').find('a').trigger('click');
        });

        $('.knitting.btnPrevious').click(function () {
            $('.nav-tabs.knitting > .active').prev('li').find('a').trigger('click');
        });
        
        // *************** dyeing tab panel previous and next trigger *************** 
        $('.dyeing.btnNext').click(function () {
            $('.nav-tabs.dyeing > .active').next('li').find('a').trigger('click');
        });

        $('.dyeing.btnPrevious').click(function () {
            $('.nav-tabs.dyeing > .active').prev('li').find('a').trigger('click');
        });
        
        // *************** compacting tab panel previous and next trigger *************** 
        $('.compacting.btnNext').click(function () {
            $('.nav-tabs.compacting > .active').next('li').find('a').trigger('click');
        });

        $('.compacting.btnPrevious').click(function () {
            $('.nav-tabs.compacting > .active').prev('li').find('a').trigger('click');
        });

        // *************** lab tab panel previous and next trigger *************** 
        $('.lab.btnNext').click(function () {
            $('.nav-tabs.lab > .active').next('li').find('a').trigger('click');
        });

        $('.lab.btnPrevious').click(function () {
            $('.nav-tabs.lab > .active').prev('li').find('a').trigger('click');
        });
        
    </script>    