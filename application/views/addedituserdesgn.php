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
                <h1 class="firstHeading">User Department & Designation <?php if (empty($Edit) && !empty($VarId)) echo ''; else echo '- Add / Edit' ?></h1>
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
                                        //echo $ArrBasicInfo['usertypeid'];
                                        //echo '<pre>'; print_r($ArrUserTypes); die('die');
                                        ?>
                                        <div class="alert alert-success alert-dismissable hide"
                                             id="divSuccessBasicInfoMsg"></div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="frmBasicUserType" class="col-sm-4 control-label">Department</label>
                                                <div class="col-sm-8">
                                                    <select name="frmBasicUserType" id="frmBasicUserType"
                                                            class="form-control">
                                                        <option value="">Select</option>
                                                        <?php
                                                        foreach ($ArrUserTypes as $VarKey => $VarItem) {
                                                            ?>
                                                            <option value="<?php echo $VarKey?>" <?php if(!empty($ArrBasicInfo['usertypeid'])) if($ArrBasicInfo['usertypeid'] == $VarKey) echo "selected" ?>>
                                                                <?php echo $VarItem ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="herr" id="ErrfrmBasicUserType"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Designation</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="frmBasicDesgn" class="form-control"
                                                           id="frmBasicDesgn" placeholder="Designation"
                                                           value="<?php echo @$ArrBasicInfo['desgn']; ?>">
                                                    <div class="herr" id="ErrfrmBasicDesgn"></div>
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
                                                                value="<?php echo $VarKey ?>" <?php if (@$ArrBasicInfo['desgn_status'] == $VarKey) {
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
                                                <label for="" class="col-sm-4 text-right">Department</label>
                                                <div class="col-sm-8" id="divDispBasicName">
                                                    <?php echo @$ArrUserTypes[$ArrBasicInfo['usertypeid']] ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Designation</label>
                                                <div class="col-sm-8" id="">
                                                    <?php echo @$ArrBasicInfo['desgn']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Status</label>
                                                <div class="col-sm-8" id="divDispStatus">
                                                    <?php
                                                    echo @$ArrStatus[$ArrBasicInfo['desgn_status']];
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
                                <a href="<?php echo base_url('dashboard/manageDesignations') ?>"
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
            var frmBasicUserType = $("#frmBasicUserType").val();
            var frmBasicDesgn = jsTrim($("#frmBasicDesgn").val());
            var Status = $("#frmBasicStatus").val();
            if (frmBasicUserType == "" || frmBasicUserType == 0) {
                $('#ErrfrmBasicUserType').text("Please select User Type");
                $('#frmBasicUserType').focus();
                $('#frmBasicUserType').css("border", "1px solid #B94A48");
                return false;
            }
            if(frmBasicDesgn == "") {
                $('#ErrfrmBasicDesgn').text("Please fill the desgination");
                $('#frmBasicDesgn').focus();
                $('#frmBasicDesgn').css("border", "1px solid #B94A48");
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
                ProfileFormData.append("ut", frmBasicUserType);
                ProfileFormData.append("ds", frmBasicDesgn);
                ProfileFormData.append("s", Status);
                ProfileFormData.append("id", GlbId);
            }
            $.ajax({
                url: base_path +  'dashboard/updateUserDesgn',
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
                fnRedirectPageTimeOut(base_path +  'dashboard/addedituserdesgn/' + data.eid);
            }
        }
    }
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>