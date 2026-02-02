<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); 
$ArrProfileInfo = fnGetUserLoggedInfo(1);?>
    <!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
   <style>
  
    .jexcel > tbody > tr > td {
        /*background-color: rgb(247 247 247 / 45%);*/
         background-color:#f3f3f3;
    }

   .jexcel {
    border-right: 1px solid #f7f7f7 !important;
    border-bottom: 1px solid #f7f7f7 !important; 
    /*border-right: 1px solid #D9D9D9 !important;*/
   }
   .jexcel > tbody > tr > td:first-child,.jexcel > thead > tr > td,.jexcel > tfoot > tr > td {
      background-color:#D9D9D9!important;
   }
   .jexcel > thead > tr > td,.jexcel > tbody > tr > td,.jexcel > tfoot > tr > td {
    border: 0.01em solid #f7f7f7 !important;
   }
   .jexcel > tfoot > tr > td{
       height: 37px!important;
   }
   .b-0{
       border-top:none!important;
   }
   .table-responsive {
    overflow-x: unset !important;
}
.jdropdown-focus {
    position: inherit !important;
}
.content{
    padding-top:50px!important;
}
.ord-procs-cell {
    width: 25%;
    padding-top:2px!important;
    padding-left:4px!important;
    padding-right:4px!important;
    padding-bottom:4px!important;
}

.tbl-procs-border {
    border: 1px solid #ddd!important;
}
.table > tbody > tr > td {
    border-top:0px!important;
}
td.process-value,
td.process-title,
.process-main-value,
td.process-main-head {
    font-size: 12px;
    padding:6px!important;
}

td.process-main-heads {
    font-size: 12px;
}

td.process-title {
    background: #f3f3f3;
    width: 25% !important;
    text-align: right;
}
tfoot td:first-child, tfoot td:nth-child(2){
     display: table-cell!important; 
}
td.process-main-head {
    background: #022b61;
    color: #ffffff;
    text-align: center;
}

td.process-main-heads {
    background: #e8e8e8;
    color: #050505;
    text-align: left;
}
.tables{
    margin-bottom: 5px!important;
}
.card-body{
    margin:6px!important;
}
table.table.tbl-procs-border {
    margin-bottom: 0;
}
.table {
    background: #F7F7F7!important;
}
.swal2-content {
    font-size: 18px!important;
}
.swal2-titles {
    color: red!important;
    font-weight: 500!important;
}
.swal2-icon.swal2-warning{
    border-color: #FFCC00!important;
    color: #FFCC00!important;
    border: 2px solid #FFCC00!important;
}
   </style>
    <body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/commonheader');
    // $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar hide">
        <?php // $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper">
        <section class="content">
            <section class="invoice form-horizontal">
                <div class="row hidden-print"> 
                    <div class="col-xs-12">
                        <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"> Proforma Invoice
                          <div class="pull-right">
                                            <input type="hidden" id="subscriber_id" value="">
                                            <input type="hidden" id="proforma_type" value="<?php if (!empty($BasicInfo->proforma_type)) echo $BasicInfo->proforma_type; ?>">
                                            <div class="ml-auto pr-3">
                                             <a href="<?php echo base_url('invoice/manage'); ?>" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
                                            </div>
                                        </div>
                        <div class="col-sm-12 " style="padding: 7px 25px;border-bottom: 1px solid #022B61;"></div>
                        </h2>
                        <h4 class="mr-2 py-2 text-royal-blue">
                        </h4>
                    </div><!-- /.col -->
                </div>
                 <div class="row no-rad-form add-form-mar" id="custom_form">
                    <div class="col-md-12" id="cus-radio-btnblk">
                        <div style="border:1px solid #ddd;padding: 16px 0px 40px 0px;">
                            <div class="col-md-6" style="padding-left: 0px !important" >
                            <div class="col-md-5" >
                                <div class="form-group">
                                        <label for="id-form-field-focus-1" class="col-sm-6 control-label labelclr">
                                           Within State
                                        </label>
                                    <div class="col-sm-2">
                                       <input type="checkbox"  class="cus-radio-btn" id="withinstate" name="title" value="within" <?php if (!empty($BasicInfo) && ($BasicInfo->invoice_type=='within')) { echo 'checked'; }?> disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                        <label for="id-form-field-focus-1" class="col-sm-6 control-label labelclr">
                                         Inter State
                                        </label>
                                    <div class="col-sm-2">
                                       <input type="checkbox" class="cus-radio-btn" name="title" value="inter" <?php if (!empty($BasicInfo) && ($BasicInfo->invoice_type=='inter')) { echo 'checked'; }?> disabled >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                        <label for="id-form-field-focus-1" class="col-sm-6 control-label labelclr">
                                         Export
                                        </label>
                                    <div class="col-sm-2">
                                       <input type="checkbox" class="cus-radio-btn" name="title" value="export" <?php if (!empty($BasicInfo) && ($BasicInfo->invoice_type=='export')) { echo 'checked'; }?> disabled>
                                    </div>
                                </div>
                            </div></div>
                        </div>
                    </div>
                    <div class="col-md-12" >
                        <div class="col-md-12 py-3 px-0" style="border:1px solid #ddd;margin-bottom:0px">
                            <div class="col-md-7"><p><b>Azibo Infotech Private Limited </b></p></div>
                            <div class="col-md-5 text-right"><p>No.88, Block - B, Bose Garden Layout, Saravanampatti, Coimbatore - 641035.</p>
                            <p>Mobile: 9943931113, e-mail: jigmesoft@gmail.com, </p>                                                                                                                                                                                                                
                            <p>GST No:33ABCCA1637R1ZH </p></div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center"><h4 style="font-weight:600">Proforma Invoice</h4><hr class="my-0"></div>
                     <div class="col-md-12">
                         <div class="col-md-6 py-2 px-4"><b>To
                         </b>
                         </div>
                         <div class="col-md-6 py-2 text-center"><b>Proforma Reference</b></div>
                     </div>
                     <div class="col-md-12">
                        <table id="" class="tables table">
                            <tbody>
                                <tr>
                                    <td class="ord-procs-cell">
                                        <table class="table tbl-procs-border">
                                        <tbody>
                                             <?php $addr=(!empty($BasicInfo->invoaddress)) ?$BasicInfo->invoaddress:'';
                                                $city= (!empty($addr && $BasicInfo->invocity)) ?','.$BasicInfo->invocity:''; 
                                                $state= (!empty($city && $BasicInfo->invostate))? ','.$BasicInfo->invostate:''; 
                                                $country=(!empty($state && $BasicInfo->invocountry))?','.$BasicInfo->invocountry:''; 
                                                $pincode=(!empty($country && $BasicInfo->invopincode))? ', Pincode:'.$BasicInfo->invopincode:''; 
                                                $addressstr=$addr.$city.$state.$country.$pincode; ?>
                                            <tr>
                                                <td class="process-title">Name</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo->invocmpnyname)) echo $BasicInfo->invocmpnyname; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Address</td>
                                                 <td class="process-value" <?php if (!empty($addressstr)  && (strlen($addressstr)>=50)) { ?> style="padding:6px 6px 30px 6px!important;"  <?php } else { ?> style="padding:6px 6px 64px 6px!important;" <?php } ?>>
                                                <?php 
                                                
                                                if (!empty($addressstr)) { echo wordwrap($addressstr, 50, "<br/>"); } ?></td>
                                            </tr>
                                            <!--<tr>-->
                                            <!--    <td class="process-title"></td>-->
                                            <!--    <td class="process-value" style="padding: 16px 10px!important;"><?php if (!empty($BasicInfo->invocity)) echo $BasicInfo->invocity; ?>,<?php if (!empty($BasicInfo->invostate)) echo $BasicInfo->invostate; ?>,<?php if (!empty($BasicInfo->invocountry)) echo $BasicInfo->invocountry; ?></td>-->
                                            <!--</tr>-->
                                            <!--<tr>-->
                                            <!--    <td class="process-title"></td>-->
                                            <!--    <td class="process-value"><?php if (!empty($BasicInfo->invopincode)) echo 'Pincode:'.$BasicInfo->invopincode; ?></td>-->
                                            <!--</tr>-->
                                            <tr>
                                                <td class="process-title">GST No</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo->invogst_no)) echo $BasicInfo->invogst_no; ?></td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </td>
                                    
                                    <td class="ord-procs-cell">
                                        <table class="table tbl-procs-border">
                                        <tbody>
                                            <tr>
                                                <td class="process-title">Prof. Invoice Ref. No</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo)) echo $BasicInfo->invoice_refno; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Date</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo)) echo $BasicInfo->invoice_datetime; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Prof. Invoice Validity</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo)) echo $BasicInfo->invoice_validity; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Subscription Period</td>
                                                <td class="process-value">
                                                <?php
                                                $subscription_period = unserialize(ARRSUBSCRIPTIONPERIOD);
                                                if (!empty($BasicInfo->subscription_period)) { echo $subscription_period[$BasicInfo->subscription_period];} 
                                                ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Purchase Type</td>
                                                <td class="process-value"><?php if (!empty($purchase_type)) echo $purchase_type; ?></td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <div class="card border-0">
                            <div class="card-header p-2 border-0 bgc-white mb-1">
                                <div class="card-title f-14 text-500">
                                    Herewith, we quote for the following services:
                                </div>
                               
                            </div>
                            <div class="card-body border-0 p-0 m-0 collapse show">
                                <div class="table-responsive">
                                    <div id="cgst_sgst_proforma_grid"></div>
                                    <div id="igst_proforma_grid"></div>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 p-0">
                        <div class="col-md-6">
                            <div class="col-md-12 p-1">
                            <div class="form-group mb-0">
                                    <label for="id-form-field-focus-1" class="col-sm-3  labelclr">
                                      Amount in words &nbsp;&nbsp;&nbsp;&nbsp;:
                                    </label>
                                <div class="col-sm-9" id="amtinwords" style="padding:0px">
                                  <?php echo ucwords($amount) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 p-1">
                            <div class="form-group mb-0">
                                    <label for="id-form-field-focus-1" class="col-sm-3 labelclr">
                                     Terms & Conditions :
                                    </label>
                                <div class="col-sm-9" style="padding:0px"><?php if (!empty($BasicInfo)) echo $BasicInfo->terms_and_condition; ?>
                                   <input type="hidden" class="form-control" id="terms_and_condition" disabled  value="<?php if (!empty($BasicInfo)) echo $BasicInfo->terms_and_condition; ?>">
                                </div>
                            </div>
                        </div>
                        <!--<p>Terms & Conditions:<input type="text" class="form-control" id="terms_and_condition"></p>-->
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                    <div class="col-md-12 pl-2">
                        <div class="col-md-9"></div>
                        <div class="col-md-3"><p style="text-align:right">For <b>Azibo Infotech Private Limited</b></p></div>
                    </div>
                    <div class="col-md-12 pl-2">
                        <div class="col-md-9"></div>
                        <div class="col-md-3"><p style="padding:1rem 0 1rem!important;text-align:right"><?php if (!empty($BasicInfo)) echo $BasicInfo->status_updatedby; ?></p><p style="padding-bottom:1rem!important;text-align:right">Authorized Signatory</p></div>
                    </div>
                </div>
                <div class="row hidden-print">
                    <div class="col-xs-12" >
    <h2 class="page-header text-royal-black" style="border-bottom:0px">

        <div class="pull-right d-flex" style="gap:10px; padding-right: 20px;" >
            <a href="<?php echo base_url('invoice/csgstpprint/'.$VarId); ?>" 
               target="_blank" 
               class="btn custbtn btn-royal-blue btn-sm">
               Print
            </a>
            <a href="<?php echo base_url('invoice/csgstpdf/'.$VarId); ?>" 
               target="_blank" 
               class="btn custbtn btn-royal-blue btn-sm">
               Generate PDF
            </a>

            
        </div>

        <div class="col-sm-12" style="padding: 2px 25px;"></div>
    </h2>
</div>
                </div>
            </section>     
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
 
    <script src="<?= base_url() ?>assets/js/ajax.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
<script src="<?= base_url() ?>assets/js/jexcelNew/jexcel.js"></script>
<script src="<?= base_url() ?>assets/js/jexcelNew/jsuites.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jexcelNew/jspreadsheet.css" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jexcelNew/jsuites.css" type="text/css" />

    <script src="<?= base_url() ?>assets/ace/node_modules/interactjs/dist/interact.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
<script type="text/javascript">
    var GlbId = "<?php echo $VarId; ?>";
    var GlbsubscriberId = "<?php echo $SubscriberId; ?>";
    var lasturi='<?php echo $lastURI?>';
    var swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    var draftdata='<?php if (!empty($BasicInfo)) { echo $BasicInfo->invoice_type; } else { echo '';}?>';
   
     $(function (){
         
         $('input[type=checkbox][name=title]:checked').each(function() {
          if($(this).val()=='within'){
            $("#cgst_sgst_proforma_grid").show();
             $("#igst_proforma_grid").hide();
            // Cgst_Sgst_Grid(GlbsubscriberId);
            //  $("#scgstbtn_grid").show();
            //  $("#igstbtn_grid").hide();
             Cgst_Sgst_Grid_Draft(GlbsubscriberId);
          }else{
            $("#cgst_sgst_proforma_grid").hide();
             $("#igst_proforma_grid").show();
            // Cgst_Sgst_Grid(GlbsubscriberId);
            //  $("#scgstbtn_grid").hide();
            //  $("#igstbtn_grid").show();
             Cgst_Sgst_Grid_Draft(GlbsubscriberId);  
          }
        });
     });
   

 var SUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        total = numeral(total).format('0.00');
        total = (total > 0) ? total : ''
        return total;
    }
   
    function validateFiled(grid_name,data) {
        let validate_filed = [];
       
        if(grid_name == 'cgst_sgst_proforma_grid') {
            validate_filed = [0,1,2,3,5,6];
        }
        else if(grid_name == 'igst_proforma_grid') {
            validate_filed = [0,1,2,3,5];
        }

        validate = validateForm(validate_filed, data);
        return validate;
     
    }
    function validateForm(validateField, dataValue) {
       // console.log(dataValue)
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            for(let j = 0; j < validateField.length; j++) {
                let col = validateField[j];
                if(dataValue[i][col] == "") {
                    errorCount++;
                }
            }
        }
        return errorCount;
    }
    function Cgst_Sgst_Grid_Draft(enquiry_id)
    {    var checkboxval = '';
         $('input[type=checkbox][name=title]:checked').each(function() {
         checkboxval = $(this).val();
        });
        var proformatype=$('#proforma_type').val();
        //alert(checkboxval);
        if(checkboxval=='within'){
        $("#cgst_sgst_proforma_grid").html("");
        MakeAsynPostRequest(base_path + "badmin/mreqrcved/getquotedetails_cgst_sgst", "enquiry_id=" + enquiry_id + "&proforma_id=" + GlbId + "&proformatype=" + proformatype + "&invotype=" + checkboxval, "json", function(data) {
         //   console.log("draft" + data.data);
        let min_dimensions = data.column.length;
        let options = {
                data: data.data,
                editable:false,
                columns: data.column,
                minDimensions: [min_dimensions, 1],
                allowDeleteColumn: false,
                allowInsertRow: false,
                allowInsertColumn: false,
                footers:[['', '', '','Total : ','=SUMCOL(TABLE(), COLUMN())','', '','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())']],
                updateTable: function (instance, cell, col, row, val, label, cellName) { 
                    
                    if(col === 2)
                                    { 
                                        var txtValue = numeral(val).format('0.00');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        unit_rate = txtValue;
                                    }
                                    if(col === 3)
                                    {   
                                        var txtValue = numeral(val).format('0');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        qty = txtValue;
                                    }
                                    if(col === 4)
                                    {   
                                        amount = parseFloat(unit_rate) * parseFloat(qty);
                                        amount = numeral(amount).format('0.00');
                                       // amount  = (amount > 0) ? amount : '';
                                        $(cell).text(amount);
                                        instance.jexcel.options.data[row][col] = amount;
                                    }
                                    if(col === 5)
                                    {   
                                        sgst_percent =  val;
                                        sgst_percent = numeral(sgst_percent).format('0.00');
                                        sgst_percent  = (sgst_percent > 0) ? sgst_percent : '';
                                        $(cell).text(sgst_percent);
                                        instance.jexcel.options.data[row][col] = sgst_percent;
                                    }
                                    if(col === 6)
                                    {   
                                        cgst_percent =  val;
                                        cgst_percent = numeral(cgst_percent).format('0.00');
                                        cgst_percent  = (cgst_percent > 0) ? cgst_percent : '';
                                        $(cell).text(cgst_percent);
                                        instance.jexcel.options.data[row][col] = cgst_percent;
                                    }
                                    if(col === 7)
                                    {   
                                        sgst_amount = (parseFloat(sgst_percent) *(parseFloat(amount)))/100;
                                        sgst_amount = numeral(sgst_amount).format('0.00');
                                        //sgst_amount  = (sgst_amount > 0) ? sgst_amount : '';
                                        $(cell).text(sgst_amount);
                                        instance.jexcel.options.data[row][col] = sgst_amount;
                                    }
                                    if(col === 8)
                                    {   
                                        cgst_amount = (parseFloat(cgst_percent) *(parseFloat(amount)))/100;
                                        cgst_amount = numeral(cgst_amount).format('0.00');
                                        //cgst_amount  = (cgst_amount > 0) ? cgst_amount : '';
                                        $(cell).text(cgst_amount);
                                        instance.jexcel.options.data[row][col] = cgst_amount;
                                    }
                                    if(col === 9)
                                    {   
                                        subtot = parseFloat(amount) + parseFloat(sgst_amount) + parseFloat(cgst_amount);
                                        subtot = numeral(subtot).format('0.00');
                                        $(cell).text(subtot);
                                        instance.jexcel.options.data[row][col] = subtot;
                                        
                                    }
                }
            };
            
             let k = new Vue({
                    el: '#cgst_sgst_proforma_grid',
                    mounted: function () {
                        let spreadsheet = jspreadsheet(this.$el, options);
                        Object.assign(this, spreadsheet);
                    },
                    methods: {
                        submitData: function () {
                            let data = this.getData();
                            var validity=$('#invoice_validity').val();
                            var terms=$('#terms_and_condition').val();
                            var subscription_period=$('#subscription_period').val();
                            
                            MakeAsynPostRequest(base_path + GlbBAdminFdr + "mreqrcved/updateproformaInfo",
                            "rfrom=1&draftstatus=1&invotype=" + checkboxval + "&validity=" + validity + "&terms=" + terms + "&subscription_period=" + subscription_period + 
                            "&subsciber_id=" + GlbsubscriberId + "&id=" + GlbId + "&object=" + JSON.stringify(data), 
                            "json",function (data) {
                                    if (data != '') {
                                        if (data.errcode == '404') {
                                        fnCallSessionExpire();
                                        return false;
                                    } else if (data.errcode == -1) {
                                        swalWithBootstrapButtons.fire({
                                            title: data.msg,type: 'warning',
                                            icon: 'warning',
                                            customClass: {'confirmButton': 'btn btn-info'}
                                        });
                                        return false;
                                    } else if (data.errcode == 1) {
                                            swalWithBootstrapButtons.fire({
                                                        title: 'Saved!',type: 'success',
                                                        icon: 'success',
                                                        customClass: {'confirmButton': 'btn btn-info'}
                                            }).then((result) => {
                                                let redirectpath = base_path + GlbBAdminFdr + 'mreqrcved/addedit/'+ encodeURIComponent(base64_encode(lasturi)) + '/edit';
                                                window.location.href = redirectpath;
                                            });
                                        
                                    }
                                }
                            });
                        },
                        submitsaveData: function () {
                            let data = this.getData();
                            var validity=$('#invoice_validity').val();
                            var terms=$('#terms_and_condition').val();
                            var invoice_no=$('#invoice_no').val();
                            var subscription_period=$('#subscription_period').val();
                            
                            MakeAsynPostRequest(base_path + GlbBAdminFdr + "mreqrcved/updateproformaInfo",
                            "rfrom=1&draftstatus=2&invotype=" + checkboxval + "&validity=" + validity + "&terms=" + terms + "&invoice_no=" + invoice_no +  "&subscription_period=" + subscription_period +
                            "&subsciber_id=" + GlbsubscriberId + "&id=" + GlbId + "&object=" + JSON.stringify(data), 
                            "json",function (data) {
                                    if (data != '') {
                                        if (data.errcode == '404') {
                                        fnCallSessionExpire();
                                        return false;
                                    } else if (data.errcode == -1) {
                                        swalWithBootstrapButtons.fire({
                                            title: data.msg,type: 'warning',
                                            icon: 'warning',
                                            customClass: {'confirmButton': 'btn btn-info'}
                                        });
                                        return false;
                                    } else if (data.errcode == 1) {
                                            swalWithBootstrapButtons.fire({
                                                        title: 'Saved!',type: 'success',
                                                        icon: 'success',
                                                        customClass: {'confirmButton': 'btn btn-info'}
                                            }).then((result) => {
                                                let redirectpath = base_path + GlbBAdminFdr + 'mreqrcved/manage/';
                                                window.location.href = redirectpath;
                                            });
                                        
                                    }
                                }
                            });
                        },
                        
                    }
                });
                $('#cgst_sgst_proforma_grid_draftbtn').click(function (){
                    swalWithBootstrapButtons.fire(
                        {
                            title: 'Do you want to save the draft details ?',
                            type: 'warning',
                            showCancelButton: true,
                            scrollbarPadding: false,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            reverseButtons: true,
                            width:460,
                            customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                        }
                    ).then(function(result) {
                        if (result.value) {
                            k.submitData();
                        }else if (result.dismiss === Swal.DismissReason.cancel) {
                        }
                    });
                });
                $('#cgst_sgst_proforma_grid_btn').click(function (){
                        let validate = 0;
                        let data = k.getData();
                        var validity=$('#invoice_validity').val();
                        var terms=$('#terms_and_condition').val();
                        var subscription_period=$('#subscription_period').val();
                       
                        validate = validateFiled('cgst_sgst_proforma_grid', data);
                        
                    if(validate == 0 && validity!='' && terms!='' && subscription_period!=''){
                    swalWithBootstrapButtons.fire(
                        {
                            title: 'Do you want to save the details ?',
                            type: 'warning',
                            showCancelButton: true,
                            scrollbarPadding: false,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            reverseButtons: true,
                             width:460,
                            customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                        }
                    ).then(function(result) {
                        if (result.value) {
                            k.submitsaveData();
                        }
                        // commented by me on 20/03/23
                        else if (result.dismiss === Swal.DismissReason.cancel) {
                            // commented by myself regards to retain last state 
                            // WithinStateGrid(enquiry_id)
                            
                        }
                    });
                }else {
                           var msg="";
                            if(validity==''){
                                msg="Please fill Prof. Invoice. Validity to continue.";
                            }else if(subscription_period==''){
                                msg="Please fill Subscription Period to continue.";
                            }else if(validate!=0 ){
                               msg="Please fill all the fields to continue."; 
                            }else if(terms=='' ){
                                msg="Please fill Terms & Condition to continue.";
                            }
                            swalWithBootstrapButtons.fire({
                                title: 'Warning',
                                text:msg,
                                icon: 'warning',
                                width:460,
                                confirmButtonText: 'OK',
                                customClass: {
                                    'confirmButton': 'btn btn-info',
                                    'title':'swal2-titles',
                                    'text':'swal2-texts'
                                }
                            })
                      }
                });    
            
        });
        }else{
         $("#igst_proforma_grid").html("");
         MakeAsynPostRequest(base_path + "badmin/mreqrcved/getquotedetails_igst", "enquiry_id=" + enquiry_id + "&proforma_id=" + GlbId + "&proformatype=" + proformatype + "&invotype=" + checkboxval, "json", function(data) {
        let min_dimensions = data.column.length;
        let options = {
                data: data.data,
                editable:false,
                columns: data.column,
                minDimensions: [min_dimensions, 1],
                allowDeleteColumn: false,
                allowInsertRow: false,
                allowInsertColumn: false,
                footers:[['', '', '','','', 'Total : ','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())']],
                updateTable: function (instance, cell, col, row, val, label, cellName) { 
                    
                    if(col === 2)
                                    { 
                                        var txtValue = numeral(val).format('0.00');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        unit_rate = txtValue;
                                    }
                                    if(col === 3)
                                    {   
                                        var txtValue = numeral(val).format('0');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        qty = txtValue;
                                    }
                                    if(col === 4)
                                    {   
                                        amount = parseFloat(unit_rate) * parseFloat(qty);
                                        amount = numeral(amount).format('0.00');
                                       // amount  = (amount > 0) ? amount : '';
                                        $(cell).text(amount);
                                        instance.jexcel.options.data[row][col] = amount;
                                    }
                                    if(col === 5)
                                    {   
                                        igst_percent =  val;
                                        igst_percent = numeral(igst_percent).format('0.00');
                                        igst_percent  = (igst_percent > 0) ? igst_percent : '';
                                        $(cell).text(igst_percent);
                                        instance.jexcel.options.data[row][col] = igst_percent;
                                    }
                                    if(col === 6)
                                    {   
                                        igst_amount = (igst_percent *(parseInt(amount)))/100;
                                        igst_amount = numeral(igst_amount).format('0.00');
                                        //igst_amount  = (igst_amount > 0) ? igst_amount : '';
                                        $(cell).text(igst_amount);
                                        instance.jexcel.options.data[row][col] = igst_amount;
                                    }
                                    if(col === 7)
                                    {   
                                        subtot = parseFloat(amount) + parseFloat(igst_amount);
                                        subtot = numeral(subtot).format('0.00');
                                        $(cell).text(subtot);
                                        instance.jexcel.options.data[row][col] = subtot;
                                        
                                    }
                }
            };
            
             let k = new Vue({
                    el: '#igst_proforma_grid',
                    mounted: function () {
                        let spreadsheet = jspreadsheet(this.$el, options);
                        Object.assign(this, spreadsheet);
                    },
                    methods: {
                        // submitData: function () {
                        //     let data = this.getData();
                        //     var validity=$('#invoice_validity').val();
                        //     var terms=$('#terms_and_condition').val();
                        //     var subscription_period=$('#subscription_period').val();
                            
                        //     MakeAsynPostRequest(base_path + GlbBAdminFdr + "mreqrcved/updateproformaInfo",
                        //     "rfrom=1&draftstatus=1&invotype=" + checkboxval + "&validity=" + validity + "&terms=" + terms +  "&subscription_period=" + subscription_period +
                        //     "&subsciber_id=" + GlbsubscriberId + "&id=" + GlbId + "&object=" + JSON.stringify(data), 
                        //     "json",function (data) {
                        //             if (data != '') {
                        //                 if (data.errcode == '404') {
                        //                 fnCallSessionExpire();
                        //                 return false;
                        //             } else if (data.errcode == -1) {
                        //                 swalWithBootstrapButtons.fire({
                        //                     title: data.msg,type: 'warning',
                        //                     icon: 'warning',
                        //                     customClass: {'confirmButton': 'btn btn-info'}
                        //                 });
                        //                 return false;
                        //             } else if (data.errcode == 1) {
                        //                     swalWithBootstrapButtons.fire({
                        //                                 title: 'Saved!',type: 'success',
                        //                                 icon: 'success',
                        //                                 customClass: {'confirmButton': 'btn btn-info'}
                        //                     }).then((result) => {
                        //                         let redirectpath = base_path + GlbBAdminFdr + 'mreqrcved/addedit/'+ encodeURIComponent(base64_encode(lasturi)) + '/edit';
                        //                         window.location.href = redirectpath;
                        //                     });
                                        
                        //             }
                        //         }
                        //     });
                        // },
                        // submitsaveData: function () {
                        //     let data = this.getData();
                        //     var validity=$('#invoice_validity').val();
                        //     var terms=$('#terms_and_condition').val();
                        //     var invoice_no=$('#invoice_no').val();
                        //     var subscription_period=$('#subscription_period').val();
                            
                        //     MakeAsynPostRequest(base_path + GlbBAdminFdr + "mreqrcved/updateproformaInfo",
                        //     "rfrom=1&draftstatus=2&invotype=" + checkboxval + "&validity=" + validity + "&terms=" + terms + "&invoice_no=" + invoice_no +  "&subscription_period=" + subscription_period +
                        //     "&subsciber_id=" + GlbsubscriberId + "&id=" + GlbId + "&object=" + JSON.stringify(data), 
                        //     "json",function (data) {
                        //             if (data != '') {
                        //                 if (data.errcode == '404') {
                        //                 fnCallSessionExpire();
                        //                 return false;
                        //             } else if (data.errcode == -1) {
                        //                 swalWithBootstrapButtons.fire({
                        //                     title: data.msg,type: 'warning',
                        //                     icon: 'warning',
                        //                     customClass: {'confirmButton': 'btn btn-info'}
                        //                 });
                        //                 return false;
                        //             } else if (data.errcode == 1) {
                        //                     swalWithBootstrapButtons.fire({
                        //                                 title: 'Saved!',type: 'success',
                        //                                 icon: 'success',
                        //                                 customClass: {'confirmButton': 'btn btn-info'}
                        //                     }).then((result) => {
                        //                         let redirectpath = base_path + GlbBAdminFdr + 'mreqrcved/manage/';
                        //                         window.location.href = redirectpath;
                        //                     });
                                        
                        //             }
                        //         }
                        //     });
                        // },
                    }
                });
                
                // $('#igst_proforma_grid_draftbtns').click(function (){
                //     swalWithBootstrapButtons.fire(
                //         {
                //             title: 'Do you want to save the draft details ?',
                //             type: 'warning',
                //             showCancelButton: true,
                //             scrollbarPadding: false,
                //             confirmButtonText: 'Yes',
                //             cancelButtonText: 'No',
                //             reverseButtons: true,
                //             width:460,
                //             customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                //         }
                //     ).then(function(result) {
                //         if (result.value) {
                //             k.submitData();
                //         }else if (result.dismiss === Swal.DismissReason.cancel) {
                         
                //         }
                //     });
               
                // });
                // $('#igst_proforma_grid_btn').click(function (){
                //         let validate = 0;
                //         let data = k.getData();
                //         validate = validateFiled('igst_proforma_grid', data);
                //         var validity=$('#invoice_validity').val();
                //         var terms=$('#terms_and_condition').val();
                //         var subscription_period=$('#subscription_period').val();
                       
                //     if(validate == 0 && validity!='' && terms!='' && subscription_period!=''){
                //     swalWithBootstrapButtons.fire(
                //         {
                //             title: 'Do you want to save the details ?',
                //             type: 'warning',
                //             showCancelButton: true,
                //             scrollbarPadding: false,
                //             confirmButtonText: 'Yes',
                //             cancelButtonText: 'No',
                //             reverseButtons: true,
                //              width:460,
                //             customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                //         }
                //     ).then(function(result) {
                //         if (result.value) {
                //             k.submitsaveData();
                //         }
                //         // commented by me on 20/03/23
                //         else if (result.dismiss === Swal.DismissReason.cancel) {
                //             // commented by myself regards to retain last state 
                //             // WithinStateGrid(enquiry_id)
                            
                //         }
                //     });
                // }else {
                //             var msg="";
                //             if(validity==''){
                //                 msg="Please fill Prof. Invoice. Validity to continue.";
                //             }else if(subscription_period==''){
                //                 msg="Please fill Subscription Period to continue.";
                //             }else if(validate!=0 ){
                //               msg="Please fill all the fields to continue."; 
                //             }else if(terms=='' ){
                //                 msg="Please fill Terms & Condition to continue.";
                //             }
                //             swalWithBootstrapButtons.fire({
                //                 title: 'Warning',
                //                 text:msg,
                //                 icon: 'warning',
                //                 width:460,
                //                 confirmButtonText: 'OK',
                //                 customClass: {
                //                     'confirmButton': 'btn btn-info',
                //                     'title':'swal2-titles',
                //                     'text':'swal2-texts'
                //                 }
                //             })
                //       }
                // });    
        });
        }
    }
</script>
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFBADMIN ?>raiseproforma.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>