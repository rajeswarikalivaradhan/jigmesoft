<?php $this->load->view(CNFCADMIN . 'template/pageheader'); ?>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCADMIN . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCADMIN . 'template/templateleftmenu'); ?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="col-md-12">
                <h1 class="firstHeading">
                    Manage Company(s)
                    <a class="btn btn-default addBtnDetails"
                       href="<?php echo base_url() . CNFCADMIN ?>company/addeditcompany"><i class="fa fa-plus"
                                                                                            style="margin-right: 10px"></i>
                        ADD
                        COMPANY</a>
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
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form class="form-horizontal" name="frmNameSearchCompany" id="frmNameSearchCompany">
                            <div class="box-body pdt20_pdb0">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Company Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchCompanyName" class="form-control"
                                                   id="frmSrchCompanyName" placeholder="Company Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Company Code</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchComCode" class="form-control"
                                                   id="frmSrchComCode" placeholder="Company Code">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Mobile No</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchContactMobile" class="form-control"
                                                   id="frmSrchContactMobile" placeholder="Phone No">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">E-Mail Id</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchContactEmail" class="form-control"
                                                   id="frmSrchContactEmail" placeholder="E-Mail Id">
                                            <input type="hidden" name="hiddenAlpha" id="hiddenAlpha">
                                            <input type="hidden" name="hidddenColumnId" id="hidddenColumnId">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Business Type</label>
                                        <div class="col-sm-8">
                                            <select name="frmSrchBusinessType" id="frmSrchBusinessType"
                                                    class="form-control">
                                                <option value="">Choose the Business Type</option>
                                                <?php
                                                $ArrBusinessTypeList = unserialize(ARRCOMPANYBUSINESSTYPE);
                                                foreach ($ArrBusinessTypeList as $VarBusinessId => $VarBusinessName) { ?>
                                                    <option
                                                        value="<?php echo $VarBusinessId ?>"><?php echo $VarBusinessName ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">City</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchContactCity" class="form-control"
                                                   id="frmSrchContactCity" placeholder="City">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">State</label>
                                        <div class="col-sm-8">
                                            <select id="frmSrchContactState" class="form-control">
                                                <option value="">Choose the State</option>
                                                <?php
                                                foreach ($states as $state) { ?>
                                                    <option
                                                        value="<?php echo $state['id'] ?>"><?php echo $state['statename'] ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Country</label>
                                        <div class="col-sm-8">
                                            <select name="frmSrchCountry" id="frmSrchCountry" class="form-control">
                                                <option value="">Choose the Country</option>
                                                <?php
                                                foreach ($countries as $VarCountryId => $VarCountryName) { ?>
                                                    <option
                                                        value="<?php echo $VarCountryId ?>"><?php echo $VarCountryName->countryname ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer boxFooter_pd1025">
                                <button type="button" class="btn btn-default"
                                        onclick="refreshPage('frmNameSearchCompany');">Reset
                                </button>
                                <button type="button" class="btn btn-info pull-right" onclick="fnSearchCompany();">
                                    Search
                                </button>
                            </div><!-- /.box-footer -->
                        </form>
                    </div><!-- /.box -->
                    <div class="box box-info">
                        <div class="box-header">
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
                                                class="form-control">
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
                            <table id='companyListTable' class='table table-bordered table-hover'>
                                <thead>
                                <tr>
                                    <td></td>
                                    <th class='sortable asc' id="0">Company Name <i class="fa fa-fw fa-sort"></i></th>
                                    <th class='sortable asc' id="1">Mobile No<i class="fa fa-fw fa-sort"></i></th>
                                    <th class='sortable asc' id="2">E-mail Id<i class="fa fa-fw fa-sort"></i></th>
                                    <th class='sortable asc' id="3"><i class="fa fa-fw fa-sort"></i>Business Type</th>
                                    <th class='sortable asc' id="4"><i class="fa fa-fw fa-sort"></i>Factory Ownership
                                    </th>
                                    <th class="sortable asc" id="5"><i class="fa fa-fw fa-sort"></i>City</th>
                                    <th class="sortable asc" id="6"><i class="fa fa-fw fa-sort"></i>State</th>
                                    <th class="sortable asc" id="7"><i class="fa fa-fw fa-sort"></i>Country</th>
                                    <th class="sortable asc" id="8"><i class="fa fa-fw fa-sort"></i>Last Update</th>
                                    <th class="sortable asc" id="9"><i class="fa fa-fw fa-sort"></i>Status</th>
                                    </th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                </tbody></table>
                            <section id="pagination_my" class="animated for_animate pdl15 pdb15">
                                <ul class="pagination m-b-none animated for_animate" id="ResPagination"></ul>
                            </section>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCADMIN . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/js/<?php echo CNFCADMIN ?>companyprofile.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script>
    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });
    fnListCompany();
</script>
<?php $this->load->view(CNFCADMIN . 'template/pagefooter'); ?>