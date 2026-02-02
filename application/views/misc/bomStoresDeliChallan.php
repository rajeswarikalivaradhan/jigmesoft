<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>

    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>BOM D.C. PREVIEW</h1>
        </section>

        <section class="content">
            <div class="box box-info">
                <h3 class="text-center">DELIVERY CHALLAN
                    <small id="dcType" class="pull-right">INTERNAL</small>
                </h3>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><strong>FIRM</strong></h4>
                                <form class="form-horizontal">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">NAME:</label>
                                            <div class="col-sm-9">
                                                <?php
                                                //echo '<pre>'; print_r($ArrCompanyRes); die('die');
                                                echo $ArrCompanyRes[0]['companyname'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label   class="col-sm-3 control-label">ADDRESS:</label>
                                            <div class="col-sm-9">
                                                <p><?php echo $ArrCompanyRes[0]['address'] ?></p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">CONTACT NO:</label>
                                            <div class="col-sm-9">
                                                <?php echo $ArrCompanyRes[0]['mobile'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label   class="col-sm-3 control-label">EMAIL ID:</label>

                                            <div class="col-sm-9">
                                                <?php echo $ArrCompanyRes[0]['username'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label   class="col-sm-3 control-label">GST NO:</label>
                                            <div class="col-sm-9">

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label   class="col-sm-3 control-label">IE CODE:</label>

                                            <div class="col-sm-9">

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><strong>FROM</strong></h4>
                                <form class="form-horizontal">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">DEPT. NAME:</label>
                                            <div class="col-sm-9">
                                                <?php
                                                $VarUserType = $fromInfo[0]->usertype;
                                                $ArrUserTypes = unserialize(ARRUSERTYPE);
                                                echo $ArrUserTypes[$VarUserType];
                                                //echo '<pre>'; print_r($fromInfo); die('die');
                                                //echo $fromInfo[0]['companyname'];
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">CONT. PERSON:</label>
                                            <div class="col-sm-9">
                                                <?php
                                                echo $fromInfo[0]->contactname;
                                                ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">CONTACT NO:</label>
                                            <div class="col-sm-9">
                                                <?php
                                                echo $fromInfo[0]->mobile;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title"><strong>ISSUE TO</strong></h4>
                                <form class="form-horizontal">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">DEPT. NAME:</label>
                                            <div class="col-sm-9">
                                                <?php echo $ArrCompanyRes[0]['companyname'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">CONT. PERSON:</label>
                                            <div class="col-sm-9">
                                                <?php echo $ArrCompanyRes[0]['companyname'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">CONTACT NO:</label>
                                            <div class="col-sm-9">
                                                <?php echo $ArrCompanyRes[0]['mobile'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center"><strong>DELIVERY REFERENCE</strong></h4>
                                <form class="form-horizontal">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">D.C. NO:</label>
                                            <div class="col-sm-9">
                                                <span class="customcontrol-readonly" id="dcNoHere"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label   class="col-sm-3 control-label">DATE & TIME:</label>
                                            <div class="col-sm-9">
                                                <?php ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">CUTOFF DATE & TIME:</label>
                                            <div class="col-sm-9">
                                                <?php echo $ArrCompanyRes[0]['mobile'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label   class="col-sm-3 control-label">MATERIAL INDENT REF. NO:</label>

                                            <div class="col-sm-9">
                                                <?php echo $ArrCompanyRes[0]['username'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label   class="col-sm-3 control-label">QUEUE NO:</label>
                                            <div class="col-sm-9">

                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                    <div class="box-footer">
                                        <div class="form-group">
                                            <label   class="col-sm-3 control-label">WIP NO:</label>
                                            <div class="col-sm-9">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-footer -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-header with-border">
                    <h3 class="box-title pull-left">MATERIAL ISSUED DETAILS</h3>
                </div>

                <div class="box-body">
                    <label class="">SAMPLE No:
                        <?php
                        //echo '<pre>'; print_r($jsonDataGrid); die('die');
                        $ArrSampleReq = json_decode($jsonDataGrid,true);

                            echo $ArrSampleReq[0][0] . ' / ';
                            echo $ArrSampleReq[0][1] . ' / ';
                            echo $ArrSampleReq[0][2] . ' / ';
                            echo $ArrSampleReq[0][3] . ' / ';
                            echo $ArrSampleReq[0][4];


                        //echo '<pre>'; print_r($bomIssuedTo);
                        //echo '<pre>'; print_r($bomIndentCutOffDatetime);
                        //die('die');
                        ?></label>
                </div>
                <div class="box-body">
                    <div id="bomIndJxl"></div>
                    <div class="card">
                        <div class="card-body">
                            <div class="col-md-3">
                                <label class="control-label">Material Indent Raised by</label>
                                <br/>
                                <?php echo $merchantInfo[0]->contactname; ?>
                                <br/><br/>
                                <label class="control-label">Name & Signature</label>
                                <br/>

                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Material Indent Authorized by</label><br/>
                                <?php echo $mgmtInfo[0]->contactname; ?>
                                <br/><br/>
                                <label class="control-label">Name & Signature</label>
                                <br/>
                                <?php echo '' ?>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Material Issued by</label><br/>
                                <?php echo ' Management 2 ' ?>
                                <br/><br/>
                                <label class="control-label">Name & Signature</label>
                                <br/>
                                <?php echo '' ?>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Material Received by</label><br/>
                                <?php //echo $ArrCompanyInfo[0]['companyname']; ?>
                                <br/><br/>
                                <label class="control-label">Authorized Signatory</label>
                                <br/>
                                <?php echo '' ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-header with-border"><h3></h3></div>
                <div class="box-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="pull-right">
                                <button type="button" class="btn btn-info" id="" onclick="fnSaveDc()">Save Changes</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>


    <!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/ajax.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script>
    var GlbNewItemId = '<?php echo $newItemId ?>';
    var GlbOrderId = '<?php echo $orderId ?>';
    var GlbMatDetail = '<?php echo $matDetail ?>';
    console.log(GlbMatDetail,'GlbMatDetail');
    if(GlbMatDetail !='') {
        GlbMatDetail = JSON.parse(GlbMatDetail);
    }
    else {
        GlbMatDetail = [[]];
    }
    jexcel(document.getElementById('bomIndJxl'), {
        columns: [
            {type: 'text', title: 'Item Description / Blend (%) / Content / Material', width: 400, wordWrap: true, readOnly: true},
            {type: 'text', title: 'Gar. / Label Size', width: 70, wordWrap: true, readOnly: true},
            {type: 'text', title: 'Item Code', width: 150, wordWrap: true, readOnly: true},
            {type: 'text', title: 'Item Color Code', width: 150, wordWrap: true, readOnly: true},
            {type: 'text', title: 'Size / Dim. (W*L*H)', width: 150, wordWrap: true, readOnly: true},
            {type: 'text', title: 'Unit Of Measure', width: 120, wordWrap: true, readOnly: true},
            {type: 'text', title: 'Material Issued Qty.', width: 120, wordWrap: true, readOnly: true},
            {type: 'text', title: 'Unit Of Measure', width: 120, wordWrap: true, readOnly: true},
            {type: 'checkbox', title: 'Select', width: 60},

        ],
        data: GlbMatDetail
    });
    
    function fnSaveDc() {
        //MakeAsynPostRequest(base_path+''+);
    }
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>