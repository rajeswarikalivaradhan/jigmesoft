<div class="box-footer pull-right" style="width: 330px">
    <?php
    $ArrFabPages = ARR_FABRIC_PROGRAM_PAGE_NO;
    $VarCurrentPage = $this->uri->segment(2);
    if($VarKi = array_search($VarCurrentPage,$ArrFabPages)) {
    $VarPrevKey = $VarKi - 1;
    $VarNextKey = $VarKi + 1;
    ?>
    <div class="bottomNav">
        <div class="" style="width: 90px; float: left; font-size: 18px; text-align: justify">
            <a href="<?php echo !empty($ArrFabPages[$VarPrevKey]) ? base_url('fabricprogram') . '/' . $ArrFabPages[$VarPrevKey] . '/' . $VarHashEnquiryId : 'javascript:void(0)' ?>"
               style="color:<?php echo empty($ArrFabPages[$VarPrevKey]) ? "#bdbdbd" : "grey" ?>">
                <i class="fa fa-arrow-left" style="font-size: 14px"></i>
                <span style=""><b>PREV.</b></span>
            </a>
        </div>
        <div class="pageNoBox">
            <?php echo $VarKi ?>
        </div>
        <div class="" style="width: 90px; float: left; padding-left: 0; font-size: 18px; text-align: justify">
            <a href="<?php echo !empty($ArrFabPages[$VarNextKey]) ? base_url('fabricprogram') . '/' .
                $ArrFabPages[$VarNextKey] . '/' . $VarHashEnquiryId : 'javascript:void(0)' ?>"
               style="color:<?php echo empty($ArrFabPages[$VarNextKey]) ? "#bdbdbd" : "grey" ?>">
                <span style=""><b>NEXT</b></span>
                <i class="fa fa-arrow-right" style="font-size: 14px"></i>
            </a>
        </div>
        <?php
        if ($this->uri->segment(2) != "fabConCalc") {
            if ($this->uri->segment(2) != "fabRequirement") {
                if ($this->uri->segment(2) != "thirteen") {
                    if ($this->saveAccess) {
                        ?>
                        <button type="button" class="btn btn-info" onclick="return cmnSaveChanges()">Save</button>
                        <?php
                    }
                }
            }
        }
        ?>
        </div>
        <?php
    }
    ?>
</div>
<div class="col-md-12" style="padding: 5px !important;">
    <div class="alert alert-success alert-dismissible hide" id="divCmnSuccessMsg"></div>
</div>