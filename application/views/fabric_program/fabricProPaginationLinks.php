<ul class="pagination" style="margin: 5px">
    <?php
	$ArrFabricPages = ARR_FABRIC_PROGRAM_PAGE_NO;
    $VarCurrentPage = $this->uri->segment(2);
    $VarHashId = $this->uri->segment(3);
    foreach ($ArrFabricPages as $key => $VarPage) {
        if(!empty($VarPage)) { ?>
            <li class="<?php echo $VarPage == $VarCurrentPage ? 'active' : '' ?>">
            <a href="<?php echo base_url('fabricprogram').'/'.$VarPage.'/'.$VarHashId ?>">
            <?php echo $key; ?>
            </a>
            </li>
            <?php
        }
    }
    ?>
</ul>