<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Lot Inspection Details<a class="btn btn-default addBtnDetails" href="<?php echo base_url().CNFCOMPANY?>mlotinspection/addedit"><i class="fa fa-plus" style="margin-right: 10px"></i> ADD Lot Inspection Detail</a>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url().CNFCOMPANY?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Lot Inspection Details</li>
                <li class="active">Lot Inspection Details List(s)</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Search</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <form class="form-horizontal" name="frmNameSearchFabricType" id="frmNameSearchFabricType">
                            <div class="box-body">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Level</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchLevel" class="form-control" id="frmSrchLevel" placeholder="Level">
                                            <input type="hidden" name="hidddenColumnId" id="hidddenColumnId">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Code Letter</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmSrchCodeL" placeholder="Code Letter">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">AQL</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmSrchAql" placeholder="AQL">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Sample Size</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmSrchSampleS" placeholder="Sample Size">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Status</label>
                                        <div class="col-sm-8">
                                            <select name="frmSrchYarnStatus" id="frmSrchYarnStatus" class="form-control">
                                                <option value="">Select</option>
                                                <?php
                                                $ArrStatus  = unserialize(ARRSTATUS);
                                                unset($ArrStatus[3]);
                                                foreach($ArrStatus as $VarKey=>$VarStatus) {?>
                                                    <option value="<?php echo $VarKey?>" ><?php echo $VarStatus?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-default"
                                        onclick="refreshPage('frmNameSearchFabricType');">Reset
                                </button>
                                <button type="button" class="btn btn-info pull-right" onclick="fnSearch();">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Lot Inspection Detail List</h3>
                        </div>
                        <div class="box-body table-responsive no-padding">

                            <div class="clearfix"></div>

                            <div class="col-sm-12 pdt10">

                                <div class="col-sm-9 col-xs-12 pdl0">

                                    <div id="DivTotalCntResult" class="pdt10"></div>

                                </div>

                                <div class="col-sm-3 col-xs-12 pull-right mpull-left no-padding">

                                    <div class="col-sm-9 col-xs-9 no-padding">
                                        <select name="frmItemStatus" id="frmItemStatus" class="form-control ">

                                            <option value="">Select</option>

                                            <option value="1">Activate</option>

                                            <option value="2">Deactivate</option>

                                        </select>

                                        <div class="herr" id="ErrItemStatus"></div>

                                    </div>

                                    <div class="col-sm-3  col-xs-3 pdr0">

                                        <input type="button" name="btnChangeStatus" id="btnChangeStatus" class="btn btn-info pull-right" value="Update">

                                    </div>

                                </div>

                            </div>
                            <div id="DivTotalCntResult" class="pd10"></div>
<div id="ResResult"></div>
                            <table id="mLotInspectionList" class="table table-bordered table-hover"><thead><tr>
                                    <th></th>
                                    <th class="sortable asc" id="0">Level<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="1">Code Letter<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="2">AQL<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Sample Size<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="4">Status<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="5">Updated By<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="6">Recent Update<i class="fa fa-fw fa-sort"></i></th>
                                    <th>Action</th></tr></thead></table>
                            <div>
                                <section id="pagination_my" class="animated for_animate pdl15 ">
                                    <ul class="pagination m-b-none animated for_animate" id="ResPagination"></ul>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url();?>assets/js/<?php echo CNFCOMPANY?>mlotinspection.js"></script>
<script language="javascript">
    fnList();
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>