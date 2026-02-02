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
        <section class="content-header">
            <div class="col-md-12">
                <h1 class="firstHeading">
                    Testing Authority<a class="btn btn-default addBtnDetails"
                                        href="<?php echo base_url(CNFCOMPANY . "mauth/addedittestingauth") ?>">
                        <i class="fa fa-plus" style="margin-right: 10px"></i>ADD
                    </a>
                </h1>
            </div>
        </section>
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
                        </div>
                        <form class="form-horizontal" name="frmNameSearch" id="frmNameSearch">
                            <div class="box-body pdt20_pdb0">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for=""
                                               class="col-sm-4 control-label">Testing Authority</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="frmSrchAuths"
                                                   placeholder="Testing Authority">
                                            <div id="ErrfrmSrchAuths" class="herr"></div>
                                            <input type="hidden" name="hidddenColumnId" id="hidddenColumnId">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="frmSrchStatus" class="col-sm-4 control-label">Status</label>
                                        <div class="col-sm-8">
                                            <select name="frmSrchStatus" id="frmSrchStatus" class="form-control">
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
                            </div>
                            <div class="box-footer boxFooter_pd1025">
                                <button type="button" class="btn btn-default"
                                        onclick="refreshPage('frmNameSearch');">Reset
                                </button>
                                <button type="button" class="btn btn-info pull-right" onclick="fnSearchTestingAuth();">
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">List</h3>
                        </div>
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
                            <table id="mTableId" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th class="sortable asc" id="0">Testing Authority<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="1">Status<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="2">Updated By<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Recent Update<i class="fa fa-fw fa-sort"></i></th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                            <section id="pagination_my" class="animated for_animate pdl15 ">
                                <ul class="pagination m-b-none animated for_animate" id="ResPagination"></ul>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script>
    var GlbType = '<?php echo $arrdata['type'] ?>';
</script>
<script src="<?php echo base_url(); ?>assets/js/<?php echo CNFCOMPANY ?>mauth.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script type="text/javascript">
    fnTestingList();
    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>