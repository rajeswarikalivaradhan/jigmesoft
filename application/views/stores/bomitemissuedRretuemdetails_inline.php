<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/datepicker/datepicker3.css">
<style type="text/css">
    .bomReceivedItemsDivLoop .box-header {
        background-color: #E7E7E7;
    }
    th, .verticalThead {
        background-color: #ffefef;
    }
</style>
<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
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
            <div class="row">
                <div class="col-md-12" id="divCommonOrderEntryBasicInfo"><?php $this->load->view('commonBasicInfoOrderEntry') ?></div>
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">BOM - ITEM ISSUED / RETURNED DETAILS (IN-LINE)</h3>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <div class="box-header with-border"> <h3 class="box-title">ITEM DESCRIPTION</h3> </div>
                            <table class="table table-hover">
                                <tr>
                                    <th>S.No</th>
                                    <th>Item Description / (%) Blend / Content / Material</th>
                                    <th>Item Code</th>
                                    <th>Item Color Code</th>
                                    <th>Size or Dim (W*L*H)</th>
                                    <th>Unit of Measure</th>
                                    <th>P.I. Qty.</th>
                                    <th>Total Received Qty.</th>
                                    <th>Unit of Measure</th>
                                </tr>
                                <tr>
                                    <td><?php 1 ?></td>
                                    <td><?php 1 ?></td>
                                    <td><?php 1 ?></td>
                                    <td><?php 1 ?></td>
                                    <td><?php 1 ?></td>
                                    <td><?php 1 ?></td>
                                    <td><?php 1 ?></td>
                                    <td><?php 1 ?></td>
                                    <td><?php 1 ?></td>

                                </tr>
                            </table>
                        </div>

                        <div class="box-header with-border">
                            <h3 class="box-title">INDENT RECEIVED DETAILS</h3>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <form class="form-horizontal" id="invDetailsFrm_">
                                <table class="table table-hover" id="<?php ?>">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Indent Ref. No.</th>
                                        <th>Indent Raised Date & Time</th>
                                        <th>Indent Cutoff <br />Date & Time</th>
                                        <th>Purpose</th>
                                        <th>Indent Raised By</th>
                                        <th>Indent Authorized By</th>
                                        <th>Department to Issue</th>
                                        <th>Indent Qty.</th>
                                        <th>Unit of Measure</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="indentRecdDetailsTrCls">
                                        <td><?php ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
<div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/ajax.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    var GlbId = 0; var GlbPiRefId = '<?php echo @$VarPiRefId ?>'; var GlbUserAuthType = ''; var GlbAllDropdownStatus = 0; var GlbItemRefno = '<?php echo @$VarItemRefNo ?>';
    var GlbCurretUrl = '<?php echo $VarCurrenturl ?>';
    
    var GlbInv = $(".invoiceFirstRowCls").length;
    console.log(GlbInv,'GlbInv');
    function addInvoiceRow(ids) {
        GlbInv++;
        var ele = '<tr class="invoiceFirstRowCls" id="invoiceNewRow_' + GlbInv + '">\n' +
            '                                        <td>' + GlbInv + '</td>\n' +
            '                                        <td><input type="text" id="basicInvNo' + GlbInv + '" name="'+ids+'_basicInvNo[]"></td>\n' +
            '                                        <td><input type="text" id="basicInvDate" name="'+ids+'_basicInvDate[]" style="width: 82px"></td>\n' +
            '                                        <td><input type="text" id="basicInvQty' + GlbInv + '" name="'+ids+'_basicInvQty[]" onchange="invoiceQtyChange(this.value,' + ids + ')"></td>\n' +
            '                                        <td>\n' +
            '                                            <select id="basivInvQtyUom' + GlbInv + '" name="'+ids+'_basicInvQtyUom[]">\n' +
            '                                                <option value="">Choose</option>\n' +
            '                                                <option value="1">Inches</option><option value="2">Cms</option><option value="4">Meter</option>                                            </select>\n' +
            '                                        </td>\n' +
            '                                        <td><input type="text" id="basicReceivedDate' + GlbInv + '" name="'+ids+'_basicReceivedDate[]" style="width: 82px"></td>\n' +
            '\n' +
            '                                        <td><input type="text" id="basicReceivedQty' + GlbInv + '" name="'+ids+'_basicRecdQty[]" onchange="receivedQtyChange(this.value,' + ids + ')"></td>\n' +
            '\n' +
            '                                        <td>\n' +
            '                                            <select id="basicReceivedQtyUom' + GlbInv + '" class="" name="'+ids+'_basicRecdQtyUnitofmeasure[]">\n' +
            '                                                <option value="">Choose</option>\n' +
            '                                                <option value="1">Inches</option><option value="2">Cms</option><option value="3">Meter</option>                                            </select>\n' +
            '                                        </td>\n' +
            '                                        <td>\n' +
            '                                            <select id="basicReceuvedQtyFullPart' + GlbInv + '" name="'+ids+'_basicRecdQtyFullPart[]">\n' +
            '                                                <option value="">Choose</option>\n' +
            '                                                <option value="1">Full</option>\n' +
            '                                                <option value="2">Part</option>\n' +
            '                                            </select>\n' +
            '                                        </td>\n' +
            '                                    </tr>';
        $("#invDetailsTbl_" + ids).append(ele);

        $('#basicInvDate').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
    }
    function removeInvoiceRow(ids) {
        var rowslength = $("#invDetailsTbl_" + ids + " .invoiceFirstRowCls").length;
        if (rowslength > 1) {
            $("#invDetailsTbl_" + ids + " > tbody > tr:last").remove();
        }
    }
    $('#basicInvDate').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $('#basicReceivedDate').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    var GlbInvoiceQty = 0;
    function invoiceQtyChange(thisvalue, ids) {
        GlbInvoiceQty += Number(thisvalue);
        $("#invoiceQtySum_" + ids).text(GlbInvoiceQty);
    }
    var GlbReceivedQty = 0;
    function receivedQtyChange(thisvalue, ids) {
        GlbReceivedQty += Number(thisvalue);
        $("#receivedQtySum_" + ids).text(GlbReceivedQty);
    }
    function UnitofmeasureForTot(thisobj, ids) {
        var unitofmeasure = thisobj.options[thisobj.selectedIndex].text;
        $("#UnitofmeasureForTot_" + ids).text(unitofmeasure);
    }
    function UnitofmeasureForReceivedQtyTot(thisobj, ids) {
        var unitofmeasure = thisobj.options[thisobj.selectedIndex].text;
        $("#UnitofmeasureForReceivedQtyTot_" + ids).text(unitofmeasure);
    }
    function allAuthStatusChange(thisobj,userauthtype,invoicerefno,itemrefno) {
        GlbAllDropdownStatus = thisobj.value;
        GlbUserAuthType = userauthtype;
        GlbInv = invoicerefno;
        GlbItemRefno = itemrefno;
        if(GlbAllDropdownStatus >= 1) {
            $('#myModal').modal('show');
        }
    }
    function fnCheckPin() {
        var Emailid = $("#frmUid").val();
        var Pwd = $("#frmPwd").val();
        console.log(GlbUserAuthType,'GlbUserAuthType');
        var uatypeid = '';
        if(GlbUserAuthType == 'itemverifyauthstatus') {
            uatypeid = 4;
        }
        else if(GlbUserAuthType == 'qtyverifyauthstatus') {
            uatypeid = 9;
        }
        else if(GlbUserAuthType == 'qualityanaauthstatus') {
            uatypeid = 12;
        }
        else if(GlbUserAuthType == 'invoiceverifyauthstatus') {
            uatypeid = 8;
        }
        else if(GlbUserAuthType == 'itemreadyauthstatus') {
            uatypeid = 1;
        }
        MakeAsynPostRequest(base_path+'dashboard/commonLotApprovalAuth',"e="+Emailid+"&p="+Pwd+"&apprrejectstatus="+GlbAllDropdownStatus+"&pinorefid="+GlbPiRefId+"&itemrefno="+
            GlbItemRefno+"&invoicerefno="+GlbInv+"&userauthtypeid="+uatypeid,'json',fnCheckPinRes);
        return false;
    }
    function fnCheckPinRes(data) {
        if(data != '') {
            if(data.errcode == 1) {
                $("#"+GlbUserAuthType+GlbInv+'_'+GlbItemRefno).text(data.cn);
                $("#"+GlbUserAuthType+"Datetime_"+GlbInv+'_'+GlbItemRefno).text(data.dt);
                $("#myModal").modal("hide");
                if(GlbUserAuthType == 'itemreadyauthstatus') {
                    MakeAsynPostRequest(base_path+'dashboard/updateBomLotApprCookies',"pinorefid="+GlbPiRefId+"&itemrefno="+GlbItemRefno+"&invoicerefno="+
                        GlbInv,'json',saveCookiestoDbRes);
                }
            }
            else {
                $("#ErrfrmModalAuth").text('Invalid E-mail Id / Password');
            }
        }
    }
    function saveCookiestoDbRes(data) {
        console.log(data,'saveCookiestoDbRes data');
    }
    function fnNextItemRefNo(itemCount,nextprev) {
        if(nextprev) {
            console.log('itemCount != itemno');
            console.log(itemCount,'itemCount');
            GlbItemRefno++;
            window.location.href = base_path+'storesuser/bomitem_received_details/'+GlbPiRefId+'/'+GlbItemRefno;
        }
        else {
            if (GlbItemRefno == 2) {
                GlbItemRefno = '';
                console.log(GlbItemRefno,'GlbItemRefno if == 2');
                window.location.href = base_path+'storesuser/bomitem_received_details/'+GlbPiRefId+'/'+GlbItemRefno;
            }
            else if(GlbItemRefno > 2) {
                GlbItemRefno--;
                console.log(GlbItemRefno,'GlbItemRefno decremanet');
                window.location.href = base_path+'storesuser/bomitem_received_details/'+GlbPiRefId+'/'+GlbItemRefno;
            }
        }
    }
    function fnSaveBomItemrecdInvoiceDetails(ids) {
        var invNo=[]; var invDate = []; var invQty = [];  var invUofm = []; var recdDate = []; var recdQuantity = []; var recdUofm = []; var fullPart = [];
        $("#frmErr_"+GlbItemRefno).text('');
        $("input[name='"+ids+"_basicInvNo[]']").each(function () {
            if($(this).val()) {
                invNo.push($(this).val());
            }
            else {
                $("#frmErr_"+GlbItemRefno).text('Error');
            }
        });
        $("input[name='"+ids+"_basicInvDate[]']").each(function () {
            invDate.push($(this).val());
        });
        $("input[name='"+ids+"_basicInvQty[]']").each(function () {
            invQty.push($(this).val());
        });
        $("select[name='"+ids+"_basicInvQtyUom[]']").each(function () {
            invUofm.push($(this).val());
        });
        $("input[name='"+ids+"_basicReceivedDate[]']").each(function () {
            recdDate.push($(this).val());
        });
        $("input[name='"+ids+"_basicRecdQty[]']").each(function () {
            recdQuantity.push($(this).val());
        });
        $("select[name='"+ids+"_basicRecdQtyUnitofmeasure[]']").each(function () {
            recdUofm.push($(this).val());
        });
        $("select[name='"+ids+"_basicRecdQtyFullPart[]']").each(function () {
            fullPart.push($(this).val());
        });
        if(invNo.length) {
            var Param = "invNo=" + JSON.stringify(invNo) + "&invDate=" + invDate + "&invQty=" + JSON.stringify(invQty) + "&invUofm=" + JSON.stringify(invUofm) +
                "&recdDate=" + recdDate + "&recdQuantity=" + JSON.stringify(recdQuantity) + "&recdUofm=" + JSON.stringify(recdUofm) + "&fullPart=" +
                JSON.stringify(fullPart) + "&GlbPirefId=" + GlbPiRefId + "&itemrefno=" + GlbItemRefno;
            MakeAsynPostRequest(base_path + 'dashboard/updateBomItemrecdInvoiceDetails', Param, 'json', fnSaveBomItemrecdInvoiceDetailsRes);
            return false;
        }
    }
    function fnSaveBomItemrecdInvoiceDetailsRes(data) {
        console.log(data,'data');
        if(data != '') {
            if(data.errcode == 1) {
                GlbId = data.id;
                //console.log(GlbId,'glbid');
                fnRedirectPageTimeOut(GlbCurretUrl);
            }
        }
    }

    function fnNewStockList(pirefno,itemref) {
        console.log(pirefno,'pirefno');
        console.log(itemref,'itemref');
        window.location.href = base_path+'storesuser/newbomstocklist/'+pirefno+'/'+itemref;
    }
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>