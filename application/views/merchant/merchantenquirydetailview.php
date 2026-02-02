<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/uploadfile-order.css" />
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="height:1500px;">
        <!-- Content Header (Page header) -->
        <section class="content-header" style="padding-top: 0">
            <div class="col-md-12">
                <h1 style="font-size: 20px; font-weight: 600">MERCHANT DEPT.</h1>
            </div>
            <div class="col-md-3">
                <div class="box box-solid">
                    <div class="box-body" style="padding: 9px">
                        <div class="col-md-6" style="border-right: 1px solid black; padding-left: 10px; padding-right: 10px">
                            <a href="javascript:void(0);" style="color: #000" onclick="fnShowEnqInfo();">
                                <i id="basicInfoCircle" class="fa fa-circle" style="padding-right: 5px"></i> Basic Information</a>
                        </div>
                        <div class="col-md-4" style="padding-left: 10px; padding-right: 10px">
                            <a href="javascript:void(0);" style="color: #000" onclick="fnShowEnqLog('divLog','divBasicInfo');">
                                <i id="logcircle" class="fa fa-circle-o" style="padding-right: 5px"></i> Logs List</a>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /. box -->
            </div>
            <div class="col-md-2">
                <a class="btn btn-default pull-right" style="font-weight: 600; padding: 7px 12px !important"
                   href="javascript:history.back()">Back
                </a>
            </div>
        </section>
        <!-- Main content -->
        <section class="content" style="height:600px;">
            <div class="row">
                    <div class="col-md-12" id="divBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-bgColor">
                                <h3 class="box-title box-titleFontSize">ENQUIRY DETAILS</h3>
                            </div>
                            <div class="box-body box-bodyPd2010">
                                <form id="frmBasicEnq" name="frmBasicEnq" class="form-horizontal" method="post">
                                    <?php
                                    ?>
                                    <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Order / Enquiry Ref. No.</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="orderEnqRefNo" value="<?php echo $ArrEnquiryInfo[0]->orderenqrefno ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Order / Enquiry Date</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="frmBasicEnquiryDate" name="frmBasicEnquiryDate" class="form-control" placeholder="Enter Enquiry Date"
                                                       value="<?php echo date('d-m-Y',strtotime($ArrEnquiryInfo[0]->enquirydate)) ?>">
                                                <div class="herr" id="ErrfrmBasicEnquiryDate"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Style Ref. No. / Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="frmBasicStyleRefNo" name="frmBasicStyleRefNo" class="form-control" placeholder="Enter Style Ref. No."
                                                       value="<?php echo @$ArrEnquiryInfo[0]->stylenamerefno ?>">
                                                <div class="herr" id="ErrfrmBasicStyleDesc"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Style Description</label>
                                            <div class="col-sm-8">
                                                <textarea id="frmBasicStyleDesc" style="" name="frmBasicStyleDesc" class="form-control" placeholder="Enter Style Description"><?php echo @$ArrEnquiryInfo[0]->styledesc ?></textarea>                                                <div class="herr" id="ErrfrmBasicStyleDesc"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Enquiry Type</label>
                                            <div class="col-sm-8"><?php
                                                    ?>
                                                    <select class="form-control" id="frmBasicEType">
                                                        <option value="">Select Enquiry Type</option>
                                                        <?php
                                                        foreach ($ArrEnquiryType as $item) {
                                                            echo '<option value="'.$item.'" ';
                                                            echo ($item == $ArrEnquiryInfo[0]->enquirytype) ? 'selected' : ''; echo '>'.$item.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="herr" id="ErrfrmBasicEType"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Mode Of Enquiry</label>
                                            <div class="col-sm-8">
                                                <?php
                                                if(empty($ArrModeType)) {
                                                    die('add mode of enquiry');
                                                }
                                                else {
                                                    ?>
                                                    <select class="form-control" id="frmBasicME" name="frmBasicME">
                                                        <option value="">Select Mode Of Enquiry</option>
                                                        <?php
                                                        foreach ($ArrModeType as $item) {
                                                            echo '<option value="'.$item['id'].'" ';
                                                            echo ($item['id'] == $ArrEnquiryInfo[0]->modeofenquiryid) ? 'selected' : ''; echo '>'.$item['name'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="herr" id="frmBasicME"></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Brand</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="frmBasicBrand" name="frmBasicBrand">
                                                    <option value="">Select Brand</option>
                                                    <?php
                                                    foreach ($ArrBrand as $item) {
                                                        echo '<option value="'.$item['id'].'" ';
                                                        echo ($item['id'] == $ArrEnquiryInfo[0]->brandId) ? 'selected' : ''; echo '>'.$item['brandname'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <div class="herr" id="ErrBasicBrand"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Buyer</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="frmBasicBuyer" name="frmBasicBuyer"
                                                        disabled>
                                                    <option value="">Select Buyer</option>
                                                    <?php
                                                    foreach ($ArrBuyer as $item) {
                                                        echo '<option value="'.$item['id'].'" ';
                                                        echo ($item['id'] == $ArrEnquiryInfo[0]->buyerId) ? 'selected' : ''; echo '>'.$item['buyername'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <div class="herr" id="ErrBasicBuyer"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Country</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="frmBasicCountry">
                                                    <option value="">Select Country</option>
                                                    <?php
                                                    foreach ($ArrCountries as $key => $item) {
                                                        echo '<option value="'.$key.'" ';
                                                        echo ($key == $ArrEnquiryInfo[0]->countryid) ? 'selected' : ''; echo '>'.$item.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <div class="herr" id="ErrfrmBasicCountry"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Total Order Qty.</label>
                                            <div class="col-sm-3">
                                                <input id="frmBasicPq" name="frmBasicPq" class="form-control" type="text" placeholder="Enquiry Date" value="<?php echo $ArrEnquiryInfo[0]->exporderqty ?>">
                                                <div class="herr" id="ErrfrmBasicPq"></div>
                                            </div>
                                            <label class="col-sm-2 control-label" style="padding-left: 0 !important;">Pcs. / Set</label>
                                            <div class="col-sm-3" style="padding-left: 0 !important;">
                                                <?php $ArrPcsOrSet = unserialize(ARRPCSSET); ?>
                                                <select class="form-control" id="frmBasicPs">
                                                    <option value="">Select</option>
                                                    <?php
                                                    foreach ($ArrPcsOrSet as $key => $item) {
                                                        echo '<option value="'.$key.'" ';
                                                        echo ($key == $ArrEnquiryInfo[0]->pcsorset) ? 'selected' : ''; echo '>'.$item.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <div class="herr" id="ErrfrmBasicPs"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="frmPriceQuotedFor" class="col-sm-4 control-label">Price Quoted For</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="frmPriceQuotedFor">
                                                    <option value="">Select</option>
                                                    <?php
                                                    $ArrPriceQuotedFor = PRICEQUOTEDFOR;
                                                    foreach($ArrPriceQuotedFor as $key => $item) {
                                                        ?>
                                                        <option value="<?php echo $key ?>" <?php echo @$ArrEnquiryInfo[0]->pricequotedfor == $key ? 'selected' : '' ?>>
                                                            <?php echo $item ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Quoted Price</label>
                                            <div class="col-sm-3">
                                                <input id="frmBasicQprice" name="frmBasicQprice" class="form-control" type="text" value="<?php echo $ArrEnquiryInfo[0]->quotedprice ?>">
                                                <div class="herr" id="ErrfrmBasicQprice"></div>
                                            </div>
                                            <label class="col-sm-2 control-label">Currency</label>
                                            <div class="col-sm-3" style="padding-left: 0 !important;">
                                                <select class="form-control" id="frmBasicCurrency" name="frmBasicCurrency">
                                                    <option value="">Select</option>
                                                    <?php
                                                    foreach ($ArrCurrency as $key => $item) {
                                                        echo '<option value="'.$key.'" ';
                                                        echo ($key == $ArrEnquiryInfo[0]->currency) ? 'selected' : ''; echo '>'.$item.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <div class="herr" id="ErrfrmBasicCurrency"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Buyer's Price</label>
                                            <div class="col-sm-3">
                                                <input id="frmBasicBprice" name="frmBasicBprice" class="form-control" type="text" value="<?php echo $ArrEnquiryInfo[0]->buyerprice ?>">
                                                <div class="herr" id="ErrfrmBasicBprice"></div>
                                            </div>
                                            <label class="col-sm-2 control-label">Currency</label>
                                            <div class="col-sm-3" style="padding-left: 0 !important;">
                                                <select class="form-control" id="frmBasicCurrency" name="frmBasicCurrency">
                                                    <option value="">Select Currency</option>
                                                    <?php
                                                    foreach ($ArrCurrency as $key => $item) {
                                                        echo '<option value="'.$key.'" ';
                                                        echo ($key == $ArrEnquiryInfo[0]->currency) ? 'selected' : ''; echo '>'.$item.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <div class="herr" id="ErrfrmBasicCurrency"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Confirmed Price</label>
                                            <div class="col-sm-3">
                                                <input id="frmBasicCprice" name="frmBasicCprice" class="form-control" type="text" value="<?php echo $ArrEnquiryInfo[0]->confirmprice ?>">
                                                <div class="herr" id="ErrfrmBasicCprice"></div>
                                            </div>
                                            <label class="col-sm-2 control-label">Currency</label>
                                            <div class="col-sm-3" style="padding-left: 0 !important;">
                                                <select class="form-control" id="frmBasicCurrency" name="frmBasicCurrency">
                                                    <option value="">Select Currency</option>
                                                    <?php
                                                    foreach ($ArrCurrency as $key => $item) {
                                                        echo '<option value="'.$key.'" ';
                                                        echo ($key == $ArrEnquiryInfo[0]->currency) ? 'selected' : ''; echo '>'.$item.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <div class="herr" id="ErrfrmBasicCurrency"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Request Type</label>
                                            <div class="col-sm-8">
                                                <?php $ArrIsrIor = unserialize(ARRISRIOR); ?>
                                                <select class="form-control" id="frmBasicRType" name="frmBasicRType">
                                                    <option value="">Request Type</option>
                                                    <?php
                                                    foreach ($ArrIsrIor as $key => $item) {
                                                        echo '<option value="'.$key.'" ';
                                                        echo ($key == $ArrEnquiryInfo[0]->reqforisrior) ? 'selected' : ''; echo '>'.$item.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <div class="herr" id="ErrfrmBasicRType"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Request Date & Time</label>
                                            <div class="col-sm-8">
                                                <span class="form-control" id="frmReqDateTime">
                                                    <?php echo $ArrEnquiryInfo[0]->formattedDateCreated ?>
                                                </span>
                                                <span class="form-control hide" id="frmReqDateTimeCs">

                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Merchant Remarks</label>
                                            <div class="col-sm-8">
                                                <textarea id="frmBasicMnote" style="" name="frmBasicMnote" class="form-control" placeholder="Note"><?php echo @$ArrEnquiryInfo[0]->merchantnote ?></textarea>
                                                <div class="herr" id="ErrfrmBasicMnote"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Management Remarks</label>
                                            <div class="col-sm-8">
                                                <textarea readonly style="" class="form-control"><?php echo @$ArrEnquiryInfo[0]->comments ?></textarea>
                                                <div class="herr" id="ErrfrmBasicStyleDesc"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Current Status</label>
                                            <div class="col-sm-8">
                                            <?php
                                            //echo '<pre>'; print_r($ArrEnquiryInfo); die('die');
                                            ?>
                                            <input type="text" id="divNewStatus" class="form-control <?php if($ArrEnquiryInfo[0]->orderstatus == '4' || $ArrEnquiryInfo[0]->orderstatus == '1')
                                                echo 'alert alert-warning';
                                            if($ArrEnquiryInfo[0]->orderstatus == '3') echo 'alert alert-danger'; ?>" readonly value="<?php echo $ArrOrderStatus[$ArrEnquiryInfo[0]->orderstatus] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Recent Update</label>
                                            <div class="col-sm-8"><span class="form-control" id="recentupdate">
                                                <?php
                                                echo $ArrEnquiryInfo[0]->formattedDateUpdated
                                                ?></span>
                                                <span class="form-control hide" id="recentupdateCs"></span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="box-body">
                                <div class="form-group">
                                    <div class="col-md-12" style="padding-top: 20px">
                                        <label class="control-label">ATTACHMENTS</label>
                                    </div>
                                    <div class="col-sm-5" style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                        <ul style="list-style: none;">
                                            <?php
                                            $VarFdr = UPLOADS_SLASH."orderenquiry".DIRECTORY_SEPARATOR.$VarEnqId.DIRECTORY_SEPARATOR;
                                            $ArrDwnExtensions = DWN_FILE_EXTENSIONS;
                                            if(file_exists($VarFdr)) {
                                                if ($dh = opendir($VarFdr)) {
                                                    while (($file = readdir($dh)) !== false) {
                                                        if(is_file($VarFdr.$file)) {
                                                            ?>
                                                            <li>
                                                                <div style="padding: 10px 0;">
                                                                    <?php
                                                                    $VarFileExt = pathinfo($file, PATHINFO_EXTENSION);
                                                                    echo $file .' ';?>&nbsp;
                                                                    <a href="<?php echo base_url()."merchant/enqFileDownload?id=".urlencode(base64_encode($VarEnqId))."&fileName=".urlencode($file) ?>" title="Download">
                                                                        <i class="fa fa-download fa-lg" aria-hidden="true"></i>
                                                                    </a>&nbsp;&nbsp;
                                                                    <?php
                                                                    if(in_array($VarFileExt,$ArrDwnExtensions)) {}
                                                                    else {
                                                                        ?>
                                                                        <a href="<?php echo base_url()."merchant/enqOpenFile?id=".$VarEnqId."&fileName=".urlencode($file) ?>" target="_blank" title="Open in New Tab">
                                                                            <i class="fa fa-file fa-lg" aria-hidden="true"></i>
                                                                        </a>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                    closedir($dh);
                                                }
                                                ?>
                                                <?php
                                            }
                                            else {
                                                echo 'No attachments';
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">
                                <?php
                                if($ArrEnquiryInfo[0]->status == 1) {
                                    if($ArrEnquiryInfo[0]->orderstatus == '3') { ?>
                                        <div><strong>Attachments</strong></div>
                                        <div id="uploadImgAgain" class="pdt10"></div>
                                        <button type="button" class="btn btn-info pull-right addrights" id="resendbtn" onclick="return fnReSendEnq();">Save Changes</button>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="divLog">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Logs List</h3>
                            </div>
                            <div class="box-body">
                                <table id="tableLogList" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Order Enq. Ref. No.</th>
                                        <th>Request Date Time</th>
                                        <th>Brand / Buyer</th>
                                        <th>Merchant Remarks</th>
                                        <th>Management Remarks</th>
                                        <th>Status</th>
                                        <th>Recent <br />Update </th>
                                    </tr>
                                    </thead>
                                </table>
                                <div id="DivTotalCntResult"></div>
                            </div>
                        </div>
                    </div>

            </div><!-- /.row -->
        </section><!-- /.content -->
    </div>    <!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
<script type="text/javascript">
    var GlbEnqId = '<?php echo @$VarEnqId ?>';
    var GlbOrderstatus = '<?php echo $ArrEnquiryInfo[0]->orderstatus ?>';
    var extraObj;
    $('#frmBasicEnquiryDate').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });

    $(document).ready(function() {
        extraObj     = $("#uploadImgAgain").uploadFile({
            dragDrop: true,
            multiple:true,
            url:base_path+'merchant/enqFileUpload',
            returnType: "json",
            fileName:"myFile",
            dynamicFormData:function () {
                return {'id':GlbEnqId};
            },
            autoSubmit:false
        });
        console.log(extraObj,'extraObj');

        if(GlbOrderstatus == 1 || GlbOrderstatus == 4) {
            $(".form-control").attr('readonly',true);
            $("#frmBasicCountry").attr('disabled',true);
            $("#frmBasicCurrency").attr('disabled',true);
            $("#frmBasicPs").attr('disabled',true);
            $("#frmBasicBB").attr('disabled',true);
            $("#frmBasicEType").attr('disabled',true);
            $("#frmBasicRType").attr('disabled',true);
            //$("#frmBasicMerchant").attr('disabled',true);
            $("#frmBasicEnquiryDate").attr('disabled',true);
            $("#frmBasicME").attr('disabled',true);
            $("#frmPriceQuotedFor").attr('disabled',true);
        }
    });

/*
    function array_search( name,arr ) {
        for(var i = 0, len = arr.length; i < len; i++) {
            if( arr[ i ].key === name )
                return true;
        }
        return false;
    }
*/

    $("#divLog").hide();
    function fnShowEnqInfo() {
        $("#basicInfoCircle").removeClass('fa fa-circle-o');
        $("#basicInfoCircle").addClass('fa fa-circle');

        $("#logcircle").removeClass('fa fa-circle');
        $("#logcircle").addClass('fa fa-circle-o');
        $("#divLog").hide();
        $("#divBasicInfo").show();
        $("#divNewStatus").removeClass('hide');
        //$("#divNewStatus").css('background-color','orange');
        //$("#divNewStatus").text('Pending-RR');

    }

    function fnShowEnqLog(showdivid,hidedivid) {
        $("#logcircle").removeClass('fa fa-circle-o');
        $("#logcircle").addClass('fa fa-circle');
        $("#basicInfoCircle").removeClass('fa fa-circle');
        $("#basicInfoCircle").addClass('fa fa-circle-o');
        $("#"+showdivid).show();
        $("#"+hidedivid).hide();
        fnLogList();
    }

    function fnReSendEnq() {
        if (confirm("To confirm click OK, else CANCEL")) {
            try {
                var StyDesc     = $("#frmBasicStyleDesc").val();
                var StyleRefNo  = $("#frmBasicStyleRefNo").val();
                var EnquiryDate = $("#frmBasicEnquiryDate").val();
                var frmBasicEType = $("#frmBasicEType").val();
                var frmBasicME = $("#frmBasicME").val();
                var frmBasicBrand = $("#frmBasicBrand").val();
                var frmBasicBuyer = $("#frmBasicBuyer").val();
                var frmBasicPs = $("#frmBasicPs").val();
                var frmBasicMnote = $("#frmBasicMnote").val();
                var frmBasicCountry = $("#frmBasicCountry").val();
                var frmBasicQprice = $("#frmBasicQprice").val();
                var frmBasicBprice = $("#frmBasicBprice").val();
                var frmBasicCprice = $("#frmBasicCprice").val();
                var frmBasicCurrency = $("#frmBasicCurrency").val();
                var frmBasicPq = $("#frmBasicPq").val();
                var frmBasicRType = $("#frmBasicRType").val();
                var orderEnqRefNo = $("#orderEnqRefNo").val();
                var frmPriceQuotedFor = $("#frmPriceQuotedFor").val();

                $('.form-control').css("border", "1px solid #cccccc");
                $('div.herr').text('');
                if(jsTrim(StyDesc) == "") {
                    $('#ErrfrmBasicStyleDesc').html("Enter Style Description");
                    $('#frmBasicStyleDesc').focus();
                    $('#frmBasicStyleDesc').css("border", "1px solid #B94A48");

                }
                if(jsTrim(frmBasicMnote) == "") {
                    $('#ErrfrmBasicMnote').html("Enter Merchant Remarks");
                    $('#frmBasicMnote').focus();
                    $('#frmBasicMnote').css("border", "1px solid #B94A48");

                }
                if(jsTrim(frmBasicQprice) == "") {
                    $('#ErrfrmBasicQprice').html("Enter Quoted Price");
                    $('#frmBasicQprice').focus();
                    $('#frmBasicQprice').css("border", "1px solid #B94A48");

                }
                if(jsTrim(frmBasicBprice) == "") {
                    $('#ErrfrmBasicBprice').html("Enter Buyer's Price");
                    $('#frmBasicBprice').focus();
                    $('#frmBasicBprice').css("border", "1px solid #B94A48");

                }
                if(jsTrim(frmBasicCprice) == "") {
                    $('#ErrfrmBasicCprice').html("Enter Confirmed Price");
                    $('#frmBasicCprice').focus();
                    $('#frmBasicCprice').css("border", "1px solid #B94A48");

                }
                if(frmBasicCurrency == "") {
                    $('#ErrfrmBasicCurrency').html("Select Currency");
                    $('#frmBasicCurrency').focus();
                    $('#frmBasicCurrency').css("border", "1px solid #B94A48");
                }
                let Parameters = "rfrom=1&orderenqrefno="+orderEnqRefNo+"&sd="+StyDesc+"&styref="+StyleRefNo+"&enqdt="+EnquiryDate+"&enquiryid="+GlbEnqId+
                    "&resend=1&os=4&et="+frmBasicEType+"&me="+frmBasicME+"&brn="+frmBasicBrand+"&byr="+frmBasicBuyer+"&ps="+frmBasicPs+"&mt="+frmBasicMnote+"&conty="+
                    frmBasicCountry+"&qp="+frmBasicQprice+"&bp="+frmBasicBprice+"&cp="+frmBasicCprice+"&crncy="+frmBasicCurrency+"&proq="+frmBasicPq+
                    "&rt="+frmBasicRType+"&pricequotedfor="+frmPriceQuotedFor;
                MakePostRequest(base_path+'merchant/updateenquiry',Parameters,'json',fnSaveEnquiryRes);
                return false;
            } catch(e) {
                alert(e);
            }
        }
        else {
            return false;
        }
    }

    function fnSaveEnquiryRes(data) {
        if(data!='') {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if(data.errcode==-1) {
                return false;
            } else if(data.errcode==1) {
                GlbInsertId = data.id;
                extraObj.startUpload();
                $("#resendbtn").remove();
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").html("Enquiry has been sent successfully!");
                //fnRedirectPageTimeOut(base_path+'merchant/orderEnquiryList');
            }
        }
    }

    function fnLogList() {
        MakeAsynPostRequest(base_path + 'enquiryLog/enquiryLogList',"rFrom=1&enquiryId="+GlbEnqId,'json',fnListEnqLogRes);
    }

    function fnListEnqLogRes(data) {
        if(data!=''){
            if(data.errCode!=undefined) {
                if(data.errCode == '404') {
                    fnCallSessionExpire();
                    return false;
                } else {
                    console.log(data,'data');
                    var PageContent,ListCount = '';
                    if(data.cn>0) {
                        ListCount	= '<div style="font-weight:bold;">Number of Record(s) : '+data.cn+'</div>';

                            $.each(data.re,function(index,value) {
                                console.log(index,'index');
                                console.log(value,'value');
                                PageContent=PageContent+'<tr>' +
                                    '<td>' +
                                    '<a href="'+base_path+'enquiryLog/enquiryLogDetail/'+encodeURIComponent(base64_encode(value.id))+'">'+value.orderEnqRefNo+'' +
                                    '</a>' +
                                    '</td>' +
                                    '<td>'+value.reqDateTime+'</td>' +
                                    '<td>'+value.brn+'</td>' +
                                    '<td>'+value.merchantRemarks+'</td>' +
                                    '<td>'+value.manRemarks+'</td>' +
                                    '<td>'+value.order_status_value+'</td>'+
                                    '<td>'+value.dateupdated+'</td>';
                                PageContent=PageContent+'</tr>';
                            });

                        $("#DivTotalCntResult").html(ListCount);
                    } else {
                        PageContent	= PageContent+'<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                        $("#DivTotalCntResult").html('');
                    }
                    $("tbody").empty();
                    $("#tableLogList").append(PageContent);
                }
            }
        }
    }

    $("#frmBasicBrand").change(function () {
        var BrnId = $(this).val();
        MakeAsynPostRequest(base_path + "merchant/getBuyerInfoByBrandId", "rFrom=1&id=" + BrnId, "json", function (data) {
            console.log(data.buyerId, 'buyerId');
            if (data.buyerId != '')
                $("#frmBasicBuyer").val(data.buyerId);
        });
    });
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>