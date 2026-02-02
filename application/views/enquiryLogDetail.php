<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="height:1500px;">
        <!-- Content Header (Page header) -->
        <section class="content-header" style="padding-top: 0">
            <div class="col-md-12">
                <h1 style="font-size: 20px; font-weight: 600">LOG DETAILS
                    <small style="margin-left: 50px">
                        <a class="btn btn-default btn-xs" style="font-weight: 600; padding: 7px 12px !important" href="javascript:history.back()">
                            <i class="fa fa-arrow-left" style="font-size: 14px; margin-right: 10px"></i>Back</a>
                    </small>
                </h1>
            </div>
        </section>
        <!-- Main content -->
        <section class="content" style="height:600px;">
            <div class="row">

                    <div class="col-md-12" id="divBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-bgColor">
                                <h3 class="box-title box-titleFontSize">ENQUIRY DETAILS</h3>
                            </div>
                            <div class="box-body box-bodyPd2010">
                                <form id="frmBasicEnq" name="frmBasicEnq" class="form-horizontal" method="post">
                                    <?php
/*                                    echo ($ArrEnquiryInfo->orderstatus == '1') ? '<div class="alert alert-info alert-dismissable" id="divPendingMsg"><strong>PENDING</strong></div>' : '';
                                    echo ($ArrEnquiryInfo->orderstatus == '4') ? '<div class="alert alert-info alert-dismissable" id="divPendingRRMsg"><strong>PENDING RE-REQUEST</strong></div>' : '';
                                    echo ($ArrEnquiryInfo->orderstatus == '3') ? '<div class="alert alert-danger alert-dismissable" id="divRejectMsg"><strong>ENQUIRY REJECTED</strong></div>' : '';*/
                                    ?>
                                    <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Order / Enquiry Ref. No.</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" readonly value="<?php echo $ArrEnquiryInfo->orderenqrefno ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Order / Enquiry Date</label>
                                            <div class="col-sm-8">
                                                <input type="text" readonly class="form-control" placeholder="Enter Enquiry Date"
                                                       value="<?php echo date('d-m-Y',strtotime($ArrEnquiryInfo->enquirydate)) ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Style Ref. No. / Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" readonly class="form-control" placeholder="Enter Style Ref. No."
                                                       value="<?php echo @$ArrEnquiryInfo->stylenamerefno ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Style Description</label>
                                            <div class="col-sm-8">
                                                <textarea readonly class="form-control" placeholder="Enter Style Description"><?php echo @$ArrEnquiryInfo->styledesc ?></textarea>                                                <div class="herr" id="ErrfrmBasicStyleDesc"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Enquiry Type</label>
                                            <div class="col-sm-8"><?php
                                                if(empty($ArrEnquiryType)) {
                                                    die('add enquiry type');
                                                }
                                                else {
                                                    ?>
                                                    <select class="form-control" id="frmBasicEType" disabled>
                                                        <option value="">Select Enquiry Type</option>
                                                        <?php
                                                        foreach ($ArrEnquiryType as $item) { ?>
                                                            <option value="<?php echo $item ?>" <?php echo ($item == $ArrEnquiryInfo->enquirytype ? "selected" : "") ?>>
                                                                <?php echo $item ?>
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Mode Of Enquiry</label>
                                            <div class="col-sm-8">
                                                <?php
                                                if(empty($ArrModeType)) {
                                                    die('add mode of enquiry');
                                                }
                                                else {
                                                    ?>
                                                    <select class="form-control" disabled>
                                                        <option value="">Select Mode Of Enquiry</option>
                                                        <?php
                                                        foreach ($ArrModeType as $item) {
                                                            echo '<option value="'.$item['id'].'" ';
                                                            echo ($item['id'] == $ArrEnquiryInfo->modeofenquiryid) ? 'selected' : ''; echo '>'.$item['name'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Brand</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" disabled>
                                                    <option value="">Select Brand</option>
                                                    <?php
                                                    foreach ($ArrBrand as $item) {
                                                        echo '<option value="'.$item['id'].'" ';
                                                        echo ($item['id'] == $ArrEnquiryInfo->brandId) ? 'selected' : ''; echo '>'.$item['brandname'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Buyer</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" disabled>
                                                    <option value="">Select Buyer</option>
                                                    <?php
                                                    foreach ($ArrBrand as $item) {
                                                        echo '<option value="'.$item['id'].'" ';
                                                        echo ($item['id'] == $ArrEnquiryInfo->buyerId) ? 'selected' : ''; echo '>'.$item['buyername'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Country</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" disabled>
                                                    <option value="">Select Country</option>
                                                    <?php
                                                    foreach ($ArrCountries as $key => $item) {
                                                        echo '<option value="'.$key.'" ';
                                                        echo ($key == $ArrEnquiryInfo->countryid) ? 'selected' : ''; echo '>'.$item.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Total Order Qty.</label>
                                            <div class="col-sm-3">
                                                <input readonly class="form-control" type="text" placeholder="Enquiry Date" value="<?php echo $ArrEnquiryInfo->exporderqty ?>">
                                            </div>
                                            <label class="col-sm-2 control-label" style="padding-left: 0 !important;">Pcs. / Set</label>
                                            <div class="col-sm-3" style="padding-left: 0 !important;">
                                                <?php $ArrPcsOrSet = unserialize(ARRPCSSET); ?>
                                                <select class="form-control" disabled>
                                                    <option value="">Select</option>
                                                    <?php
                                                    foreach ($ArrPcsOrSet as $key => $item) {
                                                        echo '<option value="'.$key.'" ';
                                                        echo ($key == $ArrEnquiryInfo->pcsorset) ? 'selected' : ''; echo '>'.$item.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Price Quoted For</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" disabled>
                                                    <option value="">Select</option>
                                                    <option value="1" <?php echo @$ArrEnquiryInfo->pricequotedfor == 1 ? 'selected' : '' ?>>CIF</option>
                                                    <option value="2" <?php echo @$ArrEnquiryInfo->pricequotedfor == 2 ? 'selected' : '' ?>>FOB</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Quoted Price</label>
                                            <div class="col-sm-3">
                                                <input readonly class="form-control" type="text" value="<?php echo $ArrEnquiryInfo->quotedprice ?>">
                                            </div>
                                            <label class="col-sm-2 control-label">Currency</label>
                                            <div class="col-sm-3" style="padding-left: 0 !important;">
                                                <select class="form-control" disabled>
                                                    <option value="">Select</option>
                                                    <?php
                                                    foreach ($ArrCurrency as $key => $item) {
                                                        echo '<option value="'.$key.'" ';
                                                        echo ($key == $ArrEnquiryInfo->currency) ? 'selected' : ''; echo '>'.$item.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Buyer's Price</label>
                                            <div class="col-sm-3">
                                                <input readonly class="form-control" type="text" value="<?php echo $ArrEnquiryInfo->buyerprice ?>">
                                            </div>
                                            <label class="col-sm-2 control-label">Currency</label>
                                            <div class="col-sm-3" style="padding-left: 0 !important;">
                                                <select class="form-control" disabled>
                                                    <option value="">Select Currency</option>
                                                    <?php
                                                    foreach ($ArrCurrency as $key => $item) {
                                                        echo '<option value="'.$key.'" ';
                                                        echo ($key == $ArrEnquiryInfo->currency) ? 'selected' : ''; echo '>'.$item.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Confirmed Price</label>
                                            <div class="col-sm-3">
                                                <input readonly class="form-control" type="text" value="<?php echo $ArrEnquiryInfo->confirmprice ?>">
                                            </div>
                                            <label class="col-sm-2 control-label">Currency</label>
                                            <div class="col-sm-3" style="padding-left: 0 !important;">
                                                <select class="form-control" disabled>
                                                    <option value="">Select Currency</option>
                                                    <?php
                                                    foreach ($ArrCurrency as $key => $item) {
                                                        echo '<option value="'.$key.'" ';
                                                        echo ($key == $ArrEnquiryInfo->currency) ? 'selected' : ''; echo '>'.$item.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Request Type</label>
                                            <div class="col-sm-8">
                                                <?php $ArrIsrIor = unserialize(ARRISRIOR); ?>
                                                <select class="form-control" disabled>
                                                    <option value="">Request Type</option>
                                                    <?php
                                                    foreach ($ArrIsrIor as $key => $item) {
                                                        echo '<option value="'.$key.'" ';
                                                        echo ($key == $ArrEnquiryInfo->reqforisrior) ? 'selected' : ''; echo '>'.$item.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Request Date & Time</label>
                                            <div class="col-sm-8">
                                                <input type="text" readonly class="form-control" value="<?php echo $ArrEnquiryInfo->formattedDateCreated ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Merchant Remarks</label>
                                            <div class="col-sm-8">
                                                <textarea readonly class="form-control" placeholder="Note"><?php echo @$ArrEnquiryInfo->merchantnote ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Management Remarks</label>
                                            <div class="col-sm-8">
                                                <textarea readonly style="" class="form-control"><?php echo @$ArrEnquiryInfo->comments ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Current Status</label>
                                            <div class="col-sm-8">
                                                <?php
                                                $VarClassName = '';
                                                if($ArrEnquiryInfo->order_status_value == 'PENDING-RR' || $ArrEnquiryInfo->order_status_value == 'PENDING')
                                                    $VarClassName = 'alert alert-warning';
                                                if($ArrEnquiryInfo->order_status_value == 'REJECTED') $VarClassName = 'alert alert-danger';
                                                    ?>
                                            <input type="text" class="form-control <?php echo $VarClassName ?>" readonly value="<?php echo $ArrEnquiryInfo->order_status_value ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Recent Update</label>
                                            <div class="col-sm-8">
                                                <input type="text" readonly class="form-control" value="<?php echo $ArrEnquiryInfo->formattedDateUpdated ?>">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

            </div><!-- /.row -->
        </section><!-- /.content -->
    </div>    <!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>