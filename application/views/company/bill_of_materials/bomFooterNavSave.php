<div class="box-footer pull-right" style="width: 350px; position: relative; top: -9px">
    <?php
    $ArrBomPages = ARR_BOM_PAGES;
    $VarCurrentPage = $this->uri->segment(2);
    $VarKi = array_search($VarCurrentPage,$ArrBomPages);

    $VarPrevKey = $VarKi-1;
    $VarNextKey = $VarKi+1;
    ?>
    <div class="bottomNav">
        <div class="" style="width: 90px; float: left; font-size: 18px; text-align: justify">
            <a href="<?php echo base_url('billofmaterials').'/'.$ArrBomPages[$VarPrevKey].'/'.$ArrCommonHeaderData['VarHashEnquiryId'] ?>" style="color: grey">
                <i class="fa fa-arrow-left" style="font-size: 14px"></i>
                <span style="position: relative; bottom: 0; left: 5px"><b>PREV.</b></span>
            </a>
        </div>
        <div class="pageNoBox">
            <?php echo $VarKi ?>
        </div>
        <div class="" style="width: 108px; float: left; padding-left: 0; font-size: 18px; text-align: justify">
            <a href="<?php echo base_url('billofmaterials') .'/'.$ArrBomPages[$VarNextKey].'/'.$ArrCommonHeaderData['VarHashEnquiryId'] ?>" style="color: grey">
                <span style="position: relative; bottom: 5px; top: 0"><b>NEXT</b></span>
                <i class="fa fa-arrow-right" style="font-size: 14px"></i>
            </a>

        </div>
    </div>
    <div class="saveEditBtn">
        <?php
        if($this->saveAccess) {
            if($ArrCommonHeaderData['ArrEnquiryDetails']['editaccess'] == 1) {
                ?>
                <button type="button" class="btn btn-info oeSaveEditBtn" style="" onclick="return fnSaveTable()">Save
                </button>
                <?php

            }
        }
        ?>
    </div>
</div>
