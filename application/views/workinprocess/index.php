<script src="<?= base_url() ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/interactjs/dist/interact.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>
<?php
/**
 * @var int $VarEnqId
 * @var array $components
 */
// commented by me $this->load->view(CNFCOMPANY . 'template/header');
$this->load->view(CNFCOMPANY . 'customheader'); 
?>
<div class="content px-3" >
    <?php $requestFor = isset($ArrEnquiryInfo[0]->reqforisrior) ? $ArrEnquiryInfo[0]->reqforisrior : ''; ?>
    <?php $this->load->view('workinprocess/wip_precosting',array('components' => $components, 'VarEnqId' => $VarEnqId , 'requestFor' => $requestFor, 'accessPermission' => true));?>
    <div class="col-12 col-sm-12 p-sm-0 border-0">
        <?php
        $this->load->view('workinprocess/wip_enquiry_details', array(
            'ArrEnquiryType' => $ArrEnquiryType, 'ArrCountries'   => $ArrCountries,
            'ArrModeType'    => $ArrModeType, 'ArrCurrency'    => $ArrCurrency,
            'ArrBrand'       => $ArrBrand, 'ArrBuyer'       => $ArrBuyer, 'VarEnqId'       => $VarEnqId,
            'ArrEnquiryInfo' => $ArrEnquiryInfo, 'ArrOrderStatus' => $ArrOrderStatus, 'UserType'       => $UserType
        ));
        ?>
    </div>

</div>
<style>
    .form-control {
        /*height: 38px !important;*/
        border: 0.001em solid #cccaca;
    }


    .jexcel tbody tr:nth-child(even) {
        background-color: #EEE9F1 !important;
    }
    .jexcel_overflow {
        width: 100% !important;
    }
    .jexcel{
        width: 100% !important;
        white-space: inherit !important;
    }
        .jdropdown-focus {
    position: inherit !important;
    }
    .jdropdown-default .jdropdown-item{
    padding: 2px !important
    }
    col:first-child {
        width: 3% !important;
    }
    .jexcel > thead > tr > td {
        padding: 1px !important;
        font-size: 12px;
        height: 50px;
        vertical-align: top;
        padding-top: 10px!important;
    }
    .jexcel > tbody > tr > td.jexcel_dropdown {
        background-repeat: no-repeat;
        background-position: top 50% right -1px;
        background-image: url("data:image/svg+xml,%0A%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='24' viewBox='0 0 10 20'%3E%3Cpath fill='none' d='M0 0h24v24H0V0z'/%3E%3Cpath d='M7 10l5 5 5-5H7z' fill='gray'/%3E%3C/svg%3E");
        text-overflow: ellipsis;
        overflow-x: hidden;
    }

    .jexcel > tbody > tr > td {
        height: 37px;
        color: #333 !important;
        font-size: 12px;
    }
    .jexcel > tfoot > tr > td {
        height: 37px;
        color: black !important;
        font-size: 12px;
    }
    .jexcel > col:first-child {
        width: 3% !important;
    }
    .jexcel_content {
        padding-right: 0 !important;
    }
    .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
        color: #FFFFFF;
        background-color: #022b61;
    }
    .jexcel tbody tr:nth-child(2n) {
        background-color: #FFFFFF !important;
    }

    .bgc-tab-gray:hover {
        background-color: #dcdcdc !important;
    }

    .jexcel {
        border-right: 1px solid #f7f7f7 !important;
        border-bottom: 1px solid #f7f7f7 !important;
    }

    .jexcel > thead > tr > td {
        border: 0.01em solid #f7f7f7 !important;
    }
    .jexcel > tbody > tr > td {
        border: 0.01em solid #f7f7f7 !important;
    }
    .jexcel > tfoot > tr > td {
        border: 0.01em solid #f7f7f7 !important;
    }
    .nav-item-r-border{
        border-right: 3px solid #ffffff;
    }

    /*.btn-light-lightgrey {
        color: #022b61;
        background-color: #ebecec;
    }*/
    .btn-light-lightgrey {
        color: #011837;
        background-color: #ebecec;
    }

    .btn-h-light-purple[class*="btn-light-"]:hover {
        color: #022b61;
        background-color: #dcdcdc;
        border-color: #afa8d5;
    }

    .btn-a-purple:not(:disabled):not(.disabled):active, .btn-a-purple:not(:disabled):not(.disabled).active, .show > .btn.btn-a-purple.dropdown-toggle {
        color: #fff;
        background-color: #022b61 !important;
        border-color: #695ea7;
    }

    .brc-royal-blue{
        border-color: #022b61;
    }
    /*.btn-light-lightgrey {
        background-color: #022b61;
    }*/
</style>
<?php $this->load->view(CNFCOMPANY . 'template/footer'); ?>

