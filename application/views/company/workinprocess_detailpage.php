<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.css">
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <?php
                //$ArrProfileInfo = fnGetUserLoggedInfo(1);
                if (isset($ArrProfileInfo['uertype'])) {
                    //$ArrUt = unserialize(ARRUSERTYPE);
                    //echo $ArrUt[$ArrProfileInfo['uertype']];
                }
                ?>
                <!--QUEUE LIST DETAILS-->
                <small>
                    <!--<a href="<?php /*echo base_url('dashboard/cadloglist').'/'.@$HashedCadRequestId */?>" class="small-box-footer">
                        <?php /*if (@$ArrBasicInfo->requestlisttypeid == 1) echo 'CAD'; else echo 'SAMPLE' */?> Request Log List
                        <i class="fa fa-arrow-circle-right"></i></a>-->
                </small>
            </h1>

        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body" style="padding: 0">
                            <div class="col-md-12 pd0 no-padding">
                                <?php $this->load->view('commonBasicInfoOrderEntry'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">SHIPMENT STATUS</h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" id="wipdetails" autocomplete="off" method="post">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">P.O. No. / Enq. Ref. No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php echo $VarPoNo ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Mode of Shipment</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="automodeofshipment"
                                                   readonly value="<?php echo $ArrFourthTblSeparate[$VarPoNo]['modeofshipment'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Shipment Date</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="autoshipmentdate" readonly
                                                   value="<?php echo $ArrFourthTblSeparate[$VarPoNo]['shipementdate'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Loading Port / City</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="autoloadingportcity" readonly
                                                   value="<?php echo $ArrFourthTblSeparate[$VarPoNo]['loadingportcity']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Loading Country</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php
                                            echo $ArrFourthTblSeparate[$VarPoNo]['loadingcountry'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Destination Port / City</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="autodestinationportcity" readonly value="<?php echo $ArrFourthTblSeparate[$VarPoNo]['destiportcity'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Destination Country</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php echo $ArrFourthTblSeparate[$VarPoNo]['desticountry'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Shipment Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $ArrShipmentStatus = unserialize(ARRSHIPMENTSTATUS);
                                            ?>
                                            <select class="form-control" id="frmBasicShipmentStatus" onclick="fnShipmentStatusChange(this.value)">
                                                <?php
                                                foreach ($ArrShipmentStatus as $key => $shipmentstatus) {
                                                    ?>
                                                    <option value="<?php echo $key ?>" <?php if(!empty($Commonstatus)) if($Commonstatus == $key) echo 'selected' ?>><?php echo $shipmentstatus ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Full / Part</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmBasicFullPart">
                                                <option value="">Choose</option>
                                                <option value="1" <?php if(@$Fullpart == 1) echo 'selected' ?>>Full P.O.</option>
                                                <option value="2" <?php if(@$Fullpart == 2) echo 'selected' ?>>Part P.O.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Merchant Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea rows="4" cols="50" id="frmBasicRemarks" class="form-control"><?php if(!empty($Remarks)) echo $Remarks ?></textarea>
                                        </div>
                                    </div>
                                </div>

                        </div>
                        <?php
                        //echo '<pre>'; print_r($ArrEachPoNoData); die('');
                        $ArrSavedModeOfShipment = json_decode(@$ArrEachPoNoData->modeofshipment,true);

                        $ArrSavedShipmentdate = json_decode(@$ArrEachPoNoData->shipmentdate,true);
                        //$ArrSavedExfactorydate = json_decode(@$ArrEachPoNoData->exfactorydate,true);
                        $ArrSavedLoadingport = json_decode(@$ArrEachPoNoData->loadingport,true);
                        $ArrSavedDestiport = json_decode(@$ArrEachPoNoData->destiport,true);
                        $ArrSavedEachponostatus = json_decode(@$ArrEachPoNoData->eachponostatus,true);
                        $ArrDateupdated = json_decode(@$ArrEachPoNoData->dateupdated,true);
                        //echo '<pre>'; print_r(json_decode($ArrEachPoNoData->modeofshipment)); die;
                        ?>
                        <div class="box-body">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th>Combo</th>
                                    <th>Component</th>
                                    <th>Color</th>
                                    <th>P.O Qty. / Sample Qty.</th>
                                    <th>Pcs. / Set</th>
                                    <th>R-Mode Of Shipment</th>
                                    <th>R-Ship Date / Subn. Date</th>
                                    <th>R-Loading Port / City</th>
                                    <th>R-Destination Port / City</th>
                                    <th>Shipment Status</th>
                                </tr>
                                <?php
                                //echo '<pre>'; print_r($ArrPoNoIds); die('die');
                                $ArrPoNoIds = explode(',',$ArrPoNoIds);
                                //echo '<pre>'; print_r($ArrPoNoIds);
                                $ArrWipData = json_decode($ArrFromSecondTbl->jsondatagrid,true);
                                //echo '<pre>'; print_r($ArrWipData);
                                foreach ($ArrWipData as $poNoKeys => $detaildata) {
                                    //echo '<pre>'; print_r($poNoKeys);
                                    if(in_array($poNoKeys,$ArrPoNoIds)) {
                                    ?>
                                        <tr>
                                        <td><?php echo $detaildata[0]; ?></td>
                                        <td><?php echo $detaildata[1]; ?></td>
                                        <td><?php echo $detaildata[2]; ?></td>
                                        <td><?php echo $detaildata[4]; ?></td>
                                        <td><?php echo $detaildata[5]; ?></td>
                                        <td>
                                            <select class="form-control RModeofShipment" id="frmRBasicModeofShipment_<?php echo $poNoKeys ?>">
                                                <option value="">Choose</option>
                                                <?php
                                                $modeOfShipSel = '';
                                                foreach ($ArrModeOfShipment as $key => $item) {
                                                    if(!empty($ArrSavedModeOfShipment[$poNoKeys]))
                                                        if($ArrSavedModeOfShipment[$poNoKeys] == $key) $modeOfShipSel = 'selected'
                                                    ?>
                                                    <option value="<?php echo $key ?>" <?php echo $modeOfShipSel ?>>
                                                        <?php echo $item ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <div class='input-group date' id=''>
                                                <input type='text' class="form-control RShipMentDatePicker" id="frmRBasicShipMentDate_<?php //echo $sameponokey ?>"
                                                       value="<?php //if(!empty($ArrSavedShipmentdate[$sameponokey])) echo $ArrSavedShipmentdate[$sameponokey] ?>"/>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control RLoadingPort" id="frmRBasicLoadingPort_<?php //echo $sameponokey ?>">
                                                <option value="">Choose</option>
                                                <?php

                                                foreach ($ArrPortAndCity as $item) {
                                                    $lPortSelected = '';
                                                    if(!empty($ArrSavedLoadingport[$sameponokey]))
                                                        if($ArrSavedLoadingport[$sameponokey] == $item) $lPortSelected = 'selected';
                                                    ?>
                                                    <option value="<?php echo $item ?>" <?php $lPortSelected ?>>
                                                        <?php echo $item ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control RDestinationPort" id="frmRBasicDestinationPort_<?php //echo $sameponokey ?>">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach (@$ArrPortAndCity as $item) {
                                                    $dPortSelected = '';
                                                    if(!empty($ArrSavedDestiport[$sameponokey]))
                                                        if($ArrSavedDestiport[$sameponokey] == $item) $dPortSelected = 'selected';
                                                    ?>
                                                    <option value="<?php echo $item ?>" <?php echo $dPortSelected ?>>
                                                        <?php echo $item ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <!--<div class="listShipmentStatus">-</div>-->
                                        <div class="ssdropdown">
                                            <?php                                             $ArrShipmentStatus = unserialize(ARRSHIPMENTSTATUS);
                                            ?>
                                            <select class="form-control RShippingStatus" id="listShipmentStatusDropdown_<?php //echo $sameponokey ?>">
                                                <?php
                                                $shipStatusSelected = '';
                                                foreach ($ArrShipmentStatus as $key => $status) {
                                                    //if($ArrSavedEachponostatus[$sameponokey] == $key) $shipStatusSelected = 'selected';
                                                    ?>
                                                    <option value="<?php echo $key ?>" <?php echo $shipStatusSelected ?>><?php echo $status ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <b>Recent Update:</b><?php echo date('d-m-Y',strtotime(@$ArrEachPoNoData->dateupdated)); ?>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="reset" class="btn btn-default">Cancel</button>
                            <button type="button" id="" class="btn btn-info pull-right addrights" onclick="fnSaveWipdetail()">Save Changes</button>
                        </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>

<script type="text/javascript">
    var GlbId = "<?php echo @$VarId ?>";
    function fnShipmentStatusChange(thisvalue) {
        var statusid = thisvalue;
        var FullPart = $("#frmBasicFullPart").val();
        if(statusid == 1) {
            console.log(statusid,'statusid');
            $(".RShippingStatus").val(0);
        }
        if(statusid == 2) {
            console.log(statusid,'statusid');
            $(".RShippingStatus").val(1);
        }
        if(statusid == 3) {
            $("#frmBasicFullPart").val(1);
            $("#frmBasicFullPart").attr('disabled',true);
            $(".RModeofShipment").attr('disabled',true);
            $(".RShipMentDatePicker").attr('disabled',true);

            $(".RLoadingPort").attr('disabled',true);
            $(".RDestinationPort").attr('disabled',true);
            $(".RShippingStatus").val(statusid);
            var ShipmentStatus = $("#frmBasicShipmentStatus option:selected").text();

            $("#automodeofshipment").css('background-color','#fff');
            $("#autoshipmentdate").css('background-color','#fff');
            $("#autoloadingportcity").css('background-color','#fff');
            $("#autodestinationportcity").css('background-color','#fff');
        }
        else if(statusid == 4) {
            $(".RModeofShipment").attr('disabled',false);
            $(".RShipMentDatePicker").attr('disabled',false);

            $(".RLoadingPort").attr('disabled',false);
            $(".RDestinationPort").attr('disabled',false);
            $('.RShipMentDatePicker').datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true,
                autoclose: true
            });
            $("#frmBasicFullPart").attr('disabled',false);
            var ShipmentStatusText = $("#frmBasicShipmentStatus option:selected").text();

            $("#automodeofshipment").css('background-color','#ecf0f5');
            $("#autoshipmentdate").css('background-color','#ecf0f5');
            $("#autoloadingportcity").css('background-color','#ecf0f5');
            $("#autodestinationportcity").css('background-color','#ecf0f5');
        }
        else {
            $("#frmBasicFullPart").val(0);
            $("#frmBasicFullPart").attr('disabled',false);
            $(".RModeofShipment").attr('disabled',false);
            $(".RShipMentDatePicker").attr('disabled',false);
            $(".RLoadingPort").attr('disabled',false);
            $(".RDestinationPort").attr('disabled',false);

            $("#automodeofshipment").css('background-color','#fff');
            $("#autoshipmentdate").css('background-color','#fff');
            $("#autoloadingportcity").css('background-color','#fff');
            $("#autodestinationportcity").css('background-color','#fff');
            $(".RShippingStatus").val(0);
        }
    }
    function fnSaveWipdetail() {
        var commonShipmentStatus = $("#frmBasicShipmentStatus").val();
        var FullPart = $("#frmBasicFullPart").val();
        var Remarks = $("#frmBasicRemarks").val();
        var RModeofShipmentidsobj = {};
        $('.RModeofShipment').each(function () {
            var ids = $( this ).attr('id');
            var idval = ids.indexOf('_')+1;
            var id = ids.substr(idval);
            RModeofShipmentidsobj[id] = $(this).val();
        });
        var RShipMentDatePickeridsobj = {};
        $('.RShipMentDatePicker').each(function () {
            var ids = $( this ).attr('id');
            var idval = ids.indexOf('_')+1;
            var id = ids.substr(idval);
            RShipMentDatePickeridsobj[id] = $(this).val();
        });

        var RLoadingPortidsobj = {};
        $(".RLoadingPort").each(function () {
            var ids = $( this ).attr('id');
            var idval = ids.indexOf('_')+1;
            var id = ids.substr(idval);
            RLoadingPortidsobj[id] = $(this).val();
        });
        var RDestinationPortidsobj = {};
        $(".RDestinationPort").each(function () {
            var ids = $( this ).attr('id');
            var idval = ids.indexOf('_')+1;
            var id = ids.substr(idval);
            RDestinationPortidsobj[id] = $(this).val();
        });
        var RShippingStatusidsobj = {};
        $(".RShippingStatus").each(function () {
            var ids = $( this ).attr('id');
            var idval = ids.indexOf('_')+1;
            var id = ids.substr(idval);
            RShippingStatusidsobj[id] = $(this).val();
        });

        var Param = "rfrom=1&refid="+GlbId+"&commonss="+commonShipmentStatus+"&fp="+FullPart+"&rem="+Remarks+"&ms="+JSON.stringify(RModeofShipmentidsobj)+"&sd="+
            JSON.stringify(RShipMentDatePickeridsobj)+"&lp="+JSON.stringify(RLoadingPortidsobj)+"&dp="+
            JSON.stringify(RDestinationPortidsobj)+"&ss="+JSON.stringify(RShippingStatusidsobj);

        MakeAsynPostRequest(base_path+GlbCompanyFdr+'workinprogress/updateEachPonoStatus',Param,'json',fnSaveWipdetailRes);
    }

    function fnSaveWipdetailRes(data) {
        console.log(data,'data');
        if(data.errCode == 1) {

        }
    }

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>