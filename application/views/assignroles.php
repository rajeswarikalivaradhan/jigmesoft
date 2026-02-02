<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
<!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
<body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/admintemplateheader');
    // $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar hide">
        <?php // $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Add/Edit Management Roles</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url().CNFCOMPANY?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <!--<li><a href="<?php /*echo base_url().CNFCOMPANY*/?>management/managemanagement/">Management</a></li>
                <li class="active">Add/Edit Management</li>-->
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Management</h3>
                                <div class="box-tools pull-right"></div>
                            </div><!-- /.box-header -->
                            <div class="box-body ">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                    <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                                    <?php
                                    //echo '<pre>';
                                    //echo '<pre>'; print_r($ArrData); die;
                                    //print_r($ArrData['mgmtusers']); die('');
                                    foreach ($ArrData['mgmtusers'] as $users) { ?>
                                            <div class="form-group">
                                                <div class="col-sm-10">
                                                    <label for="_<?php echo $users['id'] ?>" class="col-sm-3 control-label"><?php echo $users['n'] ?></label>
                                                    <?php
                                                    foreach ($users['modules'] as $key => $module) {
                                                        //echo @$ArrData['roles'][$users['id']][$key];
                                                        //echo ' key is '.$key;
                                                        //echo '<br/>';
                                                        ?>
                                                        <!--<input type="checkbox" <?php /*//echo (@$ArrData['roles'][$users['id']][$key] == $key) ? 'checked' : ''; */?> name="frmBasicModuleId[]" id="_<?php /*echo $users['id'] */?>" value="1">-->
                                                        <input type="checkbox" <?php if(!empty($users['datas'])) {if($key == 1) { if($users['datas'][0]->enquiryrole == 1) echo 'checked'; } elseif ($key == 2) if ($users['datas'][0]->cadrole == 1) echo 'checked'; } ?> name="frmBasicModuleId[]" id="_<?php echo $users['id'] ?>" value="1">
                                                        <?php echo $module;
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                    <?php } ?>
                                    <div class="herr" id="ErrfrmRoles"></div>
                                    <input type="submit" id="" class="btn btn-info pull-right addrights" onclick="return fnSaveRoles()">
                                </form>
                            </div><!-- /.box-body -->
                        </div><!-- /. box -->
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->

<script type="text/javascript">
    var GlbParam = "rfrom=1";
    var GlbEditMode = "<?php echo $ArrData['VarEditMode'] ?>";
    function fnSaveRoles() {
        var vals = [];
        //var vals = {};
        //console.log($("input[name='frmBasicModuleId[]']").val(),'ram');
        //var i = 0;
        $('input[name="frmBasicModuleId[]"]').map(function () {
            if($(this).prop('checked') == true) {
                //i++;
                //console.log(i);
                var cbvalnid = $(this).val() + $(this).attr('id');
                //var uid = $(this).attr('id');
                //vals[i+'_'+uid] = $(this).val();
                vals.push(cbvalnid);
            }
            else {
                var cbvalnid = 0 + $(this).attr('id');
                vals.push(cbvalnid);
            }
        }
        ).get();

/*        console.log(vals,'vals');
        console.log(vals.length,'vals length');*/
        /*var newarr = {};*/
        //console.log(Object.keys(vals),'obj keys');
        /*for(var x = 0; x < vals.length; x++) {
            var ids = vals[x].substr(vals[x].indexOf('_')+1);
            var moduleid = vals[x].substr(0,vals[x].indexOf('_'));*/
            //console.log(vals[x]);
            /*console.log(ids,'ids');
            console.log(moduleid,'moduleid');*/
            //newarr[ids] =
        /*}*/

        MakePostRequest(base_path+'management/updateMgmtRoles',GlbParam+"&r="+JSON.stringify(vals)+"&editmode="+GlbEditMode,'json',fnSaveRolesRes);
        return false;
    }

    function fnSaveRolesRes(data) {
        //console.log(data,'data');
        if (data != '') {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if (data.errcode == -1) {
                $('#ErrfrmRoles').text(data.msg);
                return false;
            } else if (data.errcode == 1) {
                GlbId = data.id;
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text(data.msg);
                fnRedirectPageTimeOut(base_path + 'management/assignroles/edit');
            }
        }
    }

    function getRoles() {
        MakePostRequest(base_path+'management/getMgmtRoles',GlbParam,'json',getRolesRes);
    }

    function getRolesRes(data) {
        if (data != '') {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if (data.errcode == -1) {

                return false;
            } else if (data.errcode == 1) {

            }
        }
    }
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>