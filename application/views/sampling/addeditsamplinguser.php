<?php $this->load->view(CNFCOMPANY.'template/pageheader'); ?>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Add/Edit Sampling Users</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url()?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?php echo base_url("msampling/managesamplingusers") ?>">Sampling user</a></li>
                <li class="active">Add/Edit Sampling user</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Basic Information</h3>
                                <div class="box-tools pull-right">
                                    <?php if($VarNew==0) { ?>
                                        <a class="btn btn-default btn-s addrights" href="javascript:void(0);" onclick="fnShowHideEndUserSub(1,'divEditBasicInfo');"><i class="fa fa-edit"></i> Edit</a>
                                    <?php } ?>
                                </div>
                            </div><!-- /.box-header -->
                            <div class="box-body ">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                    <div id="divEditBasicInfo" class="<?php if($VarNew==1) {?>show<?php } else {?>hide<?php }?>">
                                        <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="frmBasicName" class="form-control" id="frmBasicName" placeholder="Name" value="<?php echo @$ArrBasicInfo['contactname'];?>">
                                                <div class="herr" id="ErrfrmBasicName"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">E-mail Id</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="frmBasicEmailid" class="form-control" id="frmBasicEmailid" placeholder="E-mail Id" <?php echo !empty($ArrBasicInfo['username']) ? 'readonly' : '' ?> value="<?php echo @$ArrBasicInfo['username'];?>">
                                                <div class="herr" id="ErrfrmBasicEmailid"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Mobile No</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="frmBasicMobile" class="form-control" id="frmBasicMobile" placeholder="Mobile No" value="<?php echo @$ArrBasicInfo['mobile'];?>">
                                                <div class="herr" id="ErrfrmBasicMobile"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                                            <div class="col-sm-10">
                                                <select name="frmBasicStatus" id="frmBasicStatus" class="form-control">
                                                    <option value="">Select</option>
                                                    <?php
                                                    $ArrStatus  = unserialize(ARRSTATUS);
                                                    unset($ArrStatus[3]);
                                                    foreach($ArrStatus as $VarKey=>$VarStatus) {?>
                                                        <option value="<?php echo $VarKey?>" <?php if(@$ArrBasicInfo['status']==$VarKey) {echo "selected";}?>><?php echo $VarStatus?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="herr" id="ErrBasicStatus"></div>
                                            </div>
                                        </div>
                                        <div class="box-footer nopadding">
                                            <button type="button" class="btn btn-default" onclick="fnShowHideEndUserSub(1,'divShowBasicInfo');">Cancel</button>
                                            <button type="submit" class="btn btn-info pull-right addrights" onclick="return fnSaveSamplingUserInfo();">Save Changes</button>
                                        </div><!-- /.box-footer -->
                                    </div>
                                    <div id="divShowBasicInfo" class="<?php if($VarNew==1) {?>hide<?php }?>">
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 text-right">Name</label>
                                            <div class="col-sm-10" id="divDispBasicName">
                                                <?php echo @$ArrBasicInfo['contactname'];?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 text-right">E-mail Id</label>
                                            <div class="col-sm-10" id="">
                                                <?php echo @$ArrBasicInfo['username'];?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 text-right">Mobile No</label>
                                            <div class="col-sm-10" id="">
                                                <?php echo @$ArrBasicInfo['mobile'];?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 text-right">Status</label>
                                            <div class="col-sm-10"  id="divDispStatus">
                                                <?php
                                                echo @$ArrStatus[$ArrBasicInfo['status']];
                                                ?>
                                            </div>
                                        </div><!-- /.form-group -->
                                    </div>
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
    var GlbId          	        = "<?php echo @$VarId;?>";
    var GlbSearchParam='';
    var GlbFilterAlpha=''; var GlbSortOrder=''; var GlbColumnId='';
    function fnShowHideEndUserSub(VarType,VarDivShow) {
        var ArrProfileBasicList = ["divEditBasicInfo","divShowBasicInfo"];
        if(VarType==1) {
            var ArrFnalList	= ArrProfileBasicList;
        }
        //Remove Class
        for(i=0;i<ArrFnalList.length;i++) {
            $("#"+ArrFnalList[i]).removeClass('show');
            $("#"+ArrFnalList[i]).removeClass('hide');
        }
        //Add Class
        for(i=0;i<ArrFnalList.length;i++) {
            if(VarDivShow!=ArrFnalList[i]) {
                $("#"+ArrFnalList[i]).addClass('hide');
            }
        }
        $("#"+VarDivShow).addClass('show');
    }

    function fnSaveSamplingUserInfo() {
        try {
            $('.form-control').css("border", "1px solid #cccccc");
            $('div.herr').html('');
            var ProfileFormData							= false;
            var frmBasicName     					    = $("#frmBasicName").val();
            var frmBasicEmailid     					= $("#frmBasicEmailid").val();
            var frmBasicMobile     					    = $("#frmBasicMobile").val();
            var Status        							= $("#frmBasicStatus").val();
            if(jsTrim(frmBasicName)== "") {
                $('#ErrfrmBasicName').html("Please fill the Name");
                $('#frmBasicName').focus();
                $('#frmBasicName').css("border", "1px solid #B94A48");
                return false;
            }
            if(jsTrim(frmBasicEmailid)== "") {
                $('#ErrfrmBasicEmailid').html("Please fill the E-mail Id");
                $('#frmBasicEmailid').focus();
                $('#frmBasicEmailid').css("border", "1px solid #B94A48");
                return false;
            }
            if(jsTrim(Status)== ""){
                $('#ErrBasicStatus').html("Please choose the status");
                $('#frmBasicStatus').focus();
                $('#frmBasicStatus').css("border", "1px solid #B94A48");
                return false;
            }
            if (window.FormData) {
                ProfileFormData								= new FormData();
                ProfileFormData.append("n",frmBasicName);
                ProfileFormData.append("e",frmBasicEmailid);
                ProfileFormData.append("m",frmBasicMobile);
                ProfileFormData.append("s",Status);
                ProfileFormData.append("id",GlbId);
            }
            $.ajax({
                url 		: base_path+'msampling/updateSamplingUser',
                data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
                async       : false,
                dataType    : 'json',
                contentType : false,
                processData : false,
                type        : 'POST',
                success     : function(data, textStatus, jqXHR){
                    console.log(data,'data');
                    //data = JSON.parse(data);
                    fnSaveRes(data);
                }
            });
            return false;
        } catch(e) {
            alert(e);
        }
    }

    function fnSaveRes(data) {
        if(data!='') {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if(data.errcode == "-1") {
                $('#ErrBasicStatus').text(data.msg);
                return false;
            } else if(data.errcode==1){
                GlbId       = data.id;
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").html("Sampling User has updated successfully!");
                fnRedirectPageTimeOut(base_path+'msampling/addeditsamplinguser/'+data.eid);
            }
        }
    }

</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>