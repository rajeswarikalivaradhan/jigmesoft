<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
<body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/admintemplateheader');
    // $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar hide">
        <?php // $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="col-md-12">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1 class="firstHeading">CAD
                    User <?php if (empty($Edit) && !empty($VarId)) echo ''; else echo '- Add / Edit' ?></h1>
            </section>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Basic Information</h3>
                                <div class="box-tools pull-right">
                                </div>
                            </div><!-- /.box-header -->
                            <div class="box-body pdt20_pdb0">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                    <?php
                                    if ($Edit == 'edit' || empty($VarId)) {
                                        ?>
                                        <div class="alert alert-success alert-dismissable hide"
                                             id="divSuccessBasicInfoMsg"></div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="frmBasicName" class="form-control"
                                                           id="frmBasicName" placeholder="Name"
                                                           value="<?php echo @$ArrBasicInfo['contactname']; ?>">
                                                    <div class="herr" id="ErrfrmBasicName"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="frmBasicDesgn" class="col-sm-4 control-label">Desgination</label>
                                                <div class="col-sm-8">
                                                    <select name="frmBasicDesgn" id="frmBasicDesgn"
                                                            class="form-control">
                                                        <option value="">Select</option>
                                                        <?php
                                                        $VarSelected = '';
                                                        foreach ($ArrDesgn as $VarKey => $VarItem) {
                                                            if (@$ArrBasicInfo['desgnid'] == $VarItem['designationid'])
                                                                $VarSelected = 'selected';
                                                            echo "<option value=".$VarItem['designationid']." ".$VarSelected.">".$VarItem["desgn"]."</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="herr" id="ErrfrmBasicDesgn"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">E-mail
                                                    Id</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="frmBasicEmailid" class="form-control"
                                                           id="frmBasicEmailid"
                                                           placeholder="E-mail Id" value="<?php echo @$ArrBasicInfo['username']; ?>">
                                                    <div class="herr" id="ErrfrmBasicEmailid"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">

                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Mobile
                                                    No.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="frmBasicMobile" class="form-control"
                                                           id="frmBasicMobile" placeholder="Mobile No."
                                                           value="<?php echo @$ArrBasicInfo['mobile']; ?>">
                                                    <div class="herr" id="ErrfrmBasicMobile"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Status</label>
                                                <div class="col-sm-8">
                                                    <select name="frmBasicStatus" id="frmBasicStatus"
                                                            class="form-control">
                                                        <option value="">Select</option>
                                                        <?php
                                                        foreach ($ArrStatus as $VarKey => $VarStatus) { ?>
                                                            <option
                                                                value="<?php echo $VarKey ?>" <?php if (@$ArrBasicInfo['status'] == $VarKey) {
                                                                echo "selected";
                                                            } ?>><?php echo $VarStatus ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="herr" id="ErrBasicStatus"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 herr" id="AnyOtherErr"></div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Name</label>
                                                <div class="col-sm-8" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['contactname']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Desgination</label>
                                                <div class="col-sm-8" id="">
                                                    <?php echo @$VarDesignation; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">E-mail Id</label>
                                                <div class="col-sm-8" id="">
                                                    <?php echo @$ArrBasicInfo['username']; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Mobile No</label>
                                                <div class="col-sm-8" id="">
                                                    <?php echo @$ArrBasicInfo['mobile']; ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Status</label>
                                                <div class="col-sm-8" id="divDispStatus">
                                                    <?php
                                                    echo @$ArrStatus[$ArrBasicInfo['status']];
                                                    ?>
                                                </div>
                                            </div><!-- /.form-group -->
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </form>
                            </div><!-- /.box-body -->
                            <div class="box-footer boxFooter_pd1025">
                                <a href="<?php echo base_url(CNFCOMPANY . 'mcaduser/manage') ?>"
                                   class="btn btn-default">Back</a>
                                <button type="submit" class="btn btn-info pull-right <?php if ($Edit != 'edit' && !empty($VarId)) echo 'hide' ?>"
                                        onclick="return fnSaveUserInfo();">Save Changes
                                </button>
                            </div><!-- /.box-footer -->
                        </div><!-- /. box -->
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script type="text/javascript">
    GlbId = "<?php echo $VarId ?>";
    function fnSaveUserInfo() {
        try {
            $('.form-control').css("border", "1px solid #cccccc");
            $('div.herr').text('');
            var ProfileFormData = false;
            var frmBasicName = $("#frmBasicName").val();
            var frmBasicEmailid = jsTrim($("#frmBasicEmailid").val());
            var frmBasicMobile = jsTrim($("#frmBasicMobile").val());
            var frmBasicDesgn = $("#frmBasicDesgn").val();
            var Status = $("#frmBasicStatus").val();
            if (jsTrim(frmBasicName) == "") {
                $('#ErrfrmBasicName').text("Please fill the Name");
                $('#frmBasicName').focus();
                $('#frmBasicName').css("border", "1px solid #B94A48");
                return false;
            }
            if(frmBasicDesgn == "") {
                $('#ErrfrmBasicDesgn').text("Please select the designation");
                $('#frmBasicDesgn').focus();
                $('#frmBasicDesgn').css("border", "1px solid #B94A48");
                return false;
            }
            if (frmBasicEmailid == "") {
                $('#ErrfrmBasicEmailid').text("Please fill the E-mail Id");
                $('#frmBasicEmailid').focus();
                $('#frmBasicEmailid').css("border", "1px solid #B94A48");
                return false;
            }
            if(isEmail(frmBasicEmailid) === false) {
                $('#ErrfrmBasicEmailid').text("Please fill valid E-mail Id");
                $('#frmBasicEmailid').focus();
                $('#frmBasicEmailid').css("border", "1px solid #B94A48");
                return false;
            }
            if(frmBasicMobile == "") {
                $('#ErrfrmBasicMobile').text("Please fill the Mobile No.");
                $('#frmBasicMobile').focus();
                $('#frmBasicMobile').css("border", "1px solid #B94A48");
                return false;
            }
            if(isPhoneNumber(frmBasicMobile) === false) {
                $('#ErrfrmBasicMobile').text("Please fill valid Mobile No.");
                $('#frmBasicMobile').focus();
                $('#frmBasicMobile').css("border", "1px solid #B94A48");
                return false;
            }
            if (jsTrim(Status) == "") {
                $('#ErrBasicStatus').text("Please choose the status");
                $('#frmBasicStatus').focus();
                $('#frmBasicStatus').css("border", "1px solid #B94A48");
                return false;
            }
            if (window.FormData) {
                ProfileFormData = new FormData();
                ProfileFormData.append("n", frmBasicName);
                ProfileFormData.append("e", frmBasicEmailid);
                ProfileFormData.append("m", frmBasicMobile);
                ProfileFormData.append("did", frmBasicDesgn);
                ProfileFormData.append("s", Status);
                ProfileFormData.append("id", GlbId);
            }
                $.ajax({
                    url: base_path + GlbCompanyFdr + 'mcaduser/updateUser',
                    data: ProfileFormData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        console.log(data, 'data');
                        fnSaveRes(data);
                    }
                });

            return false;
        } catch (e) {
            alert(e);
        }
    }
    function fnSaveRes(data) {
        if (data != '') {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if (data.errcode == -1) {
                $('#AnyOtherErr').text(data.msg);
                return false;
            } else if (data.errcode == 1) {
                GlbId = data.id;
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("Updated successfully");
                fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'mcaduser/addedit/' + data.eid);
            }
        }
    }
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>