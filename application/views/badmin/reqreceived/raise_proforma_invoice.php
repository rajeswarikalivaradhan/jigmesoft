<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); 
$ArrProfileInfo = fnGetUserLoggedInfo(1);?>
    <!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
   <style>
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
.swal2-texts{
    font-size:18px!important;
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
                        <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px">Raise Proforma Invoice
                          <div class="pull-right">
                                            <input type="hidden" id="subscriber_id" value="">
                                            <input type="hidden" id="no_of_additional_users" value="<?php if (!empty($BasicInfo->additional_users)) echo $BasicInfo->additional_users; ?>">
                                            <div class="ml-auto pr-3">
                                             <a href="javascript:void(null)" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
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
                            <div class="col-md-2">
                                <div class="form-group">
                                        <label for="id-form-field-focus-1" class="col-sm-6 control-label labelclr">
                                           Within State
                                        </label>
                                    <div class="col-sm-2">
                                       <input type="checkbox"  class="cus-radio-btn" id="withinstate" name="title" value="within" <?php if (!empty($draftdata) && ($draftdata->invoice_type=='within')) { echo 'checked'; }?> <?php if(isset($checkDraftorNot) && ($checkDraftorNot > 0)) { echo 'disabled';}?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                        <label for="id-form-field-focus-1" class="col-sm-6 control-label labelclr">
                                         Inter State
                                        </label>
                                    <div class="col-sm-2">
                                       <input type="checkbox" class="cus-radio-btn" name="title" value="inter" <?php if (!empty($draftdata) && ($draftdata->invoice_type=='inter')) { echo 'checked'; }?> <?php if(isset($checkDraftorNot) && ($checkDraftorNot > 0)) { echo 'disabled';}?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                        <label for="id-form-field-focus-1" class="col-sm-6 control-label labelclr">
                                         Export
                                        </label>
                                    <div class="col-sm-2">
                                       <input type="checkbox" class="cus-radio-btn" name="title" value="export" <?php if (!empty($draftdata) && ($draftdata->invoice_type=='export')) { echo 'checked'; }?> <?php if(isset($checkDraftorNot) && ($checkDraftorNot > 0)) { echo 'disabled';}?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" >
                         <div class="col-md-12 py-3 px-0" style="border:1px solid #ddd;margin-bottom:0px">
                            <div class="col-md-7"><p><b>Jigme Soft Solutions Private Limited </b></p></div>
                            <div class="col-md-5 text-right"><p>No.88, Block - B, Bose Garden Layout, Saravanampatti, Coimbatore - 641035.</p>
                            <p>Mobile: 9943931113, e-mail: jigmesoft@gmail.com, </p>                                                                                                                                                                                                                
                            <p>GST No:33AAFCJ2474F1ZR </p></div>
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
                                             <?php $addr=(!empty($BasicInfo->address)) ?$BasicInfo->address:'';
                                                $city= (!empty($addr && $BasicInfo->city)) ?','.$BasicInfo->city:''; 
                                                $state= (!empty($city && $BasicInfo->state))? ','.$BasicInfo->state:''; 
                                                $country=(!empty($state && $BasicInfo->country))?','.$BasicInfo->country:''; 
                                                $pincode=(!empty($country && $BasicInfo->pincode))? ', Pincode:'.$BasicInfo->pincode:''; 
                                                $addressstr=$addr.$city.$state.$country.$pincode; ?>
                                            <tr>
                                                <td class="process-title">Name</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo->companyname)) echo $BasicInfo->companyname; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Address</td>
                                                <td class="process-value" <?php if (!empty($addressstr)  && (strlen($addressstr)>=50)) { ?> style="padding:6px 6px 30px 6px!important;"  <?php } else { ?> style="padding:6px 6px 98px 6px!important;" <?php } ?>>
                                                <?php 
                                                
                                                if (!empty($addressstr)) { echo wordwrap($addressstr, 50, "<br/>"); } ?></td>
                                            </tr>
                                            <!--<tr>-->
                                            <!--    <td class="process-title"></td>-->
                                            <!--    <td class="process-value" style="padding: 16px 10px!important;"><?php if (!empty($BasicInfo->city)) echo $BasicInfo->city; ?>,<?php if (!empty($BasicInfo->state)) echo $BasicInfo->state; ?>,<?php if (!empty($BasicInfo->country)) echo $BasicInfo->country; ?></td>-->
                                            <!--</tr>-->
                                            <!--<tr>-->
                                            <!--    <td class="process-title"></td>-->
                                            <!--    <td class="process-value"><?php if (!empty($BasicInfo->pincode)) echo 'Pincode:'.$BasicInfo->pincode; ?></td>-->
                                            <!--</tr>-->
                                            <tr>
                                                <td class="process-title">GST No</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo->gst_no)) echo $BasicInfo->gst_no; ?></td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </td>
                                    
                                    <td class="ord-procs-cell">
                                        <table class="table tbl-procs-border">
                                        <tbody>
                                            <tr>
                                                <td class="process-title">Prof. Invoice Ref. No</td>
                                                <td class="process-value"><input type="hidden" id="invoice_no" value="<?php if (!empty($invoicerefno)) echo $invoicerefno; ?>"></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Date</td>
                                                <td class="process-value"></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Prof. Invoice Validity</td>
                                                <td class="process-value"><input type="text" class="form-control" id="invoice_validity" value="<?php if (!empty($draftdata)) echo $draftdata->invoice_validity; ?>"></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Subscription Period</td>
                                                <td class="process-value">
                                                <select class="cus-sel form-control js-example-basic-single" id="subscription_period">
                                                <option value="" >Select</option>
                                                <?php
                                                $subscription_period = unserialize(ARRSUBSCRIPTIONPERIOD);
                                                foreach ($subscription_period as $key => $item)
                                                {
                                                    ?>
                                                    <option value="<?php echo $key ?>" <?php if (@$draftdata->subscription_period == $key) { echo "selected";} ?>>
                                                        <?php echo $item ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select> </td>
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
                            
                            <div class="card-footer clearfix bgc-white border-0 p-2">
                                <div class="col-xs-12" style="padding-right:0px">
                                <div id="scgstbtn_grid">
                                    <button class="btn btn-info pull-right mx-2" id="cgst_sgst_proforma_grid_btn" >Save</button>
                                    <div id="scgst_savedraft"><button class="btn btn-royal-blue pull-right mx-2" id="cgst_sgst_proforma_grid_draftbtn" >Save as Draft</button></div>
                                </div>
                                <div id="igstbtn_grid">
                                 <button class="btn btn-info pull-right mx-2" id="igst_proforma_grid_btn" >Save</button>
                                 <div id="igst_savedraft"><button class="btn btn-royal-blue pull-right mx-2" id="igst_proforma_grid_draftbtns" >Save as Draft</button></div>
    
                                </div>
                                <?php if($checkDraftorNot > 0) { ?>
                                <div id="cleardraft"><button class="btn btn-royal-blue pull-right mx-2" onclick="fncleardraft('<?php  echo $VarId; ?>','<?php  echo base64_encode($SubscriberId); ?>')">Clear Draft</button></div>
                                <?php } ?>
                            </div>
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
                                  <?php echo $amount ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 p-1">
                            <div class="form-group mb-0">
                                    <label for="id-form-field-focus-1" class="col-sm-3  labelclr">
                                     Terms & Conditions :
                                    </label>
                                <div class="col-sm-9" style="padding:0px">
                                   <input type="text" class="form-control" id="terms_and_condition" value="<?php if (!empty($draftdata)) echo $draftdata->terms_and_condition; ?>">
                                </div>
                            </div>
                        </div>
                        <!--<p>Terms & Conditions:<input type="text" class="form-control" id="terms_and_condition"></p>-->
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                    <div class="col-md-12 pl-2">
                        <div class="col-md-9"></div>
                        <div class="col-md-3"><p style="text-align:right">For <b>Jigme Soft Solutions Private Limited</b></p></div>
                    </div>
                    <div class="col-md-12 pl-2">
                        <div class="col-md-9"></div>
                        <div class="col-md-3"><p style="padding:1rem 0 1rem!important;text-align:right"></p><p style="padding-bottom:1rem!important;text-align:right">Authorized Signatory</p></div>
                    </div>
                </div>
                <div class="row hidden-print">
                    <div class="col-xs-12" style="padding:25px 0px 25px 0px">
                        
                    </div><!-- /.col -->
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
    var draftdata='<?php if (!empty($draftdata)) { echo $draftdata->invoice_type; } else { echo '';}?>';
   
     $(function (){
         if(draftdata==''){
             $("#withinstate").prop('checked', true);
         }
         $('input[type=checkbox][name=title]:checked').each(function() {
          if($(this).val()=='within'){
            $("#cgst_sgst_proforma_grid").show();
             $("#igst_proforma_grid").hide();
            // Cgst_Sgst_Grid(GlbsubscriberId);
             $("#scgstbtn_grid").show();
             $("#igstbtn_grid").hide();
             Cgst_Sgst_Grid_Draft(GlbsubscriberId);
          }else{
            $("#cgst_sgst_proforma_grid").hide();
             $("#igst_proforma_grid").show();
            // Cgst_Sgst_Grid(GlbsubscriberId);
             $("#scgstbtn_grid").hide();
             $("#igstbtn_grid").show();
             Cgst_Sgst_Grid_Draft(GlbsubscriberId);  
          }
        });
     });
     
      $('input[type=checkbox][name=title]').change(function() {
      
        if(($(this).prop('checked')==true) && ($(this).val()=='within')){
            
              $("#cgst_sgst_proforma_grid").html('');
            Cgst_Sgst_Grid_Draft(GlbsubscriberId);
            $("#igst_proforma_grid").hide();
            $("#cgst_sgst_proforma_grid").show();
            $("#scgstbtn_grid").show();
            $("#igstbtn_grid").hide();
        }else if(($(this).prop('checked')==true) && ($(this).val()=='inter' || ($(this).val()=='export'))){
             $("#igst_proforma_grid").html('');
             
             //IgstGrid(GlbsubscriberId);
              Cgst_Sgst_Grid_Draft(GlbsubscriberId);
             $("#igst_proforma_grid").show();
             $("#igstbtn_grid").show();
             $("#cgst_sgst_proforma_grid").hide();
             $("#scgstbtn_grid").hide();
             $("#withinstate").prop('checked', false);
        }else if(($(this).prop('checked')==false) && (($(this).val()=='within') || $(this).val()=='inter' || $(this).val()=='export')){
             $("#withinstate").prop('checked', true);
              $("#cgst_sgst_proforma_grid").html('');
            Cgst_Sgst_Grid_Draft(GlbsubscriberId);
            $("#igst_proforma_grid").hide();
            $("#cgst_sgst_proforma_grid").show();
            $("#scgstbtn_grid").show();
            $("#igstbtn_grid").hide();
        }else{
            
             $("#cgst_sgst_proforma_grid").html('');
              $("#igst_proforma_grid").html('');
            // $("#igst_proforma_grid").hide();
            // $("#igstbtn_grid").hide();
            // $("#withinstate").prop('checked', true);
            // $("#cgst_sgst_proforma_grid").show();
            // Cgst_Sgst_Grid_Draft(GlbsubscriberId);
            // Cgst_Sgst_Grid(GlbsubscriberId);
            // IgstGrid(GlbsubscriberId);
           // $("#scgstbtn_grid").show();
            //$("#cgst_sgst_proforma_grid").hide();
        }
     });

    // $('input[type=checkbox][name=title]').click(function() {
        
    //      $("#cgst_sgst_proforma_grid").html('');
    //      $("#igst_proforma_grid").html('');
    //     if(($(this).prop('checked')==true) && ($(this).val()=='within')){
    //         Cgst_Sgst_Grid_Draft(GlbsubscriberId);
    //         $("#igst_proforma_grid").hide();
    //         $("#cgst_sgst_proforma_grid").show();
    //         $("#scgstbtn_grid").show();
    //         $("#igstbtn_grid").hide();
    //     }else if(($(this).prop('checked')==true) && ($(this).val()=='inter' || ($(this).val()=='export'))){
    //          //IgstGrid(GlbsubscriberId);
    //           Cgst_Sgst_Grid_Draft(GlbsubscriberId);
    //          $("#igst_proforma_grid").show();
    //          $("#igstbtn_grid").show();
    //          $("#cgst_sgst_proforma_grid").hide();
    //          $("#scgstbtn_grid").hide();
    //          $("#withinstate").prop('checked', false);
    //     }else{
    //         $("#igst_proforma_grid").hide();
    //         $("#igstbtn_grid").hide();
    //         $("#withinstate").prop('checked', true);
    //         $("#cgst_sgst_proforma_grid").show();
    //         Cgst_Sgst_Grid_Draft(GlbsubscriberId);
    //         // Cgst_Sgst_Grid(GlbsubscriberId);
    //         // IgstGrid(GlbsubscriberId);
    //         $("#scgstbtn_grid").show();
    //         //$("#cgst_sgst_proforma_grid").hide();
    //     }
    // });
    
    $('#backbtn').on('click', function() {
    let redirectpath = base_path + GlbBAdminFdr + 'mreqrcved/addedit/'+ encodeURIComponent(base64_encode(lasturi)) + '/edit';
                 window.location.href = redirectpath;
    });
    $('input:checkbox').click(function() {
    $('input:checkbox').not(this).prop('checked', false);
});    
//  $('#cgst_sgst_proforma_grid_btn').click(function (){
//          if($('input:checkbox').prop('checked')==false){
//                           alert('Please select any checkbox to continue.');
//                           document.getElementById('cus-radio-btnblk').scrollIntoView();  
//         }else{
//             Cgst_Sgst_Grid('1');
//         }
                            
//     });
 var SUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        total = numeral(total).format('0.00');
//        total  = parseFloat(total).toFixed(2);
        total = (total > 0) ? total : ''
        return total;
    }
    // function IgstGrid(enquiry_id)
    // {
    //     $("#igst_proforma_grid").html("");
    //     MakeAsynPostRequest(base_path + "badmin/mreqrcved/getquotedetails_igst", "enquiry_id=" + enquiry_id, "json", function(data) {
    //         console.log(data.data);
    //     let min_dimensions = data.column.length;
    //     let options = {
    //             data: data.data,
    //             editable:true,
    //             columns: data.column,
    //             minDimensions: [min_dimensions, 1],
    //             allowDeleteColumn: false,
    //             allowInsertRow: true,
    //             allowInsertColumn: false,
    //             footers:[['', '', '','','', 'Total : ','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())']],
    //             updateTable: function (instance, cell, col, row, val, label, cellName) { 
                    
    //                 if(col === 2)
    //                                 { 
    //                                     var txtValue = numeral(val).format('0.00');
    //                                     txtValue  = (txtValue > 0) ? txtValue : '';
    //                                     $(cell).text(txtValue);
    //                                     instance.jexcel.options.data[row][col] = txtValue;
    //                                     unit_rate = txtValue;
    //                                 }
    //                                 if(col === 3)
    //                                 {   
    //                                     var txtValue = numeral(val).format('0.00');
    //                                     txtValue  = (txtValue > 0) ? txtValue : '';
    //                                     $(cell).text(txtValue);
    //                                     instance.jexcel.options.data[row][col] = txtValue;
    //                                     qty = txtValue;
    //                                 }
    //                                 if(col === 4)
    //                                 {   
    //                                     amount = parseFloat(unit_rate) * parseFloat(qty);
    //                                     amount = numeral(amount).format('0.00');
    //                                   // amount  = (amount > 0) ? amount : '';
    //                                     $(cell).text(amount);
    //                                     instance.jexcel.options.data[row][col] = amount;
    //                                 }
    //                                 if(col === 5)
    //                                 {   
    //                                     igst_percent =  val;
    //                                     igst_percent = numeral(igst_percent).format('0');
    //                                     igst_percent  = (igst_percent > 0) ? igst_percent : '';
    //                                     $(cell).text(igst_percent);
    //                                     instance.jexcel.options.data[row][col] = igst_percent;
    //                                 }
    //                                 if(col === 6)
    //                                 {   
    //                                     igst_amount = (igst_percent *(parseInt(amount)))/100;
    //                                     igst_amount = numeral(igst_amount).format('0.00');
    //                                     //igst_amount  = (igst_amount > 0) ? igst_amount : '';
    //                                     $(cell).text(igst_amount);
    //                                     instance.jexcel.options.data[row][col] = igst_amount;
    //                                 }
    //                                 if(col === 7)
    //                                 {   
    //                                     subtot = parseFloat(amount) + parseFloat(igst_amount);
    //                                     subtot = numeral(subtot).format('0.00');
    //                                     $(cell).text(subtot);
    //                                     instance.jexcel.options.data[row][col] = subtot;
                                        
    //                                 }
    //             }
    //         };
            
    //          let k = new Vue({
    //                 el: '#igst_proforma_grid',
    //                 mounted: function () {
    //                     let spreadsheet = jspreadsheet(this.$el, options);
    //                     Object.assign(this, spreadsheet);
    //                 },
    //                 methods: {
    //                     submitData: function () {
    //                         let data = this.getData();
    //                       // console.log(data);
    //                         MakeAsynPostRequest(base_path + "preCosting/updateActualCost", "enquiry_id=" + enquiry_id + "&object=" + JSON.stringify(data), "json", function (data) {
    //                         swalWithBootstrapButtons.fire({title: 'Saved!',type: 'success',icon: 'success',width:460,customClass: {'confirmButton': 'btn btn-info'}}).then(function(result){
    //                          <?php if(isset($requestFor) && $requestFor == 1) {  ?>
    //                             isrCostGrid(enquiry_id);
    //                             <?php } else { ?>
    //                             iorCostGrid(enquiry_id);
    //                             <?php } ?>
    //                         });
    //                         });
    //                     },
    //                 }
    //             });
                
    //             $('#igst_proforma_grid_btn').click(function (){
    //                     let validate = 0;
    //                     let data = k.getData();
    //                     validate = validateFiled('igst_proforma_grid', data);
    //             if(validate == 0){
    //                 swalWithBootstrapButtons.fire(
    //                     {
    //                         title: 'Do you want to save the details ?',
    //                         type: 'warning',
    //                         showCancelButton: true,
    //                         scrollbarPadding: false,
    //                         confirmButtonText: 'Yes',
    //                         cancelButtonText: 'No',
    //                         reverseButtons: true,
    //                         customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
    //                     }
    //                 ).then(function(result) {
    //                     if (result.value) {
    //                         k.submitData();
    //                     }
    //                     // commented by me on 20/03/23
    //                     else if (result.dismiss === Swal.DismissReason.cancel) {
    //                         // commented by myself regards to retain last state 
    //                         // WithinStateGrid(enquiry_id)
                            
    //                     }
    //                 });
    //             }else {
    //                         swalWithBootstrapButtons.fire({
    //                             title: 'Warning',
    //                             text:"Please fill all the fields to continue.",
    //                             icon: 'warning',
    //                             width:460,
    //                             confirmButtonText: 'OK',
    //                             customClass: {
    //                                 'confirmButton': 'btn btn-info',
    //                                 'title':'swal2-titles'
    //                             }
    //                         })
    //                   }
    //             });
    //     });
        
    // }
   
    // function Cgst_Sgst_Grid(enquiry_id)
    // {
    //     $("#cgst_sgst_proforma_grid").html("");
    //     MakeAsynPostRequest(base_path + "badmin/mreqrcved/getquotedetails_cgst_sgst", "enquiry_id=" + enquiry_id, "json", function(data) {
    //         console.log(data.data);
    //     let min_dimensions = data.column.length;
    //     let options = {
    //             data: data.data,
    //             editable:true,
    //             columns: data.column,
    //             minDimensions: [min_dimensions, 1],
    //             allowDeleteColumn: false,
    //             allowInsertRow: true,
    //             allowInsertColumn: false,
    //             footers:[['', '', '','','','', 'Total : ','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())']],
    //             updateTable: function (instance, cell, col, row, val, label, cellName) { 
                    
    //                 if(col === 2)
    //                                 { 
    //                                     var txtValue = numeral(val).format('0.00');
    //                                     txtValue  = (txtValue > 0) ? txtValue : '';
    //                                     $(cell).text(txtValue);
    //                                     instance.jexcel.options.data[row][col] = txtValue;
    //                                     unit_rate = txtValue;
    //                                 }
    //                                 if(col === 3)
    //                                 {   
    //                                     var txtValue = numeral(val).format('0.00');
    //                                     txtValue  = (txtValue > 0) ? txtValue : '';
    //                                     $(cell).text(txtValue);
    //                                     instance.jexcel.options.data[row][col] = txtValue;
    //                                     qty = txtValue;
    //                                 }
    //                                 if(col === 4)
    //                                 {   
    //                                     amount = parseFloat(unit_rate) * parseFloat(qty);
    //                                     amount = numeral(amount).format('0.00');
    //                                   // amount  = (amount > 0) ? amount : '';
    //                                     $(cell).text(amount);
    //                                     instance.jexcel.options.data[row][col] = amount;
    //                                 }
    //                                 if(col === 5)
    //                                 {   
    //                                     sgst_percent =  val;
    //                                     sgst_percent = numeral(sgst_percent).format('0');
    //                                     sgst_percent  = (sgst_percent > 0) ? sgst_percent : '';
    //                                     $(cell).text(sgst_percent);
    //                                     instance.jexcel.options.data[row][col] = sgst_percent;
    //                                 }
    //                                 if(col === 6)
    //                                 {   
    //                                     cgst_percent =  val;
    //                                     cgst_percent = numeral(cgst_percent).format('0');
    //                                     cgst_percent  = (cgst_percent > 0) ? cgst_percent : '';
    //                                     $(cell).text(cgst_percent);
    //                                     instance.jexcel.options.data[row][col] = cgst_percent;
    //                                 }
    //                                 if(col === 7)
    //                                 {   
    //                                     sgst_amount = (sgst_percent *(parseInt(amount)))/100;
    //                                     sgst_amount = numeral(sgst_amount).format('0.00');
    //                                     //sgst_amount  = (sgst_amount > 0) ? sgst_amount : '';
    //                                     $(cell).text(sgst_amount);
    //                                     instance.jexcel.options.data[row][col] = sgst_amount;
    //                                 }
    //                                 if(col === 8)
    //                                 {   
    //                                     cgst_amount = (cgst_percent *(parseInt(amount)))/100;
    //                                     cgst_amount = numeral(cgst_amount).format('0.00');
    //                                     //cgst_amount  = (cgst_amount > 0) ? cgst_amount : '';
    //                                     $(cell).text(cgst_amount);
    //                                     instance.jexcel.options.data[row][col] = cgst_amount;
    //                                 }
    //                                 if(col === 9)
    //                                 {   
    //                                     subtot = parseFloat(amount) + parseFloat(sgst_amount) + parseFloat(cgst_amount);
    //                                     subtot = numeral(subtot).format('0.00');
    //                                     $(cell).text(subtot);
    //                                     instance.jexcel.options.data[row][col] = subtot;
                                        
    //                                 }
    //             }
    //         };
            
    //          let k = new Vue({
    //                 el: '#cgst_sgst_proforma_grid',
    //                 mounted: function () {
    //                     let spreadsheet = jspreadsheet(this.$el, options);
    //                     Object.assign(this, spreadsheet);
    //                 },
    //                 methods: {
    //                     submitData: function () {
    //                         let validate = 0;
    //                         let data = this.getData();
    //                         validate = validateFiled('cgst_sgst_proforma_grid', data);
    //                         alert(validate);
    //                       // console.log(data);
    //                         MakeAsynPostRequest(base_path + "preCosting/updateActualCost", "enquiry_id=" + enquiry_id + "&object=" + JSON.stringify(data), "json", function (data) {
    //                         swalWithBootstrapButtons.fire({title: 'Saved!',type: 'success',icon: 'success',width:460,customClass: {'confirmButton': 'btn btn-info'}}).then(function(result){
    //                          <?php if(isset($requestFor) && $requestFor == 1) {  ?>
    //                             isrCostGrid(enquiry_id);
    //                             <?php } else { ?>
    //                             iorCostGrid(enquiry_id);
    //                             <?php } ?>
    //                         });
    //                         });
    //                     },
    //                 }
    //             });
                
    //             $('#cgst_sgst_proforma_grid_btn').click(function (){
    //                     let validate = 0;
    //                     let data = k.getData();
    //                     validate = validateFiled('cgst_sgst_proforma_grid', data);
    //             if(validate == 0){
    //                 swalWithBootstrapButtons.fire(
    //                     {
    //                         title: 'Do you want to save the details ?',
    //                         type: 'warning',
    //                         showCancelButton: true,
    //                         scrollbarPadding: false,
    //                         confirmButtonText: 'Yes',
    //                         cancelButtonText: 'No',
    //                         reverseButtons: true,
    //                         customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
    //                     }
    //                 ).then(function(result) {
    //                     if (result.value) {
    //                         k.submitData();
    //                     }
    //                     // commented by me on 20/03/23
    //                     else if (result.dismiss === Swal.DismissReason.cancel) {
    //                         // commented by myself regards to retain last state 
    //                         // WithinStateGrid(enquiry_id)
                            
    //                     }
    //                 });
    //             }else {
    //                         swalWithBootstrapButtons.fire({
    //                             title: 'Warning',
    //                             text:"Please fill all the fields to continue.",
    //                             icon: 'warning',
    //                             width:460,
    //                             confirmButtonText: 'OK',
    //                             customClass: {
    //                                 'confirmButton': 'btn btn-info',
    //                                 'title':'swal2-titles'
    //                             }
    //                         })
    //                   }
    //             });
    //     });
        
    // }
    
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
        //alert(checkboxval);
        if(checkboxval=='within'){
        $("#cgst_sgst_proforma_grid").html("");
        MakeAsynPostRequest(base_path + "badmin/mreqrcved/getquotedetails_cgst_sgst", "enquiry_id=" + enquiry_id + "&proforma_id=" + '' + "&proformatype=" + 'NPI' + "&invotype=" + checkboxval, "json", function(data) {
         //   console.log("draft" + data.data);
        let min_dimensions = data.column.length;
        let options = {
                data: data.data,
                editable:true,
                columns: data.column,
                minDimensions: [min_dimensions, 1],
                allowDeleteColumn: false,
                allowInsertRow: true,
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
                        var no_of_additional_users=$('#no_of_additional_users').val();
                        let precheck = [];
                        let qtychk=true;
                        let validqty=true;
                        if (data.length > 0) {
                            for (i = 0; i < data.length; i++) {
                                if (data[i][0]) {
                                    precheck[i] = data[i][0];
                                }
                                if(data[i][0]==5){
                                    if(data[i][3]!=no_of_additional_users){
                                       validqty=false; 
                                    }
                                }
                                if(data[i][0]==7 || data[i][0]==8){
                                    // if(data[i][3]!=0 && data[i][3]!=2 && data[i][3]!=4 && data[i][3]!=8 && data[i][3]!=16 && data[i][3]!=32 && data[i][3]!=64 && data[i][3]!=128 && data[i][3]!=256  && data[i][3]!=512 && data[i][3]!=1024){
                                    //  qtychk=false;   
                                    // }
                                    // Assuming data[i][1] contains the index of the selected source item
                                    var columnIndex = 1; // Assuming data[i][1] corresponds to the second column (0-indexed)                                    
                                    var selectedSourceIndex = data[i][1];
                                    // console.log('Selected source selectedSourceIndex:', selectedSourceIndex);
                                    // Access the source name using the selected index
                                    var selectedSourceName = selectedSourceIndex!=''?(options.columns[columnIndex].source[selectedSourceIndex-1].name):'';
                                    
                                    // Now, selectedSourceName will contain the exact name of the selected option
                                    //console.log('Selected source name:', selectedSourceName);
                                    
                                    // Check if the selected source name contains GB related values
                                    var containsGBValues = selectedSourceName.includes('GB');
                                    
                                    // Now, containsGBValues will be true if the selected source name contains GB related values, otherwise false
                                    if (containsGBValues) {
                                        // Do something if it contains GB related values
                                        console.log('Selected source name contains GB related values.');
                                         qtychk=true;   
                                    } else {
                                        // Do something if it doesn't contain GB related values
                                        console.log('Selected source name does not contain GB related values.');
                                         qtychk=false;   
                                    }
                                    }
                                
                            }
                            // if (qtychk == false) {
                            //     swalWithBootstrapButtons.fire({
                            //         title: 'Warning',
                            //         html: "only <b class='swal2-texts'>GB</b> value should be selected in details column..",
                            //         icon: 'warning',
                            //         width: 460,
                            //         confirmButtonText: 'OK',
                            //         customClass: {
                            //             'confirmButton': 'btn btn-info',
                            //             'title': 'swal2-titles'
                            //         }
                            //     })
                            //     return false;
                            // }
                           //alert(validqty);
                        //   console.log(precheck);
                        //   console.log('addnusr'+precheck.includes('5'));
                        //   console.log('addnusrrent'+precheck.includes('6'));
                            const hasDuplicatesResult = hasDuplicates(precheck);
                            if (hasDuplicatesResult == true) {
                                swalWithBootstrapButtons.fire({
                                    title: 'Warning',
                                    html: "Remove duplication in <b class='swal2-texts'>description</b>.",
                                    icon: 'warning',
                                    width: 460,
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        'confirmButton': 'btn btn-info',
                                        'title': 'swal2-titles'
                                    }
                                })
                                return false;
                            }
                        }
                                    
                            validate = validateFiled('cgst_sgst_proforma_grid', data);
                        
                            var msg="";
                            if(validity==''){
                                msg="Please fill Prof. Invoice. Validity to continue.";
                            }else if(subscription_period==''){
                                msg="Please fill Subscription Period to continue.";
                            }else if(validate!=0 ){
                               msg="Please fill all the fields to continue."; 
                            }else if(qtychk == false) {
                                msg = "only <b class='swal2-texts'>GB</b> value should be selected in details column.";
                            }else if ((precheck.includes('5') == false) && (precheck.includes('6') == true)) {
                                msg = "Add <b class='swal2-texts'> additional user </b> in description.";
                            }else if(validqty==false){
                                msg = "Enter additional user <b class='swal2-texts'>quantity</b> value as <b class='swal2-texts'>"+no_of_additional_users+"</b>.";
                            } else if ((precheck.includes('5') == true) && (precheck.includes('6') == false)) {
                                msg = "Add <b class='swal2-texts'> additional user rent</b> in description.";
                            }else if(terms=='' ){
                                msg="Please fill Terms & Condition to continue.";
                            }
                            if (msg != '') {
                            swalWithBootstrapButtons.fire({
                                title: 'Warning',
                                html:msg,
                                icon: 'warning',
                                width:460,
                                confirmButtonText: 'OK',
                                customClass: {
                                    'confirmButton': 'btn btn-info',
                                    'title':'swal2-titles',
                                    'html':'swal2-texts'
                                }
                            });
                            return false;
                            }
                      if(msg==''){
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
                      }
                });    
            
        });
        }else{
         $("#igst_proforma_grid").html("");
         MakeAsynPostRequest(base_path + "badmin/mreqrcved/getquotedetails_igst", "enquiry_id=" + enquiry_id + "&proforma_id=" + '' +"&proformatype=" + 'NPI' + "&invotype=" + checkboxval, "json", function(data) {
        let min_dimensions = data.column.length;
        let options = {
                data: data.data,
                editable:true,
                columns: data.column,
                minDimensions: [min_dimensions, 1],
                allowDeleteColumn: false,
                allowInsertRow: true,
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
                        submitData: function () {
                            let data = this.getData();
                            var validity=$('#invoice_validity').val();
                            var terms=$('#terms_and_condition').val();
                            var subscription_period=$('#subscription_period').val();
                            
                            MakeAsynPostRequest(base_path + GlbBAdminFdr + "mreqrcved/updateproformaInfo",
                            "rfrom=1&draftstatus=1&invotype=" + checkboxval + "&validity=" + validity + "&terms=" + terms +  "&subscription_period=" + subscription_period +
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
                
                $('#igst_proforma_grid_draftbtns').click(function (){
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
                $('#igst_proforma_grid_btn').click(function (){
                        let validate = 0;
                        let data = k.getData();
                        validate = validateFiled('igst_proforma_grid', data);
                        var validity=$('#invoice_validity').val();
                        var terms=$('#terms_and_condition').val();
                        var subscription_period=$('#subscription_period').val();
                        var no_of_additional_users=$('#no_of_additional_users').val();
                        let precheck = [];
                        let qtychk=true;
                        let validqty=true;
                        if (data.length > 0) {
                            for (i = 0; i < data.length; i++) {
                                if (data[i][0]) {
                                    precheck[i] = data[i][0];
                                }
                                if(data[i][0]==5){
                                    if(data[i][3]!=no_of_additional_users){
                                       validqty=false; 
                                    }
                                }
                                if(data[i][0]==7 || data[i][0]==8){
                                    // if(data[i][3]!=0 && data[i][3]!=2 && data[i][3]!=4 && data[i][3]!=8 && data[i][3]!=16 && data[i][3]!=32 && data[i][3]!=64 && data[i][3]!=128 && data[i][3]!=256  && data[i][3]!=512 && data[i][3]!=1024){
                                    //  qtychk=false;   
                                    // }
                                    
                                    // Assuming data[i][1] contains the index of the selected source item
                                    var columnIndex = 1; // Assuming data[i][1] corresponds to the second column (0-indexed)                                    
                                    var selectedSourceIndex = data[i][1];
                                    // console.log('Selected source selectedSourceIndex:', selectedSourceIndex);
                                    // Access the source name using the selected index
                                    var selectedSourceName = selectedSourceIndex!=''?(options.columns[columnIndex].source[selectedSourceIndex-1].name):'';
                                    
                                    // Now, selectedSourceName will contain the exact name of the selected option
                                   // console.log('Selected source name:', selectedSourceName);
                                    
                                    // Check if the selected source name contains GB related values
                                    var containsGBValues = selectedSourceName.includes('GB');
                                    
                                    // Now, containsGBValues will be true if the selected source name contains GB related values, otherwise false
                                    if (containsGBValues) {
                                        // Do something if it contains GB related values
                                        console.log('Selected source name contains GB related values.');
                                         qtychk=true;   
                                    } else {
                                        // Do something if it doesn't contain GB related values
                                        console.log('Selected source name does not contain GB related values.');
                                         qtychk=false;   
                                    }
                                }
                            }
                            // if (qtychk == false) {
                            //     swalWithBootstrapButtons.fire({
                            //         title: 'Warning',
                            //         html: "Please fill quantity value either as <b class='swal2-texts'>0 / 2 / 4/ 8 / 16 / 32 / 64 / 128 / 256 / 512 / 1024</b>.",
                            //         icon: 'warning',
                            //         width: 460,
                            //         confirmButtonText: 'OK',
                            //         customClass: {
                            //             'confirmButton': 'btn btn-info',
                            //             'title': 'swal2-titles'
                            //         }
                            //     })
                            //     return false;
                            // }
                            //console.log(qtychk);
                            const hasDuplicatesResult = hasDuplicates(precheck);
                            if (hasDuplicatesResult == true) {
                                swalWithBootstrapButtons.fire({
                                    title: 'Warning',
                                    html: "Remove duplication in <b class='swal2-texts'>description</b>.",
                                    icon: 'warning',
                                    width: 460,
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        'confirmButton': 'btn btn-info',
                                        'title': 'swal2-titles'
                                    }
                                })
                                return false;
                            }
                        }

                            var msg="";
                            if(validity==''){
                                msg="Please fill Prof. Invoice. Validity to continue.";
                            }else if(subscription_period==''){
                                msg="Please fill Subscription Period to continue.";
                            }else if(validate!=0 ){
                               msg="Please fill all the fields to continue."; 
                            }else if(qtychk == false) {
                                msg = "only <b class='swal2-texts'>GB</b> value should be selected in details column.";
                            }else if ((precheck.includes('5') == false) && (precheck.includes('6') == true)) {
                                msg = "Add <b class='swal2-texts'> additional user </b> in description.";
                            }else if(validqty==false){
                                msg = "Enter additional user <b class='swal2-texts'>quantity</b> value as <b class='swal2-texts'>"+no_of_additional_users+"</b>.";
                            } else if ((precheck.includes('5') == true) && (precheck.includes('6') == false)) {
                                msg = "Add <b class='swal2-texts'> additional user rent</b> in description.";
                            }else if(terms=='' ){
                                msg="Please fill Terms & Condition to continue.";
                            }
                            if (msg != '') {
                                swalWithBootstrapButtons.fire({
                                    title: 'Warning',
                                    html:msg,
                                    icon: 'warning',
                                    width:460,
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        'confirmButton': 'btn btn-info',
                                        'title':'swal2-titles',
                                        'html':'swal2-texts'
                                    }
                                });
                            return false;
                            }
                            
                            if(msg==''){
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
                            }
                      
                });    
        });
        }
    }
    function savedraft(){
         var checkboxval = '';
         $('input[type=checkbox][name=title]:checked').each(function() {
         checkboxval = $(this).val();
        });
        var validity=$('#invoice_validity').val();
        var terms=$('#terms_and_condition').val();
        
        if($("#cgst_sgst_proforma_grid").html().length>0){
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
                            Cgst_Sgst_Grid_Draft(GlbsubscriberId); 
                           
                        }
                    });
        }else if($("#igst_proforma_grid").html().length>0){
            
        }else{
            if(checkboxval!='' || validity!='' || terms!=''){
              swalWithBootstrapButtons.fire(
            {
               // title: 'Are you sure want to save the details ?',
               // text: "If you save You won't be able to revert this!",
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
				     MakeAsynPostRequest(base_path + GlbBAdminFdr + "mreqrcved/updateproformaInfo",
                    "rfrom=1&draftstatus=1&invotype=" + checkboxval + "&validity=" + validity + "&terms=" + terms + "&subsciber_id=" + GlbsubscriberId + "&id=" + GlbId, "json",function (data) {
                        if (data != '') {
                        if (data.errcode == '404') {
                            fnCallSessionExpire();
                            return false;
                        } else if (data.errcode == -1) {
                            //$('#AnyErrElse').text(data.msg);
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
				}
            });   
            }else{
                let redirectpath = base_path + GlbBAdminFdr + 'mreqrcved/manage';
                window.location.href = redirectpath;
            }
            
        }
    }
    function hasDuplicates(arr) { // duplicate checking inan array function
      for (let i = 0; i < arr.length; i++) {
        if (arr.includes(arr[i], i + 1)) {
          return true;
        }
      }
      return false;
    }
</script>
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFBADMIN ?>raiseproforma.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>