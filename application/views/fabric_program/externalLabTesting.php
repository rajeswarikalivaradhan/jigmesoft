<?php
$this->load->view(CNFCOMPANY.'template/pageheader');
/*$ArrLoggedUserInfo      = fnGetUserLoggedInfo(1);
$VarUserType            = $ArrLoggedUserInfo['usertype'];
$VarProfilePermission   = $ArrLoggedUserInfo['pp'];
$ArrUserDetails         = fnGetUserLoggedInfo();*/
?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<style>
    /*    td div {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 12px;
            line-height: 15px;
        }
        td {
            font-family: Verdana, Geneva, sans-serif;
            align: top;
        }
        table {
            margin-bottom: 0px !important;
        }
        .mainheading {
            background-color: #bffff9;
        }*/
</style>
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">
                    Fabric Programme
                </h1>
            </section>
        </div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body" style="padding: 0">
                            <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                <?php $this->load->view("fabric_program/fabricProPaginationLinks"); ?>
                                <div class="col-md-12 no-padding">
                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                        <strong>EXTERNAL LAB TESTING AUTHORITY CONTACT DETAILS</strong>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding: 5px !important">
                                    <div id="oe_ExtLabTesting"></div>
                                </div>
                            </form>
                            <?php $this->load->view("fabric_program/fabricProFooterLinks"); ?>
                        </div><!-- /.box-body -->
                    </div>
                </div>
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script>
    const enquiryId = '<?php echo $VarEnquiryId ?>';
    const pageId = '<?php echo $VarPageId ?>';
    const GlbParam = 'rFrom=1';
    unsaved = false;
    var currentTblData = ''; GlbLabAuthName = [];
    MakeAsynPostRequest(base_path+'fabricprogram/ajaxData',GlbParam+"&enqId="+enquiryId+"&pid="+pageId,'json',function (data) {
        console.log(data, 'data');
        GlbLabAuthName = data.ArrLabAuthName;
        GlbAddress = data.ArrAddress;
        GlbGst = data.ArrGst;
        GlbContactDetails = data.ArrContactDetail;
        console.log(GlbAddress,'GlbAddress');
        if(data.jsonExtLabTesting != "") {
            currentTblData = JSON.parse(data.jsonExtLabTesting);
        }
        else {
            currentTblData = [
                []
            ];
        }
        console.log(currentTblData,'currentTblData');
        jexcel(document.getElementById("oe_ExtLabTesting"), {
            columns: [
                {title: 'Lab Testing Authority Name', width: 200, type: 'dropdown', source: GlbLabAuthName, wordWrap: true},
                {title: 'Address', width: 200, wordWrap: true},
                {title: 'GST No.', width: 150, wordWrap: true},
                {title: 'Contact Details: Name / E Mail ID / Phone / Mobile', width: 250, wordWrap: true},
                {title: 'If On-line Booking System Web Site / User ID / Password', width: 200, wordWrap: true},
                {title: 'P.W. Exp. Date', type: 'calendar', options: {format: 'DD/MM/YYYY'}, width: 100, wordWrap: true},
            ],
            data: currentTblData,
            onchange:function () {
                unsaved = true;
            },
            updateTable: function (instance, cell, col, row, val, label, cellName) {
                if(col === 0) {
                    console.log(GlbAddress[val],'test');
                    addressVal = GlbAddress[val];
                    gstVal = GlbGst[val];
                    contactDetails = GlbContactDetails[val];
                }
                if(col === 1) {
                    $(cell).html(addressVal);
                    instance.jexcel.options.data[row][col] = addressVal;
                }
                if(col === 2) {
                    $(cell).html(gstVal);
                    instance.jexcel.options.data[row][col] = gstVal;
                }
                if(col === 3) {
                    $(cell).html(contactDetails);
                    instance.jexcel.options.data[row][col] = contactDetails;
                }
            }
        });
    });
    function cmnSaveChanges() {
        var data = JSON.stringify($("#oe_ExtLabTesting").jexcel('getData'));
        MakeAsynPostRequest(base_path+'fabricprogram/saveExtLabTesting',GlbParam+"&d="+data+
            "&enqId="+enquiryId+"&pid="+pageId,'json',fnSaveTableRes);
    }
    function fnSaveTableRes(data) {
        if(data!='') {
            if(data.errCode == 1) {
                unsaved = false;
                $("#divCmnSuccessMsg").removeClass('hide');
                $("#divCmnSuccessMsg").text("Success!");
            }
        }
    }
    function unloadPage() {
        if(unsaved) {
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>