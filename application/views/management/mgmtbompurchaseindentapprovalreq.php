<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" />
<style type="text/css">
    #frmPin {
        padding-left: 15px;
        letter-spacing: 42px;
        border: 0;
        background-image: linear-gradient(to left, black 70%, rgba(255, 255, 255, 0) 0%);
        background-position: bottom;
        background-size: 50px 1px;
        background-repeat: repeat-x;
        background-position-x: 35px;
        width: 220px;
        min-width:220px;
    }
    #divInner{
        left: 0;
        position: sticky;
    }
    #divOuter{
        width:190px;
        overflow:hidden
    }

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
        background-color: #ecf0f5;
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
    .wdtp75 {
        width: 75%;
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
                                                                <div class="customcontrol"><?php echo @$ArrMerchant['contactname'] ?></div>
                                                            </td>
                                                            <td class="secondheading">Team Name</td>
                                                            <td>
                                                                <div class="customcontrol"><?php
                                                                    //echo '<pre>'; print_r($ArrTeam); die('');
                                                                    echo @$ArrTeamInfo->contactname ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">Merch. Code</td>
                                                            <td id="merchantCode">
                                                                <div class="customcontrol"><?php echo @$ArrMerchant['code']; ?></div>
                                                            </td>
                                                            <td class="secondheading">Team Code</td>
                                                            <td id="teamcode">
                                                                <div class="customcontrol"><?php echo @$ArrTeamInfo->code; ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">Contact No.</td>
                                                            <td id="merchantContactNo">
                                                                <div class="customcontrol"><?php echo @$ArrMerchant['mobile']; ?></div>
                                                            </td>
                                                            <td class="secondheading">Contact No.</td>
                                                            <td id="mobileNo">
                                                                <div class="customcontrol"><?php echo @$ArrTeamInfo->mobile; ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">E-Mail Id</td>
                                                            <td id="merchantEmail">
                                                                <div class="customcontrol"><?php echo @$ArrMerchant['username'] ?></div>
                                                            </td>
                                                            <td class="secondheading">E-Mail Id</td>
                                                            <td id="emailId">
                                                                <div class="customcontrol"><?php echo @$ArrTeamInfo->email ?></div>
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
                                                                     id="frmBasicWipRefNo"><?php echo @$ArrOrderEnqData['isriorcode'] ?></div>
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
                                                                <div class="customcontrol"><?php echo @$ArrOrderEnqData['exporderqty'] . ' ' . @$VarPcsOrSet[$ArrOrderEnqData['pcsorset']] ?>
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
                                                <td class="no-padding wdtp75">
                                                    <table id="tableList" class="table table-bordered table-responsive">
                                                        <tr>
                                                            <td class="secondheading">Order Ref. No.</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo "Order " . $ArrOrderEnqData['id']; ?></div>
                                                            </td>
                                                            <td class="secondheading">Brand</td>
                                                            <td>
                                                                <input type="hidden" id="frmBBid"
                                                                       value="<?php echo @$VarBBId ?>">
                                                                <div class="customcontrol"
                                                                     id="frmBrandName"><?php echo $ArrBB['brandname']; ?></div>
                                                            </td>
                                                            <td class="secondheading">Season</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo @$ArrOrderDatas->season ?></div>
                                                            </td>
                                                            <td class="secondheading">Class</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo @$ArrOrderDatas->class ?></div>
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
                                                                <div class="customcontrol"><?php echo @$ArrOrderDatas->divdept ?></div>
                                                            </td>
                                                            <td class="secondheading">Sub Class</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo @$ArrOrderDatas->sclass ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">Style Name</td>
                                                            <td style="padding:9px;" colspan="7">
                                                                <div class="customcontrol"
                                                                     id="frmBasicStyleDesc"><?php echo $ArrOrderEnqData['styledesc'] ?></div>
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
                        <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                        <div class="box-header with-border">
                            <h3 class="box-title">BOM Purchase INDENT Approval Request</h3>
                            <div class="box-tools pull-right">
                                <?php
                                $ArrAllReq = unserialize(ALLREQUIREMENTS);
                                $VarReqId = $ArrBasicInfo->requirementid;
                                echo $ArrAllReq[$VarReqId];
                                ?>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="bompurindentapprovalGrid"></div>
                            <?php
                            if(empty($jsBPIApprovalGridData)) {
                                echo '<p class="pdl15 herr text-center">No request received</p>';
                            }
                            else {
                                ?><button type="submit" class="btn btn-info pull-right addrights" id="" onclick="return fnSaveBomPurchaseIndentIssue()">Save</button><?php
                            }
                            ?>

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
            <!-- Password Modal Starts Here -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Enter PIN</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal col-md-3" method="post" id="frmPinformId" autocomplete="off">
                                <div id="divOuter">
                                    <div id="divInner">
                                        <input id="frmPin" type="password" maxlength="4"  />
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" onclick="return fnCheckPin()">Continue</button>
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
    var GlbApprovalstatus = [{"id":"1","name":"Pending"},{"id":"2","name":"Approved"},{"id":"3","name":"Declined"}];
    var GlbBPIApprovalGridData = '<?php echo $jsBPIApprovalGridData ?>';
    var GlbBompurindentapprovalColGrid = ''; var GlbApprstatusCol = '';
    var GlbTblName = '<?php echo @$VarTblName ?>';
    var GlbOrderId = '<?php echo @$VarOrderId ?>';
    var GlbArtType = '<?php echo @$VarReqId ?>';
    if(GlbBPIApprovalGridData != '') {
        $('#bompurindentapprovalGrid').jexcel({
            colHeaders: ['Item Description', 'Gar.<br/>Size','Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)', 'Unit of<br/>Measure', 'Plan. BOM<br/>Qty.', 'Unit of<br/>Measure',
                'Prog. BOM<br/>Qty.', 'Unit of <br/>Measure', 'Unit<br/>Rate', 'Amount', 'Approval<br/>Status', 'Approved<br/>By Name'],
            colWidths: [200, 50,100, 80, 80, 80, 80, 80, 80, 80, 50, 80, 80, 100],
            columns: [
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'numeric', readOnly: true},
                {type: 'dropdown', source: GlbApprovalstatus},
                {type: 'text', readOnly: true}
            ],
            data: GlbBPIApprovalGridData
        });
        $("#bompurindentapprovalGrid").jexcel('updateSettings', {
            table: function (instance, cell, col, row, val, id) {
                if(col==11) {

                }
                if(col==12) {
                    //$(cell).text(GlbMgmtName);
                }
            }
        });
    }
    getSourcingSamplingApprovalDetails();
    function getSourcingSamplingApprovalDetails() {
        MakeAsynPostRequest(base_path + 'mpurchase/getAddeditBOMDatas', "rfrom=1&at=" + GlbArtType + "&refid=" + GlbOrderId, 'json', fnGetGridBOMDataRes);
    }
    function fnGetGridBOMDataRes(data) {
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
                    {type: 'text', wordWrap: true},
                    {type: 'text', wordWrap: true},
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
    }
    /*[["Item Description 1 / (%) 100 % / Content 1 / Material 1","ic 1","icc 1","25 * 2 * 5","Inches","173.25","Inches","1","Inches","1","1","Approved","","1","0"],
        ["Item Description 1 / (%) 100 % / Content 1 / Material 1","ic 12","icc 1","25 * 2 * 5","Inches","99","Inches","2","Inches","2","4","Approved","","1","0"],
        ["Item Description 1 / (%) 100 % / Content 1 / Material 1","ic 13","icc 1","25 * 2 * 5","Inches","120.75","Inches","3","Inches","3","9","Pending","","1","0"]]*/
    function fnSaveBomPurchaseIndentIssue() {
        GlbBompurindentapprovalColGrid = $("#bompurindentapprovalGrid").jexcel('getData');
        GlbApprstatusCol = $("#bompurindentapprovalGrid").jexcel('getColumnData',12);
        $('#myModal').modal('show');
        return false;
    }
    function fnCheckPin() {
        $(".herr").text('');
        try {
            var pw = $("#frmPin").val();
            if(jsTrim(pw) == "") {
                $("#ErrfrmPin").text('Enter PIN');
                return false;
            }
            MakePostRequest(base_path+'management/updateBomPurIndRequest',"rfrom=1&i="+pw+"&rid="+GlbRequestId+"&oid="+GlbOrderId+"&tblname="+GlbTblName+
                "&apprstatusCol="+JSON.stringify(GlbApprstatusCol),'json',fnAuthRes);
            return false;
        } catch (e) {
            alert(e);
        }
    }
    function fnAuthRes(data) {
        console.log(data,'data');
        if (data != '') {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if (data.errcode == '-1') {
                return false;
            } else if (data.errcode == '1') {
                $('#myModal').modal('hide');
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text('BOM Purchase INDENT has updated at successfully!');
                fnRedirectPageTimeOut(base_path+'dashboard/allqueuelist');
            }
        }
    }
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>