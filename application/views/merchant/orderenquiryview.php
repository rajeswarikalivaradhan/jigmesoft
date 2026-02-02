<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>MERCHANT - ENQUIRY DETAIL</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() . CNFCOMPANY ?>dashboard"><i class="fa fa-dashboard"></i> Home</a>
                </li>
                <li><a href="<?php echo base_url() ?>mfabrictype/managefabrictypes/">Management enquiry
                        authorization</a></li>
                <li class="active">Enquiry</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-5">
                    <a href="<?php echo base_url('merchant/manageWip') ?>">Go Back</a>
                    <div class="box box-solid">
                        <div class="box-body" style="background-color: #cce0ff">
                            <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                <a href="javascript:void(0);" style="color: #000" onclick="fnShowEnqInfo();"><i
                                            id="basicInfoCircle" class="fa fa-circle"></i> Basic Information</a></div>
                            <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                <a href="javascript:void(0);" style="color: #000"
                                   onclick="fnShowEnqLog('divLog','divBasicInfo');"><i id="logcircle"
                                                                                       class="fa fa-circle-o"></i> Logs
                                    List</a></div>
                        </div><!-- /.box-body -->
                    </div><!-- /. box -->
                </div>
                <div class="col-md-12" id="divBasicInfo">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Enquiry Authorization</h3>
                        </div>
                        <!-- form start -->
                        <div class="box-body">
                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Order / Enquiry Ref. No</label>
                                        <div class="col-sm-8"><span
                                                    class="form-control"><?php echo @$ObjEnquiry->orderenqrefno ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Order / Enquiry Date</label>
                                        <div class="col-sm-8">
                                            <span class="form-control"><?php if (!empty($ObjEnquiry->enquirydate)) echo date('d-m-Y', strtotime($ObjEnquiry->enquirydate)) ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Style Ref. No. / Name</label>
                                        <div class="col-sm-8"><span
                                                    class="form-control"><?php echo @$ObjEnquiry->stylenamerefno ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Style Description</label>
                                        <div class="col-sm-8">
                                            <textarea readonly style="height: 69px; background-color: #fff"
                                                      class="form-control"
                                                      placeholder="Style Description"><?php echo @$ObjEnquiry->styledesc ?></textarea>
                                            <div class="herr" id="ErrfrmBasicStyleDesc"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Enquiry Type</label>
                                        <div class="col-sm-8"><span
                                                    class="form-control"><?php echo $ObjEnquiry->enq ?> </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Mode Of Enquiry</label>
                                        <div class="col-sm-8"><span
                                                    class="form-control"><?php
                                                echo $ObjEnquiry->modeofenquiry ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Brand / Buyer</label>
                                        <div class="col-sm-8"><span
                                                    class="form-control"><?php echo $ObjEnquiry->brandname . ' / ' . $ObjBuyer->buyername ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Country</label>
                                        <div class="col-sm-8"><span
                                                    class="form-control"><?php echo $VarCountry ?></span></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Total Order Qty.</label>
                                        <div class="col-sm-4"><span
                                                    class="form-control"><?php echo $ObjEnquiry->exporderqty ?></span>
                                        </div>
                                        <label class="col-sm-2 control-label" style="padding-left: 0 !important;">Pcs. / Set</label>
                                        <div class="col-sm-2"><span class="form-control"><?php echo $VarPcsorSet ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Price Quoted For</label>
                                        <div class="col-sm-8">
                                            <span class="form-control"><?php
                                                if(!empty($ObjEnquiry->pricequotedfor)) {
                                                    echo $ObjEnquiry->pricequotedfor == 1 ? 'CIF' : 'FOB';
                                                }
                                                else echo '-';
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Quoted Price</label>
                                        <div class="col-sm-4"><span
                                                    class="form-control"><?php echo $ObjEnquiry->quotedprice ?></span>
                                        </div>
                                        <label class="col-sm-2 control-label">Currency</label>
                                        <div class="col-sm-2"><span
                                                    class="form-control"><?php echo $VarCurrency ?></span></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Buyer's Price</label>
                                        <div class="col-sm-4"><span
                                                    class="form-control"><?php echo $ObjEnquiry->buyerprice ?></span>
                                        </div>
                                        <label class="col-sm-2 control-label">Currency</label>
                                        <div class="col-sm-2"><span
                                                    class="form-control"><?php echo $VarCurrency ?></span></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Confirmed Price</label>
                                        <div class="col-sm-4"><span
                                                    class="form-control"><?php echo $ObjEnquiry->confirmprice ?></span>
                                        </div>
                                        <label class="col-sm-2 control-label">Currency</label>
                                        <div class="col-sm-2"><span
                                                    class="form-control"><?php echo $VarCurrency ?></span></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Merchant Name</label>
                                        <div class="col-sm-8"><span
                                                    class="form-control"><?php echo $VarMerUser->contactname ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Request Type</label>
                                        <div class="col-sm-8"><span class="form-control"><?php echo $VarIsrIor ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Request Date & Time</label>
                                        <div class="col-sm-8"><span
                                                    class="form-control"><?php echo date('d-m-Y / H:i:s', strtotime($ObjEnquiry->daterequested)); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Merchant remarks</label>
                                        <div class="col-sm-8">
                                            <textarea readonly class="form-control"
                                                      style="height: 100px; background-color: #fff"><?php echo $ObjEnquiry->merchantnote ?></textarea>
                                        </div>
                                        <!--<div class="col-sm-8"><span class="form-control"></span></div>-->
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Enquiry Authorization</label>
                                        <div class="col-sm-8"><?php
                                            $ArrAppRejStatus = unserialize(ORDERENQUIRYSTATUS);
                                            unset($ArrAppRejStatus[1]);
                                            unset($ArrAppRejStatus[4]);
                                            //$VarSty = 'style="background-color: green"';
                                            ?>
                                            <select class="form-control" id="frmBasicOrderStatus"
                                                    name="frmBasicOrderStatus">
                                                <option value="">Select Status</option>
                                                <?php
                                                foreach ($ArrAppRejStatus as $key => $item) {
                                                    echo '<option value="' . $key . '" ';
                                                    echo ($key == $ObjEnquiry->orderstatus) ? 'selected' : '';
                                                    echo '>' . $item . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmBasicPs"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Authorization Date & Time</label>
                                        <div class="col-sm-8">
                                            <span class="form-control hide" id="frmReqDTCs"></span>
                                            <span class="form-control" id="frmReqDT"><?php
                                                echo $ObjEnquiry->dateauthorized == '' ? '-' : date('d-m-Y H:i:s', strtotime($ObjEnquiry->dateauthorized)) ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Management Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea style="height: 100px" id="frmBasicComments"
                                                      name="frmBasicComments"
                                                      class="form-control"><?php echo @$ObjEnquiry->comments ?></textarea>
                                            <div class="herr" id="ErrfrmBasicComments"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Current Status</label>

                                            <div class="col-sm-8 alert alert-dismissable hide" id="divNewStatus"></div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control alert <?php if($ObjEnquiry->orderstatus == '4' || $ObjEnquiry->orderstatus == '1')
                                                echo 'alert-warning'; if($ObjEnquiry->orderstatus == '3') echo 'alert-danger';
                                                if($ObjEnquiry->orderstatus == '2') echo 'alert-success'; ?>"
                                                   readonly value="<?php echo $ArrOrderStatus[$ObjEnquiry->orderstatus] ?>">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div><!-- /.box-body -->
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-md-12" style="padding-top: 20px">
                                    <label class="control-label">ATTACHMENTS</label>
                                </div>
                                <div class="col-sm-5"
                                     style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                    <ul style="list-style: none;">
                                        <?php
                                        $VarFdr = FCPATH . "uploads/orderenquiry/" . $VarEnquiryId;
                                        if (file_exists($VarFdr)) {
                                            if ($dh = opendir($VarFdr)) {
                                                while (($file = readdir($dh)) !== false) {
                                                    if ($file != "." && $file != "..") {
                                                        ?>
                                                        <li>
                                                            <div style="padding: 10px 0;">
                                                                <?php echo $file . ' '; ?>&nbsp;<a
                                                                        href="<?php echo base_url() . CNFCOMPANY . "menquiry/download?enqid=" . $VarEnquiryId . "&filename=" . $file ?>">
                                                                    <i class="fa fa-download fa-lg"
                                                                       aria-hidden="true"></i>
                                                                </a>&nbsp;&nbsp;<a
                                                                        href="<?php echo base_url() . "uploads/orderenquiry/" . $VarEnquiryId . "/" . $file ?>"
                                                                        target="_blank">
                                                                    <i class="fa fa-file fa-lg" aria-hidden="true"></i>
                                                                </a>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                }
                                                closedir($dh);
                                            }
                                            ?>
                                            <?php
                                        } else {
                                            echo 'No attachments';
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6 hide" id="anodiv">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-8 control-label">Assigned No: </label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" style="text-align: center; font-size: 18px"
                                                  id="eno"></textarea>
                                        <!--<strong><div id="eno"></div></strong>-->
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <?php
                                //echo '<pre>'; print_r($ObjEnquiry); die('');
                                if ($ObjEnquiry->orderstatus == "1" || $ObjEnquiry->orderstatus == "4") {
                                    ?>
                                    <button type="submit" class="btn btn-info pull-right addrights"
                                            id="saveapprenqbutton" onclick="return fnSaveEnquiryApproval();">Save
                                        Changes
                                    </button>
                                    <?php
                                }
                                ?>
                                <div id="ErrfrmBasicErr" class="herr pull-right"></div>
                            </div>
                        </div>
                    </div><!-- /.box -->
                </div>
                <div class="col-md-12" id="divLog">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Logs List</h3>
                        </div>
                        <div class="box-body">
                            <table id="tableLogList" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="sortable asc" id="1">Sent Date<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="2">Approval Status<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Comments<i class="fa fa-fw fa-sort"></i></th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                            <div>
                                <section id="pagination_my" class="animated for_animate pdl15 ">
                                    <ul class="pagination m-b-none animated for_animate" id="ResPagination"></ul>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" id="logdetaildiv">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Log Detail</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">Style Ref. No. /
                                            Name</label>
                                        <div class="col-sm-4">
                                            <span class="form-control" id="lgstyleref"></span>
                                        </div>
                                        <label for="inputEmail3" class="col-sm-2 control-label">Enquiry Date</label>
                                        <div class="col-sm-4"><span class="form-control" id="lgenqdt"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">Style
                                            Description</label>
                                        <div class="col-sm-10"><span class="form-control" id="lgstyledesc"
                                                                     style="height: 57px"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">Enquiry Type</label>
                                        <div class="col-sm-4"><span class="form-control" id="lgenqtype"></span>
                                        </div>
                                        <label for="inputEmail3" class="col-sm-2 control-label">Mode Of Enquiry</label>
                                        <div class="col-sm-4"><span class="form-control" id="lgme"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">Brand / Buyer</label>
                                        <div class="col-sm-4"><span class="form-control" id="lgbb"></span>
                                        </div>
                                        <label for="enqdate" class="col-sm-2 control-label">Country</label>
                                        <div class="col-sm-4"><span class="form-control" id="lgconty"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-2 control-label">Provisional Qty.</label>
                                        <div class="col-sm-4"><span class="form-control" id="lgpqty"></span>
                                        </div>
                                        <label for="enqdate" class="col-sm-2 control-label">Pcs. / Set</label>
                                        <div class="col-sm-4"><span class="form-control" id="lgps"></span></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-2 control-label">Quoted Price</label>
                                        <div class="col-sm-4"><span class="form-control" id="lgqp"></span></div>
                                        <label for="enqdate" class="col-sm-2 control-label">Buyer's Price</label>
                                        <div class="col-sm-4"><span class="form-control" id="lgbp"></span></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-2 control-label">Confirm Price</label>
                                        <div class="col-sm-4"><span class="form-control" id="lgcp"></span></div>
                                        <label for="enqdate" class="col-sm-2 control-label">Currency</label>
                                        <div class="col-sm-4"><span class="form-control" id="lgcurrency"></span></div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-3 control-label">Merchant Name</label>
                                        <div class="col-sm-8"><span class="form-control" id="lgmerc"></span></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-3 control-label">Request Type</label>
                                        <div class="col-sm-3"><span class="form-control" id="lgisrior"></span></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-3 control-label">Request Date & Time</label>
                                        <div class="col-sm-4"><span class="form-control" id="lgreqdt"></span></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Merchant Remarks</label>
                                        <div class="col-sm-8"><span class="form-control" id="lgmnote"
                                                                    style="height: 100px"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Management
                                            Comments</label>
                                        <div class="col-sm-8"><span class="form-control" id="lgmcomm"
                                                                    style="height: 100px"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-3 control-label">Current Status</label>
                                        <strong>
                                            <div class="col-sm-3" id="divLogDetailRejectCs"></div>
                                        </strong>
                                        <strong>
                                            <div class="col-sm-3" id="divLogDetailPendingCs"></div>
                                        </strong>
                                        <strong>
                                            <div class="col-sm-3" id="divLogDetailPendingRRCs"></div>
                                        </strong>
                                        <strong>
                                            <div class="col-sm-3" id="divLogDetailApprovedCs"></div>
                                        </strong>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Recent Update</label>
                                        <div class="col-sm-4"><span class="form-control" id="lgmgmtreupdate"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="box-body">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label">View Attached Documents</label>
                                    </div>
                                    <label class="col-sm-12 control-label"> <a
                                                href="//docs.google.com/gview?url=http://www.picssel.com/demos/downloads/Fancybox.doc&embedded=true"
                                                target="_blank" class="word">Document1.doc</a> </label>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12" style="padding-top: 20px">
                                        <label class="control-label">Download Attachments: </label>
                                    </div>
                                    <div class="col-sm-5"
                                         style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                        <ul style="list-style: none;" id="downloads">
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6 hide" id="anodiv">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-8 control-label">Assigned No: </label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" style="text-align: center; font-size: 18px"
                                                      id="eno"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Enter PIN</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal col-md-3" method="post" id="frmPinformId">
                                <div id="divOuter">
                                    <div id="divInner">
                                        <input id="frmPin" type="password" maxlength="4"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" onclick="return fnCheckPin()">Continue
                            </button>
                            <div class="herr pull-left" id="ErrfrmPin"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/ajax.js"></script>
<script>
    var GlbEnquiryId = '<?php echo @$VarEnquiryId; ?>';
    var GlbIsrIor = '<?php echo @$VarIsrIorId ?>';
</script>
<script src="<?php echo base_url(); ?>assets/js/<?php echo CNFCOMPANY ?>orderenquiryview.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>