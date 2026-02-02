<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <h1></h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body" style="padding: 0">
                            <div class="col-md-12 pd0 no-padding">
                                <?php $this->load->view('commonBasicInfoOrderEntry'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="box-header with-border">
                        <h3 class="box-title box-headerBoxTitle">SHIPMENT STATUS</h3>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-bgColor">
                            <h3 class="box-title box-titleFontSize">ORIGINAL SCHEDULE AS PER P.O.</h3>
                        </div>
                        <?php
                        $PcsSet = unserialize(ARRPCSSET);
                        //echo '<pre>'; print_r($ArrGridData); die('die');
                        ?>
                        <div class="box-body">
                            <label class="">P.O. No. / Enq. Ref. No.:</label>
                            <label class=""
                                   style="font-weight: normal; margin-left: 5px"><?php echo $VarPoNo; ?></label>
                        </div>
                        <div class="box-body table-responsive">
                            <div id="originalScheduleJxl"></div>

                            <button type="button" class="pull-right btn btn-info" id=""
                                    onclick="return fnSaveOriginal()">Save Changes
                            </button>
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-bgColor">
                            <h3 class="box-title box-titleFontSize">REVISED SCHEDULE</h3>
                        </div>
                        <div class="box-body">

                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-bgColor">
                            <h3 class="box-title box-titleFontSize">GOODS SHIPPED DETAILS</h3>
                        </div>
                        <div class="box-body">

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>

<script type="text/javascript">
    var GlbDeliveryScheduleForStatus = '';
    var GlbReferenceId = '<?php echo $ReferenceId ?>';
    var poNoId = '<?php echo $poNoId ?>';

    MakeAsynPostRequest(base_path + 'dashboard/deliveryScheduleForStatus', "rFrom=1&referenceId=" + GlbReferenceId + "&poNoId=" + poNoId, "json", function (data) {
        console.log(data, 'data');
        console.log(data.re, 'data re');
        GlbDeliveryScheduleForStatus = data.re;
        let oriShipStatus = data.oriShipStatus;
        console.log(GlbDeliveryScheduleForStatus, 'GlbDeliveryScheduleForStatus');
        jexcel(document.getElementById('originalScheduleJxl'), {
            columns: [
                {type: 'text', title: 'Combo / Color', width: 135, readOnly: true},
                {type: 'text', title: 'P.O. Qty. / Sample Qty.', width: 100, readOnly: true},
                {type: 'text', title: 'Pcs. / Set', width: 135, readOnly: true},
                {type: 'text', title: 'Mode of Shipment', width: 135, readOnly: true},
                {type: 'calendar', title: 'Shipment Date / Submission Date', width: 135, readOnly: true},
                {type: 'text', title: 'Loading Port / City / Country', width: 135, readOnly: true},
                {type: 'text', title: 'Destination Port / City / Country', width: 135, readOnly: true},
                {type: 'dropdown', title: 'Shipment Status', width: 135, name: 'oStatus', source: oriShipStatus},
                //{type: 'calendar', title: 'Recent Update',width:135, readOnly: true},
                //{type: 'checkbox',title:'Select', name: 'selected'},
                {type: 'hidden', name: 'secId'}
            ],
            data: GlbDeliveryScheduleForStatus
        });

        jexcel(document.getElementById('revisedScheduleJxl'), {
            columns: [
                {type: 'text', title: 'Combo / Color', width: 135, readOnly: true},
                {type: 'text', title: 'P.O. Qty. / Sample Qty.', width: 100, readOnly: true},
                {type: 'text', title: 'Pcs. / Set', width: 135, readOnly: true},
                {type: 'text', title: 'Mode of Shipment', width: 135, readOnly: true},
                {type: 'calendar', title: 'Shipment Date / Submission Date', width: 135, readOnly: true},
                {type: 'text', title: 'Loading Port / City / Country', width: 135, readOnly: true},
                {type: 'text', title: 'Destination Port / City / Country', width: 135, readOnly: true},
                {type: 'dropdown', title: 'Shipment Status', width: 135, source: oriShipStatus},
                {type: 'calendar', title: 'Recent Update', width: 135, readOnly: true},
                {type: 'hidden', name: 'secId'}
            ],
            data: GlbDeliveryScheduleForStatus
        });
    });

    function fnSaveOriginal() {
        let oriStatus = $("#originalScheduleJxl").jexcel('getColumnData', 7);
        let originalScheduleId = $("#originalScheduleJxl").jexcel('getColumnData', 8);
        console.log(oriStatus, 'oriStatus');
        console.log(originalScheduleId, 'originalScheduleId');
        //let originalSchedule = $("#originalScheduleJxl").getColumnsData('9');
        MakeAsynPostRequest(base_path + 'dashboard/saveOriSchStatus', "rFrom=1&oStatus=" + oriStatus + "&oriId=" + originalScheduleId, "json", function (data) {
            console.log(data, 'data');
        });
    }

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>