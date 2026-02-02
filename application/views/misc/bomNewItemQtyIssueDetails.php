<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/datepicker/datepicker3.css">
    <style type="text/css">

    </style>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content">
            <!-- Default box -->
            <div class="box-body">
                <?php $this->load->view('commonBasicInfoOrderEntry') ?>
                <div class="box box-info">
                    <form method="post">
                    <div class="box-header with-border">
                        <h4 class="">BOM NEW ITEM - QTY. ISSUED DETAILS</h4>
                        <small>DESCRIPTION</small>
                        <div class="box-tools pull-right"></div>
                    </div>
                    <div class="box-body">
                        <div id="itemJxl"></div>
                    </div>
                    <div class="box-header with-border">
                        <h4>MATERIAL INDENT RECEIVED DETAILS</h4>
                        <div class="box-tools pull-right"></div>
                    </div>
                    <div class="box-body">
                        <div id="matIndentReceivedDetailsJxl"></div>
                    </div>
                    <div class="box-header with-border">
                        <h4>NEW ITEM - QTY. ISSUED DETAILS</h4>
                        <div class="box-tools pull-right"></div>
                    </div>
                    <div class="box-body">
                        <div id="newItemDC_QtyIssueDetailsJxl"></div>
                    </div>

                    <div class="box-body pull-right">
                        <button class="btn btn-info" type="button" onclick="return fnPrepareDc()">PREPARE D.C.</button>
                        <button class="btn btn-info" type="button" onclick="fnPreviewDc()">Preview D.C.</button>
                        <a href="<?php echo base_url('storesuser/newitemlist') ?>" class="btn btn-info" style="padding: 10px">BACK TO NEW ITEM LIST</a>
                        <button class="btn btn-info pull-right" type="button" onclick="fnSave()">Save</button>
                    </div>

                    </form>
                    <!--<strong></strong>-->

                </div>
                <?php
                /*echo '<pre>'; print_r($ArrPiData); die;
                echo '<pre>'; print_r($VarItemRefNo);
                echo '<pre>'; print_r($RequiredQty);
                echo '<pre>'; print_r($ArrPiData[$VarItemRefNo]); die('die');*/
                ?>
                <!--<div class="box-footer nopadding">-->
                    <!--<div class="herr" id="ErrfrmPage"></div>
                    <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                    <button class="btn btn-info pull-right" type="button">Save Changes</button>-->
                <!--</div>-->
            </div>
            <div id="successMsg" class="alert alert-success hide"></div>
            <!-- Modal Starts Here -->
            <div class="modal fade" id="dcTypeOptionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form class="form-horizontal" method="post">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Choose D.C. Type</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <input type="radio" name="frmDcType" id="frmIntDcType" value="1" class="">
                                        <label for="frmIntDcType">INTERNAL</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="radio" name="frmDcType" id="frmExtDcType" value="2" class="">
                                        <label for="frmExtDcType">EXTERNAL</label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="return fnContinueDeliveryChallan()">Continue
                                </button>
                                <div class="herr pull-left" id="dcTypeErr"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/ajax.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script type="text/javascript">
    var GlbId = '<?php echo $newItemId ?>';
    var GlbSampleRequestId = '<?php echo $sampleRequestId ?>';
    var GlbOrderId = '<?php echo $VarOrderId ?>';
    var GlbBomItemId = '<?php echo $bomItemId ?>';
    var ArrPiData         = '<?php echo $ArrPiData ?>'
    var GlbMatReceivedQty_Total         = '<?php echo @$matReceivedQty_Total ?>'

    var GlbArrIndentRefNo = '<?php echo @$ArrMatIndentRefNo ?>';
    //console.log(GlbArrIndentRefNo,'GlbArrIndentRefNo');
    //console.log(JSON.parse(GlbArrIndentRefNo),'parse GlbArrIndentRefNo');
    var GlbMatIndentReceivedDetails = '<?php echo $matIndentReceivedDetailsJxl ?>';
    //console.log(GlbMatIndentReceivedDetails,'GlbMatIndentReceivedDetails');
    //console.log(JSON.parse(GlbMatIndentReceivedDetails),'JSON.parse(GlbMatIndentReceivedDetails)');
    var GlbNewItemQtyIssuedDetails = ''; var GlbTotalReceivedQty = 0;

    // A custom method to SUM all the cells in the current column
    var SUMCOL = function (instance, columnId) {
        var total = 0;
        //console.log(instance.options.data,'instance.options.data');
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        return total;
    }

    console.log(GlbTotalReceivedQty,'GlbTotalReceivedQty');

        jexcel(document.getElementById('matIndentReceivedDetailsJxl'), {
            columns: [
                {type: 'text', title: 'Material Indent Ref No', width: 365, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Material Indent Raised Date & Time', width: 150, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Material Indent Cutoff Date & Time', width: 150, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Purpose', width: 120, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Material Indent Raised By', width: 120, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Material Indent Authorized By', width: 120, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Issued To (Department)', width: 100, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Material Indent Qty.', width: 110, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Unit Of Measure', width: 100, wordWrap: true, readOnly: true}
            ],
            //minDimensions: [9,1],
            data: JSON.parse(GlbMatIndentReceivedDetails)
        });

        let qtyJxl = jexcel(document.getElementById('newItemDC_QtyIssueDetailsJxl'), {
            url: base_path+"storesuser/getNewItemDC_QtyIssueDetailsJxl/"+GlbId,
            columns: [
                {type: 'dropdown', name: 'matIndentRefNo', source: JSON.parse(GlbArrIndentRefNo), title: 'Material Indent Ref No', width: 400, wordWrap: true},
                {type: 'text', name: 'dcNo', title: 'D.C. No.', width: 230, wordWrap: true, readOnly: true},
                {type: 'text', name: 'dcDateTime', title: 'D.C. Date & Time', width: 125, wordWrap: true, readOnly: true},
                {type: 'text', name: 'matIssuedBy', title: 'Material Issued By', width: 100, wordWrap: true, readOnly: true},
                {type: 'text', name: 'matReceivedBy', title: 'Material Received By', width: 100, wordWrap: true},
                {type: 'text', name: 'balanceQty', title: 'Balance Qty.', width: 110, wordWrap: true, readOnly: true},
                {type: 'text', name: 'issuedQty', title: 'Issued Qty.', width: 110, wordWrap: true},
                {type: 'text', name: 'unitOfMeasure', title: 'Unit Of Measure', width: 80, wordWrap: true, readOnly: true},
                {type: 'checkbox', name: 'select', title: 'Select.', width: 80}
            ],
            footers: [['', '', '', '', '', 'Total', '=SUMCOL(TABLE(), COLUMN())']],
            data: [[]],
            updateTable:function(instance, cell, col, row, val, label, cellName) {
            },
            oninsertrow: function(instance,rowNumber) {
                //console.log(rowNumber,'rowNumber');
                //console.log(instance.jexcel.options.data[rowNumber][6],'instance.options.data');
                let cellNaming = rowNumber+2;
                //let openingBal = instance.jexcel.getValue('F'+rowNumber+1);
                let openingBal = instance.jexcel.options.data[rowNumber][5];
                //console.log(openingBal,'openingBal');
                //let issuedQty = instance.jexcel.getValue('G'+rowNumber+1);
                let issuedQty = instance.jexcel.options.data[rowNumber][6];
                let uom = instance.jexcel.options.data[rowNumber][7];
                let bal = openingBal - issuedQty;

                instance.jexcel.setValue('F'+cellNaming,bal);
                instance.jexcel.setValue('H'+cellNaming,uom);
                //instance.jexcel.setValue('F'+rowNumber+1,bal);
                //console.log(instance, rowNumber, numOfRows, rowRecords, insertBefore,'instance, rowNumber, numOfRows, rowRecords, insertBefore');
            }
        });

    var GlbArrPiData = '';
    if(ArrPiData != '') {
        GlbArrPiData = JSON.parse(ArrPiData);
    }
    else {
        GlbArrPiData = [
            []
        ];
    }
    console.log(GlbArrPiData,'GlbArrPiData');
    jexcel(document.getElementById('itemJxl'), {
        columns: [
            {type: 'text', title: 'Item Description / (%)Blend / Content / Material', width: 250, wordWrap: true, readOnly: true},
            {type: 'text', title: 'Gar. Size', width: 50, wordWrap: true, readOnly: true},
            {type: 'text', title: 'Item Code', width: 120, wordWrap: true, readOnly: true},
            {type: 'text', title: 'Item Color Code', width: 120, wordWrap: true, readOnly: true},
            {type: 'text', title: 'Size / Dim. (W*L*H)', width: 120, wordWrap: true, readOnly: true},
            {type: 'text', title: 'Unit of Measure', width: 100, wordWrap: true, readOnly: true},
            {type: 'text', title: 'P.I. No.', width: 255, wordWrap: true, readOnly: true},
            {type: 'text', title: 'P.I. Qty. Itemized', width: 110, wordWrap: true, readOnly: true},
            {type: 'text', title: 'Received Qty.', width: 110, readOnly: true},
            {type: 'text', title: 'Unit of Measure', width: 100, readOnly: true},
        ],
        footers: [['', '', '', '', '', '', 'Total', '=SUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']],
        data: [GlbArrPiData]
    });

    function fnSave() {
        let newItemDC_QtyIssueDetailsJxl = JSON.stringify($("#newItemDC_QtyIssueDetailsJxl").jexcel('getJson'));
        MakeAsynPostRequest(base_path+"storesuser/saveDeliveryChallanPreview","rFrom=1&oid="+
            GlbOrderId+"&sampleRequestId="+GlbSampleRequestId+"&newItemId="+GlbId+"&jxl="+newItemDC_QtyIssueDetailsJxl+
            "&bom_item_id="+GlbBomItemId,"json",function (data) {
            if(data != '') {
                if(data.errCode == 1) {
                    $("#successMsg").removeClass('hide');
                    $("#successMsg").text('saved Successfully');
                }
            }
        });
    }
    
    function fnPrepareDc() {
        if (window.confirm("Do you want to Prepare D.C.?")) {
            $('#dcTypeOptionModal').modal('show');
        }
        else {
            $('#dcTypeOptionModal').modal('hide');
            return false;
        }
        //let Param = "newItemId="+GlbNewItemId+"&oid="+GlbOrderId+"&newItemDC_QtyIssueDetailsJxl="+jxl;
    }

    function fnContinueDeliveryChallan() {
        let dcType = $('input[name="frmDcType"]:checked').val();
        let newItemDC_QtyIssueDetailsJxl = JSON.stringify($("#newItemDC_QtyIssueDetailsJxl").jexcel('getJson'));
        if(dcType != '') {
            MakeAsynPostRequest(base_path+"storesuser/saveDeliveryChallanPreview","rFrom=1&dcType="+dcType+"&oid="+
                GlbOrderId+"&sampleRequestId="+GlbSampleRequestId+"&newItemId="+GlbId+"&jxl="+newItemDC_QtyIssueDetailsJxl+
                "&bom_item_id="+GlbBomItemId,"json",function (data) {
                console.log(data,'data');
                if(data != '') {
                    if(data.errCode == 1) {
                        window.location.href = base_path+"storesuser/bomStoresDeliveryChallan/"+GlbOrderId+"/"+GlbId;
                    }
                }
            });

        }
        else {
            $("#dcTypeErr").text('Choose a D.C. Type');
            return false;
        }

    }

    function fnPreviewDc() {

    }


</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>