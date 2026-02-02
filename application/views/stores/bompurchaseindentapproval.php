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

    .wdtp75 {
        width: 75%;
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
                        </div>
                        <div class="box-body">
                            <div id="bompurindentapprovalGrid"></div>
                            <button type="submit" class="btn btn-info pull-right addrights" style="margin: 0 10px;" id="" onclick="return fnSaveBomPurchaseIndentIssue()">Save</button>
                            <button type="submit" class="btn btn-info pull-right addrights" style="margin: 0 10px;" id="" onclick="return fnBomPurchaseIndentAppRequest()">Request For approval</button>
                            <button type="submit" class="btn btn-info pull-right addrights" style="margin: 0 10px;" id="" onclick="return fnIssuePIBOM()">Issue P.I.</button>
                            <!--<a href="<?php /* */?>" class="btn">Issue P.I.</a>-->
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
    var GlbId = '<?php echo @$VarId ?>';
    var GlbOrderId = '<?php echo @$ArrBasicInfo->orderid ?>';
    var GlbNewBomReqGridId = '<?php echo @$NewBomReqGridId ?>';
    var GlbBomRequest = '<?php echo @$ArrNewBomReqGrid ?>';
    var GlbApprovalstatus = ["Pending","Approved","Declined"];
    var GlbUom = '<?php echo @$ArrUnitofmeasure ?>';
    var GlbMgmtName = '<?php echo @$VarReqapprMgmtName ?>';
    var GlbMgmtgriddata = '<?php echo @$ArrMgmtGridData ?>';
    if(GlbMgmtgriddata!='') {
        GlbMgmtgriddata = JSON.parse(GlbMgmtgriddata);
        console.log(GlbMgmtgriddata,'GlbMgmtgriddata');
    }
    $('#bompurindentapprovalGrid').jexcel({
        colHeaders: ['Item Description', 'Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)', 'Unit of<br/>Measure', 'Plan. BOM<br/>Qty.', 'Unit of<br/>Measure',
            'Prog. BOM<br/>Qty.','Unit of <br/>Measure','Unit Rate','Amount','Approval<br/>Status','Approved<br/>By Name','Request<br/>for<br/>approval ','Issue<br/>P.I.'],
        colWidths: [150,80,80,80,80,80,80,80,80,80,80,80,80,60,50],
        columns: [
            {type: 'text',readOnly: true},
            {type: 'text',readOnly: true},
            {type: 'text',readOnly: true},
            {type: 'text',readOnly: true},
            {type: 'text',readOnly: true},
            {type: 'text',readOnly: true},
            {type: 'text',readOnly: true},
            {type: 'numeric'},
            {type: 'dropdown', source: JSON.parse(GlbUom) },
            {type: 'numeric'},
            {type: 'numeric'},
            {type: 'text', readOnly: true},
            {type: 'text',readOnly: true},
            {type: 'checkbox'},
            {type: 'checkbox'}
        ],
        data : GlbBomRequest
    });

    var ProgBOMQty=0; var UnitRate=0;
    var one = "", two = "", three = "", four = "", five = "", joined = "", AppStatus = "";
    $("#bompurindentapprovalGrid").jexcel('updateSettings', {
        table: function (instance, cell, col, row, val, id) {
            if(col==0) {ProgBOMQty=0;}
            if(col == 0) one    = $(cell).text();
            if(col == 1) two    = jsTrim($(cell).text());
            if(col == 2) three  = jsTrim($(cell).text());
            if(col == 3) four   = $(cell).text();
            if(col == 4) {
                //five = $(cell).text();
                //joined = one+"#"+two+"#"+three+"#"+four+"#"+five;
            }
            if(col==7) {
                //$(cell).html(FifthTblGroup[joined]); ProgBOMQty      = Number(FifthTblGroup[joined])
                ProgBOMQty      = Number($(cell).text());
            };
            if(col==9) {UnitRate   = Number(jsTrim($(cell).text())); }
            if(col==10) {
                if(ProgBOMQty!='' && UnitRate!='') {
                    //console.log(ProgBOMQty*UnitRate,'progbomqty * ur');
                    $(cell).text(ProgBOMQty*UnitRate);
                } else {
                    $(cell).text(0);
                }
            }
            if(col==11) {
                console.log(row,'row');
                console.log(typeof GlbMgmtgriddata[row],'typeof');
                console.log(GlbMgmtgriddata[row],'GlbMgmtgriddata[row]');
                if(GlbMgmtgriddata[row] == 2) {
                    $(cell).text('Approved');
                }
                else if(GlbMgmtgriddata[row] == 1) {

                    $(cell).text('Pending');
                }
                else {
                    $(cell).text('Pending');
                }
                /*for(var i = 0; mgmtgriddata.length; i++) {

                }*/
                //AppStatus = $(cell).text();
                //console.log(AppStatus,'AppStatus');
            }
            if(col == 12) {
                $(cell).text(GlbMgmtName);
            }
            if(col == 13) {

                if(AppStatus == "Approved") {
                    $(cell).find('input:checkbox').attr('disabled',true);
                }
            }
            if(col == 14) {
                if(AppStatus == "Pending") {
                    $(cell).find('input:checkbox').attr('disabled',true);
                }
            }
        }
    });
    function fnSaveBomPurchaseIndentIssue() {
        var reqforapp = $("#bompurindentapprovalGrid").jexcel('getColumnData',13);
        var issuepi = $("#bompurindentapprovalGrid").jexcel('getColumnData',14);
        var checkrequest = reqforapp.indexOf('1');
        var checkIssuePi = issuepi.indexOf('1');
        var bomPurchaseApprGrid = $("#bompurindentapprovalGrid").jexcel('getData');
        if(checkrequest == '-1' && checkIssuePi == '-1') {
            MakeAsynPostRequest(base_path + 'dashboard/updateBomPurchaseIndentAppr', "rfrom=1&bomPurIndApprGrid=" + JSON.stringify(bomPurchaseApprGrid) + "&rid=" +
                GlbId + "&oid=" + GlbOrderId+"&id="+GlbNewBomReqGridId, 'json', fnSaveBomPurchaseIndentIssueRes);
        }
        else {
            alert('err');
        }
    }
    function fnSaveBomPurchaseIndentIssueRes(data) {
        console.log(data,'data');
    }
    function fnBomPurchaseIndentAppRequest() {
        var bomPurchaseApprGrid = $("#bompurindentapprovalGrid").jexcel('getData');
        //var bom
        /*        for(var i = 0; i < bomPurchaseApprGrid.length; i++) {
                    console.log(bomPurchaseApprGrid[i][0],'ok1');
                    console.log(bomPurchaseApprGrid[i][12],'ok1');
                }*/
        MakeAsynPostRequest(base_path+'dashboard/updateBomPurchaseIndentAppr',"rfrom=1&bomPurIndApprGrid="+JSON.stringify(bomPurchaseApprGrid)+"&rid="+
            GlbId+"&reqBtnClick=1&oid="+GlbOrderId+"&id="+GlbNewBomReqGridId,'json',fnSaveBomPurchaseIndentIssueRes);
        return false;
    }
    function fnIssuePIBOM() {
        var bomPurchaseIndentGrid = $("#bompurindentapprovalGrid").jexcel('getData');
        var checkGridData = [];
        var issuepiCol = $("#bompurindentapprovalGrid").jexcel('getColumnData',14);
        for(var i = 0; i < issuepiCol.length; i++) {
            if(issuepiCol[i] == 1) {
                console.log(bomPurchaseIndentGrid,'bomPurchaseIndentGrid');
                console.log(bomPurchaseIndentGrid[i],'all');
                checkGridData.push([bomPurchaseIndentGrid[i][0],bomPurchaseIndentGrid[i][1],bomPurchaseIndentGrid[i][2],bomPurchaseIndentGrid[i][3],bomPurchaseIndentGrid[i][4],
                    bomPurchaseIndentGrid[i][7],bomPurchaseIndentGrid[i][9],bomPurchaseIndentGrid[i][10]]);
                //checkGridData.push(bomPurchaseIndentGrid[i]);
            }
        }
        console.log(checkGridData,'checkGridData');
        if (typeof(Storage) !== "undefined") {
            localStorage.setItem("ckGridData", JSON.stringify(checkGridData));
            // Code for localStorage/sessionStorage.
        } else {
            alert('Sorry! No Web Storage support..');

        }
        //document.cookie = "ckGridData="+JSON.stringify(checkGridData);
        //var checkedrow = issuepiCol.indexOf('1');
        //console.log(checkedrow,'checkedrow');
        //var checkGridParam = $.param(checkGridData);console.log(checkGridParam,'checkGridParam');
        fnRedirectPageTimeOut(base_path+'purchaseuser/bomPurchaseIndentInvoice/'+GlbNewBomReqGridId);
        //MakeAsynPostRequest(base_path+'purchaseuser/getBomPurchaseIndentInvoice/'+GlbNewBomReqGridId,"rfrom=1&checkedGrid="+JSON.stringify(checkGridData),'json',fnIssuePIBOMRes);
    }

    function fnIssuePIBOMRes(data) {
        console.log(data,'data');

    }

    //$(document).ready(function () {

    //});
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>