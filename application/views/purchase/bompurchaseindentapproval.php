<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" />
<style type="text/css">
    td div {
        font-family: Verdana, Geneva, sans-serif;
        font-size: 12px;
        line-height: 15px;
        /*padding: 5px 2px;*/
    }
    td {
        font-family: Verdana, Geneva, sans-serif;
        align: top;
    }
    table, .control-label {
        margin-bottom: 0px !important;
        font-size: 12px;
    }
    .form-control {
        height: 25px;
        padding: 3px 2px !important;
        font-size: 12px;
    }
    .mainheading {
        background-color: #bffff9;
    }
    .secondheading {
        background-color: #e3f9f7;
        height: 27px;
    }
    .customcontrol {
        border-radius: 0;
        box-shadow: none;
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 12px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
    }
    .table, .secondheading {
        background-color: #ecf0f5;
    }
    .table td.secondheading {
        padding-top: 15px;
    }
</style>
<?php $this->load->view(CNFCOMPANY.'template/pageheader'); $ArrLoggedUserInfo = fnGetUserLoggedInfo(1); $VarUserType = $ArrLoggedUserInfo['usertype']; $ArrUserDetails = fnGetUserLoggedInfo(); ?>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <?php
            $Arrusertype = unserialize(ARRUSERTYPE);
            $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
            ?>
            <h1>BOM Purchase INDENT Approval Request<small></small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?php echo base_url('dashboard/allqueuelist/') ?>">Purchase Queue list</a> </li>
                <li class="active">BOM Purchase INDENT Approval Request</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="divBasicInfo">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Basic Information</h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table id="tableList" class="table table-bordered table-responsive">
                                <tr>
                                    <td class="mainheading no-padding">
                                        <table id="tableList" class="table table-bordered table-responsive">
                                            <tr>
                                                <td width="25%" class="no-padding">
                                                    <div class="mainheading" style="font-size: 14px">
                                                        <div class="secondheading">COMPANY NAME <strong>
                                                                <?php echo $ArrCompanyInfo[0]['companyname'];
                                                                $ArrStatus = unserialize(ORDERENQUIRYSTATUS);
                                                                ?>
                                                            </strong>
                                                        </div>
                                                    </div>
                                                    Address <strong><?php echo $ArrCompanyInfo[0]['address'] ?></strong>
                                                </td>
                                                <td width="50%" class="no-padding">
                                                    <table id="tableList" class="table table-bordered table-responsive">
                                                        <tr>
                                                            <td class="secondheading">Merch. Name</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo $ArrMerchant['contactname'] ?></div>
                                                            </td>
                                                            <td class="secondheading">Team Name</td>
                                                            <td>
                                                                <div class="customcontrol"><?php
                                                                    //echo '<pre>'; print_r($ArrTeam); die('');
                                                                    echo $ArrTeamInfo->contactname ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">Merch. Code</td>
                                                            <td id="merchantCode">
                                                                <div class="customcontrol"><?php echo $ArrMerchant['code']; ?></div>
                                                            </td>
                                                            <td class="secondheading">Team Code</td>
                                                            <td id="teamcode">
                                                                <div class="customcontrol"><?php echo $ArrTeamInfo->code; ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">Contact No.</td>
                                                            <td id="merchantContactNo">
                                                                <div class="customcontrol"><?php echo $ArrMerchant['mobile']; ?></div>
                                                            </td>
                                                            <td class="secondheading">Contact No.</td>
                                                            <td id="mobileNo">
                                                                <div class="customcontrol"><?php echo $ArrTeamInfo->mobile; ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">E-Mail Id</td>
                                                            <td id="merchantEmail">
                                                                <div class="customcontrol"><?php echo $ArrMerchant['username'] ?></div>
                                                            </td>
                                                            <td class="secondheading">E-Mail Id</td>
                                                            <td id="emailId">
                                                                <div class="customcontrol"><?php echo $ArrTeamInfo->email ?></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="25%" class="no-padding">
                                                    <table id="tableList" class="table table-bordered table-responsive">
                                                        <tr>
                                                            <td class="mainheading text-center" colspan="4"
                                                                style="height:36px;"><strong>INTERNAL REFERENCE
                                                                    NO</strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">WIP No.</td>
                                                            <td>
                                                                <div class="customcontrol"
                                                                     id="frmBasicWipRefNo"><?php echo $ArrOrderEnqData['isriorcode'] ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">Date & Time</td>
                                                            <td>
                                                                <div class="customcontrol" id="frmBasicWipDate"><?php
                                                                    echo isset($ArrCommonData->datecreated) ? date('d-m-Y H:i:s', strtotime($ArrCommonData->datecreated)) : date('d-m-Y H:i:s');
                                                                    ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading" style="height:36px;">Total Qty.
                                                            </td>
                                                            <td>
                                                                <?php $VarPcsOrSet = unserialize(ARRPCSSET); ?>
                                                                <div class="customcontrol"><?php echo $ArrOrderEnqData['exporderqty'] . ' ' . $VarPcsOrSet[$ArrOrderEnqData['pcsorset']] ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Order Details</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-responsive">
                                <tr>
                                    <td class="no-padding">
                                        <table id="tableList" class="table table-bordered table-responsive">
                                            <tr>
                                                <td class="no-padding">
                                                    <table id="tableList" class="table table-bordered table-responsive">
                                                        <tr>
                                                            <td class="secondheading">Order Ref. No.</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo "Order " . $ArrOrderEnqData['id']; ?></div>
                                                            </td>
                                                            <td class="secondheading">Brand</td>
                                                            <td>
                                                                <div class="customcontrol"
                                                                     id="frmBrandName"><?php echo $ArrBB['brandname']; ?></div>
                                                            </td>
                                                            <td class="secondheading">Season</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo  $ArrOrderDatas->season ?></div>
                                                            </td>
                                                            <td class="secondheading">Class</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo  $ArrOrderDatas->class ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">Style Ref. No.</td>
                                                            <td>
                                                                <div class="customcontrol"
                                                                     id="frmBasicStyleRefNo"><?php echo $ArrOrderEnqData['stylenamerefno'] ?></div>
                                                            </td>
                                                            <td class="secondheading">Buyer</td>
                                                            <td>
                                                                <div class="customcontrol"
                                                                     id="frmBuyerName"><?php echo $ArrBB['buyername'] ?></div>
                                                            </td>
                                                            <td class="secondheading">Div./Dept.</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo  $ArrOrderDatas->divdept ?></div>
                                                            </td>
                                                            <td class="secondheading">Sub Class</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo  $ArrOrderDatas->sclass ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">Style Name</td>
                                                            <td style="padding:9px;" colspan="7">
                                                                <div class="customcontrol" id="frmBasicStyleDesc"><?php echo $ArrOrderEnqData['styledesc'] ?></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">BOM Purchase INDENT Approval Request</h3>
                            <div class="box-tools pull-right">
                                <?php echo $VarRequirement; ?>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="bompurindentapprovalGrid"></div>
                            <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                            <button type="submit" class="btn btn-info pull-right addrights" style="margin: 0 10px;" id="" onclick="return fnIssuePIBOM()">Issue P.I.</button>
                            <button type="submit" class="btn btn-info pull-right addrights" style="margin: 0 10px;" id="" onclick="return fnBomPurchaseIndentAppRequest()">Request For approval</button>
                            <button type="submit" class="btn btn-info pull-right addrights" style="margin: 0 10px;" id="" onclick="return fnSave()">Save</button>
                            <!--<a href="<?php /* */?>" class="btn">Issue P.I.</a>-->
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h1 class="box-title">BOM SOURCING DETAILS</h1>
                            <div class="box-tools pull-right">
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div id="bomsourcingdetailsgrid"></div>
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h1 class="box-title">BOM SAMPLING & APPROVAL DETAILS</h1>
                        </div>
                        <div class="box-body">
                            <div id="bomsamplingappr_grid"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Starts Here -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Choose a Tax Type</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" action="#" method="post" id="frmPinformId" autocomplete="off">
                                <div class="form-group">

                                    <div class="col-md-4">
                                        <input type="radio" name="frmSelectTaxType" id="inputRadio1" value="1" class="">
                                        <label for="inputRadio1" class="">SGST / CGST RATE</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="frmSelectTaxType" id="inputRadio2" value="2" class="">
                                        <label for="inputRadio2" class="">IGST RATE</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="frmSelectTaxType" id="inputRadio3" value="3" class="">
                                        <label for="inputRadio3" class="">IMPORT DUTY</label>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" onclick="return fnSaveTaxType()">Continue</button>
                            <div class="herr pull-left" id="ErrfrmPin"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url();?>assets/js/ajax.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/jquery.jexcel.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>
<script>
    var GlbRequestId = '<?php echo @$VarRequestId ?>';
    var GlbTblName = '<?php echo @$VarTblName ?>';
    var GlbOrderId = '<?php echo @$ArrBasicInfo->orderid ?>';
    var GlbBPIApprovalGridData = '<?php echo @$jsBPIApprovalGridData ?>';
    var GlbApprovalstatus = [{"id":"1","name":"Pending"},{"id":"2","name":"Approved"},{"id":"3","name":"Declined"}];
    var GlbUom = '<?php echo @$ArrUnitofmeasure ?>';
    var GlbArtType = '<?php echo @$VarRequirementId ?>';
    var GlbbompurindentapprovalGrid = '';

    MakeAsynPostRequest(base_path + 'mpurchase/getAddeditBOMDatas', "rfrom=1&at=" + GlbArtType + "&refid=" + GlbOrderId, 'json', fnGetGridBOMDataRes);

    function fnGetGridBOMDataRes(data) {
        //console.log(data, 'data');
        var bomatronetwo = data.bomPurIndApprovalRequest;
        var bomsourcingdetail = data.bomsourcingdetail;
        var SamplingAppr = data.ArrSamplingAppr;
        if (bomsourcingdetail != '' && typeof bomsourcingdetail !== 'undefined') {
            $("#bomsourcingdetailsgrid").jexcel({
                colHeaders: ['Item Description / (%)Blend /<br/>Content / Material', 'Item Code', 'Sourcing<br/>Advice', 'Vendor<br/>Location',
                    'Vendors<br/>Name / Address', 'GST /<br/>IE code<br/>Details', 'Contact Details:<br/>Person / E-mail Id<br/>/ Phone / Mobile',
                    'If On-line<br/>Ordering System:<br/>Website / Userid /<br/>Password', 'P.W. Expiry<br/>Date'],
                allowInsertColumn: false,
                colWidths: [380, 100, 110, 80, 120, 80, 120, 130, 80],
                columns: [
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', readOnly: true}
                ],
                data: bomsourcingdetail
            });
        }
        else {
            $("#bomsourcingdetailsgrid").text('No records found');
        }
        if (SamplingAppr != '' && typeof SamplingAppr !== "undefined") {
            $("#bomsamplingappr_grid").jexcel({
                colHeaders: ['Item Description / (%)Blend /<br/>Content / Material', 'Item Code', 'Item Color<br/>Code', 'Category', 'Sample Sub.<br/>for Approval',
                    'Sample<br/>Sub. Size', 'Reqd.<br/>No.<br/>of Samples', 'Approving<br/>Authority', 'Approval<br/>Status', 'Approved<br/>By', 'Approval<br/>Date',
                    'Approved<br/>Sample<br/>Code'],
                allowInsertColumn: false,
                colWidths: [200, 100, 100, 80, 100, 80, 80, 100, 100, 90, 90, 80],
                columns: [
                    {type: 'text', readOnly: true, wordWrap: true},
                    {type: 'text', readOnly: true, wordWrap: true},
                    {type: 'text', readOnly: true, wordWrap: true},
                    {type: 'text', readOnly: true, wordWrap: true},
                    {type: 'text', readOnly: true, wordWrap: true},
                    {type: 'text', readOnly: true, wordWrap: true},
                    {type: 'text', readOnly: true, wordWrap: true},
                    {type: 'text', readOnly: true, wordWrap: true},
                    {type: 'text', readOnly: true, wordWrap: true},
                    {type: 'text', readOnly: true, wordWrap: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true, wordWrap: true}
                ],
                data: SamplingAppr
            });
        }
        else {
            $("#bomsamplingappr_grid").text('No records found');
        }
        //console.log(GlbBPIApprovalGridData,'GlbBPIApprovalGridData');
        console.log(GlbBPIApprovalGridData,'GlbBPIApprovalGridData');
        if (GlbBPIApprovalGridData == '[]') {
            console.log('empty');
        }
        if (GlbBPIApprovalGridData == '[]') {
            GlbbompurindentapprovalGrid = bomatronetwo
        }
        else {
            GlbbompurindentapprovalGrid = GlbBPIApprovalGridData;
        }
        console.log(GlbbompurindentapprovalGrid,'GlbbompurindentapprovalGrid');
            $('#bompurindentapprovalGrid').jexcel({
                colHeaders: ['Item Description', 'Gar.</br/>Size','Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)', 'Unit of<br/>Measure', 'Plan. BOM<br/>Qty.', 'Unit of<br/>Measure',
                    'Prog. BOM<br/>Qty.', 'Unit of <br/>Measure', 'Unit<br/>Rate', 'Amount', 'Approval<br/>Status', 'Approved<br/>By Name', 'Request<br/>for<br/>approval ', 'Issue<br/>P.I.'],
                colWidths: [150, 50, 80, 80, 80, 70, 80, 70, 80, 70, 40, 80, 80, 80, 70, 50],
                columns: [
                    {type: 'text', readOnly: true, wordWrap: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'numeric'},
                    {type: 'dropdown', source: JSON.parse(GlbUom)},
                    {type: 'numeric'},
                    {type: 'numeric'},
                    {type: 'dropdown', source: GlbApprovalstatus, readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'checkbox'},
                    {type: 'checkbox'}
                ],
                data: GlbbompurindentapprovalGrid
            });
            $("#bompurindentapprovalGrid").jexcel('updateSettings', {
                table: function (instance, cell, col, row, val, id) {
                    if(col==8) {
                        //$(cell).html(FifthTblGroup[joined]); ProgBOMQty      = Number(FifthTblGroup[joined])
                        ProgBOMQty      = Number($(cell).text());
                    };
                    if(col==10) {UnitRate   = Number(jsTrim($(cell).text())); }
                    if(col==11) {
                        if(ProgBOMQty!='' && UnitRate!='') {
                            console.log(ProgBOMQty,'ProgBOMQty');
                            console.log(UnitRate,'UnitRate');
                            var multi = Number(ProgBOMQty) * Number(UnitRate);
                            console.log(multi,'multi');
                            $(cell).text(multi);
                        } else {
                            $(cell).text(0);
                        }
                    }
                    if(col==12) {
                        AppStatus = $(cell).text();
                    }
                    if(col == 13) {
                    }
                    if(col == 14) {
                        if(AppStatus == "Approved") {
                            $(cell).find('input:checkbox').attr('disabled',true);
                        }
                    }
                    if(col == 15) {
                        if(AppStatus != "Approved") {
                            $(cell).find('input:checkbox').attr('disabled',true);
                        }
                        //console.log(val,'val');
/*                        console.log(GlbbompurindentapprovalGrid,'GlbbompurindentapprovalGrid');``
                        for(var v = 0; v < GlbbompurindentapprovalGrid.length; v++) {
                            console.log(GlbbompurindentapprovalGrid[v][14],'14');
                            if(GlbbompurindentapprovalGrid[v][14] == 1) {
                                $(cell).find('input:checkbox').attr('disabled',true);
                            }
                        }*/
                        if(val == 1) {
                            $(cell).find('input:checkbox').attr('disabled',true);
                            //$(cell).find('input:checkbox').remove();
                        }
                    }
                }
            });
    }
    function fnSave() {
        var reqforapp = $("#bompurindentapprovalGrid").jexcel('getColumnData',14);
        var issuepi = $("#bompurindentapprovalGrid").jexcel('getColumnData',15);
        var checkrequest = reqforapp.indexOf('1');
        var checkIssuePi = issuepi.indexOf('1');
        var bomPurchaseApprGrid = $("#bompurindentapprovalGrid").jexcel('getData');
        if(checkrequest == '-1' && checkIssuePi == '-1') {
            MakeAsynPostRequest(base_path + 'dashboard/updateBomPurchaseIndentAppr', "rfrom=1&bomPurIndApprGrid=" + JSON.stringify(bomPurchaseApprGrid) + "&rid=" +
                GlbRequestId + "&oid=" + GlbOrderId+"&tblname="+GlbTblName, 'json', fnSaveRes);
        }
        else {
            console.log('Do not tick checkbox when saving');
            alert('err');
        }
    }
    function fnSaveRes(data) {
        //console.log(data,'data');
        if(data!='') {
            if(data.errcode == '1') {
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("Purchase Indent has been updated successfully!");
                var pageurl = $(location).attr('href');
                fnRedirectPageTimeOut(pageurl);
            }
        }
    }
    function fnBomPurchaseIndentAppRequest() {
        var bomPurchaseApprGrid = $("#bompurindentapprovalGrid").jexcel('getData');
        MakeAsynPostRequest(base_path+'dashboard/updateBomPurchaseIndentAppr',"rfrom=1&bomPurIndApprGrid="+JSON.stringify(bomPurchaseApprGrid)+"&rid="+
            GlbRequestId+"&oid="+GlbOrderId+"&tblname="+GlbTblName,'json',fnSaveBomPurchaseIndentIssueRes);
        return false;
    }
    function fnSaveBomPurchaseIndentIssueRes(data) {
        $("#divSuccessBasicInfoMsg").removeClass('hide');
        $("#divSuccessBasicInfoMsg").text('Purchase Indent request has been sent to management');
    }
    function fnIssuePIBOM() {
        var bomPurchaseIndentGrid = $("#bompurindentapprovalGrid").jexcel('getData');
        var issuepiCol = $("#bompurindentapprovalGrid").jexcel('getColumnData',15);
        //console.log(issuepiCol,'issuepiCol');
        var checkGridData = [];
        for(var i = 0; i < issuepiCol.length; i++) {
            if(issuepiCol[i] == 1) {
                checkGridData.push([bomPurchaseIndentGrid[i][0], bomPurchaseIndentGrid[i][1], bomPurchaseIndentGrid[i][2], bomPurchaseIndentGrid[i][3],
                    bomPurchaseIndentGrid[i][4],bomPurchaseIndentGrid[i][5], bomPurchaseIndentGrid[i][7], bomPurchaseIndentGrid[i][9], bomPurchaseIndentGrid[i][10]]);
            }
        }
        var checkedOne = issuepiCol.includes('1');
        if(checkedOne) {
            if (typeof(Storage) !== "undefined") {
                localStorage.setItem("forInvoiceGridLs", JSON.stringify(checkGridData));
                localStorage.setItem("forSavingIssuePIDynamicTbl", JSON.stringify(bomPurchaseIndentGrid));
                // Code for localStorage/sessionStorage.
            } else {
                alert('Sorry! No Web Storage support..');
            }
            $('#myModal').modal('show');
        }
        else {
            alert('Select a Item');
            return false;
        }
    }
    function fnSaveTaxType() {
        var taxtype = $('input[name="frmSelectTaxType"]:checked').val();
        var Param = "rfrom=1&taxtype="+taxtype;
        MakeAsynPostRequest(base_path+'dashboard/saveTaxType',Param,'json',fnSaveTaxTypeRes);
    }
    function fnSaveTaxTypeRes(data) {
        if(data.errcode == '1') {
            $('#myModal').modal('hide');
            fnRedirectPageTimeOut(base_path+'purchaseuser/bomPurchaseIndentInvoice/'+GlbRequestId);
        }
    }
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>