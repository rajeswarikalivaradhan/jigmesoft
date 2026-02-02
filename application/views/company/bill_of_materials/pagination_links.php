<ul class="pagination" style="margin: 5px">
    <?php
    $ArrBomPages = ARR_BOM_PAGES;
    $VarCurrentPage = $this->uri->segment(2);
    foreach ($ArrBomPages as $key => $VarPage) {
        if(!empty($VarPage)) { ?>
            <li class="<?php echo $VarPage == $VarCurrentPage ? 'active' : '' ?>">
            <a href="<?php echo base_url('billofmaterials').'/'.$VarPage.'/'.$ArrCommonHeaderData['VarHashEnquiryId'] ?>">
            <?php echo $key; ?>
            </a>
            </li>
            <?php
        }
    }
    ?>
</ul>