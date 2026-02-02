<ul class="pagination" style="margin: 5px">
    <?php
	$ArrOrderEntryPages = unserialize(ARRORDERENTRYPAGES);
    $VarCurrentPage = $this->uri->segment(2);
    foreach ($ArrOrderEntryPages as $key => $VarPage) {
        if(!empty($VarPage)) { ?>
            <li class="<?php echo $VarPage == $VarCurrentPage ? 'active' : '' ?>">
            <a href="<?php echo base_url('orderentryvtwo').'/'.$VarPage.'/'.$ArrCommonHeaderData['VarHashEnquiryId'] ?>">
            <?php echo $key; ?>
            </a>
            </li>
            <?php
        }
    }
    ?>
    <div class="" style="display: inline; margin: 0 100px">
        <?php
        if(!empty($ArrCommonHeaderData['ArrEnquiryDetails'])) {
            if($ArrCommonHeaderData['ArrEnquiryDetails']['completestatus'] == 1) {
                ?>
                <a href="javascript:void(0)" class="" style="margin: 0 10px" onclick="return mgmtpwdprompt()">Edit</a>
                <?php
                /*To confirm all changes has been completed. Now make all order entry as readonly access. (Disable save)
        This is not need in the last page of order entry Here Document and logistics page
        */
                if($VarCurrentPage != 'docandlogisticstwentytwo') { ?>
                    <a href="javascript:void(0)" class="btn btn-info" style="margin: 0 10px" onclick="return fnOrderEntrySaveAll(<?php echo $VarEnquiryId ?>)">Save All</a>
                <?php }
            }
        }
        ?>
    </div>
</ul>
<div class="modal fade" id="orderEntryEditAccessPwdModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="col-md-12">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="">Enter E-mail & Password</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="frmMgmt" role="form" autocomplete="off">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"> E-mail </label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="frmEmail" placeholder="Enter E-mail">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"> Password </label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" id="frmPwd" placeholder="Enter password">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="herr" id="ErrCredentials"></div>
                <button type="button" class="btn btn-primary" onclick="return fnMgmtPinforOrderEntryEditAccess(<?php echo $VarEnquiryId ?>)">Continue
                </button>
                <div class="herr pull-left" id="ErrfrmPin"></div>
            </div>
        </div>
        </div>
    </div>
</div>

<script>

    function fnMgmtPinforOrderEntryEditAccess(oid) {
        var frmEmail = $("#frmEmail").val();
        var pwd = $("#frmPwd").val();
        MakePostRequest(base_path+'orderentryvtwo/MgmtPinforOrderEntryEditAccess',"rfrom=1&frmEmail="+frmEmail+"&pwd="+pwd+"&oid="+oid,"json",ResultFn);
        return false;
    }

    function ResultFn(data) {
        console.log(data,'data');
        //$('#myModal').modal('hide');
        if(data.errcode === 1) {
            var redirecturl = window.location.href;
            fnRedirectPageTimeOut(redirecturl);
        }
        else if(data.errcode === -1) {
            $("#ErrCredentials").text(data.msg);
        }
    }

    function fnOrderEntrySaveAll(oid) {
        if (confirm("To confirm click OK, else CANCEL")) {
            MakePostRequest(base_path+'orderentryvtwo/saveAllOrderEntry',"rfrom=1&oid="+oid,"json",ResultSaveAllFn);
        }
    }

    function ResultSaveAllFn(data) {
        if(data.errcode === 1) {
            var redirecturl = window.location.href;
            fnRedirectPageTimeOut(redirecturl);
        }
        else {
            alert('Err');
        }
    }
</script>