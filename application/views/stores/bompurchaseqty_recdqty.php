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
<?php $this->load->view(CNFCOMPANY.'template/pageheader');
$ArrLoggedUserInfo = fnGetUserLoggedInfo(1); $VarUserType = $ArrLoggedUserInfo['usertype']; $ArrUserDetails = fnGetUserLoggedInfo();
?>
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
            <?php $Arrusertype = unserialize(ARRUSERTYPE); $ArrUserLoggedInfo = fnGetUserLoggedInfo('1'); ?>
            <h1><?php echo $Arrusertype[$ArrUserLoggedInfo['usertype']]; ?> Dashboard<small>Control panel</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><?php echo $Arrusertype[$ArrUserLoggedInfo['usertype']]; ?> Dashboard</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="divBasicInfo">
                    <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
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
                                                                <?php
                                                                echo $ArrCompanyInfo[0]['companyname'];
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
                            <h3 class="box-title">BOM PURCHASE INDENT QTY. & RECEIVED. QTY. DETAILS</h3>
                        </div>
                        <div class="box-body">
                            <div id="bompurindentqty_recdqty"></div>
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
    var GlbAceeptStatus = [{"id":"1","name":"Pending"},{"id":"2","name":"Accepted"},{"id":"3","name":"Req. Replace."},{"id":"4","name":"Returned"}];
    var GlbUom = '<?php echo @$ArrUnitofmeasure ?>';
    $('#bompurindentqty_recdqty').jexcel({
        colHeaders: ['WIP Ref. No.','Requirement','P.I. No.','Vendor','Item Description / (%) Blend<br/>/ Content<br/>/ Material', 'Item Code', 'Item Color<br/>Code',
            'Size / Dim.<br/>(W*L*H)', 'Unit of<br/>Measure', 'BOM P.I.<br/>Qty.','BOM Recd.<br/>Qty.','Difference<br/>in Qty.','Acceptance<br/>Status','Stock<br/>Register'],
        colWidths: [80,100,80,80,190,80,80,80,80,80,80,70,80,60],
        columns: [
            {type: 'text',readOnly: true, wordWrap: true},
            {type: 'text',readOnly: true, wordWrap: true},
            {type: 'text',readOnly: true, wordWrap: true},
            {type: 'text',readOnly: true, wordWrap: true},
            {type: 'text',readOnly: true, wordWrap: true},
            {type: 'text',readOnly: true, wordWrap: true},
            {type: 'text',readOnly: true, wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'dropdown', source: JSON.parse(GlbUom), wordWrap: true },
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'dropdown',source: GlbAceeptStatus, wordWrap: true},
            {type: 'checkbox'},
        ]
    });

    // Live update of the settings
    $('#bompurindentqty_recdqty').jexcel('updateSettings', {
        table: function (instance, cell, col, row, val, id) {
            if (col == 9) {
                // Get text
                colnine = Number($(cell).text());
            }
            if (col == 10) {
                // Get text
                colten = Number($(cell).text());
            }
            if (col == 11) {
                $(cell).text(0);
                // Get text
                differ = colten - colnine;
                if(colten > colnine) {
                    differsign = '+' + differ;
                    $(cell).text(differsign);
                }
                else {
                    $(cell).text(differ);
                }
                //console.log(differ,'differ');
            }
        }
    });
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>