<?php 
    $requestData = $requestData[0];
?>

<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap-datepicker.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/select2.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/uploadfile-order.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"  />
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
.table-responsive.cus-ovrflw {
    overflow-x: auto;
}
.custom-close {
color: #fff !important;     /* solid white */
  font-weight: 900;           /* bold/thick */
  font-size: 20px;
  opacity: 1 !important;   
}

.close {
  float: right !important;     /* white color */
  line-height: 1;           /* makes it thicker/bolder */
  font-size: 20px;   
        /* increase size if needed */
   
}
.btn.disabled {
  pointer-events: none;  /* Disables clicking */
  opacity: 0.6;          /* Make it look disabled */
}

</style>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
        <div class="content px-4">
            <!-- *********************** ORDER PROCESSING START HERE ************************-->
            <?php $this->load->view(CNFREQUEST . 'request_orderprocessing.php'); ?>
            <!-- *********************** ORDER PROCESSING ENDS HERE ************************-->
 
            <!--<div class="card border-0 mar-t-3" style="width:2500px;    overflow-x: scroll;">-->
            <div class="card border-0 mar-t-3">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">BOM <?php echo $requesttypedata ?> IN-HOUSE DETAILS</div>
                </div>
                <div class="table-responsive cus-ovrflw pad-b-20">
                    <div id="inHouseStatus"></div>
                    
                </div>
                <div class="col-12 text-right pr-3 py-3" >          
                        <a class="btn btn-info btn-sm mar-l-5rem" id="getValues">SAVE</a>
                    </div>
            </div>

            <div class="card border-0 mar-t-3">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">BOM <?php echo $requesttypedata ?> ITEM ACCEPTANCE STATUS</div>
                </div>
                <div class="table-responsive cus-ovrflw pad-b-20">
                     <div id="itemAcceptStatus"></div>
                    
                </div>
              
                <div class="col-12 text-right pr-3 py-3">          
                        <a class="btn btn-info btn-sm mar-l-5rem" id="acceptSave">SAVE</a>
                </div>
            </div>

            <div class="card border-0 mar-t-3">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">BOM <?php echo $requesttypedata ?> IN-HOUSE CONSOLIDATED QTY.</div>
                </div>
                <div id="inHouseConsolidatedQty"></div>
            </div>

            <form class="row no-rad-form add-form-mar mar-t-3" id="reqOrderProcessingForm">
                <div class="row">
                     <div class="col-12 pr-3">
                        <label class="cus-label"> Merchant Attachment </label>
                    </div>

                    <div class="row mb-5">
                        <ul class="upload-list-view ImageView" style="list-style: none;">
                        </ul>
                    </div>

                    <div class="col-12 pr-3">
                        <label class="cus-label"> Purchase Attachment<?php echo($suplaycloseData)?></label>
                    </div>

                    <div class="row mb-5">
                        <ul class="upload-list-view purchaseImageView" style="list-style: none;">
                        </ul>
                    </div>
                    <div class="col-12 text-right pr-3 py-3">                
                        <!--<a class="btn btn-info btn-sm mar-l-5rem" id="back">BACK</a>-->
                        <?php if($suplaycloseData==1) { ?>
                            <a class="btn btn-info btn-sm mar-l-5rem" id="supplyClosed" disabled>SUPPLY CLOSED </a>
                       <?php } else {?>
                             <a class="btn btn-info btn-sm mar-l-5rem" id="supplyClosed"  >SUPPLY CLOSED </a>
                        <?php  }?>

                         <?php if($orderstockData==1) { ?>
                            <a class="btn btn-info btn-sm mar-l-5rem" id="orderStockList" disabled>MOVE - ORDER STOCK LIST</a>
                       <?php } else {?>
                              <a class="btn btn-info btn-sm mar-l-5rem" id="orderStockList">MOVE -  ORDER STOCK LIST</a>
                        <?php  }?>
                          
                          
                        <!-- <a class="btn btn-info btn-sm mar-l-5rem" id="orderStockList">BOM ORDER STOCK LIST</a> -->
                      
                        <a class="btn btn-info btn-sm mar-l-5rem" id="save">SAVE</a>
                    </div>
                </div>
            </form>
        </div>

        <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
        <div class="control-sidebar-bg"></div>
    </div>
    
    <div id="acceptModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Check PIN</h4>
      </div>
      <div class="modal-body">
        <labeL>Enter PIN</labeL>
        <input type="text" id="pin" name="pin" class="form-control" autocomplete="off">
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="modal_save">save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<div id="supplyModal" class="modal" role="dialog"  >
  <div class="modal-dialog">
    <div class="modal-content" style="width:300px;">
      <div class="modal-header"  style="width:300px; background-color: #022B61;">
        <!-- <button type="button" style="color: #fff; " class="close" data-dismiss="modal">&times;</button> -->
        <!-- <button type="button" class="close custom-close" data-dismiss="modal">&times;</button> -->
           <a  class="close custom-close" data-dismiss="modal">&times;</a>
        <h4 class="modal-title" style="color: #fff;">Supply Closure Status</h4>
      </div>
      <div class="modal-body" style="width:300px;height: 160px;">
        <!--<labeL>Supply Closure Status</labeL>-->
        <!--<select class="form-control" id="supply_status" name="supply_status">-->
        <!--    <option value="">Select</option>-->
        <!--    <option value="1">DISC. SUPPLY CLOSED</option>-->
        <!--    <option value="2">SHORT SUPPLY CLOSED</option>-->
        <!--    <option value="3">FULL SUPPLY CLOSED</option>-->
        <!--    <option value="4">P.I. CANCELLED</option>-->
        <!--</select>-->
        
        
        <div class="" style="padding: 0px 27px 0px 35px;">
            <p>
            <input type="radio" id="1" name="supply_status" value="1" class="cus-radio-btn pur_req" style="float: left !important;">
            <label for="sample" class="" style="padding-top: 3px;"> DISC. SUPPLY CLOSED </label>
            </p>
            <p>
            <input type="radio" id="2" name="supply_status" value="2" class="cus-radio-btn pur_req" style="float: left !important;">
            <label for="sample" class="" style="padding-top: 3px;"> SHORT SUPPLY CLOSED </label>
            </p>
            <p>
            <input type="radio" id="3" name="supply_status" value="3" class="cus-radio-btn pur_req" style="float: left !important;">
            <label for="sample" class="" style="padding-top: 3px;"> FULL SUPPLY CLOSED </label>
            </p>
            <p>
            <input type="radio" id="4" name="supply_status" value="4" class="cus-radio-btn pur_req" style="float: left !important;">
            <label for="sample" class="" style="padding-top: 3px;">P.I. CANCELLED </label>
            </p>
        </div>
        
      </div>
      
      <div class="col-sm-12" >
          
      </div>
      <div class="modal-footer" style="width:300px;height: 63px;">
          <button type="button" class="btn btn-info btn-sm mar-l-5rem" id="modal_status">SAVE</button>
        <button type="button" class="btn btn-default btn-sm mar-l-5rem" data-dismiss="modal">CLOSE</button>
      </div>
    </div>

  </div>
</div>


    <!-- <script src="<?php echo base_url();?>assets/js/datatables.min.js"></script> -->
    <?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>
    <script>
        var enquiry_id = <?php echo $VarEnqId; ?>;
        var reqId = <?php echo $request_id; ?>;
        var pId = <?php echo $pId; ?>;
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/bom/store/store_pi_update.js">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#getValues').addClass('disabled');

            $(".mgmt").prop('disabled', true);

            function matchStart(params, data) {
                params.term = params.term || '';
                if (data.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
                    return data;
                }
                return false;
            }

            $('.js-example-basic-single').select2({
                placeholder: "Select",
                matcher: function(params, data) {
                    return matchStart(params, data);
                },
            });

            $('b[role="presentation"]').hide();
            $('.select2-selection__arrow').append('<span class="arrow-select2-ji"><span>');
            
        });
    </script>