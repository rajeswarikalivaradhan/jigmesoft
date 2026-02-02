<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/datepicker/datepicker3.css">
<style type="text/css">
</style>
<?php $this->load->view(CNFCOMPANY.'template/pageheader'); ?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="divCommonOrderEntryBasicInfo">
                    <?php $this->load->view('commonBasicInfoOrderEntry') ?>
                </div>
                <div class="col-md-12">
                    <div class="box box-primary" id="bomInvoiceDetails">
                        <div class="box-header with-border"><h3 class="box-title">BOM - INVOICE DETAILS</h3>
                            <div class="box-tools pull-right">
                                <select class="form-control" id="bominvoicedetailstype" onchange="fnBomInvoiceDetailsType(this.value)">
                                    <option value="">Choose INVOICE DETAILS</option>
                                    <option value="1">Domestic Invoice</option>
                                    <option value="2">Import Invoice</option>
                                </select>
                            </div>
                        </div>
                        <div class="box-body">

                                <div class="col-md-6">
                                    <form class="form-horizontal" id="bomInvoiceDetailFrm" method="post" action="">
                                        <div class="hide" id="domesticinvoiceFrm">
                                            <small id="headingforinvoicedetails"></small>
                                            <div class="form-group">
                                                <label for="" class="col-sm-2 control-label">Invoice No.</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="frmDom_invoiceno" name="frmDom_invoiceno">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-2 control-label">Invoice Date</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="frmDom_invoicedate" name="frmDom_invoicedate">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-2 control-label">Invoice Value</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="frmDom_invoicevalue" name="frmDom_invoicevalue">
                                                </div>
                                            </div>
                                            <div id='TextBoxesGroup'>
                                                <!--<div id="TextBoxDiv1">
                                                    <select class="" id="frmDom_Select1" name="frmDom_Select1">
                                                        <option value="1">CGST</option>
                                                        <option value="2">SGST</option>
                                                        <option value="3">IGST</option>
                                                        <option value="4">IMPORT DUTY</option>
                                                    </select>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="frmDom_TextBox1" name="frmDom_TextBox1">
                                                    </div>
                                                </div>-->
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-2 control-label">Total</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="frmDom_InvoiceTaxTotal" name="frmDom_InvoiceTaxTotal">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hide" id="importinvoiceFrm">
                                            <div class="form-group">
                                                <label for="" class="col-sm-2 control-label">Invoice No.</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="frmDom_invoiceno" name="frmDom_invoiceno">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-2 control-label">Invoice Date</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="frmDom_invoicedate" name="frmDom_invoicedate">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-2 control-label">Invoice Value</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="frmDom_invoicevalue" name="frmDom_invoicevalue">
                                                </div>
                                            </div>
                                        </div>
                                        TAX TYPE
                                        <button type="button" class="" id="addButton">Plus</button>

                                        <button type="button" class="" id="removeButton">Minus</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <small>OTHER EXPENSES</small>
                                    <form id="otherExpFrm" name="otherExpFrm" class="form-horizontal">
                                        <div class="form-group" id="clonethisDiv">
                                            <div class="col-sm-4">
                                                <select id="OtherExpSelect1" name="OtherExpSelect1" class="form-control">
                                                <?php
                                                foreach($ArrBomInvoiceOtherExpenses as $expKey => $exp) {

                                                    echo '<option value="'.$expKey.'">'.$exp.'</option>';

                                                }
                                                ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="frmOtherExpValue1" name="frmOtherExpValue1">
                                            </div>
                                        </div>
                                        <div id="TargetPlaceOtherExp"></div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Total:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="frmOtherExpTotal" name="frmOtherExpTotal">
                                            </div>
                                        </div>
                                    </form>
                                    <button type="button" class="" id="OthExpaddButton">Plus</button>
                                    <button type="button" class="" id="OthExpremoveButton">Minus</button>
                                </div>

                                <div class="box-footer nopadding">
                                    <div id="divSuccessBasicInfoMsg" class="alert alert-success alert-dismissable hide"></div>
                                    <div class="herr" id="frmReqErr"></div>
                                    <button class="btn btn-info pull-right" type="submit" onclick="return fnSaveInvoice_OtherExp()">Send Payment Request</button>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/ajax.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    var GlbInvoiceType = 0;
    function fnBomInvoiceDetailsType(thisvalue) {
        GlbInvoiceType = thisvalue;
        if(thisvalue == 1) {
            $("#importinvoiceFrm").addClass('hide')
            $("#headingforinvoicedetails").text('DOMESTIC INVOICE');
            $("#domesticinvoiceFrm").removeClass('hide');
        }
        else {
            $("#domesticinvoiceFrm").addClass('hide');
            $("#headingforinvoicedetails").text('IMPORT INVOICE');
            $("#importinvoiceFrm").removeClass('hide');
        }
    }
    /*function fnAddTaxTypes() {
        var click = 0;

        var ele = '';
    }*/
    $(document).ready(function() {
        var counter = 1;
        $("#addButton").click(function () {
            var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
            newTextBoxDiv.after().html('<div class="form-group" id="sgst_cgstAddSelectBox'+counter+'""> <div class="col-sm-2" style="padding-right: 0">' +
                '<select id="frmDom_Select_'+counter+'" class="form-control" name="frmDom_Select_'+counter+'" onchange="fnDynamicTax(this.value,'+counter+')">' +
                '<option value="">Choose</option>\n' +
                '<option value="1">CGST</option>\n' +
                '                                                    <option value="2">SGST</option>\n' +
                '                                                    <option value="3">IGST</option>\n' +
                '                                                    <option value="4">IMPORT DUTY</option></select></div>' +
                '<div class="col-sm-1" style="padding-left: 0px"><input type="text" class="form-control col-sm-1" name="frmDom_Percent'+counter+'" onchange="fnCalcPercent(this.value,'+counter+')" id="frmDom_Percent'+counter+'"></div><div class="col-sm-7">' +
                '<input type="text" class="form-control frmDom_PercentValCls" id="frmDom_PercentVal'+counter+'" name="frmDom_PercentVal'+counter+'"></div></div>');
            newTextBoxDiv.appendTo("#TextBoxesGroup");
            counter++;
        });
        $("#removeButton").click(function () {
            if(counter==1){
                alert("No more textbox to remove");
                return false;
            }
            counter--;
            $("#TextBoxDiv" + counter).remove();
            var Glbtotaltax = 0;
            $(".frmDom_PercentValCls").each(function () {
                //console.log($(this).val(),'add all these');
                Glbtotaltax += Number($(this).val());
            });
            $("#frmDom_InvoiceTaxTotal").val(Glbtotaltax.toFixed(2));
        });

        var otherexpCount = 1;
        $("#OthExpaddButton").click(function () {
            otherexpCount++;
            var ele = '<div class="form-group" id="NewOtherCharges'+otherexpCount+'">'+
                '<div class="col-sm-4">'+
                '<select id="OtherExpSelect'+otherexpCount+'" name="OtherExpSelect'+otherexpCount+'" class="form-control">'+
                '<option value="1">Documentation Charges</option><option value="2">C &amp; F Charges</option><option value="3">Courier Charges</option><option value="4">Transportation Charges</option><option value="5">Handling Charges</option><option value="6">Other Charges</option></select>'+
            '</div>'+
            '<div class="col-sm-8">'+
                '<input type="text" class="form-control" id="frmOtherExpValue'+otherexpCount+'" name="frmOtherExpValue'+otherexpCount+'">'+
                '</div>'+
                '</div>';
            //console.log(ele,'ele');
            $("#TargetPlaceOtherExp").append(ele);
        });
        $("#OthExpremoveButton").click(function () {
            $("#NewOtherCharges"+otherexpCount).detach();
            otherexpCount--;
        });
    });
    function fnDynamicTax(thisvalue,ids) {
        //console.log(thisvalue,'thisvalue');
        //$("#sgst_addingnow").remove();
        if(thisvalue == 1) {
             var ele = '<div class="form-group" id="sgst_addingnow"> <div class="col-sm-2"> <select id="frmDom_Select_'+ids+'" name="frmDom_Select_'+ids+'"><option value="">SGST</option></select></div>' +
                 '<div class="col-sm-1" style="position: relative; right: 17px">' +
                 '<input type="text" onchange="fnCalcSgstPercent(this.value)" style="width: 43px" class="form-control col-sm-1"></div><div class="col-sm-7">' +
                 '<input type="text" id="sgstPercentValue" style="width: auto" class="form-control col-sm-2 frmDom_PercentValCls"></div></div>';
            $("#sgst_cgstAddSelectBox"+ids).after(ele);
        }
        else {
            //$("#sgst_addingnow").remove();
        }
    }
    function fnCalcPercent(thisvalue,countid) {
        var invoiceval = $("#frmDom_invoicevalue").val()
        //console.log(invoiceval,'invoiceval');
        var percent = thisvalue;
        //console.log(percent,'percent');
        var res = Number(percent) / Number(invoiceval);
        //console.log(res,'res');
        var final = Number(res) * 100;
        //console.log(final,'final');
        $("#frmDom_PercentVal"+countid).val(final.toFixed(2));
        var totaltax = 0;
        $(".frmDom_PercentValCls").each(function () {
            //console.log($(this).val(),'add all these');
            totaltax += Number($(this).val());
        });
        $("#frmDom_InvoiceTaxTotal").val(totaltax.toFixed(2));
    }
    function fnCalcSgstPercent(thisvalue) {
        var invoiceval = $("#frmDom_invoicevalue").val()
        var sgstpercent = thisvalue;

        var sgstres = Number(sgstpercent) / Number(invoiceval);
        var sgstfinal = sgstres * 100;

        $("#sgstPercentValue").val(sgstfinal.toFixed(2));
        var sgsttotaltax = 0;
        $(".frmDom_PercentValCls").each(function () {
            console.log($(this).val(),'add all these');
            sgsttotaltax += Number($(this).val());
        });
        $("#frmDom_InvoiceTaxTotal").val(sgsttotaltax.toFixed(2));
    }
    function fnSaveInvoice_OtherExp() {
        var frmDom_PercentValCls = 0;
        $(".frmDom_PercentValCls").each(function () {
            frmDom_PercentValCls += Number($(this).val());
        });
        var invoiceDetails = $("#bomInvoiceDetailFrm").serialize();
        var otherExpFrm = $("#otherExpFrm").serialize();
        var Param = "frmDom_PercentValCls="+frmDom_PercentValCls+"&invtype="+GlbInvoiceType+"&"+invoiceDetails+"&"+otherExpFrm;
        console.log(Param,'Param');
        MakeAsynPostRequest(base_path+'dashboard/updateInvoiceDetails',Param,'json',fnSaveInvoice_OtherExpRes);
        return false;
    }
    function fnSaveInvoice_OtherExpRes(data) {
        //console.log(data,'data');
        if(data.errcode=='1') {
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("BOM Invoice Request has been updated successfully!");
        }
    }
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>