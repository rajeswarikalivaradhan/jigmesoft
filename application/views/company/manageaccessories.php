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
                Manage Accessories
                <a class="btn btn-default addBtnDetails" href="<?php echo base_url().CNFCOMPANY?>maccessories/addedit"><i class="fa fa-plus" style="margin-right: 10px"></i> ADD ACCESSORIES</a>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url().CNFCOMPANY?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Manage Accessories</li>
                <li class="active">Accessories List(s)</li>
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
                        <form class="form-horizontal" name="frmNameSearchAccessoriesType" id="frmNameSearchAccessoriesType">
                            <div class="box-body">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchAccessoriesName" class="form-control" id="frmSrchAccessoriesName" placeholder="Accessories">
                                            <input type="hidden" name="hidddenColumnId" id="hidddenColumnId">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Status</label>
                                        <div class="col-sm-8">
                                            <select name="frmSrchAccessoriesStatus" id="frmSrchAccessoriesStatus" class="form-control">
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
                                        onclick="refreshPage('frmNameSearchAccessories');">Reset
                                </button>
                                <button type="button" class="btn btn-info pull-right" onclick="fnSearchAccessories();">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Accessories List</h3>
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
                            <table id="tableList" class="table table-bordered table-hover"><thead><tr>
                                    <th></th>
                                    <th class="sortable asc" id="0">Accessories Name<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="1">Status<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="2">Updated By<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Recent Update<i class="fa fa-fw fa-sort"></i></th>
                                    <th>Action</th></tr></thead>
                            </table>
                            <div id="ResResult"></div>
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
<script src="<?php echo base_url();?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<script src="<?php echo base_url();?>assets/js/<?php echo CNFCOMPANY?>maccessories.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<script language="javascript">fnListAccessories();</script>
<script src="<?php echo base_url();?>assets/plugins/datatables/jquery.dataTables.min.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables/dataTables.bootstrap.min.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>