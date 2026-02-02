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
                <h1></h1>
            </section>
        </div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Team Members</h3>
                            </div>
                            <div class="box-body pdt20_pdb0 box-comments">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                <table id="tableId" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th class="sortable asc" id="0">Name<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="1">Designation<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="2">E-mail Id<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="3">Mobile No<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="4">Status<i class="fa fa-fw fa-sort"></i></th>
                                    </tr>
                                    </thead>
                                    <?php
                                    //echo '<pre>';
                                    //print_r(@$ArrExistingMates);
                                    //print_r(@$ArrNotFreeTeamMates);
                                    //print_r(@$ArrFreeTeamMates);
                                    foreach ($ArrBasicInfo as $item) {
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" id="<?php echo $item['id'] ?>"
                                                       class="merchantSel" style="float: left" <?php if(!empty($ArrSavedTeams)) if(in_array($item['id'], $ArrSavedTeams)) echo 'checked' ?>
                                                       name="<?php echo $item['id'] ?>">
                                            </td>
                                            <td><?php echo $item['contactname']; ?></td>
                                            <td><?php echo $item['desgn']; ?></td>
                                            <td><?php echo $item['username']; ?></td>
                                            <td><?php echo $item['mobile']; ?></td>
                                            <td><?php echo $ArrStatus[$item['status']]; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                                    <div class="alert alert-success alert-dismissable hide"
                                         id="divSuccessBasicInfoMsg"></div>
                                </form>
                            </div>
                            <div class="herr" id="AnyOtherErr"></div>
                            <div class="box-footer boxFooter_pd1025">
                                <a href="<?php echo base_url(CNFCOMPANY.'merteam/teamList') ?>"
                                   class="btn btn-default">Back</a>
                                <button type="button"
                                        class="btn btn-info pull-right"
                                        onclick="return fnSaveTeamMates();">Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">
    const GlbTeamId = "<?php echo $VarTeamId; ?>";
    const GlbHashId = '<?php echo $VarHashId ?>';
    function fnSaveTeamMates() {
        var checkBoxIds = [];
        var checkBoxRemovedIds = [];
        $(".merchantSel").each(function () {
            var $this = $(this);
            if ($this.is(":checked")) {
                checkBoxIds.push($this.attr("id"));
            }
            else {
                checkBoxRemovedIds.push($this.attr("id"));
            }
        });
        console.log(checkBoxIds, 'checkBoxIds');
        console.log(checkBoxRemovedIds, 'checkBoxRemovedIds');
        var Param = "rfrom=1&checkedIds=" + JSON.stringify(checkBoxIds) + "&rem="+ JSON.stringify(checkBoxRemovedIds)+"&teamid=" + GlbTeamId;
        MakeAsynPostRequest(base_path + GlbCompanyFdr + 'merteam/updateTeamMates', Param, "json", function (data) {
            console.log(data, 'data');
            if (data != '') {
                if (data.errCode != undefined) {
                    if (data.errCode == '404') {
                        fnCallSessionExpire();
                        return false;
                    }
                }
                if(data.errCode === 1) {
                    $("#divSuccessBasicInfoMsg").removeClass('hide');
                    $("#divSuccessBasicInfoMsg").text("Saved successfully");
                    fnRedirectPageTimeOut(base_path+GlbCompanyFdr+"merteam/addeditteammates/"+GlbHashId);
                }
                if(data.errCode === -1) {
                    $("#AnyOtherErr").text(data.msg);
                }
            }

        });
    }
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>