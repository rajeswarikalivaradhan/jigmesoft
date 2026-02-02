<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
<body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/admintemplateheader');
    // $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar hide">
        <?php // $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper">
        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">User Department & Designation List
                    <a class="btn btn-default addBtnDetails" href="<?php echo base_url("dashboard/addedituserdesgn") ?>">
                        <i class="fa fa-plus" style="margin-right: 10px"></i>ADD</a>
                </h1>
            </section>
        </div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Search</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form class="form-horizontal" name="frmSearch" id="frmSearch">
                            <div class="box-body pdt20_pdb0">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="frmSrchUserType" class="col-sm-4 control-label">Department</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchUserType" name="frmSrchUserType">
                                                <option value="">Select</option>
                                                <?php
                                                foreach ($ArrUserTypes as $key => $item) {
                                                    echo '<option value="'.$key.'">'.$item.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="frmSrchMStatus" class="col-sm-4 control-label">Status</label>
                                        <div class="col-sm-8">
                                            <select name="frmSrchMStatus" id="frmSrchMStatus" class="form-control">
                                                <option value="">Select</option>
                                                <?php
                                                $ArrStatus = unserialize(ARRSTATUS);
                                                foreach ($ArrStatus as $VarKey => $VarStatus) { ?>
                                                    <option
                                                        value="<?php echo $VarKey ?>"><?php echo $VarStatus ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer boxFooter_pd1025">
                                <button type="button" class="btn btn-default" onclick="refreshPage();">
                                    Reset
                                </button>
                                <button type="button" class="btn btn-info pull-right" onclick="fnSearch();">Search
                                </button>
                            </div><!-- /.box-footer -->
                        </form>
                    </div><!-- /.box -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">List</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <div class="col-sm-12" style="padding: 10px 0 20px 0">
                                <div class="col-sm-9 col-xs-12 no-padding">
                                    <div id="DivTotalCntResult"></div>
                                </div>
                                <div class="col-sm-3 col-xs-12 pull-right no-padding">
                                    <div class="col-sm-8 col-xs-8">
                                        <select name="frmItemStatus" title="change Status" id="frmItemStatus"
                                                class="form-control ">
                                            <option value="">Select</option>
                                            <?php
                                            foreach ($ArrStatus as $VarKey => $VarStatus) { ?>
                                                <option value="<?php echo $VarKey ?>"><?php echo $VarStatus ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4  col-xs-4">
                                        <input type="button" name="btnChangeStatus" id="btnChangeStatus"
                                               class="btn btn-info pull-right" value="Update">
                                    </div>
                                </div>
                            </div>
                            <table id="tableId" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th class="sortable asc" id="0">Department<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="1">Designation<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="2">Status<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Recent Update<i class="fa fa-fw fa-sort"></i></th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                            <section id="pagination_my" class="animated for_animate pdl15 ">
                                <ul class="pagination m-b-none animated for_animate" id="ResPagination"></ul>
                            </section>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/mdesignations.js"></script>
<script>
    fnList();

    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>