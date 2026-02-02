<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/datepicker/datepicker3.css">
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
                Bill Of Materials Sourcing & Supplier<a class="btn btn-default addBtnDetails" href="<?php echo base_url().CNFCOMPANY?>mbomsourcing/addedit"><i class="fa fa-plus" style="margin-right: 10px"></i> ADD BOM Sourcing & Supplier</a>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url().CNFCOMPANY?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>BOM Sourcing & Supplier</li>
                <li class="active">BOM Sourcing & Supplier List(s)</li>
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
                        <?php
                        $ArrStatus  = unserialize(ARRSTATUS);
                        unset($ArrStatus[3]);
                        ?>
                        <form class="form-horizontal" name="frmNameSearchBom" id="frmNameSearchBom">
                            <div class="box-body">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Contact Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchBomName" class="form-control" id="frmSrchBomName" placeholder="Sourcing Detail">
                                            <input type="hidden" name="hidddenColumnId" id="hidddenColumnId">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Vendor Address</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchBomName" class="form-control" id="frmSrchSuppName" placeholder="Supplier Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">E-mail Id</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchUid" class="form-control" id="frmSrchUid" placeholder="User Id">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Phone No</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchPwdExp" class="form-control" id="frmSrchPwdExp" placeholder="Password Expiry Date">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Mobile No</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchPwdExp" class="form-control" id="frmSrchPwdExp" placeholder="Password Expiry Date">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Status</label>
                                        <div class="col-sm-8">
                                            <select name="frmSrchBomStatus" id="frmSrchBomStatus" class="form-control">
                                                <option value="">Select</option>
                                                <?php
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
                                        onclick="refreshPage('frmNameSearchBom');">Reset
                                </button>
                                <button type="button" class="btn btn-info pull-right" onclick="fnSearchBom();">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Bill Of Materials Sourcing & Supplier List</h3>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <div class="clearfix"></div>

                            <div class="col-sm-12 pdt10">

                                <div class="col-sm-9 col-xs-12 pdl0">

                                    <div id="DivTotalCntResult" class="pdt10"></div>

                                </div>

                                <div class="col-sm-3 col-xs-12 pull-right mpull-left no-padding">
                                    <div id="ResResult"></div>

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

                            <table id="mBomSrcList" class="table table-bordered table-hover"><thead><tr>
                                    <th></th>
                                    <th class="sortable asc" id="0">Supplier Name<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="1">Vendor Address<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="2">E-mail Id<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Phone No<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="4">Mobile No<i class="fa fa-fw fa-sort"></i></th>
                                    <!--<th class="sortable asc" id="5">Website<i class="fa fa-fw fa-sort"></i></th>-->
                                    <th class="sortable asc" id="5">Updated By<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="6">Status<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="7">Recent Update<i class="fa fa-fw fa-sort"></i></th>
                                    <th>Action</th></tr></thead>
                            </table>
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
<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/js/<?php echo CNFCOMPANY?>mbomsrc.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
<script>
    fnListBom();

    $(document).ajaxStart(function(a){
        $.LoadingOverlay("show",{image: base_path+"assets/img/fullpage.gif"});
    });

    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

</script>

<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>